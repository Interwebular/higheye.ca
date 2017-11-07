<?php
/**
* Plugin Name: Excerpts Everywhere
* Plugin URI: http://axiomcode.com
* Description: This plugin will allow you to add post/page/custom post type excerpt anywhere on your page using shortcode. Just specify the id. There are number of layout available. You can use the featured image or specify another image to be used
* Version: 1.0.4
* Author: Edesa P. Cabang
* Author URI: http://axiomcode.com
*/

error_reporting(1);
@ini_set('display_errors', 1);

add_action('admin_head', 'excerpts_everywhere_page_thumbnail', 10);

function excerpts_everywhere_page_thumbnail()
{
    add_meta_box('postimagediv', 'Featured Image', 'post_thumbnail_meta_box', 'page', 'side', 'low');
}

if ( function_exists( 'add_image_size' ) ) {        
    add_image_size( 'excerpt-icon-200', 200, 200, true ); //(cropped)
    add_image_size( 'excerpt-icon-300200', 300, 200, true ); //(cropped)
    add_image_size( 'excerpt-icon-100', 100, 100, true ); //(cropped)
    add_image_size( 'excerpt-icon-10050', 100, 100, true ); //(cropped)
    add_image_size( 'excerpt-icon-50', 50, 50, true ); //(cropped)
}

include_once('templates.php');

wp_enqueue_style('SPES-style', WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) . '/style.css');

add_shortcode('excerpt-everywhere', 'smpl_page_exerpt');

function smpl_page_exerpt($atts, $content) {
    global $post;

    $template = $atts['t'];
    if (!$template) {
        $template = 3;
    }

    if (!$atts['thumb_size']) {
        $atts['thumb_size'] = '100';
    }

    $thumb_size = 'excerpt-icon-' . $atts['thumb_size'];
    $excerpt_icon_size = 'excerpt-icon-' . $atts['thumb_size'];

    $hide_title = $atts['hide_title'];

    $id = ($atts['id']) ? $atts['id'] : $post->ID;
    $q = 'p=' . $id . '&posts_per_page=1';
    
    if ($atts['type']) {
        $q .= '&post_type=' . $atts['type'];
    }
    
    $page = get_post($id);
    if (!$page->ID) {
        return '';
    }
    ///////////////////////////////////////////////////////
    $image = get_the_post_thumbnail($id, $thumb_size);
    
    if ($atts['src']) {
        $src = $atts['src'];
        $image = '<img src="' . $src . '" class="attachment-thumbnail" alt="thumbnail" />';
    }

    if($atts['is_icon'] == 1) {
        $thumb_id = get_post_thumbnail_id();
        $thumb_url = wp_get_attachment_image_src($thumb_id, $excerpt_icon_size, true);                    
        $the_image = '';
    }
    else {
        $the_image = '<a title="' . $page->post_title . '"href="' . get_permalink($atts['id']). '" class="thumbnail">' . $image . '</a>';
    }

    if (!$hide_title) {
        $title = ($atts['title']) ? $atts['title'] : $page->post_title;
    }
    if ($content != '') {
        $content = $content;
    }
    else if ($atts['word_count']) {
        $content = spes_trim_paragraph(strip_tags($page->post_content, '<span><a><p><div>'), $atts['word_count'], '(...)');
    }
    else {
        $content = apply_filters( 'get_the_excerpt', $post->post_excerpt );
    }
    $options['template'] = $template;
    $options['is_icon'] = $atts['is_icon'];
    if ($atts['is_icon']) {
        $options['icon_size'] = $excerpt_icon_size;
    }
    $options['src'] = ($atts['src']) ? $atts['src'] : $thumb_url[0]; //$image;
    switch ($template) {
        case 1:
            $text .= template_1($id, $title, $content, $the_image, get_permalink($id), $options);
            break;
        case 2:
            $text .= template_2($id, $title, $content, $the_image, get_permalink($id), $options);
            break;
        case 3:
            $text .= template_3($id, $title, $content, $the_image, get_permalink($id), $options);                        
            break;
        case 4:
            $text .= template_4($id, $title, $content, $the_image, get_permalink($id), $options);                        
            break;                    
        default :
            $text .= template_3($id, $title, $content, $the_image, get_permalink($id), $options);                
    }                

      $text .= '</div><div style="clear:both"></div>';
      return $text;  
}

function smpl_page_exerpt_old($atts, $content)
{
    global $post;

    $template = $atts['t'];
    if (!$template) {
        $template = 3;
    }

    if (!$atts['thumb_size']) {
        $atts['thumb_size'] = '100';
    }

    $thumb_size = 'excerpt-icon-' . $atts['thumb_size'];
    $excerpt_icon_size = 'excerpt-icon-' . $atts['thumb_size'];

    $hide_title = $atts['hide_title'];

    $id = ($atts['id']) ? $atts['id'] : $post->ID;
    /*if ($post->ID == $id) {
        return 'Error, the post is the current page!';
    }*/
    $q = 'p=' . $id . '&posts_per_page=1';
    if ($atts['type']) {
        $q .= '&post_type=' . $atts['type'];
    }
    query_posts($q);
      if (have_posts()) : ?>
       <?php while (have_posts()) : the_post();
            $image = get_the_post_thumbnail(get_the_ID(), $thumb_size);
            if ($atts['src']) {
                $src = $atts['src'];
                $image = '<img src="' . $src . '" class="attachment-thumbnail" alt="thumbnail" />';
            }

            if($atts['is_icon'] == 1) {
                $thumb_id = get_post_thumbnail_id();
                $thumb_url = wp_get_attachment_image_src($thumb_id, $excerpt_icon_size, true);                    
                $the_image = '';
            }
            else {
                $the_image = '<a title="' . get_the_title() . '"href="' . get_permalink(). '" class="thumbnail">' . $image . '</a>';
            }

            if (!$hide_title) {
                $title = ($atts['title']) ? $atts['title'] : get_the_title();
            }
            if ($content != '') {
                $content = $content;
            }
            else if ($atts['word_count']) {
                $content = spes_trim_paragraph(strip_tags(get_the_content(), '<span><a><p><div>'), $atts['word_count'], '(...)');
            }
            else {
                $content = get_the_excerpt();
            }
            $options['template'] = $template;
            $options['is_icon'] = $atts['is_icon'];
            if ($atts['is_icon']) {
                $options['icon_size'] = $excerpt_icon_size;
            }
            $options['src'] = ($atts['src']) ? $atts['src'] : $thumb_url[0]; //$image;
            switch ($template) {
                case 1:
                    $text .= template_1(get_the_ID(), $title, $content, $the_image, get_permalink(), $options);
                    break;
                case 2:
                    $text .= template_2(get_the_ID(), $title, $content, $the_image, get_permalink(), $options);
                    break;
                case 3:
                    $text .= template_3(get_the_ID(), $title, $content, $the_image, get_permalink(), $options);                        
                    break;
                case 4:
                    $text .= template_4(get_the_ID(), $title, $content, $the_image, get_permalink(), $options);                        
                    break;                    
                default :
                    $text .= template_3(get_the_ID(), $title, $content, $the_image, get_permalink(), $options);                
            }                


             endwhile; ?>
       <?php else :
           $text = 'No post found.';
           endif;


      wp_reset_query();
      $text .= '</div><div style="clear:both"></div>';
      return $text;
}

function spes_trim_paragraph($str, $word_limit, $append = '')
{
    $arr = explode(' ', $str);
    $total = count($arr);
    $new_string = '';
    for ($i = 0; $i < $word_limit; $i++) {
        $new_string .= ' ' . $arr[$i];
    }
    
    if ($word_limit < $total) {
        $new_string .= $append;
    }
    return $new_string;
}