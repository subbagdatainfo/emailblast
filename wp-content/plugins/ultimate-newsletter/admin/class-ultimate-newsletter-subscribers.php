<?php
/**
 * Subscribers.
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
 * Ultimate_Newsletter_Subscribers Class
 *
 * @since    1.0.0
 */
class Ultimate_Newsletter_Subscribers {
	
	/**
	 * Add the subscribers submenu.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu() {

		$hook = add_submenu_page(
       		'ultimate_newsletters',
        	__( 'Subscribers', 'ultimate-newsletter' ),
        	__( 'Subscribers', 'ultimate-newsletter' ),
        	'manage_options',
        	'un_subscribers',
        	array( $this, 'display_subscribers' )
    	);
		
		add_action( "load-$hook", array( $this, 'screen_options' ) );

	}
	
	/**
	 * Add screen options.
	 *
	 * @since    1.0.0
	 */
	public function screen_options() {
	
		global $un_subscribers_list_table;
		
		$option = 'per_page';
		
  		$args = array(
        	'label'   => __( 'Subscribers', 'ultimate-newsletter' ),
        	'default' => 20,
         	'option'  => 'items_per_page'
         );
		 
  		add_screen_option( $option, $args );
		
		$un_subscribers_list_table = new Ultimate_Newsletter_Subscribers_List_Table();
		
	}

	/**
	 * Display subscribers page.
	 *
	 * @since    1.0.0
	 */
	public function display_subscribers() {
	
		$action = $this->get_current_action();
		
		switch( $action ) {
			case 'add'  :
			case 'edit' :
				$this->add_edit_subscriber();
				break;
			case 'import' :
				$email_groups = get_terms( 'un_email_groups', array( 'hide_empty' => 0 ) );
				
				include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/subscribers/ultimate-newsletter-import-form.php';
				break;
			case 'import-list-data' :
				$email_groups = get_terms( 'un_email_groups', array( 'hide_empty' => 0 ) );
				
				if( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['un_subscriber_nonce'] ) && wp_verify_nonce( $_POST['un_subscriber_nonce'], 'un_import_subscribers' ) ) {
					$csv = isset( $_POST['csv'] ) ? $_POST['csv'] : '';
					$selected_email_groups = isset( $_POST['email_groups'] ) ? $_POST['email_groups'] : array();
					$status = isset( $_POST['status'] ) ? $_POST['status'] : 'subscribed';

					include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/subscribers/ultimate-newsletter-import-list.php';
				} else {
					wp_redirect( admin_url( 'admin.php?page=un_subscribers&action=import' ) );
					exit();
				}
				break;
			case 'export' :
				$email_groups = get_terms( 'un_email_groups', array( 'hide_empty' => 0 ) );
				
				include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/subscribers/ultimate-newsletter-export-form.php';
				break;
			default:
				global $un_subscribers_list_table;				
				$un_subscribers_list_table->prepare_items();
			
				include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/subscribers/ultimate-newsletter-subscribers-list.php';
		}		
		
	} 
	
	/**
	 * Add/Edit/Delete subscribers.
	 *
	 * @since    1.0.0
	 */
	public function manage_form_actions() {
	
		if( isset( $_REQUEST['page'] ) && 'un_subscribers' == $_REQUEST['page'] ) {
		
			$action = $this->get_current_action();
		
			switch( $action ) {
				case 'save' :
					$this->save_subscriber();
					break;
				case 'import-save-data' :
					$this->import_subscribers();
					break;
				case 'export-data' :
					$this->export_subscribers();
					break;
				case 'delete' :
				case 'bulk-delete' :
					$this->delete_subscriber_s();
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
	 * Add/Edit susbcriber.
	 *
	 * @since    1.0.0
	 */
	public function add_edit_subscriber() {
	
		$subscriber_id = isset( $_REQUEST['id'] ) ? (int) $_REQUEST['id'] : 0;
		
		$email_groups_list = get_terms( 'un_email_groups', array( 'hide_empty' => 0 ) );
		
		$title 		  = '';
		$email 		  = '';
		$email_groups = array();
		$status 	  = 'subscribed';
		$user_id      = 0;
		$token        = '';
		
		if( $subscriber_id > 0 ) {
			$title        = get_the_title( $subscriber_id );		
			$email 		  = get_post_meta( $subscriber_id, 'email', true );
			$email_groups = wp_get_object_terms( $subscriber_id, 'un_email_groups', array( 'fields' => 'ids' ) );
			$status 	  = get_post_meta( $subscriber_id, 'status', true );
			$user_id      = get_post_meta( $subscriber_id, 'user_id', true );
			$token        = get_post_meta( $subscriber_id, 'token', true );
		}
		
		include ULTIMATE_NEWSLETTER_PLUGIN_DIR.'admin/partials/subscribers/ultimate-newsletter-subscriber-form.php';
		
	}
	
	/**
	 * Save subscriber.
	 *
	 * @since    1.0.0
	 */
	public function save_subscriber() {
	
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['un_subscriber_nonce'] ) && wp_verify_nonce( $_POST['un_subscriber_nonce'], 'un_save_subscriber' ) ) {
		
			$subscriber_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;
			$user_id       = isset( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0;	
			$name          = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';	
			$email         = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
			$token         = ! empty( $_POST['token'] ) ? sanitize_text_field( $_POST['token'] ) : un_generate_subscriber_token();
			$status        = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : 'subscribed';
			$email_groups  = isset( $_POST['email_groups'] ) ? $_POST['email_groups'] : array();
			$is_duplicate  = 0;
			
			if( 0 == $subscriber_id ) {
				$args = array(
    				'post_type' 	 => 'un_subscribers',
    				'post_status' 	 => 'any',
    				'posts_per_page' => 1,
					'meta_key' 		 => 'email',
    				'meta_value' 	 => $email,
					'fields'         =>'ids',
				);
		
				$posts = get_posts( $args );
							
				if( count( $posts ) ) {
					$subscriber_id = (int) $posts[0];
					$is_duplicate = 1;
				}
			}
		
			if( ! $is_duplicate ) {

				$args = array(
					'ID'          => $subscriber_id,
					'post_type'	  => 'un_subscribers',
					'post_status' => 'publish',
					'post_title'  => $name
				);	
			
				$subscriber_id = wp_insert_post( $args );	

				if( 0 == $user_id ) {
					$user = get_user_by( 'email', $email );
					if( ! empty( $user ) ) $user_id = $user->ID;
				}
				
				update_post_meta( $subscriber_id, 'user_id', $user_id );
				
				update_post_meta( $subscriber_id, 'email', $email );
				update_post_meta( $subscriber_id, 'token', $token );
				update_post_meta( $subscriber_id, 'status', $status );

				if( $user_id > 0 ) {
					$email_groups[] = (int) get_option( 'un_subscriber_wp_users' );
					$email_groups   = array_unique( $email_groups );
				}				
					
				wp_set_object_terms( $subscriber_id, '', 'un_email_groups' );		
				if( ! empty( $email_groups ) ) {
					foreach( $email_groups as $email_group ) {
						wp_set_object_terms( $subscriber_id, (int) $email_group, 'un_email_groups', true );
					}
				}
				
				if( $user_id > 0 ) update_user_meta( $user_id, 'un_subscriber_id', $subscriber_id );
		
			} else {
		
				$message  = __( 'Trying to duplicate. Already there is an subscriber account associated to this email ID.', 'ultimate-newsletter' );
				$message .= ' '.sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=un_subscribers&action=edit&id='.$subscriber_id ), __( 'Edit subscriber', 'ultimate-newsletter' ) );
				set_transient( 'un_subscribers_admin_notice', $message, 30 );
		
			}
		
		}
		
		wp_redirect( admin_url( 'admin.php?page=un_subscribers' ) );
		exit();
		
	}
	
	/**
	 * Import subscribers.
	 *
	 * @since    1.1.0
	 */
	public function import_subscribers() {
		
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['un_subscriber_nonce'] ) && wp_verify_nonce( $_POST['un_subscriber_nonce'], 'un_import_subscribers' ) ) {
		
			$status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : 'subscribed';
			$email_groups = isset( $_POST['email_groups'] ) ? $_POST['email_groups'] : array();
			
			if( isset( $_POST['type'] ) && 'editor' == sanitize_text_field( $_POST['type'] ) ) {
				
				// Import from TextArea
				if( $content = $_POST['editor'] ) {
	
					$lines = explode( "\n", trim( $content ) ); 
			
					foreach( $lines as $line ) {
				
						$meta = explode( ',', trim( $line ) );
				 
						if( $email = sanitize_email( $meta[0] ) ) {
							$name = isset( $meta[1] ) ? sanitize_text_field( $meta[1] ) : '';
							if( ! $name ) {
								$name = explode( '@', $email );
								$name = $name[0];
							}
			
							$this->import_subscriber( $email, $name, $status, $email_groups );
						}
						
					}
					
				}
				
			} else {
			
				// Import from CSV
				$url = sanitize_url( $_POST['csv'] );
				$csv = file_get_contents( $url );
				$lines = explode( "\n", $csv );
				
				$columns = array( 'col_1', 'col_2' );
				
				foreach( $lines as $line ) { 
			
					$parts = str_getcsv( $line, ",", '"' );
				
					$meta = array();
					foreach( $columns as $key => $column ) {
						$column_name = $_POST[ $column ][0];					
						$meta[ $column_name ] = ! empty( $parts[ $key ] ) ? trim( $parts[ $key ] ) : '';
					}
			
					if( $email = sanitize_email( $meta['email'] ) ) {
						$name = isset( $meta['name'] ) ? sanitize_text_field( $meta['name'] ) : '';
						if( ! $name ) {
							$name = explode( '@', $email );
							$name = $name[0];
						}
			
						$this->import_subscriber( $email, $name, $status, $email_groups );
					}
					
				}
				
			}
		
		}
		
	}
	
	/**
	 * Import Subscriber.
	 *
	 * @since    1.1.0
	 *
	 * @param    string    $email           Subscriber Email Address.
	 * @param    string    $name            Subscriber Name.
	 * @param    string    $status          Subscription Status.
	 * @param    array     $email_groups    Array. Email Groups.
	 */
	public function import_subscriber( $email, $name, $status, $email_groups ) {
				
		$args = array(
			'post_type' 	 => 'un_subscribers',
			'post_status' 	 => 'any',
			'posts_per_page' => 1,
			'meta_key' 		 => 'email',
			'meta_value' 	 => $email,
			'fields'         =>'ids',
		);

		$posts = get_posts( $args );
		
		if( count( $posts ) ) {

			$subscriber_id = (int) $posts[0];

			$_email_groups = wp_get_object_terms( $subscriber_id, 'un_email_groups', array( 'fields' => 'ids' ) );
			if( ! is_wp_error( $_email_groups ) && ! empty( $_email_groups ) ) {
				$_email_groups[] = $email_groups;
				$email_groups = array_unique( $_email_groups );
			
				wp_set_object_terms( $subscriber_id, '', 'un_email_groups' );	
				foreach( $email_groups as $email_group ) {
					wp_set_object_terms( $subscriber_id, (int) $email_group, 'un_email_groups', true );
				}
			} else {
				if( ! empty( $email_groups ) ) {
					foreach( $email_groups as $email_group ) {
						wp_set_object_terms( $subscriber_id, (int) $email_group, 'un_email_groups', true );
					}
				}
			}

		} else {

			// Add as a subscriber
			$args = array(
				'post_type'	  => 'un_subscribers',
				'post_status' => 'publish',
				'post_title'  => $name
			);
	
			$subscriber_id = wp_insert_post( $args );

			update_post_meta( $subscriber_id, 'email', $email );
			update_post_meta( $subscriber_id, 'token', un_generate_subscriber_token() );
			update_post_meta( $subscriber_id, 'status', $status );
			if( ! empty( $email_groups ) ) {
				foreach( $email_groups as $email_group ) {
					wp_set_object_terms( $subscriber_id, (int) $email_group, 'un_email_groups', true );
				}
			}

		}					
		
	}
	
	/**
	 * Export subscribers.
	 *
	 * @since    1.1.0
	 */
	public function export_subscribers() {
	
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['un_subscriber_nonce'] ) && wp_verify_nonce( $_POST['un_subscriber_nonce'], 'un_export_subscribers' ) ) {
		
			$email_groups = !( $_POST['email_groups'] ) ? $_POST['email_groups'] : array();
			
			$args = array(
				'post_type'      => 'un_subscribers',
				'posts_per_page' => -1
			);
			
			if( isset( $_POST['confirmed_only'] ) ) {
			
				$args['meta_query'] = array(
					array(
						'key' 	=> 'status',
						'value' => 'subscribed'
					)
				);
				
			}
			
			if( ! empty( $_POST['email_groups'] ) ) {
			
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'un_email_groups',
						'field'    => 'term_id',
						'terms'    => array_map( 'intval', $_POST['email_groups'] ),
					),
				);
				
			}
			
			$posts = get_posts( $args );
			
			// output headers so that the file is downloaded rather than displayed
			$browser = "";
			if( preg_match('/Opera(/| )([0-9].[0-9]{1,2})/', $_SERVER['HTTP_USER_AGENT']) ) {
        		$browser = "Opera";
        	} else if ( preg_match('/MSIE ([0-9].[0-9]{1,2})/', $_SERVER['HTTP_USER_AGENT']) ) {
            	$browser = "IE";
        	}

        	$mime = ($browser == 'IE' || $browser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';
        	$file_name = 'subscribers-list';
			
			ob_end_clean();
        	header('Content-Encoding: UTF-8');
        	header('Content-Type: ' . $mime . '; charset=UTF-8');
        	header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        	if( $browser == 'IE' ) {
        		header('Content-Disposition: attachment; filename="' . $file_name . '.csv"');
            	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            	header('Pragma: public');
        	} else {
            	header('Content-Disposition: attachment; filename="' . $file_name . '.csv"');
            	header('Pragma: no-cache');
        	}

			// create a file pointer connected to the output stream
			$csv = fopen( 'php://output', 'w' );
		
			// add UTF-8 byte-order mark '\xef\xbb\xbf' to the document's header
			fwrite( $csv, "\xEF\xBB\xBF" );	
 		
			// output the column headings
			$header = array();
			
			if( isset( $_POST['include_email'] ) ) {
				$header[] = __( 'Email', 'ultimate-newsletter' );
			}
			
			if( isset( $_POST['include_name'] ) ){
				$header[] = __( 'Name', 'ultimate-newsletter' );
			}
			
			if( isset( $_POST['include_date'] ) ) {
				$header[] = __( 'Date', 'ultimate-newsletter' );
			}
			
			if( isset( $_POST['include_status'] ) ) {
				$header[] = __( 'Status', 'ultimate-newsletter' );
			}
			
			fputcsv( $csv, $header );
			
			// output the subscribers
			foreach( $posts as $post ) {
			
				$row = array();
				
				if( isset( $_POST['include_email'] ) ) {
					$row[] = get_post_meta( $post->ID, 'email', true );
				}
			
				if( isset( $_POST['include_name'] ) ){
					$row[] = $post->post_title;
				}
			
				if( isset( $_POST['include_date'] ) ) {
					$row[] = date_i18n( get_option('date_format'), strtotime( $post->post_date ) );
				}
			
				if( isset( $_POST['include_status'] ) ) {
					$row[] = get_post_meta( $post->ID, 'status', true );
				}
				
				fputcsv( $csv, $row );
			
			}
			
			// flush file output
			fclose( $csv );
		
			exit();
		
		}
		
	}
	
	/**
	 * Delete subscribers.
	 *
	 * @since    1.0.0
	 */
	public function delete_subscriber_s() {
	
		// Delete single subscribers
		if( isset( $_GET['id'] ) ) {
		
			$nonce = esc_attr( $_GET['_wpnonce'] );
			 
			if( wp_verify_nonce( $nonce, 'un_delete_subscriber' ) ) {
				$subscriber_id = (int) $_GET['id'];
			
				$user_id = (int) get_post_meta( $subscriber_id, 'user_id', true );
				if( $user_id > 0 ) delete_user_meta( $user_id, 'un_subscriber_id' );
			
				wp_delete_post( $subscriber_id , true );
			}
			
		}
		
		// Delete subscribers in bulk action
		if( isset( $_POST['ids'] ) ) {
		
			if( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            	$nonce  = esc_attr( $_POST['_wpnonce'] );

            	if( wp_verify_nonce( $nonce, 'bulk-subscribers' ) ) {
	
					foreach( $_POST['ids'] as $subscriber_id ) {
						$subscriber_id = (int) $subscriber_id;
			
						$user_id = (int) get_post_meta( $subscriber_id, 'user_id', true );
						if( $user_id > 0 ) delete_user_meta( $user_id, 'un_subscriber_id' );
				
						wp_delete_post( $subscriber_id , true );
					}
			
				}
				
			}
			
		}
		
		wp_redirect( admin_url( 'admin.php?page=un_subscribers' ) );
		exit();
		
	}

	/**
	 * Add the new registered user as a subscriber.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $user_id    User ID.
	 */
	public function user_register( $user_id ) {
		
        $user_info = get_userdata( $user_id );
			
		$name  	     = $user_info->display_name;
		$email	 	 = $user_info->user_email;
		$status	     = 'subscribed';
		$email_group = (int) get_option( 'un_subscriber_wp_users' );
				
		$args = array(
    		'post_type' 	 => 'un_subscribers',
    		'post_status' 	 => 'any',
    		'posts_per_page' => 1,
			'meta_key' 		 => 'email',
    		'meta_value' 	 => $email,
			'fields'         =>'ids',
		);
		
		$posts = get_posts( $args );
			
		if( count( $posts ) ) {
			
			$subscriber_id = (int) $posts[0];
				
			$args = array(
				'ID'         => $subscriber_id,
				'post_title' => $name
			);
			wp_update_post( $args );
			
			update_post_meta( $subscriber_id, 'user_id', $user_id );	
				
			$status = get_post_meta( $subscriber_id, 'status', true );	
			if( 'unconfirmed' == $status ) update_post_meta( $subscriber_id, 'status', 'subscribed' );
			
			$email_groups = wp_get_object_terms( $subscriber_id, 'un_email_groups', array( 'fields' => 'ids' ) );
			if( ! is_wp_error( $email_groups ) && ! empty( $email_groups ) ) {
				$email_groups[] = $email_group;
				$email_groups = array_unique( $email_groups );
						
				wp_set_object_terms( $subscriber_id, '', 'un_email_groups' );	
				foreach( $email_groups as $email_group ) {
					wp_set_object_terms( $subscriber_id, (int) $email_group, 'un_email_groups', true );
				}
			} else {
				wp_set_object_terms( $subscriber_id, $email_group, 'un_email_groups' );
			}
			
			update_user_meta( $user_id, 'un_subscriber_id', $subscriber_id );

		} else {
		
			// Add as a subscriber
			$args = array(
				'post_type'	  => 'un_subscribers',
				'post_status' => 'publish',
				'post_title'  => $name
			);
				
			$subscriber_id = wp_insert_post( $args );
				
			update_post_meta( $subscriber_id, 'user_id', $user_id );
			update_post_meta( $subscriber_id, 'email', sanitize_email( $email ) );
			update_post_meta( $subscriber_id, 'token', un_generate_subscriber_token() );
			update_post_meta( $subscriber_id, 'status', $status );
			wp_set_object_terms( $subscriber_id, $email_group, 'un_email_groups' );
			
			update_user_meta( $user_id, 'un_subscriber_id', $subscriber_id );
			
		}
		
	}
	
	/**
	 * Update subscriber data when their user profile updated.
	 *
	 * @since    1.0.0
	 *
	 * @param    int       $user_id          User ID.
	 * @param    object    $old_user_data    Object containing user's data prior to update.
	 */
	public function profile_update( $user_id, $old_user_data ) {

		$subscriber_id = (int) get_user_meta( $user_id, 'un_subscriber_id', true );
				
		if( is_string( get_post_status( $subscriber_id ) ) ) {
		
			$user_info = get_userdata( $user_id );			
			
			$name  = $user_info->display_name;
			$email = $user_info->user_email;
				
			$args = array(
				'ID'         => $subscriber_id,
				'post_title' => $name
			);
			wp_update_post( $args );
			
			update_post_meta( $subscriber_id, 'email', $email );	

		} 
		
	}
	
	/**
	 * Delete subscriber when his user account deleted from the website.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $user_id    User ID.
	 */
	public function delete_user( $user_id ) {

		$subscriber_id = (int) get_user_meta( $user_id, 'un_subscriber_id', true );
				
		if( is_string( get_post_status( $subscriber_id ) ) ) {
			wp_delete_post( $subscriber_id, true );
		} 
		
	}
	
	/**
	 * Admin notice.
	 *
	 * @since    1.0.0
	 */
	public function admin_notice() {
	
		if( $value = get_transient( 'un_subscribers_admin_notice' ) ) {
		
			printf( '<div class="notice notice-error is-dismissible"><p>%s</p></div>', $value );			
			delete_transient( 'un_subscribers_admin_notice' );
			
		}
	
	}
	
}

/**
 * Load the base class.
 *
 * @since    1.0.0
 */
if( ! class_exists('WP_List_Table') ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Ultimate_Newsletter_Subscribers_List_Table Class
 *
 * @since    1.0.0
 */
class Ultimate_Newsletter_Subscribers_List_Table extends WP_List_Table {
	
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
            	'singular' => __( 'Subscriber', 'ultimate-newsletter' ),     
            	'plural'   => __( 'Subscribers', 'ultimate-newsletter' ),   
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
		$base_link = admin_url( 'admin.php?page=un_subscribers' );
		
		$counts = array( 'all' => 0, 'subscribed' => 0, 'unconfirmed' => 0, 'unsubscribed' => 0 );
		$status = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all';

		 $args = array( 
			'post_type'      => 'un_subscribers',
			'post_status' 	 => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids'
		);
		$subscribers = get_posts( $args );
		
		foreach( $subscribers as $subscriber ) {
			++$counts['all'];
			
			$sub_status = get_post_meta( $subscriber, 'status', true );
			++$counts[ $sub_status ];
		}
		
		if( $counts['all'] > 0 ) {
			$links[] = sprintf( '<li><a href="%s"%s>%s <span class="count">(%d)</span></a></li>', $base_link, ( 'all' == $status ? ' class="current"' : '' ), __( 'All', 'ultimate-newsletter' ), $counts['all'] );	
			
			if( $counts['subscribed'] > 0 ) {
				$links[] = sprintf( '<li><a href="%s"%s>%s <span class="count">(%d)</span></a></li>', add_query_arg( 'status', 'subscribed', $base_link ), ( 'subscribed' == $status ? ' class="current"' : '' ), __( 'Subscribed', 'ultimate-newsletter' ), $counts['subscribed'] );
			}
			
			if( $counts['unconfirmed'] > 0 ) {
				$links[] = sprintf( '<li><a href="%s"%s>%s <span class="count">(%d)</span></a></li>', add_query_arg( 'status', 'unconfirmed', $base_link ), ( 'confirmed' == $status ? ' class="current"' : '' ), __( 'Unconfirmed', 'ultimate-newsletter' ), $counts['unconfirmed'] );
			}
			
			if( $counts['unsubscribed'] > 0 ) {
				$links[] = sprintf( '<li><a href="%s"%s>%s <span class="count">(%d)</span></a></li>', add_query_arg( 'status', 'unsubscribed', $base_link ), ( 'unsubscribed' == $status ? ' class="current"' : '' ), __( 'Unsubscribed', 'ultimate-newsletter' ), $counts['unsubscribed'] );
			}
		}
		
		if( count( $links ) ) {
			echo '<ul class="subsubsub">'.implode( ' | ', $links ).'</ul>';
		}
	
	}

 	/**
	 * Add Email groups filter.
	 *
	 * @since    1.0.0
	 */
 	public function extra_tablenav( $which ) {
	
		if( 'top' === $which ) {		
		
			echo '<div class="alignleft actions">';
			
			$terms = get_terms( 'un_email_groups', array( 'hide_empty' => 0 ) );
			$active_term_id = isset( $_POST['un_email_groups'] ) ? (int) $_POST['un_email_groups'] : 0;  

			echo '<select name="un_email_groups">'; 
			echo '<option value="">'.__( 'All Email Groups', 'ultimate-newsletter' ).'</option>';
			foreach( $terms as $term ) {
			
				 $args = array( 
				 	'post_type'      => 'un_subscribers',
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
				
				if( isset( $_GET['status'] ) && in_array( $_GET['status'], array( 'subscribed', 'unconfirmed', 'unsubscribed' ) ) ) {
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
	 * Called when the parent class can't find a method specifically
     * build for a given column.
	 *
	 * @since    1.0.0
	 */
	public function column_default( $item, $column_name ){
	
        switch( $column_name ) {
			case 'email':
				return get_post_meta( $item->ID, 'email', true );
            case 'email_groups':
				return implode( '<br>', wp_get_object_terms( $item->ID, 'un_email_groups', array( 'fields' => 'names' ) ) );
            case 'status':
				return get_post_meta( $item->ID, 'status', true );
			case 'date':
				return date_i18n( get_option('date_format'), strtotime( $item->post_date ) );
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
	 
	 	// Create a nonce
  		$delete_nonce = wp_create_nonce( 'un_delete_subscriber' );
  
	 	// Build row actions
		 $actions = array(
            'edit'   => sprintf('<a href="?page=%s&action=edit&id=%d">%s</a>', $_REQUEST['page'], $item->ID, __( 'Edit', 'ultimate-newsletter' ) ),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%d&_wpnonce=%s">%s</a>', $_REQUEST['page'], $item->ID, $delete_nonce, __( 'Delete', 'ultimate-newsletter' ) ),
        );
		
		// Return the title contents
    	return sprintf( '%1$s %2$s', $item->post_title, $this->row_actions( $actions ) );
		
	}
	
	/**
	 * Dictates the table's columns and titles.
	 *
	 * @since    1.0.0
	 * @return   Array
	 */
 	 public function get_columns(){
	 
        $columns = array(
			'cb'        	=> '<input type="checkbox" />',
			'title' 	    => __( 'Name', 'ultimate-newsletter' ),
			'email'       	=> __( 'Email', 'ultimate-newsletter' ),       		
            'email_groups'  => __( 'Email Groups', 'ultimate-newsletter' ),
            'status'        => __( 'Status', 'ultimate-newsletter' ),
			'date'          => __( 'Subscribed On', 'ultimate-newsletter' )
        );
 
        return $columns;
		
    }
	
    /**
     * Define the sortable columns.
     *
	 * @since    1.0.0
     * @return   Array
     */
    public function get_sortable_columns(){
	
		$sortable_columns = array(
			'title'  => array( 'title', false ),
            'date'   => array( 'date', false ), 
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
	 * We handle this action in Ultimate_Newsletter_Subscribers. So,
	 * it's better to override parent class used for the same.
	 *
	 * @since    1.0.0
	 */
	public function process_bulk_action() {
		 
    }

	/**
	 * Display a custom message when no subscriber found.
	 *
	 * @since    1.0.0
	 */
  	public function no_items() {
	  
		_e( 'No subscribers found.', 'ultimate-newsletter' );
		
	}

	/**
     * Prepare your data for display. This method willusually be used to
	 * query the database, sort and filter the data, and generally get it
	 * ready to be displayed.
     *
     * @since    1.0.0
     */
    public function prepare_items() {	

		// Define our column headers
  		$columns = $this->get_columns();
  		$hidden = array();
  		$sortable = $this->get_sortable_columns();
		
  		$this->_column_headers = array( $columns, $hidden, $sortable );
		
		// Decide how many records per page to show
		$per_page = $this->get_items_per_page( 'items_per_page', 25 );
		
		// Get subscribers list to be displayed
		$subscribers = array();
		$total_subscribers = 0;
		
		$args = array(
			'post_type'		 => 'un_subscribers',
			'post_status' 	 => 'any',
			'posts_per_page' => $per_page,
			'orderby'		 => isset( $_POST['orderby'] ) ? sanitize_text_field( $_POST['orderby'] ) : 'date',
			'order'			 => isset( $_POST['order'] ) ? sanitize_text_field( $_POST['order'] ) : 'DESC',
			's'				 => isset( $_POST['s'] ) ? sanitize_text_field( $_POST['s'] ) : '',	
		);  
		
   		if( isset( $_GET['status'] ) && in_array( $_GET['status'], array( 'subscribed', 'unconfirmed', 'unsubscribed' ) ) ) {
			$args['meta_query'] = array(
				array(
					'key'   => 'status',
					'value' =>  sanitize_text_field( $_GET['status'] ),
				),
			);
		}
		
		if( ! empty( $_POST['un_email_groups'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'un_email_groups',
					'field'    => 'term_id',
					'terms'    => (int) $_POST['un_email_groups']
				 )
			 );
		}
		
		$un_query = new WP_Query( $args );

		if( $un_query->have_posts() ) {
		
			global $post;
			
			while( $un_query->have_posts() ) {
				$un_query->the_post();
				$subscribers[] = $post;
			}
			
			wp_reset_postdata();
			
			$total_subscribers = $un_query->found_posts;
		
		}
		
		$this->items = $subscribers;
		
		// Register our pagination options & calculations
  		$this->set_pagination_args( array(
    		'total_items' => $total_subscribers,
    		'per_page'    => $per_page,
			'total_pages' => ceil( $total_subscribers / $per_page )
  		) );  		
		
    }
	
}

/**
 * Ultimate_Newsletter_Import_List_Table Class
 *
 * @since    1.1.0
 */
class Ultimate_Newsletter_Import_List_Table extends WP_List_Table {

	/**
	 * Builder Header List Box.
	 *
	 * @since    1.1.0
	 */
	 public function list_box_header( $column, $field_name ) {
	 
	 	$options = array(
			'ignore' => __( 'Ignore column', 'ultimate-newsletter' ),
			'email'  => __( 'Email', 'ultimate-newsletter' ),
			'name'   => __( 'Name', 'ultimate-newsletter' )
		);
		
		$html = sprintf( '<select name="%s[]" id="un-%s" class="un-import-column">', $column, $field_name );
		foreach( $options as $value => $label ) {
			$selected = ( $value == $field_name ) ? ' selected="selected"' : '';
			$html .= sprintf( '<option value="%s"%s>%s</option>', $value, $selected, $label );			
		}
		$html .= '</select>';
		
		return $html;
		
	 }
	
	/**
	 * Dictates the table's columns and titles.
	 *
	 * @since    1.1.0
	 * @return   Array
	 */
	public function get_columns() {
	
		$columns = array(
			'email' => $this->list_box_header( 'col_1', 'email' ),
			'name'  => $this->list_box_header( 'col_2', 'name' )
	  	);
	  
	  	return $columns;
	  
	}

	/**
	 * Called when the parent class can't find a method specifically
     * build for a given column.
	 *
	 * @since    1.1.0
	 */
	public function column_default( $item, $column_name ) {
		
		switch( $column_name ) { 
			case 'email':
				return ! empty( $item[0] ) ? $item[0] : '';
			case 'name':
				return ! empty( $item[1] ) ? $item[1] : '';
		}
		  
		return '';
		  
	}
	
	/**
     * Prepare your data for display. This method willusually be used to
	 * query the database, sort and filter the data, and generally get it
	 * ready to be displayed.
     *
     * @since    1.1.0
     */
	public function prepare_items() {
	
		if( isset( $_POST['csv'] ) ) {
		
			$columns = $this->get_columns();
	  		$hidden = array();
	  		$this->_column_headers = array( $columns, $hidden );
		
			$data = array();
			$url = sanitize_url( $_POST['csv'] );
			$csv = file_get_contents( $url );
			$lines = explode( "\n", $csv );
			$lines = array_filter( $lines );
			
			foreach( $lines as $line ) { 
				$parts = str_getcsv( $line, ",", '"' );
				array_push( $data, $parts );
			}
       	
        	$per_page = 5;
        	$current_page = $this->get_pagenum();
        	$total_items  = count( $data );
        	$this->set_pagination_args( array(
            	'total_items' => $total_items,
            	'per_page'    => $per_page
        	) );
		
        	$data = array_slice( $data, ( $current_page - 1 ) * $per_page, $per_page );
		
	  		$this->items = $data;
		
		}
	  
	}
	
}