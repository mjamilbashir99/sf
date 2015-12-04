<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Product extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('tank_auth');
		$this->load->model('categories_model');
		$this->load->model('product_model');
		$this->load->model('admin_model');
		$this->load->model('content_model');
		$this->load->model('sale_model');
		$this->load->model('wishlist_model');
		$this->load->model('newsletter_model');
		$this->load->model('tank_auth/users');
		$this->load->library('my_upload');
		$this->load->library('pagination');
		// $this->tank_auth->logout();
	}

	function index() {
		ini_set('memory_limit', '-1');
		if ($this->input->post('search')) {
			$products = $this->product_model->find_products($this->input->post('product_name'));
		} else {
			$products = $this->product_model->get_all_products();
		}
		$product_html = $this->create_product_html($products);
		$data['categories']   = $this->categories_model->get_all_categories();
		$retailers    = $this->admin_model->get_all_users(2);
		$catingories_listing = '<ul>';
		if ($categories) {
			foreach ($categories as $category) {
				$catingories_listing .= '<li><a href="#" class="cat" rel="' . $category['cat_id'] . '" id="category_"' . $category['cat_id'] . '>' . $category['cat_name'] . '</a></li>';
			}
			$catingories_listing .= '</ul>';
		}
		$data['cities'] = $this->content_model->get_all_cities();
		$data['retailers'] = $retailers;
		$data['categories_listing'] = $catingories_listing;
		$data['product_listing'] = $product_html;
		$this->load->view('responsive/product.php', $data);
	}

	public function product_detail($product_id) {
		$product_detail = $this->product_model->get_product_detail($product_id,0);	
				if(!$product_detail){
                  redirect(base_url());
                }
		$product_view = $product_detail['product_view'] + 1;
		$update_view = $this->product_model->product_up($product_id, $product_view);
		$sale_info = $this->sale_model->get_sale($product_detail['sale_type_id']);
		$detail = '';
		$sale_tag='';
		if ($product_detail) { 
			if($product_detail['from_api']==0){
				$image_path_original = other_asset_url($product_detail['product_image'] . '_l.' . $product_detail['product_ext'], '', 'uploads/images/products');	
				}
				else
				{
						$image_path_original = $product_detail['product_image'];
				}
			 $wish_list =  $this->wishlist_model->get_wishlist_detail_product($product_id);
			 $wish_div = '';
			 if(!$wish_list)
			 {
			 	$wish_div = '<a href="#" class="add_wishlist" rel="' . $product_id . '">' . image_asset('btn_02.jpg') . '</a>';
			 }
			 $product_link = '';
			 if($product_detail['buy_link_type']=='1'){
			 	$product_link = '<a href="'.base_url().'product/visit/?url='.urlencode($product_detail['product_buy_link']) . '" class="buylink" target="_blank" rel="' . $product_id . '">' . image_asset('btn_01.jpg') . '</a>';	
			 }
			 $map = '';
			 if($product_detail['buy_link_type']=='2'){
				$map = '<div id="map_canvas" class="gray_bg" rel="'.$product_detail['product_buy_link'].'" style="width:300px; height:300px;">
				</div>';	
			 }
			if($product_detail['sale_type_id']=='1')
			{
				$sale_tag= '<span style="float:left;"> Före: '.makeCurrency($product_detail['product_price']).' kr
							<br>
							<label style="color:red">Nu: '.makeCurrency($product_detail['sale_value']).' kr</label> </span>
							<div class="persentage_imgs" style="float:left">
								-'.makeCurrency(100-($product_detail['sale_value']/$product_detail['product_price']*100)).'%
							</div>
							';
			}
			elseif($product_detail['sale_type_id']=='5')
			{
				$sale_tag= '<span style="float:left;"> Före: '.makeCurrency($product_detail['product_price']).' kr
							<br>
							<label style="color:red">Nu: '.makeCurrency($product_detail['sale_price']).' kr</label> </span>
							<div class="persentage_imgs" style="float:left">
								-'.$product_detail['api_reduction_percent'].'%
							</div>
							';
			}   
			elseif($product_detail['sale_type_id']=='2')
			{
				$product_detail['product_price']."*".$product_detail['sale_value'];
				$now_price = $product_detail['product_price']* ($product_detail['sale_value']/100); 
				$sale_tag= '<span class="beforeprice">Price Före: '.makeCurrency($product_detail['product_price']).' SEK</span> 
							<span class="afterprice" style="float:left">Nu: '.makeCurrency($product_detail['product_price']-$now_price).' SEK</span>
							<div class="persentage_imgs" style="float:left">
										-'.makeCurrency($product_detail['sale_value']).'%
							</div>';
			}
			
			$detail = '
                                                <div class="product_detail_area">
                                                    <div class="img_left">
                                                            <a href="'.$product_detail['product_buy_link'].'" target="_blank">
                                                                    <img width="" height="" src="' . $image_path_original . '" title="large image" />
                                                            </a>
                                                            <br />';
															
		if(is_array($this->product_model->get_product_more_images($product_id))){
			$detail .= '<a href="'.$product_detail['product_buy_link'].'" target="_blank" class="viewmoreimages" rel="'.$product_id.'" style="margin-left:125px;">' . image_asset('more_images.jpg').'</a>';	
		}
			$detail.='                                        </div>
                                                    <div class="product_info">
                                                            <p>
                                                                    ' . $product_detail['product_name'] . '
                                                            </p>
                                                            '.$sale_tag.'
                                                            <div class="clear"></div>
                                                            <div class="add_btns">
                                                                    '.$product_link.'								
                                                                    '.$wish_div.'
                                                            </div><!--add_btns-->
                                                            <div class="clear"></div>
                                                            <div class="gray_bg" style="padding:10px;font-size:12px;font-family:tahoma;color:#666">
                                                                    Produkten finns tillgänglig på: 
                                                                    <b>'.$product_detail['store_name'].'</b>
                                                                    <p><strong>Beskrivning:</strong></p>
                                                                    ' . strip_tags($product_detail['product_description'], "<br><br><b><strong>"). '
                                                            </div>
                                                            '.$map.'
                                                    </div>											
                                                    <div class="share_btns" >
                                                            <span class="st_sharethis_large" displayText="ShareThis"></span>
                                                            <span class="st_facebook_large" displayText="Facebook"></span>
                                                            <span class="st_twitter_large" displayText="Tweet"></span>
                                                            <span class="st_linkedin_large" displayText="LinkedIn"></span>
                                                            <span class="st_pinterest_large" displayText="Pinterest"></span>
                                                            <span class="st_email_large" displayText="Email"></span>
                                                    </div>
                                                </div>';

		}
		$data['category_name']      = get_categroy_name($product_detail['cat_id']);
		$data['product_category']   = get_product_oncategory($product_detail['cat_id'], 3, 0);
		$data['other_choice']       = other_choice_category();
		$data['related_categories'] = related_categories($product_detail['cat_id']);
		$data['sales_to_missed']    = sales_not_missed();
		$data['product_detail']     = $detail;
		$data['product_id']         = $product_id;
		$data['cat_id']             = $product_detail['cat_id'];
		$data['store_name']         = $product_detail['store_name'];
		$data['detail']             = $product_detail;
		//$this->load->view('product_detail.html',$data);
		 $this->template->load('responsive/default','responsive/detail', $data);
         //  $this->output->enable_profiler(true);
	}

public function visit()
{
	$url = $_GET['url'];
	$data['url'] = urlencode($url);
	$this->load->view('responsive/visit', $data);
}
	public function more_pimages_html($id){
		$images_data = $this->product_model->get_product_more_images($id);
		$html = '';
		if($images_data){
			foreach ($images_data as $value) {
				$image_path_original = other_asset_url($value['image_name'] . '_o.' . $value['image_ext'], '', 'uploads/images/products');
				$image_path_med = other_asset_url($value['image_name'] . '_m.' . $value['image_ext'], '', 'uploads/images/products');
				$html .= '<li><a class="group1" href="' . $image_path_original . '"><img width="180" height="230" src="' . $image_path_med . '" title="large image" /></a></li>';
			}
		}
		return $html;
	}

	public function product_category() {
		$category_id = $_POST['cat_id'];
		$products = $this->product_model->product_by_category($category_id);
		$product_html = $this->create_product_html($products);
		echo $product_html;
	}

	public function searchresult() {
		
			if (isset($_GET['location_id']) && $_GET['location_id']!='')
				$city_id = $_GET['location_id'];
			else
				$city_id='';
			if (isset($_GET['retailer_id']) && $_GET['retailer_id']!='')
			   $retailer_id = $_GET['retailer_id'];
			else
			   $retailer_id='';
			if (isset($_GET['pro_name']) && $_GET['pro_name']!='')
			   $product_name = $_GET['pro_name'];
			else
			  $product_name='';
			//$search_data = array('city_id' => $city_id, 'retailer_id' => $retailer_id, 'product_name' => $product_name);
		//	$this->session->set_userdata($search_data);
		
		           $config['base_url'] = full_url();
		         $config['total_rows'] = $this->product_model->searh_form_total($city_id, $retailer_id, $product_name);
                  $config['prev_link'] = '&lt; Föregående';
                  $config['next_link'] = 'Nästa &gt;';
	               $config['per_page'] = 24;
                $config['uri_segment'] = (isset($_GET['per_page']))?$_GET['per_page']:0;
        $config['enable_query_strings'] = TRUE;
	    $config['page_query_string'] = TRUE;
		$this->pagination->initialize($config);				
                
        $offset = $config['uri_segment'];
		$limit = $config['per_page'];
                
		$products = $this->product_model->searh_form($city_id, $retailer_id, $product_name, $limit, $offset);
		$product_html = $this->create_product_html($products,'searchpage');
		//echo  $product_html;
		/*
                $cat_id = 1416;
		$data['category_name'] = get_categroy_name(149);
		//$data['product_category']  = get_product_oncategory(149);
		$data['product_category'] = '';
		$data['other_choice'] = other_choice_category();
		$data['related_categories'] = related_categories(149);
		$data['sales_to_missed'] = sales_not_missed();
                */
		$data['products'] = $product_html;
		$data['total_records'] = $config['total_rows'];
		$data['pagination'] = $this->pagination->create_links();
		$this->template->load('responsive/default', 'responsive/category', $data);
		//$this->output->enable_profiler(TRUE);
	}

	public function product_featured() {
		$products = $this->product_model->product_is_featured();
		$product_html = $this->create_product_html($products);
		echo $product_html;
	}

	/*public function create_product_html($products){
	 $product_html = "";
	 if($products){
	 foreach($products as $product){
	 $image_path_small	 = other_asset_url($product['product_image'].'_s.'.$product['product_ext'],'','uploads/images/products');
	 $image_path_original = other_asset_url($product['product_image'].'_o.'.$product['product_ext'],'','uploads/images/products');
	 $product_html .= '<li>
	 <a class="small" href="'.base_url().'product/product_detail/'.$product['id'].'" title="'.$product['product_name'].'"><img src="'.$image_path_small.'" title="small image" />
	 <img class="large" src="'.$image_path_original.'" title="large image" /></a>
	 <div id="product_"'.$product['id'].'>
	 <p>'.$product['product_name'].'</p>
	 </div>
	 </li>';
	 }
	 }
	 else{
	 $product_html = '<li>Sorry No Record Exit</li>';

	 }
	 return  $product_html;

	 }*/
	public function create_product_html($products,$page='') {
		$product_html = "";
		if ($products) {
			foreach ($products as $products) {
				if($page=='searchpage')  // fix join error
				   $products['id']=$products['pro_id'];
				if($products['from_api']==0)
				{
					$image_path_original = other_asset_url($products['product_image'].'_m.'.$products['product_ext'],'','uploads/images/products');
					$iarr = @getimagesize($image_path_original);
				    if(!is_array($iarr))
					   $image_path_original = image_asset_url('no_image.gif');	
				}
				else
				{
					$image_path_original = str_replace('440','220',$products['product_image']);
				}
				
				$img_featue = 'header.jpg';
				if ($products['is_featured']) {
					$img_featue = 'heart_red.jpg';
				}
				$sale_tag = '';
				if($products['sale_type_id']=='1')
				{
					$sale_tag= '<span style="float:left;"> Före: '.makeCurrency($products['product_price']).' kr
								<br>
								<label style="color:red">Nu: '.makeCurrency($products['sale_value']).' kr</label> </span>
								<div class="persentage_imgs">
									-'.makeCurrency(100-($products['sale_value']/$products['product_price']*100)).'%
								</div>';
				} 
				elseif($products['sale_type_id']=='2')
				{
					
					$now_price = $products['product_price']* ($products['sale_value']/100); 
					$sale_tag= '<span style="float:left;"> Före: '.makeCurrency($products['product_price']).' kr
								<br>
								<label style="color:red">Nu: '.makeCurrency($products['product_price']-$now_price).' kr</label> </span>
								<div class="persentage_imgs">
									-'.makeCurrency($products['sale_value']).'%
								</div><!--persentage_img-->';
				}
				elseif($products['sale_type_id']=='5')
			   {
				$sale_tag= '<span style="float:left;"> Före: '.makeCurrency($products['product_price']).' kr
							<br>
							<label style="color:red">Nu: '.makeCurrency($products['sale_price']).' kr</label> </span>
							<div class="persentage_imgs">
								-'.$products['api_reduction_percent'].'%
							</div>';
			   } 
				else{
				$sale_tag= '<span style="float:left;"> 
								<label style="color:red">Now:'.makeCurrency($products['product_price']).' kr</label> </span>';
					if($products['sale_type_id']=='3'){
						$sale_tag.= '<br /><span style="float:left;"><label> Buy 1 get '.makeCurrency($products['sale_value']).' free</label></span>';	
					}
					if($products['sale_type_id']=='4'){
						$deals    = explode('_',$products['sale_value']);
						$sale_tag.= '<span style="float:left;"><label >Buy '.$deals[0].' and pay for '.$deals[1].'</label></span>';	
					}			
				}
				$product_html .= '<div class="cata_pro">
									<a href="' . base_url() . 'product/product_detail/' . $products['id'] . '" class="product_detail"><img src="' . $image_path_original . '"/></a>
									<!-- <div class="heart">
										' . image_asset($img_featue) . '
									</div> -->
									<p>
										' . $products['product_name'] . '
									</p>
									<div class="clear"></div>									
									' . $sale_tag . '
								</div><!--cata_pro-->';

			}
		} else {
			$product_html .= '<div class="cata_pro"><p>Kommer snart!</p></div>';
		}
		return $product_html;

	}

	public function newsletter_subscribe() {

		if ($this->newsletter_model->add_newsletter()) {
			echo json_encode(array('msg'=>TRUE));
		} else {
			echo json_encode(array('msg'=>false));
		}
	}

	public function add_wishlist() {
		$product_id = $this->input->post('product_id');
		if(!$this->tank_auth->is_logged_in(TRUE)){
			if($this->session->userdata('sess_wishlist')){
				$sessionpro = $this->session->userdata('sess_wishlist');
				if(in_array($product_id, $sessionpro)){
					$response = 'product Already in wishlist';
				}
				else{
					array_push($sessionpro, $product_id);
					$this->session->set_userdata('sess_wishlist',$sessionpro);
				}
			}
			else{
				$sessionpro = array($product_id);
				$this->session->set_userdata('sess_wishlist',$sessionpro);
			}
		}
		else{
			$this->wishlist_model->add_wishlist_product();
		}
		echo '<div class="heartred" rel="'.$product_id.'" id="wish_heart_'.$product_id.'" style="display:none">
				'.image_asset('heart_red.jpg').'
			</div>';
	}
	
	public function remove_pro_wishlist() {
		$product_id = $this->input->post('product_id');
		if(!$this->tank_auth->is_logged_in(TRUE)){
			if($this->session->userdata('sess_wishlist')){
				$sessionpro = $this->session->userdata('sess_wishlist');
				if(($key = array_search($product_id, $sessionpro)) !== false) {
				    unset($sessionpro[$key]);
				}
				$this->session->set_userdata('sess_wishlist',$sessionpro);
			}
		}
		else{
			$this->wishlist_model->delete_wish_product($product_id);
		}
		echo '<div class="heart" rel="'.$product_id.'" id="wish_heart_'.$product_id.'" style="display:none">
				'.image_asset('header.jpg').'
			</div>';
	}

	public function wishlist(){
		$this->load->library('pagination');
		$config['base_url'] 		    = base_url().'category/whishlist_product';
		$config['total_rows'] 		    = $this->wishlist_model->get_all_wishedproducts_count();		
		$config['per_page'] 		    = 24;
		$config['uri_segment']		    = $this->uri->segment(3);		
		$this->pagination->initialize($config);	
		$offset						    = $config['uri_segment'];
		$limit						    = $config['per_page'];
		$this->pagination->initialize($config);
		$wishlist_products 				= $this->wishlist_model->get_all_wishedproducts($limit,$offset);
			
		$pro_html	                = ''; 
		if($wishlist_products){	
			foreach($wishlist_products as $wishlist_product){
				$products     = $this->product_model->get_product_detail($wishlist_product['product_id']);
				if($products)
				{
					
					if($products['from_api']==0)
					{
						$image_path_original = other_asset_url($products['product_image'].'_m.'.$products['product_ext'],'','uploads/images/products');	
						$iarr = getimagesize($image_path_original);
						if(!is_array($iarr))
							$image_path_original = image_asset_url('no_image.gif');
					}
					else
					{
						//$image_path_original = $products['product_image'];
						$image_path_original = str_replace('440','220',$products['product_image']);
						
					}
						
				
				$img_featue  = 'heart_red.jpg';
				$heart_class = '<div class="remove_wish" rel="'.$wishlist_product['wish_id'].'" id="wish_heart_'.$wishlist_product['wish_id'].'" style="display:none">
									'.image_asset($img_featue).'
								</div>';
				$sale_tag = ''; 
				if($products['sale_type_id']=='1')
				{
					$sale_tag= '<span style="float:left;"> Före: '.makeCurrency($products['product_price']).' kr
								<br>
								<label style="color:red">Nu: '.makeCurrency($products['sale_value']).' kr</label> </span>
								<div class="persentage_imgs">
									-'.makeCurrency(100-($products['sale_value']/$products['product_price']*100)).'%
								</div>';
				} 
				elseif($products['sale_type_id']=='2')
				{
					
					$now_price = $products['product_price']* ($products['sale_value']/100); 
					$sale_tag= '<span style="float:left;"> Före: '.makeCurrency($products['product_price']).' kr
								<br>
								<label style="color:red">Nu: '.makeCurrency($products['product_price']-$now_price).' kr</label> </span>
								<div class="persentage_imgs">
									-'.makeCurrency($products['sale_value']).'%
								</div><!--persentage_img-->';
				}
			   elseif($products['sale_type_id']=='5')
			   {
				$sale_tag= '<span style="float:left;"> Före: '.makeCurrency($products['product_price']).' kr
							<br>
							<label style="color:red">Nu: '.makeCurrency($products['sale_price']).' kr</label> </span>
							<div class="persentage_imgs">
								-'.$products['api_reduction_percent'].'%
							</div>';
			   }
				$pro_html     .= '<div class="cata_pro product_detail"  rel="'.$wishlist_product['wish_id'].'">
										<a id="product_'.$products['pro_id'].'" href="'.base_url().'product/product_detail/'.$products['pro_id'].'" ><img src="'.$image_path_original.'"/></a>
										
										'.$heart_class.'
										<p>
											'.$products['product_name'].'
										</p>
										<div class="clear"></div>									
										'.$sale_tag.'
									</div>';						
	
				} 
			}
		}
		else {
			$pro_html     .= '
                            Det finns inga produkter i önskelistan.
                            <h2 style="margin-top:10px;">
                                Fördelar med SaleFinder önskelista
                            </h2>
                            <ul>
                               <li> 
                                Du kan spara produkter till nästa gång du loggar in
                               </li>
                               <li>
                                Du kan enkelt jämföra produkterna du har i önskelistan
                               </li>
                               <li>
                                Det blir lätt att ha alla dina favorit reavaror på ett ställe
                               </li>
                            </ul>
                ';
		}	
		// $data['category_name']     = get_categroy_name(149);
		// $data['product_category']  = get_product_oncategory(149);		
		// //$data['product_category']  = ''; 	
		// $data['other_choice']      = other_choice_category();
		// $data['related_categories']= related_categories(149); 
		// $data['sales_to_missed']   = sales_not_missed();	
		$data['products']  		   = $pro_html;
		$data['pagination']        = $this->pagination->create_links();
		$this->template->load('responsive/default', 'responsive/wishlist',$data);
	  }

		function remove_wishlist($wish_id){
			if(!$this->tank_auth->is_logged_in(TRUE)){
				if($this->session->userdata('sess_wishlist')){
					$sessionpro = $this->session->userdata('sess_wishlist');
					unset($sessionpro[$wish_id]);
					$this->session->set_userdata('sess_wishlist',$sessionpro);
				}
			}
			else{
				$this->db->query("delete from wishlist where wish_id = {$wish_id}");
			}
			redirect('product/wishlist');
		}
		
	function updatebuycount(){
		$pid = $this->input->post('pid', TRUE);
		$this->product_model->update_product_buy_count($pid);
		echo true;
	}		

        
        public function categories_filter_fields(){
            $cat_id = $this->input->get("cat_id");
            $fields = $this->categories_model->get_filters_fields($cat_id);
            foreach($fields as $key=>$value){
                if($value=='product_price'){
                    continue;
                }
                echo '<p>
			            <label>'.$value.'</label>			
			            <br />			
			            <input name="'.$value.'" type="text" class="required text small" id="'.$value.'" />			
			            <span class="note error"></span> </p>';
            }
        }	
}
