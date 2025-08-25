<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_simple extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        echo "<h1>Reports & Analytics</h1>";
        echo "<p>This is a simple reports page to test navigation.</p>";
        echo "<p><a href='" . base_url('dashboard') . "'>Back to Dashboard</a></p>";
        
        try {
            $this->load->database();
            
            // Get summary statistics
            $properties_count = $this->db->query("SELECT COUNT(*) as count FROM properties")->row()->count;
            $customers_count = $this->db->query("SELECT COUNT(*) as count FROM customers")->row()->count;
            $staff_count = $this->db->query("SELECT COUNT(*) as count FROM staff")->row()->count;
            $registrations_count = $this->db->query("SELECT COUNT(*) as count FROM registrations")->row()->count;
            $transactions_count = $this->db->query("SELECT COUNT(*) as count FROM transactions")->row()->count;
            
            $total_revenue = $this->db->query("SELECT SUM(amount) as total FROM transactions")->row()->total;
            
            echo "<h2>System Summary:</h2>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Category</th><th>Count</th></tr>";
            echo "<tr><td>Properties</td><td>$properties_count</td></tr>";
            echo "<tr><td>Customers</td><td>$customers_count</td></tr>";
            echo "<tr><td>Staff</td><td>$staff_count</td></tr>";
            echo "<tr><td>Registrations</td><td>$registrations_count</td></tr>";
            echo "<tr><td>Transactions</td><td>$transactions_count</td></tr>";
            echo "<tr><td><strong>Total Revenue</strong></td><td><strong>₹" . number_format($total_revenue) . "</strong></td></tr>";
            echo "</table>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
}
?>