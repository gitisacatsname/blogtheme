<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Page
 * @since Page 1.0
 */
?>
    </div>
</div>
<nav class="site-navigation" role="navigation">
    <?php wp_nav_menu( array(
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'site-wrapper',
        'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
        'depth'          => 0,            // show all levels
        'fallback_cb'    => false         // handle fallback manually
    ) ); ?>
</nav>
<footer class="site-footer" role="contentinfo">
    <div class="site-wrapper">
        <p class="last-updated">Last updated: <?php echo esc_html( nc_get_last_updated() ); ?></p>
    </div>
</footer>
<?php get_sidebar(); ?>
<?php wp_footer(); ?>
</body>
</html>