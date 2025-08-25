<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Error Handler Controller
 * Centralized error handling and logging for the application
 * Requirements: 7.1, 7.4, 7.7
 */
class Error_handler extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(['database_error_handler', 'audit_logger']);
        $this->load->helper('url');
    }

    /**
     * Handle application errors
     * @param int $status_code HTTP status code
     * @param string $heading Error heading
     * @param string $message Error message
     * @param string $template Error template
     * @param int $log_error Whether to log the error
     */
    public function show_error($status_code = 500, $heading = 'An Error Was Encountered', $message = 'An error occurred while processing your request.', $template = 'error_general', $log_error = TRUE) {
        
        // Log the error if requested
        if ($log_error) {
            $this->log_application_error($status_code, $heading, $message);
        }
        
        // Set appropriate HTTP status code
        $this->output->set_status_header($status_code);
        
        // Prepare error data for view
        $error_data = array(
            'status_code' => $status_code,
            'heading' => $heading,
            'message' => $this->sanitize_error_message($message),
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => $this->generate_request_id()
        );
        
        // Check if this is an AJAX request
        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'success' => false,
                    'error' => array(
                        'code' => $status_code,
                        'message' => $error_data['message'],
                        'request_id' => $error_data['request_id']
                    )
                )));
            return;
        }
        
        // Load appropriate error view
        $this->load->view('errors/html/' . $template, $error_data);
    }

    /**
     * Handle 404 errors
     */
    public function show_404($page = '', $log_error = TRUE) {
        $heading = "404 Page Not Found";
        $message = "The page you requested was not found.";
        
        if ($page) {
            $message .= " Page: " . htmlspecialchars($page);
        }
        
        $this->show_error(404, $heading, $message, 'error_404', $log_error);
    }

    /**
     * Handle database errors
     * @param string $operation Operation that failed
     * @param array $context Additional context
     */
    public function handle_database_error($operation = 'database operation', $context = array()) {
        $db_error = $this->db->error();
        
        $error_response = $this->database_error_handler->handle_error(
            $db_error,
            $operation,
            $context
        );
        
        // Log to audit trail
        $this->audit_logger->log_system_event(
            'database_error',
            $error_response['message'],
            array_merge($context, array(
                'error_code' => $error_response['error_code'],
                'operation' => $operation
            ))
        );
        
        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($error_response));
        } else {
            $this->show_error(
                500,
                'Database Error',
                $error_response['message'],
                'error_database'
            );
        }
    }

    /**
     * Handle validation errors
     * @param array $validation_errors Validation errors
     * @param string $form_name Form name for context
     */
    public function handle_validation_errors($validation_errors, $form_name = 'form') {
        $error_response = array(
            'success' => false,
            'error_type' => 'validation_error',
            'message' => 'Please correct the following errors:',
            'errors' => $validation_errors,
            'form_name' => $form_name,
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        // Log validation errors
        $this->audit_logger->log_system_event(
            'validation_error',
            'Form validation failed for ' . $form_name,
            array(
                'form_name' => $form_name,
                'errors' => $validation_errors
            )
        );
        
        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($error_response));
        } else {
            // Redirect back with errors in session
            $this->session->set_flashdata('validation_errors', $validation_errors);
            $this->session->set_flashdata('error_message', $error_response['message']);
            redirect($this->agent->referrer());
        }
    }

    /**
     * Handle file upload errors
     * @param array $upload_errors Upload errors
     * @param string $field_name Field name
     */
    public function handle_upload_errors($upload_errors, $field_name = 'file') {
        $error_response = array(
            'success' => false,
            'error_type' => 'upload_error',
            'message' => 'File upload failed',
            'errors' => $upload_errors,
            'field_name' => $field_name,
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        // Log upload errors
        $this->audit_logger->log_system_event(
            'upload_error',
            'File upload failed for field: ' . $field_name,
            array(
                'field_name' => $field_name,
                'errors' => $upload_errors,
                'file_info' => isset($_FILES[$field_name]) ? $_FILES[$field_name] : null
            )
        );
        
        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($error_response));
        } else {
            $this->session->set_flashdata('upload_errors', $upload_errors);
            $this->session->set_flashdata('error_message', $error_response['message']);
            redirect($this->agent->referrer());
        }
    }

    /**
     * Handle permission/authorization errors
     * @param string $action Action attempted
     * @param string $resource Resource accessed
     */
    public function handle_permission_error($action = 'access', $resource = 'resource') {
        $message = "You don't have permission to {$action} this {$resource}.";
        
        // Log permission error
        $this->audit_logger->log_system_event(
            'permission_denied',
            $message,
            array(
                'action' => $action,
                'resource' => $resource,
                'user_id' => $this->get_current_user_id(),
                'request_uri' => $this->input->server('REQUEST_URI')
            )
        );
        
        $this->show_error(403, 'Access Denied', $message, 'error_403');
    }

    /**
     * Handle session timeout errors
     */
    public function handle_session_timeout() {
        $message = "Your session has expired. Please log in again.";
        
        // Log session timeout
        $this->audit_logger->log_auth_event(
            'session_timeout',
            $this->get_current_user_id(),
            array('request_uri' => $this->input->server('REQUEST_URI'))
        );
        
        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_type' => 'session_timeout',
                    'message' => $message,
                    'redirect' => base_url('login')
                )));
        } else {
            $this->session->set_flashdata('error_message', $message);
            redirect('login');
        }
    }

    /**
     * Handle CSRF token errors
     */
    public function handle_csrf_error() {
        $message = "Security token mismatch. Please refresh the page and try again.";
        
        // Log CSRF error
        $this->audit_logger->log_system_event(
            'csrf_error',
            'CSRF token validation failed',
            array(
                'user_id' => $this->get_current_user_id(),
                'request_uri' => $this->input->server('REQUEST_URI'),
                'request_method' => $this->input->server('REQUEST_METHOD')
            )
        );
        
        $this->show_error(403, 'Security Error', $message, 'error_csrf');
    }

    /**
     * Log application errors
     * @param int $status_code HTTP status code
     * @param string $heading Error heading
     * @param string $message Error message
     */
    protected function log_application_error($status_code, $heading, $message) {
        $error_data = array(
            'status_code' => $status_code,
            'heading' => $heading,
            'message' => $message,
            'request_uri' => $this->input->server('REQUEST_URI'),
            'request_method' => $this->input->server('REQUEST_METHOD'),
            'user_agent' => $this->input->server('HTTP_USER_AGENT'),
            'ip_address' => $this->input->ip_address(),
            'user_id' => $this->get_current_user_id(),
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        // Log to file
        $log_message = 'APPLICATION ERROR: ' . json_encode($error_data);
        error_log($log_message);
        
        // Log to audit trail
        $this->audit_logger->log_system_event(
            'application_error',
            "{$heading}: {$message}",
            $error_data
        );
    }

    /**
     * Sanitize error message for display
     * @param string $message Raw error message
     * @return string Sanitized message
     */
    protected function sanitize_error_message($message) {
        // Remove sensitive information from error messages
        $sensitive_patterns = array(
            '/password/i',
            '/token/i',
            '/secret/i',
            '/key/i',
            '/database/i',
            '/mysql/i',
            '/sql/i'
        );
        
        $safe_replacements = array(
            '[REDACTED]',
            '[REDACTED]',
            '[REDACTED]',
            '[REDACTED]',
            'data store',
            'data store',
            'query'
        );
        
        $sanitized = preg_replace($sensitive_patterns, $safe_replacements, $message);
        
        // HTML encode for safety
        return htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate unique request ID for error tracking
     * @return string Request ID
     */
    protected function generate_request_id() {
        return uniqid('req_', true);
    }

    /**
     * Get current user ID (placeholder - implement based on your auth system)
     * @return int|null User ID
     */
    protected function get_current_user_id() {
        // Implement based on your authentication system
        return null;
    }

    /**
     * Test error handling (for development/testing)
     */
    public function test_error($type = 'general') {
        if (ENVIRONMENT !== 'development') {
            show_404();
            return;
        }
        
        switch ($type) {
            case 'database':
                // Trigger database error
                $this->db->query('SELECT * FROM non_existent_table');
                break;
                
            case 'validation':
                $errors = array(
                    'name' => 'Name is required',
                    'email' => 'Invalid email format'
                );
                $this->handle_validation_errors($errors, 'test_form');
                break;
                
            case 'permission':
                $this->handle_permission_error('delete', 'test resource');
                break;
                
            case 'csrf':
                $this->handle_csrf_error();
                break;
                
            case '404':
                $this->show_404('test-page');
                break;
                
            default:
                $this->show_error(500, 'Test Error', 'This is a test error message.');
                break;
        }
    }

    /**
     * Handle 404 page not found errors (route handler)
     */
    public function page_not_found() {
        $this->show_404();
    }

    /**
     * Handle 500 server errors (route handler)
     */
    public function server_error() {
        $this->show_error(500, 'Internal Server Error', 'The server encountered an internal error and was unable to complete your request.');
    }

    /**
     * Handle 403 access denied errors (route handler)
     */
    public function access_denied() {
        $this->show_error(403, 'Access Denied', 'You do not have permission to access this resource.');
    }

    /**
     * Get error statistics for dashboard
     * @param array $filters Optional filters
     * @return array Error statistics
     */
    public function get_error_statistics($filters = array()) {
        try {
            $stats = array();
            
            // Get error counts from audit logs
            $this->db->select('action, COUNT(*) as count');
            $this->db->from('audit_logs');
            $this->db->where_in('action', array(
                'application_error',
                'database_error',
                'validation_error',
                'upload_error',
                'permission_denied',
                'csrf_error'
            ));
            
            // Apply date filters if provided
            if (!empty($filters['date_from'])) {
                $this->db->where('created_at >=', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $this->db->where('created_at <=', $filters['date_to'] . ' 23:59:59');
            }
            
            $this->db->group_by('action');
            $this->db->order_by('count', 'DESC');
            
            $error_counts = $this->db->get()->result_array();
            $stats['error_counts'] = $error_counts;
            
            // Get recent errors
            $this->db->select('*');
            $this->db->from('audit_logs');
            $this->db->where_in('action', array(
                'application_error',
                'database_error',
                'validation_error',
                'upload_error',
                'permission_denied',
                'csrf_error'
            ));
            $this->db->order_by('created_at', 'DESC');
            $this->db->limit(10);
            
            $recent_errors = $this->db->get()->result_array();
            $stats['recent_errors'] = $recent_errors;
            
            return $stats;
            
        } catch (Exception $e) {
            error_log('Failed to get error statistics: ' . $e->getMessage());
            return array();
        }
    }
}