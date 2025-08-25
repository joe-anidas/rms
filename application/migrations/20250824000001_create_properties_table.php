<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_properties_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'property_type' => array(
                'type' => 'ENUM',
                'constraint' => array('garden', 'plot', 'house', 'flat'),
                'null' => FALSE,
            ),
            'garden_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE,
            ),
            'district' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'taluk_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'village_town_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'size_sqft' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
            ),
            'price' => array(
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => TRUE,
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('unsold', 'booked', 'sold'),
                'default' => 'unsold',
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'assigned_staff_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
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
        $this->dbforge->add_key('assigned_staff_id');
        $this->dbforge->add_key('status');
        $this->dbforge->add_key('property_type');
        $this->dbforge->create_table('properties');
        
        // Add foreign key constraint (if staff table exists)
        $this->db->query('ALTER TABLE properties ADD CONSTRAINT fk_properties_staff FOREIGN KEY (assigned_staff_id) REFERENCES staff(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down() {
        $this->dbforge->drop_table('properties');
    }
}