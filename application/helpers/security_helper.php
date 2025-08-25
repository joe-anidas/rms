<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Helper Functions
 * Provides utility functions for security operations
 * Requirements: 7.1, 7.4
 */

if (!function_exists('sanitize_output')) {
    /**
     * Sanitize output data for safe display
     * @param mixed $data Data to sanitize
     * @param string $context Output context (html, attribute, js, css, url)
     * @return mixed Sanitized data
     */
    function sanitize_output($data, $context = 'html') {
        $CI =& get_instance();
        $CI->load->library('security_manager');
        return $CI->security_manager->sanitize_output($data, $context);
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get CSRF token
     * @return string CSRF token
     */
    function csrf_token() {
        $CI =& get_instance();
        $CI->load->library('security_manager');
        return $CI->security_manager->get_csrf_token();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate CSRF hidden input field
     * @return string HTML input field
     */
    function csrf_field() {
        $CI =& get_instance();
        $CI->load->library('security_manager');
        return $CI->security_manager->csrf_field();
    }
}

if (!function_exists('validate_csrf')) {
    /**
     * Validate CSRF token
     * @param string $token Token to validate
     * @return bool Validation result
     */
    function validate_csrf($token = null) {
        $CI =& get_instance();
        $CI->load->library('security_manager');
        return $CI->security_manager->validate_csrf_token($token);
    }
}

if (!function_exists('secure_hash')) {
    /**
     * Generate secure hash for passwords
     * @param string $password Password to hash
     * @return string Hashed password
     */
    function secure_hash($password) {
        $CI =& get_instance();
        $CI->config->load('security');
        
        $algorithm = $CI->config->item('hash_algorithm') ?: PASSWORD_ARGON2ID;
        $options = $CI->config->item('hash_options') ?: array();
        
        return password_hash($password, $algorithm, $options);
    }
}

if (!function_exists('verify_secure_hash')) {
    /**
     * Verify password against secure hash
     * @param string $password Password to verify
     * @param string $hash Hash to verify against
     * @return bool Verification result
     */
    function verify_secure_hash($password, $hash) {
        return password_verify($password, $hash);
    }
}

if (!function_exists('generate_secure_token')) {
    /**
     * Generate cryptographically secure random token
     * @param int $length Token length in bytes
     * @return string Hex-encoded token
     */
    function generate_secure_token($length = 32) {
        return bin2hex(random_bytes($length));
    }
}

if (!function_exists('is_secure_connection')) {
    /**
     * Check if connection is secure (HTTPS)
     * @return bool Is secure connection
     */
    function is_secure_connection() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
               $_SERVER['SERVER_PORT'] == 443 ||
               (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * Get client IP address (considering proxies)
     * @return string Client IP address
     */
    function get_client_ip() {
        $ip_keys = array(
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'REMOTE_ADDR'                // Standard
        );
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}

if (!function_exists('log_security_event')) {
    /**
     * Log security event
     * @param string $event_type Type of security event
     * @param string $message Event message
     * @param array $context Additional context
     * @param string $severity Event severity (low, medium, high, critical)
     */
    function log_security_event($event_type, $message, $context = array(), $severity = 'medium') {
        $CI =& get_instance();
        $CI->load->library('security_manager');
        $CI->security_manager->log_security_event($event_type, $message, $context);
        
        // Also log to database if table exists
        if ($CI->db->table_exists('security_logs')) {
            try {
                $log_data = array(
                    'event_type' => $event_type,
                    'severity' => $severity,
                    'message' => $message,
                    'ip_address' => get_client_ip(),
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                    'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
                    'request_method' => $_SERVER['REQUEST_METHOD'] ?? '',
                    'context_data' => json_encode($context),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                $CI->db->insert('security_logs', $log_data);
            } catch (Exception $e) {
                error_log('Failed to log security event to database: ' . $e->getMessage());
            }
        }
    }
}

if (!function_exists('validate_file_security')) {
    /**
     * Validate file upload security
     * @param array $file File data from $_FILES
     * @param array $config Upload configuration
     * @return array Validation result
     */
    function validate_file_security($file, $config = array()) {
        $CI =& get_instance();
        $CI->load->library('security_manager');
        return $CI->security_manager->validate_file_security($file, $config);
    }
}

if (!function_exists('check_rate_limit')) {
    /**
     * Check rate limiting
     * @param string $identifier Unique identifier
     * @param int $max_attempts Maximum attempts
     * @param int $time_window Time window in seconds
     * @return bool Is within rate limit
     */
    function check_rate_limit($identifier, $max_attempts = 10, $time_window = 300) {
        $CI =& get_instance();
        $CI->load->library('security_manager');
        return $CI->security_manager->check_rate_limit($identifier, $max_attempts, $time_window);
    }
}

if (!function_exists('sanitize_filename')) {
    /**
     * Sanitize filename for safe storage
     * @param string $filename Original filename
     * @return string Sanitized filename
     */
    function sanitize_filename($filename) {
        // Remove path information
        $filename = basename($filename);
        
        // Remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Remove multiple dots (except for extension)
        $parts = explode('.', $filename);
        if (count($parts) > 2) {
            $extension = array_pop($parts);
            $name = implode('_', $parts);
            $filename = $name . '.' . $extension;
        }
        
        // Ensure filename is not empty
        if (empty($filename) || $filename === '.') {
            $filename = 'file_' . time();
        }
        
        // Limit length
        if (strlen($filename) > 255) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $name = substr(pathinfo($filename, PATHINFO_FILENAME), 0, 250 - strlen($extension));
            $filename = $name . '.' . $extension;
        }
        
        return $filename;
    }
}

if (!function_exists('is_safe_redirect_url')) {
    /**
     * Check if URL is safe for redirect (prevent open redirect attacks)
     * @param string $url URL to check
     * @param array $allowed_domains Allowed domains for redirect
     * @return bool Is safe URL
     */
    function is_safe_redirect_url($url, $allowed_domains = array()) {
        // Parse URL
        $parsed = parse_url($url);
        
        if ($parsed === false) {
            return false;
        }
        
        // Allow relative URLs
        if (!isset($parsed['host'])) {
            return true;
        }
        
        // Check against allowed domains
        if (!empty($allowed_domains)) {
            return in_array($parsed['host'], $allowed_domains);
        }
        
        // Default: only allow same domain
        $current_host = $_SERVER['HTTP_HOST'] ?? '';
        return $parsed['host'] === $current_host;
    }
}

if (!function_exists('mask_sensitive_data')) {
    /**
     * Mask sensitive data for logging/display
     * @param string $data Data to mask
     * @param string $type Type of data (email, phone, card, etc.)
     * @return string Masked data
     */
    function mask_sensitive_data($data, $type = 'default') {
        if (empty($data)) {
            return $data;
        }
        
        switch ($type) {
            case 'email':
                $parts = explode('@', $data);
                if (count($parts) === 2) {
                    $username = $parts[0];
                    $domain = $parts[1];
                    $masked_username = substr($username, 0, 2) . str_repeat('*', max(0, strlen($username) - 2));
                    return $masked_username . '@' . $domain;
                }
                break;
                
            case 'phone':
                $length = strlen($data);
                if ($length > 4) {
                    return str_repeat('*', $length - 4) . substr($data, -4);
                }
                break;
                
            case 'card':
                $length = strlen($data);
                if ($length > 4) {
                    return str_repeat('*', $length - 4) . substr($data, -4);
                }
                break;
                
            case 'aadhar':
                $length = strlen($data);
                if ($length === 12) {
                    return str_repeat('*', 8) . substr($data, -4);
                }
                break;
                
            case 'pan':
                $length = strlen($data);
                if ($length === 10) {
                    return substr($data, 0, 3) . str_repeat('*', 4) . substr($data, -3);
                }
                break;
                
            default:
                $length = strlen($data);
                if ($length > 6) {
                    return substr($data, 0, 3) . str_repeat('*', $length - 6) . substr($data, -3);
                } elseif ($length > 2) {
                    return substr($data, 0, 1) . str_repeat('*', $length - 2) . substr($data, -1);
                }
                break;
        }
        
        return str_repeat('*', strlen($data));
    }
}

if (!function_exists('validate_json_input')) {
    /**
     * Validate and sanitize JSON input
     * @param string $json JSON string
     * @param int $max_depth Maximum nesting depth
     * @return array Validation result
     */
    function validate_json_input($json, $max_depth = 10) {
        if (empty($json)) {
            return array('valid' => false, 'error' => 'Empty JSON input');
        }
        
        // Check for suspicious patterns
        $suspicious_patterns = array(
            '/__proto__/i',
            '/constructor/i',
            '/prototype/i',
            '/<script/i',
            '/javascript:/i'
        );
        
        foreach ($suspicious_patterns as $pattern) {
            if (preg_match($pattern, $json)) {
                return array('valid' => false, 'error' => 'Suspicious content detected');
            }
        }
        
        // Decode JSON
        $data = json_decode($json, true, $max_depth);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return array('valid' => false, 'error' => 'Invalid JSON: ' . json_last_error_msg());
        }
        
        return array('valid' => true, 'data' => $data);
    }
}

if (!function_exists('secure_unlink')) {
    /**
     * Securely delete file
     * @param string $filepath Path to file
     * @return bool Success status
     */
    function secure_unlink($filepath) {
        if (!file_exists($filepath)) {
            return true;
        }
        
        try {
            // Log file deletion
            log_security_event('file_deletion', 'File deleted: ' . basename($filepath), array(
                'filepath' => $filepath,
                'filesize' => filesize($filepath)
            ));
            
            // Overwrite file content before deletion (for sensitive files)
            $filesize = filesize($filepath);
            if ($filesize > 0 && $filesize < 10485760) { // Only for files < 10MB
                $handle = fopen($filepath, 'r+');
                if ($handle) {
                    fwrite($handle, str_repeat("\0", $filesize));
                    fclose($handle);
                }
            }
            
            return unlink($filepath);
            
        } catch (Exception $e) {
            error_log('Secure file deletion failed: ' . $e->getMessage());
            return false;
        }
    }
}