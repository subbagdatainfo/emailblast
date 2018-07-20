<?php
if ( ! defined('ABSPATH') ) {
    require( dirname( __FILE__ ) . '/wp-load.php' );
    }

function isiemail() {
	$blogs = get_last_updated();
	
	foreach ($blogs AS $blog) {    
		switch_to_blog($blog["blog_id"]);
		$today = getdate();
		$args = array(
    	'post_type'         => 'post',
    	'post_status'       => 'publish',
    	'date_query'        => array(
    		array(
            'year'  => $today['year'],
            'month' => $today['mon'],
            'day'   => $today['mday']
        		)
    		)
		);
		echo the_title($args);
	 	} 
}