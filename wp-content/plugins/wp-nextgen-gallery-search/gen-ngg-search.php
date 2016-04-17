<?php
/**
Plugin Name: WP NextGen Gallery Search
Description: Enables Search functionality for the images of NextGEN Gallery
Author: UpScaleThought
Version: 1.0
Author URI: http://www.upscalethought.com/
Plugin URI: http://www.upscalethought.com/
Text Domain: gen-wp-ngg-search
Domain Path: /i18n/languages/
 
Copyright (C) 2015 UpScaleThought
@package NextGEN Gallery
**/

define('GEN_USTS_NGGS_PLUGIN_URL', plugins_url('',__FILE__));
define("GEN_USTS_BASE_URL", WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)));
define('GEN_USTS_NGGS_DIR', plugin_dir_path(__FILE__) );
//$page = get_page_by_path($slug);
//$title = get_the_title($page->ID);
/*$usts_ngg_page = get_posts(
    array(
        'name'      => 'gen-gallery-search',
        'post_type' => 'page'
    )
);*/
$usts_ngg_page = get_page_by_path('gen-gallery-search');
//die(print_r($pg));
//$usts_ngg_page = get_page_by_title('Gen Ngg Gallery Search');
$usts_ngg_page_id = 0;
if($usts_ngg_page){
	$usts_ngg_page_id = $usts_ngg_page->ID;
}
define('GEN_USTS_NGG_GALLERYSEARCH_PAGEID',$usts_ngg_page_id);

include_once('includes/create_page.php');
include_once('includes/usts_ngg_init.php');
include_once('includes/search_ngg_image.php');

add_action('admin_menu', 'gen_nextgen_plugin_admin_menu');
function gen_nextgen_plugin_admin_menu(){
	add_object_page('WP NextGen Gallery Search', 'WP NextGen Gallery Search', 'publish_posts', 'custom_gallerysearch', 'gen_nextgensearch_settings_menu');
}
function gen_nextgensearch_settings_menu(){
  include_once('includes/nextgen_gallery_features.php');
}
function gen_nextgen_gallery_search_add_menu(){
  add_submenu_page( 'custom_gallerysearch', 'Pro Version', 'Pro Vesrion', 'manage_options', 'pro-version-menu', 'pro_version_settings');
}
function pro_version_settings(){
  include_once('includes/gallerysearch_pro_version.php');
}
add_action('admin_menu','gen_nextgen_gallery_search_add_menu');

function gen_nextgengallerysearchcss_front(){
		wp_register_style( 'add_style_front_css',plugins_url('/assets/css/style.css',__FILE__));
    wp_enqueue_style( 'add_style_front_css');
}
add_action('wp_enqueue_scripts','gen_nextgengallerysearchcss_front');

register_activation_hook( __FILE__, 'gen_usts_nggsearch_install' );
register_deactivation_hook( __FILE__, 'gen_usts_nggsearch_uninstall' );

