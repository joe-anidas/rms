<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seeder_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
    }

    /**
     * Seed all tables with test data
     */
    public function seed_all() {
        echo "Starting database seeding...\n";
        echo str_repeat("-", 50) . "\n";
        
        $this->seed_staff();
        $this->seed_customers();
        $this->seed_properties();
        $this->seed_registrations();
        $this->seed_transactions();
        $this->seed_property_assignments();
        
        echo str_repeat("-", 50) . "\n";
        echo "Database seeding completed successfully!\n";
    }

    /**
     * Seed staff table
     */
    public function seed_staff() {
        echo "Seeding staff table...\n";
        
        $staff_data = array(
            array(
                'employee_name' => 'John Doe',
                'father_name' => 'Robert Doe',
                'date_of_birth' => '1990-05-15',
                'gender' => 'Male',
                'marital_status' => 'Single',
                'blood_group' => 'A+',
                'contact_number' => '9876543210',
                'alternate_contact' => '9876543211',
                'email_address' => 'john.doe@rms.com',
                'permanent_address' => '123 Main Street, Bangalore, Karnataka - 560001',
                'current_address' => '456 Work Street, Bangalore, Karnataka - 560002',
                'emergency_contact_name' => 'Robert Doe',
                'emergency_contact_phone' => '9876543212',
                'emergency_contact_relation' => 'Father',
                'id_proof_type' => 'Aadhar',
                'id_proof_number' => '123456789012',
                'designation' => 'Sales Manager',
                'department' => 'Sales',
                'joining_date' => '2023-01-15',
                'salary' => 50000.00,
                'bank_name' => 'State Bank of India',
                'bank_account_number' => '1234567890',
                'ifsc_code' => 'SBIN0001234',
                'pan_number' => 'ABCDE1234F',
                'aadhar_number' => '123456789012'
            ),
            array(
                'employee_name' => 'Jane Smith',
                'father_name' => 'Michael Smith',
                'date_of_birth' => '1988-12-20',
                'gender' => 'Female',
                'marital_status' => 'Married',
                'blood_group' => 'B+',
                'contact_number' => '9876543213',
                'alternate_contact' => '9876543214',
                'email_address' => 'jane.smith@rms.com',
                'permanent_address' => '789 Home Street, Mysore, Karnataka - 570001',
                'current_address' => '321 Office Street, Bangalore, Karnataka - 560003',
                'emergency_contact_name' => 'Michael Smith',
                'emergency_contact_phone' => '9876543215',
                'emergency_contact_relation' => 'Father',
                'id_proof_type' => 'PAN',
                'id_proof_number' => 'FGHIJ5678K',
                'designation' => 'Customer Service Manager',
                'department' => 'Customer Service',
                'joining_date' => '2022-08-10',
                'salary' => 45000.00,
                'bank_name' => 'HDFC Bank',
                'bank_account_number' => '0987654321',
                'ifsc_code' => 'HDFC0005678',
                'pan_number' => 'FGHIJ5678K',
                'aadhar_number' => '987654321098'
            ),
            array(
                'employee_name' => 'Raj Kumar',
                'father_name' => 'Suresh Kumar',
                'date_of_birth' => '1985-03-10',
                'gender' => 'Male',
                'marital_status' => 'Married',
                'blood_group' => 'O+',
                'contact_number' => '9876543216',
                'alternate_contact' => '9876543217',
                'email_address' => 'raj.kumar@rms.com',
                'permanent_address' => '456 Garden Road, Hubli, Karnataka - 580001',
                'current_address' => '789 Staff Colony, Bangalore, Karnataka - 560004',
                'emergency_contact_name' => 'Suresh Kumar',
                'emergency_contact_phone' => '9876543218',
                'emergency_contact_relation' => 'Father',
                'id_proof_type' => 'Aadhar',
                'id_proof_number' => '456789123456',
                'designation' => 'Property Maintenance',
                'department' => 'Operations',
                'joining_date' => '2021-06-01',
                'salary' => 35000.00,
                'bank_name' => 'Canara Bank',
                'bank_account_number' => '5678901234',
                'ifsc_code' => 'CNRB0009012',
                'pan_number' => 'KLMNO9012P',
                'aadhar_number' => '456789123456'
            )
        );
        
        foreach ($staff_data as $staff) {
            $this->db->insert('staff', $staff);
        }
        
        echo "✓ Inserted " . count($staff_data) . " staff records\n";
    }

    /**
     * Seed customers table
     */
    public function seed_customers() {
        echo "Seeding customers table...\n";
        
        $customers_data = array(
            array(
                'plot_buyer_name' => 'Ramesh Sharma',
                'father_name' => 'Mohan Sharma',
                'district' => 'Bangalore Urban',
                'pincode' => '560001',
                'taluk_name' => 'Bangalore South',
                'village_town_name' => 'Bangalore',
                'street_address' => '123 MG Road, Bangalore',
                'alternate_address' => '456 Brigade Road, Bangalore',
                'total_plot_bought' => '2 acres',
                'phone_number_1' => '9876543220',
                'phone_number_2' => '9876543221',
                'email_address' => 'ramesh.sharma@email.com',
                'occupation' => 'Business Owner',
                'annual_income' => 1200000.00,
                'reference_source' => 'Advertisement',
                'emergency_contact_name' => 'Mohan Sharma',
                'emergency_contact_phone' => '9876543222',
                'emergency_contact_relation' => 'Father',
                'id_proof' => 'Aadhar',
                'aadhar_number' => '234567890123',
                'pan_number' => 'PQRST2345U',
                'bank_name' => 'ICICI Bank',
                'bank_account_number' => '2345678901',
                'ifsc_code' => 'ICIC0003456',
                'customer_status' => 'active',
                'notes' => 'Interested in premium properties'
            ),
            array(
                'plot_buyer_name' => 'Priya Patel',
                'father_name' => 'Kiran Patel',
                'district' => 'Mysore',
                'pincode' => '570001',
                'taluk_name' => 'Mysore',
                'village_town_name' => 'Mysore',
                'street_address' => '789 Palace Road, Mysore',
                'alternate_address' => '321 Chamundi Hill Road, Mysore',
                'total_plot_bought' => '1.5 acres',
                'phone_number_1' => '9876543223',
                'phone_number_2' => '9876543224',
                'email_address' => 'priya.patel@email.com',
                'occupation' => 'Software Engineer',
                'annual_income' => 800000.00,
                'reference_source' => 'Friend Referral',
                'emergency_contact_name' => 'Kiran Patel',
                'emergency_contact_phone' => '9876543225',
                'emergency_contact_relation' => 'Father',
                'id_proof' => 'PAN',
                'aadhar_number' => '345678901234',
                'pan_number' => 'UVWXY3456Z',
                'bank_name' => 'Axis Bank',
                'bank_account_number' => '3456789012',
                'ifsc_code' => 'UTIB0004567',
                'customer_status' => 'active',
                'notes' => 'Looking for investment properties'
            ),
            array(
                'plot_buyer_name' => 'Sunil Reddy',
                'father_name' => 'Venkat Reddy',
                'district' => 'Hubli',
                'pincode' => '580001',
                'taluk_name' => 'Hubli',
                'village_town_name' => 'Hubli',
                'street_address' => '456 Station Road, Hubli',
                'alternate_address' => '789 Market Street, Hubli',
                'total_plot_bought' => '3 acres',
                'phone_number_1' => '9876543226',
                'phone_number_2' => '9876543227',
                'email_address' => 'sunil.reddy@email.com',
                'occupation' => 'Doctor',
                'annual_income' => 1500000.00,
                'reference_source' => 'Website',
                'emergency_contact_name' => 'Venkat Reddy',
                'emergency_contact_phone' => '9876543228',
                'emergency_contact_relation' => 'Father',
                'id_proof' => 'Aadhar',
                'aadhar_number' => '456789012345',
                'pan_number' => 'ABCDE4567F',
                'bank_name' => 'SBI',
                'bank_account_number' => '4567890123',
                'ifsc_code' => 'SBIN0005678',
                'customer_status' => 'active',
                'notes' => 'High-value customer, prefers large plots'
            )
        );
        
        foreach ($customers_data as $customer) {
            $this->db->insert('customers', $customer);
        }
        
        echo "✓ Inserted " . count($customers_data) . " customer records\n";
    }

    /**
     * Seed properties table
     */
    public function seed_properties() {
        echo "Seeding properties table...\n";
        
        $properties_data = array(
            array(
                'property_type' => 'garden',
                'garden_name' => 'Green Valley Gardens',
                'district' => 'Bangalore Urban',
                'taluk_name' => 'Bangalore South',
                'village_town_name' => 'Electronic City',
                'size_sqft' => 87120.00, // 2 acres
                'price' => 2500000.00,
                'status' => 'unsold',
                'description' => 'Premium garden plot with excellent connectivity',
                'assigned_staff_id' => 1
            ),
            array(
                'property_type' => 'plot',
                'garden_name' => 'Sunrise Plots',
                'district' => 'Mysore',
                'taluk_name' => 'Mysore',
                'village_town_name' => 'Hebbal',
                'size_sqft' => 65340.00, // 1.5 acres
                'price' => 1800000.00,
                'status' => 'booked',
                'description' => 'Residential plot in peaceful location',
                'assigned_staff_id' => 2
            ),
            array(
                'property_type' => 'garden',
                'garden_name' => 'Royal Gardens',
                'district' => 'Hubli',
                'taluk_name' => 'Hubli',
                'village_town_name' => 'Gokul Road',
                'size_sqft' => 130680.00, // 3 acres
                'price' => 3500000.00,
                'status' => 'sold',
                'description' => 'Large garden plot suitable for farming',
                'assigned_staff_id' => 3
            ),
            array(
                'property_type' => 'house',
                'garden_name' => 'Dream Homes',
                'district' => 'Bangalore Urban',
                'taluk_name' => 'Bangalore North',
                'village_town_name' => 'Yelahanka',
                'size_sqft' => 2400.00,
                'price' => 4500000.00,
                'status' => 'unsold',
                'description' => '3BHK independent house with garden',
                'assigned_staff_id' => 1
            ),
            array(
                'property_type' => 'flat',
                'garden_name' => 'City Apartments',
                'district' => 'Bangalore Urban',
                'taluk_name' => 'Bangalore Central',
                'village_town_name' => 'Rajajinagar',
                'size_sqft' => 1200.00,
                'price' => 3200000.00,
                'status' => 'unsold',
                'description' => '2BHK apartment in prime location',
                'assigned_staff_id' => 2
            )
        );
        
        foreach ($properties_data as $property) {
            $this->db->insert('properties', $property);
        }
        
        echo "✓ Inserted " . count($properties_data) . " property records\n";
    }

    /**
     * Seed registrations table
     */
    public function seed_registrations() {
        echo "Seeding registrations table...\n";
        
        $registrations_data = array(
            array(
                'registration_number' => 'REG' . date('Ymd') . '001',
                'property_id' => 2, // Sunrise Plots (booked)
                'customer_id' => 2, // Priya Patel
                'registration_date' => date('Y-m-d', strtotime('-30 days')),
                'status' => 'active',
                'total_amount' => 1800000.00,
                'paid_amount' => 360000.00 // 20% advance
            ),
            array(
                'registration_number' => 'REG' . date('Ymd') . '002',
                'property_id' => 3, // Royal Gardens (sold)
                'customer_id' => 3, // Sunil Reddy
                'registration_date' => date('Y-m-d', strtotime('-60 days')),
                'status' => 'completed',
                'total_amount' => 3500000.00,
                'paid_amount' => 3500000.00 // Full payment
            )
        );
        
        foreach ($registrations_data as $registration) {
            $this->db->insert('registrations', $registration);
        }
        
        echo "✓ Inserted " . count($registrations_data) . " registration records\n";
    }

    /**
     * Seed transactions table
     */
    public function seed_transactions() {
        echo "Seeding transactions table...\n";
        
        $transactions_data = array(
            array(
                'registration_id' => 1,
                'amount' => 360000.00,
                'payment_type' => 'advance',
                'payment_method' => 'bank_transfer',
                'payment_date' => date('Y-m-d', strtotime('-30 days')),
                'receipt_number' => 'RCP' . date('Ymd') . '001',
                'notes' => 'Initial advance payment for Sunrise Plots'
            ),
            array(
                'registration_id' => 2,
                'amount' => 700000.00,
                'payment_type' => 'advance',
                'payment_method' => 'cheque',
                'payment_date' => date('Y-m-d', strtotime('-60 days')),
                'receipt_number' => 'RCP' . date('Ymd') . '002',
                'notes' => 'Initial advance payment for Royal Gardens'
            ),
            array(
                'registration_id' => 2,
                'amount' => 1400000.00,
                'payment_type' => 'installment',
                'payment_method' => 'bank_transfer',
                'payment_date' => date('Y-m-d', strtotime('-45 days')),
                'receipt_number' => 'RCP' . date('Ymd') . '003',
                'notes' => 'Second installment for Royal Gardens'
            ),
            array(
                'registration_id' => 2,
                'amount' => 1400000.00,
                'payment_type' => 'full_payment',
                'payment_method' => 'bank_transfer',
                'payment_date' => date('Y-m-d', strtotime('-30 days')),
                'receipt_number' => 'RCP' . date('Ymd') . '004',
                'notes' => 'Final payment for Royal Gardens - Property fully paid'
            )
        );
        
        foreach ($transactions_data as $transaction) {
            $this->db->insert('transactions', $transaction);
        }
        
        echo "✓ Inserted " . count($transactions_data) . " transaction records\n";
    }

    /**
     * Seed property assignments table
     */
    public function seed_property_assignments() {
        echo "Seeding property assignments table...\n";
        
        $assignments_data = array(
            array(
                'property_id' => 1,
                'staff_id' => 1,
                'assignment_type' => 'sales',
                'assigned_date' => date('Y-m-d', strtotime('-90 days')),
                'is_active' => 1
            ),
            array(
                'property_id' => 2,
                'staff_id' => 2,
                'assignment_type' => 'sales',
                'assigned_date' => date('Y-m-d', strtotime('-60 days')),
                'is_active' => 1
            ),
            array(
                'property_id' => 3,
                'staff_id' => 3,
                'assignment_type' => 'maintenance',
                'assigned_date' => date('Y-m-d', strtotime('-30 days')),
                'is_active' => 1
            ),
            array(
                'property_id' => 4,
                'staff_id' => 1,
                'assignment_type' => 'sales',
                'assigned_date' => date('Y-m-d', strtotime('-45 days')),
                'is_active' => 1
            ),
            array(
                'property_id' => 5,
                'staff_id' => 2,
                'assignment_type' => 'customer_service',
                'assigned_date' => date('Y-m-d', strtotime('-20 days')),
                'is_active' => 1
            )
        );
        
        foreach ($assignments_data as $assignment) {
            $this->db->insert('property_assignments', $assignment);
        }
        
        echo "✓ Inserted " . count($assignments_data) . " property assignment records\n";
    }

    /**
     * Clear all seeded data
     */
    public function clear_all() {
        echo "Clearing all seeded data...\n";
        echo str_repeat("-", 50) . "\n";
        
        // Clear in reverse order due to foreign key constraints
        $this->db->truncate('property_assignments');
        echo "✓ Cleared property_assignments table\n";
        
        $this->db->truncate('transactions');
        echo "✓ Cleared transactions table\n";
        
        $this->db->truncate('registrations');
        echo "✓ Cleared registrations table\n";
        
        $this->db->truncate('properties');
        echo "✓ Cleared properties table\n";
        
        $this->db->truncate('customers');
        echo "✓ Cleared customers table\n";
        
        $this->db->truncate('staff');
        echo "✓ Cleared staff table\n";
        
        $this->db->truncate('audit_logs');
        echo "✓ Cleared audit_logs table\n";
        
        echo str_repeat("-", 50) . "\n";
        echo "All data cleared successfully!\n";
    }

    /**
     * Show seeding status
     */
    public function status() {
        echo "Database Seeding Status:\n";
        echo str_repeat("-", 50) . "\n";
        
        $tables = array('staff', 'customers', 'properties', 'registrations', 'transactions', 'property_assignments', 'audit_logs');
        
        foreach ($tables as $table) {
            if ($this->db->table_exists($table)) {
                $count = $this->db->count_all($table);
                printf("%-20s: %d records\n", ucfirst($table), $count);
            } else {
                printf("%-20s: Table not found\n", ucfirst($table));
            }
        }
        
        echo str_repeat("-", 50) . "\n";
    }

    /**
     * Show help information
     */
    public function help() {
        echo "Seeder Controller Help\n";
        echo str_repeat("=", 50) . "\n";
        echo "Available commands:\n\n";
        echo "seed_all             - Seed all tables with test data\n";
        echo "seed_staff           - Seed only staff table\n";
        echo "seed_customers       - Seed only customers table\n";
        echo "seed_properties      - Seed only properties table\n";
        echo "seed_registrations   - Seed only registrations table\n";
        echo "seed_transactions    - Seed only transactions table\n";
        echo "seed_property_assignments - Seed only property assignments table\n";
        echo "clear_all            - Clear all seeded data\n";
        echo "status               - Show current seeding status\n";
        echo "help                 - Show this help message\n\n";
        echo "Examples:\n";
        echo "  /seeder_controller/seed_all\n";
        echo "  /seeder_controller/seed_staff\n";
        echo "  /seeder_controller/clear_all\n";
        echo str_repeat("=", 50) . "\n";
    }
}