<?php
/**
 * Template Name: artikel terbaru
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Fourteen 1.0
 */
get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
			function tulisan_terbaru_function() { 
				echo "<h3>Tulisan terbaru website kebudayaan ".date('j F Y')."</h3>";

				$blogs = get_last_updated();?>
				<table class="widefat" cellspacing="0">
				<?php
			 	foreach ($blogs AS $blog)    {    
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
			  	};
			};
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer();
