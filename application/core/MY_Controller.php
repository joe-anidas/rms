<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple Base Controller
 * Basic controller with minimal security features
 */
class MY_Controller extends CI_Controller {
    
    protected $csrf_exempt_methods = array(); // Methods exempt from CSRF check
    
    public function __construct() {
        parent::__construct();
        
        // Load basic libraries
        $this->load->helper('url');
        $this->load->library('session');
        
        // Basic security headers
        $this->set_basic_security_headers();
    }
    
    /**
     * Set basic security headers
     */
    protected function set_basic_security_headers() {
        // Prevent clickjacking
        header('X-Frame-Options: SAMEORIGIN');
        
        // Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Enable XSS protection
        header('X-XSS-Protection: 1; mode=block');
    }
    
    /**
     * Simple JSON response helper
     * @param array $data Response data
     * @param int $status_code HTTP status code
     */
    protected function json_response($data, $status_code = 200) {
        $this->output->set_status_header($status_code);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data));
    }
}