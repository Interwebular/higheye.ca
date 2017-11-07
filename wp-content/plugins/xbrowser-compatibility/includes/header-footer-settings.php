<?php
//Begin settings script
function xb_header_footer_settings() {
	
	if( $_POST['header-footer-save'] == 1 ) {
		
		update_option('wjmc_header_footer', $_POST['header_footer_script']);
		
		echo "
		<div class='updated save-info'>
			<p>Header and Footer Settings Saved!</p>
		</div>
		";
		
	}
	
	//Get Header and Footer script array
	$header_footer_script = get_option('wjmc_header_footer');
	
	$header_script = stripslashes( $header_footer_script['header_script'] );
	$footer_script = stripslashes( $header_footer_script['footer_script'] );
	
	//Require the CodeMirror Script. Needed to include it this way so that the CodeMirror's scrollTo function will work with PHP Sessions :\
	require('codemirror-script.php');
	xb_header_footer_settings_script();
?>
<div class="wrap">

	<h2>XBrowser Header and Footer Script <?php echo XB_PLUGIN_VERSION; ?> <span class="change_log"><a href="<?php echo XB_PLUGIN_DIR_URL; ?>changelog.txt?version=<?php echo XB_PLUGIN_VERSION; ?>" target="_blank">View Change log</a></span></h2>
	
	<div id="x-browser-header-footer-settings" style="background: url(<?php echo XB_PLUGIN_DIR_URL . 'img/strip-lines.png'; ?>);">
	
	<?php
	//Define reactivation of the xBrowser plugin to enable activation hook when upgrading from 1.3.4 and onwards
	if( strcasecmp(get_option('wjmc_plugin_reactivate'), XB_PLUGIN_VERSION) != 0 ) {
		require('xb-reactivate.php');
	}
	else { ?>
	
		<h4 class="xbrowser_head_title">Header and Footer Script</h4>
		
		<form class="header_footer_script" action="" method="post">
			<fieldset class="header_script">
				<legend>
					Header Script
					<br /><span style="font-weight: normal;"><b>Note:</b> Script is placed before the &lt;&frasl;head&gt; tag.</span>
				</legend>
				<textarea id="head-script" name="header_footer_script[header_script]" placeholder="Enter header script"><?php echo $header_script; ?></textarea>
			</fieldset>
			<fieldset class="footer_script">
				<legend>
					Footer Script
					<br /><span style="font-weight: normal;"><b>Note:</b> Script is placed before the &lt;&frasl;body&gt; tag.</span>
				</legend>
				<textarea id="footer-script" name="header_footer_script[footer_script]" placeholder="Enter footer script"><?php echo $footer_script; ?></textarea>
			</fieldset>
			
			<div style="clear:both;"></div>
			<hr>
			<div style="clear:both;"></div>
			<input type="hidden" name="header-footer-save" value="1"/>
			<input class="button button-primary" type="submit" value="Save Header and Footer Settings" />
		</form>
		
		<div style="clear:both;"></div>
		
	<?php } ?>
		
	</div>
</div>
<?php } ?>