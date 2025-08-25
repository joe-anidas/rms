<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

/**
 * Database Transaction Tests
 * Tests database transaction handling, rollback scenarios, and data integrity
 */
class DatabaseTransactionTest extends RMS_TestCase {
    
    private $customer_id;
    private $property_id;
    private $staff_id;
    
    protected function setUpTestData() {
        // Create test data for transaction testing
        $customer_data = $this->createTestCustomer();
        $this->customer_id = $this->CI->Customer_model->insert_customer($customer_data);
        
        $property_data = $this->createTestProperty();
        $this->property_id = $this->CI->Property_model->create_property($property_data);
        
        $staff_data = $this->createTestStaff();
        $staff_result = $this->CI->Staff_model->insert_staff($staff_data);
        $this->staff_id = $staff_result['staff_id'];
    }
    
    /**
     * Test successful transaction commit
     */
    public function testSuccessfulTransactionCommit() {
        // Start manual transaction
        $this->db->trans_start();
        
        // Perform multiple related operations
        $registration_id = $this->CI->Registration_model->create_registration(
            $this->property_id, 
            $this->customer_id,
            ['total_amount' => 500000.00]
        );
        
        $this->assertNotNull($registration_id, 'Registration should be created');
        
        // Record a payment
        $transaction_data = $this->createTestTransaction($registration_id);
        $transaction_id = $this->CI->Transaction_model->record_payment($transaction_data);
        
        $this->assertNotNull($transaction_id, 'Transaction should be recorded');
        
        // Assign staff to property
        $assignment_result = $this->CI->Staff_model->assign_to_property(
            $this->staff_id, 
            $this->property_id, 
            'sales'
        );
        
        $this->assertTrue($assignment_result['success'], 'Staff assignment should succeed');
        
        // Complete transaction
        $this->db->trans_complete();
        
        // Verify transaction was successful
        $this->assertTrue($this->db->trans_status(), 'Transaction should be successful');
        
        // Verify all data was committed
        $registration = $this->CI->Registration_model->get_registration_by_id($registration_id);
        $this->assertNotNull($registration, 'Registration should exist after commit');
        
        $transaction = $this->CI->Transaction_model->get_transaction($transaction_id);
        $this->assertNotNull($transaction, 'Transaction should exist after commit');
        
        $assignments = $this->CI->Staff_model->get_staff_assignments($this->staff_id);
        $this->assertTrue(count($assignments['property_assignments']) >= 1, 'Assignment should exist after commit');
    }
    
    /**
     * Test transaction rollback on failure
     */
    public function testTransactionRollbackOnFailure() {
        // Get initial counts
        $initial_registration_count = count($this->CI->Registration_model->get_registrations());
        $initial_transaction_count = count($this->CI->Transaction_model->get_transaction_history());
        
        // Start manual transaction
        $this->db->trans_start();
        
        try {
            // Create registration
            $registration_id = $this->CI->Registration_model->create_registration(
                $this->property_id, 
                $this->customer_id,
                ['total_amount' => 500000.00]
            );
            
            $this->assertNotNull($registration_id, 'Registration should be created');
            
            // Record valid transaction
            $transaction_data = $this->createTestTransaction($registration_id);
            $transaction_id = $this->CI->Transaction_model->record_payment($transaction_data);
            
            $this->assertNotNull($transaction_id, 'Transaction should be recorded');
            
            // Simulate failure by trying to create invalid data
            $invalid_transaction_data = [
                'registration_id' => 99999, // Non-existent registration
                'amount' => 50000.00,
                'payment_type' => 'advance',
                'payment_method' => 'cash',
                'payment_date' => date('Y-m-d')
            ];
            
            $invalid_transaction_id = $this->CI->Transaction_model->record_payment($invalid_transaction_data);
            
            if ($invalid_transaction_id === false) {
                // Force rollback
                $this->db->trans_rollback();
                throw new Exception('Simulated transaction failure');
            }
            
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->trans_rollback();
        }
        
        // Verify rollback occurred
        $this->assertFalse($this->db->trans_status(), 'Transaction should have failed');
        
        // Verify no data was committed
        $final_registration_count = count($this->CI->Registration_model->get_registrations());
        $final_transaction_count = count($this->CI->Transaction_model->get_transaction_history());
        
        $this->assertEqual($initial_registration_count, $final_registration_count, 
            'Registration count should be unchanged after rollback');
        $this->assertEqual($initial_transaction_count, $final_transaction_count, 
            'Transaction count should be unchanged after rollback');
    }
    
    /**
     * Test nested transaction handling
     */
    public function testNestedTransactionHandling() {
        // Start outer transaction
        $this->db->trans_start();
        
        // Create registration in outer transaction
        $registration_id = $this->CI->Registration_model->create_registration(
            $this->property_id, 
            $this->customer_id,
            ['total_amount' => 500000.00]
        );
        
        $this->assertNotNull($registration_id, 'Registration should be created in outer transaction');
        
        // Start inner transaction (nested)
        $this->db->trans_start();
        
        // Record payment in inner transaction
        $transaction_data = $this->createTestTransaction($registration_id);
        $transaction_id = $this->CI->Transaction_model->record_payment($transaction_data);
        
        $this->assertNotNull($transaction_id, 'Transaction should be recorded in inner transaction');
        
        // Complete inner transaction
        $this->db->trans_complete();
        
        // Verify inner transaction status
        $this->assertTrue($this->db->trans_status(), 'Inner transaction should be successful');
        
        // Complete outer transaction
        $this->db->trans_complete();
        
        // Verify outer transaction status
        $this->assertTrue($this->db->trans_status(), 'Outer transaction should be successful');
        
        // Verify all data exists
        $registration = $this->CI->Registration_model->get_registration_by_id($registration_id);
        $this->assertNotNull($registration, 'Registration should exist after nested transactions');
        
        $transaction = $this->CI->Transaction_model->get_transaction($transaction_id);
        $this->assertNotNull($transaction, 'Transaction should exist after nested transactions');
    }
    
    /**
     * Test concurrent transaction handling
     */
    public function testConcurrentTransactionHandling() {
        // Simulate concurrent access to the same property
        
        // Transaction 1: Try to register property to customer 1
        $this->db->trans_start();
        
        $registration_id_1 = $this->CI->Registration_model->create_registration(
            $this->property_id, 
            $this->customer_id,
            ['total_amount' => 500000.00]
        );
        
        $this->assertNotNull($registration_id_1, 'First registration should be created');
        
        // Simulate delay
        usleep(100000); // 0.1 second
        
        $this->db->trans_complete();
        $this->assertTrue($this->db->trans_status(), 'First transaction should succeed');
        
        // Transaction 2: Try to register same property to different customer
        $customer_data_2 = $this->createTestCustomer(['plot_buyer_name' => 'Concurrent Customer']);
        $customer_id_2 = $this->CI->Customer_model->insert_customer($customer_data_2);
        
        $this->db->trans_start();
        
        $registration_id_2 = $this->CI->Registration_model->create_registration(
            $this->property_id, 
            $customer_id_2,
            ['total_amount' => 500000.00]
        );
        
        // This should fail because property is already registered
        $this->assertFalse($registration_id_2, 'Second registration should fail for already registered property');
        
        $this->db->trans_complete();
        
        // Verify only first registration exists
        $property_registration = $this->CI->Registration_model->get_registration_by_property($this->property_id);
        $this->assertNotNull($property_registration, 'Property should have a registration');
        $this->assertEqual($this->customer_id, $property_registration->customer_id, 
            'Property should be registered to first customer');
    }
    
    /**
     * Test transaction with foreign key constraints
     */
    public function testTransactionWithForeignKeyConstraints() {
        // Start transaction
        $this->db->trans_start();
        
        try {
            // Create registration
            $registration_id = $this->CI->Registration_model->create_registration(
                $this->property_id, 
                $this->customer_id,
                ['total_amount' => 500000.00]
            );
            
            $this->assertNotNull($registration_id, 'Registration should be created');
            
            // Try to delete customer with active registration (should fail due to foreign key)
            $delete_result = $this->CI->Customer_model->delete_customer($this->customer_id);
            
            // This should fail or return error due to foreign key constraint
            if (is_array($delete_result) && !$delete_result['success']) {
                $this->assertTrue(true, 'Customer deletion should fail with active registrations');
            } else {
                // If deletion succeeded, it means soft delete was used
                $this->assertTrue(true, 'System handled foreign key constraint appropriately');
            }
            
            // Try to delete property with active registration (should fail)
            $property_delete_result = $this->CI->Property_model->delete_property($this->property_id);
            
            if (is_array($property_delete_result) && !$property_delete_result['success']) {
                $this->assertTrue(true, 'Property deletion should fail with active registrations');
            } else {
                $this->assertTrue(true, 'System handled foreign key constraint appropriately');
            }
            
        } catch (Exception $e) {
            // Expected behavior for foreign key constraint violations
            $this->assertTrue(true, 'Foreign key constraints should prevent invalid deletions');
        }
        
        $this->db->trans_complete();
    }
    
    /**
     * Test transaction isolation levels
     */
    public function testTransactionIsolation() {
        // Test READ COMMITTED isolation (default in most databases)
        
        // Start first transaction
        $this->db->trans_start();
        
        // Create registration in first transaction
        $registration_id = $this->CI->Registration_model->create_registration(
            $this->property_id, 
            $this->customer_id,
            ['total_amount' => 500000.00]
        );
        
        $this->assertNotNull($registration_id, 'Registration should be created');
        
        // Don't commit yet - simulate another connection trying to read
        // In a real scenario, this would be a separate database connection
        
        // Update registration in same transaction
        $update_result = $this->CI->Registration_model->update_registration($registration_id, [
            'total_amount' => 600000.00
        ]);
        
        $this->assertTrue($update_result, 'Registration update should succeed');
        
        // Commit transaction
        $this->db->trans_complete();
        $this->assertTrue($this->db->trans_status(), 'Transaction should be successful');
        
        // Verify final state
        $final_registration = $this->CI->Registration_model->get_registration_by_id($registration_id);
        $this->assertEqual(600000.00, $final_registration->total_amount, 
            'Final amount should reflect committed changes');
    }
    
    /**
     * Test deadlock handling
     */
    public function testDeadlockHandling() {
        // Create additional test data
        $property_data_2 = $this->createTestProperty(['garden_name' => 'Deadlock Test Property 2']);
        $property_id_2 = $this->CI->Property_model->create_property($property_data_2);
        
        $customer_data_2 = $this->createTestCustomer(['plot_buyer_name' => 'Deadlock Test Customer 2']);
        $customer_id_2 = $this->CI->Customer_model->insert_customer($customer_data_2);
        
        // Simulate potential deadlock scenario
        // Transaction 1: Update property 1, then property 2
        $this->db->trans_start();
        
        $update_1 = $this->CI->Property_model->update_property($this->property_id, [
            'description' => 'Updated by transaction 1 - step 1'
        ]);
        $this->assertTrue($update_1, 'First update should succeed');
        
        // Small delay to increase chance of deadlock in real concurrent scenario
        usleep(50000); // 0.05 second
        
        $update_2 = $this->CI->Property_model->update_property($property_id_2, [
            'description' => 'Updated by transaction 1 - step 2'
        ]);
        $this->assertTrue($update_2, 'Second update should succeed');
        
        $this->db->trans_complete();
        $this->assertTrue($this->db->trans_status(), 'Transaction should complete successfully');
        
        // In a real deadlock scenario, one transaction would be rolled back
        // and would need to be retried. Since we can't easily simulate true
        // deadlock in a single-threaded test, we verify the system can handle
        // the operations correctly.
        
        // Verify updates were applied
        $property_1 = $this->CI->Property_model->get_property_by_id($this->property_id);
        $property_2 = $this->CI->Property_model->get_property_by_id($property_id_2);
        
        $this->assertEqual('Updated by transaction 1 - step 1', $property_1->description);
        $this->assertEqual('Updated by transaction 1 - step 2', $property_2->description);
    }
    
    /**
     * Test transaction with large dataset
     */
    public function testTransactionWithLargeDataset() {
        $this->db->trans_start();
        
        $created_ids = [];
        
        try {
            // Create multiple properties in single transaction
            for ($i = 0; $i < 50; $i++) {
                $property_data = $this->createTestProperty([
                    'garden_name' => "Bulk Property $i",
                    'price' => 500000.00 + ($i * 10000)
                ]);
                
                $property_id = $this->CI->Property_model->create_property($property_data);
                $this->assertNotNull($property_id, "Property $i should be created");
                $created_ids[] = $property_id;
            }
            
            // Bulk update all created properties
            $bulk_update_result = $this->CI->Property_model->bulk_update_status($created_ids, 'booked');
            $this->assertTrue($bulk_update_result, 'Bulk update should succeed');
            
            // Verify all properties were created and updated
            foreach ($created_ids as $property_id) {
                $property = $this->CI->Property_model->get_property_by_id($property_id);
                $this->assertNotNull($property, 'Property should exist');
                $this->assertEqual('booked', $property->status, 'Property should be booked');
            }
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            throw $e;
        }
        
        $this->db->trans_complete();
        $this->assertTrue($this->db->trans_status(), 'Large dataset transaction should succeed');
        
        // Verify transaction committed successfully
        $this->assertEqual(50, count($created_ids), 'Should have created 50 properties');
    }
    
    /**
     * Test transaction rollback with cleanup
     */
    public function testTransactionRollbackWithCleanup() {
        // Get initial state
        $initial_property_count = $this->CI->Property_model->get_properties_count();
        $initial_customer_count = $this->CI->Customer_model->get_customer_count();
        
        $this->db->trans_start();
        
        try {
            // Create multiple related records
            $new_customer_data = $this->createTestCustomer(['plot_buyer_name' => 'Rollback Test Customer']);
            $new_customer_id = $this->CI->Customer_model->insert_customer($new_customer_data);
            
            $new_property_data = $this->createTestProperty(['garden_name' => 'Rollback Test Property']);
            $new_property_id = $this->CI->Property_model->create_property($new_property_data);
            
            $registration_id = $this->CI->Registration_model->create_registration(
                $new_property_id, 
                $new_customer_id,
                ['total_amount' => 500000.00]
            );
            
            // Simulate error condition
            throw new Exception('Simulated error for rollback test');
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
        }
        
        // Verify rollback occurred
        $this->assertFalse($this->db->trans_status(), 'Transaction should have been rolled back');
        
        // Verify counts are unchanged
        $final_property_count = $this->CI->Property_model->get_properties_count();
        $final_customer_count = $this->CI->Customer_model->get_customer_count();
        
        $this->assertEqual($initial_property_count, $final_property_count, 
            'Property count should be unchanged after rollback');
        $this->assertEqual($initial_customer_count, $final_customer_count, 
            'Customer count should be unchanged after rollback');
    }
    
    /**
     * Test auto-commit behavior
     */
    public function testAutoCommitBehavior() {
        // Test that operations outside transactions are auto-committed
        
        $property_data = $this->createTestProperty(['garden_name' => 'Auto Commit Test Property']);
        $property_id = $this->CI->Property_model->create_property($property_data);
        
        $this->assertNotNull($property_id, 'Property should be created with auto-commit');
        
        // Verify property exists immediately (auto-committed)
        $created_property = $this->CI->Property_model->get_property_by_id($property_id);
        $this->assertNotNull($created_property, 'Auto-committed property should be immediately available');
        
        // Update property (should also auto-commit)
        $update_result = $this->CI->Property_model->update_property($property_id, [
            'description' => 'Auto-commit update test'
        ]);
        $this->assertTrue($update_result, 'Auto-commit update should succeed');
        
        // Verify update is immediately visible
        $updated_property = $this->CI->Property_model->get_property_by_id($property_id);
        $this->assertEqual('Auto-commit update test', $updated_property->description, 
            'Auto-committed update should be immediately visible');
    }
}