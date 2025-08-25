<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('Property_model');
        $this->load->model('Staff_model');
        $this->load->model('Theme_model');
        $this->load->library('form_validation');
    }

    /**
     * Properties listing page
     */
    public function index() {
        try {
            $data['theme'] = $this->Theme_model->get_theme_path();
            
            // Get filter parameters
            $filters = array();
            if ($this->input->get('status')) {
                $filters['status'] = $this->input->get('status');
            }
            if ($this->input->get('property_type')) {
                $filters['property_type'] = $this->input->get('property_type');
            }
            if ($this->input->get('assigned_staff_id')) {
                $filters['assigned_staff_id'] = $this->input->get('assigned_staff_id');
            }
            if ($this->input->get('search')) {
                $filters['search'] = $this->input->get('search');
            }

            // Pagination
            $limit = 20;
            $offset = ($this->input->get('page') ? ($this->input->get('page') - 1) * $limit : 0);

            $data['properties'] = $this->Property_model->get_properties($filters, $limit, $offset);
            $data['total_properties'] = $this->Property_model->get_properties_count($filters);
            $data['current_page'] = $this->input->get('page') ? $this->input->get('page') : 1;
            $data['total_pages'] = ceil($data['total_properties'] / $limit);
            
            // Get filter options
            $data['staff_list'] = $this->Staff_model->get_all_staff();
            $data['districts'] = $this->Property_model->get_distinct_values('district');
            $data['property_types'] = array('garden', 'plot', 'house', 'flat');
            $data['statuses'] = array('unsold', 'booked', 'sold');
            
            // Current filters for form
            $data['current_filters'] = $filters;

            $this->load->view('others/header', $data);
            $this->load->view('properties/property_list', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Error in properties index: ' . $e->getMessage());
            show_error('Error loading properties list: ' . $e->getMessage());
        }
    }

    /**
     * Create new property page
     */
    public function create() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $data['staff_list'] = $this->Staff_model->get_all_staff();
        $data['property_types'] = array('garden', 'plot', 'house', 'flat');
        
        $this->load->view('others/header', $data);
        $this->load->view('properties/property_create', $data);
        $this->load->view('others/footer');
    }

    /**
     * Store new property
     */
    public function store() {
        try {
            // Set validation rules
            $this->form_validation->set_rules('garden_name', 'Property Name', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('property_type', 'Property Type', 'required|in_list[garden,plot,house,flat]');
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[100]');
            $this->form_validation->set_rules('taluk_name', 'Taluk', 'trim|max_length[100]');
            $this->form_validation->set_rules('village_town_name', 'Village/Town', 'trim|max_length[100]');
            $this->form_validation->set_rules('size_sqft', 'Size (Sq Ft)', 'numeric');
            $this->form_validation->set_rules('price', 'Price', 'numeric');
            $this->form_validation->set_rules('status', 'Status', 'in_list[unsold,booked,sold]');
            $this->form_validation->set_rules('assigned_staff_id', 'Assigned Staff', 'integer');
            $this->form_validation->set_rules('description', 'Description', 'trim');

            if ($this->form_validation->run() == FALSE) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => validation_errors()
                );
            } else {
                // Prepare data for insertion
                $property_data = array(
                    'garden_name' => $this->input->post('garden_name'),
                    'property_type' => $this->input->post('property_type'),
                    'district' => $this->input->post('district'),
                    'taluk_name' => $this->input->post('taluk_name'),
                    'village_town_name' => $this->input->post('village_town_name'),
                    'size_sqft' => $this->input->post('size_sqft'),
                    'price' => $this->input->post('price'),
                    'status' => $this->input->post('status') ? $this->input->post('status') : 'unsold',
                    'description' => $this->input->post('description'),
                    'assigned_staff_id' => $this->input->post('assigned_staff_id') ? $this->input->post('assigned_staff_id') : null
                );

                $property_id = $this->Property_model->create_property($property_data);

                if ($property_id) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Property created successfully!',
                        'property_id' => $property_id
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Failed to create property. Please try again.'
                    );
                }
            }

            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                if ($response['status'] == 'success') {
                    redirect('properties/view/' . $response['property_id']);
                } else {
                    $data['error'] = $response['message'];
                    $data['theme'] = $this->Theme_model->get_theme_path();
                    $data['staff_list'] = $this->Staff_model->get_all_staff();
                    $data['property_types'] = array('garden', 'plot', 'house', 'flat');
                    
                    $this->load->view('others/header', $data);
                    $this->load->view('properties/property_create', $data);
                    $this->load->view('others/footer');
                }
            }

        } catch (Exception $e) {
            error_log('Error in property store: ' . $e->getMessage());
            $response = array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            );
            
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                show_error('Error creating property: ' . $e->getMessage());
            }
        }
    }

    /**
     * View property details
     */
    public function view($id) {
        try {
            $data['theme'] = $this->Theme_model->get_theme_path();
            $data['property'] = $this->Property_model->get_property_by_id($id);
            
            if (!$data['property']) {
                show_404();
                return;
            }

            $this->load->view('others/header', $data);
            $this->load->view('properties/property_view', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Error in property view: ' . $e->getMessage());
            show_error('Error loading property details: ' . $e->getMessage());
        }
    }

    /**
     * Edit property page
     */
    public function edit($id) {
        try {
            $data['theme'] = $this->Theme_model->get_theme_path();
            $data['property'] = $this->Property_model->get_property_by_id($id);
            
            if (!$data['property']) {
                show_404();
                return;
            }

            $data['staff_list'] = $this->Staff_model->get_all_staff();
            $data['property_types'] = array('garden', 'plot', 'house', 'flat');

            $this->load->view('others/header', $data);
            $this->load->view('properties/property_edit', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Error in property edit: ' . $e->getMessage());
            show_error('Error loading property edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update property
     */
    public function update($id) {
        try {
            // Check if property exists
            $property = $this->Property_model->get_property_by_id($id);
            if (!$property) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Property not found'
                );
            } else {
                // Set validation rules
                $this->form_validation->set_rules('garden_name', 'Property Name', 'required|trim|max_length[255]');
                $this->form_validation->set_rules('property_type', 'Property Type', 'required|in_list[garden,plot,house,flat]');
                $this->form_validation->set_rules('district', 'District', 'trim|max_length[100]');
                $this->form_validation->set_rules('taluk_name', 'Taluk', 'trim|max_length[100]');
                $this->form_validation->set_rules('village_town_name', 'Village/Town', 'trim|max_length[100]');
                $this->form_validation->set_rules('size_sqft', 'Size (Sq Ft)', 'numeric');
                $this->form_validation->set_rules('price', 'Price', 'numeric');
                $this->form_validation->set_rules('status', 'Status', 'in_list[unsold,booked,sold]');
                $this->form_validation->set_rules('assigned_staff_id', 'Assigned Staff', 'integer');
                $this->form_validation->set_rules('description', 'Description', 'trim');

                if ($this->form_validation->run() == FALSE) {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Validation failed',
                        'errors' => validation_errors()
                    );
                } else {
                    // Prepare data for update
                    $update_data = array(
                        'garden_name' => $this->input->post('garden_name'),
                        'property_type' => $this->input->post('property_type'),
                        'district' => $this->input->post('district'),
                        'taluk_name' => $this->input->post('taluk_name'),
                        'village_town_name' => $this->input->post('village_town_name'),
                        'size_sqft' => $this->input->post('size_sqft'),
                        'price' => $this->input->post('price'),
                        'status' => $this->input->post('status'),
                        'description' => $this->input->post('description'),
                        'assigned_staff_id' => $this->input->post('assigned_staff_id') ? $this->input->post('assigned_staff_id') : null
                    );

                    $result = $this->Property_model->update_property($id, $update_data);

                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => 'Property updated successfully!'
                        );
                    } else {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Failed to update property. Please try again.'
                        );
                    }
                }
            }

            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                if ($response['status'] == 'success') {
                    redirect('properties/view/' . $id);
                } else {
                    $data['error'] = $response['message'];
                    $data['theme'] = $this->Theme_model->get_theme_path();
                    $data['property'] = $property;
                    $data['staff_list'] = $this->Staff_model->get_all_staff();
                    $data['property_types'] = array('garden', 'plot', 'house', 'flat');
                    
                    $this->load->view('others/header', $data);
                    $this->load->view('properties/property_edit', $data);
                    $this->load->view('others/footer');
                }
            }

        } catch (Exception $e) {
            error_log('Error in property update: ' . $e->getMessage());
            $response = array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            );
            
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                show_error('Error updating property: ' . $e->getMessage());
            }
        }
    }

    /**
     * Delete property with enhanced dependency checking
     */
    public function delete($id) {
        try {
            $result = $this->Property_model->delete_property($id);

            if ($result === true) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Property deleted successfully!'
                );
            } elseif (is_array($result) && !$result['success']) {
                $response = array(
                    'status' => 'error',
                    'message' => $result['message'],
                    'dependencies' => isset($result['dependencies']) ? $result['dependencies'] : null
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Cannot delete property. It may have associated records or dependencies.'
                );
            }

            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                if ($response['status'] == 'success') {
                    redirect('properties');
                } else {
                    show_error($response['message']);
                }
            }

        } catch (Exception $e) {
            error_log('Error in property delete: ' . $e->getMessage());
            $response = array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            );
            
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                show_error('Error deleting property: ' . $e->getMessage());
            }
        }
    }

    /**
     * Check property dependencies (AJAX endpoint)
     */
    public function check_dependencies($id) {
        try {
            $dependencies = $this->Property_model->get_property_dependencies($id);
            
            $response = array(
                'status' => 'success',
                'has_dependencies' => !empty($dependencies),
                'dependencies' => $dependencies
            );

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in check_dependencies: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Change property status
     */
    public function change_status() {
        try {
            $property_id = $this->input->post('property_id');
            $new_status = $this->input->post('status');

            if (!$property_id || !$new_status) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Property ID and status are required'
                );
            } else {
                $result = $this->Property_model->change_status($property_id, $new_status);

                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Property status updated successfully!'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Failed to update property status'
                    );
                }
            }

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in change_status: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Assign staff to property
     */
    public function assign_staff() {
        try {
            $property_id = $this->input->post('property_id');
            $staff_id = $this->input->post('staff_id');

            if (!$property_id || !$staff_id) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Property ID and Staff ID are required'
                );
            } else {
                $result = $this->Property_model->assign_staff($property_id, $staff_id);

                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Staff assigned to property successfully!'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Failed to assign staff to property'
                    );
                }
            }

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in assign_staff: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Unassign staff from property
     */
    public function unassign_staff() {
        try {
            $property_id = $this->input->post('property_id');

            if (!$property_id) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Property ID is required'
                );
            } else {
                $result = $this->Property_model->unassign_staff($property_id);

                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Staff unassigned from property successfully!'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Failed to unassign staff from property'
                    );
                }
            }

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in unassign_staff: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Advanced search properties
     */
    public function search() {
        try {
            $data['theme'] = $this->Theme_model->get_theme_path();
            
            // Get search criteria from form
            $criteria = array();
            if ($this->input->post('search_text')) {
                $criteria['search_text'] = $this->input->post('search_text');
            }
            if ($this->input->post('status')) {
                $criteria['status'] = $this->input->post('status');
            }
            if ($this->input->post('property_type')) {
                $criteria['property_type'] = $this->input->post('property_type');
            }
            if ($this->input->post('min_price')) {
                $criteria['min_price'] = $this->input->post('min_price');
            }
            if ($this->input->post('max_price')) {
                $criteria['max_price'] = $this->input->post('max_price');
            }
            if ($this->input->post('min_size')) {
                $criteria['min_size'] = $this->input->post('min_size');
            }
            if ($this->input->post('max_size')) {
                $criteria['max_size'] = $this->input->post('max_size');
            }
            if ($this->input->post('district')) {
                $criteria['district'] = $this->input->post('district');
            }
            if ($this->input->post('taluk_name')) {
                $criteria['taluk_name'] = $this->input->post('taluk_name');
            }
            if ($this->input->post('assigned_staff_id')) {
                $criteria['assigned_staff_id'] = $this->input->post('assigned_staff_id');
            }
            if ($this->input->post('unassigned')) {
                $criteria['unassigned'] = true;
            }

            $data['properties'] = $this->Property_model->search_properties($criteria);
            $data['search_criteria'] = $criteria;
            
            // Get filter options
            $data['staff_list'] = $this->Staff_model->get_all_staff();
            $data['districts'] = $this->Property_model->get_distinct_values('district');
            $data['taluks'] = $this->Property_model->get_distinct_values('taluk_name');
            $data['property_types'] = array('garden', 'plot', 'house', 'flat');
            $data['statuses'] = array('unsold', 'booked', 'sold');

            $this->load->view('others/header', $data);
            $this->load->view('properties/property_search', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Error in property search: ' . $e->getMessage());
            show_error('Error searching properties: ' . $e->getMessage());
        }
    }

    /**
     * Get property statistics and analytics
     */
    public function statistics() {
        try {
            $data['theme'] = $this->Theme_model->get_theme_path();
            $data['statistics'] = $this->Property_model->get_property_statistics();

            $this->load->view('others/header', $data);
            $this->load->view('properties/property_statistics', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Error in property statistics: ' . $e->getMessage());
            show_error('Error loading property statistics: ' . $e->getMessage());
        }
    }

    /**
     * Get properties by staff (AJAX)
     */
    public function get_by_staff($staff_id) {
        try {
            $properties = $this->Property_model->get_properties_by_staff($staff_id);
            
            $response = array(
                'status' => 'success',
                'properties' => $properties
            );

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in get_by_staff: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Enhanced bulk operations on properties
     */
    public function bulk_action() {
        try {
            $action = $this->input->post('bulk_action');
            $property_ids = $this->input->post('property_ids');

            if (!$action || !$property_ids || !is_array($property_ids)) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Invalid bulk action parameters'
                );
            } else {
                switch ($action) {
                    case 'change_status':
                        $new_status = $this->input->post('new_status');
                        if (!$new_status) {
                            $response = array(
                                'status' => 'error',
                                'message' => 'Status is required for bulk status change'
                            );
                        } else {
                            $result = $this->Property_model->bulk_update_status($property_ids, $new_status);
                            if ($result) {
                                $count = count($property_ids);
                                $response = array(
                                    'status' => 'success',
                                    'message' => $count . ' properties status updated to ' . ucfirst($new_status) . ' successfully!'
                                );
                            } else {
                                $response = array(
                                    'status' => 'error',
                                    'message' => 'Failed to update properties status'
                                );
                            }
                        }
                        break;

                    case 'assign_staff':
                        $staff_id = $this->input->post('staff_id');
                        if (!$staff_id) {
                            $response = array(
                                'status' => 'error',
                                'message' => 'Staff ID is required for bulk staff assignment'
                            );
                        } else {
                            $result = $this->Property_model->bulk_assign_staff($property_ids, $staff_id);
                            if ($result) {
                                $count = count($property_ids);
                                $response = array(
                                    'status' => 'success',
                                    'message' => 'Staff assigned to ' . $count . ' properties successfully!'
                                );
                            } else {
                                $response = array(
                                    'status' => 'error',
                                    'message' => 'Failed to assign staff to properties'
                                );
                            }
                        }
                        break;

                    case 'unassign_staff':
                        $result = $this->Property_model->bulk_unassign_staff($property_ids);
                        if ($result) {
                            $count = count($property_ids);
                            $response = array(
                                'status' => 'success',
                                'message' => 'Staff unassigned from ' . $count . ' properties successfully!'
                            );
                        } else {
                            $response = array(
                                'status' => 'error',
                                'message' => 'Failed to unassign staff from properties'
                            );
                        }
                        break;

                    case 'delete':
                        $result = $this->Property_model->bulk_delete_properties($property_ids);
                        if ($result['success']) {
                            $count = $result['deleted_count'];
                            $skipped = $result['skipped_count'];
                            $message = $count . ' properties deleted successfully!';
                            if ($skipped > 0) {
                                $message .= ' (' . $skipped . ' properties skipped due to dependencies)';
                            }
                            $response = array(
                                'status' => 'success',
                                'message' => $message
                            );
                        } else {
                            $response = array(
                                'status' => 'error',
                                'message' => 'Failed to delete properties: ' . $result['message']
                            );
                        }
                        break;
                        
                    default:
                        $response = array(
                            'status' => 'error',
                            'message' => 'Invalid bulk action'
                        );
                        break;
                }
            }

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in bulk_action: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Get property data for AJAX requests
     */
    public function get_property($id) {
        try {
            $property = $this->Property_model->get_property_by_id($id);
            
            if ($property) {
                $response = array(
                    'status' => 'success',
                    'property' => $property
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Property not found'
                );
            }

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in get_property: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Get filter options for AJAX requests
     */
    public function get_filter_options() {
        try {
            $response = array(
                'status' => 'success',
                'districts' => $this->Property_model->get_distinct_values('district'),
                'taluks' => $this->Property_model->get_distinct_values('taluk_name'),
                'villages' => $this->Property_model->get_distinct_values('village_town_name'),
                'property_types' => array('garden', 'plot', 'house', 'flat'),
                'statuses' => array('unsold', 'booked', 'sold')
            );

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in get_filter_options: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Get available properties for dropdown
     */
    public function get_available_for_dropdown() {
        try {
            $properties = $this->Property_model->get_available_for_dropdown();
            
            $response = array(
                'status' => 'success',
                'properties' => $properties
            );

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in properties/get_available_for_dropdown: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred while loading available properties.'
            ));
        }
    }
}