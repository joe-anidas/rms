<?php
/**
 * RMS Test Suite Runner
 * Executes all tests and generates comprehensive reports
 */

// Set error reporting for testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include test bootstrap
require_once dirname(__FILE__) . '/TestBootstrap.php';

// Include all test classes
require_once dirname(__FILE__) . '/models/PropertyModelTest.php';
require_once dirname(__FILE__) . '/models/CustomerModelTest.php';
require_once dirname(__FILE__) . '/models/StaffModelTest.php';
require_once dirname(__FILE__) . '/models/TransactionModelTest.php';
require_once dirname(__FILE__) . '/models/RegistrationModelTest.php';
require_once dirname(__FILE__) . '/models/DashboardModelTest.php';
require_once dirname(__FILE__) . '/integration/PropertyControllerIntegrationTest.php';
require_once dirname(__FILE__) . '/integration/DatabaseTransactionTest.php';
require_once dirname(__FILE__) . '/validation/FormValidationTest.php';
require_once dirname(__FILE__) . '/seeders/TestDataSeeder.php';

/**
 * Enhanced Test Runner with detailed reporting
 */
class RMSTestRunner extends TestRunner {
    
    private $test_results = [];
    private $start_time;
    private $memory_usage = [];
    
    public function run() {
        $this->start_time = microtime(true);
        echo "=== RMS Comprehensive Test Suite ===\n";
        echo "Started at: " . date('Y-m-d H:i:s') . "\n\n";
        
        $total_tests = 0;
        $passed_tests = 0;
        $failed_tests = 0;
        $skipped_tests = 0;
        
        foreach ($this->test_classes as $class_name) {
            echo "Running tests for $class_name...\n";
            echo str_repeat('-', 50) . "\n";
            
            $class_results = $this->runTestClass($class_name);
            $this->test_results[$class_name] = $class_results;
            
            $total_tests += $class_results['total'];
            $passed_tests += $class_results['passed'];
            $failed_tests += $class_results['failed'];
            $skipped_tests += $class_results['skipped'];
            
            echo "\n";
        }
        
        $this->generateSummaryReport($total_tests, $passed_tests, $failed_tests, $skipped_tests);
        $this->generateDetailedReport();
        
        return $failed_tests === 0;
    }
    
    private function runTestClass($class_name) {
        $results = [
            'total' => 0,
            'passed' => 0,
            'failed' => 0,
            'skipped' => 0,
            'tests' => []
        ];
        
        $reflection = new ReflectionClass($class_name);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        
        foreach ($methods as $method) {
            if (strpos($method->getName(), 'test') === 0) {
                $results['total']++;
                
                $test_result = $this->runSingleTest($class_name, $method->getName());
                $results['tests'][$method->getName()] = $test_result;
                
                if ($test_result['status'] === 'passed') {
                    $results['passed']++;
                    echo "  ✓ " . $method->getName() . " (" . $test_result['duration'] . "ms)\n";
                } elseif ($test_result['status'] === 'failed') {
                    $results['failed']++;
                    echo "  ✗ " . $method->getName() . " - " . $test_result['error'] . "\n";
                } else {
                    $results['skipped']++;
                    echo "  ⚠ " . $method->getName() . " - SKIPPED\n";
                }
            }
        }
        
        return $results;
    }
    
    private function runSingleTest($class_name, $method_name) {
        $start_time = microtime(true);
        $start_memory = memory_get_usage();
        
        try {
            $test_instance = new $class_name();
            $test_instance->setUp();
            
            $method = new ReflectionMethod($class_name, $method_name);
            $method->invoke($test_instance);
            
            $test_instance->tearDown();
            
            $end_time = microtime(true);
            $end_memory = memory_get_usage();
            
            return [
                'status' => 'passed',
                'duration' => round(($end_time - $start_time) * 1000, 2),
                'memory_used' => $end_memory - $start_memory,
                'error' => null
            ];
            
        } catch (Exception $e) {
            $end_time = microtime(true);
            
            return [
                'status' => 'failed',
                'duration' => round(($end_time - $start_time) * 1000, 2),
                'memory_used' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function generateSummaryReport($total, $passed, $failed, $skipped) {
        $end_time = microtime(true);
        $total_duration = round(($end_time - $this->start_time) * 1000, 2);
        
        echo "\n" . str_repeat('=', 60) . "\n";
        echo "TEST SUMMARY REPORT\n";
        echo str_repeat('=', 60) . "\n";
        echo "Total Tests: $total\n";
        echo "Passed: $passed (" . round(($passed / $total) * 100, 1) . "%)\n";
        echo "Failed: $failed (" . round(($failed / $total) * 100, 1) . "%)\n";
        echo "Skipped: $skipped (" . round(($skipped / $total) * 100, 1) . "%)\n";
        echo "Total Duration: {$total_duration}ms\n";
        echo "Memory Peak: " . $this->formatBytes(memory_get_peak_usage()) . "\n";
        echo "Status: " . ($failed === 0 ? "✓ PASSED" : "✗ FAILED") . "\n";
        echo str_repeat('=', 60) . "\n\n";
    }
    
    private function generateDetailedReport() {
        echo "DETAILED TEST RESULTS\n";
        echo str_repeat('-', 60) . "\n";
        
        foreach ($this->test_results as $class_name => $class_results) {
            echo "\n$class_name:\n";
            
            foreach ($class_results['tests'] as $test_name => $test_result) {
                $status_icon = $test_result['status'] === 'passed' ? '✓' : 
                              ($test_result['status'] === 'failed' ? '✗' : '⚠');
                
                echo "  $status_icon $test_name ({$test_result['duration']}ms)";
                
                if ($test_result['status'] === 'failed') {
                    echo "\n    Error: " . $test_result['error'];
                }
                
                echo "\n";
            }
        }
        
        echo "\n" . str_repeat('-', 60) . "\n";
    }
    
    private function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    public function generateJUnitXML($filename = 'test_results.xml') {
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;
        
        $testsuites = $xml->createElement('testsuites');
        $xml->appendChild($testsuites);
        
        foreach ($this->test_results as $class_name => $class_results) {
            $testsuite = $xml->createElement('testsuite');
            $testsuite->setAttribute('name', $class_name);
            $testsuite->setAttribute('tests', $class_results['total']);
            $testsuite->setAttribute('failures', $class_results['failed']);
            $testsuite->setAttribute('skipped', $class_results['skipped']);
            
            foreach ($class_results['tests'] as $test_name => $test_result) {
                $testcase = $xml->createElement('testcase');
                $testcase->setAttribute('name', $test_name);
                $testcase->setAttribute('classname', $class_name);
                $testcase->setAttribute('time', $test_result['duration'] / 1000);
                
                if ($test_result['status'] === 'failed') {
                    $failure = $xml->createElement('failure');
                    $failure->setAttribute('message', $test_result['error']);
                    $testcase->appendChild($failure);
                }
                
                $testsuite->appendChild($testcase);
            }
            
            $testsuites->appendChild($testsuite);
        }
        
        $xml->save($filename);
        echo "JUnit XML report saved to: $filename\n";
    }
}

// Create and configure test runner
$test_runner = new RMSTestRunner();

// Add all test classes
$test_runner->addTestClass('PropertyModelTest');
$test_runner->addTestClass('CustomerModelTest');
$test_runner->addTestClass('StaffModelTest');
$test_runner->addTestClass('TransactionModelTest');
$test_runner->addTestClass('RegistrationModelTest');
$test_runner->addTestClass('DashboardModelTest');
$test_runner->addTestClass('PropertyControllerIntegrationTest');
$test_runner->addTestClass('DatabaseTransactionTest');
$test_runner->addTestClass('FormValidationTest');

// Run tests
$success = $test_runner->run();

// Generate XML report for CI/CD integration
$test_runner->generateJUnitXML('tests/reports/junit_results.xml');

// Exit with appropriate code
exit($success ? 0 : 1);