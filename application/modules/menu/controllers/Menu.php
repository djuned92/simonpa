<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MX_Controller {

	public function index()
	{
		$this->template->set_layout('backend')
					->title('Menu - Gentella')
					->build('v_menu');
	}

}

/* End of file Menu.php */
/* Location: ./application/modules/menu/controllers/Menu.php */