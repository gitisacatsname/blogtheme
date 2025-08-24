<div id="sidebar" class="main-sidebar">
    <span class="pageslide-close">close</span>
    <?php
    if ( is_active_sidebar( 'sidebar' ) ) {
        dynamic_sidebar( 'sidebar' );
    }
    echo '<ul class="page-list">';
    wp_list_pages( array( 'title_li' => '', 'sort_column' => 'menu_order' ) );
    echo '</ul>';
    ?>
</div>
<div id="pageslide"></div>
