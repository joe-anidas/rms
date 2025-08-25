<?php
// Simple migration runner script
// This will create all the necessary database tables

echo "<h1>Running Database Migrations</h1>";

// Load CodeIgniter
require_once 'index.php';

// Get the CI instance
$CI =& get_instance();

// Load the migration library
$CI->load->library('migration');

// Check if migrations are enabled
if (!$CI->config->item('migration_enabled')) {
    echo "<p style='color: red;'>Migrations are disabled in config!</p>";
    exit;
}

echo "<p><strong>Migration Status:</strong></p>";
echo "<ul>";
echo "<li>Enabled: " . ($CI->config->item('migration_enabled') ? 'Yes' : 'No') . "</li>";
echo "<li>Type: " . $CI->config->item('migration_type') . "</li>";
echo "<li>Target Version: " . $CI->config->item('migration_version') . "</li>";
echo "<li>Path: " . $CI->config->item('migration_path') . "</li>";
echo "</ul>";

// Check current migration version
$current_version = $CI->migration->current();
echo "<p><strong>Current Migration Version:</strong> " . $current_version . "</p>";

// Run migrations to latest
echo "<h2>Running Migrations...</h2>";
if ($CI->migration->latest()) {
    echo "<p style='color: green;'>✓ Migrations completed successfully!</p>";
    
    // Show new current version
    $new_version = $CI->migration->current();
    echo "<p><strong>New Migration Version:</strong> " . $new_version . "</p>";
    
    // Show migration status
    echo "<h3>Migration Status:</h3>";
    $CI->migration->show_migration_status();
    
} else {
    echo "<p style='color: red;'>✗ Migration failed!</p>";
    echo "<p><strong>Error:</strong> " . $CI->migration->error_string() . "</p>";
}

echo "<hr>";
echo "<p><strong>Migration process completed at:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><a href='index.php'>Go to Main Application</a></p>";
?>
