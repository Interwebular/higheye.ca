<?php
//Load Admin CSS
function xbrowser_admin_css() {
	
	/* There needs to declare the CSS version using a url parameter in order to apply the updated CSS file accross the plugin. */
	?>
	
	<link rel='stylesheet' href='<?php echo XB_PLUGIN_DIR_URL; ?>css/admin-css.css?version=<?php echo XB_PLUGIN_VERSION; ?>' type='text/css' media='all' />
	<style type="text/css">
		<?php
		//Toggle Hide / Show Options
		$toggle_show_hide = get_option('wjmc_toggle_show_hide');
		
		if($toggle_show_hide == 'on') {
			echo "
			#autoUpdate, #autoUpdate_mobile, #autoUpdate-x {
				display: block!important;
			}
			";
		}
		?>
	</style>
	
	<?php
}

//Load Admin JS
function xbrowser_admin_js() {
	
	?>
	
	<script type="text/javascript">
		var toggle_content = function() {
			
			jQuery('#checkbox-x').change(function () {
				jQuery('#autoUpdate-x').slideToggle('fast');
			});
		
			jQuery('#checkbox1').change(function () {
				jQuery('#autoUpdate').slideToggle('fast');
			});

			jQuery('#checkbox2').change(function () {
				jQuery('#autoUpdate_mobile').slideToggle('fast');
			});
			
			jQuery('#mopbile_menu_id').click(function () {
				jQuery('#collapsed_label_show').slideToggle('fast');
			});
			
			//Define display toggle based on sessions
			<?php if( $_SESSION['checkbox_x'] ): ?>
			jQuery('#autoUpdate-x').attr('style', 'display: block;');
			
			//Auto scroll to the textarea fields when saving
			jQuery(window).scrollTop(
				( jQuery('#xbrowser_fields').offset().top ) - 40
			);
			<?php endif; ?>
			
			<?php if( $_SESSION['checkbox_1'] ): ?>
			jQuery('#autoUpdate').attr('style', 'display: block;');
			
			//Auto scroll to the textarea fields when saving
			jQuery(window).scrollTop(
				( jQuery('#desktop_fields').offset().top ) - 40
			);
			<?php endif; ?>
			
			<?php if( $_SESSION['checkbox_2'] ): ?>
			jQuery('#autoUpdate_mobile').attr('style', 'display: block;');
			
			//Auto scroll to the textarea fields when saving
			jQuery(window).scrollTop(
				( jQuery('#mobile_fields').offset().top ) - 40
			);
			<?php endif; ?>
			
			//Load an iframe to the settings page.
			jQuery('#iframe_changelog').append('<p id="changelog-label">Changelog</p><iframe src="<?php echo XB_PLUGIN_DIR_URL; ?>changelog.txt?version=<?php echo XB_PLUGIN_VERSION; ?>"/>');
			
			//Add a fade effect popup save info message
			<?php if( XB_CURRENT_PAGE == 'xbrowser-compatibility' && ( $_SESSION['checkbox_x'] || $_SESSION['checkbox_1'] || $_SESSION['checkbox_2'] ) ): ?>
			jQuery('#main-xbrowser-page').append('<div id="save-popup-info">Options Saved!</div>');
			
			jQuery('#save-popup-info').css({
				top: ( ( jQuery(window).height() / 2 ) - jQuery('#save-popup-info').height() - 50 ), 
				left: ( ( jQuery(window).width() / 2 ) - ( jQuery('#save-popup-info').width() / 2 ) )
			});

			setTimeout(function() {
				jQuery('#save-popup-info').fadeOut();
			}, 1500);
			<?php else: ?>
			setTimeout(function() {
			   jQuery('.save-info').fadeOut();
		   }, 7000);
			<?php endif; ?>
			
			jQuery('#checkbox-x').css('display', 'inline-block');
			jQuery('#checkbox1').css('display', 'inline-block');
			jQuery('#checkbox2').css('display', 'inline-block');
			
			/*
			jQuery(window).on('scroll', function() {
				jQuery('#css_fields').each(function() {
					if( jQuery(window).scrollTop() >= jQuery(this).offset().top ) {
						jQuery('.right_submit').css('display', 'block');
					}
				});
			}); */
			
			<?php if(get_option('wjmc_toggle_show_hide') == 'on'): ?>
				jQuery('.right_submit').css('display', 'block');
			<?php endif; ?>
			
			//Detect if slideToggle is triggered and then display the save icon
			jQuery('#checkbox-x, #checkbox1, #checkbox2').change(function () {
				jQuery('.right_submit').css('display', 'block');
			});
			
			if( jQuery('#checkbox-x:checked, #checkbox1:checked, #checkbox2:checked')[0] != null ) {
				jQuery('.right_submit').css('display', 'block');
			}
		   
		}
		
		// Launch function after window load.
		jQuery(window).load(toggle_content);
	</script>
	
	<?php
	
	//Unset session variables
	unset( $_SESSION['checkbox_x'] );
	unset( $_SESSION['checkbox_1'] );
	unset( $_SESSION['checkbox_2'] );
	unset( $_SESSION['chrome_event'] );
	unset( $_SESSION['firefox_event'] );
	unset( $_SESSION['ie_event'] );
	unset( $_SESSION['safari_event'] );
	unset( $_SESSION['general_event'] );
	//unset( $_SESSION['extra_large_event'] );
	//unset( $_SESSION['large_1_event'] );
	unset( $_SESSION['large_event'] );
	unset( $_SESSION['medium_event'] );
	unset( $_SESSION['small_event'] );
	unset( $_SESSION['generic_mobile_event'] );
	unset( $_SESSION['ipad_event'] );
	unset( $_SESSION['nexus_event'] );
	unset( $_SESSION['ipod_event'] );
	
}

//Load Code Mirror Library
function load_code_mirror_lib() {
	
	?>
	
	<link rel="stylesheet" href="<?php echo XB_PLUGIN_DIR_URL; ?>lib/ambiance.css">
	<link rel="stylesheet" href="<?php echo XB_PLUGIN_DIR_URL; ?>lib/codemirror.css">
	
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>lib/codemirror.js"></script>
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>mode/css/css.js"></script>
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>mode/javascript/javascript.js"></script>
    <script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/edit/matchbrackets.js"></script>
    <script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/edit/closebrackets.js"></script>
    <script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/display/autorefresh.js"></script>
    <script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/selection/active-line.js"></script>
    <script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/display/placeholder.js"></script>
    <script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/selection/selection-pointer.js"></script>
	<!-- <script src="<?php //echo $plugin_path; ?>lib/script.js"></script> -->
	
	<!-- Lint Lib -->
	<link rel="stylesheet" href="<?php echo XB_PLUGIN_DIR_URL; ?>addon/lint/lint.css">
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/lint/lint.js"></script>
	
	<!-- CSS Lint Lib -->
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/lint/css-lint.js"></script>
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/lint/css-lint-lib.js"></script>
	
	<!-- Javascript Lint Lib -->
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/lint/jshint.js"></script>
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/lint/jsonlint.js"></script>
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/lint/javascript-lint.js"></script>
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>addon/lint/json-lint.js"></script>
	
	<!-- HTML Mixed -->
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>mode/htmlmixed/htmlmixed.js"></script>
	
	<!-- XML Mixed -->
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>mode/xml/xml.js"></script>
	
	<!-- VB Script -->
	<script src="<?php echo XB_PLUGIN_DIR_URL; ?>mode/vbscript/vbscript.js"></script>
	
	<?php
}

//Begin error report/display
function error_report() {

	echo "<div style='width: 89%; float: right;'><pre>";
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	echo "</pre></div>";

}

//Define global Debug Mode variable
function debug_mode() {
	
	global $debug_mode;
	$debug_mode = get_option('wjmc_debug_mode');
	
}

function css_options_ajax_script(){
	?>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			
			jQuery('#css_options_form').submit(function(event){
				
				jQuery('#xb_ajax_overlay_save').css('display', 'block');
				
				event.preventDefault();
				
				var form = jQuery('#css_options_form').serialize();
				//var form_chrome = chrome_global_val();
				
				jQuery.ajax({
					url:'<?php echo admin_url("admin-ajax.php"); ?>',
					type:'POST',
					dataType: "json",
					data:'action=css_options_ajax&'+ form,
					error:function(data_1){ console.log(data_1) },
					success:function(data){
						
						//console.log( data['xb_ajax_post_array'] );
						
						if(data['xb_ajax_post_array']['cache_override'] == 'on') {
							jQuery('.override_cache_info').html('Enabled');
						}
						else {
							jQuery('.override_cache_info').html('Disabled');
						}
						
						jQuery('#xb_ajax_overlay_save').css('display', 'none');
						
						jQuery('#main-xbrowser-page').append('<div id="save-popup-info">Options Saved!</div>');
						jQuery('#save-popup-info').css({
							top: ( ( jQuery(window).height() / 2 ) - jQuery('#save-popup-info').height() - 50 ), 
							left: ( ( jQuery(window).width() / 2 ) - ( jQuery('#save-popup-info').width() / 2 ) )
						});
						setTimeout(function() {
							jQuery('#save-popup-info').fadeOut();
						}, 1500);
						setTimeout(function() {
							jQuery('#save-popup-info').remove();
						}, 2000);
						
					}
				});
			});
			
			var overlay_height = jQuery(window).height();
			jQuery('body').append('<div id="xb_ajax_overlay_save"><p>Saving...</p></div>');
			jQuery('#xb_ajax_overlay_save p').css('top', (overlay_height/2) - 50 + 'px');
			
		});
		</script>
	<?php
}