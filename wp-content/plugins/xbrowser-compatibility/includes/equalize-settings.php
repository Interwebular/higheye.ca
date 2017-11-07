<?php
function xb_equalize_settings() {
	
	if( isset($_POST['equalize_container_height']) ) {
		
		$xb_equalize = $_POST['xb_equalize'];
		$wjmc_screen_trigger_equalize = $_POST['wjmc_screen_trigger_equalize'];
		
		//Discard escape character
		$xb_equalize = stripslashes($xb_equalize);
		
		//Turn the 'id' parameter into an array
		$xb_equalize = explode( ",", $xb_equalize );
		
		//Remove whitespaces rom each array item
		$xb_equalize = array_filter( array_map('trim', $xb_equalize) );
		
		//Define invalid characters
		$invalid_chars_class = array( "@", "/", "!", "=", "+", "$", "%", "^", "&", "(", ")", "*", ";", "'", '"', "|", "?", "{", "}", "[", "]", "`", "~", "<", ">" );
		
		//Replace invalid characters
		$xb_equalize = str_replace( $invalid_chars_class, array(), $xb_equalize );
		$wjmc_screen_trigger_equalize = preg_replace('/[^0-9]/', '', $wjmc_screen_trigger_equalize);
		
		//Finally save it to the database
		update_option('wjmc_xb_equalize', $xb_equalize);
		update_option('wjmc_screen_trigger_equalize', $wjmc_screen_trigger_equalize);
		
		echo "
		<div class='updated save-info'>
			<p>Equalize Settings Saved!</p>
		</div>
		";
		
	}
	
	if( isset($_POST['add_se']) || isset($_POST['update_se']) ) {
		
		$special_equalize = $_POST['special-equalize'];
		
		if( !empty($special_equalize['container_1'][0]) && !empty($special_equalize['container_2'][0]) ) {
			
			//Get the screen width array items and convert strings into integers
			$screen_width_post = $special_equalize['screen_width'];
			$screen_width_post = implode(',', $screen_width_post);
			$screen_width_post = array_map('intval', explode(',', $screen_width_post));
			
			//Add it back to the array
			$special_equalize['screen_width'] = $screen_width_post;
			
			//Finally save it to the database
			update_option('wjmc_special_equalize', $special_equalize);
			
			if( isset($_POST['add_se']) ) {
				echo "
				<div class='updated save-info'>
					<p>Special Equalize Added!</p>
				</div>
				";
			}
			
			if( isset($_POST['update_se']) ) {
				echo "
				<div class='updated save-info'>
					<p>Special Equalize Updated!</p>
				</div>
				";
			}
			
		}
		else {
			
			echo "
			<div class='error save-info'>
				<p>Special Equalize not added. Both container fields must not be empty!</p>
			</div>
			";
			
		}
		
	}
	
	//Get Special Equalize Array
	$special_equalize = get_option('wjmc_special_equalize');
	
	//Count Array Items from one of the special equalize container array
	$count_se = count( $special_equalize['container_1'] );
	
	//Do the checks
	if( !empty($special_equalize['container_1'][0]) && !empty($special_equalize['container_2'][0]) ) {
		
		//Define counters
		$i = 1;
		$s = 0;
		
		//Start the loop
		while( $i <= $count_se ) {
			
			if( isset($_POST['se_delete_' . $i]) ) {
				
				//Deletes the specified array item
				unset( $special_equalize['container_1'][$s] );
				unset( $special_equalize['container_2'][$s] );
				unset( $special_equalize['screen_width'][$s] );
				
				//Recontstruct the array keys
				$array_container_1 = array_values($special_equalize['container_1']);
				$array_container_2 = array_values($special_equalize['container_2']);
				$array_screen_width = array_values($special_equalize['screen_width']);
				
				//Re-define the array
				$container_1 = array();
				$container_1['container_1'] = $array_container_1;
				
				$container_2 = array();
				$container_2['container_2'] = $array_container_2;
				
				$screen_width = array();
				$screen_width['screen_width'] = $array_screen_width;
				
				//Merge it to a single array
				$special_equalize = array_merge($container_1, $container_2, $screen_width);
				
				//Finally save it to the database
				update_option('wjmc_special_equalize', $special_equalize);
				
				//Get Special Equalize Array again due to delete update of the code above
				$special_equalize = get_option('wjmc_special_equalize');
				
				//Count Array Items again due to delete update
				$count_se = count( $special_equalize['container_1'] );
				
				echo "
				<div class='updated save-info'>
					<p>Special Equalize Deleted!</p>
				</div>
				";
				
			}
			$i++;
			$s++;
			
		}
		
	}
	
	//Equalize container height
	$xb_equalize = get_option('wjmc_xb_equalize');
	
	//Screen Width Trigger
	$wjmc_screen_trigger_equalize = get_option('wjmc_screen_trigger_equalize');
	
	//Initialize codemirror script
	require('codemirror-script.php');
	xb_equalize_settings_script();
?>

<div class="wrap">
	<h2>XBrowser Equalize Height Settings <?php echo XB_PLUGIN_VERSION; ?> <span class="change_log"><a href="<?php echo XB_PLUGIN_DIR_URL; ?>changelog.txt?version=<?php echo XB_PLUGIN_VERSION; ?>" target="_blank">View Change log</a></span></h2>
	
	<div id="xb-qualize-settings-page" style="background: url(<?php echo XB_PLUGIN_DIR_URL . 'img/strip-lines.png'; ?>);">
	
	<?php
	//Define reactivation of the xBrowser plugin to enable activation hook when upgrading from 1.3.4 and onwards
	if( strcasecmp(get_option('wjmc_plugin_reactivate'), XB_PLUGIN_VERSION) != 0 ) {
		require('xb-reactivate.php');
	}
	else { ?>
	
	<h4 class="xbrowser_head_title">Equalize Height Settings</h4>
	
		<form method="post" class="equalize_container_height">
			<div class="show-hide equalize_field">
				<fieldset>
					<legend>Equalize Container Height</legend>
					<textarea id="equalize" name="xb_equalize" placeholder="Enter CSS Class / ID's or HTML Elements"><?php if( !empty($xb_equalize[0]) ) { echo implode(", ", $xb_equalize); } ?></textarea>
				</fieldset>
				<span class="equalize_note">Enter <b>CSS Class / ID's</b> or <b>HTML Elements</b> that are separated by commas. <b>Example: .my-class, .my-class p, #my-id p</b></span><br />
				<span class="equalize_note_important"><b>Important Note:</b> <span>If using ID's, do not use an ID alone. Instead, use it as a target selector. </span>Example: #my-div p</span><br />
				<span class="collapsed_field">Screen Width Trigger</span> <input class="cmxb_screen_trigger" type="text" name="wjmc_screen_trigger_equalize" value="<?php echo $wjmc_screen_trigger_equalize; ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"/> <span class="cmxb_field_note">Default: <b>980px</b>. Screen width trigger is the <b>mimimum</b> width trigger to <b>disable</b> equalize height.</span>
				<br />
				<input id="ech_button" class="button button-primary" type="submit" name="equalize_container_height" value="Save Equalize Settings"/>
		<hr style="margin: 20px 0;">
		</form>
		<form method="post">
				<div id="add_special_equalize">Add Special Equalize</div>
				<div id="special_equalize"></div>
				
				<?php if( !empty($special_equalize['container_1'][0]) && !empty($special_equalize['container_2'][0]) ): ?>
				<p style="margin-bottom: 0;">Special Equalize Entries</p>
				<table id="special_equalize_entries">
					<tr>
						<th>Container 1</th>
						<th></th>
						<th>Container 2</th>
						<th>Screen Width Trigger</th>
						<th>Action</th>
					</tr>
					<?php $a = 1; $b = 0; while( $a <= $count_se ): ?>
					<tr>
						<td><input type="text" name="special-equalize[container_1][]" value="<?php echo $special_equalize['container_1'][$b]; ?>"/></td>
						<td style="text-align: center;"> = </td>
						<td><input type="text" name="special-equalize[container_2][]" value="<?php echo $special_equalize['container_2'][$b]; ?>"/></td>
						<td><input type="text" name="special-equalize[screen_width][]" value="<?php echo $special_equalize['screen_width'][$b]; ?>"/></td>
						<td><input class="button button-primary update_se" type="submit" name="update_se" value="Update"/><input class="ech_del" type="submit" name="se_delete_<?php echo $a; ?>" value="Delete"/></td>
					</tr>
					<?php $a++; $b++; endwhile; ?>
				</table>
				<p><span class="equalize_note"><b>NOTE:</b> <b>Height</b> of <b>Container 1</b> will be applied to <b>Container 2.</b> Screen width trigger is the <b>mimimum</b> width trigger to <b>disable</b> equalize height.</span></p>
				<?php endif; ?>
				<div id="css_fields"></div>
			</div>
		</form>
	
	</div>
	
</div>

<div id="se_hidden_html" style="display: none;">
	<div id="se_html_contents">
		<table>
			<tr>
				<th>Container 1</th>
				<th></th>
				<th>Container 2</th>
				<th>Screen Width Trigger</th>
				<th></th>
			</tr>
			<tr>
				<td><input type="text" name="special-equalize[container_1][]"/></td>
				<td> = </td>
				<td><input type="text" name="special-equalize[container_2][]"/></td>
				<td><input type="text" name="special-equalize[screen_width][]"/></td>
				<td><input class="button button-primary" type="submit" name="add_se" value="Add"/></td>
			</tr>
		</table>
		<span class="equalize_note">Enter <b>CSS Class / ID's</b> or <b>HTML Elements</b>. Note that the height of <b>Container 1</b> will be applied to <b>Container 2</b>.</span><br />
		<span class="equalize_note_important"><b>Important Note:</b> <span>If using ID's, do not use an ID alone. Instead, use it as a target selector. </span>Example: #my-div p</span>
	</div>
</div>
	<?php } ?>

<?php } ?>