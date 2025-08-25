<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('migration');
        $this->load->database();
        
        // Enable migrations in config
        $this->config->set_item('migration_enabled', TRUE);
    }

    /**
     * Run all pending migrations
     */
    public function migrate() {
        try {
            if ($this->migration->latest()) {
                echo "Migrations completed successfully.\n";
                $this->show_migration_status();
            } else {
                echo "Migration failed: " . $this->migration->error_string() . "\n";
            }
        } catch (Exception $e) {
            echo "Migration error: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Rollback to a specific migration version
     * Usage: /migration_controller/rollback/20250824000003
     */
    public function rollback($version = null) {
        if (!$version) {
            echo "Please specify a migration version to rollback to.\n";
            echo "Usage: /migration_controller/rollback/[version]\n";
            return;
        }

        try {
            if ($this->migration->version($version)) {
                echo "Rollback to version {$version} completed successfully.\n";
                $this->show_migration_status();
            } else {
                echo "Rollback failed: " . $this->migration->error_string() . "\n";
            }
        } catch (Exception $e) {
            echo "Rollback error: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Show current migration status
     */
    public function status() {
        $this->show_migration_status();
    }

    /**
     * Reset all migrations (rollback to version 0)
     */
    public function reset() {
        try {
            if ($this->migration->version(0)) {
                echo "All migrations have been reset successfully.\n";
                $this->show_migration_status();
            } else {
                echo "Reset failed: " . $this->migration->error_string() . "\n";
            }
        } catch (Exception $e) {
            echo "Reset error: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Run a specific migration
     * Usage: /migration_controller/run_specific/20250824000001
     */
    public function run_specific($version = null) {
        if (!$version) {
            echo "Please specify a migration version to run.\n";
            echo "Usage: /migration_controller/run_specific/[version]\n";
            return;
        }

        try {
            if ($this->migration->version($version)) {
                echo "Migration to version {$version} completed successfully.\n";
                $this->show_migration_status();
            } else {
                echo "Migration failed: " . $this->migration->error_string() . "\n";
            }
        } catch (Exception $e) {
            echo "Migration error: " . $e->getMessage() . "\n";
        }
    }

    /**
     * List all available migrations
     */
    public function list_migrations() {
        $migration_path = APPPATH . 'migrations/';
        $migrations = array();
        
        if (is_dir($migration_path)) {
            $files = scandir($migration_path);
            foreach ($files as $file) {
                if (preg_match('/^(\d{14})_(.+)\.php$/', $file, $matches)) {
                    $migrations[] = array(
                        'version' => $matches[1],
                        'name' => $matches[2],
                        'file' => $file
                    );
                }
            }
        }

        echo "Available Migrations:\n";
        echo str_repeat("-", 80) . "\n";
        printf("%-20s %-50s %-20s\n", "Version", "Name", "File");
        echo str_repeat("-", 80) . "\n";
        
        foreach ($migrations as $migration) {
            printf("%-20s %-50s %-20s\n", 
                $migration['version'], 
                str_replace('_', ' ', $migration['name']), 
                $migration['file']
            );
        }
        echo str_repeat("-", 80) . "\n";
    }

    /**
     * Create database backup before migration
     */
    public function backup() {
        $this->load->dbutil();
        
        $backup_name = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
        $backup_path = APPPATH . 'backups/';
        
        // Create backup directory if it doesn't exist
        if (!is_dir($backup_path)) {
            mkdir($backup_path, 0755, true);
        }
        
        $backup = $this->dbutil->backup();
        
        if (write_file($backup_path . $backup_name, $backup)) {
            echo "Database backup created successfully: {$backup_name}\n";
            echo "Backup location: {$backup_path}{$backup_name}\n";
        } else {
            echo "Failed to create database backup.\n";
        }
    }

    /**
     * Validate database schema
     */
    public function validate_schema() {
        echo "Validating database schema...\n";
        echo str_repeat("-", 50) . "\n";
        
        $tables_to_check = array(
            'customers', 'staff', 'properties', 'registrations', 
            'transactions', 'property_assignments', 'audit_logs'
        );
        
        foreach ($tables_to_check as $table) {
            if ($this->db->table_exists($table)) {
                $fields = $this->db->list_fields($table);
                echo "✓ Table '{$table}' exists with " . count($fields) . " fields\n";
            } else {
                echo "✗ Table '{$table}' does not exist\n";
            }
        }
        
        echo str_repeat("-", 50) . "\n";
        echo "Schema validation completed.\n";
    }

    /**
     * Show current migration status and database info
     */
    private function show_migration_status() {
        echo "\nMigration Status:\n";
        echo str_repeat("-", 40) . "\n";
        
        // Get current migration version
        $current_version = $this->migration->current();
        if ($current_version !== FALSE) {
            echo "Current migration version: " . $this->get_current_version() . "\n";
        } else {
            echo "No migrations have been run yet.\n";
        }
        
        // Show database info
        echo "Database: " . $this->db->database . "\n";
        echo "Tables: " . count($this->db->list_tables()) . "\n";
        echo str_repeat("-", 40) . "\n";
    }

    /**
     * Get current migration version from database
     */
    private function get_current_version() {
        $query = $this->db->get('migrations');
        if ($query->num_rows() > 0) {
            return $query->row()->version;
        }
        return '0';
    }

    /**
     * Show help information
     */
    public function help() {
        echo "Migration Controller Help\n";
        echo str_repeat("=", 50) . "\n";
        echo "Available commands:\n\n";
        echo "migrate              - Run all pending migrations\n";
        echo "rollback/[version]   - Rollback to specific version\n";
        echo "reset                - Reset all migrations (rollback to 0)\n";
        echo "run_specific/[ver]   - Run specific migration version\n";
        echo "status               - Show current migration status\n";
        echo "list_migrations      - List all available migrations\n";
        echo "backup               - Create database backup\n";
        echo "validate_schema      - Validate database schema\n";
        echo "help                 - Show this help message\n\n";
        echo "Examples:\n";
        echo "  /migration_controller/migrate\n";
        echo "  /migration_controller/rollback/20250824000003\n";
        echo "  /migration_controller/run_specific/20250824000001\n";
        echo str_repeat("=", 50) . "\n";
    }
}