<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_simple extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        echo "<h1>Customers List</h1>";
        echo "<p>This is a simple customers page to test navigation.</p>";
        echo "<p><a href='" . base_url('dashboard') . "'>Back to Dashboard</a></p>";
        
        try {
            $this->load->database();
            $query = $this->db->query("SELECT * FROM customers LIMIT 10");
            $customers = $query->result_array();
            
            echo "<h2>Customers from Database:</h2>";
            if (!empty($customers)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Name</th><th>Phone</th><th>District</th><th>Plots</th></tr>";
                foreach ($customers as $customer) {
                    echo "<tr>";
                    echo "<td>" . $customer['id'] . "</td>";
                    echo "<td>" . $customer['plot_buyer_name'] . "</td>";
                    echo "<td>" . $customer['phone_number_1'] . "</td>";
                    echo "<td>" . $customer['district'] . "</td>";
                    echo "<td>" . $customer['total_plot_bought'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No customers found.</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
    
    public function create() {
        echo "<h1>Create New Customer</h1>";
        echo "<p>This would be the customer creation form.</p>";
        echo "<p><a href='" . base_url('customers') . "'>Back to Customers List</a></p>";
    }
}
?>