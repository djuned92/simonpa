<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('curl');
		$this->load->module('api/api_news');
	}

	public function index()
	{
		$url 			= base_url() .'api/api_news/get_all';
		$json 			= $this->curl->simple_get($url);
		$news 			= json_decode($json, TRUE);
		$data['news'] 	= $news['news'];

		// print_r($data);die();
		$this->template->set_layout('backend')
						->title('Home - Gentella')
						->build('v_news', $data);
	}

	public function add()
	{
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('content', 'Content', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			$this->api_news->add();
		} else {
			$this->template->set_layout('backend')
							->title('Home - Gentella')
							->build('f_news');	
		}
	}

	public function update()
	{
		$id 			= $this->uri->segment(3);
		$url 			= base_url() . 'api/api_news/get_by_id/' . $id;
		$news 			= json_decode($this->curl->simple_get($url), TRUE);
		$d['news'] 		= $news['news'];

		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('content', 'Content', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			$this->api_news->update();
		} else {
			$this->template->set_layout('backend')
							->title('Home - Gentella')
							->build('f_news', $d);	
		}	
		
	}

	public function delete()
	{
		$this->api_news->delete();
	}

	public function insert_dummy()
	{
		for ($i=1; $i <=10 ; $i++) { 
			$data[] = [
				'title'		=> 'ini title ' .$i,
				'content'	=> 'ini content ' .$i,
				'image'		=> 'ini image ' .$i,
				'created_at'=> date('Y-m-d H:i:s'),
				'created_by'=> 1,
			];
		}

		$this->global->add_batch('news', $data);

		echo 'Add news Success';
	}

}

/* End of file News.php */
/* Location: ./application/modules/news/controllers/News.php */