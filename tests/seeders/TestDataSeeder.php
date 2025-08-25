<?php
require_once dirname(__FILE__) . '/../TestBootstrap.php';

/**
 * Test Data Seeder
 * Creates comprehensive test data for testing scenarios
 */
class TestDataSeeder extends RMS_TestCase {
    
    private $seeded_data = [];
    
    /**
     * Seed comprehensive test data
     */
    public function seedComprehensiveTestData() {
        $this->seeded_data = [
            'customers' => [],
            'staff' => [],
            'properties' => [],
            'registrations' => [],
            'transactions' => []
        ];
        
        // Seed customers
        $this->seedCustomers(20);
        
        // Seed staff
        $this->seedStaff(5);
        
        // Seed properties
        $this->seedProperties(30);
        
        // Seed registrations
        $this->seedRegistrations(15);
        
        // Seed transactions
        $this->seedTransactions(25);
        
        return $this->seeded_data;
    }
    
    /**
     * Seed customers with diverse data
     */
    public function seedCustomers($count = 10) {
        $districts = ['Mumbai', 'Pune', 'Nashik', 'Nagpur', 'Aurangabad'];
        $taluks = ['Andheri', 'Bandra', 'Colaba', 'Dadar', 'Malad'];
        $villages = ['Village A', 'Village B', 'Village C', 'Village D', 'Village E'];
        
        for ($i = 1; $i <= $count; $i++) {
            $customer_data = [
                'plot_buyer_name' => "Test Customer $i",
                'father_name' => "Father of Customer $i",
                'district' => $districts[array_rand($districts)],
                'pincode' => str_pad(rand(400000, 499999), 6, '0', STR_PAD_LEFT),
                'taluk_name' => $taluks[array_rand($taluks)],
                'village_town_name' => $villages[array_rand($villages)],
                'street_address' => "Street Address $i, Area $i",
                'phone_number_1' => '98' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'phone_number_2' => '97' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'email_address' => "customer$i@example.com",
                'id_proof' => 'Aadhar Card',
                'aadhar_number' => str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                'pan_number' => 'ABCDE' . str_pad($i, 4, '0', STR_PAD_LEFT) . 'F',
                'annual_income' => rand(300000, 2000000),
                'occupation' => ['Business', 'Service', 'Agriculture', 'Professional'][rand(0, 3)],
                'emergency_contact_name' => "Emergency Contact $i",
                'emergency_contact_phone' => '99' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'customer_status' => 'active'
            ];
            
            try {
                $customer_id = $this->CI->Customer_model->insert_customer($customer_data);
                if ($customer_id) {
                    $this->seeded_data['customers'][] = $customer_id;
                }
            } catch (Exception $e) {
                // Log error but continue seeding
                error_log("Error seeding customer $i: " . $e->getMessage());
            }
        }
        
        return $this->seeded_data['customers'];
    }
    
    /**
     * Seed staff with different roles
     */
    public function seedStaff($count = 5) {
        $designations = ['Sales Executive', 'Sales Manager', 'Customer Service', 'Property Manager', 'Admin'];
        $departments = ['Sales', 'Customer Service', 'Operations', 'Administration'];
        
        for ($i = 1; $i <= $count; $i++) {
            $staff_data = [
                'employee_name' => "Staff Member $i",
                'father_name' => "Father of Staff $i",
                'date_of_birth' => date('Y-m-d', strtotime('-' . rand(25, 50) . ' years')),
                'gender' => ['Male', 'Female'][rand(0, 1)],
                'marital_status' => ['Single', 'Married'][rand(0, 1)],
                'blood_group' => ['A+', 'B+', 'AB+', 'O+', 'A-', 'B-', 'AB-', 'O-'][rand(0, 7)],
                'contact_number' => '98' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'alternate_contact' => '97' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'email_address' => "staff$i@company.com",
                'permanent_address' => "Permanent Address $i, City $i",
                'current_address' => "Current Address $i, City $i",
                'emergency_contact_name' => "Emergency Contact for Staff $i",
                'emergency_contact_phone' => '99' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'emergency_contact_relation' => ['Spouse', 'Parent', 'Sibling', 'Friend'][rand(0, 3)],
                'id_proof_type' => 'Aadhar Card',
                'id_proof_number' => str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                'designation' => $designations[($i - 1) % count($designations)],
                'department' => $departments[rand(0, count($departments) - 1)],
                'joining_date' => date('Y-m-d', strtotime('-' . rand(1, 1000) . ' days')),
                'salary' => rand(25000, 80000),
                'bank_name' => 'Test Bank',
                'bank_account_number' => str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT),
                'ifsc_code' => 'TEST0001234',
                'pan_number' => 'STAFF' . str_pad($i, 4, '0', STR_PAD_LEFT) . 'F',
                'aadhar_number' => str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT)
            ];
            
            try {
                $result = $this->CI->Staff_model->insert_staff($staff_data);
                if ($result['success']) {
                    $this->seeded_data['staff'][] = $result['staff_id'];
                }
            } catch (Exception $e) {
                error_log("Error seeding staff $i: " . $e->getMessage());
            }
        }
        
        return $this->seeded_data['staff'];
    }
    
    /**
     * Seed properties with various types and statuses
     */
    public function seedProperties($count = 20) {
        $property_types = ['garden', 'plot', 'house', 'flat'];
        $districts = ['Mumbai', 'Pune', 'Nashik', 'Nagpur', 'Aurangabad'];
        $taluks = ['Andheri', 'Bandra', 'Colaba', 'Dadar', 'Malad'];
        $villages = ['Village A', 'Village B', 'Village C', 'Village D', 'Village E'];
        $statuses = ['unsold', 'booked', 'sold'];
        
        for ($i = 1; $i <= $count; $i++) {
            $property_type = $property_types[rand(0, count($property_types) - 1)];
            $status = $statuses[rand(0, count($statuses) - 1)];
            
            $property_data = [
                'property_type' => $property_type,
                'garden_name' => ucfirst($property_type) . " Property $i",
                'district' => $districts[rand(0, count($districts) - 1)],
                'taluk_name' => $taluks[rand(0, count($taluks) - 1)],
                'village_town_name' => $villages[rand(0, count($villages) - 1)],
                'size_sqft' => rand(500, 5000),
                'price' => rand(200000, 2000000),
                'status' => $status,
                'description' => "This is a test $property_type property with good amenities and location.",
                'assigned_staff_id' => !empty($this->seeded_data['staff']) ? 
                    $this->seeded_data['staff'][rand(0, count($this->seeded_data['staff']) - 1)] : null
            ];
            
            try {
                $property_id = $this->CI->Property_model->create_property($property_data);
                if ($property_id) {
                    $this->seeded_data['properties'][] = $property_id;
                }
            } catch (Exception $e) {
                error_log("Error seeding property $i: " . $e->getMessage());
            }
        }
        
        return $this->seeded_data['properties'];
    }
    
    /**
     * Seed registrations linking customers to properties
     */
    public function seedRegistrations($count = 10) {
        if (empty($this->seeded_data['customers']) || empty($this->seeded_data['properties'])) {
            error_log('Cannot seed registrations: customers or properties not seeded');
            return [];
        }
        
        $used_properties = [];
        $statuses = ['active', 'completed', 'cancelled'];
        
        for ($i = 1; $i <= $count && $i <= count($this->seeded_data['properties']); $i++) {
            // Get unused property
            $available_properties = array_diff($this->seeded_data['properties'], $used_properties);
            if (empty($available_properties)) {
                break;
            }
            
            $property_id = $available_properties[array_rand($available_properties)];
            $customer_id = $this->seeded_data['customers'][rand(0, count($this->seeded_data['customers']) - 1)];
            $used_properties[] = $property_id;
            
            // Get property price for total amount
            $property = $this->CI->Property_model->get_property_by_id($property_id);
            $total_amount = $property ? $property->price : rand(300000, 1500000);
            
            $registration_data = [
                'total_amount' => $total_amount,
                'paid_amount' => rand(0, $total_amount * 0.8), // 0-80% paid
                'registration_date' => date('Y-m-d', strtotime('-' . rand(1, 365) . ' days')),
                'status' => $statuses[rand(0, count($statuses) - 1)],
                'agreement_path' => "/uploads/agreements/test_agreement_$i.pdf"
            ];
            
            try {
                $registration_id = $this->CI->Registration_model->create_registration(
                    $property_id, 
                    $customer_id, 
                    $registration_data
                );
                
                if ($registration_id) {
                    $this->seeded_data['registrations'][] = $registration_id;
                }
            } catch (Exception $e) {
                error_log("Error seeding registration $i: " . $e->getMessage());
            }
        }
        
        return $this->seeded_data['registrations'];
    }
    
    /**
     * Seed transactions for registrations
     */
    public function seedTransactions($count = 20) {
        if (empty($this->seeded_data['registrations'])) {
            error_log('Cannot seed transactions: registrations not seeded');
            return [];
        }
        
        $payment_types = ['advance', 'installment', 'full_payment'];
        $payment_methods = ['cash', 'cheque', 'bank_transfer', 'online'];
        
        for ($i = 1; $i <= $count; $i++) {
            $registration_id = $this->seeded_data['registrations'][rand(0, count($this->seeded_data['registrations']) - 1)];
            
            // Get registration details for realistic amounts
            $registration = $this->CI->Registration_model->get_registration_by_id($registration_id);
            if (!$registration) {
                continue;
            }
            
            $max_amount = $registration->total_amount * 0.5; // Max 50% of total in single transaction
            $amount = rand(10000, min($max_amount, 500000));
            
            $transaction_data = [
                'registration_id' => $registration_id,
                'amount' => $amount,
                'payment_type' => $payment_types[rand(0, count($payment_types) - 1)],
                'payment_method' => $payment_methods[rand(0, count($payment_methods) - 1)],
                'payment_date' => date('Y-m-d', strtotime('-' . rand(1, 180) . ' days')),
                'notes' => "Test transaction $i - automated seeding",
                'receipt_number' => 'RCP' . date('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT)
            ];
            
            try {
                $transaction_id = $this->CI->Transaction_model->record_payment($transaction_data);
                if ($transaction_id) {
                    $this->seeded_data['transactions'][] = $transaction_id;
                }
            } catch (Exception $e) {
                error_log("Error seeding transaction $i: " . $e->getMessage());
            }
        }
        
        return $this->seeded_data['transactions'];
    }
    
    /**
     * Seed staff assignments
     */
    public function seedStaffAssignments() {
        if (empty($this->seeded_data['staff']) || empty($this->seeded_data['properties']) || empty($this->seeded_data['customers'])) {
            error_log('Cannot seed assignments: required data not seeded');
            return;
        }
        
        $assignment_types = ['sales', 'maintenance', 'customer_service'];
        
        // Assign staff to properties
        foreach ($this->seeded_data['properties'] as $property_id) {
            if (rand(0, 1)) { // 50% chance of assignment
                $staff_id = $this->seeded_data['staff'][rand(0, count($this->seeded_data['staff']) - 1)];
                $assignment_type = $assignment_types[rand(0, count($assignment_types) - 1)];
                
                try {
                    $this->CI->Staff_model->assign_to_property(
                        $staff_id, 
                        $property_id, 
                        $assignment_type,
                        date('Y-m-d', strtotime('-' . rand(1, 100) . ' days'))
                    );
                } catch (Exception $e) {
                    error_log("Error seeding property assignment: " . $e->getMessage());
                }
            }
        }
        
        // Assign staff to customers
        foreach ($this->seeded_data['customers'] as $customer_id) {
            if (rand(0, 2) == 0) { // 33% chance of assignment
                $staff_id = $this->seeded_data['staff'][rand(0, count($this->seeded_data['staff']) - 1)];
                
                try {
                    $this->CI->Staff_model->assign_to_customer(
                        $staff_id, 
                        $customer_id, 
                        'customer_service',
                        date('Y-m-d', strtotime('-' . rand(1, 100) . ' days')),
                        'Seeded assignment for testing'
                    );
                } catch (Exception $e) {
                    error_log("Error seeding customer assignment: " . $e->getMessage());
                }
            }
        }
    }
    
    /**
     * Seed payment schedules
     */
    public function seedPaymentSchedules() {
        if (empty($this->seeded_data['registrations'])) {
            error_log('Cannot seed payment schedules: registrations not seeded');
            return;
        }
        
        foreach ($this->seeded_data['registrations'] as $registration_id) {
            if (rand(0, 2) == 0) { // 33% chance of having payment schedule
                $registration = $this->CI->Registration_model->get_registration_by_id($registration_id);
                if (!$registration) continue;
                
                $schedule_data = [
                    'total_amount' => $registration->total_amount,
                    'installment_count' => rand(3, 12),
                    'start_date' => date('Y-m-d', strtotime('-' . rand(30, 180) . ' days'))
                ];
                
                try {
                    $this->CI->Transaction_model->create_payment_schedule($registration_id, $schedule_data);
                } catch (Exception $e) {
                    error_log("Error seeding payment schedule: " . $e->getMessage());
                }
            }
        }
    }
    
    /**
     * Clean up all seeded data
     */
    public function cleanupSeededData() {
        // Note: In a real test environment, this would be handled by transaction rollback
        // This method is for manual cleanup if needed
        
        try {
            // Delete transactions
            foreach ($this->seeded_data['transactions'] as $transaction_id) {
                $this->CI->Transaction_model->delete_transaction($transaction_id);
            }
            
            // Delete registrations
            foreach ($this->seeded_data['registrations'] as $registration_id) {
                $this->CI->Registration_model->update_status($registration_id, 'cancelled');
            }
            
            // Delete properties
            foreach ($this->seeded_data['properties'] as $property_id) {
                $this->CI->Property_model->delete_property($property_id);
            }
            
            // Delete staff
            foreach ($this->seeded_data['staff'] as $staff_id) {
                $this->CI->Staff_model->delete_staff($staff_id);
            }
            
            // Delete customers
            foreach ($this->seeded_data['customers'] as $customer_id) {
                $this->CI->Customer_model->delete_customer($customer_id);
            }
            
        } catch (Exception $e) {
            error_log("Error during cleanup: " . $e->getMessage());
        }
    }
    
    /**
     * Get seeded data summary
     */
    public function getSeededDataSummary() {
        return [
            'customers_count' => count($this->seeded_data['customers']),
            'staff_count' => count($this->seeded_data['staff']),
            'properties_count' => count($this->seeded_data['properties']),
            'registrations_count' => count($this->seeded_data['registrations']),
            'transactions_count' => count($this->seeded_data['transactions']),
            'total_records' => array_sum(array_map('count', $this->seeded_data))
        ];
    }
    
    /**
     * Seed realistic business scenario data
     */
    public function seedBusinessScenario() {
        // Scenario: Real estate company with 6 months of operations
        
        // Create staff hierarchy
        $manager_id = $this->seedStaffMember([
            'employee_name' => 'Sales Manager',
            'designation' => 'Sales Manager',
            'department' => 'Sales'
        ]);
        
        $executives = [];
        for ($i = 1; $i <= 3; $i++) {
            $executives[] = $this->seedStaffMember([
                'employee_name' => "Sales Executive $i",
                'designation' => 'Sales Executive',
                'department' => 'Sales'
            ]);
        }
        
        // Create diverse property portfolio
        $property_portfolio = [
            ['type' => 'garden', 'count' => 10, 'price_range' => [500000, 1500000]],
            ['type' => 'plot', 'count' => 15, 'price_range' => [300000, 800000]],
            ['type' => 'house', 'count' => 5, 'price_range' => [1500000, 3000000]],
            ['type' => 'flat', 'count' => 8, 'price_range' => [800000, 2000000]]
        ];
        
        foreach ($property_portfolio as $portfolio_item) {
            for ($i = 1; $i <= $portfolio_item['count']; $i++) {
                $property_data = [
                    'property_type' => $portfolio_item['type'],
                    'garden_name' => ucfirst($portfolio_item['type']) . " Property $i",
                    'price' => rand($portfolio_item['price_range'][0], $portfolio_item['price_range'][1]),
                    'assigned_staff_id' => $executives[rand(0, count($executives) - 1)]
                ];
                
                $property_id = $this->CI->Property_model->create_property($property_data);
                if ($property_id) {
                    $this->seeded_data['properties'][] = $property_id;
                }
            }
        }
        
        // Create customer base with realistic distribution
        $customer_segments = [
            ['segment' => 'Premium', 'count' => 5, 'income_range' => [1500000, 5000000]],
            ['segment' => 'Mid-tier', 'count' => 15, 'income_range' => [800000, 1500000]],
            ['segment' => 'Budget', 'count' => 20, 'income_range' => [300000, 800000]]
        ];
        
        foreach ($customer_segments as $segment) {
            for ($i = 1; $i <= $segment['count']; $i++) {
                $customer_data = [
                    'plot_buyer_name' => $segment['segment'] . " Customer $i",
                    'annual_income' => rand($segment['income_range'][0], $segment['income_range'][1])
                ];
                
                $customer_id = $this->CI->Customer_model->insert_customer(
                    array_merge($this->createTestCustomer(), $customer_data)
                );
                
                if ($customer_id) {
                    $this->seeded_data['customers'][] = $customer_id;
                }
            }
        }
        
        return $this->getSeededDataSummary();
    }
    
    /**
     * Helper method to seed individual staff member
     */
    private function seedStaffMember($overrides = []) {
        $staff_data = array_merge($this->createTestStaff(), $overrides);
        $result = $this->CI->Staff_model->insert_staff($staff_data);
        
        if ($result['success']) {
            $this->seeded_data['staff'][] = $result['staff_id'];
            return $result['staff_id'];
        }
        
        return null;
    }
}