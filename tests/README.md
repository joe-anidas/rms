# RMS Testing Framework

This comprehensive testing framework provides unit tests, integration tests, and validation tests for the Real Estate Management System (RMS).

## Test Structure

```
tests/
├── TestBootstrap.php           # Test framework initialization
├── RunAllTests.php            # Main test runner
├── README.md                  # This file
├── models/                    # Unit tests for models
│   ├── PropertyModelTest.php
│   ├── CustomerModelTest.php
│   ├── StaffModelTest.php
│   ├── TransactionModelTest.php
│   ├── RegistrationModelTest.php
│   └── DashboardModelTest.php
├── integration/               # Integration tests
│   ├── PropertyControllerIntegrationTest.php
│   └── DatabaseTransactionTest.php
├── validation/                # Form validation tests
│   └── FormValidationTest.php
├── seeders/                   # Test data seeders
│   └── TestDataSeeder.php
└── reports/                   # Test reports (generated)
    └── junit_results.xml
```

## Running Tests

### Run All Tests
```bash
php tests/RunAllTests.php
```

### Run Specific Test Class
```bash
php -c php.ini tests/models/PropertyModelTest.php
```

### Run with Memory Limit
```bash
php -d memory_limit=512M tests/RunAllTests.php
```

## Test Categories

### 1. Unit Tests (models/)
Tests individual model methods and business logic:

- **PropertyModelTest**: Property CRUD operations, status management, staff assignments
- **CustomerModelTest**: Customer management, search functionality, analytics
- **StaffModelTest**: Staff management, assignments, performance tracking
- **TransactionModelTest**: Payment recording, balance calculations, financial reporting
- **RegistrationModelTest**: Registration workflows, status transitions
- **DashboardModelTest**: Dashboard metrics, analytics, reporting

### 2. Integration Tests (integration/)
Tests component interactions and workflows:

- **PropertyControllerIntegrationTest**: Controller-model interactions, form processing
- **DatabaseTransactionTest**: Transaction handling, rollback scenarios, data integrity

### 3. Validation Tests (validation/)
Tests form validation and security measures:

- **FormValidationTest**: Input validation, XSS prevention, SQL injection protection

### 4. Test Data Seeders (seeders/)
Provides comprehensive test data generation:

- **TestDataSeeder**: Creates realistic test data for all entities

## Test Features

### Comprehensive Coverage
- **Business Logic Testing**: All model methods and calculations
- **Workflow Testing**: Complete user workflows from start to finish
- **Error Handling**: Invalid inputs, edge cases, exception scenarios
- **Security Testing**: XSS prevention, SQL injection protection, CSRF validation
- **Performance Testing**: Large dataset handling, query optimization
- **Data Integrity**: Transaction rollbacks, foreign key constraints

### Advanced Testing Capabilities
- **Database Transactions**: Automatic rollback for test isolation
- **Test Data Generation**: Realistic test data with proper relationships
- **Memory Usage Tracking**: Monitor memory consumption during tests
- **Execution Time Measurement**: Performance profiling for each test
- **Detailed Reporting**: Comprehensive test results with error details

## Test Configuration

### Database Setup
Tests use database transactions for isolation. Each test:
1. Starts a transaction in `setUp()`
2. Executes test logic
3. Rolls back transaction in `tearDown()`

### Environment Requirements
- PHP 7.4+
- CodeIgniter 3.x
- MySQL/MariaDB
- Required PHP extensions: mysqli, json, mbstring

### Configuration Files
- `TestBootstrap.php`: Test environment initialization
- Database configuration from `application/config/database.php`
- Test-specific configurations in individual test classes

## Writing New Tests

### Creating a Model Test
```php
<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

class MyModelTest extends RMS_TestCase {
    
    protected function setUpTestData() {
        // Create test data specific to your tests
        $this->test_data_id = $this->CI->My_model->create_test_record();
    }
    
    public function testMyModelMethod() {
        // Arrange
        $input_data = ['field' => 'value'];
        
        // Act
        $result = $this->CI->My_model->my_method($input_data);
        
        // Assert
        $this->assertNotNull($result, 'Method should return a result');
        $this->assertTrue($result > 0, 'Result should be positive');
    }
}
```

### Creating an Integration Test
```php
<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

class MyIntegrationTest extends RMS_TestCase {
    
    public function testCompleteWorkflow() {
        // Test complete user workflow
        $step1_result = $this->CI->Model1->create_record($data1);
        $this->assertNotNull($step1_result);
        
        $step2_result = $this->CI->Model2->process_record($step1_result);
        $this->assertTrue($step2_result);
        
        // Verify final state
        $final_state = $this->CI->Model1->get_record($step1_result);
        $this->assertEqual('expected_status', $final_state->status);
    }
}
```

## Test Data Management

### Using Test Data Seeder
```php
// In your test class
protected function setUpTestData() {
    $seeder = new TestDataSeeder();
    $seeded_data = $seeder->seedComprehensiveTestData();
    
    $this->customer_ids = $seeded_data['customers'];
    $this->property_ids = $seeded_data['properties'];
}
```

### Creating Custom Test Data
```php
// Use helper methods from RMS_TestCase
$customer_data = $this->createTestCustomer([
    'plot_buyer_name' => 'Custom Test Customer',
    'phone_number_1' => '9876543210'
]);

$property_data = $this->createTestProperty([
    'garden_name' => 'Custom Test Property',
    'price' => 500000.00
]);
```

## Assertion Methods

### Basic Assertions
- `assertEqual($expected, $actual, $message)`
- `assertTrue($value, $message)`
- `assertFalse($value, $message)`
- `assertNotNull($value, $message)`

### Array Assertions
- `assertArrayHasKey($key, $array, $message)`
- `assertCount($expected, $array, $message)`

### Custom Assertions
```php
// Check if value is within range
$this->assertTrue($value >= $min && $value <= $max, 'Value should be in range');

// Check if array contains specific structure
$this->assertArrayHasKey('required_field', $result);
$this->assertTrue(is_numeric($result['numeric_field']));
```

## Best Practices

### Test Organization
1. **One test per method**: Each test should focus on one specific functionality
2. **Descriptive names**: Use clear, descriptive test method names
3. **Arrange-Act-Assert**: Structure tests with clear setup, execution, and verification
4. **Independent tests**: Each test should be independent and not rely on others

### Test Data
1. **Use transactions**: Always use database transactions for test isolation
2. **Clean test data**: Use realistic but clearly identifiable test data
3. **Avoid hardcoded IDs**: Use dynamic test data creation
4. **Test edge cases**: Include boundary conditions and error scenarios

### Performance
1. **Minimize database calls**: Use efficient queries and batch operations
2. **Clean up resources**: Properly clean up files, connections, and memory
3. **Monitor execution time**: Keep tests fast and efficient
4. **Use appropriate test data size**: Balance realism with performance

## Continuous Integration

### CI/CD Integration
The test suite generates JUnit XML reports compatible with most CI/CD systems:

```bash
# Run tests and generate XML report
php tests/RunAllTests.php

# XML report location
tests/reports/junit_results.xml
```

### GitHub Actions Example
```yaml
name: RMS Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '7.4'
      - name: Run Tests
        run: php tests/RunAllTests.php
      - name: Upload Test Results
        uses: actions/upload-artifact@v2
        with:
          name: test-results
          path: tests/reports/junit_results.xml
```

## Troubleshooting

### Common Issues

1. **Database Connection Errors**
   - Verify database configuration in `application/config/database.php`
   - Ensure test database exists and is accessible
   - Check database user permissions

2. **Memory Limit Errors**
   - Increase PHP memory limit: `php -d memory_limit=512M tests/RunAllTests.php`
   - Optimize test data size
   - Check for memory leaks in test code

3. **Test Failures**
   - Check test isolation (database transactions)
   - Verify test data setup
   - Review error messages and stack traces

4. **Performance Issues**
   - Profile slow tests
   - Optimize database queries
   - Reduce test data size where appropriate

### Debug Mode
Enable detailed debugging by setting environment variables:

```bash
export RMS_TEST_DEBUG=1
export RMS_TEST_VERBOSE=1
php tests/RunAllTests.php
```

## Contributing

When adding new tests:

1. Follow existing naming conventions
2. Include comprehensive test coverage
3. Add appropriate documentation
4. Test both success and failure scenarios
5. Update this README if adding new test categories

## Support

For questions or issues with the testing framework:

1. Check this documentation
2. Review existing test examples
3. Check CodeIgniter testing documentation
4. Create an issue with detailed error information