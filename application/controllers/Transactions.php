<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_model');
        $this->load->model('Registration_model');
        $this->load->model('Customer_model');
        $this->load->model('Property_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'form', 'date']);
    }

    /**
     * Display transactions list
     */
    public function index() {
        $data['title'] = 'Transaction Management';
        
        // Get filter parameters
        $filters = [
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'payment_type' => $this->input->get('payment_type'),
            'payment_method' => $this->input->get('payment_method'),
            'customer_id' => $this->input->get('customer_id'),
            'property_id' => $this->input->get('property_id'),
            'limit' => 50,
            'offset' => $this->input->get('offset') ?: 0
        ];

        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        $data['transactions'] = $this->Transaction_model->get_transaction_history($filters);
        $data['filters'] = $filters;
        $data['customers'] = $this->Customer_model->get_all_customers();
        $data['properties'] = $this->Property_model->get_all_properties();

        $this->load->view('others/header', $data);
        $this->load->view('transactions/transactions_list', $data);
        $this->load->view('others/footer');
    }

    /**
     * Record new payment
     */
    public function record_payment($registration_id = null) {
        if ($registration_id) {
            $data['registration'] = $this->Registration_model->get_registration($registration_id);
            if (!$data['registration']) {
                show_404();
            }
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('registration_id', 'Registration', 'required|integer');
            $this->form_validation->set_rules('amount', 'Amount', 'required|decimal|greater_than[0]');
            $this->form_validation->set_rules('payment_type', 'Payment Type', 'required|in_list[advance,installment,full_payment]');
            $this->form_validation->set_rules('payment_method', 'Payment Method', 'required|in_list[cash,cheque,bank_transfer,online]');
            $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
            $this->form_validation->set_rules('notes', 'Notes', 'max_length[500]');

            if ($this->form_validation->run()) {
                $transaction_data = [
                    'registration_id' => $this->input->post('registration_id'),
                    'amount' => $this->input->post('amount'),
                    'payment_type' => $this->input->post('payment_type'),
                    'payment_method' => $this->input->post('payment_method'),
                    'payment_date' => $this->input->post('payment_date'),
                    'notes' => $this->input->post('notes')
                ];

                // Validate transaction data
                $validation = $this->Transaction_model->validate_transaction_data($transaction_data);
                if (!$validation['valid']) {
                    $data['error'] = implode('<br>', $validation['errors']);
                } else {
                    $transaction_id = $this->Transaction_model->record_payment($transaction_data);
                    if ($transaction_id) {
                        $this->session->set_flashdata('success', 'Payment recorded successfully. Receipt #' . 
                            $this->Transaction_model->get_transaction($transaction_id)['receipt_number']);
                        redirect('transactions/view/' . $transaction_id);
                    } else {
                        $data['error'] = 'Failed to record payment. Please try again.';
                    }
                }
            }
        }

        $data['title'] = 'Record Payment';
        $data['registrations'] = $this->Registration_model->get_active_registrations();
        
        $this->load->view('others/header', $data);
        $this->load->view('transactions/record_payment', $data);
        $this->load->view('others/footer');
    }

    /**
     * View transaction details
     */
    public function view($transaction_id) {
        $data['transaction'] = $this->Transaction_model->get_transaction($transaction_id);
        if (!$data['transaction']) {
            show_404();
        }

        $data['balance_info'] = $this->Transaction_model->calculate_balance($data['transaction']['registration_id']);
        $data['payment_schedule'] = $this->Transaction_model->get_payment_schedule($data['transaction']['registration_id']);
        $data['title'] = 'Transaction Details';

        $this->load->view('others/header', $data);
        $this->load->view('transactions/transaction_view', $data);
        $this->load->view('others/footer');
    }

    /**
     * Generate and display receipt
     */
    public function receipt($transaction_id) {
        $data['receipt'] = $this->Transaction_model->generate_receipt($transaction_id);
        if (!$data['receipt']) {
            show_404();
        }

        $data['title'] = 'Payment Receipt';
        
        $this->load->view('transactions/receipt', $data);
    }

    /**
     * Create payment schedule
     */
    public function create_schedule($registration_id) {
        $data['registration'] = $this->Registration_model->get_registration($registration_id);
        if (!$data['registration']) {
            show_404();
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('total_amount', 'Total Amount', 'required|decimal|greater_than[0]');
            $this->form_validation->set_rules('installment_count', 'Number of Installments', 'required|integer|greater_than[0]|less_than_equal_to[60]');
            $this->form_validation->set_rules('start_date', 'Start Date', 'required');

            if ($this->form_validation->run()) {
                $schedule_data = [
                    'total_amount' => $this->input->post('total_amount'),
                    'installment_count' => $this->input->post('installment_count'),
                    'start_date' => $this->input->post('start_date')
                ];

                if ($this->Transaction_model->create_payment_schedule($registration_id, $schedule_data)) {
                    $this->session->set_flashdata('success', 'Payment schedule created successfully.');
                    redirect('transactions/schedule/' . $registration_id);
                } else {
                    $data['error'] = 'Failed to create payment schedule. Please try again.';
                }
            }
        }

        $data['title'] = 'Create Payment Schedule';
        
        $this->load->view('others/header', $data);
        $this->load->view('transactions/create_schedule', $data);
        $this->load->view('others/footer');
    }

    /**
     * View payment schedule
     */
    public function schedule($registration_id) {
        $data['registration'] = $this->Registration_model->get_registration($registration_id);
        if (!$data['registration']) {
            show_404();
        }

        $data['payment_schedule'] = $this->Transaction_model->get_payment_schedule($registration_id);
        $data['transactions'] = $this->Transaction_model->get_transactions_by_registration($registration_id);
        $data['balance_info'] = $this->Transaction_model->calculate_balance($registration_id);
        $data['title'] = 'Payment Schedule';

        $this->load->view('others/header', $data);
        $this->load->view('transactions/payment_schedule', $data);
        $this->load->view('others/footer');
    }

    /**
     * Financial reports
     */
    public function reports() {
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');
        $group_by = $this->input->get('group_by') ?: 'day';

        $params = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'group_by' => $group_by
        ];

        $data['report'] = $this->Transaction_model->generate_financial_report($params);
        $data['params'] = $params;
        $data['title'] = 'Financial Reports';

        $this->load->view('others/header', $data);
        $this->load->view('transactions/financial_reports', $data);
        $this->load->view('others/footer');
    }

    /**
     * Pending payments
     */
    public function pending_payments() {
        $days_ahead = $this->input->get('days_ahead') ?: 30;
        
        $data['pending_payments'] = $this->Transaction_model->get_pending_payments($days_ahead);
        $data['overdue_payments'] = $this->Transaction_model->get_overdue_payments();
        $data['days_ahead'] = $days_ahead;
        $data['title'] = 'Pending Payments';

        $this->load->view('others/header', $data);
        $this->load->view('transactions/pending_payments', $data);
        $this->load->view('others/footer');
    }

    /**
     * Update transaction
     */
    public function edit($transaction_id) {
        $data['transaction'] = $this->Transaction_model->get_transaction($transaction_id);
        if (!$data['transaction']) {
            show_404();
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('amount', 'Amount', 'required|decimal|greater_than[0]');
            $this->form_validation->set_rules('payment_type', 'Payment Type', 'required|in_list[advance,installment,full_payment]');
            $this->form_validation->set_rules('payment_method', 'Payment Method', 'required|in_list[cash,cheque,bank_transfer,online]');
            $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
            $this->form_validation->set_rules('notes', 'Notes', 'max_length[500]');

            if ($this->form_validation->run()) {
                $update_data = [
                    'amount' => $this->input->post('amount'),
                    'payment_type' => $this->input->post('payment_type'),
                    'payment_method' => $this->input->post('payment_method'),
                    'payment_date' => $this->input->post('payment_date'),
                    'notes' => $this->input->post('notes')
                ];

                if ($this->Transaction_model->update_transaction($transaction_id, $update_data)) {
                    $this->session->set_flashdata('success', 'Transaction updated successfully.');
                    redirect('transactions/view/' . $transaction_id);
                } else {
                    $data['error'] = 'Failed to update transaction. Please try again.';
                }
            }
        }

        $data['title'] = 'Edit Transaction';
        
        $this->load->view('others/header', $data);
        $this->load->view('transactions/transaction_edit', $data);
        $this->load->view('others/footer');
    }

    /**
     * Delete transaction
     */
    public function delete($transaction_id) {
        $transaction = $this->Transaction_model->get_transaction($transaction_id);
        if (!$transaction) {
            show_404();
        }

        if ($this->input->post('confirm_delete')) {
            if ($this->Transaction_model->delete_transaction($transaction_id)) {
                $this->session->set_flashdata('success', 'Transaction deleted successfully.');
                redirect('transactions');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete transaction.');
                redirect('transactions/view/' . $transaction_id);
            }
        }

        $data['transaction'] = $transaction;
        $data['title'] = 'Delete Transaction';
        
        $this->load->view('others/header', $data);
        $this->load->view('transactions/transaction_delete', $data);
        $this->load->view('others/footer');
    }

    /**
     * AJAX: Get registration balance
     */
    public function ajax_get_balance() {
        $registration_id = $this->input->post('registration_id');
        if ($registration_id) {
            $balance_info = $this->Transaction_model->calculate_balance($registration_id);
            echo json_encode($balance_info);
        } else {
            echo json_encode(['error' => 'Registration ID required']);
        }
    }

    /**
     * AJAX: Get payment statistics
     */
    public function ajax_get_statistics() {
        $filters = [
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'payment_type' => $this->input->post('payment_type'),
            'payment_method' => $this->input->post('payment_method')
        ];

        $filters = array_filter($filters);
        $statistics = $this->Transaction_model->get_payment_statistics($filters);
        echo json_encode($statistics);
    }
}