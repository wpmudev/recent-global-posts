<?php
/*
Plugin Name: Recent Posts
Plugin URI:
Description:
Author: Andrew Billits (Incsub)
Version: 2.0
Author URI:
WDP ID: 75
*/

/*
Copyright 2007-2009 Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
Usage:
display_recent_posts(NUMBER,TITLE_CHARACTERS,CONTENT_CHARACTERS,TITLE_CONTENT_DIVIDER,TITLE_BEFORE,TITLE_AFTER,GLOBAL_BEFORE,GLOBAL_AFTER,BEFORE,AFTER,TITLE_LINK,SHOW_AVATARS,AVATAR_SIZE);

Ex:
display_recent_posts(10,40,150,'<br />','<strong>','</strong>','<ul>','</ul>','<li>','</li>','yes','yes',16);
*/
/* -------------------- Update Notifications Notice -------------------- */
if ( !function_exists( 'wdp_un_check' ) ) {
  add_action( 'admin_notices', 'wdp_un_check', 5 );
  add_action( 'network_admin_notices', 'wdp_un_check', 5 );
  function wdp_un_check() {
    if ( !class_exists( 'WPMUDEV_Update_Notifications' ) && current_user_can( 'edit_users' ) )
      echo '<div class="error fade"><p>' . __('Please install the latest version of <a href="http://premium.wpmudev.org/project/update-notifications/" title="Download Now &raquo;">our free Update Notifications plugin</a> which helps you stay up-to-date with the most stable, secure versions of WPMU DEV themes and plugins. <a href="http://premium.wpmudev.org/wpmu-dev/update-notifications-plugin-information/">More information &raquo;</a>', 'wpmudev') . '</a></p></div>';
  }
}
/* --------------------------------------------------------------------- */

//------------------------------------------------------------------------//
//---Config---------------------------------------------------------------//
//------------------------------------------------------------------------//

//------------------------------------------------------------------------//
//---Hook-----------------------------------------------------------------//
//------------------------------------------------------------------------//

//------------------------------------------------------------------------//
//---Functions------------------------------------------------------------//
//------------------------------------------------------------------------//

//------------------------------------------------------------------------//
//---Output Functions-----------------------------------------------------//
//------------------------------------------------------------------------//

function display_recent_posts($tmp_number,$tmp_title_characters = 0,$tmp_content_characters = 0,$tmp_title_content_divider = '<br />',$tmp_title_before,$tmp_title_after,$tmp_global_before,$tmp_global_after,$tmp_before,$tmp_after,$tmp_title_link = 'no',$tmp_show_avatars = 'yes', $tmp_avatar_size = 16){
	global $wpdb;
	$query = "SELECT * FROM " . $wpdb->base_prefix . "site_posts WHERE blog_public = '1' ORDER BY post_published_stamp DESC LIMIT " . $tmp_number;
	$tmp_posts = $wpdb->get_results( $query, ARRAY_A );

	if (count($tmp_posts) > 0){
		echo $tmp_global_before;
		$default_avatar = get_option('default_avatar');
		foreach ($tmp_posts as $tmp_post){
			echo $tmp_before;
			if ( $tmp_title_characters > 0 ) {
				echo $tmp_title_before;
				if ( $tmp_show_avatars == 'yes' ) {
					if ( $tmp_title_link == 'no' ) {
						echo get_avatar( $tmp_post['post_author'], $tmp_avatar_size, $default_avatar) . ' ' . substr($tmp_post['post_title'],0,$tmp_title_characters);
					} else {
						echo get_avatar( $tmp_post['post_author'], $tmp_avatar_size, $default_avatar) . ' <a href="' . $tmp_post['post_permalink'] . '" style="text-decoration:none;">' . substr($tmp_post['post_title'],0,$tmp_title_characters) . '</a>';
					}
				} else {
					if ( $tmp_title_link == 'no' ) {
						echo substr($tmp_post['post_title'],0,$tmp_title_characters);
					} else {
						echo '<a href="' . $tmp_post['post_permalink'] . '" style="text-decoration:none;">' . substr($tmp_post['post_title'],0,$tmp_title_characters) . '</a>';
					}
				}
				echo $tmp_title_after;
			}
			echo $tmp_title_content_divider;
			if ( $tmp_content_characters > 0 ) {
				echo substr($tmp_post['post_content_stripped'],0,$tmp_content_characters);
			}
			echo $tmp_after;
		}
		echo $tmp_global_after;
	}
}


//------------------------------------------------------------------------//
//---Page Output Functions------------------------------------------------//
//------------------------------------------------------------------------//

//------------------------------------------------------------------------//
//---Support Functions----------------------------------------------------//
//------------------------------------------------------------------------//

?>