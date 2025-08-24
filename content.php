<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Page
 * @since Page 1.0
 */
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header>
            <?php
            if ( is_single() ) { ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php } else { ?>
            <h1 class="entry-title">
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'page' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
            </h1>
            <?php
            }
            ?>
            <?php nc_page_entry_meta_header(); ?>
        </header>

        <?php
        if ( is_search() ) { // Only display Excerpts for Search ?>
        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div>
        <?php } else { ?>
        <div class="entry-content">
            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'page' ) ); ?>
            <?php
            wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'page' ), 'after' => '</p>' ) );
            if ( is_single() ) {
                nc_page_single_post_nav();
            }
            ?>
        </div>
        <?php } ?>

        <footer>
            <?php nc_page_entry_meta_footer(); ?>
            <?php edit_post_link( __( 'Edit', 'page' ), '<span class="edit-link">', '</span>' ); ?>
        </footer>
    </article>
    <?php if ( is_singular() )
        comments_template( '', true ); ?>
