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

// Resolve a theme asset URI, stripping any leading page/ prefix.
function nc_theme_file_uri( $file ) {
    $file = preg_replace( '#^page/#', '', $file );
    return get_theme_file_uri( $file );
}

// Resolve a theme asset path, stripping any leading page/ prefix.
function nc_theme_file_path( $file ) {
    $file = preg_replace( '#^page/#', '', $file );
    return get_theme_file_path( $file );
}

// Enqueue overlay assets for playing DOOM in the browser.
function nc_enqueue_doom_overlay_assets() {
    $css_rel = 'assets/doom/overlay/doom-overlay.css';
    $css_path = nc_theme_file_path( $css_rel );
    $css_ver = file_exists( $css_path ) ? filemtime( $css_path ) : null;

    $js_rel = 'assets/doom/overlay/doom-overlay.js';
    $js_path = nc_theme_file_path( $js_rel );
    $js_ver = file_exists( $js_path ) ? filemtime( $js_path ) : null;

    wp_enqueue_style( 'doom-overlay', nc_theme_file_uri( $css_rel ), array(), $css_ver );
    wp_enqueue_script( 'doom-overlay', nc_theme_file_uri( $js_rel ), array( 'jquery' ), $js_ver, true );

    $shareware_rel = 'assets/doom/iwads/doom1.wad';
    $shareware_path = nc_theme_file_path( $shareware_rel );
    $shareware = file_exists( $shareware_path ) ? nc_theme_file_uri( $shareware_rel ) : '';

    wp_localize_script( 'doom-overlay', 'DOOM_OVERLAY_CFG', array(
        'engineUrl'   => nc_theme_file_uri( 'assets/doom/engine/index.html' ),
        'freedoomUrl' => nc_theme_file_uri( 'assets/doom/iwads/freedoom1.wad' ),
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
