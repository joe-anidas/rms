<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Security Configuration
|--------------------------------------------------------------------------
|
| This file contains security-related configuration settings for the RMS application.
| These settings help protect against various security threats including SQL injection,
| XSS attacks, CSRF attacks, and file upload vulnerabilities.
|
*/

/*
|--------------------------------------------------------------------------
| SQL Injection Protection
|--------------------------------------------------------------------------
|
| Enable/disable automatic SQL injection protection features
|
*/
$config['sql_injection_protection'] = TRUE;
$config['validate_sql_queries'] = TRUE;
$config['log_dangerous_queries'] = TRUE;

/*
|--------------------------------------------------------------------------
| XSS Protection
|--------------------------------------------------------------------------
|
| Configure Cross-Site Scripting (XSS) protection settings
|
*/
$config['xss_protection'] = TRUE;
$config['auto_sanitize_input'] = TRUE;
$config['auto_sanitize_output'] = TRUE;
$config['allowed_html_tags'] = '<p><br><strong><em><ul><ol><li>';

/*
|--------------------------------------------------------------------------
| CSRF Protection Settings
|--------------------------------------------------------------------------
|
| Additional CSRF protection settings beyond CodeIgniter's built-in protection
|
*/
$config['csrf_strict_mode'] = TRUE;
$config['csrf_ajax_protection'] = TRUE;
$config['csrf_token_regeneration'] = TRUE;
$config['csrf_exclude_controllers'] = array('api'); // Controllers to exclude from CSRF

/*
|--------------------------------------------------------------------------
| File Upload Security
|--------------------------------------------------------------------------
|
| Security settings for file uploads
|
*/
$config['file_upload_security'] = array(
    'enabled' => TRUE,
    'scan_content' => TRUE,
    'check_mime_type' => TRUE,
    'virus_scan' => FALSE, // Enable if antivirus is available
    'allowed_extensions' => array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'),
    'max_file_size' => 5242880, // 5MB in bytes
    'quarantine_suspicious_files' => TRUE,
    'log_upload_attempts' => TRUE
);

/*
|--------------------------------------------------------------------------
| Input Validation Security
|--------------------------------------------------------------------------
|
| Settings for input validation and sanitization
|
*/
$config['input_validation'] = array(
    'strict_mode' => TRUE,
    'validate_all_inputs' => TRUE,
    'sanitize_on_input' => TRUE,
    'validate_field_names' => TRUE,
    'max_input_length' => 10000,
    'blocked_patterns' => array(
        'sql_injection' => array(
            '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION|SCRIPT)\b)/i',
            '/(\b(OR|AND)\s+\d+\s*=\s*\d+)/i',
            '/(\-\-|\#|\/\*|\*\/)/i'
        ),
        'xss_patterns' => array(
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi',
            '/javascript:/i',
            '/vbscript:/i',
            '/on\w+\s*=/i'
        ),
        'path_traversal' => array(
            '/\.\./i',
            '/\0/i'
        )
    )
);

/*
|--------------------------------------------------------------------------
| Rate Limiting
|--------------------------------------------------------------------------
|
| Configure rate limiting to prevent abuse
|
*/
$config['rate_limiting'] = array(
    'enabled' => TRUE,
    'requests_per_minute' => 60,
    'requests_per_hour' => 1000,
    'form_submissions_per_minute' => 5,
    'login_attempts_per_hour' => 10,
    'block_duration' => 300, // 5 minutes
    'whitelist_ips' => array('127.0.0.1', '::1')
);

/*
|--------------------------------------------------------------------------
| Security Headers
|--------------------------------------------------------------------------
|
| HTTP security headers to be sent with responses
|
*/
$config['security_headers'] = array(
    'X-Frame-Options' => 'DENY',
    'X-Content-Type-Options' => 'nosniff',
    'X-XSS-Protection' => '1; mode=block',
    'Referrer-Policy' => 'strict-origin-when-cross-origin',
    'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; img-src 'self' data: https:; connect-src 'self'; frame-ancestors 'none';",
    'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains', // Enable in production with HTTPS
    'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()'
);

/*
|--------------------------------------------------------------------------
| Session Security
|--------------------------------------------------------------------------
|
| Enhanced session security settings
|
*/
$config['session_security'] = array(
    'regenerate_on_login' => TRUE,
    'regenerate_on_privilege_change' => TRUE,
    'session_timeout' => 7200, // 2 hours
    'idle_timeout' => 1800, // 30 minutes
    'validate_ip' => FALSE, // Set to TRUE if users have static IPs
    'validate_user_agent' => TRUE,
    'secure_cookies' => FALSE, // Set to TRUE in production with HTTPS
    'httponly_cookies' => TRUE,
    'samesite_cookies' => 'Strict'
);

/*
|--------------------------------------------------------------------------
| Database Security
|--------------------------------------------------------------------------
|
| Database-related security settings
|
*/
$config['database_security'] = array(
    'use_prepared_statements' => TRUE,
    'validate_table_names' => TRUE,
    'validate_column_names' => TRUE,
    'log_database_errors' => TRUE,
    'prevent_information_disclosure' => TRUE,
    'audit_database_operations' => TRUE,
    'encrypt_sensitive_data' => FALSE // Enable if encryption is needed
);

/*
|--------------------------------------------------------------------------
| Audit Logging
|--------------------------------------------------------------------------
|
| Settings for security audit logging
|
*/
$config['audit_logging'] = array(
    'enabled' => TRUE,
    'log_failed_logins' => TRUE,
    'log_privilege_escalations' => TRUE,
    'log_data_modifications' => TRUE,
    'log_file_uploads' => TRUE,
    'log_security_violations' => TRUE,
    'log_retention_days' => 90,
    'log_to_database' => TRUE,
    'log_to_file' => TRUE,
    'alert_on_suspicious_activity' => TRUE
);

/*
|--------------------------------------------------------------------------
| Password Security
|--------------------------------------------------------------------------
|
| Password policy and security settings
|
*/
$config['password_security'] = array(
    'min_length' => 8,
    'require_uppercase' => TRUE,
    'require_lowercase' => TRUE,
    'require_numbers' => TRUE,
    'require_special_chars' => TRUE,
    'prevent_common_passwords' => TRUE,
    'password_history' => 5, // Remember last 5 passwords
    'password_expiry_days' => 90,
    'hash_algorithm' => PASSWORD_ARGON2ID,
    'hash_options' => array(
        'memory_cost' => 65536, // 64 MB
        'time_cost' => 4,       // 4 iterations
        'threads' => 3          // 3 threads
    )
);

/*
|--------------------------------------------------------------------------
| API Security
|--------------------------------------------------------------------------
|
| Security settings for API endpoints
|
*/
$config['api_security'] = array(
    'require_authentication' => TRUE,
    'rate_limit_per_minute' => 100,
    'validate_content_type' => TRUE,
    'require_https' => FALSE, // Set to TRUE in production
    'cors_enabled' => FALSE,
    'cors_allowed_origins' => array(),
    'api_key_required' => FALSE,
    'jwt_enabled' => FALSE
);

/*
|--------------------------------------------------------------------------
| Error Handling Security
|--------------------------------------------------------------------------
|
| Security settings for error handling and information disclosure
|
*/
$config['error_security'] = array(
    'hide_php_errors' => TRUE,
    'hide_database_errors' => TRUE,
    'hide_file_paths' => TRUE,
    'generic_error_messages' => TRUE,
    'log_all_errors' => TRUE,
    'email_critical_errors' => FALSE,
    'error_email' => 'admin@example.com'
);

/*
|--------------------------------------------------------------------------
| Content Security
|--------------------------------------------------------------------------
|
| Settings for content validation and filtering
|
*/
$config['content_security'] = array(
    'validate_json_input' => TRUE,
    'max_json_depth' => 10,
    'validate_xml_input' => TRUE,
    'disable_external_entities' => TRUE,
    'validate_file_contents' => TRUE,
    'scan_uploaded_files' => TRUE,
    'content_type_validation' => TRUE
);

/*
|--------------------------------------------------------------------------
| Development Security
|--------------------------------------------------------------------------
|
| Security settings that should be different in development vs production
|
*/
if (ENVIRONMENT === 'development') {
    // Relaxed settings for development
    $config['security_headers']['Strict-Transport-Security'] = '';
    $config['session_security']['secure_cookies'] = FALSE;
    $config['api_security']['require_https'] = FALSE;
    $config['error_security']['hide_php_errors'] = FALSE;
} else {
    // Strict settings for production
    $config['session_security']['secure_cookies'] = TRUE;
    $config['api_security']['require_https'] = TRUE;
    $config['error_security']['hide_php_errors'] = TRUE;
    $config['error_security']['generic_error_messages'] = TRUE;
}