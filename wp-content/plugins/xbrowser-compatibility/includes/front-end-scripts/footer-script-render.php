<?php
	//Get Header and Footer script array
	$header_footer_script = get_option('wjmc_header_footer');
	
	$footer_script = stripslashes( $header_footer_script['footer_script'] );
	
	echo $footer_script;
?>