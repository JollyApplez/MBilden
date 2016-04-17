<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/*
Plugin Name: NextGEN Gallery Search
Plugin URI: http://wordpress.org/plugins/nextgen-gallery-search-galleries/
Description: Adds a gallery search option to the NextGEN galleries menu. <strong>Please notice: </strong>you can only search galleries with this plugin. You can search for images by using the search option in the top right on the 'Manage Galleries' page.
Author: By the WWW...
Author URI: http://bythewww.com/
Version: 2.12

Copyright (c) 2013 By the WWW...

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/**
 * Add submenu to the NextGEN gallery menu.
 **/
function add_wp_ngg_search() {
/* 	
 * We HAVE to create a new menu in order to get the submenu working, but we fix that by making the initial menu invisible with CSS
*/
	add_menu_page('Search a Gallery in NextGEN Galleries', __('Search Galleries','ngg-search'), 'upload_files', 'ngg-search', 'wp_ngg_search', plugin_dir_url( __FILE__ ).'icon.png');
	add_submenu_page('nextgen-gallery', 'Search Galleries', 'Search Galleries', 'upload_files', 'ngg-search', wp_ngg_search);
}

add_action('admin_menu', 'add_wp_ngg_search');



/**
 * Start the function
 **/

function wp_ngg_search() {
?>
<!-- There was not a lot to style so we use an internal stylesheet -->
<style> 
#search { float: left; margin: 0 0 23px 0; }
#help strong {font-size: 15px; }
#help { margin: -8px 0 0 21px; float: left; background: #FFFBCC; border: 1px solid #E6DB55; width: 398px; padding: 10px; border-radius: 5px; }
#toplevel_page_ngg-search { display: none; }
</style>

<!-- Start the table where the results will be shown -->
<div class="wrap">
 <table class="wp-list-table widefat fixed" cellspacing="0">
  <thead>
   <tr>
    <th id="id" class="manage-column column-id sortable asc" style="width:45px" scope="col">
     <span style="padding: 7px 7px 8px;">ID</span>
    </th>
    <th id="title" class="manage-column column-title sortable desc" style="width:30%" scope="col">
     <span style="padding: 7px 7px 8px;">Gallery</span>
    </th>
    <th id="description" class="manage-column column-description" style="width:65%" scope="col">Description</th>
   </tr>
  </thead>	
  <tfoot>
   <tr>
    <th id="id" class="manage-column column-id sortable asc" style="width:45px" scope="col">
     <span style="padding: 7px 7px 8px;">ID</span>
    </th>
    <th id="title" class="manage-column column-title sortable desc" style="width:30%" scope="col">
     <span style="padding: 7px 7px 8px;">Gallery</span>
    </th>
    <th id="description" class="manage-column column-description" style="width:65%" scope="col">Description</th>
   </tr>
  </tfoot>	
 <tbody id="the-list">

 <div id="icon-nextgen-gallery" class="icon32"></div>
 <h2>Search Galleries</h2><br>

 <!-- Here we begin our search form -->
 <form action="admin.php?page=ngg-search" method="post">
    <div id="search">

 	<!-- Checkbox for the 'add description' option -->
    <input name="description" id="description" style="margin: 0 5px 0 0;" type="checkbox" value="description" <?php if(isset($_POST['description'])) echo "checked='checked'"; ?>>Also search in description</br>

 	<!-- Search input field -->
    <input name="find" id="find" maxlength="255" title="" style="margin-right: 3px; padding: 4px 4px 5px; width: 300px;" value="<?php echo ($_POST['find']);?>" type="text">
  
  	<!--Break multiple inputs into a single variables -->
    <?php if (isset($_POST['submit'])) {
    	$string = $_POST['find'];
		/* We don't want to use explode here, just in case there are extra spaces in the search query */
    	$parts = preg_split('/\s+/', $string);
    	/* Find the last word in the query */
		$last_word = end($parts);
		
		/*
		 *  Here we create all the search queries 
		 *	Each word in the search input field will get it's own LIKE query for mySQL, so it doesn't matter how many words are used
		 */
		foreach($parts as $part) {
			/* Check to see if it's the last word in the query */
			if($part == $last_word) {
				$title .= 'title LIKE \'%'.$part.'%\'';
				$desc .= 'galdesc LIKE \'%'.$part.'%\'';
			} else {
				$title .= 'title LIKE \'%'.$part.'%\' OR ';
				$desc .= 'galdesc LIKE \'%'.$part.'%\' OR ';
			}
		}
    }
    ?>
	<input class="button" name="submit" type="submit" value="Search" style="margin-top: 5px;">
	</div>
 </form>

<?php

global $wpdb;
/*
 *  Get the prefix for the database
 */
$gallery = $wpdb->prefix . 'ngg_gallery';

/*
 *  Check if there is input and if the description checkbox is on, and show results
 */
if ($string != '') {
	if(isset($_POST['description'])) {
			/* if the 'add description' checkbox is on we use this line */
			$result = $wpdb->get_results( "SELECT * FROM ". $gallery ." WHERE (". $title . " OR " . $desc . ") "); 
		} else {
			/* if the 'add description' checkbox is off we use this line */
			$result  = $wpdb->get_results( "SELECT * FROM ". $gallery ." WHERE (". $title . ") "); 
		}

if (!empty($result)) {
	foreach($result as $row) {
		echo '<tr id="gallery-' .$row->gid. '">';
		echo '<td class="id column-id">' .$row->gid. '</td>';
		echo '<td class="title column-title">';
		echo '<a class="edit" title="Bewerken" href="admin.php?page=nggallery-manage-gallery&mode=edit&gid=' .$row->gid. '">' .$row->title. '</a>';
		echo '<div class="row-actions"></div>';
		echo '</td>';
		echo '<td class="description column-description">' .$row->galdesc. '</td>';
		echo '</tr>';
		}
	} else {
	 	echo '<div id="help">No results. Please try again with part of the name.<br>For instance: <strong>int</strong> will find <strong>int</strong>ernet, but also w<strong>int</strong>er, ballpo<strong>int</strong>, sa<strong>int</strong>s etc.</div><br>';
	}
}
?>
  </tbody>
 </table>
</div>

<?php }

/****************************************************************************************
* Check to make sure NextGEN Gallery is installed and activated.
* If not, show an admin notification to assist with the installation/activation process.
****************************************************************************************/
function nggs_nextgen_installed_and_activated_check() {
	global $pagenow;
	if ($pagenow == 'plugins.php' || isset($_GET['page'])) {
        
		// check if nextgen gallery is installed		
		if (!get_plugins('/nextgen-gallery')) {

			$nggs_nextgen_check = '<div class="error"><p>';
			$nggs_nextgen_check.= '<b>NextGEN Gallery Search Error:</b><br />';
			$nggs_nextgen_check.= 'NextGEN Gallery Search is an add-on for the NextGEN Gallery WordPress plugin, but <b>NextGEN Gallery is not <i>installed</i>.</b><br />';
			$nggs_nextgen_check.= 'Please <a href="' . get_admin_url('', 'plugin-install.php?tab=search&s=NextGEN+Gallery') . '">download it here automatically</a> ';
			$nggs_nextgen_check.= 'or <a href="http://wordpress.org/extend/plugins/nextgen-gallery">manually from the WordPress repository</a>.';
			$nggs_nextgen_check.= '</p></div>';
			
			echo $nggs_nextgen_check;
		}
		
		// check if nextgen gallery is installed and activated
		if (get_plugins('/nextgen-gallery') && !is_plugin_active('nextgen-gallery/nggallery.php')) {

			$nggs_nextgen_check = '<div class="error"><p>';
			$nggs_nextgen_check.= '<b>NextGEN Gallery Search Error Notification:</b><br />';
			$nggs_nextgen_check.= 'NextGEN Gallery Search is an add-on for the NextGEN Gallery WordPress plugin, but <b>NextGEN Gallery is not <i>activated</i>.</b><br />';
			$nggs_nextgen_check.= 'Please click the "Activate" link under the "NextGEN Gallery" item on <a href="' . get_admin_url('', 'plugins.php') . '">your plugins page</a>.';
			$nggs_nextgen_check.= '</p></div>';
			
			echo $nggs_nextgen_check;
		}
	}		
}
add_action('admin_notices', 'nggs_nextgen_installed_and_activated_check');

?>