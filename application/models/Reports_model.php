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
                case 'transactions':
                    return $this->get_transaction_report($start_date, $end_date, $garden_id);
                default:
                    return array();
            }
        } catch (Exception $e) {
            error_log('Error getting export data: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * Get transaction report data
     * @param string $start_date Start date
     * @param string $end_date End date
     * @param int $garden_id Garden ID filter
     * @return array Transaction report data
     */
    public function get_transaction_report($start_date = null, $end_date = null, $garden_id = null) {
        try {
            $this->db->select('
                t.*,
                r.registration_number,
                r.property_id,
                c.plot_buyer_name,
                p.garden_name
            ');
            $this->db->from('transactions t');
            $this->db->join('registrations r', 'r.id = t.registration_id', 'left');
            $this->db->join('customers c', 'c.id = r.customer_id', 'left');
            $this->db->join('properties p', 'p.id = r.property_id', 'left');
            
            if ($start_date) {
                $this->db->where('t.payment_date >=', $start_date);
            }
            if ($end_date) {
                $this->db->where('t.payment_date <=', $end_date);
            }
            if ($garden_id) {
                $this->db->where('r.property_id', $garden_id);
            }
            
            $this->db->order_by('t.payment_date', 'DESC');
            $this->db->order_by('t.id', 'DESC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : array();
            
        } catch (Exception $e) {
            error_log('Error getting transaction report: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * Get advanced analytics data
     * @param array $filters Filter parameters
     * @return array Analytics data
     */
    public function get_advanced_analytics($filters = array()) {
        try {
            $analytics = array();
            
            // Revenue trends
            $analytics['revenue_trends'] = $this->get_revenue_trends($filters);
            
            // Customer acquisition trends
            $analytics['customer_trends'] = $this->get_customer_acquisition_trends($filters);
            
            // Property performance metrics
            $analytics['property_metrics'] = $this->get_property_performance_metrics($filters);
            
            // Staff performance metrics
            $analytics['staff_metrics'] = $this->get_staff_performance_metrics($filters);
            
            return $analytics;
            
        } catch (Exception $e) {
            error_log('Error getting advanced analytics: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * Get revenue trends over time
     * @param array $filters Filter parameters
     * @return array Revenue trends data
     */
    private function get_revenue_trends($filters = array()) {
        try {
            $start_date = isset($filters['start_date']) ? $filters['start_date'] : date('Y-m-01', strtotime('-12 months'));
            $end_date = isset($filters['end_date']) ? $filters['end_date'] : date('Y-m-t');
            
            $this->db->select('
                DATE_FORMAT(payment_date, "%Y-%m") as month,
                SUM(amount) as monthly_revenue,
                COUNT(*) as transaction_count,
                AVG(amount) as average_transaction
            ');
            $this->db->from('transactions');
            $this->db->where('payment_date >=', $start_date);
            $this->db->where('payment_date <=', $end_date);
            $this->db->group_by('DATE_FORMAT(payment_date, "%Y-%m")');
            $this->db->order_by('month', 'ASC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : array();
            
        } catch (Exception $e) {
            error_log('Error getting revenue trends: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * Get customer acquisition trends
     * @param array $filters Filter parameters
     * @return array Customer trends data
     */
    private function get_customer_acquisition_trends($filters = array()) {
        try {
            $start_date = isset($filters['start_date']) ? $filters['start_date'] : date('Y-m-01', strtotime('-12 months'));
            $end_date = isset($filters['end_date']) ? $filters['end_date'] : date('Y-m-t');
            
            $this->db->select('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as new_customers
            ');
            $this->db->from('customers');
            $this->db->where('created_at >=', $start_date);
            $this->db->where('created_at <=', $end_date);
            $this->db->group_by('DATE_FORMAT(created_at, "%Y-%m")');
            $this->db->order_by('month', 'ASC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : array();
            
        } catch (Exception $e) {
            error_log('Error getting customer acquisition trends: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * Get property performance metrics
     * @param array $filters Filter parameters
     * @return array Property metrics data
     */
    private function get_property_performance_metrics($filters = array()) {
        try {
            $this->db->select('
                p.id,
                p.garden_name,
                p.district,
                COUNT(r.id) as total_registrations,
                SUM(CASE WHEN p.status = "sold" THEN 1 ELSE 0 END) as sold_count,
                SUM(CASE WHEN p.status = "booked" THEN 1 ELSE 0 END) as booked_count,
                SUM(t.amount) as total_revenue,
                AVG(t.amount) as average_transaction
            ');
            $this->db->from('properties p');
            $this->db->join('registrations r', 'r.property_id = p.id', 'left');
            $this->db->join('transactions t', 't.registration_id = r.id', 'left');
            
            if (isset($filters['garden_id']) && $filters['garden_id']) {
                $this->db->where('p.id', $filters['garden_id']);
            }
            if (isset($filters['start_date']) && $filters['start_date']) {
                $this->db->where('t.payment_date >=', $filters['start_date']);
            }
            if (isset($filters['end_date']) && $filters['end_date']) {
                $this->db->where('t.payment_date <=', $filters['end_date']);
            }
            
            $this->db->group_by('p.id');
            $this->db->order_by('total_revenue', 'DESC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : array();
            
        } catch (Exception $e) {
            error_log('Error getting property performance metrics: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * Get staff performance metrics
     * @param array $filters Filter parameters
     * @return array Staff metrics data
     */
    private function get_staff_performance_metrics($filters = array()) {
        try {
            $this->db->select('
                s.id,
                s.employee_name,
                s.designation,
                COUNT(pa.id) as total_assignments,
                COUNT(DISTINCT r.id) as total_registrations,
                SUM(t.amount) as total_sales,
                AVG(t.amount) as average_sale
            ');
            $this->db->from('staff s');
            $this->db->join('property_assignments pa', 'pa.staff_id = s.id', 'left');
            $this->db->join('registrations r', 'r.property_id = pa.property_id', 'left');
            $this->db->join('transactions t', 't.registration_id = r.id', 'left');
            
            if (isset($filters['staff_id']) && $filters['staff_id']) {
                $this->db->where('s.id', $filters['staff_id']);
            }
            if (isset($filters['start_date']) && $filters['start_date']) {
                $this->db->where('t.payment_date >=', $filters['start_date']);
            }
            if (isset($filters['end_date']) && $filters['end_date']) {
                $this->db->where('t.payment_date <=', $filters['end_date']);
            }
            
            $this->db->group_by('s.id');
            $this->db->order_by('total_sales', 'DESC');
            
            $result = $this->db->get();
            return $result->num_rows() > 0 ? $result->result_array() : array();
            
        } catch (Exception $e) {
            error_log('Error getting staff performance metrics: ' . $e->getMessage());
            return array();
        }
    }
}
