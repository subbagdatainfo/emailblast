<?php
if ( ! defined('ABSPATH') ) {
    /** Set up WordPress environment */
    require( dirname( __FILE__ ) . '/wp-load.php' );
    }
function display_blogs() {
    // loop through all blogs
    $all_blog = wp_get_sites();
    foreach ($blog_ids as $key=>$current_blog) {
        // switch to each blog to get the posts
        switch_to_blog($current_blog['blog_id']);
        // fetch all the posts 
        $blog_posts = get_posts(array( 'posts_per_page' => -1));
        restore_current_blog();
        // display all posts
    }
}?>