<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

/**
 * Unit Tests for Registration_model
 * Tests registration creation, status management, and customer-property linking
 */
class RegistrationModelTest extends RMS_TestCase {
    
    private $customer_id;
    private $property_id;
    private $registration_id;
    
    protected function setUpTestData() {
        // Create test customer
        $customer_data = $this->createTestCustomer();
        $this->customer_id = $this->CI->Customer_model->insert_customer($customer_data);
        
        // Create test property
        $property_data = $this->createTestProperty();
        $this->property_id = $this->CI->Property_model->create_property($property_data);
        
        // Create test registration
        $this->registration_id = $this->CI->Registration_model->create_registration(
            $this->property_id, 
            $this->customer_id,
            ['total_amount' => 500000.00]
        );
    }
    
    /**
     * Test registration creation with valid data
     */
    public function testCreateRegistrationWithValidData() {
        // Create additional property for this test
        $property_data = $this->createTestProperty(['garden_name' => 'Test Registration Property']);
        $test_property_id = $this->CI->Property_model->create_property($property_data);
        
        $registration_data = [
            'total_amount' => 750000.00,
            'registration_date' => date('Y-m-d'),
            'status' => 'active'
        ];
        
        $result = $this->CI->Registration_model->create_registration(
            $test_property_id, 
            $this->customer_id, 
            $registration_data
        );
        
        $this->assertNotNull($result, 'Registration creation should return registration ID');
        $this->assertTrue(is_numeric($result), 'Registration ID should be numeric');
        
        // Verify registration was created correctly
        $created_registration = $this->CI->Registration_model->get_registration_by_id($result);
        $this->assertNotNull($created_registration, 'Created registration should be retrievable');
        $this->assertEqual($test_property_id, $created_registration->property_id);
        $this->assertEqual($this->customer_id, $created_registration->customer_id);
        $this->assertEqual($registration_data['total_amount'], $created_registration->total_amount);
        $this->assertEqual('active', $created_registration->status);
        
        // Verify property status was updated
        $updated_property = $this->CI->Property_model->get_property_by_id($test_property_id);
        $this->assertEqual('booked', $updated_property->status);
    }
    
    /**
     * Test registration creation with missing parameters
     */
    public function testCreateRegistrationWithMissingParameters() {
        // Test with missing property_id
        $result1 = $this->CI->Registration_model->create_registration(null, $this->customer_id);
        $this->assertFalse($result1, 'Registration creation should fail with missing property_id');
        
        // Test with missing customer_id
        $result2 = $this->CI->Registration_model->create_registration($this->property_id, null);
        $this->assertFalse($result2, 'Registration creation should fail with missing customer_id');
        
        // Test with non-existent property
        $result3 = $this->CI->Registration_model->create_registration(99999, $this->customer_id);
        $this->assertFalse($result3, 'Registration creation should fail with non-existent property');
        
        // Test with non-existent customer
        $result4 = $this->CI->Registration_model->create_registration($this->property_id, 99999);
        $this->assertFalse($result4, 'Registration creation should fail with non-existent customer');
    }
    
    /**
     * Test registration creation with already sold/booked property
     */
    public function testCreateRegistrationWithUnavailableProperty() {
        // Change property status to sold
        $this->CI->Property_model->change_status($this->property_id, 'sold');
        
        $result = $this->CI->Registration_model->create_registration(
            $this->property_id, 
            $this->customer_id
        );
        
        $this->assertFalse($result, 'Registration creation should fail with sold property');
    }
    
    /**
     * Test getting registrations with filters
     */
    public function testGetRegistrationsWithFilters() {
        // Create additional registrations for filtering tests
        $property_data2 = $this->createTestProperty(['garden_name' => 'Filter Test Property']);
        $property_id2 = $this->CI->Property_model->create_property($property_data2);
        
        $customer_data2 = $this->createTestCustomer(['plot_buyer_name' => 'Filter Test Customer']);
        $customer_id2 = $this->CI->Customer_model->insert_customer($customer_data2);
        
        $registration_id2 = $this->CI->Registration_model->create_registration(
            $property_id2, 
            $customer_id2,
            ['status' => 'completed']
        );
        
        // Test status filter
        $active_registrations = $this->CI->Registration_model->get_registrations(['status' => 'active']);
        $this->assertTrue(count($active_registrations) >= 1, 'Should find active registrations');
        
        $completed_registrations = $this->CI->Registration_model->get_registrations(['status' => 'completed']);
        $this->assertTrue(count($completed_registrations) >= 1, 'Should find completed registrations');
        
        // Test multiple status filter
        $multiple_status = $this->CI->Registration_model->get_registrations(['status' => ['active', 'completed']]);
        $this->assertTrue(count($multiple_status) >= 2, 'Should find registrations with multiple statuses');
        
        // Test property filter
        $property_registrations = $this->CI->Registration_model->get_registrations(['property_id' => $this->property_id]);
        $this->assertTrue(count($property_registrations) >= 1, 'Should find registrations by property');
        
        // Test customer filter
        $customer_registrations = $this->CI->Registration_model->get_registrations(['customer_id' => $this->customer_id]);
        $this->assertTrue(count($customer_registrations) >= 1, 'Should find registrations by customer');
        
        // Test search filter
        $search_results = $this->CI->Registration_model->get_registrations(['search' => 'Filter Test']);
        $this->assertTrue(count($search_results) >= 1, 'Should find registrations by search term');
        
        // Test date range filter
        $date_results = $this->CI->Registration_model->get_registrations([
            'date_from' => date('Y-m-d'),
            'date_to' => date('Y-m-d')
        ]);
        $this->assertTrue(count($date_results) >= 1, 'Should find registrations in date range');
    }
    
    /**
     * Test registration update functionality
     */
    public function testUpdateRegistration() {
        $update_data = [
            'total_amount' => 600000.00,
            'agreement_path' => '/uploads/agreements/test_agreement.pdf',
            'notes' => 'Updated registration notes'
        ];
        
        $result = $this->CI->Registration_model->update_registration($this->registration_id, $update_data);
        $this->assertTrue($result, 'Registration update should succeed');
        
        // Verify updates
        $updated_registration = $this->CI->Registration_model->get_registration_by_id($this->registration_id);
        $this->assertEqual($update_data['total_amount'], $updated_registration->total_amount);
        $this->assertEqual($update_data['agreement_path'], $updated_registration->agreement_path);
        
        // Test update with non-existent registration
        $invalid_result = $this->CI->Registration_model->update_registration(99999, $update_data);
        $this->assertFalse($invalid_result, 'Update of non-existent registration should fail');
    }
    
    /**
     * Test registration status update with workflow validation
     */
    public function testUpdateRegistrationStatus() {
        // Test valid status transition: active -> completed
        $result1 = $this->CI->Registration_model->update_status($this->registration_id, 'completed');
        $this->assertTrue($result1, 'Valid status transition should succeed');
        
        // Verify status was updated
        $updated_registration = $this->CI->Registration_model->get_registration_by_id($this->registration_id);
        $this->assertEqual('completed', $updated_registration->status);
        
        // Verify property status was updated
        $updated_property = $this->CI->Property_model->get_property_by_id($this->property_id);
        $this->assertEqual('sold', $updated_property->status);
        
        // Test valid status transition: completed -> cancelled
        $result2 = $this->CI->Registration_model->update_status($this->registration_id, 'cancelled');
        $this->assertTrue($result2, 'Valid status transition should succeed');
        
        // Test invalid status
        $result3 = $this->CI->Registration_model->update_status($this->registration_id, 'invalid_status');
        $this->assertFalse($result3, 'Invalid status should be rejected');
        
        // Test update with non-existent registration
        $result4 = $this->CI->Registration_model->update_status(99999, 'completed');
        $this->assertFalse($result4, 'Status update of non-existent registration should fail');
    }
    
    /**
     * Test registration number generation
     */
    public function testGenerateRegistrationNumber() {
        $registration_number = $this->CI->Registration_model->generate_registration_number();
        
        $this->assertNotNull($registration_number, 'Registration number should be generated');
        $this->assertTrue(strpos($registration_number, 'REG-') === 0, 'Registration number should start with REG-');
        $this->assertTrue(strlen($registration_number) >= 12, 'Registration number should have proper length');
        
        // Test uniqueness
        $registration_number2 = $this->CI->Registration_model->generate_registration_number();
        $this->assertFalse($registration_number === $registration_number2, 'Registration numbers should be unique');
        
        // Verify format (REG-YYYYMM-NNNN)
        $pattern = '/^REG-\d{6}-\d{4}$/';
        $this->assertTrue(preg_match($pattern, $registration_number), 'Registration number should match expected format');
    }
    
    /**
     * Test getting registration by property
     */
    public function testGetRegistrationByProperty() {
        $registration = $this->CI->Registration_model->get_registration_by_property($this->property_id);
        
        $this->assertNotNull($registration, 'Should find registration by property');
        $this->assertEqual($this->property_id, $registration->property_id);
        $this->assertEqual($this->customer_id, $registration->customer_id);
        
        // Test with property that has no registration
        $property_data = $this->createTestProperty(['garden_name' => 'No Registration Property']);
        $no_reg_property_id = $this->CI->Property_model->create_property($property_data);
        
        $no_registration = $this->CI->Registration_model->get_registration_by_property($no_reg_property_id);
        $this->assertEqual(null, $no_registration, 'Should return null for property with no registration');
    }
    
    /**
     * Test customer registration history
     */
    public function testGetCustomerRegistrationHistory() {
        // Create additional registration for the same customer
        $property_data2 = $this->createTestProperty(['garden_name' => 'History Test Property']);
        $property_id2 = $this->CI->Property_model->create_property($property_data2);
        
        $registration_id2 = $this->CI->Registration_model->create_registration(
            $property_id2, 
            $this->customer_id,
            ['total_amount' => 300000.00]
        );
        
        $history = $this->CI->Registration_model->get_customer_registration_history($this->customer_id);
        
        $this->assertTrue(is_array($history), 'Registration history should be array');
        $this->assertTrue(count($history) >= 2, 'Should have at least 2 registrations for this customer');
        
        // Verify history structure
        if (count($history) > 0) {
            $registration = $history[0];
            $this->assertNotNull($registration->registration_number, 'Should have registration number');
            $this->assertNotNull($registration->garden_name, 'Should have property name');
            $this->assertEqual($this->customer_id, $registration->customer_id);
        }
        
        // Test with customer that has no registrations
        $customer_data2 = $this->createTestCustomer(['plot_buyer_name' => 'No Registration Customer']);
        $customer_id2 = $this->CI->Customer_model->insert_customer($customer_data2);
        
        $empty_history = $this->CI->Registration_model->get_customer_registration_history($customer_id2);
        $this->assertEqual([], $empty_history, 'Customer with no registrations should have empty history');
    }
    
    /**
     * Test registration statistics
     */
    public function testGetRegistrationStatistics() {
        $stats = $this->CI->Registration_model->get_registration_statistics();
        
        $this->assertArrayHasKey('total_registrations', $stats, 'Should have total registrations count');
        $this->assertArrayHasKey('status_active', $stats, 'Should have active registrations count');
        $this->assertArrayHasKey('monthly_trends', $stats, 'Should have monthly trends');
        $this->assertArrayHasKey('total_value', $stats, 'Should have total value');
        $this->assertArrayHasKey('total_paid', $stats, 'Should have total paid');
        $this->assertArrayHasKey('total_pending', $stats, 'Should have total pending');
        $this->assertArrayHasKey('average_amount', $stats, 'Should have average amount');
        
        $this->assertTrue($stats['total_registrations'] >= 1, 'Should have at least one registration');
        $this->assertTrue(is_numeric($stats['total_value']), 'Total value should be numeric');
        $this->assertTrue(is_numeric($stats['average_amount']), 'Average amount should be numeric');
        $this->assertTrue(is_array($stats['monthly_trends']), 'Monthly trends should be array');
    }
    
    /**
     * Test agreement document storage
     */
    public function testStoreAgreementDocument() {
        $file_path = '/uploads/agreements/test_agreement_' . uniqid() . '.pdf';
        
        $result = $this->CI->Registration_model->store_agreement_document($this->registration_id, $file_path);
        $this->assertTrue($result, 'Agreement document storage should succeed');
        
        // Verify document path was stored
        $updated_registration = $this->CI->Registration_model->get_registration_by_id($this->registration_id);
        $this->assertEqual($file_path, $updated_registration->agreement_path);
        
        // Test with non-existent registration
        $invalid_result = $this->CI->Registration_model->store_agreement_document(99999, $file_path);
        $this->assertFalse($invalid_result, 'Document storage for non-existent registration should fail');
    }
    
    /**
     * Test registrations count functionality
     */
    public function testGetRegistrationsCount() {
        $total_count = $this->CI->Registration_model->get_registrations_count();
        $this->assertTrue($total_count >= 1, 'Should have at least one registration');
        $this->assertTrue(is_numeric($total_count), 'Count should be numeric');
        
        // Test count with filters
        $active_count = $this->CI->Registration_model->get_registrations_count(['status' => 'active']);
        $this->assertTrue(is_numeric($active_count), 'Filtered count should be numeric');
        
        $customer_count = $this->CI->Registration_model->get_registrations_count(['customer_id' => $this->customer_id]);
        $this->assertTrue($customer_count >= 1, 'Should have registrations for this customer');
    }
    
    /**
     * Test active registrations for payment recording
     */
    public function testGetActiveRegistrations() {
        $active_registrations = $this->CI->Registration_model->get_active_registrations();
        
        $this->assertTrue(is_array($active_registrations), 'Active registrations should be array');
        $this->assertTrue(count($active_registrations) >= 1, 'Should have at least one active registration');
        
        if (count($active_registrations) > 0) {
            $registration = $active_registrations[0];
            $this->assertArrayHasKey('id', $registration, 'Should have registration ID');
            $this->assertArrayHasKey('registration_number', $registration, 'Should have registration number');
            $this->assertArrayHasKey('total_amount', $registration, 'Should have total amount');
            $this->assertArrayHasKey('paid_amount', $registration, 'Should have paid amount');
            $this->assertArrayHasKey('plot_buyer_name', $registration, 'Should have customer name');
            $this->assertArrayHasKey('garden_name', $registration, 'Should have property name');
        }
    }
    
    /**
     * Test associations with details
     */
    public function testGetAssociationsWithDetails() {
        $associations = $this->CI->Registration_model->get_associations_with_details();
        
        $this->assertTrue(is_array($associations), 'Associations should be array');
        $this->assertTrue(count($associations) >= 1, 'Should have at least one association');
        
        if (count($associations) > 0) {
            $association = $associations[0];
            $this->assertNotNull($association->customer_name, 'Should have customer name');
            $this->assertNotNull($association->property_name, 'Should have property name');
            $this->assertNotNull($association->registration_number, 'Should have registration number');
        }
        
        // Test with filters
        $filtered_associations = $this->CI->Registration_model->get_associations_with_details([
            'status' => 'active',
            'date_from' => date('Y-m-d'),
            'date_to' => date('Y-m-d')
        ]);
        $this->assertTrue(is_array($filtered_associations), 'Filtered associations should be array');
    }
    
    /**
     * Test registration workflow and business logic
     */
    public function testRegistrationWorkflow() {
        // Create new property and customer for workflow test
        $workflow_property_data = $this->createTestProperty(['garden_name' => 'Workflow Test Property']);
        $workflow_property_id = $this->CI->Property_model->create_property($workflow_property_data);
        
        $workflow_customer_data = $this->createTestCustomer(['plot_buyer_name' => 'Workflow Test Customer']);
        $workflow_customer_id = $this->CI->Customer_model->insert_customer($workflow_customer_data);
        
        // Step 1: Create registration (property should become 'booked')
        $workflow_registration_id = $this->CI->Registration_model->create_registration(
            $workflow_property_id, 
            $workflow_customer_id,
            ['total_amount' => 400000.00]
        );
        
        $this->assertNotNull($workflow_registration_id, 'Registration creation should succeed');
        
        $property_after_booking = $this->CI->Property_model->get_property_by_id($workflow_property_id);
        $this->assertEqual('booked', $property_after_booking->status, 'Property should be booked after registration');
        
        // Step 2: Complete registration (property should become 'sold')
        $complete_result = $this->CI->Registration_model->update_status($workflow_registration_id, 'completed');
        $this->assertTrue($complete_result, 'Registration completion should succeed');
        
        $property_after_completion = $this->CI->Property_model->get_property_by_id($workflow_property_id);
        $this->assertEqual('sold', $property_after_completion->status, 'Property should be sold after completion');
        
        // Step 3: Cancel registration (property should become 'unsold')
        $cancel_result = $this->CI->Registration_model->update_status($workflow_registration_id, 'cancelled');
        $this->assertTrue($cancel_result, 'Registration cancellation should succeed');
        
        $property_after_cancellation = $this->CI->Property_model->get_property_by_id($workflow_property_id);
        $this->assertEqual('unsold', $property_after_cancellation->status, 'Property should be unsold after cancellation');
    }
}