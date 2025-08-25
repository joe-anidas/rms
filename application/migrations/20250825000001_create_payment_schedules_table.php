<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_payment_schedules_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'registration_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'installment_number' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
            ),
            'due_date' => array(
                'type' => 'DATE',
                'null' => FALSE,
            ),
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => FALSE,
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('pending', 'paid', 'overdue', 'cancelled'),
                'default' => 'pending',
            ),
            'paid_date' => array(
                'type' => 'DATE',
                'null' => TRUE,
            ),
            'notes' => array(
                'type' => 'TEXT',
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
        $this->dbforge->add_key('registration_id');
        $this->dbforge->add_key('due_date');
        $this->dbforge->add_key('status');
        $this->dbforge->create_table('payment_schedules');
        
        // Add foreign key constraint
        $this->db->query('ALTER TABLE payment_schedules ADD CONSTRAINT fk_payment_schedules_registration FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down() {
        $this->dbforge->drop_table('payment_schedules');
    }
}