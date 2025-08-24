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

get_header(); ?>

<?php
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
