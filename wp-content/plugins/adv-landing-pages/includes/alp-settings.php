<?php
function alp_settings_page() {
	
	//Include the manual updater class
	include(ALP_BASEPATH . 'lib/manual-plugin-updater/manual-plugin-updater.php');
	
	//Instantiate the class
	new manualPluginUpdater(
		ALP_PLUGIN_VERSION,	//String: Current plugin version in string format: 1.1.1
		ALP_BASEPATH,	//String: Current plugin base path in string format like: home\public_html\root\wp-content\plugins\plugin-dir-name/
		'file',	//String: File upload POST method name attribute like <input type="file" name="file"/> where "file" is the name attribute.
		'adv-landing-pages'	//String: Plugin dir name like "plugin-dir-name".
	);
	
	//Get the settings database
	$alp_settings = get_option("alp_settings");
	
	?>
	<div class="wrap">
	
	<h2>Advanced Landing Page Settings <span class="alp_version_txt"><?php echo ALP_PLUGIN_VERSION; ?></span></h2>
	
		<div id="alp-settings" style="background: url('<?php echo ALP_BASEURL . 'images/strip-lines-overlay.jpg'; ?>');">
		
		<?php
		//Define reactivation to enable activation hook when upgrading/updating/installing a new version of the plugin
		if( strcasecmp(get_option('alp_plugin_reactivate'), ALP_PLUGIN_VERSION) != 0 ) {
			require('alp-reactivate.php');
		}
		else { ?>
		
			<div id="iframe_changelog" class="iframe_changelog"></div>
		
			<div class="alp_settings_wrapper" style="border-left: 3px solid #00a0d2;">
			
				<h4 class="alp_head_title"><span class="dashicons dashicons-admin-plugins" style="display: inline-block;"></span> Advance Landing Page Manual Plugin Update</h4>
				<span>Upload the plugin in zipped format. This will overwrite the existing plugin and replaces it with the updated plugin.</span>
				<br />
				<form action="" method="post" enctype="multipart/form-data">
					<input type="file" name="file"/>
					<input type="hidden" value="1" name="upload"/>
					<input class="button button-primary" type="submit" value="Upload and update plugin" />
				</form>
				
			</div>
			
			<div class="alp_settings_wrapper">
				<form id="alp_settings" method="post">
					<h4 class="alp_head_title"><span class="dashicons dashicons-admin-generic"></span> Settings</h4>
					<p class="show-hide" style="border-left: 3px solid #FF9800;">Enable Sendpepper API <span style="font-style: italic; font-weight: normal; color: #FF5722;">(Deprecated)</span> <input class="alp_settings_input" type="checkbox" name="alp_settings[sendpepper_api]" <?php if($alp_settings['sendpepper_api'] == 'on'){ echo "checked"; } ?>/> <span class="info-alp-settings"><?php if($alp_settings['sendpepper_api'] == 'on'){ echo "Currently <span>Enabled</span>"; }else{ echo "Currently <span>Disabled</span>"; } ?></span> <span class="info_api_settings">This will enable <b>Sendpepper API</b> at the <b>advance landing page options</b> in the <b>page editor</b>.</span></p>
					<input type="hidden" value="1" name="save_settings">
					<input class="button button-primary" type="submit" value="Save Settings">
				</form>
			</div>
			
		<?php } ?>
			
		</div>
		
	</div>
	<?php
}