<?php

/**
 * The plugin bootstrap file
 *
 * @link           https://yendif.com
 * @since          1.0.0
 * @package        ultimate-newsletter
 *
 * @wordpress-plugin
 * Plugin Name:    Ultimate Newsletter
 * Plugin URI:     https://yendif.com
 * Description:    Easy to use WordPress newsletter plugin. Send professional email newsletters and manage subscribers in WordPress.
 * Version:        1.2.0
 * Author:         Yendif Technologies
 * Author URI:     https://yendif.com
 * License:        GPL-2.0+
 * License URI:    http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:    ultimate-newsletter
 * Domain Path:    /languages
 */

// If this file is called directly, abort.
if( ! defined( 'WPINC' ) ) {
	die;
}

// Name of the plugin
if( ! defined( 'ULTIMATE_NEWSLETTER_PLUGIN_NAME' ) ) {	
  	define( 'ULTIMATE_NEWSLETTER_PLUGIN_NAME', 'Ultimate Newsletter' );
}

// Unique identifier for the plugin. Used as Text Domain
if( ! defined( 'ULTIMATE_NEWSLETTER_PLUGIN_SLUG' ) ) {
	define( 'ULTIMATE_NEWSLETTER_PLUGIN_SLUG', 'ultimate-newsletter' );
}

// Path to the plugin directory
if( ! defined( 'ULTIMATE_NEWSLETTER_PLUGIN_DIR' ) ) {
	define( 'ULTIMATE_NEWSLETTER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// URL of the plugin
if( ! defined( 'ULTIMATE_NEWSLETTER_PLUGIN_URL' ) ) {
	define( 'ULTIMATE_NEWSLETTER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// The actual plugin version
if( ! defined( 'ULTIMATE_NEWSLETTER_PLUGIN_VERSION' ) ) {
	define( 'ULTIMATE_NEWSLETTER_PLUGIN_VERSION', '1.2.0' );
}
 
/**
 * The code that runs during plugin activation.
 */
function activate_ultimate_newsletter() {
	
	require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'includes/class-ultimate-newsletter-activator.php';
	Ultimate_Newsletter_Activator::activate();
		
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_ultimate_newsletter() {
	
	require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'includes/class-ultimate-newsletter-deactivator.php';
	Ultimate_Newsletter_Deactivator::deactivate();
	
}

register_activation_hook( __FILE__, 'activate_ultimate_newsletter' );
register_deactivation_hook( __FILE__, 'deactivate_ultimate_newsletter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'includes/class-ultimate-newsletter.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
*/
function run_ultimate_newsletter() {

	$plugin = new Ultimate_Newsletter();
	$plugin->run();

	// Register widgets
	require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'widgets/subscription-form/subscription-form.php';
	
}

run_ultimate_newsletter();