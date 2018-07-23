<?php
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if ( ! defined('ABSPATH') ) {
    /** Set up WordPress environment */
    require_once( dirname( __FILE__ ) . '/wp-load.php' );
   
    }
	//require 'email_body.php';
	

$blogs = get_last_updated(' ', 1, 5);
foreach ($blogs AS $blog) {
    switch_to_blog($blog["blog_id"]);
    $args = array(
        'orderby'         => 'post_date',
        'order'           => 'DESC',
        'numberposts'     => 1,
        'post_type'       => 'post',
        'post_status'     => 'publish',
        'suppress_filters' => true
    );
    $lastposts = get_posts( $args );

    foreach($lastposts as $thispost) {

        setup_postdata($thispost);

    }
    restore_current_blog();
}
?>