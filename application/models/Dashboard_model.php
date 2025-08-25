<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get comprehensive dashboard metrics
     * @return array
     */
    public function get_dashboard_metrics() {
        $metrics = array();
        
        // Property metrics
        $metrics['properties'] = $this->get_property_summary();
        
        // Customer metrics
        $metrics['customers'] = $this->get_customer_summary();
        
        // Staff metrics
        $metrics['staff'] = $this->get_staff_summary();
        
        // Transaction metrics
        $metrics['transactions'] = $this->get_transaction_summary();
        
        // Revenue metrics
        $metrics['revenue'] = $this->get_revenue_summary();
        
        return $metrics;
    }

    /**
     * Get property summary statistics
     * @return array
     */
    private function get_property_summary() {
        $summary = array();
        
        // Total properties by status
        $query = "SELECT status, COUNT(*) as count FROM properties GROUP BY status";
        $result = $this->db->query($query)->result_array();
        
        $summary['by_status'] = array();
        $summary['total'] = 0;
        
        foreach ($result as $row) {
            $summary['by_status'][$row['status']] = (int)$row['count'];
            $summary['total'] += (int)$row['count'];
        }
        
        // Ensure all statuses are represented
        $statuses = array('unsold', 'booked', 'sold');
        foreach ($statuses as $status) {
            if (!isset($summary['by_status'][$status])) {
                $summary['by_status'][$status] = 0;
            }
        }
        
        // Property value statistics
        $value_query = "SELECT 
            SUM(CASE WHEN status = 'sold' THEN price ELSE 0 END) as sold_value,
            SUM(CASE WHEN status = 'booked' THEN price ELSE 0 END) as booked_value,
            SUM(price) as total_value,
            AVG(price) as average_price
            FROM properties";
        $value_result = $this->db->query($value_query)->row_array();
        
        $summary['values'] = array(
            'sold_value' => (float)$value_result['sold_value'],
            'booked_value' => (float)$value_result['booked_value'],
            'total_value' => (float)$value_result['total_value'],
            'average_price' => (float)$value_result['average_price']
        );
        
        return $summary;
    }

    /**
     * Get customer summary statistics
     * @return array
     */
    private function get_customer_summary() {
        $summary = array();
        
        // Total customers
        $summary['total'] = $this->db->count_all('customers');
        
        // Customers with active registrations
        $active_query = "SELECT COUNT(DISTINCT customer_id) as count 
                        FROM registrations 
                        WHERE status = 'active'";
        $summary['active'] = (int)$this->db->query($active_query)->row()->count;
        
        // New customers this month
        $month_query = "SELECT COUNT(*) as count 
                       FROM customers 
                       WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
                       AND YEAR(created_at) = YEAR(CURRENT_DATE())";
        $summary['new_this_month'] = (int)$this->db->query($month_query)->row()->count;
        
        return $summary;
    }

    /**
     * Get staff summary statistics
     * @return array
     */
    private function get_staff_summary() {
        $summary = array();
        
        // Total staff
        $summary['total'] = $this->db->count_all('staff');
        
        // Staff with property assignments
        $assigned_query = "SELECT COUNT(DISTINCT staff_id) as count 
                          FROM property_assignments 
                          WHERE is_active = 1";
        $summary['assigned'] = (int)$this->db->query($assigned_query)->row()->count;
        
        // Staff workload distribution
        $workload_query = "SELECT s.employee_name, COUNT(pa.id) as assignment_count
                          FROM staff s
                          LEFT JOIN property_assignments pa ON s.id = pa.staff_id AND pa.is_active = 1
                          GROUP BY s.id, s.employee_name
                          ORDER BY assignment_count DESC";
        $summary['workload'] = $this->db->query($workload_query)->result_array();
        
        return $summary;
    }

    /**
     * Get transaction summary statistics
     * @return array
     */
    private function get_transaction_summary() {
        $summary = array();
        
        // Total transactions
        $summary['total'] = $this->db->count_all('transactions');
        
        // Transactions by payment type
        $type_query = "SELECT payment_type, COUNT(*) as count, SUM(amount) as total_amount
                      FROM transactions 
                      GROUP BY payment_type";
        $type_result = $this->db->query($type_query)->result_array();
        
        $summary['by_type'] = array();
        foreach ($type_result as $row) {
            $summary['by_type'][$row['payment_type']] = array(
                'count' => (int)$row['count'],
                'amount' => (float)$row['total_amount']
            );
        }
        
        // Recent transactions (last 30 days)
        $recent_query = "SELECT COUNT(*) as count, SUM(amount) as total_amount
                        FROM transactions 
                        WHERE payment_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)";
        $recent_result = $this->db->query($recent_query)->row_array();
        
        $summary['recent'] = array(
            'count' => (int)$recent_result['count'],
            'amount' => (float)$recent_result['total_amount']
        );
        
        return $summary;
    }

    /**
     * Get revenue summary statistics
     * @return array
     */
    private function get_revenue_summary() {
        $summary = array();
        
        // Total revenue collected
        $total_query = "SELECT SUM(amount) as total FROM transactions";
        $summary['total_collected'] = (float)$this->db->query($total_query)->row()->total;
        
        // Revenue by month (last 12 months)
        $monthly_query = "SELECT 
            DATE_FORMAT(payment_date, '%Y-%m') as month,
            SUM(amount) as revenue,
            COUNT(*) as transaction_count
            FROM transactions 
            WHERE payment_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(payment_date, '%Y-%m')
            ORDER BY month";
        $summary['monthly'] = $this->db->query($monthly_query)->result_array();
        
        // Pending payments (total property value - collected amount)
        $pending_query = "SELECT 
            SUM(r.total_amount - COALESCE(t.paid_amount, 0)) as pending
            FROM registrations r
            LEFT JOIN (
                SELECT registration_id, SUM(amount) as paid_amount
                FROM transactions
                GROUP BY registration_id
            ) t ON r.id = t.registration_id
            WHERE r.status = 'active'";
        $summary['pending'] = (float)$this->db->query($pending_query)->row()->pending;
        
        return $summary;
    }

    /**
     * Get property analytics with status distribution and trends
     * @param array $date_range
     * @return array
     */
    public function get_property_analytics($date_range = array()) {
        $analytics = array();
        
        // Status distribution
        $analytics['status_distribution'] = $this->get_property_status_distribution();
        
        // Property trends over time
        $analytics['trends'] = $this->get_property_trends($date_range);
        
        // Property type distribution
        $analytics['type_distribution'] = $this->get_property_type_distribution();
        
        // Average days to sell
        $analytics['sales_metrics'] = $this->get_property_sales_metrics();
        
        return $analytics;
    }

    /**
     * Get property status distribution
     * @return array
     */
    private function get_property_status_distribution() {
        $query = "SELECT status, COUNT(*) as count, 
                 ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM properties)), 2) as percentage
                 FROM properties 
                 GROUP BY status";
        return $this->db->query($query)->result_array();
    }

    /**
     * Get property trends over time
     * @param array $date_range
     * @return array
     */
    private function get_property_trends($date_range = array()) {
        $where_clause = "";
        if (!empty($date_range['start']) && !empty($date_range['end'])) {
            $where_clause = "WHERE created_at BETWEEN '{$date_range['start']}' AND '{$date_range['end']}'";
        }
        
        $query = "SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(*) as properties_added,
            status
            FROM properties 
            {$where_clause}
            GROUP BY DATE_FORMAT(created_at, '%Y-%m'), status
            ORDER BY month";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get property type distribution
     * @return array
     */
    private function get_property_type_distribution() {
        $query = "SELECT property_type, COUNT(*) as count,
                 AVG(price) as average_price,
                 SUM(CASE WHEN status = 'sold' THEN 1 ELSE 0 END) as sold_count
                 FROM properties 
                 GROUP BY property_type";
        return $this->db->query($query)->result_array();
    }

    /**
     * Get property sales metrics
     * @return array
     */
    private function get_property_sales_metrics() {
        $query = "SELECT 
            AVG(DATEDIFF(r.registration_date, p.created_at)) as avg_days_to_sell,
            COUNT(*) as total_sold,
            MIN(DATEDIFF(r.registration_date, p.created_at)) as fastest_sale,
            MAX(DATEDIFF(r.registration_date, p.created_at)) as slowest_sale
            FROM properties p
            INNER JOIN registrations r ON p.id = r.property_id
            WHERE p.status = 'sold'";
        
        return $this->db->query($query)->row_array();
    }

    /**
     * Get financial analytics with revenue tracking and payment analysis
     * @param array $date_range
     * @return array
     */
    public function get_financial_analytics($date_range = array()) {
        $analytics = array();
        
        // Revenue trends
        $analytics['revenue_trends'] = $this->get_revenue_trends($date_range);
        
        // Payment method analysis
        $analytics['payment_methods'] = $this->get_payment_method_analysis();
        
        // Payment type analysis
        $analytics['payment_types'] = $this->get_payment_type_analysis();
        
        // Outstanding payments
        $analytics['outstanding'] = $this->get_outstanding_payments();
        
        // Revenue forecasting
        $analytics['forecast'] = $this->get_revenue_forecast();
        
        return $analytics;
    }

    /**
     * Get revenue trends over time
     * @param array $date_range
     * @return array
     */
    private function get_revenue_trends($date_range = array()) {
        $where_clause = "";
        if (!empty($date_range['start']) && !empty($date_range['end'])) {
            $where_clause = "WHERE payment_date BETWEEN '{$date_range['start']}' AND '{$date_range['end']}'";
        }
        
        $query = "SELECT 
            DATE_FORMAT(payment_date, '%Y-%m-%d') as date,
            SUM(amount) as daily_revenue,
            COUNT(*) as transaction_count,
            AVG(amount) as average_transaction
            FROM transactions 
            {$where_clause}
            GROUP BY DATE_FORMAT(payment_date, '%Y-%m-%d')
            ORDER BY date";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get payment method analysis
     * @return array
     */
    private function get_payment_method_analysis() {
        $query = "SELECT 
            payment_method,
            COUNT(*) as transaction_count,
            SUM(amount) as total_amount,
            AVG(amount) as average_amount,
            ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM transactions)), 2) as percentage
            FROM transactions 
            GROUP BY payment_method
            ORDER BY total_amount DESC";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get payment type analysis
     * @return array
     */
    private function get_payment_type_analysis() {
        $query = "SELECT 
            payment_type,
            COUNT(*) as transaction_count,
            SUM(amount) as total_amount,
            AVG(amount) as average_amount
            FROM transactions 
            GROUP BY payment_type
            ORDER BY total_amount DESC";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get outstanding payments analysis
     * @return array
     */
    private function get_outstanding_payments() {
        $query = "SELECT 
            r.registration_number,
            c.plot_buyer_name as customer_name,
            p.garden_name as property_name,
            r.total_amount,
            COALESCE(SUM(t.amount), 0) as paid_amount,
            (r.total_amount - COALESCE(SUM(t.amount), 0)) as outstanding_amount,
            r.registration_date,
            DATEDIFF(CURRENT_DATE(), r.registration_date) as days_outstanding
            FROM registrations r
            INNER JOIN customers c ON r.customer_id = c.id
            INNER JOIN properties p ON r.property_id = p.id
            LEFT JOIN transactions t ON r.id = t.registration_id
            WHERE r.status = 'active'
            GROUP BY r.id
            HAVING outstanding_amount > 0
            ORDER BY outstanding_amount DESC";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get revenue forecast based on pending registrations
     * @return array
     */
    private function get_revenue_forecast() {
        $query = "SELECT 
            SUM(r.total_amount - COALESCE(paid.amount, 0)) as potential_revenue,
            COUNT(*) as pending_registrations,
            AVG(r.total_amount - COALESCE(paid.amount, 0)) as average_outstanding
            FROM registrations r
            LEFT JOIN (
                SELECT registration_id, SUM(amount) as amount
                FROM transactions
                GROUP BY registration_id
            ) paid ON r.id = paid.registration_id
            WHERE r.status = 'active'
            AND (r.total_amount - COALESCE(paid.amount, 0)) > 0";
        
        return $this->db->query($query)->row_array();
    }

    /**
     * Get customer analytics
     * @param array $date_range
     * @return array
     */
    public function get_customer_analytics($date_range = array()) {
        $analytics = array();
        
        // Customer acquisition trends
        $analytics['acquisition_trends'] = $this->get_customer_acquisition_trends($date_range);
        
        // Top customers by value
        $analytics['top_customers'] = $this->get_top_customers();
        
        // Customer geographic distribution
        $analytics['geographic_distribution'] = $this->get_customer_geographic_distribution();
        
        // Customer lifecycle analysis
        $analytics['lifecycle'] = $this->get_customer_lifecycle_analysis();
        
        return $analytics;
    }

    /**
     * Get customer acquisition trends
     * @param array $date_range
     * @return array
     */
    private function get_customer_acquisition_trends($date_range = array()) {
        $where_clause = "";
        if (!empty($date_range['start']) && !empty($date_range['end'])) {
            $where_clause = "WHERE created_at BETWEEN '{$date_range['start']}' AND '{$date_range['end']}'";
        }
        
        $query = "SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(*) as new_customers
            FROM customers 
            {$where_clause}
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get top customers by transaction value
     * @return array
     */
    private function get_top_customers() {
        $query = "SELECT 
            c.plot_buyer_name as customer_name,
            c.contact_details,
            COUNT(r.id) as properties_count,
            SUM(COALESCE(t.amount, 0)) as total_paid,
            SUM(r.total_amount) as total_value
            FROM customers c
            LEFT JOIN registrations r ON c.id = r.customer_id
            LEFT JOIN transactions t ON r.id = t.registration_id
            GROUP BY c.id
            ORDER BY total_paid DESC
            LIMIT 10";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get customer geographic distribution
     * @return array
     */
    private function get_customer_geographic_distribution() {
        // This is a simplified version - in a real implementation,
        // you might parse the address_details field more sophisticatedly
        $query = "SELECT 
            SUBSTRING_INDEX(address_details, ',', 1) as area,
            COUNT(*) as customer_count
            FROM customers 
            WHERE address_details IS NOT NULL AND address_details != ''
            GROUP BY area
            ORDER BY customer_count DESC
            LIMIT 10";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get customer lifecycle analysis
     * @return array
     */
    private function get_customer_lifecycle_analysis() {
        $query = "SELECT 
            CASE 
                WHEN r.status = 'completed' THEN 'Completed'
                WHEN r.status = 'active' AND paid_percentage >= 100 THEN 'Fully Paid'
                WHEN r.status = 'active' AND paid_percentage >= 50 THEN 'Partially Paid'
                WHEN r.status = 'active' AND paid_percentage > 0 THEN 'Started Payment'
                WHEN r.status = 'active' AND paid_percentage = 0 THEN 'No Payment'
                ELSE 'Other'
            END as lifecycle_stage,
            COUNT(*) as customer_count
            FROM (
                SELECT 
                    r.*,
                    ROUND((COALESCE(SUM(t.amount), 0) / r.total_amount) * 100, 2) as paid_percentage
                FROM registrations r
                LEFT JOIN transactions t ON r.id = t.registration_id
                GROUP BY r.id
            ) r
            GROUP BY lifecycle_stage";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get staff analytics
     * @param array $date_range
     * @return array
     */
    public function get_staff_analytics($date_range = array()) {
        $analytics = array();
        
        // Staff performance metrics
        $analytics['performance'] = $this->get_staff_performance_metrics();
        
        // Staff workload distribution
        $analytics['workload'] = $this->get_staff_workload_distribution();
        
        // Staff assignment history
        $analytics['assignment_history'] = $this->get_staff_assignment_history($date_range);
        
        return $analytics;
    }

    /**
     * Get staff performance metrics
     * @return array
     */
    private function get_staff_performance_metrics() {
        $query = "SELECT 
            s.employee_name,
            s.designation,
            COUNT(DISTINCT pa.property_id) as properties_assigned,
            COUNT(DISTINCT r.id) as registrations_handled,
            SUM(COALESCE(t.amount, 0)) as revenue_generated
            FROM staff s
            LEFT JOIN property_assignments pa ON s.id = pa.staff_id
            LEFT JOIN registrations r ON pa.property_id = r.property_id
            LEFT JOIN transactions t ON r.id = t.registration_id
            GROUP BY s.id
            ORDER BY revenue_generated DESC";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get staff workload distribution
     * @return array
     */
    private function get_staff_workload_distribution() {
        $query = "SELECT 
            s.employee_name,
            COUNT(CASE WHEN pa.assignment_type = 'sales' THEN 1 END) as sales_assignments,
            COUNT(CASE WHEN pa.assignment_type = 'maintenance' THEN 1 END) as maintenance_assignments,
            COUNT(CASE WHEN pa.assignment_type = 'customer_service' THEN 1 END) as service_assignments,
            COUNT(pa.id) as total_assignments
            FROM staff s
            LEFT JOIN property_assignments pa ON s.id = pa.staff_id AND pa.is_active = 1
            GROUP BY s.id
            ORDER BY total_assignments DESC";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get staff assignment history
     * @param array $date_range
     * @return array
     */
    private function get_staff_assignment_history($date_range = array()) {
        $where_clause = "";
        if (!empty($date_range['start']) && !empty($date_range['end'])) {
            $where_clause = "WHERE pa.assigned_date BETWEEN '{$date_range['start']}' AND '{$date_range['end']}'";
        }
        
        $query = "SELECT 
            DATE_FORMAT(pa.assigned_date, '%Y-%m') as month,
            COUNT(*) as assignments_made,
            COUNT(DISTINCT pa.staff_id) as staff_involved
            FROM property_assignments pa
            {$where_clause}
            GROUP BY DATE_FORMAT(pa.assigned_date, '%Y-%m')
            ORDER BY month";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get transactions for today
     * @return array
     */
    public function get_transactions_today() {
        $query = "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total_amount
                 FROM transactions 
                 WHERE DATE(created_at) = CURRENT_DATE()";
        return $this->db->query($query)->row_array();
    }

    /**
     * Get new customers for today
     * @return int
     */
    public function get_new_customers_today() {
        $query = "SELECT COUNT(*) as count
                 FROM customers 
                 WHERE DATE(created_at) = CURRENT_DATE()";
        return (int)$this->db->query($query)->row()->count;
    }

    /**
     * Get comprehensive dashboard insights
     * @return array
     */
    public function get_dashboard_insights() {
        $insights = array();
        
        // Revenue growth insight
        $revenue_growth_query = "SELECT 
            (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE())) as current_month,
            (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE MONTH(payment_date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(payment_date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)) as previous_month";
        
        $revenue_growth = $this->db->query($revenue_growth_query)->row_array();
        
        if ($revenue_growth['previous_month'] > 0) {
            $growth_percentage = (($revenue_growth['current_month'] - $revenue_growth['previous_month']) / $revenue_growth['previous_month']) * 100;
            $insights['revenue_growth'] = array(
                'percentage' => round($growth_percentage, 1),
                'trend' => $growth_percentage > 0 ? 'up' : 'down',
                'current_month' => $revenue_growth['current_month'],
                'previous_month' => $revenue_growth['previous_month']
            );
        }
        
        // Property sales velocity
        $velocity_query = "SELECT 
            AVG(DATEDIFF(r.registration_date, p.created_at)) as avg_days_to_sell
            FROM properties p
            INNER JOIN registrations r ON p.id = r.property_id
            WHERE p.status = 'sold' 
            AND r.registration_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 3 MONTH)";
        
        $velocity_result = $this->db->query($velocity_query)->row();
        $insights['sales_velocity'] = array(
            'avg_days' => round($velocity_result->avg_days_to_sell ?? 0),
            'performance' => ($velocity_result->avg_days_to_sell ?? 0) < 30 ? 'excellent' : (($velocity_result->avg_days_to_sell ?? 0) < 60 ? 'good' : 'needs_improvement')
        );
        
        // Customer satisfaction proxy (repeat customers)
        $repeat_customers_query = "SELECT 
            COUNT(DISTINCT customer_id) as total_customers,
            COUNT(DISTINCT CASE WHEN property_count > 1 THEN customer_id END) as repeat_customers
            FROM (
                SELECT customer_id, COUNT(*) as property_count
                FROM registrations
                GROUP BY customer_id
            ) customer_properties";
        
        $repeat_result = $this->db->query($repeat_customers_query)->row_array();
        $repeat_rate = $repeat_result['total_customers'] > 0 ? ($repeat_result['repeat_customers'] / $repeat_result['total_customers']) * 100 : 0;
        
        $insights['customer_satisfaction'] = array(
            'repeat_rate' => round($repeat_rate, 1),
            'repeat_customers' => $repeat_result['repeat_customers'],
            'total_customers' => $repeat_result['total_customers']
        );
        
        return $insights;
    }

    /**
     * Get market trends analysis
     * @return array
     */
    public function get_market_trends() {
        $trends = array();
        
        // Property type popularity
        $type_trend_query = "SELECT 
            property_type,
            COUNT(*) as total_properties,
            SUM(CASE WHEN status = 'sold' THEN 1 ELSE 0 END) as sold_properties,
            AVG(price) as avg_price,
            (SUM(CASE WHEN status = 'sold' THEN 1 ELSE 0 END) / COUNT(*)) * 100 as conversion_rate
            FROM properties
            GROUP BY property_type
            ORDER BY conversion_rate DESC";
        
        $trends['property_types'] = $this->db->query($type_trend_query)->result_array();
        
        // Seasonal trends
        $seasonal_query = "SELECT 
            MONTH(registration_date) as month,
            COUNT(*) as registrations,
            AVG(total_amount) as avg_amount
            FROM registrations
            WHERE registration_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
            GROUP BY MONTH(registration_date)
            ORDER BY month";
        
        $trends['seasonal'] = $this->db->query($seasonal_query)->result_array();
        
        // Price trends
        $price_trend_query = "SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            AVG(price) as avg_price,
            MIN(price) as min_price,
            MAX(price) as max_price
            FROM properties
            WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month";
        
        $trends['pricing'] = $this->db->query($price_trend_query)->result_array();
        
        return $trends;
    }
}