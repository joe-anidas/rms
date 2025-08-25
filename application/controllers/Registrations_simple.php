<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registrations_simple extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        echo "<h1>Registrations List</h1>";
        echo "<p>This is a simple registrations page to test navigation.</p>";
        echo "<p><a href='" . base_url('dashboard') . "'>Back to Dashboard</a></p>";
        
        try {
            $this->load->database();
            $query = $this->db->query("SELECT r.*, c.plot_buyer_name, p.garden_name FROM registrations r LEFT JOIN customers c ON r.customer_id = c.id LEFT JOIN properties p ON r.property_id = p.id LIMIT 10");
            $registrations = $query->result_array();
            
            echo "<h2>Registrations from Database:</h2>";
            if (!empty($registrations)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Customer</th><th>Property</th><th>Date</th><th>Amount</th><th>Status</th></tr>";
                foreach ($registrations as $registration) {
                    echo "<tr>";
                    echo "<td>" . $registration['id'] . "</td>";
                    echo "<td>" . $registration['plot_buyer_name'] . "</td>";
                    echo "<td>" . $registration['garden_name'] . "</td>";
                    echo "<td>" . $registration['registration_date'] . "</td>";
                    echo "<td>₹" . number_format($registration['registration_amount']) . "</td>";
                    echo "<td>" . $registration['status'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No registrations found.</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
    
    public function create() {
        echo "<h1>Create New Registration</h1>";
        echo "<p>This would be the registration creation form.</p>";
        echo "<p><a href='" . base_url('registrations') . "'>Back to Registrations List</a></p>";
    }
}
?>