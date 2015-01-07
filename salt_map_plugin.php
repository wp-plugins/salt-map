<?php
/*
 Plugin Name: SALT Map locations
 Description: Plugin for placing locations on a map with assiciated information, based on Google maps.
 Author: Samuel Erdtman for Salt
 Version: 1.4.2
 */
?>
<?php
include('salt_map_locations.php');
include('salt_map_settings.php');
include('salt_map_shortcode.php');

load_plugin_textdomain('salt-map', false, dirname( plugin_basename( __FILE__ )) . "/languages/");
$salt_map_locations = new salt_map_locations();
$salt_map_settings = new salt_map_settings();
$salt_map_shortcode = new salt_map_shortcode();
?>