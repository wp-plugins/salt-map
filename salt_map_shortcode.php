<?php
include_once('salt_map_setting.php');

class salt_map_shortcode {
	public function __construct() {
		add_shortcode( 'salt_map', array($this,'draw_map') );
		wp_register_script( 'salt_map_page_script', plugins_url( '/scripts/salt_map.js', __FILE__ ) );
		wp_register_script( 'salt_map_markerclusterer_script', plugins_url( '/scripts/markerclusterer_compiled.js', __FILE__ ) );
		wp_register_script( 'salt_map_mustache_script', plugins_url( '/scripts/mustache.js', __FILE__ ) );
		$salt_map_setting = new salt_map_setting(array());
		wp_register_script( 'salt_map_google_map', '//maps.googleapis.com/maps/api/js?key=' . $salt_map_setting->apiKey . '&sensor=false');
		
		wp_register_style( 'salt_map_page_style', plugins_url( '/styles/salt_map.css', __FILE__ ) );
		wp_register_style( 'salt_map_smoothness_style', plugins_url( '/jquery/css/smoothness/jquery-ui-1.10.3.custom.min.css', __FILE__ ) );
						
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'));
	}

	public function enqueue_scripts() {
		wp_enqueue_script( array('salt_map_google_map', 'salt_map_mustache_script', 'salt_map_markerclusterer_script', 'salt_map_page_script') );
		wp_enqueue_script( array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position','jquery-ui-autocomplete', 'jquery-ui-menu') );
		
		wp_enqueue_style( array('salt_map_page_style', 'salt_map_smoothness_style') );
	}
	
	public function createLocation(salt_map_setting $salt_map_setting, $meta_values, $content, $title) {
		if ($meta_values['salt_map_lat'][0] == null || empty($meta_values['salt_map_lat'][0]) ||
		$meta_values['salt_map_lng'][0] == null || empty($meta_values['salt_map_lng'][0])) {
			return null;
		}

		$location = array();
		$location['content'] = $content;
		$location['title'] = $title;
		$location['lat'] = $meta_values['salt_map_lat'][0];
		$location['lng'] = $meta_values['salt_map_lng'][0];
		foreach ($salt_map_setting->fieldsSettings as $field) {
			$value = $meta_values[$field['id']][0];
			$location[$field['id']] = $value;
		}

		return json_encode($location);
	}

	public function draw_map( $atts ) {		
		$salt_map_setting = new salt_map_setting($atts);

		if($salt_map_setting->includeSearch == "true") {
			$code .= '<input class="salt_map_search" id="saltMapSearch' . $salt_map_setting->instanceName . '" type="text" placeholder="' . __('Search', "salt-map") . '"/>';
		}
		$code .= '<style>#googleMap' . $salt_map_setting->instanceName . ' img {max-width: none !important;}</style>';
		$code .= '<div id="googleMap' . $salt_map_setting->instanceName . '" style="height:' . $salt_map_setting->height . 'px;"></div>';
		$code .= '<div id="infoWindow' . $salt_map_setting->instanceName . '"></div>';

		$code .= '<script>';
		$code .= 'var data = [';
		$args = array();
		$args['post_type'] = 'salt_map_location';
		$args['posts_per_page'] = 1000;
		if($salt_map_setting->hasMetaQuery()) {
			$args['meta_query'] = $salt_map_setting->getMetaQuery();
		}
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ){
			$loop->the_post();

			$salt_map_location = $this->createLocation($salt_map_setting, get_post_meta( get_the_ID() ), get_the_content(), get_the_title());
			if($salt_map_location != null) {
				$code .= $salt_map_location . ',';
			}
		}
		$code .= '];';
		$code .= 'var config =' . json_encode($salt_map_setting) . ';';
		$code .= 'config.infoWindow = document.getElementById("infoWindow' . $salt_map_setting->instanceName . '");';
		$code .= 'config.googleMap = document.getElementById("googleMap' . $salt_map_setting->instanceName . '");';
		$code .= 'config.saltMapSearch = document.getElementById("saltMapSearch' . $salt_map_setting->instanceName . '");';
		$code .= 'google.maps.event.addDomListener(window, "load", function(theData, theConfig){return function(){salt_setup_map(theData, theConfig);};}(data, config));';
		$code .= '</script>';

		return $code;
	}
}
?>