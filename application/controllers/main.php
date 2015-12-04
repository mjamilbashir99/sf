<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Main extends CI_Controller
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
		$this->load->library('tank_auth');
		// $this->tank_auth->logout();
	}

	function index()
	{
		$data['category']  = $this->category_listings();				
		//$this->load->view('main/index',$data);
		$this->template->load('responsive/default_home','responsive/index',$data);
	}
        
        function content($id)
	{
		$data['content'] = $this->content_model->get_content($id);				
		//$this->load->view('responsive/content',$data);
		$this->template->load('default','responsive/content',$data);
	}    
   	function category_listings()
	{
		//$data['category_html']=$this->category_listings();
		$parent_categories = $this->categories_model->get_parent_categories();		
		$category_html     = '';
		foreach($parent_categories as $pa_cat){
		$image_path = base_url().'/assets/uploads/images/categories/'.$pa_cat['cat_img'].'_m.'.$pa_cat['cat_ext'];
		$category_html    .= '<div class="fashion_cata">
							<img src="'.$image_path.'" width=225 height=400/>
							<h4 style=" margin-left: 55px;">'.$pa_cat['cat_name'].'</h4>';
		$child_categories = $this->categories_model->get_child_categories($pa_cat['cat_id']);
		if($child_categories){					
		$category_html    .='<ul style=" background-color:#000">';
		 foreach($child_categories as $child_categories){
			$category_html    .='<li>
									<a href="#">'.$child_categories['cat_name'].'</a>
								</li>';
								}								
			$category_html    .='<li style="background:url('.image_asset_url('arrow-red.jpg').') left no-repeat;">
									<a style="color:red" href="#">MORE</a>
								</li>
							</ul>';
							}							
		$category_html    .='</div>';		
		}	
		return 	$category_html ;	
	}
	function uploader(){
		// logo uploading
		if($_FILES["image"]["name"] != ""){
			$time = time().rand();
			$logo = array(
					'file'		=>$_FILES["image"],
					'new_name'	=>$time.'_logo',
					'dst_path'	=>$this->config->item('image_path')
					);	
			$logo_response = upload_original($logo);
			$src_path = $this->config->item('image_path').$logo_response['file_name'];
			$logo_thumbs = array(
					array(
						'src_path'=>$src_path,
						'dst_path'=>$this->config->item('image_path'),
						'image_x'=>239,
						'image_y'=>87,
						'image_ratio'=>TRUE,
						'image_ratio_fill'=>TRUE,
						'new_name'=>$time.'_logo_m'
						),
					array(
						'src_path'=>$src_path,
						'dst_path'=>$this->config->item('image_path'),
						'image_x'=>48,
						'image_y'=>48,
						'image_ratio'=>TRUE,
						'image_ratio_fill'=>TRUE,
						'new_name'=>$time.'_logo_s'
						)
					);
			foreach ($logo_thumbs as $logo_thumb) {
				upload_resized_images($logo_thumb);	
			}
			$logo_name = $time;
			$logo_ext = $logo_response['file_ext'];
			echo $src_path;
		}
	} 

	function test(){
		$query = "select p.id, p.product_price, pd.sale_type_id,pd.sale_value,
					CASE pd.sale_type_id WHEN 1 THEN pd.sale_value WHEN 2 THEN (p.product_price-(p.product_price*pd.sale_value/100))
					WHEN 3 THEN (p.product_price/2) WHEN 4 THEN p.product_price ELSE NULL END as price
					from products as p
					join product_deal as pd ON pd.product_id = p.id";
		$res = $this->db->query($query)->result_array();
		foreach ($res as $value) {
			$this->db->query("update products set sale_price = '{$value['price']}' where id = {$value['id']}");
		}			
	}
	
	function pdf(){
		//Load the library
	    $this->load->library('html2pdf');
	    
	    //Set folder to save PDF to
	    $this->html2pdf->folder('./assets/pdfs/');
	    
	    //Set the filename to save/download as
	    $this->html2pdf->filename('tests.pdf');
	    
	    //Set the paper defaults
	    $this->html2pdf->paper('a4', 'portrait');
	    
	    $data = array(
	    	'title' => 'PDF Created',
	    	'message' => 'Hello World!'
	    );
	    
	    //Load html view
	    $this->html2pdf->html($this->load->view('responsive/pdf', $data, true));
	    
	    if($this->html2pdf->create('save')) {
	    	//PDF was successfully saved or downloaded
	    	echo 'PDF saved';
	    }
	}
}
