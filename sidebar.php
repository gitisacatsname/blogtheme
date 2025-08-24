<div id="sidebar" class="main-sidebar">
    <span class="pageslide-close">close</span>
    <?php
    echo '<ul class="page-list">';
    wp_list_pages( array( 'title_li' => '', 'sort_column' => 'menu_order' ) );
    echo '</ul>';

    $categories = get_terms( array(
        'taxonomy'   => 'category',
        'hide_empty' => false,
    ) );
    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
        echo '<ul class="category-list">';
        wp_list_categories( array( 'title_li' => '', 'taxonomy' => 'category' ) );
        echo '</ul>';
    }

    $archive_output = wp_get_archives( array(
        'post_type' => 'page',
        'echo'      => 0,
    ) );
    if ( ! empty( $archive_output ) ) {
        echo '<ul class="archive-list">' . $archive_output . '</ul>';
    }

    $tags = get_terms( array(
        'taxonomy'   => 'post_tag',
        'hide_empty' => false,
    ) );
    if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
        echo '<ul class="tag-list">';
        wp_tag_cloud( array( 'taxonomy' => 'post_tag', 'format' => 'list' ) );
        echo '</ul>';
    }
    ?>
</div>
<div id="pageslide"></div>
