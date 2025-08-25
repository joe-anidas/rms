<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Breadcrumb Helper
 * Generate breadcrumb navigation for enhanced user orientation
 * Requirements: 7.2, 7.6 - Modern UI and responsive design
 */

if (!function_exists('generate_breadcrumbs')) {
    /**
     * Generate breadcrumbs based on current URL and custom data
     * 
     * @param array $custom_breadcrumbs Custom breadcrumb data
     * @return array Breadcrumb array for view
     */
    function generate_breadcrumbs($custom_breadcrumbs = array()) {
        $CI =& get_instance();
        
        // If custom breadcrumbs provided, use them
        if (!empty($custom_breadcrumbs)) {
            return $custom_breadcrumbs;
        }
        
        // Auto-generate from URL
        $segments = $CI->uri->segment_array();
        $breadcrumbs = array();
        
        // Always start with Dashboard
        $breadcrumbs[] = array(
            'title' => 'Dashboard',
            'url' => base_url('dashboard'),
            'icon' => 'zmdi-view-dashboard'
        );
        
        // Define route mappings
        $route_map = array(
            'properties' => array(
                'title' => 'Properties',
                'icon' => 'zmdi-home',
                'children' => array(
                    'create' => 'Add Property',
                    'edit' => 'Edit Property',
                    'view' => 'Property Details',
                    'search' => 'Search Properties',
                    'statistics' => 'Property Statistics'
                )
            ),
            'customers' => array(
                'title' => 'Customers',
                'icon' => 'zmdi-account-box',
                'children' => array(
                    'create' => 'Add Customer',
                    'edit' => 'Edit Customer',
                    'view' => 'Customer Details',
                    'profile' => 'Customer Profile',
                    'search' => 'Search Customers',
                    'analytics' => 'Customer Analytics'
                )
            ),
            'staff' => array(
                'title' => 'Staff',
                'icon' => 'zmdi-accounts',
                'children' => array(
                    'create' => 'Add Staff',
                    'edit' => 'Edit Staff',
                    'view' => 'Staff Details',
                    'profile' => 'Staff Profile',
                    'assignments' => 'Staff Assignments',
                    'workload' => 'Workload Dashboard',
                    'performance' => 'Performance Metrics'
                )
            ),
            'registrations' => array(
                'title' => 'Registrations',
                'icon' => 'zmdi-assignment',
                'children' => array(
                    'create' => 'New Registration',
                    'edit' => 'Edit Registration',
                    'view' => 'Registration Details',
                    'statistics' => 'Registration Statistics'
                )
            ),
            'transactions' => array(
                'title' => 'Transactions',
                'icon' => 'zmdi-money',
                'children' => array(
                    'record-payment' => 'Record Payment',
                    'edit' => 'Edit Transaction',
                    'view' => 'Transaction Details',
                    'schedules' => 'Payment Schedules',
                    'pending' => 'Pending Payments',
                    'reports' => 'Financial Reports'
                )
            ),
            'reports' => array(
                'title' => 'Reports',
                'icon' => 'zmdi-chart',
                'children' => array(
                    'sales' => 'Sales Report',
                    'bookings' => 'Booking Report',
                    'financial' => 'Financial Summary',
                    'customer-analytics' => 'Customer Analytics',
                    'staff-performance' => 'Staff Performance',
                    'property-analytics' => 'Property Analytics'
                )
            ),
            'analytics' => array(
                'title' => 'Analytics',
                'icon' => 'zmdi-trending-up',
                'children' => array(
                    'properties' => 'Property Analytics',
                    'financial' => 'Financial Analytics',
                    'customers' => 'Customer Analytics',
                    'staff' => 'Staff Analytics'
                )
            )
        );
        
        // Build breadcrumbs from segments
        $current_url = base_url();
        
        for ($i = 1; $i <= count($segments); $i++) {
            $segment = $segments[$i];
            $current_url .= $segment . '/';
            
            if ($i == 1 && isset($route_map[$segment])) {
                // Main section
                $breadcrumbs[] = array(
                    'title' => $route_map[$segment]['title'],
                    'url' => rtrim($current_url, '/'),
                    'icon' => $route_map[$segment]['icon']
                );
            } elseif ($i == 2 && isset($segments[1]) && isset($route_map[$segments[1]]['children'][$segment])) {
                // Sub-section
                $breadcrumbs[] = array(
                    'title' => $route_map[$segments[1]]['children'][$segment],
                    'url' => rtrim($current_url, '/'),
                    'icon' => null
                );
            } elseif (is_numeric($segment)) {
                // ID segment - try to get entity name
                $entity_name = get_entity_name($segments[1], $segment);
                if ($entity_name) {
                    $breadcrumbs[] = array(
                        'title' => $entity_name,
                        'url' => rtrim($current_url, '/'),
                        'icon' => null
                    );
                }
            }
        }
        
        return $breadcrumbs;
    }
}

if (!function_exists('get_entity_name')) {
    /**
     * Get entity name by ID for breadcrumbs
     * 
     * @param string $entity_type Entity type (properties, customers, etc.)
     * @param int $entity_id Entity ID
     * @return string|null Entity name
     */
    function get_entity_name($entity_type, $entity_id) {
        $CI =& get_instance();
        
        try {
            switch ($entity_type) {
                case 'properties':
                    $CI->load->model('Property_model');
                    $property = $CI->Property_model->get_property($entity_id);
                    return $property ? $property['garden_name'] : null;
                    
                case 'customers':
                    $CI->load->model('Customer_model');
                    $customer = $CI->Customer_model->get_customer($entity_id);
                    return $customer ? $customer['plot_buyer_name'] : null;
                    
                case 'staff':
                    $CI->load->model('Staff_model');
                    $staff = $CI->Staff_model->get_staff($entity_id);
                    return $staff ? $staff['employee_name'] : null;
                    
                case 'registrations':
                    $CI->load->model('Registration_model');
                    $registration = $CI->Registration_model->get_registration($entity_id);
                    return $registration ? 'Registration #' . $registration['registration_number'] : null;
                    
                case 'transactions':
                    $CI->load->model('Transaction_model');
                    $transaction = $CI->Transaction_model->get_transaction($entity_id);
                    return $transaction ? 'Transaction #' . $transaction['id'] : null;
                    
                default:
                    return null;
            }
        } catch (Exception $e) {
            // Log error and return null
            log_message('error', 'Failed to get entity name: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('set_breadcrumbs')) {
    /**
     * Set breadcrumbs for a view
     * 
     * @param array $breadcrumbs Breadcrumb array
     */
    function set_breadcrumbs($breadcrumbs) {
        $CI =& get_instance();
        $CI->load->vars(array('breadcrumbs' => $breadcrumbs));
    }
}

if (!function_exists('add_breadcrumb')) {
    /**
     * Add a single breadcrumb
     * 
     * @param string $title Breadcrumb title
     * @param string $url Breadcrumb URL (optional)
     * @param string $icon Breadcrumb icon (optional)
     */
    function add_breadcrumb($title, $url = null, $icon = null) {
        $CI =& get_instance();
        
        // Get existing breadcrumbs or initialize
        $breadcrumbs = isset($CI->breadcrumbs) ? $CI->breadcrumbs : array();
        
        $breadcrumbs[] = array(
            'title' => $title,
            'url' => $url,
            'icon' => $icon
        );
        
        $CI->breadcrumbs = $breadcrumbs;
        $CI->load->vars(array('breadcrumbs' => $breadcrumbs));
    }
}

if (!function_exists('render_breadcrumbs')) {
    /**
     * Render breadcrumbs HTML
     * 
     * @param array $breadcrumbs Breadcrumb array
     * @param string $separator Breadcrumb separator
     * @return string Breadcrumb HTML
     */
    function render_breadcrumbs($breadcrumbs = null, $separator = '›') {
        $CI =& get_instance();
        
        if ($breadcrumbs === null) {
            $breadcrumbs = generate_breadcrumbs();
        }
        
        if (empty($breadcrumbs)) {
            return '';
        }
        
        $html = '<nav aria-label="breadcrumb">';
        $html .= '<ol class="breadcrumb breadcrumb-modern">';
        
        $total = count($breadcrumbs);
        foreach ($breadcrumbs as $index => $crumb) {
            $is_last = ($index === $total - 1);
            
            if ($is_last) {
                $html .= '<li class="breadcrumb-item active" aria-current="page">';
                if (!empty($crumb['icon'])) {
                    $html .= '<i class="' . $crumb['icon'] . ' me-1"></i>';
                }
                $html .= htmlspecialchars($crumb['title']);
                $html .= '</li>';
            } else {
                $html .= '<li class="breadcrumb-item">';
                if (!empty($crumb['url'])) {
                    $html .= '<a href="' . $crumb['url'] . '" class="text-decoration-none">';
                }
                if (!empty($crumb['icon'])) {
                    $html .= '<i class="' . $crumb['icon'] . ' me-1"></i>';
                }
                $html .= htmlspecialchars($crumb['title']);
                if (!empty($crumb['url'])) {
                    $html .= '</a>';
                }
                $html .= '</li>';
            }
        }
        
        $html .= '</ol>';
        $html .= '</nav>';
        
        return $html;
    }
}

if (!function_exists('get_page_title_from_breadcrumbs')) {
    /**
     * Get page title from breadcrumbs
     * 
     * @param array $breadcrumbs Breadcrumb array
     * @return string Page title
     */
    function get_page_title_from_breadcrumbs($breadcrumbs = null) {
        if ($breadcrumbs === null) {
            $breadcrumbs = generate_breadcrumbs();
        }
        
        if (empty($breadcrumbs)) {
            return 'RMS';
        }
        
        // Get the last breadcrumb as page title
        $last_crumb = end($breadcrumbs);
        return $last_crumb['title'];
    }
}