<?php
class sgPlugin_cdag{
    
function spg_get_allowed_html_v2(){
    	$allowedposttags = array(
		'address'    => array(),
		'a'          => array(
			'href'     => true,
			'rel'      => true,
			'rev'      => true,
			'name'     => true,
			'target'   => true,
			'download' => array(
				'valueless' => 'y',
			),
		),
		'abbr'       => array(),
		'acronym'    => array(),
		'area'       => array(
			'alt'    => true,
			'coords' => true,
			'href'   => true,
			'nohref' => true,
			'shape'  => true,
			'target' => true,
		),
		'article'    => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'aside'      => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'audio'      => array(
			'autoplay' => true,
			'controls' => true,
			'loop'     => true,
			'muted'    => true,
			'preload'  => true,
			'src'      => true,
		),
		'b'          => array(),
		'bdo'        => array(
			'dir' => true,
		),
		'big'        => array(),
		'blockquote' => array(
			'cite'     => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'br'         => array(),
		'button'     => array(
			'disabled' => true,
			'name'     => true,
			'type'     => true,
			'value'    => true,
		),
		'caption'    => array(
			'align' => true,
		),
		'cite'       => array(
			'dir'  => true,
			'lang' => true,
		),
		'code'       => array(),
		'col'        => array(
			'align'   => true,
			'char'    => true,
			'charoff' => true,
			'span'    => true,
			'dir'     => true,
			'valign'  => true,
			'width'   => true,
		),
		'colgroup'   => array(
			'align'   => true,
			'char'    => true,
			'charoff' => true,
			'span'    => true,
			'valign'  => true,
			'width'   => true,
		),
		'del'        => array(
			'datetime' => true,
		),
		'dd'         => array(),
		'dfn'        => array(),
		'details'    => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'open'     => true,
			'xml:lang' => true,
		),
		'div'        => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'dl'         => array(),
		'dt'         => array(),
		'em'         => array(),
		'fieldset'   => array(),
		'figure'     => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'figcaption' => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'font'       => array(
			'color' => true,
			'face'  => true,
			'size'  => true,
		),
		'footer'     => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'h1'         => array(
			'align' => true,
		),
		'h2'         => array(
			'align' => true,
		),
		'h3'         => array(
			'align' => true,
		),
		'h4'         => array(
			'align' => true,
		),
		'h5'         => array(
			'align' => true,
		),
		'h6'         => array(
			'align' => true,
		),
		'header'     => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'hgroup'     => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'hr'         => array(
			'align'   => true,
			'noshade' => true,
			'size'    => true,
			'width'   => true,
		),
		'i'          => array(),
		'img'        => array(
			'alt'      => true,
			'align'    => true,
			'border'   => true,
			'height'   => true,
			'hspace'   => true,
			'longdesc' => true,
			'vspace'   => true,
			'src'      => true,
			'usemap'   => true,
			'width'    => true,
		),
		'ins'        => array(
			'datetime' => true,
			'cite'     => true,
		),
		'kbd'        => array(),
		'label'      => array(
			'for' => true,
		),
		'legend'     => array(
			'align' => true,
		),
		'li'         => array(
			'align' => true,
			'value' => true,
		),
		'map'        => array(
			'name' => true,
		),
		'mark'       => array(),
		'menu'       => array(
			'type' => true,
		),
		'nav'        => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'p'          => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'pre'        => array(
			'width' => true,
		),
		'q'          => array(
			'cite' => true,
		),
		's'          => array(),
		'samp'       => array(),
		'span'       => array(
			'dir'      => true,
			'align'    => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'section'    => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'small'      => array(),
		'strike'     => array(),
		'strong'     => array(),
		'sub'        => array(),
		'summary'    => array(
			'align'    => true,
			'dir'      => true,
			'lang'     => true,
			'xml:lang' => true,
		),
		'sup'        => array(),
		'table'      => array(
			'align'       => true,
			'bgcolor'     => true,
			'border'      => true,
			'cellpadding' => true,
			'cellspacing' => true,
			'dir'         => true,
			'rules'       => true,
			'summary'     => true,
			'width'       => true,
		),
		'tbody'      => array(
			'align'   => true,
			'char'    => true,
			'charoff' => true,
			'valign'  => true,
		),
		'td'         => array(
			'abbr'    => true,
			'align'   => true,
			'axis'    => true,
			'bgcolor' => true,
			'char'    => true,
			'charoff' => true,
			'colspan' => true,
			'dir'     => true,
			'headers' => true,
			'height'  => true,
			'nowrap'  => true,
			'rowspan' => true,
			'scope'   => true,
			'valign'  => true,
			'width'   => true,
		),
		'textarea'   => array(
			'cols'     => true,
			'rows'     => true,
			'disabled' => true,
			'name'     => true,
			'readonly' => true,
		),
		'tfoot'      => array(
			'align'   => true,
			'char'    => true,
			'charoff' => true,
			'valign'  => true,
		),
		'th'         => array(
			'abbr'    => true,
			'align'   => true,
			'axis'    => true,
			'bgcolor' => true,
			'char'    => true,
			'charoff' => true,
			'colspan' => true,
			'headers' => true,
			'height'  => true,
			'nowrap'  => true,
			'rowspan' => true,
			'scope'   => true,
			'valign'  => true,
			'width'   => true,
		),
		'thead'      => array(
			'align'   => true,
			'char'    => true,
			'charoff' => true,
			'valign'  => true,
		),
		'title'      => array(),
		'tr'         => array(
			'align'   => true,
			'bgcolor' => true,
			'char'    => true,
			'charoff' => true,
			'valign'  => true,
		),
		'track'      => array(
			'default' => true,
			'kind'    => true,
			'label'   => true,
			'src'     => true,
			'srclang' => true,
		),
		'tt'         => array(),
		'u'          => array(),
		'ul'         => array(
			'type' => true,
		),
		'ol'         => array(
			'start'    => true,
			'type'     => true,
			'reversed' => true,
		),
		'var'        => array(),
		'video'      => array(
			'autoplay' => true,
			'controls' => true,
			'height'   => true,
			'loop'     => true,
			'muted'    => true,
			'poster'   => true,
			'preload'  => true,
			'src'      => true,
			'width'    => true,
		),
		'a'          => array(
			'href'  => true,
			'title' => true,
		),
		'abbr'       => array(
			'title' => true,
		),
		'acronym'    => array(
			'title' => true,
		),
		'b'          => array(),
		'blockquote' => array(
			'cite' => true,
		),
		'cite'       => array(),
		'code'       => array(),
		'del'        => array(
			'datetime' => true,
		),
		'em'         => array(),
		'i'          => array(),
		'q'          => array(
			'cite' => true,
		),
		's'          => array(),
		'strike'     => array(),
		'strong'     => array(),
        'style' => array(),
		'button' => array(),
		'onclick' => array(),
		'javascript' => array(),
		'input' => array(),
		'img' => array(
			'alt'    => array(),
			'class'  => array(),
			'height' => array(),
			'src'    => array(),
			'width'  => array(),
		),
		'div' => array(
			'style'    => array(),
			'background'  => array(),
			'height' => array(),
			'class'    => array(),
			'width'  => array(),
		)
		); 
	
	return $allowedposttags ;
}

    
function spg_get_allowed_html(){
$allowed_tags_spg = array(
		'a' => array(
			'class' => array(),
			'href'  => array(),
			'rel'   => array(),
			'title' => array(),
		),
		'abbr' => array(
			'title' => array(),
		),
		'b' => array(),
		'blockquote' => array(
			'cite'  => array(),
		),
		'cite' => array(
			'title' => array(),
		),
		'code' => array(),
		'del' => array(
			'datetime' => array(),
			'title' => array(),
		),
		'dd' => array(),
		'div' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'dl' => array(),
		'dt' => array(),
		'em' => array(),
		'h1' => array(),
		'h2' => array(),
		'h3' => array(),
		'h4' => array(),
		'h5' => array(),
		'h6' => array(),
		'i' => array(),
		'br' => array(),
		'style' => array(),
		'button' => array(
			'disabled' => array(),
			'name'     => array(),
			'id'     => array(),
			'type'     => array(),
			'style'    => array(),
			'display'    => array(),
			'onclick'    => array(),
			),
		'onclick' => array(), 
		'input' => array(
			'type' => array(),
			'name' => array(),
			'id' => array(),
			'value' => array(),
			'placeholder' => array(),
			'size' => array(),
			'class' => array(),
			),
		'img' => array(
			'alt'    => array(),
			'class'  => array(),
			'height' => array(),
			'src'    => array(),
			'width'  => array(),
		),
		'div' => array(
			'id'    => array(),
			'style'    => array(),
			'background'  => array(),
			'height' => array(),
			'class'    => array(),
			'width'  => array(),
			'margin-top'  => array(),
			'margin-left'  => array(),
			'margin-bottom'  => array(),
			'margin-right'  => array(),
		),
		'select' => array(
			'id'    => array(),
			'style'    => array(),
			'name'  => array(),
			'height' => array(),
			'class'    => array(),
			'width'  => array(), 
		),
		'option' => array(
			'value'    => array() 
		),
		'form' => array(
			'action'    => array(),
			'name'  => array(),
			'id' => array(),
			'method'    => array(), 
		),
		'li' => array(
			'class' => array(),
		),
		'ol' => array(
			'class' => array(),
		),
		'p' => array(
			'class' => array(),
		),
		'q' => array(
			'cite' => array(),
			'title' => array(),
		),
		'span' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'strike' => array(),
		'strong' => array(),
		'ul' => array(
			'class' => array(),
		),
	);
	
	
	return $allowed_tags_spg;
}

 
 function get_google_fonts() {
     $fonts='<option value="\'Abel\',sans-serif">Abel,sans-serif</option>
             <option value="\'Libre Franklin\',sans-serif">Libre Franklin, sans-serif</option>
             <option value="\'Montserrat\', sans-serif">Montserrat, sans-serif</option>';
             
     return $fonts;
 }
 
 
/*
 * update grid details once submit is clicked
*/ 
 function ginsert_data_val($gridid,$val,$label) {
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


}