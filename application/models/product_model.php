<?php
class product_model extends CI_Model {
        public static $table = "products";
	    public function __construct(){
		parent::__construct();  
	}
	    public function get_product($id){
		$res = $this->db->select('*')
						->from(self::$table)
						->where('id',$id)
						->get()
						->result_array();
				
		if(count($res)<=0)
			return false;				
		return $res[0];			
	}
	
	public function get_product_detail($id,$public = 0){
            if($public){
                $where = "( ( NOW() BETWEEN product_deal.sale_start_date and product_deal.sale_end_date) OR (product_deal.sale_end_date = '0000-00-00')) ";
            }else{
                $where = '';
            }
	    	$query = $this->db->select('*, products.id as pro_id')
						->from(self::$table)
						->join('product_deal','product_deal.product_id = products.id','left')
						//->join('cities','cities.city_id = products.city_id','left')
						->join('categories','categories.cat_id = products.cat_id','left')
						->where('products.id',$id)
						->where('products.status',1)
						//->order_by('product_price','desc')
						;

                if($public){
                    $query =  $query->where($where);
                }
					$res = $query->get()
						->row_array();
                
		if(count($res)<=0)
			return false;				
		return $res;			
	} 

	public function find_products($searchterm){		
		$this->db->like('product_name', $searchterm);
		$res = $this->db->select('*')
						->from(self::$table)
						->get()
						->result_array();								
		if(count($res)<=0)
			return false;				
		return $res;			
	} 
	
	public function get_all_products(){
		$res = $this->db->select('*, products.id as pro_id')
						->from(self::$table)
						->join('product_deal','product_deal.product_id = products.id','left')
						->join('cities','cities.city_id = products.city_id','left')
						->join('categories','categories.cat_id = products.cat_id','left')
						->order_by('products.product_date','desc')
                                               
						->get()
						->result_array();
		$this->db->last_query();			
		if(count($res)<=0)
			return false;				
		return $res;			
	}
	
	public function get_all_products_by_user($user_id,$limit = '', $offset = ''){
		$this->db->select('*, products.id as pro_id');
		$this->db->from(self::$table);
		$this->db->join('product_deal','product_deal.product_id = products.id');
		$this->db->join('cities','cities.city_id = products.city_id','left');
		$this->db->join('categories','categories.cat_id = products.cat_id');
		$this->db->where('products.status',1);
      
		$this->db->where('products.user_id',$user_id);
		$this->db->order_by('products.product_date','desc');
		if($limit){
			$this->db->limit($limit,$offset);
		}
		$res = $this->db->get()->result_array();
		if(count($res)<=0)
			return false;				
		return $res;			
	}
	
	public function get_gallery_products(){
		$res = $this->db->select('*')
						->from(self::$table)
						->where('show_in_gallery',1)
						->get()
						->result_array();
		return $res;			
	}
	
	public function get_toprated_products($limit){
		if($limit)
			$this->db->limit($limit);
		$this->db->order_by("product_rating", "desc"); 
		$res = $this->db->select('*')
						->from(self::$table)
						->get()
						->result_array();
		return $res;			
	}
	
	
	
	
	public function get_product_more_images($id){
		$res = $this->db->select('*')
						->from('product_images')
						->where('product_id',$id)
						->get()
						->result_array();
				
		if(count($res)<=0)
			return false;				
		return $res;			
	}
	
	public function add_product(){ 
		$_POST['product_description'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_POST['product_description']);		
	 	$data = $this->input->post(NULL, TRUE);
		$data['user_id'] = ($this->session->userdata('user_id'))?$this->session->userdata('user_id'):0;
		$data['product_buy_link'] = ($data['buy_link_type'] == 1)?$data['product_buy_link1']:$data['product_buy_link2'];
		
		$product_moreimages = explode(',',$data['product_moreimages']);
		$product_moreimagesext = explode(',',$data['product_moreimagesext']);
		$sale_type = $data['sale_type_id'];
		$sale_value_arr = $data['sale_value'];
		if($sale_type == 1){
		$data['sale_price'] = $sale_value_arr[$sale_type-1];
		}
		elseif($sale_type == 2){
				$data['sale_price'] = ($data['product_price']-(($data['product_price']*$sale_value_arr[$sale_type-1])/100));
		}
		elseif ($sale_type == 3) {
			$data['sale_price'] = ($data['product_price']/$sale_value_arr[$sale_type-1]);
		}
		else{
			$data['sale_price'] = $data['product_price'];
		}
		unset($data['product_buy_link1']);
		unset($data['product_buy_link2']);
		unset($data['product_moreimages']);
		unset($data['product_moreimagesext']);
                unset($data['more_upload']);
	 	unset($data['sale_type_id']);
		unset($data['sale_value']);
		unset($data['sale_start_date']);
		unset($data['sale_end_date']);
		unset($data['pcat_id']);
		unset($data['add']);	
		$res = $this->db->insert('products', $data);
		$lastid = $this->db->insert_id();
		// Add more images
		if(is_array($product_moreimages) && count($product_moreimages) > 0){
			$pimges = array();
			foreach ($product_moreimages as $key => $value) {
				if(file_exists($this->config->item('pro_image_path').$value.'_o.'.$product_moreimagesext[$key]))
					$pimges[] = array('product_id'=>$lastid,'image_name'=>$value,'image_ext'=>$product_moreimagesext[$key]);
			}
			if(count($pimges) > 0)
				$this->db->insert_batch('product_images', $pimges);
		}
		return $lastid;
	}
	
	public function add_product_deal($data){		
		return $this->db->insert('product_deal', $data);
	}
	
	public function update_product_deal($id, $data){		
		$this->db->where('product_id', $id)
				->update('product_deal', $data);
	}
	
	public function save_rating($data){
		$this->db->insert('user_product_rating', $data);
		$this->update_product_rating($data['id']);
		return;
	}
	
	public function update_product_rating($id){
		$data = array();
		$res = $this->db->query("select SUM(rating) as totalrate, COUNT(*) as numrate from user_product_rating 
								where id = {$id}"
								)->result_array();
		$data['product_rating']  = ($res[0]['totalrate']/$res[0]['numrate']);
		$this->db->where('id', $id)
			 ->update('products', $data);
	}
	
	public function update_product_buy_count($id){
		$query = "update products set buy_link_hits = (buy_link_hits+1) where id = {$id}";
		$this->db->query($query);
	}
	
	public function delete_rating($rating_id)
	{
		$this->db->delete('user_product_rating', array('rating_id' => $rating_id));
	}
	
	public function save_product(){
		$data = $this->input->post(NULL, TRUE);
		$data['product_name'] = htmlentities($data['product_name'],ENT_COMPAT, "UTF-8");
		$data['product_tagline'] = htmlentities($data['product_tagline'],ENT_COMPAT, "UTF-8");
		$this->db->insert('products', $data);
		//$id = $this->db->insert_id();
		return;
	}
	
	public function update_product($id){
		$data = $this->input->post(NULL, TRUE);		
		if(isset($data['is_featured']))
			$data['is_featured'] = 1;
		else {
			$data['is_featured'] = 0;
		}
		$data['product_buy_link'] = ($data['buy_link_type'] == 1)?$data['product_buy_link1']:$data['product_buy_link2'];
		$product_moreimages = explode(',',$data['product_moreimages']);
		$product_moreimagesext = explode(',',$data['product_moreimagesext']);
		$sale_type = $data['sale_type_id'];
		$sale_value_arr = $data['sale_value'];
		if($sale_type == 1){
		$data['sale_price'] = $sale_value_arr[$sale_type-1];
		}
		elseif($sale_type == 2){
				$data['sale_price'] = ($data['product_price']-(($data['product_price']*$sale_value_arr[$sale_type-1])/100));
		}
		elseif ($sale_type == 3) {
			$data['sale_price'] = ($data['product_price']/$sale_value_arr[$sale_type-1]);
		}
		else{
			$data['sale_price'] = $data['product_price'];
		}
		unset($data['product_moreimages']);
		unset($data['product_moreimagesext']);
                unset($data['more_upload']);
		unset($data['product_buy_link1']);
		unset($data['product_buy_link2']);
	 	unset($data['sale_type_id']);
		unset($data['sale_value']);
		unset($data['sale_start_date']);
		unset($data['sale_end_date']);
		unset($data['pcat_id']);
		unset($data['add']);
		$this->db->where('id', $id)->update('products', $data);
		// Add more images
		if(is_array($product_moreimages) && count($product_moreimages) > 0){
			// empty existiing records
			$this->db->delete('product_images',array('product_id'=>$id));
			$pimges = array();
			foreach ($product_moreimages as $key => $value) {
				if(file_exists($this->config->item('pro_image_path').$value.'_o.'.$product_moreimagesext[$key]))
					$pimges[] = array('product_id'=>$id,'image_name'=>$value,'image_ext'=>$product_moreimagesext[$key]);
			}
			if(count($pimges) > 0)
				$this->db->insert_batch('product_images', $pimges);
		}
	}
	
	public function delete_product($id){
		$product = $this->get_product($id);
		// unlink product images:
		if (file_exists($this->config->item('pro_image_path').'/'.$product['product_image'].'_o.'.$product['product_ext'])) {
			unlink( $this->config->item('pro_image_path').'/'.$product['product_image'].'_o.'.$product['product_ext'] );
			unlink( $this->config->item('pro_image_path').'/'.$product['product_image'].'_m.'.$product['product_ext'] );
			unlink( $this->config->item('pro_image_path').'/'.$product['product_image'].'_s.'.$product['product_ext'] );
		}
		// delete product:
		$this->db->delete('product_deal', array('product_id' => $id));
		$this->db->delete('product_review', array('product_id' => $id));
		$this->db->delete('products', array('id' => $id));  
	}
	
        
	public function product_by_category($category_id,$limit = NULL, $offset = NULL)
	{
            $all_child_cat = implode(',', mychildrecur($category_id)); 

            $cat_detail = $this->db->query("select * from categories where cat_id = {$category_id};")->row_array();
            if($all_child_cat != ''){
                    $where = 'products.cat_id IN ('.$category_id.','.$all_child_cat.')';
            }
            else{
                $where = "products.cat_id = {$category_id}";
            }
			  // $where = "products.cat_id = {$category_id}";

            $filters = explode(",",$cat_detail['filters']);
            $query_params = $this->input->get();
			if(isset($query_params['brand']))
			{
			   
			    $raw_brands = str_replace(array("-","_",'.'),array(" ","&","','"),$query_params['brand']);
			}
			else
			{
				$raw_brands='';
			}
			if(isset($query_params['min_price']))
			{
			   
			    $min_price = $query_params['min_price'];
			}
			else
			{
				$min_price=0;
			}
			if(isset($query_params['max_price']))
			{
			   
			    $max_price = $query_params['max_price'];
			}
			else
			{
				$max_price=0;
			}
           if($raw_brands!=''){
                $where .= " AND products.brand in ('".$raw_brands."')";
            }
			if($min_price>0){
                $where .= " AND products.sale_price>=".$min_price;
            }
			if($max_price>0){
                $where .= " AND products.sale_price<=".$max_price;
            }
			
			$where .= " and products.status=1 ";
            
            ($limit       === NULL) ? ($limit = 1) : ($limit = $limit);
            ($offset      === NULL) ? ($offset = 0) : ($offset = $offset);
		$res = $this->db->select('*, products.id as pro_id')
						->from(self::$table)
						->join('product_deal','product_deal.product_id = products.id')
						->join('cities','cities.city_id = products.city_id','left')
						->join('categories','categories.cat_id = products.cat_id')
						->where("( ( NOW() BETWEEN product_deal.sale_start_date and product_deal.sale_end_date) OR (product_deal.sale_end_date = '0000-00-00') )")
						->where($where)
						->limit($limit,$offset)					
                                                ->order_by('pro_id','desc')
						->get()
						->result_array();		
                //echo $this->db->last_query();
		if(count($res)<=0)
			return false;						
		return $res;		
	}

	public function product_by_category_left($category_id)
	{	
		$res = $this->db->select('*, products.id as pro_id')
						->from(self::$table)
						->join('product_deal','product_deal.product_id = products.id')
						->join('cities','cities.city_id = products.city_id','left')
						->join('categories','categories.cat_id = products.cat_id')
                        			->where("( ( NOW() BETWEEN product_deal.sale_start_date and product_deal.sale_end_date) OR (product_deal.sale_end_date = '0000-00-00') )")
						->where('products.cat_id',$category_id)										
						->get()
						->result_array();		
		if(count($res)<=0)
			return false;						
		return $res;		
	}
	
	public function more_product_by_category($category_id, $pro_id,$limit){

		$res = $this->db->select('*, products.id as pro_id')
						->from(self::$table)
						->join('product_deal','product_deal.product_id = products.id')
						->join('cities','cities.city_id = products.city_id','left')
						->join('categories','categories.cat_id = products.cat_id')
						->where('products.status',1)
						->where('products.cat_id',$category_id)
						->where_not_in('products.id', $pro_id)
                                                ->where("( ( NOW() BETWEEN product_deal.sale_start_date and product_deal.sale_end_date) OR (product_deal.sale_end_date = '0000-00-00') )")
												->order_by('products.sale_price','DESC')
                                                ->limit($limit)
                                                                   
                                                ->get()
						->result_array();		


		if(count($res)<=0)
			return false;						
		return $res;
    }
	public function more_product_by_retailer($store_name,$pro_id,$limit){

		$res = $this->db->select('*, products.id as pro_id')
						->from(self::$table)
						->join('product_deal','product_deal.product_id = products.id')
						->join('cities','cities.city_id = products.city_id','left')
						->join('categories','categories.cat_id = products.cat_id')
						->where('products.status',1)
						->where('products.store_name',$store_name)
						->where_not_in('products.id', $pro_id)
                        ->where("( ( NOW() BETWEEN product_deal.sale_start_date and product_deal.sale_end_date) OR (product_deal.sale_end_date = '0000-00-00') )")
						->order_by('products.sale_price','DESC')
                                                ->limit($limit)
                                                ->get()
                                                
						->result_array();		

		if(count($res)<=0)
			return false;						
		return $res;
    }
	
	public function product_by_price($min, $max,$cat_id, $limit){

		$res = $this->db->select('*, products.id as pro_id')
						->from(self::$table)
						->join('product_deal','product_deal.product_id = products.id')
						->join('cities','cities.city_id = products.city_id','left')
						->join('categories','categories.cat_id = products.cat_id')
						->where("products.sale_price < {$max}")
						->where('products.status',1)
						->where("( ( NOW() BETWEEN product_deal.sale_start_date and product_deal.sale_end_date) OR (product_deal.sale_end_date = '0000-00-00') ) and products.cat_id='$cat_id'")
						->limit($limit)
						->order_by('products.sale_price','DESC')
                                                ->get()
                                               
						->result_array();

		if(count($res)<=0)
			return false;						
		return $res;
    }
	
	public function total_product_by_category($category_id)
	{
		$all_child_cat = implode(',', mychildrecur($category_id));	
		$cat_detail = $this->db->query("select parent_id from categories where cat_id = {$category_id};")->row_array();
		if($all_child_cat != ''){
			$where = 'products.cat_id IN('.$category_id.','.$all_child_cat.')';
        }
		else{
            $where = "products.cat_id = {$category_id}";
        }
		               // $where = "products.cat_id = {$category_id}";

		$query_params = $this->input->get();
		if(isset($query_params['brand']))
			{
			   
			   $raw_brands = str_replace(array("-","_",'.'),array(" ","&","','"),$query_params['brand']);
			}
			else
			{
				$raw_brands='';
			}
			if(isset($query_params['min_price']))
			{
			   
			    $min_price = $query_params['min_price'];
			}
			else
			{
				$min_price=0;
			}
			if(isset($query_params['max_price']))
			{
			   
			    $max_price = $query_params['max_price'];
			}
			else
			{
				$max_price=0;
			}
            if($raw_brands!=''){
                $where .= " AND products.brand in ('".$raw_brands."')";
            }
			if($min_price>0){
                $where .= " AND products.sale_price>=".$min_price;
            }
			if($max_price>0){
                $where .= " AND products.sale_price<=".$max_price;
            }
		$where .= " and products.status=1 ";
		$query = $this->db->select('count(*) as products_total')						
						->join('product_deal','product_deal.product_id = products.id')
						->join('cities','cities.city_id = products.city_id','left')
						->join('categories','categories.cat_id = products.cat_id')
                        			->where("( ( NOW() BETWEEN product_deal.sale_start_date and product_deal.sale_end_date) OR (product_deal.sale_end_date = '0000-00-00') )")
						->where($where)
                                               
						->get('products')->row_array();
                //echo $this->db->last_query();
                $res = (isset($query['products_total']) && $query['products_total'] )? intval($query['products_total']):0;	
		return $res;
                
	}
	
	public function product_is_featured()
	{
		$res = $this->db->select('*, products.id as pro_id')
						->from('products')
						->join('product_deal','product_deal.product_id = products.id')
						->join('cities','cities.city_id = products.city_id')
						->join('categories','categories.cat_id = products.cat_id')
						->where('products.Is_featured',1)
						->where('products.status',1)
						->get()
                                               
						->result_array();
				
		if(count($res)<=0)
			return false;				
		return $res;		
	}
        
        
        
        
	public function searh_form_total($city_id="",$retailer_id="",$searchterm="")
	{
		
                $arr = array();
		if(!empty($searchterm)){
		   $where =  "(products.product_name LIKE '%$searchterm%' OR products.product_description LIKE '%$searchterm%')";	
		   
		}else{
                    $where = array();
                }
		if(!empty( $retailer_id)){
                    $arr['products.store_name'] = $retailer_id;
		}
		elseif($retailer_id=="" && !empty($city_id)){
                    $arr['city_id'] = $city_id;
		}
		elseif(empty($city_id)&& empty( $retailer_id)){
                    
		}
		
                
		$query = $this->db->select('count(products.id) as products_total')
						->from('products')
						->join('product_deal','product_deal.product_id = products.id')
						->join('cities','cities.city_id = products.city_id','left')
						->join('categories','categories.cat_id = products.cat_id')						
                        			->where("( ( NOW() BETWEEN product_deal.sale_start_date and product_deal.sale_end_date) OR (product_deal.sale_end_date = '0000-00-00') )")
						->where($where)
						->where($arr)
                                               
						->get()
						->row_array();
		$res = (isset($query['products_total']) && $query['products_total'] )? intval($query['products_total']):0;	
		return $res;
			
	}
	public function searh_form($city_id="",$retailer_id="",$searchterm="",$limit = NULL, $offset = NULL)
	{
		
		$query = '';
                $arr = array();
		if($limit!= NULL){
			$this->db->limit($limit,$offset);
		}
		if(!empty($searchterm)){
		   $where =  "(products.product_name LIKE '%$searchterm%' OR products.product_description LIKE '%$searchterm%')";	
		   
		}else{
                    $where = array();
                }
		if(!empty( $retailer_id)){
                    $arr['products.store_name'] = $retailer_id;
		}
		elseif($retailer_id=="" && !empty($city_id)){
                    $arr['city_id'] = $city_id;
		}
		elseif(empty($city_id)&& empty( $retailer_id)){
                    
		}
                
		$query = $this->db->select('*, products.id as pro_id')
						->from('products')
						->join('product_deal','product_deal.product_id = products.id')
						->join('cities','cities.city_id = products.city_id','left')
						->join('categories','categories.cat_id = products.cat_id')						
                        			->where("( ( NOW() BETWEEN product_deal.sale_start_date and product_deal.sale_end_date) OR (product_deal.sale_end_date = '0000-00-00') )")
						->where($where)
						->where($arr)
                                                
						->get()
						->result_array();
		$res = $query ;					
		if(count($res)<=0)
			return false;				
		return $res;
			
	}
        
	public function product_up($id,$product_count){
		$res = $this->db->set('product_view',$product_count)
						->where('id', $id)
						->update('products');
		$data['product_id']       = $id; 
		//$data['referred_datetime']= date('Y-m-d H:i:s');			
		//$this->db->insert('product_stats', $data);				
		return $res;				
	}
        
        
        /**
         * 
         * Get popular sales on the base of products having most buy_links_hits
         * 
         * @param int $limit
         * @return array $popular_sales
         */
        public function get_popular_sales($limit = 25){
            $sql = "select * from ".self::$table." as p inner join product_deal as pd on p.id = pd.product_id 
                    where ( ( NOW() BETWEEN pd.sale_start_date and pd.sale_end_date) OR (pd.sale_end_date = '0000-00-00') )   order by rand() desc limit $limit" ;       
            
            $popular_sales = $this->db->query($sql)->result_array();
			 return $popular_sales;
            $popular_sales = $this->db->from(self::$table." as p")
                                      ->join('product_deal as pd', 'p.id = pd.product_id','left')
                                      //->where('sale_start_date <= now()')
                                     // ->where('sale_end_date >= now()')
                                     //  ->group_by('p.api_product_id')
                                      //->order_by('buy_link_hits desc')
                                      ->get(self::$table,$limit)->result_array();
             /**/
           
        }
		


	
}