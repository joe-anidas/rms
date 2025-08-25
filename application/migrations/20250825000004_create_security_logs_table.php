<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Create Security Logs Table
 * Creates table for logging security events and audit trail
 * Requirements: 7.1, 7.4
 */
class Migration_Create_security_logs_table extends CI_Migration {

    public function up() {
        // Create security_logs table
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'event_type' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE,
                'comment' => 'Type of security event (login_failed, csrf_violation, etc.)'
            ),
            'severity' => array(
                'type' => 'ENUM',
                'constraint' => array('low', 'medium', 'high', 'critical'),
                'default' => 'medium',
                'null' => FALSE
            ),
            'message' => array(
                'type' => 'TEXT',
                'null' => FALSE,
                'comment' => 'Detailed message about the security event'
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => TRUE,
                'comment' => 'IP address of the request (supports IPv6)'
            ),
            'user_agent' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'User agent string from the request'
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'comment' => 'User ID if authenticated'
            ),
            'session_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => TRUE,
                'comment' => 'Session ID associated with the event'
            ),
            'request_uri' => array(
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => TRUE,
                'comment' => 'URI that was requested'
            ),
            'request_method' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE,
                'comment' => 'HTTP method (GET, POST, etc.)'
            ),
            'context_data' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Additional context data in JSON format'
            ),
            'resolved' => array(
                'type' => 'BOOLEAN',
                'default' => FALSE,
                'null' => FALSE,
                'comment' => 'Whether the security issue has been resolved'
            ),
            'resolved_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'comment' => 'User ID who resolved the issue'
            ),
            'resolved_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE,
                'comment' => 'When the issue was resolved'
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP'
            )
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('event_type');
        $this->dbforge->add_key('severity');
        $this->dbforge->add_key('ip_address');
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('created_at');
        $this->dbforge->add_key(array('event_type', 'created_at'));

        $this->dbforge->create_table('security_logs', TRUE);

        // Create file_upload_logs table for tracking file uploads
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'original_filename' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
                'comment' => 'Original filename as uploaded'
            ),
            'stored_filename' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
                'comment' => 'Filename as stored on server'
            ),
            'file_path' => array(
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => TRUE,
                'comment' => 'Full path where file is stored'
            ),
            'file_size' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
                'comment' => 'File size in bytes'
            ),
            'mime_type' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
                'comment' => 'MIME type of the uploaded file'
            ),
            'file_extension' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE,
                'comment' => 'File extension'
            ),
            'upload_status' => array(
                'type' => 'ENUM',
                'constraint' => array('success', 'failed', 'blocked', 'quarantined'),
                'default' => 'success',
                'null' => FALSE
            ),
            'security_scan_result' => array(
                'type' => 'ENUM',
                'constraint' => array('clean', 'suspicious', 'malicious', 'not_scanned'),
                'default' => 'not_scanned',
                'null' => FALSE
            ),
            'scan_details' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Details from security scan'
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => TRUE
            ),
            'user_agent' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'session_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => TRUE
            ),
            'related_table' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
                'comment' => 'Table the file is associated with'
            ),
            'related_record_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'comment' => 'Record ID the file is associated with'
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP'
            )
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('upload_status');
        $this->dbforge->add_key('security_scan_result');
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('created_at');
        $this->dbforge->add_key(array('related_table', 'related_record_id'));

        $this->dbforge->create_table('file_upload_logs', TRUE);

        // Create rate_limit_logs table for tracking rate limiting
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'identifier' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
                'comment' => 'Identifier for rate limiting (IP, user ID, etc.)'
            ),
            'identifier_type' => array(
                'type' => 'ENUM',
                'constraint' => array('ip', 'user', 'session', 'api_key'),
                'default' => 'ip',
                'null' => FALSE
            ),
            'action_type' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE,
                'comment' => 'Type of action being rate limited'
            ),
            'request_count' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'default' => 1,
                'null' => FALSE
            ),
            'limit_exceeded' => array(
                'type' => 'BOOLEAN',
                'default' => FALSE,
                'null' => FALSE
            ),
            'time_window_start' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'comment' => 'Start of the rate limiting time window'
            ),
            'time_window_end' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'comment' => 'End of the rate limiting time window'
            ),
            'blocked_until' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE,
                'comment' => 'When the identifier will be unblocked'
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP'
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            )
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('identifier');
        $this->dbforge->add_key('identifier_type');
        $this->dbforge->add_key('action_type');
        $this->dbforge->add_key('limit_exceeded');
        $this->dbforge->add_key('time_window_start');
        $this->dbforge->add_key(array('identifier', 'action_type'));

        $this->dbforge->create_table('rate_limit_logs', TRUE);

        echo "Security logging tables created successfully.\n";
    }

    public function down() {
        $this->dbforge->drop_table('security_logs', TRUE);
        $this->dbforge->drop_table('file_upload_logs', TRUE);
        $this->dbforge->drop_table('rate_limit_logs', TRUE);
        
        echo "Security logging tables dropped successfully.\n";
    }
}