<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Excel Exporter Library
 * Generates Excel files for data export using CSV format with Excel compatibility
 */
class Excel_exporter {
    
    private $CI;
    private $data;
    private $headers;
    private $filename;
    private $worksheet_name;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->initialize();
    }
    
    /**
     * Initialize Excel exporter
     */
    private function initialize() {
        $this->data = array();
        $this->headers = array();
        $this->filename = 'export_' . date('Y-m-d_H-i-s');
        $this->worksheet_name = 'Sheet1';
    }
    
    /**
     * Set filename for export
     * @param string $filename Filename without extension
     */
    public function set_filename($filename) {
        $this->filename = $filename;
    }
    
    /**
     * Set worksheet name
     * @param string $name Worksheet name
     */
    public function set_worksheet_name($name) {
        $this->worksheet_name = $name;
    }
    
    /**
     * Set headers for the Excel file
     * @param array $headers Array of header names
     */
    public function set_headers($headers) {
        $this->headers = $headers;
    }
    
    /**
     * Add data row
     * @param array $row Data row
     */
    public function add_row($row) {
        $this->data[] = $row;
    }
    
    /**
     * Set all data at once
     * @param array $data Array of data rows
     */
    public function set_data($data) {
        $this->data = $data;
    }
    
    /**
     * Export sales report to Excel
     * @param array $sales_data Sales data
     * @param array $filters Applied filters
     * @return bool Success status
     */
    public function export_sales_report($sales_data, $filters = array()) {
        $this->set_filename('sales_report_' . date('Y-m-d'));
        $this->set_worksheet_name('Sales Report');
        
        // Set headers
        $headers = array(
            'Sale Date',
            'Property/Garden',
            'Plot Number',
            'Customer Name',
            'Customer Phone',
            'Sale Amount',
            'Payment Status',
            'District',
            'Registration Date'
        );
        $this->set_headers($headers);
        
        // Process data
        $processed_data = array();
        foreach ($sales_data as $sale) {
            $processed_data[] = array(
                isset($sale->sale_date) ? date('d/m/Y', strtotime($sale->sale_date)) : 'N/A',
                $sale->garden_name ?? 'N/A',
                $sale->plot_no ?? 'N/A',
                $sale->plot_buyer_name ?? 'N/A',
                $sale->phone_number_1 ?? 'N/A',
                isset($sale->sale_amount) ? number_format($sale->sale_amount, 2) : '0.00',
                ucfirst($sale->status ?? 'N/A'),
                $sale->district ?? 'N/A',
                isset($sale->created_at) ? date('d/m/Y', strtotime($sale->created_at)) : 'N/A'
            );
        }
        
        $this->set_data($processed_data);
        return $this->generate_excel();
    }
    
    /**
     * Export financial report to Excel
     * @param array $financial_data Financial data
     * @param array $filters Applied filters
     * @return bool Success status
     */
    public function export_financial_report($financial_data, $filters = array()) {
        $this->set_filename('financial_report_' . date('Y-m-d'));
        $this->set_worksheet_name('Financial Report');
        
        // Create multiple sheets for different financial data
        $this->export_financial_summary($financial_data);
        
        return true;
    }
    
    /**
     * Export financial summary
     * @param array $financial_data Financial data
     */
    private function export_financial_summary($financial_data) {
        // Monthly Revenue Sheet
        if (isset($financial_data['monthly_revenue'])) {
            $headers = array('Month', 'Revenue', 'Transaction Count', 'Average Transaction');
            $this->set_headers($headers);
            
            $processed_data = array();
            foreach ($financial_data['monthly_revenue'] as $month_data) {
                $avg_transaction = $month_data->transaction_count > 0 ? 
                    $month_data->monthly_revenue / $month_data->transaction_count : 0;
                    
                $processed_data[] = array(
                    $month_data->month,
                    number_format($month_data->monthly_revenue, 2),
                    $month_data->transaction_count,
                    number_format($avg_transaction, 2)
                );
            }
            
            $this->set_data($processed_data);
            $this->generate_excel();
        }
    }
    
    /**
     * Export customer analytics to Excel
     * @param array $customer_data Customer analytics data
     * @return bool Success status
     */
    public function export_customer_analytics($customer_data) {
        $this->set_filename('customer_analytics_' . date('Y-m-d'));
        $this->set_worksheet_name('Customer Analytics');
        
        // Top Customers Sheet
        if (isset($customer_data['top_customers'])) {
            $headers = array(
                'Customer Name',
                'Phone Number',
                'District',
                'Properties Owned',
                'Total Investment',
                'Average Investment'
            );
            $this->set_headers($headers);
            
            $processed_data = array();
            foreach ($customer_data['top_customers'] as $customer) {
                $avg_investment = $customer->properties_owned > 0 ? 
                    $customer->total_investment / $customer->properties_owned : 0;
                    
                $processed_data[] = array(
                    $customer->plot_buyer_name,
                    $customer->phone_number_1 ?? 'N/A',
                    $customer->district ?? 'N/A',
                    $customer->properties_owned,
                    number_format($customer->total_investment, 2),
                    number_format($avg_investment, 2)
                );
            }
            
            $this->set_data($processed_data);
            return $this->generate_excel();
        }
        
        return false;
    }
    
    /**
     * Export property performance to Excel
     * @param array $property_data Property performance data
     * @return bool Success status
     */
    public function export_property_performance($property_data) {
        $this->set_filename('property_performance_' . date('Y-m-d'));
        $this->set_worksheet_name('Property Performance');
        
        $headers = array(
            'Garden/Property Name',
            'District',
            'Total Plots',
            'Sold Plots',
            'Booked Plots',
            'Unsold Plots',
            'Total Value',
            'Total Sales',
            'Conversion Rate (%)',
            'Revenue Percentage'
        );
        $this->set_headers($headers);
        
        $processed_data = array();
        foreach ($property_data as $property) {
            $revenue_percentage = $property->total_value > 0 ? 
                ($property->total_sales / $property->total_value) * 100 : 0;
                
            $processed_data[] = array(
                $property->garden_name,
                $property->district ?? 'N/A',
                $property->total_plots,
                $property->sold_plots,
                $property->booked_plots,
                $property->unsold_plots,
                number_format($property->total_value, 2),
                number_format($property->total_sales, 2),
                number_format($property->conversion_rate, 2),
                number_format($revenue_percentage, 2)
            );
        }
        
        $this->set_data($processed_data);
        return $this->generate_excel();
    }
    
    /**
     * Export staff performance to Excel
     * @param array $staff_data Staff performance data
     * @return bool Success status
     */
    public function export_staff_performance($staff_data) {
        $this->set_filename('staff_performance_' . date('Y-m-d'));
        $this->set_worksheet_name('Staff Performance');
        
        $headers = array(
            'Employee Name',
            'Designation',
            'Department',
            'Assigned Properties',
            'Sold Properties',
            'Booked Properties',
            'Total Sales',
            'Success Rate (%)',
            'Average Sale Value'
        );
        $this->set_headers($headers);
        
        $processed_data = array();
        foreach ($staff_data as $staff) {
            $avg_sale_value = $staff->sold_properties > 0 ? 
                $staff->total_sales / $staff->sold_properties : 0;
                
            $processed_data[] = array(
                $staff->employee_name,
                $staff->designation ?? 'N/A',
                $staff->department ?? 'N/A',
                $staff->assigned_properties,
                $staff->sold_properties,
                $staff->booked_properties,
                number_format($staff->total_sales, 2),
                number_format($staff->success_rate, 2),
                number_format($avg_sale_value, 2)
            );
        }
        
        $this->set_data($processed_data);
        return $this->generate_excel();
    }
    
    /**
     * Export transaction history to Excel
     * @param array $transactions Transaction data
     * @return bool Success status
     */
    public function export_transaction_history($transactions) {
        $this->set_filename('transaction_history_' . date('Y-m-d'));
        $this->set_worksheet_name('Transaction History');
        
        $headers = array(
            'Transaction Date',
            'Receipt Number',
            'Customer Name',
            'Property',
            'Registration Number',
            'Payment Type',
            'Payment Method',
            'Amount',
            'Notes'
        );
        $this->set_headers($headers);
        
        $processed_data = array();
        foreach ($transactions as $transaction) {
            $processed_data[] = array(
                date('d/m/Y', strtotime($transaction['payment_date'])),
                $transaction['receipt_number'] ?? 'N/A',
                $transaction['plot_buyer_name'] ?? 'N/A',
                $transaction['garden_name'] ?? 'N/A',
                $transaction['registration_number'] ?? 'N/A',
                ucfirst(str_replace('_', ' ', $transaction['payment_type'])),
                ucfirst($transaction['payment_method']),
                number_format($transaction['amount'], 2),
                $transaction['notes'] ?? ''
            );
        }
        
        $this->set_data($processed_data);
        return $this->generate_excel();
    }
    
    /**
     * Generate Excel file (CSV format with Excel compatibility)
     * @return bool Success status
     */
    public function generate_excel() {
        try {
            // Set headers for Excel download
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . $this->filename . '.xls"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
            
            // Start output
            echo $this->generate_excel_content();
            
            return true;
            
        } catch (Exception $e) {
            log_message('error', 'Excel export failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate Excel content in HTML table format
     * @return string Excel-compatible HTML content
     */
    private function generate_excel_content() {
        $content = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . $this->worksheet_name . '</title>
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #000; padding: 5px; text-align: left; }
                th { background-color: #f0f0f0; font-weight: bold; }
                .number { text-align: right; }
            </style>
        </head>
        <body>
            <table>
                <thead>
                    <tr>';
                    
        // Add headers
        foreach ($this->headers as $header) {
            $content .= '<th>' . htmlspecialchars($header) . '</th>';
        }
        
        $content .= '</tr>
                </thead>
                <tbody>';
                
        // Add data rows
        foreach ($this->data as $row) {
            $content .= '<tr>';
            foreach ($row as $cell) {
                $class = is_numeric($cell) ? 'number' : '';
                $content .= '<td class="' . $class . '">' . htmlspecialchars($cell) . '</td>';
            }
            $content .= '</tr>';
        }
        
        $content .= '</tbody>
            </table>
        </body>
        </html>';
        
        return $content;
    }
    
    /**
     * Save Excel file to server
     * @param string $filepath File path to save
     * @return bool Success status
     */
    public function save_excel($filepath) {
        try {
            $directory = dirname($filepath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $content = $this->generate_excel_content();
            return file_put_contents($filepath, $content) !== false;
            
        } catch (Exception $e) {
            log_message('error', 'Failed to save Excel file: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate CSV format for simple export
     * @return bool Success status
     */
    public function generate_csv() {
        try {
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $this->filename . '.csv"');
            header('Cache-Control: max-age=0');
            
            // Create output stream
            $output = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 Excel compatibility
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add headers
            if (!empty($this->headers)) {
                fputcsv($output, $this->headers);
            }
            
            // Add data rows
            foreach ($this->data as $row) {
                fputcsv($output, $row);
            }
            
            fclose($output);
            return true;
            
        } catch (Exception $e) {
            log_message('error', 'CSV export failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Clear all data and reset
     */
    public function reset() {
        $this->initialize();
    }
}