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
                plot_rate_per_sqft DECIMAL(10,2),
                status ENUM('unsold', 'booked', 'sold', 'registered') DEFAULT 'unsold',
                
                -- Customer Information
                customer_name VARCHAR(255),
                customer_phone VARCHAR(20),
                customer_phone2 VARCHAR(20),
                father_name VARCHAR(255),
                customer_address TEXT,
                customer_pincode VARCHAR(10),
                customer_district VARCHAR(100),
                customer_taluk VARCHAR(100),
                customer_village_town VARCHAR(100),
                id_proof_type VARCHAR(50),
                id_proof_number VARCHAR(100),
                
                -- Registration Details
                registration_document_no VARCHAR(100),
                registration_date DATE,
                patta_chitta_no VARCHAR(100),
                ts_no VARCHAR(100),
                ward_block VARCHAR(100),
                referred_by VARCHAR(255),
                
                -- Booking Information
                booking_date DATE,
                booking_amount DECIMAL(15,2),
                booking_reference VARCHAR(100),
                payment_method VARCHAR(50),
                
                -- Sale Information
                sale_date DATE,
                sale_amount DECIMAL(15,2),
                final_payment_method VARCHAR(50),
                sale_reference VARCHAR(100),
                
                -- Document Paths
                title_deed_path VARCHAR(255),
                plot_sketch_path VARCHAR(255),
                
                -- Additional Notes
                notes TEXT,
                
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

    public function get_unregistered_plots() {
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
            error_log('Error in get_unregistered_plots: ' . $e->getMessage());
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

    // New methods for plot management
    public function submit_registered_plot($plot_data) {
        try {
            if (!$this->db->table_exists('plots')) {
                $this->create_plots_table();
            }
            
            $plot_data['status'] = 'registered';
            $result = $this->db->insert('plots', $plot_data);
            
            if ($result) {
                return $this->db->insert_id();
            }
            return false;
            
        } catch (Exception $e) {
            error_log('Error submitting registered plot: ' . $e->getMessage());
            return false;
        }
    }

    public function submit_plot_booking($plot_id, $booking_data) {
        try {
            $this->db->where('id', $plot_id);
            $update_data = array(
                'status' => 'booked',
                'customer_name' => $booking_data['customer_name'],
                'customer_phone' => $booking_data['customer_phone'],
                'customer_phone2' => $booking_data['customer_phone2'] ?? null,
                'father_name' => $booking_data['father_name'] ?? null,
                'customer_address' => $booking_data['customer_address'] ?? null,
                'customer_pincode' => $booking_data['customer_pincode'] ?? null,
                'customer_district' => $booking_data['customer_district'] ?? null,
                'customer_taluk' => $booking_data['customer_taluk'] ?? null,
                'customer_village_town' => $booking_data['customer_village_town'] ?? null,
                'id_proof_type' => $booking_data['id_proof_type'] ?? null,
                'id_proof_number' => $booking_data['id_proof_number'] ?? null,
                'booking_date' => $booking_data['booking_date'],
                'booking_amount' => $booking_data['booking_amount'],
                'payment_method' => $booking_data['payment_method'] ?? null,
                'notes' => $booking_data['notes'] ?? null
            );
            
            return $this->db->update('plots', $update_data);
            
        } catch (Exception $e) {
            error_log('Error submitting plot booking: ' . $e->getMessage());
            return false;
        }
    }

    public function submit_plot_sale($plot_id, $sale_data) {
        try {
            $this->db->where('id', $plot_id);
            $update_data = array(
                'status' => 'sold',
                'customer_name' => $sale_data['customer_name'],
                'customer_phone' => $sale_data['customer_phone'],
                'customer_phone2' => $sale_data['customer_phone2'] ?? null,
                'father_name' => $sale_data['father_name'] ?? null,
                'customer_address' => $sale_data['customer_address'] ?? null,
                'customer_pincode' => $sale_data['customer_pincode'] ?? null,
                'customer_district' => $sale_data['customer_district'] ?? null,
                'customer_taluk' => $sale_data['customer_taluk'] ?? null,
                'customer_village_town' => $sale_data['customer_village_town'] ?? null,
                'id_proof_type' => $sale_data['id_proof_type'] ?? null,
                'id_proof_number' => $sale_data['id_proof_number'] ?? null,
                'sale_date' => $sale_data['sale_date'],
                'sale_amount' => $sale_data['sale_amount'],
                'final_payment_method' => $sale_data['payment_method'] ?? null,
                'notes' => $sale_data['notes'] ?? null
            );
            
            return $this->db->update('plots', $update_data);
            
        } catch (Exception $e) {
            error_log('Error submitting plot sale: ' . $e->getMessage());
            return false;
        }
    }

    public function get_plots_overview() {
        try {
            if (!$this->db->table_exists('plots')) {
                $this->create_plots_table();
            }
            
            $this->db->select('plots.*, gardens.garden_name, gardens.district, gardens.taluk_name, gardens.village_town_name');
            $this->db->from('plots');
            $this->db->join('gardens', 'gardens.id = plots.garden_id');
            $this->db->order_by('plots.created_at', 'DESC');
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }
            
        } catch (Exception $e) {
            error_log('Error in get_plots_overview: ' . $e->getMessage());
            return array();
        }
    }

    public function get_plots_by_status($status) {
        try {
            if (!$this->db->table_exists('plots')) {
                $this->create_plots_table();
            }
            
            $this->db->select('plots.*, gardens.garden_name, gardens.district, gardens.taluk_name, gardens.village_town_name');
            $this->db->from('plots');
            $this->db->join('gardens', 'gardens.id = plots.garden_id');
            $this->db->where('plots.status', $status);
            $this->db->order_by('plots.created_at', 'DESC');
            
            $result = $this->db->get();
            
            if ($result->num_rows() > 0) {
                return $result->result();
            } else {
                return array();
            }
            
        } catch (Exception $e) {
            error_log('Error in get_plots_by_status: ' . $e->getMessage());
            return array();
        }
    }

    public function get_plot_statistics() {
        try {
            if (!$this->db->table_exists('plots')) {
                $this->create_plots_table();
            }
            
            $stats = array();
            
            // Total plots
            $total = $this->db->count_all('plots');
            $stats['total_plots'] = $total;
            
            // Plots by status
            $this->db->select('status, COUNT(*) as count');
            $this->db->from('plots');
            $this->db->group_by('status');
            $status_counts = $this->db->get()->result();
            
            foreach ($status_counts as $status) {
                $stats[$status->status . '_plots'] = $status->count;
            }
            
            // Total value
            $this->db->select('SUM(plot_value) as total_value');
            $this->db->from('plots');
            $total_value = $this->db->get()->row();
            $stats['total_value'] = $total_value ? $total_value->total_value : 0;
            
            // Sold value
            $this->db->select('SUM(sale_amount) as sold_value');
            $this->db->from('plots');
            $this->db->where('status', 'sold');
            $sold_value = $this->db->get()->row();
            $stats['sold_value'] = $sold_value ? $sold_value->sold_value : 0;
            
            return $stats;
            
        } catch (Exception $e) {
            error_log('Error in get_plot_statistics: ' . $e->getMessage());
            return array();
        }
    }
}
