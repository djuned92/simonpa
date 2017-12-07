<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_pintu_air extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_by_id($id)
	{
		$pintu_air = $this->global->fetch(
					'pintu_air',
					'*',
					NULL,
					array('id' => $id));
		
		if($pintu_air->num_rows() > 0) {
			$result['code'] 	= 200;
			$result['error']	= FALSE;
			$result['message']	= 'Success';
			$result['pintu_air'] = $pintu_air->row_array();
		} else {
			$result['code'] 	= 404;
			$result['error']	= TRUE;
			$result['message']	= 'Not found pintu_air';
		}
		echo json_encode($result, JSON_NUMERIC_CHECK); 
	}

	public function json_chart()
	{
		$name = ['Pintu Air'];
		$x 	= $this->global->get_group_by(
				'pintu_air',
				'measureDateTime',
				'measureDateTime',
				array('id'=>'DESC'),
				3)->result_array();

		$measureDateTime = [];
		foreach($x as $key) {
			$date = date_create($key['measureDateTime']);
			$measureDateTime[] = date_format($date, 'Y-m-d H:i');
		}

		$y	= $this->global->get_group_by(
				'pintu_air',
				'gaugeNameId, measureDateTime',
				'gaugeNameId',
				array('id'=>'DESC'),
				12)->result_array();

		$z 	= $this->global->get_group_by(
				'pintu_air',
				'gaugeNameId, depth',
				NULL,
				array('id'=>'DESC'),
				36)->result_array();

		$value = [];
		

		foreach($y as $key => $val) {
			$value[$key][] = $val['gaugeNameId'];
			foreach($z as $r) {
				if($val['gaugeNameId'] == $r['gaugeNameId']) {
					$value[$key][] = $r['depth'];
					// array_merge($value)
				}
			}
		}

		$tanggal = [
			array_merge($name, $measureDateTime),
		];
		echo json_encode(array_merge($tanggal, $value), JSON_NUMERIC_CHECK);
		
	}

}

/* End of file Api_pintu_air.php */
/* Location: ./application/modules/api/controllers/Api_pintu_air.php */