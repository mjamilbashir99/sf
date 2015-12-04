<?php

class shopello 
{
/*** API endpoint 
* 
*access Private 
*/ 
private $_api_endpoint = 'https://api.shopello.se/1/'; 

/** 
* API Key 
* 
*access Private 
*/ 
private $_api_key='OHIjAmuZqyLMPK5urPj1RiXT5YcMMjbrRKAhXV7d'; 

/** 
* Load URL 
*/ 
public $last_url; 

/** 
* Constructor 
* 
*param String Optional. 
*return Void 
*/ 
public function __construct($api_key = null) {
if($api_key!==null) {
$this->set_api_key($api_key); 
} 
} 

/** 
* Set API endpoint 
* 
*param String 
*return Void 
*/ 
public function set_api_endpoint ($api_endpoint) {
$this ->_api_endpoint = $api_endpoint; 
} 

/** 
* Get API endpoint 
* 
*return String 
*/ 
public function get_api_endpoint() {
return $this ->_api_endpoint; 
} 

/** 
* Set Key API 
* 
*param String 
*return Void 
*/ 
public function set_api_key ($api_key) {
$this ->_api_key = $api_key; 
} 

/** 
* Get API Key 
* 
*return String 
*/ 
public function get_api_key () {
return $this -> _api_key; 
} 

/** 
* Call 
* 
*param String 
*param Array Optional. 
*param Bool Optional. 
*return Array 
*/ 
public function call ($method, $params = array (), $post = false) {
// Assemble the URL 
$url = $this-> get_api_endpoint (). $method. '.json'; 

// Add params 
if ($post && count ($params)> 0) {
foreach ($params as $key => $option) {
if (empty ($choices)) {
unset ($params [$key]); 
} 
} 

$url.= '?'.http_build_query($params); 
} 

// Log The load URL 
$this-> last_url = $url; 

// Initialize CURL 
$curl = curl_init (); 
        
         // Set the cURL parameters 
curl_setopt ($curl, CURLOPT_URL, $url); 
curl_setopt ($curl, CURLOPT_HEADER, false); 
curl_setopt ($curl, CURLOPT_NOBODY, false); 
curl_setopt ($curl, CURLOPT_ENCODING, 'gzip'); 
curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true); 
curl_setopt ($curl, CURLOPT_HTTPHEADER, array (
'X-API-KEY'. $this-> get_api_key () 
)); 

// Post 
if ($item) {
curl_setopt ($curl, CURLOPT_POST, true); 
curl_setopt ($curl, CURLOPT_POSTFIELDS, http_build_query ($params)); 
} 

// Execute 
$result = curl_exec ($curl); 
$error = curl_error ($curl); 

// Return Error 
if (empty ($error)) {
     return $error. '(HTTP CODE'. Curl_getinfo ($curl, CURLINFO_HTTP_CODE). ')'; 
} 

// Decode 
$data = json_decode($result); 

// Error? Exception! 
if(isset($data) > error) 
{
throw new Exception($data> error); 
} 

// Return Data 
return $data; 
} 

/** 
* Products 
* 
*param Array | Integer Optional. 
*param Array Optional. 
*return Array //$PRODUCT_ID = null,
*/ 
public function products ( $params = array ()) {
$method = 'products'; 

if (is_array ($PRODUCT_ID)) {
$params = $PRODUCT_ID; 
} 
else {
$method.= '/'. $PRODUCT_ID; 
} 

return $this-> call ($method, $params); 
} 

/** 
* Related Products 
* 
*param Integer 
*return Array 
*/ 
public function related_products ($PRODUCT_ID) {
$method = 'related_products /'. $PRODUCT_ID; 

return $this-> call ($method, array ()); 
} 

/** 
* Attributes 
* 
*param Array | Integer Optional. 
*param Array Optional. 
*return Array 
*/ 
public function attributes ($attribute = null, $params = array ()) {
$method = 'attributes'; 

if (is_array ($attribute)) {
$params = $attribute; 
} 
else {
$method.= '/'. $attribute; 
} 

return $this-> call ($method, $params); 
} 

/** 
* Stores 
* 
*param Array 
*return Array 
*/ 
public function stores ($params = array ()) {
return $this-> call ('Great', $params); 
} 

/** 
* Categories 
* 
*param Array Optional. 
*return Array 
*/ 
public function categories ($params = array ()) {
return $this-> call ('categories', $params); 
} 

/** 
* Categories 
* 
*param Array Optional. 
*return Array 
*/ 
public function category_parents ($params = array ()) {
return $this-> call ('category_parents', $params); 
} 

/** 
* Customers 
* 
*param Array Optional. 
*return Array 
*/ 
public function Highest ($params = array ()) {
return $this-> call ('friend', $params); 
} 

/** 
* Keywords 
*param String 
*return Array 
*/ 
public function keywords ($keyword) {
return $this-> call ('keywords /'. $keyword, array ()); 
} 

/** 
* Batch 
* 
*param Array 
*return Array 
*/ 
public function batch ($batch = array ()) {
return $this-> call ('batch', array (
'batch' => $batch 
), True); 
} 
} 

$products =array 
(
     'limit' => 24, 
     //'query' => ,
     //'offset' => 0, 
     //'price_min' => 0, 
     //'price_max' => 1000000 , 
     //'extra' => 'MAX_PRICE, stores, categories, brands', 
     //'disseminate' => 1, 
     //'category_id' => 52 
);  
$obj = new shopello();
$data = $obj->products($products);
 
var_dump($data);

?>