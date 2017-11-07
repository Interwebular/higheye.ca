<?php	
	//Mobile
	$general = stripslashes(get_option('wjmc_general'));
	$ipad = stripslashes(get_option('wjmc_ipad'));
	$nexus = stripslashes(get_option('wjmc_nexus'));
	$ipod = stripslashes(get_option('wjmc_ipod'));
	$content_id = stripslashes(get_option('wjmc_content_id'));
	$generic_mobile = stripslashes(get_option('wjmc_generic'));
	
	//Desktop
	$small = stripslashes(get_option('wjmc_small'));
	$medium = stripslashes(get_option('wjmc_medium'));
	$large = stripslashes(get_option('wjmc_large'));
	$large_1 = stripslashes(get_option('wjmc_large_1'));
	$extra_large = stripslashes(get_option('wjmc_extra_large'));
	
	//Desktop Cross Browser
	$chrome = stripslashes(get_option('wjmc_chrome'));
	$firefox = stripslashes(get_option('wjmc_firefox'));
	$internet_explorer = stripslashes(get_option('wjmc_internet_explorer'));
	$safari = stripslashes(get_option('wjmc_safari'));
	
	//Collapsed Sub Menu navigation on mobile with defaults
	$collapsed_mobile_submenu = get_option('wjmc_hide_mobile_submenu');
	global $wpdb;
	$table .= $wpdb->base_prefix;
	$table .= 'options';
	$plugin_query = $wpdb->get_results("SELECT * from $table where option_name like '%wjmc_hide_mobile_submenu%'", OBJECT);
	foreach($plugin_query as $option_name); { $plugin_option_name = $option_name->option_name; }
	if($plugin_option_name == null) { $collapsed_mobile_submenu = 'on'; }
?>
<style type="text/css">

	<?php echo $general; ?>
	
	<?php echo $content_id; ?> iframe, <?php echo $content_id; ?> embed, <?php echo $content_id; ?> object {
		max-width: 100%;
	}
	
	<?php
	// Define User Agents
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	if (stripos( $user_agent, 'Chrome') !== false) {
		echo "/* Chrome */\n" . $chrome;
	}
	elseif (stripos( $user_agent, 'Safari') !== false) {
	   echo "/* Safari */\n" . $safari;
	}
	elseif (stripos( $user_agent, 'MSIE') !== false) {
	   echo "/* Internet Explorer 8, 9, 10, 11, edge */\n" . $internet_explorer;
	}
	elseif (stripos( $user_agent, 'Trident') !== false) {
	   echo "/* Internet Explorer 8, 9, 10, 11, edge */\n" . $internet_explorer;
	}
	elseif (stripos( $user_agent, 'Firefox') !== false) {
	   echo "/* Firefox */\n" . $firefox;
	}
	else {
		echo "/* User Agent Not Defined */";
	}
	?>
	
	@media (max-width: 1920px) {
		<?php echo $extra_large; ?>
	}
	
	@media (max-width: 1600px) {
		<?php echo $large_1; ?>
	}
	
	@media (max-width: 1366px) {
		<?php echo $large; ?>
	}
	
	@media (max-width: 1288px) {
		<?php echo $medium; ?>
	}
	
	@media (max-width: 1024px) {
		<?php echo $small; ?>
	}
	
	@media (max-width: 980px) {
		<?php
		if($collapsed_mobile_submenu == 'on'){
			echo ".sub-menu, .sub-menu .sub-menu, .et_mobile_menu li ul, .et_mobile_menu li ul li ul {
				display: none!important;
			}";
		}
		?>
		<?php echo $generic_mobile; ?>
		.icon_img {
			float: right;
			position: relative;
			width: 35px;
			height: 31px;
			background: url('<?php echo plugins_url(); ?>/xbrowser-compatibility/img/roll.png');
			background-repeat: no-repeat;
			background-position: 5px 5px;
			cursor: pointer;
		}
		.over {
			background: url('<?php echo plugins_url(); ?>/xbrowser-compatibility/img/roll-over.png');
			background-repeat: no-repeat;
			background-position: 5px 5px;
			cursor: pointer;
		}
		
	}
	
	@media (max-width: 773px) {
		<?php echo $ipad; ?>
	}
	
	@media (max-width: 601px) {
		/* Defaults for images on pages */
		.entry-content .alignleft, .entry-content .alignright {
			 display: block!important;
			 float: none!important;
			 margin: 0 auto 30px!important;
		}
		<?php echo $nexus ?>
	}
	
	@media (max-width: 480px) {
		<?php echo $ipod; ?>
	}
</style>