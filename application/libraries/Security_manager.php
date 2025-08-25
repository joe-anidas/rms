<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Manager Library
 * Comprehensive security implementation for SQL injection prevention, XSS protection, 
 * CSRF protection, and input sanitization
 * Requirements: 7.1, 7.4
 */
class Security_manager {
    
    protected $CI;
    protected $csrf_token_name = 'csrf_token';
    protected $csrf_cookie_name = 'csrf_cookie';
    protected $csrf_expire = 7200; // 2 hours
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->helper('security');
        
        // Initialize CSRF protection
        $this->init_csrf_protection();
    }
    
    /**
     * Initialize CSRF protection
     */
    protected function init_csrf_protection() {
        // Generate CSRF token if not exists
        if (!$this->CI->session->userdata($this->csrf_token_name)) {
            $this->regenerate_csrf_token();
        }
        
        // Set CSRF cookie
        $this->set_csrf_cookie();
    }
    
    /**
     * Generate new CSRF token
     * @return string Generated token
     */
    public function regenerate_csrf_token() {
        $token = bin2hex(random_bytes(32));
        $this->CI->session->set_userdata($this->csrf_token_name, $token);
        $this->set_csrf_cookie();
        return $token;
    }
    
    /**
     * Set CSRF cookie
     */
    protected function set_csrf_cookie() {
        $token = $this->CI->session->userdata($this->csrf_token_name);
        if ($token) {
            setcookie(
                $this->csrf_cookie_name,
                $token,
                time() + $this->csrf_expire,
                '/',
                '',
                false, // Set to true in production with HTTPS
                true   // HttpOnly flag
            );
        }
    }
    
    /**
     * Get current CSRF token
     * @return string Current CSRF token
     */
    public function get_csrf_token() {
        return $this->CI->session->userdata($this->csrf_token_name);
    }
    
    /**
     * Validate CSRF token
     * @param string $token Token to validate
     * @return bool Validation result
     */
    public function validate_csrf_token($token = null) {
        if ($token === null) {
            $token = $this->CI->input->post($this->csrf_token_name);
        }
        
        $session_token = $this->CI->session->userdata($this->csrf_token_name);
        
        if (!$token || !$session_token) {
            return false;
        }
        
        return hash_equals($session_token, $token);
    }
    
    /**
     * Generate CSRF hidden input field
     * @return string HTML input field
     */
    public function csrf_field() {
        $token = $this->get_csrf_token();
        return '<input type="hidden" name="' . $this->csrf_token_name . '" value="' . $token . '">';
    }
    
    /**
     * Sanitize input data to prevent XSS
     * @param mixed $data Input data
     * @param string $type Data type for specific sanitization
     * @return mixed Sanitized data
     */
    public function sanitize_input($data, $type = 'string') {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize_input($value, $type);
            }
            return $data;
        }
        
        if (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->$key = $this->sanitize_input($value, $type);
            }
            return $data;
        }
        
        // Convert to string if not already
        $data = (string) $data;
        
        switch ($type) {
            case 'email':
                return filter_var(trim($data), FILTER_SANITIZE_EMAIL);
            
            case 'phone':
                return preg_replace('/[^0-9+\-\s()]/', '', trim($data));
            
            case 'numeric':
                return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            
            case 'integer':
                return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            
            case 'url':
                return filter_var(trim($data), FILTER_SANITIZE_URL);
            
            case 'filename':
                // Remove dangerous characters from filenames
                $data = preg_replace('/[^a-zA-Z0-9._-]/', '', $data);
                return trim($data, '.');
            
            case 'html':
                // Allow specific HTML tags but sanitize
                return strip_tags($data, '<p><br><strong><em><ul><ol><li>');
            
            case 'sql_identifier':
                // For table/column names - only alphanumeric and underscore
                return preg_replace('/[^a-zA-Z0-9_]/', '', $data);
            
            case 'string':
            default:
                // Remove null bytes and control characters
                $data = str_replace(chr(0), '', $data);
                $data = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $data);
                
                // HTML encode to prevent XSS
                return htmlspecialchars(trim($data), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }
    
    /**
     * Sanitize output data to prevent XSS
     * @param mixed $data Output data
     * @param string $context Output context (html, attribute, js, css, url)
     * @return mixed Sanitized data
     */
    public function sanitize_output($data, $context = 'html') {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize_output($value, $context);
            }
            return $data;
        }
        
        if (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->$key = $this->sanitize_output($value, $context);
            }
            return $data;
        }
        
        $data = (string) $data;
        
        switch ($context) {
            case 'html':
                return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            
            case 'attribute':
                return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            
            case 'js':
                return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            
            case 'css':
                return preg_replace('/[^a-zA-Z0-9\-_#.]/', '', $data);
            
            case 'url':
                return urlencode($data);
            
            case 'raw':
                return $data; // Use with extreme caution
            
            default:
                return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }
    
    /**
     * Prepare SQL statement with parameter binding to prevent SQL injection
     * @param string $sql SQL query with placeholders
     * @param array $params Parameters to bind
     * @return object Query result
     */
    public function execute_prepared_query($sql, $params = array()) {
        try {
            // Validate SQL query structure
            if (!$this->validate_sql_query($sql)) {
                throw new Exception('Invalid SQL query structure');
            }
            
            // Use CodeIgniter's query binding
            return $this->CI->db->query($sql, $params);
            
        } catch (Exception $e) {
            error_log('SQL Execution Error: ' . $e->getMessage());
            error_log('SQL Query: ' . $sql);
            error_log('Parameters: ' . print_r($params, true));
            throw $e;
        }
    }
    
    /**
     * Validate SQL query structure for basic security
     * @param string $sql SQL query
     * @return bool Validation result
     */
    protected function validate_sql_query($sql) {
        // Remove comments and normalize whitespace
        $sql = preg_replace('/\/\*.*?\*\//', '', $sql);
        $sql = preg_replace('/--.*$/', '', $sql);
        $sql = preg_replace('/\s+/', ' ', trim($sql));
        
        // Check for dangerous patterns
        $dangerous_patterns = array(
            '/\b(DROP|ALTER|CREATE|TRUNCATE|DELETE)\s+/i',
            '/\bUNION\s+SELECT\b/i',
            '/\bINTO\s+OUTFILE\b/i',
            '/\bLOAD_FILE\s*\(/i',
            '/\bINTO\s+DUMPFILE\b/i',
            '/\bSLEEP\s*\(/i',
            '/\bBENCHMARK\s*\(/i'
        );
        
        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $sql)) {
                error_log('Dangerous SQL pattern detected: ' . $pattern . ' in query: ' . $sql);
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Secure database insert with prepared statements
     * @param string $table Table name
     * @param array $data Data to insert
     * @return bool|int Insert result or insert ID
     */
    public function secure_insert($table, $data) {
        try {
            // Sanitize table name
            $table = $this->sanitize_input($table, 'sql_identifier');
            
            // Validate table exists
            if (!$this->CI->db->table_exists($table)) {
                throw new Exception('Table does not exist: ' . $table);
            }
            
            // Sanitize data
            $sanitized_data = array();
            foreach ($data as $key => $value) {
                $clean_key = $this->sanitize_input($key, 'sql_identifier');
                $sanitized_data[$clean_key] = $this->sanitize_input($value);
            }
            
            // Use CodeIgniter's built-in insert with automatic escaping
            $result = $this->CI->db->insert($table, $sanitized_data);
            
            if ($result) {
                return $this->CI->db->insert_id();
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log('Secure insert error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Secure database update with prepared statements
     * @param string $table Table name
     * @param array $data Data to update
     * @param array $where Where conditions
     * @return bool Update result
     */
    public function secure_update($table, $data, $where) {
        try {
            // Sanitize table name
            $table = $this->sanitize_input($table, 'sql_identifier');
            
            // Validate table exists
            if (!$this->CI->db->table_exists($table)) {
                throw new Exception('Table does not exist: ' . $table);
            }
            
            // Sanitize data
            $sanitized_data = array();
            foreach ($data as $key => $value) {
                $clean_key = $this->sanitize_input($key, 'sql_identifier');
                $sanitized_data[$clean_key] = $this->sanitize_input($value);
            }
            
            // Sanitize where conditions
            foreach ($where as $key => $value) {
                $clean_key = $this->sanitize_input($key, 'sql_identifier');
                $this->CI->db->where($clean_key, $this->sanitize_input($value));
            }
            
            // Use CodeIgniter's built-in update with automatic escaping
            return $this->CI->db->update($table, $sanitized_data);
            
        } catch (Exception $e) {
            error_log('Secure update error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Validate file upload security
     * @param array $file File data from $_FILES
     * @param array $config Upload configuration
     * @return array Validation result
     */
    public function validate_file_security($file, $config = array()) {
        $errors = array();
        
        // Default security configuration
        $default_config = array(
            'allowed_types' => array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'),
            'max_size' => 5242880, // 5MB in bytes
            'scan_content' => true,
            'check_mime' => true
        );
        
        $config = array_merge($default_config, $config);
        
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $errors[] = 'No file was uploaded';
            return array('is_secure' => false, 'errors' => $errors);
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload error: ' . $this->get_upload_error_message($file['error']);
            return array('is_secure' => false, 'errors' => $errors);
        }
        
        // Check file size
        if ($file['size'] > $config['max_size']) {
            $errors[] = 'File size exceeds maximum allowed size';
        }
        
        // Check file extension
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_ext, $config['allowed_types'])) {
            $errors[] = 'File type not allowed';
        }
        
        // Check for malicious filename
        if ($this->has_malicious_filename($file['name'])) {
            $errors[] = 'Filename contains dangerous characters';
        }
        
        // Check MIME type if enabled
        if ($config['check_mime'] && function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!$this->is_allowed_mime_type($mime_type, $file_ext)) {
                $errors[] = 'File content does not match extension';
            }
        }
        
        // Scan file content if enabled
        if ($config['scan_content']) {
            $content_scan = $this->scan_file_content($file['tmp_name']);
            if (!$content_scan['is_safe']) {
                $errors = array_merge($errors, $content_scan['errors']);
            }
        }
        
        return array(
            'is_secure' => empty($errors),
            'errors' => $errors
        );
    }
    
    /**
     * Check for malicious filename patterns
     * @param string $filename Filename to check
     * @return bool Has malicious patterns
     */
    protected function has_malicious_filename($filename) {
        // Check for null bytes
        if (strpos($filename, "\0") !== false) {
            return true;
        }
        
        // Check for path traversal
        if (strpos($filename, '../') !== false || strpos($filename, '..\\') !== false) {
            return true;
        }
        
        // Check for dangerous extensions
        $dangerous_extensions = array(
            'php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'cmd', 
            'com', 'scr', 'vbs', 'js', 'jar', 'sh', 'pl', 'py'
        );
        
        foreach ($dangerous_extensions as $ext) {
            if (stripos($filename, '.' . $ext) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if MIME type is allowed for file extension
     * @param string $mime_type Detected MIME type
     * @param string $file_ext File extension
     * @return bool Is allowed
     */
    protected function is_allowed_mime_type($mime_type, $file_ext) {
        $allowed_mimes = array(
            'pdf' => array('application/pdf'),
            'doc' => array('application/msword'),
            'docx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
            'jpg' => array('image/jpeg'),
            'jpeg' => array('image/jpeg'),
            'png' => array('image/png'),
            'gif' => array('image/gif')
        );
        
        if (!isset($allowed_mimes[$file_ext])) {
            return false;
        }
        
        return in_array($mime_type, $allowed_mimes[$file_ext]);
    }
    
    /**
     * Scan file content for malicious patterns
     * @param string $file_path Path to file
     * @return array Scan result
     */
    protected function scan_file_content($file_path) {
        $errors = array();
        
        try {
            // Read first 8KB of file
            $handle = fopen($file_path, 'rb');
            if (!$handle) {
                $errors[] = 'Cannot read file for security scan';
                return array('is_safe' => false, 'errors' => $errors);
            }
            
            $content = fread($handle, 8192);
            fclose($handle);
            
            // Check for executable signatures
            $dangerous_signatures = array(
                'MZ',           // Windows executable
                '#!/',          // Unix script
                '<?php',        // PHP script
                '<script',      // JavaScript
                'javascript:',  // JavaScript protocol
                'vbscript:',    // VBScript protocol
                'data:',        // Data URI (can be dangerous)
                '%PDF-1.',      // PDF (check for embedded JS)
            );
            
            foreach ($dangerous_signatures as $signature) {
                if (strpos($content, $signature) !== false) {
                    // Special handling for PDF - check for JavaScript
                    if ($signature === '%PDF-1.' && strpos($content, '/JS') !== false) {
                        $errors[] = 'PDF contains JavaScript';
                    } elseif ($signature !== '%PDF-1.') {
                        $errors[] = 'File contains potentially dangerous content: ' . $signature;
                    }
                }
            }
            
            // Check for suspicious patterns
            $suspicious_patterns = array(
                '/eval\s*\(/i',
                '/exec\s*\(/i',
                '/system\s*\(/i',
                '/shell_exec\s*\(/i',
                '/passthru\s*\(/i',
                '/base64_decode\s*\(/i',
                '/file_get_contents\s*\(/i',
                '/curl_exec\s*\(/i'
            );
            
            foreach ($suspicious_patterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    $errors[] = 'File contains suspicious code patterns';
                    break;
                }
            }
            
        } catch (Exception $e) {
            $errors[] = 'Error scanning file content: ' . $e->getMessage();
        }
        
        return array(
            'is_safe' => empty($errors),
            'errors' => $errors
        );
    }
    
    /**
     * Get upload error message
     * @param int $error_code Upload error code
     * @return string Error message
     */
    protected function get_upload_error_message($error_code) {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'File is too large';
            case UPLOAD_ERR_PARTIAL:
                return 'File was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }
    
    /**
     * Log security events
     * @param string $event_type Type of security event
     * @param string $message Event message
     * @param array $context Additional context
     */
    public function log_security_event($event_type, $message, $context = array()) {
        $log_data = array(
            'timestamp' => date('Y-m-d H:i:s'),
            'event_type' => $event_type,
            'message' => $message,
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'context' => $context
        );
        
        // Log to file
        error_log('SECURITY EVENT: ' . json_encode($log_data));
        
        // Log to database if available
        if ($this->CI->db->table_exists('security_logs')) {
            try {
                $this->CI->db->insert('security_logs', $log_data);
            } catch (Exception $e) {
                error_log('Failed to log security event to database: ' . $e->getMessage());
            }
        }
    }
    
    /**
     * Rate limiting check
     * @param string $identifier Unique identifier (IP, user ID, etc.)
     * @param int $max_attempts Maximum attempts allowed
     * @param int $time_window Time window in seconds
     * @return bool Is within rate limit
     */
    public function check_rate_limit($identifier, $max_attempts = 10, $time_window = 300) {
        $cache_key = 'rate_limit_' . md5($identifier);
        
        // Get current attempts from session or implement with cache/database
        $attempts = $this->CI->session->userdata($cache_key) ?: array();
        $current_time = time();
        
        // Remove old attempts outside time window
        $attempts = array_filter($attempts, function($timestamp) use ($current_time, $time_window) {
            return ($current_time - $timestamp) < $time_window;
        });
        
        // Check if limit exceeded
        if (count($attempts) >= $max_attempts) {
            $this->log_security_event('rate_limit_exceeded', 'Rate limit exceeded for: ' . $identifier, array(
                'attempts' => count($attempts),
                'max_attempts' => $max_attempts,
                'time_window' => $time_window
            ));
            return false;
        }
        
        // Add current attempt
        $attempts[] = $current_time;
        $this->CI->session->set_userdata($cache_key, $attempts);
        
        return true;
    }
}