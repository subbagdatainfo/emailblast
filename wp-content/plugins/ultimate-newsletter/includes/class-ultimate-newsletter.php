<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
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
 * Ultimate_Newsletter Class
 *
 * @since    1.0.0
 */
class Ultimate_Newsletter {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       Ultimate_Newsletter_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'includes/class-ultimate-newsletter-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'includes/class-ultimate-newsletter-i18n.php';
		
		/**
		 * The class responsible for sending emails.
		 */
		require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'includes/class-ultimate-newsletter-mailer.php';
		
		/**
		 * The file that hold helper functions
		 */
		require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'includes/helper-functions.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'admin/class-ultimate-newsletter-admin.php';
		require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'admin/class-ultimate-newsletter-email-groups.php';
		require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'admin/class-ultimate-newsletter-subscribers.php';
		require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'admin/class-ultimate-newsletter-settings.php';
		require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'admin/class-ultimate-newsletter-cron.php';
			
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'public/class-ultimate-newsletter-public.php';

		$this->loader = new Ultimate_Newsletter_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ultimate_Newsletter_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Ultimate_Newsletter_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		// Hooks specific to the newsletter page and common to all admin pages
		$plugin_admin = new Ultimate_Newsletter_Admin();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'manage_form_actions' );
		$this->loader->add_action( 'wp_ajax_un_send_test_email', $plugin_admin, 'send_test_email' );
		
		$this->loader->add_filter( 'set-screen-option', $plugin_admin, 'set_screen_option', 10, 3 );	

		// Hooks specific to email groups page
		$plugin_email_groups = new Ultimate_Newsletter_Email_Groups();
		
		$this->loader->add_action( 'admin_menu', $plugin_email_groups, 'admin_menu' );
		$this->loader->add_action( 'init', $plugin_email_groups, 'register_custom_taxonomy' );
		$this->loader->add_action( 'parent_file', $plugin_email_groups, 'tax_menu_correction' );
		$this->loader->add_filter( 'manage_edit-un_email_groups_columns', $plugin_email_groups, 'remove_column_count' );
		
		// Hooks specific to subscribers page
		$plugin_subscribers = new Ultimate_Newsletter_Subscribers();
		
		$this->loader->add_action( 'admin_menu', $plugin_subscribers, 'admin_menu' );		
		$this->loader->add_action( 'admin_init', $plugin_subscribers, 'manage_form_actions' );
		$this->loader->add_action( 'user_register', $plugin_subscribers, 'user_register' );	
		$this->loader->add_action( 'profile_update', $plugin_subscribers, 'profile_update', 10, 2 );
		$this->loader->add_action( 'delete_user', $plugin_subscribers, 'delete_user' );	
		$this->loader->add_action( 'admin_notices', $plugin_subscribers, 'admin_notice' );

		// Hooks specific to settings page
		$plugin_settings = new Ultimate_Newsletter_Settings();
		
		$this->loader->add_action( 'admin_menu', $plugin_settings, 'admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'admin_init' );	
		$this->loader->add_action( 'wp_ajax_un_settings_send_test_email', $plugin_settings, 'send_test_email' );
		
		// Hooks specific to cron
		$plugin_cron = new Ultimate_Newsletter_CRON();
		
		$this->loader->add_filter( 'cron_schedules', $plugin_cron, 'un_schedule' );
		$this->loader->add_action( 'un_cron_send_newsletters', $plugin_cron, 'send_newsletters' );
		
	}
	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		// Hooks common for all public facing functionality of the plugin
		$plugin_public = new Ultimate_Newsletter_Public();

		$this->loader->add_action( 'init', $plugin_public, 'add_rewrites' );	
		$this->loader->add_action( 'init', $plugin_public, 'manage_form_submissions' );
		$this->loader->add_action( 'wp_loaded', $plugin_public, 'maybe_flush_rules' );
		$this->loader->add_action( 'parse_request', $plugin_public, 'parse_request' );			
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Ultimate_Newsletter_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}	

}