<div id="sidebar" class="main-sidebar">
    <span class="pageslide-close">close</span>
    <?php
    echo '<div class="sidebar-section">';
    echo '<h3 class="sidebar-heading">Pages</h3>';
    echo '<ul class="page-list">';
    wp_list_pages( array( 'title_li' => '', 'sort_column' => 'menu_order' ) );
    echo '</ul>';
    echo '</div>';

    $categories = get_terms( array(
        'taxonomy'   => 'category',
        'hide_empty' => false,
    ) );
    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
        echo '<div class="sidebar-section">';
        echo '<h3 class="sidebar-heading">Categories</h3>';
        echo '<ul class="category-list">';
        wp_list_categories( array( 'title_li' => '', 'taxonomy' => 'category', 'hide_empty' => 0 ) );
        echo '</ul>';
        echo '</div>';
    }

    $archive_output = wp_get_archives( array(
        'post_type' => 'page',
        'echo'      => 0,
    ) );
    if ( ! empty( $archive_output ) ) {
        echo '<div class="sidebar-section">';
        echo '<h3 class="sidebar-heading">Archives</h3>';
        echo '<ul class="archive-list">' . $archive_output . '</ul>';
        echo '</div>';
    }

    $tags = get_terms( array(
        'taxonomy'   => 'post_tag',
        'hide_empty' => false,
    ) );
    if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
        echo '<div class="sidebar-section">';
        echo '<h3 class="sidebar-heading">Tags</h3>';
        echo '<ul class="tag-list">';
        wp_tag_cloud( array( 'taxonomy' => 'post_tag', 'format' => 'list', 'hide_empty' => 0 ) );
        echo '</ul>';
        echo '</div>';
    }
    ?>
</div>
<div id="pageslide"></div>
