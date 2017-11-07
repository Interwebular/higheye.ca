<?php
$lssc_contact_array_1 = get_option('lssc-contact');
$lssc_contact_array_2 = get_option('lssc-contact-2');
$lssc_contact_array_3 = get_option('lssc-contact-3');

if( $lssc_contact_array_1['name'] != null || !empty($lssc_contact_array_1['name']) ) {
	if( $lssc_contact_array_1['logo'] == null || empty($lssc_contact_array_1['logo']) ) {
		$address = '<span class="lssc_box_warning">Address 1 Tab</span>';
		lssc_notification($address);
	}
}
if( $lssc_contact_array_2['name'] != null || !empty($lssc_contact_array_2['name']) ) {
	if( $lssc_contact_array_2['logo'] == null || empty($lssc_contact_array_2['logo']) ) {
		$address = '<span class="lssc_box_warning">Address 2 Tab</span>';
		lssc_notification($address);
	}
}
if( $lssc_contact_array_3['name'] != null || !empty($lssc_contact_array_3['name']) ) {
	if( $lssc_contact_array_3['logo'] == null || empty($lssc_contact_array_3['logo']) ){
		$address = '<span class="lssc_box_warning">Address 3 Tab</span>';
		lssc_notification($address);
	}
}

function lssc_notification($address){
	echo '
	<style>
	.wp_local_seo_notif {
		border-left-color: #F44336!important;
	}
	.lssc_box_warning {
		font-weight: 600;
		color: #F44336;
		background-color: #ffebe9;
		padding: 3px 10px;
		border: 1px solid #ffc2bd;
		border-radius: 3px;
	}
	</style>
	<div class="updated wp_local_seo_notif">
		<p><b>Warning:</b> The <span style="font-weight: 600; color: #F44336;">Company Logo</span> field in the '
		. $address .
		' of the <span style="font-weight: 600; color: #F44336;">WP Local SEO</span> settings page is <b>empty</b>. Please provide a link to the logo URL and place it in the <a href="' . get_option('siteurl') . '/wp-admin/admin.php?page=local-seo">settings page</a>.</p>
	</div>
	';
}