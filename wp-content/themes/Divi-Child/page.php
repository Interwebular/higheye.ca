<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">

<?php if ( $is_page_builder_used && (!is_front_page() && !is_home())): ?>
	<script>
	/* Adding breadcrumb divider when using page builder */
	var get_class_func = function() {
		
		var class_f = jQuery('.et_pb_row_0 .et_pb_widget_area').hasClass('et_pb_sidebar_0');
		
		if(class_f) {
			jQuery('#bread-div').addClass('right-breadcrumbs-div');
		}
		
		//console.debug(class_f);
	}
	// Launch function after window load and after ajax request
	jQuery(window).load(get_class_func);
	jQuery(document).ajaxSuccess(get_class_func);
	</script>
	<style>
	#main-content .container {
		padding-top: 20px!important;
	}
	#main-content .container:before {
		display: none!important;
	}
	.et_pb_section {
		padding: 20px 0;
	}
	.et_pb_section_0 {
		padding-top: 0px;
	}
	.breadcrumbs {
		margin-bottom: 10px;
		float: left;
	}
	.right-breadcrumbs-div {
		right: 29.55%;
		height: 80px;
		border-right: 1px solid rgba(0, 0, 0, 0.1);
		position: absolute;
		z-index: 1;
		top: 0px;
	}
	@media (max-width: 980px) {
		.right-breadcrumbs-div {
			display: none;
		}
	}
	</style>
	<div class="container">
		<div class="breadcrumbs"><?php if(function_exists('bcn_display')) { bcn_display(); }?></div>
		<div id="bread-div"></div>
		<!-- <h1><?php //the_title(); ?></h1> -->
	</div>
	<div style="clear: both;"></div>
<?php endif; ?>


<?php if ( ! $is_page_builder_used ) : ?>
<style>
#main-content .container {
	padding-top: 20px;
}
.breadcrumbs {
	padding-bottom: 20px;
}
</style>
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<div class="breadcrumbs"> <?php if(function_exists('bcn_display')) { bcn_display(); }?> </div>
			
<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( ! $is_page_builder_used ) : ?>

					<h1 class="main_title entry-title"><?php the_title(); ?></h1>
					<span style="display: none;" class="post_status">Posted by <span class="vcard author"><span class="fn"><?php the_author(); ?></span></span> <span class="updated"><?php echo get_the_modified_time('F jS, Y'); ?></span></span>
				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
				?>

				<?php else: ?>
					<div style="display: none;">
						<span class="entry-title"><?php the_title(); ?></span>
						<span class="post_status">Posted by <span class="vcard author"><span class="fn"><?php the_author(); ?></span></span> <span class="updated"><?php echo get_the_modified_time('F jS, Y'); ?></span></span>
					</div>
				<?php endif; ?>

					<div class="entry-content">
					<?php
						the_content();

						if ( ! $is_page_builder_used )
							wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->

				<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>

<?php if ( ! $is_page_builder_used ) : ?>

			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->

<?php endif; ?>

</div> <!-- #main-content -->

<?php get_footer(); ?>