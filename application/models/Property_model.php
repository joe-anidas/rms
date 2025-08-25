<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Property_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a new property
     * @param array $data Property data
     * @return int|false Property ID on success, false on failure
     */
    public function create_property($data) {
        try {
            // Validate required fields
            if (empty($data['garden_name']) || empty($data['property_type'])) {
                error_log('Property creation failed: Missing required fields');
                return false;
            }

            // Set default values
            $property_data = array(
                'property_type' => $data['property_type'],
                'garden_name' => $data['garden_name'],
                'district' => isset($data['district']) ? $data['district'] : null,
                'taluk_name' => isset($data['taluk_name']) ? $data['taluk_name'] : null,
                'village_town_name' => isset($data['village_town_name']) ? $data['village_town_name'] : null,
                'size_sqft' => isset($data['size_sqft']) ? $data['size_sqft'] : null,
                'price' => isset($data['price']) ? $data['price'] : null,
                'status' => isset($data['status']) ? $data['status'] : 'unsold',
                'description' => isset($data['description']) ? $data['description'] : null,
                'assigned_staff_id' => isset($data['assigned_staff_id']) ? $data['assigned_staff_id'] : null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            $result = $this->db->insert('properties', $property_data);
            
            if ($result) {
                $property_id = $this->db->insert_id();
                error_log('Property created successfully with ID: ' . $property_id);
                return $property_id;
            } else {
                error_log('Property creation failed: ' . print_r($this->db->error(), true));
                return false;
            }
            
        } catch (Exception $e) {
            error_log('Exception in create_property: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get properties with optional filters
     * @param array $filters Optional filters (status, property_type, assigned_staff_id, search)
     * @param int $limit Optional limit
     * @param int $offset Optional offset
     * @return array Properties list
     */
    public function get_properties($filters = [], $limit = null, $offset = 0) {
        try {
            $this->db->select('p.*, s.employee_name as staff_name, s.designation as staff_designation');
            $this->db->from('properties p');
            $this->db->join('staff s', 's.id = p.assigned_staff_id', 'left');

            // Apply filters
            if (!empty($filters['status'])) {
                if (is_array($filters['status'])) {
                    $this->db->where_in('p.status', $filters['status']);
                } else {
                    $this->db->where('p.status', $filters['status']);
                }
            }

            if (!empty($filters['property_type'])) {
                $this->db->where('p.property_type', $filters['property_type']);
            }

            if (!empty($filters['assigned_staff_id'])) {
                $this->db->where('p.assigned_staff_id', $filters['assigned_staff_id']);
            }

            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $this->db->group_start();
                $this->db->like('p.garden_name', $search);
                $this->db->or_like('p.district', $search);
                $this->db->or_like('p.taluk_name', $search);
                $this->db->or_like('p.village_town_name', $search);
                $this->db->or_like('p.description', $search);
                $this->db->group_end();
            }

            // Apply limit and offset
            if ($limit !== null) {
                $this->db->limit($limit, $offset);
            }

            $this->db->order_by('p.created_at', 'DESC');
            $result = $this->db->get();

            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }

        } catch (Exception $e) {
            error_log('Exception in get_properties: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get property by ID
     * @param int $id Property ID
     * @return object|null Property object or null
     */
    public function get_property_by_id($id) {
        try {
            $this->db->select('p.*, s.employee_name as staff_name, s.designation as staff_designation');
            $this->db->from('properties p');
            $this->db->join('staff s', 's.id = p.assigned_staff_id', 'left');
            $this->db->where('p.id', $id);
            
            $result = $this->db->get();
            
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
     * Update property
     * @param int $id Property ID
     * @param array $data Update data
     * @return bool Success status
     */
    public function update_property($id, $data) {
        try {
            // Add updated timestamp
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            $this->db->where('id', $id);
            $result = $this->db->update('properties', $data);
            
            if ($result) {
                error_log('Property updated successfully: ID ' . $id);
                return true;
            } else {
                error_log('Property update failed: ' . print_r($this->db->error(), true));
                return false;
            }

        } catch (Exception $e) {
            error_log('Exception in update_property: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete property (soft delete by changing status)
     * @param int $id Property ID
     * @return bool|array Success status or array with error details
     */
    public function delete_property($id) {
        try {
            // Check if property has dependencies (registrations, transactions)
            $dependencies = $this->get_property_dependencies($id);
            if (!empty($dependencies)) {
                error_log('Cannot delete property with dependencies: ID ' . $id);
                return array(
                    'success' => false,
                    'message' => 'Cannot delete property. It has associated ' . implode(', ', array_keys($dependencies)),
                    'dependencies' => $dependencies
                );
            }

            // Perform soft delete by updating status
            $this->db->where('id', $id);
            $result = $this->db->update('properties', array(
                'status' => 'deleted',
                'updated_at' => date('Y-m-d H:i:s')
            ));
            
            if ($result) {
                error_log('Property soft deleted successfully: ID ' . $id);
                return true;
            } else {
                error_log('Property deletion failed: ' . print_r($this->db->error(), true));
                return false;
            }

        } catch (Exception $e) {
            error_log('Exception in delete_property: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get detailed property dependencies
     * @param int $property_id Property ID
     * @return array Dependencies with counts
     */
    public function get_property_dependencies($property_id) {
        try {
            $dependencies = array();

            // Check registrations
            if ($this->db->table_exists('registrations')) {
                $this->db->where('property_id', $property_id);
                $registration_count = $this->db->count_all_results('registrations');
                if ($registration_count > 0) {
                    $dependencies['registrations'] = $registration_count;
                }
            }

            // Check property assignments
            if ($this->db->table_exists('property_assignments')) {
                $this->db->where('property_id', $property_id);
                $this->db->where('is_active', 1);
                $assignment_count = $this->db->count_all_results('property_assignments');
                if ($assignment_count > 0) {
                    $dependencies['active_assignments'] = $assignment_count;
                }
            }

            // Check transactions (through registrations)
            if ($this->db->table_exists('transactions') && $this->db->table_exists('registrations')) {
                $this->db->select('COUNT(t.id) as transaction_count');
                $this->db->from('transactions t');
                $this->db->join('registrations r', 'r.id = t.registration_id');
                $this->db->where('r.property_id', $property_id);
                $result = $this->db->get()->row();
                if ($result && $result->transaction_count > 0) {
                    $dependencies['transactions'] = $result->transaction_count;
                }
            }

            return $dependencies;

        } catch (Exception $e) {
            error_log('Exception in get_property_dependencies: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Change property status
     * @param int $id Property ID
     * @param string $status New status (unsold, booked, sold)
     * @return bool Success status
     */
    public function change_status($id, $status) {
        try {
            $valid_statuses = array('unsold', 'booked', 'sold');
            
            if (!in_array($status, $valid_statuses)) {
                error_log('Invalid status provided: ' . $status);
                return false;
            }

            $this->db->where('id', $id);
            $result = $this->db->update('properties', array(
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ));
            
            if ($result) {
                error_log('Property status changed successfully: ID ' . $id . ' to ' . $status);
                return true;
            } else {
                error_log('Property status change failed: ' . print_r($this->db->error(), true));
                return false;
            }

        } catch (Exception $e) {
            error_log('Exception in change_status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Assign staff to property
     * @param int $property_id Property ID
     * @param int $staff_id Staff ID
     * @return bool Success status
     */
    public function assign_staff($property_id, $staff_id) {
        try {
            // Verify staff exists
            $this->db->where('id', $staff_id);
            $staff_exists = $this->db->get('staff')->num_rows() > 0;
            
            if (!$staff_exists) {
                error_log('Staff not found: ID ' . $staff_id);
                return false;
            }

            $this->db->where('id', $property_id);
            $result = $this->db->update('properties', array(
                'assigned_staff_id' => $staff_id,
                'updated_at' => date('Y-m-d H:i:s')
            ));
            
            if ($result) {
                error_log('Staff assigned to property successfully: Property ' . $property_id . ', Staff ' . $staff_id);
                return true;
            } else {
                error_log('Staff assignment failed: ' . print_r($this->db->error(), true));
                return false;
            }

        } catch (Exception $e) {
            error_log('Exception in assign_staff: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove staff assignment from property
     * @param int $property_id Property ID
     * @return bool Success status
     */
    public function unassign_staff($property_id) {
        try {
            $this->db->where('id', $property_id);
            $result = $this->db->update('properties', array(
                'assigned_staff_id' => null,
                'updated_at' => date('Y-m-d H:i:s')
            ));
            
            if ($result) {
                error_log('Staff unassigned from property successfully: Property ' . $property_id);
                return true;
            } else {
                error_log('Staff unassignment failed: ' . print_r($this->db->error(), true));
                return false;
            }

        } catch (Exception $e) {
            error_log('Exception in unassign_staff: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Search properties with advanced filters
     * @param array $criteria Search criteria
     * @return array Properties list
     */
    public function search_properties($criteria) {
        try {
            $this->db->select('p.*, s.employee_name as staff_name, s.designation as staff_designation');
            $this->db->from('properties p');
            $this->db->join('staff s', 's.id = p.assigned_staff_id', 'left');

            // Text search
            if (!empty($criteria['search_text'])) {
                $search = $criteria['search_text'];
                $this->db->group_start();
                $this->db->like('p.garden_name', $search);
                $this->db->or_like('p.district', $search);
                $this->db->or_like('p.taluk_name', $search);
                $this->db->or_like('p.village_town_name', $search);
                $this->db->or_like('p.description', $search);
                $this->db->group_end();
            }

            // Status filter
            if (!empty($criteria['status'])) {
                if (is_array($criteria['status'])) {
                    $this->db->where_in('p.status', $criteria['status']);
                } else {
                    $this->db->where('p.status', $criteria['status']);
                }
            }

            // Property type filter
            if (!empty($criteria['property_type'])) {
                $this->db->where('p.property_type', $criteria['property_type']);
            }

            // Price range filter
            if (!empty($criteria['min_price'])) {
                $this->db->where('p.price >=', $criteria['min_price']);
            }
            if (!empty($criteria['max_price'])) {
                $this->db->where('p.price <=', $criteria['max_price']);
            }

            // Size range filter
            if (!empty($criteria['min_size'])) {
                $this->db->where('p.size_sqft >=', $criteria['min_size']);
            }
            if (!empty($criteria['max_size'])) {
                $this->db->where('p.size_sqft <=', $criteria['max_size']);
            }

            // Location filters
            if (!empty($criteria['district'])) {
                $this->db->where('p.district', $criteria['district']);
            }
            if (!empty($criteria['taluk_name'])) {
                $this->db->where('p.taluk_name', $criteria['taluk_name']);
            }

            // Staff assignment filter
            if (!empty($criteria['assigned_staff_id'])) {
                $this->db->where('p.assigned_staff_id', $criteria['assigned_staff_id']);
            }
            if (isset($criteria['unassigned']) && $criteria['unassigned']) {
                $this->db->where('p.assigned_staff_id IS NULL');
            }

            // Date range filter
            if (!empty($criteria['created_from'])) {
                $this->db->where('p.created_at >=', $criteria['created_from']);
            }
            if (!empty($criteria['created_to'])) {
                $this->db->where('p.created_at <=', $criteria['created_to']);
            }

            // Exclude deleted properties by default
            if (!isset($criteria['include_deleted']) || !$criteria['include_deleted']) {
                $this->db->where('p.status !=', 'deleted');
            }

            $this->db->order_by('p.created_at', 'DESC');
            $result = $this->db->get();

            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }

        } catch (Exception $e) {
            error_log('Exception in search_properties: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get property statistics and analytics
     * @return array Statistics data
     */
    public function get_property_statistics() {
        try {
            $stats = array();

            // Total properties count (excluding deleted)
            $this->db->where('status !=', 'deleted');
            $stats['total_properties'] = $this->db->count_all_results('properties');

            // Properties by status
            $this->db->select('status, COUNT(*) as count');
            $this->db->from('properties');
            $this->db->where('status !=', 'deleted');
            $this->db->group_by('status');
            $status_counts = $this->db->get()->result();

            foreach ($status_counts as $status) {
                $stats['status_' . $status->status] = $status->count;
            }

            // Properties by type
            $this->db->select('property_type, COUNT(*) as count');
            $this->db->from('properties');
            $this->db->where('status !=', 'deleted');
            $this->db->group_by('property_type');
            $type_counts = $this->db->get()->result();

            foreach ($type_counts as $type) {
                $stats['type_' . $type->property_type] = $type->count;
            }

            // Total value calculations
            $this->db->select('SUM(price) as total_value, AVG(price) as avg_price');
            $this->db->from('properties');
            $this->db->where('status !=', 'deleted');
            $this->db->where('price IS NOT NULL');
            $value_stats = $this->db->get()->row();

            $stats['total_value'] = $value_stats ? $value_stats->total_value : 0;
            $stats['average_price'] = $value_stats ? $value_stats->avg_price : 0;

            // Sold properties value
            $this->db->select('SUM(price) as sold_value, COUNT(*) as sold_count');
            $this->db->from('properties');
            $this->db->where('status', 'sold');
            $sold_stats = $this->db->get()->row();

            $stats['sold_value'] = $sold_stats ? $sold_stats->sold_value : 0;
            $stats['sold_count'] = $sold_stats ? $sold_stats->sold_count : 0;

            // Staff assignment statistics
            $this->db->select('COUNT(*) as assigned_count');
            $this->db->from('properties');
            $this->db->where('assigned_staff_id IS NOT NULL');
            $this->db->where('status !=', 'deleted');
            $assigned_stats = $this->db->get()->row();

            $stats['assigned_properties'] = $assigned_stats ? $assigned_stats->assigned_count : 0;
            $stats['unassigned_properties'] = $stats['total_properties'] - $stats['assigned_properties'];

            // Monthly creation trends (last 12 months)
            $this->db->select('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count');
            $this->db->from('properties');
            $this->db->where('created_at >=', date('Y-m-d', strtotime('-12 months')));
            $this->db->where('status !=', 'deleted');
            $this->db->group_by('month');
            $this->db->order_by('month', 'ASC');
            $monthly_trends = $this->db->get()->result();

            $stats['monthly_trends'] = $monthly_trends;

            return $stats;

        } catch (Exception $e) {
            error_log('Exception in get_property_statistics: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get properties assigned to a specific staff member
     * @param int $staff_id Staff ID
     * @return array Properties list
     */
    public function get_properties_by_staff($staff_id) {
        try {
            $this->db->select('p.*, s.employee_name as staff_name, s.designation as staff_designation');
            $this->db->from('properties p');
            $this->db->join('staff s', 's.id = p.assigned_staff_id', 'left');
            $this->db->where('p.assigned_staff_id', $staff_id);
            $this->db->where('p.status !=', 'deleted');
            $this->db->order_by('p.created_at', 'DESC');

            $result = $this->db->get();

            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }

        } catch (Exception $e) {
            error_log('Exception in get_properties_by_staff: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get count of properties by various criteria
     * @param array $filters Optional filters
     * @return int Count
     */
    public function get_properties_count($filters = []) {
        try {
            $this->db->from('properties');

            // Apply filters
            if (!empty($filters['status'])) {
                if (is_array($filters['status'])) {
                    $this->db->where_in('status', $filters['status']);
                } else {
                    $this->db->where('status', $filters['status']);
                }
            }

            if (!empty($filters['property_type'])) {
                $this->db->where('property_type', $filters['property_type']);
            }

            if (!empty($filters['assigned_staff_id'])) {
                $this->db->where('assigned_staff_id', $filters['assigned_staff_id']);
            }

            // Exclude deleted by default
            if (!isset($filters['include_deleted']) || !$filters['include_deleted']) {
                $this->db->where('status !=', 'deleted');
            }

            return $this->db->count_all_results();

        } catch (Exception $e) {
            error_log('Exception in get_properties_count: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Check if property has dependencies (registrations, transactions)
     * @param int $property_id Property ID
     * @return bool Has dependencies
     */
    private function has_dependencies($property_id) {
        try {
            // Check if registrations table exists and has records for this property
            if ($this->db->table_exists('registrations')) {
                $this->db->where('property_id', $property_id);
                $registration_count = $this->db->count_all_results('registrations');
                if ($registration_count > 0) {
                    return true;
                }
            }

            // Check if property_assignments table exists and has records for this property
            if ($this->db->table_exists('property_assignments')) {
                $this->db->where('property_id', $property_id);
                $assignment_count = $this->db->count_all_results('property_assignments');
                if ($assignment_count > 0) {
                    return true;
                }
            }

            return false;

        } catch (Exception $e) {
            error_log('Exception in has_dependencies: ' . $e->getMessage());
            return true; // Err on the side of caution
        }
    }

    /**
     * Get distinct values for filter dropdowns
     * @param string $field Field name (district, taluk_name, village_town_name, property_type)
     * @return array Distinct values
     */
    public function get_distinct_values($field) {
        try {
            $valid_fields = array('district', 'taluk_name', 'village_town_name', 'property_type');
            
            if (!in_array($field, $valid_fields)) {
                return array();
            }

            $this->db->select($field);
            $this->db->from('properties');
            $this->db->where($field . ' IS NOT NULL');
            $this->db->where($field . ' !=', '');
            $this->db->where('status !=', 'deleted');
            $this->db->distinct();
            $this->db->order_by($field, 'ASC');

            $result = $this->db->get();

            if ($result->num_rows() > 0) {
                return array_column($result->result_array(), $field);
            } else {
                return array();
            }

        } catch (Exception $e) {
            error_log('Exception in get_distinct_values: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Bulk update properties status
     * @param array $property_ids Array of property IDs
     * @param string $status New status
     * @return bool Success status
     */
    public function bulk_update_status($property_ids, $status) {
        try {
            $valid_statuses = array('unsold', 'booked', 'sold');
            
            if (!in_array($status, $valid_statuses) || empty($property_ids)) {
                return false;
            }

            $this->db->where_in('id', $property_ids);
            $result = $this->db->update('properties', array(
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ));
            
            if ($result) {
                error_log('Bulk status update successful: ' . count($property_ids) . ' properties to ' . $status);
                return true;
            } else {
                error_log('Bulk status update failed: ' . print_r($this->db->error(), true));
                return false;
            }

        } catch (Exception $e) {
            error_log('Exception in bulk_update_status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Bulk assign staff to properties
     * @param array $property_ids Array of property IDs
     * @param int $staff_id Staff ID to assign
     * @return bool Success status
     */
    public function bulk_assign_staff($property_ids, $staff_id) {
        try {
            if (empty($property_ids) || !$staff_id) {
                return false;
            }

            // Verify staff exists
            $this->db->where('id', $staff_id);
            $staff_exists = $this->db->get('staff')->num_rows() > 0;
            
            if (!$staff_exists) {
                error_log('Staff not found for bulk assignment: ID ' . $staff_id);
                return false;
            }

            $this->db->where_in('id', $property_ids);
            $result = $this->db->update('properties', array(
                'assigned_staff_id' => $staff_id,
                'updated_at' => date('Y-m-d H:i:s')
            ));
            
            if ($result) {
                error_log('Bulk staff assignment successful: ' . count($property_ids) . ' properties to staff ' . $staff_id);
                return true;
            } else {
                error_log('Bulk staff assignment failed: ' . print_r($this->db->error(), true));
                return false;
            }

        } catch (Exception $e) {
            error_log('Exception in bulk_assign_staff: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Bulk unassign staff from properties
     * @param array $property_ids Array of property IDs
     * @return bool Success status
     */
    public function bulk_unassign_staff($property_ids) {
        try {
            if (empty($property_ids)) {
                return false;
            }

            $this->db->where_in('id', $property_ids);
            $result = $this->db->update('properties', array(
                'assigned_staff_id' => null,
                'updated_at' => date('Y-m-d H:i:s')
            ));
            
            if ($result) {
                error_log('Bulk staff unassignment successful: ' . count($property_ids) . ' properties');
                return true;
            } else {
                error_log('Bulk staff unassignment failed: ' . print_r($this->db->error(), true));
                return false;
            }

        } catch (Exception $e) {
            error_log('Exception in bulk_unassign_staff: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Bulk delete properties (soft delete)
     * @param array $property_ids Array of property IDs
     * @return array Result with success status, deleted count, and skipped count
     */
    public function bulk_delete_properties($property_ids) {
        try {
            if (empty($property_ids)) {
                return array(
                    'success' => false,
                    'message' => 'No properties selected',
                    'deleted_count' => 0,
                    'skipped_count' => 0
                );
            }

            $deleted_count = 0;
            $skipped_count = 0;
            $errors = array();

            foreach ($property_ids as $property_id) {
                // Check if property has dependencies
                if ($this->has_dependencies($property_id)) {
                    $skipped_count++;
                    continue;
                }

                // Perform soft delete
                $this->db->where('id', $property_id);
                $result = $this->db->update('properties', array(
                    'status' => 'deleted',
                    'updated_at' => date('Y-m-d H:i:s')
                ));

                if ($result) {
                    $deleted_count++;
                } else {
                    $skipped_count++;
                    $errors[] = 'Failed to delete property ID: ' . $property_id;
                }
            }

            $success = ($deleted_count > 0);
            $message = '';
            
            if (!empty($errors)) {
                $message = implode(', ', $errors);
            }

            error_log('Bulk delete completed: ' . $deleted_count . ' deleted, ' . $skipped_count . ' skipped');

            return array(
                'success' => $success,
                'message' => $message,
                'deleted_count' => $deleted_count,
                'skipped_count' => $skipped_count
            );

        } catch (Exception $e) {
            error_log('Exception in bulk_delete_properties: ' . $e->getMessage());
            return array(
                'success' => false,
                'message' => $e->getMessage(),
                'deleted_count' => 0,
                'skipped_count' => count($property_ids)
            );
        }
    }

    /**
     * Get available properties for dropdown (unsold properties)
     * @return array Available properties
     */
    public function get_available_for_dropdown() {
        try {
            $this->db->select('id, garden_name, property_type, district, price');
            $this->db->from('properties');
            $this->db->where('status', 'unsold');
            $this->db->order_by('garden_name', 'ASC');

            $result = $this->db->get();

            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }

        } catch (Exception $e) {
            error_log('Exception in get_available_for_dropdown: ' . $e->getMessage());
            return array();
        }
    }
}