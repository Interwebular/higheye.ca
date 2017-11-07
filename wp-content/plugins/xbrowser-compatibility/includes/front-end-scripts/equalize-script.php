<?php
//Get Option
$xb_equalize = get_option('wjmc_xb_equalize');

//Set checker
if( !empty($xb_equalize[0]) ):

//Define array string
$xb_class_equalize = implode(", ", $xb_equalize);

//Define Counters
$class_count = count($xb_equalize);
$num  = 1;
$counter_num = 1;
$num_1 = 1;
$counter_num_1 = 1;

//Screen Width Trigger
$cmxb_screen_trigger_equalize = get_option('wjmc_screen_trigger_equalize');

if( empty($cmxb_screen_trigger_equalize) ) {
	$cmxb_screen_trigger_equalize = '980';
}
?>
<script type='text/javascript'>
var desk_xb = jQuery.noConflict();

function extra_script_desk_xb() {
	
	desk_xb('body').css('overflow', 'hidden');
	var static_width = desk_xb(window).width();
	desk_xb('body').css('overflow', 'auto');
	
	if( static_width > <?php echo $cmxb_screen_trigger_equalize; ?> ) {
		xb_equalize();
	}
	
	var resizeTimeout;
	
	desk_xb(window).resize(function(){
		
		if( !!resizeTimeout ){
			clearTimeout(resizeTimeout);
		}
		
		resizeTimeout = setTimeout(function(){
			
			desk_xb('body').css('overflow', 'hidden');
			var resized_width = desk_xb(this).width();
			desk_xb('body').css('overflow', 'auto');
			
			if( resized_width <= <?php echo $cmxb_screen_trigger_equalize; ?> ) {
				desk_xb('<?php echo $xb_class_equalize; ?>').attr('style', 'height: auto;');
			}
			else {
				desk_xb('<?php echo $xb_class_equalize; ?>').attr('style', 'height: auto;');
				xb_equalize();
			}
			
		},100);
		
	});
			
	function xb_equalize(){
		
		<?php while( $num <= $class_count ): ?>
		
		//Define array for a container
		var blurbs_<?php echo $num; ?> = [];
		
		<?php $num++; endwhile; ?>
		
		<?php foreach($xb_equalize as $class_x ): ?>
		
		desk_xb('<?php echo $class_x; ?>').each(function(){
			
			//Get the absolute height of the whole container
			var blurb_item_height_<?php echo $counter_num; ?> = desk_xb(this).outerHeight();
			
			//Pass it to a function
			blurb_func_<?php echo $counter_num; ?>( parseInt( blurb_item_height_<?php echo $counter_num; ?> ));
			
		});
		
		<?php  $counter_num++; endforeach; ?>
		
		<?php while( $num_1 <= $class_count ): ?>
		
		//Define equalization of height based on the maximum container element
		function blurb_func_<?php echo $num_1; ?>( b_<?php echo $num_1; ?> ){
			
			//Place it inside the array
			blurbs_<?php echo $num_1; ?>.push( b_<?php echo $num_1; ?> );
			
		}
		
		//Run the function
		blurb_func_<?php echo $num_1; ?>();
		
		<?php $num_1++; endwhile; ?>
		
		<?php foreach($xb_equalize as $class_x ): ?>
		
		//Perform if a container element is defined
		if( blurbs_<?php echo $counter_num_1; ?>[0] ) {
			
			//Remove the undefined variable at the last item inside the array due to the .each() function
			blurbs_<?php echo $counter_num_1; ?>.pop();
			
			//Get the maximum intger inside the array
			var max_blurb_height_<?php echo $counter_num_1; ?> = Math.max.apply( Math, blurbs_<?php echo $counter_num_1; ?> );
			
			//Finally apply the heighest integer as height to all the container elements
			desk_xb('<?php echo $class_x; ?>').attr('style', 'height:' + max_blurb_height_<?php echo $counter_num_1; ?> + 'px;');
		
		}
		
		<?php  $counter_num_1++; endforeach; ?>

	}
	
}
desk_xb(window).load(extra_script_desk_xb);
</script>
<?php endif; ?>