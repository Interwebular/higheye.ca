<?php
/**
 * Plugin Name: Advanced Landing Page
 * Plugin URI: http://wordjack.com/
 * Description: Create landing pages using a normal page, and create form easily ideal of specials/offer pages.
 * Version: 4.1.1
 * Author: Edesa Cabang (Wordjack Team) maintained by Virson Ebillo
 * Author URI: https://virson.wordpress.com/
 */

//Silently hide errors
error_reporting(0);
@ini_set('display_errors', 0);

//Load Wordpress core get_plugins function if it was not loaded
if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

//Define Constants
define('ALP_PLUGIN_VERSION', get_plugins( '/adv-landing-pages' )['adv-landing-pages.php']['Version']);
define('ALP_MetaBoxId', 'landingpage-metabox');
define('ALP_BASEURL', preg_replace('/\s+/', '', plugin_dir_url(__FILE__)));
define('ALP_BASEPATH', preg_replace('/\s+/', '', plugin_dir_path(__FILE__)));
define('ALP_CURRENT_PAGE', $_GET['page']);
define('ALP_PLUGIN_HOST_FILE', 'https://dl.dropboxusercontent.com/u/90662976/WP%20Plugins/Adv%20Landing%20Page/alp-version-host/alp-version-host.txt');
define('ALP_PLUGIN_VERSION_DB', get_option('alp_host_version'));
define('ALP_PLUGIN_CHANGELOG_HOST', 'https://dl.dropboxusercontent.com/u/90662976/WP%20Plugins/Adv%20Landing%20Page/alp-version-host/alp-change-log-host.txt');

/* ---------------------- Advance Landing Page Cron Jobs ------------------------- */
//Begin plugin activation hook
register_activation_hook(__FILE__, 'alp_activation');

function alp_activation() {
	
	//Read file with read and write permissions
	$handle = fopen( ALP_BASEPATH . 'version.txt', 'w+' );
	
	//Write the version string to the file
	fwrite( $handle, ALP_PLUGIN_VERSION);
	
	//Closes the file that was opened.
	fclose( $handle );
	
	//Set initial time to 3,600 seconds (1 hour)
	update_option('alp_schedule_event', time() + 3600);
	
	//Get plugin version content from external source (Dropbox)
	update_option('alp_host_version', @file_get_contents( ALP_PLUGIN_HOST_FILE ));
	
}

//Begin plugin deactivation hook
register_deactivation_hook(__FILE__, 'alp_deactivation');
function alp_deactivation() {
	
	//For the old scheduled Advance Landing Page Cron Job
	wp_clear_scheduled_hook('alp_schedule_event');
	
	//Delete option on deactivate
	delete_option('alp_schedule_event');
	
}

//Load function first-hand on each visit to Wordpress admin pages
add_action('init', 'alp_run_event');
function alp_run_event() {
	
	//Begin checking
	if(get_option('alp_schedule_event')) {
		if(time() > intval(get_option('alp_schedule_event'))) {
			
			//Get plugin version content from external source (Dropbox)
			update_option('alp_host_version', @file_get_contents( ALP_PLUGIN_HOST_FILE ));
			
			//Store the new timestamp again with an additional 3,600 seconds (1 hour)
			update_option('alp_schedule_event', time() + 3600);
		}
	}
	
}

add_action('admin_head', 'alp_version_checker');
function alp_version_checker() {
	
	//Had to do it this way because doing the empty() function on a constant will result in a T_PAAMAYIM_NEKUDOTAYIM or double colon (::) error in PHP
	$alp_plugin_version_db = ALP_PLUGIN_VERSION_DB;

	if( !empty( $alp_plugin_version_db ) ) {
		
		//String version num to Int version num
		$host_version = explode('.', ALP_PLUGIN_VERSION_DB);
		$host_version = intval( implode('', $host_version) );
		
		//String version num to Int version num
		$alp_plugin_version = explode('.', ALP_PLUGIN_VERSION);
		$alp_plugin_version = intval( implode('', $alp_plugin_version) );
		
		if( $alp_plugin_version < $host_version ) {
			
			echo "
			<script type='text/javascript'>
			jQuery(document).ready(function(){
				var update_html = jQuery('#alp_update_notif');
				jQuery('#alp_update_notif').remove();
				jQuery('.wrap').prepend(update_html);
			});
			</script>
			<div id='alp_update_notif' class='update-nag'>
				<span style='font-weight: normal;'><b>Advanced Landing Page</b> plugin version <pan style='color: #F44336; font-weight: 600;'>" . ALP_PLUGIN_VERSION_DB . "</span> is now available. <a href='" . ALP_PLUGIN_CHANGELOG_HOST . "' target='_blank'>Click here</a> to view the <b>change log</b>.</span>
			</div>
			";
			
		}
		
	}
	
	//Define reactivation to enable activation hook when upgrading/updating/installing a new version of the plugin
	if( strcasecmp(get_option('alp_plugin_reactivate'), ALP_PLUGIN_VERSION) != 0 && ALP_CURRENT_PAGE != 'alp-settings') {
		require(ALP_BASEPATH . 'includes/alp-reactivate.php');
	}
	
}
/* ---------------------- End of Advance Landing Page Cron Jobs ------------------------- */

//Define gobal warning message when no ga_commands(Google Analytics) is added
add_action('admin_head', 'ga_commands');

function ga_commands(){
	
	if( get_option('alp_ga_commands')[0] == 'false' ) {
		echo "
			<script type='text/javascript'>
			jQuery(document).ready(function(){
				var ga_commands_html = jQuery('#alp_ga_commands_notif');
				jQuery('#alp_ga_commands_notif').remove();
				jQuery('.wrap').prepend(ga_commands_html);
			});
			</script>
			<div id='alp_ga_commands_notif' class='update-nag'>
				<span style='font-weight: 600; color: #ff0000;'>Warning: </span><b>Google Analytics Tracking Code</b> is not added in this website.<br />
				Please add the tracking code using header/footer script embedder and test the <b>Advance Landing Page</b> <a href='" . get_option('alp_ga_commands')[1] . "' target='_blank'>form (click to view page)</a> again to solve this problem.
			</div>
		";
	}
	
}

//Define Ajax Advance Landing Page Reactivate
function alp_reactivate() {
	
	if($_POST['alp_reactivate'] == 1) {
		deactivate_plugins( 'adv-landing-pages/adv-landing-pages.php' );
		activate_plugin( 'adv-landing-pages/adv-landing-pages.php' );
		update_option('alp_plugin_reactivate', ALP_PLUGIN_VERSION);
		$alpr_json['alp_reactivate'] = true;
	}
	
	print_r( json_encode($alpr_json) );
	exit;
	
}

//Load ajax action hook for reactivation module
add_action('wp_ajax_alp_reactivate_ajax', 'alp_reactivate');

//Include action links on the plugins page
add_filter( 'plugin_action_links', 'alp_action_links', 10, 5 );
function alp_action_links( $actions, $plugin_file ) {
	
	static $plugin;
	
	if (!isset($plugin)) {
		$plugin = plugin_basename(__FILE__);
	}
	if ($plugin == $plugin_file) {
			$settings = array('settings' => '<a href="options-general.php?page=alp-settings">' . __('Settings', 'Advance Landing Page') . '</a>');
			$actions = array_merge($settings, $actions);
	}
	return $actions;
	
}

//Load ALP functions
include_once('includes/meta-box-class.php');
include_once('includes/functions.php');

//Load function scripts
require('includes/function-scripts.php');

//Load action hooks
add_action('admin_head', 'alpAdminStylesAndScripts');
add_action('wp_head', 'alpStylesAndScripts');
add_action('wp_footer', 'alpBlogStylesAndScripts');

//Begin loading specific Scripts/CSS at the main plugin admin page
if(ALP_CURRENT_PAGE == 'alp-settings') {
	
	//Load Admin CSS
	add_action('admin_footer', 'alp_settings_css');

}

//Add settings page
add_action( 'admin_menu', 'add_alp_settings_page' );
function add_alp_settings_page() {
	
	add_options_page('Advance Landing Page Settings', 'ALP Settings', 'administrator', 'alp-settings', 'alp_settings_page');
	require('includes/alp-settings.php');
	
}

add_action('admin_head', 'alp_page_thumbnail', 10);

function alp_page_thumbnail() {
    add_meta_box('postimagediv', 'Featured Image', 'post_thumbnail_meta_box', 'page', 'side', 'low');
}

new LandingPageMeta('landingpage-metabox', array('page'), array(
  'title' => 'Landing Page Options',
  'context' => 'normal',
));

add_shortcode('adv-quote', 'adv_quote');

function adv_quote(){
    global $post;
    $quote = get_post_meta($post->ID, 'landingpage-metabox_formcomponents_quote', true); 

    $q = '<div class="quote ' . $quote['color'] . '">';
    
    $q.= ($quote['photo']) ? '<img src="' . $quote['photo'] . '" alt="photo" class="quote-photo"/>' : '';
    $q.=  '<div class="content">' . $quote['content'] . '</div><div class="attribution">' . $quote['attribution'] . '</div>';
    $q.= '</div>';
    return $q;
}

wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script('alp-scripts', ALP_BASEURL . 'js/scripts.js');
wp_enqueue_script('alp-website-scripts', ALP_BASEURL . 'js/front-scripts.js');

add_filter ('the_content', 'advLandingPageFilter');

//Front-end Post method
function advLandingPageFilter($content) {
	
    global $post;

    $metaBoxId = 'landingpage-metabox';
    $data = get_post_meta($post->ID, $metaBoxId, true);

    if (isset($_POST) && $_POST['action'] == 'thank-you') {
        $message .= '';
        if (is_array($_POST['short-text'])) {
            foreach ($_POST['short-text'] as $idx => $userData) {
                    $message .= "\n" . $idx . ' : ' . $userData;
            }
        }

        if (is_array($_POST['long-text'])) {
            foreach ($_POST['long-text'] as $idx => $userData) {
                    $message .= "\n" . $idx . ' : ' . $userData;
            }
        }

        $notifyEmail = $data['alp-recepient-email'];

        if ($data['layout'] == 'user-form') {
                $subject = 'Inquiry from ' . get_permalink($post->ID);
        }
        else if  ($data['layout'] == 'download') {
                $subject = 'Download submitted at ' . get_permalink($post->ID);		
        }
        else {
                $subject = 'Inquiry from ' . get_permalink($post->ID);
        }

        $fromEmail = ( $data['from-email']) ?  $data['from-email'] : $data['from-name'];
        $headers = 'From: ' . $data['from-name'] . ' <' . $data['from-email'] . '>' . "\r\n";	
        
        if (wp_mail($notifyEmail, $subject, $message)) {
                //echo 'Sending notifi... to ' . $notifyEmail . ' <br />' . $subject . '<br />' . $message;
        }
        else {
                //echo 'Email not sent to ' . $notifyEmail . ' <br />' . $subject . '<br />' . $message;
        }

        $replaceForm = '<div>' . $data['thank-you-msg'] . '</div>';

    }

    $data2 = get_post_meta($post->ID, $metaBoxId . '_formcomponents', true);

    $formComponentsTextBoxShort = get_post_meta($post->ID, $metaBoxId . '_formcomponents_textbox_short', true);
    $formComponentsTextBoxLong = get_post_meta($post->ID, $metaBoxId . '_formcomponents_textbox_long', true);
    $formComponentsTextBoxPhone = get_post_meta($post->ID, $metaBoxId . '_formcomponents_textbox_phone', true);        
    //$formComponentsRadioButton = get_post_meta($post->ID, $metaBoxId . '_formcomponents_radio_button', true);
    //$formComponentsCheckbox = get_post_meta($post->ID, $metaBoxId . '_formcomponents_checkbox', true);
    //$formComponentsDropdown = get_post_meta($post->ID, $metaBoxId . '_formcomponents_dropdown', true);

    $formComponentsRadioButtonLabel = get_post_meta($post->ID, $metaBoxId . '_formcomponents_radioButtonLabel', true);
    $formComponentsRadioButtonChoices = get_post_meta($post->ID, $metaBoxId . '_formcomponents_radioButtonChoices', true);

    $formComponentsCheckboxLabel = get_post_meta($post->ID, $metaBoxId . '_formcomponents_checkboxLabel', true);
    $formComponentsCheckboxChoices = get_post_meta($post->ID, $metaBoxId . '_formcomponents_checkboxChoices', true);

    $formComponentsDropdownLabel = get_post_meta($post->ID, $metaBoxId . '_formcomponents_dropdownLabel', true);
    $formComponentsDropdownChoices = get_post_meta($post->ID, $metaBoxId . '_formcomponents_dropdownChoices', true);

    $fields = array('textbox-short'	=> $formComponentsTextBoxShort,			
                    'textbox-phone'	=> $formComponentsTextBoxPhone,
                    'radio-button'	=> array('label' => $formComponentsRadioButtonLabel, 'choices' => $formComponentsRadioButtonChoices),
                    'checkbox'	=> array('label' => $formComponentsCheckboxLabel, 'choices' => $formComponentsCheckboxChoices),
                    'dropdown'	=> array('label' => $formComponentsDropdownLabel, 'choices' => $formComponentsDropdownChoices),
                    'textbox-long'	=> $formComponentsTextBoxLong,
                    );
    if ($data['enable-landing-page'] != 'on') {
            return $content;	
    }

    if ($data['layout'] == 'user-form') {
            return alpGetNewContentUserForm($content, $data, $fields, $replaceForm);
    }

    else if ($data['layout'] == 'call-now') {
            return alpGetNewContentCallNow($content, $data);
    }

    else if ($data['layout'] == 'download') {
            return alpGetNewDownloadForm($content, $data, $fields, $replaceForm);
    }
    else if ($data['layout'] == 'print-coupon') {
            return alpGetNewPrintCoupon($content, $data, array('postId' => $post->ID));
    }    
}

function alpGetNewDownloadForm($content, $data = array(), $elements = array(), $replaceForm = '') {
	return alpGetNewContentUserForm($content, $data, $elements, '', true);
}

/**
 * Returns the new content for the landing page.
 *
 * @param $content the post content
 * @param $data = array() the post landing page meta
 * @param $elements = array() the elements to be added on the form
 */
function alpGetNewContentUserForm($content, $data = array(), $elements = array(), $replaceForm = '', $download = false) {
	
	global $post;
	if ($data['use-custom-template-page'] == 'on') {
		$newContent = '<div class="alp-body"><h1 class="alp-header">' . get_the_title($post->ID) . '</h1><h2 class="alp-sub-header">' . $data['sub-header'] . '</h2>';
	}
	else {
		$newContent = '<div class="alp-body"><h2 class="alp-sub-header">' . $data['sub-header'] . '</h2>';
	}
	$data2 = get_post_meta($post->ID, ALP_MetaBoxId . '_formcomponents', true);
	$formElements = '';
        
	if (!is_array($elements)) {
		$elements = array();
	}

	foreach ($elements as $idx => $fields) {
		if (!is_array($fields)) {
			continue;
		}

		// Add checkbox
		if ($idx == 'checkbox') {
			$appendRequired = '';
			if (is_array($fields['label']) && $data2['include-checkboxes'] == 'on') {
				foreach ($fields['label'] as $idx => $label) {
					$options = explode("\r\n", $fields['choices'][$idx]);
					foreach ($options as $opt) {
						if ($opt != '') {
							$checkboxOptions .= '<input type="checkbox" name="cb[' . $label . '][' . $opt . ']" value="' . $opt . '" /> ' . $opt . '<br />';
						}
					}
					$formElements .= '<p><label>' . $label . '</label>' . $checkboxOptions . '</p>';
				}
			}
		}
		else if ($idx == 'radio-button') {
			$appendRequired = '';
			if (is_array($fields['label']) && $data2['include-radio-buttons'] == 'on') {
				foreach ($fields['label'] as $idx => $label) {
					$requiredClass = 'no-required';
					if (alpStartsWith($label, '[Req]')) {
						$requiredClass = 'required';
						$label = substr($label, 5);
						$appendRequired = ' *';
					}
					$options = explode("\r\n", $fields['choices'][$idx]);
					foreach ($options as $opt) {
						if ($opt != '') {
							$radioOptions .= '<input class="' . $requiredClass . '" type="radio" name="radio[' . $label . ']" value="' . $opt . '"> ' . $opt . '<br />';
						}
					}
					$formElements .= '<p><label>' . $label . $appendRequired . '</label>' . $radioOptions . '</p>';
				}
			}
		}
		else if ($idx == 'dropdown') {
			$appendRequired = '';
			if (is_array($fields['label']) && $data2['include-drop-down-menu'] == 'on') {
				foreach ($fields['label'] as $idx => $label) {
					$requiredClass = 'no-required';
					if (alpStartsWith($label, '[Req]')) {
						$requiredClass = 'required';
						$label = substr($label, 5);
						$appendRequired = ' *';
					}

					$options = explode("\r\n", $fields['choices'][$idx]);
					foreach ($options as $opt) {
						if ($opt != '') {
							$selectOptions .= '<option value="' . $opt . '">' . $opt . '</option>';
						}
					}
					$formElements .= '<p><label>' . $label . $appendRequired. '</label><select class="' . $requiredClass . '" name="dp[' . $label . ']"><option>' . $selectOptions . '</option></select></p>';
				}
			}
		}
		else {
		foreach ($fields as $field) {
			if ($field == '') {
				continue;
			}
			$requiredClass = 'no-required';
			if (alpStartsWith($field, '[Req]')) {
				$requiredClass = 'required';
				$field = substr($field, 5) . ' *';
			}
			// Add text field elements
			if ($idx == 'textbox-short' && $data2['include-text-box-short'] == 'on') {
				$formElements .= '<p id="' . $field  . '"><label>' . $field . '</label><input class="' . $requiredClass . '" type="text" name="short-text[' . $field . ']" /></p>';
			}
			
			if ($idx == 'textbox-phone' && $data2['include-text-phone'] == 'on') {
				$formElements .= '<p id="' . $field  . '"><label>' . $field . '</label><input class="' . $requiredClass . ' phone1" type="text" name="phone[' . $field . '][1]" /> <input class="' . $requiredClass . ' phone2" type="text" name="phone[' . $field . '][2]" /> <input class="' . $requiredClass . ' phone3" type="text" name="phone[' . $field . '][3]" /></p>';
			}
			

			// Add textarea
			if ($idx == 'textbox-long' && $data2['include-text-box-long'] == 'on') {
				$formElements .= '<p id="' . $field  . '"><label>' . $field . '</label><textarea class="' . $requiredClass . '" name="long-text[' . $field . ']" /></textarea></p>';
			}
		}
		}

	}
        //display: none!important; visibility:hidden!important; opacity:0!important
        $formElements .= '<p id="hp-adv" style="display: none!important; visibility:hidden!important; opacity:0!important"><input type="text" name="hp-'. $post->post_name .'" /></textarea></p>';
        
        
        $newFields = get_post_meta($post->ID, ALP_MetaBoxId . '_formcomponents_fields', true);           
        foreach ($newFields as $idx => $newf) {
            $type = $newf['type'];
            $fieldId = $idx;
            $label = $newf['label'];
            $isRequired = ($newf['is-required']) ? ' *' : '';
            
            $options = $newf['options'];
            
            //$formElements .= '<p id="' . $fieldId  . '"><label>' . $label . '</label></p>';            
            switch ($type) {
            case 'text':
                $formElements .= '<p><label>' . $label . $isRequired . '</label><input type="text" name="field[' . $fieldId . ']"></p>';
                break;
            case 'checkbox':
                $formElements .= '<p><label>' . $label . $isRequired . '</label>';
                $optionsArr = explode("\r\n", $options);

                foreach ($optionsArr as $opt) {
                    $formElements .= '<input type="checkbox" value="' . $opt . '" name="field[' . $fieldId . '][]" /> ' . $opt . '<br />';
                }       
		$formElements .= '</p>';
                break;
            case 'radio':
                $formElements .= '<p><label>' . $label . $isRequired . '</label>';
                $optionsArr = explode("\r\n", $options);

                foreach ($optionsArr as $opt) {
                    $formElements .= '<input type="radio" value="' . $opt . '" name="field[' . $fieldId . ']" /> ' . $opt . '<br />';
                }                
                //$ret = '<span class="fieldtype">Radio</span><input type="text" value="' . $label . '" placeholder="label"  name="field[' . $fieldId . '][label]">';
                //$ret .= '<textarea name="field[' . $fieldId . '][options]">' . $options . '</textarea>';
                $formElements .= '</p>';
                break;
            case 'dropdown':
                $formElements .= '<p><label>' . $label . $isRequired . '</label>';
                $optionsArr = explode("\r\n", $options);
                    
                $formElements .= '<select name="field[' . $fieldId . ']">';
                foreach ($optionsArr as $opt) {
                    $formElements .= '<option value="' . $opt . '" />' . $opt . '</option>';
                }
                $formElements .= '</select></p>';
                break;
            case 'textarea':
                $formElements .= '<p><label>' . $label . $isRequired . '</label><textarea name="field[' . $fieldId . ']"></textarea></p>';
                break;
            case 'email':
                $formElements .= '<p><label>' . $label . $isRequired . '</label><input type="text" name="field[' . $fieldId . ']"></p>';
                break;
            case 'phone':
                $formElements .= '<p id="' . $fieldId  . '"><label>' . $label . $isRequired . '</label><input class="phone1" type="text" name="field[' . $fieldId . '][1]" /> <input class="phone2" type="text" name="field[' . $fieldId . '][2]" /> <input class="phone3" type="text" name="field[' . $fieldId . '][3]" /></p>';
                break;            
            }     
            
        }
	$formAction = ($data['use-thank-you-page'] == 'on') ? $data['thank-you-page'] : '';
	if ($replaceForm != '') {
		$sideForm = $replaceForm;
	}
	else {
		$isDownload = ($data['layout'] == 'download') ? 'true' : 'false';		
		$sideForm = '<p class="alp-actiontext-msg">' . $data['action-text-msg'] . '</p><div class="error-msg"></div>
				' . $formElements  . 
				'<div class="alp-submit">                                        
					<input type="hidden" name="form-action" value="thank-you" />
					<input class="submit-btn" type="submit" value="' . $data['button-text'] .'" onclick = "javascript: alpSubmitForm(' . $isDownload . ');" />
					<div class="submit-load" style="display:none;">Sending...</div>
				</div>';
	}
	$newContent .= '

			
	<div id="alp-main">
		<div class="alp-content">
		' . $content . '
		</div>
		<div class="alp-sidebar">
			<form class="alp-contact-form" onSubmit="return false" action="' . $formAction . '" method="post">
			' . $sideForm . '
		
			</form>
		</div>
	</div></div>';
        

                
	return $newContent;

}

function alpStartsWith($haystack, $needle) {
    return !strncmp($haystack, $needle, strlen($needle));
}

/**
 * Returns the new content for the landing page.
 *
 * @param $content the post content
 * @param $data = array() the post landing page meta
 */
function alpGetNewContentCallNow($content, $data = array()) {
	
	if ($data['use-custom-template-page'] == 'on') {
            $newContent = '<div class="alp-body"><h1 class="alp-header">' . get_the_title($post->ID) . '</h1><h2 class="alp-sub-header">' . $data['sub-header'] . '</h2>';
	}
	else {
            $newContent = '<div class="alp-body"><h2 class="alp-sub-header">' . $data['sub-header'] . '</h2>';
	}
	$html_callnow = "<div style='background-color: #ffffff; padding: 10px;'><p style='color: #ffffff; background-color: #ff0000; text-align: center; padding: 5px;'>Google Analytics Tracking Script is not defined!</p><p>Please contact the Website Administrator to resolve this issue.</p></div>";
	$newContent .= '
	<script>
	function ga_callNow() {
		if(typeof ga === "undefined") {
			$jx(".alp-sidebar").html("'. $html_callnow .'");
		}
		else {
			ga("send", "event", { eventCategory: "Lead", eventAction: "Call Now", eventLabel: "/' . (get_post($args['postId'])->post_name) . '/"});
		}
	}
	</script>
	<div id="alp-main">
            <div class="alp-content">
            ' . $content . '
            </div>
            <div class="alp-sidebar">
                <div class="alp-call-now-button">
                        <a ' . (( $data['button-url']) ? 'href="' .  $data['button-url'] . '"' : '') . ' type="submit" onClick="ga_callNow();">' . $data['button-text'] .'</a>
                </div>
                <p class="alp-actiontext-msg">' . $data['action-text-msg'] . '</p>
            </div>
	</div></div>';
	return $newContent;
	
}

function alpGetNewPrintCoupon($content, $data = array(), $args = array()) {
	
	if ($data['use-custom-template-page'] == 'on') {
		$newContent = '<div class="alp-body"><h1 class="alp-header">' . get_the_title($post->ID) . '</h1><h2 class="alp-sub-header">' . $data['sub-header'] . '</h2>';
	}
	else {
		$newContent = '<div class="alp-body"><h2 class="alp-sub-header">' . $data['sub-header'] . '</h2>';
	}
	$html_coupon = "<div style='background-color: #ffffff; padding: 10px;'><p style='color: #ffffff; background-color: #ff0000; text-align: center; padding: 5px;'>Google Analytics Tracking Script is not defined!</p><p>Please contact the Website Administrator to resolve this issue.</p></div>";
	$newContent .= '
	<script>
	function ga_printCoupon() {
		if(typeof ga === "undefined") {
			$jx(".alp-sidebar").html("'. $html_coupon .'");
		}
		else {
			ga("send", "event", { eventCategory: "Lead", eventAction: "Print Coupon", eventLabel: "/' . (get_post($args['postId'])->post_name) . '/"});
		}
	}
	</script>
	<div id="alp-main">
		<div class="alp-content">
		' . $content . '
		</div>
		<div class="alp-sidebar">
			<div class="alp-print-page-button">
				<a src="' . ($data['coupon-url']) . '"  slug="' . (get_post($args['postId'])->post_name) . '" type="submit" onclick="ga_printCoupon();"/>' . 
                $data['button-text'] .'</a>
			</div>
			<p class="alp-actiontext-msg">' . $data['action-text-msg'] . '</p>
		</div>
	</div></div>';

//<a target="_blank" onclick="printLandingPage(\'' . (get_post($args['postId'])->post_name) . '\')" type="submit" />' . $data['button-text'] .'</a>
	return $newContent;
//onclick="printCoupon(\'' . (get_post($args['postId'])->post_name) . '\', \'' . $data['coupon-url'] . '\')"
}

add_action('template_redirect', 'alpChangeTemplate');

function alpChangeTemplate()
{
	global $post;
	$data = get_post_meta($post->ID, ALP_MetaBoxId, true);
	if ($data['enable-landing-page'] == 'on' && $data['use-custom-template-page'] == 'on' && !is_feed()) {
		include_once(ALP_BASEPATH . 'includes/custom-template.php');
		exit;
	}

}

function myAjax1()
{
    $postId = $_POST['post-id'];
    $offer_page = get_post($postId);
    $emailLabel = 'Email : ';
    $metaBoxId = 'landingpage-metabox';
    $isDownload = $_POST['is-download'];
    $data = get_post_meta($postId, $metaBoxId, true);

    $formComponentsTextBoxPhone = get_post_meta($postId, $metaBoxId . '_formcomponents_textbox_phone', true);
    $userEmail = getShortcodeValue($data['email-field'], $_POST);
    $userName = getShortcodeValue($data['name-field'], $_POST);
    //echo 'User Name: ' . $userName;
    //exit;
    if ($userName == '') {
        $userName = $userEmail;
    }
    
    foreach ($formComponentsTextBoxPhone as $phoneField) {
            $p1 = $_POST['phone'][$phoneField][1];
            $p2 = $_POST['phone'][$phoneField][2];
            $p3 = $_POST['phone'][$phoneField][3];
            if (strlen($p1) != 3 || strlen($p2) != 3 || strlen($p3) != 4 ) {
                    $result['value'] = 'invalid_phone|' . $phoneField;   
                    print_r(json_encode($result));                        
                    exit;
            }
            if (!is_numeric($p1) || !is_numeric($p2) || !is_numeric($p3)) {
                    $result['value'] = 'invalid_phone|' . $phoneField;        		
                    print_r(json_encode($result));
                    exit;
            }
    }
	
    if ($_POST['hp-' . $offer_page->post_name] != '') {
        $result['value'] = 'spam_bots|' . 'Spambots detected.';
        print_r(json_encode($result));
        exit;
    }

    $message .= '';

    if (is_array($_POST['short-text'])) {
        foreach ($_POST['short-text'] as $idx => $userData) {


            if ($userEmailArr[0] == 'short-text' && (rtrim($userEmailArr[1], ']')) == $idx) {
                $message .= "\n" . $emailLabel . $userData . "\n";
            }
            else {
                $message .= "\n" . $idx . ': ' . $userData . "\n";
            }
			
        }
    }

    if (is_array($_POST['long-text'])) {
            foreach ($_POST['long-text'] as $idx => $userData) {
                    $message .= "\n" . $idx . ': ' . $userData . "\n";
            }
    }

    if (is_array($_POST['radio'])) {
            foreach ($_POST['radio'] as $idx => $userData) {
                    $message .= "\n" . $idx . ': ' . $userData . "\n";
            }
    }

    if (is_array($_POST['phone'])) {
        foreach ($_POST['phone'] as $idx => $userData) {
            $message .= "\n" . $idx . ': ' . $userData[1] . '-' . $userData[2] . '-' . $userData[3] . "\n";
        }
    }	

    if (is_array($_POST['dp'])) {
        foreach ($_POST['dp'] as $idx => $userData) {
            $message .= "\n" . $idx . ': ' . $userData . "\n";
        }
    }

    if (is_array($_POST['cb'])) {
        foreach ($_POST['cb'] as $idx => $userData) {
            $message .= "\n" . $idx . ': ' . implode(', ', $userData) . "\n";
        }
    }

    $newFields = get_post_meta($postId, ALP_MetaBoxId . '_formcomponents_fields', true);        
    foreach ($newFields as $idx => $newf) {
        $type = $newf['type'];
        $fieldId = $idx;
        $label = $newf['label'];
        $options = $newf['options'];
        $isRequired = $newf['is-required'];

        // validate
        if ($isRequired && $_POST['field'][$fieldId] == '') {
                $result['value'] = 'fillrequired|';        		
                print_r(json_encode($result));    
                exit;
        }
		
		// Validate email address
		if ($type== 'email') {
			$email_val = $_POST['field'][$fieldId];
			if ( !is_email($email_val) ) {
				$result['value'] = 'invalid_email|';
				print_r(json_encode($result));
				exit;
			}
		}

	
        if ($type== 'phone') {
            $p1 = $_POST['field'][$fieldId][1];
            $p2 = $_POST['field'][$fieldId][2];
            $p3 = $_POST['field'][$fieldId][3];
            if (strlen($p1) != 3 || strlen($p2) != 3 || strlen($p3) != 4 ) {
                $result['value'] = 'invalid_phone|' . $phoneField;   
                print_r(json_encode($result));                        
                exit;
            }
            if (!is_numeric($p1) || !is_numeric($p2) || !is_numeric($p3)) {
                $result['value'] = 'invalid_phone|' . $label;        		
                print_r(json_encode($result));
                exit;
            }
        }
        
        if ($type=='phone') {
            $_POST['field'][$fieldId] = $_POST['field'][$fieldId][1] . '-' . $_POST['field'][$fieldId][2].'-' . $_POST['field'][$fieldId][3] . "\n";
        }
        else if ($type == 'checkbox') {
            $_POST['field'][$fieldId] = implode(', ', $_POST['field'][$fieldId]) . "\n";
        }
        
        $userEmail = getShortcodeValue($data['email-field'], $_POST); 
        $fieldNameArr = getActualFieldName($data['email-field']);
        
        if ($fieldId == $fieldNameArr['value']) {
            $message .= "\n" . $emailLabel . $_POST['field'][$fieldId] . "\n";
        }
        else {
            $message .= "\n" . $label . ': ' . $_POST['field'][$fieldId] . "\n";
        }
    }
    
    $notifyEmail = $data['alp-recepient-email'];
    if ($data['layout'] == 'user-form') {
        $subject = 'Inquiry from ' . get_permalink($postId);
        $headers = 'From: ' . $data['from-name'] . ' <' . $data['from-email'] . '>' . "\r\n";
    }
    else if  ($data['layout'] == 'download') {
        $subject = 'Download submitted at ' . get_permalink($postId);		
        $headers = 'From: ' . $data['from-name'] . ' <' . $data['from-email'] . '>' . "\r\n";
    }
    else {
        $subject = 'Inquiry from ' . get_permalink($postId);
    }

    $fromEmail = ( $data['from-email']) ?  $data['from-email'] : $data['from-name'];

    if (wp_mail($notifyEmail, $subject, $message, $headers)) {
        //echo 'Sending notifi... to ' . $notifyEmail . ' <br />' . $subject . '<br />' . $message;
    }
    else {
        //echo 'Email not sent to ' . $notifyEmail . ' <br />' . $subject . '<br />' . $message;
    }

    $result['debug'] = 'Notify Email: ' . $notifyEmail . "\nSubject: " . $subject . "\nMessage: " . $message . "\nHeaders: " . $headers;
    
    if ($data['layout'] == 'download') {
        //echo 'Inside download!';
        $downloadUrl = $data['download-file'];
        $subject = $data['download-email-subject'];
        $message = $data['download-email'];
        
        $message = str_replace('[download-link]', get_bloginfo('wpurl') . $downloadUrl, $message);        
        $pattern = '/%(.)+%/';
        preg_match_all($pattern, $message, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[0] as $m) {
            $shortcode = $m[0];            
            $shortcode_value = getShortcodeValue($shortcode, $_POST);
            $message = str_replace($shortcode, $shortcode_value, $message);
        }
        
        if (is_array($_POST['short-text'])) {
            foreach ($_POST['short-text'] as $idx => $userData) {
                $message = str_replace('[' . $idx . ']', $userData, $message);
            }
        }	
        //echo "\nEMAIL: " . $message . "\n";
        $to = $userEmail;
        //echo 'Sending email to ' . $to;
        $result['debug'] .= "\n---\n" . 'Recipient: ' . $to . "\nSubject: " . $subject . " \nMessage: " . $message . "\nHeaders: " . $headers;
        wp_mail($to, $subject, $message, $headers, $attachments);
    }
    else {
        
        //echo 'Outside download!';
    }
    if ($data['use-thank-you-page'] == 'on' && $data['thank-you-page']) {
        $result['value']  = 'redirect|' . $data['thank-you-page'];
    }
    else if ($data['thank-you-msg']) {
        $result['value']  = 'stay|' . $data['thank-you-msg'];
    }
    else {
        $result['value']  = 'stay|' . 'Your information has been sent. We will contact you shortly.';
    }
        
    $result['page_slug'] = get_post($postId)->post_name;
	
	//Get the settings database
	$alp_settings = get_option("alp_settings");

	//Load Sendpepper API if enabled
	if($data['use-sendpepper'] == 'on' && $alp_settings['sendpepper_api'] == 'on') {
		
		//Define variables
		$email_field = getShortcodeValue($data['email-field'], $_POST);    
		$name_field = getShortcodeValue($data['name-field'], $_POST);
		$sendpepper_api_id = $data['sendpepper-api-id'];
		$sendpepper_api_key = $data['sendpepper-api-key'];
		
		//Convert the string (contact tags) into an array
		$sendpepper_contact_tags = explode(',', $data['sendpepper-contact-tags']);
		
		//Remove white spaces on each array item
		$sendpepper_contact_tags = array_filter( array_map('trim', $sendpepper_contact_tags) );
		
		//Format Contact tags (Contact tags must be separated with */*)
		$sendpepper_contact_tags = implode('*/*', $sendpepper_contact_tags);
	
		include('api/sendpepper/sendpepper-contact-api.php');
		
		//Define debugging output on the browser's console
		$result['sendpepper_debug'] = $response_req;
		
	}
	
	//Print debug on browser console
	print_r(json_encode($result));
	
    exit;
} // end myAjax1

function myAjax2()
{
	global $post;
	?>
	<script type="text/javascript">
		var $jx = jQuery.noConflict();
		var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';

		/**
		 * called when an element is clicked
		 * <input type="text" name="myname" />
		 * <a href="#" onclick="javascript: alertName()">Click</a>
		 */
		
		function alpSubmitForm(isDownload){
		///
		form = $jx('.alp-contact-form');
		errorField = 0;
		
		$jx('.alp-contact-form input.required, .alp-contact-form textarea.required').each(function() {
			type = $jx(this).attr('type');
			id = $jx(this).attr('id');
			name = $jx(this).attr('name');
			val = $jx(this).val();
			//console.log(val + ' = ID : ' + id + ' = Name :' + name + ' Type: ' + type);

			if (type == 'radio') {
				var $checkedElement = $jx('input[name="'+name+'"]:checked');
				if (! $checkedElement.length) {
					errorField++;
				}
			}
			else {
				console.log(val);
				if (val == '') {
					//alert('error other'+id);
					errorField++;
				}
			}
		});
		
		$jx('.submit-btn').attr('disabled', true);
		
		$jx('.submit-btn').css('cursor', 'default');
		
		$jx('.submit-load').css('display', 'block');
			
		if (errorField == 0) {
			$jx.ajax({
				url:ajaxurl,
				type:'POST',
				dataType: "json",
				data:'action=pgst_ajax&post-id=<?php echo $post->ID ?>&is-download='+isDownload+'&'+form.serialize(),
				error:function(data_1) {
					if(data_1.statusText == 'error') {
						$jx('.error-msg').html("Error: Ajax could not connect to server! Try reloading the page.");
					}
				},
				success:function(data) {
									result = data.value;                                    
									$res = result.split('|');
									
									if ($res[0] == 'invalid_phone') {
										$jx('.error-msg').html("Invalid phone number.");
										$jx('.submit-btn').attr('disabled', false);
										$jx('.submit-btn').css('cursor', 'pointer');
										$jx('.submit-load').css('display', 'none');
									}
									else if($res[0] == 'fillrequired') {
										$jx('.error-msg').html("Please fill all the required fields.");
										$jx('.submit-btn').attr('disabled', false);
										$jx('.submit-btn').css('cursor', 'pointer');
										$jx('.submit-load').css('display', 'none');
									}
									else if ($res[0] == 'spam_bots') {
										$jx('.error-msg').html("Spam bots detected");
										$jx('.submit-btn').attr('disabled', false);
										$jx('.submit-btn').css('cursor', 'pointer');
										$jx('.submit-load').css('display', 'none');
									}
									else if ($res[0] == 'invalid_email') {
										$jx('.error-msg').html("Invalid email address.");
										$jx('.submit-btn').attr('disabled', false);
										$jx('.submit-btn').css('cursor', 'pointer');
										$jx('.submit-load').css('display', 'none');
									}
									else if ($res[0] == 'redirect') {
										window.location.href = $res[1];
									}
									else if (isDownload == true) {
										$jx('.submit-load').css('display', 'none');
										
										console.log('GA Send ' + '/' + data.page_slug + '/');
										ga('send', 'event', { eventCategory: 'Lead', eventAction: 'Form Download', eventLabel: '/' + data.page_slug + '/'});
										$jx('.alp-contact-form').html($res[1]);
										
									}
									else {
										$jx('.submit-load').css('display', 'none');
										
										//console.log('GA Send ' + '/' + data.page_slug + '/');
										ga('send', 'event', { eventCategory: 'Lead', eventAction: 'Form Submit', eventLabel: '/' + data.page_slug + '/'});
										$jx('.alp-contact-form').html($res[1]);
										
									}
				}
			});
		}
		else {
			//alert('Please fill all the required fields.');
			$jx('.error-msg').html("Please fill all the required fields.");
		}
		
	}
	</script>
<?php
} //end myAjax2()

add_action('wp_head','myAjax2');
add_action('wp_ajax_pgst_ajax', 'myAjax1');
add_action('wp_ajax_nopriv_pgst_ajax', 'myAjax1');//for users that are not logged 

function ga_check() {
	
	if(json_decode(stripslashes($_POST['ga_commands']))[0] == 'false') {
		update_option('alp_ga_commands', json_decode(stripslashes($_POST['ga_commands'])));
		$post_data['ga_commands'] = json_decode(stripslashes($_POST['ga_commands']));
	} else {
		update_option('alp_ga_commands', json_decode(stripslashes($_POST['ga_commands'])));
		$post_data['ga_commands'] = json_decode(stripslashes($_POST['ga_commands']));
	}
	
	//Print debug on browser console
	print_r(json_encode($post_data));
	exit;
	
}

//Define Ajax action hooks
add_action('wp_ajax_ga_check_ajax', 'ga_check');
add_action('wp_ajax_nopriv_ga_check_ajax', 'ga_check');//for users that are not logged 

function ga_check_js(){
	?>
	<script>
	jQuery(document).ready(function(){
		
		jQuery('.submit-btn').off('click').on('click', function(){
			
			//Define variable
			var ga_commands = [],
				target_url = '<?php echo $_SERVER['REDIRECT_URL']; ?>';
			
			if(typeof ga === 'undefined') {
				ga_commands[0] = 'false';
				ga_commands[1] = target_url;
			}else {
				ga_commands[0] = 'true';
				ga_commands[1] = target_url;
			}
			
			jQuery.ajax({
				url:'<?php echo admin_url("admin-ajax.php"); ?>',
				type:'POST',
				dataType: "json",
				data:'action=ga_check_ajax&ga_commands=' + JSON.stringify(ga_commands) + '',
				error:function(data_1) {
					if(data_1.statusText == 'error') {
						jQuery('.error-msg').html("Error: Ajax could not connect to server! Try reloading the page.");
					}
				},
				success:function(data) {
					//console.log(data);
				}
			});
			
		});
		
	});
	</script>
	<?php
}

//Define front-end script for ga_commands check
add_action('wp_head','ga_check_js');
/**
 * $to = the email of the user
 * $postId = the page id or post id
 */
function emailUser($to, $postId)
{
	$subject = 'Your free download: [offer page title]';
	$message = "
Dear [name as submitted on the form] \n\n
[Message that can be defined in the offer page creation]\n
Here is your document: [link to the document to download]\n\n
Regards,\n
[business name]";

	wp_mail($to, $subject, $message, $headers, $attachments);
}

function wpse31748_exclude_menu_items( $items, $menu, $args ) {
    // Iterate over the items to search and destroy
    foreach ( $items as $key => $item ) {
    	//echo '<br />' . $item->object_id . ' = ' . $item->title;
		$data = get_post_meta($item->object_id, 'landingpage-metabox', true);
		
		//$today =  strtotime(date('F j Y H:i'));
		$today = strtotime( current_time('mysql', $gmt = 0 ) );
	
		if (!$data['expired-date']) {
			continue;
		}
		$expiredDate = strtotime($data['expired-date']  . '23:59');
                
		//echo '<br />=> ' . $today . '+' . date('F j Y') . ' = ' . $data['expired-date'] . '+' . $expiredDate;
		if ($expiredDate <= $today) {
			unset( $items[$key] );
		}        	
    }

    return $items;
}

add_filter( 'wp_get_nav_menu_items', 'wpse31748_exclude_menu_items', null, 3);

wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

add_action('template_redirect', 'checkPageExpiry');

function checkPageExpiry()
{
    global $post, $wp_query;
    $data = get_post_meta($post->ID, 'landingpage-metabox', true);
	
	//$today =  strtotime(date('F j Y H:i'));
	$today = strtotime( current_time('mysql', $gmt = 0 ) );
		
    if ($data['expired-date']) {
        $expiredDate = strtotime($data['expired-date'] . '23:59');
        //echo '<br />=> ' . $today . '+' . date('F j Y') . ' = ' . $data['expired-date'] . '+' . $expiredDate;
        if ($expiredDate <= $today) {
              $wp_query->is_404 = true;
              $wp_query->is_single = false;
              $wp_query->is_page = false;
              wp_redirect(get_bloginfo('wpurl'));
              exit;
        }
    }
}

add_shortcode('active-offers-detailed', 'alp_active_offers_detailed_shortcode');

function alp_active_offers_detailed_shortcode($atts = array(), $content = '')
{
	$args = array(
            'order'    => 'ASC',
            'posts_per_page' => -1,
            'post_type' => 'page',	
	);

	// The Query
	//query_posts( $args );
        $the_query = new WP_Query( $args );
	$total = 0;

	// The Loop
	while ( $the_query->have_posts() ) : $the_query->the_post();
            $post_id = get_the_ID();	
            $metaBoxId = 'landingpage-metabox';		
            $image = get_the_post_thumbnail($post_id, 'thumbnail');
            $data = get_post_meta($post_id, $metaBoxId, true);
            $isOngoing = $data['ongoing'];
            //print_r($data);
            
		//$today =  strtotime(date('F j Y H:i'));
		$today = strtotime( current_time('mysql', $gmt = 0 ) );

            if (!$data['expired-date'] && $isOngoing != 'on') {
                continue;
            }

            $expiredDate = strtotime($data['expired-date']  . '23:59');
            $expired = false;

            if ($data['expired-date'] != '' && $expiredDate <= $today) {
                $expired = true;
            }

            if ($data['enable-landing-page'] == 'on' && !$expired) {
                $content = get_the_content();
                $summary = $data['summary'] ? $data['summary'] : str_replace('[…]', '...', alp_excerpt_max_charlength(150, $content));
                $subtitle = $data['sub-header'];
                $permalink = get_permalink($post_id);
                $text .= '<li>';
                $text .= '<h3 class="offer-title"><a href="' . $permalink . '">' . get_the_title() . '</a></h3>';
                $text .= '<div class="thumbnail"><a href="' . $permalink . '">' . $image . '</a></div>';
                $text .= '<div class="content">';                
                $text .= '<h4 class="offer-sub-title">' . $subtitle . '</h4>';
                $text .= '<div class="excerpt">';
                $text .= $summary;
                $text .= '<span class="readmore"><a href="' . $permalink . '">Read More &raquo;</a></span>';
                $text .= '</div></div></li>';
                $total++;
            }
	endwhile;

	// Reset Query
	wp_reset_postdata();
        if ($total == 0) {
            $customNoOffersMsg = ($atts['no_offers_msg']) ? $atts['no_offers_msg'] : 'There are no specials available right now. But please contact us to discuss your needs and get a free estimate.';
            $text = '<li class="no-active-offers-detailed">' . $customNoOffersMsg . '</li>';
        }
	return '<ul id="alp-active-offers-list-content" class="active-offers-detailed">' . $text . '</ul>';
}

add_shortcode('active-offers', 'alp_active_offers_shortcode');

function alp_active_offers_shortcode()
{
	$args = array(
		'order'    => 'ASC',
		'posts_per_page' => -1,
		'post_type' => 'page',	
	);

	// The Query
	query_posts( $args );
	$total = 0;
	// The Loop
	while ( have_posts() ) : the_post();
            $post_id = get_the_ID();	
            $metaBoxId = 'landingpage-metabox';		
            $image = get_the_post_thumbnail($post_id, 'thumbnail');
            $data = get_post_meta($post_id, $metaBoxId, true);
            $subtitle = $data['sub-header'];
			
			//$today =  strtotime(date('F j Y H:i'));
			$today = strtotime( current_time('mysql', $gmt = 0 ) );

            $isOngoing = $data['ongoing'];
            if (!$data['expired-date'] && $isOngoing != 'on') {
                continue;
            }            

            $expiredDate = strtotime($data['expired-date'] . '23:59');
            $expired = false;
            if ($data['expired-date'] != '' && $expiredDate <= $today) {
                $expired = true;
            }

            if ($data['enable-landing-page'] == 'on' && !$expired) {
                $summary = $data['summary'] ? $data['summary'] : str_replace('[…]', '...', alp_excerpt_max_charlength(50));
                $permalink = get_permalink($post_id);			
                $text .= '<li>';
                $text .= '<h3><a href="' . $permalink . '">' . get_the_title() . '</a></h3>';                
                $text .= '<div class="thumbnail"><a href="' . $permalink . '">' . $image . '</a></div>';
                $text .= '<div class="content">';
                $text .= '<p class="offer-sub-title">' . $subtitle . '</p>';
                $text .= '<div class="excerpt">';
                $text .= '<span class="readmore"><a href="' . $permalink . '">Read More &raquo;</a></span>';
                $text .= '</div></div></li>';
                $total++;
            }

            //$data['summary']
	endwhile;
	
	// Reset Query
	wp_reset_query();
        if ($total == 0) {
            $text = '<li class="no-active-offers">No active offers!</li>';
        }
	return '<ul id="alp-active-offers-list-sidebar" class="active-offers">' . $text . '</ul>';
}

function alp_excerpt_max_charlength($charlength, $content = '')
{
	$excerpt = ($content == '') ? get_the_excerpt() : $content;
	$charlength++;
	$text = '';
	if ( mb_strlen( $excerpt ) > $charlength ) {
            $subex = mb_substr( $excerpt, 0, $charlength - 5 );
            $exwords = explode( ' ', $subex );
            $excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
            if ( $excut < 0 ) {
                    $text .= mb_substr( $subex, 0, $excut );
            }
            else {
                    $text .= $subex;
            }
            $text .= '...';
	}
	else {
            $text .= $excerpt;
	}

	return $text;
}

?>