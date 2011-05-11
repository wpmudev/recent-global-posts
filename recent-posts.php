<?php
/*
Plugin Name: Recent Posts
Plugin URI:
Description: Recent posts function and shortcode
Author: Andrew Billits (Incsub)
Version: 2.1
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
display_recent_posts(NUMBER,TITLE_CHARACTERS,CONTENT_CHARACTERS,TITLE_CONTENT_DIVIDER,TITLE_BEFORE,TITLE_AFTER,GLOBAL_BEFORE,GLOBAL_AFTER,BEFORE,AFTER,TITLE_LINK,SHOW_AVATARS,AVATAR_SIZE,POSTTYPE, ECHO);

Ex:
display_recent_posts(10,40,150,'<br />','<strong>','</strong>','<ul>','</ul>','<li>','</li>','yes','yes',16, 'post', true);
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

function display_recent_posts($tmp_number,$tmp_title_characters = 0,$tmp_content_characters = 0,$tmp_title_content_divider = '<br />',$tmp_title_before,$tmp_title_after,$tmp_global_before,$tmp_global_after,$tmp_before,$tmp_after,$tmp_title_link = 'no',$tmp_show_avatars = 'yes', $tmp_avatar_size = 16, $posttype = 'post', $output = true){
	global $wpdb;
	$query = $wpdb->prepare("SELECT * FROM " . $wpdb->base_prefix . "site_posts WHERE blog_public = '1' AND post_type = %s ORDER BY post_published_stamp DESC LIMIT %d", $posttype, $tmp_number);
	$tmp_posts = $wpdb->get_results( $query, ARRAY_A );

	$html = '';

	if (count($tmp_posts) > 0){
		$html .= $tmp_global_before;

		$default_avatar = get_option('default_avatar');
		foreach ($tmp_posts as $tmp_post){

			$html .= $tmp_before;
			if ( $tmp_title_characters > 0 ) {
				$html .= $tmp_title_before;
				if ( $tmp_show_avatars == 'yes' ) {
					if ( $tmp_title_link == 'no' ) {
						$html .= get_avatar( $tmp_post['post_author'], $tmp_avatar_size, $default_avatar) . ' ' . substr($tmp_post['post_title'],0,$tmp_title_characters);
					} else {
						$html .= get_avatar( $tmp_post['post_author'], $tmp_avatar_size, $default_avatar) . ' <a href="' . $tmp_post['post_permalink'] . '" style="text-decoration:none;">' . substr($tmp_post['post_title'],0,$tmp_title_characters) . '</a>';
					}
				} else {
					if ( $tmp_title_link == 'no' ) {
						$html .= substr($tmp_post['post_title'],0,$tmp_title_characters);
					} else {
						$html .= '<a href="' . $tmp_post['post_permalink'] . '" style="text-decoration:none;">' . substr($tmp_post['post_title'],0,$tmp_title_characters) . '</a>';
					}
				}
				$html .= $tmp_title_after;
			}
			$html .= $tmp_title_content_divider;
			if ( $tmp_content_characters > 0 ) {
				$html .= substr($tmp_post['post_content_stripped'],0,$tmp_content_characters);
			}
			$html .= $tmp_after;
		}
		$html .= $tmp_global_after;

	}

	if($output) {
		echo $html;
	} else {
		return $html;
	}

}

function display_recent_posts_shortcode($atts, $content = null, $code = "") {

	$defaults = array(	'number'	=>	5,
						'title_characters' => 250,
						'content_characters' => 0,
						'title_content_divider' => '<br />',
						'title_before'	=>	'',
						'title_after'	=>	'',
						'global_before'	=>	'<ul>',
						'global_after'	=>	'</ul>',
						'before'	=>	'<li>',
						'after'	=>	'</li>',
						'title_link' => 'yes',
						'show_avatars' => 'no',
						'avatar_size' => 16,
						'posttype' => 'post'
					);

	extract(shortcode_atts($defaults, $atts));

	$html = '';

	$html .= display_recent_posts( $number, $title_characters, $content_characters, $title_content_divider, $title_before, $title_after, $global_before, $global_after, $before, $after, $title_link, $show_avatars, $avatar_size, $posttype, false);

	return $html;

}
add_shortcode( 'globalrecentposts', 'display_recent_posts_shortcode');


//------------------------------------------------------------------------//
//---Page Output Functions------------------------------------------------//
//------------------------------------------------------------------------//

//------------------------------------------------------------------------//
//---Support Functions----------------------------------------------------//
//------------------------------------------------------------------------//

?>