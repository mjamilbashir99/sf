<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller
{
	function __construct()
	{
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

	public function index()
	{	   
		$cat_id 	                    = $_GET['cat_id'];		
		$config['base_url'] 		    = base_url().'category/?cat_id='.$_GET['cat_id'];
		$config['total_rows'] 		    = $this->product_model->total_product_by_category($_GET['cat_id']);
		$config['per_page'] 		    = 20;
		$config['uri_segment']		    = (isset($_GET['per_page']))?$_GET['per_page']:9;		
		$this->pagination->initialize($config);	
		$offset						= $config['uri_segment'];
		$limit						= $config['per_page'];
		$config['enable_query_strings'] = TRUE;
		$config['page_query_string'] = TRUE;
		$this->pagination->initialize($config);				
		$products     = $this->product_model->product_by_category($cat_id,$limit,$offset);
		$pro_html	  = '';
                
		
		if($products){			
                    foreach($products as $products){
                       if($products['product_image']){
                            $image_path_original = other_asset_url($products['product_image'].'_m.'.$products['product_ext'],'','uploads/images/products'); 
                       }
			$iarr = @getimagesize($image_path_original);
			if(!is_array($iarr)){
				$image_path_original = image_asset_url('no_image.gif');
                        }
			$img_featue  = 'header.jpg';
			$heart_class = '<div class="heart" rel="'.$products['pro_id'].'" id="wish_heart_'.$products['pro_id'].'" style="display:none">
										'.image_asset($img_featue).'
									</div>';
			$whislist_detail = $this->wishlist_model->get_wishlist_detail_product($products['pro_id']);			
			if($this->wishlist_model->get_wishlist_detail_product($products['pro_id'])){
				$img_featue  = 'heart_red.jpg';
				$heart_class = '<div class="heartred" rel="'.$products['pro_id'].'" id="wish_heart_'.$products['pro_id'].'">
										'.image_asset($img_featue).'
									</div>';
			}
			$sale_tag = '';
			if($products['sale_type_id']=='1')
			{
				$sale_tag= '<span style="float:left;"> Before: '.round($products['product_price']).' kr
							<br>
							<label style="color:red">Now: '.round($products['sale_value']).' kr</label> </span>
							<div class="persentage_imgs">
								-'.round(100-($products['sale_value']/$products['product_price']*100)).'%
							</div>';
			} 
			elseif($products['sale_type_id']=='2')
			{
				
				$now_price = $products['product_price']* ($products['sale_value']/100); 
				$sale_tag= '<span style="float:left;"> Before: '.round($products['product_price']).' kr
							<br>
							<label style="color:red">Now: '.round($products['product_price']-$now_price).' kr</label> </span>
							<div class="persentage_imgs">
										-'.round($products['sale_value']).'%
							</div><!--persentage_img-->';
			}
			else{
			$sale_tag= '<span style="float:left;"> 
							<label style="color:red">Now:'.round($products['product_price']).' kr</label> </span>';
				if($products['sale_type_id']=='3'){
					$sale_tag.= '<br /><span style="float:left;"><label> Buy 1 get '.$products['sale_value'].' free</label></span>';	
				}
				if($products['sale_type_id']=='4'){
					$deals    = explode('_',$products['sale_value']);
					$sale_tag.= '<span style="float:left;"><label >Buy '.$deals[0].' and pay for '.$deals[1].'</label></span>';	
				}			
			}
			$pro_html     .= '<div class="cata_pro product_detail" rel="'.$products['pro_id'].'">
									<a id="product_'.$products['pro_id'].'" href="'.base_url().'product/product_detail/'.$products['pro_id'].'" ><img height="256" width="200" src="'.$image_path_original.'"/></a>
									
									'.$heart_class.'
									<p>
										'.trim_text(strip_tags($products['product_name']),30).'
									</p>
									<div class="clear"></div>									
									'.$sale_tag.'
								</div><!--cata_pro-->';
				}				
		}
		else{
			$pro_html  .= '<div class="cata_pro"><p>Sorry No Record Yet!</p></div>';
		}
                $cat_id = $this->input->get("cat_id");
		$data['category_name']     = get_categroy_name($cat_id);
		$data['category_detail']     = $this->categories_model->get_category_detail($cat_id);
		$data['product_category']  = get_product_oncategory($cat_id);		
		//$data['product_category']  = ''; 	
		$data['other_choice']      = other_choice_category();
		$data['related_categories']= related_categories($cat_id); 
		$data['sales_to_missed']   = sales_not_missed();	
		$data['products']  		   = $pro_html;
		$data['pagination']        = $this->pagination->create_links();
                $data['query_params'] = $this->input->get();
                $data['total_records'] = $config['base_url'];
 		$this->load->view('main/category.html',$data);
	}

}