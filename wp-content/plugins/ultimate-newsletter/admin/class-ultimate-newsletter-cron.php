<?php 
/**
 * CRON.
 *
 * @link          http://yendif.com
 * @since         1.0.0
 *
 * @package       ultimate-newsletter
 * @subpackage    ultimate-newsletter/admin
 */

// Exit if accessed directly
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Ultimate_Newsletter_CRON Class
 *
 * @since    1.0.0
 */
class Ultimate_Newsletter_CRON {
	
	/**
	 * Create custom CRON schedule.
	 *
	 * @since    1.0.0
	 */
	public function un_schedule( $schedules ) {
		
		$email_throttling_settings = get_option( 'un_email_throttling_settings');		
		$interval = $email_throttling_settings['interval'];
		
		switch( $interval ) {
			case '1m':
				$duration = 1 * 60;
				$display  = __( 'Every 1 minute', 'ultimate-newsletter' );
				break;
			case '2m':
				$duration = 2 * 60;
				$display  = __( 'Every 2 minutes', 'ultimate-newsletter' );
				break;
			case '5m':
				$duration = 5 * 60;
				$display  = __( 'Every 5 minutes', 'ultimate-newsletter' );
				break;
			case '10m':
				$duration = 10 * 60;
				$display  = __( 'Every 10 minutes', 'ultimate-newsletter' );
				break;
			case '15m':
				$duration = 15 * 60;
				$display  = __( 'Every 15 minutes', 'ultimate-newsletter' );
				break;
			case '30m':
				$duration = 30 * 60;
				$display  = __( 'Every 30 minutes', 'ultimate-newsletter' );
				break;
			case '1h':
				$duration = 1 * 60 * 60;
				$display  = __( 'Every 1 hour', 'ultimate-newsletter' );
				break;
			case '2h':
				$duration = 2 * 60 * 60;
				$display  = __( 'Every 2 hours', 'ultimate-newsletter' );
				break;
			default :
				$duration = 1 * 60;
				$display  = __( 'Every 1 minute', 'ultimate-newsletter' );
				break;
		}
			
		$schedules['un_schedule'] = array(
			'interval' => $duration,
			'display'  => $display
		);
		
		return $schedules;
		
	}

	/**
	 * Send Newsletter Emails.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $newsletter_id    Newsletter ID.
	 */
	public function send_newsletters( $newsletter_id = 0 ) {
		
		$settings = get_option( 'un_email_throttling_settings');
		$newsletter_ids = get_option( 'un_cron_queue', array() );
		$cron = false;
		
		if( 0 == $newsletter_id ) {
		
			$cron = true;
			
			if( empty( $newsletter_ids ) ) {
				// unschedule this cron job here
				$time = wp_next_scheduled( 'un_cron_send_newsletters' );
				wp_unschedule_event( $time, 'un_cron_send_newsletters' );

				return;
			}
		
			foreach( $newsletter_ids as $id ) {
				$status = get_post_meta( $id, 'status', true );
				if( 'scheduled' == $status ) {
					$newsletter_id = $id;
					break;
				}
			}
			
		}

		if( $newsletter_id == 0 ) return;

		// Get Email Groups assigned to this post(newsletter)
		$email_groups = wp_get_object_terms( $newsletter_id, 'un_email_groups', array( 'fields' => 'ids' ) );
		
		// Get subscribers list	
		$args = array(
			'post_type'   	 => 'un_subscribers',
			'posts_per_page' => $settings['quantity'],
			'post_status' 	 => 'publish',
			'fields'         => 'ids',
			'tax_query'   	 => array(
				array(
					'taxonomy' => 'un_email_groups',
					'field'    => 'term_id',
					'terms'    => $email_groups,
					'operator' => 'IN',
				),
			),
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' 	  => 'status',
					'value'   => 'subscribed',
					'compare' => '='
				),
				array(
					'relation' => 'OR',
					array(
						'key' 	  => 'newsletters_received',
						'compare' => 'NOT EXISTS'
					),
					array(
						'key' 	  => 'newsletters_received',
						'value'   => '"'.$newsletter_id.'"',
						'compare' => 'NOT LIKE'
					),
				)
			)
		);
					
		$subscribers = get_posts( $args );	
		
		if( count( $subscribers ) ) {	
						
			// Send newsletter to the subscribers
			$subject = get_the_title( $newsletter_id );
			
			foreach( $subscribers as $subscriber ) {	
				
				$subscriber_id = (int) $subscriber;
				
				$to 	 = get_post_meta( $subscriber_id, 'email', true );
				$message = un_prepare_newsletter_content( $newsletter_id, $subscriber_id );
				
				if( Ultimate_Newsletter_Mailer::send_mail( $to, $subject, $message, $newsletter_id ) ) {
								
					// Update newsletter meta
					$subscribers_sent 	= (array) get_post_meta( $newsletter_id, 'sent', true );
					$subscribers_sent[] = strval( $subscriber_id );
					$subscribers_sent 	= array_filter( $subscribers_sent );
								
					update_post_meta( $newsletter_id, 'sent', array_unique( $subscribers_sent ) );
					
					// Update subscriber meta
					$newsletters_received 	= (array) get_post_meta( $subscriber_id, 'newsletters_received', true );
					$newsletters_received[] = strval( $newsletter_id );
					$newsletters_received 	= array_filter( $newsletters_received );
								
					update_post_meta( $subscriber_id, 'newsletters_received', array_unique( $newsletters_received ) );
					
				}
			}

		} else {
		
			un_remove_from_cron_queue( $newsletter_id );
			update_post_meta( $newsletter_id, 'status', 'sent' );
			
			if( $cron == true ) $this->send_newsletters();
			
		}
			
	}
	
}