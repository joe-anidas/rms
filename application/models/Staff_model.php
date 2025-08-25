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
                return array('success' => false, 'message' => 'Employee name is required');
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
                return array('success' => false, 'message' => 'Failed to insert staff member');
            } else {
                $insert_id = $this->db->insert_id();
                error_log('Staff inserted successfully with ID: ' . $insert_id);
                
                // Log audit trail
                $this->log_audit('staff', $insert_id, 'INSERT', null, $data);
                
                return array('success' => true, 'message' => 'Staff member created successfully', 'staff_id' => $insert_id);
            }
            
        } catch (Exception $e) {
            error_log('Exception inserting staff: ' . $e->getMessage());
            error_log('Exception trace: ' . $e->getTraceAsString());
            return array('success' => false, 'message' => 'An error occurred while creating staff member');
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
        // Get old values for audit trail
        $old_data = $this->get_staff_by_id($id);
        
        if (!$old_data) {
            return array('success' => false, 'message' => 'Staff member not found');
        }
        
        $this->db->where('id', $id);
        $result = $this->db->update('staff', $data);
        
        if ($result) {
            // Log audit trail
            $this->log_audit('staff', $id, 'UPDATE', (array)$old_data, $data);
            return array('success' => true, 'message' => 'Staff member updated successfully');
        }
        
        return array('success' => false, 'message' => 'Failed to update staff member');
    }

    public function delete_staff($id) {
        // Check for active assignments before deletion
        if ($this->has_active_assignments($id)) {
            return array('success' => false, 'message' => 'Cannot delete staff member with active assignments. Please reassign or end assignments first.');
        }
        
        $this->db->where('id', $id);
        $result = $this->db->delete('staff');
        
        if ($result) {
            $this->log_audit('staff', $id, 'DELETE', null, null);
        }
        
        return array('success' => $result, 'message' => $result ? 'Staff deleted successfully' : 'Failed to delete staff');
    }

    // Assignment tracking methods
    
    /**
     * Assign staff to property
     */
    public function assign_to_property($staff_id, $property_id, $assignment_type, $assigned_date = null) {
        if (!$assigned_date) {
            $assigned_date = date('Y-m-d');
        }
        
        // Check if staff exists
        if (!$this->staff_exists($staff_id)) {
            return array('success' => false, 'message' => 'Staff member not found');
        }
        
        // Check if property exists
        if (!$this->property_exists($property_id)) {
            return array('success' => false, 'message' => 'Property not found');
        }
        
        // End any existing active assignments of the same type for this property
        $this->end_property_assignment($property_id, $assignment_type);
        
        $assignment_data = array(
            'property_id' => $property_id,
            'staff_id' => $staff_id,
            'assignment_type' => $assignment_type,
            'assigned_date' => $assigned_date,
            'is_active' => 1
        );
        
        $result = $this->db->insert('property_assignments', $assignment_data);
        
        if ($result) {
            $assignment_id = $this->db->insert_id();
            $this->log_audit('property_assignments', $assignment_id, 'INSERT', null, $assignment_data);
            return array('success' => true, 'message' => 'Staff assigned to property successfully', 'assignment_id' => $assignment_id);
        }
        
        return array('success' => false, 'message' => 'Failed to assign staff to property');
    }
    
    /**
     * Assign staff to customer
     */
    public function assign_to_customer($staff_id, $customer_id, $assignment_type, $assigned_date = null, $notes = null) {
        if (!$assigned_date) {
            $assigned_date = date('Y-m-d');
        }
        
        // Check if staff exists
        if (!$this->staff_exists($staff_id)) {
            return array('success' => false, 'message' => 'Staff member not found');
        }
        
        // Check if customer exists
        if (!$this->customer_exists($customer_id)) {
            return array('success' => false, 'message' => 'Customer not found');
        }
        
        // End any existing active assignments of the same type for this customer
        $this->end_customer_assignment($customer_id, $assignment_type);
        
        $assignment_data = array(
            'customer_id' => $customer_id,
            'staff_id' => $staff_id,
            'assignment_type' => $assignment_type,
            'assigned_date' => $assigned_date,
            'notes' => $notes,
            'is_active' => 1
        );
        
        $result = $this->db->insert('customer_assignments', $assignment_data);
        
        if ($result) {
            $assignment_id = $this->db->insert_id();
            $this->log_audit('customer_assignments', $assignment_id, 'INSERT', null, $assignment_data);
            return array('success' => true, 'message' => 'Staff assigned to customer successfully', 'assignment_id' => $assignment_id);
        }
        
        return array('success' => false, 'message' => 'Failed to assign staff to customer');
    }
    
    /**
     * Get all assignments for a staff member
     */
    public function get_staff_assignments($staff_id, $include_inactive = false) {
        $assignments = array(
            'property_assignments' => array(),
            'customer_assignments' => array()
        );
        
        // Get property assignments
        $this->db->select('pa.*, p.garden_name, p.property_type, p.location_details, p.status as property_status');
        $this->db->from('property_assignments pa');
        $this->db->join('properties p', 'pa.property_id = p.id', 'left');
        $this->db->where('pa.staff_id', $staff_id);
        
        if (!$include_inactive) {
            $this->db->where('pa.is_active', 1);
        }
        
        $this->db->order_by('pa.assigned_date', 'DESC');
        $property_assignments = $this->db->get()->result();
        
        // Get customer assignments
        $this->db->select('ca.*, c.plot_buyer_name, c.contact_details, c.address_details');
        $this->db->from('customer_assignments ca');
        $this->db->join('customers c', 'ca.customer_id = c.id', 'left');
        $this->db->where('ca.staff_id', $staff_id);
        
        if (!$include_inactive) {
            $this->db->where('ca.is_active', 1);
        }
        
        $this->db->order_by('ca.assigned_date', 'DESC');
        $customer_assignments = $this->db->get()->result();
        
        return array(
            'property_assignments' => $property_assignments,
            'customer_assignments' => $customer_assignments
        );
    }
    
    /**
     * Get staff performance metrics
     */
    public function get_staff_performance($staff_id, $date_from = null, $date_to = null) {
        if (!$date_from) {
            $date_from = date('Y-m-01'); // First day of current month
        }
        if (!$date_to) {
            $date_to = date('Y-m-d'); // Today
        }
        
        $performance = array();
        
        // Get active assignments count
        $this->db->where('staff_id', $staff_id);
        $this->db->where('is_active', 1);
        $active_property_assignments = $this->db->count_all_results('property_assignments');
        
        $this->db->where('staff_id', $staff_id);
        $this->db->where('is_active', 1);
        $active_customer_assignments = $this->db->count_all_results('customer_assignments');
        
        // Get total assignments in date range
        $this->db->where('staff_id', $staff_id);
        $this->db->where('assigned_date >=', $date_from);
        $this->db->where('assigned_date <=', $date_to);
        $total_property_assignments = $this->db->count_all_results('property_assignments');
        
        $this->db->where('staff_id', $staff_id);
        $this->db->where('assigned_date >=', $date_from);
        $this->db->where('assigned_date <=', $date_to);
        $total_customer_assignments = $this->db->count_all_results('customer_assignments');
        
        // Get transactions involving this staff member (through property assignments)
        $this->db->select('COUNT(t.id) as transaction_count, SUM(t.amount) as total_amount');
        $this->db->from('transactions t');
        $this->db->join('registrations r', 't.registration_id = r.id');
        $this->db->join('property_assignments pa', 'r.property_id = pa.property_id');
        $this->db->where('pa.staff_id', $staff_id);
        $this->db->where('pa.is_active', 1);
        $this->db->where('t.payment_date >=', $date_from);
        $this->db->where('t.payment_date <=', $date_to);
        $transaction_data = $this->db->get()->row();
        
        // Get completed registrations
        $this->db->select('COUNT(r.id) as completed_registrations');
        $this->db->from('registrations r');
        $this->db->join('property_assignments pa', 'r.property_id = pa.property_id');
        $this->db->where('pa.staff_id', $staff_id);
        $this->db->where('r.status', 'completed');
        $this->db->where('r.updated_at >=', $date_from);
        $this->db->where('r.updated_at <=', $date_to);
        $completed_registrations = $this->db->get()->row()->completed_registrations;
        
        return array(
            'active_property_assignments' => $active_property_assignments,
            'active_customer_assignments' => $active_customer_assignments,
            'total_property_assignments_period' => $total_property_assignments,
            'total_customer_assignments_period' => $total_customer_assignments,
            'transaction_count' => $transaction_data->transaction_count ?: 0,
            'total_transaction_amount' => $transaction_data->total_amount ?: 0,
            'completed_registrations' => $completed_registrations,
            'date_from' => $date_from,
            'date_to' => $date_to
        );
    }
    
    /**
     * Get workload distribution for all staff
     */
    public function get_workload_distribution() {
        // Get property assignment workload
        $this->db->select('s.id, s.employee_name, s.designation, COUNT(pa.id) as property_count');
        $this->db->from('staff s');
        $this->db->join('property_assignments pa', 's.id = pa.staff_id AND pa.is_active = 1', 'left');
        $this->db->group_by('s.id');
        $this->db->order_by('property_count', 'DESC');
        $property_workload = $this->db->get()->result();
        
        // Get customer assignment workload
        $this->db->select('s.id, s.employee_name, s.designation, COUNT(ca.id) as customer_count');
        $this->db->from('staff s');
        $this->db->join('customer_assignments ca', 's.id = ca.staff_id AND ca.is_active = 1', 'left');
        $this->db->group_by('s.id');
        $this->db->order_by('customer_count', 'DESC');
        $customer_workload = $this->db->get()->result();
        
        // Combine workload data
        $workload = array();
        foreach ($property_workload as $staff) {
            $workload[$staff->id] = array(
                'staff_id' => $staff->id,
                'employee_name' => $staff->employee_name,
                'designation' => $staff->designation,
                'property_count' => $staff->property_count,
                'customer_count' => 0
            );
        }
        
        foreach ($customer_workload as $staff) {
            if (isset($workload[$staff->id])) {
                $workload[$staff->id]['customer_count'] = $staff->customer_count;
            } else {
                $workload[$staff->id] = array(
                    'staff_id' => $staff->id,
                    'employee_name' => $staff->employee_name,
                    'designation' => $staff->designation,
                    'property_count' => 0,
                    'customer_count' => $staff->customer_count
                );
            }
        }
        
        return array_values($workload);
    }
    
    /**
     * Get assignment history for a staff member
     */
    public function get_assignment_history($staff_id, $limit = 50) {
        $history = array();
        
        // Get property assignment history
        $this->db->select('pa.*, p.garden_name, p.property_type, "property" as assignment_category');
        $this->db->from('property_assignments pa');
        $this->db->join('properties p', 'pa.property_id = p.id', 'left');
        $this->db->where('pa.staff_id', $staff_id);
        $this->db->order_by('pa.assigned_date', 'DESC');
        $this->db->limit($limit);
        $property_history = $this->db->get()->result();
        
        // Get customer assignment history
        $this->db->select('ca.*, c.plot_buyer_name, "customer" as assignment_category');
        $this->db->from('customer_assignments ca');
        $this->db->join('customers c', 'ca.customer_id = c.id', 'left');
        $this->db->where('ca.staff_id', $staff_id);
        $this->db->order_by('ca.assigned_date', 'DESC');
        $this->db->limit($limit);
        $customer_history = $this->db->get()->result();
        
        // Combine and sort by date
        $combined_history = array_merge($property_history, $customer_history);
        
        usort($combined_history, function($a, $b) {
            return strtotime($b->assigned_date) - strtotime($a->assigned_date);
        });
        
        return array_slice($combined_history, 0, $limit);
    }
    
    /**
     * Search and filter staff
     */
    public function search_staff($filters = array()) {
        $this->db->select('s.*, 
                          COUNT(DISTINCT pa.id) as active_property_assignments,
                          COUNT(DISTINCT ca.id) as active_customer_assignments');
        $this->db->from('staff s');
        $this->db->join('property_assignments pa', 's.id = pa.staff_id AND pa.is_active = 1', 'left');
        $this->db->join('customer_assignments ca', 's.id = ca.staff_id AND ca.is_active = 1', 'left');
        
        // Apply filters
        if (!empty($filters['name'])) {
            $this->db->group_start();
            $this->db->like('s.employee_name', $filters['name']);
            $this->db->or_like('s.father_name', $filters['name']);
            $this->db->group_end();
        }
        
        if (!empty($filters['designation'])) {
            $this->db->where('s.designation', $filters['designation']);
        }
        
        if (!empty($filters['department'])) {
            $this->db->where('s.department', $filters['department']);
        }
        
        if (!empty($filters['contact'])) {
            $this->db->group_start();
            $this->db->like('s.contact_number', $filters['contact']);
            $this->db->or_like('s.alternate_contact', $filters['contact']);
            $this->db->or_like('s.email_address', $filters['contact']);
            $this->db->group_end();
        }
        
        if (!empty($filters['has_assignments'])) {
            if ($filters['has_assignments'] === 'yes') {
                $this->db->group_start();
                $this->db->where('pa.id IS NOT NULL');
                $this->db->or_where('ca.id IS NOT NULL');
                $this->db->group_end();
            } elseif ($filters['has_assignments'] === 'no') {
                $this->db->where('pa.id IS NULL');
                $this->db->where('ca.id IS NULL');
            }
        }
        
        $this->db->group_by('s.id');
        
        // Apply sorting
        $sort_by = !empty($filters['sort_by']) ? $filters['sort_by'] : 'employee_name';
        $sort_order = !empty($filters['sort_order']) ? $filters['sort_order'] : 'ASC';
        $this->db->order_by('s.' . $sort_by, $sort_order);
        
        // Apply pagination
        if (!empty($filters['limit'])) {
            $this->db->limit($filters['limit']);
            if (!empty($filters['offset'])) {
                $this->db->offset($filters['offset']);
            }
        }
        
        return $this->db->get()->result();
    }
    
    /**
     * End property assignment
     */
    public function end_property_assignment($property_id, $assignment_type, $end_date = null) {
        if (!$end_date) {
            $end_date = date('Y-m-d');
        }
        
        $this->db->where('property_id', $property_id);
        $this->db->where('assignment_type', $assignment_type);
        $this->db->where('is_active', 1);
        
        $update_data = array(
            'is_active' => 0,
            'end_date' => $end_date
        );
        
        return $this->db->update('property_assignments', $update_data);
    }
    
    /**
     * End customer assignment
     */
    public function end_customer_assignment($customer_id, $assignment_type, $end_date = null) {
        if (!$end_date) {
            $end_date = date('Y-m-d');
        }
        
        $this->db->where('customer_id', $customer_id);
        $this->db->where('assignment_type', $assignment_type);
        $this->db->where('is_active', 1);
        
        $update_data = array(
            'is_active' => 0,
            'end_date' => $end_date
        );
        
        return $this->db->update('customer_assignments', $update_data);
    }
    
    /**
     * End staff assignment (all assignments for a staff member)
     */
    public function end_staff_assignments($staff_id, $end_date = null) {
        if (!$end_date) {
            $end_date = date('Y-m-d');
        }
        
        $update_data = array(
            'is_active' => 0,
            'end_date' => $end_date
        );
        
        // End property assignments
        $this->db->where('staff_id', $staff_id);
        $this->db->where('is_active', 1);
        $this->db->update('property_assignments', $update_data);
        
        // End customer assignments
        $this->db->where('staff_id', $staff_id);
        $this->db->where('is_active', 1);
        $this->db->update('customer_assignments', $update_data);
        
        return true;
    }
    
    // Helper methods
    
    /**
     * Check if staff has active assignments
     */
    private function has_active_assignments($staff_id) {
        $this->db->where('staff_id', $staff_id);
        $this->db->where('is_active', 1);
        $property_assignments = $this->db->count_all_results('property_assignments');
        
        $this->db->where('staff_id', $staff_id);
        $this->db->where('is_active', 1);
        $customer_assignments = $this->db->count_all_results('customer_assignments');
        
        return ($property_assignments > 0 || $customer_assignments > 0);
    }
    
    /**
     * Check if staff exists
     */
    private function staff_exists($staff_id) {
        $this->db->where('id', $staff_id);
        return $this->db->count_all_results('staff') > 0;
    }
    
    /**
     * Check if property exists
     */
    private function property_exists($property_id) {
        $this->db->where('id', $property_id);
        return $this->db->count_all_results('properties') > 0;
    }
    
    /**
     * Check if customer exists
     */
    private function customer_exists($customer_id) {
        $this->db->where('id', $customer_id);
        return $this->db->count_all_results('customers') > 0;
    }
    
    /**
     * Log audit trail
     */
    private function log_audit($table_name, $record_id, $action, $old_values = null, $new_values = null) {
        $audit_data = array(
            'table_name' => $table_name,
            'record_id' => $record_id,
            'action' => $action,
            'old_values' => $old_values ? json_encode($old_values) : null,
            'new_values' => $new_values ? json_encode($new_values) : null,
            'user_id' => $this->session->userdata('user_id') ?: null,
            'user_ip' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent()
        );
        
        $this->db->insert('audit_logs', $audit_data);
    }
    
    /**
     * Get staff statistics
     */
    public function get_staff_statistics() {
        $stats = array();
        
        // Total staff count
        $stats['total_staff'] = $this->db->count_all('staff');
        
        // Staff by designation
        $this->db->select('designation, COUNT(*) as count');
        $this->db->group_by('designation');
        $this->db->order_by('count', 'DESC');
        $stats['by_designation'] = $this->db->get('staff')->result();
        
        // Staff by department
        $this->db->select('department, COUNT(*) as count');
        $this->db->group_by('department');
        $this->db->order_by('count', 'DESC');
        $stats['by_department'] = $this->db->get('staff')->result();
        
        // Active assignments
        $this->db->where('is_active', 1);
        $stats['active_property_assignments'] = $this->db->count_all_results('property_assignments');
        
        $this->db->where('is_active', 1);
        $stats['active_customer_assignments'] = $this->db->count_all_results('customer_assignments');
        
        // Staff with assignments
        $this->db->select('COUNT(DISTINCT staff_id) as count');
        $this->db->where('is_active', 1);
        $stats['staff_with_property_assignments'] = $this->db->get('property_assignments')->row()->count;
        
        $this->db->select('COUNT(DISTINCT staff_id) as count');
        $this->db->where('is_active', 1);
        $stats['staff_with_customer_assignments'] = $this->db->get('customer_assignments')->row()->count;
        
        return $stats;
    }
    
    /**
     * Get unique designations for filter dropdown
     */
    public function get_designations() {
        $this->db->select('DISTINCT designation');
        $this->db->where('designation IS NOT NULL');
        $this->db->where('designation !=', '');
        $this->db->order_by('designation', 'ASC');
        $result = $this->db->get('staff')->result();
        
        return array_column($result, 'designation');
    }
    
    /**
     * Get unique departments for filter dropdown
     */
    public function get_departments() {
        $this->db->select('DISTINCT department');
        $this->db->where('department IS NOT NULL');
        $this->db->where('department !=', '');
        $this->db->order_by('department', 'ASC');
        $result = $this->db->get('staff')->result();
        
        return array_column($result, 'department');
    }
    
    /**
     * End property assignment by ID
     */
    public function end_property_assignment_by_id($assignment_id, $end_date = null) {
        if (!$end_date) {
            $end_date = date('Y-m-d');
        }
        
        $this->db->where('id', $assignment_id);
        $update_data = array(
            'is_active' => 0,
            'end_date' => $end_date
        );
        
        $result = $this->db->update('property_assignments', $update_data);
        
        if ($result) {
            $this->log_audit('property_assignments', $assignment_id, 'UPDATE', null, $update_data);
            return array('success' => true, 'message' => 'Property assignment ended successfully');
        }
        
        return array('success' => false, 'message' => 'Failed to end property assignment');
    }
    
    /**
     * End customer assignment by ID
     */
    public function end_customer_assignment_by_id($assignment_id, $end_date = null) {
        if (!$end_date) {
            $end_date = date('Y-m-d');
        }
        
        $this->db->where('id', $assignment_id);
        $update_data = array(
            'is_active' => 0,
            'end_date' => $end_date
        );
        
        $result = $this->db->update('customer_assignments', $update_data);
        
        if ($result) {
            $this->log_audit('customer_assignments', $assignment_id, 'UPDATE', null, $update_data);
            return array('success' => true, 'message' => 'Customer assignment ended successfully');
        }
        
        return array('success' => false, 'message' => 'Failed to end customer assignment');
    }
}
