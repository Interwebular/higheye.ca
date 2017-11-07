<?php
//Mobile menu ID
$mobile_menu_id_value = get_option('wjmc_mobile_menu_id');
$mobile_menu_id = '';
if($mobile_menu_id_value != null) {
	$mobile_menu_id = '#' . $mobile_menu_id_value . ' a';
	$mobile_menu_inc_parent = '#' . $mobile_menu_id_value . ' .sub-menu';
}

//Default variables for themes (height variables)
$mobile_menu_id_divi = '';
$mobile_menu_id_themify = '';

//Default variables for themes (parent and children function)
$mobile_menu_inc_parent_divi = '';
$mobile_menu_inc_parent_themify = '';

if($mobile_menu_id_value == null) {
	//For height variables
	$mobile_menu_id_divi = "#mobile_menu a,"; //Elegant
	$mobile_menu_id_themify = "#main-nav a"; //Themify
	
	//For parent and children function
	$mobile_menu_inc_parent_divi = "#mobile_menu .sub-menu,"; //Elegant
	$mobile_menu_inc_parent_themify = "#main-nav .sub-menu"; //Themify
}

//Manual Icon Positiong
$manual_pos = get_option('wjmc_icon_manual_pos');
?>
<script type='text/javascript'>
var cmxb = jQuery.noConflict();

function collapse_menu_x(){
	
	var class_counter = 0;
	<?php if($manual_pos == null): ?>
		var a_item_inner_height = cmxb('<?php echo $mobile_menu_id . $mobile_menu_id_divi . $mobile_menu_id_themify; ?>').height();
		var a_item_outer_height = cmxb('<?php echo $mobile_menu_id . $mobile_menu_id_divi . $mobile_menu_id_themify; ?>').outerHeight();
		var a_difference_height = a_item_outer_height - a_item_inner_height;
		var icon_pos_num = a_difference_height + parseInt(a_difference_height/2);
		var negative_pos_num = -Math.abs( icon_pos_num +5 ) + 'px';
	<?php endif; ?>
	
	<?php if($manual_pos != null): ?>
		var manual_pos = <?php echo $manual_pos; ?>;
	<?php endif; ?>
	
	//Checks for parent and children html elements and then aplying attributes to children.
	function display_roll(object) {
		if ( cmxb(object).hasClass('over') ) {
			cmxb(object).removeClass('over');
			cmxb(object).parent().children('ul').attr('style', 'display: none!important');
		}
		else {
			cmxb(object).addClass('over');
			cmxb(object).parent().children('ul').attr('style', 'display: block!important');
		}
	}
	
	cmxb('<?php echo $mobile_menu_inc_parent . $mobile_menu_inc_parent_divi . $mobile_menu_inc_parent_themify; ?>').each(function(){
		
		//Counter to assign unique ID's
		class_counter = class_counter + 1;
		
		//Add each icons before the .sub-menu items with unique ID's
		cmxb(this).before('<span id="sub_menu_item_' + class_counter + '" class="icon_img"></span>');
		
		//Position the icons to properly align with the menu items.
		cmxb('.icon_img').css('margin-top', <?php if($manual_pos == null): ?>negative_pos_num<?php endif; ?><?php if($manual_pos != null): ?>manual_pos<?php endif; ?>);
		
		// Click event on icons and then stop affecting the parent bind click event.
		cmxb('#sub_menu_item_' + class_counter + '').click(function(event){
			event.stopPropagation(); // Stop click event propagation
			event.preventDefault(); // Stop linked anchors.
			//Perform the function on the element where the click event is assigned.
			display_roll(cmxb(this));
		});
		
	});
	
}
cmxb(window).load(collapse_menu_x);
</script>