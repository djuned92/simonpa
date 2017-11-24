<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pintu_air extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('curl');	
		$this->load->module('api/api_pintu_air');	
	}

	public function index()
	{
		$data['pintu_air'] = $this->global->get_group_by(
								'pintu_air',
								'gaugeNameId, measureDateTime, warningNameId',
								'gaugeNameId',
								array('id'=>'DESC'),
								12)->result_array();
		$this->template->set_layout('backend')
						->title('Home - Gentella')
						->build('v_pintu_air', $data);
	}

	// this url for save bad json
	public function bad_json()
	{
		$url	 			= 'http://bpbd.jakarta.go.id/cgi-bin/wlr';
		$data['data_json']	= $this->curl->simple_get($url);

		$this->load->view('save_json', $data);
	}

	public function save_json()
	{
		$myJSON = $this->input->post('myJSON');
		$decode = json_decode($myJSON);
		
		$gaugeId 			= [];
		$measureDateTime 	= [];
		$depth 				= [];
		$deviceId 			= [];
		$latitude 			= [];
		$longitude 			= [];
		$reportType 		= [];
		$level 				= [];
		$notificationFlag 	= [];
		$gaugeNameId 		= [];
		$gaugeNameEn 		= [];
		$gaugeNameJp 		= [];
		$warningLevel 		= [];
		$warningNameId 		= [];
		$warningNameEn 		= [];
		$warningNameJp 		= [];
		$comment 			= [];
		$time;

		foreach($decode->reports as $key => $value) {
			$gaugeId[] 			= $value->gaugeId;
			$measureDateTime[] 	= $value->measureDateTime;
			$depth[] 			= $value->depth;
			$deviceId[]			= $value->deviceId;
			$latitude[] 		= $value->latitude;
			$longitude[] 		= $value->longitude;
			$reportType[] 		= $value->reportType;
			$level[] 			= $value->level;
			$notificationFlag[] = $value->notificationFlag;
			$gaugeNameId[] 		= $value->gaugeNameId;
			$gaugeNameEn[] 		= $value->gaugeNameEn;
			$gaugeNameJp[] 		= $value->gaugeNameJp;
			$warningLevel[] 	= $value->warningLevel;
			$warningNameId[] 	= $value->warningNameId;
			$warningNameEn[] 	= $value->warningNameEn;
			$warningNameJp[] 	= $value->warningNameJp;
			$comment[] 			= $value->comment;
			$time 				= $value->measureDateTime; // for compare time
		}

		for ($i=0; $i < count($gaugeId); $i++) { 
			$data [] = [
				'gaugeId'			=> $gaugeId[$i],
				'measureDateTime'	=> $measureDateTime[$i],
				'depth'				=> $depth[$i],
				'deviceId'			=> $deviceId[$i],
				'latitude'			=> $latitude[$i],
				'longitude'			=> $longitude[$i],
				'reportType'		=> $reportType[$i],
				'level'				=> $level[$i],
				'notificationFlag'	=> $notificationFlag[$i],
				'gaugeNameId'		=> $gaugeNameId[$i],
				'gaugeNameEn'		=> $gaugeNameEn[$i],
				'gaugeNameJp'		=> $gaugeNameJp[$i],
				'warningLevel'		=> $warningLevel[$i],
				'warningNameId'		=> $warningNameId[$i],
				'warningNameEn'		=> $warningNameEn[$i],
				'warningNameJp'		=> $warningNameJp[$i],
				'comment'			=> $comment[$i],
			];
		}

		$pintu_air = $this->global->get_last_record('pintu_air', 1, array('measureDateTime'=>'DESC'))->row_array();
		$date 	= date_create($pintu_air['measureDateTime']);
		$day 	= date_format($date,'Y-m-d H:i:s');

		$date2 	= date_create($time);
		$day2 	= date_format($date2, 'Y-m-d H:i:s');
		
		if($day == $day2) {
			$result['message'] = 'Data sudah ada';
		} else {
			$this->global->add_batch('pintu_air', $data);
			$result['message'] = 'Data berhasil disimpan';
		}
		echo json_encode($result);
	}

}

/* End of file Pintu_air.php */
/* Location: ./application/modules/pintu_air/controllers/Pintu_air.php */