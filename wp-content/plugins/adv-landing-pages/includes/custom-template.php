<?php
/**
 * Custom Template
 */

get_header(); ?>
<div id="content" class="container" style="margin:0 auto">	
	<?php
	// The Loop
	while ( have_posts() ) : the_post();
		the_content();	
	endwhile;
	?>
</div>
<?php get_footer(); ?>
