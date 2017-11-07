<?php
//Begin settings script
function xb_global_jquery_lib_settings() {
	
	if($_POST) {
		
		update_option('wjmc_jquery_lib', $_POST['jquery_lib']);
		$jquery_lib = get_option('wjmc_jquery_lib');
		
		echo "
		<div class='updated save-info-settings'>
			<p>Global jQuery Lib updated to version <span style='color: #ff0000;'>" . $jquery_lib . "</span></p>
		</div>
		";
		
	}
	
	$jquery_lib = get_option('wjmc_jquery_lib');
	
	if($jquery_lib == null) {
		$jquery_lib = '1.12.4';
	}
?>
<div class="wrap">

	<h2>XBrowser Global jQuery Lib <?php echo XB_PLUGIN_VERSION; ?> <span class="change_log"><a href="<?php echo XB_PLUGIN_DIR_URL; ?>changelog.txt?version=<?php echo XB_PLUGIN_VERSION; ?>" target="_blank">View Change log</a></span></h2>
	
	<div id="x-browser-global-jquery-lib-settings" style="background: url(<?php echo XB_PLUGIN_DIR_URL . 'img/strip-lines.png'; ?>);">
	
	<?php
	//Define reactivation of the xBrowser plugin to enable activation hook when upgrading from 1.3.4 and onwards
	if( strcasecmp(get_option('wjmc_plugin_reactivate'), XB_PLUGIN_VERSION) != 0 ) {
		require('xb-reactivate.php');
	}
	else { ?>
	
		<h4 class="xbrowser_head_title">jQuery Libraries</h4>
		
		<form class="jquery_lib_form" action="" method="post">
			<input class="jquery_lib_input" type="radio" name="jquery_lib" value="1.12.4" <?php if($jquery_lib == '1.12.4'){ echo 'checked'; } ?>>jQuery Lib <b>1.12.4</b> <b style="font-style: italic;">(Default)</b><span><?php if($jquery_lib == '1.12.4'){ echo 'Enabled'; }else{ echo 'Disabled'; } ?></span><br />
			<input class="jquery_lib_input" type="radio" name="jquery_lib" value="2.2.4" <?php if($jquery_lib == '2.2.4'){ echo 'checked'; } ?>>jQuery Lib <b>2.2.4</b><span><?php if($jquery_lib == '2.2.4'){ echo 'Enabled'; }else{ echo 'Disabled'; } ?></span><br />
			<input class="jquery_lib_input" type="radio" name="jquery_lib" value="3.1.0" <?php if($jquery_lib == '3.1.0'){ echo 'checked'; } ?>>jQuery Lib <b>3.1.0</b><span><?php if($jquery_lib == '3.1.0'){ echo 'Enabled'; }else{ echo 'Disabled'; } ?></span><br />
			<p class="jquery_lib_note"><b>Note:</b> jQuery Lib will be loaded at the <b>front-end</b> page header.</p>
			<hr>
			<input class="button button-primary" type="submit" value="Apply & Load jQuery Lib on Front-end" />
		</form>
		
		<div style="clear:both;"></div>
		
	<?php } ?>
		
	</div>
</div>
<?php } ?>