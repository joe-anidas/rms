<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(['enhanced_validation', 'database_error_handler', 'audit_logger']);
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
        $this->load->library('secure_database');
        $this->load->helper('security');
        
        try {
            // Validate customer data
            $validation_result = $this->enhanced_validation->validate_customer($data);
            if (!$validation_result['is_valid']) {
                throw new Exception('Validation failed: ' . implode(', ', $validation_result['errors']));
            }
            
            // Sanitize input data with enhanced security
            $sanitized_data = array();
            foreach ($data as $key => $value) {
                // Validate field name for SQL injection
                if (!$this->enhanced_validation->validate_sql_safety($key)) {
                    throw new Exception('Invalid field name detected: ' . $key);
                }
                
                switch ($key) {
                    case 'email_address':
                        $sanitized_data[$key] = $this->enhanced_validation->sanitize_input($value, 'email');
                        break;
                    case 'phone_number_1':
                    case 'phone_number_2':
                    case 'emergency_contact_phone':
                        $sanitized_data[$key] = $this->enhanced_validation->sanitize_input($value, 'phone');
                        break;
                    case 'annual_income':
                        $sanitized_data[$key] = $this->enhanced_validation->sanitize_input($value, 'numeric');
                        break;
                    case 'aadhar_number':
                    case 'pan_number':
                        // Validate sensitive data patterns
                        if (!$this->enhanced_validation->validate_xss_safety($value)) {
                            throw new Exception('XSS pattern detected in sensitive field: ' . $key);
                        }
                        $sanitized_data[$key] = $this->enhanced_validation->sanitize_input($value, 'string');
                        break;
                    default:
                        // Validate for XSS and SQL injection
                        if (!$this->enhanced_validation->validate_xss_safety($value)) {
                            throw new Exception('XSS pattern detected in field: ' . $key);
                        }
                        if (!$this->enhanced_validation->validate_sql_safety($value)) {
                            throw new Exception('SQL injection pattern detected in field: ' . $key);
                        }
                        $sanitized_data[$key] = $this->enhanced_validation->sanitize_input($value, 'string');
                        break;
                }
            }
            
            // Check if table exists
            if (!$this->db->table_exists('customers')) {
                $this->create_customer_table();
            }
            
            // Use secure database insert
            $customer_id = $this->secure_database->secure_insert('customers', $sanitized_data);
            
            if ($customer_id) {
                // Log the creation with masked sensitive data
                $log_data = $sanitized_data;
                if (isset($log_data['aadhar_number'])) {
                    $log_data['aadhar_number'] = mask_sensitive_data($log_data['aadhar_number'], 'aadhar');
                }
                if (isset($log_data['pan_number'])) {
                    $log_data['pan_number'] = mask_sensitive_data($log_data['pan_number'], 'pan');
                }
                
                log_security_event(
                    'customer_created',
                    'New customer created successfully',
                    array(
                        'customer_id' => $customer_id,
                        'customer_name' => $sanitized_data['plot_buyer_name'] ?? 'Unknown',
                        'operation' => 'customer_creation'
                    ),
                    'low'
                );
                
                return $customer_id;
            } else {
                throw new Exception('Failed to insert customer data');
            }
            
        } catch (Exception $e) {
            // Log security event for failed customer creation
            log_security_event(
                'customer_creation_failed',
                'Customer creation failed: ' . $e->getMessage(),
                array(
                    'error' => $e->getMessage(),
                    'data_keys' => array_keys($data)
                ),
                'medium'
            );
            
            throw $e;
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
        return $this->database_error_handler->execute_with_error_handling(
            function() use ($id, $data) {
                // Validate customer data
                $validation_result = $this->enhanced_validation->validate_customer($data);
                if (!$validation_result['is_valid']) {
                    throw new Exception('Validation failed: ' . implode(', ', $validation_result['errors']));
                }
                
                // Get old data for audit trail
                $this->db->where('id', $id);
                $old_data = $this->db->get('customers')->row_array();
                
                if (!$old_data) {
                    throw new Exception('Customer not found');
                }
                
                // Sanitize input data
                $sanitized_data = array();
                foreach ($data as $key => $value) {
                    switch ($key) {
                        case 'email_address':
                            $sanitized_data[$key] = $this->enhanced_validation->sanitize_input($value, 'email');
                            break;
                        case 'phone_number_1':
                        case 'phone_number_2':
                        case 'emergency_contact_phone':
                            $sanitized_data[$key] = $this->enhanced_validation->sanitize_input($value, 'phone');
                            break;
                        case 'annual_income':
                            $sanitized_data[$key] = $this->enhanced_validation->sanitize_input($value, 'numeric');
                            break;
                        default:
                            $sanitized_data[$key] = $this->enhanced_validation->sanitize_input($value, 'string');
                            break;
                    }
                }
                
                // Validate database constraints
                $constraint_check = $this->database_error_handler->validate_constraints(
                    'customers', 
                    $sanitized_data, 
                    'update',
                    $id
                );
                
                if (!$constraint_check['is_valid']) {
                    throw new Exception('Constraint validation failed: ' . implode(', ', $constraint_check['errors']));
                }
                
                // Update customer
                $this->db->where('id', $id);
                $result = $this->db->update('customers', $sanitized_data);
                
                if ($result) {
                    // Log the update
                    $this->audit_logger->log_customer_change(
                        $id,
                        'update',
                        $old_data,
                        $sanitized_data,
                        array('operation' => 'customer_update')
                    );
                    
                    return true;
                } else {
                    throw new Exception('Failed to update customer data');
                }
            },
            'update_customer',
            array('customer_id' => $id, 'data_keys' => array_keys($data))
        );
    }

    public function delete_customer($id) {
        return $this->database_error_handler->execute_with_error_handling(
            function() use ($id) {
                // Get customer data for audit trail
                $this->db->where('id', $id);
                $customer_data = $this->db->get('customers')->row_array();
                
                if (!$customer_data) {
                    throw new Exception('Customer not found');
                }
                
                // Check if customer has active registrations before deletion
                if ($this->db->table_exists('registrations')) {
                    $this->db->where('customer_id', $id);
                    $this->db->where('status !=', 'cancelled');
                    $active_registrations = $this->db->count_all_results('registrations');
                    
                    if ($active_registrations > 0) {
                        throw new Exception('Cannot delete customer with active registrations. Found ' . $active_registrations . ' active registration(s).');
                    }
                }
                
                // Check for other dependencies
                $dependencies = array();
                
                // Check transactions through registrations
                if ($this->db->table_exists('transactions') && $this->db->table_exists('registrations')) {
                    $this->db->select('COUNT(t.id) as transaction_count');
                    $this->db->from('transactions t');
                    $this->db->join('registrations r', 't.registration_id = r.id');
                    $this->db->where('r.customer_id', $id);
                    $transaction_count = $this->db->get()->row()->transaction_count;
                    
                    if ($transaction_count > 0) {
                        $dependencies[] = $transaction_count . ' transaction(s)';
                    }
                }
                
                if (!empty($dependencies)) {
                    throw new Exception('Cannot delete customer. Customer has associated ' . implode(', ', $dependencies));
                }
                
                // Perform soft delete by updating status instead of hard delete
                $this->db->where('id', $id);
                $result = $this->db->update('customers', array(
                    'customer_status' => 'deleted',
                    'updated_at' => date('Y-m-d H:i:s')
                ));
                
                if ($result) {
                    // Log the deletion
                    $this->audit_logger->log_customer_change(
                        $id,
                        'soft_delete',
                        $customer_data,
                        array('customer_status' => 'deleted'),
                        array('operation' => 'customer_deletion', 'deletion_type' => 'soft')
                    );
                    
                    return array('success' => true, 'message' => 'Customer deleted successfully');
                } else {
                    throw new Exception('Failed to delete customer');
                }
            },
            'delete_customer',
            array('customer_id' => $id)
        );
    }

    /**
     * Get all properties associated with a customer
     * Requirement 4.3: Display all associated properties and transaction history
     */
    public function get_customer_properties($customer_id) {
        try {
            $this->db->select('p.*, r.registration_number, r.registration_date, r.status as registration_status, r.total_amount, r.paid_amount');
            $this->db->from('properties p');
            $this->db->join('registrations r', 'p.id = r.property_id');
            $this->db->where('r.customer_id', $customer_id);
            $this->db->order_by('r.registration_date', 'DESC');
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }
        } catch (Exception $e) {
            error_log('Error in get_customer_properties: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get transaction history for a customer
     * Requirement 4.3: Display transaction history
     */
    public function get_customer_transactions($customer_id) {
        try {
            $this->db->select('t.*, r.registration_number, p.garden_name, p.property_type');
            $this->db->from('transactions t');
            $this->db->join('registrations r', 't.registration_id = r.id');
            $this->db->join('properties p', 'r.property_id = p.id');
            $this->db->where('r.customer_id', $customer_id);
            $this->db->order_by('t.payment_date', 'DESC');
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }
        } catch (Exception $e) {
            error_log('Error in get_customer_transactions: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Search customers with multiple criteria
     * Requirement 4.6: Search by name, phone, email, or property association
     */
    public function search_customers($criteria) {
        try {
            $this->db->select('c.*, COUNT(r.id) as total_properties, SUM(r.total_amount) as total_investment');
            $this->db->from('customers c');
            $this->db->join('registrations r', 'c.id = r.customer_id', 'left');
            
            // Build search conditions
            if (!empty($criteria['name'])) {
                $this->db->group_start();
                $this->db->like('c.plot_buyer_name', $criteria['name']);
                $this->db->or_like('c.father_name', $criteria['name']);
                $this->db->group_end();
            }
            
            if (!empty($criteria['phone'])) {
                $this->db->group_start();
                $this->db->like('c.phone_number_1', $criteria['phone']);
                $this->db->or_like('c.phone_number_2', $criteria['phone']);
                $this->db->or_like('c.emergency_contact_phone', $criteria['phone']);
                $this->db->group_end();
            }
            
            if (!empty($criteria['email'])) {
                $this->db->like('c.email_address', $criteria['email']);
            }
            
            if (!empty($criteria['aadhar'])) {
                $this->db->like('c.aadhar_number', $criteria['aadhar']);
            }
            
            if (!empty($criteria['pan'])) {
                $this->db->like('c.pan_number', $criteria['pan']);
            }
            
            if (!empty($criteria['location'])) {
                $this->db->group_start();
                $this->db->like('c.district', $criteria['location']);
                $this->db->or_like('c.taluk_name', $criteria['location']);
                $this->db->or_like('c.village_town_name', $criteria['location']);
                $this->db->group_end();
            }
            
            if (!empty($criteria['status'])) {
                $this->db->where('c.customer_status', $criteria['status']);
            }
            
            if (!empty($criteria['property_type'])) {
                $this->db->join('properties p', 'r.property_id = p.id');
                $this->db->where('p.property_type', $criteria['property_type']);
            }
            
            // Date range filter
            if (!empty($criteria['date_from'])) {
                $this->db->where('c.created_at >=', $criteria['date_from']);
            }
            
            if (!empty($criteria['date_to'])) {
                $this->db->where('c.created_at <=', $criteria['date_to'] . ' 23:59:59');
            }
            
            $this->db->group_by('c.id');
            $this->db->order_by('c.created_at', 'DESC');
            
            // Pagination support
            if (!empty($criteria['limit'])) {
                $this->db->limit($criteria['limit'], !empty($criteria['offset']) ? $criteria['offset'] : 0);
            }
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }
        } catch (Exception $e) {
            error_log('Error in search_customers: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get customer statistics and analytics
     * Requirement 4.7: Customer analytics with acquisition trends and geographic distribution
     */
    public function get_customer_statistics() {
        try {
            $stats = array();
            
            // Total customers count
            $this->db->select('COUNT(*) as total_customers');
            $this->db->from('customers');
            $stats['total_customers'] = $this->db->get()->row()->total_customers;
            
            // Active customers (with active registrations)
            $this->db->select('COUNT(DISTINCT c.id) as active_customers');
            $this->db->from('customers c');
            $this->db->join('registrations r', 'c.id = r.customer_id');
            $this->db->where('r.status', 'active');
            $stats['active_customers'] = $this->db->get()->row()->active_customers;
            
            // Customer status distribution
            $this->db->select('customer_status, COUNT(*) as count');
            $this->db->from('customers');
            $this->db->group_by('customer_status');
            $status_result = $this->db->get();
            $stats['status_distribution'] = $status_result->result();
            
            // Geographic distribution by district
            $this->db->select('district, COUNT(*) as count');
            $this->db->from('customers');
            $this->db->where('district IS NOT NULL');
            $this->db->where('district !=', '');
            $this->db->group_by('district');
            $this->db->order_by('count', 'DESC');
            $this->db->limit(10);
            $geo_result = $this->db->get();
            $stats['geographic_distribution'] = $geo_result->result();
            
            // Monthly acquisition trends (last 12 months)
            $this->db->select('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count');
            $this->db->from('customers');
            $this->db->where('created_at >=', date('Y-m-d', strtotime('-12 months')));
            $this->db->group_by('month');
            $this->db->order_by('month', 'ASC');
            $trend_result = $this->db->get();
            $stats['acquisition_trends'] = $trend_result->result();
            
            // Top customers by investment value
            $this->db->select('c.id, c.plot_buyer_name, c.phone_number_1, SUM(r.total_amount) as total_investment, COUNT(r.id) as properties_count');
            $this->db->from('customers c');
            $this->db->join('registrations r', 'c.id = r.customer_id');
            $this->db->group_by('c.id');
            $this->db->order_by('total_investment', 'DESC');
            $this->db->limit(10);
            $top_customers_result = $this->db->get();
            $stats['top_customers'] = $top_customers_result->result();
            
            // Average investment per customer
            $this->db->select('AVG(total_investment) as avg_investment');
            $this->db->from('(SELECT c.id, SUM(r.total_amount) as total_investment FROM customers c LEFT JOIN registrations r ON c.id = r.customer_id GROUP BY c.id) as customer_investments');
            $avg_result = $this->db->get();
            $stats['average_investment'] = $avg_result->row()->avg_investment ?: 0;
            
            return $stats;
        } catch (Exception $e) {
            error_log('Error in get_customer_statistics: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get customer profile with comprehensive details
     * Requirement 4.1, 4.2: Complete profile with property associations
     */
    public function get_customer_profile($customer_id) {
        try {
            // Get customer basic details
            $this->db->where('id', $customer_id);
            $customer = $this->db->get('customers')->row();
            
            if (!$customer) {
                return null;
            }
            
            // Get associated properties
            $customer->properties = $this->get_customer_properties($customer_id);
            
            // Get transaction summary
            $this->db->select('COUNT(*) as total_transactions, SUM(amount) as total_paid, MAX(payment_date) as last_payment_date');
            $this->db->from('transactions t');
            $this->db->join('registrations r', 't.registration_id = r.id');
            $this->db->where('r.customer_id', $customer_id);
            $transaction_summary = $this->db->get()->row();
            $customer->transaction_summary = $transaction_summary;
            
            // Get registration summary
            $this->db->select('COUNT(*) as total_registrations, SUM(total_amount) as total_investment, SUM(paid_amount) as total_paid_amount');
            $this->db->from('registrations');
            $this->db->where('customer_id', $customer_id);
            $registration_summary = $this->db->get()->row();
            $customer->registration_summary = $registration_summary;
            
            return $customer;
        } catch (Exception $e) {
            error_log('Error in get_customer_profile: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get customers with property associations for listing
     * Requirement 4.7: Multiple property purchases tracking
     */
    public function get_customers_with_associations($filters = array()) {
        try {
            $this->db->select('c.*, COUNT(r.id) as total_properties, SUM(r.total_amount) as total_investment, SUM(r.paid_amount) as total_paid, MAX(r.created_at) as last_purchase_date');
            $this->db->from('customers c');
            $this->db->join('registrations r', 'c.id = r.customer_id', 'left');
            
            // Apply filters
            if (!empty($filters['status'])) {
                $this->db->where('c.customer_status', $filters['status']);
            }
            
            if (!empty($filters['has_properties'])) {
                if ($filters['has_properties'] === 'yes') {
                    $this->db->having('total_properties > 0');
                } else {
                    $this->db->having('total_properties = 0');
                }
            }
            
            $this->db->group_by('c.id');
            $this->db->order_by('c.created_at', 'DESC');
            
            // Pagination
            if (!empty($filters['limit'])) {
                $this->db->limit($filters['limit'], !empty($filters['offset']) ? $filters['offset'] : 0);
            }
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }
        } catch (Exception $e) {
            error_log('Error in get_customers_with_associations: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Update customer profile with audit trail
     * Requirement 4.4: Maintain audit log of changes with timestamps
     */
    public function update_customer_with_audit($id, $data, $user_id = null) {
        try {
            // Get old values for audit
            $this->db->where('id', $id);
            $old_data = $this->db->get('customers')->row_array();
            
            if (!$old_data) {
                return array('success' => false, 'message' => 'Customer not found');
            }
            
            // Update customer
            $this->db->where('id', $id);
            $result = $this->db->update('customers', $data);
            
            if ($result) {
                // Log the audit trail if audit_logs table exists
                if ($this->db->table_exists('audit_logs')) {
                    $audit_data = array(
                        'table_name' => 'customers',
                        'record_id' => $id,
                        'action' => 'update',
                        'old_values' => json_encode($old_data),
                        'new_values' => json_encode($data),
                        'user_id' => $user_id,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('audit_logs', $audit_data);
                }
                
                return array('success' => true, 'message' => 'Customer updated successfully');
            } else {
                return array('success' => false, 'message' => 'Failed to update customer');
            }
        } catch (Exception $e) {
            error_log('Error in update_customer_with_audit: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Error updating customer: ' . $e->getMessage());
        }
    }

    /**
     * Get customer count for dashboard
     */
    public function get_customer_count() {
        return $this->db->count_all('customers');
    }

    /**
     * Get recent customers for dashboard
     */
    public function get_recent_customers($limit = 5) {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('customers')->result();
    }
}
