<?php
	//Get Header and Footer script array
	$header_footer_script = get_option('wjmc_header_footer');
	
	$header_script = stripslashes( $header_footer_script['header_script'] );
	
	echo $header_script;
?>