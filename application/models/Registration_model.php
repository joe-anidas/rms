<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a new registration linking customer to property
     * @param int $property_id Property ID
     * @param int $customer_id Customer ID
     * @param array $data Registration data
     * @return int|false Registration ID on success, false on failure
     */
    public function create_registration($property_id, $customer_id, $data = []) {
        try {
            // Validate required parameters
            if (empty($property_id) || empty($customer_id)) {
                error_log('Registration creation failed: Missing property_id or customer_id');
                return false;
            }

            // Check if property exists and is available
            $property = $this->get_property_by_id($property_id);
            if (!$property) {
                error_log('Registration creation failed: Property not found - ID: ' . $property_id);
                return false;
            }

            // Check if property is already sold or booked
            if (in_array($property->status, ['sold', 'booked'])) {
                error_log('Registration creation failed: Property already ' . $property->status . ' - ID: ' . $property_id);
                return false;
            }

            // Check if customer exists
            $customer = $this->get_customer_by_id($customer_id);
            if (!$customer) {
                error_log('Registration creation failed: Customer not found - ID: ' . $customer_id);
                return false;
            }

            // Generate unique registration number
            $registration_number = $this->generate_registration_number();
            if (!$registration_number) {
                error_log('Registration creation failed: Could not generate registration number');
                return false;
            }

            // Prepare registration data
            $registration_data = [
                'registration_number' => $registration_number,
                'property_id' => $property_id,
                'customer_id' => $customer_id,
                'registration_date' => isset($data['registration_date']) ? $data['registration_date'] : date('Y-m-d'),
                'agreement_path' => isset($data['agreement_path']) ? $data['agreement_path'] : null,
                'status' => isset($data['status']) ? $data['status'] : 'active',
                'total_amount' => isset($data['total_amount']) ? $data['total_amount'] : $property->price,
                'paid_amount' => isset($data['paid_amount']) ? $data['paid_amount'] : 0.00,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Start transaction
            $this->db->trans_start();

            // Insert registration
            $result = $this->db->insert('registrations', $registration_data);
            
            if (!$result) {
                $this->db->trans_rollback();
                error_log('Registration creation failed: Database insert error - ' . print_r($this->db->error(), true));
                return false;
            }

            $registration_id = $this->db->insert_id();

            // Update property status based on registration type
            $new_property_status = isset($data['property_status']) ? $data['property_status'] : 'booked';
            $property_update = $this->update_property_status($property_id, $new_property_status);
            
            if (!$property_update) {
                $this->db->trans_rollback();
                error_log('Registration creation failed: Could not update property status');
                return false;
            }

            // Create audit log entry
            $this->create_audit_log('registrations', $registration_id, 'CREATE', null, $registration_data);

            // Complete transaction
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                error_log('Registration creation failed: Transaction failed');
                return false;
            }

            error_log('Registration created successfully with ID: ' . $registration_id);
            return $registration_id;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            error_log('Exception in create_registration: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get registrations with optional filters
     * @param array $filters Optional filters (status, property_id, customer_id, date_from, date_to, search)
     * @param int $limit Optional limit
     * @param int $offset Optional offset
     * @return array Registrations list
     */
    public function get_registrations($filters = [], $limit = null, $offset = 0) {
        try {
            $this->db->select('r.*, p.garden_name, p.property_type, p.district, p.taluk_name, 
                              c.plot_buyer_name, c.phone_number_1, c.father_name');
            $this->db->from('registrations r');
            $this->db->join('properties p', 'p.id = r.property_id', 'left');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');

            // Apply filters
            if (!empty($filters['status'])) {
                if (is_array($filters['status'])) {
                    $this->db->where_in('r.status', $filters['status']);
                } else {
                    $this->db->where('r.status', $filters['status']);
                }
            }

            if (!empty($filters['property_id'])) {
                $this->db->where('r.property_id', $filters['property_id']);
            }

            if (!empty($filters['customer_id'])) {
                $this->db->where('r.customer_id', $filters['customer_id']);
            }

            if (!empty($filters['registration_number'])) {
                $this->db->like('r.registration_number', $filters['registration_number']);
            }

            if (!empty($filters['date_from'])) {
                $this->db->where('r.registration_date >=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $this->db->where('r.registration_date <=', $filters['date_to']);
            }

            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $this->db->group_start();
                $this->db->like('r.registration_number', $search);
                $this->db->or_like('p.garden_name', $search);
                $this->db->or_like('c.plot_buyer_name', $search);
                $this->db->or_like('c.phone_number_1', $search);
                $this->db->group_end();
            }

            // Apply limit and offset
            if ($limit !== null) {
                $this->db->limit($limit, $offset);
            }

            $this->db->order_by('r.created_at', 'DESC');
            $result = $this->db->get();

            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }

        } catch (Exception $e) {
            error_log('Exception in get_registrations: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get registration by ID with related data
     * @param int $id Registration ID
     * @return object|null Registration object or null
     */
    public function get_registration_by_id($id) {
        try {
            $this->db->select('r.*, p.garden_name, p.property_type, p.district, p.taluk_name, p.village_town_name,
                              p.size_sqft, p.price as property_price, p.description as property_description,
                              c.plot_buyer_name, c.father_name, c.phone_number_1, c.phone_number_2, 
                              c.district as customer_district, c.street_address, c.aadhar_number');
            $this->db->from('registrations r');
            $this->db->join('properties p', 'p.id = r.property_id', 'left');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');
            $this->db->where('r.id', $id);
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->row();
            } else {
                return null;
            }

        } catch (Exception $e) {
            error_log('Exception in get_registration_by_id: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get registration by property ID
     * @param int $property_id Property ID
     * @return object|null Registration object or null
     */
    public function get_registration_by_property($property_id) {
        try {
            $this->db->select('r.*, c.plot_buyer_name, c.phone_number_1');
            $this->db->from('registrations r');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');
            $this->db->where('r.property_id', $property_id);
            $this->db->where_in('r.status', ['active', 'completed']);
            $this->db->order_by('r.created_at', 'DESC');
            $this->db->limit(1);
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->row();
            } else {
                return null;
            }

        } catch (Exception $e) {
            error_log('Exception in get_registration_by_property: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update registration
     * @param int $id Registration ID
     * @param array $data Update data
     * @return bool Success status
     */
    public function update_registration($id, $data) {
        try {
            // Get current registration data for audit log
            $old_data = $this->get_registration_by_id($id);
            if (!$old_data) {
                error_log('Registration update failed: Registration not found - ID: ' . $id);
                return false;
            }

            // Add updated timestamp
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            $this->db->where('id', $id);
            $result = $this->db->update('registrations', $data);
            
            if ($result) {
                // Create audit log entry
                $this->create_audit_log('registrations', $id, 'UPDATE', $old_data, $data);
                error_log('Registration updated successfully: ID ' . $id);
                return true;
            } else {
                error_log('Registration update failed: ' . print_r($this->db->error(), true));
                return false;
            }

        } catch (Exception $e) {
            error_log('Exception in update_registration: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update registration status with workflow validation
     * @param int $id Registration ID
     * @param string $status New status (active, completed, cancelled)
     * @return bool Success status
     */
    public function update_status($id, $status) {
        try {
            $valid_statuses = ['active', 'completed', 'cancelled'];
            
            if (!in_array($status, $valid_statuses)) {
                error_log('Invalid status provided: ' . $status);
                return false;
            }

            // Get current registration
            $registration = $this->get_registration_by_id($id);
            if (!$registration) {
                error_log('Registration not found for status update: ID ' . $id);
                return false;
            }

            // Validate status transition
            if (!$this->is_valid_status_transition($registration->status, $status)) {
                error_log('Invalid status transition from ' . $registration->status . ' to ' . $status);
                return false;
            }

            // Start transaction
            $this->db->trans_start();

            // Update registration status
            $update_data = [
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->where('id', $id);
            $result = $this->db->update('registrations', $update_data);
            
            if (!$result) {
                $this->db->trans_rollback();
                error_log('Registration status update failed: ' . print_r($this->db->error(), true));
                return false;
            }

            // Update property status based on registration status
            $property_status = $this->get_property_status_from_registration_status($status);
            if ($property_status) {
                $property_update = $this->update_property_status($registration->property_id, $property_status);
                if (!$property_update) {
                    $this->db->trans_rollback();
                    error_log('Failed to update property status during registration status change');
                    return false;
                }
            }

            // Create audit log entry
            $this->create_audit_log('registrations', $id, 'STATUS_UPDATE', 
                                  ['status' => $registration->status], 
                                  ['status' => $status]);

            // Complete transaction
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                error_log('Registration status update transaction failed');
                return false;
            }

            error_log('Registration status updated successfully: ID ' . $id . ' to ' . $status);
            return true;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            error_log('Exception in update_status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate unique registration number with date-based prefix
     * @return string|false Registration number or false on failure
     */
    public function generate_registration_number() {
        try {
            // Format: REG-YYYYMM-NNNN (e.g., REG-202501-0001)
            $date_prefix = 'REG-' . date('Ym') . '-';
            
            // Get the highest number for current month
            $this->db->select('registration_number');
            $this->db->from('registrations');
            $this->db->like('registration_number', $date_prefix, 'after');
            $this->db->order_by('registration_number', 'DESC');
            $this->db->limit(1);
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                $last_number = $result->row()->registration_number;
                // Extract the numeric part and increment
                $numeric_part = (int)substr($last_number, -4);
                $new_number = $numeric_part + 1;
            } else {
                $new_number = 1;
            }
            
            // Format with leading zeros
            $registration_number = $date_prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
            
            // Verify uniqueness (in case of concurrent requests)
            $this->db->where('registration_number', $registration_number);
            $exists = $this->db->count_all_results('registrations') > 0;
            
            if ($exists) {
                // If exists, try with next number
                $new_number++;
                $registration_number = $date_prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
            }
            
            error_log('Generated registration number: ' . $registration_number);
            return $registration_number;

        } catch (Exception $e) {
            error_log('Exception in generate_registration_number: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get registration history for a customer
     * @param int $customer_id Customer ID
     * @return array Registration history
     */
    public function get_customer_registration_history($customer_id) {
        try {
            $this->db->select('r.*, p.garden_name, p.property_type, p.district');
            $this->db->from('registrations r');
            $this->db->join('properties p', 'p.id = r.property_id', 'left');
            $this->db->where('r.customer_id', $customer_id);
            $this->db->order_by('r.created_at', 'DESC');
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }

        } catch (Exception $e) {
            error_log('Exception in get_customer_registration_history: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get registration statistics
     * @return array Statistics data
     */
    public function get_registration_statistics() {
        try {
            $stats = array();

            // Total registrations count
            $stats['total_registrations'] = $this->db->count_all('registrations');

            // Registrations by status
            $this->db->select('status, COUNT(*) as count');
            $this->db->from('registrations');
            $this->db->group_by('status');
            $status_counts = $this->db->get()->result();

            foreach ($status_counts as $status) {
                $stats['status_' . $status->status] = $status->count;
            }

            // Monthly registration trends (last 12 months)
            $this->db->select('DATE_FORMAT(registration_date, "%Y-%m") as month, COUNT(*) as count');
            $this->db->from('registrations');
            $this->db->where('registration_date >=', date('Y-m-d', strtotime('-12 months')));
            $this->db->group_by('month');
            $this->db->order_by('month', 'ASC');
            $monthly_trends = $this->db->get()->result();

            $stats['monthly_trends'] = $monthly_trends;

            // Total amount statistics
            $this->db->select('SUM(total_amount) as total_value, SUM(paid_amount) as total_paid, 
                              AVG(total_amount) as avg_amount');
            $this->db->from('registrations');
            $this->db->where('status !=', 'cancelled');
            $amount_stats = $this->db->get()->row();

            $stats['total_value'] = $amount_stats ? $amount_stats->total_value : 0;
            $stats['total_paid'] = $amount_stats ? $amount_stats->total_paid : 0;
            $stats['total_pending'] = $stats['total_value'] - $stats['total_paid'];
            $stats['average_amount'] = $amount_stats ? $amount_stats->avg_amount : 0;

            return $stats;

        } catch (Exception $e) {
            error_log('Exception in get_registration_statistics: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Store agreement document path
     * @param int $registration_id Registration ID
     * @param string $file_path Path to uploaded agreement file
     * @return bool Success status
     */
    public function store_agreement_document($registration_id, $file_path) {
        try {
            $this->db->where('id', $registration_id);
            $result = $this->db->update('registrations', [
                'agreement_path' => $file_path,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            if ($result) {
                // Create audit log entry
                $this->create_audit_log('registrations', $registration_id, 'DOCUMENT_UPLOAD', 
                                      null, ['agreement_path' => $file_path]);
                error_log('Agreement document stored successfully: Registration ' . $registration_id);
                return true;
            } else {
                error_log('Agreement document storage failed: ' . print_r($this->db->error(), true));
                return false;
            }

        } catch (Exception $e) {
            error_log('Exception in store_agreement_document: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get registrations count with filters
     * @param array $filters Optional filters
     * @return int Count
     */
    public function get_registrations_count($filters = []) {
        try {
            $this->db->from('registrations r');
            $this->db->join('properties p', 'p.id = r.property_id', 'left');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');

            // Apply same filters as get_registrations
            if (!empty($filters['status'])) {
                if (is_array($filters['status'])) {
                    $this->db->where_in('r.status', $filters['status']);
                } else {
                    $this->db->where('r.status', $filters['status']);
                }
            }

            if (!empty($filters['property_id'])) {
                $this->db->where('r.property_id', $filters['property_id']);
            }

            if (!empty($filters['customer_id'])) {
                $this->db->where('r.customer_id', $filters['customer_id']);
            }

            if (!empty($filters['date_from'])) {
                $this->db->where('r.registration_date >=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $this->db->where('r.registration_date <=', $filters['date_to']);
            }

            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $this->db->group_start();
                $this->db->like('r.registration_number', $search);
                $this->db->or_like('p.garden_name', $search);
                $this->db->or_like('c.plot_buyer_name', $search);
                $this->db->or_like('c.phone_number_1', $search);
                $this->db->group_end();
            }

            return $this->db->count_all_results();

        } catch (Exception $e) {
            error_log('Exception in get_registrations_count: ' . $e->getMessage());
            return 0;
        }
    }

    // Helper methods

    /**
     * Get property by ID
     * @param int $id Property ID
     * @return object|null Property object or null
     */
    private function get_property_by_id($id) {
        try {
            $this->db->where('id', $id);
            $result = $this->db->get('properties');
            
            if ($result->num_rows() > 0) {
                return $result->row();
            } else {
                return null;
            }
        } catch (Exception $e) {
            error_log('Exception in get_property_by_id: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get customer by ID
     * @param int $id Customer ID
     * @return object|null Customer object or null
     */
    private function get_customer_by_id($id) {
        try {
            $this->db->where('id', $id);
            $result = $this->db->get('customers');
            
            if ($result->num_rows() > 0) {
                return $result->row();
            } else {
                return null;
            }
        } catch (Exception $e) {
            error_log('Exception in get_customer_by_id: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update property status
     * @param int $property_id Property ID
     * @param string $status New status
     * @return bool Success status
     */
    private function update_property_status($property_id, $status) {
        try {
            $this->db->where('id', $property_id);
            $result = $this->db->update('properties', [
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            return $result;
        } catch (Exception $e) {
            error_log('Exception in update_property_status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate status transition
     * @param string $current_status Current status
     * @param string $new_status New status
     * @return bool Is valid transition
     */
    private function is_valid_status_transition($current_status, $new_status) {
        $valid_transitions = [
            'active' => ['completed', 'cancelled'],
            'completed' => ['cancelled'], // Allow cancellation of completed registrations
            'cancelled' => [] // Cannot change from cancelled
        ];

        return isset($valid_transitions[$current_status]) && 
               in_array($new_status, $valid_transitions[$current_status]);
    }

    /**
     * Get property status based on registration status
     * @param string $registration_status Registration status
     * @return string|null Property status or null
     */
    private function get_property_status_from_registration_status($registration_status) {
        $status_mapping = [
            'active' => 'booked',
            'completed' => 'sold',
            'cancelled' => 'unsold'
        ];

        return isset($status_mapping[$registration_status]) ? $status_mapping[$registration_status] : null;
    }

    /**
     * Create audit log entry
     * @param string $table_name Table name
     * @param int $record_id Record ID
     * @param string $action Action performed
     * @param mixed $old_values Old values
     * @param mixed $new_values New values
     * @return bool Success status
     */
    private function create_audit_log($table_name, $record_id, $action, $old_values = null, $new_values = null) {
        try {
            // Check if audit_logs table exists
            if (!$this->db->table_exists('audit_logs')) {
                return true; // Skip if audit table doesn't exist
            }

            $audit_data = [
                'table_name' => $table_name,
                'record_id' => $record_id,
                'action' => $action,
                'old_values' => $old_values ? json_encode($old_values) : null,
                'new_values' => $new_values ? json_encode($new_values) : null,
                'user_id' => 1, // Default admin user, should be replaced with actual user session
                'created_at' => date('Y-m-d H:i:s')
            ];

            return $this->db->insert('audit_logs', $audit_data);

        } catch (Exception $e) {
            error_log('Exception in create_audit_log: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get active registrations for payment recording
     * @return array Active registrations
     */
    public function get_active_registrations() {
        try {
            $this->db->select('r.id, r.registration_number, r.total_amount, r.paid_amount, 
                              c.plot_buyer_name, p.garden_name, p.id as property_id');
            $this->db->from('registrations r');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');
            $this->db->join('properties p', 'p.id = r.property_id', 'left');
            $this->db->where('r.status', 'active');
            $this->db->order_by('r.registration_number', 'ASC');
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->result_array();
            } else {
                return array();
            }

        } catch (Exception $e) {
            error_log('Exception in get_active_registrations: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get customer-property associations with detailed information
     * @param array $filters Optional filters
     * @return array Associations with details
     */
    public function get_associations_with_details($filters = []) {
        try {
            $this->db->select('r.id, r.registration_number, r.registration_date, r.status, 
                              r.total_amount, r.paid_amount, r.agreement_path,
                              c.id as customer_id, c.plot_buyer_name as customer_name, 
                              c.phone_number_1 as customer_phone,
                              p.id as property_id, p.garden_name as property_name, 
                              p.property_type, p.district as property_location');
            $this->db->from('registrations r');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');
            $this->db->join('properties p', 'p.id = r.property_id', 'left');

            // Apply filters
            if (!empty($filters['customer'])) {
                $this->db->like('c.plot_buyer_name', $filters['customer']);
            }

            if (!empty($filters['property'])) {
                $this->db->like('p.garden_name', $filters['property']);
            }

            if (!empty($filters['status'])) {
                $this->db->where('r.status', $filters['status']);
            }

            if (!empty($filters['property_type'])) {
                $this->db->where('p.property_type', $filters['property_type']);
            }

            if (!empty($filters['date_from'])) {
                $this->db->where('r.registration_date >=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $this->db->where('r.registration_date <=', $filters['date_to']);
            }

            $this->db->order_by('r.created_at', 'DESC');
            $result = $this->db->get();

            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }

        } catch (Exception $e) {
            error_log('Exception in get_associations_with_details: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get registration by ID (alias for get_registration_by_id for consistency)
     * @param int $id Registration ID
     * @return array|null Registration data
     */
    public function get_registration($id) {
        return $this->get_registration_by_id($id);
    }
}