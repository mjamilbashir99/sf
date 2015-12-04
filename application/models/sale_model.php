<?php

class sale_model extends CI_Model {
	public function __construct(){
		parent::__construct();  
	}
	
	
	public function get_sale($id){
		$res = $this->db->select('*')
						->from('sale_type')
						->where('id',$id)
						->get()
						->result_array();
				
		if(count($res)<=0)
			return false;				
		return $res[0];			
	}	
}