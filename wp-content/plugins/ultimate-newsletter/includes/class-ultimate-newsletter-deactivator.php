<?php

/**
 * Fired during plugin deactivation
 *
 * @link          https://yendif.com
 * @since         1.0.0
 *
 * @package       ultimate-newsletter
 * @subpackage    ultimate-newsletter/includes
 */

// Exit if accessed directly
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Ultimate_Newsletter_Deactivator Class
 *
 * @since    1.0.0
 */
class Ultimate_Newsletter_Deactivator {

	/**
	 * Called when plugin deactivated.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
	
		delete_option( 'rewrite_rules' );

	}

}
