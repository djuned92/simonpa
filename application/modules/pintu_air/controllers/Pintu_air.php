<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pintu_air extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('curl','push'));	
		$this->load->module('api/api_users');
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
		$url 	= base_url() .'api/api_users/get_all';
		$decode_user = json_decode($this->curl->simple_get($url), TRUE);
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

		$id = [];
		foreach($decode_user['users'] as $key => $value) {
			if($value['device_token'] != NULL) {
				$id[] = $value['id'];
			}
		}

		// print_r($id);die();

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
		
		// if($day == $day2) {
		// 	$result['message'] = 'Data sudah ada';
		// } else {
			// $this->global->add_batch('pintu_air', $data);

			$measureDateTime = $day2;
	
			$result['message'] = 'Data berhasil disimpan';
			$user_pintu_air = [];
			for ($i=0; $i < count($id) ; $i++) { 
				$query = "SELECT `u`.`username`,`u`.`device_token`, `pa`.`id`, `pa`.`gaugeNameId`, `pa`.`latitude` as `lat`,
									`pa`.`longitude` as `lng`, `pa`.`measureDateTime`, `pa`.`warningNameId`,`upa`.`user_id`, 
									`upa`.`gaugeId`
						FROM `user_pintu_air` as `upa`
						JOIN `users` as `u` ON `upa`.`user_id` = `u`.`id`
						JOIN `pintu_air` as `pa` ON `upa`.`gaugeId` = `pa`.`gaugeId`
						WHERE `upa`.`user_id` = '$id[$i]'
						AND `upa`.`is_active` = 1
						AND `pa`.`measureDateTime` = '$measureDateTime'  
						ORDER BY `upa`.`user_id` ASC";
				$user_pintu_air[] = $this->db->query($query)->result_array();
				// print_r($this->db->last_query());die();
			}


			$device_token 	= [];
			$title 			= [];
			$body 			= [];
			for ($j=0; $j < count($id); $j++) { 
				foreach ($user_pintu_air[$j] as $key => $value) {
					$title[] = $value['gaugeNameId'];
					$device_token[] = $value['device_token'];
					$content['measureDateTime'] = $value['measureDateTime'];
					$content['warningNameId'] = $value['warningNameId'];
					$content[] = $value['lat'];
					$content[] = $value['lng'];
					$content[] = $value['id'];
					$body[] = $content;

				}
			}
			
			for ($k=0; $k < count($title) ; $k++) { 
				$this->push->setTitle( $title[$k] )
               		->setbody( $body[$k] )
		        	->fire( $device_token[$k] )[$k];
			}
		// }
		echo json_encode($result);
	}

	public function send_push_notif()
	{
		$device_token = [
			0 => 'eZ-LoIk0Xpw:APA91bENuXIAOTOeUHpdfvMq5dDuSFrcEASUuSnmI0PUcc2wMID0H9wz9NcO_y2zs3srotbykY1HFKxbC2-Ag7tmgzeLecjcQZQmkJi7BQdwhhvuFiDgCAEz7as0i56CCXDwBDFyod3X',
		];

		// print_r($device_token[1]);die();
    	for ($i=0; $i < count($device_token) ; $i++) { 
    		# code...
	    	$this->push->setTitle( 'title' )
	               		->setbody( 'body' )
			        	->fire( $device_token[$i] )[$i];
    	}
	}
}

/* End of file Pintu_air.php */
/* Location: ./application/modules/pintu_air/controllers/Pintu_air.php */