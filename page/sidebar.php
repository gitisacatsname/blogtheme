<?php if ( is_active_sidebar( 'sidebar' ) || ! has_nav_menu( 'primary' ) ) { ?>
<div id="sidebar" class="main-sidebar">
    <span class="pageslide-close">close</span>
    <?php if ( is_active_sidebar( 'sidebar' ) ) {
        dynamic_sidebar( 'sidebar' );
    }
    if ( ! has_nav_menu( 'primary' ) ) {
        wp_page_menu( array( 'depth' => 0 ) );
    } ?>
</div>
<div id="pageslide"></div>
<?php } ?>
