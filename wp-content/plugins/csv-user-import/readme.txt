=== Plugin Name ===
Contributors: AndyDunn
Tags: csv, user, import, users
Requires at least: 2.0.2
Tested up to: 3.1
Stable tag: 1.0.3

This allows you to import a list of users taken from an uploaded CSV file.

== Description ==

This allows you to import a list of users taken from an uploaded CSV file. It will add users with basic information, including firstname, lastname, username, password and email address. Each user who is added will be a 'subscriber' by default, and be able to login to your site.

There are no additional options available at the moment, but if you want to add a bunch of users in one go, this will do it for you quickly.

Suggestions are more than welcome through the forum for the plugin.

== Installation ==

1. Upload `csv-user-import.php` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. You will see a new 'Import' option under the existing 'Users' menu area.

== Changelog ==

= 1.0.3 =
* Changed PHP tags so they work on more servers now.
* Updated WP table references to be more generic, avoiding hardcoded table prefix. (thanks leohung)
* Trimmed data on import and updated table prefix for subscribers table. (thanks cacaobeans)

= 1.0.2 =
* Fixed issue where CSV rows weren't being read in correctly from Mac formatted text files.

= 1.0.1 =
* Fixed minor issue where the email address was not being imported correctly.

= 1.0.0 =
* First release of plugin. No changes.
