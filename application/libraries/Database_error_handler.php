<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Database Error Handler Library
 * Provides comprehensive database error handling with user-friendly messages and logging
 * Requirements: 7.1, 7.4
 */
class Database_error_handler {
    
    protected $CI;
    protected $log_errors = true;
    protected $show_detailed_errors = false;
    
    public function __construct($config = array()) {
        $this->CI =& get_instance();
        
        if (isset($config['log_errors'])) {
            $this->log_errors = $config['log_errors'];
        }
        
        if (isset($config['show_detailed_errors'])) {
            $this->show_detailed_errors = $config['show_detailed_errors'];
        }
    }
    
    /**
     * Handle database errors with user-friendly messages
     * @param array $db_error Database error array
     * @param string $operation Operation being performed
     * @param array $context Additional context information
     * @return array Formatted error response
     */
    public function handle_error($db_error, $operation = 'database operation', $context = array()) {
        $error_code = isset($db_error['code']) ? $db_error['code'] : 0;
        $error_message = isset($db_error['message']) ? $db_error['message'] : 'Unknown database error';
        
        // Log the detailed error
        if ($this->log_errors) {
            $this->log_database_error($db_error, $operation, $context);
        }
        
        // Generate user-friendly message
        $user_message = $this->get_user_friendly_message($error_code, $error_message, $operation);
        
        return array(
            'success' => false,
            'error_type' => 'database_error',
            'error_code' => $error_code,
            'message' => $user_message,
            'detailed_message' => $this->show_detailed_errors ? $error_message : null,
            'operation' => $operation,
            'timestamp' => date('Y-m-d H:i:s')
        );
    }
    
    /**
     * Get user-friendly error message based on error code
     * @param int $error_code MySQL error code
     * @param string $error_message Original error message
     * @param string $operation Operation being performed
     * @return string User-friendly message
     */
    protected function get_user_friendly_message($error_code, $error_message, $operation) {
        switch ($error_code) {
            case 1062: // Duplicate entry
                if (strpos($error_message, 'PRIMARY') !== false) {
                    return 'A record with this ID already exists.';
                } elseif (strpos($error_message, 'email') !== false) {
                    return 'This email address is already registered.';
                } elseif (strpos($error_message, 'phone') !== false) {
                    return 'This phone number is already registered.';
                } elseif (strpos($error_message, 'registration_number') !== false) {
                    return 'This registration number already exists.';
                } else {
                    return 'This record already exists in the system.';
                }
                
            case 1451: // Foreign key constraint fails (delete)
                return 'Cannot delete this record because it is referenced by other records. Please remove the related records first.';
                
            case 1452: // Foreign key constraint fails (insert/update)
                if (strpos($error_message, 'customer') !== false) {
                    return 'The selected customer does not exist.';
                } elseif (strpos($error_message, 'property') !== false) {
                    return 'The selected property does not exist.';
                } elseif (strpos($error_message, 'staff') !== false) {
                    return 'The selected staff member does not exist.';
                } else {
                    return 'Invalid reference to another record.';
                }
                
            case 1406: // Data too long
                return 'One or more fields contain too much data. Please reduce the length of your input.';
                
            case 1048: // Column cannot be null
                preg_match("/Column '([^']+)'/", $error_message, $matches);
                $column = isset($matches[1]) ? str_replace('_', ' ', $matches[1]) : 'required field';
                return ucfirst($column) . ' is required and cannot be empty.';
                
            case 1054: // Unknown column
                return 'Invalid field specified in the request.';
                
            case 1146: // Table doesn't exist
                return 'System configuration error. Please contact administrator.';
                
            case 2002: // Connection refused
            case 2003: // Can't connect to server
                return 'Unable to connect to the database. Please try again later.';
                
            case 1205: // Lock wait timeout
                return 'The system is busy. Please try again in a moment.';
                
            case 1213: // Deadlock
                return 'A conflict occurred while processing your request. Please try again.';
                
            case 1040: // Too many connections
                return 'The system is currently overloaded. Please try again later.';
                
            case 1044: // Access denied for user to database
            case 1045: // Access denied for user
                return 'Database access error. Please contact administrator.';
                
            default:
                // Generic messages based on operation
                switch (strtolower($operation)) {
                    case 'insert':
                    case 'create':
                        return 'Failed to create the record. Please check your input and try again.';
                        
                    case 'update':
                    case 'edit':
                        return 'Failed to update the record. Please check your input and try again.';
                        
                    case 'delete':
                    case 'remove':
                        return 'Failed to delete the record. It may be referenced by other records.';
                        
                    case 'select':
                    case 'fetch':
                    case 'get':
                        return 'Failed to retrieve data. Please try again.';
                        
                    default:
                        return 'A database error occurred while processing your request. Please try again.';
                }
        }
    }
    
    /**
     * Log database error with context
     * @param array $db_error Database error
     * @param string $operation Operation being performed
     * @param array $context Additional context
     */
    protected function log_database_error($db_error, $operation, $context) {
        $log_data = array(
            'timestamp' => date('Y-m-d H:i:s'),
            'error_code' => isset($db_error['code']) ? $db_error['code'] : 0,
            'error_message' => isset($db_error['message']) ? $db_error['message'] : 'Unknown error',
            'operation' => $operation,
            'context' => $context,
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            'ip_address' => $this->CI->input->ip_address(),
            'request_uri' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '',
            'request_method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : ''
        );
        
        $log_message = 'DATABASE ERROR: ' . json_encode($log_data);
        error_log($log_message);
        
        // Also log to custom database error log if configured
        $this->log_to_database($log_data);
    }
    
    /**
     * Log error to database audit_logs table
     * @param array $log_data Log data
     */
    protected function log_to_database($log_data) {
        try {
            // Only log to database if audit_logs table exists
            if ($this->CI->db->table_exists('audit_logs')) {
                $audit_data = array(
                    'table_name' => 'system_errors',
                    'record_id' => 0,
                    'action' => 'database_error',
                    'old_values' => null,
                    'new_values' => json_encode($log_data),
                    'user_id' => $this->get_current_user_id(),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                $this->CI->db->insert('audit_logs', $audit_data);
            }
        } catch (Exception $e) {
            // Don't throw exception if audit logging fails
            error_log('Failed to log database error to audit_logs: ' . $e->getMessage());
        }
    }
    
    /**
     * Get current user ID (placeholder - implement based on your auth system)
     * @return int|null User ID
     */
    protected function get_current_user_id() {
        // Implement based on your authentication system
        // For now, return null or a default value
        return null;
    }
    
    /**
     * Check database connection and handle connection errors
     * @return array Connection status
     */
    public function check_connection() {
        try {
            // Test database connection
            $this->CI->db->query('SELECT 1');
            
            return array(
                'success' => true,
                'message' => 'Database connection is healthy'
            );
            
        } catch (Exception $e) {
            $error_response = $this->handle_error(
                array('code' => 0, 'message' => $e->getMessage()),
                'connection_test'
            );
            
            return $error_response;
        }
    }
    
    /**
     * Execute database operation with error handling
     * @param callable $operation Database operation function
     * @param string $operation_name Operation name for logging
     * @param array $context Additional context
     * @return array Operation result
     */
    public function execute_with_error_handling($operation, $operation_name = 'database operation', $context = array()) {
        try {
            // Start transaction if not already in one
            $this->CI->db->trans_start();
            
            // Execute the operation
            $result = call_user_func($operation);
            
            // Complete transaction
            $this->CI->db->trans_complete();
            
            // Check transaction status
            if ($this->CI->db->trans_status() === FALSE) {
                $db_error = $this->CI->db->error();
                return $this->handle_error($db_error, $operation_name, $context);
            }
            
            return array(
                'success' => true,
                'result' => $result,
                'message' => ucfirst($operation_name) . ' completed successfully'
            );
            
        } catch (Exception $e) {
            // Rollback transaction on exception
            $this->CI->db->trans_rollback();
            
            $error_data = array(
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            );
            
            return $this->handle_error($error_data, $operation_name, $context);
        }
    }
    
    /**
     * Validate database constraints before operation
     * @param string $table Table name
     * @param array $data Data to validate
     * @param string $operation Operation type (insert, update, delete)
     * @param int $record_id Record ID for update operations
     * @return array Validation result
     */
    public function validate_constraints($table, $data, $operation = 'insert', $record_id = null) {
        $errors = array();
        
        try {
            // Check for duplicate entries on unique fields
            if ($operation === 'insert' || $operation === 'update') {
                $unique_fields = $this->get_unique_fields($table);
                
                foreach ($unique_fields as $field) {
                    if (isset($data[$field]) && !empty($data[$field])) {
                        $this->CI->db->where($field, $data[$field]);
                        
                        // For updates, exclude current record
                        if ($operation === 'update' && $record_id) {
                            $this->CI->db->where('id !=', $record_id);
                        }
                        
                        $count = $this->CI->db->count_all_results($table);
                        
                        if ($count > 0) {
                            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' already exists';
                        }
                    }
                }
            }
            
            // Check foreign key constraints
            if ($operation === 'insert' || $operation === 'update') {
                $foreign_keys = $this->get_foreign_keys($table);
                
                foreach ($foreign_keys as $fk) {
                    $field = $fk['field'];
                    $ref_table = $fk['ref_table'];
                    $ref_field = $fk['ref_field'];
                    
                    if (isset($data[$field]) && !empty($data[$field])) {
                        $this->CI->db->where($ref_field, $data[$field]);
                        $count = $this->CI->db->count_all_results($ref_table);
                        
                        if ($count === 0) {
                            $errors[] = 'Invalid ' . str_replace('_', ' ', str_replace('_id', '', $field));
                        }
                    }
                }
            }
            
            return array(
                'is_valid' => empty($errors),
                'errors' => $errors
            );
            
        } catch (Exception $e) {
            return array(
                'is_valid' => false,
                'errors' => array('Constraint validation failed: ' . $e->getMessage())
            );
        }
    }
    
    /**
     * Get unique fields for a table (simplified - extend based on your schema)
     * @param string $table Table name
     * @return array Unique fields
     */
    protected function get_unique_fields($table) {
        $unique_fields = array();
        
        switch ($table) {
            case 'customers':
                $unique_fields = array('email_address', 'phone_number_1', 'aadhar_number', 'pan_number');
                break;
            case 'staff':
                $unique_fields = array('email_address', 'phone_number');
                break;
            case 'registrations':
                $unique_fields = array('registration_number');
                break;
            case 'transactions':
                $unique_fields = array('receipt_number');
                break;
        }
        
        return $unique_fields;
    }
    
    /**
     * Get foreign key relationships for a table (simplified - extend based on your schema)
     * @param string $table Table name
     * @return array Foreign key relationships
     */
    protected function get_foreign_keys($table) {
        $foreign_keys = array();
        
        switch ($table) {
            case 'properties':
                $foreign_keys = array(
                    array('field' => 'assigned_staff_id', 'ref_table' => 'staff', 'ref_field' => 'id')
                );
                break;
            case 'registrations':
                $foreign_keys = array(
                    array('field' => 'property_id', 'ref_table' => 'properties', 'ref_field' => 'id'),
                    array('field' => 'customer_id', 'ref_table' => 'customers', 'ref_field' => 'id')
                );
                break;
            case 'transactions':
                $foreign_keys = array(
                    array('field' => 'registration_id', 'ref_table' => 'registrations', 'ref_field' => 'id')
                );
                break;
            case 'property_assignments':
                $foreign_keys = array(
                    array('field' => 'property_id', 'ref_table' => 'properties', 'ref_field' => 'id'),
                    array('field' => 'staff_id', 'ref_table' => 'staff', 'ref_field' => 'id')
                );
                break;
        }
        
        return $foreign_keys;
    }
}