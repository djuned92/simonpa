<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_users extends MX_Controller {

	public function get_all()
	{
		$users =  $this->global->fetch(
					'users as u', // table
					'u.id, u.username, u.device_token, p.fullname, p.address, p.gender, p.phone, p.created_at, p.updated_at', // select
					array('profiles as p'=>'u.id = p.user_id'), // Join
					NULL, // where
					array('p.id'=>'DESC'));		
		
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
		echo json_encode($result, JSON_NUMERIC_CHECK);	
	}

	public function get_by_id($id)
	{
		$user = $this->global->fetch(
					'users as u',
					'u.id, u.username, p.fullname, p.address, p.gender, p.phone, p.created_at, p.updated_at',
					array('profiles as p'=>'u.id = p.user_id'),
					array('u.id' => $id));
		
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
		echo json_encode($result, JSON_NUMERIC_CHECK); 
	}

	public function get_user_pintu_air($id)
	{
		$user_pintu_air = $this->global->fetch(
							'user_pintu_air as upa',
							'u.username, pa.gaugeId, pa.gaugeNameId, upa.*',
							array(
								'users as u' => 'upa.user_id = u.id',
								'pintu_air as pa' => 'upa.gaugeId = pa.gaugeId'),
							array('upa.user_id' => $id),
							array('pa.id'=>'ASC'),
							12);

		if($user_pintu_air->num_rows() > 0) {
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'Success';
			$result['user_pintu_air'] 	= $user_pintu_air->result_array();
		} else {
			$result['code'] 	= 202;
			$result['error']	= FALSE;
			$result['message']	= 'No content user pintu air';
		}
		echo json_encode($result, JSON_NUMERIC_CHECK);
	}

	public function get_status_user_pintu_air()
	{
		$user_id = implode(',', [20,21]);
		$measureDateTime = '2017-11-25 15:00:00';
		$query = "SELECT `u`.`username`, `pa`.`gaugeNameId`, `pa`.`measureDateTime`, `pa`.`warningNameId`,`upa`.`user_id`, `upa`.`gaugeId`
					FROM `user_pintu_air` as `upa`
					JOIN `users` as `u` ON `upa`.`user_id` = `u`.`id`
					JOIN `pintu_air` as `pa` ON `upa`.`gaugeId` = `pa`.`gaugeId`
					WHERE `upa`.`user_id` IN ($user_id)
					AND `upa`.`is_active` = 1
					AND `pa`.`measureDateTime` = '$measureDateTime'  
					ORDER BY `upa`.`user_id` ASC";
		$result = $this->db->query($query)->result_array();
		print_r($result);
	}

	public function add()
	{
		$this->db->trans_begin();
		
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
			'role'		=> 2,
			'created_at'=> date('Y-m-d H:i:s'),
		];

		$user_id = $this->global->add('users', $data, TRUE);

		$data_profile = [
			'user_id'	=> $user_id,
			'fullname'	=> $this->input->post('fullname'),
			'address'	=> $this->input->post('address'),
			'phone'		=> $this->input->post('phone'),
			'gender'	=> $this->input->post('gender'),
			'created_at'=> date('Y-m-d H:i:s'),
		];

		$this->global->add('profiles', $data_profile);
		
		$gaugeId = ['TMA00001','TMA00002','TMA00003','TMA00004','TMA00005','TMA00006',
					'TMA00007','TMA00008','TMA00009','TMA00010','TMA00011','TMA00012'];

		for ($i=0; $i < count($gaugeId); $i++) { 
			$data2[] = [
				'user_id'	=> $user_id,
				'gaugeId'	=> $gaugeId[$i]
			];
		}

		$this->global->add_batch('user_pintu_air', $data2);

		if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['code']		= 501;
			$result['error']	= FALSE;
			$result['message']	= 'Failed registered!';
        } else {
        	$this->db->trans_commit();
			$result['code']		= 200;
			$result['error']	= FALSE;
			$result['message']	= 'Success registered!';
        }

		echo json_encode($result);		
	}

	public function update()
	{
		$this->db->trans_begin();

		$id_user 	= $this->input->post('id');
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
		];

		$this->global->update('users', $data, array('id' => $id_user));
		
		$data_profile = [
			'fullname'	=> $this->input->post('fullname'),
			'address'	=> $this->input->post('address'),
			'phone'		=> $this->input->post('phone'),
			'gender'	=> $this->input->post('gender'),
			'created_at'=> date('Y-m-d H:i:s'),
		];

		$this->global->update('profiles', $data_profile, array('id' => $id_user));

		if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['code']		= 501;
			$result['error']	= FALSE;
			$result['message']	= 'User failed to updated!';
        } else {
        	$this->db->trans_commit();
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'User has been updated!';
        }

		echo json_encode($result);
	}

	public function delete()
	{
		$id = $this->input->post('id');

		if($id != NULL || $id != '') {
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'User has been deleted!';
			$this->global->delete('users', array('id' => $id));
			$this->global->delete('user_pintu_air', array('user_id' => $id));
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