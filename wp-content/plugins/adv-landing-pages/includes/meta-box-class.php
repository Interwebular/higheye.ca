<?php

/**
 * Description of LandingPageMeta
 * 
 * @version 1.0.1
 * @author Edesa
 */

class LandingPageMeta
{
    const METABOX_PRIORITY_HIGH     = 'high';
    const METABOX_PRIORITY_DEFAULT  = 'default';
    const METABOX_PRIORITY_LOW      = 'low';
    const METABOX_CONTEXT_NORMAL    = 'normal';
    const METABOX_CONTEXT_ADVANCED  = 'advanced';
    const METABOX_CONTEXT_SIDE      = 'side';

    private $_metaBoxId;

    /**
     * The post types where this meta box should appear
     * 
     * @var array 
     */

    private $_applyToPostTypes;

    /**
     *
     * @var array
     */

    private $_options = array();

    public function __construct($id, $applyToPostTypes = array(), $options = array())
    {

        $defaults['title'] = 'Custom Meta Box';    
        $defaults['priority'] = LandingPageMeta::METABOX_PRIORITY_DEFAULT;    
        $this->_options = $options + $defaults;
        $this->_metaBoxId = $id;
        if (!is_array($applyToPostTypes)) {
            $this->_applyToPostTypes = array($applyToPostTypes);
        }
        else {
            $this->_applyToPostTypes = $applyToPostTypes;
        }

        add_action('add_meta_boxes', array($this, 'addMetaBox'));
        add_action('save_post', array($this, 'savePostData'));        
    }

    

    /**

     * Adds a box to the main column on the Post and Page edit screens

     * 

     */

    public function addMetaBox() {
        foreach ($this->_applyToPostTypes as $postType) {
            add_meta_box(
                $this->_metaBoxId, $this->_options['title'],
                array($this, 'innerCustomBox'),
                $postType,

                $this->_options['context'],

                $this->_options['priority']

            );

        }

    }

    /**
     * This function will display the meta box
     * 
     * @param type $post
     */
    public function innerCustomBox( $post ){

	wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );

	$data = get_post_meta($post->ID, $this->_metaBoxId, true);

	$data2 = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents', true);
        
        $quote = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_quote', true);
	
        $formComponentsTextBoxShort = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_textbox_short', true);
	$formComponentsTextPhone = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_textbox_phone', true);

    $formComponentsTextBoxLong = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_textbox_long', true);

	$formComponentsRadioButtonLabel = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_radioButtonLabel', true);
	$formComponentsRadioButtonChoices = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_radioButtonChoices', true);

	$formComponentsCheckboxLabel = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_checkboxLabel', true);
	$formComponentsCheckboxChoices = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_checkboxChoices', true);

	//$formComponentsDropdown = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_dropdown', true);
    $formComponentsDropdownLabel = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_dropdownLabel', true);
    $formComponentsDropdownChoices = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_dropdownChoices', true);
	?>

	<div id="alp_metabox" class="alp-metabox"> 
	<?php
	//$test = explode(',', $data['sendpepper-contact-tags']);
	//$test = array_filter( array_map('trim', $test) );
	//echo "<pre>";
	//var_dump( implode('*/*', $test) );
	//echo "</pre>";
	?>
	<p>
		<input type="checkbox" name="alp[enable-landing-page]" <?php echo ($data['enable-landing-page'] == 'on') ? 'checked="checked"' : '' ?> />
		<label for="enable-landing-page" style="display:inline">Enable Landing Page</label>
	</p>
	<p>
		<label for="enable-landing-page" style="display:inline">Expired Date</label><br />
		<input type="text" class="expiry-date date-picker" name="alp[expired-date]" value="<?php echo $data['expired-date'] ?>" />
                <input type="checkbox" class="cb-ongoing" name="alp[ongoing]" <?php echo ($data['ongoing'] == 'on') ? 'checked="checked"' : '' ?> />Ongoing Offer Page
                <span class="expiry-date-note"></span>
	</p>
	<p>
		<label for="summary" style="display:inline">Summary</label><br />
		<textarea type="checkbox" name="alp[summary]" class="summary"><?php echo $data['summary'] ?></textarea>	
                <br /><em>Will be used on the parent specials page (if activated by shortcode). If not defined, the first few words of the content will be used.</em>
	</p>
	<?php
	//Get the settings database
	$alp_settings = get_option("alp_settings");
	if($alp_settings['sendpepper_api'] == "on"):
	?>
	<div class="sendpepper_area">
		<p>
			<input type="checkbox" name="alp[use-sendpepper]" <?php echo ($data['use-sendpepper'] == 'on') ? 'checked="checked"' : '' ?> />
			<label for="use-sendpepper" style="display:inline">Use Sendpepper</label> <em>Check this if you want to integrate Sendpepper (Only works on Download and User Form templates).</em>
		</p>
		<p><label for="sendpepper-api-id">Sendpepper API ID</label><input type="text" name="alp[sendpepper-api-id]" value="<?php echo $data['sendpepper-api-id']; ?>" /></p>
		<p><label for="sendpepper-api-key">Sendpepper API Key</label><input type="text" name="alp[sendpepper-api-key]" value="<?php echo $data['sendpepper-api-key']; ?>" /></p>
		<p><label for="sendpepper-contact-tags">Sendpepper Contact Tags</label><em>Contact tags must be separrated by a <b>comma</b> symbol. <b>Example:</b>Tag1, Tag2, Tag3</em><br /><input style="width: 100%;" type="text" name="alp[sendpepper-contact-tags]" value="<?php echo $data['sendpepper-contact-tags']; ?>" /></p>
	</div>
	<?php endif; ?>
	<p>
		<input type="checkbox" name="alp[use-custom-template-page]" <?php echo ($data['use-custom-template-page'] == 'on') ? 'checked="checked"' : '' ?> />
		<label for="use-custom-template-page" style="display:inline">Use Custom Template</label> <em>Check this if the theme has no "full width template"</em>
	</p>
	<p>

		<label for="sub-header">Select Layout</label>
		<input type="radio" class="radio-call-now" value="call-now" name="alp[layout]" <?php echo ($data['layout'] == 'call-now') ? 'checked="checked"' : '' ?> /> Call Now &nbsp;
		<input type="radio" class="radio-download" value="download" name="alp[layout]" <?php echo ($data['layout'] == 'download') ? 'checked="checked"' : '' ?> /> Download &nbsp;
		<input type="radio" class="radio-user-form" value="user-form" name="alp[layout]" <?php echo ($data['layout'] == 'user-form' || $data['layout'] != 'call-now' && $data['layout'] != 'download') ? 'checked="checked"' : '' ?> /> User Form
        <input type="radio" class="radio-print-coupon" value="print-coupon" name="alp[layout]" <?php echo ($data['layout'] == 'print-coupon') ? 'checked="checked"' : '' ?> />Print Coupon&nbsp;
		<div id="alp_notif_form_option"></div>
	</p> 

	<p>

		<label for="header-text-color">Header Text Color</label>

		<input class="wpvs-color-picker-input" size="120" type="text" name="alp[header-text-color]" id="header-text-color" value="<?php echo ($data['header-text-color'] != '') ? $data['header-text-color'] : '#000'; ?>" />

		<em>Applied if custom template is used</em>

                <div class="wpvs-color-picker" rel="header-text-color"></div>

	</p>

	<p><label for="sub-header">Sub Header Text</label><input size="120" type="text" name="alp[sub-header]" id="sub-header" value="<?php echo $data['sub-header']; ?>" /></p>

	<p>

		<label for="subheader-text-color">SubHeader Text Color</label>

		<input class="wpvs-color-picker-input" size="120" type="text" name="alp[subheader-text-color]" id="subheader-text-color" value="<?php echo ($data['subheader-text-color'] != '') ? $data['subheader-text-color'] : '#000'; ?>" />

                <div class="wpvs-color-picker" rel="subheader-text-color"></div>

	</p>

	<p>

		<label for="bg-color">Background Color</label>

		<input class="wpvs-color-picker-input" size="120" type="text" name="alp[bg-color]" id="bg-color" value="<?php echo ($data['bg-color'] != '') ? $data['bg-color'] : '#000'; ?>" />

                <div class="wpvs-color-picker" rel="bg-color"></div>

		<input class="alp-no-background" type="checkbox" name="alp[no-bg-color]" <?php echo ($data['no-bg-color'] == 'on') ? 'checked="checked"' : '' ?> /> No Background Color

	</p>
	<p>

		<label for="text-color">Text Color</label>

		<input class="wpvs-color-picker-input" size="120" type="text" name="alp[text-color]" id="text-color" value="<?php echo ($data['text-color'] != '') ? $data['text-color'] : '#000'; ?>" />

                <div class="wpvs-color-picker" rel="text-color"></div>

	</p>        

	<p><label for="action-text-msg">Action Message Text</label><input size="120" type="text" name="alp[action-text-msg]" id="bg-color" value="<?php echo $data['action-text-msg']; ?>" /></p>

	<p><label for="button-text">Button Text</label><input size="120" type="text" name="alp[button-text]" id="bg-color" value="<?php echo $data['button-text']; ?>" /></p>

	<p class="call-now-only"><label for="button-url">Button URL</label><input size="120" type="text" name="alp[button-url]" id="button-url" value="<?php echo $data['button-url']; ?>" /></p>
        <p class="print-coupon-only"><label for="button-url">Coupon Url</label><input size="120" type="text" name="alp[coupon-url]" id="coupon-url" value="<?php echo $data['coupon-url']; ?>" /></p>
	<p>
            <label for="button-color">Button Color</label>
            <input class="wpvs-color-picker-input" size="120" type="text" name="alp[button-color]" id="button-color" value="<?php echo ($data['button-color']) ? $data['button-color'] : '#aaa'; ?>" />
            <div class="wpvs-color-picker" rel="button-color"></div>
        </p>
	<p>
            <label for="button-border-color">Button Border Color</label>
            <input class="wpvs-color-picker-input" size="120" type="text" name="alp[button-border-color]" id="button-border-color" value="<?php echo ($data['button-border-color']) ? $data['button-border-color'] : '#aaa'; ?>" />
            <div class="wpvs-color-picker" rel="button-border-color"></div>
        </p>
	<p>
            <label for="button-text-color">Button Text Color</label>
            <input class="wpvs-color-picker-input" size="120" type="text" name="alp[button-text-color]" id="button-text-color" value="<?php echo ($data['button-text-color']) ? $data['button-text-color'] : '#000'; ?>" />
            <div class="wpvs-color-picker" rel="button-text-color"></div>
        </p>

	<p class="user-form-only">
		<label for="thank-you-msg">Thank You Message</label>		
		<textarea name="alp[thank-you-msg]" style="width:91%"><?php echo $data['thank-you-msg'] ?></textarea>
	</p>

	
	<p class="user-form-only">
		<input type="checkbox" name="alp[use-thank-you-page]" <?php echo ($data['use-thank-you-page'] == 'on') ? 'checked="checked"' : '' ?> />
		<label for="use-thank-you-page" style="display:inline">Use Thank You Page</label>
	</p>

	<p class="user-form-only">

		<label for="thank-you-page">Thank You Page</label>

		<input type="text" style="width:91%" name="alp[thank-you-page]" value="<?php echo $data['thank-you-page'] ?>" />		

	</p>

	

	<p class="user-form-only">

            <label for="form-bg-color">Form Background Color</label>

            <input class="wpvs-color-picker-input" size="120" type="text" name="alp[form-bg-color]" id="form-bg-color" value="<?php echo ($data['form-bg-color']) ? $data['form-bg-color'] : '#fff'; ?>" />

            <div class="wpvs-color-picker" rel="form-bg-color"></div>

        </p>

	<p id="form-textcolor">
		<label for="action-text-msg-color">Form Text Color</label>
		<input class="wpvs-color-picker-input" size="120" type="text" name="alp[action-text-msg-color]" id="action-text-msg-color" value="<?php echo ($data['action-text-msg-color']) ? $data['action-text-msg-color'] : '#000'; ?>" />
		<div class="wpvs-color-picker" rel="action-text-msg-color"></div>
	</p>
        <div class="download-email-settings">
            <h3>User Download Email Settings</h3>
            <p
                    <label for="download-file">Download File (upload the file via Media and copy the url of the file and remove the root></label>
                    E.g. /wp-content/uploads/2013/09/87356587.zip<br />
                    <input type="text" style="width:91%" name="alp[download-file]" style="width:91%" value="<?php echo $data['download-file'] ?>" />
            </p>
            <p>
                    <label for="download-email-subject">Download Email Subject</label>		
                    <input type="text" name="alp[download-email-subject]" style="width:91%" value="<?php echo $data['download-email-subject'] ?>" />
            </p>

            <p>
                    <label for="download-email">Download Email Message</label>		
                    <textarea name="alp[download-email]" style="width:91%"><?php echo $data['download-email'] ?></textarea>
            </p>
            <div>
                <h5>Download Email Shortcodes</h5>
                file = [download-link]<br />
                form fields - see shortcodes below
            </div>
        
	<!--<p class="download-form-only">
		<label for="alp-download-from-name">From Name - appears on the email sent to user</label><input size="120" type="text" name="alp[download-from-name]" id="alp-download-from-name" value="<?php echo ($data['download-from-name']) ? $data['download-from-name'] :  "WordJack Contact"; ?>" />		
	</p>
	<p class="download-form-only">
		<label for="alp-download-from-email">From Email - appears on the email sent to user</label><input size="120" type="text" name="alp[download-from-email]" id="alp-donwload-from-email" value="<?php echo ($data['download-from-email']) ? $data['download-from-email'] : 'contact@wordjack.com'; ?>" />
	</p>-->

        </div>

        <div class="admin-notification">
            <h3>Admin Email Settings</h3>
	<p>
		<label for="download-file">User Email Field Shortcode - <em>see form components below</em>
        <a target=" _blank" href="<?php echo ALP_BASEURL; ?>how-to/whatsthis.jpg">what's this?</a></label>		
		<input type="text" style="width:91%" name="alp[email-field]" style="width:91%" value="<?php echo $data['email-field'] ?>" />
	</p>

	<p>
		<label for="download-file">User Name Field Shortcode - 
        <a target=" _blank" href="<?php echo ALP_BASEURL; ?>how-to/whatsthis.jpg">what's this?</a> <em>see form components below</em></label>
		<input type="text" style="width:91%" name="alp[name-field]" style="width:91%" value="<?php echo $data['name-field'] ?>" />
	</p>

            <p class="user-form-only">
                    <label for="alp-from-name">Admin From Name</label><input size="120" type="text" name="alp[from-name]" id="alp-from-name" value="<?php echo ($data['from-name']) ? $data['from-name'] :  "WordJack Contact"; ?>" />		
            </p>
            <p class="user-form-only">
                    <label for="alp-from-email">Admin From Email</label><input size="120" type="text" name="alp[from-email]" id="alp-from-email" value="<?php echo ($data['from-email']) ? $data['from-email'] : 'contact@wordjack.com'; ?>" />
            </p>
            <p class="user-form-only">
                    <label for="alp-recepient-email">Admin Recepient Email Address</label><input size="120" type="text" name="alp[alp-recepient-email]" id="alp-recepient-email" value="<?php echo $data['alp-recepient-email']; ?>" />		
            </p>
        </div>
	<div class="user-form-only">
		<h4>Form Components</h4>
		<table class="tbl-form-elements wp-list-table widefat " width="100%" cellspacing="5" cellpadding="5" align="left">
			<thead><tr>
				<th width="10%">Field Type</th><th width="10%">Include It</th><th width="80%"><span class="shortcode-old">Shortcode</span>Default Properties</th>
			</tr></thead>
			<tr>
				<td>Text Box (Short)</td>
				<td><input type="checkbox" name="formcomp[include-text-box-short]" <?php echo ($data2['include-text-box-short'] == 'on') ? 'checked="checked"' : '' ?>/></td>
				<td>
				<?php

				if (is_array($formComponentsTextBoxShort)) {
                                    foreach ($formComponentsTextBoxShort as $tbs) {
                                        if ($tbs != '') {
                                            echo '<p><span class="shortcode-old">%shorttext-'. $tbs . '%</span><input type="text" name="textbox-short[]" value="' . $tbs . '" /><a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>';
                                        }
                                    }
				}
				?>

				<div class="textbox-short-container">
				</div>
				<span class="add-more-element add-textbox-short">(+) Add More</span>
				</td>
			</tr>
			<tr>
				<td>Phone</td>
				<td><input type="checkbox" name="formcomp[include-text-phone]" <?php echo ($data2['include-text-phone'] == 'on') ? 'checked="checked"' : '' ?>/></td>
				<td>
				<?php
				if (is_array($formComponentsTextPhone)) {
					foreach ($formComponentsTextPhone as $tbs) {
                                            if ($tbs != '') {
						echo '<p><span class="shortcode-old">%phone-' . $tbs . '%</span><input type="text" name="textbox-phone[]" value="' . $tbs . '" /><a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>';
                                            }
					}
				}
				?>
				<div class="textbox-phone-container"></div>
				<span class="add-more-element add-textbox-phone">(+) Add More</span>
				</td>
			</tr>			
			<tr>
				<td>Text Box (Long)</td>
				<td><input type="checkbox" name="formcomp[include-text-box-long]" <?php echo ($data2['include-text-box-long'] == 'on') ? 'checked="checked"' : '' ?>/></td>
				<td>
                                <?php
				if (is_array($formComponentsTextBoxLong)) {
					foreach ($formComponentsTextBoxLong as $tbs) {
                                                if ($tbs != '') {
                                                    echo '<p><span class="shortcode-old">%longtext-' . $tbs . '%</span><input type="text" name="textbox-long[]" value="' . $tbs . '" /><a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>';
                                                }
					}
				}
				?>
				<div class="textbox-long-container">
				</div>
				<span class="add-more-element add-textbox-long">(+) Add More</span>
                                </td>
			</tr>
			<tr>
				<td>Radio Buttons</td>
				<td><input type="checkbox" name="formcomp[include-radio-buttons]" <?php echo ($data2['include-radio-buttons'] == 'on') ? 'checked="checked"' : '' ?>/></td>
				<td>
                                <?php
				if (is_array($formComponentsRadioButtonLabel)) {

					foreach ($formComponentsRadioButtonLabel as $idx => $tbs) {

                                                if ($tbs != '') {
                                                    //echo '<p><input type="text" name="radio-button[]" value="' . $tbs . '" /><a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>';
							echo '<p><span class="shortcode-old">%radio-' . $tbs . '%</span><input type="text" name="radioButtonLabel[' . $idx . ']" value="' . $tbs . '" />';
							echo '<textarea name="radioButtonChoices[' . $idx . ']">' . $formComponentsRadioButtonChoices[$idx] . '</textarea>';
							echo '<a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>';


                                                }

					}
					$idx += 1;

				}

				?>
				<div class="radio-button-container">
				</div>
                                <span class="add-more-element add-radio-button">(+) Add More</span>
                                </td>                                
			</tr>
			<tr>
				<td>Drop Down Menu</td>
				<td><input type="checkbox" name="formcomp[include-drop-down-menu]" <?php echo ($data2['include-drop-down-menu'] == 'on') ? 'checked="checked"' : '' ?>/></td>
				<td>
                                <?php
                                $idx = 0;
				if (is_array($formComponentsDropdownLabel)) {

					foreach ($formComponentsDropdownLabel as $idx => $tbs) {

                                                if ($tbs != '') {
                                                    echo '<p><span class="shortcode-old">%dp-' . $tbs . '%</span><input type="text" name="dropdownLabel[' . $idx . ']" value="' . $tbs . '" />';
                                                    echo '<textarea name="dropdownChoices[' . $idx . ']">' . $formComponentsDropdownChoices[$idx] . '</textarea>';
                                                    echo '<a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>';

                                                }

					}
                                        $idx += 1;

				}

				?>
				<div class="dropdown-container">
				</div>
                                    <!--<input type="text" name="dropdownLabel[<?php echo $idx; ?>]" />
                                    <textarea name="dropdownChoices[<?php echo $idx; ?>]"></textarea>-->
                                <span class="add-more-element add-dropdown">(+) Add More</span>
                                </td>

			</tr>

			<tr>

				<td>Checkboxes</td>

				<td><input type="checkbox" name="formcomp[include-checkboxes]" <?php echo ($data2['include-checkboxes'] == 'on') ? 'checked="checked"' : '' ?>/></td>
				<td>
                                <?php

				if (is_array($formComponentsCheckboxLabel)) {
					foreach ($formComponentsCheckboxLabel as $idx => $tbs) {
                                                if ($tbs != '') {
							echo '<p><span class="shortcode-old">%cb-' . $tbs . '%</span><input type="text" name="checkboxLabel[' . $idx . ']" value="' . $tbs . '" />';
							echo '<textarea name="checkboxChoices[' . $idx . ']">' . $formComponentsCheckboxChoices[$idx] . '</textarea>';
							echo '<a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>';


                                                }

					}
					$idx += 1;

				}

				?>
				<div class="checkbox-container">
				</div>
                                <span class="add-more-element add-checkbox">(+) Add More</span>
                                </td>                                
			</tr>

		</table>
                
	<div style="clear:both">
		<ul style="font-style:italic">
			<li>To make field required, just add [Req] in front of the label, e.g [Req]Name</li>
			<li>To enter options/values for drop down menu, checkboxes, and radio button, separate each option by line (one item per line)</li>
                </ul>
	</div>                
                <div id="fields">
                    <h4>Below is the latest and the recommended way to add elements to the form. All the elements added below will be appended to the elements added above.</h4>
<div class="component-options-header"><span class="shortcode">Shortcode</span><span class="delete">Delete</span><span class="fieldtype">Type</span><span class="is-required">Is Required</span><span class="label">Label</span><span class="values">Values</span></div>
                    <ul class="form-fields">     

<?php
        $newFields = get_post_meta($post->ID, $this->_metaBoxId . '_formcomponents_fields', true);
        foreach ($newFields as $idx => $newf) {
            $type = $newf['type'];
            $fieldId = $idx;
            $label = $newf['label'];
            $options = $newf['options'];
            $isRequired = $newf['is-required'];
            $isRequiredField = '<span class="is-required"><input title="Required?" type="checkbox" ' . (($isRequired) ? 'checked="checked"' : '') . ' name="field[' . $fieldId . '][is-required]" /></span>';
            $deleteField = '<span class="delete"><button class="delete-field" id="delete-' . $fieldId . '">Delete</button></span>';
            
            switch ($type) {
            case 'text':
                $ret = $deleteField . '<span class="fieldtype">Text</span>' . $isRequiredField . '<input type="text" class="label" value="' . $label . '" placeholder="label" name="field[' . $fieldId . '][label]">';
                break;
            case 'checkbox':
                $ret = $deleteField . '<span class="fieldtype">Checkbox</span>' . $isRequiredField . '<input type="text" class="label" value="' . $label . '" placeholder="label"  name="field[' . $fieldId . '][label]">';
                $ret .= '<textarea name="field[' . $fieldId . '][options]">' . $options . '</textarea>';
                break;
            case 'radio':
                $ret = $deleteField . '<span class="fieldtype">Radio</span>' . $isRequiredField . '<input type="text" class="label" value="' . $label . '" placeholder="label"  name="field[' . $fieldId . '][label]">';
                $ret .= '<textarea name="field[' . $fieldId . '][options]">' . $options . '</textarea>';
                
                break;
            case 'dropdown':
                $ret = $deleteField . '<span class="fieldtype">Dropdown</span>' . $isRequiredField . '<input type="text" class="label" value="' . $label . '" placeholder="label"  name="field[' . $fieldId . '][label]">';
                $ret .= '<textarea name="field[' . $fieldId . '][options]">' . $options . '</textarea>';
                break;
            case 'textarea':
                $ret = $deleteField . '<span class="fieldtype">Textarea</span>' . $isRequiredField . '<input type="text" class="label" value="' . $label . '" placeholder="label"  name="field[' . $fieldId . '][label]">';                             
                break;
            case 'phone':
                $ret = $deleteField . '<span class="fieldtype">Phone</span>' . $isRequiredField . '<input type="text" class="label" value="' . $label . '" placeholder="label"  name="field[' . $fieldId . '][label]">';                             
                break;
            case 'email':
                $ret = $deleteField . '<span class="fieldtype">Email</span>' . $isRequiredField . '<input type="text" class="label" value="' . $label . '" placeholder="label"  name="field[' . $fieldId . '][label]">';                             
                break;            
            }
            
            $ret .= '<input type="hidden" value="' . $type . '" name="field[' . $fieldId . '][type]" />';
            
            $ret2 = '<li id="item-' . $fieldId . '"><span class="shortcode"><input type="text" readonly="readonly" value="%field-' . $fieldId . '%" /></span>' . $ret . '</li>';
            echo $ret2;
        }
?>
            </ul>
                <select name="field-type" id="field-type">
                    <option value="text">Text</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="radio">Radio</option>
                    <option value="dropdown">Drop Down</option>
                    <option value="textarea">Textarea</option>
                    <option value="phone">Phone</option>
                    <option value="email">Email</option>                    
                </select>
                <input name="field-id" id="field-id" type="text" placeholder="Field Label"/>
                <button id="add-field" placeholder="Field Id">Add</button>    
                
                <div style="clear:both">
                        <ul style="font-style:italic">

                                <li>To enter options/values for drop down menu, checkboxes, and radio button, separate each option by line (one item per line)</li>
                                <li>Check the box to make the field required.</li>
                                <li>The items are sortable by drag and drop.</li>
                                
                        </ul>
                </div>                   
            </div>
	</div>
        <div class="quote">
            <h5>Quote [adv-quote]</h5>
            <div class="quote"><label>Quote</label><textarea class="quote-content" name="quote[content]"><?php echo $quote['content'] ?></textarea></div>
            <div class="attribution"><label>Attribution</label><input type="text" name="quote[attribution]"  value="<?php echo $quote['attribution'] ?>" /></div>
            
            <div class="quote-color">
                <label>Quote Mark Color</label>
                <input type="radio" name="quote[color]" value="dark" <?php echo ($quote['color'] == 'dark') ? ' checked="checked"' : '' ?> />Dark &nbsp;
                <input type="radio" name="quote[color]" value="light" <?php echo ($quote['color'] == 'light') ? ' checked="checked"' : '' ?> >Light
            </div>        
        </div>
		
        <hr />
        <div style="clear: both">
                <ul>
                    <li>To add active promotions teaser on the sidebar, use the shortcode [active-offers]</li>
                    <li>For parent page, add this shortcode [active-offers-detailed no_offers_msg = "message if no offers is available"]
                    <li>Sidebar shortcode : [active-offers]
		</ul>            
        </div>
		
        </div>

	<?php

    }

    /**
     * This function is to save the categories on the hide box
     * 
     * @param type $post_id
     * @return type
     */
    public function savePostData( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) ) {
		return;
	}
	
	// Check permissions
	if ( 'page' == $_POST['post_type'] )
	{
	if ( !current_user_can( 'edit_page', $post_id ) )
	    return;
	}
	else
	{
	if ( !current_user_can( 'edit_post', $post_id ) )
	    return;
	}
	
	if ($_POST['wpvs']['update-thumbnail'] == 'on') {
	
	$url = 'http://img.youtube.com/vi/' . $_POST['wpvs']['video-url'] . '/0.jpg';
	
	if ($_POST['wpvs']['video-url'] != '') {
	    $img = self::$uploadPath . $_POST['wpvs']['video-url'] . '.jpg';
	}
	@file_put_contents($img, file_get_contents($url));			
	}

	update_post_meta($post_id, $this->_metaBoxId, $_POST['alp']);
	update_post_meta($post_id, $this->_metaBoxId . '_formcomponents', $_POST['formcomp']);
	update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_textbox_short', $_POST['textbox-short']);
	update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_textbox_phone', $_POST['textbox-phone']);		
	update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_textbox_long', $_POST['textbox-long']);
	update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_radioButtonLabel', $_POST['radioButtonLabel']);	
	update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_radioButtonChoices', $_POST['radioButtonChoices']);	
	update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_checkboxLabel', $_POST['checkboxLabel']);	
	update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_checkboxChoices', $_POST['checkboxChoices']);	
	//update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_checkbox', $_POST['checkbox']);	
	//update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_dropdown', $_POST['dropdown']);	
	////////////
	update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_dropdownLabel', $_POST['dropdownLabel']);
	update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_dropdownChoices', $_POST['dropdownChoices']);
        update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_quote', $_POST['quote']);
        
        update_post_meta($post_id, $this->_metaBoxId . '_formcomponents_fields', $_POST['field']);
    }
    
    
} 