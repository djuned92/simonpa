<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->module('api/api_users');
		$this->load->library('curl');
	}

	public function index()
	{
		$url 			= base_url() .'api/api_users/get_all';
		$json 			= $this->curl->simple_get($url);
		$users 			= json_decode($json, TRUE);
		$data['users'] 	= $users['users'];

		// print_r($data);die();
		$this->template->set_layout('backend')
						->title('Home - Gentella')
						->build('v_users', $data);
	}

}

/* End of file Users.php */
/* Location: ./application/modules/users/controllers/Users.php */