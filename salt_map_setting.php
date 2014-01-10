<?php
class salt_map_setting {

	var $resourcesLocation;
	var $lat;
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

	public function __construct($atts) {
		extract( shortcode_atts( array(
		'height' => null,
		'lat' => null,
		'lng' => null,
		'zoom' => null,
		'gridSize' => null,
		'maxWidth' => null,
		'infoTemplate' => null,
		'apiKey' => null,
		'largeScreenLimit' => null,
		'filterattribute' => null,
		'filtervalue' => null
		), $atts ) );

		$this->resourcesLocation = plugins_url('salt_map');
		$this->lat = $this->getSetting('lat', 59, $lat);
		$this->lng = $this->getSetting("lng", 17, $lng);
		$this->zoom = intval($this->getSetting("zoom", "5", $zoom));
		$this->gridSize = intval($this->getSetting("gridSize", 20, $gridSize));
		$this->maxWidth = $this->getSetting("maxWidt", 300, $maxWidth);
		$this->height = $this->getSetting("height", "380px", $height);
		$this->infoTemplate = $this->getSetting("infoTemplate", "{{title}} - {{{text}}}", $infoTemplate);
		$this->height = $this->getSetting("height", "380px", $height);
		$this->apiKey = $this->getSetting("api_key", "AIzaSyDSNxdSHJ-t71R5v-K2PnFMBCVv2DKC_mU", $apiKey);
		$this->fieldsSettings = json_decode($this->getSetting("fieldsSettings", "[]", null), true);
		$this->largeScreenLimit = $this->getSetting("largeScreenLimit", "500px", $largeScreenLimit);
		$this->filterAttribute = $filterattribute;
		$this->filterValue = $filtervalue;
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