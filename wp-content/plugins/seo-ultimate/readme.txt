=== SEO Ultimate ===
Contributors: SEO Design Solutions, JohnLamansky
Tags: seo, SEO Ultimate, suite, google, yahoo, bing, search engines, admin, post, page, custom post types, categories, tags, terms, custom taxonomies, base, title, title tag, wp_title, meta, robots, noindex, nofollow, canonical, HTTP headers, 404, robots.txt, htaccess, slugs, url, anchor, more, link, excerpt, permalink, links, autolinks, code, footer, settings, redirect, 301, 302, 307, modules, uninstallable, reinstallable, downgradable, import, export, CSV, affiliate, Open Graph, og, microdata, Facebook, Twitter, Schema.org
Requires at least: 3.5
Tested up to: 3.5
Stable tag: 7.6.1

This all-in-one SEO plugin gives you control over title tags, noindex, meta tags, Open Graph, slugs, canonical, autolinks, 404 errors, rich snippets, and more.

== Description ==

= Recent Releases =

* Version 7.6 adds Deeplink Juggernaut autolink dampening
* Version 7.5 adds support for Twitter Cards, Schema.org, and place snippets
* Version 7.4 adds the Author Highlighter module
* Version 7.3 adds the Open Graph Integrator module
* Version 7.2 updates Permalink Tweaker

= Features =

SEO Ultimate is an all-in-one [SEO](http://www.seodesignsolutions.com/) plugin with these powerful features:

* **Title Tag Rewriter**
	* Out-of-the-box functionality puts your post titles at the beginning of the `<title>` tag for improved keyword SEO.
	* Easily override the entire `<title>` tag contents for any individual post, page, attachment, category, post tag, or post format archive on your blog. Also supports custom post types and custom taxonomies.
	* Customize your homepage's `<title>` tag.
	* Format the `<title>` tags of posts, pages, categories, tags, archives, search results, and more!
	* Choose between two rewrite methods: "output buffering" or "filtering"

* **Meta Description Editor**
	* Edit the `<meta>` description tags for posts, pages, attachments, categories, tags, post format archives, and the homepage.
	* Increase SERP clickthrough rates by influencing search engine result snippets.
	* Mass-editor makes it a cinch to go back and add descriptions to old posts.
	* Use the `{excerpt::autogen}` variable to auto-generate meta descriptions if desired.

* **Deeplink Juggernaut**
	* Use the power of anchor text to boost your internal ranking SEO paradigm.
	* Searches your site's content for anchor texts you specify and automatically links them to a destination of your choosing. Lets you easily build internal links to URLs, posts, pages, attachments, custom post type items, categories, terms, post format archives, and custom taxonomy term archives.
	* Customize footer links for your entire site and/or on a page-by-page basis.
	* Easily select autolink destinations using autocomplete textboxes that scour your site's content and taxonomy terms to find the link destination you're looking for.
	* Autolinks point to the objects themselves, not to their URLs, so if you change the URL of a post or category on your site, the autolinks automatically adjust.
	* Avoid over-optimization penalties by controlling the maximum number of autolinks added to each post/page, the maximum number of times an anchor is linked per post/page, and/or the maximum number of times a post/page can link to the same destination.
	* Reduce autolink frequency by a given percentage (globally or per-link) with the frequency dampener feature. (Useful for large sites.)
	* Choose whether or not posts can link to themselves and/or to the current URL with a few simple checkboxes.
	* Apply the nofollow attribute on a per-link basis. (Perfect for automatic affiliate links.)
	* Exclude specific posts/pages from having links added to them, if desired (e.g. contact pages, the homepage, etc.).
	* Import/export your links as CSV files.
	* Create links pointing to draft posts that will auto-enable when the post is published!
	* Build internal links to your posts from within the WordPress post editor! Use "Instant Post Propulsion" technology to automatically link your old posts to new ones.
	* Lets you enable "Silo Linking" mode so that posts only link to other posts in the same category.

* **Open Graph Integrator**
	* Out-of-the-box functionality autogenerates Open Graph data for your homepage, posts, pages, attachments, custom post type objects, and user profile pages.
	* Fine-grained controls allow you to customize the Open Graph title, image, and content type for every single post, page, attachment, and custom post type object on your site.
	* Mass-editors let you specify Open Graph data for multiple posts and pages at a time.
	* Includes support for summary-style and photo-style Twitter Cards.
	* Lets you fix the official Facebook/Twitter HTML so that it validates as XHTML or HTML5.

* **Rich Snippet Creator**
	* Easily add rich snippet code for reviews and places.
	* Attract more search traffic with eye-catching supplementary SERP data.
	* Supports the new Schema.org format used by Google, Bing, Yahoo, and Yandex.

* **Author Highlighter**
	* Generates code so that when one of your site's posts appears in Google search results, the Google+ profile picture of the post's author will appear alongside it.
	* Includes support for both single-author and multi-author site setups.
	* Supports author highlighting for posts, pages, attachments, custom post types, the homepage, archive pages, and author pages.
	* Inserts a "Google+ Profile URL" field on the WordPress user profile editor, so that users can insert their Google+ URL and enable author highlighting on their posts.

* **Link Mask Generator**
	* Generate robots.txt-blocked "link masks" (e.g. `www.example.com/go/google/`) that pass-through to an external URL.
	* Mask links on a per-link, per-post basis so you can exert fine-tuned control over your posts' linkflow.
	* Create global link masks that apply across your entire site.
	* Change `/go/` to a directory of your choosing.
	* Link masks provide a modern replacement for the deprecated, nofollow-based "PageRank Sculpting" technique.
	* Perfect for affiliate marketers and SEO-savvy bloggers.

* **Canonicalizer**
	* Point search engines to preferred content access points with `<link rel="canonical" />` head tags and/or `Link: <url>; rel="canonical"` HTTP headers.
	* Go beyond WordPress's basic canonical tag functionality with SEO Ultimate's support for category/tag/date/author archives.
	* Redirect requests for non-existent pagination with a simple checkbox.

* **404 Monitor**
	* Improve the visiting experience of users and spiders by keeping tabs on "page not found" errors. (Use a redirection plugin to point dead-end URLs to your content.)
	* Find out what URLs are referring visitors to 404 errors.
	* The default settings hone in on the most important errors by only logging 404s that either have a referring URL or are generated by a search engine spider.
	* If desired, ignore 404s generated from specific URLs or wildcard URL patterns.

* **Permalink Tweaker**
	* Lets you remove the permalink base for categories, tags, and/or custom taxonomies.
	* For example, enable category base removal to convert `http://example.com/category/example` into `http://example.com/example`, and then pair that with a `/%category%/%postname%/` permalink to enable some serious SEO siloing action.
	* The "URL Conflict Resolution" setting lets you arbitrate between pages/terms when taxonomy base removal causes their URLs to conflict

* **Meta Robot Tags Editor**
	* Add the `<meta name="robots" content="noindex,follow" />` tag to archives, comment feeds, the login page, and more.
	* Set meta robots tags (index/noindex and follow/nofollow) for each individual post, page, category, tag, and post type archive on your blog. Also supports custom post types and custom taxonomies.
	* Avoid duplicate content SEO issues with the recommended noindex settings (see built-in module documentation for details).
	* Give instructions to search engine spiders if desired (`noodp`, `noydir`, and `noarchive`).

* **SEO Ultimate Widgets**
	* Lets you output your Deeplink Juggernaut Footer Links in a widget.
	* The Siloed Categories widget makes it drag-and-drop-easy to construct siloed navigation on your site.

* **Plugin Settings Manager** (located under Settings > SEO Ultimate)
	* Export your SEO Ultimate settings to a file and re-import later if desired.
	* Use the export/import functionality to move SEO Ultimate settings between WordPress sites.
	* Reset all settings back to "factory defaults" if something goes wrong.

* And much more...
	* **Code Inserter**: Easily insert SEO/SEM-enhancing custom HTML into your site's `<head>` tag, footer, or item content. Code remains even when switching themes.
	* **File Editor**: Implement advanced SEO strategies with the `.htaccess` editor, and give instructions to search engines via the `robots.txt` editor.
	* **Linkbox Inserter**: Encourage natural linkbuilding activity by adding textboxes to the end of your posts/pages that contain automatically-generated link HTML.
	* **Meta Keywords Editor**: Auto-generate and edit `<meta>` keywords for posts, pages, categories, tags, terms, and the homepage.
	* **More Link Customizer**: Optimize your posts' "read more" links by including the posts' keyword-rich titles in the anchor text.
	* **Nofollow Manager**: Lets you maintain `rel="nofollow"` settings when migrating from other SEO plugins
	* **Settings Monitor**: Keep tabs on the SEO-friendliness of your site's settings with a dashboard of green/yellow/red indicators.
	* **Sharing Facilitator**: Adds buttons that make it easy for visitors to share your content on social networking sites (thus building links to your site).
	* **Slug Optimizer**: Increase in-URL keyword potency by removing customizeable "filler words" (like "the," "with," "and," etc.) from post/page URLs.
	* **Webmaster Verification Assistant**: Enter verification codes in the provided fields to access search engine webmaster tools.

* **Additional features**
	* Features a clean, aesthetically-pleasing interface, with no donation nags.
	* Cleanly integrates into the admin interface with minimal branding.
	* Includes seamlessly-integrated documentation, accessible via the "Help" dropdown in the upper-right-hand corner of the admin screen. In-depth info, explanations, and FAQs are just a click away.
	* Lets you import post meta from All in One SEO Pack.
	* Lets you downgrade to the previous version of the plugin in case an upgrade goes awry.
	* Displays admin notices if WordPress settings are configured to discourage search engines.
	* Supports [WordPress plugin translation](http://urbangiraffe.com/articles/translating-wordpress-themes-and-plugins/). POT file is included in the zip file.
	* Includes an uninstaller that can delete the plugin's files and database entries if desired.

[**Download**](http://downloads.wordpress.org/plugin/seo-ultimate.zip) **your free copy of SEO Ultimate today.**

[youtube http://www.youtube.com/watch?v=CZwZuUPCAto]

== Installation ==

To install the plugin automatically:

1. Go to the [SEO Ultimate homepage](http://www.seodesignsolutions.com/wordpress-seo/).
2. In the "Auto Installer" box on the right, enter your blog's URL and click "Launch Installer."
3. Click "Install Now," then click "Activate this plugin."

That's it! Now go to the new "SEO" menu and explore the modules of the SEO Ultimate plugin.


To install the plugin manually:

1. Download and unzip the plugin.
2. Upload the `seo-ultimate` directory to `/wp-content/plugins/`.
3. Activate the plugin through the 'Plugins' menu in WordPress.


== Frequently Asked Questions ==

= Troubleshooting =

* **I installed SEO Ultimate and my site broke. What do I do?**
	If you get an error message or your screen goes blank after you activate SEO Ultimate, it's likely because your site ran out of memory. A common site memory limit is 32 megabytes. SEO Ultimate uses only 5 megabytes of memory, but combined with WordPress and your other plugins, you may be running over the limit. Try increasing your memory limit to "64M" (64 megabytes) [in your wp-config.php file](http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP), or contact your web host for assistance. (Note: The amount of site memory has nothing to do with the amount of your computer's memory.)

* **I upgraded SEO Ultimate and something broke. What do I do?**
	If a new version of SEO Ultimate introduces a bug, you can easily revert back to the previous version you were using. Just go to the `Settings > SEO Ultimate` page and click on the "Downgrade" tab. (Downgrading is available most of the time, but if the new version upgraded the plugin's settings infrastructure, SEO Ultimate won't let you downgrade, since doing so would cause you to lose your settings. There may be other cases in which downgrading isn't available or causes problems; it's an unsupported feature, but should work in most cases.)

= Compatibility =

* **What's the minimum version of WordPress required to run SEO Ultimate?**
	WordPress 3.3 is required. SEO Ultimate would generate PHP errors if you tried to run it on an earlier version, and so it will simply refuse to activate on any version of WordPress that's older than 3.3.

* **Will SEO Ultimate work on multisite setups?**
	Yes. SEO Ultimate versions 7.2.6 and later are designed to work on networks running WordPress 3.3 or later.

* **Which browsers work best with the SEO Ultimate administration interface?**
	SEO Ultimate's administration interface occasionally uses some HTML5 features. All modern browsers support these features with the exception of Internet Explorer. Don't worry, you'll still be able to use the administration interface with Internet Explorer, but you'll get the best experience if you use another browser. (Note that this only applies to the browser _you_ use. The visitors to your website, on the other hand, can use whatever browser they want.)

= General FAQ =

* **Why "SEO Ultimate" instead of "Ultimate SEO"?**
	Because "SEO Ultimate" works better as a brand name.

* **Where in WordPress does the plugin add itself?**
	SEO Ultimate puts all its admin pages under a new "SEO" top-level menu. The only exception is the plugin settings page, which goes under `Settings > SEO Ultimate`.

* **Where's the documentation?**
	SEO Ultimate's documentation is built into the plugin itself. Whenever you're viewing an SEO Ultimate page in your WordPress admin, you can click the "Help" tab in the upper-right-hand corner to view documentation for the area you're viewing.

* **How do I disable the attribution link?**
	The attribution link is disabled by default. It only appears if you enable it. You can re-disable it the same place you enabled it: under `Settings > SEO Ultimate`.

* **How do I uninstall SEO Ultimate?**
	1. Go to the `Settings > SEO Ultimate` admin page and click the "Uninstall" tab.
	2. Click the "Uninstall Now" button and click "Yes" to confirm. SEO Ultimate's files and database entries will be deleted.

* **Will all my settings be deleted if I delete SEO Ultimate in the Plugins manager?**
	No. Your settings will be retained unless you uninstall SEO Ultimate under `Settings > SEO Ultimate > Uninstall`.

* **Where is the Plugin Settings page?**
	The plugin settings page is located under `Settings > SEO Ultimate`.

= "SEO Settings" box =

* **Where is the SEO Settings box located?**
	The SEO Settings box is located on WordPress's post/page editor underneath the content area.

* **How do I disable the "SEO Settings" box in the post/page editors?**
	Open the editor, click the "Screen Options" tab in the upper-right-hand corner, and uncheck the "SEO Settings" checkbox. Note that the box's visibility is a per-user preference.

* **Why did some of the textboxes disappear from the "SEO Settings" box?**
	The "SEO Settings" fields are added by your modules. The "Title Tag" field is added by the Title Tag Rewriter module, the "Meta Description" field is added by the Meta Description Editor module, etc. If you disable a module using the Module Manager, its fields in the "SEO Settings" box will be disabled too. You can re-enable the field in question by re-enabling the corresponding module.


= Module FAQ =

Frequently asked questions, settings help, and troubleshooting tips for SEO Ultimate's modules can be found by clicking the help tabs in the upper-right-hand corner of the module admin pages themselves.



== Screenshots ==

1. Module Manager lets you enable, disable, or hide SEO Ultimate features
2. 404 Monitor logs attempts to access non-existent webpages
3. 404 Monitor settings
4. Code Inserter lets you insert custom HTML in various locations on your site
5. Deeplink Juggernaut lets you build autolinks to content on your site and/or external URLs
6. Deeplink Juggernaut settings
7. File Editor lets you customize your robots.txt or .htaccess
8. Linkbox Inserter encourages natural linkbuilding activity with link code boxes below your posts/pages
9. Meta Description Editor lets you specify defaults for different types of pages
10. The Blog Homepage tab in Meta Description Editor
11. Meta Description Editor lets you set your meta descriptions all at once
12. Meta Keywords Editor lets you auto-generate keyword tags for your site
13. Meta Robot Tags Editor lets you send various instructions to search engine spiders
14. Meta Robot Tags Editor lets you "noindex" desired parts of your site
15. Meta Robot Tags Editor lets you "noindex" and/or "nofollow" individual pages on your site
16. Nofollow Manager lets you add the nofollow attribute to various types of links
17. Rich Snippet Creator's settings page
18. Slug Optimizer removes common, keyword-impoverished words from your post URLs
19. Title Tag Rewriter lets you set default <title> formats for various parts of your site
20. SEO Ultimate plugin settings
21. SEO Ultimate lets you import settings from exported files or from other plugins
22. SEO Ultimate lets you export your settings so you can transfer them to another site
23. If you ever run into problems with a new version of SEO Ultimate, use the downgrade feature to revert to a version of your choosing
24. SEO Ultimate puts its modules in the SEO menu and its plugin options under "Settings"


== Changelog ==

= Version 7.6.1 (January 1, 2013) =
* Bugfix: Fixed a bug that prevented the "Dampener" column from appearing on the Deeplink Juggernaut Content Links tab

= Version 7.6 (December 31, 2012) =
* Feature: Open Graph Integrator now lets you output W3C-valid XHTML or HTML5 instead of the non-validating code prescribed by Open Graph and Twitter
* Improvement: Deeplink Juggernaut's "Site Cap" feauture has been replaced with a more efficient "Dampener" feature
* Improvement: Open Graph Integrator no longer outputs author data for pages, and lets you turn it off for posts
* Improvement: Rich Snippet Creator now has an admin section that gives a brief explanation of how to use it, since some users were confused by the module's lack of an admin page and assumed it wasn't working
* Improvement: Link Mask Generator now disables itself and displays a warning if you're not using a compatible permalink structure
* Improvement: Added lots of missing documentation
* Improvement: Removed unused code throughout the plugin
* Bugfix: Open Graph Integrator no longer generates a fatal error when the active theme doesn't support post thumbnails
* Bugfix: Open Graph Integrator now outputs the correct URL on the "Posts page" when the "Front page displays" option (under Settings > Reading) is set to "A static page"
* Bugfix: Fixed errors that would appear when Meta Keywords Editor and the WP_DEBUG mode were both enabled
* Bugfix: Archive pages for new terms no longer generate 404 errors when Permalink Tweaker's base removal feature is in use
* Bugfix: The "Nofollow" checkbox on the "Miscellaneous" tab of the "SEO Settings" post editor box no longer reverts to an unchecked state when the post is saved
* Bugfix: The plugin no longer generates a "WordPress Database Error" after upgrading the plugin on sites with the WP_DEBUG mode enabled
* Compatibility: Fixed minor WordPress 3.5 compatibility issues

= Version 7.5.7 (August 17, 2012) =
* Removed Feature: Open Graph Integrator no longer falls back to using webpage screenshot images from the WordPress.com mShots service, as this was causing "Generating Image" placeholders to be cached by Facebook

= Version 7.5.6 (August 13, 2012) =
* Feature: Rich Snippet Creator now includes an "Image of Reviewed Item" field for reviews
* Bugfix: The autocomplete dropdowns of the "Link Location" boxes of Deeplink Juggernaut's Footer Linker once again include taxonomy archive pages
* Bugfix: Fixed bug that would cause a single, blank autolink entry pointing to a post to appear on the Deeplink Juggernaut Content Links list (bug introduced in 3.9)
* Bugfix: The "Link Masks" section of the "SEO Settings" box no longer includes links to anchor targets (i.e. links that begin with "#")
* Bugfix: Restored access to the "When displaying page lists, nofollow links to this page" checkbox that disappeared from the "SEO Settings" box in version 7.3
* Bugfix: Fixed errors that would appear when Open Graph Integrator and the WP_DEBUG mode were both enabled (bug introduced in 7.3)
* Bugfix: Fixed errors that would appear when Rich Snippet Creator and the WP_DEBUG mode were both enabled (bug introduced in 7.5)
* Bugfix: Fixed errors that would appear on the post editor screen with the WP_DEBUG mode enabled (bug introduced in 7.5.3)
* Bugfix: Rich Snippet Creator now outputs a hidden datePublished property using a `<meta>` tag instead of using a self-closing `<time>` tag

= Version 7.5.5 (August 12, 2012) =
* Bugfix: Fixed PHP warning that appeared on the post editor screen with Meta Keywords Editor enabled (bug introduced in 7.5.3)

= Version 7.5.4 (August 11, 2012) =
* Bugfix: Fixed broken settings reset function (bug introduced in 5.0)

= Version 7.5.3 (August 11, 2012) =
* Bugfix: The Autolink Exclusion checkbox now appears when adding posts, not just when editing them
* Bugfix: Documentation for the SEO Settings box now appears on the post editor screen's "Help" dropdown under the "SEO Settings" tab instead of under the generic "Overview" tab
* Removed Feature: Webmaster Verification Assistant no longer supports verification tags for the now-defunct Yahoo! Site Explorer
* Compatibility: SEO Ultimate will now refuse to activate on any version of WordPress older than 3.3

= Version 7.5.2 (August 11, 2012) =
* Bugfix: Fixed disorganized documentation on the Miscellaneous page (bug introduced in 7.5)
* Bugfix: Fixed bug that caused Open Graph Integrator image boxes to show autocomplete suggestions for all types of site items, instead of just images (bug introduced in 7.3)
* Improvement: Tweaked the behavior of the meta description mass-editor textareas

= Version 7.5.1 (August 9, 2012) =
* Bugfix: Removed blank Rich Snippet Creator admin page from the SEO menu (bug introduced in 7.5)
* Bugfix: Removed non-functional "Test" link from the "Add a New Alias" section of Link Mask Generator's admin page (bug introduced in 7.1)

= Version 7.5 (August 9, 2012) =
* Feature: Open Graph Integrator now includes Twitter Card support
* Feature: Rich Snippet Creator now supports Place microdata
* Improvement: Rich Snippet Creator has been updated to use the new Schema.org microdata format supported by Google, Bing, Yahoo, and Yandex
* Improvement: Module Manager no longer shows the "Hidden" option for Miscellaneous modules, since for those modules the "Hidden" option behaves no differently than the "Enabled" option
* Bugfix: Adjusted 404 Monitor CSS to account for WordPress CSS changes
* Bugfix: Since Open Graph articles are only supposed to have one "article:section" element but can have multiple "article:tag" elements, posts with multiple categories now have those categories listed in the Open Graph code as "article:tag" elements instead of multiple "article:section" elements, while posts with just one category continue to have that category marked as "article:section"
* Removed Feature: Rich Snippet Creator no longer lets you choose between the microdata/microformat/RDFa data formats, since these have been replaced with the new Schema.org format. (Rich Snippet Creator will update all your old microdata/microformat/RDFa code automatically.)
* Removed Feature: The previous version of Rich Snippet Creator was able to mark old posts as reviews if they had certain categories or tags. This feature has been removed, and old posts must now be marked as reviews manually. This is because automatically marking old posts as reviews won't do you much good unless those posts also have review data, which must be entered in manually anyway.
* Note: Rich Snippet Creator no longer has an admin page; now the module's only interface is in the "SEO Settings" box
* Change: The "Link Masks" section now appears on the "Links" tab of the "SEO Settings" box instead of on the "Miscellaneous" tab
* Change: The admin pages for Linkbox Inserter, Nofollow Manager, and Slug Optimizer have been moved to the Miscellaneous page in order to reduce clutter on the SEO menu

= Version 7.4 (August 7, 2012) =
* New Module: Author Highlighter
	* Generates code so that when one of your site's posts appears in Google search results, the Google+ profile picture of the post's author will appear alongside it
	* Includes support for both single-author and multi-author site setups
	* Supports author highlighting for posts, pages, attachments, custom post types, the homepage, archive pages, and author pages
	* Inserts a "Google+ Profile URL" field on the WordPress user profile editor, so that users can insert their Google+ URL and enable author highlighting on their posts

= Version 7.3.5 (August 7, 2012) =
* Bugfix: Fixed PHP error that appeared when Open Graph Integrator was enabled but Meta Description Editor was disabled (bug introduced in 7.3)

= Version 7.3.4 (August 7, 2012) =
* Bugfix: Fixed bug that caused the Open Graph Type dropdown in the "SEO Settings" box to read "Video" by default (bug introduced in 7.3)

= Version 7.3.3 (August 7, 2012) =
* Bugfix: Fixed JavaScript error on the Add New Post screen (bug introduced in 7.3)

= Version 7.3.2 (August 6, 2012) =
* Bugfix: Fixed broken Open Graph author elements on posts with the "article" type (bug introduced in 7.3)

= Version 7.3.1 (August 6, 2012) =
* Bugfix: Fixed broken autogenerated Open Graph image URLs (bug introduced in 7.3)

= Version 7.3 (August 6, 2012) =
* New Module: Open Graph Integrator
	* Automatically generates Open Graph elements for posts, pages, attachments, custom post type objects, user profile pages, and the blog homepage
	* Includes a mass-editor that lets you bulk-edit data for posts, pages, attachments, and custom post types
	* Adds Open Graph fields to the "SEO Settings" box
	* Lets you specify sitewide and default Open Graph values
* Feature: The "Title Tag" field in the "SEO Settings" box now includes a character counter
* Improvement: The "SEO Settings" box has been broken up into 4 tabs in order to make the box smaller and in order to make settings easier to find
* Improvement: Removed some backcompatibility code for old versions of WordPress (2.9 and before)
* Improvement: Mass-editors now include posts/pages/etc. that are drafts/pending/scheduled/trashed
* Bugfix: Restored the ability to edit media items in module mass-editors
* Bugfix: Meta Keywords Editor now removes stopwords case-insensitively from lists of autogenerated meta keywords

= Version 7.2.9 (July 9, 2012) =
* Bugfix: Code Inserter no longer calls the `wp_get_current_user` function prematurely during first-time plugin activation (bug introduced in 7.2.6)

= Version 7.2.8 (July 7, 2012) =
* Bugfix: Permalink Tweaker now removes its changes to WordPress's rewrite rules when SEO Ultimate is deactivated/uninstalled or when Permalink Tweaker is disabled from the Module Manager
* Bugfix: International characters are no longer stripped from autogenerated meta keywords
* Compatibility: SEO Ultimate will now refuse to activate on versions of WordPress below 3.2

= Version 7.2.7 (July 5, 2012) =
* Feature: Canonicalizer now lets you force a URL scheme (`http://` or `https://`) across all canonical URLs
* Bugfix: Canonicalizer's "Redirect requests for nonexistent pagination" once again works for archive pages

= Version 7.2.6 (July 3, 2012) =
* Improvement: Updated the Webmaster Verification Assistant interface to further clarify its functionality
* Security Fix: Webmaster Verification Assistant no longer allows users without the unfiltered_html capability to insert arbitrary `<meta>` tags (and, theoretically, other HTML)
* Security Fix: Code Inserter now checks for the unfiltered_html capability in addition to the manage_options capability. In other words, site administrators on multisite setups can no longer use Code Inserter. (Security Note: If any users without the unfiltered_html capability previously entered code into Code Inserter, that code will remain active even after upgrading. Network admins may want to check the Code Inserter pages of their network's sites to ensure that site admins have not entered any malicious code.)
* Bugfix: Fixed broken settings exporter (bug introduced in 5.0)
* Bugfix: Fixed a bug which caused some modules' settings to be excluded from settings export files (bug introduced in 1.5)
* Bugfix: 404 Monitor's logs are now excluded from settings export files even if 404 Monitor is disabled (bug introduced in 2.1)
* Bugfix: The custom title tags and meta data of categories/tags/terms are now excluded from settings export files (bug introduced in 2.9)
* Bugfix: The dialog box that confirms you want to leave a page without saving changes no longer appears after starting a settings import (bug introduced in 5.7)

= Version 7.2.5 (June 29, 2012) =
* Improvement: The Upgrade/Downgrade/Reinstall/Uninstall tabs have been moved from the site admin to the network admin in cases where the plugin is network-activated on multisite setups
* Security Fix: The Uninstall tool now checks for the delete_plugins capability (whereas previously, any user with the manage_options capability could uninstall)
* Security Fix: The Downgrade and Reinstall tools now check for the install_plugins capability instead of the update_plugins capability
* Security Fix: The Upgrade/Downgrade/Reinstall/Uninstall tools now check for super admin status on multisite setups
* Bugfix: SEO Ultimate now hides the Upgrade/Downgrade/Reinstall/Uninstall tabs from users who lack the proper capabilities, rather than merely aborting those functions when attempted by unauthorized users
* Change: The "Plugin Settings" module can no longer be disabled by site admins when SEO Ultimate is network-activated on a multisite setup

= Version 7.2.4 (June 25, 2012) =
* Bugfix: Icons no longer disappear from the WordPress admin menu on the Module Manager admin page
* Bugfix: Fixed a function that generated non-validating HTML on multiple admin pages
* Removed 2 outdated screenshots

= Version 7.2.3 (June 19, 2012) =
* Bugfix: Fixed bug that broke the "Only on This Post" boxes in Link Mask Generator and prevented posts from being selected in the "Link Location" boxes of Deeplink Juggernaut's Footer Links editor
* Bugfix: Meta Description Editor now trims leading and trailing whitespace from meta descriptions
* Bugfix: Made minor CSS tweaks to the autocomplete boxes to adjust for various changes made in recent versions of WordPress
* Change: "Insert comments around HTML code insertions" is now enabled by default

= Version 7.2.2 (June 15, 2012) =
* Feature: You can now specify a CSS class for autolinks generated by Deeplink Juggernaut
* Bugfix: Link Mask Generator no longer nofollows all links in a post when the "Nofollow aliased links" option is checked (bug introduced in 6.1)

= Version 7.2.1 (December 30, 2011) =
* Bugfix: Fixed malformed HTML that broke multiple module admin pages (bug introduced in 7.2)
* Bugfix: Deeplink Juggernaut's "Tag Restrictions" setting now takes effect even when the excluded tag has a `<br />` or other nested tag in it
* Bugfix: Link masks with empty alias URLs no longer appear in Deeplink Juggernaut destination dropdowns
* Bugfix: Deeplink Juggernaut and Link Mask Generator now handle cases in which a post, term, user, or link mask is specified in the "Destination," "Link Location," or "Only on This Post" boxes and then is later deleted
* Bugfix: Deeplink Juggernaut now handles cases when a link mask is specified in the "Destination" box and then the Link Mask Generator module is later disabled
* Bugfix: Link masks no longer show up in the "Link Location" autocomplete dropdowns of Deeplink Juggernaut's Footer Links editor (bug introduced in 7.1)
* Improvement: Made Deeplink Juggernaut's Footer Links editor look better on smaller screen resolutions

= Version 7.2 (December 22, 2011) =
* Feature: Added "URL Conflict Resolution" setting to Permalink Tweaker that lets you arbitrate between pages/terms when taxonomy base removal causes their URLs to conflict
* Improvement: Cleaned up the Permalink Tweaker config interface
* Bugfix: Fixed stray `">` that appeared on some posts due to malformed HTML that Rich Snippet Creator generated when set on Microformats mode

= Version 7.1 (December 21, 2011) =
* Feature: Link Mask Generator now lets you edit all your link masks from a new interface on its admin page
* Feature: You can now create link masks that apply across your entire site instead of just to one post
* Feature: You can now use link masks as autolink destinations in Deeplink Juggernaut
* Security Fix: Link Mask Generator now properly escapes link mask URLs
* Bugfix: The "SEO Settings" box's "Link Masks" section no longer lists a URL more than once if it's in the post more than once
* Change: Link mask creation now requires the manage_options user capability

= Version 7.0 (December 19, 2011) =
* Compatibility: SEO Ultimate now supports and requires WordPress 3.3
* Improvement: SEO Ultimate now uses the new help tabs system of WordPress 3.3
* Feature: Meta Description Editor now supports page and pagination formats
* Bugfix: Title Tag Rewriter's mass-editors no longer add backslashes before special characters in custom titles
* Removed the Competition Researcher and Internal Relevance Researcher modules (due to Google website changes that make these tools unusable)
* New Translation: Italian (partial) by [gidibao](http://gidibao.net)

= Version 6.9.8 (December 1, 2011) =
* Bugfix: Title Tag Rewriter now applies the Pagination Title Format to custom category/tag/taxonomy title tags

= Version 6.9.7 (October 28, 2011) =
* Bugfix: Fixed errors that appeared in Deeplink Juggernaut CSV exports
* Bugfix: Deeplink Juggernaut now handles get_permalink() errors silently
* Bugfix: Added missing textdomains to some strings
* Bugfix: Fixed some strings that were being gettexted before the plugin textdomain was loaded

= Version 6.9.6 (September 23, 2011) =
* Bugfix: Deeplink Juggernaut now applies changes to sitewide link caps gradually instead of all at once, in order to alleviate out-of-memory errors and large processing overhead on sites with many posts

= Version 6.9.4 (August 31, 2011) =
* Bugfix: Fixed mb_strtolower errors that appeared on some PHP setups (bug introduced in 6.9.2)

= Version 6.9.3 (August 22, 2011) =
* Bugfix: Fixed bug in Rich Snippet Creator that caused raw HTML to be outputted in posts (bug introduced in 6.9.2)
* Bugfix: Added missing textdomains to some strings

= Version 6.9.2 (August 20, 2011) =
* Feature: The Siloed Categories widget now lets you choose whether or not to use a term's description as the value of its link title attribute
* Bugfix: The Siloed Categories widget now hides itself on the archives of empty categories instead of outputting its header and an empty list
* Bugfix: Fixed the SEO Settings box's "Autolink Exclusion" checkbox, which broke in 6.8
* Bugfix: Fixed Link Mask Generator and Deeplink Juggernaut features that broke when WordPress's Site URL option had capitalization in the domain name
* Bugfix: Slug Optimizer no longer messes up Unicode characters in slugs
* Bugfix: SEO Ultimate's uninstallation routine no longer leaves behind module data
* Bugfix: Fixed additional errors that could have appeared when WP_DEBUG mode was enabled
* Security Fix: Rich Snippet Creator now properly escapes HTML attributes

= Version 6.9.1 (July 21, 2011) =
* Bugfix: Fixed invalid callback errors that appeared on the post editor pages for some users

= Version 6.9 (July 20, 2011) =
* New Module: Settings Monitor
* Bugfix: Deeplink Juggernaut CSV import/export now maintains Site Cap values

= Version 6.8 (July 18, 2011) =
* Feature: Title Tag Rewriter now supports `wp_title` filtering in addition to output buffering. A new Settings field lets you choose between the two rewrite methods.
* Feature: Deeplink Juggernaut now lets you control which post types have autolinks added to them

= Version 6.7 (July 16, 2011) =
* Feature: Deeplink Juggernaut now lets you specify per-link sitewide quantity caps (just go to "Content Link Settings," check "Enable per-link customization of quantity limits," click "Save Changes," and then edit "Site Cap" fields under "Content Links")
* Feature: Deeplink Juggernaut now lets you limit the number of times a post can link to the same destination
* Feature: Deeplink Juggernaut now lets you decide whether to allow posts to link to the URL by which the visitor is accessing the post
* Bugfix: Deeplink Juggernaut's sitewide link quantity restriction now takes into account the "Autolink Exclusion" checkbox in the "SEO Settings" post meta box
* Bugfix: Restored "Hidden" option for disabled modules in Module Manager (broke in 6.4)
* Bugfix: Fixed minor aesthetic issue in Module Manager that appears in WordPress 3.2

= Version 6.6 (July 15, 2011) =
* New Module: SEO Ultimate Widgets
	* The new "Footer Links" widget lets you display your Deeplink Juggernaut Footer Links in a widgetized footer or sidebar
	* The new "Siloed Categories" widget lets you create a navigation section that's siloed around a taxonomy of your choosing

= Version 6.5.2 (July 13, 2011) =
* Bugfix: Restored access to the "Global" tab of Meta Robot Tags Editor (broke in 6.4)

= Version 6.5.1 (July 11, 2011) =
* Bugfix: Restored Deeplink Juggernaut's ability to link to arbitrary URLs (broke in 6.5)

= Version 6.5 (July 9, 2011) =
* Feature: Added "Footer Links" functionality to Deeplink Juggernaut
* Feature: Deeplink Juggernaut's autocomplete textboxes now include author archives
* Bugfix: Fixed broken "Blog Homepage" option in Deeplink Juggernaut
* Bugfix: Replaced a deprecated function call in Canonicalizer that generated a warning in WP_DEBUG mode

= Version 6.4 (July 8, 2011) =
* Feature: Deeplink Juggernaut can now limit the number of times an anchor is autolinked across your entire site
* Feature: Deeplink Juggernaut's autocomplete textboxes now include a "Blog Homepage" option
* Feature: Rich Snippet Creator now supports a "Name of Reviewed Item" field for reviews
* Feature: Deeplink Juggernaut now lets you stop autolinks from being added within HTML tags of your choosing
* Improvement: By default, Deeplink Juggernaut no longer adds autolinks inside h1/h2/h3/h4/h5/h6 tags
* Improvement: Added activity indicators to Deeplink Juggernaut autocomplete textboxes
* Improvement: 404 Monitor now truncates long URLs (hold your mouse over a truncated URL to view it in its entirety)
* Improvement: Tweaked the Deeplink Juggernaut interface to better fit a 1024x768 screen resolution
* Updated WordPress.org plugin page screenshots
* Bugfix: 404 Monitor now adheres to the maximum log size instead of 1 less than the specified max size
* Bugfix: SEO menu icon now properly colorizes when the menu is active
* Bugfix: Fixed aesthetic issue in Deeplink Juggernaut
* Bugfix: Fixed problem with quotes in Deeplink Juggernaut URLs and title attributes
* Bugfix: Module Manager's anchor links to sections of the "Miscellaneous" page now work
* Bugfix: Fixed more errors that appeared when WP_DEBUG mode was enabled
* Security Fix: Fixed potential vulnerability in 404 Monitor
* Change: Meta Keywords Editor is now disabled by default for new SEO Ultimate users
* Change: Deeplink Juggernaut now treats all destinations with slashes as URLs
* Compatibility: SEO Ultimate now supports and requires WordPress 3.2
* Known Issue: The "Screen Options" tab in 404 Monitor no longer works in WordPress 3.1; upgrade to WordPress 3.2 to resolve the issue

= Version 6.3 (June 27, 2011) =
* Feature: Canonicalizer can now send Google's newly-supported `Link: <url>; rel="canonical"` HTTP headers

= Version 6.2 (June 24, 2011) =
* Feature: Added a "Silo Linking" mode to Deeplink Juggernaut that lets you confine autolinks to posts of the same category (or tag/term)

= Version 6.1 (June 23, 2011) =
* Feature: Link Mask Generator now lets you add the `rel="nofollow"` attribute to all masked links
* Bugfix: Fixed bug that caused some link masks to disappear when the post was saved
* Bugfix: Fixed an issue with masking links with ampersands

= Version 6.0 (June 21, 2011) =
* Feature: Deeplink Juggernaut's "Destination Type" dropdowns have been replaced with snazzy autocomplete textboxes that let you link to a content item on your blog by typing the first few letters of its title
* Feature: Deeplink Juggernaut now lets you autolink to categories, tags, post format archives, and custom taxonomy term archives
* Improvement: The meta description field of the "SEO Settings" post box now indicates search engines use around 140 characters instead of 160, to better reflect current search engine trends
* Improvement: Improved layout of Meta Robot Tags mass-editors
* Bugfix: Deeplink Juggernaut's admin page no longer overruns memory limits on sites with lots of content
* Bugfix: Fixed errors that appeared on Deeplink Juggernaut's admin page when WP_DEBUG mode was enabled
* Bugfix: Fixed errors generated by Rich Snippet Creator when WP_DEBUG mode was enabled

= Version 5.9 (June 17, 2011) =
* Feature: Meta Description Editor now supports default formats for category/tag archives, starting with support for the `{description}` variable.
* Feature: Meta Keywords Editor now has mass-editors for categories, tags, post format archives, and custom taxonomy term archives
* Improvement: Added "Reset" links to the format textboxes in Meta Description Editor
* Bugfix: Fixed Whitepapers module errors that appeared with WP_DEBUG mode enabled

= Version 5.8 (June 3, 2011) =
* Feature: Added the Permalink Tweaker module, which lets you remove permalink bases for categories, tags, and/or custom taxonomies
* Improvement: Module pages with few settings have been grouped into a new "Miscellaneous" admin page (to turn off this behavior, disable the Miscellaneous module in the Module Manager)
* Bugfix: Module Manager changes are now reflected immediately after clicking "Save Changes"
* Bugfix: Removed the "|Dropdown Title" that appeared at the end of contextual help dropdown titles
* Bugfix: Updated contextual help dropdown styling to work with WordPress 3.1
* Bugfix: Fixed errors that appeared when saving posts with WP_DEBUG mode enabled

= Version 5.7 (June 2, 2011) =
* Feature: Title Tag Rewriter, Meta Descriptions Editor, and Meta Robot Tags Editor now officially support mass-editing post format archives (requires WordPress 3.1 or later)
* Feature: SEO Ultimate now alerts users when they're about to leave a module admin page that has unsaved changes
* Improvement: Custom taxonomy mass-editors now support taxonomies that are registered only with pages or custom post types
* Improvement: Taxonomies registered without `show_ui` support no longer have "Edit" links in the mass-editors
* Bugfix: Fixed the mass-editors' category edit links, which broke starting with WordPress 3.0
* Bugfix: Fixed a bug that hindered Title Tag Rewriter from rewriting custom taxonomy archives' `<title>` tags
* Bugfix: Fixed more errors that appeared when WP_DEBUG mode was enabled

= Version 5.6.2 (June 1, 2011) =
* Bugfix: Fixed bug that stopped settings from being saved (introduced in 5.6.1)
* Bugfix: Fixed bug that disabled mass-editors (introduced in 5.6.1)
* Bugfix: Fixed more errors that appeared when WP_DEBUG mode was enabled

= Version 5.6.1 (May 31, 2011) =
* Bugfix: Fixed many errors that appeared when WP_DEBUG mode was enabled

= Version 5.6 (May 27, 2011) =
* Feature: Added the Nofollow Manager module (disabled by default)

= Version 5.5.1 (May 21, 2011) =
* Bugfix: Link Mask Generator can now mask links that are created in the Visual Editor and whose URLs contain ampersands
* Improvement: When the "Convert lowercase category/tag names to title case when used in title tags" option was checked and a term title had some capitalization, Title Tag Rewriter used to leave the entire term title alone; now it title-cases just the words without capitalization and leaves the capitalized words alone (so with the option enabled, the "iPod tips" category now becomes "iPod Tips" when used in title tags, for example)

= Version 5.5 (May 20, 2011) =
* Feature: Meta Robot Tags Editor now has a noindex/nofollow mass-editor for categories, tags, and custom taxonomy terms

= Version 5.4 (May 19, 2011) =
* Feature: Meta Robot Tags Editor now has a noindex/nofollow mass-editor for posts, pages, and custom post types

= Version 5.3 (May 18, 2011) =
* Feature: Meta Keywords Editor now lets you auto-generate keywords based on the words most commonly used in your posts, pages, and custom post types
* Improvement: Meta Keywords Editor now removes duplicate keywords case-insensitively

= Version 5.2 (May 17, 2011) =
* Feature: Meta Description Editor now has a mass-editor for categories, tags, and custom taxonomy terms

= Version 5.1 (May 16, 2011) =
* Feature: Meta Keywords Editor can now auto-generate keywords for posts and custom post types using categories, tags, and custom taxonomy terms

= Version 5.0.1 (May 14, 2011) =
* Bugfix: Meta Description Editor no longer calls the `the_content` filter, since doing so broke some plugins that would add their content to that filter only once per page load
* Bugfix: Added additional input filtering to 404 Monitor settings

= Version 5.0 (May 13, 2011) =
* Improvement: Revamped database infrastructure for improved efficiency
* Feature: Title Tag Rewriter supports a new `{page_parent}` variable
* Bugfix: Links' "Destination Type" values are now preserved when importing Deeplink Juggernaut CSV files
* This is a major upgrade, so please backup your database first!

= Version 4.9 (May 12, 2011) =
* Feature: You can now change the Link Mask Generator's alias directory to something other than `/go/`
* Feature: You can now use the `{excerpt::autogen}` variable in the Meta Description Editor to auto-generate an excerpt if the post doesn't have one (a la the `the_excerpt()` template tag)
* Compatibility: SEO Ultimate now requires WordPress 3.0 or above

= Version 4.8.1 (March 1, 2011) =
* Improvement: Deeplink Juggernaut no longer inserts links within `<code>`, `<pre>`, and `<kbd>` elements
* Bugfix: Link Mask Generator can now properly mask URLs with ampersands
* Bugfix: Title Tag Rewriter now properly rewrites custom taxonomy term pages

= Version 4.8 (February 7, 2011) =
* Feature: Deeplink Juggernaut now lets you limit the number of times per post the same anchor text is linked
* Feature: Deeplink Juggernaut now lets you toggle whether posts can link to themselves
* Improvement: When configured to prevent posts from linking to themselves, Deeplink Juggernaut now does so across the whole site, not just on the post's own single page

= Version 4.7.1 (December 30, 2010) =
* Bugfix: Editing link mask slugs now removes the old link mask instead of just adding a second one
* Bugfix: Link Mask Generator now runs before WordPress' canonical function in order to stop WordPress from overriding a link mask

= Version 4.7 (December 28, 2010) =
* Feature: Added the Link Mask Generator module
* Bugfix: More Link Customizer now fails silently if only 1 parameter is passed to the `the_content_more_link` filter.
* Bugfix: Fixed array_combine() errors by adding PHP4 back-compatibility function

= Version 4.6 (December 23, 2010) =
* Feature: Added meta keywords mass-editor for posts, pages, attachments, and custom post types

= Version 4.5.4 (December 22, 2010) =
* Bugfix: "New window" checkboxes now stay checked in Deeplink Juggernaut
* Bugfix: Fixed textdomain path
* Improvement: Post destination options in Deeplink Juggernaut are now sorted by title, making it easier to find the target post

= Version 4.5.3 (August 18, 2010) =
* Bugfix: A third fix for Deeplink Juggernaut URL-loss problem

= Version 4.5.2 (August 18, 2010) =
* Bugfix: A second fix for Deeplink Juggernaut URL-loss problem

= Version 4.5.1 (August 14, 2010) =
* Bugfix: Fix for Deeplink Juggernaut URL-loss problem (bug introduced in 3.9)

= Version 4.5 (August 13, 2010) =
* Improvement: SEO Ultimate now only saves database data when its settings are updated
* Bugfix: Fixed array_combine() error when importing CSV files
* Bugfix: Restored htaccess editing for non-multisite installations, fixing a feature regression introduced in version 3.9
* Bugfix: The "new window" option no longer checks itself by default in Deeplink Juggernaut
* Bugfix: Fixed conflict with the "Markdown for WordPress and bbPress" plugin

= Version 4.4 (August 7, 2010) =
* Feature: Added new "Global Keywords" textbox to Meta Keywords Editor, which allows sitewide keywords to be specified
* Improvement: Meta Keywords Editor now removes duplicate keywords from the meta keywords tag
* Improvement: Meta Keywords Editor now fixes keywords that are incorrectly separated with newlines instead of commas
* Improvement: Meta Keywords Editor now removes extra spaces from the keywords list
* Improvement: Title Tag Rewriter can now rewrite empty `<title></title>` tags
* Bugfix: The SEO menu no longer doubles the alert count of modules

= Version 4.3 (August 6, 2010) =
* Feature: Added new meta description format field for posts (allows you to set a default post meta description incorporating the post's excerpt)

= Version 4.2 (August 5, 2010) =
* Feature: Users can now stop autolinks from being added to specific posts/pages

= Version 4.1 (August 4, 2010) =
* Feature: Title Tag Rewriter now automatically converts lowercase category/tag names into title case when used in title tags (can be adjusted under the new "Settings" tab of Title Tag Rewriter)

= Version 4.0.1 (August 3, 2010) =
* Improvement: Added admin page documentation for the Meta Description Editor, Meta Keywords Editor, Meta Robot Tags Editor, and Webmaster Verification Assistant modules

= Version 4.0 (August 3, 2010) =
* Feature: Added meta description mass-editor for posts, pages, attachments, and custom post types
* Change: Meta Editor has been split into four new modules: Meta Descriptions, Meta Keywords, Meta Robot Tags, and Webmaster Verification
* Change: The Noindex Manager module can now be found under new Meta Robot Tags module
* Improvement: When entire `<meta>` tags are entered instead of verification codes, the Webmaster Verification Assistant will now output the tag properly

= Version 3.9 (August 2, 2010) =
* Feature: Added "Instant Post Propulsion" feature to Deeplink Juggernaut (new "Incoming Autolink Anchors" postmeta field)
* Improvement: File Editor now limits .htaccess editing to super admins on multisite installations
* Improvement: Deeplink Juggernaut now gives priority to links with longer anchor text
* Improvement: Deeplink Juggernaut no longer links webpages to themselves
* Improvement: Deeplink Juggernaut's post/page dropdowns now include drafts; now you can build links to in-progress posts that automatically enable when the post is published!
* Improvement: When a post/page is sent to the trash, autolinks pointing to it no longer disappear from the Deeplink Juggernaut interface
* Improvement: Added a workaround for Firefox so that "Destination Type" dropdowns in Deeplink Juggernaut no longer get "stuck" on the incorrect value
* Improvement: Miscellaneous aesthetic changes
* Bugfix: Removed duplicate "title" attribute from "Deeplink Juggernaut" links
* Bugfix: Fixed bug that caused "New window" option to enable itself on Deeplink Juggernaut autolinks
* Bugfix: Contextual help dropdowns are now styled correctly in WordPress 3.0+
* Bugfix: Contextual help dropdowns no longer generate 404 errors in WordPress 3.0+
* Bugfix: Fixed invalid HTML in admin interface
* Bugfix: Fixed many warnings that appeared when WP_DEBUG was enabled

= Version 3.8 (July 30, 2010) =
* Feature: Deeplink Juggernaut can now link directly to posts/pages (and custom post types) in addition to arbitrary URLs
* Bugfix: Readded SEO Ultimate upgrade info to the `Dashboard > Updates` (or `Tools > Upgrade`) page, fixing a feature regression introduced in version 3.1

= Version 3.7.1 (July 1, 2010) =
* Bugfix: Fixed fatal error on editor screens for custom post types

= Version 3.7 (June 30, 2010) =
* Feature: "SEO Settings" box now added to editing screens for custom post types
* Bugfix: Fixed invalid HTML in the admin interfaces of Noindex Manager and Sharing Facilitator

= Version 3.6 (June 28, 2010) =
* Feature: Users can now set the maximum number of log entries that 404 Monitor will keep at a time
* Improvement: Fixed aesthetic issue that appeared in SEO Settings box under certain configurations
* Bugfix: Browsers will no longer jump to the top of the screen when expanding/collapsing the Referer/User Agent lists in 404 Monitor
* Bugfix: Fixed invalid HTML in the admin interface of 404 Monitor
* Bugfix: Fixed PHP4 error by removing usage of PHP5-only str_split function

= Version 3.5 (June 26, 2010) =
* Feature: Added the Sharing Facilitator module
* Feature: Rich Snippet Creator now supports half-star ratings (0.5, 1.5, 2.5, etc) for reviews
* Improvement: Removed unnecessary double-quotes from code

= Version 3.4.1 (June 25, 2010) =
* Bugfix: Fixed fatal error on 404 pages
* Bugfix: Fixed issue where 404 errors weren't being logged

= Version 3.4 (June 25, 2010) =
* Feature: 404 Monitor can now ignore specific URLs and/or wildcard URL patterns
* Bugfix: 404 Monitor no longer logs the same referer more than once

= Version 3.3 (June 24, 2010) =
* Feature: 404 Monitor can now be configured to only log 404 errors generated by search engine spiders and/or 404 errors with a referring URL (Note: this new configuration will be enabled automatically upon upgrading to version 3.3 or newer. You can adjust or disable this configuration on the Settings tab of 404 Monitor.)

= Version 3.2 (June 23, 2010) =
* Feature: Added CSV import/export for Deeplink Juggernaut
* Improvement: When installed on an old, unsupported version of WordPress, SEO Ultimate now presents a nice error message instead of crashing like most plugins do
* Bugfix: Fixed PHP error that would appear on Upgrade/Downgrade tabs upon WordPress API error
* Bugfix: Fixed SEO Ultimate settings importer

= Version 3.1 (June 22, 2010) =
* Feature: Deeplink Juggernaut now supports unlimited autolinks instead of just 20
* Feature: Deeplink Juggernaut now supports custom title attributes for autolinks
* Feature: Deeplink Juggernaut interface now has convenient checkboxes for deleting autolinks
* Feature: Deeplink Juggernaut autolinks can now open in new windows if desired
* Deeplink Juggernaut is now out of beta
* Improvement: SEO Ultimate upgrade notices now use official WordPress plugin API
* Bugfix: SEO Ultimate no longer slows down the "Plugins" admin page excessively

= Version 3.0 (June 19, 2010) =
* Feature: Added the Rich Snippet Creator module
* Change: "Title Rewriter" has been renamed to "Title Tag Rewriter" to clarify that the module edits `<title>` tags, not post titles

= Version 2.9.1 (June 18, 2010) =
* Bugfix: Restored support for editing the title tags of categories/tags/terms in WordPress 3.0

= Version 2.9 (June 17, 2010) =
* Feature: Title Rewriter now has mass-editor tabs for custom taxonomies
* Feature: Title Rewriter mass-editors now support pagination
* Improvement: Upgrade/downgrade tabs now use official WordPress plugin API to obtain version info

= Version 2.8 (June 8, 2010) =
* Feature: Title Rewriter now has mass-editor tabs for custom post types
* Feature: Title Rewriter can now edit the title tags of attachments
* Improvement: Title Rewriter's mass-editors no longer display an empty table when no items of a particular type exist

= Version 2.7 (June 4, 2010) =
* Feature: Added Code Inserter module
* Change: Meta Editor's "Custom HTML Code" field is now the "`<head>` Tag" field in the new Code Inserter module

= Version 2.6 (June 3, 2010) =
* Feature: Users can now reinstall a fresh copy of the plugin from within `Settings > SEO Ultimate > Reinstall` in case, for example, custom modifications go awry

= Version 2.5.1 (June 1, 2010) =
* Bugfix: Fixed "string offset" fatal error that appeared on certain setups

= Version 2.5 (June 1, 2010) =
* Feature: Users can now upgrade/downgrade SEO Ultimate to versions of their choosing starting with 2.5
* Bugfix: Fixed "string offset" fatal error that appeared on certain setups

= Version 2.4 (May 28, 2010) =
* Feature: Added nofollow option for Deeplink Juggernaut links

= Version 2.3 (May 26, 2010) =
* Feature: Meta robots tags (index/noindex and follow/nofollow) can now be set for each post or page via the "SEO Settings" box
* Behavior Change: Since the Noindex Manager's advertised functionality is controlling the "noindex" attribute only, its behavior has been changed to output "noindex,follow" where it previously outputted "noindex,nofollow"

= Version 2.2 (May 24, 2010) =
* Feature: Deeplink Juggernaut now has a links-per-post limiter option
* Bugfix: The current tab is now maintained when submitting a tabbed form twice in a row
* Bugfix: When a module page reloads after submitting a tabbed form, the screen no longer jumps part-way down the page

= Version 2.1.1 (May 19, 2010) =
* Bugfix: Fixed "get_table_name" fatal error that appeared when upgrading certain configurations
* Bugfix: Restored missing success/error messages for import/reset functions

= Version 2.1 (May 18, 2010) =
* Improvement: Major 404 Monitor upgrade, featuring a new space-saving interface redesign
* Improvement: 404 Monitor now stores its 404 log in wp_options instead of its own database table
* Improvement: 404 Monitor now ignores apple-touch-icon.png 404s
* Improvement: Plugin now silently ignores a missing readme.txt instead of giving error
* Improvement: CSS and JavaScript now exist in separate, static files instead of being outputted by PHP files
* Improvement: SEO Ultimate settings now remain when plugin files are deleted; settings can now be deleted through new "Uninstall" function under `Settings > SEO Ultimate > Uninstall`
* Improvement: Database usage for the Whitepapers module reduced more than 90%
* Improvement: Users can now tab from a post's HTML editor directly into the "SEO Settings" fields
* Improvement: Removed blank admin CSS/JS file references
* Improvement: Added list of active modules to SEO Ultimate's plugin page listing
* Improvement: Added an "Uninstall" link to SEO Ultimate's plugin page listing
* Improvement: Update info notices now also visible under `Tools > Upgrade`
* Improvement: Added some missing documentation
* Improvement: Added/updated screenshots
* Improvement: Removed unused code
* Improvement: Added blank index.php files to module directories to prevent indexing/snooping of directory listings
* Feature: You can now hide 404 Monitor columns with the new "Screen Options" dropdown
* Bugfix: Removed duplicate excerpt ellipses from Whitepapers module
* Known Issue: If you had previously disabled 404 Monitor in version 2.0 or earlier, it will re-enable itself when upgrading to version 2.1 or later. The workaround is to re-disable 404 Monitor from the Module Manager after upgrading.

= Version 2.0 (April 29, 2010) =
* Feature: Title Rewriter can now edit the title tags of post tag archives

= Version 1.9 (April 3, 2010) =
* Feature: Title Rewriter can now edit the title tags of category archives

= Version 1.8.3 (March 30, 2010) =
* Bugfix: Fixed bug that caused disabled attribution link to display under certain circumstances

= Version 1.8.2 (March 29, 2010) =
* Bugfix: Fixed front-end Deeplink Juggernaut error

= Version 1.8.1 (March 27, 2010) =
* Bugfix: Fixed back-end Deeplink Juggernaut error

= Version 1.8 (March 27, 2010) =
* Feature: Added Deeplink Juggernaut beta module

= Version 1.7.3 (March 11, 2010) =
* Bugfix: Fixed variable name conflict introduced in 1.7.1 that disabled WordPress's plugin/theme editors

= Version 1.7.2 (March 6, 2010) =
* Bugfix: Fixed blank-admin-area bug in WordPress 3.0 alpha

= Version 1.7.1 (February 27, 2010) =
* Bugfix: Fixed conflict with Flexibility theme
* Bugfix: Comment administration no longer alters SEO Ultimate menu bubble counters
* Bugfix: SEO Ultimate menu icon is no longer accidentally added to other plugins' menus
* Bugfix: Disabling visitor logging now disables all related code as well
* Bugfix: Module Manager: Fixed invalid HTML IDs
* Bugfix: Module Manager: Module titles are now consistent between enabled and disabled states
* Bugfix: Module Manager: The "Silenced" option no longer disappears when all modules that support it are disabled
* Bugfix: Module Manager: The "Plugin Settings" module link no longer breaks when re-enabling that module
* Improvement: Added blank index.php files to additional plugin directories

= Version 1.7 (February 20, 2010) =
* Feature: Displays admin notices if blog privacy settings are configured to block search engines

= Version 1.6 (January 30, 2010) =
* Feature: Added All in One SEO Pack importer module

= Version 1.5.3 (January 27, 2010) =
* Bugfix: Fixed "get_parent_module_key" fatal error that appeared under limited circumstances
* Bugfix: Fixed "load_rss" fatal error that appeared under some circumstances
* Bugfix: Fixed broken image in the Whitepapers module

= Version 1.5.2 (January 25, 2010) =
* Bugfix: Uninstallation now works when the plugin is deactivated

= Version 1.5.1 (January 23, 2010) =
* Bugfix: Stopped the included Markdown library from "helpfully" functioning as a WordPress plugin
* Bugfix: Fixed error that appeared above changelog notices

= Version 1.5 (January 23, 2010) =
* Major under-the-hood changes and improvements
* Feature: Added new {url_words} title format variable to Title Rewriter
* Bugfix: Fixed broken link in the "SEO Settings" contextual help dropdown
* Improvement: Module documentation now loaded directly from the readme file (eliminates duplication)
* Improvement: Much more documentation now available from within the plugin
* Improvement: Module Manager now only shows the "Silenced" option for applicable modules
* Improvement: Cleaned root folder (now includes only the readme, screenshots, plugin file, POT file, and blank index.php)
* Improvement: Reduced database usage when saving post meta

= Version 1.4.1 (January 11, 2010) =
* Compatibility: Meta Editor now supports the new Google Webmaster Tools verification code

= Version 1.4 (December 16, 2009) =
* Feature: Added the Internal Relevance Researcher
* Bugfix: Title Rewriter no longer rewrites XML `<title>` tags in feeds
* Improvement: Copied all documentation to the readme.txt file

= Version 1.3 (November 13, 2009) =
* Feature: Added the More Link Customizer module
* Bugfix: Postmeta fields now handle HTML entities properly
* Improvement: Made minor tweaks to the Competition Researcher

= Version 1.2 (October 31, 2009) =
* Feature: Added the Competition Researcher module

= Version 1.1.2 (October 9, 2009) =
* Compatibility: Added PHP4 support

= Version 1.1.1 (October 8, 2009) =
* Bugfix: Fixed tab rendering bug

= Version 1.1 (October 7, 2009) =
* Feature: You can now mass-edit post/page titles from the Title Rewriter module
* Bugfix: Fixed logo background color in the Whitepapers module
* Improvement: Title Rewriter now supports 10 additional title format variables
* Improvement: Added internationalization support for admin menu notice numbers
* Improvement: Certain third-party plugin notices are now removed from SEO Ultimate's admin pages

= Version 1.0 (September 21, 2009) =
* Feature: Canonicalizer can now redirect requests for nonexistent pagination
* Feature: Visitor logging can now be disabled completely from the Plugin Settings page
* Feature: Logged visitor information can now be automatically deleted after a certain number of days
* Feature: Added icon support for the Ozh Admin Drop Down Menu plugin
* Bugfix: 404 Monitor notification count now consistent with new errors shown
* Improvement: Canonicalizer now removes the duplicate canonical tags produced by WordPress 2.9-rare
* Improvement: Inline changelogs now won't display if the Changelogger plugin is activated
* Improvement: SEO Ultimate now selectively logs visitors based on which modules are enabled

= Version 0.9.3 (August 1, 2009) =
* Bugfix: Optimized slugs save with post
* Bugfix: Slug Optimizer now treats words as case-insensitive
* Bugfix: Slug Optimizer now handles words with apostrophes

= Version 0.9.1 (August 1, 2009) =
* Bugfix: Fixed PHP parse errors

= Version 0.9 (August 1, 2009) =
* Feature: Added the Slug Optimizer module
* Feature: Noindex Manager now supports noindexing comment subpages
* Bugfix: 404 Monitor's numeric notice now only includes new 404s
* Bugfix: Linkbox Inserter now respects the "more" tag
* Bugfix: Missing strings added to the POT file
* Improvement: 404 Monitor now shows the referring URL for all 404 errors
* Improvement: Reduced the number of database queries the plugin makes
* Improvement: CSS and JavaScript are now only loaded when appropriate
* Improvement: Added additional built-in documentation
* Improvement: Divided built-in help into multiple tabs to reduce dropdown height
* Improvement: Miscellaneous code efficiency improvements
* Improvement: Many additional code comments added

= Version 0.8 (July 22, 2009) =
* Feature: Added robots.txt editor (new File Editor module)
* Feature: Added .htaccess editor (new File Editor module)
* Bugfix: 404 Monitor no longer uses the unreliable get_browser() function
* Bugfix: 404 Monitor now ignores favicon requests
* Bugfix: Fixed conflict with the WP Table Reloaded plugin
* Bugfix: Fixed bug that caused Module Manager to appear blank on certain configurations
* Bugfix: Fixed bug that caused multiple drafts to be saved per post
* Bugfix: Post meta box no longer leaves behind empty postmeta database rows
* Bugfix: Added missing Module Manager help
* Bugfix: Fixed settings double-serialization bug
* Bugfix: Fixed error that appeared when re-enabling disabled modules
* Bugfix: Newlines and tabs now removed from HTML attributes
* Improvement: SEO Ultimate now stores its wp_options data in 1 entry instead of 4
* Improvement: The settings read/write process has been streamlined
* Improvement: Drastically expanded the readme.txt FAQ section
* Improvement: Plugin's directories now return 403 codes
* Improvement: Settings importer now retains the settings of modules added after the export

= Version 0.7 (July 16, 2009) =
* Feature: Added the Module Manager
* Feature: Modules can optionally display numeric notices in the menu

= Version 0.6 (July 2, 2009) =
* Feature: Added the Linkbox Inserter module
* Bugfix: Fixed plugin notices bug

= Version 0.5 (June 25, 2009) =
* Feature: Added settings exporter
* Feature: Added settings importer
* Feature: Added button that restores default settings
* Bugfix: Fixed bug that decoded HTML entities in textboxes
* Bugfix: Added internationalization support to some overlooked strings
* Compatibility: Restores support for the WordPress 2.7 branch

= Version 0.4 (June 18, 2009) =
* Added the 404 Monitor module

= Version 0.3 (June 11, 2009) =
* Added the Canonicalizer module
* Added alerts of possible plugin conflicts
* Fixed a WordPress 2.8 compatibility issue
* SEO Ultimate now requires WordPress 2.8 or above

= Version 0.2 (June 4, 2009) =
* Added the Meta Editor module
* Fixed a double-escaping bug in the Title Rewriter
* Fixed a bug that caused the Modules list to display twice on some installations

= Version 0.1.1 (May 28, 2009) =
* Fixed a bug that surfaced when other SEO plugins were installed
* Fixed a bug that appeared on certain PHP setups

= Version 0.1 (May 22, 2009) =
* Initial release
