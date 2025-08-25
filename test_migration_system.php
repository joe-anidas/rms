<?php
/**
 * Test script for the RMS Migration System
 * 
 * This script tests the database migration and seeding functionality
 * Run this from the command line or browser to verify the system works
 */

// Include CodeIgniter bootstrap
require_once 'index.php';

echo "RMS Migration System Test\n";
echo str_repeat("=", 50) . "\n";

// Test database connection
echo "1. Testing database connection...\n";
try {
    $CI =& get_instance();
    $CI->load->database();
    
    if ($CI->db->conn_id) {
        echo "✓ Database connection successful\n";
        echo "  Database: " . $CI->db->database . "\n";
        echo "  Host: " . $CI->db->hostname . "\n";
    } else {
        echo "✗ Database connection failed\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "✗ Database connection error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test migration library
echo "\n2. Testing migration library...\n";
try {
    $CI->load->library('migration');
    echo "✓ Migration library loaded successfully\n";
} catch (Exception $e) {
    echo "✗ Migration library error: " . $e->getMessage() . "\n";
    exit(1);
}

// Check migration files
echo "\n3. Checking migration files...\n";
$migration_path = APPPATH . 'migrations/';
$migration_files = array(
    '20250824000001_create_properties_table.php',
    '20250824000002_create_staff_table.php',
    '20250824000003_create_registrations_table.php',
    '20250824000004_create_transactions_table.php',
    '20250824000005_create_property_assignments_table.php',
    '20250824000006_create_audit_logs_table.php',
    '20250824000007_enhance_customers_table.php'
);

foreach ($migration_files as $file) {
    if (file_exists($migration_path . $file)) {
        echo "✓ Found: $file\n";
    } else {
        echo "✗ Missing: $file\n";
    }
}

// Check controllers
echo "\n4. Checking migration controllers...\n";
$controller_files = array(
    'Migration_controller.php',
    'Seeder_controller.php'
);

foreach ($controller_files as $file) {
    if (file_exists(APPPATH . 'controllers/' . $file)) {
        echo "✓ Found: $file\n";
    } else {
        echo "✗ Missing: $file\n";
    }
}

// Check backup directory
echo "\n5. Checking backup directory...\n";
$backup_dir = APPPATH . 'backups/';
if (is_dir($backup_dir)) {
    echo "✓ Backup directory exists\n";
    if (is_writable($backup_dir)) {
        echo "✓ Backup directory is writable\n";
    } else {
        echo "⚠ Backup directory is not writable\n";
    }
} else {
    echo "✗ Backup directory missing\n";
}

// Test migration configuration
echo "\n6. Checking migration configuration...\n";
$CI->load->config('migration');
$migration_enabled = $CI->config->item('migration_enabled');
$migration_type = $CI->config->item('migration_type');
$migration_version = $CI->config->item('migration_version');

echo "Migration enabled: " . ($migration_enabled ? "✓ YES" : "✗ NO") . "\n";
echo "Migration type: $migration_type\n";
echo "Migration version: $migration_version\n";

echo "\n" . str_repeat("=", 50) . "\n";
echo "Migration System Test Complete\n";

// Instructions
echo "\nNext Steps:\n";
echo "1. Run migrations: http://yoursite.com/migration_controller/migrate\n";
echo "2. Seed test data: http://yoursite.com/seeder_controller/seed_all\n";
echo "3. Check status: http://yoursite.com/migration_controller/status\n";
echo "4. Validate schema: http://yoursite.com/migration_controller/validate_schema\n";
?>