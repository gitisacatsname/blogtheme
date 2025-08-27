<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Page
 * @since Page 1.0
 */

get_header();

if (is_category()) { ?>
    <h1 class="archive-title"><span><?php _e("Posts Categorized:", 'page'); ?></span> <?php single_cat_title(); ?></h1><?php
    if ( category_description() ) { ?>
        <div class="archive-meta"><?php echo category_description(); ?></div><?php
    }
} elseif (is_tag()) { ?>
    <h1 class="archive-title"><span><?php _e("Posts Tagged:", 'page'); ?></span> <?php single_tag_title(); ?></h1><?php
    if ( tag_description() ) { ?>
            <div class="archive-meta"><?php echo tag_description(); ?></div><?php
    }
} elseif (is_author()) {
    global $post;
    $author_id = $post->post_author;
    ?>
    <h1 class="archive-title"><span><?php _e("Posts By:", 'page'); ?></span> <?php echo get_the_author_meta('display_name', $author_id); ?></h1><?php
    // If a user has filled out their description, show a bio on their entries.
    if ( get_the_author_meta( 'description', $author_id ) ) { ?>
    <div class="author-info clearfix">
        <div class="author-avatar">
            <?php echo get_avatar( get_the_author_meta( 'user_email', $author_id ), 128 ); ?>
        </div>
        <div class="author-description">
            <h2><?php printf( __( 'About %s', 'page' ), get_the_author_meta('display_name', $author_id)); ?></h2>
            <p><?php the_author_meta( 'description', $author_id ); ?></p>
        </div>
    </div>
    <?php

    }
?>
    <?php
} elseif (is_day()) { ?>
    <h1 class="archive-title"><span><?php _e("Daily Archives:", 'page'); ?></span> <?php the_time('l, F j, Y'); ?></h1><?php
} elseif (is_month()) { ?>
    <h1 class="archive-title"><span><?php _e("Monthly Archives:", 'page'); ?></span> <?php the_time('F Y'); ?></h1><?php
} elseif (is_year()) { ?>
    <h1 class="archive-title"><span><?php _e("Yearly Archives:", 'page'); ?></span> <?php the_time('Y'); ?></h1><?php
}

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        get_template_part( 'content', get_post_format() );
    }
    nc_page_content_nav( 'nav-below' );
} else { ?>
    <article id="post-0" class="not-found">
        <header class="entry-header">
            <h1 class="entry-title"><?php _e( 'Nothing Found', 'page' ); ?></h1>
        </header>
        <div class="entry-content">
            <p><?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'page' ); ?></p>
            <?php get_search_form(); ?>
        </div>
    </article>
<?php }

get_footer();

