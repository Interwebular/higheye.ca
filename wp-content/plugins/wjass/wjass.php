<?php
/*
Plugin Name: WJASS
Description: WordJack Anti-Spam Service for Contact Form 7 & Advanced Landing Pages
Author: Keven Ages
Version: 2.0.1
Author URI: https://wordjack.com
*/
define( 'WJASS_URI', 'https://wjass.wordjack.com/spammers/filter' );
//V1 is now deprecated, but we should still check for both for now
define( 'ADVANCED_LANDING_PAGE_FIELD_V1', 'Email * :');
//V2 is the new field name
define( 'ADVANCED_LANDING_PAGE_FIELD_V2', 'Email :');
//Software name and version for user-agent
define( 'WJASS', 'WJASS');
define( 'WJASS_VERSION', '2.0.1');
//Curl timeout
define( 'WJASS_TIMEOUT', 30);

$plugin = plugin_basename(__FILE__);
/*
* wjass_wp_mail_check_spammer: catches wp_mail (wordpress native) form data
*/ 
add_filter('wpcf7_mail_components', 'wjass_wpcf7_mail_components', 1);
add_filter( 'wp_mail', 'wjass_wp_mail', 1);
add_filter('wpcf7_skip_mail','wjass_skip_mail', 2);
add_filter("plugin_action_links_$plugin", 'wjass_settings_link' );
/*
 * Admin filters/actions
 */
add_action('admin_menu', 'wjass_admin_page');
add_action('admin_init', 'wjass_register_settings');
add_action('admin_notices', 'wjass_admin_notices');
add_action( 'admin_notices', 'wjass_admin_notice__error' );
/*
 * We had to hook this function in CF7, otherwise it's impossible to get clean field values for things like:
 * recipient, sender, etc.
 */
function wjass_admin_notice__error() {
	$cf7_version = get_plugin_data( WP_PLUGIN_DIR . '/contact-form-7/wp-contact-form-7.php' );
	if($cf7_version['Version'] < 4){
		echo '<div class="notice notice-error">
        		<p>WjasS requires Contact Form 7 version 4.3.1 or greater.  Currently using version ' . $cf7_version['Version'] . '</p>
    	  	  </div>';
	}
}
/* Used for versions of Contact form 7 pre-4.3.1 */
function wjass_wpcf7_mail_components($mail_params, $form = null){
	$cf7 = WPCF7_Submission::get_instance();
	$cf7 = $cf7->get_posted_data();

	$api_key = get_option( 'wjass_settings' );
	$api_key = $api_key['wjass_api_key'];

	$additional_info = array_merge($mail_params, RemoveCf7Elements($cf7));
	unset($additional_info['attachments'], 
		  $additional_info['additional_headers'], 
		  $additional_info['sender'], 
		  $additional_info['body'], 
		  $additional_info['recipient'], 
		  $additional_info['your-email'], 
		  $additional_info['your-subject'], 
		  $additional_info['your-message']
		  );

	$recipients = array();

	$params_recipients = explode(",", $mail_params['recipient']);

	foreach ($params_recipients as $key => $value) {
		array_push($recipients, array('email' => trim($value)));
	}

	$data = array('data' => array(
								'email' => $cf7['your-email'],
								'message' => $cf7['your-message'],
								'recipient' => $recipients,
								'client_key' => md5($api_key),
								'site_url' => GetSiteUrl(),
								'site_name' => get_option('blogname'),
								'additional_info' => $additional_info
								 )
				  );
	//Send to WJASS
	MakeRequest($data);
}
/* For versions of contact form 7 >= 4.3.1 */
function wjass_skip_mail($form){

	$mail_params = array();
	$additional_emails = array();

	$cf7 = WPCF7_Submission::get_instance();
	$cf7 = $cf7->get_posted_data();
	
	$WPCF7_ContactForm = WPCF7_ContactForm::get_current();
	$mail_params = $WPCF7_ContactForm->mail;

	if ( !function_exists( 'wpcf7_mail_replace_tags' ) ) { 
    	require_once '/includes/mail.php'; 
	}

	$formatted_body = wpcf7_mail_replace_tags($mail_params['body'], $mail_params); 
	$formatted_subject = wpcf7_mail_replace_tags($mail_params['subject'], $mail_params);
	
        if(isset($mail_params['additional_headers'])){
          $additional_headers = explode(PHP_EOL, $mail_params['additional_headers']);
          for($i = 0; $i < sizeof($additional_headers); $i++){
          	if(strpos(strtolower($additional_headers[$i]), 'bcc') !== false){
          		$bcc = explode(":", $additional_headers[$i]);
          		$bcc = array_reverse($bcc);
          		$additional_emails['bcc'] = $bcc[0];
          		array_splice($additional_headers, $i, 1);

          	}

          	if(strpos(strtolower($additional_headers[$i]), 'cc') !== false){
          		$cc = explode(":", $additional_headers[$i]);
          		$cc = array_reverse($cc);
          		if($cc[0] !== ''){
          			$additional_emails['cc'] = $cc[0];
          		}
          	}

          }
        }
	
	$api_key = get_option( 'wjass_settings' );
	$api_key = $api_key['wjass_api_key'];

	$additional_info = RemoveCf7Elements($cf7);
	unset($additional_info['attachments'], 
		  $additional_info['additional_headers'], 
		  $additional_info['sender'], 
		  $additional_info['body'], 
		  $additional_info['recipient'], 
		  $additional_info['your-email'], 
		  $additional_info['your-subject'], 
		  $additional_info['your-message']
		  );

	$recipients = array();

	if(substr(trim($mail_params['recipient']), 0, 1) === '['){
		//first let's unset this from additional info
		$to_unset = $mail_params['recipient'];
		$to_unset = str_replace('[', '', $to_unset);
		$to_unset = str_replace(']', '', $to_unset);
		unset($additional_info[$to_unset]);
		//now let's replace the template
		$mail_params['recipient'] = wpcf7_mail_replace_tags($mail_params['recipient'], $mail_params);
	}

	$params_recipients = explode(",", $mail_params['recipient']);

	foreach ($params_recipients as $key => $value) {
		array_push($recipients, array('email' => trim($value)));
	}

	if(empty($additional_emails)){
		$additional_emails = null;
	}

	$data = array('data' => array(
								'email' => $cf7['your-email'],
								'additional_emails' => $additional_emails,
								'message' => $formatted_body,
								'plugin_used' => 'cf7',
								'recipient' => $recipients,
								'client_key' => md5($api_key),
								'site_url' => GetSiteUrl(),
								'site_name' => get_option('blogname'),
								'additional_info' => $additional_info
								 )
				  );
	//Send to WJASS
	MakeRequest($data);
    return true; // DO NOT SEND E-MAIL
}
/**
 * Gets and formats post data from Advanced Landing Pages to send to WjasS
 */
function wjass_wp_mail( $args ) {

	if(!empty($args['to']) && isset($_POST['post-id']) && !isset($_POST['_wpcf7'])){

		$api_key = get_option( 'wjass_settings' );
		$api_key = $api_key['wjass_api_key'];
		$email_address = null;
		$additional_info = array();
		$message_args = explode( "\n", $args['message'] ); 
		$senders_name = '';	

		foreach( $message_args as $key => $msg_value ) {
					
					if ( empty($msg_value) || $msg_value == '' ) { 
						unset($message_args[$key]);
						continue;
					}

	                if( strpos( $msg_value, ADVANCED_LANDING_PAGE_FIELD_V1 ) !== false) {
						$email_address = trim( str_replace( ADVANCED_LANDING_PAGE_FIELD_V1, '', $msg_value ) ); 
						unset($message_args[$key]);
						continue;                   
	                }
	                else if (strpos( $msg_value, ADVANCED_LANDING_PAGE_FIELD_V2 ) !== false ){
						$email_address = trim( str_replace( ADVANCED_LANDING_PAGE_FIELD_V2, '', $msg_value ) );
						unset($message_args[$key]);
						continue;
					}

					if ( strpos( strtolower($msg_value), 'message') !== false ){
						$message = trim($msg_value); 
						unset($message_args[$key]);
						continue;
					}

					if ( strpos( strtolower($msg_value), 'name') !== false ){
						$senders_name = trim($msg_value); 
						unset($message_args[$key]);
						$normalized_name = explode(":", $senders_name);
						$additional_info += array('your-name' => trim($normalized_name[1]));
						continue;
					}

					$a = explode(":", $msg_value);
					$additional_info += array(trim($a[0]) => trim($a[1]));
		}

		$recipients = array();
		$params_recipients = explode(",", $args['to']);

		foreach ($params_recipients as $k => $v) {
			array_push($recipients, array('email' => trim($v)));
		}
		
		$data = array('data' => array(
						'email' => $email_address,
						'message' => $message,
						'recipient' => $recipients,
						'plugin_used' => 'alp',
						'client_key' => md5($api_key),
						'site_url' => GetSiteUrl(),
						'site_name' => get_option('blogname'),
						'additional_info' => $additional_info
						)
					  );

		// Because WP_MAIL doesn't have a skip mail feature like CF7, we have to blackhole them.
		$args['to'] = 'blackhole@wordjack.com';
		//Send to WJASS
		MakeRequest($data);
	}
	return $args;
}
/*
 * Add settings link on plugin page
 */
function wjass_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=wjass-settings">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
/*
 * Add an Admin Menu item
 */
function wjass_admin_page(){
    add_menu_page('WjasS Settings', 'WjasS', 'administrator', 'wjass-settings', 'wjass_admin_page_callback', 'dashicons-shield');
}
/*
 * Register the settings
 */
function wjass_register_settings(){
    //this will save the option in the wp_options table as 'wjass_settings'
    //the third parameter is a function that will validate input values
    register_setting('wjass_settings', 'wjass_settings', 'wjass_settings_validate');
}
function wjass_settings_validate($args){
	if(!isset($args['wjass_api_key']) || $args['wjass_api_key'] === ''){
		$args['wjass_api_key'] = '';
    	add_settings_error('wjass_settings', 'wjass_invalid_email', 'WjasS API Key cannot be blank', $type = 'error');
	} elseif(strlen($args['wjass_api_key']) !== 22) {
		add_settings_error('wjass_settings', 'wjass_invalid_email', 'WjasS API Key must be 22 characters', $type = 'error');
	}
    return $args;
}
//Display the validation errors and update messages
/*
 * Admin notices
 */
function wjass_admin_notices(){
   settings_errors();
}
//The markup for settings page
function wjass_admin_page_callback(){
?>
    <div class="wrap">
    <h2>WordJack Anti-Spam Service \ Settings</h2>
    <hr>
    <form action="options.php" method="post"><?php
        settings_fields( 'wjass_settings' );
        do_settings_sections( __FILE__ );
        //get the older values, wont work the first time
        $options = get_option( 'wjass_settings' );
        $client_suggested_name = get_option( 'blogname' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">API Key</th>
                <td>
                    <fieldset>
                        <label>
                            <input style="width: 100%;" placeholder="API Key" name="wjass_settings[wjass_api_key]" type="text" id="wjass_api_key" value="<?php echo (isset($options['wjass_api_key']) && $options['wjass_api_key'] != '') ? $options['wjass_api_key'] : ''; ?>"/>
                            <br />
                            <span class="description">Please enter a valid api key from Wombat.</span>
                            <div>
                            	<ol>
                            		<li>
	                            		<a target="_blank" href="http://wombat.wordjack.com/clients/search?query=<?php echo urlencode($client_suggested_name) ?>">
	                            			Search Wombat
	                            		</a> for this client (<?php echo $client_suggested_name ?>)
                            		</li>
                            		<li>
                            			On the client's summary screen you will see a link titled: "<stron>Public profile</strong>".  Click that link.
                            		</li>
                            		<li>
                            			You can get the API key from the URL, it will look similar to this:
                            			<br />
                            			http://wombat.wordjack.com/api/v1/publics/writer_details?api_key=<strong>B6lqXmA3cDrIRX1wEMH1VA</strong> * Please note, this is just an example and NOT the correct API key
                            		</li>
                            	</ol>
                            </div>
                        </label>
                    </fieldset>
                </td>
            </tr>
        </table>
        <input type="submit" value="Save" />
    </form>
</div>
<?php 
}

/**
 * Utility functions
 */

/*
 * Removes the fields that start with _ because those are specific to CF7
 */
function RemoveCf7Elements($data){
	foreach($data as $key => $value){
		if (substr($key,0,1) == '_') {
			unset($data[$key]);
		}
	}
	return $data;
}

/** 
 * Finds an email address in arbitrary values of an array
 */
function FindEmail($data = array()){
	foreach ($data as $d){
		//Use a validator to see if we have an email address somewhere in the data array
		if ( filter_var( $d, FILTER_VALIDATE_EMAIL ) ) {
	    	return $d;
		}
	}
	return ;
}
/** 
 * Returns a host type
 * @return http:// or https://
 */ 
function GetHostType(){
	return "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://");
}
/** 
 * Returns the site url, tries with the most reliable methods first, fallsback on less reliable methods
 * @return url of website
 */ 
function GetSiteUrl(){
	if ( !empty( $_SERVER['HTTP_HOST'] ) ) {
		return GetHostType() . $_SERVER['HTTP_HOST'];
	} elseif ( get_bloginfo( 'url' ) !== '' ) {
		return get_bloginfo( 'url' );
	} elseif ( get_site_url() !== '' ) {
		return get_site_url();
	} else {
		return GetHostType() . $_SERVER['SERVER_NAME'];
	}
}
/**
 * Curl request to anti-spam server to check email address. If the email address is on the spam list, return true.
 * Uses native wp_remote_get
 * @see http://codex.wordpress.org/Function_Reference/wp_remote_post
 * @return boolean (true/false) 
 */ 
function MakeRequest($data = array()){

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch,CURLOPT_FAILONERROR,true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_URL, WJASS_URI);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);  
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	
  $response = curl_exec($ch);
  //For debugging:	
  //$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  //$curl_errno= curl_errno($ch);
  curl_close($ch);
  return false;
  
}
?>