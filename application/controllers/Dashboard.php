<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    /**
     * Main dashboard view
     */
    public function index() {
        $data['title'] = 'Dashboard - RMS Admin';
        $data['page_title'] = 'Dashboard';
        $data['theme'] = 'bg-theme bg-theme1'; // Default theme
        
        // Initialize breadcrumbs
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')]
        ];
        
        try {
            // Check if database tables exist
            if ($this->check_database_setup()) {
                // Get dashboard metrics
                $data['metrics'] = $this->Dashboard_model->get_dashboard_metrics();
                $data['database_ready'] = true;
            } else {
                // Database not set up, show installation message
                $data['metrics'] = $this->get_default_metrics();
                $data['database_ready'] = false;
                $data['installation_message'] = 'Database tables not found. Please run the database installer.';
            }
        } catch (Exception $e) {
            // Handle database errors gracefully
            $data['metrics'] = $this->get_default_metrics();
            $data['database_ready'] = false;
            $data['error_message'] = 'Database error: ' . $e->getMessage();
            log_message('error', 'Dashboard database error: ' . $e->getMessage());
        }
        
        // Load dashboard view
        $this->load->view('others/header_clean', $data);
        $this->load->view('dashboard/modern_dashboard', $data);
        $this->load->view('others/footer');
    }
    
    /**
     * Check if database tables exist
     */
    private function check_database_setup() {
        try {
            $required_tables = ['properties', 'customers', 'staff', 'registrations', 'transactions'];
            
            foreach ($required_tables as $table) {
                if (!$this->db->table_exists($table)) {
                    return false;
                }
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get default metrics when database is not ready
     */
    private function get_default_metrics() {
        return [
            'properties' => [
                'total' => 0,
                'by_status' => [
                    'unsold' => 0,
                    'booked' => 0,
                    'sold' => 0
                ],
                'values' => [
                    'total_value' => 0,
                    'sold_value' => 0,
                    'booked_value' => 0,
                    'average_price' => 0
                ]
            ],
            'customers' => [
                'total' => 0,
                'active' => 0,
                'new_this_month' => 0
            ],
            'staff' => [
                'total' => 0,
                'assigned' => 0,
                'workload' => []
            ],
            'transactions' => [
                'total' => 0,
                'by_type' => [],
                'recent' => [
                    'count' => 0,
                    'amount' => 0
                ]
            ],
            'revenue' => [
                'total_collected' => 0,
                'pending' => 0,
                'monthly' => []
            ]
        ];
    }

    /**
     * Get dashboard data via AJAX
     */
    public function get_dashboard_data() {
        header('Content-Type: application/json');
        
        try {
            $metrics = $this->Dashboard_model->get_dashboard_metrics();
            echo json_encode(array(
                'success' => true,
                'data' => $metrics
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error loading dashboard data: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Property analytics page
     */
    public function property_analytics() {
        $data['title'] = 'Property Analytics - RMS Admin';
        
        // Get date range from query parameters
        $date_range = array();
        if ($this->input->get('start_date')) {
            $date_range['start'] = $this->input->get('start_date');
        }
        if ($this->input->get('end_date')) {
            $date_range['end'] = $this->input->get('end_date');
        }
        
        $data['analytics'] = $this->Dashboard_model->get_property_analytics($date_range);
        $data['date_range'] = $date_range;
        
        $this->load->view('others/header', $data);
        $this->load->view('dashboard/property_analytics', $data);
        $this->load->view('others/footer');
    }

    /**
     * Financial analytics page
     */
    public function financial_analytics() {
        $data['title'] = 'Financial Analytics - RMS Admin';
        
        // Get date range from query parameters
        $date_range = array();
        if ($this->input->get('start_date')) {
            $date_range['start'] = $this->input->get('start_date');
        }
        if ($this->input->get('end_date')) {
            $date_range['end'] = $this->input->get('end_date');
        }
        
        $data['analytics'] = $this->Dashboard_model->get_financial_analytics($date_range);
        $data['date_range'] = $date_range;
        
        $this->load->view('others/header', $data);
        $this->load->view('dashboard/financial_analytics', $data);
        $this->load->view('others/footer');
    }

    /**
     * Customer analytics page
     */
    public function customer_analytics() {
        $data['title'] = 'Customer Analytics - RMS Admin';
        
        // Get date range from query parameters
        $date_range = array();
        if ($this->input->get('start_date')) {
            $date_range['start'] = $this->input->get('start_date');
        }
        if ($this->input->get('end_date')) {
            $date_range['end'] = $this->input->get('end_date');
        }
        
        $data['analytics'] = $this->Dashboard_model->get_customer_analytics($date_range);
        $data['date_range'] = $date_range;
        
        $this->load->view('others/header', $data);
        $this->load->view('dashboard/customer_analytics', $data);
        $this->load->view('others/footer');
    }

    /**
     * Staff analytics page
     */
    public function staff_analytics() {
        $data['title'] = 'Staff Analytics - RMS Admin';
        
        // Get date range from query parameters
        $date_range = array();
        if ($this->input->get('start_date')) {
            $date_range['start'] = $this->input->get('start_date');
        }
        if ($this->input->get('end_date')) {
            $date_range['end'] = $this->input->get('end_date');
        }
        
        $data['analytics'] = $this->Dashboard_model->get_staff_analytics($date_range);
        $data['date_range'] = $date_range;
        
        $this->load->view('others/header', $data);
        $this->load->view('dashboard/staff_analytics', $data);
        $this->load->view('others/footer');
    }

    /**
     * Get property analytics data via AJAX
     */
    public function ajax_property_analytics() {
        header('Content-Type: application/json');
        
        try {
            $date_range = array();
            if ($this->input->post('start_date')) {
                $date_range['start'] = $this->input->post('start_date');
            }
            if ($this->input->post('end_date')) {
                $date_range['end'] = $this->input->post('end_date');
            }
            
            $analytics = $this->Dashboard_model->get_property_analytics($date_range);
            echo json_encode(array(
                'success' => true,
                'data' => $analytics
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error loading property analytics: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Get financial analytics data via AJAX
     */
    public function ajax_financial_analytics() {
        header('Content-Type: application/json');
        
        try {
            $date_range = array();
            if ($this->input->post('start_date')) {
                $date_range['start'] = $this->input->post('start_date');
            }
            if ($this->input->post('end_date')) {
                $date_range['end'] = $this->input->post('end_date');
            }
            
            $analytics = $this->Dashboard_model->get_financial_analytics($date_range);
            echo json_encode(array(
                'success' => true,
                'data' => $analytics
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error loading financial analytics: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Get customer analytics data via AJAX
     */
    public function ajax_customer_analytics() {
        header('Content-Type: application/json');
        
        try {
            $date_range = array();
            if ($this->input->post('start_date')) {
                $date_range['start'] = $this->input->post('start_date');
            }
            if ($this->input->post('end_date')) {
                $date_range['end'] = $this->input->post('end_date');
            }
            
            $analytics = $this->Dashboard_model->get_customer_analytics($date_range);
            echo json_encode(array(
                'success' => true,
                'data' => $analytics
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error loading customer analytics: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Get staff analytics data via AJAX
     */
    public function ajax_staff_analytics() {
        header('Content-Type: application/json');
        
        try {
            $date_range = array();
            if ($this->input->post('start_date')) {
                $date_range['start'] = $this->input->post('start_date');
            }
            if ($this->input->post('end_date')) {
                $date_range['end'] = $this->input->post('end_date');
            }
            
            $analytics = $this->Dashboard_model->get_staff_analytics($date_range);
            echo json_encode(array(
                'success' => true,
                'data' => $analytics
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error loading staff analytics: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Export dashboard data to Excel
     */
    public function export_dashboard_data() {
        // This would require a library like PhpSpreadsheet
        // For now, we'll provide CSV export
        
        $metrics = $this->Dashboard_model->get_dashboard_metrics();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="dashboard_metrics_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Property metrics
        fputcsv($output, array('Property Metrics'));
        fputcsv($output, array('Status', 'Count', 'Percentage'));
        $total_properties = $metrics['properties']['total'];
        foreach ($metrics['properties']['by_status'] as $status => $count) {
            $percentage = $total_properties > 0 ? round(($count / $total_properties) * 100, 2) : 0;
            fputcsv($output, array(ucfirst($status), $count, $percentage . '%'));
        }
        
        fputcsv($output, array(''));
        
        // Revenue metrics
        fputcsv($output, array('Revenue Metrics'));
        fputcsv($output, array('Metric', 'Amount'));
        fputcsv($output, array('Total Collected', number_format($metrics['revenue']['total_collected'], 2)));
        fputcsv($output, array('Pending Amount', number_format($metrics['revenue']['pending'], 2)));
        fputcsv($output, array('Total Property Value', number_format($metrics['properties']['values']['total_value'], 2)));
        fputcsv($output, array('Sold Property Value', number_format($metrics['properties']['values']['sold_value'], 2)));
        
        fputcsv($output, array(''));
        
        // Customer metrics
        fputcsv($output, array('Customer Metrics'));
        fputcsv($output, array('Metric', 'Count'));
        fputcsv($output, array('Total Customers', $metrics['customers']['total']));
        fputcsv($output, array('Active Customers', $metrics['customers']['active']));
        fputcsv($output, array('New This Month', $metrics['customers']['new_this_month']));
        
        fputcsv($output, array(''));
        
        // Staff metrics
        fputcsv($output, array('Staff Metrics'));
        fputcsv($output, array('Metric', 'Count'));
        fputcsv($output, array('Total Staff', $metrics['staff']['total']));
        fputcsv($output, array('Assigned Staff', $metrics['staff']['assigned']));
        
        fputcsv($output, array(''));
        
        // Transaction metrics
        fputcsv($output, array('Transaction Metrics'));
        fputcsv($output, array('Metric', 'Value'));
        fputcsv($output, array('Total Transactions', $metrics['transactions']['total']));
        fputcsv($output, array('Recent Transactions (30 days)', $metrics['transactions']['recent']['count']));
        fputcsv($output, array('Recent Amount (30 days)', number_format($metrics['transactions']['recent']['amount'], 2)));
        
        fclose($output);
    }

    /**
     * Get real-time dashboard updates via AJAX
     */
    public function get_real_time_updates() {
        header('Content-Type: application/json');
        
        try {
            // Get only essential metrics for real-time updates
            $updates = array(
                'properties' => $this->Dashboard_model->get_property_summary(),
                'revenue' => $this->Dashboard_model->get_revenue_summary(),
                'transactions_today' => $this->Dashboard_model->get_transactions_today(),
                'new_customers_today' => $this->Dashboard_model->get_new_customers_today(),
                'timestamp' => date('Y-m-d H:i:s')
            );
            
            echo json_encode(array(
                'success' => true,
                'data' => $updates
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error getting real-time updates: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Get performance score calculation
     */
    public function get_performance_score() {
        header('Content-Type: application/json');
        
        try {
            $metrics = $this->Dashboard_model->get_dashboard_metrics();
            
            // Calculate performance score based on multiple factors
            $performance_score = 0;
            
            if ($metrics['properties']['total'] > 0) {
                // Sales rate (40% weight)
                $sales_rate = ($metrics['properties']['by_status']['sold'] / $metrics['properties']['total']) * 40;
                
                // Collection rate (40% weight)
                $total_potential = $metrics['revenue']['total_collected'] + $metrics['revenue']['pending'];
                $collection_rate = $total_potential > 0 ? ($metrics['revenue']['total_collected'] / $total_potential) * 40 : 0;
                
                // Staff efficiency (20% weight)
                $staff_efficiency = $metrics['staff']['total'] > 0 ? ($metrics['staff']['assigned'] / $metrics['staff']['total']) * 20 : 0;
                
                $performance_score = $sales_rate + $collection_rate + $staff_efficiency;
            }
            
            $performance_data = array(
                'overall_score' => round($performance_score, 1),
                'sales_rate' => round($sales_rate ?? 0, 1),
                'collection_rate' => round($collection_rate ?? 0, 1),
                'staff_efficiency' => round($staff_efficiency ?? 0, 1),
                'grade' => $performance_score >= 80 ? 'A' : ($performance_score >= 60 ? 'B' : ($performance_score >= 40 ? 'C' : 'D')),
                'status' => $performance_score >= 80 ? 'Excellent' : ($performance_score >= 60 ? 'Good' : ($performance_score >= 40 ? 'Average' : 'Needs Improvement'))
            );
            
            echo json_encode(array(
                'success' => true,
                'data' => $performance_data
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error calculating performance score: ' . $e->getMessage()
            ));
        }
    }

    /**
     * Get dashboard alerts and notifications
     */
    public function get_dashboard_alerts() {
        header('Content-Type: application/json');
        
        try {
            $alerts = array();
            
            // Check for overdue payments
            $overdue_query = "SELECT COUNT(*) as count FROM registrations r
                             LEFT JOIN (SELECT registration_id, SUM(amount) as paid FROM transactions GROUP BY registration_id) t 
                             ON r.id = t.registration_id
                             WHERE r.status = 'active' 
                             AND (r.total_amount - COALESCE(t.paid, 0)) > 0
                             AND DATEDIFF(CURRENT_DATE(), r.registration_date) > 30";
            $overdue_result = $this->db->query($overdue_query)->row();
            
            if ($overdue_result->count > 0) {
                $alerts[] = array(
                    'type' => 'warning',
                    'title' => 'Overdue Payments',
                    'message' => $overdue_result->count . ' payments are overdue by more than 30 days',
                    'action_url' => base_url('dashboard/financial_analytics'),
                    'priority' => 'high'
                );
            }
            
            // Check for unassigned properties
            $unassigned_query = "SELECT COUNT(*) as count FROM properties WHERE assigned_staff_id IS NULL AND status = 'unsold'";
            $unassigned_result = $this->db->query($unassigned_query)->row();
            
            if ($unassigned_result->count > 0) {
                $alerts[] = array(
                    'type' => 'info',
                    'title' => 'Unassigned Properties',
                    'message' => $unassigned_result->count . ' properties need staff assignment',
                    'action_url' => base_url('properties'),
                    'priority' => 'medium'
                );
            }
            
            // Check for low performance staff
            $low_performance_query = "SELECT COUNT(DISTINCT s.id) as count FROM staff s
                                     LEFT JOIN property_assignments pa ON s.id = pa.staff_id AND pa.is_active = 1
                                     GROUP BY s.id
                                     HAVING COUNT(pa.id) = 0";
            $low_performance_result = $this->db->query($low_performance_query)->result();
            
            if (count($low_performance_result) > 0) {
                $alerts[] = array(
                    'type' => 'warning',
                    'title' => 'Inactive Staff',
                    'message' => count($low_performance_result) . ' staff members have no active assignments',
                    'action_url' => base_url('dashboard/staff_analytics'),
                    'priority' => 'medium'
                );
            }
            
            echo json_encode(array(
                'success' => true,
                'data' => $alerts
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error getting dashboard alerts: ' . $e->getMessage()
            ));
        }
    }
}