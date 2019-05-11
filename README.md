# Recent Global Posts

**INACTIVE NOTICE: This plugin is unsupported by WPMUDEV, we've published it here for those technical types who might want to fork and maintain it for their needs.**

## Translations

Translation files can be found at https://github.com/wpmudev/translations

## Recent Global Posts allows you to display a list of recent posts from across your Multisite network on your main site.

### Built for your network

This plugin uses the power of [Post Indexer](http://premium.wpmudev.org/project/post-indexer/ "Post Indexer") to publish recent posts anywhere on your main site dynamically using shortcodes – on pages, posts and widget areas, such as a sidebar or footer. Built and supported by WordPress network experts, Recent Network Posts provides the safe reliable service you need for operating a successful Multisite or BuddyPress community. 

![image](http://premium.wpmudev.org/wp-content/uploads/2009/06/latestposts.jpg)

 Help users discover new content from across your network and strengthen your sense of community with Recent Global Posts.

## Usage

### To Get Started:

Start by reading the [Installing Plugins](https://premium.wpmudev.org/wpmu-manual/installing-regular-plugins-on-wpmu/) section in our comprehensive [WordPress and WordPress Multisite Manual](https://premium.wpmudev.org/wpmu-manual/) if you are new to WordPress. _This plugin requires the [Post Indexer](https://premium.wpmudev.org/project/post-indexer) plugin_ _If you have an older version of Network Recent Posts installed in /mu-plugins/ please delete it._ Once installed and _network-activated_, it's ready to use. There's no configuration necessary!

### Displaying your network posts

You can display your recent posts anywhere on your site simply by adding a shortcode. The base shortcode is:

    [globalrecentposts]

That will display a simple list of linked titles to the 5 most recent posts in your network. 

![Recent Network Posts Basic Display](https://premium.wpmudev.org/wp-content/uploads/2009/06/recent-network-posts-3000-basic-list.png)

 But you can use the following attributes to really customize what content to display, and how to display it:

*   _number="5"_ - How many posts you want to display
*   _title_characters="250"_ - Maximum number of characters in each title.
*   _content_characters="200"_ - Maximum number of characters in the content of each entry
*   _title_content_divider="-"_ - What to use to separate the title from the content. If this parameter is not included, the content will display beneath the title.
*   _title_link="no"_ - By default, the title links to the post. You can use this to remove the link.
*   _show_avatars="yes"_ - Displays the author avatar if avatars are used on your site.
*   _avatar_size="32"_ - Sets the square size of the avatars.
*   _posttype="post-type"_ - Use to specify the post type to display. Default is "post". Note that you can only specify one post-type.

So, for example, if you want to show the 3 most recent posts with a little excerpt and author avatar, you could use it like this:

    [globalrecentposts number="3" content_characters="200" show_avatars="yes" avatar_size="32"]

The above shortcode would produce something like this on your site: 

![Recent Network Posts Avatar List](https://premium.wpmudev.org/wp-content/uploads/2009/06/recent-network-posts-3000-avatar-list.png)

 Don't like the default styling? You can also use these parameters to add text or HTML elements to really customize the content, as well as layout & styling:

*   _title_before="text or HTML element"_ - Executes before the title of each entry.
*   _title_after="text or HTML element"_ - Executes after the title of each entry.
*   _global_before="text or HTML element"_ - Executes above the list of entries.
*   _global_after="text or HTML element"_ - Executes beneath the list of entries.
*   _before="text or HTML element"_ - Executes before each entry.
*   _after="text or HTML element"_ - Executes after each entry.

Here's an example shortcode with some of these extra parameters. You'll notice we've included headline tags for the titles, and a custom class for each entry so we can style the output with a bit of custom CSS.

[globalrecentposts number="2" content_characters="200" global_before="Here are our most recent posts. Enjoy!" before="" after="" show_avatars="yes" avatar_size="48" title_before="" title_after=""]

The above shortcode could display something like this (note that the actual CSS to be used would depend a lot on your theme, so we haven't included that here). 

![Recent Network Posts Styled List](https://premium.wpmudev.org/wp-content/uploads/2009/06/recent-network-posts-3000-styled-list.png)
