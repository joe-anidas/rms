<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Performance Helper Functions
 * Provides utility functions for performance optimization
 */

if (!function_exists('load_optimized_assets')) {
    /**
     * Load optimized CSS and JS assets
     * @param array $css_files CSS files to load
     * @param array $js_files JS files to load
     * @param bool $minify Whether to minify assets
     */
    function load_optimized_assets($css_files = array(), $js_files = array(), $minify = null) {
        $CI =& get_instance();
        $CI->load->library('asset_optimizer');
        
        if ($minify === null) {
            $minify = ENVIRONMENT === 'production';
        }
        
        // Add CSS files
        foreach ($css_files as $css) {
            if (is_array($css)) {
                $CI->asset_optimizer->add_css($css['file'], $css['media'] ?? 'all');
            } else {
                $CI->asset_optimizer->add_css($css);
            }
        }
        
        // Add JS files
        foreach ($js_files as $js) {
            if (is_array($js)) {
                $CI->asset_optimizer->add_js($js['file'], $js['defer'] ?? false);
            } else {
                $CI->asset_optimizer->add_js($js);
            }
        }
        
        // Store in view data for rendering in header/footer
        $CI->load->vars(array(
            'optimized_css' => $CI->asset_optimizer->render_css($minify),
            'optimized_js' => $CI->asset_optimizer->render_js($minify)
        ));
    }
}

if (!function_exists('cache_remember')) {
    /**
     * Cache remember function - get from cache or execute callback
     * @param string $key Cache key
     * @param callable $callback Callback function
     * @param int $ttl Time to live in seconds
     * @return mixed Cached or fresh data
     */
    function cache_remember($key, $callback, $ttl = 3600) {
        $CI =& get_instance();
        $CI->load->library('cache_manager');
        
        return $CI->cache_manager->remember($key, $callback, $ttl);
    }
}

if (!function_exists('paginate_results')) {
    /**
     * Create pagination for results
     * @param string $base_url Base URL for pagination
     * @param int $total_rows Total number of rows
     * @param int $per_page Items per page
     * @param int $current_page Current page
     * @return array Pagination data
     */
    function paginate_results($base_url, $total_rows, $per_page = 20, $current_page = 1) {
        $CI =& get_instance();
        $CI->load->library('pagination_helper');
        
        $config = $CI->pagination_helper->create_pagination_config($base_url, $total_rows, $per_page);
        $pagination_links = $CI->pagination_helper->initialize_pagination($config);
        $offset = $CI->pagination_helper->get_offset($current_page, $per_page);
        $pagination_info = $CI->pagination_helper->get_pagination_info($current_page, $per_page, $total_rows);
        
        return array(
            'links' => $pagination_links,
            'offset' => $offset,
            'info' => $pagination_info,
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_rows' => $total_rows
        );
    }
}

if (!function_exists('optimize_uploaded_image')) {
    /**
     * Optimize uploaded image
     * @param string $file_path Path to uploaded file
     * @param array $options Optimization options
     * @return array Optimization result
     */
    function optimize_uploaded_image($file_path, $options = array()) {
        $CI =& get_instance();
        $CI->load->library('image_optimizer');
        
        return $CI->image_optimizer->optimize_image($file_path, $options);
    }
}

if (!function_exists('get_cached_dropdown_options')) {
    /**
     * Get cached dropdown options
     * @param string $type Type of dropdown (districts, taluks, etc.)
     * @return array Dropdown options
     */
    function get_cached_dropdown_options($type) {
        $CI =& get_instance();
        $CI->load->library('cache_manager');
        
        return $CI->cache_manager->get_dropdown_options($type);
    }
}

if (!function_exists('invalidate_cache_on_update')) {
    /**
     * Invalidate relevant caches when data is updated
     * @param string $table_name Table that was updated
     */
    function invalidate_cache_on_update($table_name) {
        $CI =& get_instance();
        $CI->load->library('cache_manager');
        
        switch ($table_name) {
            case 'properties':
                $CI->cache_manager->invalidate_property_cache();
                break;
            case 'customers':
                $CI->cache_manager->invalidate_customer_cache();
                break;
            case 'staff':
                $CI->cache_manager->invalidate_staff_cache();
                break;
            default:
                $CI->cache_manager->invalidate_dashboard_cache();
                break;
        }
    }
}

if (!function_exists('measure_execution_time')) {
    /**
     * Measure execution time of a function
     * @param callable $callback Function to measure
     * @param string $label Label for logging
     * @return mixed Function result
     */
    function measure_execution_time($callback, $label = 'Function') {
        $start_time = microtime(true);
        $result = call_user_func($callback);
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time) * 1000; // Convert to milliseconds
        
        log_message('info', $label . ' execution time: ' . number_format($execution_time, 2) . 'ms');
        
        return $result;
    }
}

if (!function_exists('compress_output')) {
    /**
     * Enable output compression
     */
    function compress_output() {
        if (ENVIRONMENT === 'production' && !ob_get_level()) {
            ob_start('ob_gzhandler');
        }
    }
}

if (!function_exists('set_cache_headers')) {
    /**
     * Set appropriate cache headers for static assets
     * @param int $max_age Cache max age in seconds
     */
    function set_cache_headers($max_age = 86400) {
        $CI =& get_instance();
        
        if (ENVIRONMENT === 'production') {
            $CI->output->set_header('Cache-Control: public, max-age=' . $max_age);
            $CI->output->set_header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $max_age) . ' GMT');
            $CI->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime(FCPATH . 'index.php')) . ' GMT');
        }
    }
}

if (!function_exists('lazy_load_images')) {
    /**
     * Generate lazy loading image HTML
     * @param string $src Image source
     * @param string $alt Alt text
     * @param array $attributes Additional attributes
     * @return string HTML
     */
    function lazy_load_images($src, $alt = '', $attributes = array()) {
        $default_attributes = array(
            'loading' => 'lazy',
            'decoding' => 'async'
        );
        
        $attributes = array_merge($default_attributes, $attributes);
        $attr_string = '';
        
        foreach ($attributes as $key => $value) {
            $attr_string .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }
        
        return '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($alt) . '"' . $attr_string . '>';
    }
}

if (!function_exists('preload_critical_resources')) {
    /**
     * Generate preload links for critical resources
     * @param array $resources Array of resources to preload
     * @return string HTML preload links
     */
    function preload_critical_resources($resources) {
        $CI =& get_instance();
        $CI->load->library('asset_optimizer');
        
        return $CI->asset_optimizer->generate_preload_links($resources);
    }
}

if (!function_exists('get_performance_metrics')) {
    /**
     * Get basic performance metrics
     * @return array Performance metrics
     */
    function get_performance_metrics() {
        $CI =& get_instance();
        
        return array(
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
            'included_files' => count(get_included_files()),
            'database_queries' => $CI->db->query_count ?? 0
        );
    }
}

if (!function_exists('optimize_database_query')) {
    /**
     * Add query optimization hints
     * @param string $query SQL query
     * @param array $indexes Suggested indexes
     * @return string Optimized query
     */
    function optimize_database_query($query, $indexes = array()) {
        // Add USE INDEX hints if provided
        if (!empty($indexes)) {
            foreach ($indexes as $table => $index) {
                $query = str_replace(
                    "FROM {$table}",
                    "FROM {$table} USE INDEX ({$index})",
                    $query
                );
            }
        }
        
        return $query;
    }
}

if (!function_exists('enable_query_cache')) {
    /**
     * Enable MySQL query cache for a query
     * @param string $query SQL query
     * @return string Query with cache hint
     */
    function enable_query_cache($query) {
        if (strpos(strtoupper($query), 'SELECT') === 0) {
            return str_replace('SELECT', 'SELECT SQL_CACHE', $query);
        }
        return $query;
    }
}