<?php

class news_model extends CI_Model {

	 public function __construct(){
	
        parent::__construct();
		global $table_name;		
		$table_name = 'news';
		
    }
	public function get_all_news(){
		$res = $this->db->select('*')
						->from('news')						
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res;
	}
	
	public function get_all_news_by_user($user_id){
		$res = $this->db->select('*')
						->from('news')
						->where('user_id',$user_id)						
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res;
	}
	
	public function add_news(){
		$_POST['news_text'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_POST['news_text']);	 		
		$data = $this->input->post(NULL, TRUE);
		$data['user_id'] = ($this->session->userdata('user_id'))?$this->session->userdata('user_id'):0;
		unset($data['add']);		
		$query = $this->db->insert('news', $data);		
		return $query;	
	}
	public function update_news($newsID,$post){	 
		$data = $this->input->post(NULL, TRUE);
		unset($data['add']);
		$query =	 $this->db->where('id',$newsID)
							  ->update('news', $data);	
		return $query;					   	
	
	}
	public function delete_news($NewsID)
	{
		$this->db->where('id',$NewsID);
		$query = $this->db->delete('news'); 
		return $query;
	}
	
	public function get_news_detail($NewsID){
		$res = $this->db->select('*')
						->from('news')
						->where('id',$NewsID)
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res[0];			
	}

		
}