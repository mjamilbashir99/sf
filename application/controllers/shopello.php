<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class shopello extends CI_Controller {

    var $country;
    var $user_id;

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        ini_set('memory_limit', '1824M');
        $this->user_id = ($this->session->userdata('user_id')) ? $this->session->userdata('user_id') : 0;
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $this->load->helper('api');
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
    }

    public function index() {
		echo "stopped";
		}

// End of Index Function

    public function copyProductsbyCatId() {
        $start = time();
        $j = 0;
        $i = 0;
        try {

            $this->delete_product();
            $myCats = $this->showAllCatsFromDB();
            for ($p = 0; $p <= 21; $p++) {
                if ($p == 0)
				{
                    $parameters["price_min"] = 1;
                    $parameters["price_max"] = 100;
                }
				 elseif ($p == 1) {
                    $parameters["price_min"] = 101;
                    $parameters["price_max"] = 200;
                }
				 elseif ($p == 2) {
                    $parameters["price_min"] = 201;
                    $parameters["price_max"] = 300;
                } elseif ($p == 3) {
                    $parameters["price_min"] = 301;
                    $parameters["price_max"] = 500;
                } elseif ($p == 4) {
                    $parameters["price_min"] = 501;
                    $parameters["price_max"] = 650;
                } elseif ($p == 5) {
                    $parameters["price_min"] = 651;
                    $parameters["price_max"] = 800;
                } elseif ($p == 6) {
                    $parameters["price_min"] = 801;
                    $parameters["price_max"] = 900;
                } elseif ($p == 7) {
                    $parameters["price_min"] = 901;
                    $parameters["price_max"] = 1000;
                } elseif ($p == 8) {
                    $parameters["price_min"] = 1001;
                    $parameters["price_max"] = 1250;
                } elseif ($p == 9) {
                    $parameters["price_min"] = 1251;
                    $parameters["price_max"] = 1500;
                } elseif ($p == 10) {
                    $parameters["price_min"] = 1501;
                    $parameters["price_max"] = 1800;
                } elseif ($p == 11) {
                    $parameters["price_min"] = 1801;
                    $parameters["price_max"] = 2000;
                } elseif ($p == 12) {
                    $parameters["price_min"] = 2001;
                    $parameters["price_max"] = 2500;
                } elseif ($p == 13) {
                    $parameters["price_min"] = 2501;
                    $parameters["price_max"] = 3000;
                } elseif ($p == 14) {
                    $parameters["price_min"] = 3001;
                    $parameters["price_max"] = 3500;
                } elseif ($p == 15) {
                    $parameters["price_min"] = 3501;
                    $parameters["price_max"] = 4000;
                } elseif ($p == 16) {
                    $parameters["price_min"] = 4001;
                    $parameters["price_max"] = 4500;
                } elseif ($p == 17) {
                    $parameters["price_min"] = 4501;
                    $parameters["price_max"] = 5000;
                } elseif ($p == 18) {
                    $parameters["price_min"] = 5001;
                    $parameters["price_max"] = 6000;
                } elseif ($p == 19) {
                    $parameters["price_min"] = 6001;
                    $parameters["price_max"] = 8000;
                } elseif ($p == 20) {
                    $parameters["price_min"] = 8001;
                    $parameters["price_max"] = 10000;
                } elseif ($p == 21) {
                    $parameters["price_min"] = 10001;
                    $parameters["price_max"] = 200000;
                }
                foreach ($myCats as $mycat) {  // get all cat from db and loop
                    $per_page = 1000;
                    $parameters['category_id'] = $mycat['ap_cat_id'];
                    $parameters['offset'] = 0;
                    $parameters['limit'] = $per_page;
					//$this->debug($parameters);
                    $result = getByCatId('products', $parameters, 0, $per_page, $mycat['ap_cat_id']); // pass loop cat id 0,
                    $status = $result->status;
                   	//$this->debug($result->status);
					//$this->debug($result->total_found);
					$data = $result->data;
                    $addData = array();
                    $addData['user_id'] = $this->user_id;
                    $addData['from_api'] = 1;
                    $i = 0;
					//$data = array();
                    if($result->status)
					{
					    //echo $result->total_found;
					    foreach ($data as $my_product) {
                        $product_id = $my_product->product_id;
						  $i++;
						  $addData['store_name'] = $my_product->store->name;
                          $product_price_reduction_percent = $my_product->price->reduction_percent;
                          if ($product_price_reduction_percent > 0 AND $addData['store_name']!='Lightinthebox')
						   {
                            $category_id = $my_product->categories[0]->category_id;
                            $category_name = trim($my_product->categories[0]->name);
                            $src = '';
                            if (isset($my_product->images->{'440'}))
                                $src = $my_product->images->{'440'};
                            $addData['cat_id'] = $this->getCatagoryId($category_name);
                            if ($src != '' && $addData['cat_id'])
							 { //if($addData['cat_id'])
                                $addData['api_product_id'] = $product_id;
                                $addData['product_name'] = $my_product->name;
                                $addData['product_description'] = $my_product->description;
                                $addData['product_quantity'] = 10;
                                $addData['product_buy_link'] = $my_product->url;
                                $addData['product_price'] = round($my_product->price->regular_price,2);
                                $addData['api_reduction_percent'] = $product_price_reduction_percent;
                                $addData['sale_price'] = round($my_product->price->price,2);
                               
                                $addData['store_url'] = $my_product->store->url;
                                if (is_object($my_product->attributes))
								{
                                    $addData['brand'] = $my_product->attributes->brand;
                                }
                                $addData['product_image'] = $src;

                                if ($this->checkProduct($product_id))
								    $this->addProduct($addData);
								else
								    $this->updateProduct($addData);
                                $j++;
                            }
                        } //end of IF
                    
                        
                    }// end of foreach loop
					}
                } // for loop
            }// price loop
        } catch (Exception $e) {
            echo $e; // Failed/Error
        }
        $body = '';

        $end = time();
        $total = $end - $start;
        $body .= 'Start time = ' . $start . '<br>';
        $body .= 'End time = ' . $end . '<br>';
        $body .= 'Total time = ' . $total . '<br>';
        $body .= 'Total item to be inserted = ' . $j . '<br>';
        $this->email->to('site.testing.1234@gmail.com', 'comgcu8@gmail.com');
        $this->email->bcc('site.testing.1234@gmail.com');
        $this->email->subject('salefinder Entry ');
        $this->email->message($body);
        $this->email->from('m.jamil@themindguage.com', 'Admin');
        $this->email->send();
        $this->output->enable_profiler(TRUE);
    }

    public function addProduct($addData) {
        $addData['product_date'] = date('Y-m-d H:i:s', time());
        $res = $this->db->insert('products', $addData);
        $lastid = $this->db->insert_id();
       
	    $sale_deal = array('product_id' => $lastid ,
            'sale_type_id' => 5,
            'sale_value' => $addData['sale_price'],
            'sale_start_date' => date('Y-m-d'),
            'sale_end_date' => '0000-00-00',
            'from_api' => 1);
        $this->db->insert('product_deal', $sale_deal);

    }

    public function getCatagoryId($api_cat_name) {
        $where = array('name' => $api_cat_name);
        $query = $this->db->get_where('api_categories', $where, 1);
        $record['categories'] = $query->row();
        if (!empty($record['categories'])) {
            $cat_id = $record['categories']->sale_cat_id;
        } else {
            $cat_id = 0;
        }
        return $cat_id;
    }

    public function checkProduct($api_product_id) {
        $where = array('api_product_id' => $api_product_id);
        $query = $this->db->get_where('products', $where);
        $num = $query->num_rows();
        if ($num) // found don't add
		 {
           return false;
         }
		 else
		{
           return true;
        }
        
    }

    public function mapCatagoryId() {

        $query = $this->db->get('categories');
        $records = $query->result_array();
        foreach ($records as $record) {
            $api_cat_name = $record['api_cat_name'];
            if ($api_cat_name != '') {
                $api_cat_name_array = explode(",", $api_cat_name);
                foreach ($api_cat_name_array as $api_cat) {
                    $cat['name'] = trim($api_cat);
                    if ($cat['name'] != '') {

                        $cat['sale_cat_id'] = $record['cat_id'];
                        $this->db->insert('api_catagories', $cat);
                    }
                }
            }
        }
    }

    public function delete_product() {
        $fromApi = 1;
        $this->db->delete('product_deal', array('from_api' => $fromApi));
        $this->db->delete('product_review', array('from_api' => $fromApi));
        $this->db->delete('products', array('from_api' => $fromApi));
    }

    function updateProduct($pData) {
		$this->db->where('api_product_id', $pData['api_product_id']);
        $this->db->update('products',$pData);
		//var_dump($pData);
		//exit;
    }

    public function showAllCats() {
        $parameters = array();
        $result = shopello('categories', $parameters);
        //var_dump($result);
        $data = $result->data;
        $i = 1;
        echo "<table border=1>";
        echo "<tr>";
        echo "<td>Sr</td>";
        echo "<td><strong>category_id</strong></td>";
        echo "<td><strong>name</strong></td>";
        echo "<td><strong>key</strong></td>";
        echo "</tr>";
        foreach ($data as $my_cats) {
            echo "<tr>";
            echo "<td>" . $i++ . "</td>";
            echo "<td>$my_cats->category_id</td>";
            echo "<td> $my_cats->name</td>";
            echo "<td> $my_cats->key</td>";
            echo "</tr>";
            //$sql = "UPDATE api_categories SET ap_cat_id=".$my_cats->category_id." WHERE name='".$my_cats->name."'";
            // $res  = $this->db->query($sql);
        }
        echo "</table>";

        $this->output->enable_profiler(TRUE);
    }

    public function showAllCatsFromDB() {
        $sql = "SELECT * FROM api_categories  WHERE ap_cat_id>0 ";
        $res = $this->db->query($sql);
        return $records = $res->result_array();
    }

    public function showAllProducts() {
        $start = time();
        $j = 0;
        try {
            $parameters = array("min_price" => 7400, "max_price" => 8000);
            $result = shopello('products', $parameters);
            //print_r($result);
            //die();
            $total_found = $result->total_found;
            $status = $result->status;
            $data = $result->data;
            $per_page = 25;
            $total_pages = ceil($total_found / $per_page);
            echo "total found products" . $total_found;
            for ($i = 0; $i < $total_pages; $i++) {
                //	if($i==10)
                //	break;	
                if ($i == 0) {
                    $start = 0;
                } else {
                    $start = ($i * $per_page) + 1;
                }
                $result = shopello('products', $parameters, $start, $per_page);
                $status = $result->status;
                $data = $result->data;
                $addData = array();
                $addData['user_id'] = $this->user_id;
                $addData['from_api'] = 1;
                echo "<body>";
                foreach ($data as $my_product) {
                    $product_price_reduction_percent = $my_product->price->reduction_percent;
                    if ($product_price_reduction_percent > 0) {
                        $product_id = $my_product->product_id;
                        echo "<br>product No->" . ( ++$j) . " ----- product id->" . $product_id . "<br>";
                        $this->debug($my_product->price);
                        echo "<br>";
                    } //end of IF
                }// end of foreach loop
            } // for loop
        } catch (Exception $e) {
            echo $e; // Failed/Error
        }
    }

// End of Index Function

    function debug($res) {
        echo "<pre>";
        print_r($res);
        echo "</pre>";
    }

}
