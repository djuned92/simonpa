<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_auth','auth');
		$this->load->library('form_validation');
	}

	public function index()
	{	
		$this->load->view('v_login');
	}

	public function add()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');	
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');	
		
		if ($this->form_validation->run() == FALSE) {
			$result['error'] 	= TRUE;
			$result['message'] 	= validation_errors();
		} else {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$options = [
			    'cost' => 11,
			    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
			];
			$password_hash = password_hash($password, PASSWORD_BCRYPT, $options);
			
			$data = [
				'username'	=> $username,
				'password'	=> $password_hash,
				'role'		=> 1
			];
			$this->auth->add('users',$data);
			$result['error']	= FALSE;
			$result['message']	= 'Registrasi berhasil';
		
			echo json_encode($result);
		}
	}
	
	public function do_login()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'password', 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			$result['error'] 	= TRUE;
			$result['message'] 	= validation_errors();
		} else {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$user = $this->auth->check_login($username);
			
			if(!empty($user)) {
				if(password_verify($password, $user['password'])) {
					// set session
					$sess_data = [
						'logged_in' => TRUE,
						'username'	=> $user['username'],
					];
					$this->session->set_userdata($sess_data);

					// update last login
					$data['last_login'] = date('Y-m-d H:i:s');
					(isset($user['id'])) ? $this->auth->update('users', $data, array('id'=> $user['id'])) : '';

					$result['error'] = FALSE;
				} else {
					$result['error'] 	= TRUE;
					$result['message'] 	= 'Password salah';
				}
			} else {
				$result['error'] 	= TRUE;
				$result['message']	= 'User tidak sesuai';
			}

			echo json_encode($result);
		}

	}

	public function do_logout()
	{
		$this->session->sess_destroy();
		redirect('auth');
	}
}

/* End of file Auth.php */
/* Location: ./application/modules/auth/controllers/Auth.php */