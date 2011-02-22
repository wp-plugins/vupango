<?php
/*************************************************************************
	Time v.1b
		These functions take timestamps and deconstruct them for display
		in the settings form and reconstruct them for proper timestamps.
		
	Written by Joe Clay

	Revision History
	v0.1b - 7 Feb 2011
		Initial build

	v0.2b - 9 Feb 2011
		Fixed bug that cause 12pm to revert to 0am

	v1.0 - 9 Feb 2011
		First installed version
*************************************************************************/


/*************************************************************************
	Variable initialization and required files
*************************************************************************/


/*************************************************************************
	Main Program
*************************************************************************/

	function timestamp_to_time($timestamp)
	{
		$time = date_parse($timestamp);
		$time['AM'] = 1;
		if($time['hour'] >= 12)
		{
			if($time['hour'] != '12')
			{
				$time['hour'] -= 12;
			}
			$time['AM'] = 0;
		}
		if(strlen($time['minute']) < 2)
		{
			$time['minute'] = '0' . $time['minute'];
		}
		return $time;
	}
	
	function time_to_timestamp($time)
	{
		if($time['am'] == 'TRUE')
		{
			$am = 'AM';
		}
		else
		{
			$am = 'PM';
		}
		$time_str = $time['year'] . '-' . $time['month'] . '-' . $time['day'] . ' ' . $time['hour'] . ':' . $time['min'] . ' ' . $am;
		$timestamp = date('Y-m-d H:i:s',strtotime($time_str));
	
		return $timestamp;
	}

?>