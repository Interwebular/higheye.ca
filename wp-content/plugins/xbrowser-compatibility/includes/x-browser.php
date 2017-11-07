<?php
function main_xbrowser_options() {
	
	if( $_POST['is_submit']== '1' ) {
		
		//Define sessions
		$_SESSION['checkbox_x'] = $_POST['checkbox-x'];
		$_SESSION['checkbox_1'] = $_POST['checkbox1'];
		$_SESSION['checkbox_2'] = $_POST['checkbox2'];
		
		//Scroll Events
		$_SESSION['chrome_event'] = $_POST['chrome_event'];
		$_SESSION['firefox_event'] = $_POST['firefox_event'];
		$_SESSION['ie_event'] = $_POST['ie_event'];
		$_SESSION['safari_event'] = $_POST['safari_event'];
		$_SESSION['general_event'] = $_POST['general_event'];
		//$_SESSION['extra_large_event'] = $_POST['extra_large_event'];
		//$_SESSION['large_1_event'] = $_POST['large_1_event'];
		$_SESSION['large_event'] = $_POST['large_event'];
		$_SESSION['medium_event'] = $_POST['medium_event'];
		$_SESSION['small_event'] = $_POST['small_event'];
		$_SESSION['generic_mobile_event'] = $_POST['generic_mobile_event'];
		$_SESSION['ipad_event'] = $_POST['ipad_event'];
		$_SESSION['nexus_event'] = $_POST['nexus_event'];
		$_SESSION['ipod_event'] = $_POST['ipod_event'];
		
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
		
		echo "
		<div class='updated save-info'>
			<p>CSS Options Saved!</p>
		</div>
		";
		
	}
	
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
	$large_1 = stripslashes(get_option('wjmc_large_1'));
	$extra_large = stripslashes(get_option('wjmc_extra_large'));
	
	//Define New Cross Browser Settings
	$chrome = stripslashes(get_option('wjmc_chrome'));
	$firefox = stripslashes(get_option('wjmc_firefox'));
	$internet_explorer = stripslashes(get_option('wjmc_internet_explorer'));
	$safari = stripslashes(get_option('wjmc_safari'));
	
	//Cache Override
	$cache_override = get_option('wjmc_cache_override');
	
	//Toggle Hide / Show Options
	$toggle_show_hide = get_option('wjmc_toggle_show_hide');
	
	//Require the CodeMirror Script. Needed to include it this way so that the CodeMirror's scrollTo function will work with PHP Sessions :\
	require('codemirror-script.php');
	x_browser_main_script();
?>
<div class="wrap">

	<h2>XBrowser CSS Options <?php echo XB_PLUGIN_VERSION; ?> <span class="change_log"><a href="<?php echo XB_PLUGIN_DIR_URL; ?>changelog.txt?version=<?php echo XB_PLUGIN_VERSION; ?>" target="_blank">View Change log</a></span></h2>
	
	<div id="main-xbrowser-page" style="background: url(<?php echo XB_PLUGIN_DIR_URL . 'img/strip-lines.png'; ?>);">
	
	<?php
	//Define reactivation of the xBrowser plugin to enable activation hook when upgrading from 1.3.4 and onwards
	if( strcasecmp(get_option('wjmc_plugin_reactivate'), XB_PLUGIN_VERSION) != 0 ) {
		require('xb-reactivate.php');
	}
	else { ?>
	
	<h4 class="xbrowser_head_title">CSS Options</h4>
	
		<form action="" id="css_options_form" method="post" class="xBrowser_form <?php if($toggle_show_hide == 'on') { echo "xBrowser_form_show_all"; } ?>">
			<div id="xbrowser_fields"></div>
			<p class="override_cache">Override Cache <input id="checkbox-cache" type="checkbox" name="cache_override" <?php if( $cache_override == 'on' ): ?>checked<?php endif; ?>/> <span class="override_cache_info"><?php if( $cache_override == 'on' ): ?>Enabled<?php else: ?>Disabled<?php endif; ?></span><br /><span>Enable this option if using a caching plugin.</span><br /><span><b>Note:</b> This applies to the <b>xBrowser User Agents</b> CSS options.</span></p>
			<p class="show-hide" <?php if($toggle_show_hide == 'on') { echo 'style="background-color: #D6DAFF;"'; }?>><?php if($toggle_show_hide == null): ?>Show / Hide <?php endif;?><span class="xbrowser_field">xBrowser</span> User Agents <?php if($toggle_show_hide == null): ?><input type="checkbox" id="checkbox-x" name="checkbox-x" <?php if( $_SESSION['checkbox_x'] == 'on' ): ?>checked<?php endif; ?>/><?php endif; ?></p>
			<div id="autoUpdate-x" class="autoUpdate-x">
				<fieldset>
					<legend>Chrome<br /><span style="font-weight: normal;">User Agent: Chrome</span></legend>
					<textarea id="chrome" name="chrome" placeholder="Enter CSS for Chrome"><?php echo $chrome; ?></textarea>
					<input id="chrome_event" type="hidden" name="chrome_event"></input>
				</fieldset>
				<fieldset>
					<legend>Mozilla Firefox<br /><span style="font-weight: normal;">User Agent: Firefox</span></legend>
					<textarea id="firefox" name="firefox" placeholder="Enter CSS for Firefox"><?php echo $firefox; ?></textarea>
					<input id="firefox_event" type="hidden" name="firefox_event"></input>
				</fieldset>
				<fieldset>
					<legend>Internet Explorer 8, 9, 10, 11, Edge<br /><span style="font-weight: normal;">User Agent: MSIE, Trident</span></legend>
					<textarea id="internet_explorer" name="internet_explorer" placeholder="Enter CSS for Internet Explorer"><?php echo $internet_explorer; ?></textarea>
					<input id="ie_event" type="hidden" name="ie_event"></input>
				</fieldset>
				<fieldset>
					<legend>Safari<br /><span style="font-weight: normal;">User Agent: Safari</span></legend>
					<textarea id="safari" name="safari" placeholder="Enter CSS for Safari"><?php echo $safari; ?></textarea>
					<input id="safari_event" type="hidden" name="safari_event"></input>
				</fieldset>
			</div>
			
			<div style="clear:both;"></div>
			<hr>
			<div style="clear:both;"></div>
			
			<div id="desktop_fields"></div>
			<p class="show-hide" <?php if($toggle_show_hide == 'on') { echo 'style="background-color: #D6DAFF;"'; }?>><?php if($toggle_show_hide == null): ?>Show / Hide <?php endif;?><span class="xb_desktop_field">Desktop</span> Resolutions <?php if($toggle_show_hide == null): ?><input type="checkbox" id="checkbox1" name="checkbox1" <?php if( $_SESSION['checkbox_1'] == 'on' ): ?>checked<?php endif; ?>/><?php endif; ?></p>
			<div id="autoUpdate" class="autoUpdate">
				<div style="clear:both;"></div>
				<fieldset>
					<legend>General</legend>
					<textarea id="general" name="general" placeholder="Enter CSS for all screen resolutions"><?php echo $general; ?></textarea>
					<input id="general_event" type="hidden" name="general_event"></input>
				</fieldset>
				<!--<fieldset>
					<legend>Max-width: 1920px</legend>
					<textarea id="extra_large" name="extra_large" placeholder="Enter CSS for 1920px screen width"><?php //echo $extra_large; ?></textarea>
					<input id="extra_large_event" type="hidden" name="extra_large_event"></input>
				</fieldset>-->
				<!--<fieldset>
					<legend>Max-width: 1600px</legend>
					<textarea id="large_1" name="large_1" placeholder="Enter CSS for 1600px screen width"><?php //echo $large_1; ?></textarea>
					<input id="large_1_event" type="hidden" name="large_1_event"></input>
				</fieldset>-->
				<fieldset>
					<legend>Max-width: 1366px</legend>
					<textarea id="large" name="large" placeholder="Enter CSS for 1366px screen width"><?php echo $large; ?></textarea>
					<input id="large_event" type="hidden" name="large_event"></input>
				</fieldset>
				<fieldset>
					<legend>Max-width: 1288px</legend>
					<textarea id="medium" name="medium" placeholder="Enter CSS for 1288px screen width"><?php echo $medium; ?></textarea>
					<input id="medium_event" type="hidden" name="medium_event"></input>
				</fieldset>
				<fieldset>
					<legend>Max-width: 1024px</legend>
					<textarea id="small" name="small" placeholder="Enter CSS for 1024px screen width"><?php echo $small; ?></textarea>
					<input id="small_event" type="hidden" name="small_event"></input>
				</fieldset>
			</div>
			
			<div style="clear:both;"></div>
			<hr>
			<div style="clear:both;"></div>
			
			<div id="mobile_fields"></div>
			<p class="show-hide" <?php if($toggle_show_hide == 'on') { echo 'style="background-color: #D6DAFF;"'; }?>><?php if($toggle_show_hide == null): ?>Show / Hide <?php endif;?><span class="xb_mobile_field">Mobile</span> Resolutions <?php if($toggle_show_hide == null): ?><input type="checkbox" id="checkbox2" name="checkbox2" <?php if( $_SESSION['checkbox_2'] == 'on' ): ?>checked<?php endif; ?>/><?php endif; ?></p>
			<div id="autoUpdate_mobile" class="autoUpdate_mobile">
				<fieldset>
					<legend>Max-width: 980px</legend>
					<textarea id="generic_mobile" name="generic_mobile" placeholder="Enter CSS for 980px screen width"><?php echo $generic_mobile; ?></textarea>
					<input id="generic_mobile_event" type="hidden" name="generic_mobile_event"></input>
				</fieldset>
				<fieldset>
					<legend>Max-width: 773px</legend>
					<textarea id="ipad" name="ipad" placeholder="Enter CSS for 773px screen width"><?php echo $ipad; ?></textarea>
					<input id="ipad_event" type="hidden" name="ipad_event"></input>
				</fieldset>
				<fieldset>
					<legend>Max-width: 601px</legend>
					<textarea id="nexus" name="nexus" placeholder="Enter CSS for 601px screen width"><?php echo $nexus; ?></textarea>
					<input id="nexus_event" type="hidden" name="nexus_event"></input>
				</fieldset>
				<fieldset>
					<legend>Max-width: 480px</legend>
					<textarea id="ipod" name="ipod" placeholder="Enter CSS for 480px screen width"><?php echo $ipod; ?></textarea>
					<input id="ipod_event" type="hidden" name="ipod_event"></input>
				</fieldset>
			</div>
			
			<div style="clear:both;"></div>
			<hr>
			<div style="clear:both;"></div>
			
			<input type="hidden" value="1" name="is_submit" />
			<input class="button button-primary" type="submit" value="Save CSS Options"/>
			<input class="right_submit" type="image" src="<?php echo XB_PLUGIN_DIR_URL . 'img/save-icon-long.jpg'; ?>" title="Save CSS Options"/>
		</form>
	</div>
	<!--
	<div id="xbrowser-css-overrides" style="background: url(<?php echo XB_PLUGIN_DIR_URL . 'img/strip-lines.png'; ?>);">
	<h4 class="xbrowser_head_title">CSS Overrides</h4>
	
	</div>
	-->
	<?php } ?>
	
</div>
<?php	
}