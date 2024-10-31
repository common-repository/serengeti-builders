<?php


class sgPlugin_ui_settings{
   
function load_settings(){
    $res='<style>
 .btn-group button { 
  background-color: #4bc3c4; /* Green background */
  border: 0px solid green; /* Green border */
  color: white; /* White text */
  padding: 10px 24px; /* Some padding */
  cursor: pointer; /* Pointer/hand icon */
  float: left; /* Float the buttons side by side */
}
 
 .btn-group2 button {
  background-color: #9bc347; /* Green background */
  border: 0px solid green; /* Green border */
  color: white; /* White text */
  padding: 10px 24px; /* Some padding */
  cursor: pointer; /* Pointer/hand icon */
  float: left; /* Float the buttons side by side */
}

.btn-group button:not(:last-child) {
  border-right: none; /* Prevent double borders */
}

/* Clear floats (clearfix hack) */
.btn-group2:after,
.btn-group:after {
  content: "";
  clear: both;
  display: table;
}

/* Add a background color on hover */
.btn-group2 button:hover,
.btn-group button:hover {
  background-color: #3e8e41;
}
</style>';

$cdag=new sgPlugin_cdag();
echo wp_kses($res, $cdag->spg_get_allowed_html() ) ;

?>

<script>

 

  function btn_coloring(c_btn){
      //clear previous
      document.getElementById("btn_new_grid").style.background="#4CAF50";
      //document.getElementById("btn_wvt").style.background="#4CAF50";
      document.getElementById("btn_grids_list").style.background="#4CAF50";
      
      //color new
      document.getElementById(c_btn).style.background="#666";
  }
  
  /* 
    *base on div you want to open, close all others first, then one which has been specified
   */
  function div_selector(c_div){
      //clear previous
      document.getElementById('div_id_newgrid').style.display="none"; 
      document.getElementById('div_id_gridslist').style.display="none";
      document.getElementById('div_id_singlegrid').style.display="none";
      document.getElementById('div_id_loadingdiv').style.display="none";
      document.getElementById('div_id_badgesettinggrid').style.display="none";
      
      //SET CURRENT PAGE
      document.getElementById('curr_page').value=c_div;
      
      //color new
      document.getElementById(c_div).style.display="block";
  }
  
  function spg_newgrid(){ 
    btn_coloring("btn_new_grid");
    div_selector('div_id_newgrid'); 
  }
  
  function sb_webvisitortracker(){
      //btn_coloring("btn_wvt"); 
  }
  
  function spg_gridslist(){
      btn_coloring("btn_grids_list");
      div_selector('div_id_gridslist'); 
  }
  
  
  
  
  
  
  /* this function receives data of particular grid
    from get_grid_details() on serengeti_builder.php
    in form of json when such grid is opened from grids
    list page, then show its details on setting panel
    */
  function opengrid(id){
      document.getElementById("spg_selected_grid_id").value=id;
      div_selector('div_id_loadingdiv');
      
      var data = {
			'action': 'get_grid_details',
			'id': id
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
		    //CONVERT JSON ARRAY TO STRING  
	        var JSONObject3 = JSON.parse(response); 
	 
	        var padding_top=JSONObject3.feedback[0].padding_top; 
	        var padding_right=JSONObject3.feedback[1].padding_right; 
	        var padding_bottom=JSONObject3.feedback[2].padding_bottom; 
	        var padding_left=JSONObject3.feedback[3].padding_left; 
	        
	        var margin_top=JSONObject3.feedback[4].margin_top; 
	        var margin_right=JSONObject3.feedback[5].margin_right; 
	        var margin_bottom=JSONObject3.feedback[6].margin_bottom; 
	        var margin_left=JSONObject3.feedback[7].margin_left; 
	        
	        var gridperrow=JSONObject3.feedback[8].gridperrow;
	        var postcategory=JSONObject3.feedback[9].postcategory;
	        
	        var bbgc=JSONObject3.feedback[10].btn_bg_color;
	        var bfc=JSONObject3.feedback[11].btn_font_color;
	        var bbc=JSONObject3.feedback[12].btn_border_color;
	        var gridstyle=JSONObject3.feedback[13].grid_style;
	        var gridhover=JSONObject3.feedback[14].gridhover;
	        var gridtitlebg=JSONObject3.feedback[15].gridtitlebg;
	        var gridtitlefc=JSONObject3.feedback[16].gridtitlefc;
	        var titlefontfamily=JSONObject3.feedback[17].gridtitlefontfamily;
	        var descrfontfamily=JSONObject3.feedback[18].descrfontfamily;
	        var gridtitlefontsize=JSONObject3.feedback[19].titlefontsize;
	        var desc_fontcolor=JSONObject3.feedback[20].desc_fontcolor;
	        var desc_fontsize=JSONObject3.feedback[21].desc_fontsize;
	        var vanillabadge=JSONObject3.feedback[22].vanillabadge;
	        var vanillabooknow=JSONObject3.feedback[23].vanillabooknow;
	        var strawberrybadge=JSONObject3.feedback[24].strawberrybadge;
	         
            document.getElementById("spg_pd_top").value=padding_top;
            document.getElementById("spg_pd_right").value=padding_right;
            document.getElementById("spg_pd_bottom").value=padding_bottom;
            document.getElementById("spg_pd_left").value=padding_left;
	        
            document.getElementById("spg_mrg_top").value=margin_top;
            document.getElementById("spg_mrg_right").value=margin_right;
            document.getElementById("spg_mrg_bottom").value=margin_bottom;
            document.getElementById("spg_mrg_left").value=margin_left;
            document.getElementById("sel_spg_grid_per_row").value=gridperrow;
            document.getElementById("sel_spg_grid_category").value=postcategory;
            
            document.getElementById("spg_bbgc").value=bbgc;
            document.getElementById("spg_bfc").value=bfc;
            document.getElementById("spg_bbc").value=bbc;
            document.getElementById("spg_title_bc").value=gridtitlebg;
            
            document.getElementById("sel_spg_grid_style").value=gridstyle;
            document.getElementById("div_vanilla_badge").style.display="none";
            document.getElementById("div_btn_save_badges").style.display="none";
	            document.getElementById("div_strawberry_badge").style.display="none";
            
	        if(gridstyle=="mango"){
	            show_style_sample("mango");
	        }else if(gridstyle=="orange"){
	            show_style_sample("orange");
	        }else if(gridstyle=="apple"){
	            show_style_sample("apple");
	        }else if(gridstyle=="coconut"){
	            show_style_sample("coconut");
	        }else if(gridstyle=="vanilla"){
	            show_style_sample("vanilla");
	            document.getElementById("div_vanilla_badge").style.display="block";
                document.getElementById("div_btn_save_badges").style.display="block";
	        }else if(gridstyle=="strawberry"){
	            show_style_sample("strawberry");
	            document.getElementById("div_strawberry_badge").style.display="block";
                document.getElementById("div_btn_save_badges").style.display="block";
	        }
	        
	        
            document.getElementById("sel_spg_grid_hovereffect").value=gridhover;
	        document.getElementById("spg_title_fc").value=gridtitlefc;
	        document.getElementById("sel_spg_grid_fontfamily").value=titlefontfamily;
	        document.getElementById("spg_title_fontsize").value=gridtitlefontsize;
	        document.getElementById("sel_spg_grid_descrfontfamily").value=descrfontfamily;
	        document.getElementById("spg_descr_fc").value=desc_fontcolor;
	        document.getElementById("spg_descr_fs").value=desc_fontsize;
	        document.getElementById("txt_vanilla_badge").value=vanillabadge;
	        document.getElementById("txt_vanilla_booknow").value=vanillabooknow;
	        document.getElementById("txt_strawberry_badge").value=strawberrybadge;
	        
           div_selector('div_id_singlegrid');
	        document.getElementById("div_sub_menu").style.display="block";
               // var element=document.getElementById("btn_grids_list"); 
               // var rect = element.getBoundingClientRect();  
               // document.getElementById("div_basic").style.left=rect.x+"px"; 
                
		});
		
		
      
    
  }
  
  function deletegrid(id){
       
      var data = {
			'action': 'delete_grid',
			'id': id
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
		    //CONVERT JSON ARRAY TO STRING  
	           var base = "admin.php?page=spg-settings"; //Set your base address
               window.location.href = base
		});
    
    
  }
  
  
  /* 
   * base on grid style selected, show its sample (image)
  */
  var prev_sty="orange";
  function show_style_sample(stype){
        document.getElementById("div_"+prev_sty+"_sample").style.display="none";
        document.getElementById("div_"+stype+"_sample").style.display="inline-block";
        prev_sty=stype;
  } 

  
  /*
   * before submiting new grid for creation, this function checks if field is filled okey
  */
  function i_add_grid(){ 
      var gname=document.getElementById("new_spg_name").value;
      if(gname==""){
            alert("Fill grid name");
            return false;
      }else{
            document.getElementById("grdsubmit").disabled=true;
            document.getElementById("grdsubmit").value="pls wait...";
            return true;
      }
  }
  
  /*
   * before submiting details update
  */
  function i_update(){ 
            document.getElementById("upd_submit").disabled=true;
            document.getElementById("upd_submit").value="pls wait...";
            return true; 
  }
 
 
 function spg_grid_badgesettings(){
     div_selector('div_id_badgesettinggrid');
 }
 
 function spg_openbasicsettings(){
     div_selector('div_id_singlegrid');
 }
 
 
 function save_spg_grid_badgesettings(){
     
     var id=document.getElementById("spg_selected_grid_id").value;
     var v_badge=document.getElementById("txt_vanilla_badge").value;
     var v_book=document.getElementById("txt_vanilla_booknow").value;
     var s_badge=document.getElementById("txt_strawberry_badge").value;
     
     var data = {
			'action': 'update_grid_gridproperties',
			'id': id,
			'v_badge': v_badge,
			'op': 'updatebadge',
			'v_book': v_book,
			's_badge': s_badge
		}; 
		
		document.getElementById("upd_badge_submit").disabled=true;
        document.getElementById("upd_badge_submit").value="pls wait...";
        
      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
		    if(response!="ok"){
		        alert(response);
		    }
		    document.getElementById("upd_badge_submit").disabled=false;
            document.getElementById("upd_badge_submit").value="Save Updates";
		});
           
        
  }
  
</script>




<!-- below are all interfaces for the admin page -->

 <div style="width:100%; background:#21668f; height:auto">
    <div class="btn-group" style="display:inline-block;">
            <button id="btn_new_grid" onclick="javascript:spg_newgrid()">New Grid</button> 
    </div>
    <div class="btn-group2" style="display:inline-block" > 
             <button id="btn_grids_list" onclick="javascript:spg_gridslist()" >GridsList</button>  
    </div>
</div>  
 <div id='div_sub_menu' style="display:none; width:100%; height:auto; text-align:center;">
    <div id="div_basic" class="btn-group" style="display:inline-block;">
            <button id="btn_new_grid" onclick="javascript:spg_openbasicsettings()">Basic</button> 
    </div>
    <div class="btn-group2" style="display:inline-block" > 
             <button id="btn_grids_list" onclick="javascript:spg_grid_badgesettings()" >Badges & Buttons</button>  
    </div>
</div> 
 
 
 
<?php
// Generate a custom nonce value.
	$nds_add_meta_nonce = wp_create_nonce( 'nds_add_user_meta_form_nonce_spg' );
?>



<input type="hidden" name="curr_page" id="curr_page" />


 <div id="div_id_loadingdiv" style="padding-top:10%;display:none; margin-top:10px; width:100%; height:auto"> 
 Loading....Pls wait...
 </div>
 
 
<!--************ CREATE NEW GRID DIV ***************************-->
 <div id="div_id_newgrid" style="display:none; margin-top:10px; width:100%; height:auto"> 

        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="nds_add_user_meta_form_spg" onsubmit="return i_add_grid()"> 

                <input type="hidden" name="action" value="nds_form_response">
		        <input type="hidden" name="nds_add_user_meta_nonce_spg" value="<?php echo $nds_add_meta_nonce; ?>" /> 
		
                Grid Name: 
                <input type="text" name="new_spg_name" id="new_spg_name" />

                <p class="submit"><input type="submit" name="grdsubmit" id="grdsubmit" class="button button-primary" value="Submit Grid"></p> 

        </form> 
        
 </div>
<!--************ END CREATE NEW GRID DIV ***************************-->


	 
	
<!--************ 
WEB GRID LIST DIV - this is the div which holds list of grids               ***************************-->
 <div id="div_id_gridslist" style="display:none; margin-top:10px; width:100%; height:auto"> 

        <?php
    	    $pmc_fs_table = new PMC_WP_List_Table();
		 
		    $res='<div class="wrap"><h2>Serengeti Grids List</h2>'; 
		    echo wp_kses( $res, $cdag->spg_get_allowed_html() ) ;
		 
	  	    $pmc_fs_table->prepare_active_items();
	  	    $pmc_fs_table->get_bulk_actions();
	  	    $pmc_fs_table->get_columns();
	  	 
		    $res='<input type="hidden" name="page" value="" />';
		    echo wp_kses( $res, $cdag->spg_get_allowed_html() ) ;
		 
            $pmc_fs_table->views();
         
	        $res='<form method="post">';	
	        $res.=' <input type="hidden" name="page" value="pmc_fs_search">';
	        echo wp_kses( $res, $cdag->spg_get_allowed_html() ) ;
	     
	   	    $pmc_fs_table->search_box( 'search', 'search_id' );
	        $pmc_fs_table->display(); 
	     
		    $res='</form></div>';
		 
            $res.='</div>'; 
            echo wp_kses( $res, $cdag->spg_get_allowed_html() ) ;
        ?>
<!--************ END GRID LIST DIV ***************************-->






<!--************ SINGLE GRID DIV 
this is a div which holds all fields of the admin panel, all color setting, padding, margins etc. are available here
***************************-->

<div id="div_id_singlegrid" style="display:none; margin-top:10px; width:100%; height:auto"> 

    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="nds_add_user_meta_form_spg" onsubmit="return i_update()" > 

            <input type="hidden" name="action" value="nds_form_response">
		    <input type="hidden" name="nds_add_user_meta_nonce_spg_upt" value="<?php echo $nds_add_meta_nonce; ?>" /> 
		
            <input type="hidden" name="spg_wp_url" id="spg_wp_url" value="'.plugins_url('', __FILE__).'" /> 
            <input type="hidden" name="spg_selected_grid_id" id="spg_selected_grid_id" /> 


            MARGIN & PADDING SETTINGS<br><br> 
        <div style="display:inline-block">

                <div>Padding Top:<br><input type="text" name="spg_pd_top" id="spg_pd_top" size="10" placeholder="eg. 5px or 5%" /> 
                </div> 

                <div>Padding Right:<br><input type="text" name="spg_pd_right" id="spg_pd_right" size="10" placeholder="eg. 5px or 5%" /> 
                </div> 
                
                <div>Padding Bottom:<br><input type="text" name="spg_pd_bottom" id="spg_pd_bottom" size="10" placeholder="eg. 5px or 5%" /> 
                </div> 
        
                <div>Padding Left:<br><input type="text" name="spg_pd_left" id="spg_pd_left" size="10" placeholder="eg. 5px or 5%" /> 
                </div> 

        </div> 



        <div style="display:inline-block; margin-left:30px">

                <div>Margin Top:<br><input type="text" name="spg_mrg_top" id="spg_mrg_top" size="10" placeholder="eg. 5px or 5%" /> 
                </div>
    
                <div>Margin Right:<br><input type="text" name="spg_mrg_right" id="spg_mrg_right" size="10" placeholder="eg. 5px or 5%" /> 
                </div> 
    
                <div>Margin Bottom:<br><input type="text" name="spg_mrg_bottom" id="spg_mrg_bottom" size="10" placeholder="eg. 5px or 5%" />
                </div>
                
                <div>Margin Left:<br><input type="text" name="spg_mrg_left" id="spg_mrg_left" size="10" placeholder="eg. 5px or 5%" /> 
                </div>
        </div>



        <div style="display:inline-block; margin-left:30px;"> 
                BUTTONS SETTINGS<br>
                <div>Background Color:<br><input type="text" name="spg_bbgc" id="spg_bbgc" size="10" placeholder="eg. #fff" /> 
                </div>
        
                <div>Font Color:<br><input type="text" name="spg_bfc" id="spg_bfc" size="10" placeholder="eg. #fff" /> 
                </div>
        
                <div>Border Color:<br><input type="text" name="spg_bbc" id="spg_bbc" size="10" placeholder="eg. #fff" /> 
                </div>
        </div> 
 
        <br><br> 
        
        <div style="display:inline-block; background:#ccc; padding:5px; border-radius:10px;">
            <br>GRID SETTINGS<br>
                 <div>Grids Per Row:<br> 
                    <select name="sel_spg_grid_per_row" id="sel_spg_grid_per_row">
                        <option value="">-- Select --</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>


            <?php
                $args = array(
                'child_of'            => 0,
                'current_category'    => 0,
                'depth'               => 0,
                'echo'                => 1,
                'exclude'             => '',
                'exclude_tree'        => '',
                'feed'                => '',
                'feed_image'          => '',
                'feed_type'           => '',
                'hide_empty'          => 0,
                'hide_title_if_empty' => false,
                'hierarchical'        => true,
                'order'               => 'ASC',
                'orderby'             => 'name',
                'separator'           => '<br />',
                'show_count'          => 0,
                'show_option_all'     => '',
                'show_option_none'    => __( 'No categories' ),
                'style'               => 'list',
                'taxonomy'            => 'category',
                'title_li'            => __( 'Categories' ),
                'use_desc_for_title'  => 1,
                );
 
            ?>

                <div>Post Category:<br> 
                    <select name="sel_spg_grid_category" id="sel_spg_grid_category">
                        <option value="">-- Select --</option> 
                        <?php
                         $categories = get_categories($args);
                        foreach($categories as $category) {
                             echo '<option value="' . $category->name . '">' . $category->name . '</option>'; 
                         }
                        ?>
                    </select>

                </div>
                <div>Grids Style:<br> 
                        <select name="sel_spg_grid_style" id="sel_spg_grid_style" onchange="javascript:show_style_sample(this.value)">
                            <option value="">-- Select --</option>
                            <option value="orange">Orange Grid Style</option>
                            <option value="mango">Mango Grid Style</option>
                            <option value="apple">Apple Grid Style</option>
                            <option value="coconut">Coconut Grid Style</option>
                            <option value="vanilla">Vanilla Grid Style</option>
                            <option value="strawberry">StrawBerry Style</option>  
                        </select>
                </div>
        </div><!-- end grid width settings -->
    
    
         <!-- GRID TYPE SELECTION SECTION-->
        <div style="display:inline-block; margin:0px 0px 0px 15px">
             
             <div style="display:inline-block">
                    <div>Thumb HoverEffect:<br> 
                        <select name="sel_spg_grid_hovereffect" id="sel_spg_grid_hovereffect">
                            <option value="">-- Select --</option>
                            <option value="none">No Effect</option>
                            <option value="zoomin">Zoom IN</option> 
                            <option value="zoomin_divshadow">ZoomIN+Shadow</option> 
                        </select>
                    </div>
                    <div>Title bg color:<br> 
                        <input type="text" name="spg_title_bc" id="spg_title_bc" size="20" placeholder="eg. #fff" /> 
                    </div>
                    <div>Title font color:<br> 
                        <input type="text" name="spg_title_fc" id="spg_title_fc" size="20" placeholder="eg. #333" /> 
                    </div>
                    <div>Title Font Family:<br> 
                        <select name="sel_spg_grid_fontfamily" id="sel_spg_grid_fontfamily">
                            <option value="">-- Select --</option>
                            <?php
                                $cdag=new sgPlugin_cdag();
                                echo($cdag->get_google_fonts());
                            ?>
                        </select>
                    </div><!---->
             </div>
           
             
             
            <div style="display:inline-block; margin-left:20px;">
                Sample<br> 
                <div style="display:none;" id="div_orange_sample">
                    <?php
                        $img=plugins_url('images/thumbsample.png', __FILE__);
                        echo '<img src="'.$img.'" height="150">'; 
                    ?>
                </div>
             
                <div style="display:none" id="div_mango_sample">
                    <?php
                        $img=plugins_url('images/mangothumbsample.png', __FILE__);
                        echo '<img src="'.$img.'" height="150">'; 
                    ?>
                </div>
             
                 <div style="display:none" id="div_apple_sample">
                    <?php
                        $img=plugins_url('images/applethumbsample.png', __FILE__);
                        echo '<img src="'.$img.'" height="150">'; 
                    ?>
                 </div>
             
                 <div style="display:none" id="div_coconut_sample">
                    <?php
                        $img=plugins_url('images/coconutthumbsample.png', __FILE__);
                        echo '<img src="'.$img.'" height="150">'; 
                    ?>
                 </div>
             
                 <div style="display:none" id="div_vanilla_sample">
                    <?php
                        $img=plugins_url('images/vanillasample.jpg', __FILE__);
                        echo '<img src="'.$img.'" height="150">'; 
                    ?>
                 </div>
             
                 <div style="display:none" id="div_strawberry_sample">
                    <?php
                        $img=plugins_url('images/strawberrysample.jpg', __FILE__);
                        echo '<img src="'.$img.'" height="150">'; 
                    ?>
                 </div>
            </div>
            
            
             
            <div style="display:inline-block; margin-left:20px;"> 
                    <div>Title font Size:<br> 
                        <input type="text" name="spg_title_fontsize" id="spg_title_fontsize" size="20" placeholder="eg. 12px" /> 
                    </div>
                    <div>Description Font family:<br> 
                        <select name="sel_spg_grid_descrfontfamily" id="sel_spg_grid_descrfontfamily">
                            <option value="">-- Select --</option>
                            <?php
                                $cdag=new sgPlugin_cdag();
                                echo($cdag->get_google_fonts());
                            ?>
                        </select>
                    </div>
                    <div>Description Font color:<br> 
                        <input type="text" name="spg_descr_fc" id="spg_descr_fc" size="20" placeholder="eg. #fff" /> 
                    </div>
                    <div>Description Font size:<br> 
                        <input type="text" name="spg_descr_fs" id="spg_descr_fs" size="20" placeholder="eg. 12px" /> 
                    </div>
            </div>
            
        
        </div>
        <!-- END GRID WIDTH SELECTION SECTION -->
    
        <p class="submit"><input type="submit" name="updl_submit" id="upd_submit" class="button button-primary" value="Submit Updates"></p>

    </form>
</div>
<!--************ END SINGLE GRID DIV ***************************/-->


<!-- ***************   BADGES & BUTTONS SETTING ******************-->
<div id="div_id_badgesettinggrid" style="display:none; margin-top:10px; width:100%; height:auto"> 
   
    <form action="javascript:void(0)" method="post" name="frm_updatebadges" id="frm_updatebadges" onsubmit="return save_spg_grid_badgesettings()" > 

        <div id="div_vanilla_badge"><br> BADGES & BUTTONS SETTING<br>
            <div>Vanilla Badge:<br> 
                <input type="text" name="txt_vanilla_badge" id="txt_vanilla_badge" placeholder="default: NEW">
            </div>
        
            <div >Vanilla BookNow:<br> 
                <input type="text" name="txt_vanilla_booknow" id="txt_vanilla_booknow" placeholder="default: BOOKNOW">
            </div>
	    </div>
	    
        <div id="div_strawberry_badge"><br> BADGES & BUTTONS SETTING<br>
            <div>Strawberry Badge:<br> 
                <input type="text" name="txt_strawberry_badge" id="txt_strawberry_badge" placeholder="default: SALE">
            </div>
         
	    </div>
	
	    <div id="div_btn_save_badges">
	        <p class="submit"><input type="submit" name="upd_badge_submit" id="upd_badge_submit" class="button button-primary" value="Save Updates"></p>
	    </div>
	
	</form>
    
</div>
<!-- ***************  END BADGES & BUTTONS SETTING ***************-->

<?php
  
  }
  
}
?>