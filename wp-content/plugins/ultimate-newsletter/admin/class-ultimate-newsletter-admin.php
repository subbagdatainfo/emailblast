<?php 
/**
 * Newsletter.
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
 * Ultimate_Newsletter_Admin Class
 *
 * @since    1.0.0
 */
class Ultimate_Newsletter_Admin {

	/**
	 * Register the stylesheets for the admin-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'tinymce_css' );
		
		wp_enqueue_style( ULTIMATE_NEWSLETTER_PLUGIN_SLUG.'-jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );	
		wp_enqueue_style( ULTIMATE_NEWSLETTER_PLUGIN_SLUG, ULTIMATE_NEWSLETTER_PLUGIN_URL.'admin/css/ultimate-newsletter-admin.css', array(), ULTIMATE_NEWSLETTER_PLUGIN_VERSION, 'all' );
		wp_enqueue_style( ULTIMATE_NEWSLETTER_PLUGIN_SLUG.'-fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css' );

	}

	/**
	 * Register the JavaScript for the admin-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_media();				
		wp_enqueue_script('jquery');
    	wp_enqueue_script("jquery-ui-sortable");
    	wp_enqueue_script("jquery-ui-draggable");
    	wp_enqueue_script("jquery-ui-droppable"); 
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script('tiny_mce');
		
		wp_enqueue_script( ULTIMATE_NEWSLETTER_PLUGIN_SLUG, ULTIMATE_NEWSLETTER_PLUGIN_URL . 'admin/js/ultimate-newsletter-admin.js', ULTIMATE_NEWSLETTER_PLUGIN_VERSION, array( 'jquery', 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false );
		
		wp_localize_script( ULTIMATE_NEWSLETTER_PLUGIN_SLUG, 'un', array(
				'plugin_url'                  => ULTIMATE_NEWSLETTER_PLUGIN_URL,
				'sort_placeholder_text'       => __( 'Drop Blocks Here', 'ultimate-newsletter' ),
				'import_subscribers_i18n'     => __( 'Import Subscribers', 'ultimate-newsletter' ),
				'continue_i18n'               => __( 'Continue to Import', 'ultimate-newsletter' ),
				'export_subscribers_i18n'     => __( 'Export Subscribers', 'ultimate-newsletter' ),
				'required_import_data_i18n'   => __( 'You have not added any data to import.', 'ultimate-newsletter' ),
				'required_email_groups_i18n'  => __( 'Select atleast one email group.', 'ultimate-newsletter' ),
				'required_email_column_i18n'  => __( 'No email column found.', 'ultimate-newsletter' ),
				'required_export_fields_i18n' => __( 'Select atleast one field to export.', 'ultimate-newsletter' )
			) 
		);

	}	
	
	/**
	 * Add "Ultimate Newsletter" mainmenu and the "Newsletters" submenu.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu(){
	
		   add_menu_page( 
		  	 __( 'Ultimate Newsletter', 'ultimate-newsletter' ),
			 __( 'Ultimate Newsletter', 'ultimate-newsletter' ),
			  'manage_options', 
			  'ultimate_newsletters', 
			  array( $this, 'display_newsletters' ),
			  'dashicons-chart-line',
			  25
		  );
		  
		  $hook = add_submenu_page( 
		  	'ultimate_newsletters',
		  	 __( 'All Newsletters', 'ultimate-newsletter' ),
			 __( 'All Newsletters', 'ultimate-newsletter' ),
			  'manage_options', 
			  'ultimate_newsletters', 
			  array( $this, 'display_newsletters' )
		  );
		  
		  add_action( "load-$hook", array( $this, 'screen_options' ) );
		  		 
	}
	
	/**
	 * Add Screen Options.
	 *
	 * @since    1.0.0
	 */
	public function screen_options() {
	
 		global $ultimate_newsletters_list_table;
		
		$option = 'per_page';
	 
		$args = array(
			'label'   => __( 'Ultimate Newsletter', 'ultimate-newsletter' ),
			'default' => 20,
			'option'  => 'items_per_page'
		);
	 
		add_screen_option( $option, $args );
		
		$ultimate_newsletters_list_table = new Ultimate_Newsletters_List_Table();
 
	}	
	
	/**
	 * Filters a screen option value before it is set.
	 *
	 * @since    1.0.0
	 *
	 * @param    bool|int    $status    Screen option value. Default false to skip.
     * @param    string      $option    The option name.
     * @param    int         $value     The number of rows to use.
	 * @return   int         $value     Rows count to be stored.
	 */
	public function set_screen_option( $status, $option, $value ) {
	
		return $value;
		
	}
	
	/**
	 * Display Newsletters.
	 *
	 * @since    1.0.0
	 */
	public function display_newsletters() {
		
		$action = $this->get_current_action();
		
		switch( $action ) {
			case 'add' :
				$this->display_create_campaign_form();
				break;
			case 'edit' :
				$tab = isset( $_REQUEST['tab'] ) ? sanitize_text_field( $_REQUEST['tab'] ) : ''; 
				( 'templates' == $tab ) ? $this->display_templates_form() : $this->display_schedule_form();
				break;			
			default :
				global $ultimate_newsletters_list_table;
				$ultimate_newsletters_list_table->prepare_items();
				
				include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/newsletters/ultimate-newsletters-list.php';
		}
		
	} 
	
	/**
	 * Manage form actions.
	 *
	 * @since    1.0.0
	 */
	public function manage_form_actions() {
	
		if( isset( $_REQUEST['page'] ) && 'ultimate_newsletters' == $_REQUEST['page'] ) {
		
			$action = $this->get_current_action();
		
			switch( $action ) {
				case 'save' :
					$this->save();
					break;	
				case 'paused'    :
				case 'scheduled' :
					$newsletter_id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
					update_post_meta( $newsletter_id, 'status', $action );
				
					wp_redirect( admin_url( 'admin.php?page=ultimate_newsletters' ) );
					exit();
					break;
				case 'batch' :
					if( isset( $_GET['id'] ) ) {
						do_action( 'un_cron_send_newsletters', (int) $_GET['id'] );
					}
				
					wp_redirect( admin_url( 'admin.php?page=ultimate_newsletters' ) );
					exit();
					break;
				case 'duplicate' :
					$this->duplicate();
					break;			
				case 'delete' :
				case 'bulk-delete' :
					$this->delete();
					break;
			}
		
		}
	
	}
	
	/**
	 * Get the current action.
	 *
	 * @since    1.0.0
	 */
	public function get_current_action() {
	
		$action = '';
		
		if( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] )
			$action = $_REQUEST['action'];

		if( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] )
			$action = $_REQUEST['action2'];
			
		if( -1 == $action ) 
			$action = '';

		return $action;
		
	}	
	
	/**
	 * Display the campaign creation form.
	 *
	 * @since    1.0.0
	 */
	public function display_create_campaign_form() {		
		
		$email_groups_list = get_terms( 'un_email_groups', array( 'hide_empty' => 0 ) );
		$email_groups = array();
		
		include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/newsletters/ultimate-newsletter-create-campaign.php';
		
	}

	/**
	 * Save the current form.
	 *
	 * @since    1.0.0
	 */
	public function save() {
	
		$action        = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : '';
		$tab	       = isset( $_POST['tab'] ) ? sanitize_text_field( $_POST['tab'] ) : '';
		$newsletter_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		
		if( 'create' ==  $tab  ) {
		
			// Step 1
			$title = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
			
			$post = array(
				'ID'          => $newsletter_id,
				'post_type'	  => 'ultimate_newsletters',
				'post_status' => 'publish',
				'post_title'  => $title
			);	
			
			$newsletter_id = wp_insert_post( $post );	
			
			update_post_meta( $newsletter_id, 'status', 'draft' );

			$email_groups = isset( $_POST['email_groups'] ) ? $_POST['email_groups'] : '';
			
			wp_set_object_terms( $newsletter_id, '', 'un_email_groups' );
			if( ! empty( $email_groups ) ) {
				foreach( $email_groups as $email_group ) {
					wp_set_object_terms( $newsletter_id, (int) $email_group, 'un_email_groups', true );
				}
			}
			
			$redirect_url = admin_url( 'admin.php?page=ultimate_newsletters&action=edit&tab=templates&id='.$newsletter_id );
			
			
		} else if( 'templates' ==  $tab  ) {
			
			// Step 2	
			if( isset( $_POST['template'] ) ) {
				
				$template = sanitize_text_field( $_POST['template'] );
				
				update_post_meta( $newsletter_id, 'template', $template );
			
				$redirect_url = admin_url( 'admin.php?page=ultimate_newsletters&action=edit&tab=templates&id='.$newsletter_id );

			} else {
			
				$post_content = $_POST['post_content'];
				
				$post = array(
					'ID'           => $newsletter_id,
					'post_type'	   => 'ultimate_newsletters',
					'post_content' => $post_content
				);	
			
				wp_update_post( $post );
				
				$redirect_url = admin_url( 'admin.php?page=ultimate_newsletters&action=edit&tab=schedule&id='.$newsletter_id );
					
			}
				
		} else { 

			// Step 3 (final)
			$title    	    = isset( $_POST['title'] )  ? sanitize_text_field( $_POST['title'] )  : '';
			$status    	    = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : 'draft';
			$from_name      = isset( $_POST['from_name'] ) ? sanitize_text_field( $_POST['from_name'] ) : '';
			$from_email     = isset( $_POST['from_email'] ) ? sanitize_email( $_POST['from_email'] ) : '';
			$reply_to_name  = isset( $_POST['reply_to_name'] ) ? sanitize_text_field( $_POST['reply_to_name'] ) : '';
			$reply_to_email = isset( $_POST['reply_to_email'] ) ? sanitize_text_field( $_POST['reply_to_email'] ) : '';
			
			$post = array(
				'ID'           => $newsletter_id,
				'post_type'	   => 'ultimate_newsletters',
				'post_title'   => $title
			);	
			
			wp_update_post( $post );	
			
			update_post_meta( $newsletter_id, 'from_name', $from_name );	
			update_post_meta( $newsletter_id, 'from_email', $from_email );
			update_post_meta( $newsletter_id, 'reply_to_name', $reply_to_name );
			update_post_meta( $newsletter_id, 'reply_to_email', $reply_to_email );
			
			$email_groups = isset( $_POST['email_groups'] ) ? $_POST['email_groups'] : '';
			
			wp_set_object_terms( $newsletter_id, '', 'un_email_groups' );		
			if( ! empty( $email_groups ) ) {
				foreach( $email_groups as $email_group ) {
					wp_set_object_terms( $newsletter_id, (int) $email_group, 'un_email_groups', true );
				}
			}
			
			if( 'schedule' == $status ) {
			
				$newsletter_ids   = get_option( 'un_cron_queue', array() );
				$newsletter_ids[] = $newsletter_id;
				
				update_option( 'un_cron_queue', array_unique( $newsletter_ids ) );

				if( ! wp_next_scheduled( 'un_cron_send_newsletters' ) ) {
					wp_schedule_event( time(), 'un_schedule', 'un_cron_send_newsletters' );
				}			
			
				update_post_meta( $newsletter_id, 'status', 'scheduled' );	
				
			} else {
				
				update_post_meta( $newsletter_id, 'status', 'draft' );
			
			}
			
			$redirect_url = admin_url( 'admin.php?page=ultimate_newsletters' );
		
		}
	
		wp_redirect( $redirect_url );
		exit();
		
	}
	
	/**
	 * Display Newsletter templates.
	 *
	 * @since    1.0.0
	 */
	public function display_templates_form() {

		$newsletter_id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
		$show_builder  = isset( $_GET['builder'] ) ? (int) $_GET['builder'] : 1;
		
		$post_object = get_post( $newsletter_id );
		
		$post_content    = $post_object->post_content;
		$active_template = get_post_meta( $newsletter_id, 'template', true );
		$status          = get_post_meta( $newsletter_id, 'status', true );
		
		if( $show_builder && '' != $active_template ) {

			if( 'blank' == $active_template ) {
			
				include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/newsletters/ultimate-newsletter-editor.php';
				
			} else {
			
				echo '<style type="text/css">';
				echo file_get_contents( ULTIMATE_NEWSLETTER_PLUGIN_DIR . "templates/$active_template/style.css" );
				echo '</style>';
				
				if( strpos( $post_content, 'unTemplateContainer' ) !== false ) {
    				$post_content = trim( $post_content );
				} else {
					$post_content = file_get_contents( ULTIMATE_NEWSLETTER_PLUGIN_DIR . "templates/$active_template/template.html" );
					$post_content = str_replace( '[UN_PLUGIN_DIR]', ULTIMATE_NEWSLETTER_PLUGIN_URL, $post_content );
					
				}

				include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/newsletters/ultimate-newsletter-builder-elements.php';
				include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/newsletters/ultimate-newsletter-builder.php';
				
			}
		
		} else {
		
			$templates = array();
		
			$templates_dir = ULTIMATE_NEWSLETTER_PLUGIN_DIR . 'templates';		
			foreach( glob( "$templates_dir/*", GLOB_ONLYDIR ) as $template ) {
				$folder = basename( $template );
				$template_json_dir = $template.'/config.json';
				$str = file_get_contents( $template_json_dir );
				$json = json_decode( $str, true ); // decode the JSON into an associative array
				$templates[] = array(
					'title' => $json['template']['title'],
					'name'  => $folder,
					'image' => ULTIMATE_NEWSLETTER_PLUGIN_URL . "templates/$folder/screenshot.png"
				);
			}	
		
			// Insert blank template
			$blank_template = array(
				'title' => __( 'Blank Template', 'ultimate-newsletter' ),
				'name'  => 'blank',
				'image' => ULTIMATE_NEWSLETTER_PLUGIN_URL . "admin/images/blank-template-screenshot.png"
			);
			array_unshift( $templates, $blank_template );
		
			// Insert option to add new template
			$templates[] = array( 'title' => 'new' );				
			
			include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/newsletters/ultimate-newsletter-templates.php';
		
		}
		
	}
	
	/**
	 * Display newsletter schedule form.
	 *
	 * @since    1.0.0
	 */
	public function display_schedule_form() {

		$newsletter_id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
		$settings = get_option( 'un_general_settings' );
		
		$title = get_the_title( $newsletter_id );
		
		$email_groups_list = get_terms( 'un_email_groups', array( 'hide_empty' => 0 ) );
		$email_groups = wp_get_object_terms( $newsletter_id, 'un_email_groups', array( 'fields' => 'ids' ) );

		$status = get_post_meta( $newsletter_id, 'status', true );

		$from_name = get_post_meta( $newsletter_id, 'from_name', true );
		$from_name = ! empty( $from_name ) ? $from_name : $settings['from_name'];
		
		$from_email = get_post_meta( $newsletter_id, 'from_email', true );
		$from_email = ! empty( $from_email ) ? $from_email : $settings['from_email'];
		
		$reply_to_name  = get_post_meta( $newsletter_id, 'reply_to_name', true );	
		$reply_to_name  = ! empty( $reply_to_name ) ? $reply_to_name : $settings['reply_to_name'];
		
		$reply_to_email = get_post_meta( $newsletter_id, 'reply_to_email', true );
		$reply_to_email = ! empty( $reply_to_email ) ? $reply_to_email : $settings['reply_to_email'];	
		
		$to_email = $settings['admin_email'];
		
		include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/newsletters/ultimate-newsletter-schedule.php';
		
	}
	
	/**
	 * Duplicate the selected newsletter.
	 *
	 * @since    1.0.0
	 */
	public function duplicate() {
			
		$newsletter_id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
		
		if( $newsletter_id > 0 ) {
		
			$nonce = esc_attr( $_GET['_wpnonce'] );
			
			if( wp_verify_nonce( $nonce, 'un_duplicate_newsletter' ) ) {
			
				// Get newsletter data that must be duplicated
				$post = get_post( $newsletter_id );

				$email_groups   = wp_get_object_terms( $newsletter_id, 'un_email_groups', array( 'fields' => 'ids' ) );
				$template       = get_post_meta( $newsletter_id, 'template', true );
				$from_name      = get_post_meta( $newsletter_id, 'from_name', true );
				$from_email     = get_post_meta( $newsletter_id, 'from_email', true );
				$reply_to_name  = get_post_meta( $newsletter_id, 'reply_to_name', true );
				$reply_to_email = get_post_meta( $newsletter_id, 'reply_to_email', true );
		
				// Insert duplicate newsletter
				$args = array(
					'post_type'     => 'ultimate_newsletters',
					'post_content'  => $post->post_content,
					'post_title'    => sprintf( __( '%s copy', 'ultimate-newsletter' ), $post->post_title ),
					'post_status'   => 'publish'
				);
				$newsletter_id = wp_insert_post( $args );
		
				if( ! empty( $email_groups ) ) {
					foreach( $email_groups as $email_group ) {
						wp_set_object_terms( $newsletter_id, (int) $email_group, 'un_email_groups', true );
					}
				}
		
				update_post_meta( $newsletter_id, 'template', $template );
				update_post_meta( $newsletter_id, 'from_name', $from_name );	
				update_post_meta( $newsletter_id, 'from_email', $from_email );
				update_post_meta( $newsletter_id, 'reply_to_name', $reply_to_name );
				update_post_meta( $newsletter_id, 'reply_to_email', $reply_to_email );
				update_post_meta( $newsletter_id, 'status', 'draft' );
			
			}
		
		}
		
		wp_redirect( admin_url( 'admin.php?page=ultimate_newsletters' ) );
		exit();
		
	}	
	
	/**
	 * Delete newsletters.
	 *
	 * @since    1.0.0
	 */
	public function delete() {
	
		// Delete single newsletter
		if( isset( $_GET['id'] ) ) {
		
			$nonce = esc_attr( $_GET['_wpnonce'] );
			
			if( wp_verify_nonce( $nonce, 'un_delete_newsletter' ) ) {
			
				$newsletter_id = (int) $_GET['id'];
			
				wp_delete_post( $newsletter_id , true );
				un_remove_from_cron_queue( $newsletter_id );
			
			}
			
		}
		
		// Delete newsletters in bulk action
		if( isset( $_POST['ids'] ) ) {
		
			if( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {
			
				$nonce  = esc_attr( $_POST['_wpnonce'] );

            	if( wp_verify_nonce( $nonce, 'bulk-newsletters' ) ) {
				
					foreach( $_POST['ids'] as $newsletter_id ) {
						$newsletter_id = (int) $newsletter_id;
				
						wp_delete_post( $newsletter_id , true );
						un_remove_from_cron_queue( $newsletter_id );
					}
					
				}
			
			}
			
		}
		
		wp_redirect( admin_url( 'admin.php?page=ultimate_newsletters' ) );
		exit();
		
	}
	
	/**
	 * Send Test Email.
	 *
	 * @since    1.0.0
	 */
	public function send_test_email() {
	
		$newsletter_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;	
		$response = array( 'error' => 1, 'message' => __( 'Sorry, please try again', 'ultimate-newsletter' ) );
		
		if( $newsletter_id > 0 ) {
		
			$general_settings = get_option( 'un_general_settings' );

			$to      = isset( $_POST['email']  ) ? sanitize_email( $_POST['email'] ) : $general_settings['admin_email'];
			$subject = sanitize_text_field( $_POST['title'] );
			$message = un_prepare_newsletter_content( $newsletter_id, un_get_subscriber_id() );
			
			if( Ultimate_Newsletter_Mailer::send_mail( $to, $subject, $message, $newsletter_id ) ) {
				$response['error'] = 0;
		    	$response['message'] = __( 'Newsletter sent. Check your inbox.',  'ultimate-newsletter' );
			}
				
		} 
		
		echo wp_json_encode( $response );
		wp_die();
	
	}
	
}

/**
 * Load the base class.
 *
 * @since    1.0.0
 */
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Ultimate_Newsletters_List_Table Class
 *
 * @since    1.0.0
 */
class Ultimate_Newsletters_List_Table extends WP_List_Table {
	
	/**
	 * Set up a constructor that references the parent constructor.
	 *
	 * @since    1.0.0
	 */
 	 public function __construct() {
                
	   global $status, $page;
		 
     	// Set parent defaults
        parent::__construct( 
			array(
            	'singular' => __( 'Newsletter', 'ultimate-newsletter' ),     
            	'plural'   => __( 'Newsletters', 'ultimate-newsletter' ),   
            	'ajax'     => false       
       		 )
		);
			 
    }	
	
	/**
	 * Add status filters.
	 *
	 * @since    1.0.0
	 */
 	public function link_filters() {
	
		$links = array();
		$base_link = admin_url( 'admin.php?page=ultimate_newsletters' );
		$counts = array( 'all' => 0, 'draft' => 0, 'scheduled' => 0, 'sent' => 0, 'paused' => 0 );
		$status = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all';
		
		$args = array(
			'post_type'	     => 'ultimate_newsletters',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids'
		);	
		$newsletters = get_posts( $args );
		
		$counts['all'] = count( $newsletters );	
		foreach( $newsletters as $newsletter ) {
			$newsletter_status = get_post_meta( (int) $newsletter, 'status', true );
			++$counts[ $newsletter_status ];			
		}
		
		if( $counts['all'] > 0 ) {
		
			$links[] = sprintf( '<li><a href="%s"%s>%s <span class="count">(%d)</span></a></li>', $base_link, ( 'all' == $status ? ' class="current"' : '' ), __( 'All', 'ultimate-newsletter' ), $counts['all'] );	
			
			if( $counts['draft'] > 0 ) {
				$links[] = sprintf( '<li><a href="%s"%s>%s <span class="count">(%d)</span></a></li>', add_query_arg( 'status', 'draft', $base_link ), ( 'draft' == $status ? ' class="current"' : '' ), __( 'Not Sent', 'ultimate-newsletter' ), $counts['draft'] );
			}
			
			if( $counts['scheduled'] > 0 ) {
				$links[] = sprintf( '<li><a href="%s"%s>%s <span class="count">(%d)</span></a></li>', add_query_arg( 'status', 'scheduled', $base_link ), ( 'scheduled' == $status ? ' class="current"' : '' ), __( 'Scheduled', 'ultimate-newsletter' ), $counts['scheduled'] );
			}
			
			if( $counts['sent'] > 0 ) {
				$links[] = sprintf( '<li><a href="%s"%s>%s <span class="count">(%d)</span></a></li>', add_query_arg( 'status', 'sent', $base_link ), ( 'sent' == $status ? ' class="current"' : '' ), __( 'Sent', 'ultimate-newsletter' ), $counts['sent'] );
			}
			
			if( $counts['paused'] > 0 ) {
				$links[] = sprintf( '<li><a href="%s"%s>%s <span class="count">(%d)</span></a></li>', add_query_arg( 'status', 'paused', $base_link ), ( 'paused' == $status ? ' class="current"' : '' ), __( 'paused', 'ultimate-newsletter' ), $counts['paused'] );
			}
		
		}

		if( count( $links ) ) {
			echo '<ul class="subsubsub">'.implode( ' | ', $links ).'</ul>';
		}
	
	}

	/**
	 * Add Month filter.
	 *
	 * @since    1.0.0
	 */
 	protected function extra_tablenav( $which ) {
		
		if( 'top' === $which ) {
				
			echo '<div class="alignleft actions">';
			
			// Months filter
		    $this->months_dropdown( 'ultimate_newsletters' );
			
			// Email groups filter
			$terms = get_terms( 'un_email_groups', array( 'hide_empty' => 0 ) );
			$active_term_id = isset( $_POST['un_email_groups'] ) ? (int) $_POST['un_email_groups'] : 0;  

			echo '<select name="un_email_groups">'; 
			echo '<option value="">'.__( 'All Email Groups', 'ultimate-newsletter' ).'</option>';
			foreach( $terms as $term ) {
			
				 $args = array( 
				 	'post_type'      => 'ultimate_newsletters',
					'post_status' 	 => 'any',
					'posts_per_page' => -1,
        			'fields'         => 'ids',
        			'tax_query'      => array(  
            			array(
                			'taxonomy' => 'un_email_groups',  
                			'field'    => 'term_id',  
                			'terms'    => $term->term_id  
            			)
        			)
     			);
				
				if( isset( $_GET['status'] ) && in_array( $_GET['status'], array( 'draft', 'scheduled', 'sent', 'paused' ) ) ) {
					$args['meta_query'] = array(
						array(
							'key'   => 'status',
							'value' =>  sanitize_text_field( $_GET['status'] ),
						),
					);
				}
		
    			$term_posts = get_posts( $args );

 				printf( '<option value="%s"%s>%s (%d)</option>', $term->term_id, selected( $term->term_id, $active_term_id ), $term->name, count( $term_posts ) ); 
				 
        	}
			echo '</select>';
	
			echo '<input type="submit" name="filter_action" id="post-query-submit" class="button" value="'.__( 'Filter', 'ultimate-newsletter' ).'">';
			echo '</div>';	
				
		}
	
	
	}
	
	/**
	 * Add months dropdown.
	 *
	 * @since    1.0.0
	 *
	 * @param    string    $post_type    Post type.
	 */
	protected function months_dropdown( $post_type ) {
	
    	global $wpdb, $wp_locale;
		
        $months = $wpdb->get_results( $wpdb->prepare( "
            SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month
            FROM $wpdb->posts
            WHERE post_type = %s
            ORDER BY post_date DESC
        ", $post_type ) );
 
        $month_count = count( $months );
 
        if( ! $month_count || ( 1 == $month_count && 0 == $months[0]->month ) )
            return;
 
        $m = isset( $_POST['m'] ) ? (int) $_POST['m'] : 0;
		?>
        <label for="filter-by-date" class="screen-reader-text"><?php _e( 'Filter by date', 'ultimate-newsletter' ); ?></label>
        <select name="m" id="filter-by-date">
            <option<?php selected( $m, 0 ); ?> value="0"><?php _e( 'All dates', 'ultimate-newsletter' ); ?></option>
			<?php
				foreach( $months as $arc_row ) {
					if( 0 == $arc_row->year )
						continue;
	 
					$month = zeroise( $arc_row->month, 2 );
					$year = $arc_row->year;
	 
					printf( "<option %s value='%s'>%s</option>\n",
						selected( $m, $year . $month, false ),
						esc_attr( $arc_row->year . $month ),
					
						sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month ), $year )
					);
				}
			?>
        </select>
		<?php
		
    }
	
	/**
	 * Called when the parent class can't find a method specifically
     * build for a given column.
	 *
	 * @since    1.0.0
	 */
	public function column_default( $item, $column_name ) {
	
        switch( $column_name ) {
            case 'id' : 
				return $item->ID;
            case 'email_groups' :
				return implode( '<br>', wp_get_object_terms( $item->ID, 'un_email_groups', array( 'fields' => 'names' ) ) );
			case 'modified_date' :
				return date_i18n( get_option('date_format').' '.get_option('time_format'), strtotime( $item->post_modified ) );
			case 'post_date' :
				return date_i18n( get_option('date_format').' '.get_option('time_format'), strtotime( $item->post_date ) );
            default:
                return $item->$column_name;
    	}
		
    }
	
	/**
	 * Displaying checkboxes for bulk actions.
	 *
	 * @since    1.0.0
	 */
	public function column_cb( $item ) {
	
        return sprintf( '<input type="checkbox" name="ids[]" value="%d" />', $item->ID );   
		 
    }	
	
	/**
	 * A custom column method and is responsible for what is rendered
	 * in any column with a name/slug of 'title'.
	 *
	 * @since    1.0.0
	 */
	 public function column_title( $item ) {
	 
	 	// Create nonces
		$duplicate_nonce = wp_create_nonce( 'un_duplicate_newsletter' );
  		$delete_nonce = wp_create_nonce( 'un_delete_newsletter' );
		
		// Build row actions
		$newsletter_id = (int) $item->ID;

		$status    = get_post_meta( $newsletter_id, 'status', true );
		$base_link = admin_url( "admin.php?page=ultimate_newsletters&id=$newsletter_id" );

		$actions = array();
		
		if( 'draft' == $status || 'paused' == $status ) {
			$actions['edit'] = sprintf( '<a href="%s">%s</a>', add_query_arg( 'action', 'edit', $base_link ), __( 'Edit', 'ultimate-newsletter' ) );
		}
		
		$actions['preview'] = sprintf( '<a href="%s" target="_blank">%s</a>', un_get_actions_page_link( 'vi', $newsletter_id, un_get_subscriber_id() ), __( 'Preview', 'ultimate-newsletter' ) );
		$actions['duplicate'] = sprintf( '<a href="%s">%s</a>', add_query_arg( array( 'action' => 'duplicate', '_wpnonce' => $duplicate_nonce ), $base_link ), __( 'Duplicate', 'ultimate-newsletter' ) );
		$actions['delete'] = sprintf( '<a href="%s">%s</a>', add_query_arg( array( 'action' => 'delete', '_wpnonce' => $delete_nonce ), $base_link ), __( 'Delete', 'ultimate-newsletter' ) );	
		
		// Return the title contents
    	return sprintf( '%1$s %2$s', $item->post_title, $this->row_actions( $actions ) );
		
	}
	
	/**
	 * A custom column method and is responsible for what is rendered
	 * in any column with a name/slug of 'status'.
	 *
	 * @since    1.0.0
	 */
	public function column_status( $item ) {
	
		$status = get_post_meta( $item->ID, 'status', true );
		
		switch( $status ) {			
			case 'scheduled' :				
				$newsletter_ids = get_option( 'un_cron_queue', array() );
				$active_newsletter_id = 0;
				foreach( $newsletter_ids as $newsletter_id ) {
					$status = get_post_meta( $newsletter_id, 'status', true );
					if( 'scheduled' == $status ) {
						$active_newsletter_id = $newsletter_id;
						break;
					}
				}
				
				if( $item->ID == $active_newsletter_id ) {
		
					// get total subscribers in this newsletter
					$email_groups = wp_get_object_terms( $item->ID, 'un_email_groups', array( 'fields' => 'ids' ) );
					
					$args  = array(
						'post_type'   	 => 'un_subscribers',
						'posts_per_page' => -1,
						'post_status' 	 => 'publish',
						'fields' 		 => 'ids',
						'tax_query'   	 => array(
							array(
								'taxonomy' => 'un_email_groups',
								'field'    => 'term_id',
								'terms'    => $email_groups,
								'operator' => 'IN',
							),
						),
						'meta_query' => array(
							array(
								'key' 	  => 'status',
								'value'   => 'subscribed',
								'compare' => '='
							),
						)
					);
					
					$subscribers = get_posts( $args );
					$total_subscribers = count( $subscribers );
	
					if( $total_subscribers > 0 ) {
					
						// get total subscribers received this newsletter
						$args = array(
							'post_type'   	 => 'un_subscribers',
							'posts_per_page' => -1,
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
									'key' 	  => 'newsletters_received',
									'value'   => '"'.$item->ID.'"',
									'compare' => 'LIKE'
								),
							)
						);
						$subscribers  = get_posts( $args );
						$active_subscribers = count( $subscribers );
					
						// ...
						if( $active_subscribers == $total_subscribers ) {
					
							un_remove_from_cron_queue( $item->ID );
							update_post_meta( $item->ID, 'status', 'sent' );
						
							printf( '<script type="text/javascript">window.location.href="%s";</script>', admin_url( 'admin.php?page=ultimate_newsletters' ) );
							exit();
						
						} else {
					
							$meta = array();
					
							$meta[] = __( 'Sending', 'ultimate_newsletter' ).'...';
					
							$percent = intval( $active_subscribers / $total_subscribers * 100 )."%";
			
							$meta[] = sprintf( '<div id="un-progress-bg"><div id="un-status-display" style="width:%s"></div></div>', $percent );
					
							$time = wp_next_scheduled('un_cron_send_newsletters');
							$time = get_date_from_gmt( date( 'Y-m-d H:i:s', $time ) );
							$time = date_i18n( get_option("date_format")." @".get_option("time_format"), strtotime( $time ) );
							$meta[] = sprintf( __( '%s sent %d out of %d', 'ultimate-newsletter' ), $time, $active_subscribers, $total_subscribers );

							$url = "?page=ultimate_newsletters&action=paused&id=".$item->ID;
							$meta[] = sprintf( '<a href="%s">%s</a>', $url, __( 'Pause Sending','ultimate-newsletter' ) );
						
							$url = "?page=ultimate_newsletters&action=batch&id=".$item->ID;
							$meta[] = sprintf( '<a href="%s">%s</a>', $url, __( 'Send a batch now','ultimate-newsletter' ) );	
					
							echo implode( '<br />', $meta );
						
						}
						
					} else { // If no subscribers found, simply print a message
					
						un_remove_from_cron_queue( $item->ID );
						update_post_meta( $item->ID, 'status', 'draft' );
					
						_e( 'No subscribers found.', 'ultimate_newsletter' );
					
					} 
					
				} else {
					
					_e( 'Waiting for other campaign to finish sending', 'ultimate_newsletter' );
				
				}			
				break;
			case 'paused' :
				_e( 'Sending Paused', 'ultimate_newsletter' );
				
				$url = "?page=ultimate_newsletters&action=scheduled&id=".$item->ID;
				printf( '<a href="%s">%s</a>', $url, __( 'Resume Sending','ultimate-newsletter' ) );
				break;
			case 'sent' :
				_e( 'Sent', 'ultimate_newsletter' );
				break;
			default :
				_e( 'Not Sent', 'ultimate_newsletter' );
				break;
		}

	}	
	
	/**
	 * A custom column method and is responsible for what is rendered
	 * in any column with a name/slug of 'statistics'.
	 *
	 * @since    1.0.0
	 */
	public function column_statistics( $item ) {
	
		$sent = get_post_meta( $item->ID, 'sent', true );
		if( ! empty( $sent ) ) {
		 	$sent = count( $sent );
		} else {
			$sent = 0;
		}

		$opened = get_post_meta( $item->ID, 'opened', true );
		if( ! empty( $sent ) && ! empty( $opened ) ) {
		 	$opened  = count( $opened ) / $sent * 100;
		} else {
			$opened = 0;
		}
		
		$clicked = get_post_meta( $item->ID, 'clicked', true );
		if( ! empty( $sent ) && ! empty( $clicked ) ) {
		 	$clicked  = count( $clicked ) / $sent * 100;
		} else {
			$clicked = 0;
		}

		printf( __( "Sent: %d, Opened: %d%%, Clicked: %d%%", 'ultimate-newsletter' ), $sent, $opened, $clicked );
	
	}
	
	/**
	  * Dictates the table's columns and titles.
	 *
	 * @since    1.0.0
	 */
 	 public function get_columns(){
	 
        $columns = array(
			'cb'            => '<input type="checkbox" />',
       		'title' 	    => __( 'Title', 'ultimate-newsletter' ),
			'email_groups'  => __( 'Email Groups', 'ultimate-newsletter' ),
			'status'	    => __( 'Status', 'ultimate-newsletter' ),
			'statistics'    => __( 'Sent, Opened, Clicked', 'ultimate-newsletter' ),
			'modified_date'	=> __( 'Modified On', 'ultimate-newsletter' ),
			'post_date'  	=> __( 'Sent On', 'ultimate-newsletter' )
        );
		
        return $columns;
				
    }
	
	/**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns(){
	
		$sortable_columns = array(
            'title'     => array( 'title', false ), 
			'post_date'	=> array( 'post_date', false ) 
           
        );
		
        return $sortable_columns;
		
    }
	
	/**
	 * Define bulk actions
	 *
	 * @since    1.0.0
	 * @return   Array
	 */
	public function get_bulk_actions() {
	
  		$actions = array(
    		'bulk-delete' => __( 'Delete', 'ultimate-newsletter' )
  		);
		
  		return $actions;
			
	}
	
	/**
	 * We handle this action in Ultimate_Newsletter_Admin. So,
	 * it's better to override parent class used for the same.
	 *
	 * @since    1.0.0
	 */
	public function process_bulk_action() {
 													
    }
	
	/**
	  * Display a custom message when no newsletter found.
	 *
	 * @since    1.0.0
	 */
  	public function no_items() {
	  
		_e( 'No Newsletters found.', 'ultimate-newsletter' );
		
	}
	
	/**
     * Prepare the items for the table to process.
     *
     * @return Void
     */
    public function prepare_items() {	

		// Define our column headers
  		$columns = $this->get_columns();
  		$hidden = array();
  		$sortable = $this->get_sortable_columns();
		
  		$this->_column_headers = array( $columns, $hidden, $sortable );
		
		// Decide how many records per page to show
		$per_page = $this->get_items_per_page( 'items_per_page', 25 );
			
		// Get newsletters list to be displayed
		$newsletters = array();
		$total_newsletters = 0;
		
		$args = array(
			'post_type'		 => 'ultimate_newsletters',
			'post_status' 	 => 'any',
			'posts_per_page' => -1,
			'orderby'		 => isset( $_POST['orderby'] ) ? sanitize_text_field( $_POST['orderby'] ) : 'date',
			'order'			 => isset( $_POST['order'] ) ? sanitize_text_field( $_POST['order'] ) : 'DESC',
			's'				 => isset( $_POST['s'] ) ? sanitize_text_field( $_POST['s'] ) : '',	
			'm'				 => isset( $_POST['m'] ) ? sanitize_key( $_POST['m'] ) : '',
					
		);
			
		if( isset( $_GET['status'] ) ) {
			$args['meta_query'] = array(
				array(
					'key' 	=> 'status',
					'value'	=>  sanitize_text_field( $_GET['status'] ),
					
				),
			);
		}
					
		if( ! empty( $_POST['un_email_groups'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'un_email_groups',
					'field' => 'term_id',
					'terms' => (int) $_POST['un_email_groups'],
					
				 )
			 );
		}
		
		$un_query = new WP_Query( $args );

		if( $un_query->have_posts() ) {
		
			global $post;
			
			while( $un_query->have_posts() ) {
				$un_query->the_post();
				$newsletters[] = $post;
			}
			
			wp_reset_postdata();
			
			$total_newsletters = $un_query->found_posts;
		
		}
		
		$this->items = $newsletters;
		
		// Register our pagination options & calculations.
  		$this->set_pagination_args( array(
    		'total_items' => $total_newsletters,
    		'per_page'    => $per_page,
			'total_pages' => ceil( $total_newsletters / $per_page )
  		) );
		
    }	
	
}