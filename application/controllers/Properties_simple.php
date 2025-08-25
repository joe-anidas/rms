<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties_simple extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        echo "<h1>Properties List</h1>";
        echo "<p>This is a simple properties page to test navigation.</p>";
        echo "<p><a href='" . base_url('dashboard') . "'>Back to Dashboard</a></p>";
        
        try {
            $this->load->database();
            $query = $this->db->query("SELECT * FROM properties LIMIT 10");
            $properties = $query->result_array();
            
            echo "<h2>Properties from Database:</h2>";
            if (!empty($properties)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Name</th><th>Type</th><th>Status</th><th>Price</th></tr>";
                foreach ($properties as $property) {
                    echo "<tr>";
                    echo "<td>" . $property['id'] . "</td>";
                    echo "<td>" . $property['garden_name'] . "</td>";
                    echo "<td>" . $property['property_type'] . "</td>";
                    echo "<td>" . $property['status'] . "</td>";
                    echo "<td>₹" . number_format($property['price']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No properties found.</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
    
    public function create() {
        echo "<h1>Create New Property</h1>";
        echo "<p>This would be the property creation form.</p>";
        echo "<p><a href='" . base_url('properties') . "'>Back to Properties List</a></p>";
    }
}
?>