<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('curl');
	}

	public function index()
	{
		$url 			= base_url() .'api/api_news/get_all';
		$json 			= $this->curl->simple_get($url);
		$news 			= json_decode($json, TRUE);
		$data['news'] 	= $news['news'];

		$this->template->set_layout('backend')
						->title('Home - Gentella')
						->build('v_news', $data);
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