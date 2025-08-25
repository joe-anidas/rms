<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_simple extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        echo "<h1>Staff List</h1>";
        echo "<p>This is a simple staff page to test navigation.</p>";
        echo "<p><a href='" . base_url('dashboard') . "'>Back to Dashboard</a></p>";
        
        try {
            $this->load->database();
            $query = $this->db->query("SELECT * FROM staff LIMIT 10");
            $staff = $query->result_array();
            
            echo "<h2>Staff from Database:</h2>";
            if (!empty($staff)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Name</th><th>Employee ID</th><th>Phone</th><th>Designation</th><th>Status</th></tr>";
                foreach ($staff as $member) {
                    echo "<tr>";
                    echo "<td>" . $member['id'] . "</td>";
                    echo "<td>" . $member['employee_name'] . "</td>";
                    echo "<td>" . $member['employee_id'] . "</td>";
                    echo "<td>" . $member['phone_number'] . "</td>";
                    echo "<td>" . $member['designation'] . "</td>";
                    echo "<td>" . $member['status'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No staff found.</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
    
    public function create() {
        echo "<h1>Create New Staff Member</h1>";
        echo "<p>This would be the staff creation form.</p>";
        echo "<p><a href='" . base_url('staff') . "'>Back to Staff List</a></p>";
    }
}
?>