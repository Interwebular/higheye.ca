<?php
function alp_settings_css(){
	?>
	<link rel='stylesheet' href='<?php echo ALP_BASEURL; ?>css/admin-css.css?version=<?php echo ALP_PLUGIN_VERSION; ?>' type='text/css' media='all' />
	<script type="text/javascript">
	jQuery(document).ready(function(){
		//Load an iframe to the settings page.
		jQuery('#iframe_changelog').append('<p id="changelog-label">Changelog</p> <span class="full_changelog_link"><a href="<?php echo ALP_BASEURL; ?>changelog.txt?version=<?php echo ALP_PLUGIN_VERSION; ?>" target="_blank">View full change log</a></span><iframe src="<?php echo ALP_BASEURL; ?>changelog.txt?version=<?php echo ALP_PLUGIN_VERSION; ?>"/>');
	});
	</script>
	<?php
}

function alpBlogStylesAndScripts() {
	?>
    <style type="text/css">
	.error-msg {
		color: #ff0000;
	}
	.submit-btn {
		cursor: pointer;
		white-space: normal;
	}
    .alp-sidebar input[type="text"],
    .alp-sidebar input[type="email"],
    .alp-sidebar input[type="password"],
    .alp-sidebar textarea {
         padding:5px 3px;
         max-width: 96%;
    }
    .active-offers {
        list-style-type: none;
    }
    .active-offers li {
        list-style-type: none;
        margin-left: 0!important;
    }
    .active-offers .read-more{
        display: inline-block;
    }
    .active-offers .read-more {
        display: block;
        text-decoration: none;
        width: 83px;
        margin-top: 10px;
    }
    .active-offers .thumbnail img {
        height: auto;
        max-width: 100%;
    }
    .no-active-offers {
        display: none!important;
    }
    .quote {
        border-top: solid #fff 1px;
        border-bottom: solid #fff 1px;        
        padding: 15px 0;
        margin: 15px 0;
    }
    .quote{
        font-style: italic;
        background: url("<?php echo ALP_BASEURL; ?>images/light-quote.png") top left no-repeat;
        padding-left: 55px;
        background-position: left 15px!important;
    }     
    .quote.dark{
        background: url("<?php echo ALP_BASEURL; ?>images/dark-quote.png") top left no-repeat;
    }
    .quote-photo {
        float: left;
        margin: 5px 5px 0 0;
    }
    .attribution {
        font-weight: bold;        
    }
    #hp-adv {
        display: none!important; 
        visibility:hidden!important; 
        opacity:0!important;
    }

    </style>
<?php
}

function alpAdminStylesAndScripts() {
    wp_enqueue_style("alp-admin-style", ALP_BASEURL . 'css/style.css');
    wp_enqueue_style('farbtastic');
    wp_enqueue_script( 'farbtastic');	    
?>

<script type="text/javascript">
	var $jx = jQuery.noConflict();
	$jx(window).ready(function() {
		updateBGColor();
		function updateBGColor()
		{
			if ($jx('.alp-no-background').is(":checked")) {
				$jx('#bg-color').val('transparent');
				$jx('#bg-color').attr('disabled', 'disabled');
			}
			else {
				$jx('#bg-color').removeAttr('disabled');
			}
		}

		$jx('.alp-no-background').click(function(){
			updateBGColor();
		});
		$jx('.add-textbox-short').click(function(){
			$jx('.textbox-short-container').append('<p><input type="text" name="textbox-short[]" /><a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>');
		});


		$jx('.add-textbox-phone').click(function(){
			$jx('.textbox-phone-container').append('<p><input type="text" name="textbox-phone[]" /><a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>');
		});
		
		$jx('.add-textbox-long').click(function(){
			$jx('.textbox-long-container').append('<p><input type="text" name="textbox-long[]" /><a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>');
		});
                                
		$jx('.add-radio-button').click(function(){
			$jx('.radio-button-container').append('<p><input type="text" name="radioButtonLabel[]" /><textarea name="radioButtonChoices[]"></textarea><a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>');
		});
                
		$jx('.add-dropdown').click(function(){
			$jx('.dropdown-container').append('<p><input type="text" name="dropdownLabel[]" /><textarea name="dropdownChoices[]"></textarea><a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>');
		});                


		$jx('.add-checkbox').click(function(){
			$jx('.checkbox-container').append('<p><input type="text" name="checkboxLabel[]" /><textarea name="checkboxChoices[]"></textarea><a onclick="this.parentNode.parentNode.removeChild(this.parentNode);" style="cursor: pointer; color: blue;"><span style="color: rgb(255, 0, 0);">(-)Remove Field</span></a></p>');
		});                

		// FOR COLOR PICKER
		$jx('.wpvs-color-picker').each(function(){
                        var $this = jQuery(this),
                        id = $this.attr('rel');
                        $jx(this).farbtastic('#' + id);
		});

		$jx('.wpvs-color-picker-input').click(function(){
                        $jx(".wpvs-color-picker").hide();
                        id = $jx(this).attr('id');
                        $jx('.wpvs-color-picker[rel="' + id + '"]').css('display', 'block');
		});	

		$jx("html").click(function() {
                        $jx(".wpvs-color-picker").hide();
		});

		$jx(".wpvs-color-picker-input").click(function (event) {
                    event.stopPropagation();
		});

		$jx(".wpvs-color-picker").click(function (event) {
		  event.stopPropagation();
		});

		// END COLOR PICKER HERE

		if($jx('.radio-call-now').is(':checked')) {
			alpCallNowForm();
		}

		if($jx('.radio-user-form').is(':checked')) {
			alpUserForm();
		}	
		
		if($jx('.radio-download').is(':checked')) {
			showDownloadFormFields();
		}		
                
		if($jx('.radio-print-coupon').is(':checked')) {
			alpPrintCoupon();
		}	                

                ////////////////////////////////////////////////
                
		$jx('.radio-call-now').click(function(){
			alpCallNowForm();
		});

		$jx('.radio-user-form').click(function(){
			alpUserForm();
		});
                
		$jx('.radio-download').click(function(){
			showDownloadFormFields();
		});    
                
		$jx('.radio-print-coupon').click(function(){
			alpPrintCoupon();
		});                  
	});

	function alpCallNowForm()
	{
		$jx('.user-form-only').css('display', 'none');
		$jx('.download-form-only').css('display', 'none');
		$jx('.call-now-only').css('display', 'block');                
                $jx('.print-coupon-only').css('display', 'none');
                
                $jx('.admin-notification').css('display', 'none');                
                $jx('#form-textcolor').css('display', 'none');
                $jx('.download-email-settings').css('display', 'none');
                
	}

	function alpPrintCoupon()
	{
		$jx('.user-form-only').css('display', 'none');
		$jx('.download-form-only').css('display', 'none');
		$jx('.call-now-only').css('display', 'none');
                $jx('.print-coupon-only').css('display', 'block');
                
                $jx('.admin-notification').css('display', 'none');                
                $jx('#form-textcolor').css('display', 'none');
                $jx('.download-email-settings').css('display', 'none');
	}
        
	function alpUserForm()
	{
		$jx('.user-form-only').css('display', 'block');
		$jx('.download-form-only').css('display', 'none');
		$jx('.call-now-only').css('display', 'none');
                $jx('.print-coupon-only').css('display', 'none');
                
                $jx('.admin-notification').css('display', 'block');                
                $jx('#form-textcolor').css('display', 'block');
                $jx('.download-email-settings').css('display', 'none');
	}
	
	function showDownloadFormFields()
	{
		$jx('.user-form-only').css('display', 'block');
		$jx('.download-form-only').css('display', 'block');
		$jx('.call-now-only').css('display', 'none');
                $jx('.print-coupon-only').css('display', 'none');
                
                $jx('.admin-notification').css('display', 'block');                
                $jx('#form-textcolor').css('display', 'block');
                $jx('.download-email-settings').css('display', 'block');
	}
</script>
<script>
//For page editor metabox warning messages.
jQuery(document).ready(function(){
	
	var option_value = jQuery('.radio-call-now:checked, .radio-download:checked, .radio-user-form:checked, .radio-print-coupon:checked').val();
	
	jQuery('.radio-call-now, .radio-download, .radio-user-form, .radio-print-coupon').on('change', function(){
		
		//Run function and pass the value
		render_notif_warning(jQuery(this).val());
	});
	
	//Run function and pass the value
	render_notif_warning(option_value);
	function render_notif_warning(option_value) {
		
		if(option_value == 'download') {
			jQuery('#alp_notif_form_option').html('<span style="font-weight: 600; color: #ff0000;">Warning:</span> Please <b>deactivate</b> <span style="font-weight: 600; color: #ff0000;">WJASS</span> plugin when using the <b>Download</b> form.');
			jQuery('#alp_notif_form_option').css({
				'font-weight':'normal',
				'display':'inline-block',
				'background-color':'#fff8bf',
				'border':'1px solid #aaaaaa',
				'border-radius':'3px',
				'padding':'10px',
				'margin':'5px 0',
				'font-style':'italic'
			});
		} else {
			jQuery('#alp_notif_form_option').html('');
			jQuery('#alp_notif_form_option').attr('style', '');
		}
		
	}
	
});
</script>
<style type="text/css">
    textarea.quote-content {
        width: 100%;
    }
    .attribution input,
    .photo input {
        width: 100%;        
    }    
</style>
<?php

}

function alpStylesAndScripts() {
	
	global $post;
	$data = get_post_meta($post->ID, 'landingpage-metabox', true);
	wp_enqueue_style("alp-website-style", ALP_BASEURL . 'css/website-style.css', ALP_PLUGIN_VERSION);

?>
<style type="text/css">
	.alp-submit .submit-load {
		background: url('<?php echo ALP_BASEURL; ?>images/ajax-loader.gif') no-repeat;
		width: 85px;
		background-position: 0 3px;
		margin: 10px auto 0 auto;
		text-align: right;
	}
	.alp-content {
		width: 67%;
		float: left;
		margin-right: 5%;
	}
	.alp-sidebar {
		width: 28%;
		float: right;
	}
	.alp-call-now-button, .alp-print-page-button {		
		background-color: <?php echo ($data['button-color'] != '') ? $data['button-color'] : '#ccc' ?>;
		border: solid <?php echo ($data['button-color'] != '') ? $data['button-border-color'] : '#ccc' ?> 2px;
		padding:10px 5px;
		box-shadow:0 0 4px #FFFFFF inset;
		text-align:center;
		margin-bottom:10px;
                text-shadow: none;
	}
	.alp-call-now-button:hover, .alp-print-page-button:hover {	
		box-shadow:0 0 1px #FFFFFF inset;
		text-shadow:0 2px 2px #333333;
	}
	.alp-call-now-button a, .alp-print-page-button a {
		text-decoration:none;
		color: <?php echo ($data['button-text-color'] != '') ? $data['button-text-color'] : '#000' ?>;
		display:block;
		font-size:19px;
		font-weight:bold;
                cursor: pointer;

	}
	#alp-main .alp-submit .submit-btn {
		border: none;
		border-radius: 3px;
		padding: 6px 15px;
		margin: 0;
		color: <?php echo ($data['button-text-color'] != '') ? $data['button-text-color'] : '#000' ?>;
		background: <?php echo ($data['button-color'] != '') ? $data['button-color'] : '#ccc' ?>!important;
		border: solid <?php echo ($data['button-border-color'] != '') ? $data['button-border-color'] : '#ccc' ?> 2px;
		max-width: 100%;
		font-size: 16px;
	
	}
	#alp-main .alp-submit .submit-btn:hover {
		opacity: 0.9;
	}
	.alp-contact-form {
		padding:20px;
		background-color: <?php echo ($data['form-bg-color'] != '') ? $data['form-bg-color'] : 'transparent' ?>;
		display: block;
        color: <?php echo ($data['action-text-msg-color'] != '') ? $data['action-text-msg-color'] : '#000' ?>;
		border-radius: 3px;
		/*box-shadow: #cccccc 0 0 10px;
		-webkit-box-shadow: #cccccc 0 0 10px;
		-moz-box-shadow: #cccccc 0 0 10px;
		-ms-box-shadow: #cccccc 0 0 10px;*/
		/*border: 1px solid rgba(0, 0, 0, 0.2);*/
		border: 1px solid #cccccc;
	}
	.alp-submit {
		text-align:center;
		padding-top: 15px;
	}
	.alp-actiontext-msg {
		color: <?php echo ($data['action-text-msg-color'] != '') ? $data['action-text-msg-color'] : '#000' ?>;
		font-size:20px;
		text-align:center;
	}
	.alp-contact-form p {
		margin:0 0 5px!important;
		padding: 0 0 10px 0;
	}
	.alp-contact-form p input {
		margin: 0!important;
		border-radius: 3px;
	}
	.alp-contact-form p input[type="text"] {
		width: 100%;
		max-width: 100%;
	}
	.alp-contact-form p input[type="text"].phone1 {
		width: 30px!important;
	}
	.alp-contact-form p input[type="text"].phone2 {
		width: 30px!important;
	}
	.alp-contact-form p input[type="text"].phone3 {
		width: 60px!important;
	}
	.alp-contact-form p textarea {
		width: 100%;
		max-width: 100%;
		border-radius: 2px;
	}
	.alp-body {
		background: <?php echo ($data['bg-color'] != '') ? $data['bg-color'] : 'transparent' ?>;	
		overflow:hidden;
		width:96%;
		padding: 2%;                
	}
	.alp-content {
			color: <?php echo ($data['text-color'] != '') ? $data['text-color'] : 'inherit' ?>;	
	}
	.alp-sub-header {
		color: <?php echo ($data['subheader-text-color'] != '') ? $data['subheader-text-color'] : '#000' ?>!important;			
		margin:0 0 5px;
	}
	.alp-header {
		color: <?php echo ($data['header-text-color'] != '') ? $data['header-text-color'] : '#000' ?>!important;
		margin:0 0 15px;
	}
	.alp-body li {
		margin-left:0px;
	}
	.alp-contact-form label {
		display:block;
		font-weight:bold;
	}
	@media (max-width: 980px) {
		.alp-content {
			width: 60%;
		}
		.alp-sidebar {
			width: 35%;
		}
	}
	@media (max-width: 773px) {
		.alp-content {
			width: 100%;
			margin-right: 0;
		}
		.alp-sidebar {
			width: 100%;
			max-width: 270px;
			display: inline-block;
			float: none;
			padding: 30px 0;
		}
	}
</style>
<script type="text/javascript">
        document.landing_page = '';
        /*function printLandingPage(slug)
        {
            document.landing_page = slug;
            window.print();
            
        }
        */
        
$jx = jQuery.noConflict();

$jx(document).ready(function(){
$jx(".alp-print-page-button a").click(function (event) {
        event.preventDefault(); // this is "like" return false

        // this get's the href from your anchor, using jQuery sugar
        var url = $jx(this).attr("src");
	slug = $jx(this).attr('slug');
        // call your fun
        printCoupon(slug, url);        
});

});
        function printCoupon(slug, image_url) {
		//alert(slug + ' = ' + image_url);
            	document.landing_page = slug;
		//popup = window.open();
		style = '<style type="text/css">@media print { .hide-on-print { display: none }}</style>';
            	popup = window.open('', 'Print Coupon', 'width=700,height=500,top=200');
            	popup.document.write(style + '<div style="text-align: center; height: 100%; overflow: scroll;"><img src="' + image_url + '" /><div class="hide-on-print" style="border-top: dotted #aaa 1px; margin-top: 20px; padding-top: 20px;"><input type="button" onclick="window.print(); window.close()" value="Continue" /> <input type="button" onclick="window.close()" value="Cancel" /></div></div>'); 
        }
        try{
            (function() {
            var afterPrint = function() {
                //alert(document.landing_page);
                slug = (document.landing_page) ? document.landing_page : 'offer-page'
                console.log('GA Send ' + '/' + document.landing_page + '/');
                ga('send', 'event', { eventCategory: 'Lead', eventAction: 'Print Page', eventLabel: '/' + document.landing_page + '/'});
            };
            
            if (window.matchMedia) {
                var mediaQueryList = window.matchMedia('print');
                mediaQueryList.addListener(function(mql) {
                    if (!mql.matches)
                    afterPrint();
                });
            }
            window.onafterprint = afterPrint;
            }());
        } catch(e) {}
</script>

<script type="text/javascript">
jQuery(document).ready(function(){
	//alp-active-offers-list-content
	//alp-active-offers-list-sidebar
	
	//Script styling (readmore buttons) for page content and sidebar offer feeds
	//var title_feed_color = jQuery('ul#alp-active-offers-list-content .offer-title a').css('color');
	var title_feed_color = jQuery('body a').css('color');
	
	jQuery('ul#alp-active-offers-list-content .content .readmore a').each(function(){
		jQuery(this).off('mouseenter').on('mouseenter', function(){
			jQuery(this).css({
				backgroundColor: '' + title_feed_color + ''
			});
		});
		jQuery(this).off('mouseleave').on('mouseleave', function(){
			jQuery(this).removeAttr('style');
		});
	});
	
	jQuery('ul#alp-active-offers-list-sidebar .content .readmore').each(function(){
		jQuery(this).off('mouseenter').on('mouseenter', function(){
			jQuery(this).css({
				backgroundColor: '' + title_feed_color + ''
			});
		});
		jQuery(this).off('mouseleave').on('mouseleave', function(){
			jQuery(this).removeAttr('style');
		});
	});
	
});
</script>
<?php

}