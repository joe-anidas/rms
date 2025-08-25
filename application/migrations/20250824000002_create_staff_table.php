<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_staff_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'employee_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE,
            ),
            'father_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'date_of_birth' => array(
                'type' => 'DATE',
                'null' => TRUE,
            ),
            'gender' => array(
                'type' => 'ENUM',
                'constraint' => array('Male', 'Female', 'Other'),
                'null' => TRUE,
            ),
            'marital_status' => array(
                'type' => 'ENUM',
                'constraint' => array('Single', 'Married', 'Divorced', 'Widowed'),
                'null' => TRUE,
            ),
            'blood_group' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE,
            ),
            'contact_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'alternate_contact' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'email_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'permanent_address' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'current_address' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'emergency_contact_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'emergency_contact_phone' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'emergency_contact_relation' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'id_proof_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'id_proof_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'designation' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'department' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'joining_date' => array(
                'type' => 'DATE',
                'null' => TRUE,
            ),
            'salary' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
            ),
            'bank_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'bank_account_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'ifsc_code' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'pan_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'aadhar_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
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
        $this->dbforge->add_key('employee_name');
        $this->dbforge->add_key('designation');
        $this->dbforge->add_key('department');
        $this->dbforge->create_table('staff');
    }

    public function down() {
        $this->dbforge->drop_table('staff');
    }
}