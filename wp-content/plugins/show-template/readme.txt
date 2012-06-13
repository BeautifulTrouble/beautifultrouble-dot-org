=== Show Template ===
Contributors: sivel
Donate Link: http://sivel.net/donate
Tags: show-template, show, template, theme, development
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 1.1

Prints an html comment in the footer of every page letting you know which template file of your theme was used for the display.

== Description ==

Prints an html comment in the footer of every page letting you know which template file of your theme was used for the display.

This plugin is aimed towards theme developers and for theme support. It is recommended to only have this plugin activated during development or support as it will expose your file system path structure.

Props to [Joel Fisher](http://flushinc.com/) for the idea behind this plugin.

== Installation ==

1. Upload the `show-template` folder to the `/wp-content/plugins/` directory or install directly through the plugin installer.
1. Activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer

== Frequently Asked Questions ==

= Why is the html comment not showing in the view source of my site? =

This plugin requires that your themes footer.php contain `<?php wp_footer(); ?>` directly above the `</body>` tag.

== Upgrade ==

1. Use the plugin updater in WordPress or...
1. Delete the previous `show-template` folder from the `/wp-content/plugins/` directory
1. Upload the new `show-template` folder to the `/wp-content/plugins/` directory

== Usage ==

1. Install and activate.
1. View source of your blog through your web browser.  You will see a html comment in the form of `<!-- Active Template: /home/username/public_html/wordpress/wp-content/themes/default/index.php -->`

== Upgrade Notice ==

= 1.1 =

Update to use new template loader features in WordPress 3.0 and add beter detection for when a plugin or theme has intercepted the template loader

== Changelog ==

= 1.1 (2010-09-12): =
* Use the WordPress 3.0 logic in determining the template file to include
* Add additional functionality to detect when a plugin or theme has intercepted the template loader

= 1.0 (2009-05-13): =
* Initial Public Release
