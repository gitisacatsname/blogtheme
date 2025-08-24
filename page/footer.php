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
    <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'site-wrapper', 'items_wrap' => '<ul class="%2$s">%3$s</ul>', 'depth' => 1 ) ); ?>
</nav>
<footer class="site-footer" role="contentinfo">
    <div class="site-wrapper">
        
    </div>
</footer>
<?php get_sidebar(); ?>
<?php wp_footer(); ?>
</body>
</html>