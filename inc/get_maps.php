<?php
/*************************************************************************
	Google Maps AJAX Interface v1.0
		This script grabs location data for streams from the database and
		returns it as JSON

	Written by Joe Clay

	Revision History
	v0.1b - 26 Jan 2011
		First working version

	v0.2b - 7 Feb 2011
		Combined files
		First working Wordpress AJAX version
		
	v1.0 - 8 Feb 2011
		First installed version
	  
*************************************************************************/


/*************************************************************************
	Variable initialization and required files
*************************************************************************/
	
	

/*************************************************************************
	Main Program
*************************************************************************/
	function get_maps()
	{
		global $wpdb;
		
		$map_type = '';

		if(isset($_POST['select']))
		{
			$map_type = 'select';
		}
		
		if(isset($_POST['display']))
		{
			$map_type = 'display';
		}
	
		switch($map_type) //Set up as switch case in case other functions are needed in the future
		{
			case 'select':
				$location = $wpdb->get_results('SELECT event_lat, event_lng FROM wp_vupango_settings',ARRAY_A);
				echo(json_encode($location));
				die();
			case 'display':
				$locations = $wpdb->get_results('SELECT camera_name, camera_lat, camera_lng FROM wp_vupango_cameras',ARRAY_A);
				echo(json_encode($locations));
				die();
		}
	}

	/*$locations = array();
	while($r = mysql_fetch_array($result))
	{
		$loc['id'] = $r['location_id'];
		$loc['name'] = $r['location_name'];
		$loc['lat'] = $r['location_lat'];
		$loc['lng'] = $r['location_lng'];
		$locations[] = $loc;
	}

	echo(json_encode($locations));*/



?>