<?php
/*
This Database Migration script is available from version 1.1.6
*/
$plugin_version = LSSC_PLUGIN_VERSION;
$plugin_version = explode('.', $plugin_version);
$plugin_version = intval( implode('', $plugin_version) );

$plugin_db_version = get_option('lssc-version'); //DB plugin version
$plugin_db_version = explode('.', $plugin_db_version);
$plugin_db_version = intval( implode('', $plugin_db_version) );

if( get_option('lssc-version') == false ) {
	update_option('lssc-version', $plugin_version);
	run_lssc_db_updater($plugin_version);
} else {
	run_lssc_db_updater($plugin_version, $plugin_db_version);
}

function run_lssc_db_updater($plugin_version, $plugin_db_version = ''){
	if( $plugin_version > $plugin_db_version || $plugin_db_version == '') {
		
		//For Contact Information Logo Field
		$lssc_contact_array_1 = get_option('lssc-contact');
		$lssc_contact_array_2 = get_option('lssc-contact-2');
		$lssc_contact_array_3 = get_option('lssc-contact-3');
		
		//For Social Media Icons
		$business_socials_array_1 = get_option('lssc-business-socials');		
        $order_socials_array_1 = get_option('lssc-business-socials-order1');
		$business_socials_array_2 = get_option('lssc-business-socials-2');
		$order_socials_array_2 = get_option('lssc-business-socials-order2');
		$business_socials_array_3 = get_option('lssc-business-socials-3');
        $order_socials_array_3 = get_option('lssc-business-socials-order3');
		
		//Insert new items into the array
		array_push($lssc_contact_array_1['logo'], '');
		array_push($lssc_contact_array_2['logo'], '');
		array_push($lssc_contact_array_3['logo'], '');
		
		//For Social Icon values
		array_push($business_socials_array_1['vimeo'], '');
		array_push($business_socials_array_1['vimeo-icon'], '');
		array_push($business_socials_array_2['vimeo'], '');
		array_push($business_socials_array_2['vimeo-icon'], '');
		array_push($business_socials_array_3['vimeo'], '');
		array_push($business_socials_array_3['vimeo-icon'], '');
		
		//For Social Icon sorting
		array_push($order_socials_array_1, 'vimeo');
		array_push($order_socials_array_2, 'vimeo');
		array_push($order_socials_array_3, 'vimeo');
		
		//Finally do the migration
		update_option('lssc-contact', $lssc_contact_array_1);
		update_option('lssc-contact-2', $lssc_contact_array_2);
		update_option('lssc-contact-3', $lssc_contact_array_3);
		
		update_option('lssc-business-socials', $business_socials_array_1);
		update_option('lssc-business-socials-2', $business_socials_array_2);
		update_option('lssc-business-socials-3', $business_socials_array_3);
		
		update_option('lssc-business-socials-order1', $order_socials_array_1);
		update_option('lssc-business-socials-order2', $order_socials_array_2);
		update_option('lssc-business-socials-order3', $order_socials_array_3);
		
		//Update plugin version number
		update_option('lssc-version', $plugin_version);
	}
}