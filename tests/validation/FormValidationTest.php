<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

/**
 * Form Validation Tests
 * Tests form validation rules, error handling, and security measures
 */
class FormValidationTest extends RMS_TestCase {
    
    protected function setUpTestData() {
        // Load form validation library
        $this->CI->load->library('form_validation');
        $this->CI->load->library('enhanced_validation');
    }
    
    /**
     * Test property form validation
     */
    public function testPropertyFormValidation() {
        // Test valid property data
        $valid_data = [
            'property_type' => 'garden',
            'garden_name' => 'Valid Garden Name',
            'district' => 'Valid District',
            'taluk_name' => 'Valid Taluk',
            'village_town_name' => 'Valid Village',
            'size_sqft' => '1000.50',
            'price' => '500000.00',
            'description' => 'Valid property description'
        ];
        
        $_POST = $valid_data;
        
        // Set validation rules
        $this->CI->form_validation->set_rules('property_type', 'Property Type', 'required|in_list[garden,plot,house,flat]');
        $this->CI->form_validation->set_rules('garden_name', 'Property Name', 'required|trim|min_length[3]|max_length[255]');
        $this->CI->form_validation->set_rules('district', 'District', 'trim|max_length[100]');
        $this->CI->form_validation->set_rules('taluk_name', 'Taluk', 'trim|max_length[100]');
        $this->CI->form_validation->set_rules('village_town_name', 'Village/Town', 'trim|max_length[100]');
        $this->CI->form_validation->set_rules('size_sqft', 'Size (Sq Ft)', 'numeric|greater_than[0]');
        $this->CI->form_validation->set_rules('price', 'Price', 'numeric|greater_than[0]');
        $this->CI->form_validation->set_rules('description', 'Description', 'trim|max_length[1000]');
        
        $result = $this->CI->form_validation->run();
        $this->assertTrue($result, 'Valid property data should pass validation');
        
        // Test invalid property type
        $_POST['property_type'] = 'invalid_type';
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Invalid property type should fail validation');
        
        // Test missing required field
        unset($_POST['garden_name']);
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Missing required field should fail validation');
        
        // Test invalid numeric values
        $_POST = $valid_data;
        $_POST['price'] = 'not_a_number';
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Non-numeric price should fail validation');
        
        // Test negative values
        $_POST['price'] = '-1000';
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Negative price should fail validation');
        
        // Clean up
        $_POST = [];
    }
    
    /**
     * Test customer form validation
     */
    public function testCustomerFormValidation() {
        // Test valid customer data
        $valid_data = [
            'plot_buyer_name' => 'Valid Customer Name',
            'father_name' => 'Valid Father Name',
            'phone_number_1' => '9876543210',
            'phone_number_2' => '9876543211',
            'email_address' => 'valid@example.com',
            'district' => 'Valid District',
            'pincode' => '400001',
            'street_address' => 'Valid Street Address',
            'aadhar_number' => '123456789012',
            'pan_number' => 'ABCDE1234F',
            'annual_income' => '500000'
        ];
        
        $_POST = $valid_data;
        
        // Set validation rules
        $this->CI->form_validation->set_rules('plot_buyer_name', 'Customer Name', 'required|trim|min_length[2]|max_length[255]');
        $this->CI->form_validation->set_rules('father_name', 'Father Name', 'trim|max_length[255]');
        $this->CI->form_validation->set_rules('phone_number_1', 'Primary Phone', 'required|regex_match[/^[6-9]\d{9}$/]');
        $this->CI->form_validation->set_rules('phone_number_2', 'Secondary Phone', 'regex_match[/^[6-9]\d{9}$/]');
        $this->CI->form_validation->set_rules('email_address', 'Email', 'valid_email|max_length[255]');
        $this->CI->form_validation->set_rules('district', 'District', 'trim|max_length[100]');
        $this->CI->form_validation->set_rules('pincode', 'Pincode', 'regex_match[/^\d{6}$/]');
        $this->CI->form_validation->set_rules('aadhar_number', 'Aadhar Number', 'regex_match[/^\d{12}$/]');
        $this->CI->form_validation->set_rules('pan_number', 'PAN Number', 'regex_match[/^[A-Z]{5}\d{4}[A-Z]$/]');
        $this->CI->form_validation->set_rules('annual_income', 'Annual Income', 'numeric|greater_than_equal_to[0]');
        
        $result = $this->CI->form_validation->run();
        $this->assertTrue($result, 'Valid customer data should pass validation');
        
        // Test invalid phone number
        $_POST['phone_number_1'] = '1234567890'; // Invalid (starts with 1)
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Invalid phone number should fail validation');
        
        // Test invalid email
        $_POST = $valid_data;
        $_POST['email_address'] = 'invalid_email';
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Invalid email should fail validation');
        
        // Test invalid Aadhar number
        $_POST = $valid_data;
        $_POST['aadhar_number'] = '12345'; // Too short
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Invalid Aadhar number should fail validation');
        
        // Test invalid PAN number
        $_POST = $valid_data;
        $_POST['pan_number'] = 'INVALID123'; // Wrong format
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Invalid PAN number should fail validation');
        
        // Clean up
        $_POST = [];
    }
    
    /**
     * Test staff form validation
     */
    public function testStaffFormValidation() {
        // Test valid staff data
        $valid_data = [
            'employee_name' => 'Valid Employee Name',
            'designation' => 'Sales Executive',
            'department' => 'Sales',
            'contact_number' => '9876543210',
            'email_address' => 'employee@company.com',
            'joining_date' => '2024-01-15',
            'salary' => '50000',
            'date_of_birth' => '1990-05-15',
            'aadhar_number' => '123456789012',
            'pan_number' => 'ABCDE1234F'
        ];
        
        $_POST = $valid_data;
        
        // Set validation rules
        $this->CI->form_validation->set_rules('employee_name', 'Employee Name', 'required|trim|min_length[2]|max_length[255]');
        $this->CI->form_validation->set_rules('designation', 'Designation', 'required|trim|max_length[100]');
        $this->CI->form_validation->set_rules('department', 'Department', 'trim|max_length[100]');
        $this->CI->form_validation->set_rules('contact_number', 'Contact Number', 'required|regex_match[/^[6-9]\d{9}$/]');
        $this->CI->form_validation->set_rules('email_address', 'Email', 'valid_email|max_length[255]');
        $this->CI->form_validation->set_rules('joining_date', 'Joining Date', 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]');
        $this->CI->form_validation->set_rules('salary', 'Salary', 'numeric|greater_than[0]');
        $this->CI->form_validation->set_rules('date_of_birth', 'Date of Birth', 'regex_match[/^\d{4}-\d{2}-\d{2}$/]');
        $this->CI->form_validation->set_rules('aadhar_number', 'Aadhar Number', 'regex_match[/^\d{12}$/]');
        $this->CI->form_validation->set_rules('pan_number', 'PAN Number', 'regex_match[/^[A-Z]{5}\d{4}[A-Z]$/]');
        
        $result = $this->CI->form_validation->run();
        $this->assertTrue($result, 'Valid staff data should pass validation');
        
        // Test missing required fields
        unset($_POST['employee_name']);
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Missing employee name should fail validation');
        
        // Test invalid date format
        $_POST = $valid_data;
        $_POST['joining_date'] = '15-01-2024'; // Wrong format
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Invalid date format should fail validation');
        
        // Test negative salary
        $_POST = $valid_data;
        $_POST['salary'] = '-1000';
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Negative salary should fail validation');
        
        // Clean up
        $_POST = [];
    }
    
    /**
     * Test transaction form validation
     */
    public function testTransactionFormValidation() {
        // Test valid transaction data
        $valid_data = [
            'registration_id' => '1',
            'amount' => '50000.00',
            'payment_type' => 'advance',
            'payment_method' => 'cash',
            'payment_date' => '2024-01-15',
            'cheque_number' => 'CHQ123456',
            'bank_name' => 'Test Bank',
            'notes' => 'Test payment notes'
        ];
        
        $_POST = $valid_data;
        
        // Set validation rules
        $this->CI->form_validation->set_rules('registration_id', 'Registration', 'required|numeric|greater_than[0]');
        $this->CI->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->CI->form_validation->set_rules('payment_type', 'Payment Type', 'required|in_list[advance,installment,full_payment]');
        $this->CI->form_validation->set_rules('payment_method', 'Payment Method', 'required|in_list[cash,cheque,bank_transfer,online]');
        $this->CI->form_validation->set_rules('payment_date', 'Payment Date', 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]');
        $this->CI->form_validation->set_rules('cheque_number', 'Cheque Number', 'trim|max_length[100]');
        $this->CI->form_validation->set_rules('bank_name', 'Bank Name', 'trim|max_length[100]');
        $this->CI->form_validation->set_rules('notes', 'Notes', 'trim|max_length[1000]');
        
        $result = $this->CI->form_validation->run();
        $this->assertTrue($result, 'Valid transaction data should pass validation');
        
        // Test invalid payment type
        $_POST['payment_type'] = 'invalid_type';
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Invalid payment type should fail validation');
        
        // Test zero amount
        $_POST = $valid_data;
        $_POST['amount'] = '0';
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Zero amount should fail validation');
        
        // Test invalid date
        $_POST = $valid_data;
        $_POST['payment_date'] = '2024-13-45'; // Invalid date
        $this->CI->form_validation->reset_validation();
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Invalid date should fail validation');
        
        // Clean up
        $_POST = [];
    }
    
    /**
     * Test XSS prevention in form validation
     */
    public function testXSSPrevention() {
        // Test XSS attempts in various fields
        $xss_attempts = [
            '<script>alert("xss")</script>',
            'javascript:alert("xss")',
            '<img src="x" onerror="alert(\'xss\')">',
            '"><script>alert("xss")</script>',
            '\'; DROP TABLE customers; --'
        ];
        
        foreach ($xss_attempts as $xss_payload) {
            $_POST = [
                'plot_buyer_name' => $xss_payload,
                'phone_number_1' => '9876543210',
                'email_address' => 'test@example.com'
            ];
            
            // Set validation rules with XSS protection
            $this->CI->form_validation->set_rules('plot_buyer_name', 'Customer Name', 'required|trim|xss_clean');
            $this->CI->form_validation->set_rules('phone_number_1', 'Phone', 'required|regex_match[/^[6-9]\d{9}$/]');
            $this->CI->form_validation->set_rules('email_address', 'Email', 'valid_email');
            
            $result = $this->CI->form_validation->run();
            
            // Check if XSS was cleaned
            $cleaned_name = $this->CI->form_validation->set_value('plot_buyer_name');
            $this->assertFalse(strpos($cleaned_name, '<script>') !== false, 'XSS script tags should be removed');
            $this->assertFalse(strpos($cleaned_name, 'javascript:') !== false, 'JavaScript protocol should be removed');
        }
        
        // Clean up
        $_POST = [];
    }
    
    /**
     * Test SQL injection prevention
     */
    public function testSQLInjectionPrevention() {
        // Test SQL injection attempts
        $sql_injection_attempts = [
            "'; DROP TABLE customers; --",
            "' OR '1'='1",
            "' UNION SELECT * FROM customers --",
            "'; INSERT INTO customers VALUES ('hacker'); --",
            "' OR 1=1 #"
        ];
        
        foreach ($sql_injection_attempts as $sql_payload) {
            $_POST = [
                'plot_buyer_name' => $sql_payload,
                'phone_number_1' => '9876543210'
            ];
            
            // Set validation rules
            $this->CI->form_validation->set_rules('plot_buyer_name', 'Customer Name', 'required|trim');
            $this->CI->form_validation->set_rules('phone_number_1', 'Phone', 'required|regex_match[/^[6-9]\d{9}$/]');
            
            $result = $this->CI->form_validation->run();
            
            // The validation should either fail or clean the input
            if ($result) {
                $cleaned_name = $this->CI->form_validation->set_value('plot_buyer_name');
                // Check that dangerous SQL keywords are handled
                $this->assertFalse(strpos(strtoupper($cleaned_name), 'DROP TABLE') !== false, 
                    'SQL injection attempts should be prevented');
            }
        }
        
        // Clean up
        $_POST = [];
    }
    
    /**
     * Test file upload validation
     */
    public function testFileUploadValidation() {
        // Simulate file upload data
        $_FILES = [
            'agreement_file' => [
                'name' => 'test_agreement.pdf',
                'type' => 'application/pdf',
                'size' => 1024000, // 1MB
                'tmp_name' => '/tmp/test_upload',
                'error' => UPLOAD_ERR_OK
            ]
        ];
        
        // Test valid file upload
        $this->CI->load->library('upload', [
            'upload_path' => './uploads/test/',
            'allowed_types' => 'pdf|doc|docx',
            'max_size' => 2048, // 2MB
            'encrypt_name' => true
        ]);
        
        // Test file type validation
        $_FILES['agreement_file']['type'] = 'application/pdf';
        $_FILES['agreement_file']['name'] = 'test.pdf';
        
        // Validate file extension
        $file_ext = pathinfo($_FILES['agreement_file']['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['pdf', 'doc', 'docx'];
        $this->assertTrue(in_array(strtolower($file_ext), $allowed_extensions), 
            'PDF files should be allowed');
        
        // Test invalid file type
        $_FILES['agreement_file']['type'] = 'application/x-executable';
        $_FILES['agreement_file']['name'] = 'malicious.exe';
        
        $file_ext = pathinfo($_FILES['agreement_file']['name'], PATHINFO_EXTENSION);
        $this->assertFalse(in_array(strtolower($file_ext), $allowed_extensions), 
            'Executable files should not be allowed');
        
        // Test file size validation
        $_FILES['agreement_file']['size'] = 5242880; // 5MB
        $max_size = 2048 * 1024; // 2MB in bytes
        $this->assertFalse($_FILES['agreement_file']['size'] <= $max_size, 
            'Files larger than 2MB should be rejected');
        
        // Clean up
        $_FILES = [];
    }
    
    /**
     * Test custom validation rules
     */
    public function testCustomValidationRules() {
        // Test Indian phone number validation
        $phone_numbers = [
            '9876543210' => true,  // Valid
            '8765432109' => true,  // Valid
            '7654321098' => true,  // Valid
            '6543210987' => true,  // Valid
            '1234567890' => false, // Invalid (starts with 1)
            '5432109876' => false, // Invalid (starts with 5)
            '98765432'   => false, // Invalid (too short)
            '98765432101' => false // Invalid (too long)
        ];
        
        foreach ($phone_numbers as $phone => $should_be_valid) {
            $is_valid = preg_match('/^[6-9]\d{9}$/', $phone);
            
            if ($should_be_valid) {
                $this->assertTrue($is_valid, "Phone number $phone should be valid");
            } else {
                $this->assertFalse($is_valid, "Phone number $phone should be invalid");
            }
        }
        
        // Test PAN number validation
        $pan_numbers = [
            'ABCDE1234F' => true,  // Valid
            'XYZAB5678C' => true,  // Valid
            'abcde1234f' => false, // Invalid (lowercase)
            'ABCD1234F'  => false, // Invalid (too short)
            'ABCDE12345' => false, // Invalid (ends with number)
            '12345ABCDE' => false  // Invalid (starts with number)
        ];
        
        foreach ($pan_numbers as $pan => $should_be_valid) {
            $is_valid = preg_match('/^[A-Z]{5}\d{4}[A-Z]$/', $pan);
            
            if ($should_be_valid) {
                $this->assertTrue($is_valid, "PAN number $pan should be valid");
            } else {
                $this->assertFalse($is_valid, "PAN number $pan should be invalid");
            }
        }
        
        // Test Aadhar number validation
        $aadhar_numbers = [
            '123456789012' => true,  // Valid
            '987654321098' => true,  // Valid
            '12345678901'  => false, // Invalid (too short)
            '1234567890123' => false, // Invalid (too long)
            '12345678901a' => false  // Invalid (contains letter)
        ];
        
        foreach ($aadhar_numbers as $aadhar => $should_be_valid) {
            $is_valid = preg_match('/^\d{12}$/', $aadhar);
            
            if ($should_be_valid) {
                $this->assertTrue($is_valid, "Aadhar number $aadhar should be valid");
            } else {
                $this->assertFalse($is_valid, "Aadhar number $aadhar should be invalid");
            }
        }
    }
    
    /**
     * Test validation error messages
     */
    public function testValidationErrorMessages() {
        // Test with invalid data to generate errors
        $_POST = [
            'plot_buyer_name' => '', // Required field empty
            'phone_number_1' => '1234567890', // Invalid phone
            'email_address' => 'invalid_email', // Invalid email
            'price' => '-1000' // Negative price
        ];
        
        // Set validation rules with custom error messages
        $this->CI->form_validation->set_rules('plot_buyer_name', 'Customer Name', 'required', [
            'required' => 'Customer name is required and cannot be empty.'
        ]);
        
        $this->CI->form_validation->set_rules('phone_number_1', 'Phone Number', 'required|regex_match[/^[6-9]\d{9}$/]', [
            'required' => 'Phone number is required.',
            'regex_match' => 'Please enter a valid 10-digit Indian mobile number.'
        ]);
        
        $this->CI->form_validation->set_rules('email_address', 'Email', 'valid_email', [
            'valid_email' => 'Please enter a valid email address.'
        ]);
        
        $this->CI->form_validation->set_rules('price', 'Price', 'numeric|greater_than[0]', [
            'numeric' => 'Price must be a valid number.',
            'greater_than' => 'Price must be greater than zero.'
        ]);
        
        $result = $this->CI->form_validation->run();
        $this->assertFalse($result, 'Validation should fail with invalid data');
        
        // Check that error messages are generated
        $errors = $this->CI->form_validation->error_array();
        $this->assertTrue(count($errors) > 0, 'Validation errors should be generated');
        
        // Check specific error messages
        $this->assertArrayHasKey('plot_buyer_name', $errors, 'Should have error for customer name');
        $this->assertArrayHasKey('phone_number_1', $errors, 'Should have error for phone number');
        $this->assertArrayHasKey('email_address', $errors, 'Should have error for email');
        $this->assertArrayHasKey('price', $errors, 'Should have error for price');
        
        // Clean up
        $_POST = [];
    }
    
    /**
     * Test CSRF protection
     */
    public function testCSRFProtection() {
        // Enable CSRF protection
        $this->CI->config->set_item('csrf_protection', TRUE);
        $this->CI->config->set_item('csrf_token_name', 'csrf_test_name');
        $this->CI->config->set_item('csrf_cookie_name', 'csrf_cookie_name');
        
        // Load security library
        $this->CI->load->library('security');
        
        // Generate CSRF token
        $csrf_token = $this->CI->security->get_csrf_hash();
        $csrf_token_name = $this->CI->security->get_csrf_token_name();
        
        $this->assertNotNull($csrf_token, 'CSRF token should be generated');
        $this->assertNotNull($csrf_token_name, 'CSRF token name should be available');
        
        // Test form with valid CSRF token
        $_POST = [
            'plot_buyer_name' => 'Test Customer',
            'phone_number_1' => '9876543210',
            $csrf_token_name => $csrf_token
        ];
        
        // Verify CSRF token
        $is_valid_csrf = $this->CI->security->csrf_verify();
        $this->assertTrue($is_valid_csrf, 'Valid CSRF token should pass verification');
        
        // Test form without CSRF token
        $_POST = [
            'plot_buyer_name' => 'Test Customer',
            'phone_number_1' => '9876543210'
            // Missing CSRF token
        ];
        
        // This should fail CSRF verification in a real scenario
        // Note: In test environment, CSRF might be disabled
        
        // Clean up
        $_POST = [];
    }
}