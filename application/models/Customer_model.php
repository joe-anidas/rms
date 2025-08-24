<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function create_customer_table() {
        try {
            // Check if table already exists
            if ($this->db->table_exists('customers')) {
                error_log('Table customers already exists');
                return true;
            }
            
            $sql = "CREATE TABLE IF NOT EXISTS customers (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                plot_buyer_name VARCHAR(255) NOT NULL,
                father_name VARCHAR(255),
                district VARCHAR(100),
                pincode VARCHAR(10),
                taluk_name VARCHAR(100),
                village_town_name VARCHAR(100),
                street_address TEXT,
                total_plot_bought VARCHAR(50),
                phone_number_1 VARCHAR(20),
                phone_number_2 VARCHAR(20),
                id_proof VARCHAR(50),
                aadhar_number VARCHAR(20),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $result = $this->db->query($sql);
            error_log('Table creation result: ' . ($result ? 'success' : 'failed'));
            
            if (!$result) {
                error_log('DB Error: ' . print_r($this->db->error(), true));
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log('Error creating table: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_customer($data) {
        try {
            error_log('Attempting to insert customer data: ' . print_r($data, true));
            
            // Check if table exists
            if (!$this->db->table_exists('customers')) {
                error_log('Table customers does not exist, creating it first');
                $this->create_customer_table();
            }
            
            // Validate required field
            if (empty($data['plot_buyer_name'])) {
                error_log('Error: plot_buyer_name is empty');
                return false;
            }
            
            // Insert customer data
            $result = $this->db->insert('customers', $data);
            error_log('Insert customer result: ' . ($result ? 'success' : 'failed'));
            
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
                error_log('Customer inserted successfully with ID: ' . $insert_id);
            }
            
            return $result;
        } catch (Exception $e) {
            error_log('Exception inserting customer: ' . $e->getMessage());
            error_log('Exception trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    public function get_all_customers() {
        try {
            error_log('get_all_customers method called');
            
            // Check if table exists
            $table_exists = $this->db->table_exists('customers');
            error_log('Table exists: ' . ($table_exists ? 'yes' : 'no'));
            
            if (!$table_exists) {
                error_log('Creating table as it does not exist');
                $this->create_customer_table();
            }
            
            $this->db->order_by('created_at', 'DESC');
            $result = $this->db->get('customers');
            
            error_log('Query executed. Rows found: ' . $result->num_rows());
            
            if ($result->num_rows() > 0) {
                $customers = $result->result();
                error_log('Customers returned: ' . count($customers));
                return $customers;
            } else {
                error_log('No customers found in table');
                return array();
            }
            
        } catch (Exception $e) {
            error_log('Error in get_all_customers: ' . $e->getMessage());
            return array();
        }
    }

    public function get_customer_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('customers')->row();
    }

    public function update_customer($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('customers', $data);
    }

    public function delete_customer($id) {
        $this->db->where('id', $id);
        return $this->db->delete('customers');
    }
}
