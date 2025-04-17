<?php

defined('BASEPATH') OR exit('No direct script access allowed');


 class Theme_model extends CI_Model
 {
 	
 	function __construct()
 	{
 		parent::__construct();
      
 	}
 public function get_theme_path(){
     $this->db->select('theme_name');
     $this->db->from('user_theme');
     $this->db->where('user_id', '2');
     $query = $this->db->get();
     $result = $query->row();
        if($result){
            return $result->theme_name;
        }else{
            return 'theme1';
        }
    
 }


}
?>