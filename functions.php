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

// Enqueue overlay assets for playing DOOM in the browser.
function nc_enqueue_doom_overlay_assets() {
    $theme_uri = get_stylesheet_directory_uri();
    $theme_dir = get_stylesheet_directory();

    wp_enqueue_style( 'doom-overlay', $theme_uri . '/assets/doom/overlay/doom-overlay.css', array(), '1.0' );
    wp_enqueue_script( 'doom-overlay', $theme_uri . '/assets/doom/overlay/doom-overlay.js', array(), '1.0', true );

    $shareware = file_exists( $theme_dir . '/assets/doom/iwads/doom1.wad' )
        ? $theme_uri . '/assets/doom/iwads/doom1.wad'
        : '';

    wp_localize_script( 'doom-overlay', 'DOOM_OVERLAY_CFG', array(
        'engineUrl'   => $theme_uri . '/assets/doom/engine/index.html',
        'freedoomUrl' => $theme_uri . '/assets/doom/iwads/freedoom1.wad',
        'sharewareUrl'=> $shareware,
    ) );
}
add_action( 'wp_enqueue_scripts', 'nc_enqueue_doom_overlay_assets' );

// Output the DOOM overlay markup in the page footer.
function nc_render_doom_overlay() {
    ?>
    <div id="doom-procrastinate">
        <button class="doom-open" aria-haspopup="dialog" aria-controls="doom-frame-wrap">Play DOOM</button>

        <div id="doom-frame-wrap" hidden>
            <div class="doom-bar">
                <span class="doom-title">DOOM</span>
                <div class="doom-spacer"></div>
                <button class="doom-iwad doom-iwad-freedoom">Freedoom</button>
                <button class="doom-iwad doom-iwad-shareware">Shareware</button>
                <button class="doom-fullscreen">Fullscreen</button>
                <button class="doom-close" aria-label="Close">✕</button>
            </div>
            <iframe id="doom-frame" title="DOOM" allow="autoplay; fullscreen; gamepad *" loading="lazy"></iframe>
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'nc_render_doom_overlay' );
