<?php
// Test script for Registration Management System
require_once 'index.php';

// Initialize CodeIgniter
$CI =& get_instance();
$CI->load->model('Registration_model');
$CI->load->model('Property_model');
$CI->load->model('Customer_model');

echo "<h2>Testing Registration Management System</h2>";

try {
    // Test 1: Check if tables exist
    echo "<h3>1. Checking Database Tables</h3>";
    
    $tables_to_check = ['registrations', 'properties', 'customers'];
    foreach ($tables_to_check as $table) {
        if ($CI->db->table_exists($table)) {
            echo "✓ Table '$table' exists<br>";
        } else {
            echo "✗ Table '$table' does not exist<br>";
        }
    }
    
    // Test 2: Test registration number generation
    echo "<h3>2. Testing Registration Number Generation</h3>";
    $reg_number = $CI->Registration_model->generate_registration_number();
    if ($reg_number) {
        echo "✓ Registration number generated: $reg_number<br>";
    } else {
        echo "✗ Failed to generate registration number<br>";
    }
    
    // Test 3: Get registration statistics
    echo "<h3>3. Testing Registration Statistics</h3>";
    $stats = $CI->Registration_model->get_registration_statistics();
    if (is_array($stats)) {
        echo "✓ Statistics retrieved successfully<br>";
        echo "Total registrations: " . (isset($stats['total_registrations']) ? $stats['total_registrations'] : 0) . "<br>";
    } else {
        echo "✗ Failed to retrieve statistics<br>";
    }
    
    // Test 4: Get available properties
    echo "<h3>4. Testing Available Properties</h3>";
    $properties = $CI->Property_model->get_properties(['status' => 'unsold']);
    if (is_array($properties)) {
        echo "✓ Found " . count($properties) . " unsold properties<br>";
    } else {
        echo "✗ Failed to retrieve properties<br>";
    }
    
    // Test 5: Get customers
    echo "<h3>5. Testing Customer Retrieval</h3>";
    $customers = $CI->Customer_model->get_all_customers();
    if (is_array($customers)) {
        echo "✓ Found " . count($customers) . " customers<br>";
    } else {
        echo "✗ Failed to retrieve customers<br>";
    }
    
    // Test 6: Get registrations
    echo "<h3>6. Testing Registration Retrieval</h3>";
    $registrations = $CI->Registration_model->get_registrations();
    if (is_array($registrations)) {
        echo "✓ Found " . count($registrations) . " registrations<br>";
    } else {
        echo "✗ Failed to retrieve registrations<br>";
    }
    
    echo "<h3>✓ All tests completed successfully!</h3>";
    echo "<p><a href='" . base_url('registrations') . "'>Go to Registration Management</a></p>";
    
} catch (Exception $e) {
    echo "<h3>✗ Error during testing:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>