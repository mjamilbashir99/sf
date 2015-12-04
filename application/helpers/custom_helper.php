<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if(!function_exists('updateImage'))
{
	function updateImage($cid,$image)
	{
		$ci=& get_instance();
		$ci->load->database(); 
		$sql = "UPDATE content SET c_image='{$image}' WHERE c_id=$cid LIMIT 1"; 
        $query = $ci->db->query($sql);
	}
}

/*
 * do uploding of category image
 * return destination file name
 */

function category_image_uploading($file) {
    $ci = & get_instance();
    if ($file["name"] != "") {
        $time = time() . rand() . '_category';
        $category = array(
            'file' => $file,
            'new_name' => $time,
            'dst_path' => $ci->config->item('image_path_category')
        );
        $category_response = upload_original($category);
        $src_path = $ci->config->item('image_path_category') . $category_response['file_name'];
        $category_thumbs = array(
            array(
                'src_path' => $src_path,
                'dst_path' => $ci->config->item('image_path_category'),
                'image_x' => $ci->config->item('cat_image_medium_x'),
                'image_y' => $ci->config->item('cat_image_medium_y'),
                'image_ratio' => TRUE,
                'image_ratio_fill' => TRUE,
                'new_name' => $time . '_m'
            ),
            array(
                'src_path' => $src_path,
                'dst_path' => $ci->config->item('image_path_category'),
                'image_x' => $ci->config->item('cat_image_small_x'),
                'image_y' => $ci->config->item('cat_image_small_y'),
                'image_ratio' => TRUE,
                'image_ratio_fill' => TRUE,
                'new_name' => $time . '_s'
            )
        );
        foreach ($category_thumbs as $category_thumb) {
            upload_resized_images($category_thumb);
        }
        return array('img_name' => $time, 'img_ext' => $category_response['file_ext']);
    }
    return array();
}

/*
 * do uploding of original file
 * return destination file name
 */

function upload_original($param = array()) {
    $ci = & get_instance();
    $ci->load->library('my_upload');
    if ($param["file"]["name"] != "") {
        $ci->my_upload->upload($param["file"]);
        if ($ci->my_upload->uploaded == true) {
            $ci->my_upload->file_new_name_body = $param['new_name'];
            $ci->my_upload->process($param['dst_path']);
        }
        return array('file_name' => $ci->my_upload->file_dst_name, 'file_ext' => $ci->my_upload->file_dst_name_ext);
    }
}

/*
 * do resizing of source file
 * upload to server
 */

function upload_resized_images($param = array()) {
    $ci = & get_instance();
    $ci->load->library('my_upload');
    $ci->my_upload->upload($param['src_path']);
    if ($ci->my_upload->uploaded == true) {
        $ci->my_upload->image_resize = true;
        $ci->my_upload->image_ratio = $param['image_ratio'];
        $ci->my_upload->image_ratio_fill = $param['image_ratio_fill'];
        $ci->my_upload->image_x = $param['image_x'];
        $ci->my_upload->image_y = $param['image_y'];
        $ci->my_upload->file_new_name_body = $param['new_name'];
	    $ci->my_upload->process($param['dst_path']);
    }
	else{
		echo $ci->my_upload->error;
		}
}

function create_ddl($data, $value_index = 0, $label_index = 1, $selected = 0) {
    $option = '';
    foreach ($data as $value) {
        $is_selected = '';
        if ($selected == $value[$value_index])
            $is_selected = 'selected="selected"';
        $option .= '<option value="' . $value[$value_index] . '" ' . $is_selected . '>' . $value[$label_index] . '</option>';
    }
    return $option;
}

function create_ddl_category($data, $value_index = 0, $label_index = 1, $selected = 0, $category_id = 0) {
    $ci = &get_instance();
    $ci->load->model("categories_model", 'cat_model');
    $option = '';
    foreach ($data as $value) {
        if ($value[$value_index] == $category_id) {
            continue;
        }
        $is_selected = '';
        $category_name = $ci->cat_model->get_recursive_parent_names_string($value[$value_index]);
        if ($selected == $value[$value_index])
            $is_selected = 'selected="selected"';
        $option .= '<option value="' . $value[$value_index] . '" ' . $is_selected . '>' . $category_name . '</option>';
    }
    return $option;
}

/**
 * get locations in which product is present
 * 
 * @return array locations array
 * 
 */
function get_locations_having_products() {
    $ci = &get_instance();
    $ci->db->select('cities.*');
    $ci->db->from('cities');
    $ci->db->join('products', 'products.city_id = cities.city_id');
    $ci->db->distinct();
    $query = $ci->db->get();

    $locations = $query->result_array();
    return $locations;
}

/**
 * 
 * get retailers
 * 
 * @return array retailers
 * 
 */
function get_retailers() {
    $ci = &get_instance();
    $ci->load->model("retailer_model");
    $retailers = $ci->retailer_model->get_all_retailers();
    return $retailers;
}
function get_stores() {
    $ci = &get_instance();
    $stores = $ci->db->query("SELECT distinct store_name from products where store_name!=''")->result_array();
    return $stores;
}
function get_brands($cat_id=0) {
    $ci = &get_instance();
	$cat_ids_arr = mychildrecur($cat_id);
    if($cat_id)
	   $cat_ids_arr[] = $cat_id;
    $cat_ids = implode(",", $cat_ids_arr);
    if($cat_ids!='')
	   $brands = $ci->db->query("SELECT distinct brand from products where brand!='' AND cat_id IN(".$cat_ids.") order by  brand ASC")->result_array();
	 else  
	   $brands = $ci->db->query("SELECT distinct brand from products where brand!='' order by  brand ASC")->result_array();  
	return $brands;
}
function get_price_range_filters($cat_id=0,$brands='') {
    $ci = & get_instance();
	
	$cat_ids_arr = mychildrecur($cat_id);
    if($cat_id)
	   $cat_ids_arr[] = $cat_id;
    $cat_ids = implode(",", $cat_ids_arr);
	if($cat_ids!='')
	   $sql = "select max(sale_price) as max_price, min(sale_price) as min_price from products where cat_id IN(".$cat_ids.")";
	else
	 	$sql = "select max(sale_price) as max_price, min(sale_price) as min_price from products";	 
     $field_values = $ci->db->query($sql)->row_array();
     return $field_values;
}

function makeCurrency($price) {
    $price = round($price);	
	return number_format($price, 0, ',', ' ');
}/**
 * 
 * Query string helper to have querystring available in view
 * 
 * @return array Query String in the form or array
 * 
 */
function parse_querystring() {
    $ci = &get_instance();
    return $ci->input->get();
}

/**
 * 
 * Get Parent Categories from categories_model
 * 
 * @return array parent categories
 */
function get_parent_categories() {
    $ci = &get_instance();
    $ci->load->model("categories_model", 'cat_model');
    $categories = $ci->cat_model->get_parent_categories();
    return $categories;
}

function get_homepage_categories() {
    $ci = &get_instance();

    $res = $ci->db->select('*')
            ->from('categories')
            ->where('parent_id', 0)->where("show_on_homepage", 1)
            ->get()
            ->result_array();
    if (count($res) <= 0)
        return false;
    return $res;
}

/**
 * 
 * Get Child Categories from categories_model
 * 
 * @param int parent_id
 * @return array child categories
 */
function get_child_categories($parent_id) {
    $ci = &get_instance();
    $ci->load->model("categories_model", 'cat_model');
    $categories = $ci->cat_model->get_child_categories($parent_id);
    return $categories;
}

/**
 * 
 * Get popular_sales from products_model
 * 
 * @param int $limit
 * @return array popular products
 */
function get_popular_sales($limit = 25) {
    $ci = &get_instance();
    $ci->load->model("product_model", 'prd_model');
    $products = $ci->prd_model->get_popular_sales($limit);
    return $products;
}

function get_more_cat_pro($category_id, $pro_id) {
    $ci = &get_instance();
    $ci->load->model("product_model", 'prd_model');
    $products = $ci->prd_model->more_product_by_category($category_id, $pro_id, 20);
    return $products;
}
function get_more_retailer_pro($store_name,$pro_id) {
    $ci = &get_instance();
    $ci->load->model("product_model", 'prd_model');
    $products = $ci->prd_model->more_product_by_retailer($store_name,$pro_id, 20);
    return $products;
}
function get_pro_by_price($min, $max, $cat_id) {
    $ci = &get_instance();
    $ci->load->model("product_model", 'prd_model');
    $products = $ci->prd_model->product_by_price($min, $max, $cat_id, 20);
    return $products;
}

/**
 * 
 * Get popular_retailers from database
 * 
 * @param int $limit
 * @return array popular retailer users
 */
function get_popular_retailers($limit = 25) {
    $ci = &get_instance();
  /*  $retailers = $ci->db->query("SELECT users.*, user_profiles.*,users.id as uid,
                        sum(products.buy_link_hits) AS total_hits,
                        products.user_id
                        FROM
                        products
                        INNER JOIN users ON users.id = products.user_id
                        INNER JOIN user_profiles ON user_profiles.user_id = users.id
                        group by products.user_id order by total_hits desc limit $limit
                        ")->result_array();*/
    $retailers = array();
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/zalando.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/siba.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/ellos.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/heppo.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/brothers.png'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/footway.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/mq.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/brandos.gif'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/gant.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/h&m.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/nelly.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/furniturebox.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/stadium.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/netonnet.jpg'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/brandos.gif'),'link'=>"abc");
	$retailers[]=array('image'=>base_url('assets/uploads/images/retailer/gant.jpg'),'link'=>"abc");


	return $retailers;
}
function get_popular_brands($limit = 25) {
    $brands = array();
	$brands[]=array('image'=>base_url('assets/uploads/images/brands/nike.jpg'),'link'=>"abc");
	$brands[]=array('image'=>base_url('assets/uploads/images/brands/Tiger.jpg'),'link'=>"abc");
	$brands[]=array('image'=>base_url('assets/uploads/images/brands/Morris.jpg'),'link'=>"abc");
	$brands[]=array('image'=>base_url('assets/uploads/images/brands/Lacoste.jpg'),'link'=>"abc");
	$brands[]=array('image'=>base_url('assets/uploads/images/brands/Converse.jpg'),'link'=>"abc");
	$brands[]=array('image'=>base_url('assets/uploads/images/brands/adidas.jpg'),'link'=>"abc");
	$brands[]=array('image'=>base_url('assets/uploads/images/brands/Diesel.jpg'),'link'=>"abc");
	$brands[]=array('image'=>base_url('assets/uploads/images/brands/Gstar.jpg'),'link'=>"abc");
	$brands[]=array('image'=>base_url('assets/uploads/images/brands/riverisland.jpg'),'link'=>"abc");
	//$brands[]=array('image'=>base_url('assets/uploads/images/brands/cameo.jpg'),'link'=>"abc");
    $brands[]=array('image'=>base_url('assets/uploads/images/brands/birkenstock.jpg'),'link'=>"abc");
	$brands[]=array('image'=>base_url('assets/uploads/images/brands/bensherman.jpg'),'link'=>"abc");

	return $brands;
}
function get_brands_name() {
    $ci = &get_instance();

    $res = $ci->db->select('*')
            ->from('categories')
            ->where('parent_id', 0)->where("show_on_homepage", 1)
            ->get()
            ->result_array();
    if (count($res) <= 0)
        return false;
    return $res;
} 
/**
 * 
 * get home page banners array
 * 
 * @return array $banners
 */
function get_homepage_rotating_banners() {
    $ci = &get_instance();
    $ci->load->model("banner_model", 'banner');
    $banners = $ci->banner->get_banners_by_position("homepage_rotating");
    return $banners;
}

/**
 * 
 * get left random banners array
 * 
 * @return array $banners
 */
function get_left_random_banners($limit) {
    $ci = &get_instance();
    $ci->load->model("banner_model", 'banner');
    $banners = $ci->banner->get_random_banners_by_position("left_random", $limit);
    return $banners;
}

function get_homepage_right_banner() {
    $ci = &get_instance();
    $ci->load->model("banner_model", 'banner');
    $banners = $ci->banner->get_random_banners_by_position("homepage_right", 1);
    $banner = ($banners) ? array_shift($banners) : null;
    return $banner;
}

function get_sale_of_week_banner() {
    $ci = &get_instance();
    $ci->load->model("banner_model", 'banner');
    $banners = $ci->banner->get_random_banners_by_position("sale_of_week", 1);
    $banner = ($banners) ? array_shift($banners) : null;
    return $banner;
}

/**
 * get sale of the week record having most buy_hit_links
 * 
 * @return array $sale sale record  
 */
function get_sale_of_week() {
    $ci = &get_instance();
    $sale = $ci->db->query("select count(product_stats.product_id) as total_hits,products.*, pd.* from products 
                            inner join product_stats on product_id = products.id left join product_deal as pd on products.id = pd.product_id 
                            where referred_datetime between date_sub(now(),INTERVAL 1 WEEK) and now() 
                            order by total_hits desc limit 1;")
            ->row_array();
    return $sale;
}

function get_categroy_name($id) {
    $ci = &get_instance();
$category_name='';
    $category_info = $ci->db->query("SELECT * FROM `categories` WHERE `cat_id`=" . $id)->row_array();
    if ($category_info['parent_id'] == 0 OR $category_info['parent_id']==99999) {
        //if(count($category_info) && $category_info['parent_id']==0){
        $category_name = $category_info['cat_name'];
    } else {
        $parent_cat = $ci->db->query("SELECT cat_name FROM `categories` WHERE `cat_id`=" . $category_info['parent_id'])->row_array();
        $category_name = $parent_cat['cat_name'] . '>' . $category_info['cat_name'];
    }

    return $category_name;
}

function getcategroyname($id=0) {
    $ci = &get_instance();
    if($id)
	{
    	$category_info = $ci->db->query("SELECT * FROM `categories` WHERE `cat_id`=" . $id)->row_array();
    	$category_name = $category_info['cat_title'];
    	return $category_name;
	}
	else
	{
		return "";
	}
}

function get_product_oncategory($cat_id) {
    $ci = &get_instance();
    $ci->load->model('product_model');
    $products = $ci->product_model->product_by_category_left($cat_id);
    $product_html = '';
    if ($products) {
        $product_html .='<ul>';
        foreach ($products as $products) {
            $product_html .='<li><a href="' . base_url() . 'product/product_detail/' . $products['id'] . '">-' . $products['product_name'] . '</a></li>';
        }
        $product_html .='</ul>';
    } else {
        $product_html .= '<ul> </ul>';
    }
    return $product_html;
}

function wishlist_count() {
    $ci = &get_instance();
    $ci->load->model('wishlist_model');
    $products = $ci->wishlist_model->get_all_wishedproducts_count();
    if ($products) {
        $count = $products;
    } else {
        $count = 0;
    }
    return $count;
}

function other_choice_category() {
    $categories = get_parent_categories();
    $cat_html = '<ul class="treeMenu">';
    foreach ($categories as $category) {
        $child_cts = get_child_cat_dll($category['cat_id']);
        $class = ($child_cts != '') ? 'class="closed"' : '';
        $cat_html .= '<li>
							<span ' . $class . '></span>
							<a href="' . base_url("category?cat_id=" . $category['cat_id']) . '&per_page=">' . $category['cat_name'] . '</a>
							' . get_child_cat_dll($category['cat_id']) . '
							<br clear="all" />
						</li>';
    }
    $cat_html .= '</ul>';
    return $cat_html;
}

function get_child_cat_dll($cat_id) {
    $child_cat = get_child_categories($cat_id);
    $related_html = '';
    if ($child_cat) {
        $related_html = '<div style="display: none;">
							<ul>';

        foreach ($child_cat as $category) {
            $related_html .= '<li><span></span><a href="' . base_url("category?cat_id=" . $category['cat_id']) . '&per_page=">' . $category['cat_name'] . '</a><br clear="all" /></li>';
        }

        $related_html .= '</ul>
						</div>';
    }
    return $related_html;
}

function related_categories($cat_id) {
    $child_cat = get_child_categories($cat_id);
    $related_html = '<ul class="treeMenu">';
    if ($child_cat) {
        foreach ($child_cat as $category) {
            $related_html .= '<li><span></span><a href="' . base_url("category?cat_id=" . $category['cat_id']) . '&per_page=">' . $category['cat_name'] . '</a><br clear="all" /></li>';
        }
    }
    $related_html .= '</ul>';
    return $related_html;
}

function sales_not_missed() {
    $sales = get_popular_sales();
    $sales_html = '<ul>';
    if ($sales) {
        foreach ($sales as $sales) {
            $sales_html .= '<li><a href="' . base_url("category?cat_id=" . $sales['cat_id']) . '&per_page="> - ' . $sales['product_name'] . '</a></li>';
        }
    }
    $sales_html .= '</ul>';
    return $sales_html;
}

function static_pages_list() {
    $ci = &get_instance();
    $ci->load->model('content_model');
    $contents = $ci->content_model->get_all_content();
    $content_html = '';
    if ($contents) {
        foreach ($contents as $content) {
            $content_html .='<li><a href="' . base_url() . 'main/content/' . $content['c_id'] . '/' . str_replace(' ', '-', strtolower($content['c_name'])) . '"> > ' . $content['c_name'] . '</a></li>';
        }
    }
    return $content_html;
}
function popupData($id=5)
{
	 $ci = &get_instance();
	$ci->load->model('content_model');
	return $contents = $ci->content_model->get_content($id);				
}
function footer_pages_list() {
    $ci = &get_instance();
    $ci->load->model('content_model');
    $contents = $ci->content_model->get_all_content();
    $content_html = array();
    if ($contents) {
        foreach ($contents as $content) {
			if($content['c_name']=='Villkor')
           		 $content_html[] = '<li><a href="#" class="topopup">' . $content['c_name'] . '</a></li>';
			else
			$content_html[] = '<li><a href="' . base_url() . 'main/content/' . $content['c_id'] . '/' . str_replace(' ', '-', strtolower($content['c_name'])) . '">' . $content['c_name'] . '</a></li>';
        }
    }
    return implode('<li> | </li>', $content_html);
}

function get_subscription_levels($selected = 0) {
    $ci = &get_instance();
    $ci->load->model('tank_auth/users');
    $contents = $ci->users->get_subscription_levels();
    $option = '';
    foreach ($contents as $value) {
        $sel = ($value['id'] == $selected) ? 'selected="selected"' : '';
        $option .= '<option value="' . $value['id'] . '" data-cat_limit="' . $value['category_limit'] . '" ' . $sel . '>' . $value['description'] . '</option>';
    }
    return $option;
}

function get_child_cat_checklist() {
    $ci = &get_instance();
    $ci->load->model("categories_model", 'cat_model');
    $categories = $ci->cat_model->get_all_child_categories();
    $chklist = '';
    foreach ($categories as $value) {
        $parent_cat = $ci->db->query("SELECT cat_name FROM `categories` WHERE `cat_id`=" . $value['parent_id'])->row_array();
        $chklist .= '<input type="checkbox" class="selected_cat" value="' . $value['cat_id'] . '" name="subscribed_cat[]">
					<font face="Tahoma" style="font-size: 10px">' . $parent_cat['cat_name'] . ' - ' . $value['cat_name'] . '</font>';
    }
    return $chklist;
}

function get_parent_cat_checklist() {
    $ci = &get_instance();
    $ci->load->model("categories_model", 'cat_model');
    $categories = $ci->cat_model->get_parent_categories();
    $chklist = '';
    foreach ($categories as $value) {
        $chklist .= '<input type="checkbox" class="selected_cat" value="' . $value['cat_id'] . '" name="subscribed_cat[]">
					<font face="Tahoma" style="font-size: 10px">' . $value['cat_name'] . '</font>';
    }
    return $chklist;
}

function get_user_parent_cat_checklist($user_id) {
    $ci = &get_instance();
    $ci->load->model("categories_model", 'cat_model');
    $ci->load->model("tank_auth/users");
    $subscribed_cats = $ci->users->get_user_subscribed_cat($user_id);
    $categories = $ci->cat_model->get_parent_categories();
    $chklist = '';
    foreach ($categories as $value) {
        $checked = '';
        if (is_array($subscribed_cats) && count($subscribed_cats) > 0)
            $checked = (in_array($value['cat_id'], $subscribed_cats)) ? 'checked="checked"' : '';
        $chklist .= '<input type="checkbox" class="selected_cat" value="' . $value['cat_id'] . '" name="subscribed_cat[]" ' . $checked . '>
					<font face="Tahoma" style="font-size: 10px">' . $value['cat_name'] . '</font>';
    }
    return $chklist;
}

function get_user_parent_cat_name($user_id) {
    $ci = &get_instance();
    $ci->load->model("categories_model", 'cat_model');
    $ci->load->model("tank_auth/users");
    $subscribed_cats = $ci->users->get_user_subscribed_cat($user_id);
    $categories = $ci->cat_model->get_parent_categories();
    $chklist = array();
    foreach ($categories as $value) {
        if (is_array($subscribed_cats) && count($subscribed_cats) > 0){
            if (in_array($value['cat_id'], $subscribed_cats)) {
                $chklist[] = $value['cat_name'];
            }
        }
    }
    return implode(', ',$chklist);
}

function encode_link_safe_string($string) {
    $variables = array("," => " ", "\n" => " ", "\t" => " ", "\r	" => " ");
    foreach ($variables as $key => $value) {
        $string = str_replace($key, $value, $string);
    }
    return $string;
}

// this method is not being used anywhere
function get_category_info($cat_id) {
    $ci = &get_instance();
    $level = 0;
    while ($cat_id) {
        $cat_arr = $ci->db->query("select * from category where cat_id = $cat_id")->row_array();
        $level++;
    }
    if ($cat_arr['parent_id'] == 0) {
        $level = 1;
    }
}

/**
 * 
 * get category info and its parents info recursively using categories model method
 * 
 * @param int $cat_id
 * @return array Category and parent info
 */
function get_category_recursive_info($cat_id) {
    $ci = get_instance();
    $info = array();

    $ci->load->model("categories_model");
    $parents = $ci->categories_model->get_recursive_parents($cat_id);
    $info['level'] = count($parents);
    $info['subcats'] = $ci->categories_model->get_child_categories($cat_id);
    $info['category'] = isset($parents[0]) ? $parents[0] : null;
    $info['parents'] = $parents;

    return $info;
}

function get_subcats($parent_id) {
    $ci = &get_instance();
    $ci->load->model("categories_model");
    return $ci->categories_model->get_child_categories($parent_id);
}

function mychildrecur($id) {
    $ci = &get_instance();
    $arr = array();
    $cats = $ci->categories_model->get_child_categories($id);
    if ($cats)
        foreach ($cats as $key => $cat) {
            $arr[] = $cat['cat_id'];
            $cat_detail = $ci->categories_model->get_child_categories($cat['cat_id']);
            if ($cat_detail) {
                $arr = array_merge($arr, mychildrecur($cat['cat_id']));
            }
        }
    return $arr;
}

function mychildrecurdetail($id) {
    $ci = &get_instance();
    $arr = array();
    $cats = $ci->categories_model->get_child_categories($id);
    if (!$cats) {
        $arr[] = $ci->categories_model->get_category_detail($id);
    } else {
        foreach ($cats as $key => $cat) {
            $arr = array_merge($arr, mychildrecurdetail($cat['cat_id']));
        }
    }
    return $arr;
}

function get_filter_field_data($field, $cat_id) {
    $ci = &get_instance();
    $cat_ids_arr = mychildrecur($cat_id);
    $cat_ids_arr[] = $cat_id;
    $cat_ids = implode(",", $cat_ids_arr);
    $sql = "select distinct $field as value from products where cat_id in ($cat_ids)";
    $field_values = $ci->db->query($sql)->result_array();
    return $field_values;
}

function get_price_range($cat_id) {
    $ci = & get_instance();
    $cat_ids_arr = mychildrecur($cat_id);
    $cat_ids_arr[] = $cat_id;
    $cat_ids = implode(",", $cat_ids_arr);
    $sql = "select max(sale_price) as max_price, min(sale_price) as min_price from products where cat_id in ($cat_ids)";
    $field_values = $ci->db->query($sql)->row_array();
    return $field_values;
}

function get_catid_by_productid($product_id) {
    $ci = & get_instance();
    $sql = "select cat_id from products where id=$product_id";
    $row = $ci->db->query($sql)->row_array();
    return $row['cat_id'];
}

function check_ads_limit() {
    $ci = & get_instance();
    $ci->load->model('tank_auth/users');
    $user_subscription = array_shift($ci->users->get_user_subscription_with_detail_by_userid($ci->session->userdata('user_id')));
    if ($user_subscription['ads_limit'] > 0) {
        $ci->load->model('product_model');
        $user_pro = $ci->product_model->get_all_products_by_user($ci->session->userdata('user_id'));
        if (is_array($user_pro) && count($user_pro) >= $user_subscription['ads_limit'])
            return false;
    }
    return true;
}

function get_user_subscription_by_userid($userid) {
    $ci = &get_instance();
    $ci->load->model('tank_auth/users');
    $subscription_user = $ci->users->get_user_subscription_by_userid($userid);
    return $subscription_user;
}

// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.
function truncate($string, $limit = 25, $break = ".", $pad = "...", $polite = false) {
    // return with no change if string is shorter than $limit
    if (strlen($string) <= $limit)
        return $string;

    //is $break present between $limit and the end of the string?
    if ($polite &&
            (false !== ($breakpoint = strpos($string, $break, $limit))) &&
            ($breakpoint < strlen($string) - 1)) {
        $string = substr($string, 0, $breakpoint) . $pad;
    } else { // otherwise just force break the string
        $string = substr($string, 0, $limit) . $pad;
    }
    return $string;
}

/**
 * trims text to a space then adds ellipses if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @return string
 */
function trim_text($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);

    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }

    return $trimmed_text;
}

/**
 * Generate html with filters
 * $Query: sql query for required result
 * $Where: option where condition
 * $aColumns: column names to use in filteration
 * $filteraColumns: column names to fetch data from result set
 * $get_request should contain an array of anything to be passed in the url query paramters as GET
 * $action_link: action link for particular entry such as View, Delete, Approve etc
 */
function ajax_admin_html($Query, $Where = '', $group_by = '', $aColumns, $filteraColumns, $whereColumns, $get_request, $is_view, $view_link, $is_edit, $edit_link, $is_delete, $delete_link = '') {
    $ci = &get_instance();
    $sIndexColumn = "id";
    /*
     * Paging
     */
    $sLimit = "";
    if (isset($get_request['iDisplayStart']) && $get_request['iDisplayLength'] != '-1') {
        $sLimit = "LIMIT " . $get_request['iDisplayStart'] . "," . $get_request['iDisplayLength'];
    }
    /*
     * Ordering
     */

    $sOrder = "";
    if (isset($get_request['iSortCol_0'])) {
        $sOrder = "ORDER BY  ";
        for ($i = 0; $i < intval($get_request['iSortingCols']); $i++) {
            if ($get_request['bSortable_' . intval($get_request['iSortCol_' . $i])] == "true") {
                $sOrder .= "" . $aColumns[intval($get_request['iSortCol_' . $i])] . " " .
                        $get_request['sSortDir_' . $i] . ", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY") {
            $sOrder = "";
        }
    }
    /*
     * Filtering
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here, but concerned about efficiency
     * on very large tables, and MySQL's regex functionality is very limited
     */
    $sWhere = "";
    if (isset($get_request['sSearch']) && $get_request['sSearch'] != "") {
        $sWhere = "WHERE (";
        for ($i = 0; $i < count($whereColumns); $i++) {
            $sWhere .= $whereColumns[$i] . " LIKE '%" . $ci->db->escape_like_str($get_request['sSearch']) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    }
    /* Individual column filtering */
    for ($i = 0; $i < count($whereColumns); $i++) {
        if (isset($get_request['bSearchable_' . $i]) && $get_request['bSearchable_' . $i] == "true" && $get_request['sSearch_' . $i] != '') {
            if ($sWhere == "") {
                $sWhere = "WHERE ";
            } else {
                $sWhere .= " AND ";
            }
            $sWhere .= $whereColumns[$i] . " LIKE %" . $ci->db->escape_like_str($get_request['sSearch_' . $i]) . "% ";
        }
    }
    if ($Where != '') {
        if ($sWhere == "") {
            $sWhere = "WHERE  $Where ";
        } else {
            $sWhere .= " AND  $Where ";
        }
    }

    /*
     * SQL queries
     * Get data to display
     */
    $asQuery = "$Query
	$sWhere
	$group_by
	$sOrder
	$sLimit
	";
    //log_message('debug','pagination_helper asQuery: '.$asQuery);
    $rResult = $ci->db->query($asQuery);

    /* Data set length after filtering */
    $bsQuery = "$Query
	$sWhere
	$group_by
	$sOrder";
    //log_message('debug','pagination_helper bsQuery: '.$bsQuery);
    $rResultFilterTotal = $ci->db->query($bsQuery);
    $aResultFilterTotal = $rResultFilterTotal->num_rows();

    /*
     * Output
     */
    $output = array(
        "sEcho" => intval($get_request['sEcho']),
        "iTotalRecords" => $aResultFilterTotal,
        "iTotalDisplayRecords" => $aResultFilterTotal,
        "aaData" => array()
    );
    $aRows = $rResult->result_array();
    foreach ($aRows as $aRow) {

        $row = array();
        for ($i = 0; $i < count($filteraColumns); $i++) {
            if ($filteraColumns[$i] == "image") {
                //$image_path = other_asset_url($aRow['product_image'] . '_s.' . $aRow['product_ext'], '', 'uploads/images/products');
                $row[] = '<img src="' . $aRow['product_image'] . '" width="100" />';
            } elseif ($filteraColumns[$i] == "is_featured") {
                $row[] = ($aRow['is_featured'] == 1) ? 'Yes' : 'No';
            } elseif ($filteraColumns[$i] == "action") {
                /* Special output formatting for 'action' column */
                $action_link = '';
                if ($is_view == TRUE)
                    $action_link .= ' <a href="' . base_url() . 'admin/' . $view_link . '/' . $aRow['pro_id'] . '">View</a> ';
                if ($is_edit == TRUE)
                    $action_link .= ' <a href="' . base_url() . 'admin/' . $edit_link . '/' . $aRow['pro_id'] . '">Edit</a>';
                if ($is_delete == TRUE) {
                    if ($delete_link == '')
                        $action_link .= ' <a href="#" rel="' . $aRow['pro_id'] . '" class="deleteproduct">Delete</a>';
                    else
                        $action_link .= ' <a href="' . base_url() . 'admin/' . $delete_link . '/' . $aRow['pro_id'] . '">Delete</a>';
                }
                $row[] = $action_link;
            }
            elseif ($filteraColumns[$i] == "is_redeem") {
                $checked = ($aRow['redeem']) ? 'checked="checked"' : '';
                $row[] = '<input type="checkbox" name="is_redeem" class="is_redeem" rel="' . $aRow['user_reward_id'] . '" ' . $checked . ' />';
            } else {
                /* General output */
                $row[] = $aRow[$filteraColumns[$i]];
            }
        }
        $output['aaData'][] = $row;
    }
    return $output;
}


function get_category_filter_fields($cat_id){
    $ci = & get_instance();
    $ci->load->model("categories_model");
    $fields = $ci->categories_model->get_filters_fields($cat_id);
    return $fields;
}

function get_user_data(){
    $ci = &get_instance();
    return $ci->session->all_userdata();
}

function get_subscription_name($id){
    $ci = & get_instance();
    $ci->load->model("tank_auth/users");
    return $ci->users->get_subscription_name($id);
}

 function full_url()
{
   $ci=& get_instance();
   $return = $ci->config->site_url().$ci->uri->uri_string();
   if(count($_GET) > 0)
   {
      $get =  array();
      foreach($_GET as $key => $val)
      {
         $get[] = $key.'='.$val;
      }
      $return .= '?'.implode('&',$get);
   }
   return $return;
} 
if(!function_exists('createThumbs'))
{
	function createThumbs( $pathToImages, $pathToThumbs,$fname, $type,$thumbWidth=118)
	{
	
	$info = $pathToImages;
		
	// load image and get image size
	switch($type)
	{
		case "jpg":
		
		$img = imagecreatefromjpeg( "{$pathToImages}" );
		
		break;
		
		case "jpeg":
		
		$img = imagecreatefromjpeg( "{$pathToImages}" );
		
		break;
		
		case "gif":
		
		$img = imagecreatefromgif( "{$pathToImages}" );
		
		break;
		
		case "png":
		
		$img = imagecreatefrompng( "{$pathToImages}" );
		
		break;
	
	}
	
	$width = imagesx( $img );
	$height = imagesy( $img );
	
	// calculate thumbnail size
	$new_width = $thumbWidth;
	$new_height = floor( $height * ( $thumbWidth / $width ) );
	
	// create a new temporary image
	$tmp_img = imagecreatetruecolor( $new_width, $new_height );
	// copy and resize old image into new image
	imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
	
	// save thumbnail into a file
	imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
	
	}
}
 