<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Retailer extends CI_Controller
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

	public function index($retailer_id = 0)
	{
		if(!$retailer_id){
			show_404();exit;
		}
		$config['base_url'] = base_url() . 'retailer/index/'.$retailer_id;
		$config['total_rows'] = count($this -> product_model -> get_all_products_by_user($retailer_id));
		$config['per_page'] = 20;
		$config['uri_segment'] = 4;
		$this -> pagination -> initialize($config);
		$offset = ($this->uri->segment(4) == '')?0:$this->uri->segment(4);
		$limit = $config['per_page'];
		///$config['use_page_numbers']	= FALSE;
		$this -> pagination -> initialize($config);
		$products = $this -> product_model -> get_all_products_by_user($retailer_id,$limit,$offset);
		$product_html = $this -> create_product_html($products);
		
		//echo  $product_html;
		/*
                $cat_id = 1416;
		$data['category_name'] = get_categroy_name(149);
		//$data['product_category']  = get_product_oncategory(149);
		$data['product_category'] = '';
		$data['other_choice'] = other_choice_category();
		$data['related_categories'] = related_categories(149);
		$data['sales_to_missed'] = sales_not_missed() 
		            */
		$data['products'] = $product_html;
		$data['total_records'] = $config['total_rows'];
		$data['pagination'] = $this -> pagination -> create_links();
		//$this -> load -> view('responsive/category', $data);
		$this->template->load('responsive/default','responsive/category', $data);
		//$this->output->enable_profiler(1);
	}
	public function create_product_html($products) {
		$product_html = "";
		if ($products) {
			foreach ($products as $products) {
				$image_path_original = other_asset_url($products['product_image'] . '_m.' . $products['product_ext'], '', 'uploads/images/products');
				$iarr = @getimagesize($image_path_original);
				if(!is_array($iarr))
					$image_path_original = image_asset_url('no_image.gif');
				$img_featue = 'header.jpg';
				if ($products['is_featured']) {
					$img_featue = 'heart_red.jpg';
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
				$product_html .= '<div class="cata_pro">
									<a href="' . base_url() . 'product/product_detail/' . $products['id'] . '" class="product_detail"><img height="256" width="200" src="' . $image_path_original . '"/></a>
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
			$product_html .= '<div class="cata_pro"><p>Kommer snart! </p></div>';
		}
		return $product_html;

	}

}