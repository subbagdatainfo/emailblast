<?php
/**
* Plugin Name: Tulisan Terbaru
* Plugin URI: http://kdesain.com
* Description: Menampilkan tulisan terbaru setiap hari dari konten multisite
* Version: 0.2
* Author: Ahmad Bagwi Rifai
* Author URI: https://instagram.com/ahmadbagwi
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function tulisan_terbaru_actions() {
     add_menu_page('Tulisan Terbaru', 'Tulisan terbaru', 'read', 'tulisan_terbaru', 'tulisan_terbaru_function', '', 87);
     add_submenu_page('tulisan_terbaru', 'Draft', 'Draft', 'read', 'tulisan-draft', 'draft_function' );
};
add_action('admin_menu', 'tulisan_terbaru_actions');

function tulisan_terbaru_function() {
	echo '<h3>Tulisan terbaru website kebudayaan'.date('j F Y').'</h3>';
	$blogs = get_last_updated();
	echo '<table class="widefat" cellspacing="0">';
	
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
		$wpb_all_query = new WP_Query($args); ?>
		<?php if ( $wpb_all_query->have_posts() ) : ?>
	 
	    	<!-- the loop -->
	    	<?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>
	       <tr> <td><?php the_time('H:i');?></td><td><?php echo get_bloginfo('name');?></td><td><a href="<?php the_permalink(); ?>"><?php the_title();?></a></td></tr>
	    	<?php endwhile; ?>
	    	<!-- end of the loop -->
			 
	    <?php wp_reset_postdata(); ?>
	 	<?php endif;
	  		restore_current_blog();
  	}
  	return ($html);
}
add_shortcode('artikel-terbaru', 'tulisan_terbaru_function');

function draft_function() { 
	echo "<h3>Draft tulisan website kebudayaan ".date('j F Y')."</h3>";

	$blogs = get_last_updated();?>
	<table class="widefat" cellspacing="0">
	<?php
 	foreach ($blogs AS $blog) {    
		switch_to_blog($blog["blog_id"]);
		$today = getdate();
		$args = array(
    	'post_type'         => 'post',
    	'post_status'       => 'draft',
    	'date_query'        => array(
    		array(
            'year'  => $today['year'],
            'month' => $today['mon'],
            'day'   => $today['mday']
        		)
    		)
		);
		$wpb_all_query = new WP_Query($args); ?>
		<?php if ( $wpb_all_query->have_posts() ) : ?>
	 
		
	    	<!-- the loop -->
	    	<?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>
	       <tr> <td><?php the_time('H:i');?></td><td><?php echo get_bloginfo('name');?></td><td><a href="<?php the_permalink(); ?>"><?php the_title();?></a></td></tr>
	    	<?php endwhile; ?>
	    	<!-- end of the loop -->
		
	 
	    <?php wp_reset_postdata(); ?>
	 
		<?php endif;
	  		restore_current_blog();
  	};
}; 
