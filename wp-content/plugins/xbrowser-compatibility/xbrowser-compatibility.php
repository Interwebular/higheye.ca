<?php
/**
* Plugin Name: xBrowser Compatibility
* Plugin URI: https://virson.wordpress.com/
* Description: Cross-Browser and Mobile Compatibility plugin that allows custom CSS coding, Equalizing container height, Custom Header and Footer scripts and Collapsed Mobile navigation menu.
* Version: 1.3.9
* Author: Virson Ebillo (Wordjack)
*/

//Load Wordpress core get_plugins function if it was not loaded
if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

//Check if Autoptimize plugin is activated
if( is_plugin_active('autoptimize/autoptimize.php') ) {
	
	//Exclude the following jquery libraries
	add_filter('autoptimize_filter_js_exclude','autoptimize_override_jsexclude',10,1);
	function autoptimize_override_jsexclude($exclude) {
		return $exclude.", jquery-1.12.4.min.js, jquery-2.2.4.min.js, jquery-3.1.0.min.js";
	}
	
}

//Define DB Query
global $wpdb;
$table = $wpdb->base_prefix . 'options';

//Define collapsed menu defaults
$plugin_query_cm_row = $wpdb->get_results("SELECT * from $table where option_name like '%wjmc_hide_mobile_submenu%'", OBJECT);
foreach($plugin_query_cm_row as $option_name) { $plugin_option_cm = $option_name->option_name; }

//Define Ajax saving defaults
$plugin_query_ajax_row = $wpdb->get_results("SELECT * from $table where option_name like '%wjmc_ajax_saving_mode%'", OBJECT);
foreach($plugin_query_ajax_row as $option_name) { $plugin_option_ajax = $option_name->option_name; }

//Define constants
define('XB_PLUGIN_VERSION', get_plugins( '/xbrowser-compatibility' )['xbrowser-compatibility.php']['Version']);
define('XB_PLUGIN_HOST_FILE', 'https://dl.dropboxusercontent.com/u/90662976/WP%20Plugins/xBrowser/version-host/xb_version.txt');
define('XB_PLUGIN_VERSION_DB', get_option('wjmc_host_version'));
define('XB_PLUGIN_CHANGELOG_HOST', 'https://dl.dropboxusercontent.com/u/90662976/WP%20Plugins/xBrowser/version-host/xb_changelog.txt');
define('XB_CM_SUBMENU_OPTION', get_option('wjmc_hide_mobile_submenu'));
define('XB_CM_SUBMENU_OPTION_ROW', $plugin_option_cm);
define('XB_AJAX_SAVING_MODE_ROW', $plugin_option_ajax);
define('XB_AJAX_SAVING_MODE', get_option('wjmc_ajax_saving_mode'));
define('XB_CURRENT_PAGE', $_GET['page']);
define('XB_PLUGIN_DIR_URL', preg_replace('/\s+/', '', plugin_dir_url(__FILE__)));
define('XB_PLUGIN_DIR_PATH', preg_replace('/\s+/', '', plugin_dir_path(__FILE__)));

/* ---------------------- xBrowser Cron Jobs ------------------------- */
function xb_cron_schedule( $schedules ) {
	// add a custom cron schedule schedule
	$schedules['xb_cron'] = array(
		'interval' => 1800,
		'display' => __('Every 30 minuets')
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'xb_cron_schedule' );

//Begin plugin activation hook
register_activation_hook(__FILE__, 'xb_activation');

function xb_activation() {
	
	//Read file with read and write permissions
	$handle = fopen( XB_PLUGIN_DIR_PATH . 'version.txt', 'w+' );
	
	//Write the version string to the file
	fwrite( $handle, XB_PLUGIN_VERSION);
	
	//Closes the file that was opened.
	fclose( $handle );
	
    if (!wp_next_scheduled ( 'xb_schedule_event' )) {
		wp_schedule_event(time(), 'xb_cron', 'xb_schedule_event');
    }
	
}

add_action('xb_schedule_event', 'xb_hourly_event_run');
function xb_hourly_event_run() {
	
	//Get plugin version content from external source (Dropbox)
	update_option('wjmc_host_version', @file_get_contents( XB_PLUGIN_HOST_FILE ));
	
}

//Begin plugin deactivation hook
register_deactivation_hook(__FILE__, 'xb_deactivation');
function xb_deactivation() {
	wp_clear_scheduled_hook('xb_schedule_event');
}

add_action('admin_head', 'xb_version_checker');
function xb_version_checker() {
	
	//Had to do it this way because doing the empty() function on a constant will result in a T_PAAMAYIM_NEKUDOTAYIM or double colon (::) error in PHP
	$xb_plugin_version_db = XB_PLUGIN_VERSION_DB;

	if( !empty( $xb_plugin_version_db ) ) {
		
		//String version num to Int version num
		$host_version = explode('.', XB_PLUGIN_VERSION_DB);
		$host_version = intval( implode('', $host_version) );
		
		//String version num to Int version num
		$xb_plugin_version = explode('.', XB_PLUGIN_VERSION);
		$xb_plugin_version = intval( implode('', $xb_plugin_version) );
		
		if( $xb_plugin_version < $host_version ) {
			
			echo "
			<div class='notice notice-warning'>
				<p style='font-weight: 600;'>xBrowser version <span style='color: #F44336;'>" . XB_PLUGIN_VERSION_DB . "</span> is now available (<span style='color: #F44336;'>Click <a href='" . XB_PLUGIN_CHANGELOG_HOST . "' target='_blank'>here</a> to view the change log</span>).</p>
			</div>
			";
			
		}
		
	}
	
}
/* ---------------------- End of xBrowser Cron Jobs ------------------------- */

//Include action links on the plugins page
add_filter( 'plugin_action_links', 'xbrowser_action_links', 10, 5 );
function xbrowser_action_links( $actions, $plugin_file ) {
	
	static $plugin;
	
	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);
	
	if ($plugin == $plugin_file) {

			$css_options = array('css_options' => '<a href="admin.php?page=xbrowser-compatibility">' . __('CSS Options', 'xBrowser') . '</a>');
			$equalize_settings = array('equalize_settings' => '<a href="admin.php?page=xbrowser-equalize-settings">' . __('Equalize', 'xBrowser') . '</a>');
			$header_footer_settings = array('header_footer_settings' => '<a href="admin.php?page=xbrowser-header-footer-settings">' . __('Header and Footer', 'xBrowser') . '</a>');
			$settings = array('settings' => '<a href="admin.php?page=xbrowser-compatibility-settings">' . __('Settings', 'xBrowser') . '</a>');
			
			$actions = array_merge($settings, $actions);
			$actions = array_merge($header_footer_settings, $actions);
			$actions = array_merge($equalize_settings, $actions);
			$actions = array_merge($css_options, $actions);
			
		}
	return $actions;
	
}

//Require the Function Scripts
require('includes/function-scripts.php');

//Begin loading specific Scripts/CSS at the main plugin page
if (XB_CURRENT_PAGE == 'xbrowser-compatibility') {

	//Load Admin CSS
	add_action('admin_footer', 'xbrowser_admin_css');
	
	//Load Admin JS
	add_action('admin_footer', 'xbrowser_admin_js');
	
	//Load Code Mirror Library
	add_action('admin_head', 'load_code_mirror_lib');
	
	if(XB_AJAX_SAVING_MODE == 'on') {
		//Load Ajax script
		add_action('admin_head', 'css_options_ajax_script');
	}
	if(XB_AJAX_SAVING_MODE_ROW == null) {
		//Load Ajax script
		add_action('admin_head', 'css_options_ajax_script');
	}
	
}

//Define Ajax script
function css_options_php() {
	
	$post_data = $_POST;
	
	update_option('wjmc_ipad', $_POST['ipad']);
	update_option('wjmc_nexus', $_POST['nexus']);
	update_option('wjmc_ipod', $_POST['ipod']);
	update_option('wjmc_general', $_POST['general']);
	
	update_option('wjmc_generic', $_POST['generic_mobile']);
	update_option('wjmc_small', $_POST['small']);
	update_option('wjmc_medium', $_POST['medium']);
	update_option('wjmc_large', $_POST['large']);
	//update_option('wjmc_large_1', $_POST['large_1']);
	//update_option('wjmc_extra_large', $_POST['extra_large']);
	
	update_option('wjmc_chrome', $_POST['chrome']);
	update_option('wjmc_safari', $_POST['safari']);
	update_option('wjmc_internet_explorer', $_POST['internet_explorer']);
	update_option('wjmc_firefox', $_POST['firefox']);
	
	update_option('wjmc_cache_override', $_POST['cache_override']);
	
	$debug_post['xb_ajax_post_array'] = $post_data;
	print_r( json_encode($debug_post) );
	exit;
}

//Load ajax action hook
add_action('wp_ajax_css_options_ajax', 'css_options_php');

//Begin loading specific Scripts/CSS at the equalize settings plugin page
if (XB_CURRENT_PAGE == 'xbrowser-header-footer-settings') {

	//Load Admin CSS
	add_action('admin_footer', 'xbrowser_admin_css');
	
	//Load Admin JS
	add_action('admin_footer', 'xbrowser_admin_js');
	
	//Load Code Mirror Library
	add_action('admin_head', 'load_code_mirror_lib');

}

//Begin loading specific Scripts/CSS at the header and footer settings plugin page
if (XB_CURRENT_PAGE == 'xbrowser-equalize-settings') {

	//Load Admin CSS
	add_action('admin_footer', 'xbrowser_admin_css');
	
	//Load Admin JS
	add_action('admin_footer', 'xbrowser_admin_js');
	
	//Load Code Mirror Library
	add_action('admin_head', 'load_code_mirror_lib');

}

//Begin loading specific Scripts/CSS at the jquery lib settings plugin page
if (XB_CURRENT_PAGE == 'xbrowser-global-jquery-lib-settings') {

	//Load Admin CSS
	add_action('admin_footer', 'xbrowser_admin_css');

}

//Begin loading specific Scripts/CSS at the plugin admin settings page
if (XB_CURRENT_PAGE == 'xbrowser-compatibility-settings') {

	//Load Admin CSS
	add_action('admin_footer', 'xbrowser_admin_css');
	
	//Load Admin JS
	add_action('admin_footer', 'xbrowser_admin_js');

}

//Begin Global Debug Mode
debug_mode();
if($debug_mode == 'on') {
	add_action('admin_head', 'error_report');
}

//Load front-end header script (Header and Footer Script)
add_action('wp_head', 'xbrowser_header_script', 0);
function xbrowser_header_script() {
	require('jquery-lib/jquery-lib.php');
	require('includes/front-end-scripts/header-script-render.php');
}

//Load front-end JS
if(XB_CM_SUBMENU_OPTION == 'on') {
	
	add_action('wp_footer', 'xbrowser_collapsed_menu_script');
	function xbrowser_collapsed_menu_script(){
		require('includes/front-end-scripts/collapsed-menu-script.php');
	}
	
}
if(XB_CM_SUBMENU_OPTION_ROW == null) {
	
	add_action('wp_footer', 'xbrowser_collapsed_menu_script');
	function xbrowser_collapsed_menu_script(){
		require('includes/front-end-scripts/collapsed-menu-script.php');
	}
	
}

//Load front-end footer scripts
add_action('wp_footer', 'xb_wp_footer_scripts', 99);
function xb_wp_footer_scripts() {
	
	//Load Wordpress built-in dashicons
	wp_enqueue_style( 'dashicons' );
	
	//Load front-end CSS
	require('includes/front-end-scripts/front-end-css.php');
	
	//Load front-end script for equalize container height ( had to do it separately from the collapsed sub-menu feature. :\ )
	require('includes/front-end-scripts/equalize-script.php');
	
	//Load front-end special equalize script
	require('includes/front-end-scripts/special-equalize-script.php');
	
	//Load front-end footer script (Header and Footer Script)
	require('includes/front-end-scripts/footer-script-render.php');
	
}

//Setup Menu pages
add_action('admin_menu', 'xb_menu_pages');
function xb_menu_pages() {
	
	//Require the plugin files
	require('includes/x-browser.php');
	require('includes/equalize-settings.php');
	require('includes/header-footer-settings.php');
	require('includes/jquery-lib-settings.php');
	require('includes/settings.php');
	
	add_menu_page('xBrowser Compatibility', 'xBrowser', 'administrator', 'xbrowser-compatibility', 'main_xbrowser_options', XB_PLUGIN_DIR_URL . 'img/xb-icon.png', 61);
	add_submenu_page('xbrowser-compatibility', 'xBrowser CSS Options', 'CSS Options', 'administrator', 'xbrowser-compatibility');
	add_submenu_page( 'xbrowser-compatibility', 'xBrowser Equalize Height Settings', 'Equalize Height', 'administrator', 'xbrowser-equalize-settings', 'xb_equalize_settings' );
	add_submenu_page( 'xbrowser-compatibility', 'xBrowser Header and Footer Settings', 'Header and Footer', 'administrator', 'xbrowser-header-footer-settings', 'xb_header_footer_settings' );
	add_submenu_page( 'xbrowser-compatibility', 'xBrowser Global jQuery Lib', 'Global jQuery Lib', 'administrator', 'xbrowser-global-jquery-lib-settings', 'xb_global_jquery_lib_settings' );
	add_submenu_page( 'xbrowser-compatibility', 'xBrowser Settings', 'Settings', 'administrator', 'xbrowser-compatibility-settings', 'xb_settings' );
	
}