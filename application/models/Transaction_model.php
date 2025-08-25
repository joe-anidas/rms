<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Record a new payment transaction
     * @param array $data Transaction data
     * @return int|false Transaction ID on success, false on failure
     */
    public function record_payment($data) {
        try {
            // Validate required fields
            $required_fields = ['registration_id', 'amount', 'payment_type', 'payment_method', 'payment_date'];
            foreach ($required_fields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    throw new Exception("Required field '$field' is missing");
                }
            }

            // Generate receipt number if not provided
            if (!isset($data['receipt_number']) || empty($data['receipt_number'])) {
                $data['receipt_number'] = $this->generate_receipt_number();
            }

            // Start transaction
            $this->db->trans_start();

            // Insert transaction
            $this->db->insert('transactions', $data);
            $transaction_id = $this->db->insert_id();

            if (!$transaction_id) {
                throw new Exception('Failed to insert transaction');
            }

            // Update registration paid amount
            $this->update_registration_balance($data['registration_id']);

            // Update payment schedule if installment
            if ($data['payment_type'] === 'installment') {
                $this->update_payment_schedule($data['registration_id'], $data['amount']);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }

            return $transaction_id;

        } catch (Exception $e) {
            log_message('error', 'Transaction recording failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate unique receipt number
     * @return string Receipt number
     */
    public function generate_receipt_number() {
        $prefix = 'RCP';
        $date = date('Ymd');
        
        // Get the last receipt number for today
        $this->db->select('receipt_number');
        $this->db->from('transactions');
        $this->db->like('receipt_number', $prefix . $date, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $result = $this->db->get()->row();

        if ($result) {
            // Extract sequence number and increment
            $last_number = substr($result->receipt_number, -4);
            $sequence = str_pad((int)$last_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $sequence = '0001';
        }

        return $prefix . $date . $sequence;
    }

    /**
     * Calculate balance for a registration
     * @param int $registration_id Registration ID
     * @return array Balance information
     */
    public function calculate_balance($registration_id) {
        try {
            // Get registration total amount
            $this->db->select('total_amount, paid_amount');
            $this->db->from('registrations');
            $this->db->where('id', $registration_id);
            $registration = $this->db->get()->row();

            if (!$registration) {
                return ['error' => 'Registration not found'];
            }

            // Calculate total paid from transactions
            $this->db->select('SUM(amount) as total_paid');
            $this->db->from('transactions');
            $this->db->where('registration_id', $registration_id);
            $result = $this->db->get()->row();

            $total_paid = $result ? (float)$result->total_paid : 0;
            $total_amount = (float)$registration->total_amount;
            $balance = $total_amount - $total_paid;

            return [
                'total_amount' => $total_amount,
                'total_paid' => $total_paid,
                'balance' => $balance,
                'payment_status' => $this->get_payment_status($total_paid, $total_amount)
            ];

        } catch (Exception $e) {
            log_message('error', 'Balance calculation failed: ' . $e->getMessage());
            return ['error' => 'Failed to calculate balance'];
        }
    }

    /**
     * Get payment status based on amounts
     * @param float $paid_amount Amount paid
     * @param float $total_amount Total amount
     * @return string Payment status
     */
    private function get_payment_status($paid_amount, $total_amount) {
        if ($paid_amount <= 0) {
            return 'unpaid';
        } elseif ($paid_amount >= $total_amount) {
            return 'fully_paid';
        } else {
            return 'partially_paid';
        }
    }

    /**
     * Update registration balance after payment
     * @param int $registration_id Registration ID
     * @return bool Success status
     */
    private function update_registration_balance($registration_id) {
        try {
            $balance_info = $this->calculate_balance($registration_id);
            
            if (isset($balance_info['error'])) {
                return false;
            }

            $this->db->where('id', $registration_id);
            $this->db->update('registrations', [
                'paid_amount' => $balance_info['total_paid']
            ]);

            return true;

        } catch (Exception $e) {
            log_message('error', 'Failed to update registration balance: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create payment schedule for installments
     * @param int $registration_id Registration ID
     * @param array $schedule_data Schedule configuration
     * @return bool Success status
     */
    public function create_payment_schedule($registration_id, $schedule_data) {
        try {
            $required_fields = ['total_amount', 'installment_count', 'start_date'];
            foreach ($required_fields as $field) {
                if (!isset($schedule_data[$field])) {
                    throw new Exception("Required field '$field' is missing");
                }
            }

            // Create payment schedules table if not exists
            $this->create_payment_schedules_table();

            $total_amount = (float)$schedule_data['total_amount'];
            $installment_count = (int)$schedule_data['installment_count'];
            $start_date = $schedule_data['start_date'];
            $installment_amount = $total_amount / $installment_count;

            // Clear existing schedules for this registration
            $this->db->where('registration_id', $registration_id);
            $this->db->delete('payment_schedules');

            // Create installment schedules
            for ($i = 1; $i <= $installment_count; $i++) {
                $due_date = date('Y-m-d', strtotime($start_date . ' +' . ($i - 1) . ' months'));
                
                $schedule_item = [
                    'registration_id' => $registration_id,
                    'installment_number' => $i,
                    'due_date' => $due_date,
                    'amount' => round($installment_amount, 2),
                    'status' => 'pending'
                ];

                $this->db->insert('payment_schedules', $schedule_item);
            }

            return true;

        } catch (Exception $e) {
            log_message('error', 'Payment schedule creation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payment schedule for a registration
     * @param int $registration_id Registration ID
     * @return array Payment schedule
     */
    public function get_payment_schedule($registration_id) {
        try {
            $this->db->select('*');
            $this->db->from('payment_schedules');
            $this->db->where('registration_id', $registration_id);
            $this->db->order_by('installment_number', 'ASC');
            $result = $this->db->get();

            return $result->num_rows() > 0 ? $result->result_array() : [];

        } catch (Exception $e) {
            log_message('error', 'Failed to get payment schedule: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Update payment schedule after installment payment
     * @param int $registration_id Registration ID
     * @param float $payment_amount Payment amount
     * @return bool Success status
     */
    private function update_payment_schedule($registration_id, $payment_amount) {
        try {
            // Get pending schedules
            $this->db->select('*');
            $this->db->from('payment_schedules');
            $this->db->where('registration_id', $registration_id);
            $this->db->where('status', 'pending');
            $this->db->order_by('installment_number', 'ASC');
            $schedules = $this->db->get()->result_array();

            $remaining_amount = $payment_amount;

            foreach ($schedules as $schedule) {
                if ($remaining_amount <= 0) break;

                if ($remaining_amount >= $schedule['amount']) {
                    // Full installment paid
                    $this->db->where('id', $schedule['id']);
                    $this->db->update('payment_schedules', [
                        'status' => 'paid',
                        'paid_date' => date('Y-m-d')
                    ]);
                    $remaining_amount -= $schedule['amount'];
                } else {
                    // Partial payment - update amount
                    $new_amount = $schedule['amount'] - $remaining_amount;
                    $this->db->where('id', $schedule['id']);
                    $this->db->update('payment_schedules', [
                        'amount' => $new_amount
                    ]);
                    $remaining_amount = 0;
                }
            }

            return true;

        } catch (Exception $e) {
            log_message('error', 'Failed to update payment schedule: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get transaction history with filters
     * @param array $filters Filter criteria
     * @return array Transaction history
     */
    public function get_transaction_history($filters = []) {
        try {
            $this->db->select('t.*, r.registration_number, r.property_id, c.plot_buyer_name, p.garden_name');
            $this->db->from('transactions t');
            $this->db->join('registrations r', 'r.id = t.registration_id', 'left');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');
            $this->db->join('properties p', 'p.id = r.property_id', 'left');

            // Apply filters
            if (isset($filters['registration_id'])) {
                $this->db->where('t.registration_id', $filters['registration_id']);
            }
            if (isset($filters['customer_id'])) {
                $this->db->where('r.customer_id', $filters['customer_id']);
            }
            if (isset($filters['property_id'])) {
                $this->db->where('r.property_id', $filters['property_id']);
            }
            if (isset($filters['payment_type'])) {
                $this->db->where('t.payment_type', $filters['payment_type']);
            }
            if (isset($filters['start_date'])) {
                $this->db->where('t.payment_date >=', $filters['start_date']);
            }
            if (isset($filters['end_date'])) {
                $this->db->where('t.payment_date <=', $filters['end_date']);
            }
            if (isset($filters['amount_min'])) {
                $this->db->where('t.amount >=', $filters['amount_min']);
            }
            if (isset($filters['amount_max'])) {
                $this->db->where('t.amount <=', $filters['amount_max']);
            }

            $this->db->order_by('t.payment_date', 'DESC');
            $this->db->order_by('t.id', 'DESC');

            // Pagination
            if (isset($filters['limit'])) {
                $this->db->limit($filters['limit']);
                if (isset($filters['offset'])) {
                    $this->db->offset($filters['offset']);
                }
            }

            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : [];

        } catch (Exception $e) {
            log_message('error', 'Failed to get transaction history: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Generate financial reports
     * @param array $params Report parameters
     * @return array Financial report data
     */
    public function generate_financial_report($params = []) {
        try {
            $start_date = isset($params['start_date']) ? $params['start_date'] : date('Y-m-01');
            $end_date = isset($params['end_date']) ? $params['end_date'] : date('Y-m-t');
            $group_by = isset($params['group_by']) ? $params['group_by'] : 'day';

            $report = [
                'summary' => $this->get_financial_summary($start_date, $end_date),
                'by_payment_type' => $this->get_revenue_by_payment_type($start_date, $end_date),
                'by_payment_method' => $this->get_revenue_by_payment_method($start_date, $end_date),
                'timeline' => $this->get_revenue_timeline($start_date, $end_date, $group_by),
                'top_properties' => $this->get_top_properties_by_revenue($start_date, $end_date),
                'pending_payments' => $this->get_pending_payments_report()
            ];

            return $report;

        } catch (Exception $e) {
            log_message('error', 'Failed to generate financial report: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get financial summary
     * @param string $start_date Start date
     * @param string $end_date End date
     * @return array Financial summary
     */
    private function get_financial_summary($start_date, $end_date) {
        $this->db->select('
            COUNT(*) as total_transactions,
            SUM(amount) as total_revenue,
            AVG(amount) as average_transaction,
            MIN(amount) as min_transaction,
            MAX(amount) as max_transaction
        ');
        $this->db->from('transactions');
        $this->db->where('payment_date >=', $start_date);
        $this->db->where('payment_date <=', $end_date);
        
        return $this->db->get()->row_array();
    }

    /**
     * Get revenue by payment type
     * @param string $start_date Start date
     * @param string $end_date End date
     * @return array Revenue by payment type
     */
    private function get_revenue_by_payment_type($start_date, $end_date) {
        $this->db->select('payment_type, COUNT(*) as count, SUM(amount) as total_amount');
        $this->db->from('transactions');
        $this->db->where('payment_date >=', $start_date);
        $this->db->where('payment_date <=', $end_date);
        $this->db->group_by('payment_type');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get revenue by payment method
     * @param string $start_date Start date
     * @param string $end_date End date
     * @return array Revenue by payment method
     */
    private function get_revenue_by_payment_method($start_date, $end_date) {
        $this->db->select('payment_method, COUNT(*) as count, SUM(amount) as total_amount');
        $this->db->from('transactions');
        $this->db->where('payment_date >=', $start_date);
        $this->db->where('payment_date <=', $end_date);
        $this->db->group_by('payment_method');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get revenue timeline
     * @param string $start_date Start date
     * @param string $end_date End date
     * @param string $group_by Group by period (day, week, month)
     * @return array Revenue timeline
     */
    private function get_revenue_timeline($start_date, $end_date, $group_by = 'day') {
        $date_format = match($group_by) {
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d'
        };

        $this->db->select("DATE_FORMAT(payment_date, '$date_format') as period, COUNT(*) as count, SUM(amount) as total_amount");
        $this->db->from('transactions');
        $this->db->where('payment_date >=', $start_date);
        $this->db->where('payment_date <=', $end_date);
        $this->db->group_by('period');
        $this->db->order_by('period', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get top properties by revenue
     * @param string $start_date Start date
     * @param string $end_date End date
     * @param int $limit Limit results
     * @return array Top properties
     */
    private function get_top_properties_by_revenue($start_date, $end_date, $limit = 10) {
        $this->db->select('p.id, p.garden_name, COUNT(t.id) as transaction_count, SUM(t.amount) as total_revenue');
        $this->db->from('transactions t');
        $this->db->join('registrations r', 'r.id = t.registration_id');
        $this->db->join('properties p', 'p.id = r.property_id');
        $this->db->where('t.payment_date >=', $start_date);
        $this->db->where('t.payment_date <=', $end_date);
        $this->db->group_by('p.id');
        $this->db->order_by('total_revenue', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result_array();
    }

    /**
     * Get pending payments report
     * @return array Pending payments
     */
    private function get_pending_payments_report() {
        $this->db->select('ps.*, r.registration_number, p.garden_name, c.plot_buyer_name');
        $this->db->from('payment_schedules ps');
        $this->db->join('registrations r', 'r.id = ps.registration_id');
        $this->db->join('properties p', 'p.id = r.property_id');
        $this->db->join('customers c', 'c.id = r.customer_id');
        $this->db->where('ps.status', 'pending');
        $this->db->where('ps.due_date <=', date('Y-m-d', strtotime('+30 days')));
        $this->db->order_by('ps.due_date', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function create_transactions_table() {
        try {
            if ($this->db->table_exists('transactions')) {
                error_log('Table transactions already exists');
                return true;
            }
            
            $sql = "CREATE TABLE IF NOT EXISTS transactions (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                plot_id INT(11) NOT NULL,
                customer_id INT(11),
                transaction_type ENUM('advance', 'installment', 'full_payment', 'refund') NOT NULL,
                amount DECIMAL(15,2) NOT NULL,
                payment_method ENUM('cash', 'cheque', 'bank_transfer', 'online', 'other') NOT NULL,
                payment_date DATE NOT NULL,
                receipt_number VARCHAR(100),
                cheque_number VARCHAR(100),
                bank_name VARCHAR(100),
                reference_number VARCHAR(100),
                installment_number INT(11),
                total_installments INT(11),
                notes TEXT,
                status ENUM('pending', 'completed', 'cancelled') DEFAULT 'completed',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (plot_id) REFERENCES plots(id) ON DELETE CASCADE,
                FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $result = $this->db->query($sql);
            error_log('Transactions table creation result: ' . ($result ? 'success' : 'failed'));
            
            return $result;
            
        } catch (Exception $e) {
            error_log('Error creating transactions table: ' . $e->getMessage());
            return false;
        }
    }

    public function create_payment_schedules_table() {
        try {
            if ($this->db->table_exists('payment_schedules')) {
                return true;
            }
            
            $sql = "CREATE TABLE IF NOT EXISTS payment_schedules (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                registration_id INT(11) NOT NULL,
                installment_number INT(11) NOT NULL,
                due_date DATE NOT NULL,
                amount DECIMAL(15,2) NOT NULL,
                status ENUM('pending', 'paid', 'overdue', 'cancelled') DEFAULT 'pending',
                paid_date DATE NULL,
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $result = $this->db->query($sql);
            log_message('info', 'Payment schedules table creation result: ' . ($result ? 'success' : 'failed'));
            
            return $result;
            
        } catch (Exception $e) {
            log_message('error', 'Error creating payment schedules table: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get transaction by ID
     * @param int $transaction_id Transaction ID
     * @return array|null Transaction data
     */
    public function get_transaction($transaction_id) {
        try {
            $this->db->select('t.*, r.registration_number, r.property_id, c.plot_buyer_name, p.garden_name');
            $this->db->from('transactions t');
            $this->db->join('registrations r', 'r.id = t.registration_id', 'left');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');
            $this->db->join('properties p', 'p.id = r.property_id', 'left');
            $this->db->where('t.id', $transaction_id);
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->row_array() : null;

        } catch (Exception $e) {
            log_message('error', 'Failed to get transaction: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update transaction
     * @param int $transaction_id Transaction ID
     * @param array $data Update data
     * @return bool Success status
     */
    public function update_transaction($transaction_id, $data) {
        try {
            // Get original transaction for balance recalculation
            $original = $this->get_transaction($transaction_id);
            if (!$original) {
                return false;
            }

            $this->db->trans_start();

            $this->db->where('id', $transaction_id);
            $this->db->update('transactions', $data);

            // Recalculate registration balance
            $this->update_registration_balance($original['registration_id']);

            $this->db->trans_complete();

            return $this->db->trans_status() !== FALSE;

        } catch (Exception $e) {
            log_message('error', 'Failed to update transaction: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete transaction
     * @param int $transaction_id Transaction ID
     * @return bool Success status
     */
    public function delete_transaction($transaction_id) {
        try {
            // Get transaction for balance recalculation
            $transaction = $this->get_transaction($transaction_id);
            if (!$transaction) {
                return false;
            }

            $this->db->trans_start();

            $this->db->where('id', $transaction_id);
            $this->db->delete('transactions');

            // Recalculate registration balance
            $this->update_registration_balance($transaction['registration_id']);

            $this->db->trans_complete();

            return $this->db->trans_status() !== FALSE;

        } catch (Exception $e) {
            log_message('error', 'Failed to delete transaction: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate receipt data for printing
     * @param int $transaction_id Transaction ID
     * @return array|null Receipt data
     */
    public function generate_receipt($transaction_id) {
        try {
            $transaction = $this->get_transaction($transaction_id);
            if (!$transaction) {
                return null;
            }

            $balance_info = $this->calculate_balance($transaction['registration_id']);

            $receipt = [
                'transaction' => $transaction,
                'balance_info' => $balance_info,
                'receipt_date' => date('Y-m-d H:i:s'),
                'company_info' => [
                    'name' => 'Real Estate Management System',
                    'address' => 'Your Company Address',
                    'phone' => 'Your Phone Number',
                    'email' => 'your@email.com'
                ]
            ];

            return $receipt;

        } catch (Exception $e) {
            log_message('error', 'Failed to generate receipt: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get overdue payments
     * @param int $days_overdue Days overdue (default: 0 for today)
     * @return array Overdue payments
     */
    public function get_overdue_payments($days_overdue = 0) {
        try {
            $overdue_date = date('Y-m-d', strtotime("-$days_overdue days"));

            $this->db->select('ps.*, r.registration_number, p.garden_name, c.plot_buyer_name, c.contact_details');
            $this->db->from('payment_schedules ps');
            $this->db->join('registrations r', 'r.id = ps.registration_id');
            $this->db->join('properties p', 'p.id = r.property_id');
            $this->db->join('customers c', 'c.id = r.customer_id');
            $this->db->where('ps.status', 'pending');
            $this->db->where('ps.due_date <=', $overdue_date);
            $this->db->order_by('ps.due_date', 'ASC');

            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : [];

        } catch (Exception $e) {
            log_message('error', 'Failed to get overdue payments: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Mark overdue payments
     * @return bool Success status
     */
    public function mark_overdue_payments() {
        try {
            $this->db->where('status', 'pending');
            $this->db->where('due_date <', date('Y-m-d'));
            $this->db->update('payment_schedules', ['status' => 'overdue']);

            return true;

        } catch (Exception $e) {
            log_message('error', 'Failed to mark overdue payments: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get customer transaction summary
     * @param int $customer_id Customer ID
     * @return array Transaction summary
     */
    public function get_customer_transaction_summary($customer_id) {
        try {
            $this->db->select('
                COUNT(t.id) as total_transactions,
                SUM(t.amount) as total_paid,
                MIN(t.payment_date) as first_payment_date,
                MAX(t.payment_date) as last_payment_date,
                COUNT(DISTINCT r.id) as properties_count
            ');
            $this->db->from('transactions t');
            $this->db->join('registrations r', 'r.id = t.registration_id');
            $this->db->where('r.customer_id', $customer_id);

            $summary = $this->db->get()->row_array();

            // Get pending amount
            $this->db->select('SUM(ps.amount) as pending_amount');
            $this->db->from('payment_schedules ps');
            $this->db->join('registrations r', 'r.id = ps.registration_id');
            $this->db->where('r.customer_id', $customer_id);
            $this->db->where('ps.status', 'pending');
            $pending = $this->db->get()->row();

            $summary['pending_amount'] = $pending ? $pending->pending_amount : 0;

            return $summary;

        } catch (Exception $e) {
            log_message('error', 'Failed to get customer transaction summary: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get transactions by property
     * @param int $property_id Property ID
     * @return array Transactions
     */
    public function get_transactions_by_property($property_id) {
        try {
            $this->db->select('t.*, r.registration_number, c.plot_buyer_name');
            $this->db->from('transactions t');
            $this->db->join('registrations r', 'r.id = t.registration_id');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');
            $this->db->where('r.property_id', $property_id);
            $this->db->order_by('t.payment_date', 'DESC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : [];
            
        } catch (Exception $e) {
            log_message('error', 'Error getting transactions by property: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Validate transaction data before processing
     * @param array $data Transaction data
     * @return array Validation result
     */
    public function validate_transaction_data($data) {
        $errors = [];
        $valid = true;

        // Check if registration exists and is active
        if (isset($data['registration_id'])) {
            $this->db->select('id, total_amount, paid_amount, status');
            $this->db->from('registrations');
            $this->db->where('id', $data['registration_id']);
            $registration = $this->db->get()->row();

            if (!$registration) {
                $errors[] = 'Registration not found';
                $valid = false;
            } elseif ($registration->status !== 'active') {
                $errors[] = 'Registration is not active';
                $valid = false;
            } else {
                // Check if payment amount is valid
                if (isset($data['amount'])) {
                    $balance_info = $this->calculate_balance($data['registration_id']);
                    if (!isset($balance_info['error']) && $data['amount'] > $balance_info['balance']) {
                        $errors[] = 'Payment amount exceeds remaining balance';
                        $valid = false;
                    }
                }
            }
        }

        // Validate payment date
        if (isset($data['payment_date'])) {
            $payment_date = strtotime($data['payment_date']);
            if (!$payment_date) {
                $errors[] = 'Invalid payment date format';
                $valid = false;
            } elseif ($payment_date > time()) {
                $errors[] = 'Payment date cannot be in the future';
                $valid = false;
            }
        }

        return ['valid' => $valid, 'errors' => $errors];
    }

    /**
     * Get payment statistics
     * @param array $filters Filter criteria
     * @return array Payment statistics
     */
    public function get_payment_statistics($filters = []) {
        try {
            $this->db->select('
                COUNT(*) as total_transactions,
                SUM(amount) as total_amount,
                AVG(amount) as average_amount,
                MIN(amount) as min_amount,
                MAX(amount) as max_amount
            ');
            $this->db->from('transactions');

            // Apply filters
            if (isset($filters['start_date'])) {
                $this->db->where('payment_date >=', $filters['start_date']);
            }
            if (isset($filters['end_date'])) {
                $this->db->where('payment_date <=', $filters['end_date']);
            }
            if (isset($filters['payment_type'])) {
                $this->db->where('payment_type', $filters['payment_type']);
            }
            if (isset($filters['payment_method'])) {
                $this->db->where('payment_method', $filters['payment_method']);
            }

            $result = $this->db->get()->row_array();
            
            // Get payment type breakdown
            $this->db->select('payment_type, COUNT(*) as count, SUM(amount) as total');
            $this->db->from('transactions');
            
            // Apply same filters
            if (isset($filters['start_date'])) {
                $this->db->where('payment_date >=', $filters['start_date']);
            }
            if (isset($filters['end_date'])) {
                $this->db->where('payment_date <=', $filters['end_date']);
            }
            if (isset($filters['payment_method'])) {
                $this->db->where('payment_method', $filters['payment_method']);
            }
            
            $this->db->group_by('payment_type');
            $type_breakdown = $this->db->get()->result_array();

            $result['type_breakdown'] = $type_breakdown;
            return $result;

        } catch (Exception $e) {
            log_message('error', 'Failed to get payment statistics: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get transactions by registration
     * @param int $registration_id Registration ID
     * @return array Transactions
     */
    public function get_transactions_by_registration($registration_id) {
        try {
            $this->db->select('t.*, r.registration_number, c.plot_buyer_name, p.garden_name');
            $this->db->from('transactions t');
            $this->db->join('registrations r', 'r.id = t.registration_id');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');
            $this->db->join('properties p', 'p.id = r.property_id', 'left');
            $this->db->where('t.registration_id', $registration_id);
            $this->db->order_by('t.payment_date', 'DESC');
            $this->db->order_by('t.id', 'DESC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : [];
            
        } catch (Exception $e) {
            log_message('error', 'Error getting transactions by registration: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get pending payments (upcoming due dates)
     * @param int $days_ahead Days to look ahead
     * @return array Pending payments
     */
    public function get_pending_payments($days_ahead = 30) {
        try {
            $future_date = date('Y-m-d', strtotime("+$days_ahead days"));

            $this->db->select('ps.*, r.registration_number, p.garden_name, c.plot_buyer_name, c.contact_details');
            $this->db->from('payment_schedules ps');
            $this->db->join('registrations r', 'r.id = ps.registration_id');
            $this->db->join('properties p', 'p.id = r.property_id');
            $this->db->join('customers c', 'c.id = r.customer_id');
            $this->db->where('ps.status', 'pending');
            $this->db->where('ps.due_date >=', date('Y-m-d'));
            $this->db->where('ps.due_date <=', $future_date);
            $this->db->order_by('ps.due_date', 'ASC');

            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : [];

        } catch (Exception $e) {
            log_message('error', 'Failed to get pending payments: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get transactions by customer
     * @param int $customer_id Customer ID
     * @return array Transactions
     */
    public function get_transactions_by_customer($customer_id) {
        try {
            $this->db->select('t.*, r.registration_number, p.garden_name');
            $this->db->from('transactions t');
            $this->db->join('registrations r', 'r.id = t.registration_id');
            $this->db->join('properties p', 'p.id = r.property_id', 'left');
            $this->db->where('r.customer_id', $customer_id);
            $this->db->order_by('t.payment_date', 'DESC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : [];
            
        } catch (Exception $e) {
            log_message('error', 'Error getting transactions by customer: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get transactions by registration
     * @param int $registration_id Registration ID
     * @return array Transactions
     */
    public function get_transactions_by_registration($registration_id) {
        try {
            $this->db->select('*');
            $this->db->from('transactions');
            $this->db->where('registration_id', $registration_id);
            $this->db->order_by('payment_date', 'DESC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : [];
            
        } catch (Exception $e) {
            log_message('error', 'Error getting transactions by registration: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get total revenue
     * @param string $start_date Start date (optional)
     * @param string $end_date End date (optional)
     * @return float Total revenue
     */
    public function get_total_revenue($start_date = null, $end_date = null) {
        try {
            $this->db->select('SUM(amount) as total_revenue');
            $this->db->from('transactions');
            
            if ($start_date) {
                $this->db->where('payment_date >=', $start_date);
            }
            if ($end_date) {
                $this->db->where('payment_date <=', $end_date);
            }
            
            $result = $this->db->get()->row();
            return $result ? (float)$result->total_revenue : 0;
            
        } catch (Exception $e) {
            log_message('error', 'Error getting total revenue: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get pending payments
     * @param int $days_ahead Days to look ahead (default: 30)
     * @return array Pending payments
     */
    public function get_pending_payments($days_ahead = 30) {
        try {
            $future_date = date('Y-m-d', strtotime("+$days_ahead days"));
            
            $this->db->select('ps.*, r.registration_number, p.garden_name, c.plot_buyer_name, c.contact_details');
            $this->db->from('payment_schedules ps');
            $this->db->join('registrations r', 'r.id = ps.registration_id');
            $this->db->join('properties p', 'p.id = r.property_id');
            $this->db->join('customers c', 'c.id = r.customer_id');
            $this->db->where('ps.status', 'pending');
            $this->db->where('ps.due_date <=', $future_date);
            $this->db->order_by('ps.due_date', 'ASC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : [];
            
        } catch (Exception $e) {
            log_message('error', 'Error getting pending payments: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get recent transactions
     * @param int $limit Number of transactions to retrieve
     * @return array Recent transactions
     */
    public function get_recent_transactions($limit = 10) {
        try {
            $this->db->select('t.*, r.registration_number, p.garden_name, c.plot_buyer_name');
            $this->db->from('transactions t');
            $this->db->join('registrations r', 'r.id = t.registration_id');
            $this->db->join('properties p', 'p.id = r.property_id');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');
            $this->db->order_by('t.payment_date', 'DESC');
            $this->db->order_by('t.id', 'DESC');
            $this->db->limit($limit);
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : [];
            
        } catch (Exception $e) {
            log_message('error', 'Error getting recent transactions: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Validate transaction data
     * @param array $data Transaction data
     * @return array Validation result
     */
    public function validate_transaction_data($data) {
        $errors = [];

        // Required fields validation
        $required_fields = ['registration_id', 'amount', 'payment_type', 'payment_method', 'payment_date'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[] = "Field '$field' is required";
            }
        }

        // Amount validation
        if (isset($data['amount']) && (!is_numeric($data['amount']) || $data['amount'] <= 0)) {
            $errors[] = "Amount must be a positive number";
        }

        // Payment type validation
        $valid_payment_types = ['advance', 'installment', 'full_payment'];
        if (isset($data['payment_type']) && !in_array($data['payment_type'], $valid_payment_types)) {
            $errors[] = "Invalid payment type";
        }

        // Payment method validation
        $valid_payment_methods = ['cash', 'cheque', 'bank_transfer', 'online'];
        if (isset($data['payment_method']) && !in_array($data['payment_method'], $valid_payment_methods)) {
            $errors[] = "Invalid payment method";
        }

        // Date validation
        if (isset($data['payment_date']) && !strtotime($data['payment_date'])) {
            $errors[] = "Invalid payment date format";
        }

        // Registration validation
        if (isset($data['registration_id'])) {
            $this->db->where('id', $data['registration_id']);
            $registration = $this->db->get('registrations')->row();
            if (!$registration) {
                $errors[] = "Registration not found";
            } elseif ($registration->status === 'cancelled') {
                $errors[] = "Cannot add payment to cancelled registration";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Get payment statistics
     * @param array $filters Optional filters
     * @return array Payment statistics
     */
    public function get_payment_statistics($filters = []) {
        try {
            $stats = [];

            // Total transactions
            $this->db->select('COUNT(*) as total_count, SUM(amount) as total_amount');
            $this->db->from('transactions');
            $this->apply_filters($filters);
            $total = $this->db->get()->row_array();
            $stats['total'] = $total;

            // By payment type
            $this->db->select('payment_type, COUNT(*) as count, SUM(amount) as amount');
            $this->db->from('transactions');
            $this->apply_filters($filters);
            $this->db->group_by('payment_type');
            $stats['by_type'] = $this->db->get()->result_array();

            // By payment method
            $this->db->select('payment_method, COUNT(*) as count, SUM(amount) as amount');
            $this->db->from('transactions');
            $this->apply_filters($filters);
            $this->db->group_by('payment_method');
            $stats['by_method'] = $this->db->get()->result_array();

            return $stats;

        } catch (Exception $e) {
            log_message('error', 'Error getting payment statistics: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Apply filters to query
     * @param array $filters Filters to apply
     */
    private function apply_filters($filters) {
        if (isset($filters['start_date'])) {
            $this->db->where('payment_date >=', $filters['start_date']);
        }
        if (isset($filters['end_date'])) {
            $this->db->where('payment_date <=', $filters['end_date']);
        }
        if (isset($filters['payment_type'])) {
            $this->db->where('payment_type', $filters['payment_type']);
        }
        if (isset($filters['payment_method'])) {
            $this->db->where('payment_method', $filters['payment_method']);
        }
    }
}
