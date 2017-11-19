<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!isset($this->session->logged_in)) {
			redirect('auth');
		}
	}

	public function index()
	{
		$this->template->set_layout('backend')
						->title('Home - Gentella')
						->build('v_home');
	}

}

/* End of file Home.php */
/* Location: ./application/modules/welcome/controllers/Home.php */