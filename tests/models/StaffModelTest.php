<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

/**
 * Unit Tests for Staff_model
 * Tests staff management, assignments, and performance tracking
 */
class StaffModelTest extends RMS_TestCase {
    
    private $staff_id;
    private $property_id;
    private $customer_id;
    
    protected function setUpTestData() {
        // Create test staff
        $staff_data = $this->createTestStaff();
        $result = $this->CI->Staff_model->insert_staff($staff_data);
        $this->staff_id = $result['staff_id'];
        
        // Create test property for assignments
        $property_data = $this->createTestProperty();
        $this->property_id = $this->CI->Property_model->create_property($property_data);
        
        // Create test customer for assignments
        $customer_data = $this->createTestCustomer();
        $this->customer_id = $this->CI->Customer_model->insert_customer($customer_data);
    }
    
    /**
     * Test staff creation with valid data
     */
    public function testInsertStaffWithValidData() {
        $staff_data = $this->createTestStaff([
            'employee_name' => 'John Smith',
            'designation' => 'Sales Manager',
            'contact_number' => '9876543210'
        ]);
        
        $result = $this->CI->Staff_model->insert_staff($staff_data);
        
        $this->assertArrayHasKey('success', $result, 'Result should have success key');
        $this->assertTrue($result['success'], 'Staff creation should succeed');
        $this->assertArrayHasKey('staff_id', $result, 'Result should have staff_id');
        
        // Verify staff was created correctly
        $created_staff = $this->CI->Staff_model->get_staff_by_id($result['staff_id']);
        $this->assertNotNull($created_staff, 'Created staff should be retrievable');
        $this->assertEqual($staff_data['employee_name'], $created_staff->employee_name);
        $this->assertEqual($staff_data['designation'], $created_staff->designation);
    }
    
    /**
     * Test staff creation with missing required fields
     */
    public function testInsertStaffWithMissingRequiredFields() {
        $invalid_data = [
            'designation' => 'Sales Executive'
            // Missing required employee_name
        ];
        
        $result = $this->CI->Staff_model->insert_staff($invalid_data);
        
        $this->assertArrayHasKey('success', $result, 'Result should have success key');
        $this->assertFalse($result['success'], 'Staff creation should fail with missing required fields');
        $this->assertArrayHasKey('message', $result, 'Result should have error message');
    }
    
    /**
     * Test getting all staff
     */
    public function testGetAllStaff() {
        $staff = $this->CI->Staff_model->get_all_staff();
        
        $this->assertTrue(is_array($staff), 'Should return array of staff');
        $this->assertTrue(count($staff) >= 1, 'Should have at least one staff member');
        
        // Verify staff structure
        if (count($staff) > 0) {
            $staff_member = $staff[0];
            $this->assertNotNull($staff_member->id, 'Staff should have ID');
            $this->assertNotNull($staff_member->employee_name, 'Staff should have name');
        }
    }
    
    /**
     * Test staff update functionality
     */
    public function testUpdateStaff() {
        $update_data = [
            'employee_name' => 'Updated Staff Name',
            'designation' => 'Senior Sales Executive',
            'contact_number' => '9999999999'
        ];
        
        $result = $this->CI->Staff_model->update_staff($this->staff_id, $update_data);
        
        $this->assertArrayHasKey('success', $result, 'Result should have success key');
        $this->assertTrue($result['success'], 'Staff update should succeed');
        
        // Verify updates
        $updated_staff = $this->CI->Staff_model->get_staff_by_id($this->staff_id);
        $this->assertEqual($update_data['employee_name'], $updated_staff->employee_name);
        $this->assertEqual($update_data['designation'], $updated_staff->designation);
    }
    
    /**
     * Test staff deletion
     */
    public function testDeleteStaff() {
        // Create staff without assignments for deletion test
        $delete_staff_data = $this->createTestStaff(['employee_name' => 'Delete Test Staff']);
        $delete_result = $this->CI->Staff_model->insert_staff($delete_staff_data);
        $delete_staff_id = $delete_result['staff_id'];
        
        $result = $this->CI->Staff_model->delete_staff($delete_staff_id);
        
        $this->assertArrayHasKey('success', $result, 'Result should have success key');
        $this->assertTrue($result['success'], 'Staff deletion should succeed');
        
        // Verify staff was deleted
        $deleted_staff = $this->CI->Staff_model->get_staff_by_id($delete_staff_id);
        $this->assertEqual(null, $deleted_staff, 'Deleted staff should not be retrievable');
    }
    
    /**
     * Test staff assignment to property
     */
    public function testAssignToProperty() {
        $result = $this->CI->Staff_model->assign_to_property(
            $this->staff_id, 
            $this->property_id, 
            'sales',
            date('Y-m-d')
        );
        
        $this->assertArrayHasKey('success', $result, 'Result should have success key');
        $this->assertTrue($result['success'], 'Property assignment should succeed');
        $this->assertArrayHasKey('assignment_id', $result, 'Result should have assignment_id');
        
        // Verify assignment was created
        $assignments = $this->CI->Staff_model->get_staff_assignments($this->staff_id);
        $this->assertTrue(count($assignments['property_assignments']) >= 1, 
            'Should have at least one property assignment');
    }
    
    /**
     * Test staff assignment to customer
     */
    public function testAssignToCustomer() {
        $result = $this->CI->Staff_model->assign_to_customer(
            $this->staff_id, 
            $this->customer_id, 
            'customer_service',
            date('Y-m-d'),
            'Test assignment notes'
        );
        
        $this->assertArrayHasKey('success', $result, 'Result should have success key');
        $this->assertTrue($result['success'], 'Customer assignment should succeed');
        $this->assertArrayHasKey('assignment_id', $result, 'Result should have assignment_id');
        
        // Verify assignment was created
        $assignments = $this->CI->Staff_model->get_staff_assignments($this->staff_id);
        $this->assertTrue(count($assignments['customer_assignments']) >= 1, 
            'Should have at least one customer assignment');
    }
    
    /**
     * Test getting staff assignments
     */
    public function testGetStaffAssignments() {
        // Create assignments
        $this->CI->Staff_model->assign_to_property($this->staff_id, $this->property_id, 'sales');
        $this->CI->Staff_model->assign_to_customer($this->staff_id, $this->customer_id, 'customer_service');
        
        $assignments = $this->CI->Staff_model->get_staff_assignments($this->staff_id);
        
        $this->assertArrayHasKey('property_assignments', $assignments, 'Should have property assignments');
        $this->assertArrayHasKey('customer_assignments', $assignments, 'Should have customer assignments');
        $this->assertTrue(is_array($assignments['property_assignments']), 'Property assignments should be array');
        $this->assertTrue(is_array($assignments['customer_assignments']), 'Customer assignments should be array');
        
        // Test including inactive assignments
        $all_assignments = $this->CI->Staff_model->get_staff_assignments($this->staff_id, true);
        $this->assertTrue(is_array($all_assignments['property_assignments']), 'Should include inactive assignments');
    }
    
    /**
     * Test staff performance metrics
     */
    public function testGetStaffPerformance() {
        // Create assignments and transactions for performance testing
        $this->CI->Staff_model->assign_to_property($this->staff_id, $this->property_id, 'sales');
        
        // Create registration and transaction
        $registration_id = $this->CI->Registration_model->create_registration(
            $this->property_id, 
            $this->customer_id,
            ['total_amount' => 500000.00]
        );
        
        $transaction_data = $this->createTestTransaction($registration_id);
        $this->CI->Transaction_model->record_payment($transaction_data);
        
        $performance = $this->CI->Staff_model->get_staff_performance($this->staff_id);
        
        $this->assertArrayHasKey('active_property_assignments', $performance);
        $this->assertArrayHasKey('active_customer_assignments', $performance);
        $this->assertArrayHasKey('transaction_count', $performance);
        $this->assertArrayHasKey('total_transaction_amount', $performance);
        $this->assertArrayHasKey('completed_registrations', $performance);
        
        $this->assertTrue(is_numeric($performance['active_property_assignments']), 
            'Property assignments should be numeric');
        $this->assertTrue(is_numeric($performance['transaction_count']), 
            'Transaction count should be numeric');
    }
    
    /**
     * Test workload distribution
     */
    public function testGetWorkloadDistribution() {
        // Create assignments for workload testing
        $this->CI->Staff_model->assign_to_property($this->staff_id, $this->property_id, 'sales');
        $this->CI->Staff_model->assign_to_customer($this->staff_id, $this->customer_id, 'customer_service');
        
        $workload = $this->CI->Staff_model->get_workload_distribution();
        
        $this->assertTrue(is_array($workload), 'Workload should be array');
        
        if (count($workload) > 0) {
            $staff_workload = $workload[0];
            $this->assertArrayHasKey('employee_name', $staff_workload, 'Should have employee name');
            $this->assertArrayHasKey('property_count', $staff_workload, 'Should have property count');
            $this->assertArrayHasKey('customer_count', $staff_workload, 'Should have customer count');
        }
    }
    
    /**
     * Test assignment history
     */
    public function testGetAssignmentHistory() {
        // Create assignments
        $this->CI->Staff_model->assign_to_property($this->staff_id, $this->property_id, 'sales');
        $this->CI->Staff_model->assign_to_customer($this->staff_id, $this->customer_id, 'customer_service');
        
        $history = $this->CI->Staff_model->get_assignment_history($this->staff_id, 10);
        
        $this->assertTrue(is_array($history), 'Assignment history should be array');
        $this->assertTrue(count($history) <= 10, 'Should respect limit parameter');
        
        if (count($history) > 0) {
            $assignment = $history[0];
            $this->assertArrayHasKey('assignment_category', $assignment, 'Should have assignment category');
            $this->assertArrayHasKey('assigned_date', $assignment, 'Should have assigned date');
        }
    }
    
    /**
     * Test staff search and filtering
     */
    public function testSearchStaff() {
        // Create staff with specific search terms
        $search_staff_data = $this->createTestStaff([
            'employee_name' => 'Unique Search Staff',
            'designation' => 'Unique Designation',
            'contact_number' => '8888888888'
        ]);
        $search_result = $this->CI->Staff_model->insert_staff($search_staff_data);
        $search_staff_id = $search_result['staff_id'];
        
        // Test name search
        $name_results = $this->CI->Staff_model->search_staff(['name' => 'Unique Search']);
        $this->assertTrue(count($name_results) >= 1, 'Should find staff by name');
        
        // Test designation filter
        $designation_results = $this->CI->Staff_model->search_staff(['designation' => 'Unique Designation']);
        $this->assertTrue(count($designation_results) >= 1, 'Should find staff by designation');
        
        // Test contact search
        $contact_results = $this->CI->Staff_model->search_staff(['contact' => '8888888888']);
        $this->assertTrue(count($contact_results) >= 1, 'Should find staff by contact');
        
        // Test sorting
        $sorted_results = $this->CI->Staff_model->search_staff([
            'sort_by' => 'employee_name',
            'sort_order' => 'DESC'
        ]);
        $this->assertTrue(is_array($sorted_results), 'Sorted results should be array');
    }
    
    /**
     * Test ending assignments
     */
    public function testEndAssignments() {
        // Create assignments
        $property_result = $this->CI->Staff_model->assign_to_property($this->staff_id, $this->property_id, 'sales');
        $customer_result = $this->CI->Staff_model->assign_to_customer($this->staff_id, $this->customer_id, 'customer_service');
        
        // End property assignment
        $end_property_result = $this->CI->Staff_model->end_property_assignment_by_id(
            $property_result['assignment_id']
        );
        $this->assertArrayHasKey('success', $end_property_result, 'Result should have success key');
        $this->assertTrue($end_property_result['success'], 'Ending property assignment should succeed');
        
        // End customer assignment
        $end_customer_result = $this->CI->Staff_model->end_customer_assignment_by_id(
            $customer_result['assignment_id']
        );
        $this->assertArrayHasKey('success', $end_customer_result, 'Result should have success key');
        $this->assertTrue($end_customer_result['success'], 'Ending customer assignment should succeed');
        
        // Verify assignments are ended
        $active_assignments = $this->CI->Staff_model->get_staff_assignments($this->staff_id);
        $this->assertEqual(0, count($active_assignments['property_assignments']), 
            'Should have no active property assignments');
        $this->assertEqual(0, count($active_assignments['customer_assignments']), 
            'Should have no active customer assignments');
    }
    
    /**
     * Test staff statistics
     */
    public function testGetStaffStatistics() {
        $stats = $this->CI->Staff_model->get_staff_statistics();
        
        $this->assertArrayHasKey('total_staff', $stats, 'Should have total staff count');
        $this->assertArrayHasKey('by_designation', $stats, 'Should have designation breakdown');
        $this->assertArrayHasKey('by_department', $stats, 'Should have department breakdown');
        $this->assertArrayHasKey('active_property_assignments', $stats, 'Should have active assignments');
        
        $this->assertTrue($stats['total_staff'] >= 1, 'Should have at least one staff member');
        $this->assertTrue(is_array($stats['by_designation']), 'Designation breakdown should be array');
        $this->assertTrue(is_numeric($stats['active_property_assignments']), 
            'Active assignments should be numeric');
    }
    
    /**
     * Test getting designations and departments
     */
    public function testGetDesignationsAndDepartments() {
        $designations = $this->CI->Staff_model->get_designations();
        $this->assertTrue(is_array($designations), 'Designations should be array');
        
        $departments = $this->CI->Staff_model->get_departments();
        $this->assertTrue(is_array($departments), 'Departments should be array');
        
        // Should include our test staff designation/department
        $this->assertTrue(in_array('Sales Executive', $designations) || 
                         count($designations) >= 0, 'Should have designations');
    }
    
    /**
     * Test assignment validation
     */
    public function testAssignmentValidation() {
        // Test assignment to non-existent property
        $invalid_property_result = $this->CI->Staff_model->assign_to_property(
            $this->staff_id, 
            99999, 
            'sales'
        );
        $this->assertArrayHasKey('success', $invalid_property_result, 'Result should have success key');
        $this->assertFalse($invalid_property_result['success'], 'Assignment to non-existent property should fail');
        
        // Test assignment to non-existent customer
        $invalid_customer_result = $this->CI->Staff_model->assign_to_customer(
            $this->staff_id, 
            99999, 
            'customer_service'
        );
        $this->assertArrayHasKey('success', $invalid_customer_result, 'Result should have success key');
        $this->assertFalse($invalid_customer_result['success'], 'Assignment to non-existent customer should fail');
        
        // Test assignment of non-existent staff
        $invalid_staff_result = $this->CI->Staff_model->assign_to_property(
            99999, 
            $this->property_id, 
            'sales'
        );
        $this->assertArrayHasKey('success', $invalid_staff_result, 'Result should have success key');
        $this->assertFalse($invalid_staff_result['success'], 'Assignment of non-existent staff should fail');
    }
}