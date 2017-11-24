<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_users extends MX_Controller {

	public function get_all()
	{
		$users = $this->global->fetch('users'); // table	
		
		if($users->num_rows() > 0) {
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'Success';
			$result['users'] 	= $users->result_array();
		} else {
			$result['code'] 	= 204;
			$result['error']	= FALSE;
			$result['message']	= 'No content users';
		}
		echo json_encode($result);	
	}

	public function get_by_id()
	{
		$id = $this->input->post('id');

		$user = $this->global->fetch(
								'users',
								'*',
								NULL,
								array('id' => $id));
		
		if($user->num_rows() > 0) {
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'Success';
			$result['user'] 	= $user->row_array();
		} else {
			$result['code'] 	= 404;
			$result['error']	= TRUE;
			$result['message']	= 'Not found user';
		}
		echo json_encode($result); 
	}

	public function add()
	{
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

		$this->global->add('users',$data);
		
		$result['code']		= 200;
		$result['error']	= FALSE;
		$result['message']	= 'Success registered!';
		
		echo json_encode($result);		
	}

	public function update()
	{
		$id 		= $this->input->post('id');
		$username 	= $this->input->post('username');
		$password 	= $this->input->post('password');
		
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

		$this->global->update('users', $data, array('id' => $id));
			
		$result['code'] 	= 200;
		$result['error']	= FALSE;
		$result['message']	= 'User has been updated!';
	
		echo json_encode($result);
	}

	public function delete()
	{
		$id = $this->input->post('id');

		if($id != NULL || $id != '') {
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'user has been deleted!';
			$this->global->delete('users', array('id' => $id));
		} else {
			$result['code'] 	= 404;
			$result['error']	= TRUE;
			$result['message']	= 'User fail to delete!';	
		}
		echo json_encode($result);
	}
}

/* End of file Api_user.php */
/* Location: ./application/modules/api/controllers/Api_user.php */