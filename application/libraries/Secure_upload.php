<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Secure File Upload Library
 * Provides secure file upload handling with validation and security measures
 * Requirements: 7.1, 7.4, 7.7
 */
class Secure_upload {
    
    protected $CI;
    protected $upload_path = './uploads/';
    protected $allowed_types = 'pdf|doc|docx|jpg|jpeg|png';
    protected $max_size = 5120; // 5MB in KB
    protected $encrypt_name = true;
    protected $remove_spaces = true;
    protected $detect_mime = true;
    protected $mod_mime_fix = true;
    protected $errors = array();
    
    public function __construct($config = array()) {
        $this->CI =& get_instance();
        $this->CI->load->library('upload');
        
        // Override default config with provided config
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        // Ensure upload directory exists and is writable
        $this->ensure_upload_directory();
    }
    
    /**
     * Upload file with security validation
     * @param string $field_name Form field name
     * @param string $subfolder Optional subfolder within upload path
     * @return array Upload result
     */
    public function upload_file($field_name, $subfolder = '') {
        try {
            // Reset errors
            $this->errors = array();
            
            // Check if file was uploaded
            if (!isset($_FILES[$field_name]) || $_FILES[$field_name]['error'] === UPLOAD_ERR_NO_FILE) {
                return array(
                    'success' => false,
                    'message' => 'No file was uploaded',
                    'errors' => array('No file selected')
                );
            }
            
            $file = $_FILES[$field_name];
            
            // Validate file before upload
            $validation_result = $this->validate_file($file);
            if (!$validation_result['is_valid']) {
                return array(
                    'success' => false,
                    'message' => 'File validation failed',
                    'errors' => $validation_result['errors']
                );
            }
            
            // Set upload path with subfolder
            $upload_path = $this->upload_path;
            if (!empty($subfolder)) {
                $upload_path = rtrim($upload_path, '/') . '/' . trim($subfolder, '/') . '/';
                $this->ensure_directory($upload_path);
            }
            
            // Configure upload library
            $config = array(
                'upload_path' => $upload_path,
                'allowed_types' => $this->allowed_types,
                'max_size' => $this->max_size,
                'encrypt_name' => $this->encrypt_name,
                'remove_spaces' => $this->remove_spaces,
                'detect_mime' => $this->detect_mime,
                'mod_mime_fix' => $this->mod_mime_fix
            );
            
            $this->CI->upload->initialize($config);
            
            // Perform upload
            if ($this->CI->upload->do_upload($field_name)) {
                $upload_data = $this->CI->upload->data();
                
                // Additional security checks on uploaded file
                $security_check = $this->post_upload_security_check($upload_data);
                if (!$security_check['is_secure']) {
                    // Delete the uploaded file if security check fails
                    @unlink($upload_data['full_path']);
                    
                    return array(
                        'success' => false,
                        'message' => 'File failed security validation',
                        'errors' => $security_check['errors']
                    );
                }
                
                // Log successful upload
                $this->log_upload_activity($upload_data, 'success');
                
                return array(
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'file_data' => $upload_data,
                    'file_path' => $upload_data['full_path'],
                    'file_name' => $upload_data['file_name'],
                    'original_name' => $upload_data['orig_name'],
                    'file_size' => $upload_data['file_size'],
                    'file_type' => $upload_data['file_type']
                );
                
            } else {
                $upload_errors = $this->CI->upload->display_errors('', '');
                
                // Log failed upload
                $this->log_upload_activity($file, 'failed', $upload_errors);
                
                return array(
                    'success' => false,
                    'message' => 'File upload failed',
                    'errors' => array($upload_errors)
                );
            }
            
        } catch (Exception $e) {
            error_log('Exception in secure upload: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'message' => 'An error occurred during file upload',
                'errors' => array($e->getMessage())
            );
        }
    }
    
    /**
     * Validate file before upload
     * @param array $file File data from $_FILES
     * @return array Validation result
     */
    protected function validate_file($file) {
        $errors = array();
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors[] = 'File is too large';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errors[] = 'File was only partially uploaded';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $errors[] = 'Missing temporary folder';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $errors[] = 'Failed to write file to disk';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $errors[] = 'File upload stopped by extension';
                    break;
                default:
                    $errors[] = 'Unknown upload error';
                    break;
            }
            
            return array('is_valid' => false, 'errors' => $errors);
        }
        
        // Check file size
        if ($file['size'] > ($this->max_size * 1024)) {
            $errors[] = 'File size exceeds maximum allowed size of ' . ($this->max_size / 1024) . 'MB';
        }
        
        // Check if file is empty
        if ($file['size'] === 0) {
            $errors[] = 'File is empty';
        }
        
        // Check file extension
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_types = explode('|', $this->allowed_types);
        
        if (!in_array($file_ext, $allowed_types)) {
            $errors[] = 'File type not allowed. Allowed types: ' . str_replace('|', ', ', $this->allowed_types);
        }
        
        // Check MIME type for security
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            $allowed_mimes = $this->get_allowed_mime_types();
            
            if (!in_array($mime_type, $allowed_mimes)) {
                $errors[] = 'File type not allowed based on content analysis';
            }
        }
        
        // Check for malicious file names
        if ($this->has_malicious_filename($file['name'])) {
            $errors[] = 'File name contains invalid characters';
        }
        
        // Check file content for potential threats
        $content_check = $this->scan_file_content($file['tmp_name']);
        if (!$content_check['is_safe']) {
            $errors = array_merge($errors, $content_check['errors']);
        }
        
        return array(
            'is_valid' => empty($errors),
            'errors' => $errors
        );
    }
    
    /**
     * Perform security checks after file upload
     * @param array $upload_data Upload data from CI upload library
     * @return array Security check result
     */
    protected function post_upload_security_check($upload_data) {
        $errors = array();
        
        // Verify file still exists
        if (!file_exists($upload_data['full_path'])) {
            $errors[] = 'Uploaded file not found';
            return array('is_secure' => false, 'errors' => $errors);
        }
        
        // Re-check MIME type of uploaded file
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $upload_data['full_path']);
            finfo_close($finfo);
            
            $allowed_mimes = $this->get_allowed_mime_types();
            
            if (!in_array($mime_type, $allowed_mimes)) {
                $errors[] = 'File content does not match expected type';
            }
        }
        
        // Check file size matches what was uploaded
        $actual_size = filesize($upload_data['full_path']);
        if ($actual_size !== $upload_data['file_size'] * 1024) {
            $errors[] = 'File size mismatch detected';
        }
        
        // Scan for executable content in non-executable files
        if (in_array($upload_data['file_ext'], array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'))) {
            $executable_check = $this->check_for_executable_content($upload_data['full_path']);
            if (!$executable_check['is_safe']) {
                $errors = array_merge($errors, $executable_check['errors']);
            }
        }
        
        return array(
            'is_secure' => empty($errors),
            'errors' => $errors
        );
    }
    
    /**
     * Get allowed MIME types based on file extensions
     * @return array Allowed MIME types
     */
    protected function get_allowed_mime_types() {
        $mime_types = array(
            // PDF
            'application/pdf',
            
            // Microsoft Word
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            
            // Images
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif'
        );
        
        return $mime_types;
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
        
        // Check for path traversal attempts
        if (strpos($filename, '../') !== false || strpos($filename, '..\\') !== false) {
            return true;
        }
        
        // Check for executable extensions (double extension attacks)
        $dangerous_extensions = array('php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'cmd', 'com', 'scr', 'vbs', 'js', 'jar');
        
        foreach ($dangerous_extensions as $ext) {
            if (stripos($filename, '.' . $ext) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Scan file content for malicious patterns and viruses
     * @param string $file_path Path to file
     * @return array Scan result
     */
    protected function scan_file_content($file_path) {
        $errors = array();
        $scan_details = array();
        
        try {
            // Read file content for analysis
            $file_size = filesize($file_path);
            $scan_details['file_size'] = $file_size;
            
            // Don't scan very large files (> 50MB) for performance
            if ($file_size > 52428800) {
                $scan_details['scan_type'] = 'signature_only';
                return $this->scan_file_signatures($file_path);
            }
            
            $handle = fopen($file_path, 'rb');
            if (!$handle) {
                $errors[] = 'Cannot read file for security scan';
                return array('is_safe' => false, 'errors' => $errors, 'scan_details' => $scan_details);
            }
            
            // Read content in chunks for large files
            $content = '';
            $bytes_read = 0;
            $max_scan_size = 1048576; // 1MB max scan size
            
            while (!feof($handle) && $bytes_read < $max_scan_size) {
                $chunk = fread($handle, 8192);
                $content .= $chunk;
                $bytes_read += strlen($chunk);
            }
            fclose($handle);
            
            $scan_details['bytes_scanned'] = $bytes_read;
            $scan_details['scan_type'] = 'content_analysis';
            
            // Check for executable signatures
            $dangerous_signatures = array(
                'MZ' => 'Windows executable',
                '#!/bin/' => 'Unix script',
                '#!/usr/' => 'Unix script',
                '<?php' => 'PHP script',
                '<script' => 'JavaScript',
                'javascript:' => 'JavaScript protocol',
                'vbscript:' => 'VBScript protocol',
                'data:text/html' => 'HTML data URI',
                'data:application/' => 'Application data URI',
                '\x7fELF' => 'Linux executable',
                'PK\x03\x04' => 'ZIP archive (potential)',
                '\x50\x4b\x03\x04' => 'ZIP archive'
            );
            
            foreach ($dangerous_signatures as $signature => $description) {
                if (strpos($content, $signature) !== false) {
                    // Special handling for PDF - check for embedded JavaScript
                    if (strpos($content, '%PDF-') !== false && strpos($content, '/JS') !== false) {
                        $errors[] = 'PDF contains embedded JavaScript';
                        $scan_details['threats'][] = 'pdf_javascript';
                    } elseif ($signature !== '%PDF-1.') {
                        $errors[] = "File contains potentially dangerous content: $description";
                        $scan_details['threats'][] = strtolower(str_replace(' ', '_', $description));
                    }
                }
            }
            
            // Check for suspicious code patterns
            $suspicious_patterns = array(
                '/eval\s*\(/i' => 'Code evaluation function',
                '/exec\s*\(/i' => 'Command execution function',
                '/system\s*\(/i' => 'System command function',
                '/shell_exec\s*\(/i' => 'Shell execution function',
                '/passthru\s*\(/i' => 'Command passthrough function',
                '/base64_decode\s*\(/i' => 'Base64 decode (potential obfuscation)',
                '/file_get_contents\s*\(/i' => 'File access function',
                '/curl_exec\s*\(/i' => 'Network request function',
                '/fopen\s*\(/i' => 'File open function',
                '/fwrite\s*\(/i' => 'File write function',
                '/include\s*\(/i' => 'File inclusion function',
                '/require\s*\(/i' => 'File requirement function',
                '/<iframe/i' => 'Iframe element',
                '/<object/i' => 'Object element',
                '/<embed/i' => 'Embed element',
                '/onload\s*=/i' => 'JavaScript event handler',
                '/onerror\s*=/i' => 'JavaScript error handler',
                '/onclick\s*=/i' => 'JavaScript click handler'
            );
            
            foreach ($suspicious_patterns as $pattern => $description) {
                if (preg_match($pattern, $content)) {
                    $errors[] = "Suspicious pattern detected: $description";
                    $scan_details['suspicious_patterns'][] = $description;
                }
            }
            
            // Check for common malware signatures
            $malware_signatures = array(
                'X5O!P%@AP[4\PZX54(P^)7CC)7}$EICAR-STANDARD-ANTIVIRUS-TEST-FILE!$H+H*' => 'EICAR test file',
                'eval(gzinflate(base64_decode(' => 'PHP malware pattern',
                'eval(base64_decode(' => 'PHP obfuscated code',
                'chr(base64_decode(' => 'PHP character obfuscation',
                'WScript.Shell' => 'Windows Script Host',
                'CreateObject("WScript.Shell")' => 'VBScript shell object',
                'document.write(unescape(' => 'JavaScript obfuscation'
            );
            
            foreach ($malware_signatures as $signature => $description) {
                if (strpos($content, $signature) !== false) {
                    $errors[] = "Malware signature detected: $description";
                    $scan_details['malware_signatures'][] = $description;
                }
            }
            
            // Log scan results
            $this->log_file_scan($file_path, $scan_details, empty($errors));
            
        } catch (Exception $e) {
            $errors[] = 'Error scanning file content: ' . $e->getMessage();
            $scan_details['scan_error'] = $e->getMessage();
        }
        
        return array(
            'is_safe' => empty($errors),
            'errors' => $errors,
            'scan_details' => $scan_details
        );
    }
    
    /**
     * Scan file signatures only (for large files)
     * @param string $file_path Path to file
     * @return array Scan result
     */
    protected function scan_file_signatures($file_path) {
        $errors = array();
        $scan_details = array('scan_type' => 'signature_only');
        
        try {
            $handle = fopen($file_path, 'rb');
            if (!$handle) {
                $errors[] = 'Cannot read file for signature scan';
                return array('is_safe' => false, 'errors' => $errors, 'scan_details' => $scan_details);
            }
            
            // Read first 1KB for signature detection
            $header = fread($handle, 1024);
            fclose($handle);
            
            // Check for dangerous file signatures
            $dangerous_headers = array(
                'MZ' => 'Windows executable',
                '\x7fELF' => 'Linux executable',
                'PK\x03\x04' => 'ZIP archive',
                '#!/bin/' => 'Unix script',
                '#!/usr/' => 'Unix script',
                '<?php' => 'PHP script'
            );
            
            foreach ($dangerous_headers as $signature => $description) {
                if (strpos($header, $signature) === 0) {
                    $errors[] = "Dangerous file signature detected: $description";
                    $scan_details['signature_detected'] = $description;
                }
            }
            
        } catch (Exception $e) {
            $errors[] = 'Error scanning file signatures: ' . $e->getMessage();
            $scan_details['scan_error'] = $e->getMessage();
        }
        
        return array(
            'is_safe' => empty($errors),
            'errors' => $errors,
            'scan_details' => $scan_details
        );
    }
    
    /**
     * Log file scan results
     * @param string $file_path Path to scanned file
     * @param array $scan_details Scan details
     * @param bool $is_safe Whether file is safe
     */
    protected function log_file_scan($file_path, $scan_details, $is_safe) {
        try {
            if ($this->CI->db->table_exists('file_upload_logs')) {
                $log_data = array(
                    'original_filename' => basename($file_path),
                    'file_path' => $file_path,
                    'file_size' => filesize($file_path),
                    'security_scan_result' => $is_safe ? 'clean' : 'suspicious',
                    'scan_details' => json_encode($scan_details),
                    'ip_address' => $this->CI->input->ip_address(),
                    'user_agent' => $this->CI->input->user_agent(),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                $this->CI->db->insert('file_upload_logs', $log_data);
            }
        } catch (Exception $e) {
            error_log('Failed to log file scan results: ' . $e->getMessage());
        }
    }
    
    /**
     * Check for executable content in uploaded files
     * @param string $file_path Path to uploaded file
     * @return array Check result
     */
    protected function check_for_executable_content($file_path) {
        $errors = array();
        
        // For images, check for embedded scripts
        $file_ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        
        if (in_array($file_ext, array('jpg', 'jpeg', 'png', 'gif'))) {
            // Use getimagesize to validate image
            $image_info = @getimagesize($file_path);
            if ($image_info === false) {
                $errors[] = 'Invalid image file';
            }
        }
        
        // Check file for suspicious content
        $content = file_get_contents($file_path, false, null, 0, 8192); // Read first 8KB
        
        $suspicious_patterns = array(
            '/<\?php/i',
            '/<script/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload=/i',
            '/onerror=/i'
        );
        
        foreach ($suspicious_patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $errors[] = 'File contains suspicious script content';
                break;
            }
        }
        
        return array(
            'is_safe' => empty($errors),
            'errors' => $errors
        );
    }
    
    /**
     * Ensure upload directory exists and is properly configured
     */
    protected function ensure_upload_directory() {
        $this->ensure_directory($this->upload_path);
        
        // Create .htaccess file for security
        $htaccess_path = rtrim($this->upload_path, '/') . '/.htaccess';
        if (!file_exists($htaccess_path)) {
            $htaccess_content = "# Deny direct access to uploaded files\n";
            $htaccess_content .= "Options -Indexes\n";
            $htaccess_content .= "Options -ExecCGI\n";
            $htaccess_content .= "<FilesMatch \"\\.(php|php3|php4|php5|phtml|pl|py|jsp|asp|sh|cgi)$\">\n";
            $htaccess_content .= "    Order allow,deny\n";
            $htaccess_content .= "    Deny from all\n";
            $htaccess_content .= "</FilesMatch>\n";
            
            file_put_contents($htaccess_path, $htaccess_content);
        }
    }
    
    /**
     * Ensure directory exists and is writable
     * @param string $path Directory path
     */
    protected function ensure_directory($path) {
        if (!is_dir($path)) {
            if (!mkdir($path, 0755, true)) {
                throw new Exception('Failed to create upload directory: ' . $path);
            }
        }
        
        if (!is_writable($path)) {
            throw new Exception('Upload directory is not writable: ' . $path);
        }
    }
    
    /**
     * Log upload activity for audit trail
     * @param array $file_data File data
     * @param string $status Upload status (success/failed)
     * @param string $error_message Error message if failed
     */
    protected function log_upload_activity($file_data, $status, $error_message = '') {
        try {
            $log_data = array(
                'timestamp' => date('Y-m-d H:i:s'),
                'status' => $status,
                'original_name' => isset($file_data['orig_name']) ? $file_data['orig_name'] : $file_data['name'],
                'file_size' => isset($file_data['file_size']) ? $file_data['file_size'] : $file_data['size'],
                'file_type' => isset($file_data['file_type']) ? $file_data['file_type'] : $file_data['type'],
                'ip_address' => $this->CI->input->ip_address(),
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
                'error_message' => $error_message
            );
            
            // Log to file
            $log_message = 'FILE UPLOAD: ' . json_encode($log_data);
            error_log($log_message);
            
            // Log to database if audit_logs table exists
            if ($this->CI->db->table_exists('audit_logs')) {
                $audit_data = array(
                    'table_name' => 'file_uploads',
                    'record_id' => 0,
                    'action' => 'file_upload_' . $status,
                    'old_values' => null,
                    'new_values' => json_encode($log_data),
                    'user_id' => $this->get_current_user_id(),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                $this->CI->db->insert('audit_logs', $audit_data);
            }
            
        } catch (Exception $e) {
            error_log('Failed to log upload activity: ' . $e->getMessage());
        }
    }
    
    /**
     * Get current user ID (placeholder - implement based on your auth system)
     * @return int|null User ID
     */
    protected function get_current_user_id() {
        // Implement based on your authentication system
        return null;
    }
    
    /**
     * Delete uploaded file securely
     * @param string $file_path Path to file
     * @return bool Success status
     */
    public function delete_file($file_path) {
        try {
            if (file_exists($file_path)) {
                // Log deletion
                $this->log_file_deletion($file_path);
                
                // Securely delete file
                return unlink($file_path);
            }
            
            return true; // File doesn't exist, consider it deleted
            
        } catch (Exception $e) {
            error_log('Failed to delete file: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Log file deletion for audit trail
     * @param string $file_path Path to deleted file
     */
    protected function log_file_deletion($file_path) {
        try {
            $log_data = array(
                'timestamp' => date('Y-m-d H:i:s'),
                'action' => 'file_deletion',
                'file_path' => $file_path,
                'ip_address' => $this->CI->input->ip_address(),
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
            );
            
            error_log('FILE DELETION: ' . json_encode($log_data));
            
        } catch (Exception $e) {
            error_log('Failed to log file deletion: ' . $e->getMessage());
        }
    }
}