<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://yendif.com
 * @since      1.0.0
 *
 * @package    ultimate-newsletter
 */

// If uninstall not called from WordPress, then exit.
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

// Delete All the Custom Post Types
$un_post_types = array( 'ultimate_newsletters', 'un_subscribers' );

foreach( $un_post_types as $post_type ) {

	$items = get_posts( array( 'post_type' => $post_type, 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids' ) );
	
	if( $items ) {
		foreach( $items as $item ) {
			// Delete the actual post
			wp_delete_post( $item, true );
		}
	}
			
}

// Delete All the Terms & Taxonomies
$un_taxonomies = array( 'un_email_groups' );

foreach( $un_taxonomies as $taxonomy ) {

	$terms = $wpdb->get_results( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('%s') ORDER BY t.name ASC", $taxonomy ) );
	
	// Delete Terms
	if( $terms ) {
		foreach( $terms as $term ) {
			$wpdb->delete( $wpdb->term_taxonomy, array( 'term_taxonomy_id' => $term->term_taxonomy_id ) );
			$wpdb->delete( $wpdb->terms, array( 'term_id' => $term->term_id ) );
		}
	}
	
	// Delete Taxonomies
	$wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => $taxonomy ), array( '%s' ) );

}

// Delete the Plugin Pages
$un_general_settings = get_option( 'un_general_settings' );
$un_signup_confirmation_settings = get_option( 'un_signup_confirmation_settings' );

if( ! empty( $un_general_settings['actions_page'] ) ) {
	wp_delete_post( (int) $un_general_settings['actions_page'], true );
}

if( ! empty( $un_general_settings['subscriber_profile_page'] ) ) {
	wp_delete_post( (int) $un_general_settings['subscriber_profile_page'], true );
}

if( ! empty( $un_general_settings['unsubscribe_page'] ) ) {
	wp_delete_post( (int) $un_general_settings['unsubscribe_page'], true );
}

if( ! empty( $un_signup_confirmation_settings['confirmation_page'] ) ) {
	wp_delete_post( (int) $un_signup_confirmation_settings['confirmation_page'], true );
}

// Delete all the Plugin Options
delete_option( 'un_general_settings' );
delete_option( 'un_email_settings' );
delete_option( 'un_email_smtp_settings' );
delete_option( 'un_email_throttling_settings' );
delete_option( 'un_signup_confirmation_settings' );
delete_option( 'un_subscriber_wp_users' );
delete_option( 'un_version' );