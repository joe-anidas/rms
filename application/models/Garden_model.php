<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Garden_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function create_garden_table() {
        try {
            // Check if table already exists
            if ($this->db->table_exists('gardens')) {
                error_log('Table gardens already exists');
                return true;
            }
            
            $sql = "CREATE TABLE IF NOT EXISTS gardens (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                garden_name VARCHAR(255) NOT NULL,
                district VARCHAR(100),
                taluk_name VARCHAR(100),
                village_town_name VARCHAR(100),
                patta_chitta_no VARCHAR(100),
                ts_no VARCHAR(100),
                ward_block VARCHAR(100),
                land_mark VARCHAR(255),
                dtcp_no VARCHAR(100),
                rera_no VARCHAR(100),
                total_extension VARCHAR(100),
                total_plots INT(11),
                sale_extension VARCHAR(100),
                park_extension VARCHAR(100),
                road_extension VARCHAR(100),
                eb_line ENUM('yes', 'no'),
                tree_saplings ENUM('yes', 'no'),
                water_tank ENUM('yes', 'no'),
                land_purchased_rs VARCHAR(100),
                land_unpurchased_rs VARCHAR(100),
                incentive_percentage VARCHAR(10),
                registration_district VARCHAR(100),
                registration_sub_district VARCHAR(100),
                town_village VARCHAR(100),
                revenue_taluk VARCHAR(100),
                sub_registrar VARCHAR(100),
                image_path VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $result = $this->db->query($sql);
            error_log('Garden table creation result: ' . ($result ? 'success' : 'failed'));
            
            if (!$result) {
                error_log('DB Error: ' . print_r($this->db->error(), true));
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log('Error creating garden table: ' . $e->getMessage());
            return false;
        }
    }

    public function create_plots_table() {
        try {
            // Check if table already exists
            if ($this->db->table_exists('plots')) {
                error_log('Table plots already exists');
                return true;
            }
            
            $sql = "CREATE TABLE IF NOT EXISTS plots (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                garden_id INT(11) NOT NULL,
                plot_no VARCHAR(50) NOT NULL,
                plot_extension VARCHAR(100),
                north VARCHAR(100),
                east VARCHAR(100),
                west VARCHAR(100),
                south VARCHAR(100),
                plot_value DECIMAL(15,2),
                status ENUM('unsold', 'booked', 'sold') DEFAULT 'unsold',
                customer_name VARCHAR(255),
                customer_phone VARCHAR(20),
                booking_date DATE,
                booking_amount DECIMAL(15,2),
                sale_date DATE,
                sale_amount DECIMAL(15,2),
                payment_method VARCHAR(50),
                booking_reference VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (garden_id) REFERENCES gardens(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $result = $this->db->query($sql);
            error_log('Plots table creation result: ' . ($result ? 'success' : 'failed'));
            
            if (!$result) {
                error_log('DB Error: ' . print_r($this->db->error(), true));
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log('Error creating plots table: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_garden($data) {
        try {
            error_log('Attempting to insert garden data: ' . print_r($data, true));
            
            // Check if tables exist
            if (!$this->db->table_exists('gardens')) {
                error_log('Table gardens does not exist, creating it first');
                $this->create_garden_table();
            }
            
            // Validate required field
            if (empty($data['garden_name'])) {
                error_log('Error: garden_name is empty');
                return false;
            }
            
            // Insert garden data
            $result = $this->db->insert('gardens', $data);
            error_log('Insert garden result: ' . ($result ? 'success' : 'failed'));
            
            if (!$result) {
                $db_error = $this->db->error();
                error_log('DB Error: ' . print_r($db_error, true));
            } else {
                $insert_id = $this->db->insert_id();
                error_log('Garden inserted successfully with ID: ' . $insert_id);
            }
            
            return $result;
        } catch (Exception $e) {
            error_log('Exception inserting garden: ' . $e->getMessage());
            return false;
        }
    }

    public function insert_plots($garden_id, $plots_data) {
        try {
            // Check if plots table exists
            if (!$this->db->table_exists('plots')) {
                error_log('Table plots does not exist, creating it first');
                $this->create_plots_table();
            }
            
            $inserted_plots = 0;
            foreach ($plots_data as $plot) {
                $plot['garden_id'] = $garden_id;
                $result = $this->db->insert('plots', $plot);
                if ($result) {
                    $inserted_plots++;
                }
            }
            
            error_log('Inserted ' . $inserted_plots . ' plots for garden ID: ' . $garden_id);
            return $inserted_plots > 0;
            
        } catch (Exception $e) {
            error_log('Exception inserting plots: ' . $e->getMessage());
            return false;
        }
    }

    public function get_all_gardens() {
        try {
            if (!$this->db->table_exists('gardens')) {
                $this->create_garden_table();
            }
            
            $this->db->order_by('created_at', 'DESC');
            $result = $this->db->get('gardens');
            
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }
            
        } catch (Exception $e) {
            error_log('Error in get_all_gardens: ' . $e->getMessage());
            return array();
        }
    }

    public function get_garden_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('gardens')->row();
    }

    public function get_sold_plots() {
        try {
            if (!$this->db->table_exists('plots')) {
                $this->create_plots_table();
            }
            
            $this->db->select('plots.*, gardens.garden_name, gardens.district, gardens.taluk_name, gardens.village_town_name');
            $this->db->from('plots');
            $this->db->join('gardens', 'gardens.id = plots.garden_id');
            $this->db->where('plots.status', 'sold');
            $this->db->order_by('plots.sale_date', 'DESC');
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }
            
        } catch (Exception $e) {
            error_log('Error in get_sold_plots: ' . $e->getMessage());
            return array();
        }
    }

    public function get_unsold_plots() {
        try {
            if (!$this->db->table_exists('plots')) {
                $this->create_plots_table();
            }
            
            $this->db->select('plots.*, gardens.garden_name, gardens.district, gardens.taluk_name, gardens.village_town_name');
            $this->db->from('plots');
            $this->db->join('gardens', 'gardens.id = plots.garden_id');
            $this->db->where('plots.status', 'unsold');
            $this->db->order_by('plots.created_at', 'DESC');
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }
            
        } catch (Exception $e) {
            error_log('Error in get_unsold_plots: ' . $e->getMessage());
            return array();
        }
    }

    public function get_booked_plots() {
        try {
            if (!$this->db->table_exists('plots')) {
                $this->create_plots_table();
            }
            
            $this->db->select('plots.*, gardens.garden_name, gardens.district, gardens.taluk_name, gardens.village_town_name');
            $this->db->from('plots');
            $this->db->join('gardens', 'gardens.id = plots.garden_id');
            $this->db->where('plots.status', 'booked');
            $this->db->order_by('plots.booking_date', 'DESC');
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }
            
        } catch (Exception $e) {
            error_log('Error in get_booked_plots: ' . $e->getMessage());
            return array();
        }
    }

    public function get_plot_by_id($id) {
        $this->db->select('plots.*, gardens.garden_name, gardens.district, gardens.taluk_name, gardens.village_town_name');
        $this->db->from('plots');
        $this->db->join('gardens', 'gardens.id = plots.garden_id');
        $this->db->where('plots.id', $id);
        return $this->db->get()->row();
    }

    public function mark_plot_as_sold($plot_id, $sale_data) {
        try {
            $this->db->where('id', $plot_id);
            $update_data = array(
                'status' => 'sold',
                'customer_name' => $sale_data['customer_name'],
                'customer_phone' => $sale_data['customer_phone'],
                'sale_date' => $sale_data['sale_date'],
                'sale_amount' => $sale_data['sale_amount']
            );
            
            return $this->db->update('plots', $update_data);
            
        } catch (Exception $e) {
            error_log('Error marking plot as sold: ' . $e->getMessage());
            return false;
        }
    }

    public function convert_booking_to_sale($plot_id, $sale_data) {
        try {
            $this->db->where('id', $plot_id);
            $update_data = array(
                'status' => 'sold',
                'sale_date' => $sale_data['sale_date'],
                'sale_amount' => $sale_data['final_sale_amount'],
                'payment_method' => $sale_data['payment_method']
            );
            
            return $this->db->update('plots', $update_data);
            
        } catch (Exception $e) {
            error_log('Error converting booking to sale: ' . $e->getMessage());
            return false;
        }
    }

    public function cancel_booking($plot_id, $cancellation_data) {
        try {
            $this->db->where('id', $plot_id);
            $update_data = array(
                'status' => 'unsold',
                'customer_name' => null,
                'customer_phone' => null,
                'booking_date' => null,
                'booking_amount' => null,
                'booking_reference' => null
            );
            
            return $this->db->update('plots', $update_data);
            
        } catch (Exception $e) {
            error_log('Error cancelling booking: ' . $e->getMessage());
            return false;
        }
    }

    public function update_garden($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('gardens', $data);
    }

    public function delete_garden($id) {
        $this->db->where('id', $id);
        return $this->db->delete('gardens');
    }
}
