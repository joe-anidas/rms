#!/bin/bash

# RMS Test Suite Runner Script
# Provides convenient ways to run different test suites

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Default values
TEST_TYPE="all"
VERBOSE=false
MEMORY_LIMIT="512M"
GENERATE_REPORT=true
CLEANUP=true

# Function to display usage
usage() {
    echo "Usage: $0 [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  -t, --type TYPE        Test type: all, unit, integration, validation (default: all)"
    echo "  -v, --verbose          Enable verbose output"
    echo "  -m, --memory LIMIT     Set PHP memory limit (default: 512M)"
    echo "  -r, --no-report        Skip generating XML report"
    echo "  -c, --no-cleanup       Skip cleanup after tests"
    echo "  -h, --help             Show this help message"
    echo ""
    echo "Examples:"
    echo "  $0                     # Run all tests"
    echo "  $0 -t unit             # Run only unit tests"
    echo "  $0 -t integration -v   # Run integration tests with verbose output"
    echo "  $0 -m 1G --no-cleanup # Run with 1GB memory, no cleanup"
}

# Function to print colored output
print_status() {
    local color=$1
    local message=$2
    echo -e "${color}${message}${NC}"
}

# Function to check prerequisites
check_prerequisites() {
    print_status $BLUE "Checking prerequisites..."
    
    # Check PHP
    if ! command -v php &> /dev/null; then
        print_status $RED "Error: PHP is not installed or not in PATH"
        exit 1
    fi
    
    # Check PHP version
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    print_status $GREEN "PHP Version: $PHP_VERSION"
    
    # Check if CodeIgniter is accessible
    if [ ! -f "../index.php" ]; then
        print_status $RED "Error: CodeIgniter application not found. Run from tests directory."
        exit 1
    fi
    
    # Create reports directory if it doesn't exist
    mkdir -p reports
    
    print_status $GREEN "Prerequisites check passed"
}

# Function to run specific test type
run_tests() {
    local test_type=$1
    
    print_status $BLUE "Running $test_type tests..."
    
    case $test_type in
        "unit")
            run_unit_tests
            ;;
        "integration")
            run_integration_tests
            ;;
        "validation")
            run_validation_tests
            ;;
        "all")
            run_all_tests
            ;;
        *)
            print_status $RED "Unknown test type: $test_type"
            exit 1
            ;;
    esac
}

# Function to run unit tests only
run_unit_tests() {
    print_status $YELLOW "Executing unit tests..."
    
    local php_cmd="php -d memory_limit=$MEMORY_LIMIT"
    
    if [ "$VERBOSE" = true ]; then
        php_cmd="$php_cmd -d display_errors=1"
    fi
    
    # Run individual model tests
    for test_file in models/*Test.php; do
        if [ -f "$test_file" ]; then
            print_status $BLUE "Running $(basename $test_file)..."
            $php_cmd "$test_file" || print_status $RED "Failed: $test_file"
        fi
    done
}

# Function to run integration tests only
run_integration_tests() {
    print_status $YELLOW "Executing integration tests..."
    
    local php_cmd="php -d memory_limit=$MEMORY_LIMIT"
    
    # Run integration tests
    for test_file in integration/*Test.php; do
        if [ -f "$test_file" ]; then
            print_status $BLUE "Running $(basename $test_file)..."
            $php_cmd "$test_file" || print_status $RED "Failed: $test_file"
        fi
    done
}

# Function to run validation tests only
run_validation_tests() {
    print_status $YELLOW "Executing validation tests..."
    
    local php_cmd="php -d memory_limit=$MEMORY_LIMIT"
    
    # Run validation tests
    for test_file in validation/*Test.php; do
        if [ -f "$test_file" ]; then
            print_status $BLUE "Running $(basename $test_file)..."
            $php_cmd "$test_file" || print_status $RED "Failed: $test_file"
        fi
    done
}

# Function to run all tests
run_all_tests() {
    print_status $YELLOW "Executing complete test suite..."
    
    local php_cmd="php -d memory_limit=$MEMORY_LIMIT"
    
    if [ "$VERBOSE" = true ]; then
        export RMS_TEST_VERBOSE=1
        export RMS_TEST_DEBUG=1
    fi
    
    # Run the main test runner
    $php_cmd RunAllTests.php
}

# Function to generate coverage report (if available)
generate_coverage() {
    if command -v phpunit &> /dev/null; then
        print_status $BLUE "Generating code coverage report..."
        # This would require PHPUnit setup for coverage
        print_status $YELLOW "Coverage reporting requires PHPUnit configuration"
    fi
}

# Function to cleanup test artifacts
cleanup_tests() {
    if [ "$CLEANUP" = true ]; then
        print_status $BLUE "Cleaning up test artifacts..."
        
        # Remove temporary test files
        find . -name "*.tmp" -delete 2>/dev/null || true
        find . -name "test_*.log" -delete 2>/dev/null || true
        
        # Clean up test uploads directory
        if [ -d "../uploads/test" ]; then
            rm -rf ../uploads/test/* 2>/dev/null || true
        fi
        
        print_status $GREEN "Cleanup completed"
    fi
}

# Function to display test summary
display_summary() {
    print_status $BLUE "Test execution completed"
    
    if [ -f "reports/junit_results.xml" ]; then
        print_status $GREEN "JUnit XML report generated: reports/junit_results.xml"
    fi
    
    # Display memory usage
    if command -v free &> /dev/null; then
        print_status $BLUE "System memory usage:"
        free -h | head -2
    fi
}

# Parse command line arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        -t|--type)
            TEST_TYPE="$2"
            shift 2
            ;;
        -v|--verbose)
            VERBOSE=true
            shift
            ;;
        -m|--memory)
            MEMORY_LIMIT="$2"
            shift 2
            ;;
        -r|--no-report)
            GENERATE_REPORT=false
            shift
            ;;
        -c|--no-cleanup)
            CLEANUP=false
            shift
            ;;
        -h|--help)
            usage
            exit 0
            ;;
        *)
            print_status $RED "Unknown option: $1"
            usage
            exit 1
            ;;
    esac
done

# Main execution
main() {
    print_status $GREEN "=== RMS Test Suite Runner ==="
    print_status $BLUE "Test Type: $TEST_TYPE"
    print_status $BLUE "Memory Limit: $MEMORY_LIMIT"
    print_status $BLUE "Verbose: $VERBOSE"
    echo ""
    
    # Check prerequisites
    check_prerequisites
    
    # Record start time
    START_TIME=$(date +%s)
    
    # Run tests
    run_tests "$TEST_TYPE"
    
    # Calculate execution time
    END_TIME=$(date +%s)
    DURATION=$((END_TIME - START_TIME))
    
    # Generate coverage if requested
    if [ "$GENERATE_REPORT" = true ]; then
        generate_coverage
    fi
    
    # Cleanup
    cleanup_tests
    
    # Display summary
    display_summary
    
    print_status $GREEN "Total execution time: ${DURATION} seconds"
    print_status $GREEN "=== Test Suite Completed ==="
}

# Run main function
main