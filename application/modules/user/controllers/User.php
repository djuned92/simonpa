<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!isset($this->session->logged_in)) {
			redirect('auth');
		}
		$this->load->library(array('table','datatables'));
	}

	public function index()
	{
		$this->template->set_layout('backend')
						->title('User - Gentella')
						->build('v_user');
	}

	public function dt_user()
	{
		// if(!$this->input->is_ajax_request()) show_404();
		$this->datatables->select('id, username, password', FALSE)
							->from('users');
		$this->datatables->unset_column('id');

		$edit 	= '<li><a href="'.base_url('user/edit/$1').'"><i class="fa fa-pencil"></i> Edit</a></li>';
		$delete = '<li><a href="#" class="btn-delete" data-id="$1"><i class="fa fa-trash"></i> Delete</a></li>';
		$divider= '<li class="divider"></li>';
		$this->datatables->add_column('action','<ul class="fa fa-bars"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></a><ul class="dropdown-menu dropdown-menu-right">' . $edit . $divider . $delete . '</ul></li></ul>' , 'encode(id)');
		
		echo $this->datatables->generate();
	}

}
/* End of file User.php */
/* Location: ./application/modules/user/controllers/User.php */