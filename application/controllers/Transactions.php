<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('Transaction_model');
        $this->load->model('Garden_model');
        $this->load->model('Customer_model');
        $this->load->model('Staff_model');
        $this->load->model('Theme_model');
    }

    public function index() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $data['transactions'] = array();
        
        // Get recent transactions
        try {
            $data['recent_transactions'] = $this->Transaction_model->get_recent_transactions(10);
        } catch (Exception $e) {
            error_log('Error getting recent transactions: ' . $e->getMessage());
            $data['recent_transactions'] = array();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('transactions/transactions_list');
        $this->load->view('others/footer');
    }

    public function get_all_transactions() {
        try {
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            
            $transactions = $this->Transaction_model->get_all_transactions($start_date, $end_date);
            
            $response = array(
                'status' => 'success',
                'data' => $transactions
            );
            
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function record_payment() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get available plots for payment
        try {
            $data['plots'] = $this->Garden_model->get_plots_by_status(array('sold', 'booked'));
            $data['customers'] = $this->Customer_model->get_all_customers();
        } catch (Exception $e) {
            error_log('Error getting plots/customers: ' . $e->getMessage());
            $data['plots'] = array();
            $data['customers'] = array();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('transactions/record_payment');
        $this->load->view('others/footer');
    }

    public function submit_payment() {
        try {
            // Validate required fields
            $required_fields = ['plot_id', 'transaction_type', 'amount', 'payment_method', 'payment_date'];
            foreach ($required_fields as $field) {
                if (empty($this->input->post($field))) {
                    throw new Exception("Field '$field' is required");
                }
            }
            
            // Prepare transaction data
            $transaction_data = array(
                'plot_id' => $this->input->post('plot_id'),
                'customer_id' => $this->input->post('customer_id') ?: null,
                'transaction_type' => $this->input->post('transaction_type'),
                'amount' => $this->input->post('amount'),
                'payment_method' => $this->input->post('payment_method'),
                'payment_date' => $this->input->post('payment_date'),
                'receipt_number' => $this->input->post('receipt_number') ?: null,
                'cheque_number' => $this->input->post('cheque_number') ?: null,
                'bank_name' => $this->input->post('bank_name') ?: null,
                'reference_number' => $this->input->post('reference_number') ?: null,
                'installment_number' => $this->input->post('installment_number') ?: null,
                'total_installments' => $this->input->post('total_installments') ?: null,
                'notes' => $this->input->post('notes') ?: null,
                'status' => 'completed'
            );
            
            // Record the transaction
            $transaction_id = $this->Transaction_model->record_transaction($transaction_data);
            
            if ($transaction_id) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Payment recorded successfully',
                    'transaction_id' => $transaction_id
                );
            } else {
                throw new Exception('Failed to record payment');
            }
            
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function payment_schedules() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get payment schedules
        try {
            $plot_id = $this->input->get('plot_id');
            if ($plot_id) {
                $data['schedules'] = $this->Transaction_model->get_payment_schedules_by_plot($plot_id);
            } else {
                $data['schedules'] = array();
            }
            
            $data['plots'] = $this->Garden_model->get_plots_by_status(array('sold', 'booked'));
        } catch (Exception $e) {
            error_log('Error getting payment schedules: ' . $e->getMessage());
            $data['schedules'] = array();
            $data['plots'] = array();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('transactions/payment_schedules');
        $this->load->view('others/footer');
    }

    public function create_schedule() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get available plots
        try {
            $data['plots'] = $this->Garden_model->get_plots_by_status(array('sold', 'booked'));
            $data['customers'] = $this->Customer_model->get_all_customers();
        } catch (Exception $e) {
            error_log('Error getting plots/customers: ' . $e->getMessage());
            $data['plots'] = array();
            $data['customers'] = array();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('transactions/create_schedule');
        $this->load->view('others/footer');
    }

    public function submit_schedule() {
        try {
            // Validate required fields
            $required_fields = ['plot_id', 'schedule_type', 'due_date', 'amount'];
            foreach ($required_fields as $field) {
                if (empty($this->input->post($field))) {
                    throw new Exception("Field '$field' is required");
                }
            }
            
            // Prepare schedule data
            $schedule_data = array(
                'plot_id' => $this->input->post('plot_id'),
                'customer_id' => $this->input->post('customer_id') ?: null,
                'schedule_type' => $this->input->post('schedule_type'),
                'due_date' => $this->input->post('due_date'),
                'amount' => $this->input->post('amount'),
                'installment_number' => $this->input->post('installment_number') ?: null,
                'total_installments' => $this->input->post('total_installments') ?: null,
                'notes' => $this->input->post('notes') ?: null,
                'status' => 'pending'
            );
            
            // Create the payment schedule
            $result = $this->Transaction_model->create_payment_schedule($schedule_data);
            
            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Payment schedule created successfully'
                );
            } else {
                throw new Exception('Failed to create payment schedule');
            }
            
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function pending_payments() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        try {
            $data['pending_payments'] = $this->Transaction_model->get_pending_payments();
        } catch (Exception $e) {
            error_log('Error getting pending payments: ' . $e->getMessage());
            $data['pending_payments'] = array();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('transactions/pending_payments');
        $this->load->view('others/footer');
    }

    public function customer_transactions($customer_id = null) {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        if (!$customer_id) {
            $customer_id = $this->input->get('customer_id');
        }
        
        try {
            if ($customer_id) {
                $data['transactions'] = $this->Transaction_model->get_transactions_by_customer($customer_id);
                $data['payment_summary'] = $this->Transaction_model->get_customer_payment_summary($customer_id);
                $data['customer'] = $this->Customer_model->get_customer_by_id($customer_id);
            } else {
                $data['transactions'] = array();
                $data['payment_summary'] = array();
                $data['customer'] = null;
            }
        } catch (Exception $e) {
            error_log('Error getting customer transactions: ' . $e->getMessage());
            $data['transactions'] = array();
            $data['payment_summary'] = array();
            $data['customer'] = null;
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('transactions/customer_transactions');
        $this->load->view('others/footer');
    }

    public function plot_transactions($plot_id = null) {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        if (!$plot_id) {
            $plot_id = $this->input->get('plot_id');
        }
        
        try {
            if ($plot_id) {
                $data['transactions'] = $this->Transaction_model->get_transactions_by_plot($plot_id);
                $data['payment_schedules'] = $this->Transaction_model->get_payment_schedules_by_plot($plot_id);
                $data['plot'] = $this->Garden_model->get_plot_by_id($plot_id);
            } else {
                $data['transactions'] = array();
                $data['payment_schedules'] = array();
                $data['plot'] = null;
            }
        } catch (Exception $e) {
            error_log('Error getting plot transactions: ' . $e->getMessage());
            $data['transactions'] = array();
            $data['payment_schedules'] = array();
            $data['plot'] = null;
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('transactions/plot_transactions');
        $this->load->view('others/footer');
    }

    public function delete_transaction($transaction_id) {
        try {
            $result = $this->Transaction_model->delete_transaction($transaction_id);
            
            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Transaction deleted successfully'
                );
            } else {
                throw new Exception('Failed to delete transaction');
            }
            
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function export_transactions() {
        try {
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            $plot_id = $this->input->get('plot_id');
            $customer_id = $this->input->get('customer_id');
            
            $transactions = array();
            
            if ($plot_id) {
                $transactions = $this->Transaction_model->get_transactions_by_plot($plot_id);
            } elseif ($customer_id) {
                $transactions = $this->Transaction_model->get_transactions_by_customer($customer_id);
            } else {
                // Get all transactions within date range
                $transactions = $this->Transaction_model->get_all_transactions($start_date, $end_date);
            }
            
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="transactions_' . date('Y-m-d') . '.csv"');
            
            // Create CSV output
            $output = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($output, array('Date', 'Plot', 'Customer', 'Type', 'Amount', 'Method', 'Status'));
            
            // Add data
            foreach ($transactions as $transaction) {
                fputcsv($output, array(
                    $transaction->payment_date,
                    $transaction->plot_no ?? 'N/A',
                    $transaction->customer_name ?? 'N/A',
                    $transaction->transaction_type,
                    $transaction->amount,
                    $transaction->payment_method,
                    $transaction->status
                ));
            }
            
            fclose($output);
            
        } catch (Exception $e) {
            error_log('Error exporting transactions: ' . $e->getMessage());
            echo "Error exporting transactions: " . $e->getMessage();
        }
    }

    public function get_recent_transactions() {
        try {
            $limit = $this->input->get('limit') ?: 10;
            
            $transactions = $this->Transaction_model->get_recent_transactions($limit);
            
            $response = array(
                'status' => 'success',
                'data' => $transactions
            );
            
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
