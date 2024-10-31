<?php
/**
 * Plugin Name: Serengeti Builders
 * Plugin URI: http://www.serengetibuilders.com/serengeti-postgrid
 * Description: Feel the power of creating endless post grids, with unlimited options and possibilities.
 * Version: 1.1
 * Author: Serengeti Builders Inc.
 * Author URI: http://www.serengetibuilders.com
 */
 
defined('ABSPATH') or die('\../');
require_once('CDAG.php');
require_once('UI_Dashboard.php');
require_once('UI_Settings.php');
require_once( ABSPATH . 'wp-includes/category.php' );

 if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ); 
 }
 
 require_once('pcm_wp_list_table.php');



class sgPlugin{
  function __construct(){
        add_action( 'admin_menu', array( $this, 'wpa_add_menu' )); 
       
        add_action('wp_enqueue_scripts',array( $this,'my_scripts'));
        register_activation_hook( __FILE__, array( $this, 'wpa_install' ) );
        register_deactivation_hook( __FILE__, array( $this, 'wpa_uninstall' ) );
  }
 
/*
 * import all scripts required. are in css folder
*/
 function my_scripts() { 
    wp_register_style( 'new_style', plugins_url('/css/styles.css', __FILE__), false, '2.4.3', 'all');
    wp_enqueue_style( 'new_style' );
    
    wp_register_style( 'new_style2', plugins_url('/css/css/all.css', __FILE__), false, '1.0.1', 'all');
    wp_enqueue_style( 'new_style2' );
    
  }


    /*
      * Actions perform at loading of admin menu
      */ 
    function wpa_add_menu() { 
        add_menu_page( 'Serengeti Grid', 'SerengetiGrid', 'manage_options', 'sgrid-dashboard', 'sgPlugin_ui_dashboard::load_dashboard', plugins_url('images/icon30x19.png', __FILE__),'2.2.9');
       
         add_submenu_page( 'sgrid-dashboard', 'Settings', 'Settings', 'manage_options', 'spg-settings', 'sgPlugin_ui_settings::load_settings' );
     
    }
    
 
 
    /*
     * Actions perform on loading of menu pages
    */
    function wpa_page_file_path() {
      
    }

    /*
     * Actions perform on activation of plugin
    */
    function wpa_install() {
     
    }

    /*
     * Actions perform on de-activation of plugin
    */
    function wpa_uninstall() {
      
    }
     

    /*
     * Checking if table exist & create
    */  
function create_table(){
    global $wpdb;
    $table_name = $wpdb->prefix.'serengeti_pg';
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        //table not in database. Create new table
        $charset_collate = $wpdb->get_charset_collate();
  
        $sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          name text NOT NULL,
          padding text NOT NULL,
          margin text NOT NULL,
          gridperrow text NOT NULL,
          postcategory text NOT NULL,
          status text NOT NULL,
          _gridid text NOT NULL,
          _value text NOT NULL,
          _label text NOT NULL,
          PRIMARY KEY (id)
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;";
     
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
        dbDelta( $sql );
    }else{
       //do nothing
    }
}   
  
    /*
     * Insert data
    */
 function insert_data($pg_name) {
	global $wpdb;
	 
	$table_name = $wpdb->prefix . 'serengeti_pg';
	
	$wpdb->insert( 
		$table_name, 
		array(  
			'name' => $pg_name, 
			'padding' => '', 
			'status'=>'active',
		) 
	);
}
  
 /*
 * update grid details once submit is clicked
*/ 
 function insert_data_val($gridid,$val,$label) {
	global $wpdb;
	 
	$table_name = $wpdb->prefix . 'serengeti_pg';
	
	//first delete of previous additional records of this grid
	$wpdb->delete( 
	    $table_name, 
	    array( 
	        '_gridid' => $gridid,
	        '_label' => $label,
	        ) 
	);
	
	
	//then add additional records of this grid
	$wpdb->insert( 
		$table_name, 
		array(  
			'_gridid' => $gridid, 
			'_value' => $val, 
			'_label' => $label,
		) 
	);
	
}

    /*
     * update padding,margin & postcateogry data of a grid
    */
 function update_spg($id,$padding,$margin,$postcategory) {
	global $wpdb;
	 
	$table_name = $wpdb->prefix . 'serengeti_pg';
	
	$wpdb->update(
	    $table_name, 
	    array(
	        'padding'=>$padding, 
	        'margin'=>$margin,
	        'postcategory'=>$postcategory
	        ), array('id'=>$id)
	        );
	 
}
  
    /*
     * update grids per row data
    */
 function update_spg_page_per_row($id,$qty) {
	global $wpdb;
	 
	$table_name = $wpdb->prefix . 'serengeti_pg';
	
	$wpdb->update(
	    $table_name, 
	    array(
	        'gridperrow'=>$qty
	        ), array('id'=>$id)
	        );
	 
}

    /*
     * diactivate grid
    */
 function delete_spg_page_per_row($id) {
	    global $wpdb;
	 
	    $table_name = $wpdb->prefix . 'serengeti_pg';
	
	    $wpdb->update(
	    $table_name, 
	    array(
	        'status'=>'inactive'
	        ), array('id'=>$id)
	    );
	 
}
  

    /*
     * universal excute, it accept full sql statement and return data from statement
    */
 function excute($stm){
 	 global $wpdb;
	  
     $result = $wpdb->get_results($stm);
     $res="";
     foreach($result as $row) {
          $res=$row->res;
     }
      
      return $res;
 }
 




/* below is the function to post all info about grid, whenever submit 
button is clicked to update grid info, this function is called, including
creating new grid, where $naumns is checked to identify which button has
been clicked (whether is create new or update existing
*/
public function the_form_response() {
    /***********************   POST GRID RESPONSE *********************/
    $naumns=sanitize_text_field($_POST['nds_add_user_meta_nonce_spg']);
    
	if( isset($naumns) && wp_verify_nonce($naumns, 'nds_add_user_meta_form_nonce_spg') ) {
		// sanitize the input
		  
	 	 $spg_name = sanitize_text_field( $_POST['new_spg_name'] );
		 sgPlugin::create_table();
	     sgPlugin::insert_data($spg_name);
	    
	    //die("ok");
	    $url="admin.php?page=spg-settings";
	    if ( wp_redirect( $url ) ) {
           exit;
         }
		// do the processing
		// add the admin notice
		$admin_notice = "success";
		// redirect the user to the appropriate page
		$this->custom_redirect( $admin_notice, $_POST );
		exit;
	  
	}			
	else {
	/*	wp_die( __( 'Invalid nonce specified', $this->plugin_name ), __( 'Error', $this->plugin_name ), array(
					'response' 	=> 403,
					'back_link' => 'admin.php?page=' . $this->plugin_name,
			) );*/
	}
   /***********************   END POST GRID RESPONSE *********************/	
	
	 	
   
	
    /***********************   UPDATE GRID RESPONSE *********************/
    $naumnsu=sanitize_text_field($_POST['nds_add_user_meta_nonce_spg_upt']);
    
	if( isset($naumnsu) && wp_verify_nonce( $naumnsu, 'nds_add_user_meta_form_nonce_spg') ) {
		// sanitize the input
		
		
		$id = sanitize_text_field( $_POST['spg_selected_grid_id'] ); 
		$id=str_replace("!","",$id);
		
		//PADINGS
		$spg_pd_top = strtolower(sanitize_text_field($_POST['spg_pd_top'])); 
		if(!is_numeric(str_replace("%","",str_replace("px","",sanitize_text_field($spg_pd_top))))){
		    $spg_pd_top="0px";
		}
		$spg_pd_right = strtolower(sanitize_text_field($_POST['spg_pd_right'])); 
		if(!is_numeric(str_replace("%","",str_replace("px","",$spg_pd_right)))){
		    $spg_pd_right="0px";
		}  
		$spg_pd_bottom = strtolower(sanitize_text_field($_POST['sp(g_pd_bottom'])); 
		if(!is_numeric(str_replace("%","",str_replace("px","",$spg_pd_bottom)))){
		    $spg_pd_bottom="0px";
		}  
		$spg_pd_left = strtolower(sanitize_text_field($_POST['spg_pd_left'])); 
		if(!is_numeric(str_replace("%","",str_replace("px","",$spg_pd_left)))){
		    $spg_pd_left="0px";
		} 
		
		$padding=$spg_pd_top." ".$spg_pd_right." ".$spg_pd_bottom." ".$spg_pd_left;
		
		//MARGINS
		$spg_mrg_top = strtolower(sanitize_text_field($_POST['spg_mrg_top']));  
		if(!is_numeric(str_replace("%","",str_replace("px","",$spg_mrg_top)))){
		    $spg_mrg_top="0px";
		} 
		$spg_mrg_right = strtolower(sanitize_text_field($_POST['spg_mrg_right'])); 
		if(!is_numeric(str_replace("%","",str_replace("px","",$spg_mrg_right)))){
		    $spg_mrg_right="0px";
		} 
		$spg_mrg_bottom = strtolower(sanitize_text_field($_POST['spg_mrg_bottom'])); 
		if(!is_numeric(str_replace("%","",str_replace("px","",$spg_mrg_bottom)))){
		    $spg_mrg_bottom="0px";
		} 
		$spg_mrg_left = strtolower(sanitize_text_field($_POST['spg_mrg_left'])); 
		if(!is_numeric(str_replace("%","",str_replace("px","",$spg_mrg_left)))){
		    $spg_mrg_left="0px";
		} 
		
		$margin=$spg_mrg_top." ".$spg_mrg_right." ".$spg_mrg_bottom." ".$spg_mrg_left; 
		
		
		$btn_bg_color=sanitize_hex_color($_POST['spg_bbgc']);
		if($btn_bg_color==""){
		    $btn_bg_color="#fff";
		}
		$btn_font_color=sanitize_hex_color($_POST['spg_bfc']);
		if($btn_font_color==""){
		    $btn_font_color="#333";
		}
		$btn_border_color=sanitize_hex_color($_POST['spg_bbc']);
		if($btn_border_color==""){
		    $btn_border_color="#ccc";
		}
		
		//GRID PER ROW
		$grid_per_row = sanitize_text_field($_POST['sel_spg_grid_per_row']); 
		
		//POST CATEGORY
		$postcategory=sanitize_text_field($_POST['sel_spg_grid_category']);
		
		//GRID STYLE
		$gridstyle=sanitize_text_field($_POST['sel_spg_grid_style']);
		if($gridstyle==""){
		    $gridstyle="mango";
		}
		
		//GRID THUMB HOVER EFFECT
		$gridhovereffect=sanitize_text_field($_POST['sel_spg_grid_hovereffect']);
		if($gridhovereffect==""){
		    $gridhovereffect="none";
		}
		 
		//GRID TITLE BACKGROUND
		$tittle_bg=sanitize_text_field($_POST['spg_title_bc']);
		if($tittle_bg==""){
		    $tittle_bg="no color";
		}
		
		//GRID TITLE COLOR 
		$tittle_fc=sanitize_hex_color($_POST['spg_title_fc']); 
		if($tittle_fc==""){ 
		    $tittle_fc="#333"; 
		}
		
		//GRID TITLE FONT FAMILY 
		 $tittle_fontfamily=sanitize_text_field($_POST['sel_spg_grid_fontfamily']); 
		if($tittle_fontfamily==""){ 
		    $tittle_fontfamily="Arial"; 
		}
		
		//GRID DESCRIPTION FONT FAMILY 
		 $descr_fontfamily=sanitize_text_field($_POST['sel_spg_grid_descrfontfamily']); 
		if($descr_fontfamily==""){ 
		    $descr_fontfamily="Arial"; 
		}
		
		//GRID TITLE FONT SIZE
		 $tittle_fontsize=sanitize_text_field($_POST['spg_title_fontsize']); 
		if($tittle_fontsize==""){ 
		    $tittle_fontsize="12px"; 
		}
		
		//GRID DESCRIPTION FONT COLOR
		 $descr_font_color=sanitize_hex_color($_POST['spg_descr_fc']); 
		if($descr_font_color==""){ 
		    $descr_font_color="#333"; 
		}
		
		//GRID DESCRIPTION FONT SIZE
		 $descr_font_size=sanitize_text_field($_POST['spg_descr_fs']); 
		if($descr_font_size==""){ 
		    $descr_font_size="12px"; 
		}elseif(!is_numeric(str_replace("px","",$descr_font_size))){
		    $descr_font_size="12px";
		}
		 
		 
		 
		
	    sgPlugin::update_spg($id,$padding,$margin,$postcategory); 
	    if($grid_per_row!=""){
	        sgPlugin::update_spg_page_per_row($id,$grid_per_row);
	    }
	    
	    
	    /* all grid properties uses one table, with grid id specify a _value is for which grid & label specify if for which field
	    */
	    //INSERT BUTTONS PROPERTIES
		sgPlugin::insert_data_val($id,$btn_bg_color,'btn_bg_color');
		sgPlugin::insert_data_val($id,$btn_font_color,'btn_font_color');
		sgPlugin::insert_data_val($id,$btn_border_color,'btn_border_color');
		
		//INSERT GRID STYLE
		sgPlugin::insert_data_val($id,$gridstyle,'gridstyle');
		
		//INSERT GRID OVER EFFECT
		sgPlugin::insert_data_val($id,$gridhovereffect,'gridhovereffect');
		
		//INSERT TITLE BG
		sgPlugin::insert_data_val($id,$tittle_bg,'gridtitlebg');
		
		//INSERT FONT COLOR
		sgPlugin::insert_data_val($id,$tittle_fc,'gridtitlefc');
		
		//INSERT FONT FAMILY
		sgPlugin::insert_data_val($id,$tittle_fontfamily,'gridtitlefontfamily');
		
		//INSERT DESCRIPTION FONT FAMILY
		sgPlugin::insert_data_val($id,$descr_fontfamily,'griddescriptionfontfamily');
		
		//INSERT TITLE FONT SIZE
		sgPlugin::insert_data_val($id,$tittle_fontsize,'gridtitlefontsize');
		
		//INSERT DESCRIPTION FONT COLOR
		sgPlugin::insert_data_val($id,$descr_font_color,'descriptionfontcolor');
		
		//INSERT DESCRIPTION FONT SIZE
		sgPlugin::insert_data_val($id,$descr_font_size,'descriptionfontsize');
		
		
		
		
	   $url="admin.php?page=spg-settings";
	    if ( wp_redirect( $url ) ) {
           exit;
         }

	    die("UPDATE OK, go back & refresh to see changes");
		// do the processing
		// add the admin notice
		$admin_notice = "success";
		// redirect the user to the appropriate page
		$this->custom_redirect( $admin_notice, $_POST );
		exit;
	  
	}			
	else {
		/*wp_die( __( 'Invalid nonce specified', $this->plugin_name ), __( 'Error', $this->plugin_name ), array(
					'response' 	=> 403,
					'back_link' => 'admin.php?page=' . $this->plugin_name,
			) );*/
	}
   /***********************   END UPDATE GRID RESPONSE *********************/
}

   
   
   
   
     
  /*below is the function called whenever grid shortcode is called
    1. it checks if is valid shortcode id
    2. it fetch all required properties/attributes of the shortcode
    3. base of the information of the short code, fetch its data eg. grids per each row, css classes to be used etc.
  */
  function spg_core($atts) {
	 $a=shortcode_atts( array(
		'pos' => '1',
		'dcat' => 'all',
		'id' => '0',
	), $atts );
	
	
	global $wpdb;
	
	if($atts['id']==0){
	    return "shortcode id not found";
	}else{
	        //GET GRID ATTRIBUTES
	        $table_name = $wpdb->prefix.'serengeti_pg';
	    
	        $gridperrow=sgPlugin::excute("SELECT gridperrow as res FROM ".$table_name." where id='".$atts['id']."' limit 1");
	    
	        $postcategory=sgPlugin::excute("SELECT postcategory as res FROM ".$table_name." where id='".$atts['id']."' limit 1");
	    
	        $padding=sgPlugin::excute("SELECT padding as res FROM ".$table_name." where id='".$atts['id']."' limit 1");
	        if($padding==""){
	            $padding="0 0 0 0";
	        }
	    
	        $margin=sgPlugin::excute("SELECT margin as res FROM ".$table_name." where id='".$atts['id']."' limit 1");
	        if($margin==""){
	            $margin="0 0 0 0";
	        }
	   
	   
	   
	   
	   //get btn bg
	   $btn_bg_color=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='btn_bg_color' limit 1");
	   if($btn_bg_color==""){
	       $btn_bg_color="#ccc";
	   }
	   
	   //get btn font color
	   $btn_font_color=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='btn_font_color' limit 1");
	   if($btn_font_color==""){
	       $btn_font_color="#fff";
	   }
	   
	   //get btn border color
	   $btn_border_color=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='btn_border_color' limit 1");
	    
	    if($btn_border_color!=""){ 
	       $btn_border_style='background:'.$btn_bg_color.'; color:'.$btn_font_color.';border: 1px solid '.$btn_border_color;
	    }else{
	        $btn_border_style='';
	    }
	   
	   
	   //get grid style
	   $gridstyle=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='gridstyle' limit 1");
	   if($gridstyle==""){
	       $gridstyle="mango";
	   }
	   
	   //get grid thumb hover effect
	   $thumbhovereffect=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='gridhovereffect' limit 1");
	   if($thumbhovereffect=="zoomin"){
	       $theffect_class='b';
	   }elseif($thumbhovereffect=="zoomin_divshadow"){
	       $theffect_class='c';
	   }else{
	       $theffect_class='';
	   }
	   
	   //get title background
	   $gridtitlebg=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='gridtitlebg' limit 1");
	   if($gridtitlebg=="" || $gridtitlebg=="no color"){
	       $gridtitlebg='rgba(0,0,0,0)';
	   } 
	   
	   //get title font color
	   $gridtitlefc=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='gridtitlefc' limit 1");
	   if($gridtitlefc==""){
	       $gridtitlefc='#000';
	   } 
	   
	   //get title font family
	   $gridtitlefontfamily=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='gridtitlefontfamily' limit 1");
	   $gridtitlefontfamily=str_replace("\'","'",$gridtitlefontfamily);
	   
	   if($gridtitlefontfamily==""){
	       $gridtitlefontfamily='Arial';
	    } 
	   
	   //get description font family
	   $descrfontfamily=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='griddescriptionfontfamily' limit 1");
	   $descrfontfamily=str_replace("\'","'",$descrfontfamily);
	   
	   if($descrfontfamily==""){
	       $descrfontfamily='Arial';
	    } 
	   
	   //get title font size
	   $gridtitlefontsize=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='gridtitlefontsize' limit 1");
	   
	   if($gridtitlefontsize==""){
	       $gridtitlefontsize="12px";
	   }
	   
	   
	   //get description font color
	   $descr_fontcolor=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='descriptionfontcolor' limit 1");
	   
	   if($descr_fontcolor==""){
	       $descr_fontcolor="#333";
	   }
	   
	   //get description font color
	   $descr_fontsize=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='descriptionfontsize' limit 1");
	   
	   if($descr_fontsize==""){
	       $descr_fontsize="13px";
	   }
	   
	   $vanilla_badge="";
	   $vanilla_booknow="";
	   if($gridstyle=="vanilla"){
	       $vanilla_badge=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='vanilla_right_badge' limit 1");
	       if($vanilla_badge==""){
	           $vanilla_badge="NEW";
	       }
	       
	       $vanilla_booknow=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='vanilla_booknow' limit 1");
	       if($vanilla_booknow==""){
	           $vanilla_booknow="BookNow";
	       }
	   } 
	   
	   $straw_badge="";
	   if($gridstyle=="strawberry"){
	       $straw_badge=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$atts['id']."' and _label='strawberry_badge' limit 1");
	       if($straw_badge==""){
	           $straw_badge="SALE";
	       }
	   }
	   
	   //base on gridperrow set appropriate class
	   $add_wr_css='class="post_wrapper_4" ';
	   if($gridperrow==2){ 
	       $add_wr_css='class="post_wrapper_2'.$theffect_class.'" ';
	   }else if($gridperrow==3){
	       $add_wr_css='class="post_wrapper_3'.$theffect_class.'" ';
	   }else if($gridperrow==4){
	       $add_wr_css='class="post_wrapper_4'.$theffect_class.'" ';
	   }else if($gridperrow==5){
	       $add_wr_css='class="post_wrapper_5'.$theffect_class.'" ';
	   }
	   
	   if($gridstyle=="strawberry"){
	       $add_wr_css='class="post_wrapper_6" ';
	   }
	   
	//query posts
    ob_start();
    $query = new WP_Query( array(
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'title',
        'category_name' => $postcategory,
    ) );
    
    $res="";
    if ( $query->have_posts() ) { 
    while ( $query->have_posts() ) : $query->the_post();
    
     
            /*** LOOK FOR EXTERNAL URL FOR THE POST ***/
            $post_keys = array(); $post_val = array();
            $post_keys = get_post_custom_keys($thePostID);
            $perm=get_permalink();
    
            $straw_share_link="#";
            $vanilla_booknow_link="#";
            if (!empty($post_keys)) {
                foreach ($post_keys as $pkey) {
                    if ($pkey=='external_url') {
                        $post_val = get_post_custom_values($pkey);
                    }elseif ($pkey=='straw_share_url') {
                        $post_val2 = get_post_custom_values($pkey);
                    }elseif ($pkey=='vanilla_booknow_url') {
                        $post_val3 = get_post_custom_values($pkey);
                    }
                 }
      
                if (empty($post_val)) {
                    $link = $perm;
                }else {
                    $link = $post_val[0];
                }
                
                
                if (!empty($post_val2)) { 
                    $straw_share_link = $post_val2[0];
                }
                
                if (!empty($post_val3)) { 
                    $vanilla_booknow_link = $post_val3[0];
                }
                
      
            } else {
                $link = $perm;
            }
            /***  END LOOK FOR EXTERNAL URL ***/
    
    
    
    
        $gtitle='<a href="'.$link.'" class="spg_link" style="color:'.$gridtitlefc.';font-family:'.$gridtitlefontfamily.';font-size:'.$gridtitlefontsize.';">'.get_the_title().'</a>';
        
        $gtitle2='<a href="'.$link.'" class="spg_link2">'.get_the_title().'</a>';
    
    
        // each gridstyle has its own div arrangement, all are done here
        if($gridstyle=="mango"){   
            $res.='<div '.$add_wr_css.' style="margin:'.$margin.'; text-align:left;">';
                $res.='<div class="post_thumb"><a href="'.$link.'">'.get_the_post_thumbnail().'</a></div>';
    
                //SEPARATE CONTENT DIV WITH THUMB DIV AS MIGHT HAVE DIFFERENT CSS
                $res.='<div style="padding:'.$padding.';">';
                    $res.='<div class="post_thumb_title"><a href="'.$link.'">'.$gtitle.'</a></div>'; 
                    $res.='<div class="post_thumb_date">by '.get_the_author().'&nbsp;&nbsp;|&nbsp;&nbsp; </div>';
                    $res.='<div class="post_thumb_date"> '.get_the_date().'</div>';
                 
                    $cont=get_the_content();
                 
                    $res.='<div class="post_thumb_cont" style="font-family:'.$descrfontfamily.';color:'.$descr_fontcolor.';font-size:'.$descr_fontsize.';">'.$cont.'</div>';
                 
                    $res.='<div style="margin-top:25px;padding:10px;"><a href="'.$link.'"><button class="btn md cta" style="'.$btn_border_style.';">Read more</button></a></div>';
                 $res.='</div>';
    
            $res.='</div>';
        
        }elseif($gridstyle=="orange"){ 
        
                $res.='<div '.$add_wr_css.' style="margin:'.$margin.'"> 
                  <div class="post_thumb">
                        <a href="'.$link.'">'.get_the_post_thumbnail().'</a>
                       <div class="orange_title_css" style="background:'.$gridtitlebg.';">'.$gtitle.'</div>
                   </div>      
                </div>';
                
                
        }elseif($gridstyle=="apple"){
            $res.='<div '.$add_wr_css.' style="margin:'.$margin.'">';
                $res.='<div class="post_thumb"><a href="'.$link.'">'.get_the_post_thumbnail().'</a></div>';
    
            //SEPARATE CONTENT DIV WITH THUMB DIV AS MIGHT HAVE DIFFERENT CSS
            $res.='<div style="padding:'.$padding.';">';
                $res.='<div class="post_thumb_title"><a href="'.$link.'">'.$gtitle.'</a></div>'; 
                  
                $cont=get_the_content();
                
                $res.='<div class="post_thumb_cont" style="font-family:'.$descrfontfamily.';color:'.$descr_fontcolor.';font-size:'.$descr_fontsize.';">'.$cont.'</div>';
                
                $res.='<div style="margin-top:25px;padding:10px;"><a href="'.$link.'"><button class="btn md cta" style="'.$btn_border_style.';">Read more</button></a></div>';
             $res.='</div>';
    
            $res.='</div>';
        }elseif($gridstyle=="coconut"){
            $res.='<div '.$add_wr_css.' style="margin:'.$margin.'">';
                 $res.='<div class="post_thumb" ><a href="'.$link.'">'.get_the_post_thumbnail().'</a></div>';
    
            //SEPARATE CONTENT DIV WITH THUMB DIV AS MIGHT HAVE DIFFERENT CSS
            $res.='<div style="padding:'.$padding.';">';
                $res.='<div class="post_thumb_title"><a href="'.$link.'">'.$gtitle.'</a></div>'; 
                  
                //$cont=get_the_content();
                
                $res.='<hr style="width:80%;display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 0.5px;">';
                
                $res.='<div style="margin-top:15px;padding:10px;"><a href="'.$link.'"><button class="btn md cta" style="height:30px;border-radius:15px;'.$btn_border_style.';">Read more</button></a></div>';
             $res.='</div>';
    
            $res.='</div>';
        }elseif($gridstyle=="vanilla"){
            $res.='<div '.$add_wr_css.' style="border-width: 0px; margin:'.$margin.'">';
                 $res.='<div class="post_thumb" ><a href="'.$link.'">'.get_the_post_thumbnail().'</a>';
                 
                 $tbtn='<div style="position:absolute; margin-top:1px;padding:0px; right:5px; width:auto;"><a href="'.$vanilla_booknow_link.'"><div style="height:30px;border-radius:15px; padding:3px 10px 0 10px; background:#ffdd00; color:#000; width:auto; ">'.$vanilla_booknow.'</div></a></div>';
                 
                    $res.='<div class="orange_title_css" style="width:100%; top:15px; bottom:0px;height:50px; padding:0px; background:none; ">
                      <div style="position:absolute; min-width:45px; width:auto; background:#6cc028; padding:3.5px 5px 0px 10px; font-family:arial; font-weight:normal;font-size:12px; height:25px;">'.$vanilla_badge.'</div><div class="cls_ribbon"><div style="position:absolute; z-index:1000000; padding:2px 0 0 5px;"><i class="fas fa-bolt" style="color: #fff;">&nbsp;</i></div></div>'.$tbtn.'
                    </div>';
                    
                    $res.='<div class="orange_title_css" style="background:'.$gridtitlebg.';bottom:40px;">'.$gtitle2.'</div>';
                        $res.='<div class="orange_title_css" style="bottom:15px; background:none">
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00">&nbsp;</i></div>
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00;">&nbsp;</i></div>
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00;">&nbsp;</i></div>
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00;">&nbsp;</i></div>
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00;">&nbsp;</i></div>
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00;">&nbsp;</i></div>
                        </div>
                 </div>';
    
            //SEPARATE CONTENT DIV WITH THUMB DIV AS MIGHT HAVE DIFFERENT CSS
            $res.='<div style="padding:'.$padding.';">';
                //$res.='<div class="post_thumb_title"><a href="'.$link.'">'.$gtitle.'</a></div>'; 
                  
                //$cont=get_the_content();
                
                //$res.='<hr style="width:80%;display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 0.5px;">';
                
                $res.='<div style="margin-top:15px;padding:10px;"><a href="'.$link.'"><button class="btn md cta" style="height:30px;border-radius:15px;'.$btn_border_style.';">Read more</button></a></div>';
             $res.='</div>';
    
            $res.='</div>';
        }elseif($gridstyle=="strawberry"){
            $res.='<div '.$add_wr_css.' style="margin:'.$margin.';background:#fafafa;" >';
                 
                  /**** GREEN RIBON ****/
                    $res.='<div style="position:absolute; z-index:100000; min-width:45px; width:auto; background:#6cc028; padding:3.5px 5px 0px 10px; font-family:arial; font-weight:normal;font-size:12px; height:25px; color:#fff">'.$straw_badge.'</div>
                    <div class="cls_ribbon" style=" z-index:1000000;">
                        <div style="position:absolute; z-index:1000000; padding:2px 0 0 5px;"><i class="fas fa-bolt" style="color: #fff;">&nbsp;</i></div>
                    </div>';
                  /*** END GREEN RIBON ***/
                  
                $res.='<div style="width:30%; overflow:hidden; display:inline-block; " >
                    <a href="'.$link.'">'.get_the_post_thumbnail().'</a>';
                $res.='</div>';
                
                $res.='<div class="cls_berry_right" >';
                    $res.='<div style="width:100%; ">'.$gtitle.'</div>';
                    $res.='<div style="width:100%; text-align:right; padding:0 0 10px 0;">
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00">&nbsp;</i></div>
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00;">&nbsp;</i></div>
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00;">&nbsp;</i></div>
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00;">&nbsp;</i></div>
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00;">&nbsp;</i></div>
                            <div style="display: inline-block;"><i class="fas fa-star" style="color: #ffdd00;">&nbsp;</i></div>
                    </div>';
                    $res.='<div class="cls_berry_cont" style="font-family:'.$descrfontfamily.';color:'.$descr_fontcolor.';font-size:'.$descr_fontsize.';">'.get_the_content().'</div>';
                    $res.='<div style="width:100%; margin-top:15px;">';
                        
                        $res.='<div style="display:inline-block; margin-left:10px; width:45%; ">
                            <a href="'.$link.'">
                                <button class="btn md-green" style="min-width:90px;">Read more</button>
                            </a>
                        </div>';
                        $res.='<div style="position:relative; display:inline-block; width:45%; text-align:right;">
                            <a href="'.$straw_share_link.'">
                                <button class="btn md-green" style="min-width:60px;">
                                    <i class="fas fa-share-alt" style="color: #ffdd00;">&nbsp;</i>
                                </button>
                            </a>
                        </div>';
                        
                    $res.='</div>';
                $res.='</div>';
            $res.='</div>';
        }
        
        
    
    endwhile;
    }
    
    return $res;
 }
  }
 
 
 
 
 /*below function used to fetch details of particular grid
   on admin page, once grid clicked on list of grids created.
   1. fetch all grid info
   2. encode them to json
   3. return to function on UI_setting files
   3. decode them n display
 */
   
function get_grid_details() {
	global $wpdb; // this is how you get access to the database

	$id =  str_replace("!","",sanitize_text_field($_POST['id']));
    

    $table_name = $wpdb->prefix.'serengeti_pg';
	
	 $tdate=date("Y-m-d");
     $result = $wpdb->get_results("SELECT padding,margin,gridperrow,postcategory FROM ".$table_name." where id='".$id."' limit 1");
     
     $res="";
     $feedback_array=array('feedback' => array()); 
     foreach($result as $row) {
          $count+=1;
          
          $padding=explode(" ",$row->padding);
          $margin=explode(" ",$row->margin);
          $gridperrow=$row->gridperrow;
          $postcategory=$row->postcategory;
          
		  $feedback_array['feedback'][] = array('padding_top' => $padding[0]);  
		  $feedback_array['feedback'][] = array('padding_right' => $padding[1]); 
		  $feedback_array['feedback'][] = array('padding_bottom' => $padding[2]); 
		  $feedback_array['feedback'][] = array('padding_left' => $padding[3]); 
		  
		  $feedback_array['feedback'][] = array('margin_top' => $margin[0]);  
		  $feedback_array['feedback'][] = array('margin_right' => $margin[1]);  
		  $feedback_array['feedback'][] = array('margin_bottom' => $margin[2]); 
		  $feedback_array['feedback'][] = array('margin_left' => $margin[3]);  
		  
		  $feedback_array['feedback'][] = array('gridperrow' => $gridperrow); 
		  $feedback_array['feedback'][] = array('postcategory' => $postcategory);   
	
     }
     
     
     
     
     
     
     
     
     //GET BUTTON BG COLOR
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='btn_bg_color' limit 1");
     $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('btn_bg_color' => $row->_value);
     }
     if($c<=0){//if nothing found add blank to the btn_bg_color array
        $feedback_array['feedback'][] = array('btn_bg_color' => ''); 
     }
     
     //GET BUTTON FONT COLOR
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='btn_font_color' limit 1");
      $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('btn_font_color' => $row->_value);
     }
     if($c<=0){
        $feedback_array['feedback'][] = array('btn_font_color' =>''); 
     }
     
     
     //GET BUTTON BORDER COLOR
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='btn_border_color' limit 1");
      $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('btn_border_color' => $row->_value);
     }
     if($c<=0){
        $feedback_array['feedback'][] = array('btn_border_color' => ''); 
     }
     
     
     //GET GRID STYLE
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='gridstyle' limit 1");
      $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('grid_style' => $row->_value);
     }
     if($c<=0){
        $feedback_array['feedback'][] = array('grid_style' => ''); 
     }
     
     //GET GRID HOVER EFFECT
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='gridhovereffect' limit 1");
      $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('gridhover' => $row->_value);
     }
     if($c<=0){
        $feedback_array['feedback'][] = array('gridhover' => ''); 
     }
     
     //GET GRID TITLE BACKGROUND
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='gridtitlebg' limit 1");
      $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('gridtitlebg' => $row->_value);
     }
     if($c<=0){
        $feedback_array['feedback'][] = array('gridtitlebg' => ''); 
     }
     
     //GET GRID TITLE FONT COLOR
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='gridtitlefc' limit 1");
      $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('gridtitlefc' => $row->_value);
     }
     if($c<=0){
        $feedback_array['feedback'][] = array('gridtitlefc' => ''); 
     }
     
     //GET GRID TITLE FONT FAMILY
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='gridtitlefontfamily' limit 1");
      $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('gridtitlefontfamily' => str_replace("\'","'",$row->_value));
     }
     if($c<=0){
        $feedback_array['feedback'][] = array('gridtitlefontfamily' => ''); 
     }
     
     //GET GRID DESCRIPTION FONT FAMILY
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='griddescriptionfontfamily' limit 1");
      $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('descrfontfamily' => str_replace("\'","'",$row->_value));
     }
     if($c<=0){
        $feedback_array['feedback'][] = array('descrfontfamily' => ''); 
     }
     
     //GET GRID TITLE FONT SIZE
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='gridtitlefontsize' limit 1");
      $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('titlefontsize' => $row->_value);
     }
     if($c<=0){
        $feedback_array['feedback'][] = array('titlefontsize' => ''); 
     }
     
     //GET GRID DESCRIPTION FONT COLOR
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='descriptionfontcolor' limit 1");
      $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('desc_fontcolor' => $row->_value);
     }
     if($c<=0){
        $feedback_array['feedback'][] = array('desc_fontcolor' => ''); 
     }
     
     //GET GRID DESCRIPTION FONT SIZE
     $result = $wpdb->get_results("SELECT _value FROM ".$table_name." where _gridid='".$id."' and _label='descriptionfontsize' limit 1");
      $c=0;
     foreach($result as $row) {
         $c+=1;
         $feedback_array['feedback'][] = array('desc_fontsize' => $row->_value);
     }
     if($c<=0){
        $feedback_array['feedback'][] = array('desc_fontsize' => ''); 
     }
     
     //GET VANILLA BADGE
     $vanilla_badge=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$id."' and _label='vanilla_right_badge' limit 1");
     if($vanilla_badge!=""){
         $feedback_array['feedback'][] = array('vanillabadge' => $vanilla_badge);
     }else{
         $feedback_array['feedback'][] = array('vanillabadge' =>'');
     }
     
     //GET VANILLA BOOKNOW
     $vanilla_booknow=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$id."' and _label='vanilla_booknow' limit 1");
     if($vanilla_booknow!=""){
         $feedback_array['feedback'][] = array('vanillabooknow' => $vanilla_booknow);
     }else{
         $feedback_array['feedback'][] = array('vanillabooknow' =>'');
     }
     
     //GET STRAWBERRY BADGE
     $straw_badge=sgPlugin::excute("SELECT _value as res FROM ".$table_name." where _gridid='".$id."' and _label='strawberry_badge' limit 1");
     if($straw_badge!=""){
         $feedback_array['feedback'][] = array('strawberrybadge' => $straw_badge);
     }else{
         $feedback_array['feedback'][] = array('strawberrybadge' =>'');
     }
     
     
     
     
     
    $res=json_encode($feedback_array) ;

	die($res);
}
 
 
 
    function delete_grid() {
	    global $wpdb; // this is how you get access to the database

	    $id =  str_replace("!","",sanitize_text_field($_POST['id']));
    
        sgPlugin::delete_spg_page_per_row($id);
     
        $url="admin.php?page=spg-settings";
	        if ( wp_redirect( $url ) ) {
                exit;
            }
          
    }
 
    function update_grid_gridproperties(){
        $op=sanitize_text_field($_POST['op']);
        $gridid=sanitize_text_field(str_replace("!","",$_POST['id']));
        
        $cdag=new sgPlugin_cdag();
        if($op=="updatebadge"){
            
            //update vanilla left badge
            $vanilla_badge=sanitize_text_field($_POST['v_badge']);
            if($vanilla_badge!=""){
                 $cdag->ginsert_data_val($gridid,$vanilla_badge,'vanilla_right_badge') ;
            }
            
            //update vanilla book now button
            $vanilla_booknow=sanitize_text_field($_POST['v_book']);
            if($vanilla_booknow!=""){
                 $cdag->ginsert_data_val($gridid,$vanilla_booknow,'vanilla_booknow') ;
            }
            
            //update strawberry badge
            $straw_badge=sanitize_text_field($_POST['s_badge']);
            if($straw_badge!=""){
                 $cdag->ginsert_data_val($gridid,$straw_badge,'strawberry_badge') ;
            }
        
            
            

            die("ok");
        }else{
            die("unknown op");
        }
    } 
 
 
 
 
 
 
}

if(class_exists('sgPlugin')){
$sgPlugin=new sgPlugin();
}
 
 add_action( 'admin_post_nds_form_response', array('sgPlugin','the_form_response'));
 
 add_shortcode( 'serengeti-pg',  array('sgPlugin','spg_core'));
 
 add_action( 'wp_ajax_get_grid_details', array('sgPlugin','get_grid_details') );
 add_action( 'wp_ajax_delete_grid', array('sgPlugin','delete_grid') );
 add_action( 'wp_ajax_update_grid_gridproperties', array('sgPlugin','update_grid_gridproperties') );
  
 
 //THIS IS NEED FOR wp_kses()
 add_filter( 'safe_style_css', function( $styles ) {
    $styles[] = 'javascript';
    $styles[] = 'display'; 
    return $styles;
} );

 
 