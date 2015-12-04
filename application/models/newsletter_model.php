<?php

class newsletter_model extends CI_Model {

	 public function __construct(){
	
        parent::__construct();
		global $table_name;		
		$table_name = 'news_letter';
		
    }
	public function get_all_newsletter(){
		$res = $this->db->select('*')
						->from(' news_letter')						
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res;
	}
	
	public function add_newsletter(){
		//if($this->session->userdata('admin_sample')==1)
			$_POST['user_id']  = 0;
		$query = $this->db->query('SELECT * FROM `news_letter` WHERE `user_id` ='.$_POST['user_id'].' AND `email` ="'.$_POST['email'].'"');	
		if($query->num_rows==0){				
			$data = $this->input->post(NULL, TRUE);
			unset($data['add']);		
			$query = $this->db->insert('news_letter', $data);		
			return $query;
		}
		else{
			$this->session->set_flashdata('message', '<div class="message errormsg"><p>Email Already subscribed for NewsLetter!</p></div>');
			return false;
		}	
	}
	public function update_newsletter($id){		 
		$data = $this->input->post(NULL, TRUE);
		unset($data['add']);
		$query =	 $this->db->where('id',$id)
							  ->update('news_letter', $data);	
		return $query;					   	
	
	}
	public function delete_newsletter($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->delete('news_letter'); 
		return $query;
	}
	
	public function get_newsletter_detail($id){
		$res = $this->db->select('*')
						->from('news_letter')
						->where('id',$id)
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res[0];			
	}		
}