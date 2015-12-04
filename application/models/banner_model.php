<?php

class banner_model extends CI_Model {

	 public function __construct(){
	
        parent::__construct();
		global $table_name;		
		$table_name = 'banner';
		
    }
	public function get_all_banners(){
		$res = $this->db->select('*')
						->from('banner')						
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res;
	}
	
        /**
         * 
         * Get banners by position
         * 
         * @param string $position Position
         * @return array
         */
        public function get_banners_by_position($position){
		$res = $this->db->select('*')
				->from('banner')						
                                ->where(array('position'=>$position))
				->get()
				->result_array();
		if(count($res)<=0)
			return false;				
		return $res;
	}
	
        public function get_random_banners_by_position($position,$limit){
		$res = $this->db->query("SELECT * FROM banner
                                        WHERE position ='$position'
                                        ORDER BY RAND()
                                        LIMIT $limit")
				->result_array();
						
		return $res;
		$this->output->enable_profiler(TRUE);
	}
        
	public function add_banners(){
		 $data = $this->input->post(NULL, TRUE);
		 unset($data['add']);		
		 return $this->db->insert('banner', $data);
	}
	
	public function update_banner($bannerID,$post){	
		$query = $this->db->where('id',$bannerID)
						->update('banner', $post);	
		return TRUE;						   	
	}
	
	public function delete_banner($banner_id)
	{
		$this->db->where('id',$banner_id);
		$query = $this->db->delete('banner'); 
		return $query;
	}
	
	public function get_banner_detail($banner_id){
		$res = $this->db->select('*')
						->from('banner')
						->where('id',$banner_id)
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res[0];			
	}
			
}