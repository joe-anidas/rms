<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_property_assignments_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'property_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'staff_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'assignment_type' => array(
                'type' => 'ENUM',
                'constraint' => array('sales', 'maintenance', 'customer_service'),
                'null' => FALSE,
            ),
            'assigned_date' => array(
                'type' => 'DATE',
                'null' => FALSE,
            ),
            'end_date' => array(
                'type' => 'DATE',
                'null' => TRUE,
            ),
            'is_active' => array(
                'type' => 'BOOLEAN',
                'default' => TRUE,
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
        $this->dbforge->add_key('property_id');
        $this->dbforge->add_key('staff_id');
        $this->dbforge->add_key('assignment_type');
        $this->dbforge->add_key('is_active');
        $this->dbforge->create_table('property_assignments');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE property_assignments ADD CONSTRAINT fk_assignments_property FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE property_assignments ADD CONSTRAINT fk_assignments_staff FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down() {
        $this->dbforge->drop_table('property_assignments');
    }
}