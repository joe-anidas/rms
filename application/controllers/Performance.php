<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Performance Controller
 * Handles performance monitoring and optimization tasks
 */
class Performance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('performance');
        $this->load->library(['cache_manager', 'asset_optimizer', 'image_optimizer']);
    }

    /**
     * Performance dashboard
     */
    public function index() {
        $data = array(
            'page_title' => 'Performance Dashboard',
            'breadcrumbs' => array(
                array('title' => 'Dashboard', 'url' => base_url('dashboard')),
                array('title' => 'Performance', 'url' => '')
            )
        );

        // Get performance metrics
        $data['metrics'] = get_performance_metrics();
        $data['cache_info'] = $this->cache_manager->get_cache_info();
        $data['optimization_stats'] = $this->image_optimizer->get_optimization_stats();

        $this->load->view('others/header', $data);
        $this->load->view('performance/dashboard', $data);
        $this->load->view('others/footer');
    }

    /**
     * Clear all caches
     */
    public function clear_cache() {
        try {
            $result = $this->cache_manager->flush();
            
            if ($result) {
                $this->session->set_flashdata('success', 'All caches cleared successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to clear caches');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error clearing caches: ' . $e->getMessage());
        }

        redirect('performance');
    }

    /**
     * Warm up cache
     */
    public function warm_cache() {
        try {
            $this->cache_manager->warm_up_cache();
            $this->session->set_flashdata('success', 'Cache warmed up successfully');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error warming up cache: ' . $e->getMessage());
        }

        redirect('performance');
    }

    /**
     * Clean old minified files
     */
    public function clean_assets() {
        try {
            $this->asset_optimizer->clean_old_files();
            $this->session->set_flashdata('success', 'Old asset files cleaned successfully');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error cleaning assets: ' . $e->getMessage());
        }

        redirect('performance');
    }

    /**
     * Clean old optimized images
     */
    public function clean_images() {
        try {
            $days_old = $this->input->post('days_old') ?: 30;
            $this->image_optimizer->cleanup_old_images($days_old);
            $this->session->set_flashdata('success', 'Old optimized images cleaned successfully');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error cleaning images: ' . $e->getMessage());
        }

        redirect('performance');
    }

    /**
     * Run database optimization
     */
    public function optimize_database() {
        try {
            // Get all tables
            $tables = $this->db->list_tables();
            $optimized_tables = 0;

            foreach ($tables as $table) {
                $this->db->query("OPTIMIZE TABLE {$table}");
                $optimized_tables++;
            }

            $this->session->set_flashdata('success', "Optimized {$optimized_tables} database tables successfully");
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error optimizing database: ' . $e->getMessage());
        }

        redirect('performance');
    }

    /**
     * Get performance metrics via AJAX
     */
    public function get_metrics() {
        $metrics = get_performance_metrics();
        $cache_info = $this->cache_manager->get_cache_info();
        
        $response = array(
            'success' => true,
            'metrics' => $metrics,
            'cache_info' => $cache_info,
            'timestamp' => date('Y-m-d H:i:s')
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Test page load speed
     */
    public function test_speed() {
        $url = $this->input->post('url') ?: base_url();
        
        $start_time = microtime(true);
        
        // Use cURL to test page load
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'RMS Performance Tester');
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        $size_download = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
        
        curl_close($ch);
        
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time) * 1000;

        $result = array(
            'success' => $http_code === 200,
            'url' => $url,
            'http_code' => $http_code,
            'total_time' => $total_time,
            'execution_time' => $execution_time,
            'size_download' => $size_download,
            'response_size' => strlen($response)
        );

        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        } else {
            $this->session->set_flashdata('speed_test_result', $result);
            redirect('performance');
        }
    }

    /**
     * Batch optimize images in uploads directory
     */
    public function batch_optimize_images() {
        try {
            $upload_dir = FCPATH . 'uploads/';
            
            if (!is_dir($upload_dir)) {
                throw new Exception('Upload directory not found');
            }

            $options = array(
                'create_thumbnail' => true,
                'create_webp' => true
            );

            $results = $this->image_optimizer->batch_optimize($upload_dir, $options);
            
            $success_count = 0;
            $error_count = 0;
            
            foreach ($results as $result) {
                if ($result['result']['success']) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }

            $message = "Batch optimization completed. Success: {$success_count}, Errors: {$error_count}";
            $this->session->set_flashdata('success', $message);
            
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Batch optimization failed: ' . $e->getMessage());
        }

        redirect('performance');
    }

    /**
     * Generate performance report
     */
    public function generate_report() {
        $data = array(
            'page_title' => 'Performance Report',
            'breadcrumbs' => array(
                array('title' => 'Dashboard', 'url' => base_url('dashboard')),
                array('title' => 'Performance', 'url' => base_url('performance')),
                array('title' => 'Report', 'url' => '')
            )
        );

        // Collect comprehensive performance data
        $data['metrics'] = get_performance_metrics();
        $data['cache_info'] = $this->cache_manager->get_cache_info();
        $data['optimization_stats'] = $this->image_optimizer->get_optimization_stats();
        
        // Database performance metrics
        $data['db_metrics'] = $this->get_database_metrics();
        
        // Asset optimization metrics
        $data['asset_metrics'] = $this->get_asset_metrics();

        $this->load->view('others/header', $data);
        $this->load->view('performance/report', $data);
        $this->load->view('others/footer');
    }

    /**
     * Get database performance metrics
     */
    private function get_database_metrics() {
        try {
            $metrics = array();
            
            // Get table sizes
            $query = "SELECT 
                table_name,
                ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'size_mb',
                table_rows
                FROM information_schema.TABLES 
                WHERE table_schema = DATABASE()
                ORDER BY (data_length + index_length) DESC";
            
            $result = $this->db->query($query);
            $metrics['table_sizes'] = $result->result();
            
            // Get slow query log status
            $slow_query_result = $this->db->query("SHOW VARIABLES LIKE 'slow_query_log'");
            $metrics['slow_query_log'] = $slow_query_result->row();
            
            // Get query cache status
            $query_cache_result = $this->db->query("SHOW VARIABLES LIKE 'query_cache%'");
            $metrics['query_cache'] = $query_cache_result->result();
            
            return $metrics;
            
        } catch (Exception $e) {
            log_message('error', 'Error getting database metrics: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get asset optimization metrics
     */
    private function get_asset_metrics() {
        $metrics = array();
        
        // CSS files
        $css_dir = FCPATH . 'assets/css/';
        $css_files = glob($css_dir . '*.css');
        $css_size = 0;
        
        foreach ($css_files as $file) {
            $css_size += filesize($file);
        }
        
        $metrics['css_files'] = count($css_files);
        $metrics['css_size'] = $css_size;
        
        // JavaScript files
        $js_dir = FCPATH . 'assets/js/';
        $js_files = glob($js_dir . '*.js');
        $js_size = 0;
        
        foreach ($js_files as $file) {
            $js_size += filesize($file);
        }
        
        $metrics['js_files'] = count($js_files);
        $metrics['js_size'] = $js_size;
        
        // Minified files
        $minified_dir = FCPATH . 'assets/minified/';
        if (is_dir($minified_dir)) {
            $minified_files = glob($minified_dir . '*.min.{css,js}', GLOB_BRACE);
            $minified_size = 0;
            
            foreach ($minified_files as $file) {
                $minified_size += filesize($file);
            }
            
            $metrics['minified_files'] = count($minified_files);
            $metrics['minified_size'] = $minified_size;
        } else {
            $metrics['minified_files'] = 0;
            $metrics['minified_size'] = 0;
        }
        
        return $metrics;
    }
}