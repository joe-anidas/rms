<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_performance_indexes extends CI_Migration {

    public function up() {
        // Add indexes for customers table
        $this->db->query("ALTER TABLE customers ADD INDEX idx_plot_buyer_name (plot_buyer_name)");
        $this->db->query("ALTER TABLE customers ADD INDEX idx_phone_number_1 (phone_number_1)");
        $this->db->query("ALTER TABLE customers ADD INDEX idx_phone_number_2 (phone_number_2)");
        $this->db->query("ALTER TABLE customers ADD INDEX idx_aadhar_number (aadhar_number)");
        $this->db->query("ALTER TABLE customers ADD INDEX idx_created_at (created_at)");
        $this->db->query("ALTER TABLE customers ADD INDEX idx_district (district)");
        
        // Add indexes for properties table
        $this->db->query("ALTER TABLE properties ADD INDEX idx_status (status)");
        $this->db->query("ALTER TABLE properties ADD INDEX idx_property_type (property_type)");
        $this->db->query("ALTER TABLE properties ADD INDEX idx_garden_name (garden_name)");
        $this->db->query("ALTER TABLE properties ADD INDEX idx_district (district)");
        $this->db->query("ALTER TABLE properties ADD INDEX idx_assigned_staff_id (assigned_staff_id)");
        $this->db->query("ALTER TABLE properties ADD INDEX idx_created_at (created_at)");
        $this->db->query("ALTER TABLE properties ADD INDEX idx_price (price)");
        $this->db->query("ALTER TABLE properties ADD INDEX idx_size_sqft (size_sqft)");
        
        // Add indexes for staff table
        $this->db->query("ALTER TABLE staff ADD INDEX idx_employee_name (employee_name)");
        $this->db->query("ALTER TABLE staff ADD INDEX idx_designation (designation)");
        $this->db->query("ALTER TABLE staff ADD INDEX idx_created_at (created_at)");
        
        // Add indexes for registrations table (if exists)
        if ($this->db->table_exists('registrations')) {
            $this->db->query("ALTER TABLE registrations ADD INDEX idx_property_id (property_id)");
            $this->db->query("ALTER TABLE registrations ADD INDEX idx_customer_id (customer_id)");
            $this->db->query("ALTER TABLE registrations ADD INDEX idx_registration_number (registration_number)");
            $this->db->query("ALTER TABLE registrations ADD INDEX idx_status (status)");
            $this->db->query("ALTER TABLE registrations ADD INDEX idx_registration_date (registration_date)");
            $this->db->query("ALTER TABLE registrations ADD INDEX idx_created_at (created_at)");
        }
        
        // Add indexes for transactions table (if exists)
        if ($this->db->table_exists('transactions')) {
            $this->db->query("ALTER TABLE transactions ADD INDEX idx_registration_id (registration_id)");
            $this->db->query("ALTER TABLE transactions ADD INDEX idx_payment_type (payment_type)");
            $this->db->query("ALTER TABLE transactions ADD INDEX idx_payment_date (payment_date)");
            $this->db->query("ALTER TABLE transactions ADD INDEX idx_receipt_number (receipt_number)");
            $this->db->query("ALTER TABLE transactions ADD INDEX idx_created_at (created_at)");
        }
        
        // Add indexes for property_assignments table (if exists)
        if ($this->db->table_exists('property_assignments')) {
            $this->db->query("ALTER TABLE property_assignments ADD INDEX idx_property_id (property_id)");
            $this->db->query("ALTER TABLE property_assignments ADD INDEX idx_staff_id (staff_id)");
            $this->db->query("ALTER TABLE property_assignments ADD INDEX idx_assignment_type (assignment_type)");
            $this->db->query("ALTER TABLE property_assignments ADD INDEX idx_is_active (is_active)");
            $this->db->query("ALTER TABLE property_assignments ADD INDEX idx_assigned_date (assigned_date)");
        }
        
        // Add indexes for audit_logs table (if exists)
        if ($this->db->table_exists('audit_logs')) {
            $this->db->query("ALTER TABLE audit_logs ADD INDEX idx_table_name (table_name)");
            $this->db->query("ALTER TABLE audit_logs ADD INDEX idx_record_id (record_id)");
            $this->db->query("ALTER TABLE audit_logs ADD INDEX idx_action (action)");
            $this->db->query("ALTER TABLE audit_logs ADD INDEX idx_user_id (user_id)");
            $this->db->query("ALTER TABLE audit_logs ADD INDEX idx_created_at (created_at)");
        }
        
        // Add composite indexes for common queries
        $this->db->query("ALTER TABLE properties ADD INDEX idx_status_type (status, property_type)");
        $this->db->query("ALTER TABLE properties ADD INDEX idx_status_created (status, created_at)");
        
        if ($this->db->table_exists('registrations')) {
            $this->db->query("ALTER TABLE registrations ADD INDEX idx_customer_status (customer_id, status)");
            $this->db->query("ALTER TABLE registrations ADD INDEX idx_property_status (property_id, status)");
        }
        
        if ($this->db->table_exists('transactions')) {
            $this->db->query("ALTER TABLE transactions ADD INDEX idx_registration_date (registration_id, payment_date)");
        }
    }

    public function down() {
        // Remove indexes for customers table
        $this->db->query("ALTER TABLE customers DROP INDEX idx_plot_buyer_name");
        $this->db->query("ALTER TABLE customers DROP INDEX idx_phone_number_1");
        $this->db->query("ALTER TABLE customers DROP INDEX idx_phone_number_2");
        $this->db->query("ALTER TABLE customers DROP INDEX idx_aadhar_number");
        $this->db->query("ALTER TABLE customers DROP INDEX idx_created_at");
        $this->db->query("ALTER TABLE customers DROP INDEX idx_district");
        
        // Remove indexes for properties table
        $this->db->query("ALTER TABLE properties DROP INDEX idx_status");
        $this->db->query("ALTER TABLE properties DROP INDEX idx_property_type");
        $this->db->query("ALTER TABLE properties DROP INDEX idx_garden_name");
        $this->db->query("ALTER TABLE properties DROP INDEX idx_district");
        $this->db->query("ALTER TABLE properties DROP INDEX idx_assigned_staff_id");
        $this->db->query("ALTER TABLE properties DROP INDEX idx_created_at");
        $this->db->query("ALTER TABLE properties DROP INDEX idx_price");
        $this->db->query("ALTER TABLE properties DROP INDEX idx_size_sqft");
        $this->db->query("ALTER TABLE properties DROP INDEX idx_status_type");
        $this->db->query("ALTER TABLE properties DROP INDEX idx_status_created");
        
        // Remove indexes for staff table
        $this->db->query("ALTER TABLE staff DROP INDEX idx_employee_name");
        $this->db->query("ALTER TABLE staff DROP INDEX idx_designation");
        $this->db->query("ALTER TABLE staff DROP INDEX idx_created_at");
        
        // Remove indexes for other tables if they exist
        if ($this->db->table_exists('registrations')) {
            $this->db->query("ALTER TABLE registrations DROP INDEX idx_property_id");
            $this->db->query("ALTER TABLE registrations DROP INDEX idx_customer_id");
            $this->db->query("ALTER TABLE registrations DROP INDEX idx_registration_number");
            $this->db->query("ALTER TABLE registrations DROP INDEX idx_status");
            $this->db->query("ALTER TABLE registrations DROP INDEX idx_registration_date");
            $this->db->query("ALTER TABLE registrations DROP INDEX idx_created_at");
            $this->db->query("ALTER TABLE registrations DROP INDEX idx_customer_status");
            $this->db->query("ALTER TABLE registrations DROP INDEX idx_property_status");
        }
        
        if ($this->db->table_exists('transactions')) {
            $this->db->query("ALTER TABLE transactions DROP INDEX idx_registration_id");
            $this->db->query("ALTER TABLE transactions DROP INDEX idx_payment_type");
            $this->db->query("ALTER TABLE transactions DROP INDEX idx_payment_date");
            $this->db->query("ALTER TABLE transactions DROP INDEX idx_receipt_number");
            $this->db->query("ALTER TABLE transactions DROP INDEX idx_created_at");
            $this->db->query("ALTER TABLE transactions DROP INDEX idx_registration_date");
        }
        
        if ($this->db->table_exists('property_assignments')) {
            $this->db->query("ALTER TABLE property_assignments DROP INDEX idx_property_id");
            $this->db->query("ALTER TABLE property_assignments DROP INDEX idx_staff_id");
            $this->db->query("ALTER TABLE property_assignments DROP INDEX idx_assignment_type");
            $this->db->query("ALTER TABLE property_assignments DROP INDEX idx_is_active");
            $this->db->query("ALTER TABLE property_assignments DROP INDEX idx_assigned_date");
        }
        
        if ($this->db->table_exists('audit_logs')) {
            $this->db->query("ALTER TABLE audit_logs DROP INDEX idx_table_name");
            $this->db->query("ALTER TABLE audit_logs DROP INDEX idx_record_id");
            $this->db->query("ALTER TABLE audit_logs DROP INDEX idx_action");
            $this->db->query("ALTER TABLE audit_logs DROP INDEX idx_user_id");
            $this->db->query("ALTER TABLE audit_logs DROP INDEX idx_created_at");
        }
    }
}