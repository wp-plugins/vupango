<?php
/*************************************************************************
	VuPango Options 1.0
		This page creates the options page in Wordpress Admin.
		
	Written by Joe Clay

	Revision History
	v0.1b - 4 Jan 2011
		Began work

	v0.2b - 5 Jan 2011
		Created Top level navigation
		Created Dummy pages
	
	v0.3b - 6 Jan 2011
		Created Cameras forms

	v0.4b - 7 Jan 2011
		Created Event forms
		Created Settings forms
		Created form logic
		Added persistency

	v1.0 - 8 Jan 2011
		First installed version
		Full form logic and persistency
*************************************************************************/


/*************************************************************************
	Variable initialization and required files
*************************************************************************/

	$plugin_path = plugins_url() . '/vupango/';
	$admin_path = admin_url() . 'admin.php?';

/*************************************************************************
	Main Program
*************************************************************************/

	add_action('admin_head','select_map');
	add_action('wp_ajax_get_maps','get_maps');

	add_action('admin_menu','add_vupango_panel');


/*************************************************************************
	AJAX Calls
*************************************************************************/
	function select_map()
	{
		global $plugin_path;
		echo '<script type="text/javascript" src="' . $plugin_path . 'inc/select_map.js"></script>';
	}

/*************************************************************************
	VuPango Menu
*************************************************************************/

	function add_vupango_panel()
	{
		global $plugin_path;
		
		add_menu_page('VuPango','VuPango','manage_options','vupango','event_page',$plugin_path . 'img/vupango_icon_s.png');
		add_submenu_page('vupango','Event','Event','manage_options','vupango','event_page');
		add_submenu_page('vupango','Cameras','Cameras','manage_options','cameras','cameras_page');
		add_submenu_page('vupango','Settings','Settings','manage_options','settings','settings_page');
	}


/*************************************************************************
	Event Page
*************************************************************************/

	function event_page()
	{
		if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		global $plugin_path, $admin_path, $vp_cameras, $vp_settings, $wpdb;

		$event_settings = $wpdb->get_results("SELECT event_name,event_lat,event_lng,event_start,event_end,event_hashtag FROM $vp_settings WHERE event_id = '1'");
		
		$event_name = $event_settings[0]->event_name;
		$event_lat = $event_settings[0]->event_lat;
		$event_lng = $event_settings[0]->event_lng;
		$event_start = $event_settings[0]->event_start;
		$event_end = $event_settings[0]->event_end;
		$event_hashtag = $event_settings[0]->event_hashtag;
		$event_signup = $event_settings[0]->event_signup;
		$event_start = timestamp_to_time($event_start);
		$event_end = timestamp_to_time($event_end);
?>		
		<div class="wrap">
			<div class="icon32"><img src="<?php echo $plugin_path; ?>img/vupango_icon.png" /></div>
			<h2>Event</h2>
			<p>Fill in your event details below to get started with your VuPango installation.</p>
			<form method="post" action="<?php echo $admin_path; ?>page=vupango">
			<h3>Main Location</h3>

			<div class="form-field" style="width:323px;">
				<label for="event_name">Event Name</label>
				<input type="text" name="event_name" value="<?php echo $event_name; ?>" size="40" />
			</div>

			<p>Click on the map to select the location for the main event</p>
			<input type="text" name="event_lat" id="lat" value="<?php echo $event_lat; ?>" style="width:150px;color:#E6E6E6;">
			<input type="text" name="event_lng" id="lng" value="<?php echo $event_lng; ?>" style="width:150px;color:#E6E6E6;">
			<div id="map_canvas_select" class="event_map" style="width:587px;height:587px;border:10px solid #E6E6E6;margin:10px 0;"></div>

			<h3>Event Date</h3>

			<div class="form-field" style="width:318px;">
				<label style="display:block;">Event Start</label>
				<select name="event_start_month">
<?php
				$months = array();
				$months[] = 'Jan';
				$months[] = 'Feb';
				$months[] = 'Mar';
				$months[] = 'Apr';
				$months[] = 'May';
				$months[] = 'Jun';
				$months[] = 'Jul';
				$months[] = 'Aug';
				$months[] = 'Sep';
				$months[] = 'Oct';
				$months[] = 'Nov';
				$months[] = 'Dec';
				
				$i = 1;
				
				foreach($months as $month)
				{
					echo '<option value="' . $i . '"';
					echo ($event_start['month'] == $i ? 'selected="selected"' : '');
					echo '>' . $month . '</option>';
					$i++;
				}
?>
				</select>
				<select name="event_start_day">
<?php
				for($i = 1; $i < 32; $i++)
				{
					echo '<option value="' . $i . '"';
					echo ($event_start['day'] == $i ? 'selected="selected"' : '');
					echo '>' . $i . '</option>';
				}
?>
				</select>
				<select name="event_start_year" style="margin-right:15px;">
<?php
				for($i = date('Y'); $i < date('Y')+5; $i++)
				{
					echo '<option value="' . $i . '"';
					echo ($event_start['year'] == $i ? 'selected="selected"' : '');
					echo '>' . $i . '</option>';
				}
?>
				</select>
				<input type="text" name="event_start_hour" value="<?php echo $event_start['hour']; ?>" size="2" style="width:30px;" />:
				<input type="text" name="event_start_min" value="<?php echo $event_start['minute']; ?>" size="2" style="width:30px;" />
				<select name="event_start_am">
					<option value="TRUE"<?php echo($event_start['AM'] ? 'selected="selected"' : '') ?>>AM</option>
					<option value="FALSE"<?php echo($event_start['AM'] ? '' : 'selected="selected"') ?>>PM</option>
				</select>


		
				<label style="display:block;margin-top:10px;">Event End</label>
				<select name="event_end_month">
<?php			
				$i = 1;
				
				foreach($months as $month)
				{
					echo '<option value="' . $i . '"';
					echo ($event_end['month'] == $i ? 'selected="selected"' : '');
					echo '>' . $month . '</option>';
					$i++;
				}
?>
				</select>
				<select name="event_end_day">
<?php
				for($i = 1; $i < 32; $i++)
				{
					echo '<option value="' . $i . '"';
					echo ($event_end['day'] == $i ? 'selected="selected"' : '');
					echo '>' . $i . '</option>';
				}
?>
				</select>
				<select name="event_end_year" style="margin-right:15px;">
<?php
				for($i = date('Y'); $i < date('Y')+5; $i++)
				{
					echo '<option value="' . $i . '"';
					echo ($event_end['year'] == $i ? 'selected="selected"' : '');
					echo '>' . $i . '</option>';
				}
?>
				</select>
				<input type="text" name="event_end_hour" value="<?php echo $event_end['hour']; ?>" size="2" style="width:30px;" />:
				<input type="text" name="event_end_min" value="<?php echo $event_end['minute']; ?>" size="2" style="width:30px;" />
				<select name="event_end_am">
					<option value="TRUE"<?php echo($event_end['AM'] ? 'selected="selected"' : '') ?>>AM</option>
					<option value="FALSE"<?php echo($event_end['AM'] ? '' : 'selected="selected"') ?>>PM</option>
				</select>

				<h3>Social</h3>
		
				<label for="event_hashtag">Twitter hashtag for event</label>
				<input type="text" name="event_hashtag" value="<?php echo $event_hashtag; ?>" size="40" style="margin-bottom:20px;" />
				<input type="submit" name="event_settings" value="Save" class="primary button-primary" style="width:50px;" />
				</form>
			</div>
		</div>
<?php
	}


/*************************************************************************
	Cameras Page
*************************************************************************/

	function cameras_page()
	{
		if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
		global $plugin_path, $admin_path, $vp_cameras, $vp_settings, $wpdb;
?>
		<div class="wrap">
			<div class="icon32"><img src="<?php echo $plugin_path; ?>img/vupango_icon.png" /></div>
			<h2>Camera Stream Settings</h2>
			<p>These are the settings for the camera streams. Only streams entered into this table will be visible from the live event page.</p>
			<form method="post" action="<?php echo $admin_path; ?>page=cameras">
			<h3>Service</h3>

<?php
		$event_service = $wpdb->get_var("SELECT event_service FROM $vp_settings WHERE event_id = '1'");
?>
			<select name="event_service">
			<option value="qik"<?php echo($event_service == 'qik' ? ' selected="selected"' : ''); ?>>Qik</option>
			<option value="bambuser"<?php echo($event_service == 'bambuser' ? ' selected="selected"' : ''); ?>>Bambuser</option>
			<option value="ustream"<?php echo($event_service == 'ustream' ? ' selected="selected"' : ''); ?>>Ustream</option>
			</select>
			<input type="submit" name="event_service_form" value="Save" class="primary button-primary" style="margin-left:10px;" />
			</form>
	
			<h3>Current Streams</h3>
			
			<table class="widefat" style="margin-bottom:20px;">
				<thead>
					<tr>
						<th>Camera Name</th>
						<th>Service Username/ID</th>
						<th>Latitude</th>
						<th>Longitude</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Camera Name</th>
						<th>Service Username/ID</th>
						<th>Latitude</th>
						<th>Longitude</th>
						<th>Delete</th>
					</tr>
				</tfoot>
				<tbody>
<?php
		$streams = $wpdb->get_results('SELECT * FROM wp_vupango_cameras');
		foreach($streams as $stream)
		{
			echo 	'<tr>';
			echo 		'<td>';
			echo			$stream->camera_name;
			echo		'</td>';
			echo		'<td>';
			echo			$stream->camera_username;
			echo		'</td>';
			echo		'<td>';
			echo			$stream->camera_lat;
			echo		'</td>';
			echo		'<td>';
			echo			$stream->camera_lng;
			echo		'</td>';
			echo		'<td>';
			echo			'<form method="post" action="' . $admin_path . 'page=cameras"><input type="hidden" name="camera_id" value="' . $stream->camera_id . '"><input type="submit" name="delete" value="Delete" style="text-align:center;" /></form>';
			echo		'</td>';
			echo 	'</tr>';
		}
?>
				</tbody>
			</table>
		
		
			<h3>Add a New Camera</h3>
			<form method="post" action="<?php echo $admin_path; ?>page=cameras">
			<input type="text" name="camera_name" value="Camera Name" style="width:150px;">
<?php
		if($event_service == 'ustream')
		{
?>
			<div class="form-field" style="width:318px;margin:15px 0 30px 0;">
				<p>Copy your Ustream embed code here. It can be found under the live stream in your channel/show page.</p>
				<input type="text" name="camera_username" value="Ustream embed code" style="width:300px;">
			</div>
<?php
		}
		else
		{
?>
			<input type="text" name="camera_username" value="Service Username" style="width:150px;">
<?php
		}
?>
			<input type="text" name="camera_lat" id="lat" value="Latitude" style="width:150px;color:#E6E6E6;">
			<input type="text" name="camera_lng" id="lng" value="Longitude" style="width:150px;color:#E6E6E6;">
			<p>Click the location of the camera on the map to select coordinates</p>
			<div id="map_canvas_select" style="width:587px;height:587px;border:10px solid #E6E6E6;margin-bottom:10px;"></div>
			
			<input type="submit" name="camera_settings" value="+ Add New Camera" class="primary button-primary" />
			</form>
		</div>
<?php
	}


/*************************************************************************
	Settings Page
*************************************************************************/

	function settings_page()
	{
		if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
		global $plugin_path, $admin_path, $vp_cameras, $vp_settings, $wpdb;
		
		$event_settings = $wpdb->get_results("SELECT event_signup,event_destroy FROM $vp_settings WHERE event_id = '1'");
		$event_signup = $event_settings[0]->event_signup;
		$event_destroy = $event_settings[0]->event_destroy;
?>
		<div class="wrap">
			<div class="icon32"><img src="<?php echo $plugin_path; ?>img/vupango_icon.png" /></div>
			<h2>VuPango Settings</h2>
			<p>These are the settings for VuPango</p>
			<form method="post" action="<?php echo $admin_path; ?>page=settings">		
			<div class="form-field">
				<h3>Signup</h3>				
				<p>Allow open signup?</p>
				<input type="checkbox" name="event_signup"<?php echo ($event_signup ? 'checked="yes"' : ''); ?> />
				<h3>Deactivation</h3>
				<p>Destroy data on deactivation?</p>
				<input type="checkbox" name="event_destroy"<?php echo ($event_destroy ? 'checked="yes"' : ''); ?> />
			</div>
			<input type="submit" name="vupango_settings" value="Save" class="primary button-primary" style="margin-top:20px;" />
			</form>
		</div>
<?php
	}
?>