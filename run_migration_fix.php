<?php
// Simple migration runner
require_once 'index.php';

// Get CodeIgniter instance
$CI =& get_instance();
$CI->load->library('migration');

echo "<h1>Running Database Migrations</h1>";

try {
    if ($CI->migration->current() === FALSE) {
        echo "<p style='color: red;'>Migration failed: " . $CI->migration->error_string() . "</p>";
    } else {
        echo "<p style='color: green;'>Migrations completed successfully!</p>";
        
        // Test database connection
        $CI->load->database();
        $query = $CI->db->query("SHOW TABLES");
        $tables = $query->result_array();
        
        echo "<h2>Created Tables:</h2>";
        echo "<ul>";
        foreach ($tables as $table) {
            $table_name = array_values($table)[0];
            echo "<li>" . $table_name . "</li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
}

echo "<p><a href='dashboard'>Go to Dashboard</a></p>";
?>