<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_news extends MX_Controller {

	public function get_all()
	{
		$news = $this->global->fetch(
								'mytable', // table
								'*' // select
								NULL, // Join
								array('is_published' => 1), // where
								array('id'=>'DESC'));	
		
		if($news->num_rows() > 0) {
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'Success';
			$result['news'] 	= $news->result_array();
		} else {
			$result['code'] 	= 204;
			$result['error']	= FALSE;
			$result['message']	= 'No content news';
		}
		echo json_encode($result);	
	}

	public function get_limit()
	{
		$news = $this->global->get_limit(
								'mytable', // table
								3, // limit 
								NULL, // start
								array('is_published' => 1)); // where	
		
		if($news->num_rows() > 0) {
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'Success';
			$result['news'] 	= $news->result_array();
		} else {
			$result['code'] 	= 204;
			$result['error']	= FALSE;
			$result['message']	= 'No content news';
		}
		echo json_encode($result); 
	}

	public function get_by_id()
	{
		$id = $this->input->post('id');

		$news = $this->global->fetch(
								'mytable',
								'*',
								NULL,
								array('id' => $id));
		
		if($news->num_rows() > 0) {
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'Success';
			$result['news'] 	= $news->row_array();
		} else {
			$result['code'] 	= 404;
			$result['error']	= TRUE;
			$result['message']	= 'Not found news';
		}
		echo json_encode($result); 
	}

	public function add()
	{
		$data = [
			'field'	=> $this->input->post('field'),
		];
		
		$this->global->add('mytable', $data);
	
		$result['code'] 	= 200;
		$result['error']	= FALSE;
		$result['message']	= 'News has been created!';
	
		echo json_encode($result);		
	}

	public function update()
	{
		$id 	= $this->input->post('id');

		$data = [
			'field'	=> $this->input->post('field'),
		];

		$this->global->update('mytable', $data, array('id' => $id));	
	
		$result['code'] 	= 200;
		$result['error']	= FALSE;
		$result['message']	= 'News has been updated!';
		
		echo json_encode($result);
	}

	public function delete()
	{
		$id = $this->input->post('id');

		if($id != NULL || $id != '') {
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'News has been deleted!';
			$this->global->delete('mytable', array('id' => $id));
		} else {
			$result['code'] 	= 404;
			$result['error']	= TRUE;
			$result['message']	= 'News fail to delete!';	
		}
		echo json_encode($result);
	}
}

/* End of file Api_news.php */
/* Location: ./application/modules/api/controllers/Api_news.php */