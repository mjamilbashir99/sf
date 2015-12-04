<?php

class content_model extends CI_Model {
	
	public function get_all_content(){
		$res = $this->db->select('*')
						->from('content')
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res;			
	}
	
	public function get_content($content_id){
		$res = $this->db->select('*')
						->from('content')
						->where('c_id',$content_id)
						->get()
						->row_array();	
		if(count($res)<=0)
			return false;				
		return $res;			
	}
	
	public function add_content(){
		$_POST['c_text'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_POST['c_text']);
		$data = $this->input->post(NULL, TRUE);
		unset($data['add']);
		$this->db->insert('content', $data);
	}
	
	public function update_content($content_id){
		$_POST['c_text'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_POST['c_text']);
		$data = $this->input->post(NULL, TRUE);
		unset($data['add']);
		$this->db->where('c_id',$content_id)
				->update('content', $data);
	}
	
	public function delete_content($content_id)
	{
		$this->db->delete('content', array('c_id' => $content_id));
	}
	
	public function get_all_cities(){
		$res = $this->db->select('*')
						->from('cities')						
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res;
	}
	public function get_all_countries(){
		$res = $this->db->select('*')
						->from('countries')						
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res;
	}
}