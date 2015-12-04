<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('categories_model');
        $this->load->model('content_model');
        $this->load->model('product_model');
        $this->load->model('news_model');
        $this->load->model('wishlist_model');
        $this->load->model('newsletter_model');
        $this->load->model('user_model');
        $this->load->model('banner_model');
        $this->load->model('tank_auth/users');
        $this->load->library('my_upload');
		 $this->load->library('uploadimages');
        if ($this->uri->segment(2) != "login" && !$this->session->userdata('admin_sample')) {
            redirect('admin/login');
        }
    }

    public function login() {
        if (isset($_POST['submit'])) {
            $res = $this->admin_model->verify_login();
            if (!$res) {
                $data['error'] = '<div class="message errormsg"><p>Username or password is wrong</p></div>';
                $this->load->view('admin/login.html', $data);
                return;
            } else {
                redirect('admin/users');
                return;
            }
        }
        $this->load->view('admin/login.html');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('admin/login');
    }

    public function index() {
        $this->users(1);
        // $data['page'] = "Dashboard";
        // $this->load->view('admin/home.html',$data);
    }

    public function category() {
        if ($this->input->post('add')) {
            if ($this->categories_model->add_categories()) {
                $this->session->set_flashdata('message', '<div class="message success"><p>New Category has been Added successfully</p></div>');
            } else {
                $this->session->set_flashdata('message', '<div class="message errormsg"><p>Some Problem with Query!</p></div>');
            }
            redirect('admin/category/');
        } elseif ($this->input->post('edit')) {
            
        } else {
            $category = $this->categories_model->get_all_categories();
            $data['category_html'] = "";
            if ($category) {
                foreach ($category as $categories) {
                    $del = '';
                    if ($categories['user_id'] == 0) {
                        $del = '<a href="#" class="deletecategories" rel="' . $categories['cat_id'] . '">Delete</a>';
                        $edit = '<a href="' . base_url() . 'admin/edit_category/' . $categories['cat_id'] . '" class="editcategories" rel="' . $categories['cat_id'] . '">Edit</a>';
                    }
                    if ($categories['parent_id'] == 0) {
                        $parent_category_name = 'NULL';
                    } else {
                        $parent_category_name = $this->categories_model->get_recursive_parent_names_string($categories['parent_id']);
                    }
                    $image_path = base_url() . '/assets/uploads/images/categories/' . $categories['cat_img'] . '_s.' . $categories['cat_ext'];
                    $data['category_html'] .= '<tr>
											<td>' . $categories['cat_name'] . '</td>
											<td>' . $categories['cat_desc'] . '</td>
											<td>' . $categories['cat_title'] . '</td>
											<td>' . $parent_category_name . '</td>																						
											<td><img src="' . $image_path . '" /></td>
											<td class="delete">
												' . $edit . ' |  
												' . $del . '
											</td>
										  </tr>';
                }
            }
            $data['page'] = "categories";
            $data['categories'] = $this->categories_model->get_all_categories();
            $this->load->view('admin/categories.html', $data);
        }
    }

    public function edit_category($edit_id) {
        if (!$edit_id) {
            show_404();
            exit;
        }
        if ($this->input->post('add')) {
            $post = $this->input->post(NULL, TRUE);
            unset($post['add']);
            if ($this->categories_model->update_category($edit_id, $post))
                $this->session->set_flashdata('message', '<div class="message success"><p>Categories Updated successfully</p></div>');
            redirect('admin/category/');
        }
        $data['edit_data'] = $this->categories_model->get_category_detail($edit_id);
        $image_path = base_url() . '/assets/uploads/images/categories/' . $data['edit_data']['cat_img'] . '_s.' . $data['edit_data']['cat_ext'];
        $data['page'] = "categories";
        $data['categories'] = $this->categories_model->get_all_categories();
        $data['image_pah'] = $image_path;
        $this->load->view('admin/edit_categories.html', $data);
    }

    public function delete_categories($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->categories_model->deleteCategory($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>Categories deleted successfully</p></div>');
        redirect('admin/category/');
    }

    public function add_cat_image() {
        $time = time() . rand();
        $logo = array(
            'file' => $_FILES["userfile"],
            'new_name' => $time . '_o',
            'dst_path' => $this->config->item('image_path_category')
        );
        $logo_response = upload_original($logo);
        $src_path = $this->config->item('image_path_category') . $logo_response['file_name'];
        $logo_thumbs = array(
            array(
                'src_path' => $src_path,
                'dst_path' => $this->config->item('image_path_category'),
                'image_x' => $this->config->item('cat_image_medium_x'),
                'image_y' => $this->config->item('cat_image_medium_y'),
                'image_ratio' => TRUE,
                'image_ratio_fill' => TRUE,
                'new_name' => $time . '_m'
            ),
            array(
                'src_path' => $src_path,
                'dst_path' => $this->config->item('image_path_category'),
                'image_x' => $this->config->item('cat_image_small_x'),
                'image_y' => $this->config->item('cat_image_small_y'),
                'image_ratio' => TRUE,
                'image_ratio_fill' => TRUE,
                'new_name' => $time . '_s'
            )
        );
        foreach ($logo_thumbs as $logo_thumb) {
            upload_resized_images($logo_thumb);
        }
        echo json_encode(array('name' => $time, 'ext' => $logo_response['file_ext'], 'imagename' => other_asset_url($time . '_s.' . $logo_response['file_ext'], '', 'uploads/images/categories')));
    }

//////////////////////////////////////////////////Banner Section ///////////////////////////////////////////////////////////////////////////
    public function banner() {
        if ($this->input->post('add')) {
            if ($this->banner_model->add_banners()) {
                $this->session->set_flashdata('message', '<div class="message success"><p>New Banner has been Added successfully</p></div>');
            } else {
                $this->session->set_flashdata('message', '<div class="message errormsg"><p>Some Problem with Query!</p></div>');
            }
            redirect('admin/banner/');
        } elseif ($this->input->post('edit')) {
            
        } else {
            $banner = $this->banner_model->get_all_banners();
            $data['category_html'] = "";
            if ($banner) {
                foreach ($banner as $banners) {
                    $del = '';
                    if ($banners['user_id'] == 0) {
                        $del = '<a href="#" class="deletebanner" rel="' . $banners['id'] . '">Delete</a>';
                       if ($banners['banner_type'] == 1)
					    $edit = '<a href="' . base_url() . 'admin/edit_banner1/' . $banners['id'] . '" class="editbanners" rel="' . $banners['id'] . '">Edit</a>';
                    	else
					    $edit = '<a href="' . base_url() . 'admin/edit_banner/' . $banners['id'] . '" class="editbanners" rel="' . $banners['id'] . '">Edit</a>';
                    
					}
                    $image_path = base_url() . '/assets/uploads/images/banners/' . $banners['image_name'] . '_s.' . $banners['image_ext'];
                    $data['category_html'] .= '<tr>
											<td>' . $banners['name'] . '</td>
																						
											<td>' . $banners['description'] . '</td>																						
											<td><img src="' . $image_path . '" /></td>
											<td>' . $banners['position'] . '</td>
											<td>' . $banners['sort_order'] . '</td>
											<td class="delete">
												' . $edit . ' |  
												' . $del . '
											</td>
										  </tr>';
                }
            }
            $data['page'] = "banner";
            $this->load->view('admin/banner.html', $data);
        }
    }

    public function edit_banner($edit_id) {
        if (!$edit_id) {
            show_404();
            exit;
        }
        if ($this->input->post('add')) {
            if ($_FILES["image"]["name"] != "") {
                $cat_img = category_image_uploading($_FILES["image"]);
                $_POST['cat_img'] = $cat_img['img_name'];
                $_POST['cat_ext'] = $cat_img['img_ext'];
            }
            $post = $this->input->post(NULL, TRUE);
            unset($post['add']);
            if ($this->banner_model->update_banner($edit_id, $post))
                $this->session->set_flashdata('message', '<div class="message success"><p>Banner Updated successfully</p></div>');
            redirect('admin/banner/');
        }
        $data['edit_data'] = $this->banner_model->get_banner_detail($edit_id);
        $image_path = base_url() . '/assets/uploads/images/banners/' . $data['edit_data']['image_name'] . '_s.' . $data['edit_data']['image_ext'];
        $data['page'] = "categories";
        $data['image_pah'] = $image_path;
        $this->load->view('admin/edit_banner.html', $data);
    }


 public function edit_banner1($edit_id) {
        if (!$edit_id) {
            show_404();
            exit;
        }
        if ($this->input->post('add')) {
            if ($_FILES["image"]["name"] != "") {
                $cat_img = category_image_uploading($_FILES["image"]);
                $_POST['cat_img'] = $cat_img['img_name'];
                $_POST['cat_ext'] = $cat_img['img_ext'];
            }
            $post = $this->input->post(NULL, TRUE);
            unset($post['add']);
            if ($this->banner_model->update_banner($edit_id, $post))
                $this->session->set_flashdata('message', '<div class="message success"><p>Banner Updated successfully</p></div>');
            redirect('admin/banner/');
        }
        $data['edit_data'] = $this->banner_model->get_banner_detail($edit_id);
        $image_path = base_url() . '/assets/uploads/images/banners/' . $data['edit_data']['image_name'] . '_s.' . $data['edit_data']['image_ext'];
        $data['page'] = "categories";
        $data['image_pah'] = $image_path;
        $this->load->view('admin/edit_banner1.html', $data);
    }





    public function delete_banner($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->banner_model->delete_banner($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>Banner deleted successfully</p></div>');
        redirect('admin/banner/');
    }

    public function add_ban_image() {
        $time = time() . rand();
        $logo = array(
            'file' => $_FILES["userfile"],
            'new_name' => $time . '_o',
            'dst_path' => $this->config->item('ban_image_path')
        );
        $logo_response = upload_original($logo);
        $src_path = $this->config->item('ban_image_path') . $logo_response['file_name'];
		$ext = $logo_response['file_ext'];
		//echo $this->config->item('ban_image_path');
		createThumbs($src_path, $this->config->item('ban_image_path'),$time."_s.".$ext, $ext,100); 
		createThumbs($src_path, $this->config->item('ban_image_path'),$time."_l.".$ext, $ext,250); 
      /*  $logo_thumbs = array(
            array(
                'src_path' => $src_path,
                'dst_path' => $this->config->item('ban_image_path'),
                'image_x' => $this->config->item('ban_image_large_x'),
                'image_y' => $this->config->item('ban_image_large_y'),
                'image_ratio' => FALSE,
                'image_ratio_fill' => FALSE,
                'new_name' => $time . '_l'
            ),
            array(
                'src_path' => $src_path,
                'dst_path' => $this->config->item('ban_image_path'),
                'image_x' => $this->config->item('ban_image_small_x'),
                'image_y' => $this->config->item('ban_image_small_y'),
                'image_ratio' => TRUE,
                'image_ratio_fill' => TRUE,
                'new_name' => $time . '_s'
            )
        );
        foreach ($logo_thumbs as $logo_thumb) {
            //upload_resized_images($logo_thumb);
        }*/
        echo json_encode(array('name' => $time, 'ext' => $logo_response['file_ext'], 'imagename' => other_asset_url($time . '_s.' . $logo_response['file_ext'], '', 'uploads/images/banners')));
    }

//////////////////////////////////////////////////Banner Section End //////////////////////////////////////////////////////////////////////
    public function product() {
        if ($this->input->post('add')) {
            if ($lastid = $this->product_model->add_product()) {
                // add product deals
                $sale_type = $this->input->post('sale_type_id', TRUE);
                $sale_value_arr = $this->input->post('sale_value', TRUE);
                if ($sale_type < 4)
                    $sale_value = $sale_value_arr[$sale_type - 1];
                else
                    $sale_value = $sale_value_arr[3] . '_' . $sale_value_arr[4];
                $start_date = ($this->input->post('sale_start_date', TRUE) != '') ? date('Y-m-d', strtotime($this->input->post('sale_start_date', TRUE))) : '';
                $end_date = ($this->input->post('sale_end_date', TRUE) != '') ? date('Y-m-d', strtotime($this->input->post('sale_end_date', TRUE))) : '';
                $sale_deal = array('product_id' => $lastid,
                    'sale_type_id' => $sale_type,
                    'sale_value' => $sale_value,
                    'sale_start_date' => $start_date,
                    'sale_end_date' => $end_date);
                $this->product_model->add_product_deal($sale_deal);

                $this->session->set_flashdata('message', '<div class="message success"><p>New product has been added successfully</p></div>');
            }
            else {
                $this->session->set_flashdata('message', '<div class="message errormsg"><p>Some Problem with Query!</p></div>');
            }
            redirect('admin/product/');
        } elseif ($this->input->post('edit')) {
            
        } else {

            //$data['parent_categories'] = $this->categories_model->get_parent_categories();
            $data['categories'] = $this->categories_model->get_last_level_categories();
            $data['cities'] = $this->content_model->get_all_cities();
            $data['page'] = "product";
            $this->load->view('admin/products.html', $data);
        }
    }

    public function products_html() {
        $get_request = $this->input->get(NULL, TRUE);
        $filteraColumns = array('product_name', 'cat_name', 'image', 'sale_start_date', 'sale_end_date', 'store_name', 'product_view', 'buy_link_hits', 'is_featured', 'action');
        $aColumns = array('products.product_name', 'categories.cat_name', 'image', 'product_deal.sale_start_date', 'product_deal.sale_end_date', 'products.store_name', 'products.product_view', 'products.buy_link_hits', 'products.is_featured');
        $whereColumns = array('products.product_name', 'categories.cat_name', 'products.store_name', 'product_deal.sale_start_date', 'product_deal.sale_end_date');
        $sWhere = "";
        $group_by = "";
        $sQuery = "SELECT *, `products`.`id` as pro_id FROM (`products`) 
					LEFT JOIN `product_deal` ON `product_deal`.`product_id` = `products`.`id` 
					LEFT JOIN `cities` ON `cities`.`city_id` = `products`.`city_id` 
					LEFT JOIN `categories` ON `categories`.`cat_id` = `products`.`cat_id` 
					";
        $is_view = FALSE;
        $view_link = '';
        $is_edit = TRUE;
        $edit_link = 'edit_product';
        $is_delete = TRUE;
        $delete_link = '';

        $response = ajax_admin_html($sQuery, $sWhere, $group_by, $aColumns, $filteraColumns, $whereColumns, $get_request, $is_view, $view_link, $is_edit, $edit_link, $is_delete, $delete_link);
        echo json_encode($response);
    }

    public function change_cat() {
        $cat_id = $this->input->post('cat_id', TRUE);
        $categories = $this->categories_model->get_child_categories($cat_id);
        echo '<select class="catstyled" name="cat_id" id="cat_id">
				' . create_ddl($categories, 'cat_id', 'cat_name') . '
			</select>';
    }

    public function edit_product($edit_id) {
        if (!$edit_id) {
            show_404();
            exit;
        }
        if ($this->input->post('add')) {
            $this->product_model->update_product($edit_id);
            // add product deals
            $sale_type = $this->input->post('sale_type_id', TRUE);
            $sale_value_arr = $this->input->post('sale_value', TRUE);
            if ($sale_type < 4)
                $sale_value = $sale_value_arr[$sale_type - 1];
            else
                $sale_value = $sale_value_arr[3] . '_' . $sale_value_arr[4];
            $start_date = ($this->input->post('sale_start_date', TRUE) != '') ? date('Y-m-d', strtotime($this->input->post('sale_start_date', TRUE))) : '';
            $end_date = ($this->input->post('sale_end_date', TRUE) != '') ? date('Y-m-d', strtotime($this->input->post('sale_end_date', TRUE))) : '';
            $sale_deal = array('sale_type_id' => $sale_type,
                'sale_value' => $sale_value,
                'sale_start_date' => $start_date,
                'sale_end_date' => $end_date);
            $this->product_model->update_product_deal($edit_id, $sale_deal);
            $this->session->set_flashdata('message', '<div class="message success"><p>Product update successfully</p></div>');
            redirect('admin/product/');
        }
        $data['edit_data'] = $this->product_model->get_product_detail($edit_id);
        $data['pro_images'] = $this->more_pimages_html($edit_id);
        $data['category_detail'] = $this->categories_model->get_category_detail($data['edit_data']['cat_id']);
        //$data['parent_categories'] = $this->categories_model->get_parent_categories();
        $data['categories'] = $this->categories_model->get_last_level_categories();
        $data['cities'] = $this->content_model->get_all_cities();
        $data['edit_data']['sale1'] = '';
        $data['edit_data']['sale2'] = '';
        if (isset($data['edit_data']['sale_type_id']) && ($data['edit_data']['sale_type_id'] == 4)) {
            $sale_val = explode('_', $data['edit_data']['sale_value']);
            $data['edit_data']['sale1'] = $sale_val[0];
            $data['edit_data']['sale2'] = $sale_val[1];
        }
        $data['page'] = "product";
        $this->load->view('admin/edit_product.html', $data);
    }

    public function more_pimages_html($id) {
        $images_data = $this->product_model->get_product_more_images($id);
        $image_name = array();
        $image_ext = array();
        $html = '';
        if ($images_data) {
            foreach ($images_data as $value) {
                $image_name[] = $value['image_name'];
                $image_ext[] = $value['image_ext'];
                $html .= '<li><img src="' . other_asset_url($value['image_name'] . '_s.' . $value['image_ext'], "", "uploads/images/products") . '" width="32" height="32" alt="image" />
				              <div class="clr"></div><a href="#" class="delproductimg" rel="' . $value['image_name'] . '_' . $value['image_ext'] . '">Delete</a> </li>';
            }
        }
        return array('image_name' => $image_name, 'image_ext' => $image_ext, 'html' => $html);
    }

    public function delete_product($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->product_model->delete_product($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>Product deleted successfully</p></div>');
        redirect('admin/product/');
	  //	$this->output->enable_profiler(TRUE);

    }

    public function add_pro_image() {
        $time = time() . rand();
        $logo = array(
            'file' => $_FILES["userfile"],
            'new_name' => $time . '_o',
            'dst_path' => $this->config->item('pro_image_path')
        );
        $logo_response = upload_original($logo);
        $src_path = $this->config->item('pro_image_path') . $logo_response['file_name'];
        $logo_thumbs = array(
            array(
                'src_path' => $src_path,
                'dst_path' => $this->config->item('pro_image_path'),
                'image_x' => $this->config->item('pro_image_large_x'),
                'image_y' => $this->config->item('pro_image_large_y'),
                'image_ratio' => TRUE,
                'image_ratio_fill' => TRUE,
                'new_name' => $time . '_l'
            ),
            array(
                'src_path' => $src_path,
                'dst_path' => $this->config->item('pro_image_path'),
                'image_x' => $this->config->item('pro_image_medium_x'),
                'image_y' => $this->config->item('pro_image_medium_y'),
                'image_ratio' => TRUE,
                'image_ratio_fill' => TRUE,
                'new_name' => $time . '_m'
            ),
            array(
                'src_path' => $src_path,
                'dst_path' => $this->config->item('pro_image_path'),
                'image_x' => $this->config->item('pro_image_small_x'),
                'image_y' => $this->config->item('pro_image_small_y'),
                'image_ratio' => TRUE,
                'image_ratio_fill' => TRUE,
                'new_name' => $time . '_s'
            )
        );
        foreach ($logo_thumbs as $logo_thumb) {
            upload_resized_images($logo_thumb);
        }
        echo json_encode(array('name' => $time, 'ext' => $logo_response['file_ext'], 'imagename' => other_asset_url($time . '_s.' . $logo_response['file_ext'], '', 'uploads/images/products')));
    }

    function unlink_promoreimage() {
        $img = explode('_', $this->input->post('img'));
        unlink($this->config->item('pro_image_path') . $img[0] . '_o.' . $img[1]);
        unlink($this->config->item('pro_image_path') . $img[0] . '_s.' . $img[1]);
        unlink($this->config->item('pro_image_path') . $img[0] . '_m.' . $img[1]);
        unlink($this->config->item('pro_image_path') . $img[0] . '_l.' . $img[1]);
        $this->db->delete('product_images', array('image_name' => $img[0]));
    }

//*********************************************** News Section***********************************************************************//
    public function news() {
        if ($this->input->post('add')) {
            if ($this->news_model->add_news()) {
                $this->session->set_flashdata('message', '<div class="message success"><p>New News has been Added successfully</p></div>');
            } else {
                $this->session->set_flashdata('message', '<div class="message errormsg"><p>Some Problem with Query!</p></div>');
            }
            redirect('admin/news/');
        } elseif ($this->input->post('edit')) {
            
        } else {
            $news = $this->news_model->get_all_news();
            $data['news_html'] = "";
            if ($news) {
                foreach ($news as $news) {
                    $del = '';
                    if ($news['user_id'] == 0) {
                        $del = '<a href="' . base_url() . 'admin/delete_news/' . $news['id'] . '" class="deletenews" rel="' . $news['id'] . '">Delete</a>';
                        $edit = '<a href="' . base_url() . 'admin/edit_news/' . $news['id'] . '" class="editnews" rel="' . $news['id'] . '">Edit</a>';
                    }
                    if ($news['user_id'] != 0) {
                        $user_info = $this->admin_model->get_user($news['user_id']);
                        $name = $user_info['first_name'] . ' ' . $user_info['last_name'];
                    } else {
                        $user_info = $this->admin_model->get_admin_user($this->session->userdata('admin_sample'));
                        $name = $user_info['admin_name'];
                    }
                    $data['news_html'] .= '<tr>
												<td>' . $news['news_text'] . '</td>													
												<td>' . $news['news_status'] . '</td>
												<td>' . $name . '</td>													
												<td class="delete">
													' . $edit . ' | ' . $del . '
												</td>
											  </tr>';
                }
            }
            $data['page'] = "news";
            $this->load->view('admin/news.html', $data);
        }
    }

    public function edit_news($edit_id) {
        if (!$edit_id) {
            show_404();
            exit;
        }
        if ($this->input->post('add')) {
            if ($this->news_model->update_news($edit_id))
                $this->session->set_flashdata('message', '<div class="message success"><p>News Updated successfully</p></div>');
            redirect('admin/news/');
        }
        $data['edit_data'] = $this->news_model->get_news_detail($edit_id);
        $data['page'] = "news";
        $this->load->view('admin/edit_news.html', $data);
    }

    public function delete_news($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->news_model->delete_news($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>News deleted successfully</p></div>');
        redirect('admin/news/');
    }

//*********************************************** News Section End*****************************************************************//	

    public function user_invoices() {
        if ($this->input->post('edit')) {
            
        } else if ($this->input->post('add')) {
            $post = $this->input->post(NULL, TRUE);
            $user_id = $this->input->get('user_id');
            unset($post['add']);
            if ($this->users->add_user_subscription_invoice($post, $user_id)) {
                $this->session->set_flashdata('message', '<div class="message success"><p>User subscription Added successfully</p></div>');
                redirect('admin/user_invoices/');
            }
        } else {
            //$this->load->model("users");
            $invoice = $this->users->get_all_users_subscription();
            $data['invoices_html'] = "";
            if ($invoice) {
                foreach ($invoice as $invoices) {
                    $del = '';
                    $del = '<a href="javascipt:void(0)" class="deleteinvoice" rel="' . $invoices['suid'] . '">Delete</a>';
                    $edit = '<a href="' . base_url() . 'admin/edit_invoice/' . $invoices['suid'] . '" class="editinvoice" rel="' . $invoices['suid'] . '">Edit</a>';
                    $is_active_field = ($invoices['is_active']) ? 'Active' : 'In-active';
                    $data['invoices_html'] .= '<tr>
											<td>' . $invoices['suid'] . '</td>
											<td>' . $invoices['email'] . '</td>
											<td>' . $invoices['subs_name'] . '</td>
											<td>' . $invoices['totalprice'] . '</td>
											<td>' . $invoices['due_date'] . '</td>
											<td>' . $is_active_field . '</td>
											<td>' . $invoices['status'] . '</td>
											<td class="delete">
												' . $edit . ' |  
												' . $del . '
											</td>
										  </tr>';
                }
            }
            $data['page'] = "user_invoices";
            $this->load->view('admin/invoice.html', $data);
        }
    }

    public function edit_invoice($edit_id) {
        if (!$edit_id) {
            show_404();
            exit;
        }
        if ($this->input->post('add')) {
            $post = $this->input->post(NULL, TRUE);
            unset($post['add']);
            if ($this->users->update_subscription_users($edit_id, $post)) {
                $this->session->set_flashdata('message', '<div class="message success"><p>User subscription Updated successfully</p></div>');
                redirect('admin/user_invoices/');
            }
        }
        $data['edit_data'] = $this->users->get_subscription_detail_by_id($edit_id);
        $data['page'] = "User subscriptions";
        $this->load->view('admin/edit_invoice.html', $data);
    }

    public function delete_invoice($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->users->delete_user_subscription_invoice($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>Subscription invoice deleted successfully</p></div>');
        redirect('admin/user_invoices/');
    }

    public function users($user_type = 1) {
        if ($this->input->post('add')) {
            if ($this->admin_model->addRetailerUsers()) {
                $this->session->set_flashdata('message', '<div class="message success"><p>New Retailer has been Added successfully</p></div>');
            } else {
                $this->session->set_flashdata('message', '<div class="message errormsg"><p>Some Problem with Query!</p></div>');
            }
            redirect('admin/users/2');
        } else {
            $users = $this->admin_model->get_all_users($user_type);
            $data['users_html'] = "";
            if ($users)
                foreach ($users as $user) {
                    if ($user['banned'])
                        $banned = '<a href="#" class="banuser" rel="' . $user['user_id_m'] . '" title="Activate">Activate</a>';
                    else
                        $banned = '<a href="#" class="banuser" rel="' . $user['user_id_m'] . '" title="Deactivate">Deactivate</a>';
                    if ($user_type == 1) {
                        $data['users_html'] .= '<tr>										
										<td>' . $user['email'] . '</td>
										<td>' . (($user_type == 1) ? $user['first_name'] . ' ' . $user['last_name'] : $user['contact_person']) . '</td>										
										<td>' . (($user['gender'] == 1) ? 'Male' : 'Female') . '</td>
										<td>' . $user['dob'] . '</td>
										<td>' . $user['city_name'] . '</td>
										<td class="delete">
											' . $banned . ' | 
											<a href="#" class="deleteproduct" rel="' . $user['user_id_m'] . '">Delete</a>
										</td>			
									</tr>';
                    } else {
                        $data['users_html'] .= '<tr>										
										<td>' . $user['email'] . '</td>
										<td>' . $user['company_name'] . '</td>										
										<td>' . $user['description'] . '</td>
										<td>' . $user['contact_person'] . '</td>
										<td class="delete">
											<a href="' . base_url() . 'admin/edit_user/' . $user['user_id_m'] . '/" rel="' . $user['user_id_m'] . '">Edit</a> | 
											' . $banned . ' | 
											<a href="#" class="deleteproduct" rel="' . $user['user_id_m'] . '">Delete</a>|
											<a href="' . base_url() . 'admin/user_invoices?user_id=' . $user['user_id_m'] . '" class="addinvoice" >Add User invoice</a>
										</td>			
									</tr>';
                    }
                }
            $data['page'] = "users";
            $data['group_id'] = $user_type;
            $data['cities'] = $this->content_model->get_all_cities();
            $this->load->view("admin/users.html", $data);
			//$this->output->enable_profiler(TRUE);
        }
    }

    public function add_retailer_image() {
        $time = time() . rand();
        $logo = array(
            'file' => $_FILES["userfile"],
            'new_name' => $time . '_o',
            'dst_path' => $this->config->item('retailer_image_path')
        );
        $logo_response = upload_original($logo);
        $src_path = $this->config->item('retailer_image_path') . $logo_response['file_name'];
        $logo_thumbs = array(
            array(
                'src_path' => $src_path,
                'dst_path' => $this->config->item('retailer_image_path'),
                'image_x' => $this->config->item('retailer_image_large_x'),
                'image_y' => $this->config->item('retailer_image_large_y'),
                'image_ratio' => TRUE,
                'image_ratio_fill' => TRUE,
                'new_name' => $time . '_l'
            ),
            array(
                'src_path' => $src_path,
                'dst_path' => $this->config->item('retailer_image_path'),
                'image_x' => $this->config->item('retailer_image_medium_x'),
                'image_y' => $this->config->item('retailer_image_medium_y'),
                'image_ratio' => TRUE,
                'image_ratio_fill' => TRUE,
                'new_name' => $time . '_m'
            ),
            array(
                'src_path' => $src_path,
                'dst_path' => $this->config->item('retailer_image_path'),
                'image_x' => $this->config->item('retailer_image_small_x'),
                'image_y' => $this->config->item('retailer_image_small_y'),
                'image_ratio' => TRUE,
                'image_ratio_fill' => TRUE,
                'new_name' => $time . '_s'
            )
        );
        foreach ($logo_thumbs as $logo_thumb) {
            upload_resized_images($logo_thumb);
        }
        echo json_encode(array('name' => $time, 'ext' => $logo_response['file_ext'], 'imagename' => other_asset_url($time . '_s.' . $logo_response['file_ext'], '', 'uploads/images/retailer')));
    }

    public function edit_user($edit_id) {
        if (!$edit_id) {
            show_404();
            exit;
        }
        if ($this->input->post('add')) {
            if ($this->admin_model->update_retailer_users()) {
                $this->session->set_flashdata('message', '<div class="message success"><p>Retailer has been Updated successfully</p></div>');
            } else {
                $this->session->set_flashdata('message', '<div class="message errormsg"><p>Some Problem with Query!</p></div>');
            }
            redirect('admin/users/2');
        }
        $data['edit_data'] = $this->admin_model->get_retail_user($edit_id);
        $data['page'] = "users";
        $data['cities'] = $this->content_model->get_all_cities();
        $this->load->view('admin/edit_user.html', $data);
    }

    public function ban_user() {
        $post = $this->input->post(NULL, TRUE);
        if ($post['action'] == 'Activate') {
            $this->users->unban_user($post['id']);
            echo json_encode(array('link' => '<a href="#" class="banuser" rel="' . $post['id'] . '" title="Deactivate">Deactivate</a>',
                'message' => '<div class="message success"><p>User activate successfully</p></div>'));
        } else {
            $this->users->ban_user($post['id']);
            echo json_encode(array('link' => '<a href="#" class="banuser" rel="' . $post['id'] . '" title="Activate">Activate</a>',
                'message' => '<div class="message success"><p>User deactivate successfully</p></div>'));
        }
    }

    public function delete_user($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $query = $this->admin_model->delete_user($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>User deleted successfully</p></div>');
        redirect('admin/users/2');
    }

    public function package() {
        $this->load->model('tank_auth/users');
        if ($this->input->post('add')) {
            if ($this->users->add_subscription_level()) {
                $this->session->set_flashdata('message', '<div class="message success"><p>New Subscription Package has been Added successfully</p></div>');
            } else {
                $this->session->set_flashdata('message', '<div class="message errormsg"><p>Some Problem with Query!</p></div>');
            }
            redirect('admin/package/');
        } elseif ($this->input->post('edit')) {
            
        } else {
            $packages = $this->users->get_subscription_levels();
            $data['package_html'] = "";
            if ($packages) {
                foreach ($packages as $package) {
                    $del = '<a href="#" class="deletepackage" rel="' . $package['id'] . '">Delete</a>';
                    $edit = '<a href="' . base_url() . 'admin/edit_package/' . $package['id'] . '" class="editbanners" rel="' . $package['id'] . '">Edit</a>';
                    $data['package_html'] .= '<tr>
											<td>' . $package['name'] . '</td>
											<td>' . $package['initial_payment'] . '</td>																						
											<td>' . $package['billing_amount'] . '</td>
											<td>' . $package['cycle_number'] . '</td>
											<td>' . $package['cycle_period'] . '</td>
											<td>' . (($package['category_limit'] == 0) ? 'Unlimited' : $package['category_limit']) . '</td>
                                                                                        <td>' . (($package['ads_limit'] == 0) ? 'Unlimited' : $package['ads_limit']) . '</td>
											<td class="delete">
												' . $edit . ' |  
												' . $del . '
											</td>
										  </tr>';
                }
            }
            $data['page'] = "package";
            $this->load->view('admin/package.html', $data);
        }
    }

    public function edit_package($edit_id) {
        if (!$edit_id) {
            show_404();
            exit;
        }
        $this->load->model('tank_auth/users');
        if ($this->input->post('add')) {
            $post = $this->input->post(NULL, TRUE);
            unset($post['add']);
            if ($this->users->update_subscription_level($edit_id, $post))
                $this->session->set_flashdata('message', '<div class="message success"><p>Subscription Package Updated successfully</p></div>');
            redirect('admin/package/');
        }
        $data['edit_data'] = $this->users->get_subscription_detail($edit_id);
        $data['page'] = "package";
        $this->load->view('admin/edit_package.html', $data);
    }

    public function delete_package($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->load->model('tank_auth/users');
        $query = $this->users->delete_subscription_level($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>Subscription Package deleted successfully</p></div>');
        redirect('admin/package/');
    }

    public function contents() {
        $this->load->helper('text');
        if ($this->input->post('add')) {
            $this->content_model->add_content();
			$edit_id = $this->db->insert_id();
			#code for image upload
			$filename = $_FILES['c_image']['name'];
			$extention = $this->uploadimages->getExtension($filename);
			$imageError= $this->uploadimages->isValidImg($extention);
			
			$imageName = '';		
			if(empty($imageError))
			{		
				$baseName=basename($edit_id.".".$extention);							
				$target_path = dirname(BASEPATH).'/assets/uploads/images/page_contents/'.$baseName; 
				$temp = move_uploaded_file($_FILES['c_image']['tmp_name'], $target_path);								
				$imageName = $baseName;
				updateImage($edit_id,$imageName);
			}
            $this->session->set_flashdata('message', '<div class="message success"><p>Content Added successfully</p></div>');
            redirect('admin/contents/');
        } else {
            $contents = $this->content_model->get_all_content();
            $data['contents_html'] = "";
            if ($contents) {
                foreach ($contents as $content) {
                    $data['contents_html'] .= '<tr>
											<td>' . $content['c_name'] . '</td>
											<td>' . $content['c_title'] . '</td>
											<td>' . word_limiter($content['c_text'], 20) . '</td>
											<td class="delete">
												<a href="' . base_url() . 'admin/edit_content/' . $content['c_id'] . '">Edit</a>
												<a href="#" class="deletecontent" rel="' . $content['c_id'] . '">Delete</a>
											</td>
																					
										  </tr>';
                }
            }
            $data['page'] = "contents";
            $this->load->view('admin/contents.html', $data);
        }
    }

    public function edit_content($edit_id) {
        if (!$edit_id) {
            show_404();
            exit;
        }
        if ($this->input->post('add')) {
            $this->content_model->update_content($edit_id);
			
			#code for image upload
			$filename = $_FILES['c_image']['name'];
			$extention = $this->uploadimages->getExtension($filename);
			$imageError= $this->uploadimages->isValidImg($extention);
			
			$imageName = '';		
			if(empty($imageError))
			{		
				$baseName=basename($edit_id.".".$extention);							
				$target_path = dirname(BASEPATH).'/assets/uploads/images/page_contents/'.$baseName; 
				$temp = move_uploaded_file($_FILES['c_image']['tmp_name'], $target_path);								
				$imageName = $baseName;
				updateImage($edit_id,$imageName);
			}
			
			
            $this->session->set_flashdata('message', '<div class="message success"><p>Content Update successfully</p></div>');
            redirect('admin/contents/');
        }
        $data['edit_data'] = $this->content_model->get_content($edit_id);
        $data['page'] = "contents";
        $this->load->view('admin/edit_contents.html', $data);
    }

    public function delete_content($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->content_model->delete_content($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>Content deleted successfully</p></div>');
        redirect('admin/contents/');
    }

    public function cuisines() {
        if ($this->input->post('add')) {
            if (!$this->cuisine_model->find_cuisine($this->input->post('name', TRUE))) {
                $this->cuisine_model->add_cuisine();
                $this->session->set_flashdata('message', '<div class="message success"><p>Cuisine Added successfully</p></div>');
            } else {
                $this->session->set_flashdata('message', '<div class="message errormsg"><p>Cuisine Already exist!</p></div>');
            }
            redirect('admin/cuisines/');
        } elseif ($this->input->post('edit')) {
            
        } else {
            $cuisines = $this->cuisine_model->get_all_cuisine();
            $data['cuisine_html'] = "";
            if ($cuisines) {
                foreach ($cuisines as $cuisine) {
                    $data['cuisine_html'] .= '<tr>
											<td>' . $cuisine['name'] . '</td>
											<td class="delete">
												<a href="#" class="deleteproduct" rel="' . $cuisine['id'] . '">Delete</a>
											</td>
																					
										  </tr>';
                }
            }
            $data['page'] = "cuisines";
            $this->load->view('admin/cuisines.html', $data);
        }
    }

    public function delete_cuisine($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->cuisine_model->delete_cuisine($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>Cuisine deleted successfully</p></div>');
        redirect('admin/cuisines/');
    }

    public function rating() {
        if ($this->input->post('add')) {
            
        } elseif ($this->input->post('edit')) {
            
        } else {
            $comments = $this->truck_model->get_all_comments();
            $data['comment_html'] = "";
            if ($comments) {
                foreach ($comments as $comment) {
                    $data['comment_html'] .= '<tr>
											<td>' . $comment['truck_name'] . '</td>
											<td>' . $comment['first_name'] . ' ' . $comment['last_name'] . '</td>
											<td>' . $comment['comments'] . '</td>
											<td>' . floor($comment['crating']) . '/5</td>
											<td class="delete">
												<a href="#" class="deleteproduct" rel="' . $comment['comment_id'] . '_' . $comment['truckid'] . '">Delete</a>
											</td>
																					
										  </tr>';
                }
            }
            $data['page'] = "rating";
            $this->load->view('admin/rating.html', $data);
        }
    }

    public function delete_rating($id) {
        $truck_comment = explode('_', $id);
        if (!$truck_comment[0]) {
            show_404();
            exit;
        }
        $this->truck_model->delete_truck_comments(array('comment_id' => $truck_comment[0], 'truck_id' => $truck_comment[1]));
        $this->session->set_flashdata('message', '<div class="message success"><p>Comments deleted successfully</p></div>');
        redirect('admin/rating/');
    }

    public function suggestion() {

        if ($this->input->post('add')) {
            
        } elseif ($this->input->post('edit')) {
            
        } else {
            $suggestions = $this->suggestion_model->get_all_suggestion();
            $data['suggestion_html'] = "";
            if ($suggestions) {
                foreach ($suggestions as $suggestion) {
                    $data['suggestion_html'] .= '<tr>
											<td>' . $suggestion['first_name'] . ' ' . $suggestion['last_name'] . '</td>
											<td>' . $suggestion['name'] . '</td>
											<td>' . $suggestion['suggestion'] . '</td>
											<td>' . $suggestion['created'] . '</td>
											<td class="delete">
												<a href="#" class="deleteproduct" rel="' . $suggestion['suggestion_id'] . '">Delete</a>
											</td>										
										  </tr>';
                }
            }
            $data['page'] = "suggestion";
            $this->load->view('admin/suggestion.html', $data);
        }
    }

    public function delete_suggestion($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->suggestion_model->delete_suggestion($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>Suggestion deleted successfully</p></div>');
        redirect('admin/suggestion/');
    }

    public function admins() {
        if ($this->input->post('add')) {
            if (!$this->admin_model->check_admin_available()) {
                $this->admin_model->addAdminUsers();
                $this->session->set_flashdata('message', '<div class="message success"><p>Admin Added successfully</p></div>');
            } else {
                $this->session->set_flashdata('message', '<div class="message errormsg"><p>Admin Already exist!</p></div>');
            }
            redirect('admin/admins/');
        } elseif ($this->input->post('edit')) {
            
        } else {
            $users = $this->admin_model->getAllAdminUsers();
            $data['users_html'] = "";
            if ($users) {
                foreach ($users as $user) {
                    $del = '<a href="' . base_url() . 'admin/edit_admin/' . $user['admin_id'] . '" rel="' . $user['admin_id'] . '">Edit</a>';
                    if ($user['admin_id'] != 1) {
                        $del .= ' | <a href="#" class="deleteproduct" rel="' . $user['admin_id'] . '">Delete</a>';
                    }
                    $data['users_html'] .= '<tr>
											<td>' . $user['admin_name'] . '</td>
											<td>' . $user['email'] . '</td>
											<td class="delete">
												' . $del . '
											</td>
																					
										  </tr>';
                }
            }
            $data['page'] = "admins";
            $this->load->view('admin/admins.html', $data);
        }
    }

    public function edit_admin($id) {
        if (!$id) {
            show_404();
            exit;
        }
        if ($this->input->post('add')) {
            $post = $this->input->post(null, true);
            if(!$post['password']){
                unset($post['password']);
            }
            unset($post['cpassword']);
            unset($post['add']);
            if ($this->admin_model->updateAdminUsers($post, $id)) {
                $this->session->set_flashdata('message', '<div class="message success"><p>Admin Updated successfully</p></div>');
            } 
            redirect('admin/admins/');
        } else {
            $data['edit_data'] = $this->admin_model->get_admin_user($id);
            $data['page'] = "admins";
            $this->load->view('admin/edit_admins.html', $data);
        }
    }

    public function delete_admin($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->admin_model->deleteAdminUser($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>Admin deleted successfully</p></div>');
        redirect('admin/admins/');
    }

    public function wishlist() {
        if ($this->input->post('add')) {
            if ($this->wishlist_model->add_wishlist_product()) {
                $this->session->set_flashdata('message', '<div class="message success"><p>New Product has been Added successfully to WishList</p></div>');
            } else {
                
            }
            redirect('admin/wishlist/');
        } elseif ($this->input->post('edit')) {
            
        } else {
            $wishlist = $this->wishlist_model->get_all_wishedproducts();
            $products = $this->product_model->get_all_products();
            $data['wishlist_html'] = "";
            if ($wishlist) {
                foreach ($wishlist as $wishlist) {
                    $user_info = $this->user_model->select_user_by_id($wishlist['user_id']);
                    $product_info = $this->product_model->get_product_detail($wishlist['product_id']);
                    if ($wishlist['user_id'] == 0) {
                        $del = '<a href="#" class="deletewishlist" rel="' . $wishlist['wish_id'] . '">Delete</a>';
                        $edit = '<a href="' . base_url() . 'admin/edit_wishlist/' . $wishlist['wish_id'] . '" class="editcategories" rel="' . $wishlist['wish_id'] . '">Edit</a>';
                    }
                    $data['wishlist_html'] .= '<tr>
											<td>' . $user_info['username'] . '</td>
											<td>' . $product_info['product_name'] . '</td>											
											<td class="delete">
												' . $edit . ' |  
												' . $del . '
											</td>
										  </tr>';
                }
            }
            $data['page'] = "wishlist";
            $data['products'] = $products;
            $this->load->view('admin/wishlist.html', $data);
        }
    }

    public function edit_wishlist($edit_id) {
        if (!$edit_id) {
            show_404();
            exit;
        }
        $products = $this->product_model->get_all_products();
        if ($this->input->post('add')) {
            if ($this->wishlist_model->update_wishlist($edit_id))
                $this->session->set_flashdata('message', '<div class="message success"><p>New Product has been Updated successfully</p></div>');
            redirect('admin/wishlist/');
        }
        $data['edit_data'] = $this->wishlist_model->get_wishlist_detail($edit_id);
        $data['page'] = "wishlist";
        $data['products'] = $products;
        $this->load->view('admin/edit_wishlist.html', $data);
    }

    public function delete_wishlist($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->wishlist_model->delete_wishlist_product($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>Product removed from Wish List successfully</p></div>');
        redirect('admin/wishlist');
    }

    public function subscriber() {
        if ($this->input->post('add')) {
            if ($this->newsletter_model->add_newsletter()) {
                $this->session->set_flashdata('message', '<div class="message success"><p>New Product has been Added successfully to WishList</p></div>');
            } else {
                
            }
            redirect('admin/subscriber/');
        } elseif ($this->input->post('edit')) {
            
        } else {
            $newsletter = $this->newsletter_model->get_all_newsletter();
            $data['newsletter_html'] = "";
            if ($newsletter) {
                foreach ($newsletter as $newsletters) {
                    if ($newsletters['user_id'] == 0) {
                        $del = '<a href="#" class="deletenewsletter" rel="' . $newsletters['id'] . '">Delete</a>';
                    }
                    $data['newsletter_html'] .= '<tr>											
											<td>' . $newsletters['email'] . '</td>											
											<td class="delete">												  
												' . $del . '
											</td>
										  </tr>';
                }
            }
            $data['page'] = "users";
            $this->load->view('admin/newsletter.html', $data);
        }
    }

    /* public function edit_newsletter($edit_id){
      if(!$edit_id){
      show_404();exit;
      }
      $products = $this->product_model->get_all_products();
      if($this->input->post('add')){
      if($this->newsletter_model->update_newsletter($edit_id))
      $this->session->set_flashdata('message', '<div class="message success"><p>Email ID has been Updated successfully</p></div>');
      redirect('admin/newsletter/');
      }
      $data['edit_data'] 	= $this->newsletter_model->get_newsletter_detail($edit_id);
      $data['page'] 		= "newsletter";
      $this->load->view('admin/edit_newsletter.html',$data);
      } */

    public function delete_newsletter($id) {
        if (!$id) {
            show_404();
            exit;
        }
        $this->newsletter_model->delete_newsletter($id);
        $this->session->set_flashdata('message', '<div class="message success"><p>Email removed from newsletter List</p></div>');
        redirect('admin/subscriber');
    }

    public function dowanload_subscriber() {
        $file = 'subscriber_data_export';
        $csv_output = "";
        $csv_output = $this->subscriber_analytics();
        $filename = $file . "_" . date("Y-m-d_H-i", time());
        header('Content-Type: text/csv;');
        header("Content-disposition: csv" . date("Y-m-d") . ".csv");
        header("Content-disposition: filename=" . $filename . ".csv");
        print $csv_output;
    }

    public function subscriber_analytics() {
        $subscribers = $this->newsletter_model->get_all_newsletter();
        $tr = "";
        $i = 1;
        if ($subscribers) {
            foreach ($subscribers as $row) {
                $tr .= "\n" . $i . "," . encode_link_safe_string($row['email']) . "," .
                        encode_link_safe_string($row['createdTimeStamp']);
                $i++;
            }
        } else {
            $tr .= "\nNo user avaliable.";
        }
        $response = ",Subscriber Analytics\nSr.No,Email,Created At";
        $response .= $tr;
        return $response;
    }

    public function parent_category($parent_cat_id = '') {
        $parent_categgories = $this->categories_model->get_parent_categories();
        $options = create_ddl($parent_categgories, 'cat_id', 'cat_name', $parent_cat_id);
        $testing = '';
        $testing .= $options;
        return $testing;
    }

    public function child_category() {
        $parent_category = $_POST['parent_id'];
        $child_categgories = $this->categories_model->get_child_categories($parent_category);
        if ($child_categgories) {
            $options = create_ddl($child_categgories, 'cat_id', 'cat_name', 'cat_id');
            $testing = '';
            $testing .= $options;
            echo $testing;
        } else {
            echo '<option value = 0 selected>NULL</option>';
        }
    }

    public function categories_filter_fields() {
        $cat_id = $this->input->get("cat_id");
        $fields = $this->categories_model->get_filters_fields($cat_id);
        foreach ($fields as $key => $value) {
            if ($key == 'price') {
                continue;
            }
            echo '<p>
			            <label>' . $value . '</label>			
			            <br />			
			            <input name="' . $value . '" type="text" class="required text small" id="' . $value . '" />			
			            <span class="note error"></span> </p>';
        }
    }

}