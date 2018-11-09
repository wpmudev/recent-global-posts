<?php
/*
Plugin Name: Recent Network Posts
Plugin URI: http://premium.wpmudev.org/project/recent-posts/
Description: Display a customizable list of recent posts from across your Multisite network on your site.
Author: WPMU DEV
Version: 3.1-beta-1
Author URI: http://premium.wpmudev.org/
WDP ID: 75
*/

// +----------------------------------------------------------------------+
// | Copyright Incsub (http://incsub.com/)                                |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License, version 2, as  |
// | published by the Free Software Foundation.                           |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,               |
// | MA 02110-1301 USA                                                    |
// +----------------------------------------------------------------------+

/*
Usage:
display_recent_posts(NUMBER,TITLE_CHARACTERS,CONTENT_CHARACTERS,TITLE_CONTENT_DIVIDER,TITLE_BEFORE,TITLE_AFTER,GLOBAL_BEFORE,GLOBAL_AFTER,BEFORE,AFTER,TITLE_LINK,SHOW_AVATARS,AVATAR_SIZE,POSTTYPE, READ_MORE, READ_MORE_LINK, SHOW_BLOG, ECHO);

Ex:
display_recent_posts(10,40,150,'<br />','<strong>','</strong>','<ul>','</ul>','<li>','</li>','yes','yes',16, 'post', '... more', true, true, true);
*/

class recentpostsshortcode {

	/** @var wpdb */
	var $db;

	function __construct() {
		global $wpdb;

		$this->db = $wpdb;

		add_shortcode( 'globalrecentposts', array( $this, 'display_recent_posts_shortcode' ) );
	}

	function display_recent_posts( $tmp_number, $tmp_title_characters, $tmp_content_characters, $tmp_title_content_divider, $tmp_title_before, $tmp_title_after, $tmp_global_before, $tmp_global_after, $tmp_before, $tmp_after, $tmp_title_link = 'no', $tmp_show_avatars = 'yes', $tmp_avatar_size = 16, $posttype = 'post', $read_more = '', $read_more_link = false, $show_blog = false, $output = true ) {
		
		global $network_post;
		$html = '';
		$classes = apply_filters( 
						'recent_network_posts_classes', 
						array(
							'blog-info' => 'blog-info',
							'read-more' => 'read-more'
						) 
					);

		network_query_posts( array( 'post_type' => $posttype, 'posts_per_page' => $tmp_number ) );
		if ( network_have_posts() ) {
			
			$default_avatar = get_option( 'default_avatar' );

			while ( network_have_posts() ) {
				network_the_post();

				$blog_id = $network_post->BLOG_ID;
				$post_url = network_get_permalink();
				$html .= $tmp_before;

				if ( $tmp_title_characters > 0 ) {

					$html .= $tmp_title_before;

					if ( $tmp_show_avatars == 'yes' ) {
						$the_author = network_get_the_author_id();
						$html .= get_avatar( $the_author, $tmp_avatar_size, $default_avatar ) . ' ';
					}

					$the_title = network_get_the_title();

					if ( $tmp_title_link == 'no' ) {
						$html .= substr( $the_title, 0, $tmp_title_characters );
					} else {
						$html .= '<a href="' . $post_url . '" >' . substr( $the_title, 0, $tmp_title_characters ) . '</a>';
					}

					if ( $show_blog ) {
						$blog_details = get_blog_details( $blog_id );
						$site_url = get_site_url( $blog_id );
						$class = $classes['blog-info'];
						$post_blog = "<span class='{$class}'>
										<a href='{$site_url}'>{$blog_details->blogname}</a>
									</span>";

						$post_blog = apply_filters( 
										'recent_network_posts_post_blog',
										$post_blog,
										$blog_id
									);

						$html .= ' (<a href="' . get_site_url( $blog_id ) . '">'. $blog_details->blogname .'</a>)';
					}

					$html .= $tmp_title_after;
				}
				$html .= $tmp_title_content_divider;

				if ( $tmp_content_characters > 0 ) {
					$the_content = network_get_the_content();

					$words = substr( strip_tags( $the_content ),0 ,$tmp_content_characters );
					$last_space_position = strrpos( $words, ' ' );
					$html .= substr($words, 0, $last_space_position);

					if ( ! empty( $read_more ) ) {

						$read_more_text = $read_more;
						$class = $classes['read-more'];

						if ( $read_more_link ) {
							$read_more_text = "<a href='{$post_url}' >{$read_more_text}</a>";
						}

						$html .= "<span class='{$class}'>{$read_more_text}</span>";
					}

				}

				$html .= $tmp_after;
			}

			$html = apply_filters( 
						'recent_network_posts_list_html',
						$tmp_global_before . $html . $tmp_global_after,
						$html,
						$tmp_global_before,
						$tmp_global_after
					);
		}

		if ( $output ) {
			echo $html;
		} else {
			return $html;
		}
	}

	function display_recent_posts_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'number'                => 5,
			'title_characters'      => 250,
			'content_characters'    => 0,
			'title_content_divider' => '<br />',
			'title_before'          => '',
			'title_after'           => '',
			'global_before'         => '<ul>',
			'global_after'          => '</ul>',
			'before'                => '<li>',
			'after'                 => '</li>',
			'title_link'            => 'yes',
			'show_avatars'          => 'no',
			'avatar_size'           => 16,
			'posttype'              => 'post',
			'read_more'				=> '',
			'read_more_link'		=> false,
			'show_blog'				=> false

		), $atts ) );

		return $this->display_recent_posts( $number, $title_characters, $content_characters, $title_content_divider, $title_before, $title_after, $global_before, $global_after, $before, $after, $title_link, $show_avatars, $avatar_size, $posttype, $read_more, $read_more_link, $show_blog, false );
	}

}

function display_recent_posts( $tmp_number, $tmp_title_characters, $tmp_content_characters, $tmp_title_content_divider, $tmp_title_before, $tmp_title_after, $tmp_global_before, $tmp_global_after, $tmp_before, $tmp_after, $tmp_title_link = 'no', $tmp_show_avatars = 'yes', $tmp_avatar_size = 16, $posttype = 'post', $read_more = '', $read_more_link = false, $show_blog = false, $output = true ) {
	global $recentpostsshortcode;
	$recentpostsshortcode->display_recent_posts( $tmp_number, $tmp_title_characters, $tmp_content_characters, $tmp_title_content_divider, $tmp_title_before, $tmp_title_after, $tmp_global_before, $tmp_global_after, $tmp_before, $tmp_after, $tmp_title_link, $tmp_show_avatars, $tmp_avatar_size, $posttype, $read_more, $read_more_link, $show_blog, $output );
}

$recentpostsshortcode = new recentpostsshortcode();
