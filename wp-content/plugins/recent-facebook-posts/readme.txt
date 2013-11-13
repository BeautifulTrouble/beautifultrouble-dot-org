=== Plugin Name ===
Contributors: DvanKooten
Donate link: http://dannyvankooten.com/donate/
Tags: facebook,posts,fanpage,recent posts,fb,like box alternative,widget,facebook widget,widgets,facebook updates,like button,fb posts
Requires at least: 3.1
Tested up to: 3.7.1
Stable tag: 1.8.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Lists most recent Facebook posts from public Facebook pages. A faster, prettier and more customizable alternative to Facebooks Like Box. 

== Description ==

This plugin adds a widget, a shortcode `[recent_facebook_posts]` and a template function `recent_facebook_posts()` to your WordPress website which you can use to list your most recent Facebook posts. This plugin works with public pages and to a certain extent with personal profiles.

= Facebook Posts Widget =
Render a number of most recent Facebook page updates in any of your widget areas using the Recent Facebook Posts widget. 

= Facebook Posts Shortcode =
Display a list of your most recent Facebook posts in your posts or pages using the `[recent_facebook_posts]` shortcode. Optionally, specify some arguments to customize the output.

**Features**

* SEO friendly. Your Facebook posts are rendered as plain HTML which means they are indexable by search engines, no frames or JavaScript is used.
* High performance. Facebook posts are cached for a customizable period.
* Customizable. Your Facebook updates will blend in with your theme perfectly and can be easily styled because of smart CSS selectors.
* Easy Configuration, the plugin comes with a comprehensive [installation guide](http://wordpress.org/plugins/recent-facebook-posts/installation/) and [screenshots](http://wordpress.org/plugins/recent-facebook-posts/screenshots/).
* Translation ready!

**Demo**

There is a demo on [my own website](http://dannyvankooten.com/), I use the plugin to show my most recent Facebook post in the footer.

**Other Links**

* [Recent Facebook Posts for WordPress](http://dannyvankooten.com/wordpress-plugins/recent-facebook-posts/)
* Using MailChimp to send out email newsletters and looking to grow your lists? Try [MailChimp for WordPress](http://wordpress.org/plugins/mailchimp-for-wp/).
* Check out more [WordPress plugins](http://dannyvankooten.com/wordpress-plugins/) by Danny van Kooten
* You should follow [@DannyvanKooten](http://twitter.com/DannyvanKooten) on Twitter.

== Installation ==

= Installing the plugin =
1. [Download the latest version of the plugin](http://downloads.wordpress.org/plugin/recent-facebook-posts.zip)
1. Upload the contents of the downloaded .zip-file to your WordPress plugin directory
1. Activate the plugin through the 'Plugins' menu in WordPress

= Registering a Facebook App =
This plugin requires a Facebook application to fetch posts from Facebook.

1. If you're not a Facebook developer yet, register as one [here](http://developers.facebook.com/apps).
1. [Create a new Facebook application](http://developers.facebook.com/apps). Fill in only the `App Name` field and click `Continue`.

= Configuring the plugin =
1. Go to *Settings > Recent Facebook* posts in your WP Admin panel.
1. Copy and paste your Facebook `App ID/API Key` and `App Secret` into the setting fields. 
1. Find the numeric Facebook ID of your public Facebook page using [this website](http://findmyfacebookid.com/).
1. Copy paste the ID in the `Facebook Page ID` field.
1. Test if fetching posts works by clicking the "Renew Facebook Posts" button.
1. Add `[recent_facebook_posts]` to the page where you would like to show a list of recent Facebook posts or use the widget.

= Extra notes =
* Take a look at the [screenshots](http://wordpress.org/extend/plugins/recent-facebook-posts/screenshots/), they will tell you which values from Facebook you need.
* The plugin works with personal profiles, but only to a certain extend. I am not actively supporting personal profiles because of many privacy settings related issues.

Ran into an error? Have a look at the [FAQ](http://wordpress.org/plugins/recent-facebook-posts/faq/) for solutions to common problems.

== Frequently Asked Questions ==

= What does Recent Facebook Posts do? =
With this plugin you can show a list of the most recent Facebook posts of a public page. You can display these posts in pages, posts and widget areas by using a shortcode or widget. Have a look at my [own WordPress website](http://dannyvankooten.com/) for an example, I have a widget with my latest Facebook update in my footer.

= How to configure this plugin? =
You need to create a Facebook application for this plugin to work. Have a **close** look at the [installation instructions](http://wordpress.org/plugins/recent-facebook-posts/installation/).

= Do you have a working demo I can take a look at? =
Sure, I use the plugin on my own [WordPress website](http://dannyvankooten.com/), in the middle footer widget.

= I want to apply custom styling to the Facebook posts. How do I go about this? =
You can add custom CSS rules to your theme stylesheet. This file is usually located here in `/wp-content/themes/your-theme-name/style.css`.

= Does this plugin work with group posts? =
No, sorry. Recent Facebook Posts works with public pages and to a certain extent with personal profiles.

= Can I show a list of recent facebook updates in my posts or pages? =
Yes, you can use the `[recent_facebook_posts]` shortcode. Optionally, add the following attributes.

`
likes = 1 // show like count, 1 = yes, 0 = no
comments = 1 // show comment count, 1 = yes, 0 = no
excerpt_length = 140 // the number of characters to show from each post
number = 5 // number of posts to show,
show_page_link = 0 // show a link to Facebook page after posts?
el = div // which element to use as a post container?
show_link_previews = 1 // show preview of attached links?
`

*Shortcode example*
`[recent_facebook_posts number=10 likes=1 comments=1 excerpt_length=250 show_page_link=1 show_link_previews=1]`

= Do you have a function I can use in template files? =
Use `<?php recent_facebook_posts(array('likes' => 1, 'excerpt_length => 140')); ?>` in your theme files. The parameter is optional, it can be an array of the same values available for the shortcode.

= How do I change the .. at the end of the excerpt? =
You can change this using a so-called filter. Add the following snippet to your theme its `functions.php` file to change *..* into a link to the Facebook post.

`
function my_rfbp_read_more($more, $link)
{
	return '<a href="'. $link . '">Read more &raquo;</a>';
}

add_filter('rfbp_read_more', 'my_rfbp_read_more', 10, 2);
`

= How do I disable the automatic paragraphs? =
`
remove_filter('rfbp_content', 'wpautop');
`

= How do I add text to all posts? =
`
function my_rfbp_content($content, $link)
{
	return $content . " my appended text.";
}

add_filter('rfbp_content', 'my_rfbp_content', 10, 2);
`

= How do I change the time posts are cached? =
`
function my_rfbp_cache_time($time)
{
	return 3600; // 1 hour
}

add_filter('rfbp_cache_time', 'my_rfbp_cache_time');
`

== Screenshots ==

1. The Recent Facebook Posts settings screen.
2. This is where you'll find your App ID / API Key and App Secret in your [Facebook App Settings](https://developers.facebook.com/apps/).
3. This is where you'll find your Facebook Page Slug on Facebook.com. 

== Changelog ==

= 1.8.1 - November 4, 2013 =
* Fixed: link previews without images not showing
* Added: filter `rfbp_show_link_images` to hide link preview images
* Improved: Link preview CSS

= 1.8 - November 3, 2013 =
* Added: previews of attached links, with image and short description (like Facebook)
* Added: Translation files
* Added: Dutch translations
* Improved: Moved cache time to a filter.
* Improved: Removed `session_start()` call.

= 1.7.3 - October 28, 2013 =
* Added: `rfbp_read_more` filter.
* Added: `rfbp_content` filter.
* Added: option to unhook `wpautop` from `rfbp_content` filter.

= 1.7.2 - October 18, 2013 =
* Fixed: No posts showing up for Scandinavian languages
* Improved: Links will no longer show up twice
* Added: Conversion of common smileys

= 1.7.1 - October 17, 2013 =
* Fixed: fetching posts from wrong Facebook page. Sorry for the quick version push.
* Improved: default CSS

= 1.7 - October 16, 2013 =
* Fixed issue where strings with dots where turned into (broken) links.
* Improved: better linebreaks
* Improved: Now using WP Transients for caching
* Improved: Now using WP HTTP API for fetching posts, which allows for other transfer methods besides just cURL.
* Improved: No user access token is required any more. Access tokens will now *never* expire.

= 1.6 - October 7, 2013 =
* Improved code performance and readability
* Improved usability of admin settings
* Improved: cleaner HTML output
* Improved: default CSS
* Improved: image resizing
* Improved: default settings
* Added installation instructions link to admin settings
* Added many CSS classes to output
* Fixed extra double quote breaking link validation

**Important:** CSS Selectors and HTML output has changed in this version. If you're using custom styling rules you'll have to edit them after updating.

= 1.5.3 - October 3, 2013 =
* Improved: Code improvement
* Improved: UI improvement, implemented some HTML5 fields
* Improved: Moved options page back to sub-item of Settings.

= 1.5.2 - October 1, 2013 =
* Fixed: max-width in older browsers

= 1.5.1 - September 20, 2013 =
* Improved: a lot of refactoring, code clean-up, etc.
* Improved: "open link in new window" option now applies to ALL generated links

= 1.5 =
* Improved: huge performance improvement for retrieving posts from Facebook
* Improved: some code refactoring
* Improved: cache now automatically invalidated when updating settings
* Improved: settings are now sanitized before saving
* Fixed: like and comment count no longer capped at 25
* Changed links to show your appreciation for the plugin.

= 1.4 =
* Changed cache folder to the WP Content folder (outside of the plugin to prevent cache problems after updating the plugin).
* Added redirection fallbacks when headers have already been sent when trying to connect to Facebook.
* Fixed error message when cURL is not enabled.
* Improved some messages and field labels so things are more clear.
* Updated Facebook API class.

= 1.3 =
* Added Facebook icon to WP Admin menu item
* Changed the connecting to Facebook process
* Improved error messages
* Improved code, code clean-up
* Improved usability in admin area by showing notifications, removing unnecessary options, etc.
* Added notice when access token expires (starting 14 days in advance)
* Fixed: Cannot redeclare Facebook class.
* Fixed: Images not being shown when using "normal" as image source size
* Fixed: empty status updates (friends approved)

= 1.2.3 =
* Changed the way thumbnail and normal image links are generated, now works with shared photos as well.
* Added read_stream permission, please update your access token.
* Added cache succesfully updated notice

= 1.2.2 =
* Added option to hide images
* Added option to load either thumbnail or normal size images from Facebook's CDN
* Added border to image links

= 1.2.1 =
* Fixed parameter app_id is required notice before being able to enter it.

= 1.2 =
* Fixed: Reverted back to 'posts' instead of 'feed', to exclude posts from others.
* Fixed: undefined index 'count' when renewing cache file   
* Fixed: wrong comment or like count for some posts
* Improved: calculation of cache file modification time to prevent unnecessary cache renewal
* Improved: error message when cURL is not enabled
* Improved: access token and cache configuration options are now only available when connected

= 1.1.2 =
* Fixed: Added spaces after the like and comment counts in the shortcode output

= 1.1.1 =
* Updated: Expanded installation instructions.
* Changed: Some code improvements
* Added: Link to Facebook numeric ID helper website.
* Added: Check if cache directory exists. If not the plugin will now automatically try to create it with the right permissions.
* Added: option to open link to Facebook Page in a new window.

= 1.1 =
* Added: Shortcode to show a list of recent facebook updates in your posts: '[recent_facebook_posts]'

= 1.0.5 =
* Added: More user-friendly error message when cURL is not enabled on your server.

= 1.0.4 =
* Improved: The way the excerpt is created, words (or links) won't be cut off now
* Fixed: FB API Error for unknown fields.
* Added: Images from FB will now be shown too. Drop me a line if you think this should be optional.

= 1.0.3 = 
* Improved the way the link to the actual status update is created (thanks Nepumuk84).
* Improved: upped the limit of the call to Facebooks servers. 

= 1.0.2 =
* Fixed a PHP notice in the backend area when renewing cache and fetching shared status updates.
* Added option to show link to Facebook page, with customizable text.

= 1.0.1 =
* Added error messages for easier debugging.

= 1.0 =
* Added option to load some default CSS
* Added option to show like count
* Added option to show comment count
* Improved usability. Configuring Recent Facebook Posts should be much easier now due to testing options.

= 0.1 =
* Initial release

== Upgrade Notice ==

= 1.8.1 =
Added link previews (like Facebook) and Dutch translations. Please update your settings after updating.

= 1.8 =
Added link previews (like Facebook) and Dutch translations. Please update your settings after updating.

= 1.6 =
CSS and HTML output have changed. If you're using custom CSS styles you will have to edit them after updating.