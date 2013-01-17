<?php
/*
Plugin Name: Recent Posts function and Shortcode
Plugin URI:
Description: Recent posts function and shortcode
Author: Barry (Incsub)
Version: 3.0
Author URI:
WDP ID: 75
*/

/*
Copyright 2012 Incsub (http://incsub.com)

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
class recentpostsshortcode {

	var $build = 1;

	var $db;

	function __construct() {

		global $wpdb;

		$this->db =& $wpdb;

		if($this->db->blogid == 1) {
			// Only add the feed for the main site
			add_action('init', array(&$this, 'initialise_recentpostsshortcode') );
		}

		add_shortcode( 'globalrecentposts', array( &$this, 'display_recent_posts_shortcode') );

	}

	function recentpostsshortcode() {
		$this->__construct();
	}

	function initialise_recentpostsshortcode() {
		// In case we need it in future :)
	}

	function display_recent_posts($tmp_number,$tmp_title_characters = 0,$tmp_content_characters = 0,$tmp_title_content_divider = '<br />',$tmp_title_before,$tmp_title_after,$tmp_global_before,$tmp_global_after,$tmp_before,$tmp_after,$tmp_title_link = 'no',$tmp_show_avatars = 'yes', $tmp_avatar_size = 16, $posttype = 'post', $output = true) {

		global $network_query, $network_post;

		$network_query = network_query_posts( array( 'post_type' => $posttype, 'posts_per_page' => $tmp_number ));

		$html = '';

		if( network_have_posts() ) {
			$html .= $tmp_global_before;
			$default_avatar = get_option('default_avatar');

			while( network_have_posts()) {
				network_the_post();

				$html .= $tmp_before;
				if ( $tmp_title_characters > 0 ) {
					$html .= $tmp_title_before;
					if ( $tmp_show_avatars == 'yes' ) {
						$the_author = network_get_the_author_id();
						$html .= get_avatar( $the_author, $tmp_avatar_size, $default_avatar) . ' ';
					}
					$the_title = network_get_the_title();
					if ( $tmp_title_link == 'no' ) {
						$html .= substr($the_title,0,$tmp_title_characters);
					} else {
						$html .= '<a href="' . network_get_permalink() . '" >' . substr($the_title,0,$tmp_title_characters) . '</a>';
					}

					$html .= $tmp_title_after;
				}
				$html .= $tmp_title_content_divider;

				if ( $tmp_content_characters > 0 ) {
					$the_content = network_get_the_content();
					$html .= substr(strip_tags($the_content),0,$tmp_content_characters);
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

		$html .= $this->display_recent_posts( $number, $title_characters, $content_characters, $title_content_divider, $title_before, $title_after, $global_before, $global_after, $before, $after, $title_link, $show_avatars, $avatar_size, $posttype, false);

		return $html;

	}

}

function display_recent_posts($tmp_number,$tmp_title_characters = 0,$tmp_content_characters = 0,$tmp_title_content_divider = '<br />',$tmp_title_before,$tmp_title_after,$tmp_global_before,$tmp_global_after,$tmp_before,$tmp_after,$tmp_title_link = 'no',$tmp_show_avatars = 'yes', $tmp_avatar_size = 16, $posttype = 'post', $output = true) {
	global $recentpostsshortcode;

	$recentpostsshortcode->display_recent_posts( $tmp_number, $tmp_title_characters, $tmp_content_characters, $tmp_title_content_divider, $tmp_title_before, $tmp_title_after, $tmp_global_before, $tmp_global_after, $tmp_before, $tmp_after, $tmp_title_link, $tmp_show_avatars, $tmp_avatar_size, $posttype, $output );
}

$recentpostsshortcode = new recentpostsshortcode();
