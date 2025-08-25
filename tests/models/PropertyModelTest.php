<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

/**
 * Unit Tests for Property_model
 * Tests all business logic and CRUD operations
 */
class PropertyModelTest extends RMS_TestCase {
    
    private $property_id;
    private $staff_id;
    
    protected function setUpTestData() {
        // Create test staff for assignment tests
        $staff_data = $this->createTestStaff();
        $this->staff_id = $this->CI->Staff_model->insert_staff($staff_data)['staff_id'];
        
        // Create test property
        $property_data = $this->createTestProperty();
        $this->property_id = $this->CI->Property_model->create_property($property_data);
    }
    
    /**
     * Test property creation with valid data
     */
    public function testCreatePropertyWithValidData() {
        $property_data = $this->createTestProperty([
            'garden_name' => 'Test Garden Create',
            'price' => 750000.00
        ]);
        
        $result = $this->CI->Property_model->create_property($property_data);
        
        $this->assertNotNull($result, 'Property creation should return property ID');
        $this->assertTrue(is_numeric($result), 'Property ID should be numeric');
        
        // Verify property was created correctly
        $created_property = $this->CI->Property_model->get_property_by_id($result);
        $this->assertNotNull($created_property, 'Created property should be retrievable');
        $this->assertEqual($property_data['garden_name'], $created_property->garden_name);
        $this->assertEqual($property_data['price'], $created_property->price);
        $this->assertEqual('unsold', $created_property->status);
    }
    
    /**
     * Test property creation with missing required fields
     */
    public function testCreatePropertyWithMissingRequiredFields() {
        $invalid_data = [
            'price' => 500000.00
            // Missing garden_name and property_type
        ];
        
        $result = $this->CI->Property_model->create_property($invalid_data);
        $this->assertFalse($result, 'Property creation should fail with missing required fields');
    }
    
    /**
     * Test getting properties with filters
     */
    public function testGetPropertiesWithFilters() {
        // Create properties with different statuses
        $sold_property_data = $this->createTestProperty(['status' => 'sold', 'garden_name' => 'Sold Garden']);
        $sold_id = $this->CI->Property_model->create_property($sold_property_data);
        
        $booked_property_data = $this->createTestProperty(['status' => 'booked', 'garden_name' => 'Booked Garden']);
        $booked_id = $this->CI->Property_model->create_property($booked_property_data);
        
        // Test status filter
        $sold_properties = $this->CI->Property_model->get_properties(['status' => 'sold']);
        $this->assertTrue(count($sold_properties) >= 1, 'Should find at least one sold property');
        
        // Test multiple status filter
        $sold_and_booked = $this->CI->Property_model->get_properties(['status' => ['sold', 'booked']]);
        $this->assertTrue(count($sold_and_booked) >= 2, 'Should find sold and booked properties');
        
        // Test search filter
        $search_results = $this->CI->Property_model->get_properties(['search' => 'Sold Garden']);
        $this->assertTrue(count($search_results) >= 1, 'Should find properties by search term');
    }
    
    /**
     * Test property update functionality
     */
    public function testUpdateProperty() {
        $update_data = [
            'garden_name' => 'Updated Garden Name',
            'price' => 600000.00,
            'description' => 'Updated description'
        ];
        
        $result = $this->CI->Property_model->update_property($this->property_id, $update_data);
        $this->assertTrue($result, 'Property update should succeed');
        
        // Verify updates
        $updated_property = $this->CI->Property_model->get_property_by_id($this->property_id);
        $this->assertEqual($update_data['garden_name'], $updated_property->garden_name);
        $this->assertEqual($update_data['price'], $updated_property->price);
        $this->assertEqual($update_data['description'], $updated_property->description);
    }
    
    /**
     * Test property status change
     */
    public function testChangePropertyStatus() {
        // Test valid status change
        $result = $this->CI->Property_model->change_status($this->property_id, 'booked');
        $this->assertTrue($result, 'Status change should succeed');
        
        $property = $this->CI->Property_model->get_property_by_id($this->property_id);
        $this->assertEqual('booked', $property->status);
        
        // Test invalid status
        $invalid_result = $this->CI->Property_model->change_status($this->property_id, 'invalid_status');
        $this->assertFalse($invalid_result, 'Invalid status change should fail');
    }
    
    /**
     * Test staff assignment to property
     */
    public function testAssignStaffToProperty() {
        $result = $this->CI->Property_model->assign_staff($this->property_id, $this->staff_id);
        $this->assertTrue($result, 'Staff assignment should succeed');
        
        $property = $this->CI->Property_model->get_property_by_id($this->property_id);
        $this->assertEqual($this->staff_id, $property->assigned_staff_id);
        
        // Test assignment to non-existent staff
        $invalid_result = $this->CI->Property_model->assign_staff($this->property_id, 99999);
        $this->assertFalse($invalid_result, 'Assignment to non-existent staff should fail');
    }
    
    /**
     * Test staff unassignment from property
     */
    public function testUnassignStaffFromProperty() {
        // First assign staff
        $this->CI->Property_model->assign_staff($this->property_id, $this->staff_id);
        
        // Then unassign
        $result = $this->CI->Property_model->unassign_staff($this->property_id);
        $this->assertTrue($result, 'Staff unassignment should succeed');
        
        $property = $this->CI->Property_model->get_property_by_id($this->property_id);
        $this->assertEqual(null, $property->assigned_staff_id);
    }
    
    /**
     * Test property search functionality
     */
    public function testSearchProperties() {
        // Create property with specific search terms
        $search_property_data = $this->createTestProperty([
            'garden_name' => 'Unique Search Garden',
            'district' => 'Unique District',
            'property_type' => 'plot'
        ]);
        $search_id = $this->CI->Property_model->create_property($search_property_data);
        
        // Test text search
        $text_results = $this->CI->Property_model->search_properties(['search_text' => 'Unique Search']);
        $this->assertTrue(count($text_results) >= 1, 'Should find properties by text search');
        
        // Test property type filter
        $type_results = $this->CI->Property_model->search_properties(['property_type' => 'plot']);
        $this->assertTrue(count($type_results) >= 1, 'Should find properties by type');
        
        // Test price range filter
        $price_results = $this->CI->Property_model->search_properties([
            'min_price' => 400000,
            'max_price' => 600000
        ]);
        $this->assertTrue(count($price_results) >= 1, 'Should find properties in price range');
    }
    
    /**
     * Test property statistics calculation
     */
    public function testGetPropertyStatistics() {
        $stats = $this->CI->Property_model->get_property_statistics();
        
        $this->assertArrayHasKey('total_properties', $stats);
        $this->assertArrayHasKey('status_unsold', $stats);
        $this->assertArrayHasKey('total_value', $stats);
        $this->assertArrayHasKey('average_price', $stats);
        $this->assertArrayHasKey('monthly_trends', $stats);
        
        $this->assertTrue($stats['total_properties'] >= 1, 'Should have at least one property');
        $this->assertTrue(is_numeric($stats['total_value']), 'Total value should be numeric');
    }
    
    /**
     * Test property deletion with dependencies check
     */
    public function testDeletePropertyWithDependencies() {
        // Test deletion without dependencies
        $clean_property_data = $this->createTestProperty(['garden_name' => 'Clean Property']);
        $clean_id = $this->CI->Property_model->create_property($clean_property_data);
        
        $result = $this->CI->Property_model->delete_property($clean_id);
        $this->assertTrue($result, 'Property without dependencies should be deletable');
        
        // Verify soft delete
        $deleted_property = $this->CI->Property_model->get_property_by_id($clean_id);
        $this->assertEqual('deleted', $deleted_property->status);
    }
    
    /**
     * Test bulk operations
     */
    public function testBulkOperations() {
        // Create multiple properties for bulk operations
        $property_ids = [];
        for ($i = 0; $i < 3; $i++) {
            $property_data = $this->createTestProperty(['garden_name' => "Bulk Property $i"]);
            $property_ids[] = $this->CI->Property_model->create_property($property_data);
        }
        
        // Test bulk status update
        $bulk_status_result = $this->CI->Property_model->bulk_update_status($property_ids, 'booked');
        $this->assertTrue($bulk_status_result, 'Bulk status update should succeed');
        
        // Verify status updates
        foreach ($property_ids as $id) {
            $property = $this->CI->Property_model->get_property_by_id($id);
            $this->assertEqual('booked', $property->status);
        }
        
        // Test bulk staff assignment
        $bulk_assign_result = $this->CI->Property_model->bulk_assign_staff($property_ids, $this->staff_id);
        $this->assertTrue($bulk_assign_result, 'Bulk staff assignment should succeed');
        
        // Verify assignments
        foreach ($property_ids as $id) {
            $property = $this->CI->Property_model->get_property_by_id($id);
            $this->assertEqual($this->staff_id, $property->assigned_staff_id);
        }
    }
    
    /**
     * Test property count functionality
     */
    public function testGetPropertiesCount() {
        $total_count = $this->CI->Property_model->get_properties_count();
        $this->assertTrue($total_count >= 1, 'Should have at least one property');
        
        // Test count with filters
        $unsold_count = $this->CI->Property_model->get_properties_count(['status' => 'unsold']);
        $this->assertTrue(is_numeric($unsold_count), 'Count should be numeric');
    }
    
    /**
     * Test getting distinct values for filters
     */
    public function testGetDistinctValues() {
        $districts = $this->CI->Property_model->get_distinct_values('district');
        $this->assertTrue(is_array($districts), 'Should return array of distinct values');
        
        $property_types = $this->CI->Property_model->get_distinct_values('property_type');
        $this->assertTrue(is_array($property_types), 'Should return array of property types');
        
        // Test invalid field
        $invalid = $this->CI->Property_model->get_distinct_values('invalid_field');
        $this->assertEqual([], $invalid, 'Invalid field should return empty array');
    }
    
    /**
     * Test getting properties by staff
     */
    public function testGetPropertiesByStaff() {
        // Assign property to staff
        $this->CI->Property_model->assign_staff($this->property_id, $this->staff_id);
        
        $staff_properties = $this->CI->Property_model->get_properties_by_staff($this->staff_id);
        $this->assertTrue(count($staff_properties) >= 1, 'Should find properties assigned to staff');
        
        // Test with non-existent staff
        $empty_properties = $this->CI->Property_model->get_properties_by_staff(99999);
        $this->assertEqual([], $empty_properties, 'Non-existent staff should have no properties');
    }
}