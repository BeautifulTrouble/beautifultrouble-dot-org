=== Plugin Name ===
Contributors: dglingren
Donate link: http://fairtradejudaica.org/make-a-difference/donate/
Tags: attachment, attachments, documents, gallery, image, images, media, library, media library, media-tags, media tags, tags, media categories, categories, IPTC, EXIF, meta, metadata, photo, photos, photograph, photographs, photoblog, photo albums, lightroom, photoshop, MIME, mime-type, icon, upload, file extensions
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 1.41
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enhances the Media Library; powerful [mla_gallery], taxonomy support, IPTC/EXIF processing, bulk & quick edit actions and where-used reporting.

== Description ==

The Media Library Assistant provides several enhancements for managing the Media Library, including:

* The **`[mla_gallery]` shortcode**, used in a post, page or custom post type to add a gallery of images and/or other Media Library items (such as PDF documents). [MLA Gallery](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Complete Documentation") is a superset of the WordPress `[gallery]` shortcode; it is compatible with `[gallery]` and provides many enhancements. These include: 1) full query and display support for WordPress categories, tags, custom taxonomies and custom fields, 2) support for all post_mime_type values, not just images 3) media Library items need not be "attached" to the post, and 4) control over the styles, markup and content of each gallery using Style and Markup Templates.

* **Attachment metadata** such as file size, image dimensions and where-used information can be assigned to WordPress custom fields. You can then use the custom fields in your `[mla_gallery]` display and you can add custom fields as sortable, searchable columns in the Media/Assistant submenu table.

* **IPTC** and **EXIF** metadata can be assigned to standard WordPress fields, taxonomy terms and custom fields. You can update all existing attachments from the Settings page IPTC/EXIF tab, groups of existing attachments with a Bulk Action or one existing attachment from the Edit Media/Edit Single Item screen. Display **IPTC** and **EXIF** metadata with `[mla_gallery]` custom templates.

* Complete control over **Post MIME Types, File Upload extensions/MIME Types and file type icon images**. Fifty four (54) additional upload types, 112 file type icon images and a searchable list of over 1,500 file extension/MIME type associations.

* **Integrates with Photonic Gallery** (plugin), so you can add slideshows, thumbnail strips and special effects to your `[mla_gallery]` galleries.

* **Enhanced Search Media box**. Search can be extended to the name/slug, ALT text and caption fields. The connector between search terms can be "and" or "or". Search by attachment ID is supported.

* **Where-used reporting** shows which posts use a media item as the "featured image", an inserted image or link, an entry in a `[gallery]` and/or an entry in an `[mla_gallery]`.
* **Complete support for ALL taxonomies**, including the standard Categories and Tags, your custom taxonomies and the Assistant's pre-defined Att. Categories and Att. Tags. You can add taxonomy columns to the Assistant listing, filter on any taxonomy, assign terms and list the attachments for a term.
* An inline **"Bulk Edit"** area; update author, parent and custom fields, add, remove or replace taxonomy terms for several attachments at once
* An inline **"Quick Edit"** action for many common fields and for custom fields
* Displays more attachment information such as parent information, file URL and image metadata. Uses and enhances the new Edit Media screen for WordPress 3.5 and above.
* Allows you to edit the post_parent, the menu_order and to "unattach" items
* Provides additional view filters for MIME types and taxonomies
* Provides many more listing columns (more than 20) to choose from

The Assistant is designed to work like the standard Media Library pages, so the learning curve is short and gentle. Contextual help is provided on every new screen to highlight new features.

This plugin was inspired by my work on the WordPress web site for our nonprofit, Fair Trade Judaica. If you find the Media Library Assistant plugin useful and would like to support a great cause, consider a [<strong>tax-deductible</strong> donation](http://fairtradejudaica.org/make-a-difference/donate/ "Support Our Work") to our work. Thank you!

== Installation ==

1. Upload `media-library-assistant` and its subfolders to your `/wp-content/plugins/` directory
1. Activate the plugin through the "Plugins" menu in WordPress
1. Visit the Settings page to customize category and tag support
1. Visit the Settings page Custom Fields and IPTC/EXIF tabs to map metadata to attachment fields
1. Visit the "Assistant" submenu in the Media admin section
1. Click the Screen Options link to customize the display
1. Use the enhanced Edit, Quick Edit and Bulk Edit pages to assign categories and tags
1. Use the `[mla_gallery]` shortcode to add galleries of images, documents and more to your posts and pages

== Frequently Asked Questions ==

= How can I sort the Media/Assistant submenu table on values such as File Size? =

You can add support for many attachment metadata values such as file size by visiting the Custom Fields tab on the Settings page. There you can define a rule that maps the data to a WordPress custom field and check the "MLA Column" box to make that field a sortable column in the Media/Assistant submenu table. You can also use the field in your `[mla_gallery]` shortcodes.

= How can I use Categories, Tags and custom taxonomies to select images for display in my posts and pages? =

The powerful `[mla_gallery]` shortcode supports almost all of the query flexibility provided by the WP_Query class. You can find [complete documentation](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Complete Documentation") in the Other Notes section.

= Can I use `[mla_gallery]` for attachments other than images? =

Yes! The `[mla_gallery]` shortcode supports all MIME types when you add the post_mime_type parameter to your query. You can build a gallery of your PDF documents, plain text files and other attachments. You can mix images and other MIME types in the same gallery, too; check out [the documentation](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Complete Documentation").

= Can I attach an image to more than one post or page? =

No; that's a structural limitation of the WordPress database. However, you can use Categories, Tags and custom taxonomies to organize your images and associate them with posts and pages in any way you like. The `[mla_gallery]` shortcode makes it easy.

= Can the Assistant use the standard WordPress post Categories and Tags? =

Yes! You can activate or deactivate support for Categories and Tags at any time by visiting the Media Library Assistant Settings page.

= Do I have to use the WordPress post Categories and Tags? =

No! The Assistant supplies pre-defined Att. Categories and Att. Tags; these are WordPress custom taxonomies, with all of the API support that implies. You can activate or deactivate the pre-defined taxonomies at any time by visiting the Media Library Assistant Settings page.

= Can I add my own custom taxonomies to the Assistant? =

Yes. Any custom taxonomy you register with the Attachment post type will appear in the Assistant UI. Use the Media Library Assistant Settings page to add support for your taxonomies to the Assistant UI.

= Why don't the "Posts" counts in the taxonomy edit screens match the search results when you click on them? =

This is a known WordPress problem with multiple support tickets already in Trac, e.g., 
Ticket #20708(closed defect (bug): duplicate) Wrong posts count in taxonomy table,
Ticket #14084(assigned defect (bug)) Custom taxonomy count includes draft & trashed posts,
and Ticket #14076(closed defect (bug): duplicate) Misleading post count on taxonomy screen.

For example, if you add Tags support to the Assistant and then assign tag values to your attachments, the "Posts" column in the "Tags" edit screen under the Posts admin section includes attachments in the count. If you click on the number in that column, only posts and pages are displayed. There are similar issues with custom post types and taxonomies (whether you use the Assistant or not). The "Attachments" column in the edit screens added by the Assistant shows the correct count because it works in a different way.

= How do I "unattach" an item? =

Hover over the item you want to modify and click the "Edit" action. On the Edit Single Item page, set the ID portion of the Parent Info field to zero (0), then click "Update" to record your changes. If you change your mind, click "Cancel" to return to the main page without recording any changes.

= The Media/Assistant submenu seems sluggish; is there anything I can do to make it faster? =

Some of the MLA features such as where-used reporting and ALT Text sorting/searching require a lot of database processing. If this is an issue for you, go to the Settings page and adjust the "Where-used database access tuning" settings. For any where-used category you can enable or disable processing. For the "Gallery in" and "MLA Gallery in" you can also choose to update the results on every page load or to cache the results for fifteen minutes between updates. The cache is also flushed automatically when posts, pages or attachments are inserted or updated.

= Are other language versions available? =

Not at this time; I don't have working knowledge of anything but English. If you'd like to volunteer to produce another version, I'll rework the code to internationalize it and work with you to localize it.

= What's in the "phpDocs" directory and do I need it? =

All of the MLA source code has been annotated with "DocBlocks", a special type of comment used by phpDocumentor to generate API documentation. If you'd like a deeper understanding of the code, click on "index.html" in the phpDocs directory and have a look. Note that these pages require JavaScript for much of their functionality.

== Screenshots ==

1. The Media/Assistant submenu table showing the available columns, including "Featured in", "Inserted in", "Att. Categories" and "Att. Tags"; also shows the Quick Edit area.
2. The Media/Assistant submenu table showing the Bulk Edit area with taxonomy Add, Remove and Replace options; also shows the tags suggestion popup.
3. A typical edit taxonomy page, showing the "Attachments" column.
4. The enhanced Edit page showing additional fields, categories and tags.
5. The Settings page General tab, where you can customize support of Att. Categories, Att. Tags and other taxonomies, where-used reporting and the default sort order.
6. The Settings page MLA Gallery tab, where you can add custom style and markup templates for `[mla_gallery]` shortcode output.
7. The Settings page IPTC &amp; EXIF Processing Options screen, where you can map image metadata to standard fields (e.g. caption), taxonomy terms and custom fields.
8. The Settings page Custom Field Processing Options screen, where you can map attachment metadata to custom fields for display in [mla_gallery] shortcodes and as sortable, searchable columns in the Media/Assistant submenu.
9. The Media Manager popup modal window showing additional filters for date and taxonomy terms. Also shows the enhanced Search Media box.

== Changelog ==

= 1.41 =
* New: For `[mla_gallery]`, the new `mla_output` parameter lets you get "previous_link" and "next_link" values to support moving through an `[mla_gallery]` one item at a time. Look for **Support for Alternative Gallery Output** in the Other Notes section or the Settings/Media Library Assistant Documentation tab for complete information.
* New: For `[mla_gallery]`, field-level substitution parameters now include $_REQUEST arguments. You can pass any values you need from HTML form or hyperlink variables to the Gallery Display Content parameters and to your custom style and markup templates.
* New: Hover text/tool tips, e.g., "Filter by...", "Edit..." added to most links on the Media/Assistant submenu table.
* New: The ALL_EXIF and ALL_IPTC pseudo variables now limit each field value to 256 bytes or less. Array values are included once, at their most expanded level.
* New: For `[mla_gallery]`, EXIF values containing arrays now use the ",single" and ",export" qualifiers.
* Fix: Intermittent "full height" display of attachment thumbnails has been eliminated. Attachment thumbnail is now a link to the Edit Media screen.
* Fix: EXIF and IPTC values containing invalid UTF8 characters are converted to valid UTF8 equivalents.
* Fix: When editing `[gallery]` shortcodes in the Media Manager the proper gallery contents (image thumbnails) are now returned.
* Fix: Better handling of Media/Assistant submenu table listing when returning from a Bulk Action, especially Bulk Edit. Display filters for date, category/tag and the search box are retained.
* Fix: For `[mla_gallery]`, Gallery Content Display parameters are now processed when `mla_viewer=true`.
* Fix: For `[mla_gallery]`, the default "alt" attribute (item caption) is processed when `mla_viewer=true`.
* Fix: For `[mla_gallery]`, error messages are displayed for invalid "terms:" and "custom:" substitution parameters.

= 1.40 =
* New: **"base" selection** for the where-used database access tuning "Inserted in" option **can significantly improve performance** while retaining the most useful part of the where-used information. It's on the Settings/Media Library Assistant screen, General tab.
* New: **Add Post MIME Types and define new views** for the Media/Library screen and the Media Manager/Add Media "media items" drop down list. 
* New: MLA's Media/Assistant screen and the Media Manager/Add Media "media items" drop down list use an enhanced version of the list, **Table Views**, to support views with multiple MIME Types (e.g., "audio,video") and wildcard specifications (e.g. "*/*ms*"). You can also create views based on custom field values.
* New: Add file extensions and MIME types for uploads to the Media Library. Search the list of over 1,500 extension/MIME type associations to get the best matches possible.
* New: **Choose from 112 enhanced file type images** to associate more specific and colorful icons with non-image file extensions for admin screens and `[gallery]` or `[mla_gallery]` displays.
* New: For `[mla_gallery]`, four new "Gallery Display Content" parameters, `mla_link_attributes`, `mla_image_attributes`, `mla_image_class` and `mla_image_alt`, give you complete control over the link and image portions of gallery items without requiring custom style or markup templates. 
* New: `upload_date`, `parent_date` and eight "where used" values added to the custom field data sources list.
* New: Five options for mapping multi-value custom fields, "text", "single", "export", "array" and "multi", give more control over the process.
* New: "Delete NULL values" option offers better control over storing custom field values mapped from MLA data sources.
* New: The Media/Assistant "MIME Type" column now links to a table listing filtered by MIME Type.
* Fix: Better performance for database-intensive oprations such as custom field mapping rules processing.
* Fix: MLA help tabs are not added to edit taxonomy screens when post_type is not "attachment".
* Fix: Duplicate MLA help tabs not added to the WordPress Edit Tags and Categories screens.
* Fix: Quick edit data now populates in Title/Name, Title or Name columns when ID/Parent column is hidden.
* Fix: Terms dropdown list is now sorted by name (was by term-id) on the Media/Assistant table listing and on the Media Manager "Add Media" dialog box. 
* Fix: Where-used reporting "Gallery in" and "MLA Gallery in" results now properly handle `[gallery]` and `[mla_gallery]` shortcodes embedded within other (enclosing) shortcodes.
* Fix: Taxonomy support now properly handles custom taxonomies registered with `show_ui = '1'` and other variations of boolean "true", e.g., those created by the "Magic Fields 2" plugin.
* Fix: Better error handling and reporting when processing invalid `[mla_gallery]` and `[gallery]` shortcodes.
* Fix: Unusual calls to the 'add_meta_boxes' action, e.g., missing arguments, no longer generate Warning messages.
* Fix: For `[mla_gallery]`, `mla_target` now works when `mla_viewer=true`.
* Fix: For `[mla_gallery]`, `mla_debug` now works with `mla_alt_shortcode`.
* Fix: For `[mla_gallery]`, the default `caption` value is now available to the `mla_caption` parameter.

= 1.30 =
* New: **ALL** metadata fields, including the **new fields extracted from audio and video files in WordPress 3.6**, can be mapped to custom fields and added as sortable columns to the Media/Assistant submenu table.
* New: For `[mla_gallery]`, field-level substitution parameters now include **ALL** metadata fields, including the **new fields extracted from audio and video files in WordPress 3.6**.
* New: Use `[mla_gallery]` to determine which items are included in a gallery, then pass them on to another gallery-generating shortcode for formatting and display. The new `mla_alt_shortcode` and `mla_alt_ids_name` parameters let you use any gallery-generating shortcode that accepts a list of attachment ID values. For example, you can use the WordPress.com Jetpack Carousel and Tiled Galleries to create elegant mosaic layouts and immersive full-screen experiences. The "Other Notes" section here or the Documentation tab on the Settings/Media Library Assistant page of the plugin have details.
* New: Improved User Interface for the "IPTC/EXIF" tab on the Settings/Media Library Assistant screen. The "Custom Fields" section of this screen is enhanced to match the "Custom Fields" Settings tab.
* Fix: Media Manager enhanced Search Media box more reliably re-queries the server when search parameters change.
* Fix: For `[mla_gallery]`, `posts_where` and `posts_orderby` filters now have a very low priority to run later and avoid conflicts with  other plugins, e.g, "Post Types Order".
* Fix: For `[mla_gallery]`, field-level "query" substitution parameters are now processed in all custom style and markup template parts.
* Fix: For `[mla_gallery]`, empty style and markup template parts are now allowed; empty "Open:" markup will not cause reversion to default template.
* Fix: Default `mla_style` settings now include "-- none --", to suppress generation of default inline CSS styles for the `[mla_gallery]` shortcodes.
* Fix: Improved handling and display of Custom fields with multiple values.
* Fix: For `[mla_gallery]`, `link=post` (added in WordPress 3.5) is now accepted to link gallery items to the corresponding attachment page. The `link=permalink` value continues to work as well.
* Fix: Filtering the Media/Media Library Assistant table display on custom field values with leading spaces (i.e., format=commas) now works properly.

= 1.20 =
* New: The long-awaited enhancements to the WordPress (3.5+) Media Manager (Add Media, etc.). Filter your attachments by additional MIME types, month and year uploaded and/or taxonomy terms. Keyword search can be extended to the name/slug, ALT text and caption fields. The connector between search terms can be "and" or "or". Search by attachment ID or parent ID is supported. Enable/disable any or all enhancements on the Settings page.
* New: In the `[mla_gallery]` shortcode, enhanced parameters for sorting the gallery results. For example,  `orderby=caption` and `orderby=description` are now available to sort gallery results by the Caption (post_excerpt) and Description (post_content) fields. You can also sort on multiple fields, e.g., `orderby="author, date DESC"`, with field-level ASC/DESC control. The "Other Notes" section here or the Documentation tab on the Settings/Media Library Assistant page of the plugin have details.
* New: For `[mla_gallery]`, field-level substitution parameters now include query arguments. You can pass any values you need to the Gallery Display Content parameters and to your custom style and markup templates.
* New: Gallery Display Content parameters now include `mla_link_href`, so you can change the destination and arguments of the URL your gallery items link to.
* New: Markup Substitution Parameters now include `site_url`.
* New: If the search box contains (only) a numeric value it is interpreted as a search by attachment ID **or parent ID (post_parent)**. You can search for a numeric value in the text fields, e.g., title, by putting quotes around the value.
* Fix: For `[mla_gallery]`, `numberposts` is now accepted as a synonym for `posts_per_page`. If both are present, `posts_per_page` wins.
* Fix:  For `[mla_gallery]`, handling of `id=0` and `post_parent=0` now match the WordPress `[gallery]` implementation, restricting the query to children of post '0', i.e., unattached media items.
* Fix: Corrected handling of Photonic Gallery `pause` parameter to match Photonic 1.43 implementation. Pause will be `true` if any non-empty value other than 'false' or '0' is present.
* Fix: A Donate button has been added to the top-right corner of the Settings/Media Library Assistant screen.

= 1.14 =
* New: In the `[mla_gallery]` shortcode, a new `mla_target` parameter allows you to specify the HTML `target` attribute in the gallery item links, e.g., `mla_target="_blank"` will open the items in a new window or tab.
* New: In the `[mla_gallery]` shortcode, a new `tax_operator` parameter allows you to specify "AND" or "NOT IN" operators in the simple `tax_name=term(s)` version of taxonomy queries. See the Settings/Media Library Assistant Documentation page for details.
* New: In the `[mla_gallery]` shortcode, `tax_query` corruption caused by the Visual mode of the post/page editor is now cleaned up before the query is submitted; Line breaks, HTML markup and escape sequences added by the Visual editor are removed.
* Fix: IPTC/EXIF values containing an array, e.g., "2#025 keywords", will be converted to a comma-separated string before assignment to Standard fields or Custom fields.
* Fix: Custom Field Mapping will always ignore rules with Data Source set to "-- None (select a value) --". 
* Fix: In the `[mla_gallery]` shortcode, the `orderby` parameter will override the explicit order in the `ids` parameter.
* Fix: In the `[mla_gallery]` shortcode, the `ids` and `include` parameters no longer require `post_parent=all` to match items not attached to the current post/page.
* Fix: The `[mla_gallery]' shortcode can now be called without a current post, e.g., from a PHP file that contains  `do_shortcode("[mla_gallery]");`.
* Fix: The value in the Attachments column in the edit taxonomy screen(s) is now correct. In previous versions this value was not correct if a term appeared in more than ten (10) attachments.
* Fix: The Attachments column in the edit taxonomy screen(s) is now updated in response to the WordPress "Quick Edit" action for taxonomy terms. In previous versions the Attachments value was not returned and the Posts/Media value was used instead.
* Fix: The Attachments column in the edit taxonomy screen(s) is now center-justified, following the standard set by the WordPress Posts/Media column. In previous versions it was left-justified.
* Fix: Corrected `vertical-align` attribute in `.gallery-caption` style of the default `mla_style` template.
* Fix: Better handling of minimum PHP and WordPress version violations; removed wp_die() calls.

= 1.13 =
* New: Any custom field can be added as a sortable, searchable (click on a value to filter the table display) column in the Media/Assistant submenu. Custom fields can also be added to the quick edit and bulk edit areas. Use the Settings/Media Library Assistant Custom Field tab to control all three uses.
* New: Access to EXIF data expanded to include the COMPUTED, THUMBNAIL and COMMENT arrays. Pseudo-values `ALL_EXIF` and `ALL_IPTC` added. Details in the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") and the Settings/Media Library Assistant Documentation tab.
* New: For the `[mla_gallery]` shortcode, `mla_viewer=true` and related parameters can be coded to supply thumbnail images for non-image file types pdf, txt, doc, xls and ppt using the Google File Viewer.
* New: For the `[mla_gallery]` shortcode, `post_parent=none` or `post_parent=any` can be coded to restrict gallery output to unattached or attached items respectively. 
* New: For the `[mla_gallery]` shortcode, `mla_style=none` parameter can be coded to suppress the inline CSS styles added to gallery output. 
* Fix: Corrected occasional error in field-level markup substitution using the `exif` prefix.
* Fix: Corrected error in Custom Field Mapping of `_wp_attachment_metadata` during Media/Add New processing.

= 1.12 =
* One-off version for a private client.

= 1.11 =
* New: If the search box contains (only) a numeric value it is interpreted as a search by attachment ID. You can search for a numeric value in the text fields, e.g., title, by putting quotes around the value.
* Fix: The edit taxonomy screen "Attachments" column is now computed correctly when adding new terms, avoiding fatal errors and other odd results.
* Fix: Adopted new WordPress standard for JavaScript files, i.e., use ".min.js" for minified (production) files.

= 1.10 =
* New: Attachment metadata such as file size, dimensions and where-used status can be assigned to WordPress custom fields. These custom fields can be added to the Media/Assistant submenu table as sortable columns and displayed in `[mla_gallery]` shortcode output.
* New: Integrates with Photonic Gallery (plugin), so you can add slideshows, thumbnail strips and special effects to your `[mla_gallery]` galleries.
* Fix: Edit Media screen with appropriate message displayed after "Map ... Metadata" actions.
* Fix: SQL View (supporting ALT Text sorting/searching) now created only when required and dropped immediately after use. Avoids conflicts with database backup/restore utilities.
* Fix: "Map IPTC/EXIF Metadata" link moved from Image Metadata box to Save Metadata box.
* Fix: Field-level debug information removed from bulk edit messages.
* Fix: PHP Notice for NULL post metadata keys resolved.
* Fix: PHP Notice for images without "sizes" metadata array resolved.

= 1.00 =
* New: IPTC and EXIF metadata can be assigned to standard WordPress fields, taxonomy terms and custom fields. You can update all existing attachments from the Settings page IPTC/EXIF tab, groups of existing attachments with a Bulk Action or one existing attachment from the Edit Media/Edit Single Item screen.
* New: Where-used processing can be tuned or disabled on the Settings page, General tab.
* New: "Gallery in" and "MLA Gallery in" results are cached for fifteen minutes, avoiding repetitive database access. The cache is automatically flushed when pages, posts or attachments are inserted or updates, and can be manually flushed or disabled on the Settings page, General tab.
* New: Default `[mla_gallery]` style and markup templates can be specified on the Settings page.
* New: `[mla_gallery]` parameter "mla_float" allows control of gallery item "float" attribute.
* Fix: Field-level substitution parameters (custom fields, taxonomy terms, IPTC metadata and EXIF metadata) are now available for mla_link_text, mla_rollover_text and mla_caption parameters.
* Fix: Attachment/Parent relationships are reported consistently on the edit pages and the Media/Assistant submenu table.
* Fix: Defect in generating mla_debug messages has been corrected.
* Fix: Default "Order by" option now includes "None".
* Fix: For WordPress 3.5, Custom Field support for attachments enabled in admin_init action.
 
= 0.90 =
* New: Field-level IPTC and EXIF metadata support for `[mla_gallery]` display using custom markup templates.
* New: Field-level custom field and taxonomy term support for `[mla_gallery]` display using custom markup templates.
* New: Contextual help tabs added to WordPress 3.5+ Edit Media Screen, explaining MLA enhancements.
* Updated for WordPress version 3.5!

= 0.81 =
* New: Improved default Style template, `[mla_gallery]` parameters "mla_itemwidth" and "mla_margin" added to allow control of gallery item spacing.
* Fix: Quick edit support of WordPress standard Categories taxonomy fixed.

= 0.80 =
* New: MLA Gallery Style and Markup Templates, for control over CSS styles, HTML markup and data content of `[mla_gallery]` shortcode output.
* New: The `[mla_gallery]` "mla_link_text", "mla_rollover_text" and "mla_caption", parameters allow easy customization of gallery display.
* New: The `[mla_gallery]` "link" parameter now accepts size values, e.g., "medium", to generate a link to image sizes other than "full".
* New: The `[mla_gallery]` "mla_debug" parameter provides debugging information for query parameters.
* New: Quick Edit area now includes caption field.
* New: Settings page now divided into three tabbed subpages for easier access to settings and documentation.
* New: For WordPress 3.5, custom field support added to attachments and to the WordPress standard Edit Media Screen.
* New: For WordPress version 3.5, the WordPress standard Edit Media screen now includes Last Modified date, Parent Info, Menu Order, Image Metadata and all "where-used" information.
* New: For WordPress versions before 3.5, the MLA Edit Single Item screen now includes "Gallery in" and "MLA Gallery in"  information.
* Fix: Bulk edit now supports "No Change" option for Author.
* Fix: Bulk edit now supports changing Parent ID to "0" (unattached).
* Fix: Where-used reporting corrected for sites without month- and year-based folders.
* Fix: "No Categories" filtering fixed; used to return items with categories in some cases.

= 0.71 =
* Fix: Removed (!) Warning displays for empty Gallery in and MLA Gallery in column entries.

= 0.70 =
* New: "Gallery in" and "MLA Gallery in" columns show where the item appears in `[gallery]` and `[mla_gallery]` shortcode output.
* New: Post titles in the where-used columns contain a link to the Edit Post/Page screen.
* New: Title/Name column distinguishes between "BAD PARENT" (no where-used references to the item) and "INVALID PARENT" (does not exist).
* Fix: `[mla_gallery]` queries are modified to avoid a conflict with the Role Scoper plugin.
* Fix: Undefined taxonomies are now bypassed when defining table columns, avoiding (!) Notice displays after changing taxonomy support settings.

= 0.60 =
* New: Enhanced Search Media box. Search can be extended to the name/slug, ALT text and caption fields. The connector between search terms can be "and" or "or".
* New: The ID/Parent and Parent ID columns now contain a link to a parent-specific search of the Media Library.
* New: Menu Order added as sortable column, to Edit Single Item and to Quick Edit area.
* New: The Author column now contains a link to an author-specific search of the Media Library.
* New: The Attached to column now contains a link to the Edit Post/Page screen for the parent.
* New: For WordPress version 3.5, the WordPress standard Edit Media screen replaces the MLA Edit Single Item screen.
* Fix: HTML markup is no longer escaped in `[mla_gallery]` captions; caption processing now matches the WordPress `[gallery]` shortcode.
* Fix: For WordPress version 3.5, duplicate "edit taxonomy" submenu entries will not appear.

= 0.50 =
* New: `[mla_gallery]` shortcode, a superset of the `[gallery]` shortcode that provides many enhancements. These include taxonomy support and all post_mime_type values (not just images). Media Library items need not be "attached" to the post.
* New: `[mla_gallery]` shortcode documentation added to Settings page
* New: Donate button and link added to Settings page

= 0.41 =
* Fix: SQL View (supporting ALT Text sorting) now created for automatic plugin upgrades

= 0.40 =
* New: Bulk Edit area; update author or parent, add, remove or replace taxonomy terms for several attachments at once
* New: ALT Text is now a sortable column, and shows attachments with no ALT Text value
* New: Activate and deactivate hooks added to create and drop an SQL View supporting ALT Text sorting
* New: Revisions are excluded from the where-used columns; a settings option lets you include them if you wish
* Fix: Better validation/sanitization of data fields on input and display
* Fix: Database query validation/sanitization with wpdb->prepare()
* Fix: check_admin_referer added to settings page
* Fix: Inline CSS styles for message DIV moved to style sheet

= 0.30 =
* New: Complete support for all taxonomies registered with WordPress, including the standard Categories and Tags, your custom taxonomies and the Assistant's pre-defined Att. Categories and Att. Tags. You can add taxonomy columns to the Assistant admin screen, filter the listing on any taxonomy, assign terms to attachments and list the attachments for a taxonomy term.
* New: MIME Type and Last Modified Date added to columns listing
* New: Last Modified Date added to single item edit screen
* New: Default column and sort order added to Settings page
* New: Plugin version number added to Settings page header
* Fix: Text fields such as Title, Alternate Text and Caption containing single quotes are no longer truncated on the Edit single item screen
* Fix: Sortable columns and sort order updated.

= 0.20 =
* New: Quick Edit action for inline editing of attachment metadata
* New: Post Author can be changed
* New: Hyperlink to phpDocs documentation added to Settings page
* New: Shortcode documentation added to settings page
* New: Some book credits added to the "Other Notes" section
* Change: Minified version of JavaScript files are loaded unless 'SCRIPT_DEBUG' is defined as true in wp-config.php
* Change: Global functions moved into classes to minimize the chance of name conflicts
* Change: All class, function and constant names are now checked for conflicts with other plugins and themes
* Fix: Retain pagination values, e.g., page 3 of 5, when returning from row-level actions
* Fix: Retain orderby and order values, e.g., descending sort on date, when returning from row-level actions

= 0.11 =
* Fix: Changed admin URL references from relative (/wp-admin/...) to absolute, using admin_url().
* Fix: Changed wp_nonce_field() calls to suppress duplicate output of nonce field variables.
* Fix: Changed the minimum WordPress version required to 3.3.

= 0.1 =
* Initial release.

== Upgrade Notice ==

= 1.41 =
New [mla_gallery] "previous link" and "next link" output for gallery navigation. New "request" substitution parameter to access $_REQUEST variables. Three other enhancements, seven fixes.

== Other Notes ==

In this section, scroll down to see:

* Acknowledgements
* MLA Gallery Shortcode Documentation
* Support for &ldquo;Photonic Gallery for Flickr, Picasa, SmugMug, 500px and Instagram&rdquo;
* MLA Gallery Style and Markup Template Documentation
* Library Views/Post MIME Type Processing
* File Extension/MIME Type Processing
* Custom Field Processing Options
* IPTC &amp; EXIF Processing Options

**NOTE:** More complete documentation is included on the plugin's Settings page and the drop-down "Help" content in the admin screens.

== Acknowledgements ==

I have used and learned much from the following books (among many):

* Professional WordPress; Design and Development, by Hal Stern, David Damstra and Brad Williams (Apr 5, 2010) ISBN-13: 978-0470560549
* Professional WordPress Plugin Development, by Brad Williams, Ozh Richard and Justin Tadlock (Mar 15, 2011) ISBN-13: 978-0470916223
* WordPress 3 Plugin Development Essentials, by Brian Bondari and Everett Griffiths (Mar 24, 2011) ISBN-13: 978-1849513524
* WordPress and Ajax, by Ronald Huereca (Jan 13, 2011) ISBN-13: 978-1451598650

Media Library Assistant includes many images drawn (with permission) from the [Crystal Project Icons](http://www.softicons.com/free-icons/system-icons/crystal-project-icons-by-everaldo-coelho), created by [Everaldo Coelho](http://www.everaldo.com), founder of [Yellowicon](http://www.yellowicon.com).

== MLA Gallery Shortcode ==

The `[mla_gallery]` shortcode is used in a post, page or custom post type to add a gallery of images and/or other Media Library items (such as PDF documents). MLA Gallery is a superset of the `[gallery]` shortcode in the WordPress core; it is compatible with `[gallery]` and provides many enhancements. These include:

* Full support for WordPress categories, tags and custom taxonomies. You can select items with any of the taxonomy parameters documented in the WP_Query class.
* Support for all post_mime_type values, not just images.
* Media Library items need not be "attached" to the post. You can build a gallery with any combination of items in the Library using taxonomy terms, custom fields and more.
* Control over the styles, markup and content of each gallery using the Style and Markup Templates documented below.
* Combine [mla_gallery] data selection with other popular gallery-generating plugins to get the best of both.

All of the options/parameters documented for the `[gallery]` shortcode are supported by the `[mla_gallery]` shortcode; you can find them in the WordPress Codex. Most of the parameters documented for the WP_Query class are also supported; see the WordPress Codex. Because the `[mla_gallery]` shortcode is designed to work with Media Library items, there are some parameter differences and extensions; these are documented below.

<h4>Gallery Display Style</h4>

Two parameters provide a way to apply custom style and markup templates to your `[mla_gallery]` display: These parameters replace the default style and/or markup templates with templates you define on the "MLA Gallery" tab of the Settings page.

* mla_style
* mla_markup

Three parameters provide control over the placement, size and spacing of gallery items without requiring the use of custom Style templates.

* mla_float
* mla_margin
* mla_itemwidth

<h4>Gallery Display Content</h4>

Nine parameters provide an easy way to control the contents of gallery items without requiring the use of custom Markup templates.  

* mla_link_attributes
* mla_link_href
* mla_link_text
* mla_rollover_text
* mla_image_attributes
* mla_image_class
* mla_ image_alt
* mla_caption
* mla_target

<h4>Google File Viewer Support</h4>

Four parameters provide an easy way to generate thumbnail images for the non-image file types.

* mla_viewer
* mla_viewer_extensions
* mla_viewer_page
* mla_viewer_width

<h4>Order, Orderby</h4>

You can sort the gallery by one or more of these values:

* none
* ID
* author
* date
* description, content
* title
* caption, excerpt
* slug, name
* modified
* parent
* menu_order
* mime_type
* comment_count
* rand
* &lt;keyname&gt;, meta_value, meta_value_num
* post__in
				
You can sort on more than one value, e.g., `orderby="author, date DESC"` and you can specify ASC/DESC on a value by value basis.

<h4>Size</h4>

The `[mla_gallery]` shortcode supports an additional Size value, "icon", which shows a 60x60 or 64x64 pixel thumbnail for image items and an appropriate icon for non-image items such as PDF or text files.

<h4>Link</h4>

For image attachments you can also specify the size of the image file you want to link to. Valid values include "thumbnail", "medium", "large" and any additional image size that was registered with add_image_size(). If the specified size is not available or if the attachment is not an image, the link will go directly to the attachment file.

<h4>Post ID, "ids", Post Parent</h4>

You can use the "post_parent" to override the default behavior. If you set "post_parent" to "current", only the items attached to the current post are displayed. If you set "post_parent" to "all", the query will not have a post ID or post_parent parameter. If you set "post_parent" to "any", only the attached items are displayed. If you set "post_parent" to "none", only the unattached items are displayed.

<h4>Author, Author Name</h4>

You can query by author's id or the "user_nicename" value (not the "display_name" value). Multiple author ID values are allowed, but only one author name value can be entered.

<h4>Category and Tag Parameters</h4>

The Category parameters search in the WordPress core "Categories" taxonomy. The Tag parameters search in the WordPress core "Tags" taxonomy.

<h4>Taxonomy Parameters, "tax_operator"</h4>

The `[mla_gallery]` shortcode supports the simple "{tax} (string)" values (deprecated as of WordPress version 3.1) as well as the more powerful "tax_query" value. For simple queries, enter the taxonomy name and the term(s) that must be matched. MLA enhances the simple taxonomy query form by providing an additional parameter, "tax_operator", which can be "IN", "NOT IN" or "AND". More complex queries can be specified by using "tax_query".

<h4>Post MIME Type</h4>

You can override the default to, for example, display PDF documents (`post_mime_type=application/pdf`) or all MIME types (`post_mime_type=all`). You can select several MIME types with a comma-separated list, e.g., `post_mime_type='audio,video'`. Wildcard specifications are also supported. For example, `post_mime_type='*/mpeg'` to select audio and video mpeg formats or `post_mime_type='application/*ms*'` to select all Microsoft application formats (Word, Excel, etc.).

<h4>Pagination Parameters</h4>

If you are working with a template that supports pagination you can use specific values for "posts_per_page", "posts_per_archive_page", "paged" and/or "offset" .

<h4>Custom Field Parameters</h4>

The `[mla_gallery]` shortcode supports the simple custom field parameters as well as the more powerful "meta_query" parameters made available as of WordPress 3.1.

== Support for Alternative Gallery Output ==
The [mla_gallery] shortcode can be used to provide "Previous" and "Next" links that support moving among the individual items in a gallery. Two parameters implement this feature:

* `mla_output` - the type of output the shortcode will return. The default value, "gallery", returns the traditional gallery of image thumbnails, etc. The "next_link" value returns a link to the next gallery item, and "previous_link" returns a link to the previous gallery item.
* `id` - (optional) the ID of the "current" gallery item. 

The link returned is drawn from the attachment-specific "link" substitution parameter for the next or previous gallery item. This means you can use all of the **Gallery Display Content** parameters to control each element of the link. For example, you can code `mla_rollover_text='&larr; Previous'` to replace the thumbnail image with a generic text link to the "previous_link" item. You can also add HTML arguments to the link to pass values along from one page to the next. 

For example, you can select images using the MLA Att. Tag taxonomy and have each gallery item link to a page (page_id=893 in this case) that displays a larger version of the single image: 

`
[mla_gallery attachment_tag="sample" mla_caption="{+title+}" mla_link_href="{+site_url+}?page_id=893&current_id={+attachment_ID+}&attachment_tag={+query:attachment_tag+}"]  
`

Note the use of `attachment_tag={+query:attachment_tag+}` in the href to pass the tag value from the gallery page to the single-image page. The single-image page would have three [mla+gallery] shortcodes; one to display the image and two for the "Previous Sample" and "Next Sample" links:

`
 [mla_gallery columns=1 ids="{+request:current_id+}" size=medium] 
 
 <div style="clear: both; float: left">
 [mla_gallery mla_output="previous_link,wrap" mla_link_text='← Previous Sample' attachment_tag="{+request:attachment_tag+}" id="{+request:current_id+}" mla_caption="{+title+}" mla_link_href="{+site_url+}?page_id=893&current_id={+attachment_ID+}&attachment_tag={+query:attachment_tag+}"]
 </div>
 <div style="float: right">
 [mla_gallery mla_output="next_link,wrap" mla_link_text='Next Sample →' attachment_tag="{+request:attachment_tag+}" id="{+request:current_id+}" mla_caption="{+title+}" mla_link_href="{+site_url+}?page_id=893&current_id={+attachment_ID+}&attachment_tag={+query:attachment_tag+}"]
 </div>  
 `
 
Note the following points: 

1.The "ids" parameter in the first [mla_gallery] takes the "current_id" value (for the single image to be displayed) from the HTML $_REQUEST array. 
2.The "id" parameters in the second and third [mla_gallery] take the "current_id" value from the HTML $_REQUEST array. In these "galleries" the "current_id" is the item from which "previous" and "next" are calculated. 
3.The "attachment_tag" parameters in the second and third [mla_gallery] take the their value from the HTML $_REQUEST array as well. The Att. Tag value is used to reconstruct the original gallery for the previous/next calculation. 

This example shows the power of the substitution parameters and in particular the "query" and "request" prefixes that can be used to pass information into an [mla_gallery] and from one page to the next. All of this without modifying PHP templates or requiring other code modifications! 

== Support for Other Gallery-generating Shortcodes ==

The [mla_gallery] shortcode can be used in combination with other gallery-generating shortcodes to give you the data selection power of [mla_gallery] and the formatting/display power of popular alternatives such as the WordPress.com Jetpack Carousel and Tiled Galleries modules. Any shortcode that accepts "ids=" or a similar parameter listing the attachment ID values for the gallery can be used. Two parameters implement this feature:

* `mla_alt_shortcode`: the name of the shortcode to be called for gallery format and display
* `mla_alt_ids_name`: (optional, default "ids") the name of the parameter used to pass a list of attachment ID values 

For example, if you want to select images using the MLA Att. Category taxonomy but want to display a "Tiled Mosaic" gallery, you can code:

`[mla_gallery attachment_category=vegetable tax_operator="NOT IN" mla_alt_shortcode=gallery type="rectangular" mla_alt_ids_name=include]`

This example selects all the images that are "NOT IN" the Att. Category "vegetable". The selected images are passed to the [gallery] shortcode in an "include" parameter, along with the "type=rectangular" parameter. The result is as if you had coded:

`[gallery include="1,2,3" type="rectangular"]`

In the above example, the "mla_alt_ids_name=include" parameter isn't really necessary, since the [gallery] shortcode accepts the "ids" parameter. It was included in the example just to show how the "mla_alt_ids_name" might be used for some other shortcode that requires a different name for the parameter.

You can pass any parameters you need through the [mla_gallery] shortcode and on to the alternate shortcode you're using. Here's another example, using the Photonic Gallery plugin:

`[mla_gallery attachment_tag=fauna orderby=rand mla_alt_shortcode=gallery type=default style=strip-below slideshow_height=320 slide_size=medium]`

Here, [mla_gallery] selects the images with an Att. Tag of "fauna" and sorts them in a random order. It then calls on the [gallery] shortcode (which Photonic also uses):

`[gallery ids="3,1,4,2,7" type=default style=strip-below slideshow_height=320 slide_size=medium]`

Photonic recognizes the "type=default" parameter and takes over, using the other three parameters to format its results. This example is a less convenient but more flexible alternative to the native Photonic support built-in to [mla_gallery] (see next section).

<strong>NOTE:</strong> When you use "mla_alt_shortcode" to pass format/display responsibility off to another shortcode you will lose the [mla_gallery] Gallery Display Style (e.g. "mla_float") and Gallery Display Content (e.g. "mla_caption") parameters. There is no reliable way for [mla_gallery] to pass this information on to the other shortcode you've specified.

== Support for &ldquo;Photonic Gallery for Flickr, Picasa, SmugMug, 500px and Instagram&rdquo; ==

The <a href="http://wordpress.org/extend/plugins/photonic/" title="Photonic Gallery plugin directory page" target="_blank">Photonic Gallery for Flickr, Picasa, SmugMug, 500px and Instagram</a> plugin adds several new parameters to the `[mla_gallery]` shortcode to enhance your galleries. All you have to do is install the plugin, then add a "style=" parameter to your `[mla_gallery]` shortcode to use the Photonic styling and markup in place of the native `[mla_gallery]` style and markup templates. 

You can use the "Photonic" screen of the Insert Media dialog to build the display portion of your shortcode parameters. After you click "Insert into post", change the shortcode name from "gallery" to "mla_gallery" and add the query parameters you need to select the attachments for the gallery. The `[mla_gallery]` code will compile the list of attachments for your gallery, then hand control over to Photonic to format the results. 

== MLA Gallery Style and Markup Templates ==

The Style and Markup templates give you great flexibility for the content and format of each `[mla_gallery]`. You can define as many templates as you need.

Style templates provide gallery-specific CSS inline styles. Markup templates provide the HTML markup for 1) the beginning of the gallery, 2) the beginning of each row, 3) each gallery item, 4) the end of each row and 5) the end of the gallery. The attachment-specific markup parameters let you choose among most of the attachment fields, not just the caption.

The MLA Gallery tab on the Settings page lets you add, change and delete custom templates. The default templates are also displayed on this tab for easy reference.

In a template, substitution parameters are surrounded by opening ('[+') and closing ('+]') tags to separate them from the template text; see the default templates for many examples.

<h4>Substitution parameters for style templates</h4>

A complete list of the <strong>13 style substitution parameters</strong> is on the plugin's Settings page.

<h4>Substitution parameters for markup templates</h4>

A complete list of the <strong>16 markup substitution parameters</strong> is on the plugin's Settings page.

<h4>Attachment-specific substitution parameters for markup templates</h4>

A complete list of the <strong>35 attachment-specific substitution parameters</strong> is on the plugin's Settings page.

<h3>Field-level Markup Substitution Parameters</h3>

Field-level substitution parameters let you access query arguments, custom fields, taxonomy terms, and attachment metadata for display in an MLA gallery. For these parameters, the value you code within the surrounding the ('[+') and ('+]') delimiters has three parts; the prefix, the field name and, if desired, a formatting option.

The <strong>prefix</strong> defines which type of field-level data you are accessing. It must immediately follow the opening ('[+') delimiter and end with a colon (':'). There can be no spaces in this part of the parameter.

The <strong>field name</strong> defines which field-level data element you are accessing. It must immediately follow the colon (':'). There can be no spaces between the colon and the field name. Spaces are allowed within the field name to accommodate custom field names that contain them.

If no formatting option is present, fields with multiple values are formatted as a comma-delimited text list. The formatting option, if present, immediately follows the field name using a comma (,) separator and ends with the closing delimiter ('+]'). There can be no spaces in this part of the parameter.

The <strong>",single" option</strong> defines how to handle fields with multiple values. If this option is present, only the first value of the field will be returned. Use this option to limit the data returned for a custom field, taxonomy or metadata field that can have many values.

The <strong>",export" option</strong> changed the display of array fields with multiple values. If this option is present, the PHP `var_export` function is used to return a string representation of all the elements in an array field.

There are seven prefix values for field-level data. Prefix values must be coded as shown; all lowercase letters.

* `request`: The parameters defined in the `$_REQUEST` array; the "query strings" sent from the browser. The PHP $_REQUEST variable is a superglobal Array that contains the contents of both $_GET, $_POST, and $_COOKIE arrays. It can be used to collect data sent with both the GET and POST methods. For example, if the URL is `http://www.mysite.com/mypage?myarg=myvalue` you can access the query string as `[+request:myarg+]`, which has the value "myvalue".
* `query`: The parameters defined in the `[mla_gallery]` shortcode. For example, if your shortcode is `[mla gallery attachment_tag=my-tag div-class=some_class]` you can access the parameters as `[+query:attachment_tag+]` and `[+query:div-class+]` respectively. Only the parameters actually coded in the shortcode are accessible; default values for parameters not actually coded are not available. You can define your own parameters, e.g., `div-class`; they will be accessible as field-level data but will otherwise be ignored.
* `custom`: WordPress custom fields, which you can define and populate on the Edit Media screen. The field name, or key, can contain spaces and some punctuation characters. You <strong>cannot use the plus sign ('+')</strong> in a field name you want to use with `[mla_gallery]`. Custom field names are case-sensitive; "client" and "Client" are not the same.
* `terms`: WordPress Category, tag or custom taxonomy terms. For this category, you code the name of the taxonomy as the field name. The term(s) associated with the attachment will be displayed in the `[mla_gallery]`. Note that you must use the name/slug string for taxonomy, not the "title" string. For example, use "attachment-category" or "attachment-tag", not "Att. Category" or "Attachment Category".

* `meta`: The WordPress "attachment metadata", if any, embedded in the image/audio/video file. For this category, you can code any of the field names embedded in the `_wp_attachment_metadata` array. The "Attachment Metadata" display in the Media/Edit Media screen will show you the names and values of these fields. Note that the fields available differ among image, audio and video attachments.

* `iptc`: The IPTC (International Press Telecommunications Council) metadata, if any, embedded in the image file. For this category, you can code any of the IPTC DataSet tag and field identifiers, e.g., "2#025" for the Keywords field. You can also use the "friendly name" MLA defines for most of the IPTC fields; see the table of identifiers and friendly names in the MLA documentation. You can find more information in the <a href="http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">IPTC-NAA Information Interchange Model Version No. 4.1 specification</a>.

* `exif`: The EXIF (EXchangeable Image File) metadata, if any, embedded in a JPEG DCT or TIFF Rev 6.0 image file. 
 Though the specification is not currently maintained by any industry or standards organization, almost all camera manufacturers use it. It is also supported by many image editing programs such as Adobe PhotoShop.
 For this category, you can code any of the field names embedded in the image by the camera or editing software. The is no official list of standard field names, so you just have to know the names your camera and software use; field names are case-sensitive. You can find more information in the <a href="http://en.wikipedia.org/wiki/Exchangeable_image_file_format" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">Exchangeable image file format</a> article on Wikipedia. You can find External Links to EXIF standards and tag listings at the end of the Wikipedia article.

Two special exif "pseudo-values" are available; <strong>ALL_IPTC</strong> and <strong>ALL_EXIF</strong>. These return a string representation of all IPTC or EXIF data respectively. You can use these pseudo-values to examine the metadata in an image, find field names and see what values are embedded in the image.

<h3>A Table-based Template Example</h3>
<p>
Here's a small example that shows a gallery using table markup. The Item markup section shows how to use the "terms", "custom", "iptc" and "exif" substitution parameters.

</p>
<h4>Style Template</h4>

	<style type='text/css'>
		#[+selector+] {
			margin: auto;
		}
		#[+selector+] .gallery-row {
			float: [+float+];
			margin-top: 10px;
			border-top: 1px solid #ddd;
			text-align: center;
			width: [+itemwidth+]%;
		}
		#[+selector+] .gallery-row td.gallery-icon {
			width: 60;
			height: 60;
			vertical-align: top;
		}
		#[+selector+] .gallery-row .gallery-icon img {
			border: 2px solid #cfcfcf;
		}
		#[+selector+] .gallery-caption {
			margin-left: 0;
			vertical-align: top;
		}
	</style>

<h4>Markup Template</h4>
<h5>Open</h5>

	<table id='[+selector+]' class='gallery galleryid-[+id+]<br />gallery-columns-[+columns+] gallery-size-[+size_class+]'>

<h5>Row Open</h5>

	<tr class='gallery-row'>

<h5>Item</h5>

	<td class='gallery-icon'>
		[+link+]
	</td>
	<td class='wp-caption-text gallery-caption'>
		<strong>[+title+]</strong><br />
		[+description+]<br />
		[+date+]<br />
		[+custom:client,single+]<br />
		[+terms:category+]<br />
		[+iptc:caption-or-abstract+]<br />
		[+iptc:2#025,single+]<br />
		[+exif:Artist+]
	</td>

<h5>Row Close</h5>

	</tr>
	
<h5>Close</h5>

	</table>

== Library Views/Post MIME Type Processing ==

WordPress uses Post MIME Types (a terrible name; they have nothing to do with Posts or Pages), to define the views for the Media/Library screen and the Media Manager/Add Media "media items" drop down list. MLA's Media/Assistant screen uses an enhanced version of the list, Table Views, to support views with multiple MIME Types (e.g., "audio,video") and wildcard specifications (e.g. "`*/*ms*`"). 

The first time it is invoked, MLA will retrieve the current list of Post MIME Types and use it to initialize the list. MLA will add any custom items it finds added to the list by other plugins and code. Once the list is initialized, MLA's list will be used and other plugins and code will have no effect. You can disable MLA handling of the list by clearing the Enable View and Post MIME Type Support checkbox at the bottom of the screen and clicking "Save Changes". 

The Table View list adds several enhancements to the Post MIME Type list. In the Specification field you can select several MIME types with a comma-separated list, e.g., `post_mime_type='audio,video'`. Wildcard specifications are also supported. For example, `post_mime_type='*/mpeg'` to select audio and video mpeg formats or `post_mime_type='application/*ms*'` to select all Microsoft application formats (Word, Excel, etc.). In the Menu Order field you can enter numeric values to re-arrange the order in which the list entries are displayed in, for example, the Media/Assistant screen. 

The Table View list also supports custom field queries. You can choose from three forms of the custom field specification: 

* To return all items that have a non-NULL value in the field, simply enter the prefix "custom:" followed by the custom field name. For example, custom:My Featured Items
* To return all items that have a NULL value in the field, enter the prefix "custom:" followed by the custom field name and then ",null". For example, custom:My Featured Items,null
* To return all items that match one or more values, enter the prefix "custom:" followed by the custom field name and then "=" followed by a list of values. For example, custom:Color=red or custom:Color=red,green,blue. Wildcard specifications are also supported; for example, "*post" to match anything ending in "post" or "th*da*" to match values like "the date" and "this day".

== File Extension/MIME Type and Icon Processing ==

The file extension/MIME Type associations are used by WordPress to decide what kind of files can be uploaded to the Media Library and to fill in the post_mime_type value for files added to the Media Library. To upload a file, the file extension must be in this list and be active. 

WordPress maintains a list of "file types" which associate file extensions with type names used to select an icon image. For example, an "audio" file type is associated with an image of musical notes. There are nine of these types: archive, audio, code, default, document, interactive, spreadsheet, text and video. MLA has a much longer list; 112 icon types/images in all. If the "Enable MLA File Type Icons Support" checkbox at the bottom of the Settings screen, Uploads tab is checked, the enhanced icon images will be used in place of the WordPress images.

The MLA icon images are slightly larger than the default images and square; 64x64 pixels. The images are drawn (with permission) from the Crystal Project Icons, created by Everaldo Coelho, founder of Yellowicon.

If you come across a new file extension, or if the existing extension/MIME type association does not suit you, you can search the MLA list of over 1,500 alternatives. The list was compiled from several Internet sources and a vigorous attempt was made to get a Description for each choice. If you find a mistake or an entry missing from the list, let me know! 

==Custom Field Processing Options==

On the Custom Fields tab of the Settings screen you can define the rules for mapping several types of file and image metadata to WordPress custom fields. Custom field mapping can be applied automatically when an attachment is added to the Media Library. You can refresh the mapping for <strong><em>ALL</em></strong> attachments using the command buttons on the screen. You can selectively apply the mapping in the bulk edit area of the Media/Assistant submenu table and/or on the Edit Media screen for a single attachment.

This is a powerful tool, but it comes at the price of additional database storage space processing time to maintain and retrieve the data. <strong><em>Think carefully about your needs before you use this tool.</em></strong> You can disable or delete any rules you create, so you might want to set up some rules for a special project or analysis of your library and then discard them when you're done. That said, the advantages of mapping metadata to custom fields are:

* You can add the data to an [mla_gallery] with a field-level markup substitution parameter. For example, add the image dimensions or a list of all the intermediate sizes available for the image.

* You can add the data as a sortable column to the Media/Assistant submenu table. For example, you can find all the "orphans" in your library by adding "reference_issues" and then sorting by that column.

Most of the data elements are static, i.e., they do not change after the attachment is added to the Media Library. The parent/reference information (parent_type, parent_name, parent_issues, reference_issues) is dynamic; it will change as you define galleries, insert images in posts, define featured images, etc. Because of the database processing required to update this information, <strong><em>parent and reference data are NOT automatically refreshed</em></strong>. If you use these elements, you must manually refresh them with the "map data" buttons on the Settings screen, the bulk edit area or the Edit Media screen.

Several of the data elements are sourced from the WordPress "image_meta" array. The credit, caption, copyright and title elements are taken from the IPTC/EXIF metadata (if any), but they go through a number of filtering rules that are not easy to replicate with the MLA IPTC/EXIF processing rules. You may find these "image_meta" elements more useful than the raw IPTC/EXIF metadata.

<h4>Data sources for custom field mapping</h4>

<strong>NOTE:</strong> Sorting by custom fields in the Media/Assistant submenu is by string values. For numeric data this can cause odd-looking results, e.g., dimensions of "1200x768" will sort before "640x480". The "file_size", "pixels", "width" and "height" data sources are converted to strings and padded on the left with spaces if you use the "commas" format. This padding makes them sort more sensibly.

A complete list of the <strong>42 data source elements</strong> is on the plugin's Settings page. In addition, you can map any of the fields found in the attachment's WordPress metadata array to a custom field.

==IPTC &amp; EXIF Processing Options==

Some image file formats such as JPEG DCT or TIFF Rev 6.0 support the addition of data about the image, or <em>metadata</em>, in the image file. Many popular image processing programs such as Adobe PhotoShop allow you to populate metadata fields with information such as a copyright notice, caption, the image author and keywords that categorize the image in a larger collection. WordPress uses some of this information to populate the Title, Slug and Description fields when you add an image to the Media Library.

The Media Library Assistant has powerful tools for copying image metadata to:

* the WordPress standard fields, e.g., the Caption
* taxonomy terms, e.g., in categories, tags or custom taxonomies
* WordPress custom fields

You can define the rules for mapping metadata on the "IPTC/EXIF" tab of the Settings page. You can choose to automatically apply the rules when new media are added to the Library (or not). You can click the "Map IPTC/EXIF metadata" button on the Edit Media/Edit Single Item screen or in the bulk edit area to selectively apply the rules to one or more images. You can click the "Map All Attachments Now" to apply the rules to <strong>all of the images in your library</strong> at one time.

<h4>Mapping tables</h4>

The three mapping tables on the IPTC/EXIF tab have the following columns:

* `Field Title`: The standard field title, taxonomy name or custom field name. In the Custom Field table you can define a new field by entering its name in the blank box at the bottom of the list; the value will be saved when you click "Save Changes" at the bottom of the screen.

* `IPTC Value`: The IPTC (International Press Telecommunications Council) metadata, if any, embedded in the image file. For this category, you can select any of the IPTC DataSet tag and field identifiers, e.g., "2#025" for the Keywords field. The dropdown list has the identifier and the "friendly name" MLA defines for most of the IPTC fields; see the table of identifiers and friendly names in the table below. You can find more information in the <a href="http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">IPTC-NAA Information Interchange Model Version No. 4.1 specification</a>.

* `EXIF Value`: The EXIF (EXchangeable Image File) metadata, if any, embedded in a JPEG DCT or TIFF Rev 6.0 image file. Though the specification is not currently maintained by any industry or standards organization, almost all camera manufacturers use it. For this category, you can code any of the field names embedded in the image by the camera or editing software. The is no official list of standard field names, so you just have to know the names your camera and software use; field names are case-sensitive. You can find more information in the <a href="http://en.wikipedia.org/wiki/Exchangeable_image_file_format" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">Exchangeable image file format</a> article on Wikipedia. You can find External Links to EXIF standards and tag listings at the end of the Wikipedia article.
		<br />&nbsp;<br />
		MLA uses a standard PHP function, <a href="http://php.net/manual/en/function.exif-read-data.php" title="PHP Manual page for exif_read_data" target="_blank">exif_read_data</a>, to extract EXIF data from images. The function returns three arrays in addition to the raw EXIF data; COMPUTED, THUMBNAIL and COMMENT. You can access the array elements by prefacing the element you want with the array name. For example, the user comment text is available as "COMPUTED.UserComment" and "COMPUTED.UserCommentEncoding". You can also get "COMPUTED.Copyright" and its two parts (if present), "COMPUTED.Copyright.Photographer" and "COMPUTED.Copyright.Editor". The THUMBNAIL and COMMENT arrays work in a similar fashion.
		<br />&nbsp;<br />
		Two special exif "pseudo-values" are available; <strong>ALL_IPTC</strong> and <strong>ALL_EXIF</strong>. These return a string representation of all IPTC or EXIF data respectively. You can use these pseudo-values to examine the metadata in an image, find field names and see what values are embedded in the image.

* `Priority`:  If both the IPTC Value and the EXIF Value are non-blank for a particular image, you can select which of the values will be used for the mapping.

* `Existing Text`: Images already in the Media Library will have non-blank values in many fields and may have existing terms in a taxonomy. You can select "Keep" to retain these values or "Replace" to always map a metadata value into the field. For a taxonomy, "Keep" will retain any terms already assigned to the item and "Replace" will delete any existing terms before assigning metadata values as terms.

* `Parent`: For hierarchical taxonomies such as Categories you can select one of the existing terms in the taxonomy as the parent term for any terms you are mapping from metadata values. For example, you could define "IPTC Keywords" as a parent and then assign all of the 2#025 values under that parent term.

<h4>Map All Attachments Now</h4>

To the right of each table heading is a "Map All Attachments Now" button. When you click one of these buttons, the mapping rules in that table are applied to <strong>all of the images in the Media Library.</strong> This is a great way to bring your media items up to date, but it is <strong>not reversible</strong>, so think carefully before you click!
Each button applies the rules in just one category, so you can update taxonomy terms without disturbing standard or custom field values.

These buttons <strong>do not</strong> save any rules changes you've made, so you can make a temporary rule change and process your attachments without disturbing the standing rules.

<h4>Other mapping techniques</h4>

There are two other ways you can perform metadata mapping to one or more existing Media Library images:

* `Single Item Edit/Edit Media screen`: For WordPress 3.5 and later, you can click the "Map IPTC/EXIF metadata" link in the "Image Metadata" postbox to apply the standing mapping rules to a single attachment.  For WordPress 3.4.x and earlier, you can click the "Map IPTC/EXIF metadata" button on the Single Item Edit screen to apply the standing mapping rules.

* `Bulk Action edit area`: To perform mapping for a group of attachments you can use the Bulk Action facility on the main Assistant screen. Check the attachments you want to map, select "edit" from the Bulk Actions dropdown list and click "Apply". The bulk edit area will open with a list of the checked attachments in the left-hand column. You can click the "Map IPTC/EXIF metadata" button in the lower left corner of the area to apply the standing mapping rules to the attachments in the list.