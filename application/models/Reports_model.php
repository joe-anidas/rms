<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_dashboard_summary() {
        try {
            $summary = array();
            
            // Property Statistics
            $summary['total_properties'] = $this->db->count_all('plots');
            $summary['sold_properties'] = $this->db->where('status', 'sold')->count_all_results('plots');
            $summary['booked_properties'] = $this->db->where('status', 'booked')->count_all_results('plots');
            $summary['unsold_properties'] = $this->db->where('status', 'unsold')->count_all_results('plots');
            
            // Customer Statistics
            $summary['total_customers'] = $this->db->count_all('customers');
            $summary['active_customers'] = $this->db->where('status', 'active')->count_all_results('customers');
            
            // Staff Statistics
            $summary['total_staff'] = $this->db->count_all('staff');
            $summary['active_staff'] = $this->db->where('status', 'active')->count_all_results('staff');
            
            // Financial Statistics
            $this->db->select('SUM(plot_value) as total_property_value');
            $this->db->from('plots');
            $total_value = $this->db->get()->row();
            $summary['total_property_value'] = $total_value ? $total_value->total_property_value : 0;
            
            $this->db->select('SUM(sale_amount) as total_sales');
            $this->db->from('plots');
            $this->db->where('status', 'sold');
            $total_sales = $this->db->get()->row();
            $summary['total_sales'] = $total_sales ? $total_sales->total_sales : 0;
            
            // Calculate pending revenue
            $summary['pending_revenue'] = $summary['total_property_value'] - $summary['total_sales'];
            
            return $summary;
            
        } catch (Exception $e) {
            error_log('Error getting dashboard summary: ' . $e->getMessage());
            return array();
        }
    }

    public function get_sales_report($start_date = null, $end_date = null, $garden_id = null) {
        try {
            $this->db->select('
                plots.*,
                gardens.garden_name,
                gardens.district,
                customers.plot_buyer_name,
                customers.phone_number_1
            ');
            $this->db->from('plots');
            $this->db->join('gardens', 'gardens.id = plots.garden_id');
            $this->db->join('customers', 'customers.id = plots.customer_id', 'left');
            $this->db->where('plots.status', 'sold');
            
            if ($start_date) {
                $this->db->where('plots.sale_date >=', $start_date);
            }
            if ($end_date) {
                $this->db->where('plots.sale_date <=', $end_date);
            }
            if ($garden_id) {
                $this->db->where('plots.garden_id', $garden_id);
            }
            
            $this->db->order_by('plots.sale_date', 'DESC');
            $result = $this->db->get();
            
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting sales report: ' . $e->getMessage());
            return array();
        }
    }

    public function get_booking_report($start_date = null, $end_date = null, $garden_id = null) {
        try {
            $this->db->select('
                plots.*,
                gardens.garden_name,
                gardens.district,
                customers.plot_buyer_name,
                customers.phone_number_1
            ');
            $this->db->from('plots');
            $this->db->join('gardens', 'gardens.id = plots.garden_id');
            $this->db->join('customers', 'customers.id = plots.customer_id', 'left');
            $this->db->where('plots.status', 'booked');
            
            if ($start_date) {
                $this->db->where('plots.booking_date >=', $start_date);
            }
            if ($end_date) {
                $this->db->where('plots.booking_date <=', $end_date);
            }
            if ($garden_id) {
                $this->db->where('plots.garden_id', $garden_id);
            }
            
            $this->db->order_by('plots.booking_date', 'DESC');
            $result = $this->db->get();
            
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting booking report: ' . $e->getMessage());
            return array();
        }
    }

    public function get_customer_analytics() {
        try {
            $analytics = array();
            
            // Customer acquisition by month
            $this->db->select('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as new_customers
            ');
            $this->db->from('customers');
            $this->db->group_by('DATE_FORMAT(created_at, "%Y-%m")');
            $this->db->order_by('month', 'DESC');
            $this->db->limit(12);
            
            $result = $this->db->get();
            $analytics['monthly_acquisition'] = $result->num_rows() > 0 ? $result->result() : array();
            
            // Top customers by property value
            $this->db->select('
                customers.plot_buyer_name,
                customers.phone_number_1,
                COUNT(plots.id) as properties_owned,
                SUM(plots.plot_value) as total_investment
            ');
            $this->db->from('customers');
            $this->db->join('plots', 'plots.customer_id = customers.id');
            $this->db->where('plots.status IN ("sold", "booked")');
            $this->db->group_by('customers.id');
            $this->db->order_by('total_investment', 'DESC');
            $this->db->limit(10);
            
            $result = $this->db->get();
            $analytics['top_customers'] = $result->num_rows() > 0 ? $result->result() : array();
            
            // Customer distribution by district
            $this->db->select('
                customers.district,
                COUNT(*) as customer_count
            ');
            $this->db->from('customers');
            $this->db->group_by('customers.district');
            $this->db->order_by('customer_count', 'DESC');
            
            $result = $this->db->get();
            $analytics['district_distribution'] = $result->num_rows() > 0 ? $result->result() : array();
            
            return $analytics;
            
        } catch (Exception $e) {
            error_log('Error getting customer analytics: ' . $e->getMessage());
            return array();
        }
    }

    public function get_property_performance($garden_id = null) {
        try {
            $this->db->select('
                gardens.garden_name,
                gardens.district,
                COUNT(plots.id) as total_plots,
                SUM(CASE WHEN plots.status = "sold" THEN 1 ELSE 0 END) as sold_plots,
                SUM(CASE WHEN plots.status = "booked" THEN 1 ELSE 0 END) as booked_plots,
                SUM(CASE WHEN plots.status = "unsold" THEN 1 ELSE 0 END) as unsold_plots,
                SUM(plots.plot_value) as total_value,
                SUM(CASE WHEN plots.status = "sold" THEN plots.sale_amount ELSE 0 END) as total_sales,
                ROUND((SUM(CASE WHEN plots.status = "sold" THEN 1 ELSE 0 END) / COUNT(plots.id)) * 100, 2) as conversion_rate
            ');
            $this->db->from('gardens');
            $this->db->join('plots', 'plots.garden_id = gardens.id', 'left');
            
            if ($garden_id) {
                $this->db->where('gardens.id', $garden_id);
            }
            
            $this->db->group_by('gardens.id');
            $this->db->order_by('conversion_rate', 'DESC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting property performance: ' . $e->getMessage());
            return array();
        }
    }

    public function get_staff_performance($staff_id = null) {
        try {
            $this->db->select('
                staff.employee_name,
                staff.designation,
                staff.department,
                COUNT(plots.id) as assigned_properties,
                SUM(CASE WHEN plots.status = "sold" THEN 1 ELSE 0 END) as sold_properties,
                SUM(CASE WHEN plots.status = "booked" THEN 1 ELSE 0 END) as booked_properties,
                SUM(CASE WHEN plots.status = "sold" THEN plots.sale_amount ELSE 0 END) as total_sales,
                ROUND((SUM(CASE WHEN plots.status = "sold" THEN 1 ELSE 0 END) / COUNT(plots.id)) * 100, 2) as success_rate
            ');
            $this->db->from('staff');
            $this->db->join('plots', 'plots.assigned_staff_id = staff.id', 'left');
            
            if ($staff_id) {
                $this->db->where('staff.id', $staff_id);
            }
            
            $this->db->group_by('staff.id');
            $this->db->order_by('total_sales', 'DESC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result() : array();
            
        } catch (Exception $e) {
            error_log('Error getting staff performance: ' . $e->getMessage());
            return array();
        }
    }

    public function get_financial_summary($start_date = null, $end_date = null) {
        try {
            $summary = array();
            
            // Revenue by transaction type
            $this->db->select('
                transaction_type,
                SUM(amount) as total_amount,
                COUNT(*) as transaction_count
            ');
            $this->db->from('transactions');
            $this->db->where('status', 'completed');
            
            if ($start_date) {
                $this->db->where('payment_date >=', $start_date);
            }
            if ($end_date) {
                $this->db->where('payment_date <=', $end_date);
            }
            
            $this->db->group_by('transaction_type');
            $result = $this->db->get();
            $summary['revenue_by_type'] = $result->num_rows() > 0 ? $result->result() : array();
            
            // Monthly revenue trend
            $this->db->select('
                DATE_FORMAT(payment_date, "%Y-%m") as month,
                SUM(amount) as monthly_revenue,
                COUNT(*) as transaction_count
            ');
            $this->db->from('transactions');
            $this->db->where('status', 'completed');
            
            if ($start_date) {
                $this->db->where('payment_date >=', $start_date);
            }
            if ($end_date) {
                $this->db->where('payment_date <=', $end_date);
            }
            
            $this->db->group_by('DATE_FORMAT(payment_date, "%Y-%m")');
            $this->db->order_by('month', 'DESC');
            $this->db->limit(12);
            
            $result = $this->db->get();
            $summary['monthly_revenue'] = $result->num_rows() > 0 ? $result->result() : array();
            
            // Payment method distribution
            $this->db->select('
                payment_method,
                SUM(amount) as total_amount,
                COUNT(*) as transaction_count
            ');
            $this->db->from('transactions');
            $this->db->where('status', 'completed');
            
            if ($start_date) {
                $this->db->where('payment_date >=', $start_date);
            }
            if ($end_date) {
                $this->db->where('payment_date <=', $end_date);
            }
            
            $this->db->group_by('payment_method');
            $result = $this->db->get();
            $summary['payment_methods'] = $result->num_rows() > 0 ? $result->result() : array();
            
            return $summary;
            
        } catch (Exception $e) {
            error_log('Error getting financial summary: ' . $e->getMessage());
            return array();
        }
    }

    public function get_export_data($report_type, $start_date = null, $end_date = null, $garden_id = null) {
        try {
            switch ($report_type) {
                case 'sales':
                    return $this->get_sales_report($start_date, $end_date, $garden_id);
                case 'bookings':
                    return $this->get_booking_report($start_date, $end_date, $garden_id);
                case 'customers':
                    return $this->get_customer_analytics();
                case 'properties':
                    return $this->get_property_performance($garden_id);
                case 'staff':
                    return $this->get_staff_performance();
                case 'financial':
                    return $this->get_financial_summary($start_date, $end_date);
                default:
                    return array();
            }
        } catch (Exception $e) {
            error_log('Error getting export data: ' . $e->getMessage());
            return array();
        }
    }
}
