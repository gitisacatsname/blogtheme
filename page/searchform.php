<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="search-form">
    <div>
        <label for="search"><?php _e( 'Search', 'page' ); ?></label>
        <input type="search" name="s" class="search-text" value="<?php the_search_query(); ?>" /><input type="submit" class="search-button" value="<?php _e( 'Search', 'page' ); ?>"  />
    </div>
</form>