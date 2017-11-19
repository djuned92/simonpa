<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {

	public function check_login($username)
	{
		$query = $this->db->get_where('users', array('username'=>$username));
		
		if($query->num_rows() > 0)
            $result = $query->row_array();
        else
            $result = array();

        return $result;

	}
	
	public function add($table, $data, $last_id = FALSE) 
	{
		$this->db->insert($table, $data);
		if($last_id)
			return $this->db->insert_id();
	}

	public function update($table, $data, $id)
	{
		return $this->db->update($table, $data, $id);
	}

}

/* End of file M_auth.php */
/* Location: ./application/modules/auth/models/M_auth.php */