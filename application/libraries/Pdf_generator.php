<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PDF Generator Library
 * Generates PDF documents for reports and receipts using TCPDF-like functionality
 */
class Pdf_generator {
    
    private $CI;
    private $pdf_content;
    private $page_orientation;
    private $page_format;
    private $margins;
    private $header_content;
    private $footer_content;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->initialize();
    }
    
    /**
     * Initialize PDF generator with default settings
     */
    private function initialize() {
        $this->page_orientation = 'P'; // Portrait
        $this->page_format = 'A4';
        $this->margins = array(
            'top' => 20,
            'right' => 15,
            'bottom' => 20,
            'left' => 15
        );
        $this->pdf_content = '';
        $this->header_content = '';
        $this->footer_content = '';
    }
    
    /**
     * Set page orientation
     * @param string $orientation P for Portrait, L for Landscape
     */
    public function set_orientation($orientation = 'P') {
        $this->page_orientation = $orientation;
    }
    
    /**
     * Set page format
     * @param string $format Page format (A4, A3, Letter, etc.)
     */
    public function set_format($format = 'A4') {
        $this->page_format = $format;
    }
    
    /**
     * Set page margins
     * @param array $margins Array with top, right, bottom, left margins
     */
    public function set_margins($margins) {
        $this->margins = array_merge($this->margins, $margins);
    }
    
    /**
     * Set header content
     * @param string $content Header HTML content
     */
    public function set_header($content) {
        $this->header_content = $content;
    }
    
    /**
     * Set footer content
     * @param string $content Footer HTML content
     */
    public function set_footer($content) {
        $this->footer_content = $content;
    }
    
    /**
     * Add content to PDF
     * @param string $content HTML content to add
     */
    public function add_content($content) {
        $this->pdf_content .= $content;
    }
    
    /**
     * Generate receipt PDF
     * @param array $receipt_data Receipt data
     * @return string PDF content
     */
    public function generate_receipt($receipt_data) {
        $html = $this->build_receipt_html($receipt_data);
        return $this->html_to_pdf($html, 'receipt');
    }
    
    /**
     * Generate report PDF
     * @param array $report_data Report data
     * @param string $report_type Type of report
     * @return string PDF content
     */
    public function generate_report($report_data, $report_type) {
        $html = $this->build_report_html($report_data, $report_type);
        return $this->html_to_pdf($html, $report_type);
    }
    
    /**
     * Build receipt HTML
     * @param array $receipt_data Receipt data
     * @return string HTML content
     */
    private function build_receipt_html($receipt_data) {
        $transaction = $receipt_data['transaction'];
        $balance_info = $receipt_data['balance_info'];
        
        // Get company info from config or use default
        if (isset($receipt_data['company_info'])) {
            $company_info = $receipt_data['company_info'];
        } else {
            // Load from config
            $this->CI->config->load('email_config', TRUE);
            $email_settings = $this->CI->config->item('email_settings', 'email_config');
            $company_info = isset($email_settings['company_info']) ? $email_settings['company_info'] : array(
                'name' => 'Real Estate Management System',
                'address' => 'Your Company Address',
                'phone' => 'Your Phone Number',
                'email' => 'your@email.com'
            );
        }
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Payment Receipt</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
                .company-name { font-size: 24px; font-weight: bold; color: #333; }
                .company-details { font-size: 12px; color: #666; margin-top: 10px; }
                .receipt-title { font-size: 20px; font-weight: bold; text-align: center; margin: 20px 0; }
                .receipt-info { display: table; width: 100%; margin-bottom: 20px; }
                .receipt-row { display: table-row; }
                .receipt-label { display: table-cell; width: 30%; font-weight: bold; padding: 5px 0; }
                .receipt-value { display: table-cell; width: 70%; padding: 5px 0; }
                .amount-section { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .amount-row { display: flex; justify-content: space-between; margin: 5px 0; }
                .total-amount { font-size: 18px; font-weight: bold; border-top: 2px solid #333; padding-top: 10px; }
                .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #666; }
                .signature-section { margin-top: 50px; display: flex; justify-content: space-between; }
                .signature-box { width: 200px; text-align: center; }
                .signature-line { border-top: 1px solid #333; margin-top: 40px; padding-top: 5px; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="company-name">' . htmlspecialchars($company_info['name']) . '</div>
                <div class="company-details">
                    ' . htmlspecialchars($company_info['address']) . '<br>
                    Phone: ' . htmlspecialchars($company_info['phone']) . ' | Email: ' . htmlspecialchars($company_info['email']) . '
                </div>
            </div>
            
            <div class="receipt-title">PAYMENT RECEIPT</div>
            
            <div class="receipt-info">
                <div class="receipt-row">
                    <div class="receipt-label">Receipt Number:</div>
                    <div class="receipt-value">' . htmlspecialchars($transaction['receipt_number']) . '</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">Date:</div>
                    <div class="receipt-value">' . date('d/m/Y', strtotime($transaction['payment_date'])) . '</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">Customer Name:</div>
                    <div class="receipt-value">' . htmlspecialchars($transaction['plot_buyer_name']) . '</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">Property:</div>
                    <div class="receipt-value">' . htmlspecialchars($transaction['garden_name']) . '</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">Registration No:</div>
                    <div class="receipt-value">' . htmlspecialchars($transaction['registration_number']) . '</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">Payment Method:</div>
                    <div class="receipt-value">' . ucfirst(htmlspecialchars($transaction['payment_method'])) . '</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">Payment Type:</div>
                    <div class="receipt-value">' . ucfirst(str_replace('_', ' ', htmlspecialchars($transaction['payment_type']))) . '</div>
                </div>
            </div>
            
            <div class="amount-section">
                <div class="amount-row">
                    <span>Payment Amount:</span>
                    <span>₹ ' . number_format($transaction['amount'], 2) . '</span>
                </div>
                <div class="amount-row">
                    <span>Total Amount:</span>
                    <span>₹ ' . number_format($balance_info['total_amount'], 2) . '</span>
                </div>
                <div class="amount-row">
                    <span>Total Paid:</span>
                    <span>₹ ' . number_format($balance_info['total_paid'], 2) . '</span>
                </div>
                <div class="amount-row total-amount">
                    <span>Balance Due:</span>
                    <span>₹ ' . number_format($balance_info['balance'], 2) . '</span>
                </div>
            </div>';
            
        if (!empty($transaction['notes'])) {
            $html .= '
            <div style="margin: 20px 0;">
                <strong>Notes:</strong><br>
                ' . nl2br(htmlspecialchars($transaction['notes'])) . '
            </div>';
        }
        
        $html .= '
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line">Received By</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">Authorized Signature</div>
                </div>
            </div>
            
            <div class="footer">
                This is a computer generated receipt and does not require signature.<br>
                Generated on: ' . date('d/m/Y H:i:s') . '
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Build report HTML
     * @param array $report_data Report data
     * @param string $report_type Report type
     * @return string HTML content
     */
    private function build_report_html($report_data, $report_type) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . ucfirst($report_type) . ' Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; font-size: 12px; }
                .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
                .report-title { font-size: 20px; font-weight: bold; margin-bottom: 10px; }
                .report-filters { font-size: 12px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f8f9fa; font-weight: bold; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .summary-section { background-color: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 5px; }
                .summary-row { display: flex; justify-content: space-between; margin: 5px 0; }
                .footer { text-align: center; margin-top: 40px; font-size: 10px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="report-title">' . strtoupper($report_type) . ' REPORT</div>
                <div class="report-filters">
                    Generated on: ' . date('d/m/Y H:i:s') . '<br>';
                    
        if (isset($report_data['filters'])) {
            $filters = $report_data['filters'];
            if (!empty($filters['start_date'])) {
                $html .= 'From: ' . date('d/m/Y', strtotime($filters['start_date'])) . ' ';
            }
            if (!empty($filters['end_date'])) {
                $html .= 'To: ' . date('d/m/Y', strtotime($filters['end_date']));
            }
        }
        
        $html .= '
                </div>
            </div>';
            
        // Add report-specific content
        switch ($report_type) {
            case 'sales':
                $html .= $this->build_sales_report_content($report_data);
                break;
            case 'financial':
                $html .= $this->build_financial_report_content($report_data);
                break;
            case 'customer':
                $html .= $this->build_customer_report_content($report_data);
                break;
            case 'property':
                $html .= $this->build_property_report_content($report_data);
                break;
            default:
                $html .= $this->build_generic_report_content($report_data);
        }
        
        $html .= '
            <div class="footer">
                This report was generated automatically by the Real Estate Management System
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Build sales report content
     * @param array $report_data Report data
     * @return string HTML content
     */
    private function build_sales_report_content($report_data) {
        $html = '';
        
        if (isset($report_data['summary'])) {
            $summary = $report_data['summary'];
            $html .= '
            <div class="summary-section">
                <h3>Summary</h3>
                <div class="summary-row">
                    <span>Total Properties Sold:</span>
                    <span>' . (isset($summary['total_properties']) ? $summary['total_properties'] : 0) . '</span>
                </div>
                <div class="summary-row">
                    <span>Total Sales Amount:</span>
                    <span>₹ ' . number_format(isset($summary['total_sales']) ? $summary['total_sales'] : 0, 2) . '</span>
                </div>
            </div>';
        }
        
        if (isset($report_data['data']) && !empty($report_data['data'])) {
            $html .= '
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Property</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';
                
            foreach ($report_data['data'] as $row) {
                $html .= '
                <tr>
                    <td>' . date('d/m/Y', strtotime($row->sale_date ?? $row->created_at)) . '</td>
                    <td>' . htmlspecialchars($row->garden_name ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($row->plot_buyer_name ?? 'N/A') . '</td>
                    <td class="text-right">₹ ' . number_format($row->sale_amount ?? 0, 2) . '</td>
                    <td class="text-center">' . ucfirst($row->status ?? 'N/A') . '</td>
                </tr>';
            }
            
            $html .= '
                </tbody>
            </table>';
        }
        
        return $html;
    }
    
    /**
     * Build financial report content
     * @param array $report_data Report data
     * @return string HTML content
     */
    private function build_financial_report_content($report_data) {
        $html = '';
        
        if (isset($report_data['summary'])) {
            $summary = $report_data['summary'];
            $html .= '
            <div class="summary-section">
                <h3>Financial Summary</h3>
                <div class="summary-row">
                    <span>Total Transactions:</span>
                    <span>' . (isset($summary['total_transactions']) ? $summary['total_transactions'] : 0) . '</span>
                </div>
                <div class="summary-row">
                    <span>Total Revenue:</span>
                    <span>₹ ' . number_format(isset($summary['total_revenue']) ? $summary['total_revenue'] : 0, 2) . '</span>
                </div>
                <div class="summary-row">
                    <span>Average Transaction:</span>
                    <span>₹ ' . number_format(isset($summary['average_transaction']) ? $summary['average_transaction'] : 0, 2) . '</span>
                </div>
            </div>';
        }
        
        return $html;
    }
    
    /**
     * Build generic report content
     * @param array $report_data Report data
     * @return string HTML content
     */
    private function build_generic_report_content($report_data) {
        $html = '';
        
        if (isset($report_data['data']) && !empty($report_data['data'])) {
            $html .= '<table><tbody>';
            
            foreach ($report_data['data'] as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value);
                }
                $html .= '
                <tr>
                    <td><strong>' . htmlspecialchars($key) . '</strong></td>
                    <td>' . htmlspecialchars($value) . '</td>
                </tr>';
            }
            
            $html .= '</tbody></table>';
        }
        
        return $html;
    }
    
    /**
     * Convert HTML to PDF using basic HTML rendering
     * @param string $html HTML content
     * @param string $filename Base filename
     * @return string PDF content (simulated)
     */
    private function html_to_pdf($html, $filename) {
        // In a real implementation, this would use a library like TCPDF, mPDF, or wkhtmltopdf
        // For this implementation, we'll return the HTML content with PDF headers
        
        // Set PDF headers
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        
        // In a real implementation, convert HTML to PDF here
        // For now, we'll return HTML content that can be printed as PDF
        return $html;
    }
    
    /**
     * Save PDF to file
     * @param string $content PDF content
     * @param string $filepath File path to save
     * @return bool Success status
     */
    public function save_pdf($content, $filepath) {
        try {
            $directory = dirname($filepath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            return file_put_contents($filepath, $content) !== false;
        } catch (Exception $e) {
            log_message('error', 'Failed to save PDF: ' . $e->getMessage());
            return false;
        }
    }
}