<?php
// Debug properties controller
echo "<h1>Debug Properties Controller</h1>";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Include CodeIgniter
    require_once 'index.php';
    
    echo "<p style='color: green;'>✓ CodeIgniter loaded successfully</p>";
    
    // Get CI instance
    $CI =& get_instance();
    echo "<p style='color: green;'>✓ CI instance obtained</p>";
    
    // Test database connection
    $CI->load->database();
    echo "<p style='color: green;'>✓ Database loaded</p>";
    
    // Test if we can query the database
    $query = $CI->db->query("SELECT COUNT(*) as count FROM properties");
    $result = $query->row();
    echo "<p style='color: green;'>✓ Database query successful - Properties count: " . $result->count . "</p>";
    
    // Try to load models
    try {
        $CI->load->model('Property_model');
        echo "<p style='color: green;'>✓ Property_model loaded</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error loading Property_model: " . $e->getMessage() . "</p>";
    }
    
    try {
        $CI->load->model('Staff_model');
        echo "<p style='color: green;'>✓ Staff_model loaded</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error loading Staff_model: " . $e->getMessage() . "</p>";
    }
    
    try {
        $CI->load->model('Theme_model');
        echo "<p style='color: green;'>✓ Theme_model loaded</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error loading Theme_model: " . $e->getMessage() . "</p>";
    }
    
    // Try to call Property_model methods
    if (class_exists('Property_model')) {
        try {
            $properties = $CI->Property_model->get_properties();
            echo "<p style='color: green;'>✓ Property_model->get_properties() successful - Found " . count($properties) . " properties</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Error calling get_properties(): " . $e->getMessage() . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Fatal error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>PHP Info</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Memory Limit: " . ini_get('memory_limit') . "</p>";
echo "<p>Max Execution Time: " . ini_get('max_execution_time') . "</p>";

echo "<h2>Loaded Extensions</h2>";
$extensions = get_loaded_extensions();
foreach ($extensions as $ext) {
    if (in_array($ext, ['mysqli', 'pdo', 'curl', 'json', 'mbstring'])) {
        echo "<p style='color: green;'>✓ $ext</p>";
    }
}
?>