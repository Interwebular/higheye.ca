<?php
/**
 * @package canvasio3D
 * @version 1.2.7
 */
/*
Plugin Name: canvasio3D Light
Plugin URI: http://www.canvasio3d.com/canvasio3d-light/
Description: Free 3D-Model Viewer | Forums: <a href="http://www.canvasio3d.com/en/forums/" target="_blank" >Link</a> | Documentation: <a href="http://www.canvasio3d.com/pub/doc/canvasio3d_light/English" target="_blank" >English</a> | <a href="http://www.canvasio3d.com/pub/doc/canvasio3d_light/Deutsch" target="_blank">German</a> || Shop owners or professional? - Maybe our <a href="http://www.canvasio3d.com/canvasio3d-pro/">Canvasio3D Pro</a> is for you.
Author: Thomas Scholl
Version: 1.2.7 
Author URI: http://www.virtuellwerk.de/
*/

$canvasioId = 0;
add_filter('upload_mimes', 'custom_upload_mimes');
include('inc/admin.inc.php');
include('inc/initLight.inc.php');
function custom_upload_mimes ( $existing_mimes=array() ) {
	$existing_mimes['js'] = 'application/javascript';
	$existing_mimes['stl'] = 'application/octet-stream';
	$existing_mimes['obj'] = 'application/octet-stream';
	$existing_mimes['dae'] = 'application/octet-stream';
	$existing_mimes['mtl'] = 'application/octet-stream';
	return $existing_mimes;
}
?>