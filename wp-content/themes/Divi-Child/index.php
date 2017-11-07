<?php get_header(); ?>

<div id="main-content">
<?php if ( !$is_page_builder_used && (is_front_page() && is_home()) || is_category()): ?>
	<style>
	#main-content .container {
		padding-top: 20px!important;
	}
	.et_pb_section {
		padding: 20px 0;
	}
	.et_pb_section_0 {
		padding-top: 0px;
	}
	</style>
	<div class="container">
		<div class="breadcrumbs"><?php if(function_exists('bcn_display')) { bcn_display(); }?></div>
	</div>
<?php endif; ?>

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<div class="blog-information">
			<?php if(ChildThemeOptions::get('blog-category-title') != null):?>
			<h1 class="blog-title"><?php echo ChildThemeOptions::get('blog-category-title'); ?></h1>
			<?php endif; if(ChildThemeOptions::get('blog-category-subtitle') != null): ?>
			<h2 class="blog-subtitle"><?php echo ChildThemeOptions::get('blog-category-subtitle'); ?></h2>
			<?php endif; if(ChildThemeOptions::get('blog-category-description') != null):?>
			<p class="blog-description"><?php echo ChildThemeOptions::get('blog-category-description'); ?></p>
			<?php endif;?>
			</div>
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					$post_format = et_pb_post_format(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>

				<?php
					$thumb = '';

					//$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );
					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 290 );

					//$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 290 );
					$classtext = 'et_pb_post_main_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					et_divi_post_format_content();

					if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) {
						if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) :
							printf(
								'<div class="et_main_video_container">
									%1$s
								</div>',
								$first_video
							);
						elseif ( ! in_array( $post_format, array( 'gallery' ) ) && 'on' === et_get_option( 'divi_thumbnails_index', 'on' ) && '' !== $thumb ) : ?>
							<div class="post-thumb-div_left">
							<a class="thumb-post" href="<?php the_permalink(); ?>">
								<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
							</a>
							</div>
					<?php
						elseif ( 'gallery' === $post_format ) :
							et_gallery_images();
						endif;
					} ?>

				<?php if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) : ?>
					<?php if ( ! in_array( $post_format, array( 'link', 'audio' ) ) ) : ?>
					<div class="post-thumb-div_right">
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php endif; ?>

					<?php
						et_divi_post_meta();

						if ( 'on' !== et_get_option( 'divi_blog_style', 'false' ) || ( is_search() && ( 'on' === get_post_meta( get_the_ID(), '_et_pb_use_builder', true ) ) ) ) {
							truncate_post( 300 );
							?>
							<div class="post-thumb-devider"></div>
							<a class="read-more-post" href="<?php the_permalink(); ?>">Read More</a>
							<?php
						} else {
							the_content();
						}
					?>
				<?php endif; ?>
					</div>
					<div class="entry_div"></div>
					</article> <!-- .et_pb_post -->
			<?php
					endwhile;

					if ( function_exists( 'wp_pagenavi' ) )
						wp_pagenavi();
					else
						get_template_part( 'includes/navigation', 'index' );
				else :
					get_template_part( 'includes/no-results', 'index' );
				endif;
			?>
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>