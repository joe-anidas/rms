<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Audit Logger Library
 * Provides comprehensive audit logging for all data modifications with user tracking
 * Requirements: 7.1, 7.4, 7.7
 */
class Audit_logger {
    
    protected $CI;
    protected $enabled = true;
    protected $log_to_database = true;
    protected $log_to_file = true;
    protected $sensitive_fields = array('password', 'token', 'secret', 'key');
    
    public function __construct($config = array()) {
        $this->CI =& get_instance();
        
        // Override default config
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        // Ensure audit_logs table exists
        if ($this->log_to_database) {
            $this->ensure_audit_table();
        }
    }
    
    /**
     * Log data modification with full audit trail
     * @param string $table_name Table name
     * @param int $record_id Record ID
     * @param string $action Action performed (insert, update, delete)
     * @param array $old_values Old values (for updates/deletes)
     * @param array $new_values New values (for inserts/updates)
     * @param int $user_id User ID performing the action
     * @param array $additional_context Additional context information
     * @return bool Success status
     */
    public function log_data_change($table_name, $record_id, $action, $old_values = null, $new_values = null, $user_id = null, $additional_context = array()) {
        if (!$this->enabled) {
            return true;
        }
        
        try {
            // Sanitize sensitive data
            $old_values = $this->sanitize_sensitive_data($old_values);
            $new_values = $this->sanitize_sensitive_data($new_values);
            
            // Prepare audit log entry
            $audit_data = array(
                'table_name' => $table_name,
                'record_id' => $record_id,
                'action' => strtolower($action),
                'old_values' => $old_values ? json_encode($old_values) : null,
                'new_values' => $new_values ? json_encode($new_values) : null,
                'user_id' => $user_id ?: $this->get_current_user_id(),
                'ip_address' => $this->CI->input->ip_address(),
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
                'request_uri' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '',
                'request_method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '',
                'additional_context' => !empty($additional_context) ? json_encode($additional_context) : null,
                'created_at' => date('Y-m-d H:i:s')
            );
            
            // Log to database
            if ($this->log_to_database) {
                $this->log_to_database_table($audit_data);
            }
            
            // Log to file
            if ($this->log_to_file) {
                $this->log_to_audit_file($audit_data);
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log('Audit logging failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Log user authentication events
     * @param string $event Event type (login, logout, failed_login, etc.)
     * @param int $user_id User ID
     * @param array $context Additional context
     * @return bool Success status
     */
    public function log_auth_event($event, $user_id = null, $context = array()) {
        return $this->log_data_change(
            'user_authentication',
            $user_id ?: 0,
            $event,
            null,
            array('event' => $event, 'context' => $context),
            $user_id,
            $context
        );
    }
    
    /**
     * Log system events
     * @param string $event Event type
     * @param string $description Event description
     * @param array $context Additional context
     * @return bool Success status
     */
    public function log_system_event($event, $description, $context = array()) {
        return $this->log_data_change(
            'system_events',
            0,
            $event,
            null,
            array('description' => $description, 'context' => $context),
            $this->get_current_user_id(),
            $context
        );
    }
    
    /**
     * Log property-related changes
     * @param int $property_id Property ID
     * @param string $action Action performed
     * @param array $old_values Old values
     * @param array $new_values New values
     * @param array $context Additional context
     * @return bool Success status
     */
    public function log_property_change($property_id, $action, $old_values = null, $new_values = null, $context = array()) {
        return $this->log_data_change(
            'properties',
            $property_id,
            $action,
            $old_values,
            $new_values,
            $this->get_current_user_id(),
            $context
        );
    }
    
    /**
     * Log customer-related changes
     * @param int $customer_id Customer ID
     * @param string $action Action performed
     * @param array $old_values Old values
     * @param array $new_values New values
     * @param array $context Additional context
     * @return bool Success status
     */
    public function log_customer_change($customer_id, $action, $old_values = null, $new_values = null, $context = array()) {
        return $this->log_data_change(
            'customers',
            $customer_id,
            $action,
            $old_values,
            $new_values,
            $this->get_current_user_id(),
            $context
        );
    }
    
    /**
     * Log transaction-related changes
     * @param int $transaction_id Transaction ID
     * @param string $action Action performed
     * @param array $old_values Old values
     * @param array $new_values New values
     * @param array $context Additional context
     * @return bool Success status
     */
    public function log_transaction_change($transaction_id, $action, $old_values = null, $new_values = null, $context = array()) {
        return $this->log_data_change(
            'transactions',
            $transaction_id,
            $action,
            $old_values,
            $new_values,
            $this->get_current_user_id(),
            $context
        );
    }
    
    /**
     * Log staff-related changes
     * @param int $staff_id Staff ID
     * @param string $action Action performed
     * @param array $old_values Old values
     * @param array $new_values New values
     * @param array $context Additional context
     * @return bool Success status
     */
    public function log_staff_change($staff_id, $action, $old_values = null, $new_values = null, $context = array()) {
        return $this->log_data_change(
            'staff',
            $staff_id,
            $action,
            $old_values,
            $new_values,
            $this->get_current_user_id(),
            $context
        );
    }
    
    /**
     * Get audit trail for a specific record
     * @param string $table_name Table name
     * @param int $record_id Record ID
     * @param int $limit Limit number of results
     * @return array Audit trail
     */
    public function get_audit_trail($table_name, $record_id, $limit = 50) {
        try {
            if (!$this->CI->db->table_exists('audit_logs')) {
                return array();
            }
            
            $this->CI->db->select('*');
            $this->CI->db->from('audit_logs');
            $this->CI->db->where('table_name', $table_name);
            $this->CI->db->where('record_id', $record_id);
            $this->CI->db->order_by('created_at', 'DESC');
            $this->CI->db->limit($limit);
            
            $result = $this->CI->db->get();
            
            if ($result->num_rows() > 0) {
                $audit_trail = $result->result_array();
                
                // Decode JSON fields
                foreach ($audit_trail as &$entry) {
                    if (!empty($entry['old_values'])) {
                        $entry['old_values'] = json_decode($entry['old_values'], true);
                    }
                    if (!empty($entry['new_values'])) {
                        $entry['new_values'] = json_decode($entry['new_values'], true);
                    }
                    if (!empty($entry['additional_context'])) {
                        $entry['additional_context'] = json_decode($entry['additional_context'], true);
                    }
                }
                
                return $audit_trail;
            }
            
            return array();
            
        } catch (Exception $e) {
            error_log('Failed to get audit trail: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * Get audit summary for dashboard
     * @param array $filters Optional filters (date_from, date_to, table_name, action, user_id)
     * @return array Audit summary
     */
    public function get_audit_summary($filters = array()) {
        try {
            if (!$this->CI->db->table_exists('audit_logs')) {
                return array();
            }
            
            $summary = array();
            
            // Apply filters
            $this->CI->db->from('audit_logs');
            $this->apply_audit_filters($filters);
            
            // Total audit entries
            $summary['total_entries'] = $this->CI->db->count_all_results('audit_logs');
            
            // Reset query
            $this->CI->db->reset_query();
            
            // Entries by action
            $this->CI->db->select('action, COUNT(*) as count');
            $this->CI->db->from('audit_logs');
            $this->apply_audit_filters($filters);
            $this->CI->db->group_by('action');
            $this->CI->db->order_by('count', 'DESC');
            
            $actions = $this->CI->db->get()->result_array();
            $summary['by_action'] = $actions;
            
            // Reset query
            $this->CI->db->reset_query();
            
            // Entries by table
            $this->CI->db->select('table_name, COUNT(*) as count');
            $this->CI->db->from('audit_logs');
            $this->apply_audit_filters($filters);
            $this->CI->db->group_by('table_name');
            $this->CI->db->order_by('count', 'DESC');
            
            $tables = $this->CI->db->get()->result_array();
            $summary['by_table'] = $tables;
            
            // Reset query
            $this->CI->db->reset_query();
            
            // Recent activity
            $this->CI->db->select('*');
            $this->CI->db->from('audit_logs');
            $this->apply_audit_filters($filters);
            $this->CI->db->order_by('created_at', 'DESC');
            $this->CI->db->limit(10);
            
            $recent = $this->CI->db->get()->result_array();
            $summary['recent_activity'] = $recent;
            
            return $summary;
            
        } catch (Exception $e) {
            error_log('Failed to get audit summary: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * Apply filters to audit query
     * @param array $filters Filters to apply
     */
    protected function apply_audit_filters($filters) {
        if (!empty($filters['date_from'])) {
            $this->CI->db->where('created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->CI->db->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        if (!empty($filters['table_name'])) {
            $this->CI->db->where('table_name', $filters['table_name']);
        }
        
        if (!empty($filters['action'])) {
            $this->CI->db->where('action', $filters['action']);
        }
        
        if (!empty($filters['user_id'])) {
            $this->CI->db->where('user_id', $filters['user_id']);
        }
    }
    
    /**
     * Sanitize sensitive data before logging
     * @param array $data Data to sanitize
     * @return array Sanitized data
     */
    protected function sanitize_sensitive_data($data) {
        if (!is_array($data)) {
            return $data;
        }
        
        $sanitized = $data;
        
        foreach ($this->sensitive_fields as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = '[REDACTED]';
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Log to database audit_logs table
     * @param array $audit_data Audit data
     */
    protected function log_to_database_table($audit_data) {
        try {
            $this->CI->db->insert('audit_logs', $audit_data);
        } catch (Exception $e) {
            error_log('Failed to log to audit_logs table: ' . $e->getMessage());
        }
    }
    
    /**
     * Log to audit file
     * @param array $audit_data Audit data
     */
    protected function log_to_audit_file($audit_data) {
        try {
            $log_message = 'AUDIT: ' . json_encode($audit_data);
            error_log($log_message);
            
            // Also log to dedicated audit file if configured
            $audit_file = APPPATH . 'logs/audit_' . date('Y-m-d') . '.log';
            
            // Ensure logs directory exists
            $logs_dir = dirname($audit_file);
            if (!is_dir($logs_dir)) {
                mkdir($logs_dir, 0755, true);
            }
            
            $formatted_message = date('Y-m-d H:i:s') . ' - ' . json_encode($audit_data) . PHP_EOL;
            file_put_contents($audit_file, $formatted_message, FILE_APPEND | LOCK_EX);
            
        } catch (Exception $e) {
            error_log('Failed to log to audit file: ' . $e->getMessage());
        }
    }
    
    /**
     * Ensure audit_logs table exists
     */
    protected function ensure_audit_table() {
        try {
            if (!$this->CI->db->table_exists('audit_logs')) {
                $this->create_audit_table();
            }
        } catch (Exception $e) {
            error_log('Failed to ensure audit table: ' . $e->getMessage());
        }
    }
    
    /**
     * Create audit_logs table
     */
    protected function create_audit_table() {
        $sql = "
            CREATE TABLE IF NOT EXISTS `audit_logs` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `table_name` varchar(100) NOT NULL,
                `record_id` int(11) NOT NULL DEFAULT 0,
                `action` varchar(50) NOT NULL,
                `old_values` longtext,
                `new_values` longtext,
                `user_id` int(11) DEFAULT NULL,
                `ip_address` varchar(45) DEFAULT NULL,
                `user_agent` text,
                `request_uri` varchar(500) DEFAULT NULL,
                `request_method` varchar(10) DEFAULT NULL,
                `additional_context` text,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_table_record` (`table_name`, `record_id`),
                KEY `idx_action` (`action`),
                KEY `idx_user_id` (`user_id`),
                KEY `idx_created_at` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $this->CI->db->query($sql);
    }
    
    /**
     * Get current user ID (placeholder - implement based on your auth system)
     * @return int|null User ID
     */
    protected function get_current_user_id() {
        // Implement based on your authentication system
        // For now, return a default value or null
        return 1; // Default admin user
    }
    
    /**
     * Clean old audit logs (for maintenance)
     * @param int $days_to_keep Number of days to keep logs
     * @return int Number of deleted records
     */
    public function clean_old_logs($days_to_keep = 365) {
        try {
            if (!$this->CI->db->table_exists('audit_logs')) {
                return 0;
            }
            
            $cutoff_date = date('Y-m-d H:i:s', strtotime("-{$days_to_keep} days"));
            
            $this->CI->db->where('created_at <', $cutoff_date);
            $this->CI->db->delete('audit_logs');
            
            $deleted_count = $this->CI->db->affected_rows();
            
            // Log the cleanup activity
            $this->log_system_event(
                'audit_cleanup',
                "Cleaned {$deleted_count} audit log entries older than {$days_to_keep} days",
                array('cutoff_date' => $cutoff_date, 'deleted_count' => $deleted_count)
            );
            
            return $deleted_count;
            
        } catch (Exception $e) {
            error_log('Failed to clean old audit logs: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Export audit logs to CSV
     * @param array $filters Optional filters
     * @param string $filename Output filename
     * @return bool Success status
     */
    public function export_audit_logs($filters = array(), $filename = null) {
        try {
            if (!$filename) {
                $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
            }
            
            // Get audit logs with filters
            $this->CI->db->select('*');
            $this->CI->db->from('audit_logs');
            $this->apply_audit_filters($filters);
            $this->CI->db->order_by('created_at', 'DESC');
            
            $result = $this->CI->db->get();
            
            if ($result->num_rows() === 0) {
                return false;
            }
            
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($output, array(
                'ID', 'Table Name', 'Record ID', 'Action', 'Old Values', 'New Values',
                'User ID', 'IP Address', 'User Agent', 'Request URI', 'Request Method',
                'Additional Context', 'Created At'
            ));
            
            // CSV data
            foreach ($result->result_array() as $row) {
                fputcsv($output, $row);
            }
            
            fclose($output);
            
            return true;
            
        } catch (Exception $e) {
            error_log('Failed to export audit logs: ' . $e->getMessage());
            return false;
        }
    }
}