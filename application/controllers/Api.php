<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API Controller
 * Handles AJAX requests and API endpoints for the enhanced navigation
 * Requirements: 7.2, 7.6 - Modern UI and responsive design
 */
class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model(['Property_model', 'Customer_model', 'Staff_model', 'Registration_model', 'Transaction_model']);
        $this->load->helper('url');
        
        // Set JSON content type for all API responses
        $this->output->set_content_type('application/json');
    }

    /**
     * Global search endpoint
     * Searches across properties, customers, staff, registrations, and transactions
     */
    public function search_global() {
        try {
            $query = $this->input->get('q');
            
            if (empty($query) || strlen(trim($query)) < 2) {
                $this->output->set_output(json_encode([
                    'success' => false,
                    'message' => 'Search query must be at least 2 characters long'
                ]));
                return;
            }
            
            $query = trim($query);
            $results = [];
            
            // Search properties
            $properties = $this->Property_model->search_properties(['search' => $query, 'limit' => 5]);
            foreach ($properties as $property) {
                $results[] = [
                    'type' => 'property',
                    'title' => $property['garden_name'],
                    'description' => $property['location_details'] . ' - ' . $property['status'],
                    'url' => base_url('properties/' . $property['id']),
                    'icon' => 'zmdi-home'
                ];
            }
            
            // Search customers
            $customers = $this->Customer_model->search_customers(['name' => $query, 'limit' => 5]);
            foreach ($customers as $customer) {
                $results[] = [
                    'type' => 'customer',
                    'title' => $customer['plot_buyer_name'],
                    'description' => 'Customer - ' . $customer['contact_details'],
                    'url' => base_url('customers/' . $customer['id']),
                    'icon' => 'zmdi-account-box'
                ];
            }
            
            // Search staff
            $staff = $this->Staff_model->search_staff(['name' => $query, 'limit' => 5]);
            foreach ($staff as $staff_member) {
                $results[] = [
                    'type' => 'staff',
                    'title' => $staff_member['employee_name'],
                    'description' => $staff_member['designation'] . ' - ' . $staff_member['contact_details'],
                    'url' => base_url('staff/' . $staff_member['id']),
                    'icon' => 'zmdi-accounts'
                ];
            }
            
            // Limit total results
            $results = array_slice($results, 0, 15);
            
            if (empty($results)) {
                $this->output->set_output(json_encode([
                    'success' => true,
                    'results' => [],
                    'message' => 'No results found for "' . htmlspecialchars($query) . '"'
                ]));
            } else {
                $this->output->set_output(json_encode([
                    'success' => true,
                    'results' => $results,
                    'total' => count($results),
                    'query' => $query
                ]));
            }
            
        } catch (Exception $e) {
            log_message('error', 'Global search error: ' . $e->getMessage());
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Search failed. Please try again.'
            ]));
        }
    }

    /**
     * Search suggestions endpoint
     * Provides quick suggestions for search autocomplete
     */
    public function search_suggestions() {
        try {
            $query = $this->input->get('q');
            
            if (empty($query) || strlen(trim($query)) < 2) {
                $this->output->set_output(json_encode([]));
                return;
            }
            
            $query = trim($query);
            $suggestions = [];
            
            // Get quick suggestions from each entity type
            $property_suggestions = $this->Property_model->get_search_suggestions($query, 3);
            $customer_suggestions = $this->Customer_model->get_search_suggestions($query, 3);
            $staff_suggestions = $this->Staff_model->get_search_suggestions($query, 3);
            
            // Format suggestions
            foreach ($property_suggestions as $suggestion) {
                $suggestions[] = [
                    'title' => $suggestion['garden_name'],
                    'description' => 'Property in ' . $suggestion['location_details'],
                    'url' => base_url('properties/' . $suggestion['id']),
                    'icon' => 'zmdi-home'
                ];
            }
            
            foreach ($customer_suggestions as $suggestion) {
                $suggestions[] = [
                    'title' => $suggestion['plot_buyer_name'],
                    'description' => 'Customer',
                    'url' => base_url('customers/' . $suggestion['id']),
                    'icon' => 'zmdi-account-box'
                ];
            }
            
            foreach ($staff_suggestions as $suggestion) {
                $suggestions[] = [
                    'title' => $suggestion['employee_name'],
                    'description' => $suggestion['designation'],
                    'url' => base_url('staff/' . $suggestion['id']),
                    'icon' => 'zmdi-accounts'
                ];
            }
            
            $this->output->set_output(json_encode($suggestions));
            
        } catch (Exception $e) {
            log_message('error', 'Search suggestions error: ' . $e->getMessage());
            $this->output->set_output(json_encode([]));
        }
    }

    /**
     * Get notifications for the user
     */
    public function notifications() {
        try {
            // Mock notifications - implement based on your notification system
            $notifications = [
                [
                    'id' => 1,
                    'title' => 'New Payment Received',
                    'message' => '₹50,000 payment received from John Doe',
                    'type' => 'payment',
                    'icon' => 'zmdi-money',
                    'time' => '2 minutes ago',
                    'read' => false,
                    'url' => base_url('transactions/1')
                ],
                [
                    'id' => 2,
                    'title' => 'Property Status Updated',
                    'message' => 'Plot #123 has been marked as sold',
                    'type' => 'property',
                    'icon' => 'zmdi-home',
                    'time' => '1 hour ago',
                    'read' => false,
                    'url' => base_url('properties/123')
                ],
                [
                    'id' => 3,
                    'title' => 'New Customer Registration',
                    'message' => 'Jane Smith has been registered as a new customer',
                    'type' => 'customer',
                    'icon' => 'zmdi-account-add',
                    'time' => '3 hours ago',
                    'read' => true,
                    'url' => base_url('customers/456')
                ]
            ];
            
            $unread_count = count(array_filter($notifications, function($n) { return !$n['read']; }));
            
            $this->output->set_output(json_encode([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unread_count
            ]));
            
        } catch (Exception $e) {
            log_message('error', 'Notifications error: ' . $e->getMessage());
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Failed to load notifications'
            ]));
        }
    }

    /**
     * Mark notifications as read
     */
    public function notifications_mark_read() {
        try {
            // Implementation would depend on your notification system
            // For now, just return success
            
            $this->output->set_output(json_encode([
                'success' => true,
                'message' => 'Notifications marked as read'
            ]));
            
        } catch (Exception $e) {
            log_message('error', 'Mark notifications read error: ' . $e->getMessage());
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Failed to mark notifications as read'
            ]));
        }
    }

    /**
     * Get navigation menu data
     */
    public function navigation_menu() {
        try {
            $menu = [
                [
                    'title' => 'Dashboard',
                    'url' => base_url('dashboard'),
                    'icon' => 'zmdi-view-dashboard',
                    'active' => $this->is_current_url('dashboard')
                ],
                [
                    'title' => 'Property Management',
                    'icon' => 'zmdi-home',
                    'children' => [
                        [
                            'title' => 'All Properties',
                            'url' => base_url('properties'),
                            'icon' => 'zmdi-view-list'
                        ],
                        [
                            'title' => 'Add New Property',
                            'url' => base_url('properties/create'),
                            'icon' => 'zmdi-plus'
                        ],
                        [
                            'title' => 'Search Properties',
                            'url' => base_url('properties/search'),
                            'icon' => 'zmdi-search'
                        ]
                    ]
                ],
                // Add more menu items as needed
            ];
            
            $this->output->set_output(json_encode([
                'success' => true,
                'menu' => $menu
            ]));
            
        } catch (Exception $e) {
            log_message('error', 'Navigation menu error: ' . $e->getMessage());
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Failed to load navigation menu'
            ]));
        }
    }

    /**
     * Check if URL is current
     */
    private function is_current_url($path) {
        $current_path = $this->uri->uri_string();
        return strpos($current_path, $path) === 0;
    }

    /**
     * Handle method not allowed
     */
    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->output
                ->set_status_header(404)
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'API endpoint not found'
                ]));
        }
    }
}