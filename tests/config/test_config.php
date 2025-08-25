<?php
/**
 * Test Configuration
 * Configuration settings specific to testing environment
 */

// Test Database Configuration
$test_db_config = [
    'dsn' => '',
    'hostname' => 'localhost',
    'username' => 'test_user',
    'password' => 'test_password',
    'database' => 'rms_test_db',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => TRUE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
];

// Test Environment Settings
define('RMS_TEST_ENVIRONMENT', TRUE);
define('RMS_TEST_DEBUG', getenv('RMS_TEST_DEBUG') ?: FALSE);
define('RMS_TEST_VERBOSE', getenv('RMS_TEST_VERBOSE') ?: FALSE);

// Test Data Configuration
$test_data_config = [
    'max_test_records' => 1000,
    'cleanup_after_tests' => TRUE,
    'use_transactions' => TRUE,
    'seed_realistic_data' => FALSE
];

// Test Performance Limits
$test_performance_config = [
    'max_execution_time_ms' => 5000,  // 5 seconds per test
    'max_memory_usage_mb' => 128,     // 128MB per test
    'slow_test_threshold_ms' => 1000  // 1 second
];

// Test File Upload Configuration
$test_upload_config = [
    'upload_path' => FCPATH . 'uploads/test/',
    'allowed_types' => 'pdf|doc|docx|jpg|jpeg|png',
    'max_size' => 2048, // 2MB
    'create_test_files' => TRUE,
    'cleanup_test_files' => TRUE
];

// Test Security Configuration
$test_security_config = [
    'enable_xss_testing' => TRUE,
    'enable_sql_injection_testing' => TRUE,
    'enable_csrf_testing' => TRUE,
    'test_file_upload_security' => TRUE
];

// Export configurations
return [
    'database' => $test_db_config,
    'environment' => $test_data_config,
    'performance' => $test_performance_config,
    'upload' => $test_upload_config,
    'security' => $test_security_config
];