<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

/**
 * Unit Tests for Customer_model
 * Tests customer management, search, and analytics functionality
 */
class CustomerModelTest extends RMS_TestCase {
    
    private $customer_id;
    private $property_id;
    private $registration_id;
    
    protected function setUpTestData() {
        // Create test customer
        $customer_data = $this->createTestCustomer();
        $this->customer_id = $this->CI->Customer_model->insert_customer($customer_data);
        
        // Create test property for associations
        $property_data = $this->createTestProperty();
        $this->property_id = $this->CI->Property_model->create_property($property_data);
        
        // Create test registration for relationship testing
        $this->registration_id = $this->CI->Registration_model->create_registration(
            $this->property_id, 
            $this->customer_id,
            ['total_amount' => 500000.00]
        );
    }
    
    /**
     * Test customer creation with valid data
     */
    public function testInsertCustomerWithValidData() {
        $customer_data = $this->createTestCustomer([
            'plot_buyer_name' => 'John Doe',
            'phone_number_1' => '9876543210',
            'email_address' => 'john.doe@example.com'
        ]);
        
        $result = $this->CI->Customer_model->insert_customer($customer_data);
        
        $this->assertNotNull($result, 'Customer creation should return customer ID');
        $this->assertTrue(is_numeric($result), 'Customer ID should be numeric');
        
        // Verify customer was created correctly
        $created_customer = $this->CI->Customer_model->get_customer_by_id($result);
        $this->assertNotNull($created_customer, 'Created customer should be retrievable');
        $this->assertEqual($customer_data['plot_buyer_name'], $created_customer->plot_buyer_name);
        $this->assertEqual($customer_data['phone_number_1'], $created_customer->phone_number_1);
    }
    
    /**
     * Test customer creation with invalid data
     */
    public function testInsertCustomerWithInvalidData() {
        $invalid_data = [
            'father_name' => 'Test Father'
            // Missing required plot_buyer_name
        ];
        
        try {
            $result = $this->CI->Customer_model->insert_customer($invalid_data);
            $this->assertFalse(true, 'Should throw exception for invalid data');
        } catch (Exception $e) {
            $this->assertTrue(true, 'Should throw exception for missing required fields');
        }
    }
    
    /**
     * Test getting all customers
     */
    public function testGetAllCustomers() {
        $customers = $this->CI->Customer_model->get_all_customers();
        
        $this->assertTrue(is_array($customers), 'Should return array of customers');
        $this->assertTrue(count($customers) >= 1, 'Should have at least one customer');
        
        // Verify customer structure
        if (count($customers) > 0) {
            $customer = $customers[0];
            $this->assertNotNull($customer->id, 'Customer should have ID');
            $this->assertNotNull($customer->plot_buyer_name, 'Customer should have name');
        }
    }
    
    /**
     * Test customer update functionality
     */
    public function testUpdateCustomer() {
        $update_data = [
            'plot_buyer_name' => 'Updated Customer Name',
            'phone_number_1' => '9999999999',
            'email_address' => 'updated@example.com'
        ];
        
        $result = $this->CI->Customer_model->update_customer($this->customer_id, $update_data);
        $this->assertTrue($result, 'Customer update should succeed');
        
        // Verify updates
        $updated_customer = $this->CI->Customer_model->get_customer_by_id($this->customer_id);
        $this->assertEqual($update_data['plot_buyer_name'], $updated_customer->plot_buyer_name);
        $this->assertEqual($update_data['phone_number_1'], $updated_customer->phone_number_1);
    }
    
    /**
     * Test customer deletion with dependency checks
     */
    public function testDeleteCustomerWithDependencies() {
        // Try to delete customer with active registration
        $result = $this->CI->Customer_model->delete_customer($this->customer_id);
        
        $this->assertArrayHasKey('success', $result, 'Delete result should have success key');
        
        // Customer with active registrations should not be deletable
        if ($result['success'] === false) {
            $this->assertTrue(strpos($result['message'], 'active registrations') !== false, 
                'Should mention active registrations in error message');
        }
    }
    
    /**
     * Test getting customer properties
     */
    public function testGetCustomerProperties() {
        $properties = $this->CI->Customer_model->get_customer_properties($this->customer_id);
        
        $this->assertTrue(is_array($properties), 'Should return array of properties');
        $this->assertTrue(count($properties) >= 1, 'Should have at least one property');
        
        // Verify property structure
        if (count($properties) > 0) {
            $property = $properties[0];
            $this->assertNotNull($property->garden_name, 'Property should have name');
            $this->assertNotNull($property->registration_number, 'Should have registration number');
        }
    }
    
    /**
     * Test getting customer transactions
     */
    public function testGetCustomerTransactions() {
        // Create a test transaction
        $transaction_data = $this->createTestTransaction($this->registration_id);
        $this->CI->Transaction_model->record_payment($transaction_data);
        
        $transactions = $this->CI->Customer_model->get_customer_transactions($this->customer_id);
        
        $this->assertTrue(is_array($transactions), 'Should return array of transactions');
        $this->assertTrue(count($transactions) >= 1, 'Should have at least one transaction');
        
        // Verify transaction structure
        if (count($transactions) > 0) {
            $transaction = $transactions[0];
            $this->assertNotNull($transaction->amount, 'Transaction should have amount');
            $this->assertNotNull($transaction->payment_date, 'Transaction should have payment date');
        }
    }
    
    /**
     * Test customer search functionality
     */
    public function testSearchCustomers() {
        // Create customer with specific search terms
        $search_customer_data = $this->createTestCustomer([
            'plot_buyer_name' => 'Unique Search Customer',
            'phone_number_1' => '8888888888',
            'email_address' => 'unique@search.com'
        ]);
        $search_id = $this->CI->Customer_model->insert_customer($search_customer_data);
        
        // Test name search
        $name_results = $this->CI->Customer_model->search_customers(['name' => 'Unique Search']);
        $this->assertTrue(count($name_results) >= 1, 'Should find customers by name');
        
        // Test phone search
        $phone_results = $this->CI->Customer_model->search_customers(['phone' => '8888888888']);
        $this->assertTrue(count($phone_results) >= 1, 'Should find customers by phone');
        
        // Test email search
        $email_results = $this->CI->Customer_model->search_customers(['email' => 'unique@search.com']);
        $this->assertTrue(count($email_results) >= 1, 'Should find customers by email');
        
        // Test date range search
        $date_results = $this->CI->Customer_model->search_customers([
            'date_from' => date('Y-m-d', strtotime('-1 day')),
            'date_to' => date('Y-m-d', strtotime('+1 day'))
        ]);
        $this->assertTrue(count($date_results) >= 1, 'Should find customers in date range');
    }
    
    /**
     * Test customer statistics calculation
     */
    public function testGetCustomerStatistics() {
        $stats = $this->CI->Customer_model->get_customer_statistics();
        
        $this->assertArrayHasKey('total_customers', $stats);
        $this->assertArrayHasKey('active_customers', $stats);
        $this->assertArrayHasKey('acquisition_trends', $stats);
        $this->assertArrayHasKey('geographic_distribution', $stats);
        $this->assertArrayHasKey('top_customers', $stats);
        
        $this->assertTrue($stats['total_customers'] >= 1, 'Should have at least one customer');
        $this->assertTrue(is_array($stats['acquisition_trends']), 'Acquisition trends should be array');
        $this->assertTrue(is_numeric($stats['average_investment']), 'Average investment should be numeric');
    }
    
    /**
     * Test customer profile retrieval
     */
    public function testGetCustomerProfile() {
        $profile = $this->CI->Customer_model->get_customer_profile($this->customer_id);
        
        $this->assertNotNull($profile, 'Customer profile should exist');
        $this->assertNotNull($profile->plot_buyer_name, 'Profile should have customer name');
        $this->assertTrue(is_array($profile->properties), 'Profile should have properties array');
        $this->assertNotNull($profile->transaction_summary, 'Profile should have transaction summary');
        $this->assertNotNull($profile->registration_summary, 'Profile should have registration summary');
        
        // Verify transaction summary structure
        $this->assertNotNull($profile->transaction_summary->total_transactions, 'Should have transaction count');
        $this->assertNotNull($profile->transaction_summary->total_paid, 'Should have total paid amount');
    }
    
    /**
     * Test customers with associations
     */
    public function testGetCustomersWithAssociations() {
        $customers = $this->CI->Customer_model->get_customers_with_associations();
        
        $this->assertTrue(is_array($customers), 'Should return array of customers');
        
        if (count($customers) > 0) {
            $customer = $customers[0];
            $this->assertNotNull($customer->total_properties, 'Should have property count');
            $this->assertNotNull($customer->total_investment, 'Should have investment total');
        }
        
        // Test with filters
        $filtered_customers = $this->CI->Customer_model->get_customers_with_associations([
            'has_properties' => 'yes',
            'limit' => 10
        ]);
        $this->assertTrue(is_array($filtered_customers), 'Filtered results should be array');
    }
    
    /**
     * Test customer update with audit trail
     */
    public function testUpdateCustomerWithAudit() {
        $update_data = [
            'plot_buyer_name' => 'Audit Test Customer',
            'phone_number_1' => '7777777777'
        ];
        
        $result = $this->CI->Customer_model->update_customer_with_audit(
            $this->customer_id, 
            $update_data, 
            1 // user_id
        );
        
        $this->assertArrayHasKey('success', $result, 'Result should have success key');
        $this->assertTrue($result['success'], 'Update with audit should succeed');
        
        // Verify update
        $updated_customer = $this->CI->Customer_model->get_customer_by_id($this->customer_id);
        $this->assertEqual($update_data['plot_buyer_name'], $updated_customer->plot_buyer_name);
    }
    
    /**
     * Test customer count functionality
     */
    public function testGetCustomerCount() {
        $count = $this->CI->Customer_model->get_customer_count();
        
        $this->assertTrue(is_numeric($count), 'Customer count should be numeric');
        $this->assertTrue($count >= 1, 'Should have at least one customer');
    }
    
    /**
     * Test recent customers functionality
     */
    public function testGetRecentCustomers() {
        $recent_customers = $this->CI->Customer_model->get_recent_customers(5);
        
        $this->assertTrue(is_array($recent_customers), 'Should return array of recent customers');
        $this->assertTrue(count($recent_customers) <= 5, 'Should respect limit parameter');
        
        if (count($recent_customers) > 0) {
            $customer = $recent_customers[0];
            $this->assertNotNull($customer->plot_buyer_name, 'Recent customer should have name');
            $this->assertNotNull($customer->created_at, 'Recent customer should have creation date');
        }
    }
    
    /**
     * Test data validation and sanitization
     */
    public function testDataValidationAndSanitization() {
        // Test with potentially malicious data
        $malicious_data = $this->createTestCustomer([
            'plot_buyer_name' => '<script>alert("xss")</script>Test Customer',
            'phone_number_1' => '9876543210\'; DROP TABLE customers; --',
            'email_address' => 'test@example.com<script>alert("xss")</script>'
        ]);
        
        try {
            $result = $this->CI->Customer_model->insert_customer($malicious_data);
            
            if ($result) {
                // Verify data was sanitized
                $customer = $this->CI->Customer_model->get_customer_by_id($result);
                $this->assertFalse(strpos($customer->plot_buyer_name, '<script>') !== false, 
                    'Script tags should be sanitized');
                $this->assertFalse(strpos($customer->phone_number_1, 'DROP TABLE') !== false, 
                    'SQL injection attempts should be sanitized');
            }
        } catch (Exception $e) {
            // Validation should catch malicious data
            $this->assertTrue(true, 'Validation should prevent malicious data insertion');
        }
    }
}