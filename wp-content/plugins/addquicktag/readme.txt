=== AddQuicktag ===
Contributors: Bueltge
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6069955
Tags: quicktag, editor, tinymce, add buttons, button, buttons, visual editor
Requires at least: 3.0
Tested up to: 3.4-beta3
Stable tag: 2.0.3

This plugin make it easy, Quicktags add to the html - and visual-editor.

== Description ==
This plugin make it easy, Quicktags add to the html - and visual-editor.. It is possible to ex- and import your Quicktags.

WP-AddQuicktag for WordPress is in originally by [Roel Meurders](http://roel.meurders.nl/ "Roel Meurders"). The versions of the Repo to AddQuicktag are newer versions, completly rewrite with version 2.0.0 and more functionalities.

The plugin add the quicktag on default to post types `post` and `page`. If you will also the plugin for other post types you can use a filter; see the follow example or an example plugin in the [Gist 1595155](https://gist.github.com/1595155).

	// add custom function to filter hook 'addquicktag_post_types'
	add_filter( 'addquicktag_post_types', 'my_addquicktag_post_types' );
	/**
	 * Return array $post_types with custom post types
	 * 
	 * @param   $post_type Array
	 * @return  $post_type Array
	 */
	function my_addquicktag_post_types( $post_types ) {
		
		$post_types[] = 'my_custom_post_type';
		
		return $post_types;
	}

**More Plugins**

Please see also my [Premium Plugins](http://wpplugins.com/author/malo.conny/). Maybe you find an solution for your requirement.

**Interested in WordPress tips and tricks**

You may also be interested in WordPress tips and tricks at [WP Engineer](http://wpengineer.com/) or for german people [bueltge.de](http://bueltge.de/) 


== Installation ==
= Requirements =
* WordPress version 3.0 and later (tested at 3.3 (nightly build) and 3.2.1)

= Installation =
1. Unpack the download-package
1. Upload the files to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress or for the Network, if you will use in Multisite for all Sites
1. Got to 'Settings' menu and configure the plugin

**Version before WordPress smaller 3.0**

If you will use this plugin with an older version of WordPress, please use an older version of this plugin, smaller version 2.0.0 - you find it in the [repo](http://wordpress.org/extend/plugins/addquicktag/download/). But i will not support this version. The version 2.0.0 and higher was rewrite with all new posibilties of the WordPress Core.


== Screenshots ==
1. Settings area in WordPress 3.3
2. Settings area in WordPress Network of an Multisite install 3.3
3. HTML Editor with new Quicktags
4. Visual editor with new Quicktags


== Other Notes ==
= Acknowledgements =
**Thanks to**

* German translation by [myself](http://bueltge.de) ;)
* French translation by [Jean-Michel MEYER (dit Li-An)](http://www.li-an.fr/blog)
* Russion translation by Flector
* Lithuanian translation files by [Vincent G](http://www.host1plus.com)

= Licence =
Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog. But if you enjoy this plugin, you can thank me and leave a [small donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6069955 "Paypal Donate link") for the time I've spent writing and supporting this plugin. And I really don't want to know how many hours of my life this plugin has already eaten ;)

= Translations =
The plugin comes with various translations, please refer to the [WordPress Codex](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") for more information about activating the translation. If you want to help to translate the plugin to your language, please have a look at the .pot file which contains all defintions and may be used with a [gettext](http://www.gnu.org/software/gettext/) editor like [Poedit](http://www.poedit.net/) (Windows) or plugin for WordPress [Localization](http://wordpress.org/extend/plugins/codestyling-localization/).


== Changelog ==
= 2.0.3 =
* Add Filter 'addquicktag_post_types' for use the plugin also on custom post types
* Update readme and add an example for this filter; also an Gist for use faster

= 2.0.2 =
* change hook for include styles and scriptes for compatibility in WP 3.4

= 2.0.1 =
* Bugfix on JS for WP smaller 3.3; use quickbuttons clean on html-editor with core-buttons

= 2.0.0 =
* complete redesign, new code from the first line
* add function for add quicktags on html and visual editor
* works also on Multisite Network
* new settings page
* add fallback in JS to use this new version also in WordPress smaller 3.3

= v1.6.5 (02/02/2011) =
* changes for admin-hints
* kill php warnings on debug-mode

= v1.6.4 (12/22/2010) =
* small changes for depreaced WP functions

= v1.6.3 (16/06/2009) =
* Add belarussian language file, thanks to Fat Cow

Please see the older changes on version on the [the official website](http://bueltge.de/wp-addquicktags-de-plugin/120/#historie "AddQuicktag")!
