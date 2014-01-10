=== SALT Map ===
Contributors: Samuel Erdtman
Tags: Google Maps, SALT, locations, map
Requires at least: 3.5.1
Tested up to: 3.5.1
Stable tag: 1.0
Donate Link: http://salt.efs.nu
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Uses google maps to visualise locations on a map. Creates a custom post type for locations and the possibility to have custom fields like address and other information. Then the information is formated for the info window with help of a template. By having the possibility to place the info window outside the map for small screens it functions well for mobile device too.

In addition to displaying the Locations it has support for search among locations with auto completion. The search will be one in all information about the location but all information does not have to be displayed by the template.

The map can be placed on whatever page or post you like with a short code. By giving the short code parameters global values can be override and locations can be filtered for each map. see short code section below for more details.

== Installation ==

Installation of this plugin is fairly easy:

1. Download the plugin
1. Extract all the files. 
1. Upload everything (keeping the directory structure) to the `/wp-content/plugins/` directory.
1. There should be a `/wp-content/plugins/salt_map` directory now with all files in it.
1. Activate the plugin through the 'Plugins' menu in WordPress.

or

1. Install through wordpress.org
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==
Non yet.

== Screenshots ==
1. A map with several locations, locations position too closely is grouped automatically.
1. Map showing location information inside map formated by template.
1. Map showing location information outside the map for mobile devices.
1. Search field with auto completion.
1. Global Map settings.
1. Global Map settings, showing how to add custom parameters for locations.
1. Location settings derived form the fields added under global settings.

== Changelog ==
= 1.0 =
Initial release.

== Upgrade Notice ==
This is the first version.

== Short Code ==
The salt map is integrated with the short code 'salt_map'. It has several parameters. All 
settings data can be override through the short code parameters, for a list of parameters 
se "Available parameters" here below. 

= Available parameters =
* **height**, hight of this map instance. 
* **lat**, inital center latetude.
* **lng**, inital center longitude.
* **zoom**, inital zoom.
* **gridSize**, limit for when to group locations 
* **maxWidth**, max width of infowindow
* **apiKey**, Google API key for loading the map.
* **largeScreenLimit**, screen size limit for when to display infowindow insode or outside map.
* **filterAttribute**, specifies an attribute to filter locations on. filterValue has to be set for this parameter to function.
* **filterValue**, specifies the value to filter attribute on, comarisone is made with LIKE. filterAttribute has to be set for this parameter to function. 

= Example =
<code>
[salt_map height=500px]
</code>

== Dependencies ==
* Markercluster
* Google maps API
* mustache.js

== Known Limitations ==
* It is not possible to have more then one map on one page.
* Changing icons requires changing of files
