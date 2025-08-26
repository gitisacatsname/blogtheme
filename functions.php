<?php
/**
 * Theme functions.
 */


if ( ! defined( 'NC_FREEDOOM_URL' ) ) {
    define( 'NC_FREEDOOM_URL', 'https://raw.githubusercontent.com/freedoom/historic/trunk/0.6.4/freedoom2.wad' );
}
if ( ! defined( 'NC_SHAREWARE_URL' ) ) {
    define( 'NC_SHAREWARE_URL', 'https://raw.githubusercontent.com/Akbar30Bill/DOOM_wads/master/doom1.wad' );
}


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

// Get timestamp of last git commit for theme directory.
function nc_get_last_updated() {
    $dir = get_template_directory();
    $timestamp = trim( shell_exec( 'git -C ' . escapeshellarg( $dir ) . ' log -1 --format=%cI' ) );
    if ( empty( $timestamp ) ) {
        $timestamp = gmdate( 'c' );
    }
    return $timestamp;
}

// Normalize a theme asset path, stripping any leading page/ segment when the
// active theme resides in a page subdirectory.
function nc_normalize_theme_file( $file ) {
    $file = ltrim( $file, '/' );
    if ( 'page' === basename( get_stylesheet_directory() ) ) {
        $file = preg_replace( '#^page/#', '', $file );
    }
    return $file;
}

// Resolve a theme asset URI.
function nc_theme_file_uri( $file ) {
    return get_theme_file_uri( nc_normalize_theme_file( $file ) );
}

// Resolve a theme asset path.
function nc_theme_file_path( $file ) {
    return get_theme_file_path( nc_normalize_theme_file( $file ) );
}

// Enqueue assets and output markup for the procrastinate button and Doom overlay.
function nc_enqueue_procrastinate_assets() {
    $css_rel = 'css/procrastinate.css';
    $css_path = nc_theme_file_path( $css_rel );
    $css_ver = file_exists( $css_path ) ? filemtime( $css_path ) : null;

    $js_rel = 'js/procrastinate.js';
    $js_path = nc_theme_file_path( $js_rel );
    $js_ver = file_exists( $js_path ) ? filemtime( $js_path ) : null;

    wp_enqueue_style( 'procrastinate', nc_theme_file_uri( $css_rel ), array(), $css_ver );
    wp_enqueue_script( 'lottie', 'https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.6/lottie.min.js', array(), null, true );
    wp_enqueue_script( 'js-dos', 'https://js-dos.com/6.22/current/js-dos.js', array(), null, true );
    wp_enqueue_script( 'procrastinate', nc_theme_file_uri( $js_rel ), array( 'lottie', 'js-dos' ), $js_ver, true );

    wp_localize_script( 'procrastinate', 'PROCRASTINATE_CFG', array(
        'lottieUrl' => nc_theme_file_uri( 'js/lotties/procrastination.json' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'nc_enqueue_procrastinate_assets' );

function nc_render_procrastinate_markup() {
    echo '<div id="procrastinate-btn"></div>';
    echo '<div id="doom-overlay"><div id="doom-container"></div><button id="close-doom">Close</button></div>';
}
add_action( 'wp_footer', 'nc_render_procrastinate_markup' );
