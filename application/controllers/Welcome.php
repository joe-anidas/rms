<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('Theme_model');
        $this->load->model('Customer_model');
        $this->load->model('Staff_model');
        $this->load->model('Garden_model');
        // Removed: $this->load->library('session');
    }

    public function index()
    {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('others/header', $data);
        $this->load->view('others/dashboard');
        $this->load->view('others/footer');
    }

    public function registered_plot() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('others/header', $data);
        $this->load->view('plots/registered_plot');
        $this->load->view('others/footer');
    }

    public function garden_profile() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('others/header', $data);
        $this->load->view('plots/garden_profile');
        $this->load->view('others/footer');
    }

    public function customer_details() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('others/header', $data);
        $this->load->view('customer/customer_details');
        $this->load->view('others/footer');
    }

    public function staff_details() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('others/header', $data);
        $this->load->view('staff/staff_details');
        $this->load->view('others/footer');
    }

    public function set_theme(){
        $theme = $this->input->post('theme');
        $user_id = "2";
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_theme');
        
        if($query->num_rows() > 0){
            $this->db->where('user_id', $user_id);
            $this->db->where('theme_id !=', $theme);
            $this->db->update('user_theme', array('theme_name' => $theme));
        }else{
            $this->db->insert('user_theme', array('user_id' => $user_id, 'theme_name' => $theme));
        }
    }

    public function submit_customer() {
        try {
            // Debug: Check if we're receiving POST data
            error_log('POST data received: ' . print_r($_POST, true));
            
            // Check if required field is present
            if (empty($this->input->post('plot_buyer_name'))) {
                throw new Exception('Plot buyer name is required');
            }
            
            // Create customer table if it doesn't exist
            $table_result = $this->Customer_model->create_customer_table();
            error_log('Table creation result: ' . ($table_result ? 'success' : 'failed'));
            
            // Get form data
            $customer_data = array(
                'plot_buyer_name' => $this->input->post('plot_buyer_name'),
                'father_name' => $this->input->post('father_name'),
                'district' => $this->input->post('district'),
                'pincode' => $this->input->post('pincode'),
                'taluk_name' => $this->input->post('taluk_name'),
                'village_town_name' => $this->input->post('village_town_name'),
                'street_address' => $this->input->post('street_address'),
                'total_plot_bought' => $this->input->post('total_plot_bought'),
                'phone_number_1' => $this->input->post('phone_number_1'),
                'phone_number_2' => $this->input->post('phone_number_2'),
                'id_proof' => $this->input->post('id_proof'),
                'aadhar_number' => $this->input->post('aadhar_number')
            );
            
            // Debug: Log the received data
            error_log('Customer data processed: ' . print_r($customer_data, true));
            
            // Insert customer data
            $insert_result = $this->Customer_model->insert_customer($customer_data);
            error_log('Insert result: ' . ($insert_result ? 'success' : 'failed'));
            
            if ($insert_result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Customer details saved successfully!'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to save customer details. Please try again.'
                );
            }
            
            // Debug: Log the response
            error_log('Response: ' . print_r($response, true));
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log('Error in submit_customer: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ));
        }
    }

    public function test_customer() {
        $this->load->view('test_customer');
    }

    public function customer_list() {
        try {
            error_log('customer_list method called');
            
            $data['theme'] = $this->Theme_model->get_theme_path();
            error_log('Theme loaded: ' . $data['theme']);
            
            $data['customers'] = $this->Customer_model->get_all_customers();
            error_log('Customers fetched: ' . count($data['customers']) . ' customers');
            error_log('Customers data: ' . print_r($data['customers'], true));
            
            $this->load->view('others/header', $data);
            $this->load->view('customer/customer_list', $data);
            $this->load->view('others/footer');
            
        } catch (Exception $e) {
            error_log('Error in customer_list: ' . $e->getMessage());
            echo 'Error loading customer list: ' . $e->getMessage();
        }
    }

    public function get_customer($id) {
        $customer = $this->Customer_model->get_customer_by_id($id);
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
    }

    public function debug_customer() {
        $this->load->view('debug_customer');
    }

    public function simple_test() {
        $this->load->view('simple_test');
    }

    public function test_db_connection() {
        try {
            // Test basic connection
            $this->db->simple_query('SELECT 1');
            
            // Test if we can access the database
            $db_name = $this->db->database;
            error_log('Connected to database: ' . $db_name);
            
            // Test table creation
            $table_result = $this->Customer_model->create_customer_table();
            error_log('Table creation test result: ' . ($table_result ? 'success' : 'failed'));
            
            $response = array(
                'status' => 'success',
                'message' => 'Database connection successful. Database: ' . $db_name . '. Table creation: ' . ($table_result ? 'success' : 'failed')
            );
        } catch (Exception $e) {
            error_log('Database connection error: ' . $e->getMessage());
            $response = array(
                'status' => 'error',
                'message' => 'Database connection failed: ' . $e->getMessage()
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function create_customers_table() {
        try {
            $result = $this->Customer_model->create_customer_table();
            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Customers table created successfully'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to create customers table'
                );
            }
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Error creating table: ' . $e->getMessage()
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_all_customers() {
        try {
            $customers = $this->Customer_model->get_all_customers();
            $response = array(
                'status' => 'success',
                'customers' => $customers
            );
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Error fetching customers: ' . $e->getMessage()
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function db_test() {
        $this->load->view('db_test');
    }

    public function check_table_exists() {
        try {
            $table_exists = $this->db->table_exists('customers');
            if ($table_exists) {
                // Check table structure
                $fields = $this->db->list_fields('customers');
                $response = array(
                    'status' => 'success',
                    'message' => 'Table customers exists with ' . count($fields) . ' fields: ' . implode(', ', $fields)
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Table customers does not exist'
                );
            }
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Error checking table: ' . $e->getMessage()
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // Staff Management Methods
    public function submit_staff() {
        try {
            // Debug: Check if we're receiving POST data
            error_log('Staff POST data received: ' . print_r($_POST, true));
            
            // Check if required field is present
            if (empty($this->input->post('employee_name'))) {
                throw new Exception('Employee name is required');
            }
            
            // Create staff table if it doesn't exist
            $table_result = $this->Staff_model->create_staff_table();
            error_log('Staff table creation result: ' . ($table_result ? 'success' : 'failed'));
            
            // Get form data
            $staff_data = array(
                'employee_name' => $this->input->post('employee_name'),
                'father_name' => $this->input->post('father_name'),
                'date_of_birth' => $this->input->post('date_of_birth'),
                'gender' => $this->input->post('gender'),
                'marital_status' => $this->input->post('marital_status'),
                'blood_group' => $this->input->post('blood_group'),
                'contact_number' => $this->input->post('contact_number'),
                'alternate_contact' => $this->input->post('alternate_contact'),
                'email_address' => $this->input->post('email_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'current_address' => $this->input->post('current_address'),
                'emergency_contact_name' => $this->input->post('emergency_contact_name'),
                'emergency_contact_phone' => $this->input->post('emergency_contact_phone'),
                'emergency_contact_relation' => $this->input->post('emergency_contact_relation'),
                'id_proof_type' => $this->input->post('id_proof_type'),
                'id_proof_number' => $this->input->post('id_proof_number'),
                'designation' => $this->input->post('designation'),
                'department' => $this->input->post('department'),
                'joining_date' => $this->input->post('joining_date'),
                'salary' => $this->input->post('salary'),
                'bank_name' => $this->input->post('bank_name'),
                'bank_account_number' => $this->input->post('bank_account_number'),
                'ifsc_code' => $this->input->post('ifsc_code'),
                'pan_number' => $this->input->post('pan_number'),
                'aadhar_number' => $this->input->post('aadhar_number')
            );
            
            // Debug: Log the received data
            error_log('Staff data processed: ' . print_r($staff_data, true));
            
            // Insert staff data
            $insert_result = $this->Staff_model->insert_staff($staff_data);
            error_log('Staff insert result: ' . ($insert_result ? 'success' : 'failed'));
            
            if ($insert_result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Staff details saved successfully!'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to save staff details. Please try again.'
                );
            }
            
            // Debug: Log the response
            error_log('Staff response: ' . print_r($response, true));
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log('Error in submit_staff: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ));
        }
    }

    public function staff_list() {
        try {
            error_log('staff_list method called');
            
            $data['theme'] = $this->Theme_model->get_theme_path();
            error_log('Theme loaded: ' . $data['theme']);
            
            $data['staff'] = $this->Staff_model->get_all_staff();
            error_log('Staff fetched: ' . count($data['staff']) . ' staff members');
            error_log('Staff data: ' . print_r($data['staff'], true));
            
            $this->load->view('others/header', $data);
            $this->load->view('staff/staff_list', $data);
            $this->load->view('others/footer');
            
        } catch (Exception $e) {
            error_log('Error in staff_list: ' . $e->getMessage());
            echo 'Error loading staff list: ' . $e->getMessage();
        }
    }

    public function get_staff($id) {
        $staff = $this->Staff_model->get_staff_by_id($id);
        if ($staff) {
            $response = array(
                'status' => 'success',
                'staff' => $staff
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Staff not found'
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // Garden Profile Methods
    public function submit_garden_profile() {
        try {
            error_log('Garden profile POST data received: ' . print_r($_POST, true));
            
            // Check if required field is present
            if (empty($this->input->post('garden_name'))) {
                throw new Exception('Garden name is required');
            }
            
            // Create tables if they don't exist
            $garden_table_result = $this->Garden_model->create_garden_table();
            $plots_table_result = $this->Garden_model->create_plots_table();
            
            // Get garden form data
            $garden_data = array(
                'garden_name' => $this->input->post('garden_name'),
                'district' => $this->input->post('district'),
                'taluk_name' => $this->input->post('taluk_name'),
                'village_town_name' => $this->input->post('village_town_name'),
                'patta_chitta_no' => $this->input->post('patta_chitta_no'),
                'ts_no' => $this->input->post('ts_no'),
                'ward_block' => $this->input->post('ward_block'),
                'land_mark' => $this->input->post('land_mark'),
                'dtcp_no' => $this->input->post('dtcp_no'),
                'rera_no' => $this->input->post('rera_no'),
                'total_extension' => $this->input->post('total_extension'),
                'total_plots' => $this->input->post('total_plots'),
                'sale_extension' => $this->input->post('sale_extension'),
                'park_extension' => $this->input->post('park_extension'),
                'road_extension' => $this->input->post('road_extension'),
                'eb_line' => $this->input->post('eb_line'),
                'tree_saplings' => $this->input->post('tree_saplings'),
                'water_tank' => $this->input->post('water_tank'),
                'land_purchased_rs' => $this->input->post('land_purchased_rs'),
                'land_unpurchased_rs' => $this->input->post('land_unpurchased_rs'),
                'incentive_percentage' => $this->input->post('incentive_percentage'),
                'registration_district' => $this->input->post('registration_district'),
                'registration_sub_district' => $this->input->post('registration_sub_district'),
                'town_village' => $this->input->post('town_village'),
                'revenue_taluk' => $this->input->post('revenue_taluk'),
                'sub_registrar' => $this->input->post('sub_registrar')
            );
            
            // Insert garden data
            $garden_insert_result = $this->Garden_model->insert_garden($garden_data);
            
            if ($garden_insert_result) {
                $garden_id = $this->db->insert_id();
                
                // Process plots data if provided
                $plots_data = array();
                $plot_nos = $this->input->post('plot_no');
                $plot_extensions = $this->input->post('plot_extension');
                $norths = $this->input->post('north');
                $easts = $this->input->post('east');
                $wests = $this->input->post('west');
                $souths = $this->input->post('south');
                $plot_values = $this->input->post('plot_value');
                $statuses = $this->input->post('status');
                
                if (is_array($plot_nos)) {
                    for ($i = 0; $i < count($plot_nos); $i++) {
                        if (!empty($plot_nos[$i])) {
                            $plots_data[] = array(
                                'plot_no' => $plot_nos[$i],
                                'plot_extension' => isset($plot_extensions[$i]) ? $plot_extensions[$i] : '',
                                'north' => isset($norths[$i]) ? $norths[$i] : '',
                                'east' => isset($easts[$i]) ? $easts[$i] : '',
                                'west' => isset($wests[$i]) ? $wests[$i] : '',
                                'south' => isset($souths[$i]) ? $souths[$i] : '',
                                'plot_value' => isset($plot_values[$i]) ? $plot_values[$i] : 0,
                                'status' => isset($statuses[$i]) ? $statuses[$i] : 'unsold'
                            );
                        }
                    }
                }
                
                // Insert plots if any
                if (!empty($plots_data)) {
                    $plots_insert_result = $this->Garden_model->insert_plots($garden_id, $plots_data);
                }
                
                $response = array(
                    'status' => 'success',
                    'message' => 'Garden profile saved successfully!',
                    'garden_id' => $garden_id
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to save garden profile. Please try again.'
                );
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log('Error in submit_garden_profile: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ));
        }
    }

    public function sold_plots() {
        try {
            $data['theme'] = $this->Theme_model->get_theme_path();
            $data['sold_plots'] = $this->Garden_model->get_sold_plots();
            
            $this->load->view('others/header', $data);
            $this->load->view('plots/sold_plots', $data);
            $this->load->view('others/footer');
            
        } catch (Exception $e) {
            error_log('Error in sold_plots: ' . $e->getMessage());
            echo 'Error loading sold plots: ' . $e->getMessage();
        }
    }

    public function unsold_plots() {
        try {
            $data['theme'] = $this->Theme_model->get_theme_path();
            $data['unsold_plots'] = $this->Garden_model->get_unsold_plots();
            
            $this->load->view('others/header', $data);
            $this->load->view('plots/unsold_plots', $data);
            $this->load->view('others/footer');
            
        } catch (Exception $e) {
            error_log('Error in unsold_plots: ' . $e->getMessage());
            echo 'Error loading unsold plots: ' . $e->getMessage();
        }
    }

    public function booked_plots() {
        try {
            $data['theme'] = $this->Theme_model->get_theme_path();
            $data['booked_plots'] = $this->Garden_model->get_booked_plots();
            
            $this->load->view('others/header', $data);
            $this->load->view('plots/booked_plots', $data);
            $this->load->view('others/footer');
            
        } catch (Exception $e) {
            error_log('Error in booked_plots: ' . $e->getMessage());
            echo 'Error loading booked plots: ' . $e->getMessage();
        }
    }

    public function get_sold_plot($id) {
        $plot = $this->Garden_model->get_plot_by_id($id);
        if ($plot) {
            $response = array(
                'status' => 'success',
                'plot' => $plot
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Plot not found'
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_unsold_plot($id) {
        $plot = $this->Garden_model->get_plot_by_id($id);
        if ($plot) {
            $response = array(
                'status' => 'success',
                'plot' => $plot
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Plot not found'
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_booked_plot($id) {
        $plot = $this->Garden_model->get_plot_by_id($id);
        if ($plot) {
            $response = array(
                'status' => 'success',
                'plot' => $plot
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Plot not found'
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function mark_plot_as_sold() {
        try {
            $plot_id = $this->input->post('plot_id');
            $sale_data = array(
                'customer_name' => $this->input->post('customer_name'),
                'customer_phone' => $this->input->post('customer_phone'),
                'sale_date' => $this->input->post('sale_date'),
                'sale_amount' => $this->input->post('sale_amount')
            );
            
            $result = $this->Garden_model->mark_plot_as_sold($plot_id, $sale_data);
            
            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Plot marked as sold successfully!'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to mark plot as sold'
                );
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ));
        }
    }

    public function convert_booking_to_sale() {
        try {
            $plot_id = $this->input->post('plot_id');
            $sale_data = array(
                'sale_date' => $this->input->post('sale_date'),
                'final_sale_amount' => $this->input->post('final_sale_amount'),
                'payment_method' => $this->input->post('payment_method')
            );
            
            $result = $this->Garden_model->convert_booking_to_sale($plot_id, $sale_data);
            
            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Booking converted to sale successfully!'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to convert booking to sale'
                );
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ));
        }
    }

    public function cancel_booking() {
        try {
            $plot_id = $this->input->post('plot_id');
            $cancellation_data = array(
                'reason' => $this->input->post('cancellation_reason'),
                'refund_amount' => $this->input->post('refund_amount'),
                'notes' => $this->input->post('cancellation_notes')
            );
            
            $result = $this->Garden_model->cancel_booking($plot_id, $cancellation_data);
            
            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Booking cancelled successfully!'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to cancel booking'
                );
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ));
        }
    }

    // Dashboard method
    public function dashboard() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('others/header', $data);
        $this->load->view('others/dashboard');
        $this->load->view('others/footer');
    }

    // Add Staff method
    public function add_staff() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('others/header', $data);
        $this->load->view('staff/add_staff');
        $this->load->view('others/footer');
    }

    // Add Customer method
    public function add_customer() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('others/header', $data);
        $this->load->view('customer/add_customer');
        $this->load->view('others/footer');
    }
}