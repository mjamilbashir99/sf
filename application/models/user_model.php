<?php

class user_model extends CI_Model {
    
     public static $table = "users";
     
    
     public function __construct(){
		parent::__construct();
	   	if($this->session->userdata('lscollege_user') != ""){
	   		$properties =  $this->select_user_by_id($this->session->userdata('lscollege_user'));
			 foreach($properties as $key => $value){
        		$this->{$key} = $value;
     	    }
	   	}   
	}
	
	public function check_user_exit($fb_id){
		$res = $this->db->select('*')
						->from('users')
						->where('facebook_id',$fb_id)
						->get()
						->result_array();
				
		return count($res);	
	}
	
	public function select_user($fb_id){
		$res = $this->db->select('*')
						->from('users')
						->where('facebook_id',$fb_id)
						->get()
						->result_array();
				
		return $res;
	}
	public function select_user_by_id($user_id){
		$res = $this->db->select('*')
						->from('users')
						->where('id',$user_id)
						->where('group_id',1)
						->get()
						->result_array();			
		return $res;
	}
	public function add_user($data){
		$this->db->insert('users',$data);
		return $this->db->insert_id();
	}
	
}