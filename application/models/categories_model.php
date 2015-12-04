<?php

class categories_model extends CI_Model {

	 public function __construct(){
	
        parent::__construct();
		global $table_name;		
		$table_name = 'categories';
		
    }
	public function get_all_categories(){
		$res = $this->db->select('*')
						->from('categories')						
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res;
	}
	public function get_parent_categories(){
		$res = $this->db->select('*')
						->from('categories')
						->where('parent_id',0)	
                                                ->order_by('sort_id','asc')
						->get()
						->result_array();
		if(count($res)<=0)
			return false;				
		return $res;
	}
        
	public function get_all_child_categories(){
		$res = $this->db->select('*')
						->from('categories')
						->where('parent_id !=',0)						
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res;
	}	
	public function get_child_categories($parent_id){
		$res = $this->db->select('*')
						->from('categories')
						->where('parent_id',$parent_id)	
                                                ->order_by('sort_id','asc')
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res;
	}	
        
	public function add_categories(){	
	 	$data  = $this->input->post(NULL, TRUE);
                $filters = $data['filters'];
                $data['filters'] = ($filters)?implode(",",$filters):'';
		unset($data['add']);		
                $data['last_level'] = 1;
		$query = $this->db->insert('categories', $data);
                $parent_id = $data['parent_id'];
                $query = $this->db->query("update categories set last_level = 0 where cat_id = $parent_id");
		
		return $query;
	}
	
	public function update_category($categoryID,$post){	
                $arr = $this->db->where('cat_id',$categoryID)->get('categories')->row_array();
                if($arr['last_level']){
                    //check siblings
                    $sibling_arr = $this->db->where('parent_id',$arr['parent_id'])->get('categories')->row_array();
                    if(!$sibling_arr && !count($sibling_arr)){
                        $this->db->update('categories',array('last_level'=>1),array('cat_id'=>$arr['parent_id']));
                    }
                }
                
                $filters = implode(",",$post['filters']);
                if(!isset($post['show_on_homepage'])){
                    $post['show_on_homepage'] = "";
                }
                $post['filters'] = $filters;
		$query = $this->db->where('cat_id',$categoryID)
						->update('categories', $post);
                $parent_id = $post['parent_id'];
                $query = $this->db->query("update categories set last_level = 0 where cat_id = $parent_id");
		return TRUE;						   	
	}
	
	public function deleteCategory($categoryID)
	{
		$arr = $this->db->where('cat_id',$categoryID)->get('categories')->row_array();
                if($arr['last_level']){
                    //check siblings
                    $sibling_arr = $this->db->where('parent_id',$arr['parent_id'])->get('categories')->row_array();
                    if(!$sibling_arr && !count($sibling_arr)){
                        $only_child = 1;
                    }
                }
		$query = $this->db->delete('categories',array('cat_id'=>$categoryID)); 
                if($only_child){
                    $this->db->update('categories',array('last_level'=>1),array('cat_id'=>$arr['parent_id']));
                }
                
		return $query;
	}
	
	public function get_category_detail($categoryID){
		$res = $this->db->select('*')
						->from('categories')
						->where('cat_id',$categoryID)
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res[0];			
	}
    public function get_category_brand($categoryID){
		$res = $this->db->select('*')
						->from('categories')
						->where('cat_id',$categoryID)
						->get()
						->result_array();	
		if(count($res)<=0)
			return false;				
		return $res[0];			
	}
	    
        public function get_recursive_parents1($category_id){
            $categories = array();
            $res = $this->db->from('categories')->where('cat_id',$category_id)->get()->row_array();
            $cat_id = $res['parent_id'];
            $categories[] = $res;
            while($cat_id){
                echo $sql = "select * from categories where cat_id = $cat_id";
                $res = $this->db->query($sql)->row_array();
                //$res = $this->db->from('categories')->where('cat_id',$cat_id)->get()->row_array();
                $categories[] = $res;
                $cat_id = $res['parent_id'];
            }
            return $categories;
        }
        
        public function get_recursive_parents($category_id)
        {
            $categories = array();
            $arr = $this->db->query("SELECT t1.cat_title AS title1, t1.cat_id as id1, t1.parent_id as pid1,
                                    t2.cat_title as title2, t2.cat_id as id2, t2.parent_id as pid2,
                                    t3.cat_title as title3, t3.cat_id as id3, t3.parent_id as pid3,
                                    t4.cat_title as title4, t4.cat_id as id4, t4.parent_id as pid4
                                    FROM categories AS t1
                                    LEFT JOIN categories AS t2 ON t1.parent_id = t2.cat_id
                                    LEFT JOIN categories AS t3 ON t2.parent_id = t3.cat_id
                                    LEFT JOIN categories AS t4 ON t3.parent_id = t4.cat_id
                                    where
                                     t1.cat_id = '$category_id'")->row_array();
            for($i = 1; $i<=4; $i++){
                if(isset($arr['id'.$i]) && $arr['id'.$i]){
                    $categories[$i-1]['cat_title'] = $arr['title'.$i];
                    $categories[$i-1]['cat_id'] = $arr['id'.$i];
                    $categories[$i-1]['parent_id'] = $arr['pid'.$i];
                }else{
                    break;
                }
            }
            return $categories;
        }
        
        public function get_recursive_childrens($category_id){
            $categories = array();
            $arr = $this->db->query("SELECT t1.cat_title AS title1, t1.cat_id as id1, t1.parent_id as pid1,
                                    t2.cat_title as title2, t2.cat_id as id2, t2.parent_id as pid2,
                                    t3.cat_title as title3, t3.cat_id as id3, t3.parent_id as pid3,
                                    t4.cat_title as title4, t4.cat_id as id4, t4.parent_id as pid4
                                    FROM categories AS t1
                                    LEFT JOIN categories AS t2 ON t2.parent_id = t1.cat_id
                                    LEFT JOIN categories AS t3 ON t3.parent_id = t2.cat_id
                                    LEFT JOIN categories AS t4 ON t4.parent_id = t3.cat_id
                                    where
                                     t1.parent_id = '$category_id'")->result_array();
            foreach($arr as $record){
                for($i = 1; $i<=4; $i++){
                    if($arr['id'.$i]){
                        $categories[$i-1]['cat_title'] = $record['title'.$i];
                        $categories[$i-1]['cat_id'] = $record['id'.$i];
                        $categories[$i-1]['parent_id'] = $record['pid'.$i];
                    }else{
                        break;
                    }
                }
            }
            return $categories;
        }
        
        public function get_recursive_parent_names_string($cat_id ,$seperater = ">>"){
            $parent_names = array();
            $parent_cats = $this->categories_model->get_recursive_parents($cat_id);
            $parents = array_reverse($parent_cats);
            foreach($parents as $parent){
                if($parent['cat_title']){
                    $parent_names[] = $parent['cat_title'];
                }
            }
            $parent_category_name = implode(">>",$parent_names);
            return $parent_category_name;
        }

        public function get_last_level_categories(){
            $cats = $this->db->get_where("categories",array('last_level'=>1))->result_array();
            return $cats;
        }
		
 
        public function get_filters_fields($cat_id){
            $categories = $this->get_recursive_parents($cat_id);
            $cat_id = array();
            foreach($categories as $category){
                $cat_id[] = $category['cat_id'];
            }
            $cat_ids = implode(',',$cat_id);
            $sql = "select * from categories where cat_id in ($cat_ids) and parent_id = 0";
            $fields = $this->db->query($sql)->row_array();
            //echo $this->db->last_query();
            
            $filter_fields = explode(",",$fields['filters']);
            return $filter_fields;
        }
}