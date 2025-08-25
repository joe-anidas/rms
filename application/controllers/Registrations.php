<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registrations extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Registration_model');
        $this->load->model('Property_model');
        $this->load->model('Customer_model');
        $this->load->library('upload');
        $this->load->helper(['url', 'form', 'file']);
    }

    /**
     * Display registrations list
     */
    public function index() {
        try {
            // Get filters from query parameters
            $filters = [];
            
            if ($this->input->get('status')) {
                $filters['status'] = $this->input->get('status');
            }
            
            if ($this->input->get('search')) {
                $filters['search'] = $this->input->get('search');
            }
            
            if ($this->input->get('date_from')) {
                $filters['date_from'] = $this->input->get('date_from');
            }
            
            if ($this->input->get('date_to')) {
                $filters['date_to'] = $this->input->get('date_to');
            }

            // Pagination
            $page = (int)$this->input->get('page') ?: 1;
            $per_page = 20;
            $offset = ($page - 1) * $per_page;

            // Get registrations
            $registrations = $this->Registration_model->get_registrations($filters, $per_page, $offset);
            $total_count = $this->Registration_model->get_registrations_count($filters);
            
            // Get statistics
            $statistics = $this->Registration_model->get_registration_statistics();

            $data = [
                'registrations' => $registrations,
                'total_count' => $total_count,
                'current_page' => $page,
                'per_page' => $per_page,
                'total_pages' => ceil($total_count / $per_page),
                'filters' => $filters,
                'statistics' => $statistics,
                'page_title' => 'Registration Management'
            ];

            $this->load->view('others/header', $data);
            $this->load->view('registrations/registration_list', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Exception in registrations/index: ' . $e->getMessage());
            show_error('An error occurred while loading registrations.', 500);
        }
    }

    /**
     * Display registration details
     */
    public function view($id) {
        try {
            if (empty($id)) {
                show_404();
            }

            $registration = $this->Registration_model->get_registration_by_id($id);
            
            if (!$registration) {
                show_404();
            }

            // Get transaction history if transactions table exists
            $transactions = [];
            if ($this->db->table_exists('transactions')) {
                $this->load->model('Transaction_model');
                $transactions = $this->Transaction_model->get_transactions(['registration_id' => $id]);
            }

            $data = [
                'registration' => $registration,
                'transactions' => $transactions,
                'page_title' => 'Registration Details - ' . $registration->registration_number
            ];

            $this->load->view('others/header', $data);
            $this->load->view('registrations/registration_view', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Exception in registrations/view: ' . $e->getMessage());
            show_error('An error occurred while loading registration details.', 500);
        }
    }

    /**
     * Create new registration form
     */
    public function create() {
        try {
            // Get available properties (unsold only)
            $properties = $this->Property_model->get_properties(['status' => 'unsold']);
            
            // Get all customers
            $customers = $this->Customer_model->get_all_customers();

            $data = [
                'properties' => $properties,
                'customers' => $customers,
                'page_title' => 'Create New Registration'
            ];

            $this->load->view('others/header', $data);
            $this->load->view('registrations/registration_create', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Exception in registrations/create: ' . $e->getMessage());
            show_error('An error occurred while loading the registration form.', 500);
        }
    }

    /**
     * Process registration creation
     */
    public function store() {
        try {
            // Validate input
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('property_id', 'Property', 'required|integer');
            $this->form_validation->set_rules('customer_id', 'Customer', 'required|integer');
            $this->form_validation->set_rules('registration_date', 'Registration Date', 'required');
            $this->form_validation->set_rules('total_amount', 'Total Amount', 'numeric');
            $this->form_validation->set_rules('paid_amount', 'Paid Amount', 'numeric');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('registrations/create');
                return;
            }

            $property_id = $this->input->post('property_id');
            $customer_id = $this->input->post('customer_id');
            
            // Prepare registration data
            $registration_data = [
                'registration_date' => $this->input->post('registration_date'),
                'total_amount' => $this->input->post('total_amount') ?: null,
                'paid_amount' => $this->input->post('paid_amount') ?: 0,
                'status' => $this->input->post('status') ?: 'active',
                'property_status' => $this->input->post('property_status') ?: 'booked'
            ];

            // Handle agreement document upload
            if (!empty($_FILES['agreement_document']['name'])) {
                $upload_result = $this->handle_agreement_upload();
                if ($upload_result['success']) {
                    $registration_data['agreement_path'] = $upload_result['file_path'];
                } else {
                    $this->session->set_flashdata('error', $upload_result['error']);
                    redirect('registrations/create');
                    return;
                }
            }

            // Create registration
            $registration_id = $this->Registration_model->create_registration($property_id, $customer_id, $registration_data);

            if ($registration_id) {
                $this->session->set_flashdata('success', 'Registration created successfully with ID: ' . $registration_id);
                redirect('registrations/view/' . $registration_id);
            } else {
                $this->session->set_flashdata('error', 'Failed to create registration. Please check if the property is available.');
                redirect('registrations/create');
            }

        } catch (Exception $e) {
            error_log('Exception in registrations/store: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'An error occurred while creating the registration.');
            redirect('registrations/create');
        }
    }

    /**
     * Edit registration form
     */
    public function edit($id) {
        try {
            if (empty($id)) {
                show_404();
            }

            $registration = $this->Registration_model->get_registration_by_id($id);
            
            if (!$registration) {
                show_404();
            }

            // Get all customers for dropdown
            $customers = $this->Customer_model->get_all_customers();

            $data = [
                'registration' => $registration,
                'customers' => $customers,
                'page_title' => 'Edit Registration - ' . $registration->registration_number
            ];

            $this->load->view('others/header', $data);
            $this->load->view('registrations/registration_edit', $data);
            $this->load->view('others/footer');

        } catch (Exception $e) {
            error_log('Exception in registrations/edit: ' . $e->getMessage());
            show_error('An error occurred while loading the registration edit form.', 500);
        }
    }

    /**
     * Process registration update
     */
    public function update($id) {
        try {
            if (empty($id)) {
                show_404();
            }

            // Validate input
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('registration_date', 'Registration Date', 'required');
            $this->form_validation->set_rules('total_amount', 'Total Amount', 'numeric');
            $this->form_validation->set_rules('paid_amount', 'Paid Amount', 'numeric');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('registrations/edit/' . $id);
                return;
            }

            // Prepare update data
            $update_data = [
                'registration_date' => $this->input->post('registration_date'),
                'total_amount' => $this->input->post('total_amount') ?: null,
                'paid_amount' => $this->input->post('paid_amount') ?: 0
            ];

            // Handle agreement document upload
            if (!empty($_FILES['agreement_document']['name'])) {
                $upload_result = $this->handle_agreement_upload();
                if ($upload_result['success']) {
                    $update_data['agreement_path'] = $upload_result['file_path'];
                } else {
                    $this->session->set_flashdata('error', $upload_result['error']);
                    redirect('registrations/edit/' . $id);
                    return;
                }
            }

            // Update registration
            $result = $this->Registration_model->update_registration($id, $update_data);

            if ($result) {
                $this->session->set_flashdata('success', 'Registration updated successfully.');
                redirect('registrations/view/' . $id);
            } else {
                $this->session->set_flashdata('error', 'Failed to update registration.');
                redirect('registrations/edit/' . $id);
            }

        } catch (Exception $e) {
            error_log('Exception in registrations/update: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'An error occurred while updating the registration.');
            redirect('registrations/edit/' . $id);
        }
    }

    /**
     * Update registration status
     */
    public function update_status($id) {
        try {
            if (empty($id)) {
                show_404();
            }

            $new_status = $this->input->post('status');
            
            if (empty($new_status)) {
                $this->session->set_flashdata('error', 'Status is required.');
                redirect('registrations/view/' . $id);
                return;
            }

            $result = $this->Registration_model->update_status($id, $new_status);

            if ($result) {
                $this->session->set_flashdata('success', 'Registration status updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update registration status. Invalid status transition.');
            }

            redirect('registrations/view/' . $id);

        } catch (Exception $e) {
            error_log('Exception in registrations/update_status: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'An error occurred while updating the status.');
            redirect('registrations/view/' . $id);
        }
    }

    /**
     * Get customer registration history (AJAX)
     */
    public function customer_history($customer_id) {
        try {
            if (empty($customer_id)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Customer ID is required']));
                return;
            }

            $history = $this->Registration_model->get_customer_registration_history($customer_id);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => true, 'data' => $history]));

        } catch (Exception $e) {
            error_log('Exception in registrations/customer_history: ' . $e->getMessage());
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'An error occurred while fetching customer history']));
        }
    }

    /**
     * Download agreement document
     */
    public function download_agreement($id) {
        try {
            if (empty($id)) {
                show_404();
            }

            $registration = $this->Registration_model->get_registration_by_id($id);
            
            if (!$registration || empty($registration->agreement_path)) {
                show_404();
            }

            $file_path = FCPATH . $registration->agreement_path;
            
            if (!file_exists($file_path)) {
                show_404();
            }

            // Force download
            $this->load->helper('download');
            $file_name = 'agreement_' . $registration->registration_number . '.' . pathinfo($file_path, PATHINFO_EXTENSION);
            force_download($file_name, file_get_contents($file_path));

        } catch (Exception $e) {
            error_log('Exception in registrations/download_agreement: ' . $e->getMessage());
            show_404();
        }
    }

    /**
     * Handle agreement document upload
     * @return array Upload result with success status and file path or error message
     */
    private function handle_agreement_upload() {
        try {
            // Create uploads directory if it doesn't exist
            $upload_path = FCPATH . 'uploads/agreements/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            // Configure upload
            $config = [
                'upload_path' => $upload_path,
                'allowed_types' => 'pdf|doc|docx|jpg|jpeg|png',
                'max_size' => 5120, // 5MB
                'encrypt_name' => true
            ];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('agreement_document')) {
                $upload_data = $this->upload->data();
                $file_path = 'uploads/agreements/' . $upload_data['file_name'];
                
                return [
                    'success' => true,
                    'file_path' => $file_path,
                    'file_name' => $upload_data['file_name']
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $this->upload->display_errors('', '')
                ];
            }

        } catch (Exception $e) {
            error_log('Exception in handle_agreement_upload: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'An error occurred during file upload.'
            ];
        }
    }

    /**
     * Get registration statistics (AJAX)
     */
    public function statistics() {
        try {
            $statistics = $this->Registration_model->get_registration_statistics();

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => true, 'data' => $statistics]));

        } catch (Exception $e) {
            error_log('Exception in registrations/statistics: ' . $e->getMessage());
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'An error occurred while fetching statistics']));
        }
    }

    /**
     * Export registrations to CSV
     */
    public function export() {
        try {
            // Get filters from query parameters
            $filters = [];
            
            if ($this->input->get('status')) {
                $filters['status'] = $this->input->get('status');
            }
            
            if ($this->input->get('date_from')) {
                $filters['date_from'] = $this->input->get('date_from');
            }
            
            if ($this->input->get('date_to')) {
                $filters['date_to'] = $this->input->get('date_to');
            }

            // Get all registrations matching filters
            $registrations = $this->Registration_model->get_registrations($filters);

            // Set headers for CSV download
            $filename = 'registrations_' . date('Y-m-d_H-i-s') . '.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            // Open output stream
            $output = fopen('php://output', 'w');

            // Write CSV headers
            fputcsv($output, [
                'Registration Number',
                'Property Name',
                'Property Type',
                'Customer Name',
                'Phone Number',
                'Registration Date',
                'Status',
                'Total Amount',
                'Paid Amount',
                'Pending Amount',
                'Created At'
            ]);

            // Write data rows
            foreach ($registrations as $registration) {
                $pending_amount = $registration->total_amount - $registration->paid_amount;
                
                fputcsv($output, [
                    $registration->registration_number,
                    $registration->garden_name,
                    $registration->property_type,
                    $registration->plot_buyer_name,
                    $registration->phone_number_1,
                    $registration->registration_date,
                    ucfirst($registration->status),
                    number_format($registration->total_amount, 2),
                    number_format($registration->paid_amount, 2),
                    number_format($pending_amount, 2),
                    $registration->created_at
                ]);
            }

            fclose($output);

        } catch (Exception $e) {
            error_log('Exception in registrations/export: ' . $e->getMessage());
            show_error('An error occurred while exporting registrations.', 500);
        }
    }
}