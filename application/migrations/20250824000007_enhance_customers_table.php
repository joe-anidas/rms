<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Enhance_customers_table extends CI_Migration {

    public function up() {
        // Add new fields to existing customers table
        $fields = array(
            'email_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
                'after' => 'phone_number_2'
            ),
            'alternate_address' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'after' => 'street_address'
            ),
            'occupation' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
                'after' => 'email_address'
            ),
            'annual_income' => array(
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => TRUE,
                'after' => 'occupation'
            ),
            'reference_source' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
                'after' => 'annual_income'
            ),
            'emergency_contact_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
                'after' => 'reference_source'
            ),
            'emergency_contact_phone' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
                'after' => 'emergency_contact_name'
            ),
            'emergency_contact_relation' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
                'after' => 'emergency_contact_phone'
            ),
            'pan_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
                'after' => 'aadhar_number'
            ),
            'bank_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
                'after' => 'pan_number'
            ),
            'bank_account_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
                'after' => 'bank_name'
            ),
            'ifsc_code' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
                'after' => 'bank_account_number'
            ),
            'customer_status' => array(
                'type' => 'ENUM',
                'constraint' => array('active', 'inactive', 'blacklisted'),
                'default' => 'active',
                'after' => 'ifsc_code'
            ),
            'notes' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'after' => 'customer_status'
            )
        );
        
        $this->dbforge->add_column('customers', $fields);
        
        // Add indexes for better performance
        $this->db->query('ALTER TABLE customers ADD INDEX idx_email (email_address)');
        $this->db->query('ALTER TABLE customers ADD INDEX idx_customer_status (customer_status)');
        $this->db->query('ALTER TABLE customers ADD INDEX idx_pan_number (pan_number)');
    }

    public function down() {
        // Remove the added columns
        $this->dbforge->drop_column('customers', 'email_address');
        $this->dbforge->drop_column('customers', 'alternate_address');
        $this->dbforge->drop_column('customers', 'occupation');
        $this->dbforge->drop_column('customers', 'annual_income');
        $this->dbforge->drop_column('customers', 'reference_source');
        $this->dbforge->drop_column('customers', 'emergency_contact_name');
        $this->dbforge->drop_column('customers', 'emergency_contact_phone');
        $this->dbforge->drop_column('customers', 'emergency_contact_relation');
        $this->dbforge->drop_column('customers', 'pan_number');
        $this->dbforge->drop_column('customers', 'bank_name');
        $this->dbforge->drop_column('customers', 'bank_account_number');
        $this->dbforge->drop_column('customers', 'ifsc_code');
        $this->dbforge->drop_column('customers', 'customer_status');
        $this->dbforge->drop_column('customers', 'notes');
    }
}