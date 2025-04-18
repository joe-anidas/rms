<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('Theme_model');
        // Removed: $this->load->library('session');
    }

    public function index()
    {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('header', $data);
        $this->load->view('dashboard');
        $this->load->view('footer');
    }

    public function registered_plot() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('header', $data);
        $this->load->view('registered_plot');
        $this->load->view('footer');
    }

    public function garden_profile() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('header', $data);
        $this->load->view('garden_profile');
        $this->load->view('footer');
    }

    public function customer_details() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('header', $data);
        $this->load->view('customer_details');
        $this->load->view('footer');
    }

    public function staff_details() {
        $data['theme'] = $this->Theme_model->get_theme_path();
        $this->load->view('header', $data);
        $this->load->view('staff_details');
        $this->load->view('footer');
    }

    public function set_theme(){
        $theme = $this->input->post('theme');
        $user_id = "2";
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_theme');
        
        if($query->num_rows() > 0){
            $this->db->where('user_id', $user_id);
            $this->db->where('theme_id !=', $theme);
            $this->db->update('user_theme', array('theme_name' => $theme));
        }else{
            $this->db->insert('user_theme', array('user_id' => $user_id, 'theme_name' => $theme));
        }
    }
}