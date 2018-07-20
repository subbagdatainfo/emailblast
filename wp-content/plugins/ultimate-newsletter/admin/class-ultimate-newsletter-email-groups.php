<?php

/**
 * E-mail Groups.
 *
 * @link          https://yendif.com
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
 * Ultimate_Newsletter_Email_Groups Class
 *
 * @since    1.0.0
 */
class Ultimate_Newsletter_Email_Groups {

	/**
	 * Add the email groups submenu.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu() {
		
		add_submenu_page( 
			'ultimate_newsletters', 
			__( 'Email Groups', 'ultimate-newsletter' ), 
			__( 'Email Groups', 'ultimate-newsletter' ), 
			'manage_options', 
			'edit-tags.php?taxonomy=un_email_groups&post_type=ultimate_newsletters'
		); 
		  
	}
	
	/**
	 * Move 'un_email_groups' Taxonomy UI to the plugin's main menu.
	 *
	 * @since    1.0.0
	 *
	 * @param    string    $parent_file    The parent file.
	 * @return   string    $parent_file    If "un_email_groups", slug of our plugin main menu.
	 								       Else, the default parent file.
	 */
	public function tax_menu_correction( $parent_file ) {
	
		global $current_screen, $submenu_file;
		
		$taxonomy = $current_screen->taxonomy;
		
		if( $taxonomy == 'un_email_groups' ) {
			$parent_file  = 'ultimate_newsletters';
			$submenu_file = 'edit-tags.php?taxonomy=un_email_groups&post_type=ultimate_newsletters';
		}
		
		return $parent_file;

	}

	/**
	 * Register the custom taxonomy "un_email_groups".
	 *
	 * @since    1.0.0
	 */
	public function register_custom_taxonomy() {
	
		if( ! taxonomy_exists( 'un_email_groups' ) ) {
			un_register_taxonomy_email_groups();
		}
			
	}
	
	/**
	 * Remove column "Count" from the "un_email_groups" list table.
	 *
	 * @since    1.0.0
	 *
	 * @param    array    $columns    General Custom Taxonomy Columns.
	 * @return   array    $columns    Modified "un_email_groups" Column values.
	 */
	public function remove_column_count( $columns ) {
	
		unset( $columns['posts'] );
    	return $columns;
			
	}
	
}