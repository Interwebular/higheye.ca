<?php
/*this function truncates titles to create preview excerpts*/
if ( ! function_exists( 'truncate_short_title' ) ){
	function truncate_short_title( $amount, $echo = true, $post = '' ) {
                $title = get_post_meta(get_the_ID(), 'short_title', true);
                if (!$title) {
                        $title = get_the_title();                    
                }
		if ( $post == '' ) $truncate = $title;
		else $truncate = $post->post_title;

		if ( strlen( $truncate ) <= $amount ) $echo_out = '';
		else $echo_out = '...';

		$truncate = wp_trim_words( $truncate, $amount, '' );

		if ( '' != $echo_out ) $truncate .= $echo_out;

		if ( $echo )
			echo $truncate;
		else
			return $truncate;
	}
}

function get_short_title($id) {
    $title = get_post_meta($id, 'short_title', true);
    if (!$title) {
        $title = get_the_title($id);
    }
    return $title;
}

/** META BOX ***************************/
/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function myplugin_add_meta_box() {
	$screens = array('post', 'page');
	foreach ( $screens as $screen ) {
		add_meta_box(
			'myplugin_sectionid',
			__( 'Additional Details', 'myplugin_textdomain' ),
			'myplugin_meta_box_callback',
			$screen
		);
	}
}

add_action( 'add_meta_boxes', 'myplugin_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function myplugin_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'myplugin_meta_box', 'myplugin_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
        global $child_theme_custom_post_meta;        
	$data = $child_theme_custom_post_meta;
        
        foreach ($data as $name => $d) {
            $value = get_post_meta( $post->ID, $name, true);         
            $type = $d['type'];
            //echo $type;
            echo '<div><label for="short_title">';
            _e( $d['label'], 'myplugin_textdomain');
            echo '</label><br /> ';            
	        switch ($type) {
        	    case 'checkbox':
                	echo '<input type="checkbox" name="data[' . $name . ']" ' . (($value == 'on') ? 'checked="checked"' : '') . '>';
			break;
        	    case 'textarea':
                	echo '<textarea name="data[' . $name . ']">' . $value . '</textarea>';
			break;                	
	            case 'text':
        	        echo '<input type="text" name="data[' . $name . ']" value="' . $value . '">';
			break;                
        	}               
        	echo '</div>';

        }
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function myplugin_save_meta_box_data( $post_id ) {


	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['myplugin_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['myplugin_meta_box_nonce'], 'myplugin_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}


	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.

	// Sanitize user input.        
	$my_data = $_POST['data'];
       
        global $child_theme_custom_post_meta;        
	$data = $child_theme_custom_post_meta;

               
        foreach ($data as $idx => $value) {
            // Update the meta field in the database.
            update_post_meta( $post_id, $idx, $_POST['data'][$idx]);            
        }
         //print_r($my_data); exit;
}

add_action( 'save_post', 'myplugin_save_meta_box_data' );