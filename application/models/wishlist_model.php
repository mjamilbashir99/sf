<?php

class wishlist_model extends CI_Model {

	 public function __construct(){
	
        parent::__construct();
		global $table_name;		
		$table_name = 'wishlist';
		
    }
	public function get_all_wishedproducts($limit = NULL, $offset = NULL){
		if(!$this->tank_auth->is_logged_in(TRUE)){
			if($this->session->userdata('sess_wishlist')){
				$products = $this->session->userdata('sess_wishlist');
				$res = array();
				foreach ($products as $key => $value) {
					$res[] = array('wish_id'=>$key, 'product_id'=>$value);
				}
			}
			else{
				$res = array();
			}
		}
		else{ 
			($limit       === NULL) ? ($limit = 1) : ($limit = $limit);
	    	($offset      === NULL) ? ($offset = 0) : ($offset = $offset);
			$res = $this->db->select('*')
							->from('wishlist')
							->where('user_id',$this->session->userdata('user_id'))
							->limit($limit,$offset)								
							->get()
							->result_array();
		}	
		if(count($res)<=0)
			return false;				
		return $res;
	}
	public function get_all_wishedproducts_count(){ 
		if(!$this->tank_auth->is_logged_in(TRUE)){
			if($this->session->userdata('sess_wishlist')){
				$res = $this->session->userdata('sess_wishlist');
			}
			else{
				$res = array();
			}
		}
		else{
			$user_id = $this->session->userdata('user_id');
			$res = $this->db->query("select * from wishlist where user_id = '{$user_id}';")
						->result_array();
		}
		if(count($res)<=0)
			return false;				
		return count($res);
	}	
	public function add_wishlist_product(){
		$user_id = $this->session->userdata('user_id');
		$_POST['user_id']  = $user_id;
		$query = $this->db->query('SELECT * FROM `wishlist` WHERE `user_id` ="'.$_POST['user_id'].'" AND `product_id` ='.$_POST['product_id'].'');	
		if($query->num_rows==0){				
			$data = $this->input->post(NULL, TRUE);
			$data['user_id'] = $_POST['user_id'];
			unset($data['add']);		
			$query = $this->db->insert('wishlist', $data);		
			return $query;
		}
		else{
			$this->session->set_flashdata('message', '<div class="message errormsg"><p>Product Already in Wish List!</p></div>');
			return false;
		}	
	}
	public function update_wishlist($id){		 
		$data = $this->input->post(NULL, TRUE);
		unset($data['add']);
		$query =	 $this->db->where('wish_id',$id)
							  ->update('wishlist', $data);	
		return $query;					   	
	}
	
	public function update_session_wishlist($id){
		if($this->session->userdata('sess_wishlist')){
			$products = $this->session->userdata('sess_wishlist');
			$res = array();
			foreach ($products as $key => $value) {
				if(!$this->get_wishlist_detail_product($value))
					$res[] = array('user_id'=>$id, 'product_id'=>$value);
			}
			if(count($res) > 0)
				$this->db->insert_batch('wishlist', $res); 
			$this->session->unset_userdata('sess_wishlist');
		}		 					   	
	}
	
	public function delete_wishlist_product($id)
	{
		$this->db->where('wish_id',$id);
		$query = $this->db->delete('wishlist'); 
		return $query;
	}
	
	public function delete_wish_product($id)
	{
		$this->db->where('product_id',$id);
		$this->db->where('user_id',$this->session->userdata('user_id'));
		$query = $this->db->delete('wishlist'); 
		return $query;
	}
	
	public function get_wishlist_detail($id){
		$res = $this->db->select('*')
						->from('wishlist')
						->where('wish_id',$id)
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res[0];			
	}	
	public function get_wishlist_detail_product($id){
		if(!$this->tank_auth->is_logged_in(TRUE)){
			if($this->session->userdata('sess_wishlist')){
				$res = $this->session->userdata('sess_wishlist');
				if(in_array($id, $res))
					return TRUE;
				return FALSE;
			}
			else{
				return FALSE;
			}
		}
		else{
			$res = $this->db->select('*')
							->from('wishlist')
							->where('product_id',$id)
							->where('user_id',$this->session->userdata('user_id'))
							->get()
							->row_array();
		}	
		if(count($res)<=0)
			return false;				
		return true;			
	}		
}