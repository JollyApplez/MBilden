<?php 
function gen_usts_gallery_image_search($atts){
	global $table_prefix,$wpdb;
	$tag = "";
	$sql_ngg_pictures = "";
	if(isset($_REQUEST['tag'])){
		$tag = $_REQUEST['tag'];
		$sql_ngg_pictures = "select * from ".$table_prefix."ngg_pictures nggp inner join ".$table_prefix."ngg_gallery ngg on nggp.galleryid = ngg.gid where nggp.alttext like '%". $tag ."%'";
	}
	if($_POST){
		if(isset($_REQUEST['txtnggSearchtag'])){
		   $tag = $_REQUEST['txtnggSearchtag'];
		}
		$sql_ngg_pictures = "select * from ".$table_prefix."ngg_pictures nggp inner join ".$table_prefix."ngg_gallery ngg on nggp.galleryid = ngg.gid where nggp.alttext like '%". $tag ."%'";
	}
	$pictures = $wpdb->get_results($sql_ngg_pictures);
	$output .= '
	<div id="ngg_picture_gallery">';
	$output .= '<form id="frmgallerysearch" action="'.get_option("siteurl").'/?page_id='.GEN_USTS_NGG_GALLERYSEARCH_PAGEID.'" method="post">
				  <!-- <input type="text" id="txtnggSearchtag" name="txtnggSearchtag" value="" style="height:30px" />
				   <input type="submit" id="btnnggsearch" name="btnnggsearch" value="Image Search" style="width:115px;height:40px;" placeholder="Search Gallery Image" />-->
				</form>';
	$output .= '<div id="wpngg_img_search_result" class="thumbs">
				  <div style="float:left;"> 
					';
	if(count($pictures)>0){				
		foreach($pictures as $picture){
			$output .= '<div style="float:left;">
							<a href="'.get_option('siteurl').'/wp-content/gallery/'.$picture->name.'/'.$picture->filename.'" title="'.$picture->alttext.'">
								<img class="wpnggimgcls" src="'.get_option('siteurl').'/wp-content/gallery/'.$picture->name.'/thumbs/thumbs_'.$picture->filename.'" style="" /> 
							</a>
							<div>'.$picture->alttext.'</div>
						</div>';
		}
	}
	$output .= '</div>
			</div>';
	$output .= '</div>';
	return $output;
	
}	
add_shortcode('gen-usts-gallery-search','gen_usts_gallery_image_search');

function gen_usts_gallery_image_search_box(){
	if($_POST){
		$box_tag = "";
		if(isset($_REQUEST['btnnggsearch_box'])){
			$box_tag = $_REQUEST['txtnggSearchtag_box'];	
		} 
		wp_redirect( get_option("siteurl").'/?page_id='.GEN_USTS_NGG_GALLERYSEARCH_PAGEID.'&tag='.$box_tag); exit; 
	}	
	$output_box = '<form id="frmgallerysearch_box" action="'.get_option("siteurl").'/?page_id='.GEN_USTS_NGG_GALLERYSEARCH_PAGEID.'" method="post">
				   <input type="text" id="txtnggSearchtag_box" name="txtnggSearchtag_box" value="" style="height:30px" />
				   <input type="submit" id="btnnggsearch_box" name="btnnggsearch_box" value="Gallery Search" style="width:115px;height:40px;" placeholder="Search Gallery Image" />
				   <input type="hidden" value="fromwidget" name="from_widget" id="from_widget"/>
				</form>';
	return $output_box;			
}

add_shortcode('gen-usts-gallery-search-box','gen_usts_gallery_image_search_box');