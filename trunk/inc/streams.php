<?php
/*************************************************************************
	VuPango Streaming Media Functions
		This library allows Pango to grab various user video streams and
		add them to the page
		
	Written by Joe Clay

	Revision History
	v0.1b - 3 Feb 2011
		Began work
	v1.0 - 9 Feb 2011
		First installed version
		Merged three functions into single get_video_stream() function
*************************************************************************/


/*************************************************************************
	Main Program
*************************************************************************/

	function get_video_stream($event_service, $user, $width)
	{
		switch($event_service)
		{
			case 'qik':
				$height = resize($width,'.75'); //Original 425x319
		
				$html =  '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,115,0" width="' . $width . '" height="' . $height . '" id="qikPlayer" align="middle">';
				$html .= '<param name="allowScriptAccess" value="sameDomain" />';
				$html .= '<param name="allowFullScreen" value="true" />';
				$html .= '<param name="movie" value="http://assets0.qik.com/swfs/qikPlayer5.swf?1296742218" />';
				$html .= '<param name="quality" value="high" />';
				$html .= '<param name="bgcolor" value="#000000" />';
				$html .= '<param name="FlashVars" value="username=' . $user . '&amp;autoplay=true&amp;mute=yes" />';
				$html .= '<embed src="http://assets0.qik.com/swfs/qikPlayer5.swf?1296742218" quality="high" bgcolor="#000000" width="' . $width . '" height="' . $height . '" name="qikPlayer" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" FlashVars="username=' . $user . '&amp;autoplay=true&amp;mute=yes" />';
				$html .= '</object>';
				
				return $html;
				break;
			case 'bambuser':
				$height = resize($width,'.9'); //Original 333x300
		
				$html =  '<object id="bplayer" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' . $width . '" height="' . $height . '">';
				$html .= '<embed name="bplayer" src="http://static.bambuser.com/r/player.swf" type="application/x-shockwave-flash" flashvars="username=' . $user . '&autostart=yes&mute=yes&chat=no" width="' . $width . '" height="' . $height . '" allowfullscreen="false" allowscriptaccess="always" wmode="opaque" />';
				$html .= '<param name="movie" value="http://static.bambuser.com/r/player.swf"></param>';
				$html .= '<param name="flashvars" value="username=' . $user . '&autostart=yes&mute=yes&chat=yes"></param>';
				$html .= '<param name="allowfullscreen" value="false"></param>';
				$html .= '<param name="allowscriptaccess" value="always"></param>';
				$html .= '<param name="wmode" value="opaque"></param>';
				$html .= '</object>';
				
				return $html;
				break;
			case 'ustream':
				$height = resize($width,'.617'); //Original 480x296
		
				$html =  '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="' . $width . '" height="' . $height . '">';
				$html .= '<param name="flashvars" value="autoplay=true&amp;brand=embed&amp;cid=' . $user . '&amp;v3=1"/>';
				$html .= '<param name="allowfullscreen" value="true"/>';
				$html .= '<param name="allowscriptaccess" value="always"/>';
				$html .= '<param name="movie" value="http://www.ustream.tv/flash/viewer.swf"/>';
				$html .= '<embed flashvars="autoplay=true&amp;brand=embed&amp;cid=' . $user . '&amp;v3=1" width="' . $width . '" height="' . $height . '" allowfullscreen="true" allowscriptaccess="always" src="http://www.ustream.tv/flash/viewer.swf" type="application/x-shockwave-flash" />';
				$html .= '</object>';
				
				return $html;
		}
	}


/*************************************************************************
	Sizing
*************************************************************************/

	function resize($width,$r)
	{
		$height = $width*$r;
		return $height;
	}

?>