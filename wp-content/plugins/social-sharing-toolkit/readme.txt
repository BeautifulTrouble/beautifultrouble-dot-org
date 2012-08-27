=== Social Sharing Toolkit ===
Contributors: MarijnRongen
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P8ZVNC57E58FE&lc=NL&item_name=WordPress%20plugins%20by%20Marijn%20Rongen&item_number=Social%20Sharing%20Toolkit&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: Facebook, Like, LinkedIn, Share, Google, Plus, +1, Pinterest, Google+, Twitter, Tweet, Buffer, Follow, StumbleUpon, Stumble, Tumblr, Delicious, Digg, Reddit, Myspace, Hyves, YouTube, Flickr, 500px, Picasa, deaviantART, mail, RSS, feed, connect, recommend, social, sharing, widget, shortcode, page, post, button, counter, icon, bitly, Open Graph
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 2.1.1
License: GPLv2 or later

Easy sharing and connecting on social networks. Display on posts or use widgets or shortcode. Also Twitter name/hashtag to link converter.

== Description ==

= Sharing content =

The plugin currently supports the following networks for sharing your content:

* Facebook (like, share and send buttons)
* Twitter
* Buffer
* Google +1
* Pinterest
* LinkedIn
* Tumblr
* StumbleUpon
* Delicious
* Digg
* Reddit
* Myspace
* Hyves

On Tweet and Buffer buttons you can specify a Twitter handle which is then appended to the tweet a visitor sends (like "... via @WordPress"). An email send button is also included.

You can decide which networks to support on your blog, where the buttons will appear (either above or below the content). Through drag and drop you can easily change the order in which the buttons appear. 

For each button you can choose a different type (not all types are available for each button):

* button
* button with counter on the side
* button with counter on top
* small icon
* small icon with text
* medium icon
* medium icon with text
* large icon

You can also choose the orientation of the buttons: 

* horizontal (buttons are placed side by side)
* vertical (buttons are placed below each other) 

= Divider = 

In version 2.0.4 three dividers were added to each list. These might be useful in ordering the buttons, especially in the case of varying button types used with a horizontal orientation. A divider will split the element containing the buttons in two, allowing for more customization. 

= Shortcode =

It is also possible to only let the buttons appear where you want by using shortcode. The shortcode function has it's own list of buttons with the same possibilities as the list for content. Use the shortcode [social_share/] in the content where you would like to display the buttons.

= Widget =

The widget has it's own list of buttons with the same possibilities as the list for posts & pages. You can however specify a fixed title and url to use for the buttons in the widget.

= Follow Widget = 

Since version 2.0.0 an extra widget is added with the possibility to provide easy links to your profiles on other social networks.

The networks currently supported for the Follow Widget are:

* Facebook
* Twitter
* Google+
* Pinterest
* Goodreads
* LinkedIn (including company and group links)
* Tumblr
* Myspace
* Hyves
* YouTube
* Flickr
* 500px
* Picasa
* Instagram
* DeviantArt
* Last.fm
* Spotify
* Soundcloud

A button for an RSS feed is also included. Of course the type, order and orientation of the buttons is also configurable for the Follow Widget. Each button for the Follow Widget supports the following types:

* small icon
* small icon with text
* medium icon
* medium icon with text
* large icon

To use a button you must enter your user / profile id or username for the network. To use the RSS feed button you must enter the full url. 

= Automatic Twitter links =

This plugin also includes a configurable & improved version of my earlier Automatic Twitter Links plugin. You can decide if you want to convert Twitter names and/or hashtags to links. 
Twitter names will link to their Twitter profile and hashtags will link to the Twitter search page.

= Bitly =

Since version 2.0.5 the plugin has the option to use bitly to shorten the urls for the tweet button. To use it you need to register with bitly and fill in your bitly username and API key.

= Pinterest =

Since version 2.0.5 the plugin has the option to show Pinterest buttons. The plugin tries to find the featured image or first image in the content if available. If no image is available the button is omitted. 
Since version 2.0.7 is is possible to enter the URL of a default image to use in case no image was found. You can also set the plugin to always use the default image regardless of any available images.

= Open Graph =

Since version 2.0.5 the plugin also has the option to automatically create Open Graph tags on your Wordpress site. These will tell Facebook for example which image to display when sharing a page.
You can enter the URL of a default image to use in case no image was found. The plugin will try to use the thumbnail attached to the post (if your theme supports it), or the first image it finds in the content. To turn of this behavior you can check a box which will force the plugin to always use the default image you have specified.

== Frequently Asked Questions ==

= My excerpts aren't displaying the buttons but some strange text is displayed =

You must disable the option "Include buttons in excerpts", it is enabled by default but some themes use custom excerpts which do not correctly parse the buttons.

= The buttons are not showing on my posts in the loop =

You must enable the option "Include buttons in excerpts", but keep in mind some themes use custom excerpts which do not correctly parse the buttons.

= How do I use display the buttons somewhere else (outside the loop) =

If you want to display the buttons somewhere else on your site you can use the following code where you want the buttons to appear:
`<?php
	$social_sharing_toolkit = new MR_Social_Sharing_Toolkit();
	echo $social_sharing_toolkit->create_bookmarks();
?>`

You can also supply an url and title to use on the buttons:
`	echo $social_sharing_toolkit->create_bookmarks('{YOUR URL}', '{YOUR TITLE}');`

To display the follow buttons somewhere else you can use the following code:
`<?php
	$social_sharing_toolkit = new MR_Social_Sharing_Toolkit();
	echo $social_sharing_toolkit->create_followers();
?>`

= The LinkedIn follow link doesn't seem right = 

You may need to setup you custom public profile url. To do so, use the following steps:
1. When signed in to LinkedIn, Go to Edit Profile
2. Click on Edit link, next to your default Public Profile URL (Under Profile tab)
3. Under "Your Public profile URL" on the right click "Customize your public profile url"
4. Type your desired URL in the popup box and you should be done!

= Can I translate the plugin in my own language? =

You can, please refer to the i18n sections on the WordPress website for information on how this works. A .pot file and a Dutch translation are included in the `/languages/` folder of the plugin.

== Installation ==

Upload the Social Sharing Toolkit plugin to the `/wp-content/plugins/` folder on your website, activate it and use the 'Social Sharing Toolkit page' under 'Settings' to configure your toolkit.

== Screenshots ==

1. Plugin configuration: General settings
2. Plugin configuration: Content, Shortcode and Share Widget have the same options 
3. Advanced settings with bitly, Pinterest and Open Graph options

== Upgrade Notice ==

= 2.1.1 =

Please update to version 2.1.1 to remove unwanted bookmarks and follow text.

= 2.1.0 =

Please update to version 2.1.0 immediately for several crucial fixes.

= 2.0.9 =

Please update to version 2.0.9 for several fixes, more features and extra networks.

= 2.0.8 =

Please update to version 2.0.8 for a critical fix causing server errors.

= 2.0.7 =

Please update to version 2.0.7 for several critical fixes and enhancements to the Pinterest buttons.

= 2.0.6 =

Please update to version 2.0.6 for several small bug fixes and enhancements.

= 2.0.5 =

Please update to version 2.0.5 for improved script handling, added buttons, bug fixes and integration of Bit.ly url shortening and Open Graph tags. Custom post types are now supported.

= 2.0.4 =

Please update to version 2.0.4 for several display bug fixes, internationalization and expanded functionality.

= 2.0.3 =

Some bug fixes.

= 2.0.2 =

Please update to version 2.0.2 for several small bug fixes and enhancements.

= 2.0.1 =

Version 2.0.1 is a major update with more possibilities. You will have to reconfigure the plugin because of this (it's gotten much better, I promise).

= 1.3.1 =

Version 1.3.1 includes an option to display buttons in excerpts.

= 1.3.0 =

Please update to version 1.3.0 for several bug fixes and enhancements.

= 1.2.5 =

Please update to version 1.2.5 for several bug fixes and enhancements.

= 1.2.0 =
New widget included in version 1.2.0.

= 1.0.1 =

Please update to version 1.0.1 to prevent an unexpected printing of the page title.

== Changelog ==

= 2.1.1 =
* Minor fix removing some unwanted text

= 2.1.0 =
* Includes a fix for intrusive p-tag inclusion by some themes (thanks to Pia and Patrick for helping to debug this)
* Replaced the Twitter icons
* Included link to settings page in Plugins page of WP Admin
* Added Instagram buttons (using Webstagram)
* Added possibility to force follow links to open in new window / tab
* Fixed slight overlap in WP admin when using IE8
* Removed some redundant functions

= 2.0.9 =
* Added the possibility to use custom text and images for (most) buttons
* Added possibility to create custom css within the plugin
* Improved feed detection to prevent buttons and text appearing in feeds
* Fixed some W3C validation issues
* Added Soundcloud buttons
* Added Goodreads buttons
* Added Flattr buttons
* Fixed possible safety issue with Javascript call for +1
* Added icons to +1 button choices

= 2.0.8 =
* Fixed issue with combinations of excerpt and Read more tags resulting in errors
* Fixed a bug with urls and StumbleUpon counters
* Fixed issue with Bitly for high traffic sites
* Updated Pinterest buttons and icons

= 2.0.7 =
* Included an option to specify a default image for the Pinterest button
* Fixed some major issues with shortcodes and excerpts

= 2.0.6 = 
* Fixed buttons not appearing on pages
* Fixed Pinterest issue

= 2.0.5 =
* Improved JavaScript handling
* Integrated Open Graph tag generator
* Integrated Bit.ly url shortening for Tweet buttons
* Fixed Automatic Twitter Links bugs
* Improved display of buttons in excerpts
* Plugin now supports custom post types
* Included Pinterest buttons
* Included Buffer buttons
* Included Xing buttons
* Slightly improved admin screens
* Buttons are excluded from feeds
* Updated StumbleUpon icons
* added rel="nofollow" option for outbound links

= 2.0.4 =
* Redesigned email button
* Redesigned Delicious buttons and counters
* Redesigned admin screens in tabs for easy configuration
* Enabled simultaneous shortcode use next to other display modes
* Added option to show buttons before and after content
* Added support for internationalization (.pot file and Dutch translation included)
* Added separate list for shortcode use
* Added Facebook button without text (text is hidden)
* Added Twitter Follow button (with & without counter)
* Added dividers to each list
* Added support for LinkedIn (company), Last.fm and Spotify follow links
* Fixed Javascript not loading in footer bug
* Fixed button alignment issues

= 2.0.3 =
* Fixed line break issue on some themes affecting horizontal button orientation
* Fixed security prompt on IE9 (thanks to Joey for debugging this)

= 2.0.2 =
* Fixed width issue with Facebook Like button
* HTML encoding will be decoded in titles for the sharing buttons

= 2.0.1 =
* It is now possible to load all JavaScript in the footer for improved page loading performance
* Minimized JavaScript load

= 2.0.0 =
* Added Follow Widget
* Added icon buttons
* Added possibility to control order of buttons
* Added choice between horizontal and vertical button orientation
* Share Widget now has it's own list of buttons
* Added support for Delicious and sending though email
* Several minor fixes

= 1.3.2 =
* Added FAQ to readme
* Enabled "Include buttons in excerpts" option by default
* Fixed minor title issue with tweet, tumblr and myspace buttons

= 1.3.1 =
* Added feature to choose if buttons display in excerpts (doesn't work on some themes)

= 1.3.0 =
* Added feature to display buttons only on posts, only on pages or on posts and pages 
* Improved hashtag to link conversion
* Fixed Facebook language to en_US to prevent width issues
* Fixed excerpt issue
* Fixed some css issues 

= 1.2.5 =
* Added title field to widget
* Added field for Twitter handle to attribute tweets to
* Fixed Digg buttons
* Fixed rendering of the list (missing closing tag)
* Reduced size of plugin code

= 1.2.0 =
* Added widget

= 1.0.1 =
* Fixed unexpected printing of the page title 

= 1.0.0 =
* First version