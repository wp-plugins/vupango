<?php
/*************************************************************************
	VuPango Database Entry Script v1.0
		General upload script for VuPango database
		
	Written by Joe Clay

	Revision History
	v0.1b - 5 Feb 2011
		First version

	v1.0 - 9 Feb 2011
		Finalized all settings
		Added sanitation for external input sources
		First installed version
*************************************************************************/


	function submit_data()
	{

/*************************************************************************
	Variable initialization and required files
*************************************************************************/

		global $wpdb;
		$vp_settings = $wpdb->prefix . 'vupango_settings';
		$vp_cameras = $wpdb->prefix . 'vupango_cameras';


/*************************************************************************
	Main Program
*************************************************************************/

		if(isset($_POST['event_settings']))
		{
			$start['month'] = $_POST['event_start_month'];
			$start['day'] = $_POST['event_start_day'];
			$start['year'] = $_POST['event_start_year'];
			$start['hour'] = $_POST['event_start_hour'];
			$start['min'] = $_POST['event_start_min'];
			$start['am'] = $_POST['event_start_am'];

			$end['month'] = $_POST['event_end_month'];
			$end['day'] = $_POST['event_end_day'];
			$end['year'] = $_POST['event_end_year'];
			$end['hour'] = $_POST['event_end_hour'];
			$end['min'] = $_POST['event_end_min'];
			$end['am'] = $_POST['event_end_am'];

			$data['event_name'] = $_POST['event_name'];
			$data['event_lat'] = $_POST['event_lat'];
			$data['event_lng'] = $_POST['event_lng'];
			$data['event_start'] = time_to_timestamp($start);
			$data['event_end'] = time_to_timestamp($end);
			$data['event_hashtag'] = $_POST['event_hashtag'];
			
			$where['event_id'] = '1';
			$wpdb->update($vp_settings,$data,$where);
			
			unset($where);
			$post_title['post_title'] = $data['event_name'];
			$where['ID'] = $wpdb->get_var("SELECT event_page_id FROM $vp_settings WHERE event_id = '1'");
			$wpdb->update($wpdb->posts,$post_title,$where);
		}
		
		if(isset($_POST['event_service_form']))
		{
			$data['event_service'] = $_POST['event_service'];
			$where['event_id'] = '1';
			
			$wpdb->update($vp_settings,$data,$where);
		}
		
		if(isset($_POST['camera_settings']))
		{
			$data['camera_name'] = filter_var($_POST['camera_name'],FILTER_SANITIZE_STRING);
			$data['camera_username'] = filter_var($_POST['camera_username'],FILTER_SANITIZE_STRING);
			
			$service = $wpdb->get_results("SELECT event_service FROM $vp_settings WHERE event_id = '1'");
			if($service[0]->event_service == 'ustream')
			{
				$data['camera_username'] = get_cid($_POST['camera_username']);
			}
			$data['camera_lat'] = $_POST['camera_lat'];
			$data['camera_lng'] = $_POST['camera_lng'];
			
			$wpdb->insert($vp_cameras,$data);
		}
		
		if(isset($_POST['delete']))
		{
			$camera_id = $_POST['camera_id'];

			$wpdb->query("DELETE FROM $vp_cameras WHERE camera_id = '$camera_id' LIMIT 1");
			
		}
		
		if(isset($_POST['vupango_settings']))
		{
			$data['event_signup'] = ($_POST['event_signup'] == 'on' ? '1' : '0');
			$data['event_destroy'] = ($_POST['event_destroy'] == 'on' ? '1' : '0');
			
			$where['event_id'] = '1';
			$wpdb->update($vp_settings,$data,$where);
		}
	}


/*************************************************************************
	Ustream Functions
*************************************************************************/

	function get_cid($embed_code)
	{
		$pattern = '/(cid=)([0-9]+)/';
		preg_match($pattern,$embed_code,$cid);
		return $cid[2];
	}

/*************************************************************************
	Debug Functions
*************************************************************************/	
	
	function pr($var)
	{
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}
?>