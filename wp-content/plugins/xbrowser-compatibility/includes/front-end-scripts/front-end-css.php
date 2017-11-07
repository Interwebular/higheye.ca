<?php

	//Mobile
	$general = stripslashes(get_option('wjmc_general'));
	$ipad = stripslashes(get_option('wjmc_ipad'));
	$nexus = stripslashes(get_option('wjmc_nexus'));
	$ipod = stripslashes(get_option('wjmc_ipod'));
	$generic_mobile = stripslashes(get_option('wjmc_generic'));
	
	//Desktop
	$small = stripslashes(get_option('wjmc_small'));
	$medium = stripslashes(get_option('wjmc_medium'));
	$large = stripslashes(get_option('wjmc_large'));
	//$large_1 = stripslashes(get_option('wjmc_large_1'));
	//$extra_large = stripslashes(get_option('wjmc_extra_large'));
	
	//Desktop Cross Browser
	$chrome = stripslashes(get_option('wjmc_chrome'));
	$firefox = stripslashes(get_option('wjmc_firefox'));
	$internet_explorer = stripslashes(get_option('wjmc_internet_explorer'));
	$safari = stripslashes(get_option('wjmc_safari'));
	
	//Cache Override
	$cache_override = get_option('wjmc_cache_override');
	
	//Collapsed Sub Menu navigation on mobile with defaults
	$collapsed_mobile_submenu = get_option('wjmc_hide_mobile_submenu');
	if(XB_CM_SUBMENU_OPTION_ROW == null) { $collapsed_mobile_submenu = 'on'; }
	
?>

<?php if( $cache_override == 'on'): ?>
<script type='text/javascript'>
	
	//Define variables
	var co = jQuery.noConflict(),
		
		//Get browser user agent
		user_agent_string = navigator.userAgent,
		
		//Define string pattern
		ua_msie = new RegExp("MSIE"),
		ua_trident = new RegExp("Trident"),
		ua_edge = new RegExp("Edge"),
		ua_chrome = new RegExp("Chrome"),
		ua_safari = new RegExp("Safari"),
		ua_firefox = new RegExp("Firefox"),
		
		//Get boolean value from the pattern
		res_msie = ua_msie.test(user_agent_string),
		res_trident = ua_trident.test(user_agent_string),
		res_edge = ua_edge.test(user_agent_string),
		res_chrome = ua_chrome.test(user_agent_string),
		res_safari = ua_safari.test(user_agent_string),
		res_firefox = ua_firefox.test(user_agent_string);
	
	//Define the function
	function cache_override() {
		
		if( res_msie ) {
			co('#trident').remove();
			co('#edge').remove();
			co('#chrome').remove();
			co('#safari').remove();
			co('#firefox').remove();
		}else if( res_trident ){
			co('#msie').remove();
			co('#edge').remove();
			co('#chrome').remove();
			co('#safari').remove();
			co('#firefox').remove();
		}else if( res_edge ){
			co('#msie').remove();
			co('#trident').remove();
			co('#chrome').remove();
			co('#safari').remove();
			co('#firefox').remove();
		}else if( res_chrome ){
			co('#trident').remove();
			co('#edge').remove();
			co('#msie').remove();
			co('#safari').remove();
			co('#firefox').remove();
		}else if( res_safari ){
			co('#trident').remove();
			co('#edge').remove();
			co('#msie').remove();
			co('#chrome').remove();
			co('#firefox').remove();
		}else if( res_firefox ){
			co('#trident').remove();
			co('#edge').remove();
			co('#msie').remove();
			co('#chrome').remove();
			co('#safari').remove();
		}else {
			console.log('User Agent Not Defined');
		}
		
	}
	co(window).load(cache_override);
</script>
<?php
echo '
		<div id="msie"><style type="text/css">' . '/* Internet Explorer 8, 9, 10, 11, edge */' . "\n" . $internet_explorer . '</style></div>
		<div id="trident"><style type="text/css">' . '/* Internet Explorer 8, 9, 10, 11, edge */' . "\n" . $internet_explorer . '</style></div>
		<div id="edge"><style type="text/css">' . '/* Internet Explorer 8, 9, 10, 11, edge */' . "\n" . $internet_explorer . '</style></div>
		<div id="chrome"><style type="text/css">' . '/* Chrome */' . "\n" . $chrome . '</style></div>
		<div id="safari"><style type="text/css">' . '/* Safari */' . "\n" . $safari . '</style></div>
		<div id="firefox"><style type="text/css">' . '/* Firefox */' . "\n" . $firefox . '</style></div>
	';
	
endif;
?>

<style type="text/css">

	<?php echo $general; ?>
	
	iframe, embed, object {
		max-width: 100%;
	}
	
	<?php
	if( $cache_override != 'on') {
	
		// Define User Agents
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
	
		if (stripos( $user_agent, 'MSIE') !== false) {
		   echo "/* Internet Explorer 8, 9, 10, 11, edge */\n" . $internet_explorer;
		}
		elseif (stripos( $user_agent, 'Trident') !== false) {
		   echo "/* Internet Explorer 8, 9, 10, 11, edge */\n" . $internet_explorer;
		}
		elseif (stripos( $user_agent, 'Edge') !== false) {
		   echo "/* Internet Explorer 8, 9, 10, 11, edge */\n" . $internet_explorer;
		}
		elseif (stripos( $user_agent, 'Chrome') !== false) {
			echo "/* Chrome */\n" . $chrome;
		}
		elseif (stripos( $user_agent, 'Safari') !== false) {
		   echo "/* Safari */\n" . $safari;
		}
		elseif (stripos( $user_agent, 'Firefox') !== false) {
		   echo "/* Firefox */\n" . $firefox;
		}
		else {
			echo "/* User Agent Not Defined */";
		}
	
	}
	
	?>
	
	/*@media (max-width: 1920px) {
		<?php //echo $extra_large; ?>
	}*/
	
	/*@media (max-width: 1600px) {
		<?php //echo $large_1; ?>
	}*/
	
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
		<?php echo $generic_mobile; ?>
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

<?php
if($collapsed_mobile_submenu == 'on'):

//Screen Width Trigger
$cmxb_screen_trigger = get_option('wjmc_screen_trigger');

if( empty($cmxb_screen_trigger) ) {
	$cmxb_screen_trigger = '980';
}
?>

<style type="text/css">
@media (max-width: <?php echo $cmxb_screen_trigger; ?>px) {
	.icon_img {
		float: right;
		position: relative;
		width: 35px;
		height: 31px;
		/* background: url('<?php echo plugins_url(); ?>/xbrowser-compatibility/img/roll.png'); */
		background-position: 5px 5px;
		cursor: pointer;
	}
	.icon_img:before {
		content: "\f345";
		font-family: "dashicons";
		left: 10px;
		position: relative;
		top: 4px;
		font-size: 16px;
	}
	.over {
		/* background: url('<?php echo plugins_url(); ?>/xbrowser-compatibility/img/roll-over.png'); */
		background-repeat: no-repeat;
		background-position: 5px 5px;
		cursor: pointer;
	}
	.over:before {
		content: "\f347";
		font-family: "dashicons";
		left: 10px;
		position: relative;
		top: 4px;
		font-size: 16px;
	}
}
</style>

<script type='text/javascript'>
var cmxb_extra = jQuery.noConflict();

function extra_script_cmxb() {
	
	cmxb_extra('body').css('overflow', 'hidden');
	var static_width = cmxb_extra(window).width();
	cmxb_extra('body').css('overflow', 'auto');
	
	if( static_width <= <?php echo $cmxb_screen_trigger; ?> ) {
		cmxb_extra('.sub-menu, .sub-menu .sub-menu, .et_mobile_menu li ul, .et_mobile_menu li ul li ul').attr('style', 'display: none!important;');
		cmxb_extra('.sub-menu, .sub-menu .sub-menu, .et_mobile_menu li ul, .et_mobile_menu li ul li ul').attr('style', 'display: none!important;');
	}
	
	var resizeTimeout;
	
	cmxb_extra(window).resize(function(){
		
		if( !!resizeTimeout ){
			clearTimeout(resizeTimeout);
		}
		
		resizeTimeout = setTimeout(function(){
			
			cmxb_extra('body').css('overflow', 'hidden');
			var resized_width = cmxb_extra(this).width();
			cmxb_extra('body').css('overflow', 'auto');
			
			if( resized_width <= <?php echo $cmxb_screen_trigger; ?> ) {
				cmxb_extra('.sub-menu, .sub-menu .sub-menu, .et_mobile_menu li ul, .et_mobile_menu li ul li ul').attr('style', 'display: none!important;');
				cmxb_extra('.service_blurb').attr('style', 'height: auto;');
			}
			else {
				cmxb_extra('.sub-menu, .sub-menu .sub-menu, .et_mobile_menu li ul, .et_mobile_menu li ul li ul').attr('style', '');
			}
		
		}, 100);
		
	});
	
}
cmxb_extra(window).load(extra_script_cmxb);
</script>
<?php endif; ?>