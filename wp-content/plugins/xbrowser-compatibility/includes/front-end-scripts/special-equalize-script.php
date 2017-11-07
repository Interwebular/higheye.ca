<?php
	//Get Special Equalize Array
	$special_equalize = get_option('wjmc_special_equalize');
	
	//Begin logic
	if( !empty($special_equalize['container_1'][0]) && !empty($special_equalize['container_2'][0]) ):
	
	//Count Array Items from one of the special equalize container array
	$count_se = count( $special_equalize['container_1'] );
	
?>
<script type='text/javascript'>
var seq_xb = jQuery.noConflict();

function extra_script_seq_xb() {
	
	seq_xb('body').css('overflow', 'hidden');
	var static_width = seq_xb(window).width();
	seq_xb('body').css('overflow', 'auto');
	
	<?php $a = 1; $b = 0; while( $a <= $count_se ): ?>
	
		if( static_width > <?php echo $special_equalize['screen_width'][$b]; ?> ) {
			xb_special_equalize_<?php echo $a; ?>();
		}
		
		var resizeTimeout_<?php echo $a; ?>;
		
		seq_xb(window).resize(function(){
			
			if( !!resizeTimeout_<?php echo $a; ?> ){
				clearTimeout(resizeTimeout_<?php echo $a; ?>);
			}
			
			resizeTimeout_<?php echo $a; ?> = setTimeout(function(){
				
				seq_xb('body').css('overflow', 'hidden');
				var resized_width = seq_xb(this).width();
				seq_xb('body').css('overflow', 'auto');
				
				if( resized_width <= <?php echo $special_equalize['screen_width'][$b]; ?> ) {
					seq_xb('<?php echo $special_equalize['container_1'][$b]; ?>, <?php echo $special_equalize['container_2'][$b]; ?>').attr('style', 'height: auto;');
				}
				else {
					seq_xb('<?php echo $special_equalize['container_1'][$b]; ?>, <?php echo $special_equalize['container_2'][$b]; ?>').attr('style', 'height: auto;');
					xb_special_equalize_<?php echo $a; ?>();
				}
				
			},100);
			
		});
				
		function xb_special_equalize_<?php echo $a; ?>(){
			
			var container_1_outer_height_<?php echo $a; ?> = seq_xb('<?php echo $special_equalize['container_1'][$b]; ?>').outerHeight();
			
			seq_xb('<?php echo $special_equalize['container_2'][$b]; ?>').attr('style', 'height:' + container_1_outer_height_<?php echo $a; ?> + 'px;');

		}
	
	<?php $a++; $b++; endwhile; ?>
	
}
seq_xb(window).load(extra_script_seq_xb);
</script>
<?php endif; ?>