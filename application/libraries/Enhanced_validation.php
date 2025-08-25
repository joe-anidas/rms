<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Enhanced Validation Library
 * Provides comprehensive server-side validation with custom rules and error handling
 * Requirements: 7.1, 7.4, 7.7
 */
class Enhanced_validation {
    
    protected $CI;
    protected $errors = array();
    protected $rules = array();
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('form_validation');
    }
    
    /**
     * Set validation rules with enhanced options
     * @param array $rules Validation rules array
     */
    public function set_rules($rules) {
        $this->rules = $rules;
        
        foreach ($rules as $field => $rule_config) {
            $label = isset($rule_config['label']) ? $rule_config['label'] : ucfirst(str_replace('_', ' ', $field));
            $rules_string = isset($rule_config['rules']) ? $rule_config['rules'] : '';
            $errors = isset($rule_config['errors']) ? $rule_config['errors'] : array();
            
            $this->CI->form_validation->set_rules($field, $label, $rules_string, $errors);
        }
    }
    
    /**
     * Run validation with enhanced error handling
     * @return bool Validation result
     */
    public function run() {
        $result = $this->CI->form_validation->run();
        
        if (!$result) {
            $this->errors = $this->CI->form_validation->error_array();
        }
        
        return $result;
    }
    
    /**
     * Get validation errors
     * @return array Validation errors
     */
    public function get_errors() {
        return $this->errors;
    }
    
    /**
     * Get formatted error messages
     * @return string Formatted error messages
     */
    public function get_error_string() {
        return validation_errors();
    }
    
    /**
     * Validate property data
     * @param array $data Property data
     * @return array Validation result
     */
    public function validate_property($data) {
        $rules = array(
            'garden_name' => array(
                'label' => 'Property Name',
                'rules' => 'required|trim|max_length[255]|min_length[3]',
                'errors' => array(
                    'required' => 'Property name is required',
                    'min_length' => 'Property name must be at least 3 characters',
                    'max_length' => 'Property name cannot exceed 255 characters'
                )
            ),
            'property_type' => array(
                'label' => 'Property Type',
                'rules' => 'required|in_list[garden,plot,house,flat]',
                'errors' => array(
                    'required' => 'Property type is required',
                    'in_list' => 'Invalid property type selected'
                )
            ),
            'size_sqft' => array(
                'label' => 'Size (Sq Ft)',
                'rules' => 'numeric|greater_than[0]',
                'errors' => array(
                    'numeric' => 'Size must be a valid number',
                    'greater_than' => 'Size must be greater than 0'
                )
            ),
            'price' => array(
                'label' => 'Price',
                'rules' => 'numeric|greater_than[0]',
                'errors' => array(
                    'numeric' => 'Price must be a valid number',
                    'greater_than' => 'Price must be greater than 0'
                )
            ),
            'status' => array(
                'label' => 'Status',
                'rules' => 'in_list[unsold,booked,sold]',
                'errors' => array(
                    'in_list' => 'Invalid status selected'
                )
            )
        );
        
        $this->set_rules($rules);
        
        // Set form data for validation
        foreach ($data as $key => $value) {
            $_POST[$key] = $value;
        }
        
        $is_valid = $this->run();
        
        return array(
            'is_valid' => $is_valid,
            'errors' => $this->get_errors()
        );
    }
    
    /**
     * Validate customer data
     * @param array $data Customer data
     * @return array Validation result
     */
    public function validate_customer($data) {
        $rules = array(
            'plot_buyer_name' => array(
                'label' => 'Customer Name',
                'rules' => 'required|trim|max_length[255]|min_length[2]',
                'errors' => array(
                    'required' => 'Customer name is required',
                    'min_length' => 'Customer name must be at least 2 characters',
                    'max_length' => 'Customer name cannot exceed 255 characters'
                )
            ),
            'phone_number_1' => array(
                'label' => 'Primary Phone',
                'rules' => 'required|trim|max_length[15]|min_length[10]|regex_match[/^[0-9+\-\s()]+$/]',
                'errors' => array(
                    'required' => 'Primary phone number is required',
                    'min_length' => 'Phone number must be at least 10 digits',
                    'max_length' => 'Phone number cannot exceed 15 characters',
                    'regex_match' => 'Invalid phone number format'
                )
            ),
            'email_address' => array(
                'label' => 'Email Address',
                'rules' => 'valid_email|max_length[255]',
                'errors' => array(
                    'valid_email' => 'Please enter a valid email address',
                    'max_length' => 'Email address cannot exceed 255 characters'
                )
            ),
            'aadhar_number' => array(
                'label' => 'Aadhar Number',
                'rules' => 'exact_length[12]|numeric',
                'errors' => array(
                    'exact_length' => 'Aadhar number must be exactly 12 digits',
                    'numeric' => 'Aadhar number must contain only numbers'
                )
            ),
            'pan_number' => array(
                'label' => 'PAN Number',
                'rules' => 'exact_length[10]|regex_match[/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/]',
                'errors' => array(
                    'exact_length' => 'PAN number must be exactly 10 characters',
                    'regex_match' => 'Invalid PAN number format (e.g., ABCDE1234F)'
                )
            ),
            'annual_income' => array(
                'label' => 'Annual Income',
                'rules' => 'numeric|greater_than_equal_to[0]',
                'errors' => array(
                    'numeric' => 'Annual income must be a valid number',
                    'greater_than_equal_to' => 'Annual income cannot be negative'
                )
            )
        );
        
        $this->set_rules($rules);
        
        // Set form data for validation
        foreach ($data as $key => $value) {
            $_POST[$key] = $value;
        }
        
        $is_valid = $this->run();
        
        return array(
            'is_valid' => $is_valid,
            'errors' => $this->get_errors()
        );
    }
    
    /**
     * Validate transaction data
     * @param array $data Transaction data
     * @return array Validation result
     */
    public function validate_transaction($data) {
        $rules = array(
            'registration_id' => array(
                'label' => 'Registration',
                'rules' => 'required|integer|greater_than[0]',
                'errors' => array(
                    'required' => 'Registration is required',
                    'integer' => 'Invalid registration selected',
                    'greater_than' => 'Invalid registration selected'
                )
            ),
            'amount' => array(
                'label' => 'Amount',
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => array(
                    'required' => 'Amount is required',
                    'numeric' => 'Amount must be a valid number',
                    'greater_than' => 'Amount must be greater than 0'
                )
            ),
            'payment_type' => array(
                'label' => 'Payment Type',
                'rules' => 'required|in_list[advance,installment,full_payment]',
                'errors' => array(
                    'required' => 'Payment type is required',
                    'in_list' => 'Invalid payment type selected'
                )
            ),
            'payment_method' => array(
                'label' => 'Payment Method',
                'rules' => 'required|in_list[cash,cheque,bank_transfer,online]',
                'errors' => array(
                    'required' => 'Payment method is required',
                    'in_list' => 'Invalid payment method selected'
                )
            ),
            'payment_date' => array(
                'label' => 'Payment Date',
                'rules' => 'required|valid_date',
                'errors' => array(
                    'required' => 'Payment date is required',
                    'valid_date' => 'Please enter a valid date'
                )
            )
        );
        
        $this->set_rules($rules);
        
        // Set form data for validation
        foreach ($data as $key => $value) {
            $_POST[$key] = $value;
        }
        
        $is_valid = $this->run();
        
        return array(
            'is_valid' => $is_valid,
            'errors' => $this->get_errors()
        );
    }
    
    /**
     * Validate file upload
     * @param array $file File data from $_FILES
     * @param array $config Upload configuration
     * @return array Validation result
     */
    public function validate_file_upload($file, $config = array()) {
        $errors = array();
        
        // Default configuration
        $default_config = array(
            'allowed_types' => 'pdf|doc|docx|jpg|jpeg|png',
            'max_size' => 5120, // 5MB in KB
            'max_width' => 0,
            'max_height' => 0
        );
        
        $config = array_merge($default_config, $config);
        
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $errors[] = 'No file was uploaded';
            return array('is_valid' => false, 'errors' => $errors);
        }
        
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
                case UPLOAD_ERR_NO_FILE:
                    $errors[] = 'No file was uploaded';
                    break;
                default:
                    $errors[] = 'File upload failed';
                    break;
            }
            return array('is_valid' => false, 'errors' => $errors);
        }
        
        // Check file size
        if ($file['size'] > ($config['max_size'] * 1024)) {
            $errors[] = 'File size exceeds maximum allowed size of ' . ($config['max_size'] / 1024) . 'MB';
        }
        
        // Check file type
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_types = explode('|', $config['allowed_types']);
        
        if (!in_array($file_ext, $allowed_types)) {
            $errors[] = 'File type not allowed. Allowed types: ' . str_replace('|', ', ', $config['allowed_types']);
        }
        
        // Check MIME type for security
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowed_mimes = array(
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png'
        );
        
        if (isset($allowed_mimes[$file_ext]) && $mime_type !== $allowed_mimes[$file_ext]) {
            $errors[] = 'File type mismatch. File appears to be corrupted or has incorrect extension.';
        }
        
        return array(
            'is_valid' => empty($errors),
            'errors' => $errors
        );
    }
    
    /**
     * Sanitize input data with enhanced security
     * @param mixed $data Input data
     * @param string $type Data type (string, email, phone, numeric, etc.)
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
        
        // Convert to string and remove null bytes
        $data = (string) $data;
        $data = str_replace(chr(0), '', $data);
        
        // Remove control characters except newlines and tabs
        $data = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $data);
        
        switch ($type) {
            case 'email':
                $data = filter_var(trim($data), FILTER_SANITIZE_EMAIL);
                return filter_var($data, FILTER_VALIDATE_EMAIL) ? $data : '';
            
            case 'phone':
                return preg_replace('/[^0-9+\-\s()]/', '', trim($data));
            
            case 'numeric':
                return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            
            case 'integer':
                return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            
            case 'url':
                $data = filter_var(trim($data), FILTER_SANITIZE_URL);
                return filter_var($data, FILTER_VALIDATE_URL) ? $data : '';
            
            case 'filename':
                // Remove dangerous characters from filenames
                $data = preg_replace('/[^a-zA-Z0-9._-]/', '', $data);
                return trim($data, '.');
            
            case 'sql_identifier':
                // For table/column names - only alphanumeric and underscore
                return preg_replace('/[^a-zA-Z0-9_]/', '', $data);
            
            case 'html':
                // Allow specific HTML tags but sanitize
                return strip_tags($data, '<p><br><strong><em><ul><ol><li>');
            
            case 'string':
            default:
                // HTML encode to prevent XSS
                return htmlspecialchars(trim($data), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }
    
    /**
     * Validate SQL injection patterns
     * @param string $input Input to validate
     * @return bool Is safe from SQL injection
     */
    public function validate_sql_safety($input) {
        $dangerous_patterns = array(
            '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION|SCRIPT)\b)/i',
            '/(\b(OR|AND)\s+\d+\s*=\s*\d+)/i',
            '/(\b(OR|AND)\s+[\'"]?\w+[\'"]?\s*=\s*[\'"]?\w+[\'"]?)/i',
            '/(\-\-|\#|\/\*|\*\/)/i',
            '/(\bUNION\s+SELECT\b)/i',
            '/(\bINTO\s+OUTFILE\b)/i',
            '/(\bLOAD_FILE\s*\()/i'
        );
        
        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Validate XSS patterns
     * @param string $input Input to validate
     * @return bool Is safe from XSS
     */
    public function validate_xss_safety($input) {
        $dangerous_patterns = array(
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i',
            '/onmouseover\s*=/i',
            '/<iframe/i',
            '/<object/i',
            '/<embed/i',
            '/<form/i'
        );
        
        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return false;
            }
        }
        
        return true;
    }
}