<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('Reports_model');
        $this->load->model('Garden_model');
        $this->load->model('Customer_model');
        $this->load->model('Staff_model');
        $this->load->model('Transaction_model');
        $this->load->model('Theme_model');
    }

    public function index() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get dashboard summary
        try {
            $data['summary'] = $this->Reports_model->get_dashboard_summary();
        } catch (Exception $e) {
            error_log('Error getting dashboard summary: ' . $e->getMessage());
            $data['summary'] = array();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('reports/dashboard');
        $this->load->view('others/footer');
    }

    public function sales_report() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get filter parameters
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $garden_id = $this->input->get('garden_id');
        
        try {
            $data['sales'] = $this->Reports_model->get_sales_report($start_date, $end_date, $garden_id);
            $data['gardens'] = $this->Garden_model->get_all_gardens();
            
            // Calculate totals
            $data['total_sales'] = 0;
            $data['total_properties'] = 0;
            foreach ($data['sales'] as $sale) {
                $data['total_sales'] += $sale->sale_amount ?: 0;
                $data['total_properties']++;
            }
            
        } catch (Exception $e) {
            error_log('Error getting sales report: ' . $e->getMessage());
            $data['sales'] = array();
            $data['gardens'] = array();
            $data['total_sales'] = 0;
            $data['total_properties'] = 0;
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('reports/sales_report');
        $this->load->view('others/footer');
    }

    public function booking_report() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get filter parameters
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $garden_id = $this->input->get('garden_id');
        
        try {
            $data['bookings'] = $this->Reports_model->get_booking_report($start_date, $end_date, $garden_id);
            $data['gardens'] = $this->Garden_model->get_all_gardens();
            
            // Calculate totals
            $data['total_bookings'] = 0;
            $data['total_amount'] = 0;
            foreach ($data['bookings'] as $booking) {
                $data['total_bookings']++;
                $data['total_amount'] += $booking->booking_amount ?: 0;
            }
            
        } catch (Exception $e) {
            error_log('Error getting booking report: ' . $e->getMessage());
            $data['bookings'] = array();
            $data['gardens'] = array();
            $data['total_bookings'] = 0;
            $data['total_amount'] = 0;
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('reports/booking_report');
        $this->load->view('others/footer');
    }

    public function customer_analytics() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        try {
            $data['analytics'] = $this->Reports_model->get_customer_analytics();
        } catch (Exception $e) {
            error_log('Error getting customer analytics: ' . $e->getMessage());
            $data['analytics'] = array();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('reports/customer_analytics');
        $this->load->view('others/footer');
    }

    public function property_performance() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get filter parameters
        $garden_id = $this->input->get('garden_id');
        
        try {
            $data['performance'] = $this->Reports_model->get_property_performance($garden_id);
            $data['gardens'] = $this->Garden_model->get_all_gardens();
        } catch (Exception $e) {
            error_log('Error getting property performance: ' . $e->getMessage());
            $data['performance'] = array();
            $data['gardens'] = array();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('reports/property_performance');
        $this->load->view('others/footer');
    }

    public function staff_performance() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get filter parameters
        $staff_id = $this->input->get('staff_id');
        
        try {
            $data['performance'] = $this->Reports_model->get_staff_performance($staff_id);
            $data['staff'] = $this->Staff_model->get_all_staff();
        } catch (Exception $e) {
            error_log('Error getting staff performance: ' . $e->getMessage());
            $data['performance'] = array();
            $data['staff'] = array();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('reports/staff_performance');
        $this->load->view('others/footer');
    }

    public function financial_summary() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get filter parameters
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        try {
            $data['financial'] = $this->Reports_model->get_financial_summary($start_date, $end_date);
            
            // Get additional financial data
            $data['total_revenue'] = $this->Transaction_model->get_total_revenue();
            $data['pending_payments'] = $this->Transaction_model->get_pending_payments();
            
        } catch (Exception $e) {
            error_log('Error getting financial summary: ' . $e->getMessage());
            $data['financial'] = array();
            $data['total_revenue'] = 0;
            $data['pending_payments'] = array();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('reports/financial_summary');
        $this->load->view('others/footer');
    }

    public function export_report() {
        try {
            $report_type = $this->input->get('type');
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            $garden_id = $this->input->get('garden_id');
            
            if (!$report_type) {
                throw new Exception('Report type is required');
            }
            
            $data = $this->Reports_model->get_export_data($report_type, $start_date, $end_date, $garden_id);
            
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $report_type . '_report_' . date('Y-m-d') . '.csv"');
            
            // Create CSV output
            $output = fopen('php://output', 'w');
            
            // Add headers based on report type
            switch ($report_type) {
                case 'sales':
                    fputcsv($output, array('Sale Date', 'Garden', 'Plot No', 'Customer', 'Sale Amount', 'Status'));
                    foreach ($data as $row) {
                        fputcsv($output, array(
                            $row->sale_date,
                            $row->garden_name,
                            $row->plot_no,
                            $row->plot_buyer_name,
                            $row->sale_amount,
                            $row->status
                        ));
                    }
                    break;
                    
                case 'bookings':
                    fputcsv($output, array('Booking Date', 'Garden', 'Plot No', 'Customer', 'Booking Amount', 'Status'));
                    foreach ($data as $row) {
                        fputcsv($output, array(
                            $row->booking_date,
                            $row->garden_name,
                            $row->plot_no,
                            $row->plot_buyer_name,
                            $row->booking_amount,
                            $row->status
                        ));
                    }
                    break;
                    
                case 'customers':
                    fputcsv($output, array('Customer Name', 'Phone', 'District', 'Properties Owned', 'Total Investment'));
                    foreach ($data['top_customers'] as $row) {
                        fputcsv($output, array(
                            $row->plot_buyer_name,
                            $row->phone_number_1,
                            $row->district ?? 'N/A',
                            $row->properties_owned,
                            $row->total_investment
                        ));
                    }
                    break;
                    
                case 'properties':
                    fputcsv($output, array('Garden Name', 'District', 'Total Plots', 'Sold', 'Booked', 'Unsold', 'Total Value', 'Total Sales', 'Conversion Rate'));
                    foreach ($data as $row) {
                        fputcsv($output, array(
                            $row->garden_name,
                            $row->district,
                            $row->total_plots,
                            $row->sold_plots,
                            $row->booked_plots,
                            $row->unsold_plots,
                            $row->total_value,
                            $row->total_sales,
                            $row->conversion_rate . '%'
                        ));
                    }
                    break;
                    
                case 'staff':
                    fputcsv($output, array('Employee Name', 'Designation', 'Department', 'Assigned Properties', 'Sold Properties', 'Booked Properties', 'Total Sales', 'Success Rate'));
                    foreach ($data as $row) {
                        fputcsv($output, array(
                            $row->employee_name,
                            $row->designation,
                            $row->department,
                            $row->assigned_properties,
                            $row->sold_properties,
                            $row->booked_properties,
                            $row->total_sales,
                            $row->success_rate . '%'
                        ));
                    }
                    break;
                    
                case 'financial':
                    fputcsv($output, array('Month', 'Revenue', 'Transaction Count'));
                    foreach ($data['monthly_revenue'] as $row) {
                        fputcsv($output, array(
                            $row->month,
                            $row->monthly_revenue,
                            $row->transaction_count
                        ));
                    }
                    break;
                    
                default:
                    throw new Exception('Invalid report type');
            }
            
            fclose($output);
            
        } catch (Exception $e) {
            error_log('Error exporting report: ' . $e->getMessage());
            echo "Error exporting report: " . $e->getMessage();
        }
    }

    public function print_report() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        $report_type = $this->input->get('type');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $garden_id = $this->input->get('garden_id');
        
        try {
            $data['report_data'] = $this->Reports_model->get_export_data($report_type, $start_date, $end_date, $garden_id);
            $data['report_type'] = $report_type;
            $data['filters'] = array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'garden_id' => $garden_id
            );
            
        } catch (Exception $e) {
            error_log('Error getting report data for printing: ' . $e->getMessage());
            $data['report_data'] = array();
            $data['report_type'] = '';
            $data['filters'] = array();
        }
        
        $this->load->view('reports/print_layout', $data);
    }
}
