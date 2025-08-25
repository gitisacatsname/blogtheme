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
if ( is_home() ) {
    // Show latest pages instead of posts on the index.
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'paged'          => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
    );
    query_posts( $args );
}

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        get_template_part( 'content', 'page' );
    }
    nc_page_content_nav( 'nav-below' );
} else { ?>
    <article id="post-0" class="not-found">
        <header class="entry-header">
            <h1 class="entry-title"><?php _e( 'Nothing Found', 'page' ); ?></h1>
        </header>
        <div class="entry-content">
            <p><?php _e( 'Apologies, but no pages were found. Perhaps searching will help find a related page.', 'page' ); ?></p>
            <?php get_search_form(); ?>
            <ul class="page-list">
                <?php wp_list_pages( array( 'title_li' => '' ) ); ?>
            </ul>
        </div>
    </article>
<?php }

if ( is_home() ) {
    wp_reset_query();
}

?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/procrastinate.css">
<div id="procrastinate-btn"></div>
<div id="doom-overlay">
    <div id="doom-container"></div>
    <button id="close-doom">Close</button>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.10.2/lottie.min.js"></script>
<script src="https://js-dos.com/v7/build/dos.js"></script>
<script>var themeUrl = "<?php echo get_template_directory_uri(); ?>";</script>
<script src="<?php echo get_template_directory_uri(); ?>/js/procrastinate.js"></script>
<?php
get_footer();
