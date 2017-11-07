<?php
//Mobile menu ID's
$mobile_menu_ids_value = get_option('wjmc_mobile_menu_id');
$mobile_menu_ids = '';
$mobile_menu_inc_parent_ids = '';

//Set checker
if( !empty( $mobile_menu_ids_value[0] ) ) {
	
	//Define array string
	$mobile_menu_ids = '#' . implode(" a, #", $mobile_menu_ids_value) . ' a';
	$mobile_menu_inc_parent_ids = '#' . implode(" .sub-menu, #", $mobile_menu_ids_value) . ' .sub-menu';
	
}

//Set checker
if( empty( $mobile_menu_ids_value[0] ) ) {
	
	/* Default variables for themes (height variables)
	mobile_menu -> Elegant Themes
	main-nav -> Themify Themes */
	$mobile_menu_ids = array('mobile_menu', 'main-nav');
	$mobile_menu_ids_parent = array('mobile_menu', 'main-nav');
	
	//Define array string
	$mobile_menu_ids = '#' . implode(" a, #", $mobile_menu_ids) . ' a';
	$mobile_menu_inc_parent_ids = '#' . implode(" .sub-menu, #", $mobile_menu_ids_parent) . ' .sub-menu';
	
}

//Mobile menu Class(es)
$mobile_menu_classes_value = get_option('wjmc_mobile_menu_class');
$mobile_menu_classes = '';
$mobile_menu_inc_parent_classes = '';

//Set checker
if( !empty( $mobile_menu_classes_value[0] ) ) {
	
	//Define array string
	$mobile_menu_classes = ', .' . implode(" a, .", $mobile_menu_classes_value) . ' a';
	$mobile_menu_inc_parent_classes = ', .' . implode(" .sub-menu, .", $mobile_menu_classes_value) . ' .sub-menu';
	
}

//Manual Icon Positiong
$manual_pos = get_option('wjmc_icon_manual_pos');

//Screen Width Trigger
$cmxb_screen_trigger = get_option('wjmc_screen_trigger');

if( empty($cmxb_screen_trigger) ) {
	$cmxb_screen_trigger = '980';
}
?>
<script type='text/javascript'>
var cmxb = jQuery.noConflict();

function collapse_menu_x(){
	
	//Get color of a tags from the menu items when the browser is loaded
	var menu_item_color = cmxb('<?php echo $mobile_menu_ids . $mobile_menu_classes; ?>').css('color');
	
	var resizeTimeout;
	
	cmxb(window).resize(function(){
		
		if( !!resizeTimeout ){
			clearTimeout(resizeTimeout);
		}
		
		resizeTimeout = setTimeout(function(){
			
			cmxb('body').css('overflow', 'hidden');
			var resized_width = cmxb(this).width();
			cmxb('body').css('overflow', 'auto');
			
			if( resized_width <= <?php echo $cmxb_screen_trigger; ?> ) {
				apply_icon_color();
			}
			else {
				apply_icon_color();
			}
			
		},100);
		
	});
			
	function apply_icon_color(){
		
		//Get color of a tags from the menu items ( Used when resizing. )
		var menu_item_color = cmxb('<?php echo $mobile_menu_ids . $mobile_menu_classes; ?>').css('color');
		
		//Set color of the icons
		cmxb('.icon_img').css('color', menu_item_color);
		
	}
	
	/*
	Start of collapsed menu script
	*/
	
	//Define counter
	var class_counter = 0;
	<?php if($manual_pos == null): ?>
		var a_item_inner_height = cmxb('<?php echo $mobile_menu_ids . $mobile_menu_classes; ?>').height();
		var a_item_outer_height = cmxb('<?php echo $mobile_menu_ids . $mobile_menu_classes; ?>').outerHeight();
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
	
	cmxb('<?php echo $mobile_menu_inc_parent_ids . $mobile_menu_inc_parent_classes; ?>').each(function(){
		
		//Counter to assign unique ID's
		class_counter = class_counter + 1;
		
		//Add each icons before the .sub-menu items with unique ID's
		cmxb(this).before('<span id="sub_menu_item_' + class_counter + '" class="icon_img"></span>');
		
		//Position the icons to properly align with the menu items.
		cmxb('.icon_img').css('margin-top', <?php if($manual_pos == null): ?>negative_pos_num<?php endif; ?><?php if($manual_pos != null): ?>manual_pos<?php endif; ?>);
		
		//Set color of the icons ( Initiated when resized. See code above. )
		cmxb('.icon_img').css('color', menu_item_color);
		
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