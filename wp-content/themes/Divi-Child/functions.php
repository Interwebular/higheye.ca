<?php
include_once('child-theme-includes/child-options.php');
include_once('child-theme-includes/metabox.php');
include_once('vars.php');

global $child_theme_admin_css, $child_theme_custom_fonts, $child_theme_options, $child_theme_custom_post_meta;

/**************************/
$child_theme_admin_css = '
	.child-theme-options label {
		display: block;
	}
	.child-theme-options textarea {
		width: 700px;
		height: 150px;
		border: 1px solid rgba(32, 72, 111, 0.3);
		border-radius: 0 0 10px 10px;
		box-shadow: rgba(0, 0, 0, 0.2) 0px 1px 5px;
	}
	.child-theme-options input[type="text"] {
		width: 700px;
	}
	.form-row {
		width: 700px;
		margin-top: 15px;
	}
	.form-row label {
		font-weight: bold;
		padding-bottom: 3px;
	}
';
/**************************/

//$font1['name'] = '';
//$font2['src'] = get_bloginfo('wpurl')...;
//$child_theme_custom_fonts = array($font1);
$child_theme_custom_fonts = array(null);

/**************************/

$child_theme_options['header-text'] = array('type' => 'textarea', 'label' => 'Header Text');
$child_theme_options['hide-tagline'] = array('type' => 'checkbox', 'label' => 'Hide Tagline');
$child_theme_options['copyright'] = array('type' => 'textarea', 'label' => 'Copyright');
$child_theme_options['blog-category-title'] = array('type' => 'textarea', 'label' => 'Blog Category Title');
$child_theme_options['blog-category-subtitle'] = array('type' => 'textarea', 'label' => 'Blog Category Sub-Title');
$child_theme_options['blog-category-description'] = array('type' => 'textarea', 'label' => 'Blog Category Description');

/**************************/

$child_theme_custom_post_meta['short_title'] = array('type' => 'text', 'label' => 'Short Title');
$child_theme_custom_post_meta['hide_feat_on_slide'] = array('type' => 'checkbox', 'label' => 'Hide Featured Image on Slider');
$child_theme_custom_post_meta['icon'] = array('type' => 'text', 'label' => 'Home Icon');
//$child_theme_custom_post_meta['show_video_on_slide'] = array('type' => 'checkbox', 'label' => 'Show Video On Slider');
//$child_theme_custom_post_meta['yt_video'] = array('type' => 'text', 'label' => 'Slide YT Video ID');


function et_pb_postinfo_meta( $postinfo, $date_format, $comment_zero, $comment_one, $comment_more ){
	$postinfo_meta = '';

	if ( in_array( 'author', $postinfo ) )
		$postinfo_meta .= ' ' . esc_html__( 'by', 'et_builder' ) . ' <span class="author vcard"><span class="fn">' . et_pb_get_the_author_posts_link() . '</span></span>';

	if ( in_array( 'date', $postinfo ) ) {
		if ( in_array( 'author', $postinfo ) ) $postinfo_meta .= ' | ';
		$postinfo_meta .= '<span class="published">' . esc_html( get_the_time( wp_unslash( $date_format ) ) ) . '</span><span style="display: none;" class="updated">' . get_the_modified_time('F jS, Y') . '</span>';
	}

	if ( in_array( 'categories', $postinfo ) ) {
		$categories_list = get_the_category_list(', ');

		// do not output anything if no categories retrieved
		if ( '' !== $categories_list ) {
			if ( in_array( 'author', $postinfo ) || in_array( 'date', $postinfo ) )	$postinfo_meta .= ' | ';

			$postinfo_meta .= $categories_list;
		}
	}

	if ( in_array( 'comments', $postinfo ) ){
		if ( in_array( 'author', $postinfo ) || in_array( 'date', $postinfo ) || in_array( 'categories', $postinfo ) ) $postinfo_meta .= ' | ';
		$postinfo_meta .= et_pb_get_comments_popup_link( $comment_zero, $comment_one, $comment_more );
	}

	return $postinfo_meta;
}
