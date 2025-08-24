<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
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
                error_log('Table payment_schedules already exists');
                return true;
            }
            
            $sql = "CREATE TABLE IF NOT EXISTS payment_schedules (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                plot_id INT(11) NOT NULL,
                customer_id INT(11),
                schedule_type ENUM('installment', 'advance', 'final') NOT NULL,
                due_date DATE NOT NULL,
                amount DECIMAL(15,2) NOT NULL,
                installment_number INT(11),
                total_installments INT(11),
                status ENUM('pending', 'paid', 'overdue', 'cancelled') DEFAULT 'pending',
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (plot_id) REFERENCES plots(id) ON DELETE CASCADE,
                FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $result = $this->db->query($sql);
            error_log('Payment schedules table creation result: ' . ($result ? 'success' : 'failed'));
            
            return $result;
            
        } catch (Exception $e) {
            error_log('Error creating payment schedules table: ' . $e->getMessage());
            return false;
        }
    }

    public function record_transaction($transaction_data) {
        try {
            if (!$this->db->table_exists('transactions')) {
                $this->create_transactions_table();
            }
            
            $result = $this->db->insert('transactions', $transaction_data);
            
            if ($result) {
                $transaction_id = $this->db->insert_id();
                
                // Update plot payment status if needed
                $this->update_plot_payment_status($transaction_data['plot_id']);
                
                return $transaction_id;
            }
            return false;
            
        } catch (Exception $e) {
            error_log('Error recording transaction: ' . $e->getMessage());
            return false;
        }
    }

    public function create_payment_schedule($schedule_data) {
        try {
            if (!$this->db->table_exists('payment_schedules')) {
                $this->create_payment_schedules_table();
            }
            
            return $this->db->insert('payment_schedules', $schedule_data);
            
        } catch (Exception $e) {
            error_log('Error creating payment schedule: ' . $e->getMessage());
            return false;
        }
    }

    public function get_transactions_by_plot($plot_id) {
        try {
            if (!$this->db->table_exists('transactions')) {
                return array();
            }
            
            $this->db->where('plot_id', $plot_id);
            $this->db->order_by('payment_date', 'DESC');
            $result = $this->db->get('transactions');
            
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting transactions by plot: ' . $e->getMessage());
            return array();
        }
    }

    public function get_transactions_by_customer($customer_id) {
        try {
            if (!$this->db->table_exists('transactions')) {
                return array();
            }
            
            $this->db->where('customer_id', $customer_id);
            $this->db->order_by('payment_date', 'DESC');
            $result = $this->db->get('transactions');
            
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting transactions by customer: ' . $e->getMessage());
            return array();
        }
    }

    public function get_payment_schedules_by_plot($plot_id) {
        try {
            if (!$this->db->table_exists('payment_schedules')) {
                return array();
            }
            
            $this->db->where('plot_id', $plot_id);
            $this->db->order_by('due_date', 'ASC');
            $result = $this->db->get('payment_schedules');
            
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting payment schedules by plot: ' . $e->getMessage());
            return array();
        }
    }

    public function get_total_revenue() {
        try {
            if (!$this->db->table_exists('transactions')) {
                return 0;
            }
            
            $this->db->select('SUM(amount) as total_revenue');
            $this->db->from('transactions');
            $this->db->where('status', 'completed');
            $result = $this->db->get()->row();
            
            return $result ? $result->total_revenue : 0;
            
        } catch (Exception $e) {
            error_log('Error getting total revenue: ' . $e->getMessage());
            return 0;
        }
    }

    public function get_pending_payments() {
        try {
            if (!$this->db->table_exists('payment_schedules')) {
                return array();
            }
            
            $this->db->select('payment_schedules.*, plots.plot_no, gardens.garden_name, customers.plot_buyer_name');
            $this->db->from('payment_schedules');
            $this->db->join('plots', 'plots.id = payment_schedules.plot_id');
            $this->db->join('gardens', 'gardens.id = plots.garden_id');
            $this->db->join('customers', 'customers.id = payment_schedules.customer_id', 'left');
            $this->db->where('payment_schedules.status', 'pending');
            $this->db->where('payment_schedules.due_date <=', date('Y-m-d'));
            $this->db->order_by('payment_schedules.due_date', 'ASC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting pending payments: ' . $e->getMessage());
            return array();
        }
    }

    public function get_revenue_report($start_date = null, $end_date = null) {
        try {
            if (!$this->db->table_exists('transactions')) {
                return array();
            }
            
            $this->db->select('
                DATE(payment_date) as payment_date,
                SUM(amount) as daily_revenue,
                COUNT(*) as transaction_count,
                transaction_type
            ');
            $this->db->from('transactions');
            $this->db->where('status', 'completed');
            
            if ($start_date) {
                $this->db->where('payment_date >=', $start_date);
            }
            if ($end_date) {
                $this->db->where('payment_date <=', $end_date);
            }
            
            $this->db->group_by('DATE(payment_date), transaction_type');
            $this->db->order_by('payment_date', 'DESC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting revenue report: ' . $e->getMessage());
            return array();
        }
    }

    public function update_plot_payment_status($plot_id) {
        try {
            // Get total plot value
            $this->db->select('plot_value');
            $this->db->from('plots');
            $this->db->where('id', $plot_id);
            $plot = $this->db->get()->row();
            
            if (!$plot) return false;
            
            // Get total paid amount
            $this->db->select('SUM(amount) as total_paid');
            $this->db->from('transactions');
            $this->db->where('plot_id', $plot_id);
            $this->db->where('status', 'completed');
            $result = $this->db->get()->row();
            
            $total_paid = $result ? $result->total_paid : 0;
            $plot_value = $plot->plot_value ?: 0;
            
            // Update plot payment status
            $payment_status = 'pending';
            if ($total_paid >= $plot_value) {
                $payment_status = 'fully_paid';
            } elseif ($total_paid > 0) {
                $payment_status = 'partially_paid';
            }
            
            $this->db->where('id', $plot_id);
            $this->db->update('plots', array('payment_status' => $payment_status));
            
            return true;
            
        } catch (Exception $e) {
            error_log('Error updating plot payment status: ' . $e->getMessage());
            return false;
        }
    }

    public function get_customer_payment_summary($customer_id) {
        try {
            if (!$this->db->table_exists('transactions')) {
                return array();
            }
            
            $this->db->select('
                plot_id,
                SUM(amount) as total_paid,
                COUNT(*) as transaction_count,
                MIN(payment_date) as first_payment,
                MAX(payment_date) as last_payment
            ');
            $this->db->from('transactions');
            $this->db->where('customer_id', $customer_id);
            $this->db->where('status', 'completed');
            $this->db->group_by('plot_id');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting customer payment summary: ' . $e->getMessage());
            return array();
        }
    }

    public function delete_transaction($transaction_id) {
        try {
            $this->db->where('id', $transaction_id);
            $result = $this->db->delete('transactions');
            
            if ($result) {
                // Update plot payment status
                $this->db->select('plot_id');
                $this->db->from('transactions');
                $this->db->where('id', $transaction_id);
                $transaction = $this->db->get()->row();
                
                if ($transaction) {
                    $this->update_plot_payment_status($transaction->plot_id);
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log('Error deleting transaction: ' . $e->getMessage());
            return false;
        }
    }

    public function get_recent_transactions($limit = 10) {
        try {
            if (!$this->db->table_exists('transactions')) {
                return array();
            }
            
            $this->db->select('transactions.*, plots.plot_no, gardens.garden_name, customers.plot_buyer_name');
            $this->db->from('transactions');
            $this->db->join('plots', 'plots.id = transactions.plot_id');
            $this->db->join('gardens', 'gardens.id = plots.garden_id');
            $this->db->join('customers', 'customers.id = transactions.customer_id', 'left');
            $this->db->order_by('transactions.payment_date', 'DESC');
            $this->db->limit($limit);
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting recent transactions: ' . $e->getMessage());
            return array();
        }
    }

    public function get_all_transactions($start_date = null, $end_date = null) {
        try {
            if (!$this->db->table_exists('transactions')) {
                return array();
            }
            
            $this->db->select('transactions.*, plots.plot_no, gardens.garden_name, customers.plot_buyer_name');
            $this->db->from('transactions');
            $this->db->join('plots', 'plots.id = transactions.plot_id');
            $this->db->join('gardens', 'gardens.id = plots.garden_id');
            $this->db->join('customers', 'customers.id = transactions.customer_id', 'left');
            
            if ($start_date) {
                $this->db->where('transactions.payment_date >=', $start_date);
            }
            if ($end_date) {
                $this->db->where('transactions.payment_date <=', $end_date);
            }
            
            $this->db->order_by('transactions.payment_date', 'DESC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting all transactions: ' . $e->getMessage());
            return array();
        }
    }
}
