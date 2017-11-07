<?php
/**
* Plugin Name: WP Local Seo Contact
* Plugin URI: https://wordjack.com/
* Description: Adds contact, social buttons, and work operations on widgets, pages/posts. and on themes.
* Version: 1.1.6
* Author: Edesa Cabang (Maintained by Virson Ebillo)
* Author URI: https://wordjack.com/
*/

//Load Wordpress core get_plugins function if it was not loaded
if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
define('LSSC_PLUGIN_VERSION', get_plugins( '/wp-local-seo-contact' )['wp-local-seo-contact.php']['Version']);
define('LSSC_PLUGIN_DIR_URL', preg_replace('/\s+/', '', plugin_dir_url(__FILE__)));

//For global notification
add_action('admin_head', function(){
	if( $_GET['page'] != 'local-seo'){
		require('notification.php');
	}
});

include_once('LSSC/Form.php');

class WPLocalSeo
{
	public function __construct()
	{
		$plugin_url = plugin_dir_url(__FILE__);
		wp_enqueue_style('lssc-style', $plugin_url . 'styles/style.css?version=' . LSSC_PLUGIN_VERSION);
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script('lssc-script', $plugin_url . 'scripts/script.js?version=' . LSSC_PLUGIN_VERSION);
		add_action('admin_menu', array($this, 'menu'));
	}

    public function menu()
	{
		add_menu_page('Local Seo', 'Local Seo', 'administrator', 'local-seo', array($this, 'main_page'));
	}

	public function main_page()
	{
		$form = new LSSC_Form();
		if (isset($_POST) && $_POST['form'] == 'lssc') {
			$contact_information = array('name', 'street', 'unit-number', 'state', 'zip', 'country', 'city', 'phone', 'alt-phone', 'fax', 'email', 'company-url', 'latitude', 'longitude', 'logo');
			$user_contact = array();

			foreach ($contact_information as $c) {
                                if (!is_array($_POST[$c])) {
                                    $user_contact[$c] = stripslashes($_POST[$c]);
                                }
                                else {
                                    $user_contact[$c] = $_POST[$c];
                                }
			}
			

			$hours_operation = array('hours-block-full-page', 'hours-heading', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
			$business_op = array();
			foreach ($hours_operation as $h) {
				$business_op[$h] = $_POST[$h];
			}

			$social_buttons = array('media-block-full-page', 'facebook', 'youtube', 'google-places', 
				'yelp', 'hot-frog', 'flickr', 'tumblr', 'delicious', 
				'twitter', 'linked-in', 'google-plus', 'pinterest',  
				'four-square', 'stumble-upon', 'merchant-circle', 'instagram', 'houzz', 'vimeo');

			$business_socials = array();
			foreach ($social_buttons as $sb) {
				$business_socials[$sb] = $_POST[$sb];
                $business_socials[$sb.'-icon'] = $_POST[$sb. '-icon'];
			}
			
			
			if ($_GET['a'] == 2) {
				update_option('lssc-business-socials-order2', $_POST['socials']);
				update_option('lssc-business-socials-2', $business_socials);
				update_option('lssc-contact-2', $user_contact);
				update_option('lssc-business-hours-2', $business_op);			
			}
			else if ($_GET['a'] == 3) {
				update_option('lssc-business-socials-order3', $_POST['socials']);
				update_option('lssc-business-socials-3', $business_socials);
				update_option('lssc-contact-3', $user_contact);
				update_option('lssc-business-hours-3', $business_op);			
			}
			else {
                update_option('lssc-business-socials-order1', $_POST['socials']);
				update_option('lssc-business-socials', $business_socials);
				update_option('lssc-contact', $user_contact);
				update_option('lssc-business-hours', $business_op);			
			}
			
			echo "
				<div class='updated save-info-settings'>
					<p><b>Options Saved!</b></p>
				</div>
			";
		}

		$shortcode_suffix = '';		
		if ($_GET['a'] == 2) {
			$shortcode_suffix = '_2';
			$contact = get_option('lssc-contact-2');
			$business_hours = get_option('lssc-business-hours-2');
			$business_socials = get_option('lssc-business-socials-2');
            $order_socials = get_option('lssc-business-socials-order2');
		}
		else if($_GET['a'] == 3){
			$shortcode_suffix = '_3';
			$contact = get_option('lssc-contact-3');
			$business_hours = get_option('lssc-business-hours-3');
			$business_socials = get_option('lssc-business-socials-3');
            $order_socials = get_option('lssc-business-socials-order3');
		}
		else {
			$shortcode_suffix = '';
			$contact = get_option('lssc-contact');
			$business_hours = get_option('lssc-business-hours');
			$business_socials = get_option('lssc-business-socials');		
            $order_socials = get_option('lssc-business-socials-order1');
		}

                if (!is_array($order_socials)) {
                    $order_socials = array('facebook', 'youtube', 'twitter', 'linked-in',
                                                 'google-plus', 'pinterest', 'google-places', 'yelp','hot-frog',
                                                 'flickr', 'tumblr', 'delicious', 'four-square','stumble-upon',
                                                 'merchant-circle', 'instagram', 'houzz', 'vimeo');
                }

		$current_address = $_GET['a'];
		if ($current_address == '') {
			$current_address = 1;
		}
	
		?>
		<div class="wrap">
		<?php
		$social_array = array(
			'facebook', 'youtube', 'google-places', 
			'yelp', 'hot-frog', 'flickr', 'tumblr',
			'delicious', 'twitter', 'linked-in',
			'google-plus', 'pinterest', 'four-square',
			'stumble-upon', 'merchant-circle',
			'instagram', 'houzz', 'vimeo'
			);
		$counter_class = 1;
		foreach( $social_array as $classes ):
		?>
		<script>
		jQuery(document).ready(function() {
			
			var onChangeTimeout_<?php echo $counter_class; ?>;
			
			function test_<?php echo $counter_class; ?>() {
				
				//Links
				jQuery('.<?php echo $classes; ?>').val( jQuery('.<?php echo $classes; ?>').val().replace("http://", "") );
				jQuery('.<?php echo $classes; ?>').val( jQuery('.<?php echo $classes; ?>').val().replace("https://", "") );
				
				//Icon links
				jQuery('.<?php echo $classes . '-icon'; ?>').val( jQuery('.<?php echo $classes . '-icon'; ?>').val().replace("http://", "") );
				jQuery('.<?php echo $classes . '-icon'; ?>').val( jQuery('.<?php echo $classes . '-icon'; ?>').val().replace("https://", "") );
				
			}
			
			jQuery('.<?php echo $classes; ?>, .<?php echo $classes . '-icon'; ?>').on('click focusin', function() {
				
				if( !!onChangeTimeout_<?php echo $counter_class; ?> ){
					clearTimeout(onChangeTimeout_<?php echo $counter_class; ?>);
				}
				
				test_<?php echo $counter_class; ?>();
				onChangeTimeout_<?php echo $counter_class; ?> = setTimeout(test_<?php echo $counter_class; ?>, 500);
				
			});
			
			jQuery('.<?php echo $classes; ?>, .<?php echo $classes . '-icon'; ?>').on('click focusout', function() {
				
				if( !!onChangeTimeout_<?php echo $counter_class; ?> ){
					clearTimeout(onChangeTimeout_<?php echo $counter_class; ?>);
				}
				
				test_<?php echo $counter_class; ?>();
				onChangeTimeout_<?php echo $counter_class; ?> = setTimeout(test_<?php echo $counter_class; ?>, 500);
				
			});

		});
		</script>
		<?php endforeach; ?>
		
			<h2>Local SEO <span class="lssc_version_txt"><?php echo LSSC_PLUGIN_VERSION; ?></span> <span class="lssc_change_log"><a href="<?php echo LSSC_PLUGIN_DIR_URL; ?>change-log.txt?version=<?php echo LSSC_PLUGIN_VERSION; ?>" target="_blank">View Change log</a></span></h2>
			
			<?php
				//For DB migration
				require('db-migrate.php');
			?>
			
			<script>
			jQuery(document).ready(function() {

				var onChangeTimeout;
				
				function logo_link_filter() {

					//Logo links
					jQuery('#fs-contactinfo .logo').val( jQuery('#fs-contactinfo .logo').val().replace("http://", "") );
					jQuery('#fs-contactinfo .logo').val( jQuery('#fs-contactinfo .logo').val().replace("https://", "") );

				}

				jQuery('#fs-contactinfo .logo').on('click focusout', function() {

					if( !!onChangeTimeout ){
						clearTimeout(onChangeTimeout);
					}

					logo_link_filter();
					onChangeTimeout = setTimeout(logo_link_filter, 500);

				});

			});
			</script>
			<fieldset id="fs-notes" class="lssc-form">
				<h4><span class="dashicons dashicons-editor-code"></span> Shortcode Parameters <span id="lssc_options_btn">Show Details</span></h4>
				<div class="lssc_hidden_options">
					<br />
					<b>CSS ID ATTR: <span style="color: #F44336;">id="your_css_id"</span></b>
					<br />
					<br />
					<b>Example:</b>
					<ul style="margin: 0;">
						<li>[ebs_seo_cp_contact_only id="my_id_1"]</li>
						<li>[ebs_seo_cp_hours_only id="my_id_2"]</li>
						<li>[ebs_seo_cp_social_media_only id="my_id_3"]</li>
					</ul>
				</div>
			</fieldset>
			<div class="address-nav">
				<a href="?page=local-seo" class="<?php echo ($current_address == 1) ? 'current-add' : ''?>">Address 1</a>
				<a href="?page=local-seo&a=2" class="<?php echo ($current_address == 2) ? 'current-add' : ''?>">Address 2</a>
				<a href="?page=local-seo&a=3" class="<?php echo ($current_address == 3) ? 'current-add' : ''?>">Address 3</a>
			</div>
                        
<form name="local-seo" method="post" class="local-seo">
<div class="local-seo-admin-left-pane">
	<fieldset id="fs-contactinfo" class="lssc-form">
		<legend>Contact Information</legend>	
		<p class="localseo-shortcode">Shortcode: <span>[ebs_seo_cp_contact_only<?php echo $shortcode_suffix ?>]</span></p>
		
		<?php echo $form->display_text_element('logo', array('label' => 'Company Logo', 'value' => $contact['logo'])) ?>			
		<?php echo $form->display_text_element('name', array('label' => 'Name', 'value' => $contact['name'])) ?>			
		<?php echo $form->display_text_element('street', array('label' => 'Street', 'value' => $contact['street'])) ?>			
		<?php echo $form->display_text_element('unit-number', array('label' => 'Unit Number', 'value' => $contact['unit-number'])) ?>			
		<?php echo $form->display_text_element('city', array('label' => 'City', 'value' => $contact['city'])) ?>			
		<?php echo $form->display_text_element('state', array('label' => 'State', 'value' => $contact['state'])) ?>			
		<?php echo $form->display_text_element('zip', array('label' => 'Zip', 'value' => $contact['zip'])) ?>
		<?php echo $form->display_text_element('country', array('label' => 'Country', 'value' => $contact['country'])) ?>			
		<?php echo $form->display_phone_element('phone', array('label' => 'Phone', 'value' => $contact['phone'])) ?>			
		<?php echo $form->display_phone_element('alt-phone', array('label' => 'Alt. Phone', 'value' => $contact['alt-phone'])) ?>			
		<?php echo $form->display_phone_element('fax', array('label' => 'Fax', 'value' => $contact['fax'])) ?>			
		<?php echo $form->display_text_element('email', array('label' => 'Email', 'value' => $contact['email'])) ?>			
		<?php echo $form->display_text_element('company-url', array('label' => 'Company URL', 'value' => $contact['company-url'])) ?>			
		<?php echo $form->display_text_element('latlong', array('label' => 'LongLong', 'value' => $contact['street'] . ' ' . $contact['unit-number'] . ' ' . $contact['city'] . ' ' . $contact['state'] . ' ' . $contact['zip'] . ' ' . $contact['country'])) ?>
		<?php echo $form->display_text_element('latitude', array('label' => 'Latitude', 'value' => $contact['latitude'])) ?>			
		<?php echo $form->display_text_element('longitude', array('label' => 'Longitude', 'value' => $contact['longitude'])) ?>
		
		<?php
		//For notifcation
		if( $_GET['page'] == 'local-seo'){
			require('notification.php');
		}
		?>
		
		<em>Copy this to the SEO Ultimate Code Inserter</em>
<textarea style="width:500px; height:140px">
<meta name="zipcode" content="<?php echo $contact['zip'] ?>">
<meta name="city" content="<?php echo $contact['city'] ?>">
<meta name="state" content="<?php echo  $contact['state'] ?>">
<meta name="geo.position" content="<?php echo $contact['latitude'] . ';' . $contact['longitude'] ?>">
<meta name="DC.Language" content="en" >
</textarea>
	</fieldset>
	<fieldset id="fs-hoursoperation" class="lssc-form">		
		<legend>Hours Of Operation</legend>						
		<p class="localseo-shortcode">Shortcode: <span>[ebs_seo_cp_hours_only<?php echo $shortcode_suffix ?>]</span></p>
		
		<p>You must use 24-hour formatted time here. See Wikipedia</p>			
		<?php echo $form->display_cb_element('hours-block-full-page', array('label' => 'Show hours block on full page', 'value' => $business_hours['hours-block-full-page'])) ?>			
		<?php echo $form->display_text_element('hours-heading', array('label' => 'Hours Heading', 'value' => $business_hours['hours-heading'])) ?>			
		<table width="50%">		
			<tr>		
				<th>Open?</th>		
				<th>Hide?</th>		
				<th>Day</th>
				<th>Opens</th>		
				<th>Closes</th>		
			</tr>		
			<tr>	
				<?php echo $form->dislay_day_operation('sun', array('label' => 'Sunday', 'value' => $business_hours['sun']))?>
			</tr>		
			<tr>				
				<?php echo $form->dislay_day_operation('mon', array('label' => 'Monday', 'value' => $business_hours['mon']))?>
			</tr>		
			<tr>		
				<?php echo $form->dislay_day_operation('tue', array('label' => 'Tuesday', 'value' => $business_hours['tue']))?>
			</tr>			
			<tr>			
				<?php echo $form->dislay_day_operation('wed', array('label' => 'Wednesday', 'value' => $business_hours['wed']))?>
			</tr>			
			<tr>				
				<?php echo $form->dislay_day_operation('thu', array('label' => 'Thursday', 'value' => $business_hours['thu']))?>
			</tr>			
			<tr>			
				<?php echo $form->dislay_day_operation('fri', array('label' => 'Friday', 'value' => $business_hours['fri']))?>
			</tr>			
			<tr>					
				<?php echo $form->dislay_day_operation('sat', array('label' => 'Saturday', 'value' => $business_hours['sat']))?>
			</tr>						
		</table>	
	</fieldset>	
</div><!-- .local-seo-admin-left-pane -->

                        <div class="local-seo-admin-right-pane">
			<fieldset id="fs-socialbuttons" class="lssc-form">	
                            <?php 
                                
                           ?>
				<legend>Social Media Buttons</legend>	
				<p class="localseo-shortcode">Shortcode: <span>[ebs_seo_cp_social_media_only<?php echo $shortcode_suffix ?>]</span></p>
				
				<p>Use links to your social media pages. If you don't have one, just leave it blank. Hover over the icon if you aren't sure what it stands for.</p>	
                                <ul class="sortable-socials">
                                    <?php echo $form->display_cb_element('media-block-full-page', array('label' => 'Show social media on full page view', 'value' => $business_socials['media-block-full-page'] )) ?>
                                    <?php
                                    foreach ($order_socials as $s) {
                                        getSocialInput($form, $business_socials, $s);
                                    }
                                    ?>
                                </ul>
			</fieldset>                            
                        </div><!-- .local-seo-admin-right-pane -->
                    


		<input type="hidden" name="form" value="lssc" />
		<div class="submit"><input class="button-primary" type="submit" value="Save Options" /></div>
		</div>


		<?php
	}
}
function getSocialInput($form, $business_socials, $type) {

    switch ($type) {
        case 'facebook':
            ?>
			<li>
				<input type="hidden" name="socials[]" value="facebook" />
				
				<?php echo $form->display_social_element('facebook', array('label' => 'Facebook', 'value' => $business_socials['facebook'])) ?>
				<?php echo $form->display_social_element('facebook-icon', array('label' => '<em class="icon">Facebook Icon</em>', 'value' => $business_socials['facebook-icon'])) ?>
			</li>
            <?php
            break;
        case 'youtube':
            ?>
                    <li>                                    
                        <input type="hidden" name="socials[]" value="youtube" />
                        <?php echo $form->display_social_element('youtube', array('label' => 'Youtube', 'value' =>  $business_socials['youtube'])) ?>
                        <?php echo $form->display_social_element('youtube-icon', array('label' => '<em class="icon">Youtube Icon</em>', 'value' =>  $business_socials['youtube-icon'])) ?>
                    </li>                                    
            <?php
            break;
        case 'twitter':
            ?>
                    <li>   
                        <input type="hidden" name="socials[]" value="twitter" />
                        <?php echo $form->display_social_element('twitter', array('label' => 'Twitter', 'value' =>  $business_socials['twitter'])) ?>
                        <?php echo $form->display_social_element('twitter-icon', array('label' => '<em class="icon">Twitter Icon</em>', 'value' =>  $business_socials['twitter-icon'])) ?>
                    </li>                                    
            <?php
            break;
        case 'linked-in':
            ?>
                    <li>   
                        <input type="hidden" name="socials[]" value="linked-in" />
                        <?php echo $form->display_social_element('linked-in', array('label' => 'LinkedIn', 'value' =>  $business_socials['linked-in'])) ?>
                        <?php echo $form->display_social_element('linked-in-icon', array('label' => '<em class="icon">LinkedIn Icon</em>', 'value' =>  $business_socials['linked-in-icon'])) ?>
                    </li>
            <?php
            break;
        case 'google-plus':
            ?>
                    <li>                                  
                        <input type="hidden" name="socials[]" value="google-plus" />
                        <?php echo $form->display_social_element('google-plus', array('label' => 'Google+', 'value' =>  $business_socials['google-plus'])) ?>
                        <?php echo $form->display_social_element('google-plus-icon', array('label' => '<em class="icon">Google+ Icon</em>', 'value' =>  $business_socials['google-plus-icon'])) ?>
                    </li>
            <?php
            break;
        case 'yelp':
            ?>
                    <li>                                   
                        <input type="hidden" name="socials[]" value="yelp" />
                        <?php echo $form->display_social_element('yelp', array('label' => 'Yelp', 'value' =>  $business_socials['yelp'])) ?>
                        <?php echo $form->display_social_element('yelp-icon', array('label' => '<em class="icon">Yelp Icon</em>', 'value' =>  $business_socials['yelp-icon'])) ?>
                    </li>
            <?php
            break;
        case 'hot-frog':
            ?>
                    <li>   
                        <input type="hidden" name="socials[]" value="hot-frog" />
                        <?php echo $form->display_social_element('hot-frog', array('label' => 'Hot Frog', 'value' =>  $business_socials['hot-frog'])) ?>
                        <?php echo $form->display_social_element('hot-frog-icon', array('label' => '<em class="icon">Hot Frog</em>', 'value' =>  $business_socials['hot-frog-icon'])) ?>
                    </li>
            <?php
            break;
        case 'flickr':
            ?>
                    <li>   
                        <input type="hidden" name="socials[]" value="flickr" />
                        <?php echo $form->display_social_element('flickr', array('label' => 'Flickr', 'value' =>  $business_socials['flickr'])) ?>
                        <?php echo $form->display_social_element('flickr-icon', array('label' => '<em class="icon">Flickr Icon</em>', 'value' =>  $business_socials['flickr-icon'])) ?>
                    </li>
            <?php
            break;
        case 'tumbler':
            ?>
                    <li>   
                        <input type="hidden" name="socials[]" value="tumbler" />
                        <?php echo $form->display_social_element('tumblr', array('label' => 'Tumblr', 'value' =>  $business_socials['tumblr'])) ?>		
                        <?php echo $form->display_social_element('tumblr-icon', array('label' => '<em class="icon">Tumblr Icon</em>', 'value' =>  $business_socials['tumblr-icon'])) ?>	
                    </li>                                    
            <?php
            break;
        case 'delicious':
            ?>
                    <li>   
                        <input type="hidden" name="socials[]" value="delicious" />
                        <?php echo $form->display_social_element('delicious', array('label' => 'Del.icio.us', 'value' =>  $business_socials['delicious'])) ?>
                        <?php echo $form->display_social_element('delicious-icon', array('label' => '<em class="icon">Del.icio.us Icon</em>', 'value' =>  $business_socials['delicious-icon'])) ?>
                    </li>
            <?php
            break;
        case 'pinterest':
            ?>
                    <li>   
                        <input type="hidden" name="socials[]" value="pinterest" />
                        <?php echo $form->display_social_element('pinterest', array('label' => 'Pinterest', 'value' =>  $business_socials['pinterest'])) ?>	
                        <?php echo $form->display_social_element('pinterest-icon', array('label' => '<em class="icon">Pinterest Icon</em>', 'value' =>  $business_socials['pinterest-icon'])) ?>	
                    </li>
            <?php
            break;
        case 'four-square':
            ?>
                    <li>   
                        <input type="hidden" name="socials[]" value="four-square" />
                        <?php echo $form->display_social_element('four-square', array('label' => 'Foursquare', 'value' =>  $business_socials['four-square'])) ?>
                        <?php echo $form->display_social_element('four-square-icon', array('label' => '<em class="icon">Foursquare Icon</em>', 'value' =>  $business_socials['four-square-icon'])) ?>
                    </li>                                    
            <?php
            break;
        case 'stumble-upon':
            ?>
                    <li>   
                        <input type="hidden" name="socials[]" value="stumble-upon" />
                        <?php echo $form->display_social_element('stumble-upon', array('label' => 'Stumbleupon', 'value' =>  $business_socials['stumble-upon'])) ?>
                        <?php echo $form->display_social_element('stumble-upon-icon', array('label' => '<em class="icon">Stumbleupon Icon</em>', 'value' =>  $business_socials['stumble-upon-icon'])) ?>
                    </li>                                    
            <?php
            break;
        case 'merchant-circle':
            ?>
                    <li>   
                        <input type="hidden" name="socials[]" value="merchant-circle" />
                        <?php echo $form->display_social_element('merchant-circle', array('label' => 'Merchant Circle', 'value' =>  $business_socials['merchant-circle'])) ?>
                        <?php echo $form->display_social_element('merchant-circle-icon', array('label' => '<em class="icon">Merchant Circle Icon</em>', 'value' =>  $business_socials['merchant-circle-icon'])) ?>
                    </li>                                    
            <?php
            break;
        case 'instagram':
            ?>
                    <li>   
                        <input type="hidden" name="socials[]" value="instagram" />
                        <?php echo $form->display_social_element('instagram', array('label' => 'Instagram', 'value' =>  $business_socials['instagram'])) ?>
                        <?php echo $form->display_social_element('instagram-icon', array('label' => '<em class="icon">Instagram Icon</em>', 
                            'value' =>  $business_socials['instagram-icon'])) ?>
                    </li>                                    
            <?php
            break;
        case 'houzz':
            ?>
                    <li>  
                        <input type="hidden" name="socials[]" value="houzz" />
                        <?php echo $form->display_social_element('houzz', array('label' => 'Houzz', 'value' =>  $business_socials['houzz'])) ?>
                        <?php echo $form->display_social_element('houzz-icon', array('label' => '<em class="icon">Houzz Icon</em>', 'value' =>  $business_socials['houzz-icon'])) ?>
                    </li>
            <?php
            break;
        case 'vimeo':
            ?>
                    <li>  
                        <input type="hidden" name="socials[]" value="vimeo" />
                        <?php echo $form->display_social_element('vimeo', array('label' => 'Vimeo', 'value' =>  $business_socials['vimeo'])) ?>
                        <?php echo $form->display_social_element('vimeo-icon', array('label' => '<em class="icon">Vimeo Icon</em>', 'value' =>  $business_socials['vimeo-icon'])) ?>
                    </li>
            <?php
            break;
    }
                                    
}

new WPLocalSeo();

//[ebs_seo_cp_social_media_only] done
//[ebs_seo_cp_contact_only]
//[ebs_seo_cp_hours_only]

add_shortcode('ebs_seo_cp_hours_only', 'hours_shortcode_1');
add_shortcode('ebs_seo_cp_hours_only_2', 'hours_shortcode_2');
add_shortcode('ebs_seo_cp_hours_only_3', 'hours_shortcode_3');

add_shortcode('ebs_seo_cp_contact_only', 'contact_shortcode_1');
add_shortcode('ebs_seo_cp_contact_only_2', 'contact_shortcode_2');
add_shortcode('ebs_seo_cp_contact_only_3', 'contact_shortcode_3');

add_shortcode('ebs_seo_cp_social_media_only', 'social_media_shortcode_1');
add_shortcode('ebs_seo_cp_social_media_only_2', 'social_media_shortcode_2');
add_shortcode('ebs_seo_cp_social_media_only_3', 'social_media_shortcode_3');

function hours_shortcode_1($atts = array(), $content = '')
{
	return  hours_shortcode(1, $atts, $content);
}

function hours_shortcode_2($atts = array(), $content = '')
{
	return  hours_shortcode(2, $atts, $content = '');
}

function hours_shortcode_3($atts = array(), $content = '')
{
	return  hours_shortcode(3, $atts, $content = '');
}

function hours_shortcode($add = 1, $atts = array(), $content = '')
{
	switch ($add) {
		case 1:
			$business_hours = get_option('lssc-business-hours');
			$contact = get_option('lssc-contact');
		break;
		case 2:
			$business_hours = get_option('lssc-business-hours-2');
			$contact = get_option('lssc-contact-2');
		break;
		case 3:
			$business_hours = get_option('lssc-business-hours-3');
			$contact = get_option('lssc-contact-3');
		break;
	}
	
	$text = '
	<div class="vCard" ' . (($atts['id']) ? 'id="' . $atts['id'] . '"' : '') . ' itemtype="https://schema.org/LocalBusiness" itemscope="">
		<h4 class="heading"><span style="font-weight:bold;">' . $business_hours['hours-heading'] . '</span></h4>
		<span style="display: none;" itemprop="name">' . $contact['name'] . '</span>';
		
	if ($business_hours['sun']['is-hide'] != 'on') {
		$sun_text = (($business_hours['sun']['is-open'] == 'on') ? strtolower('<span class="open-time">' . 
			$business_hours['sun']['opens'] . '</span> - <span class="close-time">' . 
			$business_hours['sun']['closes'] . '</span>') : '<span class="closed">Closed</span>');

		$sun_meta_opens = (($business_hours['sun']['is-open'] == 'on') ? $business_hours['sun']['opens'] : '00:00');
		$sun_meta_closes = (($business_hours['sun']['is-open'] == 'on') ? $business_hours['sun']['closes'] : '00:00');

		$text .= '<span class="ebs-dayname">Sun</span> <time datetime="Su ' . (($business_hours['sun']['is-open'] == 'on') ? date("H:i", strtotime( $sun_meta_opens )) . '-' . date("H:i", strtotime( $sun_meta_closes )) : '00:00-00:00') . '" itemprop="openinghours">' . $sun_text . '</time>
			<div itemtype="https://schema.org/OpeningHoursSpecification" itemscope="" itemprop="openingHoursSpecification">
				<link href="https://purl.org/goodrelations/v1#Sunday" itemprop="dayOfWeek">
				<meta content="' . $sun_meta_opens . '" itemprop="opens">
				<meta content="' . $sun_meta_closes . '" itemprop="closes">
			</div>';
	}
	if ($business_hours['mon']['is-hide'] != 'on') {
		$mon_text = (($business_hours['mon']['is-open'] == 'on') ? strtolower('<span class="open-time">' . $business_hours['mon']['opens'] . '</span> - <span class="close-time">' . $business_hours['mon']['closes'] . '</span>') : '<span class="closed">Closed</span>');
		$mon_meta_opens = (($business_hours['mon']['is-open'] == 'on') ? $business_hours['mon']['opens'] : '00:00');
		$mon_meta_closes = (($business_hours['mon']['is-open'] == 'on') ? $business_hours['mon']['closes'] : '00:00');
		
		$text .= '<span class="ebs-dayname">Mon</span> <time datetime="Mo ' . (($business_hours['mon']['is-open'] == 'on') ? date("H:i", strtotime( $mon_meta_opens )) . '-' . date("H:i", strtotime( $mon_meta_closes )) : '00:00-00:00') . '" itemprop="openinghours">' . $mon_text . '</time>
			<div itemtype="https://schema.org/OpeningHoursSpecification" itemscope="" itemprop="openingHoursSpecification">
				<link href="https://purl.org/goodrelations/v1#Monday" itemprop="dayOfWeek">
				<meta content="' . $mon_meta_opens . '" itemprop="opens">
				<meta content="' . $mon_meta_closes . '" itemprop="closes">
			</div>';
	}
	
	if ($business_hours['tue']['is-hide'] != 'on') {
		$tue_text = (($business_hours['tue']['is-open'] == 'on') ? strtolower('<span class="open-time">' . $business_hours['tue']['opens'] . '</span> - <span class="close-time">' . $business_hours['tue']['closes'] . '</span>') : '<span class="closed">Closed</span>');
		$tue_meta_opens = (($business_hours['tue']['is-open'] == 'on') ? $business_hours['tue']['opens'] : '00:00');
		$tue_meta_closes = (($business_hours['tue']['is-open'] == 'on') ? $business_hours['tue']['closes'] : '00:00');
		
		$text .= '<span class="ebs-dayname">Tue</span> <time datetime="Tu ' . (($business_hours['tue']['is-open'] == 'on') ? date("H:i", strtotime( $tue_meta_opens )) . '-' . date("H:i", strtotime( $tue_meta_closes )) : '00:00-00:00') . '" itemprop="openinghours">' . $tue_text . '</time>
			<div itemtype="https://schema.org/OpeningHoursSpecification" itemscope="" itemprop="openingHoursSpecification">
				<link href="https://purl.org/goodrelations/v1#Tuesday" itemprop="dayOfWeek">
				<meta content="' . $tue_meta_opens . '" itemprop="opens">
				<meta content="' . $tue_meta_closes . '" itemprop="closes">
			</div>';
	}
	
	if ($business_hours['wed']['is-hide'] != 'on') {
		$wed_text = (($business_hours['wed']['is-open'] == 'on') ? strtolower('<span class="open-time">' . $business_hours['wed']['opens'] . '</span> - <span class="close-time">' . $business_hours['wed']['closes'] . '</span>') : '<span class="closed">Closed</span>');
		$wed_meta_opens = (($business_hours['wed']['is-open'] == 'on') ? $business_hours['wed']['opens'] : '00:00');
		$wed_meta_closes = (($business_hours['wed']['is-open'] == 'on') ? $business_hours['wed']['closes'] : '00:00');
		
		$text .= '<span class="ebs-dayname">Wed</span> <time datetime="We ' . (($business_hours['wed']['is-open'] == 'on') ? date("H:i", strtotime( $wed_meta_opens )) . '-' . date("H:i", strtotime( $wed_meta_closes )) : '00:00-00:00') . '" itemprop="openinghours">' . $wed_text . '</time>
			<div itemtype="https://schema.org/OpeningHoursSpecification" itemscope="" itemprop="openingHoursSpecification">
				<link href="https://purl.org/goodrelations/v1#Wednesday" itemprop="dayOfWeek">
				<meta content="' . $wed_meta_opens . '" itemprop="opens">
				<meta content="' . $wed_meta_closes . '" itemprop="closes">
			</div>';
	}
	
	if ($business_hours['thu']['is-hide'] != 'on') {
		$thu_text = (($business_hours['tue']['is-open'] == 'on') ? strtolower('<span class="open-time">' . $business_hours['thu']['opens'] . '</span> - <span class="close-time">' . $business_hours['thu']['closes'] . '</span>') : '<span class="closed">Closed</span>');
		$thu_meta_opens = (($business_hours['tue']['is-open'] == 'on') ? $business_hours['thu']['opens'] : '00:00');
		$thu_meta_closes = (($business_hours['tue']['is-open'] == 'on') ? $business_hours['thu']['closes'] : '00:00');
		
		$text .= '<span class="ebs-dayname">Thur</span> <time datetime="Th ' . (($business_hours['thu']['is-open'] == 'on') ? date("H:i", strtotime( $thu_meta_opens )) . '-' . date("H:i", strtotime( $thu_meta_closes )) : '00:00-00:00') . '" itemprop="openinghours">' . $thu_text . '</time>
			<div itemtype="https://schema.org/OpeningHoursSpecification" itemscope="" itemprop="openingHoursSpecification">
				<link href="https://purl.org/goodrelations/v1#Thursday" itemprop="dayOfWeek">
				<meta content="' . $thu_meta_opens . '" itemprop="opens">
				<meta content="' . $thu_meta_closes . '" itemprop="closes">
			</div>';
	}
	
	if ($business_hours['fri']['is-hide'] != 'on') {
		$fri_text = (($business_hours['fri']['is-open'] == 'on') ? strtolower('<span class="open-time">' . $business_hours['fri']['opens'] . '</span> - <span class="close-time">' . $business_hours['fri']['closes'] . '</span>') : '<span class="closed">Closed</span>');
		$fri_meta_opens = (($business_hours['fri']['is-open'] == 'on') ? $business_hours['fri']['opens'] : '00:00');
		$fri_meta_closes = (($business_hours['fri']['is-open'] == 'on') ? $business_hours['fri']['closes'] : '00:00');
		
		$text .= '<span class="ebs-dayname">Fri</span> <time datetime="Fr ' . (($business_hours['fri']['is-open'] == 'on') ? date("H:i", strtotime( $fri_meta_opens )) . '-' . date("H:i", strtotime( $fri_meta_closes )) : '00:00-00:00') . '" itemprop="openinghours">' . $fri_text . '</time>
			<div itemtype="https://schema.org/OpeningHoursSpecification" itemscope="" itemprop="openingHoursSpecification">
				<link href="https://purl.org/goodrelations/v1#Friday" itemprop="dayOfWeek">
				<meta content="' . $fri_meta_opens . '" itemprop="opens">
				<meta content="' . $fri_meta_closes . '" itemprop="closes">
			</div>';
	}
	
	if ($business_hours['sat']['is-hide'] != 'on') {
		$sat_text = (($business_hours['sat']['is-open'] == 'on') ? strtolower('<span class="open-time">' . $business_hours['sat']['opens'] . '</span> - <span class="close-time">' . $business_hours['sat']['closes'] . '</span>') : '<span class="closed">Closed</span>');
		$sat_meta_opens = (($business_hours['sat']['is-open'] == 'on') ? $business_hours['sat']['opens'] : '00:00');
		$sat_meta_closes = (($business_hours['sat']['is-open'] == 'on') ? $business_hours['sat']['closes'] : '00:00');
		
		$text .= '<span class="ebs-dayname">Sat</span> <time datetime="Sa ' . (($business_hours['sat']['is-open'] == 'on') ? date("H:i", strtotime( $sat_meta_opens )) . '-' . date("H:i", strtotime( $sat_meta_closes )) : '00:00-00:00') . '" itemprop="openinghours">' . $sat_text . '</time>
			<div itemtype="https://schema.org/OpeningHoursSpecification" itemscope="" itemprop="openingHoursSpecification">
				<link href="https://purl.org/goodrelations/v1#Saturday" itemprop="dayOfWeek">
				<meta content="' . $sat_meta_opens . '" itemprop="opens">
				<meta content="' . $sat_meta_closes . '" itemprop="closes">
			</div>';
	}
	$text .= '</div>';
	
	return $text;
}



function contact_shortcode_1($atts = array(), $content = '')
{
	return contact_shortcode(1, $atts, $content);
}

function contact_shortcode_2($atts = array(), $content = '')
{
	return contact_shortcode(2, $atts, $content);
}

function contact_shortcode_3($atts = array(), $content = '')
{
	return contact_shortcode(3, $atts, $content);
}

function contact_shortcode($add, $atts = array(), $content = '')
{
	switch ($add) {
		case 1: $contact = get_option('lssc-contact');
			break;
		case 2:
			$contact = get_option('lssc-contact-2');
			break;
		case 3:
			$contact = get_option('lssc-contact-3');
			break;
	}
	
	$map = $contact['street'] . ' ' . $contact['unit-number'] . ' ' . $contact['city'] . ' ' . $contact['state'] . ' ' . $contact['zip'];
	$text = '<div class="vCard" ' . (($atts['id']) ? 'id="' . $atts['id'] . '"' : '') . ' " itemtype="https://schema.org/LocalBusiness" itemscope="">
		<meta content="https://maps.google.com/maps?q=' . $map . '&amp;output=embed" itemprop="map">
			<div style="font-weight:bold;" class="fn" itemprop="name">' . $contact['name'] . '</div>
			<img class="lssc_image_logo" itemprop="image" src="' . ((is_ssl()) ? 'https://' : 'http://') . $contact['logo'] . '" alt="' . $contact['name'] . '"/>
		<div class="adr" itemprop="address" itemtype="https://schema.org/PostalAddress" itemscope="">';
	
	if ($contact['street']) {
		$text .= '<span itemprop="streetAddress" class="street-address">' . $contact['street'] . ' ' . $contact['unit-number'] . '</span><br class="street-address">';
	}
	if ($contact['city']) {
		$text .= '<span itemprop="addressLocality" class="locality">' . $contact['city'] . '</span>, ';
	}
	if ($contact['state'] ||  $contact['zip']) {
	$text .= '<span itemprop="addressRegion" class="region">' . $contact['state'] . '</span>
		<span itemprop="postalcode" class="postal-code">' . $contact['zip'] . '</span><br>';
	};
	if ($contact['country']) {
	$text .= '
		<span itemprop="addressCountry">' . $contact['country'] . '</span> ';
	}
	$text .= '</div>';
	if ($contact['phone'][2] != '' || $contact['phone'][3] != '') {
			$phone2 = ($contact['phone'][2]) ? ' (' . $contact['phone'][2] . ') ' : '';
            $title =  $contact['phone'][1] . $phone2 . $contact['phone'][3] . '-' . $contact['phone'][4];
	$text .= '<div class="tel1">
			<meta content="Work" class="type"><span style="font-weight:bold;">Telephone: </span> <meta content="' . $title . '" itemprop="telephone"><abbr title="' . $title . '" class="value">' . $contact['phone'][1] . 
			$phone2 . $contact['phone'][3] . '-' . $contact['phone'][4] . '</abbr>
		</div>';
	}
	
	if ($contact['alt-phone'][2] != '' || $contact['alt-phone'][3] != '') {
			$contact2 = ($contact['alt-phone'][2]) ? ' (' . $contact['alt-phone'][2] . ') ' : '';
            $title =  $contact['alt-phone'][1] . $contact2 . $contact['alt-phone'][3] . '-' . $contact['alt-phone'][4];
	$text .= '<div class="tel2">
			<meta content="Work" class="type"><span style="font-weight:bold;">Telephone: </span> <meta content="' . $title . '" itemprop="telephone"><abbr title="' . $title . '" class="value">' . $contact['alt-phone'][1] . 
			$contact2 . $contact['alt-phone'][3] . '-' . $contact['alt-phone'][4] . '</abbr>
		</div>';
	}
	
	if ($contact['fax'][2] != '' || $contact['fax'][3] != '') {
			$fax2 = ($contact['fax'][2]) ? ' (' . $contact['fax'][2] . ') ' : '';
            $title =  $contact['fax'][1] . $fax2 . $contact['fax'][3] . '-' . $contact['fax'][4];
	$text .= '<div class="fax">
			<meta content="Fax" class="type"><span style="font-weight:bold;">Fax: </span><meta content="' . $title . '" itemprop="faxnumber"><abbr title="' . $title . '" class="value">' . $contact['fax'][1] . $fax2 . $contact['fax'][3] . '-' . $contact['fax'][4] . '</abbr>
		</div>';
	}
	
	if ($contact['email']) {
	$text .= '<span style="font-weight:bold;">Email: </span> <a href="mailto:' . $contact['email'] . '" itemprop="email" class="email">' . $contact['email'] . '</a><br>

		<!--<div itemprop="geo" itemtype="https://schema.org/GeoCoordinates" itemscope="">
			<meta content="42.684045" itemprop="latitude">
			<meta content="-83.035554" itemprop="longitude">
		</div>-->';

	}
	
	$text .= '</div>';
	
	return $text;
}

function social_media_shortcode_1($atts = array(), $content = '')
{
	return social_media_shortcode(1, $atts, $content);
}

function social_media_shortcode_2($atts = array(), $content = '')
{
	return social_media_shortcode(2, $atts, $content);
}

function social_media_shortcode_3($atts = array(), $content = '')
{
	return social_media_shortcode(3, $atts, $content);
}
function social_media_shortcode($add, $atts = array(), $content = '')
{
	switch ($add) {
		case 1: 
			$business_socials = get_option('lssc-business-socials');
			$order_socials = get_option('lssc-business-socials-order1');
			$contact = get_option('lssc-contact');
		break;
		case 2:
			$business_socials = get_option('lssc-business-socials-2');
            $order_socials = get_option('lssc-business-socials-order2');
			$contact = get_option('lssc-contact-2');
		break;
		case 3:
			$business_socials = get_option('lssc-business-socials-3');
            $order_socials = get_option('lssc-business-socials-order3');
			$contact = get_option('lssc-contact-3');
		break;
	}
	
        if (!is_array($order_socials)) {
			$order_socials = array(
			'facebook',
			'youtube',
			'twitter',
			'linked-in',
			'google-plus',
			'pinterest',
			'google-places',
			'yelp',
			'hot-frog',
			'flickr',
			'tumblr',
			'delicious',
			'four-square',
			'stumble-upon',
			'merchant-circle',
			'instagram',
			'houzz',
			'vimeo'
			);
        }
        
	$social_buttons = array(
			'media-block-full-page',
			'facebook',
			'youtube',
			'google-places',
			'yelp',
			'hot-frog',
			'flickr',
			'tumblr',
			'delicious', 
			'twitter',
			'linked-in',
			'google-plus',
			'pinterest',
			'four-square',
			'stumble-upon',
			'merchant-circle',
			'instagram',
			'houzz',
			'vimeo'
		);
        
	$text = '<div class="vCard" ' . (($atts['id']) ? 'id="' . $atts['id'] . '"' : '') . ' itemtype="https://schema.org/LocalBusiness" itemscope="">';
	$text .= '<span style="display: none;" itemprop="name">' . $contact['name'] . '</span>';
	$text .= '<div>';

	$socials = array(
					'facebook' => 'Facebook.png', 
					'youtube' => 'Youtube.png',
					'google-places' => 'Google Places Logo.png',
					'yelp' => 'Yelp Logo.png',
					'hot-frog' => 'Hot Frog Logo.png',
					'flickr' => 'Flickr Logo.png',
					'tumblr' => 'Tumblr Logo.png',
					'delicious' => 'Del.icio.us Logo.png',
					'twitter' => 'Twitter.png',
					'linked-in' => 'Linked In.png',
					'google-plus' => 'Google Plus Logo.png',
					'pinterest' => 'Pinterest.png',
					'four-square' => 'Foursquare Logo.png',
					'stumble-upon' => 'Stumble Upon Logo.png',
					'merchant-circle' => 'Circle Logo.png',
					'instagram'  => 'Instagram.png',
					'houzz'  => 'Houzz.png',
					'vimeo' => 'vimeo.png'
				);

	$contact = get_option('lssc-contact');		
	$business_name = $contact['name'];

	$alt = array(
				'facebook' => 'Like ' . $business_name . ' on Facebook',
				'youtube' => 'Subscribe to ' . $business_name . ' on YouTube', 
				'google-places' => 'Like ' . $business_name . ' on Google Places', 
				'twitter' => 'Follow ' . $business_name . ' on Twitter', 
				'linked-in' => 'Connect with ' . $business_name . ' on LinkedIn', 
				'google-plus' => '+1 ' . $business_name . ' on Google Plus', 					 
				'yelp' => '' . $business_name . ' on Yelp', 
				'hot-frog' => '' . $business_name . ' on Hot Frog', 
				'flickr' => '' . $business_name . ' on Flickr', 
				'tumblr' => '' . $business_name . ' on Tumblr', 
				'delicious' => '' . $business_name . ' on Delicious', 
				'pinterest' => '' . $business_name . ' on Pinterest', 
				'four-square' => '' . $business_name . ' on Four Square', 
				'stumble-upon' => '' . $business_name . ' on Stumble Upon', 
				'merchant-circle' => '' . $business_name . ' on Merchant Circle',
				'instagram' => '' . $business_name . ' on Instagram',
				'houzz' => '' . $business_name . ' on Houzz',
				'vimeo' => '' . $business_name . ' on Vimeo'
			);
    $social_icons_http_url = (is_ssl()) ? 'https://' : 'http://';
	foreach ($order_socials as $idx ) {
		$plugin_url = plugin_dir_url(__FILE__);
                $btn = $socials[$idx];
		if ($business_socials[$idx] != '') {
			$text .= '<a title="' . $alt[$idx] . '"href="' . $social_icons_http_url . $business_socials[$idx] . '" target="_blank"><img alt="' . 
                                $alt[$idx] . '" src="' . (($business_socials[$idx . '-icon']) ? $social_icons_http_url . $business_socials[$idx . '-icon'] : $plugin_url . '/images/' . $btn) . '"></a>';
		}
	}
	$text .= '</div></div>';


	return $text;
}

//add_action('wp_head', 'localSeoFrontCss');

function localSeoFrontCss()
{
    wp_enqueue_style('localSeoFrontCss', plugin_dir_url() . '/wp-local-seo-contact/styles/front-end.css');
}

//Add spaces to each social icons
add_action('wp_footer', function(){
	?>
	
	<script>
		jQuery(document).ready(function(){
			jQuery('.vCard img').attr('style', 'margin-right: 8px;');
			jQuery('.vCard img').last().attr('style', 'margin-right: 0;');
		});
	</script>
	<style>
	.lssc_image_logo {
		max-width: 1px;
		position: absolute;
	}
	</style>
	
	<?php
});