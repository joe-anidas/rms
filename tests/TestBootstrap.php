<?php
/**
 * Test Bootstrap for RMS Testing Framework
 * Initializes CodeIgniter environment for testing
 */

// Define test environment constants
define('ENVIRONMENT', 'testing');
define('BASEPATH', realpath(dirname(__FILE__) . '/../system/') . '/');
define('APPPATH', realpath(dirname(__FILE__) . '/../application/') . '/');
define('FCPATH', realpath(dirname(__FILE__) . '/../') . '/');

// Load CodeIgniter framework
require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/CodeIgniter.php';

/**
 * Base Test Case Class
 */
abstract class RMS_TestCase {
    
    protected $CI;
    protected $db;
    protected $test_data = [];
    
    public function setUp() {
        // Get CodeIgniter instance
        $this->CI =& get_instance();
        
        // Load database
        $this->CI->load->database();
        $this->db = $this->CI->db;
        
        // Start transaction for test isolation
        $this->db->trans_start();
        
        // Load required models and libraries
        $this->loadTestDependencies();
        
        // Set up test data
        $this->setUpTestData();
    }
    
    public function tearDown() {
        // Rollback transaction to clean up test data
        $this->db->trans_rollback();
        
        // Clean up test files if any
        $this->cleanUpTestFiles();
    }
    
    /**
     * Load models and libraries needed for testing
     */
    protected function loadTestDependencies() {
        $this->CI->load->model([
            'Property_model',
            'Customer_model', 
            'Staff_model',
            'Transaction_model',
            'Registration_model',
            'Dashboard_model'
        ]);
        
        $this->CI->load->library([
            'enhanced_validation',
            'database_error_handler',
            'audit_logger'
        ]);
    }
    
    /**
     * Set up test data for each test
     */
    protected function setUpTestData() {
        // Override in child classes to set up specific test data
    }
    
    /**
     * Clean up test files created during testing
     */
    protected function cleanUpTestFiles() {
        // Clean up any uploaded files or temporary files
        $upload_path = FCPATH . 'uploads/test/';
        if (is_dir($upload_path)) {
            $this->deleteDirectory($upload_path);
        }
    }
    
    /**
     * Recursively delete directory
     */
    private function deleteDirectory($dir) {
        if (!is_dir($dir)) return;
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
    
    /**
     * Assert that two values are equal
     */
    protected function assertEqual($expected, $actual, $message = '') {
        if ($expected !== $actual) {
            throw new Exception("Assertion failed: $message. Expected: " . var_export($expected, true) . ", Actual: " . var_export($actual, true));
        }
    }
    
    /**
     * Assert that value is true
     */
    protected function assertTrue($value, $message = '') {
        if (!$value) {
            throw new Exception("Assertion failed: $message. Expected true, got " . var_export($value, true));
        }
    }
    
    /**
     * Assert that value is false
     */
    protected function assertFalse($value, $message = '') {
        if ($value) {
            throw new Exception("Assertion failed: $message. Expected false, got " . var_export($value, true));
        }
    }
    
    /**
     * Assert that value is not null
     */
    protected function assertNotNull($value, $message = '') {
        if ($value === null) {
            throw new Exception("Assertion failed: $message. Expected not null");
        }
    }
    
    /**
     * Assert that array contains key
     */
    protected function assertArrayHasKey($key, $array, $message = '') {
        if (!array_key_exists($key, $array)) {
            throw new Exception("Assertion failed: $message. Array does not contain key '$key'");
        }
    }
    
    /**
     * Assert that array has specific count
     */
    protected function assertCount($expected, $array, $message = '') {
        $actual = count($array);
        if ($expected !== $actual) {
            throw new Exception("Assertion failed: $message. Expected count $expected, got $actual");
        }
    }
    
    /**
     * Create test property data
     */
    protected function createTestProperty($overrides = []) {
        $default_data = [
            'property_type' => 'garden',
            'garden_name' => 'Test Garden ' . uniqid(),
            'district' => 'Test District',
            'taluk_name' => 'Test Taluk',
            'village_town_name' => 'Test Village',
            'size_sqft' => 1000.00,
            'price' => 500000.00,
            'status' => 'unsold',
            'description' => 'Test property description'
        ];
        
        return array_merge($default_data, $overrides);
    }
    
    /**
     * Create test customer data
     */
    protected function createTestCustomer($overrides = []) {
        $default_data = [
            'plot_buyer_name' => 'Test Customer ' . uniqid(),
            'father_name' => 'Test Father',
            'phone_number_1' => '9876543210',
            'phone_number_2' => '9876543211',
            'district' => 'Test District',
            'street_address' => 'Test Address',
            'aadhar_number' => '123456789012'
        ];
        
        return array_merge($default_data, $overrides);
    }
    
    /**
     * Create test staff data
     */
    protected function createTestStaff($overrides = []) {
        $default_data = [
            'employee_name' => 'Test Staff ' . uniqid(),
            'designation' => 'Sales Executive',
            'department' => 'Sales',
            'contact_number' => '9876543210',
            'email_address' => 'test' . uniqid() . '@example.com',
            'joining_date' => date('Y-m-d')
        ];
        
        return array_merge($default_data, $overrides);
    }
    
    /**
     * Create test transaction data
     */
    protected function createTestTransaction($registration_id, $overrides = []) {
        $default_data = [
            'registration_id' => $registration_id,
            'amount' => 50000.00,
            'payment_type' => 'advance',
            'payment_method' => 'cash',
            'payment_date' => date('Y-m-d'),
            'notes' => 'Test transaction'
        ];
        
        return array_merge($default_data, $overrides);
    }
}

/**
 * Test Runner Class
 */
class TestRunner {
    
    private $test_classes = [];
    private $results = [];
    
    public function addTestClass($class_name) {
        $this->test_classes[] = $class_name;
    }
    
    public function run() {
        echo "Starting RMS Test Suite...\n\n";
        
        $total_tests = 0;
        $passed_tests = 0;
        $failed_tests = 0;
        
        foreach ($this->test_classes as $class_name) {
            echo "Running tests for $class_name...\n";
            
            $reflection = new ReflectionClass($class_name);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            
            foreach ($methods as $method) {
                if (strpos($method->getName(), 'test') === 0) {
                    $total_tests++;
                    
                    try {
                        $test_instance = new $class_name();
                        $test_instance->setUp();
                        
                        $method->invoke($test_instance);
                        
                        $test_instance->tearDown();
                        
                        echo "  ✓ " . $method->getName() . "\n";
                        $passed_tests++;
                        
                    } catch (Exception $e) {
                        echo "  ✗ " . $method->getName() . " - " . $e->getMessage() . "\n";
                        $failed_tests++;
                    }
                }
            }
            echo "\n";
        }
        
        echo "Test Results:\n";
        echo "Total: $total_tests\n";
        echo "Passed: $passed_tests\n";
        echo "Failed: $failed_tests\n";
        echo "Success Rate: " . round(($passed_tests / $total_tests) * 100, 2) . "%\n";
        
        return $failed_tests === 0;
    }
}