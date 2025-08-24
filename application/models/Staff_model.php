<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function create_staff_table() {
        try {
            // Check if table already exists
            if ($this->db->table_exists('staff')) {
                error_log('Table staff already exists');
                return true;
            }
            
            $sql = "CREATE TABLE IF NOT EXISTS staff (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                employee_name VARCHAR(255) NOT NULL,
                father_name VARCHAR(255),
                date_of_birth DATE,
                gender ENUM('Male', 'Female', 'Other'),
                marital_status ENUM('Single', 'Married', 'Divorced', 'Widowed'),
                blood_group VARCHAR(10),
                contact_number VARCHAR(20),
                alternate_contact VARCHAR(20),
                email_address VARCHAR(255),
                permanent_address TEXT,
                current_address TEXT,
                emergency_contact_name VARCHAR(255),
                emergency_contact_phone VARCHAR(20),
                emergency_contact_relation VARCHAR(100),
                id_proof_type VARCHAR(50),
                id_proof_number VARCHAR(100),
                designation VARCHAR(100),
                department VARCHAR(100),
                joining_date DATE,
                salary DECIMAL(10,2),
                bank_name VARCHAR(100),
                bank_account_number VARCHAR(50),
                ifsc_code VARCHAR(20),
                pan_number VARCHAR(20),
                aadhar_number VARCHAR(20),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $result = $this->db->query($sql);
            error_log('Staff table creation result: ' . ($result ? 'success' : 'failed'));
            
            if (!$result) {
                error_log('DB Error: ' . print_r($this->db->error(), true));
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log('Error creating staff table: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_staff($data) {
        try {
            error_log('Attempting to insert staff data: ' . print_r($data, true));
            
            // Check if table exists
            if (!$this->db->table_exists('staff')) {
                error_log('Table staff does not exist, creating it first');
                $this->create_staff_table();
            }
            
            // Validate required field
            if (empty($data['employee_name'])) {
                error_log('Error: employee_name is empty');
                return false;
            }
            
            // Insert staff data
            $result = $this->db->insert('staff', $data);
            error_log('Insert staff result: ' . ($result ? 'success' : 'failed'));
            
            if (!$result) {
                $db_error = $this->db->error();
                error_log('DB Error: ' . print_r($db_error, true));
                
                // Try to get more specific error information
                if (isset($db_error['code'])) {
                    error_log('DB Error Code: ' . $db_error['code']);
                }
                if (isset($db_error['message'])) {
                    error_log('DB Error Message: ' . $db_error['message']);
                }
            } else {
                $insert_id = $this->db->insert_id();
                error_log('Staff inserted successfully with ID: ' . $insert_id);
            }
            
            return $result;
        } catch (Exception $e) {
            error_log('Exception inserting staff: ' . $e->getMessage());
            error_log('Exception trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    public function get_all_staff() {
        try {
            error_log('get_all_staff method called');
            
            // Check if table exists
            $table_exists = $this->db->table_exists('staff');
            error_log('Staff table exists: ' . ($table_exists ? 'yes' : 'no'));
            
            if (!$table_exists) {
                error_log('Creating staff table as it does not exist');
                $this->create_staff_table();
            }
            
            $this->db->order_by('created_at', 'DESC');
            $result = $this->db->get('staff');
            
            error_log('Staff query executed. Rows found: ' . $result->num_rows());
            
            if ($result->num_rows() > 0) {
                $staff = $result->result();
                error_log('Staff returned: ' . count($staff));
                return $staff;
            } else {
                error_log('No staff found in table');
                return array();
            }
            
        } catch (Exception $e) {
            error_log('Error in get_all_staff: ' . $e->getMessage());
            return array();
        }
    }

    public function get_staff_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('staff')->row();
    }

    public function update_staff($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('staff', $data);
    }

    public function delete_staff($id) {
        $this->db->where('id', $id);
        return $this->db->delete('staff');
    }
}
