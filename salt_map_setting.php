<?php
class salt_map_setting {
	public $lat;
	public $lng;
	public $zoom;
	public $gridSize;
	public $maxWidth;
	public $height;
	public $infoTemplate;
	public $apiKey;
	public $fieldsSettings;
	public $largeScreenLimit;
	public $filterAttribute;
	public $filterValue;
	public $includeSearch;
	public $resourcesLocation;
	public $locationIcon;
	public $locationGroup1Icon;
	public $locationGroup2Icon;
	public $locationGroup3Icon;

	public function __construct($atts) {
		extract( shortcode_atts( array(
		'height' => null,
		'lat' => null,
		'lng' => null,
		'zoom' => null,
		'gridsize' => null,
		'maxwidth' => null,
		'infotemplate' => null,
		'apikey' => null,
		'largescreenlimit' => null,
		'filterattribute' => null,
		'filtervalue' => null,
		'includesearch' => null,
		'locationIcon' => null,	
		'locationGroup1Icon' => null,
		'locationGroup2Icon' => null,
		'locationGroup3Icon' => null									
		), $atts ) );

		$this->resourcesLocation = plugins_url('salt_map');
		$this->locationIcon = $this->getSetting('locationIcon', $this->resourcesLocation . '/images/salt_location.png', $locationIcon);
		$this->locationGroup1Icon = $this->getSetting('locationGroup1Icon', $this->resourcesLocation . '/images/salt_location_cluster_1.png', $locationGroup1Icon);
		$this->locationGroup2Icon = $this->getSetting('locationGroup2Icon', $this->resourcesLocation . '/images/salt_location_cluster_2.png', $locationGroup2Icon);
		$this->locationGroup3Icon = $this->getSetting('locationGroup3Icon', $this->resourcesLocation . '/images/salt_location_cluster_3.png', $locationGroup3Icon);
		$this->lat = $this->getSetting('lat', 59, $lat);
		$this->lng = $this->getSetting("lng", 17, $lng);
		$this->zoom = intval($this->getSetting("zoom", "5", $zoom));
		$this->gridSize = intval($this->getSetting("gridSize", 20, $gridsize));
		$this->maxWidth = $this->getSetting("maxWidt", 300, $maxwidth);
		$this->height = $this->getSetting("height", "380px", $height);
		$this->infoTemplate = $this->getSetting("infoTemplate", "{{title}} - {{{text}}}", $infotemplate);
		$this->height = $this->getSetting("height", "380px", $height);
		$this->apiKey = $this->getSetting("api_key", "AIzaSyDSNxdSHJ-t71R5v-K2PnFMBCVv2DKC_mU", $apikey);
		$this->fieldsSettings = json_decode($this->getSetting("fieldsSettings", "[]", null), true);
		$this->largeScreenLimit = $this->getSetting("largeScreenLimit", "500px", $largescreenlimit);
		$this->filterAttribute = $filterattribute;
		$this->filterValue = $filtervalue;
		$this->includeSearch = $includesearch;
	}

	public function getMetaQuery() {
		return array( array(
           'key' => $this->filterAttribute,
           'value' => $this->filterValue,
           'compare' => 'LIKE'
		));
	}

	public function hasMetaQuery() {
		return ($this->filterAttribute != null && $this->filterValue != null);
	}

	public function getSetting($key, $default, $override) {
		if($override != null) {
			return $override;
		}

		$value = get_option( "salt_map_settings", null );
		$value = array_key_exists($key, $value) ? $value[$key] : null;

		if($value == null || empty($value)) {
			return $default;
		}
		return $value;
	}
}
?>