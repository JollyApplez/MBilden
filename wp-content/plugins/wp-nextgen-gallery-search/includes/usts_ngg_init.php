<?php
function gen_usts_nggsearch_install() {
   global $table_prefix, $wpdb;
   //Page creation for Shopping Cart and Checkout Page
   $usts_ngg_page_id = gen_usts_programmatically_create_page('Gen Ngg Gallery Search','gen-gallery-search','[gen-usts-gallery-search-box][gen-usts-gallery-search]','page');
	 
	 
}
function gen_usts_nggsearch_uninstall(){
	wp_delete_post(GEN_USTS_NGG_GALLERYSEARCH_PAGEID,1);
}