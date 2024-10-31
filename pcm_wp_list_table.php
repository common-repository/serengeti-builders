<?php
 
class PMC_WP_List_Table extends WP_List_Table 
{        
    
 
	    function __construct(){
	    
                global $status, $page;
        
	        	parent::__construct( array(
                'singular'  => 'wp_list_event',
                'plural'    => 'wp_list_events',
			    'ajax'      => false        //does this table support ajax?
	        ) );
    	}
    	

	/**
	 * Add delete bulk op
	 */
    function get_bulk_actions() {
        $actions = array(
         'delete'    => 'Delete'
         );
        
        return $actions;
    }

/**
 * Process our bulk actions
 * 
 * @since 1.2
 */
function process_bulk_action() { 
    
    $entry_id = ( is_array($_REQUEST['entry']) ) ? $_REQUEST['entry'] : array($_REQUEST['entry']);

    if ( 'delete' === $this->current_action() ) {
        global $wpdb;

        foreach ( $entry_id as $id ) {
            
            //SANITIZATION OF $ENTRY ARRAY ITEMS IS DONE HERE
            $id = absint(sanitize_key($id) );
            if($id==""){$id="0";}
            
            $wpdb->query( "DELETE FROM ". $wpdb->prefix.'serengeti_pg'." WHERE  id = ".$id );
        }
    }
}

	/**
	 * Add columns checkbox
	 
function get_columns() {
  $columns = array(
    'cb'        => '<input type="checkbox" />'
  );
}*/


    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'],
            $item['id']
        );
    }
    
	/**
	 * Add columns to grid view
	 */
	function get_columns(){
		$columns = array(
		'cb' => '<input type="checkbox">',
		'id' => 'ID',
		'name'    => 'Name',
        'shortcode' => 'Shortcode' //custom field
		);
		return $columns;
	}	

	function column_default( $item, $column_name ) {
		switch( $column_name ) { 
			case 'id':
			case 'name':	
			case 'shortcode': //custom field
		  return $item[ $column_name ];
		default:
		  return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}			
	
	    function column_name($item)
    {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &dathangnhanh=2
        
        $rpage=sanitize_text_field($_REQUEST['page']);
        if($rpage==""){
            $rpage="0";
        }
        $actions = array(
            'edit' => sprintf('<a href="javascript:opengrid(&#39!'.$item['id'].'&#39)">Edit</a>', $item['id'], __('Edit', 'cltd_example')),
            'delete' => sprintf('<a href="javascript:deletegrid(&#39!'.$item['id'].'&#39)">Delete</a>',$rpage, $item['id'], __('Delete', 'cltd_example')),
        );
        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }
    
	protected function get_views() { 
	  $views = array();
	 
	   //accept all inputs, numbers, text or blank
	   $cvar=sanitize_text_field($_REQUEST['customvar']);
	  
	   //validate, if $cvar is blank then default is all
	   $current = ( !empty($cvar) ? $cvar : 'all');

	   //All link
	   $class = ($current == 'all' ? ' class="current"' :'');
	   $all_url = remove_query_arg('customvar');
	   $views['all'] = "<a href='{$all_url }' {$class} >All</a>";

	   //Recovered link
	   $foo_url = add_query_arg('customvar','recovered');
	   $class = ($current == 'recovered' ? ' class="current"' :'');
	   $views['recovered'] = "<a href='{$foo_url}' {$class} >Recovered</a>";

	   //Abandon
	   $bar_url = add_query_arg('customvar','abandon');
	   $class = ($current == 'abandon' ? ' class="current"' :'');
	   $views['abandon'] = "<a href='{$bar_url}' {$class} >Abandon</a>";

	   return $views;
	}
	
	
	
	function usort_reorder( $a, $b ) {
	   
	   //accept all inputs (number, text or blank)
	   $godb=sanitize_text_field($_GET['orderby']);
	   $ord=sanitize_text_field($_GET['order']);
	   
	   // validate, If no sort, default to title
	   $orderby = ( ! empty($godb) ) ? $godb : 'id';
	  
	   // If no order, default to asc
	   $order = ( ! empty($ord ) ) ? $ord: 'desc';
	   // Determine sort order
	   $result = strcmp( $a[$orderby], $b[$orderby] );
	   // Send final sort direction to usort
	  return ( $order === 'asc' ) ? $result : -$result;
	}
	
	function get_sortable_columns() {
		$sortable_columns = array(
		'action'  => array('action',false),
		);
		return $sortable_columns;
	}	

	/**
	 * Prepare admin view
	 */	
	function prepare_active_items() {
		global $wpdb;

        $table_name = $wpdb->prefix.'serengeti_pg';
        
		$per_page = 50;
		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}
		
		//REGISTER BULK ACTION
		 $this->process_bulk_action();
		 
		$search = '';
		
		//Retrieve $customvar for use in query to get items. 
		$customvar = ( isset($_REQUEST['customvar']) ? $_REQUEST['customvar'] : '');
		$customvar=sanitize_text_field($customvar);
		
		if($customvar != '') {
			$search_custom_vars= "AND name LIKE '%" . esc_sql( $wpdb->esc_like( $customvar ) ) . "%'";
		} else	{
			$search_custom_vars = '';
		}
		
		$rs=sanitize_text_field($_REQUEST['s']);
		if ( ! empty( $rs ) ) {
			$search = "AND name LIKE '%" . esc_sql( $wpdb->esc_like( $rs ) ) . "%'";
		}		
		
		$items = $wpdb->get_results( "SELECT id,concat('<a href=\"javascript:opengrid(&#39!',id,'&#39)\">',name,'</a>') as name,concat('[serengeti-pg id=\"',id,'\"]') as shortcode FROM ".$table_name." WHERE 1=1 and status='active' {$search} {$search_custom_vars}" . $wpdb->prepare( "ORDER BY id DESC LIMIT %d OFFSET %d;", $per_page, $offset ),ARRAY_A);
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);	
		usort( $items, array( &$this, 'usort_reorder' ) );
		$count = $wpdb->get_var( "SELECT COUNT(id) FROM ".$table_name." WHERE 1 = 1 {$search} {$search_custom_vars}" );

		$this->items = $items;

		// Set the pagination
		$this->set_pagination_args( array(
			'total_items' => $count,
			'per_page'    => $per_page,
			'total_pages' => ceil( $count / $per_page )
		) );
		
		
		//INITIALIZE BULK OPTION
		// $this->process_bulk_action();
	}
	

}

?>