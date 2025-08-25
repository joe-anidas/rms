<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions_simple extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        echo "<h1>Transactions List</h1>";
        echo "<p>This is a simple transactions page to test navigation.</p>";
        echo "<p><a href='" . base_url('dashboard') . "'>Back to Dashboard</a></p>";
        
        try {
            $this->load->database();
            $query = $this->db->query("SELECT t.*, c.plot_buyer_name FROM transactions t LEFT JOIN customers c ON t.customer_id = c.id LIMIT 10");
            $transactions = $query->result_array();
            
            echo "<h2>Transactions from Database:</h2>";
            if (!empty($transactions)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Customer</th><th>Type</th><th>Amount</th><th>Payment Method</th><th>Date</th></tr>";
                foreach ($transactions as $transaction) {
                    echo "<tr>";
                    echo "<td>" . $transaction['id'] . "</td>";
                    echo "<td>" . $transaction['plot_buyer_name'] . "</td>";
                    echo "<td>" . $transaction['transaction_type'] . "</td>";
                    echo "<td>₹" . number_format($transaction['amount']) . "</td>";
                    echo "<td>" . $transaction['payment_method'] . "</td>";
                    echo "<td>" . $transaction['payment_date'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No transactions found.</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
    
    public function record_payment() {
        echo "<h1>Record New Payment</h1>";
        echo "<p>This would be the payment recording form.</p>";
        echo "<p><a href='" . base_url('transactions') . "'>Back to Transactions List</a></p>";
    }
}
?>