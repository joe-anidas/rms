<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_transactions_table extends CI_Migration {

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
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => FALSE,
            ),
            'payment_type' => array(
                'type' => 'ENUM',
                'constraint' => array('advance', 'installment', 'full_payment'),
                'null' => FALSE,
            ),
            'payment_method' => array(
                'type' => 'ENUM',
                'constraint' => array('cash', 'cheque', 'bank_transfer', 'online'),
                'null' => FALSE,
            ),
            'payment_date' => array(
                'type' => 'DATE',
                'null' => FALSE,
            ),
            'receipt_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
                'unique' => TRUE,
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
        $this->dbforge->add_key('payment_date');
        $this->dbforge->add_key('payment_type');
        $this->dbforge->add_key('receipt_number', TRUE);
        $this->dbforge->create_table('transactions');
        
        // Add foreign key constraint
        $this->db->query('ALTER TABLE transactions ADD CONSTRAINT fk_transactions_registration FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down() {
        $this->dbforge->drop_table('transactions');
    }
}