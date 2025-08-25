<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Analytics_simple extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function properties() {
        echo "<h1>Property Analytics</h1>";
        echo "<p>This is a simple property analytics page to test navigation.</p>";
        echo "<p><a href='" . base_url('dashboard') . "'>Back to Dashboard</a></p>";
        
        try {
            $this->load->database();
            
            // Property status breakdown
            $query = $this->db->query("SELECT status, COUNT(*) as count FROM properties GROUP BY status");
            $status_data = $query->result_array();
            
            echo "<h2>Property Status Breakdown:</h2>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Status</th><th>Count</th></tr>";
            foreach ($status_data as $row) {
                echo "<tr><td>" . ucfirst($row['status']) . "</td><td>" . $row['count'] . "</td></tr>";
            }
            echo "</table>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
    
    public function financial() {
        echo "<h1>Financial Analytics</h1>";
        echo "<p>This is a simple financial analytics page to test navigation.</p>";
        echo "<p><a href='" . base_url('dashboard') . "'>Back to Dashboard</a></p>";
        
        try {
            $this->load->database();
            
            // Revenue by payment method
            $query = $this->db->query("SELECT payment_method, SUM(amount) as total FROM transactions GROUP BY payment_method");
            $payment_data = $query->result_array();
            
            echo "<h2>Revenue by Payment Method:</h2>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Payment Method</th><th>Total Amount</th></tr>";
            foreach ($payment_data as $row) {
                echo "<tr><td>" . ucfirst($row['payment_method']) . "</td><td>₹" . number_format($row['total']) . "</td></tr>";
            }
            echo "</table>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
    
    public function customers() {
        echo "<h1>Customer Analytics</h1>";
        echo "<p>This is a simple customer analytics page to test navigation.</p>";
        echo "<p><a href='" . base_url('dashboard') . "'>Back to Dashboard</a></p>";
        
        try {
            $this->load->database();
            
            // Customers by district
            $query = $this->db->query("SELECT district, COUNT(*) as count FROM customers GROUP BY district ORDER BY count DESC");
            $district_data = $query->result_array();
            
            echo "<h2>Customers by District:</h2>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>District</th><th>Customer Count</th></tr>";
            foreach ($district_data as $row) {
                echo "<tr><td>" . $row['district'] . "</td><td>" . $row['count'] . "</td></tr>";
            }
            echo "</table>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
}
?>