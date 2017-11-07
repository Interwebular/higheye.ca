<script>
jQuery(document).ready(function(){
	
	jQuery('#alp_reactivate').off('submit').submit(function(e){
		
		//Unpropagate submit event
		e.preventDefault();
		
		//Disable Submit button
		jQuery('#alp_reactivate_submit').attr('disabled', 'disabled');
		
		//Get the form and serialize it for json data transport
		var form = jQuery(this).serialize();
		
		//Perform DOM manipulation of the submit button
		jQuery('#alp_reactivate_submit').removeClass('button-primary');
		jQuery('#alp_reactivate_submit').removeClass('button');
		jQuery('#alp_reactivate_submit').css({
			'background-color': '#4CAF50',
			'color': '#ffffff',
			'font-style': 'italic',
			'border': '1px solid #28902c',
			'border-radius': '3px'
		});
		jQuery('#alp_reactivate_submit').val('Reactivating... Please wait!');
		
		//Add the Ajax gif loader
		jQuery('#alp_reactivate_submit').after('<img style="position: relative; top: 3px; margin-left: 5px;" src="<?php echo ALP_BASEURL . 'images/ajax-loader.gif'; ?>"/>');
		
		//Re-align text notif
		jQuery('#alp_reactivate_notif .alp_notice_upgrade').css('top', '0');
		
		//Perform the ajax function
		jQuery.ajax({
			url:'<?php echo admin_url("admin-ajax.php"); ?>',
			type:'POST',
			dataType: "json",
			data:'action=alp_reactivate_ajax&'+ form,
			error:function(data_1){ console.log("Error Occured!"); console.log(data_1); },
			success:function(data){
				if(data['alp_reactivate'] == true){
					setTimeout(function(){
					//Old
					//window.location.replace('<?php //echo get_admin_url() . 'admin.php?page=' . ALP_CURRENT_PAGE; ?>');
					
					//new
					window.location.replace('<?php echo "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>');
					}, 2000);
				}else {
					jQuery('.alp_notice_upgrade b').html('Something went wrong. Please contact the Plugin Developer!');
					jQuery('.alp_notice_upgrade').css('border-left:', '3px solid #F44336');
					//Enable Submit button
					jQuery('#alp_reactivate_submit').removeAttr('disabled');
				}
			}
		});
	
	});
	
});
</script>
<?php if(ALP_CURRENT_PAGE != 'alp-settings'): ?>
	<script>
	jQuery(document).ready(function(){
		
		//Checks the ALP metabox wrapper if rendered in the page
		if(jQuery('div').is('#alp_metabox')) {
			jQuery('#alp_metabox').hide();
			
			//Place custom notification text in the metabox
			jQuery('#alp_metabox').before('\
				<p id="alp_meta_text_notif" style="font-weight: 600; display: inline-block; margin: 0 7px 0 0; position: relative; top: 5px;">The <span style="color: #ff0000;">Advance Landing Page</span> plugin was updated or installed with a new version. <span style="color: #ff0000;">Please reactivate!</span></p>\
				<input style="display: inline-block;" id="alp_reactivate_submit_metabox" class="button button-primary" type="submit" value="Reactivate Advance Landing Page Plugin"/>\
			');
			
			jQuery('#alp_reactivate_submit_metabox').off('click').on('click', function(e){
				
				//Unpropagate submit event
				e.preventDefault();
				
				//Disable Submit button
				jQuery(this).attr('disabled', 'disabled');
				
				//Get the form and serialize it for json data transport
				var form = jQuery(this).serialize();
				
				//Perform DOM manipulation of the submit button
				jQuery(this).removeClass('button-primary');
				jQuery(this).removeClass('button');
				jQuery(this).css({
					'background-color': '#4CAF50',
					'color': '#ffffff',
					'font-style': 'italic',
					'border': '1px solid #28902c',
					'border-radius': '3px'
				});
				jQuery(this).val('Reactivating... Please wait!');
				
				//Add the Ajax gif loader
				jQuery(this).after('<img style="position: relative; top: 3px; margin-left: 5px;" src="<?php echo ALP_BASEURL . 'images/ajax-loader.gif'; ?>"/>');
				
				//Re-align text notif
				jQuery('#alp_meta_text_notif').css('top', '0');
				
				//Trigger submit event for reactivation
				jQuery('#alp_reactivate').trigger('submit');
				
			});
			
		}
		
	});
	</script>
	<style>
	div#alp_reactivate_notif {
		padding: 10px;
		border-left: 4px solid #FF9800;
	}
	div#alp_reactivate_notif .alp_notice_upgrade {
		margin: 0 7px 0 0;
		padding: 0;
		position: relative;
		top: 5px;
	}
	div#alp_reactivate_notif .alp_notice_upgrade,
	div#alp_reactivate_notif #alp_reactivate {
		display: inline-block;
	}
	</style>
<?php endif; ?>
<?php  echo (ALP_CURRENT_PAGE != 'alp-settings') ? '<div id="alp_reactivate_notif" class="updated">' : '';?>
<p class="alp_notice_upgrade">The <span style="font-weight: 600; color: #ff0000;">Advance Landing Page</span> plugin was <b>updated or installed</b> with a new version. <span style="font-weight: 600; color: #ff0000;">Please reactivate!</span></p>
<form id="alp_reactivate" action="" method="post">
	<input type="hidden" name="alp_reactivate" value="1"/>
	<input id="alp_reactivate_submit" class="button button-primary" type="submit" value="Reactivate Advance Landing Page Plugin"/>
</form>
<?php  echo (ALP_CURRENT_PAGE != 'alp-settings') ? '</div>' : '';?>