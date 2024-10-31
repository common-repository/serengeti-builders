<?php
 
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



    if($frm=="updatebadges"){
        $vanilla_badge=sanitize_text_field($_POST['txt_vanilla_badge']);
        if($vanilla_badge==""){
            $vanilla_badge="NEW";
        }
    die("sdf");
        insert_data_val($gridid,$vanilla_badge,'vanilla_left_badge');
        die("ok");
    }else{
        die("operation not identified");
    }	
    
    
    
    
    
?>