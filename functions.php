<?php 

/*
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
$content_width = 500;

if ( ! function_exists( 'twentyten_mw_continue_reading_link' ) ) :
/**
 * Return a "Continue Reading" link for excerpts.
 *
 * @since Twenty Ten 1.0
 *
 * @return string "Continue Reading" link.
 */
function twentyten_mw_continue_reading_link() {
	return ' <span class="continue-reading"><a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) . '</a></span>';
}
endif;

/**
 * add search in menu bar.
 */
add_filter( 'wp_nav_menu_items','add_search_box', 10, 2 );
function add_search_box( $items, $args ) {
$items .= '<li id="menu-search">' . get_search_form( false ) . '</li>';
	return $items;
};

/**
 * Replace "[...]" with an ellipsis and twentyten_continue_reading_link().
 *
 * "[...]" is appended to automatically generated excerpts.
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Ten 1.0
 *
 * @param string $more The Read More text.
 * @return string An ellipsis.
 */
function twentyten_mw_auto_excerpt_more( $more ) {
	return $more;
}

function twentyten_mw_custom_excerpt_more( $output ) {
	if ( !is_attachment() && function_exists("has_post_thumbnail") && has_post_thumbnail() && strpos($output, '<img src') == false ) {
		return $output . "<p>" . get_the_post_thumbnail(get_the_ID(), '500w', array("style" => "max-width: 500px; height: auto;")) . twentyten_mw_continue_reading_link() . "</p>";
	}
	return $output.= twentyten_mw_continue_reading_link();
}

function turnoff_twentyten_auto_excerpt_more() {
    remove_filter( 'excerpt_more', 'twentyten_auto_excerpt_more' );
    remove_filter( 'get_the_excerpt', 'twentyten_custom_excerpt_more' );
}

add_action( 'after_setup_theme', 'turnoff_twentyten_auto_excerpt_more' );
add_filter( 'excerpt_more', 'twentyten_mw_auto_excerpt_more' );
add_filter( 'get_the_excerpt', 'twentyten_mw_custom_excerpt_more' );

add_action('get_header', 'enable_threaded_comments'); 

function enable_threaded_comments() {
    if (!is_admin()) {
        if (is_singular() && comments_open() && (get_option('thread_comments') == 1))
            wp_enqueue_script('comment-reply');
    }
}

?>
