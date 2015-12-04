<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));  
		$this->load->library('tank_auth');
		$this->load->model('categories_model');
		$this->load->model('product_model');
		$this->load->model('content_model');
		$this->load->model('wishlist_model'); 
		$this->load->library('my_upload');   			
		// $this->tank_auth->logout();
		if (!$this->tank_auth->is_logged_in(TRUE))									// not logged in
			redirect('/auth/login/');
	}

	public function index()
	{	
 		redirect('/user/profile/');
	}
	
	public function profile(){ 
		redirect('auth/edit_profile');
		// $data = array();
	 	// $this->load->view('main/profile.html', $data);
	}
	
	public function addproduct(){
		if($this->input->post('add')){				
			if($lastid = $this->product_model->add_product()){
				// add product deals
				$sale_type = $this->input->post('sale_type_id', TRUE);
				$sale_value_arr = $this->input->post('sale_value', TRUE);
				if($sale_type < 4){
					$sale_value = $sale_value_arr[$sale_type-1];
                                }
				else{
					$sale_value = $sale_value_arr[3].'_'.$sale_value_arr[4];
                                }
				$start_date = ($this->input->post('sale_start_date', TRUE) != '')?date('Y-m-d',strtotime($this->input->post('sale_start_date', TRUE))):'';
				$end_date = ($this->input->post('sale_end_date', TRUE) != '')?date('Y-m-d',strtotime($this->input->post('sale_end_date', TRUE))):'';
				$sale_deal = array( 'product_id'=>$lastid,
                                                    'sale_type_id' => $sale_type,
                                                    'sale_value' => $sale_value,
                                                    'sale_start_date' => $start_date,
                                                    'sale_end_date' => $end_date);
				$this->product_model->add_product_deal($sale_deal);
				$this->session->set_flashdata('message', '<div class="message success"><p>New product has been added successfully</p></div>');
			}
			else{
				$this->session->set_flashdata('message', '<div class="message errormsg"><p>Some Problem with Query!</p></div>');
			}
			redirect('/user/viewproduct/');
		}
		$data = array();
		$data['cities'] = $this->content_model->get_all_cities();
		//$data['parent_categories'] = $this->categories_model->get_parent_categories();
		$this->load->model('tank_auth/users');
		$subsc = array_shift($this->users->get_user_subscription_with_detail_by_userid($this->session->userdata('user_id')));
		if($subsc['category_limit'] > 0){
			$subsc_cat = $this->users->get_user_subscribed_cat($this->session->userdata('user_id'));
			$data['categories'] = mychildrecurdetail($subsc_cat[0]);
		}
		else{
			$data['categories'] = $this->categories_model->get_last_level_categories();
		}
	 	$this->load->view('responsive/addproduct', $data);
	}
    /*************************************************************************
	** UPLOAD CSV **
	*************************************************************************/
	public function add_product_csv()
	{
			$user_dir = dirname(BASEPATH).'/csv/'.$this->session->userdata('user_id').'/';
			$data['msg']='';
			if (!file_exists($user_dir))
			{
				mkdir("csv/".$this->session->userdata('user_id'), 0777);
				chmod("csv/".$this->session->userdata('user_id'), 0777);
			}
			
			if($this->input->post('add'))
			{				
				
				$filename = $_FILES['csv_file']['name'];
				$tmp_name = explode('.',$filename);
				$extention = end($tmp_name);
				if(strtolower($extention)=="csv")
				{		
					$baseName = basename($this->session->userdata('user_id')."_".time().".".$extention);							
					$target_path = $user_dir.$baseName; 
					$tmp = move_uploaded_file($_FILES['csv_file']['tmp_name'], $target_path);
					if($tmp)
					{
						$fp = fopen($target_path, "r");
						$tb_data=array();
						$i=0;
						while(($line=fgetcsv($fp,1000,","))!=false)
						{
							set_time_limit(0);
							ini_set('memory_limit','500M');
							  
							if($line[0]=='name')
								continue;
							if($line[0]=='')
								break;
						    $i++;
							//echo "<br><pre>";
							$tb_data['product_name']=$line[0];
							$tb_data['cat_id']=  326;//$line[1];
							$tb_data['store_name']=$line[2];
							$tb_data['product_description']=$line[3];
							$tb_data['product_price']=$line[4];
							$tb_data['sale_price']=$line[5];
							$tb_data['product_buy_link']=$line[6];
							$tb_data['user_id']= $this->session->userdata('user_id');
							$tb_data['from_csv']= 1;
							// copying images
							$src = $line[7];
							$time = time().rand();
							$tmp_name = explode('.',$src);
							$tb_data['product_ext'] = strtolower(end($tmp_name));
							$tb_data['product_image'] = $time;
							$img_name =  $time."_o.".$tb_data['product_ext'];
							$src_path = $this->config->item('pro_image_path').$img_name;
							file_put_contents($src_path, file_get_contents($src));
							echo js_asset('jquery-1.8.1.min.js');
							?>
						     <script>
                             jQuery.post("js_makeThumb", { image_name: "<?php echo $img_name?>", new_name: "<?php echo $time?>_l",size:"l" } );	
							 jQuery.post("js_makeThumb", { image_name: "<?php echo $img_name?>", new_name: "<?php echo $time?>_m",size:"m" } );	
							 jQuery.post("js_makeThumb", { image_name: "<?php echo $img_name?>", new_name: "<?php echo $time?>_s",size:"s" } );	
							 </script>
							<?php
							$res = $this->db->insert('products', $tb_data);
							$lastid = $this->db->insert_id();
							$start_date = '';
							$end_date   = '';
							$sale_type  = 1;
							$sale_value = $line[5];
							$sale_deal = array('product_id'=>$lastid,
											   'sale_type_id' => $sale_type,
											   'sale_value' => $sale_value,
											   'sale_start_date' => $start_date,
											   'sale_end_date' => $end_date);
							$this->product_model->add_product_deal($sale_deal);
						}
						
						fclose($fp);
						$data['msg']= $i." products have been added successfully";
						$this->session->set_flashdata('message', '<div class="message success"><p>'.$i.' products have been added successfully</p></div>');
					
					}
					else
					{
						$this->session->set_flashdata('message', '<div class="message errormsg"><p>Unable to upload. Please try again</p></div>');
					}
				
				}
				else
				{   
					$this->session->set_flashdata('message', '<div class="message errormsg"><p>Please upload only csv file</p></div>');
				}
			}
			$this->load->model('tank_auth/users');
			$this->load->view('responsive/add_product_csv', $data);
			//$this->output->enable_profiler(TRUE);
	}
/*************************************************************************
** making thumbs by JS**
************************************************************************/

public function js_makeThumb()
{
	$src_path = $this->config->item('pro_image_path').$_POST['image_name'];
	$logo_thumb = array(
						'src_path'=>$src_path,
						'dst_path'=>$this->config->item('pro_image_path'),
						'image_ratio'=>TRUE,
						'image_ratio_fill'=>TRUE,
						'new_name'=>$_POST['new_name']
						);
	if($_POST['size']=='l')
	{
		$logo_thumb['image_x'] = $this->config->item('pro_image_large_x');
		$logo_thumb['image_y']=$this->config->item('pro_image_large_y');	
	}
	elseif($_POST['size']=='m')
	{
		$logo_thumb['image_x'] = $this->config->item('pro_image_medium_x');
		$logo_thumb['image_y']=$this->config->item('pro_image_medium_y');	
	}
	elseif($_POST['size']=='s')
	{
		$logo_thumb['image_x'] = $this->config->item('pro_image_small_x');
		$logo_thumb['image_y']=$this->config->item('pro_image_small_y');	
	}
//	print_r($logo_thumb);
  upload_resized_images($logo_thumb);	
}	
/*************************************************************************
	** Change category **
************************************************************************/
	public function change_cat(){
		$cat_id = $this->input->post('cat_id', TRUE);
		$categories = $this->categories_model->get_child_categories($cat_id);
		if($categories){
			echo '<select class="catstyled" name="cat_id" id="cat_id">
					'.create_ddl($categories,'cat_id','cat_name').'
				</select>';
		}
	}

	public function add_pro_image(){
		$time = time().rand();
		$logo = array(
				'file'=>$_FILES["userfile"],
				'new_name'=>$time.'_o',
				'dst_path'=>$this->config->item('pro_image_path')
				);	
		$logo_response = upload_original($logo);
		$src_path = $this->config->item('pro_image_path').$logo_response['file_name'];
		$logo_thumbs = array(
				array(
					'src_path'=>$src_path,
					'dst_path'=>$this->config->item('pro_image_path'),
					'image_x'=>$this->config->item('pro_image_large_x'),
					'image_y'=>$this->config->item('pro_image_large_y'),
					'image_ratio'=>TRUE,
					'image_ratio_fill'=>TRUE,
					'new_name'=>$time.'_l'
					),
				array(
					'src_path'=>$src_path,
					'dst_path'=>$this->config->item('pro_image_path'),
					'image_x'=>$this->config->item('pro_image_medium_x'),
					'image_y'=>$this->config->item('pro_image_medium_y'),
					'image_ratio'=>TRUE,
					'image_ratio_fill'=>TRUE,
					'new_name'=>$time.'_m'
					),
				array(
					'src_path'=>$src_path,
					'dst_path'=>$this->config->item('pro_image_path'),
					'image_x'=>$this->config->item('pro_image_small_x'),
					'image_y'=>$this->config->item('pro_image_small_y'),
					'image_ratio'=>TRUE,
					'image_ratio_fill'=>TRUE,
					'new_name'=>$time.'_s'
					)
				);
		foreach ($logo_thumbs as $logo_thumb) {
			upload_resized_images($logo_thumb);	
		}		
		echo json_encode(array('name'=>$time,'ext'=>$logo_response['file_ext'],'imagename'=>other_asset_url($time.'_s.'.$logo_response['file_ext'],'','uploads/images/products')));
	}

	function unlink_promoreimage(){
		$img = explode('_', $this->input->post('img'));
		unlink($this->config->item('pro_image_path').$img[0].'_o.'.$img[1]);
		unlink($this->config->item('pro_image_path').$img[0].'_s.'.$img[1]);
		unlink($this->config->item('pro_image_path').$img[0].'_m.'.$img[1]);
		unlink($this->config->item('pro_image_path').$img[0].'_l.'.$img[1]);
	}

	public function editproduct($edit_id){
		if(!$edit_id){
			show_404();exit;
		}
		if($this->input->post('add')){
			$this->product_model->update_product($edit_id);
			// add product deals
				$sale_type = $this->input->post('sale_type_id', TRUE);
				$sale_value_arr = $this->input->post('sale_value', TRUE);
				if($sale_type < 4)
					$sale_value = $sale_value_arr[$sale_type-1];
				else
					$sale_value = $sale_value_arr[3].'_'.$sale_value_arr[4];
				$start_date = ($this->input->post('sale_start_date', TRUE) != '')?date('Y-m-d',strtotime($this->input->post('sale_start_date', TRUE))):'';
				$end_date = ($this->input->post('sale_end_date', TRUE) != '')?date('Y-m-d',strtotime($this->input->post('sale_end_date', TRUE))):'';
				$sale_deal = array('sale_type_id' => $sale_type,
									'sale_value' => $sale_value,
									'sale_start_date' => $start_date,
									'sale_end_date' => $end_date);
				$this->product_model->update_product_deal($edit_id, $sale_deal);
			$this->session->set_flashdata('message', '<div class="message success"><p>Product update successfully</p></div>');
			redirect('/user/viewproduct/');
		}
		$data['edit_data'] = $this->product_model->get_product_detail($edit_id);
		$data['pro_images'] = $this->more_pimages_html($edit_id);
		$data['category_detail'] = $this->categories_model->get_category_detail($data['edit_data']['cat_id']);
	
		$this->load->model('tank_auth/users');
		$subsc = array_shift($this->users->get_user_subscription_with_detail_by_userid($this->session->userdata('user_id')));
		if($subsc['category_limit'] > 0){
			$subsc_cat = $this->users->get_user_subscribed_cat($this->session->userdata('user_id'));
			$data['categories'] = mychildrecurdetail($subsc_cat[0]);
		}
		else{
			$data['categories'] = $this->categories_model->get_last_level_categories();
		}
		$data['cities'] = $this->content_model->get_all_cities();
		$data['edit_data']['sale1'] = '';
		$data['edit_data']['sale2'] = '';
		if($data['edit_data']['sale_type_id'] == 4){
			$sale_val = explode('_', $data['edit_data']['sale_value']);
			$data['edit_data']['sale1'] = $sale_val[0];
			$data['edit_data']['sale2'] = $sale_val[1];
		}
		$data['page'] = "product";
		$this->load->view('responsive/editproduct',$data);
		//$this->output->enable_profiler(1);
	}

	public function more_pimages_html($id){
		$images_data = $this->product_model->get_product_more_images($id);
		$image_name = array();
		$image_ext = array();
		$html = '';
		if($images_data){
			foreach ($images_data as $value) {
				$image_name[] = $value['image_name'];
				$image_ext[] = $value['image_ext'];
				$html .= '<li><img src="'.other_asset_url($value['image_name'].'_s.'.$value['image_ext'],"","uploads/images/products").'" width="32" height="32" alt="image" />
				              <div class="clr"></div><a href="#" class="delproductimg" rel="'.$value['image_name'].'_'.$value['image_ext'].'">Delete</a> </li>';
			}
		}
		return array('image_name'=>$image_name, 'image_ext'=>$image_ext, 'html'=>$html);
	}

	public function viewproduct(){
		$products = $this->product_model->get_all_products_by_user($this->session->userdata('user_id'));
		$data['product_html']="";
		if($products){				
			foreach($products as $product){
				$image_path = other_asset_url($product['product_image'].'_s.'.$product['product_ext'],'','uploads/images/products');
				$data['product_html'] .= '<tr>
										<td style="border-bottom: 1px solid #EEEEEE;">'.$product['product_name'].'</td>
										<td style="border-bottom: 1px solid #EEEEEE;"><img src="'.$image_path.'" /></td>
										<td style="border-bottom: 1px solid #EEEEEE;">'.$product['product_price'].'</td>
										<td style="border-bottom: 1px solid #EEEEEE;">'.date('Y-m-d',strtotime($product['product_date'])).'</td>
										<td style="border-bottom: 1px solid #EEEEEE;" class="delete">
											<a href="'.base_url().'user/editproduct/'.$product['pro_id'].'" class="editcategories">Edit</a> |  
											<a href="#" class="deleteproduct" rel="'.$product['pro_id'].'">Delete</a>
										</td>
									  </tr>';
			}
		}
		$this->load->view('responsive/viewproduct',$data);
	}

	public function viewinvoice(){
		$this->load->model('tank_auth/users');
		$data['invoices'] = $this->users->get_user_subscription_with_detail_by_userid($this->session->userdata('user_id'));
		$this->load->view('responsive/viewinvoice',$data);
	}

	public function downloadinvoice($id){
		$this->load->model('tank_auth/users');
		$data['invoice'] = $this->users->get_subscription_detail_by_id($id);
		//Load the library
	    $this->load->library('html2pdf');
	    
	    //Set folder to save PDF to
	    $this->html2pdf->folder('./assets/pdfs/');
	    
	    //Set the filename to save/download as
	    $this->html2pdf->filename('invoice.pdf');
	    
	    //Set the paper defaults
	    $this->html2pdf->paper('a4', 'portrait');
	    //Load html view
	    $this->html2pdf->html($this->load->view('responsive/invoice', $data, true));
	    
	    if($this->html2pdf->create()) {
	    	//PDF was successfully saved or downloaded
	    	//echo 'PDF saved';
	    }
	}
	
	public function delete_product($id){
		if(!$id){
			show_404();exit;
		}
		$this->product_model->delete_product($id);
		$this->session->set_flashdata('message', '<div class="message success"><p>Product deleted successfully</p></div>');
		redirect('/user/viewproduct/');
	}

	public function newsboard(){
		$this->load->model('news_model');
		if($this->input->post('add')){
			if($this->news_model->add_news()){				
				$this->session->set_flashdata('message', '<div class="message success"><p>New News has been Added successfully</p></div>');
			}
			else{
				$this->session->set_flashdata('message', '<div class="message errormsg"><p>Some Problem with Query!</p></div>');
			}
			redirect('/user/newsboard/');
		}
		elseif($this->input->post('edit')){
			
		}
		else{
			$news = $this->news_model->get_all_news_by_user($this->session->userdata('user_id'));
			$data['news_html']="";
			if($news){				
				foreach($news as $news){
					$del = '<a href="#" class="deletenews" rel="'.$news['id'].'">Delete</a>';
					$edit = '<a href="'.base_url().'user/editnews/'.$news['id'].'" class="editnews" rel="'.$news['id'].'">Edit</a>';	
					$data['news_html'] 	   .= '<tr>
												<td>'.$news['news_text'].'</td>													
												<td>'.$news['news_status'].'</td>
												<td class="delete">
													'.$edit.' | '.$del.'
												</td>
											  </tr>';
				}
			}						
			$this->load->view('responsive/newsboard',$data);
		}
	}
	
	public function editnews($edit_id){
		$this->load->model('news_model');
		if(!$edit_id){
			show_404();exit;
		}
		if($this->input->post('add')){
			if($this->news_model->update_news($edit_id))
				$this->session->set_flashdata('message', '<div class="message success"><p>News Updated successfully</p></div>');
			redirect('/user/newsboard/');
		}
		$data['edit_data'] 	= $this->news_model->get_news_detail($edit_id);			
		$this->load->view('responsive/editnews',$data);
	}
	
	public function delete_news($id){
		$this->load->model('news_model');
		if(!$id){
			show_404();exit;
		}
		$this->news_model->delete_news($id);
		$this->session->set_flashdata('message', '<div class="message success"><p>News deleted successfully</p></div>');
		redirect('/user/newsboard/');
	}

        

}