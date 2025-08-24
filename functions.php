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
