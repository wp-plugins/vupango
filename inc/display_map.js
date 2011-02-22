/*************************************************************************
	Google Maps Stream Display v1.0
		This code displays a google map for selecting latitude and
		longitude points.

	Otherwise Written by Joe Clay

	Revision History
	v0.1b - 26 Jan 2011
		First working version

	v1.0 - 9 Feb 2011
		First installed version
*************************************************************************/


/*************************************************************************
	Variable Initialization
*************************************************************************/
	(function($) {
		
		var data = {
			action: 'get_maps',
			display: 'true'
		};
		
		$.ajax({
			type: 'POST',
			url: vupango.ajaxurl,
			dataType: 'json',
			data: data,
			success: function(locations){
				createMap(locations);
				}
		});

		function createMap(locations)
		{
			var centerLatLng = new google.maps.LatLng(39.7, -100.7);
			var options = {
				panControl:false,
				zoomControl:true,
				zoomControlOptions:{
					style: google.maps.ZoomControlStyle.SMALL
					},
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
	
			var map_canvas = $('#map_canvas_display')[0];
			var map = new google.maps.Map(map_canvas, options);
			map.setCenter(centerLatLng);
			var bounds = new google.maps.LatLngBounds();
			
			//Add markers to map and to bounds variable
			for(var i=0; i<locations.length; i++)
			{
				name = locations[i]['camera_name'];
				lat = locations[i]['camera_lat'];
				lng = locations[i]['camera_lng'];
				
				loc = new google.maps.LatLng(lat,lng);
				bounds.extend(loc);
				marker = new google.maps.Marker({
					position: loc,
					map: map,
					title: name
				});
			}
			map.fitBounds(bounds);
		}
		
		/*function codeAddress(address) {
			geocoder = new google.maps.Geocoder();
			geocoder.geocode({ 'address': address}, function(results, status){
				if (status == google.maps.GeocoderStatus.OK)
				{
					map.setCenter(results[0].geometry.location);
					var marker = new google.maps.Marker({
						map: map, 
						position: results[0].geometry.location
					});
				}
			});
		}*/
	})(jQuery);