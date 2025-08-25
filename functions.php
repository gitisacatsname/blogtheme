<?php
/**
 * Theme functions.
 */

// Enable categories and tags for pages.
function nc_enable_page_taxonomies() {
    register_taxonomy_for_object_type( 'category', 'page' );
    register_taxonomy_for_object_type( 'post_tag', 'page' );
}
add_action( 'init', 'nc_enable_page_taxonomies' );

// Remove the default Recent Posts widget.
function nc_unregister_widgets() {
    unregister_widget( 'WP_Widget_Recent_Posts' );
}
add_action( 'widgets_init', 'nc_unregister_widgets', 11 );

// Include pages in archive, category, and tag queries.
function nc_include_pages_in_archives( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( $query->is_date() || $query->is_category() || $query->is_tag() ) {
        $query->set( 'post_type', 'page' );
    }
}
add_action( 'pre_get_posts', 'nc_include_pages_in_archives' );

// Enqueue assets and output markup for the procrastinate button and Doom overlay.
function nc_enqueue_procrastinate_assets() {
    $theme_uri = get_template_directory_uri();
    wp_enqueue_style( 'procrastinate', $theme_uri . '/css/procrastinate.css' );
    wp_enqueue_script( 'lottie', $theme_uri . '/js/vendor/lottie.min.js', array(), null, true );
    wp_enqueue_script( 'dos', $theme_uri . '/js/vendor/dos.js', array(), null, true );
    wp_enqueue_script( 'procrastinate', $theme_uri . '/js/procrastinate.js', array( 'lottie', 'dos' ), null, true );
    wp_add_inline_script( 'procrastinate', 'var themeUrl = "' . $theme_uri . '";', 'before' );
}
add_action( 'wp_enqueue_scripts', 'nc_enqueue_procrastinate_assets' );

function nc_render_procrastinate_markup() {
    echo '<div id="procrastinate-btn"></div>';
    echo '<div id="doom-overlay"><div id="doom-container"></div><button id="close-doom">Close</button></div>';
}
add_action( 'wp_footer', 'nc_render_procrastinate_markup' );
