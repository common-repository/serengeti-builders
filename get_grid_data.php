<?php
 
 $id=sanitize_text_field($_GET['id']);
  
 //defined('ABSPATH') or die('\../');
 
class sgPlugin_getdata{
  function __construct(){
}
  
 
     /*
     * check data visitor
    */
 function get_data($id){
 	 global $wpdb;
	
	 $table_name = $wpdb->prefix.'serengeti_pg';
	
	 $tdate=date("Y-m-d");
     $result = $wpdb->get_results("SELECT padding,margin FROM ".$table_name." where id='".$id."' limit 1");
     
     $res="";
     $feedback_array=array('feedback' => array()); 
     foreach($result as $row) {
          $count+=1;
          
          $padding=$row->padding;
          $margin=$row->margin;
          
		  $feedback_array['feedback'][] = array('padding' => $padding);  
		  $feedback_array['feedback'][] = array('margin' => $margin);  
	
     }
	      $res=json_encode($feedback_array) ;
      
      return $res;
 }
 
  
   
}
 
 $sgd=new sgPlugin_getdata();
 die($sgd->get_data($id));
 
 
 
 