<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_auth extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_auth','auth');
	}

	public function login()
	{
		$username 	= $this->input->post('username');
		$password 	= $this->input->post('password');
		$user 		= $this->auth->check_login($username);
		
		if(!empty($user)) {
			if(password_verify($password, $user['password'])) {
				// set session
				$sess_data = [
					'logged_in' => TRUE,
					'id'		=> $user['id'],
					'username'	=> $user['username'],
				];
				$this->session->set_userdata($sess_data);

				// update last login
				$data['last_login'] = date('Y-m-d H:i:s');
				(isset($user['id'])) ? $this->global->update('users', $data, array('id'=> $user['id'])) : '';

				$result['code']  	= 200;
				$result['error'] 	= FALSE;
				$result['user']  	= $sess_data;
			} else {
				$result['code'] 	= 400;
				$result['error'] 	= TRUE;
				$result['message'] 	= 'Wrong password';
			}
		} else {
			$result['code'] 	= 404;
			$result['error'] 	= TRUE;
			$result['message']	= 'User not found';
		}

		echo json_encode($result);
	}
}

/* End of file Api_auth.php */
/* Location: ./application/modules/api/controllers/Api_auth.php */