<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Staff_model');
        $this->load->model('Property_model');
        $this->load->model('Customer_model');
        $this->load->model('Theme_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'form']);
    }

    /**
     * Enhanced staff listing with search, filtering, and assignment tracking
     */
    public function index() {
        $data['title'] = 'Staff Management';
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get search and filter parameters
        $filters = array(
            'name' => $this->input->get('name'),
            'designation' => $this->input->get('designation'),
            'department' => $this->input->get('department'),
            'contact' => $this->input->get('contact'),
            'has_assignments' => $this->input->get('has_assignments'),
            'sort_by' => $this->input->get('sort_by') ?: 'employee_name',
            'sort_order' => $this->input->get('sort_order') ?: 'ASC',
            'limit' => 20,
            'offset' => ($this->input->get('page') ?: 1 - 1) * 20
        );
        
        // Get filtered staff list
        $data['staff'] = $this->Staff_model->search_staff($filters);
        $data['filters'] = $filters;
        
        // Get filter options
        $data['designations'] = $this->Staff_model->get_designations();
        $data['departments'] = $this->Staff_model->get_departments();
        
        // Get staff statistics
        $data['stats'] = $this->Staff_model->get_staff_statistics();
        
        $this->load->view('others/header', $data);
        $this->load->view('staff/enhanced_staff_list', $data);
        $this->load->view('others/footer');
    }

    /**
     * Comprehensive staff profile view with assignment history and performance metrics
     */
    public function profile($id) {
        $data['title'] = 'Staff Profile';
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get staff details
        $data['staff'] = $this->Staff_model->get_staff_by_id($id);
        if (!$data['staff']) {
            show_404();
        }
        
        // Get staff assignments
        $data['assignments'] = $this->Staff_model->get_staff_assignments($id, true);
        
        // Get staff performance metrics
        $date_from = $this->input->get('date_from') ?: date('Y-m-01');
        $date_to = $this->input->get('date_to') ?: date('Y-m-d');
        $data['performance'] = $this->Staff_model->get_staff_performance($id, $date_from, $date_to);
        
        // Get assignment history
        $data['assignment_history'] = $this->Staff_model->get_assignment_history($id);
        
        $this->load->view('others/header', $data);
        $this->load->view('staff/staff_profile', $data);
        $this->load->view('others/footer');
    }

    /**
     * Staff creation form with extended employment details
     */
    public function create() {
        $data['title'] = 'Add New Staff';
        $data['theme'] = $this->Theme_model->get_theme_path();
        $data['action'] = 'create';
        
        $this->load->view('others/header', $data);
        $this->load->view('staff/staff_form', $data);
        $this->load->view('others/footer');
    }

    /**
     * Staff editing form with extended employment details
     */
    public function edit($id) {
        $data['title'] = 'Edit Staff';
        $data['theme'] = $this->Theme_model->get_theme_path();
        $data['action'] = 'edit';
        
        // Get staff details
        $data['staff'] = $this->Staff_model->get_staff_by_id($id);
        if (!$data['staff']) {
            show_404();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('staff/staff_form', $data);
        $this->load->view('others/footer');
    }

    /**
     * Handle staff form submission (create/update)
     */
    public function save() {
        $this->form_validation->set_rules('employee_name', 'Employee Name', 'required|trim');
        $this->form_validation->set_rules('contact_number', 'Contact Number', 'trim|numeric|exact_length[10]');
        $this->form_validation->set_rules('email_address', 'Email Address', 'trim|valid_email');
        $this->form_validation->set_rules('salary', 'Salary', 'trim|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'status' => 'error',
                'message' => validation_errors()
            );
        } else {
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
            
            $staff_id = $this->input->post('staff_id');
            
            if ($staff_id) {
                // Update existing staff
                $result = $this->Staff_model->update_staff($staff_id, $staff_data);
            } else {
                // Create new staff
                $result = $this->Staff_model->insert_staff($staff_data);
            }
            
            if ($result['success']) {
                $response = array(
                    'status' => 'success',
                    'message' => $result['message'],
                    'staff_id' => $staff_id ?: $result['staff_id']
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => $result['message']
                );
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * Staff assignment management interface
     */
    public function assignments($id = null) {
        $data['title'] = 'Staff Assignments';
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        if ($id) {
            // Show assignments for specific staff
            $data['staff'] = $this->Staff_model->get_staff_by_id($id);
            if (!$data['staff']) {
                show_404();
            }
            $data['assignments'] = $this->Staff_model->get_staff_assignments($id, true);
        } else {
            // Show all assignments
            $data['staff'] = $this->Staff_model->get_all_staff();
        }
        
        // Get available properties and customers for assignment
        $data['properties'] = $this->Property_model->get_properties(['status' => 'unsold']);
        $data['customers'] = $this->Customer_model->get_all_customers();
        
        $this->load->view('others/header', $data);
        $this->load->view('staff/staff_assignments', $data);
        $this->load->view('others/footer');
    }

    /**
     * Assign staff to property
     */
    public function assign_property() {
        $staff_id = $this->input->post('staff_id');
        $property_id = $this->input->post('property_id');
        $assignment_type = $this->input->post('assignment_type');
        $assigned_date = $this->input->post('assigned_date') ?: date('Y-m-d');
        
        $result = $this->Staff_model->assign_to_property($staff_id, $property_id, $assignment_type, $assigned_date);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Assign staff to customer
     */
    public function assign_customer() {
        $staff_id = $this->input->post('staff_id');
        $customer_id = $this->input->post('customer_id');
        $assignment_type = $this->input->post('assignment_type');
        $assigned_date = $this->input->post('assigned_date') ?: date('Y-m-d');
        $notes = $this->input->post('notes');
        
        $result = $this->Staff_model->assign_to_customer($staff_id, $customer_id, $assignment_type, $assigned_date, $notes);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * End assignment
     */
    public function end_assignment() {
        $assignment_type = $this->input->post('assignment_type'); // 'property' or 'customer'
        $assignment_id = $this->input->post('assignment_id');
        $end_date = $this->input->post('end_date') ?: date('Y-m-d');
        
        if ($assignment_type === 'property') {
            $result = $this->Staff_model->end_property_assignment_by_id($assignment_id, $end_date);
        } else {
            $result = $this->Staff_model->end_customer_assignment_by_id($assignment_id, $end_date);
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Staff workload distribution dashboard with analytics
     */
    public function workload() {
        $data['title'] = 'Staff Workload Distribution';
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get workload distribution
        $data['workload'] = $this->Staff_model->get_workload_distribution();
        
        // Get staff statistics
        $data['stats'] = $this->Staff_model->get_staff_statistics();
        
        // Get performance data for charts
        $data['performance_data'] = $this->get_performance_chart_data();
        
        $this->load->view('others/header', $data);
        $this->load->view('staff/staff_workload', $data);
        $this->load->view('others/footer');
    }

    /**
     * Delete staff member
     */
    public function delete($id) {
        $result = $this->Staff_model->delete_staff($id);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Get staff details (AJAX)
     */
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

    /**
     * Export staff data
     */
    public function export() {
        $format = $this->input->get('format') ?: 'csv';
        $filters = array(
            'name' => $this->input->get('name'),
            'designation' => $this->input->get('designation'),
            'department' => $this->input->get('department'),
            'contact' => $this->input->get('contact'),
            'has_assignments' => $this->input->get('has_assignments')
        );
        
        $staff = $this->Staff_model->search_staff($filters);
        
        if ($format === 'csv') {
            $this->export_csv($staff);
        } else {
            $this->export_excel($staff);
        }
    }

    /**
     * Get performance chart data
     */
    private function get_performance_chart_data() {
        $staff_list = $this->Staff_model->get_all_staff();
        $chart_data = array(
            'labels' => array(),
            'property_assignments' => array(),
            'customer_assignments' => array(),
            'transaction_amounts' => array()
        );
        
        foreach ($staff_list as $staff) {
            $performance = $this->Staff_model->get_staff_performance($staff->id);
            
            $chart_data['labels'][] = $staff->employee_name;
            $chart_data['property_assignments'][] = $performance['active_property_assignments'];
            $chart_data['customer_assignments'][] = $performance['active_customer_assignments'];
            $chart_data['transaction_amounts'][] = $performance['total_transaction_amount'];
        }
        
        return $chart_data;
    }

    /**
     * Export staff data as CSV
     */
    private function export_csv($staff) {
        $filename = 'staff_data_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, array(
            'ID', 'Employee Name', 'Father Name', 'Date of Birth', 'Gender', 'Marital Status',
            'Blood Group', 'Contact Number', 'Alternate Contact', 'Email Address',
            'Permanent Address', 'Current Address', 'Emergency Contact Name',
            'Emergency Contact Phone', 'Emergency Contact Relation', 'ID Proof Type',
            'ID Proof Number', 'Designation', 'Department', 'Joining Date', 'Salary',
            'Bank Name', 'Bank Account Number', 'IFSC Code', 'PAN Number', 'Aadhar Number',
            'Active Property Assignments', 'Active Customer Assignments', 'Created At'
        ));
        
        // CSV data
        foreach ($staff as $employee) {
            fputcsv($output, array(
                $employee->id,
                $employee->employee_name,
                $employee->father_name,
                $employee->date_of_birth,
                $employee->gender,
                $employee->marital_status,
                $employee->blood_group,
                $employee->contact_number,
                $employee->alternate_contact,
                $employee->email_address,
                $employee->permanent_address,
                $employee->current_address,
                $employee->emergency_contact_name,
                $employee->emergency_contact_phone,
                $employee->emergency_contact_relation,
                $employee->id_proof_type,
                $employee->id_proof_number,
                $employee->designation,
                $employee->department,
                $employee->joining_date,
                $employee->salary,
                $employee->bank_name,
                $employee->bank_account_number,
                $employee->ifsc_code,
                $employee->pan_number,
                $employee->aadhar_number,
                $employee->active_property_assignments ?? 0,
                $employee->active_customer_assignments ?? 0,
                $employee->created_at
            ));
        }
        
        fclose($output);
    }
}