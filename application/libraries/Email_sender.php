<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Email Sender Library
 * Handles sending emails with reports and receipts
 */
class Email_sender {
    
    private $CI;
    private $smtp_config;
    private $from_email;
    private $from_name;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('email');
        $this->initialize();
    }
    
    /**
     * Initialize email configuration
     */
    private function initialize() {
        // Load email configuration
        $this->CI->config->load('email_config', TRUE);
        $email_settings = $this->CI->config->item('email_settings', 'email_config');
        
        if ($email_settings) {
            // Extract SMTP configuration
            $this->smtp_config = array(
                'protocol' => $email_settings['protocol'],
                'smtp_host' => $email_settings['smtp_host'],
                'smtp_port' => $email_settings['smtp_port'],
                'smtp_user' => $email_settings['smtp_user'],
                'smtp_pass' => $email_settings['smtp_pass'],
                'smtp_crypto' => $email_settings['smtp_crypto'],
                'mailtype' => $email_settings['mailtype'],
                'charset' => $email_settings['charset'],
                'wordwrap' => $email_settings['wordwrap'],
                'newline' => $email_settings['newline']
            );
            
            $this->from_email = $email_settings['from_email'];
            $this->from_name = $email_settings['from_name'];
        } else {
            // Fallback configuration
            $this->smtp_config = array(
                'protocol' => 'smtp',
                'smtp_host' => 'localhost',
                'smtp_port' => 587,
                'smtp_user' => '',
                'smtp_pass' => '',
                'smtp_crypto' => 'tls',
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'wordwrap' => TRUE,
                'newline' => "\r\n"
            );
            
            $this->from_email = 'noreply@localhost.com';
            $this->from_name = 'Real Estate Management System';
        }
        
        // Initialize CodeIgniter email library
        $this->CI->email->initialize($this->smtp_config);
    }
    
    /**
     * Set SMTP configuration
     * @param array $config SMTP configuration
     */
    public function set_smtp_config($config) {
        $this->smtp_config = array_merge($this->smtp_config, $config);
        $this->CI->email->initialize($this->smtp_config);
    }
    
    /**
     * Set sender information
     * @param string $email Sender email
     * @param string $name Sender name
     */
    public function set_sender($email, $name) {
        $this->from_email = $email;
        $this->from_name = $name;
    }
    
    /**
     * Send receipt via email
     * @param array $receipt_data Receipt data
     * @param string $to_email Recipient email
     * @param string $to_name Recipient name
     * @return bool Success status
     */
    public function send_receipt($receipt_data, $to_email, $to_name = '') {
        try {
            $transaction = $receipt_data['transaction'];
            $balance_info = $receipt_data['balance_info'];
            
            // Prepare email
            $this->CI->email->clear();
            $this->CI->email->from($this->from_email, $this->from_name);
            $this->CI->email->to($to_email, $to_name);
            $this->CI->email->subject('Payment Receipt - ' . $transaction['receipt_number']);
            
            // Generate email body
            $email_body = $this->generate_receipt_email_body($receipt_data);
            $this->CI->email->message($email_body);
            
            // Generate PDF attachment if needed
            $this->CI->load->library('Pdf_generator');
            $pdf_content = $this->CI->pdf_generator->generate_receipt($receipt_data);
            
            // Attach PDF (in real implementation, save PDF first then attach)
            $pdf_filename = 'receipt_' . $transaction['receipt_number'] . '.pdf';
            $this->CI->email->attach($pdf_content, 'attachment', $pdf_filename, 'application/pdf');
            
            // Send email
            $result = $this->CI->email->send();
            
            if (!$result) {
                log_message('error', 'Failed to send receipt email: ' . $this->CI->email->print_debugger());
                return false;
            }
            
            return true;
            
        } catch (Exception $e) {
            log_message('error', 'Receipt email sending failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send report via email
     * @param array $report_data Report data
     * @param string $report_type Report type
     * @param string $to_email Recipient email
     * @param string $to_name Recipient name
     * @param array $attachments Additional attachments
     * @return bool Success status
     */
    public function send_report($report_data, $report_type, $to_email, $to_name = '', $attachments = array()) {
        try {
            // Prepare email
            $this->CI->email->clear();
            $this->CI->email->from($this->from_email, $this->from_name);
            $this->CI->email->to($to_email, $to_name);
            $this->CI->email->subject(ucfirst($report_type) . ' Report - ' . date('d/m/Y'));
            
            // Generate email body
            $email_body = $this->generate_report_email_body($report_data, $report_type);
            $this->CI->email->message($email_body);
            
            // Generate and attach PDF report
            $this->CI->load->library('Pdf_generator');
            $pdf_content = $this->CI->pdf_generator->generate_report($report_data, $report_type);
            $pdf_filename = $report_type . '_report_' . date('Y-m-d') . '.pdf';
            $this->CI->email->attach($pdf_content, 'attachment', $pdf_filename, 'application/pdf');
            
            // Generate and attach Excel report
            $this->CI->load->library('Excel_exporter');
            $excel_filename = $report_type . '_report_' . date('Y-m-d') . '.xls';
            
            // Save Excel file temporarily and attach
            $temp_excel_path = FCPATH . 'uploads/temp/' . $excel_filename;
            $this->generate_excel_report($report_data, $report_type, $temp_excel_path);
            
            if (file_exists($temp_excel_path)) {
                $this->CI->email->attach($temp_excel_path);
                // Clean up temp file after sending
                register_shutdown_function(function() use ($temp_excel_path) {
                    if (file_exists($temp_excel_path)) {
                        unlink($temp_excel_path);
                    }
                });
            }
            
            // Add additional attachments
            foreach ($attachments as $attachment) {
                if (isset($attachment['path']) && file_exists($attachment['path'])) {
                    $this->CI->email->attach($attachment['path']);
                }
            }
            
            // Send email
            $result = $this->CI->email->send();
            
            if (!$result) {
                log_message('error', 'Failed to send report email: ' . $this->CI->email->print_debugger());
                return false;
            }
            
            return true;
            
        } catch (Exception $e) {
            log_message('error', 'Report email sending failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send bulk emails to multiple recipients
     * @param array $recipients Array of recipient emails
     * @param string $subject Email subject
     * @param string $message Email message
     * @param array $attachments Attachments
     * @return array Results array with success/failure status
     */
    public function send_bulk_email($recipients, $subject, $message, $attachments = array()) {
        $results = array();
        
        foreach ($recipients as $recipient) {
            try {
                $this->CI->email->clear();
                $this->CI->email->from($this->from_email, $this->from_name);
                
                if (is_array($recipient)) {
                    $this->CI->email->to($recipient['email'], $recipient['name'] ?? '');
                    $recipient_key = $recipient['email'];
                } else {
                    $this->CI->email->to($recipient);
                    $recipient_key = $recipient;
                }
                
                $this->CI->email->subject($subject);
                $this->CI->email->message($message);
                
                // Add attachments
                foreach ($attachments as $attachment) {
                    if (file_exists($attachment)) {
                        $this->CI->email->attach($attachment);
                    }
                }
                
                $result = $this->CI->email->send();
                $results[$recipient_key] = $result;
                
                if (!$result) {
                    log_message('error', 'Failed to send bulk email to ' . $recipient_key . ': ' . $this->CI->email->print_debugger());
                }
                
            } catch (Exception $e) {
                log_message('error', 'Bulk email sending failed for ' . $recipient_key . ': ' . $e->getMessage());
                $results[$recipient_key] = false;
            }
        }
        
        return $results;
    }
    
    /**
     * Generate receipt email body
     * @param array $receipt_data Receipt data
     * @return string Email HTML body
     */
    private function generate_receipt_email_body($receipt_data) {
        $transaction = $receipt_data['transaction'];
        $balance_info = $receipt_data['balance_info'];
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Payment Receipt</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-radius: 5px; }
                .content { padding: 20px 0; }
                .receipt-details { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .amount-section { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; margin-top: 30px; }
                table { width: 100%; border-collapse: collapse; }
                td { padding: 8px 0; }
                .label { font-weight: bold; width: 40%; }
                .value { width: 60%; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Payment Receipt</h2>
                    <p>Receipt Number: ' . htmlspecialchars($transaction['receipt_number']) . '</p>
                </div>
                
                <div class="content">
                    <p>Dear ' . htmlspecialchars($transaction['plot_buyer_name']) . ',</p>
                    <p>Thank you for your payment. Please find your receipt details below:</p>
                    
                    <div class="receipt-details">
                        <table>
                            <tr>
                                <td class="label">Payment Date:</td>
                                <td class="value">' . date('d/m/Y', strtotime($transaction['payment_date'])) . '</td>
                            </tr>
                            <tr>
                                <td class="label">Property:</td>
                                <td class="value">' . htmlspecialchars($transaction['garden_name']) . '</td>
                            </tr>
                            <tr>
                                <td class="label">Registration No:</td>
                                <td class="value">' . htmlspecialchars($transaction['registration_number']) . '</td>
                            </tr>
                            <tr>
                                <td class="label">Payment Method:</td>
                                <td class="value">' . ucfirst(htmlspecialchars($transaction['payment_method'])) . '</td>
                            </tr>
                            <tr>
                                <td class="label">Payment Type:</td>
                                <td class="value">' . ucfirst(str_replace('_', ' ', htmlspecialchars($transaction['payment_type']))) . '</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="amount-section">
                        <table>
                            <tr>
                                <td class="label">Payment Amount:</td>
                                <td class="value">₹ ' . number_format($transaction['amount'], 2) . '</td>
                            </tr>
                            <tr>
                                <td class="label">Total Amount:</td>
                                <td class="value">₹ ' . number_format($balance_info['total_amount'], 2) . '</td>
                            </tr>
                            <tr>
                                <td class="label">Total Paid:</td>
                                <td class="value">₹ ' . number_format($balance_info['total_paid'], 2) . '</td>
                            </tr>
                            <tr style="border-top: 2px solid #333;">
                                <td class="label"><strong>Balance Due:</strong></td>
                                <td class="value"><strong>₹ ' . number_format($balance_info['balance'], 2) . '</strong></td>
                            </tr>
                        </table>
                    </div>';
                    
        if (!empty($transaction['notes'])) {
            $html .= '
                    <div style="margin: 20px 0;">
                        <strong>Notes:</strong><br>
                        ' . nl2br(htmlspecialchars($transaction['notes'])) . '
                    </div>';
        }
        
        $html .= '
                    <p>A detailed PDF receipt is attached to this email for your records.</p>
                    <p>If you have any questions regarding this payment, please contact us.</p>
                </div>
                
                <div class="footer">
                    <p>This is an automated email. Please do not reply to this email.</p>
                    <p>© ' . date('Y') . ' Real Estate Management System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Generate report email body
     * @param array $report_data Report data
     * @param string $report_type Report type
     * @return string Email HTML body
     */
    private function generate_report_email_body($report_data, $report_type) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . ucfirst($report_type) . ' Report</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-radius: 5px; }
                .content { padding: 20px 0; }
                .summary { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; margin-top: 30px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>' . ucfirst($report_type) . ' Report</h2>
                    <p>Generated on: ' . date('d/m/Y H:i:s') . '</p>
                </div>
                
                <div class="content">
                    <p>Dear User,</p>
                    <p>Please find the ' . $report_type . ' report attached to this email.</p>';
                    
        // Add summary based on report type
        if (isset($report_data['summary'])) {
            $summary = $report_data['summary'];
            $html .= '<div class="summary"><h3>Report Summary</h3>';
            
            switch ($report_type) {
                case 'sales':
                    $html .= '
                    <p><strong>Total Properties Sold:</strong> ' . (isset($summary['total_properties']) ? $summary['total_properties'] : 0) . '</p>
                    <p><strong>Total Sales Amount:</strong> ₹ ' . number_format(isset($summary['total_sales']) ? $summary['total_sales'] : 0, 2) . '</p>';
                    break;
                case 'financial':
                    $html .= '
                    <p><strong>Total Transactions:</strong> ' . (isset($summary['total_transactions']) ? $summary['total_transactions'] : 0) . '</p>
                    <p><strong>Total Revenue:</strong> ₹ ' . number_format(isset($summary['total_revenue']) ? $summary['total_revenue'] : 0, 2) . '</p>';
                    break;
            }
            
            $html .= '</div>';
        }
        
        $html .= '
                    <p>The report is available in both PDF and Excel formats for your convenience.</p>
                    <p>If you have any questions regarding this report, please contact us.</p>
                </div>
                
                <div class="footer">
                    <p>This is an automated email. Please do not reply to this email.</p>
                    <p>© ' . date('Y') . ' Real Estate Management System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Generate Excel report for email attachment
     * @param array $report_data Report data
     * @param string $report_type Report type
     * @param string $filepath File path to save
     * @return bool Success status
     */
    private function generate_excel_report($report_data, $report_type, $filepath) {
        try {
            $this->CI->load->library('Excel_exporter');
            
            switch ($report_type) {
                case 'sales':
                    return $this->CI->excel_exporter->export_sales_report($report_data['data'] ?? array());
                case 'financial':
                    return $this->CI->excel_exporter->export_financial_report($report_data);
                case 'customer':
                    return $this->CI->excel_exporter->export_customer_analytics($report_data);
                case 'property':
                    return $this->CI->excel_exporter->export_property_performance($report_data['data'] ?? array());
                case 'staff':
                    return $this->CI->excel_exporter->export_staff_performance($report_data['data'] ?? array());
                default:
                    return false;
            }
            
        } catch (Exception $e) {
            log_message('error', 'Failed to generate Excel report for email: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send payment reminder emails
     * @param array $overdue_payments Overdue payment data
     * @return array Results
     */
    public function send_payment_reminders($overdue_payments) {
        $results = array();
        
        foreach ($overdue_payments as $payment) {
            try {
                $this->CI->email->clear();
                $this->CI->email->from($this->from_email, $this->from_name);
                $this->CI->email->to($payment['customer_email'], $payment['plot_buyer_name']);
                $this->CI->email->subject('Payment Reminder - ' . $payment['registration_number']);
                
                $email_body = $this->generate_payment_reminder_body($payment);
                $this->CI->email->message($email_body);
                
                $result = $this->CI->email->send();
                $results[$payment['registration_number']] = $result;
                
            } catch (Exception $e) {
                log_message('error', 'Payment reminder email failed: ' . $e->getMessage());
                $results[$payment['registration_number']] = false;
            }
        }
        
        return $results;
    }
    
    /**
     * Generate payment reminder email body
     * @param array $payment_data Payment data
     * @return string Email HTML body
     */
    private function generate_payment_reminder_body($payment_data) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Payment Reminder</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #fff3cd; padding: 20px; text-align: center; border-radius: 5px; border: 1px solid #ffeaa7; }
                .content { padding: 20px 0; }
                .payment-details { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; margin-top: 30px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Payment Reminder</h2>
                    <p>Registration: ' . htmlspecialchars($payment_data['registration_number']) . '</p>
                </div>
                
                <div class="content">
                    <p>Dear ' . htmlspecialchars($payment_data['plot_buyer_name']) . ',</p>
                    <p>This is a friendly reminder that your payment is due for the following property:</p>
                    
                    <div class="payment-details">
                        <p><strong>Property:</strong> ' . htmlspecialchars($payment_data['garden_name']) . '</p>
                        <p><strong>Due Date:</strong> ' . date('d/m/Y', strtotime($payment_data['due_date'])) . '</p>
                        <p><strong>Amount Due:</strong> ₹ ' . number_format($payment_data['amount'], 2) . '</p>
                        <p><strong>Days Overdue:</strong> ' . (int)((time() - strtotime($payment_data['due_date'])) / (60 * 60 * 24)) . ' days</p>
                    </div>
                    
                    <p>Please make the payment at your earliest convenience to avoid any late fees.</p>
                    <p>If you have already made the payment, please ignore this reminder.</p>
                    <p>For any queries, please contact us.</p>
                </div>
                
                <div class="footer">
                    <p>© ' . date('Y') . ' Real Estate Management System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}