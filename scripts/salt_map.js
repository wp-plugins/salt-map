function salt_setup_map(data, config) {
	var mapProp = {
		scrollwheel: false,
		center : new google.maps.LatLng(config.lat, config.lng),
		zoom : config.zoom,
		mapTypeId : google.maps.MapTypeId.ROADMAP
	};
	var styles = [ {
		url : config.locationGroup1Icon,
		height : 35,
		width : 35,
		opt_anchor : [ 16, 0 ],
		opt_textColor : '#fff',
		opt_textSize : 10
	}, {
		url : config.locationGroup2Icon,
		height : 45,
		width : 45,
		opt_anchor : [ 24, 0 ],
		opt_textColor : '#fff',
		opt_textSize : 11
	}, {
		url : config.locationGroup3Icon,
		height : 55,
		width : 55,
		opt_anchor : [ 32, 0 ],
		opt_textColor : '#fff',
		opt_textSize : 12
	} ];
	
	var NewInfoWindow = function(text, maxWidth) {
		var infoWindow = config.infoWindow;
		var open = false;
		return {
			close : function() {
				infoWindow.innerHTML = "";
				open = false;
			},
			open : function() {
				infoWindow.innerHTML = text;
				open = true;
			},
			isOpen : function() {
				return open;
			}
		}
	};
	
	var NewGoogleInfoWindow = function(text, maxWidth) {
		var infoWindow = new google.maps.InfoWindow( {
			content : text,
			maxWidth : maxWidth
		});

		return {
			close : function() {
				infoWindow.close();
			},
			open : function(map, marker) {
				infoWindow.open(map, marker);
			},
			isOpen : function() {
				var map = infoWindow.getMap();
				return (map !== null && typeof map !== "undefined");
			}
		}
	};
	
	var InfoWindowCreator = NewGoogleInfoWindow;
	if (window.matchMedia !== undefined) {
		var mq = window.matchMedia("(min-width: " + config.largeScreenLimit + ")");
		if (!mq.matches) {
			InfoWindowCreator = NewInfoWindow;
		}
	}

	var openLocation;
	var NewLocation = function(locationData) {
		var marker = new google.maps.Marker( {
			position : new google.maps.LatLng(locationData.lat,
					locationData.lng),
			icon : config.locationIcon
		});
		var text = Mustache.render(config.infoTemplate, locationData);

		var infoWindow = InfoWindowCreator(text, config.maxWidth);

		var me = {
			label : locationData.title,
			toggle : function() {
				if (infoWindow.isOpen()) {
					infoWindow.close();
					if (openLocation === me) {
						openLocation = null;
					}
				} else {
					if (openLocation) {
						openLocation.close();
					}
					infoWindow.open(map, marker);
					openLocation = me;
				}
			},
			contains : function(needle) {
				for ( var prop in locationData) {
	              if (locationData.hasOwnProperty(prop) 
	            		  && typeof locationData[prop] === 'string'
	            			  && locationData[prop].search(needle) !== -1) {
	                return true;
	              }
	            }
				return false;
			},
			getMarker : function() {
				return marker;
			},
			getPosition : function() {
				return marker.getPosition();
			},
			setMap : function(localMap) {
				marker.setMap(localMap);
			},
			close : function() {
				infoWindow.close();
			}
		};
		return me;
	}

	var map = new google.maps.Map(config.googleMap, mapProp);
	var markers = [];
	var locations = [];
	for ( var i = 0; i < data.length; i++) {
		var location = NewLocation(data[i]);
		google.maps.event.addListener(location.getMarker(), 'click', location.toggle);
		markers.push(location.getMarker());
		locations.push(location);
		location.setMap(map);
	}

	var markerclusterer = new MarkerClusterer(map, markers, {
		maxZoom : null,
		gridSize : config.gridSize,
		styles : styles
	});
	
	jQuery(document).ready(function() {
	  jQuery(config.saltMapSearch).autocomplete( {
        source : function(request, response) {
		  var needle = new RegExp(request.term, "i");
          var resp = [];
          for ( var i = 0; i < locations.length && resp.length < 10; i++) {
            var current = locations[i]
            if(current.contains(needle)){
              resp.push(current);
            }
          }
          response(resp);
        },
        select : function(event, ui) {
    	  map.setCenter(ui.item.getPosition());
    	  map.setZoom(11);
    	  ui.item.toggle();
          return false;
        }
      });

      var height = jQuery(window).height();
      if (height < config.height) {
		jQuery(config.googleMap).height(height - 50);
      }
    });
}



