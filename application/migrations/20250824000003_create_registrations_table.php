<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_registrations_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'registration_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => FALSE,
                'unique' => TRUE,
            ),
            'property_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'customer_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'registration_date' => array(
                'type' => 'DATE',
                'null' => FALSE,
            ),
            'agreement_path' => array(
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => TRUE,
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('active', 'completed', 'cancelled'),
                'default' => 'active',
            ),
            'total_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => TRUE,
            ),
            'paid_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ),
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('registration_number', TRUE);
        $this->dbforge->add_key('property_id');
        $this->dbforge->add_key('customer_id');
        $this->dbforge->add_key('status');
        $this->dbforge->create_table('registrations');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE registrations ADD CONSTRAINT fk_registrations_property FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE registrations ADD CONSTRAINT fk_registrations_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down() {
        $this->dbforge->drop_table('registrations');
    }
}