<?php
//Begin settings script
function xb_settings() {
	
	//Define info save
	$settings_info = "";
	
	//String version num to Int version num
	$xb_plugin_version = explode('.', XB_PLUGIN_VERSION);
	$version_current_int = intval( implode('', $xb_plugin_version) );

	//Define file info
	$uploaded_file = $_FILES['file'];
	$file_name = $uploaded_file['name'];
	$file_format = $uploaded_file['type'];

	if( !empty($file_name) ) {
		
		//Include Wordpress File Handler
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		
		//Define upload override for wp_handle_upload()
		$upload_overrides = array( 'test_form' => false );
		
		//Handle uploaded file
		$movefile = wp_handle_upload( $uploaded_file, $upload_overrides );
		
		//Define uploaded file path
		$zipped_file_path = $movefile['file'];

		//Open archive and then read content of version.txt
		$version_target_string = @file_get_contents('zip://'. $zipped_file_path .'#xbrowser-compatibility/version.txt');
		
		//String version num to Int version num
		$target_string_exploded = explode('.',$version_target_string);
		$version_target_int = intval( implode('', $target_string_exploded) );
		
		//Begin Checking
		if ($version_current_int == $version_target_int) {
			
			//Deletes the file uploaded
			unlink($zipped_file_path);
			
			echo "
			<div class='error save-info-settings'>
				<p>Update Failed! Plugin is already up to date. No need to update!</p>
			</div>
			";

		}
		elseif ( ($version_current_int < $version_target_int) && ($file_format == 'application/x-zip-compressed') ) {
			
			//Define uncompression path
			$dir = ABSPATH . "wp-content/plugins/";
			
			WP_Filesystem();
			$unzipfile = unzip_file( $zipped_file_path, $dir);
			
			//Checks if destination path is writable
			if ( wp_is_writable( $dir ) ){
				
				if ( $unzipfile ) {
					//Deletes the file uploaded
					unlink($zipped_file_path);
					
					echo "
					<div class='updated save-info-settings'>
						<p>Successfully updated the Plugin!</p>
					</div>
					";
					?>
						<script>
							jQuery(document).ready(function(){
								setTimeout(function(){
								window.location.replace('<?php echo $_SERVER['HTTP_REFERER']; ?>');
								}, 2000);
							});
						</script>
					<?php
				
				}
				else {
					//Deletes the file uploaded
					unlink($zipped_file_path);
					
					echo "
					<div class='error save-info-settings'>
						<p>Update Failed! Zip file extraction is not Enabled.</p>
					</div>
					";
					
				}
				
			}
			else {
				
				//Deletes the file uploaded
				unlink($zipped_file_path);
				
				echo "
				<div class='error save-info-settings'>
					<p>Update Failed! File permissions for the file path: <b class='error_info_writable'>" . $dir . "</b> is incorrect!</p>
				</div>
				";
			
			}
		   
		}
		elseif ( ($version_target_int == 0) && ($file_format != 'application/x-zip-compressed') ) {
			//Deletes the file uploaded
			@unlink($zipped_file_path);
			
			echo "
			<div class='error save-info-settings'>
				<p>Incorrect Plugin File! Please upload the correct Plugin File in Zipped format.</p>
			</div>
			";

		}
		elseif ( ($version_target_int == 0) && ($file_format == 'application/x-zip-compressed') ) {
			//Deletes the file uploaded
			unlink($zipped_file_path);
			
			echo "
			<div class='error save-info-settings'>
				<p>Incorrect Plugin File! Please upload the correct Plugin File in Zipped format.</p>
			</div>
			";
			
		}
		elseif ($version_current_int > $version_target_int) {
			//Deletes the file uploaded
			unlink($zipped_file_path);
			
			echo "
			<div class='error save-info-settings'>
				<p>Update Failed! The plugin uploaded is older than the current plugin installed.</p>
			</div>
			";
			
		}
		else {
			
			echo "
			<div class='error save-info-settings'>
				<p>Incorrect Plugin File! Please upload the correct Plugin File in Zipped format.</p>
			</div>
			";
			
		}
	}
	else {
		
		if( $_POST['upload'] == '1') {
			
			$settings_info .= "
			<div class='error save-info-settings'>
				<p>No File is Choosen! Please choose a file to upload.</p>
			</div>
			";
			
		}
		
	}
	
	//Begin plugin settings
	if($_POST['save_settings']== '1') {
		
		update_option('wjmc_hide_mobile_submenu', $_POST['hide_mobile_submenu']);
		update_option('wjmc_toggle_show_hide', $_POST['toggle_show_hide']);
		update_option('wjmc_debug_mode', $_POST['debug_mode']);
		update_option('wjmc_ajax_saving_mode', $_POST['xb_ajax_saving_mode']);
		
		$cmxb_screen_trigger = $_POST['cmxb_screen_trigger'];
		$manual_pos = $_POST['manual_icon_pos'];
		$mobile_menu_id_value = $_POST['mobile_menu_id'];
		$mobile_menu_class_value = $_POST['mobile_menu_class'];
		
		//Discard escape character
		$mobile_menu_id_value = stripslashes($mobile_menu_id_value);
		$mobile_menu_id_value = stripslashes($mobile_menu_id_value);
		
		//Turn the 'id' parameter into an array
		$mobile_menu_id_value = explode( ",", $mobile_menu_id_value );
		$mobile_menu_class_value = explode( ",", $mobile_menu_class_value );
		
		//Define invalid characters
		$invalid_chars = array( "@", "/", "!", "=", "+", "$", "%", "^", "&", "(", ")", ".", "#", "*", ":", ";", "'", '"', "|", "?", "{", "}", "[", "]", "`", "~", "<", ">", " " );
		
		//Replace invalid characters
		$mobile_menu_id_value = str_replace( $invalid_chars, array(), $mobile_menu_id_value );
		$mobile_menu_class_value = str_replace( $invalid_chars, array(), $mobile_menu_class_value );
		
		$manual_pos = preg_replace('/[^-0-9]/', '', $manual_pos);
		$cmxb_screen_trigger = preg_replace('/[^0-9]/', '', $cmxb_screen_trigger);
		
		//Finally save it to the database
		update_option('wjmc_mobile_menu_id', $mobile_menu_id_value);
		update_option('wjmc_mobile_menu_class', $mobile_menu_class_value);
		update_option('wjmc_icon_manual_pos', $manual_pos);
		update_option('wjmc_screen_trigger', $cmxb_screen_trigger);
		
		echo "
		<div class='updated save-info-settings'>
			<p>Settings Saved!</p>
		</div>
		";
		
	}
	
	//Define DB Query
	global $wpdb;
	$table = $wpdb->base_prefix . 'options';
	
	//Define collapsed menu defaults
	$collapsed_mobile_submenu = get_option('wjmc_hide_mobile_submenu');

	//Define collapsed menu defaults
	$plugin_query_cm_row = $wpdb->get_results("SELECT * from $table where option_name like '%wjmc_hide_mobile_submenu%'", OBJECT);
	foreach($plugin_query_cm_row as $option_name) { $plugin_option_cm = $option_name->option_name; }
	
	if($plugin_option_cm == null) {
		$collapsed_mobile_submenu = 'on';
	}
	
	//Enable AJAX Saving Mode
	$ajax_saving_mode = get_option('wjmc_ajax_saving_mode');
	
	//Define AJAX Saving Mode defaults
	$plugin_query_ajax_row = $wpdb->get_results("SELECT * from $table where option_name like '%wjmc_ajax_saving_mode%'", OBJECT);
	foreach($plugin_query_ajax_row as $option_name) { $plugin_option_ajax = $option_name->option_name; }
	
	if($plugin_option_ajax == null) {
		$ajax_saving_mode = 'on';
	}
	
	//Screen Width Trigger
	$cmxb_screen_trigger = get_option('wjmc_screen_trigger');
	
	//Mobile Menu ID and Class
	$mobile_menu_id_value = get_option('wjmc_mobile_menu_id');
	$mobile_menu_class_value = get_option('wjmc_mobile_menu_class');
	
	//Manual Icon Positioning
	$manual_pos = get_option('wjmc_icon_manual_pos');

	//Toggle Hide / Show Options
	$toggle_show_hide = get_option('wjmc_toggle_show_hide');
	
	//Debug Mode
	$debug_mode = get_option('wjmc_debug_mode');
	
?>
<div class="wrap">

	<h2>XBrowser Settings <?php echo XB_PLUGIN_VERSION; ?></h2>
	
	<?php echo $settings_info; ?>
	
	<div id="x-browser-settings" style="background: url(<?php echo XB_PLUGIN_DIR_URL . 'img/strip-lines.png'; ?>);">
	
		<?php
		//Define reactivation of the xBrowser plugin to enable activation hook when upgrading from 1.3.4 and onwards
		if( strcasecmp(get_option('wjmc_plugin_reactivate'), XB_PLUGIN_VERSION) != 0 ) {
			require('xb-reactivate.php');
		}
		else { ?>
		
			<div id="iframe_changelog" class="iframe_changelog"></div>
			
			<div class="form-wrapper">
			<h4 class="xbrowser_head_title">xBrowser Manual Plugin Update</h4>
			<span>Upload the plugin in zipped format. This will overwrite the existing plugin and replaces it with the updated plugin.</span>
			<br />
				<form action="" method="post" enctype="multipart/form-data">
				<input type="file" name="file"/>
				<input type="hidden" value="1" name="upload"/>
				<input class="button button-primary" type="submit" value="Upload and update plugin" />
				</form>
			</div>

			<hr>
			
			<div class="xbrowser_settings_wrapper">
			
			<h4 class="xbrowser_head_title">Settings</h4>
			
				<form method="post">
				
				<p class="show-hide" style="border-left: 3px solid #00a0d2;">Enable Ajax Saving <input class="xb_settings_input" type="checkbox" name="xb_ajax_saving_mode" <?php if($ajax_saving_mode == 'on'){ echo "checked"; } ?>/> <span class="info-submenu-mobile"><?php if($ajax_saving_mode == 'on'){ echo "Currently <span>Enabled</span>"; }else{ echo "Currently <span>Disabled</span>"; } ?></span> <span class="debug_field">This will enable <b>Ajax saving</b> at the <b>CSS Options</b> plugin page. <em><b>(Default: Enabled)</b></em></span></p>
				
				<!-- <p class="show-hide" style="border-left: 3px solid #00a0d2;">Debug Mode <input class="xb_settings_input" type="checkbox" name="debug_mode" <?php if($debug_mode == 'on'){ echo "checked"; } ?>/> <span class="info-submenu-mobile"><?php if($debug_mode == 'on'){ echo "Currently <span>Enabled</span>"; }else{ echo "Currently <span>Disabled</span>"; } ?></span> <span class="debug_field">This will enable debug mode that will show any traces of errors in the whole Wordpress Dasboard.</span></p> -->
				
				<p class="show-hide" style="border-left: 3px solid #FFA500;">Show All Options in the Main Options Page <input class="xb_settings_input" type="checkbox" name="toggle_show_hide" <?php if($toggle_show_hide == 'on'){ echo "checked"; } ?>/> <span class="info-submenu-mobile"><?php if($toggle_show_hide == 'on'){ echo "Currently <span>Enabled</span>"; }else{ echo "Currently <span>Disabled</span>"; } ?></span></p>
				
				<p class="show-hide" style="border-left: 3px solid #FFA500;">Collapsed <span class="collapsed_field">Sub-Menu</span> on Mobile <input class="xb_settings_input" type="checkbox" name="hide_mobile_submenu" <?php if($collapsed_mobile_submenu == 'on'){ echo "checked"; } ?>/> <span class="info-submenu-mobile"><?php if($collapsed_mobile_submenu == 'on'){ echo "Currently <span>Enabled</span>"; }else{ echo "Currently <span>Disabled</span>"; } ?></span> | <span class="collapsed_field">Screen Width Trigger</span> <input class="cmxb_screen_trigger" type="text" name="cmxb_screen_trigger" value="<?php echo $cmxb_screen_trigger; ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"/> <span class="cmxb_field_note">Default: <b>980px</b>. Screen width trigger for mobile layout of a theme.</span><br /><br/><span id="mopbile_menu_id">Click Here To Show Advanced Options</span></p>
				<div id="collapsed_label_show" class="show-hide" style="border-left: 3px solid #40C368;">
					
					<table>
						<tr>
							<td>Mobile Menu <span class="cmxb_field_label">ID</span></td>
							<td><input style="width: 210px;" type="text" name="mobile_menu_id" placeholder="Enter mobile menu ID" value="<?php if( !empty($mobile_menu_id_value[0]) ) { echo implode(", ", $mobile_menu_id_value); } ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"/> <span class="cmxb_field_note">Enter CSS ID's to apply the collapsed menu. <b>ID's are separated by commas.</b></span></td>
						</tr>
						<tr>
							<td>Mobile Menu <span class="cmxb_field_label">Class</span></td>
							<td><input style="width: 210px;" type="text" name="mobile_menu_class" placeholder="Enter mobile menu class(es)" value="<?php if( !empty($mobile_menu_class_value[0]) ) { echo implode(", ", $mobile_menu_class_value); } ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"/> <span class="cmxb_field_note">Enter CSS Class(es) to apply the collapsed menu. <b>Classes are separated by commas.</b></span></td>
						</tr>
					</table>
					
					<p>Manually Position Icons <span class="manual_pos_bg">{ margin-top: <input class="manual_pos" type="text" name="manual_icon_pos" value="<?php if( $manual_pos != null ){ echo $manual_pos; }?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"/><span style="font-weight: bold;">px; }</span></p>
					<p>How to find the <span style="background-color: #A6FCFF;">Mobile Menu ID</span>:</p>
					<div class="guide_wrapper">
						<ul class="guide_mobile_nav">
							<li>Open "developer tool" in the browser by pressing <span style="background-color: #fff000; font-weight: bold;">F12</span> on the keyboard or open browser options.</li>
							<li>Change the resolution to <span style="background-color: #fff000; font-weight: bold;">980px width</span> on the custom resolutions fields.</li>
							<li>Click the navigation button to display the mobile dropdown menu.</li>
							<li>Inspect the mobile dropdown menu and make sure to inspect the parent <span style="background-color: #fff000; font-weight: bold;">&lt;ul&gt;</span> tag. (As an example, see screenshot.)</li>
							<li>Note the ID and then place it on the field above.(In the screenshot example, the ID should be <span style="background-color: #fff000; font-weight: bold;">mobile_menu</span>)</li>
						</ul>
						<p>NOTE: The same can be applied if the parent element is placed with a CSS Class rather than the CSS ID.</p>
					</div>
					<img src="<?php echo XB_PLUGIN_DIR_URL; ?>/img/ul_tag_example.png"/>
				</div>
				
				<hr>
				
				<input type="hidden" value="1" name="save_settings">
				<input class="button button-primary" type="submit" value="Save Settings">
				
				</form>
			
			</div>
			
		<?php } ?>
		
	</div>
	
</div>
<?php } ?>