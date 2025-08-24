<?php if ( is_active_sidebar( 'sidebar' ) ) { ?>
<div id="sidebar" class="main-sidebar">
    <span class="pageslide-close">close</span>
    <?php dynamic_sidebar( 'sidebar' ); ?>
</div>
<div id="pageslide"></div>
<?php } ?>
