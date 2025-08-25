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
            $format = $this->input->get('format', 'csv'); // csv, excel, pdf
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            $garden_id = $this->input->get('garden_id');
            
            if (!$report_type) {
                throw new Exception('Report type is required');
            }
            
            $data = $this->Reports_model->get_export_data($report_type, $start_date, $end_date, $garden_id);
            $filters = array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'garden_id' => $garden_id
            );
            
            switch ($format) {
                case 'pdf':
                    $this->export_pdf_report($data, $report_type, $filters);
                    break;
                case 'excel':
                    $this->export_excel_report($data, $report_type, $filters);
                    break;
                case 'csv':
                default:
                    $this->export_csv_report($data, $report_type, $filters);
                    break;
            }
            
        } catch (Exception $e) {
            error_log('Error exporting report: ' . $e->getMessage());
            echo "Error exporting report: " . $e->getMessage();
        }
    }
    
    /**
     * Export report as PDF
     */
    private function export_pdf_report($data, $report_type, $filters) {
        $this->load->library('Pdf_generator');
        
        $report_data = array(
            'data' => $data,
            'filters' => $filters,
            'summary' => $this->calculate_report_summary($data, $report_type)
        );
        
        $this->pdf_generator->generate_report($report_data, $report_type);
    }
    
    /**
     * Export report as Excel
     */
    private function export_excel_report($data, $report_type, $filters) {
        $this->load->library('Excel_exporter');
        
        switch ($report_type) {
            case 'sales':
                $this->excel_exporter->export_sales_report($data, $filters);
                break;
            case 'financial':
                $this->excel_exporter->export_financial_report($data, $filters);
                break;
            case 'customers':
                $this->excel_exporter->export_customer_analytics($data);
                break;
            case 'properties':
                $this->excel_exporter->export_property_performance($data);
                break;
            case 'staff':
                $this->excel_exporter->export_staff_performance($data);
                break;
            default:
                throw new Exception('Unsupported report type for Excel export');
        }
    }
    
    /**
     * Export report as CSV (existing functionality enhanced)
     */
    private function export_csv_report($data, $report_type, $filters) {
        $this->load->library('Excel_exporter');
        
        switch ($report_type) {
            case 'sales':
                $this->excel_exporter->export_sales_report($data, $filters);
                $this->excel_exporter->generate_csv();
                break;
            case 'financial':
                $this->excel_exporter->export_financial_report($data, $filters);
                $this->excel_exporter->generate_csv();
                break;
            case 'customers':
                $this->excel_exporter->export_customer_analytics($data);
                $this->excel_exporter->generate_csv();
                break;
            case 'properties':
                $this->excel_exporter->export_property_performance($data);
                $this->excel_exporter->generate_csv();
                break;
            case 'staff':
                $this->excel_exporter->export_staff_performance($data);
                $this->excel_exporter->generate_csv();
                break;
            default:
                // Fallback to original CSV export
                $this->legacy_csv_export($data, $report_type);
        }
    }
    
    /**
     * Legacy CSV export for backward compatibility
     */
    private function legacy_csv_export($data, $report_type) {
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
    
    /**
     * Email report to specified recipients
     */
    public function email_report() {
        try {
            $report_type = $this->input->post('report_type');
            $recipients = $this->input->post('recipients'); // JSON array of emails
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $garden_id = $this->input->post('garden_id');
            $message = $this->input->post('message');
            
            if (!$report_type || !$recipients) {
                throw new Exception('Report type and recipients are required');
            }
            
            // Decode recipients if JSON
            if (is_string($recipients)) {
                $recipients = json_decode($recipients, true);
            }
            
            // Get report data
            $data = $this->Reports_model->get_export_data($report_type, $start_date, $end_date, $garden_id);
            $filters = array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'garden_id' => $garden_id
            );
            
            $report_data = array(
                'data' => $data,
                'filters' => $filters,
                'summary' => $this->calculate_report_summary($data, $report_type)
            );
            
            // Send emails
            $this->load->library('Email_sender');
            $results = array();
            
            foreach ($recipients as $recipient) {
                $result = $this->email_sender->send_report($report_data, $report_type, $recipient);
                $results[$recipient] = $result;
            }
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => true,
                'message' => 'Report emails sent successfully',
                'results' => $results
            ));
            
        } catch (Exception $e) {
            error_log('Error emailing report: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => false,
                'message' => 'Error sending report emails: ' . $e->getMessage()
            ));
        }
    }
    
    /**
     * Generate and email receipt
     */
    public function email_receipt() {
        try {
            $transaction_id = $this->input->post('transaction_id');
            $recipient_email = $this->input->post('recipient_email');
            $recipient_name = $this->input->post('recipient_name');
            
            if (!$transaction_id || !$recipient_email) {
                throw new Exception('Transaction ID and recipient email are required');
            }
            
            // Get receipt data
            $receipt_data = $this->Transaction_model->generate_receipt($transaction_id);
            
            if (!$receipt_data) {
                throw new Exception('Receipt not found');
            }
            
            // Send email
            $this->load->library('Email_sender');
            $result = $this->email_sender->send_receipt($receipt_data, $recipient_email, $recipient_name);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => $result,
                'message' => $result ? 'Receipt sent successfully' : 'Failed to send receipt'
            ));
            
        } catch (Exception $e) {
            error_log('Error emailing receipt: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => false,
                'message' => 'Error sending receipt: ' . $e->getMessage()
            ));
        }
    }
    
    /**
     * Send payment reminders
     */
    public function send_payment_reminders() {
        try {
            $days_overdue = $this->input->post('days_overdue', 0);
            
            // Get overdue payments
            $overdue_payments = $this->Transaction_model->get_overdue_payments($days_overdue);
            
            if (empty($overdue_payments)) {
                throw new Exception('No overdue payments found');
            }
            
            // Send reminder emails
            $this->load->library('Email_sender');
            $results = $this->email_sender->send_payment_reminders($overdue_payments);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => true,
                'message' => 'Payment reminders sent',
                'results' => $results,
                'total_sent' => count(array_filter($results))
            ));
            
        } catch (Exception $e) {
            error_log('Error sending payment reminders: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => false,
                'message' => 'Error sending payment reminders: ' . $e->getMessage()
            ));
        }
    }
    
    /**
     * Advanced report with date range filtering
     */
    public function advanced_report() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        
        // Get filter parameters
        $report_type = $this->input->get('type', 'sales');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $garden_id = $this->input->get('garden_id');
        $customer_id = $this->input->get('customer_id');
        $staff_id = $this->input->get('staff_id');
        
        // Set default date range if not provided
        if (!$start_date) {
            $start_date = date('Y-m-01'); // First day of current month
        }
        if (!$end_date) {
            $end_date = date('Y-m-t'); // Last day of current month
        }
        
        try {
            $filters = array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'garden_id' => $garden_id,
                'customer_id' => $customer_id,
                'staff_id' => $staff_id
            );
            
            // Get report data based on type
            switch ($report_type) {
                case 'sales':
                    $data['report_data'] = $this->Reports_model->get_sales_report($start_date, $end_date, $garden_id);
                    break;
                case 'financial':
                    $data['report_data'] = $this->Reports_model->get_financial_summary($start_date, $end_date);
                    break;
                case 'customer':
                    $data['report_data'] = $this->Reports_model->get_customer_analytics();
                    break;
                case 'property':
                    $data['report_data'] = $this->Reports_model->get_property_performance($garden_id);
                    break;
                case 'staff':
                    $data['report_data'] = $this->Reports_model->get_staff_performance($staff_id);
                    break;
                case 'transactions':
                    $data['report_data'] = $this->Transaction_model->get_transaction_history($filters);
                    break;
                default:
                    $data['report_data'] = array();
            }
            
            // Calculate summary
            $data['summary'] = $this->calculate_report_summary($data['report_data'], $report_type);
            
            // Get dropdown data
            $data['gardens'] = $this->Garden_model->get_all_gardens();
            $data['customers'] = $this->Customer_model->get_all_customers();
            $data['staff'] = $this->Staff_model->get_all_staff();
            
            $data['filters'] = $filters;
            $data['report_type'] = $report_type;
            
        } catch (Exception $e) {
            error_log('Error generating advanced report: ' . $e->getMessage());
            $data['report_data'] = array();
            $data['summary'] = array();
            $data['gardens'] = array();
            $data['customers'] = array();
            $data['staff'] = array();
            $data['filters'] = array();
            $data['report_type'] = $report_type;
            $data['error'] = $e->getMessage();
        }
        
        $this->load->view('others/header', $data);
        $this->load->view('reports/advanced_report');
        $this->load->view('others/footer');
    }
    
    /**
     * Calculate report summary based on data and type
     */
    private function calculate_report_summary($data, $report_type) {
        $summary = array();
        
        try {
            switch ($report_type) {
                case 'sales':
                    $total_sales = 0;
                    $total_properties = 0;
                    
                    if (is_array($data)) {
                        foreach ($data as $sale) {
                            $total_sales += isset($sale->sale_amount) ? $sale->sale_amount : 0;
                            $total_properties++;
                        }
                    }
                    
                    $summary = array(
                        'total_properties' => $total_properties,
                        'total_sales' => $total_sales,
                        'average_sale' => $total_properties > 0 ? $total_sales / $total_properties : 0
                    );
                    break;
                    
                case 'financial':
                    if (isset($data['monthly_revenue']) && is_array($data['monthly_revenue'])) {
                        $total_revenue = 0;
                        $total_transactions = 0;
                        
                        foreach ($data['monthly_revenue'] as $month) {
                            $total_revenue += $month->monthly_revenue;
                            $total_transactions += $month->transaction_count;
                        }
                        
                        $summary = array(
                            'total_revenue' => $total_revenue,
                            'total_transactions' => $total_transactions,
                            'average_transaction' => $total_transactions > 0 ? $total_revenue / $total_transactions : 0
                        );
                    }
                    break;
                    
                case 'transactions':
                    if (is_array($data)) {
                        $total_amount = 0;
                        $total_transactions = count($data);
                        
                        foreach ($data as $transaction) {
                            $total_amount += isset($transaction['amount']) ? $transaction['amount'] : 0;
                        }
                        
                        $summary = array(
                            'total_transactions' => $total_transactions,
                            'total_amount' => $total_amount,
                            'average_amount' => $total_transactions > 0 ? $total_amount / $total_transactions : 0
                        );
                    }
                    break;
                    
                default:
                    $summary = array('total_records' => is_array($data) ? count($data) : 0);
            }
            
        } catch (Exception $e) {
            error_log('Error calculating report summary: ' . $e->getMessage());
            $summary = array('error' => 'Failed to calculate summary');
        }
        
        return $summary;
    }
    
    /**
     * Generate receipt PDF
     */
    public function generate_receipt_pdf() {
        try {
            $transaction_id = $this->input->get('transaction_id');
            
            if (!$transaction_id) {
                throw new Exception('Transaction ID is required');
            }
            
            // Get receipt data
            $receipt_data = $this->Transaction_model->generate_receipt($transaction_id);
            
            if (!$receipt_data) {
                throw new Exception('Receipt not found');
            }
            
            // Generate PDF
            $this->load->library('Pdf_generator');
            $this->pdf_generator->generate_receipt($receipt_data);
            
        } catch (Exception $e) {
            error_log('Error generating receipt PDF: ' . $e->getMessage());
            echo "Error generating receipt PDF: " . $e->getMessage();
        }
    }
}
