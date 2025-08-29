<?php
/**
 * Page functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * @package WordPress
 * @subpackage Page
 * @since Page 1.0
 */

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
    $content_width = 740;

$nc_page_socials = array(
    array( 'easid', __( 'easID', 'page' ), '5' ),
    array( 'twitter', __( 'Twitter', 'page' ), 'a' ),
    array( 'facebook', __( 'Facebook', 'page' ), 'b' ),
    array( 'google', __( 'Google+', 'page' ), 'c' ),
    array( 'pinterest', __( 'Pinterest', 'page' ), 'd' ),
    array( 'vkontakte', __( 'VKontakte', 'page' ), ';' ),
    array( 'linkedin', __( 'Linkedin', 'page' ), 'j' ),
    array( 'rss', __( 'RSS', 'page' ), ',' ),
    array( 'foursquare', __( 'foursquare', 'page' ), 'e' ),
    array( 'yahoo', __( 'Yahoo!', 'page' ), 'f' ),
    array( 'skype', __( 'skype', 'page' ), 'g' ),
    array( 'yelp', __( 'yelp', 'page' ), 'h' ),
    array( 'feedburner', __( 'FeedBurner', 'page' ), 'i' ),
    array( 'viadeo', __( 'Viadeo', 'page' ), 'k' ),
    array( 'xing', __( 'Xing', 'page' ), 'l' ),
    array( 'myspace', __( 'Myspace', 'page' ), 'm' ),
    array( 'soundcloud', __( 'soundcloud', 'page' ), 'n' ),
    array( 'spotify', __( 'Spotify', 'page' ), 'o' ),
    array( 'grooveshark', __( 'grooveshark', 'page' ), 'p' ),
    array( 'lastfm', __( 'last.fm', 'page' ), 'q' ),
    array( 'youtube', __( 'YouTube', 'page' ), 'r' ),
    array( 'vimeo', __( 'vimeo', 'page' ), 's' ),
    array( 'dailymotion', __( 'Dailymotion', 'page' ), 't' ),
    array( 'vine', __( 'Vine', 'page' ), 'u' ),
    array( 'flickr', __( 'flickr', 'page' ), 'v' ),
    array( '500px', __( '500px', 'page' ), 'w' ),
    array( 'instagram', __( 'Instagram', 'page' ), 'x' ),
    array( 'wordpress', __( 'WordPress', 'page' ), 'y' ),
    array( 'tumblr', __( 'tumblr', 'page' ), 'z' ),
    array( 'blogger', __( 'Blogger', 'page' ), 'A' ),
    array( 'technorati', __( 'Technorati', 'page' ), 'B' ),
    array( 'reddit', __( 'reddit', 'page' ), 'C' ),
    array( 'dribbble', __( 'dribbble', 'page' ), 'D' ),
    array( 'stumbleupon', __( 'StumbleUpon', 'page' ), 'E' ),
    array( 'digg', __( 'Digg', 'page' ), 'F' ),
    array( 'envato', __( 'Envato', 'page' ), 'G' ),
    array( 'behance', __( 'Behance', 'page' ), 'H' ),
    array( 'delicious', __( 'Delicious', 'page' ), 'I' ),
    array( 'deviantart', __( 'deviantART', 'page' ), 'J' ),
    array( 'forrst', __( 'Forrst', 'page' ), 'K' ),
    array( 'play', __( 'Play Store', 'page' ), 'L' ),
    array( 'zerply', __( 'Zerply', 'page' ), 'M' ),
    array( 'wikipedia', __( 'Wikipedia', 'page' ), 'N' ),
    array( 'apple', __( 'Apple', 'page' ), 'O' ),
    array( 'flattr', __( 'Flattr', 'page' ), 'P' ),
    array( 'github', __( 'GitHub', 'page' ), 'Q' ),
    array( 'chimein', __( 'Chime.in', 'page' ), 'R' ),
    array( 'friendfeed', __( 'FriendFeed', 'page' ), 'S' ),
    array( 'newsvine', __( 'NewsVine', 'page' ), 'T' ),
    array( 'identica', __( 'Identica', 'page' ), 'U' ),
    array( 'bebo', __( 'bebo', 'page' ), 'V' ),
    array( 'zynga', __( 'zynga', 'page' ), 'W' ),
    array( 'steam', __( 'steam', 'page' ), 'X' ),
    array( 'xbox', __( 'XBOX', 'page' ), 'Y' ),
    array( 'windows', __( 'Windows', 'page' ), 'Z' ),
    array( 'outlook', __( 'Outlook', 'page' ), '1' ),
    array( 'coderwall', __( 'coderwall', 'page' ), '2' ),
    array( 'tripadvisor', __( 'tripadvisor', 'page' ), '3' ),
    array( 'lanyrd', __( 'Lanyrd', 'page' ), '7' ),
    array( 'slideshare', __( 'SlideShare', 'page' ), '8' ),
    array( 'buffer', __( 'Buffer', 'page' ), '9' ),
    array( 'disqus', __( 'DISQUS', 'page' ), ':' ),
);


/**
 * Sets up theme defaults and registers the various WordPress features that
 * Page supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 *  custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Page 1.0
 */
function nc_page_setup() {
    /*
     * Makes Page available for translation.
     *
     * Translations can be added to the /languages/ directory.
     * If you're building a theme based on Page, use a find and replace
     * to change 'page' to the name of your theme in all the template files.
     */
    load_theme_textdomain( 'page', get_template_directory() . '/languages' );

    // This theme styles the visual editor with editor-style.css to match the theme style.
    add_editor_style();

    // Adds RSS feed links to <head> for posts and comments.
    add_theme_support( 'automatic-feed-links' );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menu( 'primary', __( 'Footer Menu', 'page' ) );

    // Disable default gallery css styles
    add_filter( 'use_default_gallery_style', '__return_false' );

    // Enable Post Thumbnails support
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 740, 9999 );

    /*
     * This theme supports custom background color and image, and here
     * we also set up the default background color.
     */
    add_theme_support( 'custom-background', array(
        'default-color' => 'ffffff',
    ) );

    $args = array(
        'flex-width'    => true,
        'width'         => 740,
        'flex-height'   => true,
        'height'        => 200,
        'header-text'   => false
    );
    add_theme_support( 'custom-header', $args );

    // This theme uses a custom image size for featured images, displayed on "standard" posts.
}
add_action( 'after_setup_theme', 'nc_page_setup' );


/* =============================================================================
   Options Page
   ========================================================================== */

function nc_page_admin_head( $hook_suffix ) {

    if ( array_key_exists('page', $_REQUEST) && 'nc_page_theme_options' == $_REQUEST['page'] ) {
        ?>
        <style>
        .page-theme {}
        .page-theme .product {
            float: right;
            margin: 0 0 10px 10px;
        }
        .page-theme .product img{
            max-width: 125px;
            height: auto;
        }
        .page-theme p a{
            font-weight: bold;
        }
        .page-theme .contribute a {
            text-decoration: none;
        }
        .page-theme .contribute .easid a {
            color: #1ABC9C;
        }
        .page-theme .contribute .easid a:hover {
            color: #D54E21;
        }
        .page-theme .follow a {
            display: block;
            float: left;
            width: 48px;
            height: 48px;
            margin-right: 16px;
            background: transparent none top left no-repeat;
        }
        .page-theme .follow a img {
            max-width: 48px;
            max-height: 48px;
        }
        </style>
        <?php
    }

}
add_action('admin_head', 'nc_page_admin_head');


/**
 * This function introduces a single theme menu option into the WordPress 'Appearance'
 * menu.
 */
function nc_page_theme_menu() {

    add_theme_page(
        __( 'Page Theme Options', 'page' ),
        __( 'Page Theme Options', 'page' ),
        'administrator',
        'nc_page_theme_options',
        'nc_page_theme_display'
    );

}
add_action('admin_menu', 'nc_page_theme_menu');


/**
 * Renders a simple page to display for the theme menu defined above.
 */
function nc_page_theme_display() {
    echo '<div class="wrap page-theme">';
        echo '<h2>' . __( 'Theme Options', 'page' ) . '</h2>';
        echo '<form action="options.php" method="POST">';
            settings_errors('page-settings-group');
            settings_fields( 'page-settings-group' );
            do_settings_sections( 'nc_page_theme_options' );
            submit_button();
        echo '</form>';
    echo '</div>';

} // end nc_page_theme_display


/**
 * Initializes the theme options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */
function nc_page_initialize_theme_options() {

    global $nc_page_socials;

    register_setting( 'page-settings-group', 'page-settings', 'nc_page_settings_validate' );

    // First, we register a section. This is necessary since all future options must belong to one.
    add_settings_section(
        'section-general-settings',             // ID used to identify this section and with which to register options
        __( 'Social Networks', 'page' ),        // Title to be displayed on the administration page
        'nc_page_general_settings_callback',       // Callback used to render the description of the section
        'nc_page_theme_options'                    // Page on which to add this section of options
    );

    $settings = (array) get_option( 'page-settings' );

    foreach ( $nc_page_socials as $social ) {
        add_settings_field( 'field-' . $social[0], $social[1], 'nc_page_field_social_callback', 'nc_page_theme_options', 'section-general-settings', array(
            'name' => 'page-settings[' . $social[0] . ']',
            'value' => array_key_exists( $social[0], $settings ) ? $settings[$social[0]] : '',
        ) );
    }

}
add_action('admin_init', 'nc_page_initialize_theme_options');

function nc_page_general_settings_callback() {
    echo '<p>' . __( 'For each social network, fill with your profile URLs, e.g. http://twitter.com/example', 'page' ) . '</p>';
}

function nc_page_field_social_callback( $args ) {
    $name = esc_attr( $args['name'] );
    $value = esc_attr( $args['value'] );
    echo "<input type='text' name='$name' value='$value' class='regular-text' />";
}

function nc_page_settings_validate( $input ) {

    global $nc_page_socials;

    $output = get_option( 'page-settings' );

    error_log( print_r($input, true) );
    error_log( print_r($output, true) );

    foreach ( $nc_page_socials as $social ) {
        $newVal = '';
        if ( array_key_exists( $social[0], $input ) ) {
            if ( trim( $input[ $social[0] ] ) != '' ) {
                $newVal = esc_url_raw( trim( $input[ $social[0] ] ) );
                if ( $newVal == '' ) {
                    add_settings_error( 'page-settings-group', 'invalid-' . $social[0], sprintf( __( 'Invalid url for %s : %s', 'page'), $social[1], $input[ $social[0] ] ) );
                }
            }
        }
        $output[ $social[0] ] = $newVal;
    }
    return $output;
}

/* =============================================================================
   Frontend
   ========================================================================== */

/**
 * Enqueues scripts and styles for front-end.
 *
 * @since Page 1.0
 */
function nc_page_scripts_styles() {
    global $wp_scripts;
    /*
     * Loads our main stylesheet.
     */
    wp_enqueue_style( 'page-style', get_stylesheet_uri() );
    wp_enqueue_script( 'pageslide', get_template_directory_uri() . '/js/pageslide.min.js', array( 'jquery' ), '2.0', true );
    /*
     * Loads the Internet Explorer specific stylesheet. doesn't work ??
     */
    // wp_register_script( 'page-html5-ie', get_template_directory_uri() . '/js/html5.js', false, "3.6", false );
    // $wp_scripts->add_data( 'page-html5-ie', 'conditional', 'lt IE 9' );
    // wp_enqueue_script( 'page-html5-ie' );
}
add_action( 'wp_enqueue_scripts', 'nc_page_scripts_styles' );


function nc_page_custom_script_styles() {
    ?>
         <script type="text/javascript">
             jQuery(document).ready(function($) {
                 var $trigger = $('#pageslide-trigger');
                 if( $trigger.length > 0 ) {
                    $trigger.pageslide({direction: 'left'});
                 }
                 $('.pageslide-close').click(function(){
                    $.pageslide.close();
                 });
             });
         </script>
    <?php
}
add_action( 'wp_footer', 'nc_page_custom_script_styles');

/**
 * enqueue comment-reply
 *
 * @since Page 1.03
  */
function nc_page_enqueue_comment_reply() {
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'nc_page_enqueue_comment_reply' );


/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Page 1.03
 */
function nc_page_widgets_init() {

    register_sidebar( array(
        'name' => __( 'Main Sidebar', 'page' ),
        'id' => 'sidebar',
        'description' => __( 'Appears', 'page' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

}
add_action( 'widgets_init', 'nc_page_widgets_init' );

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Page 1.03
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function nc_page_wp_title( $title, $sep ) {
    global $paged, $page;

    if ( is_feed() )
        return $title;

    // Add the site name.
    $title .= get_bloginfo( 'name' );

    // Add the site description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ( $paged >= 2 || $page >= 2 )
        $title = "$title $sep " . sprintf( __( 'Page %s', 'page' ), max( $paged, $page ) );

    return $title;
}
add_filter( 'wp_title', 'nc_page_wp_title', 10, 2 );

/**
 * Default fallback for empty titles.
 *
 * @since Page 1.03
 */
function nc_page_title_empty( $title, $id ) {
    if ( trim( $title ) == '' ) {
        return __( 'No Title', 'page' );
    }
    return $title;
}
add_filter('the_title', 'nc_page_title_empty', 10, 2);

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Page 1.03
 */
function nc_page_page_menu_args( $args ) {
    if ( ! isset( $args['show_home'] ) )
        $args['show_home'] = true;
    return $args;
}
add_filter( 'wp_page_menu_args', 'nc_page_page_menu_args' );




if ( ! function_exists( 'nc_page_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own nc_page_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Page 1.03
 */
function nc_page_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
        // Display trackbacks differently than normal comments.
    ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <p><?php _e( 'Pingback:', 'page' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'page' ), '<span class="edit-link">', '</span>' ); ?></p>
    <?php
            break;
        default :
        // Proceed with normal comments.
        global $post;
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <header class="comment-meta comment-author vcard">
                <?php
                    printf( '<cite class="fn">%1$s %2$s</cite>',
                        get_comment_author_link(),
                        // If current post author is also comment author, make it known visually.
                        ( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'page' ) . '</span>' : ''
                    );
                    printf( '<a href="%1$s"><time datetime="%2$s">&mdash; %3$s</time></a>',
                        esc_url( get_comment_link( $comment->comment_ID ) ),
                        get_comment_time( 'c' ),
                        /* translators: 1: date, 2: time */
                        sprintf( __( '%1$s at %2$s', 'page' ), get_comment_date(), get_comment_time() )
                    );
                ?>
            </header><!-- .comment-meta -->

            <?php if ( '0' == $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'page' ); ?></p>
            <?php endif; ?>

            <section class="comment-content comment">
                <?php comment_text(); ?>

                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'page' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                <?php edit_comment_link( __( '(Edit)', 'page' ), '<span class="edit-link">', '</span>' ); ?>
            </section><!-- .comment-content -->

        </article><!-- #comment-## -->
    <?php
        break;
    endswitch; // end comment_type check
}
endif;


if ( ! function_exists( 'nc_page_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Page 1.03
 */
function nc_page_content_nav( $html_id ) {
    global $wp_query;

    $html_id = esc_attr( $html_id );
    
    if ( isset( $wp_query->query['post_type'] ) && 'page' === $wp_query->query['post_type'] ) {
        $pages = wp_list_pages( array(
            'title_li' => '',
            'echo'     => false,
        ) );
        if ( $pages ) : ?>
            <nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
                <ul class="page-list">
                    <?php echo $pages; ?>
                </ul>
            </nav>
        <?php endif;
        return;
    }

    if ( $wp_query->max_num_pages > 1 ) : ?>
        <nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
            <div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'page' ) ); ?></div>
            <div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'page' ) ); ?></div>
        </nav>
    <?php endif;
}
endif;


if ( ! function_exists( 'nc_page_single_post_nav' ) ) :
/**
 * Displays navigation to next/previous post when applicable.
 *
 * @since Page 1.03
 */
function nc_page_single_post_nav() {
    ?><nav class="navigation" role="navigation">
        <?php previous_post_link('<div class="nav-previous alignleft"><span class="meta-nav">&larr;</span> %link</div>'); ?>
        <?php next_post_link('<div class="nav-next alignright">%link <span class="meta-nav">&rarr;</span></div>'); ?>
    </nav><?php
}
endif;


if ( ! function_exists( 'nc_page_entry_meta_header' ) ) :
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own nc_page_entry_meta() to override in a child theme.
 *
 * @since Page 1.03
 */
function nc_page_entry_meta_header() {

    $date = sprintf( '<time class="entry-date" datetime="%1$s"><span>%2$s</span></time>',
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() )
    );

    $utility_text = __( '%1$s', 'page' );

    printf(
        $utility_text,
        $date
    );
}
endif;


if ( ! function_exists( 'nc_page_entry_meta_footer' ) ) :
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own nc_page_entry_meta() to override in a child theme.
 *
 * @since Page 1.03
 */
function nc_page_entry_meta_footer() {
    // Translators: used between list items, there is a space after the comma.
    $categories_list = get_the_category_list( __( ', ', 'page' ) );

    // Translators: used between list items, there is a space after the comma.
    $tag_list = get_the_tag_list( '', __( ', ', 'page' ) );

    if ( is_page() ) {
        if ( $tag_list ) {
            printf( __( 'posted in %1$s and tagged %2$s.', 'page' ), $categories_list, $tag_list );
        } elseif ( $categories_list ) {
            printf( __( 'in %1$s.', 'page' ), $categories_list );
        }
        return;
    }

    $author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        esc_attr( sprintf( __( 'View all posts by %s', 'page' ), get_the_author() ) ),
        get_the_author()
    );

    // Translators: 1 is category, 2 is tag and 3 is the author's name.
    if ( $tag_list ) {
        $utility_text = __( 'posted in %1$s and tagged %2$s <span class="by-author"> by %3$s</span>.', 'page' );
    } elseif ( $categories_list ) {
        $utility_text = __( 'in %1$s <span class="by-author"> by %3$s</span>.', 'page' );
    } else {
        $utility_text = __( 'posted <span class="by-author"> by %3$s</span>.', 'page' );
    }

    printf(
        $utility_text,
        $categories_list,
        $tag_list,
        $author
    );
}
endif;


if ( ! function_exists( 'nc_page_wp_nav_menu_args' ) ) :
    function nc_page_wp_nav_menu_args( $args = '' ) {
        $args['container'] = false;
        return $args;
    }
    add_filter( 'wp_nav_menu_args', 'nc_page_wp_nav_menu_args' );
endif;


if ( ! function_exists( 'nc_page_get_socials_networks' ) ) :
    function nc_page_get_socials_networks() {

        global $nc_page_socials;

        $settings = (array) get_option( 'page-settings' );

        $html = '';
        $out = array();

        foreach ( $nc_page_socials as $social ) {

            if ( array_key_exists( $social[0], $settings ) ) {
                $url = trim( $settings[ $social[0] ] );
                if (  $url != '' ) {
                    $out[] = '<a href="' . esc_url( $url ) . '" target="_blank" title="' . esc_attr( $social[1] ) . '" class="i-' . esc_attr( $social[0] ) . '">' . $social[2] . '</a>';
                }
            }

        }

        if ( count( $out ) ){
            $html = '<div class="site-socials">' . implode( '', $out ) . '</div>';
        }

        return $html;

    }
endif;


/* =============================================================================
   Deprecated functions
   ========================================================================== */

if ( ! function_exists( 'page_comment' ) ) :
    /**
     * @deprecated Use {@see nc_page_comment} instead
     */
    function page_comment($comment, $args, $depth){
        trigger_error('page_comment  has been replaced with nc_page_comment', E_USER_NOTICE);
        return nc_page_comment($comment, $args, $depth);
    }
endif;

if ( ! function_exists( 'page_content_nav' ) ) :
    /**
     * @deprecated Use {@see nc_page_content_nav} instead
     */
    function page_content_nav( $html_id ){
        trigger_error('page_content_nav  has been replaced with nc_page_content_nav', E_USER_NOTICE);
        return nc_page_content_nav( $html_id );
    }
endif;

if ( ! function_exists( 'page_entry_meta_header' ) ) :
    /**
     * @deprecated Use {@see nc_page_entry_meta_header} instead
     */
    function page_entry_meta_header(){
        trigger_error('page_entry_meta_header has been replaced with nc_page_entry_meta_header', E_USER_NOTICE);
        return nc_page_entry_meta_header();
    }
endif;

if ( ! function_exists( 'page_entry_meta_footer' ) ) :
    /**
     * @deprecated Use {@see nc_page_entry_meta_footer} instead
     */
    function page_entry_meta_footer(){
        trigger_error('page_entry_meta_footer  has been replaced with nc_page_entry_meta_footer', E_USER_NOTICE);
        return nc_page_entry_meta_footer();
    }
endif;

if ( ! function_exists( 'page_wp_nav_menu_args' ) ) :
    /**
     * @deprecated Use {@see nc_page_wp_nav_menu_args} instead
     */
    function page_wp_nav_menu_args( $args = '' ){
        trigger_error('page_wp_nav_menu_args  has been replaced with nc_page_wp_nav_menu_args', E_USER_NOTICE);
        return nc_page_wp_nav_menu_args( $args );
    }
endif;

if ( ! function_exists( 'page_get_socials_networks' ) ) :
    /**
     * @deprecated Use {@see nc_page_get_socials_networks} instead
     */
    function page_get_socials_networks(){
        trigger_error('page_get_socials_networks  has been replaced with nc_page_get_socials_networks', E_USER_NOTICE);
        return nc_page_get_socials_networks();
    }
endif;

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

// Retrieve the timestamp of the last Git commit.
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

    wp_localize_script(
        'doom-overlay',
        'DOOM_OVERLAY_CFG',
        array(
            'engineUrl' => nc_theme_file_uri( 'assets/doom/engine/index.html' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'nc_enqueue_doom_overlay_assets' );

// Output the DOOM overlay markup in the page footer.
function nc_render_doom_overlay() {
    ?>
    <div id="doom-procrastinate">
        <button class="doom-open" aria-haspopup="dialog" aria-controls="doom-frame-wrap">Procrastinate <span class="doom-here">here!</span></button>

        <div class="doom-usk" hidden>
            <p>USK 16 – This game is rated USK 16. Dieses Spiel ist ab 16 freigegeben.</p>
            <button class="doom-usk-accept">OK</button>
        </div>

        <div id="doom-frame-wrap" hidden>
            <div class="doom-bar">
                <span class="doom-title">DOOM</span>
                <div class="doom-spacer"></div>
                <button class="doom-fullscreen">Fullscreen</button>
                <button class="doom-close" aria-label="Close">✕</button>
            </div>
            <iframe id="doom-frame" title="DOOM" allow="autoplay; fullscreen; gamepad *" loading="lazy"></iframe>
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'nc_render_doom_overlay' );
