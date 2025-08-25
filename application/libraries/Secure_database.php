<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Secure Database Library
 * Provides secure database operations with prepared statements and SQL injection prevention
 * Requirements: 7.1, 7.4
 */
class Secure_database {
    
    protected $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->library(['security_manager', 'enhanced_validation']);
    }
    
    /**
     * Secure select query with prepared statements
     * @param string $table Table name
     * @param array $where Where conditions
     * @param array $options Query options (select, join, order_by, limit, etc.)
     * @return object Query result
     */
    public function secure_select($table, $where = array(), $options = array()) {
        try {
            // Sanitize table name
            $table = $this->CI->enhanced_validation->sanitize_input($table, 'sql_identifier');
            
            // Validate table exists
            if (!$this->CI->db->table_exists($table)) {
                throw new Exception('Table does not exist: ' . $table);
            }
            
            // Build select clause
            if (isset($options['select'])) {
                $select_fields = is_array($options['select']) ? $options['select'] : array($options['select']);
                $sanitized_fields = array();
                
                foreach ($select_fields as $field) {
                    // Allow functions like COUNT(*), SUM(field), etc.
                    if (preg_match('/^[A-Z]+\s*\(/i', $field) || $field === '*') {
                        $sanitized_fields[] = $field;
                    } else {
                        $sanitized_fields[] = $this->CI->enhanced_validation->sanitize_input($field, 'sql_identifier');
                    }
                }
                
                $this->CI->db->select(implode(', ', $sanitized_fields));
            }
            
            // Set table
            $this->CI->db->from($table);
            
            // Add joins if specified
            if (isset($options['join']) && is_array($options['join'])) {
                foreach ($options['join'] as $join) {
                    $join_table = $this->CI->enhanced_validation->sanitize_input($join['table'], 'sql_identifier');
                    $join_condition = $join['condition']; // This should be validated separately
                    $join_type = isset($join['type']) ? $join['type'] : 'inner';
                    
                    $this->CI->db->join($join_table, $join_condition, $join_type);
                }
            }
            
            // Add where conditions
            foreach ($where as $field => $value) {
                $clean_field = $this->CI->enhanced_validation->sanitize_input($field, 'sql_identifier');
                
                if (is_array($value)) {
                    // Handle IN conditions
                    $sanitized_values = array();
                    foreach ($value as $v) {
                        $sanitized_values[] = $this->CI->enhanced_validation->sanitize_input($v);
                    }
                    $this->CI->db->where_in($clean_field, $sanitized_values);
                } else {
                    $sanitized_value = $this->CI->enhanced_validation->sanitize_input($value);
                    $this->CI->db->where($clean_field, $sanitized_value);
                }
            }
            
            // Add order by
            if (isset($options['order_by'])) {
                if (is_array($options['order_by'])) {
                    foreach ($options['order_by'] as $field => $direction) {
                        $clean_field = $this->CI->enhanced_validation->sanitize_input($field, 'sql_identifier');
                        $clean_direction = in_array(strtoupper($direction), array('ASC', 'DESC')) ? $direction : 'ASC';
                        $this->CI->db->order_by($clean_field, $clean_direction);
                    }
                } else {
                    $clean_field = $this->CI->enhanced_validation->sanitize_input($options['order_by'], 'sql_identifier');
                    $this->CI->db->order_by($clean_field);
                }
            }
            
            // Add group by
            if (isset($options['group_by'])) {
                $group_fields = is_array($options['group_by']) ? $options['group_by'] : array($options['group_by']);
                foreach ($group_fields as $field) {
                    $clean_field = $this->CI->enhanced_validation->sanitize_input($field, 'sql_identifier');
                    $this->CI->db->group_by($clean_field);
                }
            }
            
            // Add limit
            if (isset($options['limit'])) {
                $limit = (int) $options['limit'];
                $offset = isset($options['offset']) ? (int) $options['offset'] : 0;
                $this->CI->db->limit($limit, $offset);
            }
            
            // Execute query
            $result = $this->CI->db->get();
            
            // Log query for audit if needed
            $this->log_database_operation('select', $table, array('where' => $where, 'options' => $options));
            
            return $result;
            
        } catch (Exception $e) {
            $this->CI->security_manager->log_security_event(
                'database_error',
                'Secure select query failed',
                array(
                    'table' => $table,
                    'error' => $e->getMessage(),
                    'where' => $where,
                    'options' => $options
                )
            );
            throw $e;
        }
    }
    
    /**
     * Secure insert with prepared statements
     * @param string $table Table name
     * @param array $data Data to insert
     * @return int|bool Insert ID or false
     */
    public function secure_insert($table, $data) {
        try {
            // Sanitize table name
            $table = $this->CI->enhanced_validation->sanitize_input($table, 'sql_identifier');
            
            // Validate table exists
            if (!$this->CI->db->table_exists($table)) {
                throw new Exception('Table does not exist: ' . $table);
            }
            
            // Sanitize and validate data
            $sanitized_data = array();
            foreach ($data as $field => $value) {
                $clean_field = $this->CI->enhanced_validation->sanitize_input($field, 'sql_identifier');
                
                // Validate field exists in table
                if (!$this->field_exists($table, $clean_field)) {
                    throw new Exception('Field does not exist: ' . $clean_field . ' in table ' . $table);
                }
                
                $sanitized_data[$clean_field] = $this->CI->enhanced_validation->sanitize_input($value);
            }
            
            // Execute insert
            $result = $this->CI->db->insert($table, $sanitized_data);
            
            if ($result) {
                $insert_id = $this->CI->db->insert_id();
                
                // Log operation
                $this->log_database_operation('insert', $table, array(
                    'data' => $sanitized_data,
                    'insert_id' => $insert_id
                ));
                
                return $insert_id;
            }
            
            return false;
            
        } catch (Exception $e) {
            $this->CI->security_manager->log_security_event(
                'database_error',
                'Secure insert query failed',
                array(
                    'table' => $table,
                    'error' => $e->getMessage(),
                    'data' => $data
                )
            );
            throw $e;
        }
    }
    
    /**
     * Secure update with prepared statements
     * @param string $table Table name
     * @param array $data Data to update
     * @param array $where Where conditions
     * @return bool Update result
     */
    public function secure_update($table, $data, $where) {
        try {
            // Sanitize table name
            $table = $this->CI->enhanced_validation->sanitize_input($table, 'sql_identifier');
            
            // Validate table exists
            if (!$this->CI->db->table_exists($table)) {
                throw new Exception('Table does not exist: ' . $table);
            }
            
            // Validate where conditions exist (prevent accidental full table updates)
            if (empty($where)) {
                throw new Exception('Update operation requires WHERE conditions');
            }
            
            // Sanitize and validate data
            $sanitized_data = array();
            foreach ($data as $field => $value) {
                $clean_field = $this->CI->enhanced_validation->sanitize_input($field, 'sql_identifier');
                
                // Validate field exists in table
                if (!$this->field_exists($table, $clean_field)) {
                    throw new Exception('Field does not exist: ' . $clean_field . ' in table ' . $table);
                }
                
                $sanitized_data[$clean_field] = $this->CI->enhanced_validation->sanitize_input($value);
            }
            
            // Add where conditions
            foreach ($where as $field => $value) {
                $clean_field = $this->CI->enhanced_validation->sanitize_input($field, 'sql_identifier');
                $sanitized_value = $this->CI->enhanced_validation->sanitize_input($value);
                $this->CI->db->where($clean_field, $sanitized_value);
            }
            
            // Execute update
            $result = $this->CI->db->update($table, $sanitized_data);
            
            // Log operation
            $this->log_database_operation('update', $table, array(
                'data' => $sanitized_data,
                'where' => $where,
                'affected_rows' => $this->CI->db->affected_rows()
            ));
            
            return $result;
            
        } catch (Exception $e) {
            $this->CI->security_manager->log_security_event(
                'database_error',
                'Secure update query failed',
                array(
                    'table' => $table,
                    'error' => $e->getMessage(),
                    'data' => $data,
                    'where' => $where
                )
            );
            throw $e;
        }
    }
    
    /**
     * Secure delete with prepared statements
     * @param string $table Table name
     * @param array $where Where conditions
     * @return bool Delete result
     */
    public function secure_delete($table, $where) {
        try {
            // Sanitize table name
            $table = $this->CI->enhanced_validation->sanitize_input($table, 'sql_identifier');
            
            // Validate table exists
            if (!$this->CI->db->table_exists($table)) {
                throw new Exception('Table does not exist: ' . $table);
            }
            
            // Validate where conditions exist (prevent accidental full table deletes)
            if (empty($where)) {
                throw new Exception('Delete operation requires WHERE conditions');
            }
            
            // Add where conditions
            foreach ($where as $field => $value) {
                $clean_field = $this->CI->enhanced_validation->sanitize_input($field, 'sql_identifier');
                $sanitized_value = $this->CI->enhanced_validation->sanitize_input($value);
                $this->CI->db->where($clean_field, $sanitized_value);
            }
            
            // Execute delete
            $result = $this->CI->db->delete($table);
            
            // Log operation
            $this->log_database_operation('delete', $table, array(
                'where' => $where,
                'affected_rows' => $this->CI->db->affected_rows()
            ));
            
            return $result;
            
        } catch (Exception $e) {
            $this->CI->security_manager->log_security_event(
                'database_error',
                'Secure delete query failed',
                array(
                    'table' => $table,
                    'error' => $e->getMessage(),
                    'where' => $where
                )
            );
            throw $e;
        }
    }
    
    /**
     * Execute raw prepared query with parameter binding
     * @param string $sql SQL query with placeholders
     * @param array $params Parameters to bind
     * @return object Query result
     */
    public function execute_prepared_query($sql, $params = array()) {
        try {
            // Validate SQL structure
            if (!$this->validate_sql_structure($sql)) {
                throw new Exception('Invalid or dangerous SQL query structure');
            }
            
            // Sanitize parameters
            $sanitized_params = array();
            foreach ($params as $param) {
                $sanitized_params[] = $this->CI->enhanced_validation->sanitize_input($param);
            }
            
            // Execute query with parameter binding
            $result = $this->CI->db->query($sql, $sanitized_params);
            
            // Log operation
            $this->log_database_operation('raw_query', 'multiple', array(
                'sql' => $sql,
                'params_count' => count($sanitized_params)
            ));
            
            return $result;
            
        } catch (Exception $e) {
            $this->CI->security_manager->log_security_event(
                'database_error',
                'Prepared query execution failed',
                array(
                    'sql' => $sql,
                    'error' => $e->getMessage(),
                    'params_count' => count($params)
                )
            );
            throw $e;
        }
    }
    
    /**
     * Check if field exists in table
     * @param string $table Table name
     * @param string $field Field name
     * @return bool Field exists
     */
    protected function field_exists($table, $field) {
        try {
            $fields = $this->CI->db->list_fields($table);
            return in_array($field, $fields);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Validate SQL query structure
     * @param string $sql SQL query
     * @return bool Is valid
     */
    protected function validate_sql_structure($sql) {
        // Remove comments and normalize
        $sql = preg_replace('/\/\*.*?\*\//', '', $sql);
        $sql = preg_replace('/--.*$/', '', $sql);
        $sql = preg_replace('/\s+/', ' ', trim($sql));
        
        // Check for dangerous patterns
        $dangerous_patterns = array(
            '/\b(DROP|ALTER|CREATE|TRUNCATE)\s+/i',
            '/\bUNION\s+SELECT\b/i',
            '/\bINTO\s+OUTFILE\b/i',
            '/\bLOAD_FILE\s*\(/i',
            '/\bINTO\s+DUMPFILE\b/i',
            '/\bSLEEP\s*\(/i',
            '/\bBENCHMARK\s*\(/i',
            '/\bEXEC\s*\(/i',
            '/\bSYSTEM\s*\(/i'
        );
        
        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $sql)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Log database operations for audit trail
     * @param string $operation Operation type
     * @param string $table Table name
     * @param array $details Operation details
     */
    protected function log_database_operation($operation, $table, $details = array()) {
        try {
            // Only log if audit logging is enabled and table exists
            if ($this->CI->db->table_exists('audit_logs')) {
                $log_data = array(
                    'table_name' => $table,
                    'record_id' => isset($details['insert_id']) ? $details['insert_id'] : 0,
                    'action' => $operation,
                    'old_values' => null,
                    'new_values' => json_encode($details),
                    'user_id' => $this->get_current_user_id(),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                $this->CI->db->insert('audit_logs', $log_data);
            }
        } catch (Exception $e) {
            // Don't throw exception for logging failures
            error_log('Failed to log database operation: ' . $e->getMessage());
        }
    }
    
    /**
     * Get current user ID (implement based on your auth system)
     * @return int|null User ID
     */
    protected function get_current_user_id() {
        // Implement based on your authentication system
        // For now, return null or a default value
        return null;
    }
    
    /**
     * Begin transaction
     */
    public function begin_transaction() {
        $this->CI->db->trans_start();
    }
    
    /**
     * Commit transaction
     * @return bool Success
     */
    public function commit_transaction() {
        $this->CI->db->trans_complete();
        return $this->CI->db->trans_status();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback_transaction() {
        $this->CI->db->trans_rollback();
    }
    
    /**
     * Execute operation within transaction
     * @param callable $operation Operation to execute
     * @return mixed Operation result
     */
    public function execute_in_transaction($operation) {
        try {
            $this->begin_transaction();
            $result = $operation();
            
            if (!$this->commit_transaction()) {
                throw new Exception('Transaction commit failed');
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->rollback_transaction();
            throw $e;
        }
    }
}