<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_news extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('upload'));
		$this->load->helper('file');		
	}
	public function get_all()
	{
		$news = $this->global->fetch(
					'news', // table
					'*', // select
					NULL, // Join
					NULL, // where
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

	public function get_news_published()
	{
		$news = $this->global->fetch(
					'news', // table
					'*', // select
					NULL, // Join
					array('is_published'=>1)); // where	
		
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

	public function get_by_id($id)
	{
		$news = $this->global->fetch(
					'news',
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
		$config['upload_path'] 		= './assets/images/news/';
		$config['allowed_types'] 	= 'gif|jpg|png';
		$config['max_size']  		= 2048;
		$config['max_width']  		= 1024;
		$config['max_height']  		= 768;
		$config['encrypt_name'] 	= TRUE;

		$this->upload->initialize($config);
		// print_r($this->upload->data());die();
		
		if ( ! $this->upload->do_upload()){
			$error = array('error' => $this->upload->display_errors());
			$result['error'] = $error;
		} else {
			$data = [
				'title'			=> $this->input->post('title'),
				'content'		=> $this->input->post('content'),
				'image'			=> $_FILES['userfile']['name'],
				'encrypt_image'	=> $this->upload->data('file_name'),
				'created_by'	=> 20,
				'created_at'	=> date('Y-m-d H:i:s'),
			];
			
			$this->global->add('news', $data);
			
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'News has been created!';
		}
	
	
		echo json_encode($result);		
	}

	public function update()
	{
		if($_FILES['userfile']['name'] != NULL) {
			$config['upload_path'] 	= './assets/images/news/';
			$config['allowed_types'] 	= 'gif|jpg|png';
			$config['max_size']  		= 2048;
			$config['max_width']  		= 1024;
			$config['max_height']  		= 768;
			$config['encrypt_name'] 	= TRUE;
			
			$this->upload->initialize($config);
			
			if ( ! $this->upload->do_upload()){
				$error = array('error' => $this->upload->display_errors());
				$result['error'] = $error;
			} else {
				$id 		= $this->input->post('id');
				$news 		= $this->global->fetch(
								'news',
								'*',
								NULL,
								array('id' => $id))
								->row_array();

				$path_image = './assets/images/news/' .$news['encrypt_image'];
				unlink($path_image);
				
				$data = [
					'title'			=> $this->input->post('title'),
					'content'		=> $this->input->post('content'),
					'image'			=> $_FILES['userfile']['name'],
					'is_published'	=> $this->input->post('is_published'),
					'encrypt_image'	=> $this->upload->data('file_name'),
				];

				$this->global->update('news', $data, array('id' => $id));
			}	
		} else {
			$id 	= $this->input->post('id');
			$data = [
				'title'			=> $this->input->post('title'),
				'content'		=> $this->input->post('content'),
				'is_published'	=> $this->input->post('is_published'),
			];

			$this->global->update('news', $data, array('id' => $id));
		}

		$result['code'] 	= 200;
		$result['error']	= FALSE;
		$result['message']	= 'News has been updated!';
		
		echo json_encode($result);
	}

	public function update_is_published() 
	{
		$id 	= $this->input->post('id');
		$data = [
			'is_published'	=> $this->input->post('is_published'),
		];

		$this->global->update('news', $data, array('id' => $id));

		$result['code'] 	= 200;
		$result['error']	= FALSE;
		$result['message']	= 'News has been updated!';

		echo json_encode($result);
	}

	public function delete()
	{
		$id 		= $this->input->post('id');
		$news 		= $this->global->fetch(
						'news',
						'*',
						NULL,
						array('id' => $id))
						->row_array();

		$path_image = './assets/images/news/' .$news['encrypt_image'];
		unlink($path_image);	
		
		if($id != NULL || $id != '') {
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'News has been deleted!';
			$this->global->delete('news', array('id' => $id));
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