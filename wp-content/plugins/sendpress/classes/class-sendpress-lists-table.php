<?php
// SendPress Required Class: SendPress_Lists_Table
// Prevent loading this file directly
if ( !defined('SENDPRESS_VERSION') ) {
    header('HTTP/1.0 403 Forbidden');
    die;
}
/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 * 
 * To display this example on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 * 
 * Our theme for this list table is going to be movies.
 */
class SendPress_Lists_Table extends WP_List_Table {
    
    /** ************************************************************************
     * Normally we would be querying data from a database and manipulating that
     * for use in your list table. For this example, we're going to simplify it
     * slightly and create a pre-built array. Think of this as the data that might
     * be returned by $wpdb->query().
     * 
     * @var array 
     **************************************************************************/
   
    private $_sendpress = '';

    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page;
                
        $this->_sendpress = new SendPress();
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'list',     //singular name of the listed records
            'plural'    => 'lists',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
    
    
    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default($item, $column_name){
        switch($column_name){
            case 'rating':
            case 'director':
                return $item[$column_name];
            case 'count_subscribers':
                $role = get_post_meta($item->ID,'sync_role',true);
                $add = '';
                if($role != 'none' && $role != false){
                    $add = " - <a href='". SendPress_Admin::link('Subscribers_Sync') . "&listID=".$item->ID."'>". __('Sync','sendpress') . "</a>";
                }

                return  SendPress_data::get_count_subscribers($item->ID) . $add ;
            case 'count_unsubscribes':
                return  SendPress_data::get_count_subscribers($item->ID, 3);
            case 'count_bounced':
                return  SendPress_data::get_count_subscribers($item->ID, 4);
            case 'last_send_date':
                return date('Y-m-d');        
            case 'actions':
                $role = get_post_meta($item->ID,'sync_role',true);
                $add = '';
                if($role != 'none' && $role != false){
                    $add = " - <a href='". SendPress_Admin::link('Subscribers_Sync') . "&listID=".$item->ID."'>". __('Sync','sendpress') . "</a>";
                }


                $str = '<div class="inline-buttons">
                    <a class="btn btn-info" href="?page='.SPNL()->validate->page().'&view=subscribers&listID='.$item->ID.'"><span class="glyphicon glyphicon-edit"></span> '. __('View','sendpress') . '/'. __('Edit','sendpress') . '</a> ';
                $role = get_post_meta($item->ID,'sync_role',true);
                $add = '';
                if($role != 'none' && $role != false){
                    $str .= "<a class='btn btn-primary' href='". SendPress_Admin::link('Subscribers_Sync') . "&listID=".$item->ID."'><span class='glyphicon glyphicon-refresh'></span> ". __('Sync','sendpress') . "</a> ";
                } else {
                    if(apply_filters( 'sendpress_show_import_button', true, $this->_sendpress )){
                         $str .=  '<a class="list-import btn btn-primary" href="?page='.SPNL()->validate->page().'&view=csvimport&listID='. $item->ID .'"><span class="glyphicon glyphicon-upload"></span> '. __('Import','sendpress') . '</a> ';
                    }
                    
                    $str .= '<a class="btn btn-primary" href="?page='.SPNL()->validate->page().'&view=add&listID='. $item->ID .'"><span class="glyphicon glyphicon-user"></span> '. __('Add','sendpress') . '</a> ';
                    $str .='<a class="btn btn-primary" href="?page='.SPNL()->validate->page().'&action=export-list&listID='. $item->ID .'"><span class="glyphicon glyphicon-download"></span> '. __('Export','sendpress') . '</a> ';
                    
                    $str .=    '<a class="btn btn-primary" href="'. SendPress_Admin::link('Subscribers_Listform', array('listID' => $item->ID)) .'"><span class="glyphicon glyphicon-list"></span> '. __('Form','sendpress') . '</a> ';
                }

               
                 $str .=   '<a class="btn " href="?page='.SPNL()->validate->page().'&view=listedit&listID='. $item->ID .'"><i class="icon-cog"></i></a>
                    </div>';
                return $str;
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
    
        
    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named 
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     * 
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     * 
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_title($item){
        
        //Build row actions
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&view=%s&listID=%s">'. __('Edit','sendpress') . '</a>', 
                /*$1%s*/ 'sp-subscribers',
                /*$2%s*/ 'listedit', 
                /*$3%s*/ $item->ID
            )
        );

        if(get_post_meta($item->ID,'public',true))  {
            $p = 'public';
        } else {
            $p = 'private';
        }
        $t = '';
        if( get_post_meta($item->ID,'_test_list',true) == 1 ){ 
           $t = '  <span class="label label-info">'. __('Test List','sendpress') . '</span>';
        } 

         $role = get_post_meta($item->ID,'sync_role',true);
        // $add = '';
        global $wp_roles;
        $names = $wp_roles->get_names();
         if($role != 'none' && $role != false && isset($names[$role]) ){
            $role_info=get_role($role);
            $p = translate_user_role( $names[$role] );
         }

        $title = apply_filters('sendpress_list_table_title_actions',array(
            'title' =>$item->post_title . $t,
            'id' => ' <span style="color:silver">( id:'.$item->ID .' - ' .$p .' )</span>',
            'actions' => $this->row_actions($actions)
        ));
        
        return implode($title);

        //Return the title contents
        // return sprintf('%1$s <span style="color:silver">( id:%2$s )</span>%3$s',
        //      $item->post_title,
        //      $item->ID .' - ' .$p,
    
             
        // );
        
    }
    
    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item->ID                //The value of the checkbox should be the record's id
        );
    }
    
    
    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns(){
       
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'title' => __('Name','sendpress'),
            'count_subscribers' => _x('Active','Count subscribers','sendpress'),
            'last_send_date' => __('Last Send','sendpress'),
            //'count_bounced' => 'Bounced',
            'actions'=> __('Subscribers','sendpress')

            
        );
        return $columns;
    }
    
    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            'title' =>array('name',true),     //true means its already sorted
            /*
            'rating'    => array('rating',false),
            'director'  => array('director',false)
            */
        );
        return $sortable_columns;
    }
    
    
    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = apply_filters('sendpress_list_table_bulk_actions',array(
            'delete-lists-bulk'    => __('Delete','sendpress')
        ));


        return $actions;
    }
    
    
    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }
    
    
    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
     function prepare_items() {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();
        $sp_validate = SPNL()->validate;
        //$this->process_bulk_action();
          /*      
        select t1.* from `sp_sendpress_list_subscribers` as t1 , `sp_sendpress_subscribers` as t2
        where t1.subscriberID = t2.subscriberID and t1.listID = 2*/
         /* -- Pagination parameters -- */
        //Number of elements in your table?
       // $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        
        /* -- Register the Columns -- */
           $columns = $this->get_columns();
             $hidden = array();
             $sortable = $this->get_sortable_columns();
             $this->_column_headers = array($columns, $hidden, $sortable);
        /* -- Fetch the items -- */
            // $args = array(
            // 'post_type' => 'sendpress_list',
            // 'post_status' => array('publish','draft')
            // );

            //$args = apply_filters('sendpress_list_table_query',array('post_type' => 'sendpress_list','post_status' => array('publish','draft')));

            $query = SendPress_Data::get_lists();
            //$query = new WP_Query( $args );
           
            $totalitems = $query->found_posts;
            // get the current user ID
            $user = get_current_user_id();
            // get the current admin screen
            $screen = get_current_screen();
            // retrieve the "per_page" option
            $screen_option = $screen->get_option('per_page', 'option');
            $per_page = 10;
            if(!empty( $screen_option)) {
                // retrieve the value of the option stored for the current user
                $per_page = get_user_meta($user, $screen_option, true);
                
                if ( empty ( $per_page) || $per_page < 1 ) {
                    // get the default value if none is set
                    $per_page = $screen->get_option( 'per_page', 'default' );
                }
            }

           

            //Which page is this?
           $paged = $sp_validate->_int("paged");
            //Page Number
            if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
            //How many pages do we have in total?

            $totalpages = ceil($totalitems/$per_page);
            //adjust the query to take pagination into account
            if(!empty($paged) && !empty($per_page)){
                $offset=($paged-1)*$per_page;
               // $query.=' LIMIT '.(int)$offset.','.(int)$per_page;
            }

    /* -- Register the pagination -- */
        $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $per_page,
        ) );
            

            $args = array(
            'post_type' => 'sendpress_list' ,
            'posts_per_page' => $per_page,
            'paged'=> $paged,
            );
            
            $s = $sp_validate->_string("s");
            if ( !empty( $s ) ){
                $args['s'] = $s;
            }

            
            $args['order'] = $sp_validate->_string("order")   == 'DESC' ?  'DESC' : 'ASC';
            
            $orderby = $sp_validate->orderby($sp_validate->_string("orderby"));
            if(!empty($orderby)){
                $args['orderby']  = $orderby;
                if($orderby == 'subject'){
                    $args['orderby']  = 'meta_value';
                    $args['meta_key']= '_sendpress_subject';
                }
                 if($orderby == 'lastsend'){
                    $args['orderby']  = 'meta_value';
                    $args['meta_key']= 'send_date';
                }
           

            }
            
            //$args = apply_filters('sendpress_list_table_query',$args);

            $query2 = SendPress_Data::get_lists($args);

            // $query2 = new WP_Query( $args );

            $this->items = $query2->posts;
        }
    
    
   

}