<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cache Manager Library
 * Provides caching functionality for frequently accessed data
 */
class Cache_manager {

    protected $CI;
    protected $cache_driver;
    protected $default_ttl = 3600; // 1 hour
    protected $cache_prefix = 'rms_';

    public function __construct($params = array()) {
        $this->CI =& get_instance();
        
        // Load cache driver
        $this->CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'dummy'));
        $this->cache_driver = $this->CI->cache;
        
        // Set cache prefix if provided
        if (isset($params['prefix'])) {
            $this->cache_prefix = $params['prefix'];
        }
        
        // Set default TTL if provided
        if (isset($params['default_ttl'])) {
            $this->default_ttl = $params['default_ttl'];
        }
    }

    /**
     * Get data from cache or execute callback and cache result
     * @param string $key Cache key
     * @param callable $callback Callback to execute if cache miss
     * @param int $ttl Time to live in seconds
     * @return mixed Cached or fresh data
     */
    public function remember($key, $callback, $ttl = null) {
        if ($ttl === null) {
            $ttl = $this->default_ttl;
        }
        
        $cache_key = $this->get_cache_key($key);
        $cached_data = $this->cache_driver->get($cache_key);
        
        if ($cached_data !== FALSE) {
            return $cached_data;
        }
        
        // Execute callback and cache result
        $fresh_data = call_user_func($callback);
        $this->cache_driver->save($cache_key, $fresh_data, $ttl);
        
        return $fresh_data;
    }

    /**
     * Store data in cache
     * @param string $key Cache key
     * @param mixed $data Data to cache
     * @param int $ttl Time to live in seconds
     * @return bool Success status
     */
    public function put($key, $data, $ttl = null) {
        if ($ttl === null) {
            $ttl = $this->default_ttl;
        }
        
        $cache_key = $this->get_cache_key($key);
        return $this->cache_driver->save($cache_key, $data, $ttl);
    }

    /**
     * Get data from cache
     * @param string $key Cache key
     * @return mixed Cached data or FALSE if not found
     */
    public function get($key) {
        $cache_key = $this->get_cache_key($key);
        return $this->cache_driver->get($cache_key);
    }

    /**
     * Delete data from cache
     * @param string $key Cache key
     * @return bool Success status
     */
    public function delete($key) {
        $cache_key = $this->get_cache_key($key);
        return $this->cache_driver->delete($cache_key);
    }

    /**
     * Clear all cache data
     * @return bool Success status
     */
    public function flush() {
        return $this->cache_driver->clean();
    }

    /**
     * Cache dashboard statistics
     * @return array Dashboard statistics
     */
    public function get_dashboard_stats() {
        return $this->remember('dashboard_stats', function() {
            $this->CI->load->model(['Customer_model', 'Property_model', 'Staff_model']);
            
            $stats = array();
            
            // Customer statistics
            $stats['total_customers'] = $this->CI->Customer_model->get_customer_count();
            $stats['recent_customers'] = $this->CI->Customer_model->get_recent_customers(5);
            
            // Property statistics
            if (method_exists($this->CI->Property_model, 'get_property_statistics')) {
                $property_stats = $this->CI->Property_model->get_property_statistics();
                $stats = array_merge($stats, $property_stats);
            }
            
            // Staff statistics
            if (method_exists($this->CI->Staff_model, 'get_staff_count')) {
                $stats['total_staff'] = $this->CI->Staff_model->get_staff_count();
            }
            
            return $stats;
        }, 300); // Cache for 5 minutes
    }

    /**
     * Cache property statistics
     * @return array Property statistics
     */
    public function get_property_stats() {
        return $this->remember('property_stats', function() {
            $this->CI->load->model('Property_model');
            
            if (method_exists($this->CI->Property_model, 'get_property_statistics')) {
                return $this->CI->Property_model->get_property_statistics();
            }
            
            return array();
        }, 600); // Cache for 10 minutes
    }

    /**
     * Cache customer statistics
     * @return array Customer statistics
     */
    public function get_customer_stats() {
        return $this->remember('customer_stats', function() {
            $this->CI->load->model('Customer_model');
            
            if (method_exists($this->CI->Customer_model, 'get_customer_statistics')) {
                return $this->CI->Customer_model->get_customer_statistics();
            }
            
            return array();
        }, 600); // Cache for 10 minutes
    }

    /**
     * Cache staff statistics
     * @return array Staff statistics
     */
    public function get_staff_stats() {
        return $this->remember('staff_stats', function() {
            $this->CI->load->model('Staff_model');
            
            if (method_exists($this->CI->Staff_model, 'get_staff_statistics')) {
                return $this->CI->Staff_model->get_staff_statistics();
            }
            
            return array();
        }, 600); // Cache for 10 minutes
    }

    /**
     * Cache dropdown options for forms
     * @param string $type Type of dropdown (districts, taluks, property_types, etc.)
     * @return array Dropdown options
     */
    public function get_dropdown_options($type) {
        return $this->remember("dropdown_options_{$type}", function() use ($type) {
            $this->CI->load->model(['Property_model', 'Customer_model']);
            
            switch ($type) {
                case 'districts':
                    if (method_exists($this->CI->Property_model, 'get_distinct_values')) {
                        return $this->CI->Property_model->get_distinct_values('district');
                    }
                    break;
                    
                case 'taluks':
                    if (method_exists($this->CI->Property_model, 'get_distinct_values')) {
                        return $this->CI->Property_model->get_distinct_values('taluk_name');
                    }
                    break;
                    
                case 'property_types':
                    if (method_exists($this->CI->Property_model, 'get_distinct_values')) {
                        return $this->CI->Property_model->get_distinct_values('property_type');
                    }
                    break;
                    
                case 'villages':
                    if (method_exists($this->CI->Property_model, 'get_distinct_values')) {
                        return $this->CI->Property_model->get_distinct_values('village_town_name');
                    }
                    break;
            }
            
            return array();
        }, 1800); // Cache for 30 minutes
    }

    /**
     * Invalidate cache for specific patterns
     * @param string $pattern Cache key pattern
     */
    public function invalidate_pattern($pattern) {
        // For file cache, we need to manually delete matching files
        $cache_info = $this->cache_driver->cache_info();
        
        if (is_array($cache_info)) {
            foreach ($cache_info as $key => $info) {
                if (strpos($key, $this->cache_prefix . $pattern) === 0) {
                    $this->cache_driver->delete($key);
                }
            }
        }
    }

    /**
     * Invalidate dashboard related caches
     */
    public function invalidate_dashboard_cache() {
        $this->delete('dashboard_stats');
        $this->delete('property_stats');
        $this->delete('customer_stats');
        $this->delete('staff_stats');
    }

    /**
     * Invalidate property related caches
     */
    public function invalidate_property_cache() {
        $this->delete('property_stats');
        $this->delete('dashboard_stats');
        $this->invalidate_pattern('dropdown_options_');
    }

    /**
     * Invalidate customer related caches
     */
    public function invalidate_customer_cache() {
        $this->delete('customer_stats');
        $this->delete('dashboard_stats');
    }

    /**
     * Invalidate staff related caches
     */
    public function invalidate_staff_cache() {
        $this->delete('staff_stats');
        $this->delete('dashboard_stats');
    }

    /**
     * Get cache key with prefix
     * @param string $key Original key
     * @return string Prefixed cache key
     */
    private function get_cache_key($key) {
        return $this->cache_prefix . $key;
    }

    /**
     * Get cache statistics
     * @return array Cache statistics
     */
    public function get_cache_info() {
        $info = $this->cache_driver->cache_info();
        
        if (is_array($info)) {
            $stats = array(
                'total_keys' => count($info),
                'keys' => array_keys($info),
                'size' => 0
            );
            
            foreach ($info as $key => $data) {
                if (isset($data['size'])) {
                    $stats['size'] += $data['size'];
                }
            }
            
            return $stats;
        }
        
        return array('total_keys' => 0, 'keys' => array(), 'size' => 0);
    }

    /**
     * Warm up cache with commonly accessed data
     */
    public function warm_up_cache() {
        // Pre-load dashboard statistics
        $this->get_dashboard_stats();
        
        // Pre-load dropdown options
        $this->get_dropdown_options('districts');
        $this->get_dropdown_options('taluks');
        $this->get_dropdown_options('property_types');
        $this->get_dropdown_options('villages');
        
        // Pre-load individual statistics
        $this->get_property_stats();
        $this->get_customer_stats();
        $this->get_staff_stats();
    }

    /**
     * Set cache TTL for different data types
     * @param string $type Data type
     * @return int TTL in seconds
     */
    private function get_ttl_for_type($type) {
        $ttl_map = array(
            'dashboard' => 300,      // 5 minutes
            'statistics' => 600,     // 10 minutes
            'dropdown' => 1800,      // 30 minutes
            'reports' => 900,        // 15 minutes
            'user_data' => 3600      // 1 hour
        );
        
        return isset($ttl_map[$type]) ? $ttl_map[$type] : $this->default_ttl;
    }
}