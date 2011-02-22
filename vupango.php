<?php
/*************************************************************************
	Plugin Name: VuPango
	Plugin URI: http://tangointervention.org/
	Description: VuPango allows for the setup of remote art installations involving multiple cameras
	Version: 1.0.2
	Author: Joe Clay and Robert Lawrence
	Author URI: http://yellowdogparty.com/
	License: GPL2

	Copyright 2011 Joe Clay and Robert Lawrence

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*************************************************************************/


/*************************************************************************
	Plugin Activation / Deactivation
*************************************************************************/

	$vp_cameras = $wpdb->prefix . 'vupango_cameras';
	$vp_settings = $wpdb->prefix . 'vupango_settings';

	register_activation_hook(__FILE__,'install_vupango');
	register_deactivation_hook(__FILE__,'remove_vupango');

	function install_vupango()
	{
		global $wpdb, $user_ID;
		
		$vp_cameras = $wpdb->prefix . 'vupango_cameras';
		$vp_settings = $wpdb->prefix . 'vupango_settings';
		
		$wpdb->show_errors();
		
		//CREATE the vupango_settings table
		$query = "CREATE TABLE IF NOT EXISTS `" . $vp_settings . "` (
					`event_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
					`event_name` varchar(50) NOT NULL,
					`event_lat` decimal(10,6) NOT NULL,
					`event_lng` decimal(10,6) NOT NULL,
					`event_start` datetime NOT NULL,
					`event_end` datetime NOT NULL,
					`event_hashtag` varchar(30) NOT NULL,
					`event_service` enum('qik','bambuser','ustream') NOT NULL,
					`event_signup` tinyint(1) NOT NULL,
					`event_page_id` int(10) unsigned NOT NULL,
					`event_destroy` tinyint(1) NOT NULL,
					PRIMARY KEY (`event_id`)
					);";
		
		$wpdb->query($query);


		//CREATE vupango_cameras table
		$query = "CREATE TABLE IF NOT EXISTS `" . $vp_cameras . "` (
					`camera_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
					`camera_name` varchar(30) NOT NULL,
					`camera_lat` decimal(10,6) NOT NULL,
					`camera_lng` decimal(10,6) NOT NULL,
					`camera_username` varchar(25) NOT NULL,
					`camera_autolocate` tinyint(1) NOT NULL,
					PRIMARY KEY (`camera_id`)
					);";
		
		$wpdb->query($query);


		//INSERT default settings if data from previous install wasn't retained
		$events = $wpdb->get_var("SELECT COUNT(*) FROM $vp_settings");
		if($events < 1)
		{
			$vupango_settings['event_id'] = '1';
			$vupango_settings['event_name'] = '';
			$vupango_settings['event_lat'] = '39.7'; //Approximately the middle of the US
			$vupango_settings['event_lng'] = '-100.7';
			$vupango_settings['event_start'] = '2012-12-21 10:00:00';
			$vupango_settings['event_end'] = '2012-12-21 12:00:00'; //natch, it's the end of the world
			$vupango_settings['event_hashtag'] = '#VuPango';
			$vupango_settings['event_service'] = 'qik';
			$vupango_settings['event_signup'] = '0';
			$vupango_settings['event_page_id'] = '0';
			$vupango_settings['event_destroy'] = '0';
			
			$wpdb->insert($vp_settings,$vupango_settings);

			//INSERT VuPango page
			$vupango_page['post_author'] = $user_ID;
			$vupango_page['post_date'] = current_time('mysql');
			$vupango_page['post_date_gmt'] = $vupango_page['post_date'];
			$vupango_page['post_content'] = '';
			$vupango_page['post_title'] = 'VuPango';
			$vupango_page['post_excerpt'] = '';
			$vupango_page['post_status'] = 'publish';
			$vupango_page['comment_status'] = 'closed';
			$vupango_page['ping_status'] = 'closed';
			$vupango_page['post_password'] = '';
			$vupango_page['post_name'] = 'vupango';
			$vupango_page['to_ping'] = '';
			$vupango_page['pinged'] = '';
			$vupango_page['post_modified'] = $vupango_page['post_date'];
			$vupango_page['post_modified_gmt'] = $vupango_page['post_date'];
			$vupango_page['post_content_filtered'] = '';
			$vupango_page['post_parent'] = '0';
			$vupango_page['guid'] = '';
			$vupango_page['menu_order'] = '0';
			$vupango_page['post_type'] = 'page';
			$vupango_page['post_mime_type'] = '';
			$vupango_page['comment_count'] = '0';
			
			$wpdb->insert($wpdb->posts,$vupango_page);
	
	
			//UPDATE vupango_settings with the VuPango Page ID
			$where['event_id'] = '1';
			$event_page_id['event_page_id'] = $wpdb->insert_id;
			$wpdb->update($vp_settings,$event_page_id,$where);
		}
		else
		{
			$publish['post_status'] = 'publish';
			$where['ID'] = $wpdb->get_var("SELECT event_page_id FROM $vp_settings WHERE event_id = '1'");
			$wpdb->update($wpdb->posts,$publish,$where);
		}
	}
	
	function remove_vupango()
	{
		global $wpdb;
		
		$vp_cameras = $wpdb->prefix . 'vupango_cameras';
		$vp_settings = $wpdb->prefix . 'vupango_settings';
		
		$results = $wpdb->get_results("SELECT event_destroy FROM $vp_settings WHERE event_id = '1'");
		$event_destroy = $results[0]->event_destroy;
		
		if($event_destroy)
		{
			$vp_cameras = $wpdb->prefix . 'vupango_cameras';
			$vp_settings = $wpdb->prefix . 'vupango_settings';
		
			$wpdb->query('DROP TABLE ' . $vp_cameras);
			$wpdb->query('DROP TABLE ' . $vp_settings);
			$event_page_id = $wpdb->get_var("SELECT event_page_id FROM $vp_settings WHERE event_id = '1'");
			$wpdb->query("DELETE FROM $wpdb->posts WHERE ID = '$event_page_id' LIMIT 1");
		}
		else
		{
			$publish['post_status'] = 'draft';
			$where['ID'] = $wpdb->get_var("SELECT event_page_id FROM $vp_settings WHERE event_id = '1'");
			$wpdb->update($wpdb->posts,$publish,$where);
		}
	}


/*************************************************************************
	Variable initialization and required files
*************************************************************************/

	add_action('admin_init','submit_data'); //Function to process form data if there was a submission
	wp_enqueue_script('jquery');
	include('inc/get_maps.php');
	include('inc/db-entry.php');
	$plugin_path = plugins_url() . '/vupango/';
	
	

	if(is_admin())
	{
		add_action('admin_head','add_ajaxurl');
		add_action('admin_head','add_maps');
		add_action('wp_ajax_get_maps','get_maps');
		add_action('wp_ajax_nopriv_get_maps', 'get_maps');
		include('settings.php');
		include('inc/time.php');
	}
	
	if(!is_admin())
	{
		add_action('init','submit_data');
		add_filter('the_content','display_vupango');
		add_filter('sidebars_widgets','remove_item');
		add_action('wp_head','add_ajaxurl');
		add_action('wp_head','add_maps');
		//add_action('wp_head','display_map');
		//add_action('wp_head','load_select_map');
		add_action('wp_head','display_tweets');
		include('inc/streams.php');
		$event = $wpdb->get_results("SELECT event_service,event_hashtag,event_page_id FROM $vp_settings WHERE event_id = '1'");
		$event_service = $event[0]->event_service;
		$event_hashtag = $event[0]->event_hashtag;
		$event_page_id = $event[0]->event_page_id;
	}


/*************************************************************************
	AJAX Calls
*************************************************************************/

	function add_ajaxurl()
	{
		echo '<script type="text/javascript">';
		echo 	"var vupango = { ajaxurl: '" . admin_url('admin-ajax.php') . "'};";
		echo '</script>';
	}
	
	function add_maps()
	{
		echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>';
	}

	function display_map()
	{
		global $plugin_path;
		echo '<script type="text/javascript" src="' . $plugin_path . 'inc/display_map.js"></script>';
	}
	
	function load_select_map()
	{
		global $plugin_path;
		echo '<script type="text/javascript" src="' . $plugin_path . 'inc/select_map.js"></script>';
	}

	function display_tweets()
	{
		global $plugin_path, $event_hashtag;
		echo '<script type="text/javascript" src="' . $plugin_path . 'inc/jquery/jQuery.VuPangoTweets.js"></script>';
		echo '<script type="text/javascript">';
		echo 	'(function($) {';
		echo		"$('body').VuPangoTweets({";
		echo			"searchTerm: '" . $event_hashtag . "'";
		echo		'});';
		echo	'})(jQuery);';
		echo '</script>';
		echo '<style type="text/css">';
		echo	'#primary { display:none; }';
		echo '</style>';
	}


/*************************************************************************
	Main Program
*************************************************************************/
	
	function display_vupango($content)
	{
		global $wpdb, $post, $vp_settings, $vp_cameras, $event_service, $event_hashtag, $event_page_id, $plugin_path;
		$post_id = $post->ID;
		
		$results = $wpdb->get_results("SELECT event_start,event_end,event_service,event_signup,event_page_id FROM $vp_settings WHERE event_id = '1'");
		$event_start = strtotime($results[0]->event_start) - 600; //Add ten minutes before and after
		$event_end = strtotime($results[0]->event_end) + 600;
		$event_service = $results[0]->event_service;
		$event_signup = $results[0]->event_signup;
		$event_page_id = $results[0]->event_page_id;
		
		if($post_id == $event_page_id)
		{
			$offset = get_option('gmt_offset');
			$now = strtotime('now ' . $offset . 'hours');
			if($now > $event_start AND $now < $event_end)
			{
				$streams = $wpdb->get_results("SELECT camera_username FROM $vp_cameras");
				$stream_count = count($streams);
?>

				<div style="width:920px;margin:auto;">
					<script type="text/javascript" src="<?php echo $plugin_path; ?>inc/display_map.js"></script>
					<div id="map_canvas_display" style="width:900px;height:450px;border:10px solid #444;padding:0;margin-bottom:15px;"></div>
					<div id="stream-media" style="background:#000;border:10px solid #444;padding:0;">
<?php

				$array_width = '4';
				$stream_width = '225';
				if($event_service == 'ustream')
				{
					$array_width = '3';
					$stream_width = '300';
				}
				$makeup = $array_width - ($stream_count % $array_width);
				$makeup = $array_width == $makeup ? '0' : $makeup; // If there's no remainder, there's nothing to subtract, so we need to check
				
				foreach($streams as $stream)
				{
					echo(get_video_stream($event_service,$stream->camera_username,$stream_width));
				}
				
				for($i = 0; $i < $makeup; $i++)
				{
					echo(get_video_stream($event_service,$streams[$i]->camera_username,$stream_width));
				}
?>
					</div>
	
					<div id="tweet-display" style="width:900px;height:48px;border:10px solid #444;padding:0;margin-top:15px;">
						<ul id="tweets" style="margin:0;"></ul>
					</div>
				</div>
<?php
			}
			elseif($now > $event_end)
			{
?>
				<div style="width:900px;margin:auto;border:10px solid #444;text-align:center;">
					<h2>Welcome</h2>
					<p>This event has ended. Check our blog for updates! Thanks!</p>
				</div>
<?php
			}
			else
			{
?>
				<div style="width:900px;margin:auto;border:10px solid #444;text-align:center;">
					<h2>Welcome</h2>
					<p>This event will be occuring on <?php echo(date('l, F jS, Y \a\t g:i A',$event_start + 600)); ?>.</p>
					<p>It will conclude on <?php echo(date('l, F jS, Y \a\t g:i A',$event_end - 600)); ?>.</p>
				</div>
<?php
				if($event_signup)
				{
					if(empty($_POST['camera_settings']))
					{
?>
					<div style="width:900px;margin:auto;padding:10px 0;border:10px solid #444;text-align:center;position:relative;top:20px;">
						<h3>Add a New Camera</h3>
						<form method="post" action="<?php echo get_permalink($post_id); ?>">
						<input type="text" name="camera_name" value="Camera Name" style="width:150px;">
<?php
						if($event_service == 'ustream')
						{
?>
						<div class="form-field" style="width:318px;margin:auto;">
							<p>Copy your Ustream embed code here. It can be found under the live stream in your channel/show page.</p>
							<input type="text" name="camera_username" value="Ustream embed code" style="width:300px;">
						</div>
<?php
						}
						else
						{
?>
						<input type="text" name="camera_username" value="<?php echo $event_service; ?> username" style="width:150px;">
<?php
						}
?>
						<input type="text" name="camera_lat" id="lat" value="Latitude" style="width:150px;color:#E6E6E6;">
						<input type="text" name="camera_lng" id="lng" value="Longitude" style="width:150px;color:#E6E6E6;">
						<p>Click the location of the camera on the map to select coordinates</p>
						<script type="text/javascript" src="<?php echo $plugin_path; ?>inc/select_map.js"></script>
						<div id="map_canvas_select" style="width:587px;height:587px;border:10px solid #E6E6E6;margin:auto;"></div>
			
						<input type="submit" name="camera_settings" value="+ Add New Camera" class="primary button-primary" style="margin-top:10px;" />
						</form>
					</div>
<?php
					}
					else
					{
?>
					<div style="width:900px;margin:auto;padding:10px 0;border:10px solid #444;text-align:center;position:relative;top:20px;">
						<h2>Thanks!</h2>
						<p>We have received your camera submission.</p>
					</div>
<?php
					}
				}
			}
		}
		return $content;
	}
	
	function remove_item($item)
	{
		global $wpdb, $post, $vp_settings, $vp_cameras, $event_service, $event_hashtag, $event_page_id;
		$post_id = $post->ID;
		$event_page_id = $wpdb->get_var("SELECT event_page_id FROM $vp_settings WHERE event_id = '1'");
		if($post_id == $event_page_id)
		{
			$item = array(false);
		}
		return $item;
	}

?>