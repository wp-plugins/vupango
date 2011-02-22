/*************************************************************************
	Google Maps Grabber v1.0
		This code displays a google map for selecting latitude and
		longitude points.

	Otherwise Written by Joe Clay

	Revision History
	v0.1b - 7 Feb 2011
		First stable version

	v1.0 - 7 Feb 2011
		First installed version
*************************************************************************/


/*************************************************************************
	Variable Initialization
*************************************************************************/
	(function($) {

		var data = {
			action: 'get_maps',
			select: 'true'
		};
		
		$.ajax({
			type: 'POST',
			url: vupango.ajaxurl,
			dataType: 'json',
			data: data,
			success: function(location){
				createMap(location);
				}
		});
		
		function createMap(location)
		{
			var centerLatLng = new google.maps.LatLng(39.7, -100.7);
			var map_canvas = $('#map_canvas_select')[0];
			var event_map = $('.event_map')[0];
			var zoom = 13;
			if(event_map)
			{
				zoom = 2;
			}
			
			var options = {
				panControl:false,
				zoom:zoom,
				zoomControl:true,
				zoomControlOptions:{
					style: google.maps.ZoomControlStyle.SMALL
					},
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			
			map = new google.maps.Map(map_canvas, options);
			map.setCenter(centerLatLng);
			
			$.each(location, function(i,loc)
			{
				lat = loc.event_lat;
				lng = loc.event_lng;
			});
			
			centerLatLng = new google.maps.LatLng(lat,lng);
			map.setCenter(centerLatLng);
			
			marker = new google.maps.Marker({
				position: centerLatLng,
				map: map,
				title: 'Center'
			});
			
			google.maps.event.addDomListener(map, 'click', function(event) {
				marker.setMap(null);
				marker = new google.maps.Marker({
					position:	event.latLng,
					map:		map,
					title:		'Your Camera'
				});
				$('#lat').val(event.latLng.lat());
				$('#lng').val(event.latLng.lng());
			});
			
			$('#lat,#lng').focus(function(){
				$(this).blur();
			});

		}
		
	})(jQuery);