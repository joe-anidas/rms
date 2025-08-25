<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Customer_model');
        $this->load->library(['form_validation', 'upload', 'secure_database']);
        $this->load->helper(['url', 'form', 'file']);
        
        // Set CSRF exempt methods if any (for API endpoints)
        $this->csrf_exempt_methods = array(); // No exempt methods for now
    }

    /**
     * Enhanced customer listing with search and filters
     * Requirement 4.6: Search by name, phone, email, or property association
     */
    public function index() {
        try {
            // Get search criteria from GET parameters
            $criteria = array(
                'name' => $this->input->get('name'),
                'phone' => $this->input->get('phone'),
                'email' => $this->input->get('email'),
                'location' => $this->input->get('location'),
                'status' => $this->input->get('status'),
                'property_type' => $this->input->get('property_type'),
                'date_from' => $this->input->get('date_from'),
                'date_to' => $this->input->get('date_to'),
                'limit' => 50 // Default limit
            );

            // Remove empty criteria
            $criteria = array_filter($criteria, function($value) {
                return !empty($value);
            });

            // Get customers based on search criteria or all customers with associations
            if (!empty($criteria)) {
                $customers = $this->Customer_model->search_customers($criteria);
            } else {
                $customers = $this->Customer_model->get_customers_with_associations(array('limit' => 50));
            }

            $data = array(
                'customers' => $customers,
                'page_title' => 'Enhanced Customer Management',
                'search_criteria' => $criteria
            );

            $this->load->view('others/header', $data);
            $this->load->view('customer/enhanced_customer_list', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Error in customers/index: ' . $e->getMessage());
            show_error('An error occurred while loading customers.');
        }
    }

    /**
     * Show customer creation form
     * Requirement 4.1: Store complete profile including enhanced fields
     */
    public function create() {
        $data = array(
            'page_title' => 'Add New Customer'
        );

        $this->load->view('others/header', $data);
        $this->load->view('customer/enhanced_customer_form', $data);
        $this->load->view('others/footer');
    }

    /**
     * Store new customer with enhanced fields and security
     * Requirement 4.1, 4.2: Complete profile with enhanced contact details
     */
    public function store() {
        try {
            // Validate and sanitize input data
            $input_data = $this->input->post();
            $validation_result = $this->validate_and_sanitize_input($input_data, 'customer');
            
            if (!$validation_result['is_valid']) {
                $this->json_response(array(
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validation_result['errors']
                ), 400);
                return;
            }

            // Prepare customer data with all enhanced fields
            $customer_data = array(
                'plot_buyer_name' => $input_data['plot_buyer_name'],
                'father_name' => $input_data['father_name'] ?? '',
                'email_address' => $input_data['email_address'] ?? '',
                'occupation' => $input_data['occupation'] ?? '',
                'annual_income' => $input_data['annual_income'] ?? null,
                'reference_source' => $input_data['reference_source'] ?? '',
                'phone_number_1' => $input_data['phone_number_1'],
                'phone_number_2' => $input_data['phone_number_2'] ?? '',
                'emergency_contact_name' => $input_data['emergency_contact_name'] ?? '',
                'emergency_contact_phone' => $input_data['emergency_contact_phone'] ?? '',
                'emergency_contact_relation' => $input_data['emergency_contact_relation'] ?? '',
                'district' => $input_data['district'] ?? '',
                'taluk_name' => $input_data['taluk_name'] ?? '',
                'village_town_name' => $input_data['village_town_name'] ?? '',
                'pincode' => $input_data['pincode'] ?? '',
                'street_address' => $input_data['street_address'] ?? '',
                'alternate_address' => $input_data['alternate_address'] ?? '',
                'id_proof' => $input_data['id_proof'] ?? '',
                'aadhar_number' => $input_data['aadhar_number'] ?? '',
                'pan_number' => isset($input_data['pan_number']) ? strtoupper($input_data['pan_number']) : '',
                'bank_name' => $input_data['bank_name'] ?? '',
                'bank_account_number' => $input_data['bank_account_number'] ?? '',
                'ifsc_code' => isset($input_data['ifsc_code']) ? strtoupper($input_data['ifsc_code']) : '',
                'customer_status' => $input_data['customer_status'] ?? 'active',
                'total_plot_bought' => $input_data['total_plot_bought'] ?? '',
                'notes' => $input_data['notes'] ?? ''
            );

            // Remove empty values
            $customer_data = array_filter($customer_data, function($value) {
                return $value !== '' && $value !== null;
            });

            // Execute secure database operation
            $result = $this->execute_secure_db_operation(function() use ($customer_data) {
                return $this->secure_database->secure_insert('customers', $customer_data);
            }, 'customer_creation');

            if ($result) {
                $this->json_response(array(
                    'status' => 'success',
                    'message' => 'Customer created successfully!',
                    'customer_id' => $result
                ));
            } else {
                $this->json_response(array(
                    'status' => 'error',
                    'message' => 'Failed to create customer. Please try again.'
                ), 500);
            }

        } catch (Exception $e) {
            error_log('Error in customers/store: ' . $e->getMessage());
            $this->json_response(array(
                'status' => 'error',
                'message' => 'An error occurred while creating customer.'
            ), 500);
        }
    }

    /**
     * Show customer edit form
     * Requirement 4.4: Maintain audit log of changes
     */
    public function edit($id) {
        try {
            $customer = $this->Customer_model->get_customer_by_id($id);
            
            if (!$customer) {
                show_404();
                return;
            }

            $data = array(
                'customer' => $customer,
                'page_title' => 'Edit Customer - ' . $customer->plot_buyer_name
            );

            $this->load->view('others/header', $data);
            $this->load->view('customer/enhanced_customer_form', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Error in customers/edit: ' . $e->getMessage());
            show_error('An error occurred while loading customer details.');
        }
    }

    /**
     * Update customer with audit trail
     * Requirement 4.4: Maintain audit log of changes with timestamps
     */
    public function update($id) {
        try {
            // Set validation rules
            $this->form_validation->set_rules('plot_buyer_name', 'Customer Name', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('phone_number_1', 'Primary Phone', 'required|trim|max_length[15]');
            $this->form_validation->set_rules('email_address', 'Email', 'valid_email|max_length[255]');

            if ($this->form_validation->run() == FALSE) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                // Prepare update data
                $update_data = array(
                    'plot_buyer_name' => $this->input->post('plot_buyer_name'),
                    'father_name' => $this->input->post('father_name'),
                    'email_address' => $this->input->post('email_address'),
                    'occupation' => $this->input->post('occupation'),
                    'annual_income' => $this->input->post('annual_income'),
                    'reference_source' => $this->input->post('reference_source'),
                    'phone_number_1' => $this->input->post('phone_number_1'),
                    'phone_number_2' => $this->input->post('phone_number_2'),
                    'emergency_contact_name' => $this->input->post('emergency_contact_name'),
                    'emergency_contact_phone' => $this->input->post('emergency_contact_phone'),
                    'emergency_contact_relation' => $this->input->post('emergency_contact_relation'),
                    'district' => $this->input->post('district'),
                    'taluk_name' => $this->input->post('taluk_name'),
                    'village_town_name' => $this->input->post('village_town_name'),
                    'pincode' => $this->input->post('pincode'),
                    'street_address' => $this->input->post('street_address'),
                    'alternate_address' => $this->input->post('alternate_address'),
                    'id_proof' => $this->input->post('id_proof'),
                    'aadhar_number' => $this->input->post('aadhar_number'),
                    'pan_number' => strtoupper($this->input->post('pan_number')),
                    'bank_name' => $this->input->post('bank_name'),
                    'bank_account_number' => $this->input->post('bank_account_number'),
                    'ifsc_code' => strtoupper($this->input->post('ifsc_code')),
                    'customer_status' => $this->input->post('customer_status'),
                    'total_plot_bought' => $this->input->post('total_plot_bought'),
                    'notes' => $this->input->post('notes')
                );

                // Remove empty values
                $update_data = array_filter($update_data, function($value) {
                    return $value !== '' && $value !== null;
                });

                $result = $this->Customer_model->update_customer_with_audit($id, $update_data, 1); // User ID 1 for now

                $response = $result;
            }

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in customers/update: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred while updating customer.'
            ));
        }
    }

    /**
     * Get customer profile with comprehensive details
     * Requirement 4.3: Display all associated properties and transaction history
     */
    public function get_profile($id) {
        try {
            $customer = $this->Customer_model->get_customer_profile($id);
            
            if ($customer) {
                $response = array(
                    'status' => 'success',
                    'customer' => $customer
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Customer not found'
                );
            }

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in customers/get_profile: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred while loading customer profile.'
            ));
        }
    }

    /**
     * Get customer properties
     * Requirement 4.3: Display all associated properties
     */
    public function get_properties($id) {
        try {
            $properties = $this->Customer_model->get_customer_properties($id);
            
            $response = array(
                'status' => 'success',
                'properties' => $properties
            );

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in customers/get_properties: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred while loading customer properties.'
            ));
        }
    }

    /**
     * Get customer transactions
     * Requirement 4.3: Display transaction history
     */
    public function get_transactions($id) {
        try {
            $transactions = $this->Customer_model->get_customer_transactions($id);
            
            $response = array(
                'status' => 'success',
                'transactions' => $transactions
            );

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in customers/get_transactions: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred while loading customer transactions.'
            ));
        }
    }

    /**
     * Customer analytics dashboard
     * Requirement 4.7: Customer analytics with acquisition trends and geographic distribution
     */
    public function analytics() {
        try {
            $statistics = $this->Customer_model->get_customer_statistics();
            
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode(array(
                    'status' => 'success',
                    'statistics' => $statistics
                ));
            } else {
                $data = array(
                    'statistics' => $statistics,
                    'page_title' => 'Customer Analytics Dashboard'
                );

                $this->load->view('others/header', $data);
                $this->load->view('customer/customer_analytics', $data);
                $this->load->view('others/footer');
            }

        } catch (Exception $e) {
            error_log('Error in customers/analytics: ' . $e->getMessage());
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'An error occurred while loading analytics.'
                ));
            } else {
                show_error('An error occurred while loading customer analytics.');
            }
        }
    }

    /**
     * Export customers data
     * Requirement 4.6: Export functionality
     */
    public function export() {
        try {
            // Get search criteria for export
            $criteria = array(
                'name' => $this->input->get('name'),
                'phone' => $this->input->get('phone'),
                'email' => $this->input->get('email'),
                'location' => $this->input->get('location'),
                'status' => $this->input->get('status'),
                'property_type' => $this->input->get('property_type'),
                'date_from' => $this->input->get('date_from'),
                'date_to' => $this->input->get('date_to')
            );

            // Remove empty criteria
            $criteria = array_filter($criteria, function($value) {
                return !empty($value);
            });

            // Get customers for export
            if (!empty($criteria)) {
                $customers = $this->Customer_model->search_customers($criteria);
            } else {
                $customers = $this->Customer_model->get_customers_with_associations();
            }

            // Generate CSV
            $filename = 'customers_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($output, array(
                'ID', 'Customer Name', 'Father Name', 'Email', 'Phone 1', 'Phone 2',
                'District', 'Taluk', 'Village/Town', 'Pincode', 'Occupation',
                'Annual Income', 'Aadhar', 'PAN', 'Status', 'Total Properties',
                'Total Investment', 'Created Date'
            ));
            
            // CSV data
            foreach ($customers as $customer) {
                fputcsv($output, array(
                    $customer->id,
                    $customer->plot_buyer_name,
                    $customer->father_name ?: '',
                    $customer->email_address ?: '',
                    $customer->phone_number_1 ?: '',
                    $customer->phone_number_2 ?: '',
                    $customer->district ?: '',
                    $customer->taluk_name ?: '',
                    $customer->village_town_name ?: '',
                    $customer->pincode ?: '',
                    $customer->occupation ?: '',
                    $customer->annual_income ?: '',
                    $customer->aadhar_number ?: '',
                    $customer->pan_number ?: '',
                    $customer->customer_status ?: 'active',
                    isset($customer->total_properties) ? $customer->total_properties : '0',
                    isset($customer->total_investment) ? $customer->total_investment : '0',
                    $customer->created_at
                ));
            }
            
            fclose($output);

        } catch (Exception $e) {
            error_log('Error in customers/export: ' . $e->getMessage());
            show_error('An error occurred while exporting customer data.');
        }
    }

    /**
     * Show comprehensive customer profile view
     * Requirement 4.3: Display all associated properties and transaction history
     */
    public function profile($id) {
        try {
            $customer = $this->Customer_model->get_customer_profile($id);
            
            if (!$customer) {
                show_404();
                return;
            }

            $data = array(
                'customer' => $customer,
                'page_title' => 'Customer Profile - ' . $customer->plot_buyer_name
            );

            $this->load->view('others/header', $data);
            $this->load->view('customer/customer_profile', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Error in customers/profile: ' . $e->getMessage());
            show_error('An error occurred while loading customer profile.');
        }
    }

    /**
     * Customer-Property Association Management Interface
     * Requirement 4.7: Multiple property purchases tracking
     */
    public function associations() {
        try {
            $data = array(
                'page_title' => 'Customer-Property Association Management'
            );

            $this->load->view('others/header', $data);
            $this->load->view('customer/customer_property_associations', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Error in customers/associations: ' . $e->getMessage());
            show_error('An error occurred while loading customer associations.');
        }
    }

    /**
     * Get customer-property associations with filters
     * Requirement 4.7: Multiple property purchases tracking
     */
    public function get_associations() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $filters = $input ?: array();

            $this->load->model('Registration_model');
            $associations = $this->Registration_model->get_associations_with_details($filters);
            
            $response = array(
                'status' => 'success',
                'associations' => $associations
            );

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in customers/get_associations: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred while loading associations.'
            ));
        }
    }

    /**
     * Get all customers for dropdown
     */
    public function get_all_for_dropdown() {
        try {
            $customers = $this->Customer_model->get_all_customers();
            
            $response = array(
                'status' => 'success',
                'customers' => $customers
            );

            header('Content-Type: application/json');
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('Error in customers/get_all_for_dropdown: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred while loading customers.'
            ));
        }
    }

    /**
     * Export customer associations
     */
    public function export_associations() {
        try {
            $this->load->model('Registration_model');
            $associations = $this->Registration_model->get_associations_with_details();

            // Generate CSV
            $filename = 'customer_associations_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($output, array(
                'Registration Number', 'Customer Name', 'Customer Phone', 'Property Name', 
                'Property Type', 'Registration Date', 'Total Amount', 'Paid Amount', 
                'Pending Amount', 'Status', 'Agreement Path'
            ));
            
            // CSV data
            foreach ($associations as $association) {
                fputcsv($output, array(
                    $association->registration_number,
                    $association->customer_name,
                    $association->customer_phone ?: '',
                    $association->property_name,
                    $association->property_type,
                    $association->registration_date,
                    $association->total_amount ?: '0',
                    $association->paid_amount ?: '0',
                    ($association->total_amount ?: 0) - ($association->paid_amount ?: 0),
                    $association->status,
                    $association->agreement_path ?: ''
                ));
            }
            
            fclose($output);

        } catch (Exception $e) {
            error_log('Error in customers/export_associations: ' . $e->getMessage());
            show_error('An error occurred while exporting association data.');
        }
    }

    /**
     * Delete customer with validation
     * Requirement 4.5: Prevent deletion if customer has active registrations
     */
    public function delete($id) {
        try {
            $result = $this->Customer_model->delete_customer($id);
            
            header('Content-Type: application/json');
            echo json_encode($result);

        } catch (Exception $e) {
            error_log('Error in customers/delete: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => false,
                'message' => 'An error occurred while deleting customer.'
            ));
        }
    }
}