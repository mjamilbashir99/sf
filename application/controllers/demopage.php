<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Demopage extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		// $this->tank_auth->logout();
	}

	function index()
	{
		
		$this->load->view('demo/index-2015.html');
	}
        
   
}
