<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_audit_logs_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'table_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => FALSE,
            ),
            'record_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ),
            'action' => array(
                'type' => 'ENUM',
                'constraint' => array('INSERT', 'UPDATE', 'DELETE'),
                'null' => FALSE,
            ),
            'old_values' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'new_values' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'user_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => TRUE,
            ),
            'user_agent' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ),
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('table_name');
        $this->dbforge->add_key('record_id');
        $this->dbforge->add_key('action');
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('created_at');
        $this->dbforge->create_table('audit_logs');
    }

    public function down() {
        $this->dbforge->drop_table('audit_logs');
    }
}