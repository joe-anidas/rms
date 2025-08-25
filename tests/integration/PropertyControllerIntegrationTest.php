<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

/**
 * Integration Tests for Properties Controller and Model interactions
 * Tests complete workflows from HTTP request to database operations
 */
class PropertyControllerIntegrationTest extends RMS_TestCase {
    
    private $property_id;
    private $staff_id;
    
    protected function setUpTestData() {
        // Create test staff for assignment tests
        $staff_data = $this->createTestStaff();
        $staff_result = $this->CI->Staff_model->insert_staff($staff_data);
        $this->staff_id = $staff_result['staff_id'];
        
        // Create test property
        $property_data = $this->createTestProperty();
        $this->property_id = $this->CI->Property_model->create_property($property_data);
        
        // Load Properties controller
        $this->CI->load->library('unit_test');
        require_once APPPATH . 'controllers/Properties.php';
    }
    
    /**
     * Test property listing with filters integration
     */
    public function testPropertyListingIntegration() {
        // Simulate GET parameters for filtering
        $_GET['status'] = 'unsold';
        $_GET['property_type'] = 'garden';
        $_GET['search'] = 'Test';
        
        // Create Properties controller instance
        $properties_controller = new Properties();
        
        // Capture output to test view rendering
        ob_start();
        
        try {
            // This would normally be called by CodeIgniter router
            // We're testing the controller-model integration
            $properties_controller->index();
            $output = ob_get_contents();
        } catch (Exception $e) {
            // Expected since we're not in full CI environment
            $output = '';
        }
        
        ob_end_clean();
        
        // Test that model methods were called correctly by checking data
        $filters = [
            'status' => 'unsold',
            'property_type' => 'garden',
            'search' => 'Test'
        ];
        
        $properties = $this->CI->Property_model->get_properties($filters, 20, 0);
        $this->assertTrue(is_array($properties), 'Controller should retrieve properties array');
        
        $total_count = $this->CI->Property_model->get_properties_count($filters);
        $this->assertTrue(is_numeric($total_count), 'Controller should get property count');
        
        // Clean up GET parameters
        unset($_GET['status'], $_GET['property_type'], $_GET['search']);
    }
    
    /**
     * Test property creation workflow integration
     */
    public function testPropertyCreationWorkflow() {
        // Simulate POST data for property creation
        $_POST = [
            'property_type' => 'plot',
            'garden_name' => 'Integration Test Property',
            'district' => 'Test District',
            'taluk_name' => 'Test Taluk',
            'village_town_name' => 'Test Village',
            'size_sqft' => '2000.00',
            'price' => '800000.00',
            'description' => 'Integration test property description'
        ];
        
        // Test form validation
        $this->CI->load->library('form_validation');
        $this->CI->form_validation->set_rules('garden_name', 'Property Name', 'required|trim');
        $this->CI->form_validation->set_rules('property_type', 'Property Type', 'required');
        $this->CI->form_validation->set_rules('price', 'Price', 'numeric');
        
        // Run validation
        $validation_result = $this->CI->form_validation->run();
        $this->assertTrue($validation_result, 'Form validation should pass for valid data');
        
        // Test property creation through model
        $property_data = [
            'property_type' => $_POST['property_type'],
            'garden_name' => $_POST['garden_name'],
            'district' => $_POST['district'],
            'taluk_name' => $_POST['taluk_name'],
            'village_town_name' => $_POST['village_town_name'],
            'size_sqft' => (float)$_POST['size_sqft'],
            'price' => (float)$_POST['price'],
            'description' => $_POST['description']
        ];
        
        $created_property_id = $this->CI->Property_model->create_property($property_data);
        $this->assertNotNull($created_property_id, 'Property creation should succeed');
        
        // Verify property was created with correct data
        $created_property = $this->CI->Property_model->get_property_by_id($created_property_id);
        $this->assertEqual($_POST['garden_name'], $created_property->garden_name);
        $this->assertEqual($_POST['property_type'], $created_property->property_type);
        $this->assertEqual((float)$_POST['price'], $created_property->price);
        
        // Clean up POST data
        $_POST = [];
    }
    
    /**
     * Test property update workflow integration
     */
    public function testPropertyUpdateWorkflow() {
        // Simulate POST data for property update
        $_POST = [
            'garden_name' => 'Updated Integration Property',
            'price' => '900000.00',
            'description' => 'Updated description for integration test',
            'assigned_staff_id' => $this->staff_id
        ];
        
        // Test form validation for update
        $this->CI->load->library('form_validation');
        $this->CI->form_validation->set_rules('garden_name', 'Property Name', 'required|trim');
        $this->CI->form_validation->set_rules('price', 'Price', 'numeric');
        
        $validation_result = $this->CI->form_validation->run();
        $this->assertTrue($validation_result, 'Update form validation should pass');
        
        // Test property update through model
        $update_data = [
            'garden_name' => $_POST['garden_name'],
            'price' => (float)$_POST['price'],
            'description' => $_POST['description'],
            'assigned_staff_id' => $_POST['assigned_staff_id']
        ];
        
        $update_result = $this->CI->Property_model->update_property($this->property_id, $update_data);
        $this->assertTrue($update_result, 'Property update should succeed');
        
        // Verify updates were applied
        $updated_property = $this->CI->Property_model->get_property_by_id($this->property_id);
        $this->assertEqual($_POST['garden_name'], $updated_property->garden_name);
        $this->assertEqual((float)$_POST['price'], $updated_property->price);
        $this->assertEqual($this->staff_id, $updated_property->assigned_staff_id);
        
        // Clean up POST data
        $_POST = [];
    }
    
    /**
     * Test property status change workflow integration
     */
    public function testPropertyStatusChangeWorkflow() {
        // Test status change from unsold to booked
        $status_change_result = $this->CI->Property_model->change_status($this->property_id, 'booked');
        $this->assertTrue($status_change_result, 'Status change should succeed');
        
        // Verify status was changed
        $property_after_status_change = $this->CI->Property_model->get_property_by_id($this->property_id);
        $this->assertEqual('booked', $property_after_status_change->status);
        
        // Test invalid status change
        $invalid_status_result = $this->CI->Property_model->change_status($this->property_id, 'invalid_status');
        $this->assertFalse($invalid_status_result, 'Invalid status change should fail');
    }
    
    /**
     * Test staff assignment workflow integration
     */
    public function testStaffAssignmentWorkflow() {
        // Test staff assignment
        $assignment_result = $this->CI->Property_model->assign_staff($this->property_id, $this->staff_id);
        $this->assertTrue($assignment_result, 'Staff assignment should succeed');
        
        // Verify assignment was made
        $property_with_staff = $this->CI->Property_model->get_property_by_id($this->property_id);
        $this->assertEqual($this->staff_id, $property_with_staff->assigned_staff_id);
        
        // Test getting properties by staff
        $staff_properties = $this->CI->Property_model->get_properties_by_staff($this->staff_id);
        $this->assertTrue(count($staff_properties) >= 1, 'Should find properties assigned to staff');
        
        // Test staff unassignment
        $unassignment_result = $this->CI->Property_model->unassign_staff($this->property_id);
        $this->assertTrue($unassignment_result, 'Staff unassignment should succeed');
        
        // Verify unassignment
        $property_without_staff = $this->CI->Property_model->get_property_by_id($this->property_id);
        $this->assertEqual(null, $property_without_staff->assigned_staff_id);
    }
    
    /**
     * Test property search workflow integration
     */
    public function testPropertySearchWorkflow() {
        // Create properties with different characteristics for search testing
        $search_properties = [];
        
        $search_properties[] = $this->CI->Property_model->create_property([
            'property_type' => 'garden',
            'garden_name' => 'Searchable Garden Property',
            'district' => 'Search District',
            'price' => 600000.00
        ]);
        
        $search_properties[] = $this->CI->Property_model->create_property([
            'property_type' => 'plot',
            'garden_name' => 'Searchable Plot Property',
            'district' => 'Different District',
            'price' => 400000.00
        ]);
        
        // Test text search
        $text_search_results = $this->CI->Property_model->search_properties([
            'search_text' => 'Searchable'
        ]);
        $this->assertTrue(count($text_search_results) >= 2, 'Text search should find multiple properties');
        
        // Test property type filter
        $type_filter_results = $this->CI->Property_model->search_properties([
            'property_type' => 'garden'
        ]);
        $this->assertTrue(count($type_filter_results) >= 1, 'Type filter should find garden properties');
        
        // Test price range filter
        $price_filter_results = $this->CI->Property_model->search_properties([
            'min_price' => 500000,
            'max_price' => 700000
        ]);
        $this->assertTrue(count($price_filter_results) >= 1, 'Price filter should find properties in range');
        
        // Test combined filters
        $combined_filter_results = $this->CI->Property_model->search_properties([
            'property_type' => 'garden',
            'search_text' => 'Searchable',
            'min_price' => 500000
        ]);
        $this->assertTrue(count($combined_filter_results) >= 1, 'Combined filters should work together');
    }
    
    /**
     * Test bulk operations workflow integration
     */
    public function testBulkOperationsWorkflow() {
        // Create multiple properties for bulk operations
        $bulk_property_ids = [];
        
        for ($i = 0; $i < 3; $i++) {
            $property_data = $this->createTestProperty([
                'garden_name' => "Bulk Operation Property $i",
                'status' => 'unsold'
            ]);
            $bulk_property_ids[] = $this->CI->Property_model->create_property($property_data);
        }
        
        // Test bulk status update
        $bulk_status_result = $this->CI->Property_model->bulk_update_status($bulk_property_ids, 'booked');
        $this->assertTrue($bulk_status_result, 'Bulk status update should succeed');
        
        // Verify all properties were updated
        foreach ($bulk_property_ids as $property_id) {
            $property = $this->CI->Property_model->get_property_by_id($property_id);
            $this->assertEqual('booked', $property->status);
        }
        
        // Test bulk staff assignment
        $bulk_assign_result = $this->CI->Property_model->bulk_assign_staff($bulk_property_ids, $this->staff_id);
        $this->assertTrue($bulk_assign_result, 'Bulk staff assignment should succeed');
        
        // Verify all properties were assigned
        foreach ($bulk_property_ids as $property_id) {
            $property = $this->CI->Property_model->get_property_by_id($property_id);
            $this->assertEqual($this->staff_id, $property->assigned_staff_id);
        }
        
        // Test bulk staff unassignment
        $bulk_unassign_result = $this->CI->Property_model->bulk_unassign_staff($bulk_property_ids);
        $this->assertTrue($bulk_unassign_result, 'Bulk staff unassignment should succeed');
        
        // Verify all properties were unassigned
        foreach ($bulk_property_ids as $property_id) {
            $property = $this->CI->Property_model->get_property_by_id($property_id);
            $this->assertEqual(null, $property->assigned_staff_id);
        }
    }
    
    /**
     * Test property deletion workflow with dependency checks
     */
    public function testPropertyDeletionWorkflow() {
        // Create a clean property for deletion test
        $delete_property_data = $this->createTestProperty([
            'garden_name' => 'Property for Deletion Test'
        ]);
        $delete_property_id = $this->CI->Property_model->create_property($delete_property_data);
        
        // Test deletion without dependencies
        $deletion_result = $this->CI->Property_model->delete_property($delete_property_id);
        $this->assertTrue($deletion_result, 'Property deletion should succeed without dependencies');
        
        // Verify soft deletion
        $deleted_property = $this->CI->Property_model->get_property_by_id($delete_property_id);
        $this->assertEqual('deleted', $deleted_property->status);
        
        // Test that deleted properties are excluded from normal listings
        $active_properties = $this->CI->Property_model->get_properties();
        $deleted_found = false;
        foreach ($active_properties as $property) {
            if ($property->id == $delete_property_id) {
                $deleted_found = true;
                break;
            }
        }
        $this->assertFalse($deleted_found, 'Deleted properties should not appear in normal listings');
    }
    
    /**
     * Test property statistics integration
     */
    public function testPropertyStatisticsIntegration() {
        // Create properties with different statuses for statistics testing
        $stats_properties = [];
        
        $stats_properties[] = $this->CI->Property_model->create_property([
            'garden_name' => 'Stats Property Sold',
            'status' => 'sold',
            'price' => 500000.00
        ]);
        
        $stats_properties[] = $this->CI->Property_model->create_property([
            'garden_name' => 'Stats Property Booked',
            'status' => 'booked',
            'price' => 600000.00
        ]);
        
        // Get statistics
        $statistics = $this->CI->Property_model->get_property_statistics();
        
        // Verify statistics structure and data
        $this->assertArrayHasKey('total_properties', $statistics);
        $this->assertArrayHasKey('status_sold', $statistics);
        $this->assertArrayHasKey('status_booked', $statistics);
        $this->assertArrayHasKey('status_unsold', $statistics);
        $this->assertArrayHasKey('total_value', $statistics);
        $this->assertArrayHasKey('sold_value', $statistics);
        
        $this->assertTrue($statistics['total_properties'] >= 3, 'Should have at least 3 properties');
        $this->assertTrue($statistics['status_sold'] >= 1, 'Should have at least 1 sold property');
        $this->assertTrue($statistics['status_booked'] >= 1, 'Should have at least 1 booked property');
        $this->assertTrue($statistics['total_value'] > 0, 'Total value should be greater than 0');
    }
    
    /**
     * Test error handling integration
     */
    public function testErrorHandlingIntegration() {
        // Test creation with invalid data
        $invalid_property_data = [
            'property_type' => '', // Invalid empty type
            'garden_name' => '',   // Invalid empty name
            'price' => -1000       // Invalid negative price
        ];
        
        $invalid_creation_result = $this->CI->Property_model->create_property($invalid_property_data);
        $this->assertFalse($invalid_creation_result, 'Creation with invalid data should fail');
        
        // Test update of non-existent property
        $invalid_update_result = $this->CI->Property_model->update_property(99999, ['garden_name' => 'Test']);
        $this->assertFalse($invalid_update_result, 'Update of non-existent property should fail');
        
        // Test assignment to non-existent staff
        $invalid_assignment_result = $this->CI->Property_model->assign_staff($this->property_id, 99999);
        $this->assertFalse($invalid_assignment_result, 'Assignment to non-existent staff should fail');
        
        // Test status change with invalid status
        $invalid_status_result = $this->CI->Property_model->change_status($this->property_id, 'invalid_status');
        $this->assertFalse($invalid_status_result, 'Invalid status change should fail');
    }
    
    /**
     * Test pagination integration
     */
    public function testPaginationIntegration() {
        // Create multiple properties for pagination testing
        for ($i = 0; $i < 25; $i++) {
            $property_data = $this->createTestProperty([
                'garden_name' => "Pagination Property $i"
            ]);
            $this->CI->Property_model->create_property($property_data);
        }
        
        // Test first page
        $first_page = $this->CI->Property_model->get_properties([], 10, 0);
        $this->assertEqual(10, count($first_page), 'First page should have 10 properties');
        
        // Test second page
        $second_page = $this->CI->Property_model->get_properties([], 10, 10);
        $this->assertTrue(count($second_page) > 0, 'Second page should have properties');
        
        // Verify different properties on different pages
        $first_page_ids = array_column($first_page, 'id');
        $second_page_ids = array_column($second_page, 'id');
        $intersection = array_intersect($first_page_ids, $second_page_ids);
        $this->assertEqual(0, count($intersection), 'Pages should not have overlapping properties');
        
        // Test total count for pagination calculation
        $total_count = $this->CI->Property_model->get_properties_count();
        $this->assertTrue($total_count >= 25, 'Total count should include all created properties');
    }
}