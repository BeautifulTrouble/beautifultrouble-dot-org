<!-- template="documentation-tab" -->
<div class="mla-display-settings-page" id="mla-display-settings-documentation-tab" style="width:700px">
<h3>Plugin and Shortcode Documentation. In this tab, jump to:</h3>
<ul style="list-style-position:inside; list-style:disc; line-height: 18px">
<li>
<a href="#mla_gallery"><strong>MLA Gallery Shortcode</strong></a>
<ul style="list-style-position:inside; list-style:disc; line-height: 15px; padding-left: 20px">
<li><a href="#gallery_display_style">Gallery Display Style</a></li>
<li><a href="#gallery_display_content">Gallery Display Content</a></li>
<li><a href="#google_file_viewer_support">Google File Viewer Support</a></li>
<li><a href="#order_orderby">Order, Orderby</a></li>
<li><a href="#size">Size</a></li>
<li><a href="#link">Link</a></li>
<li><a href="#include_exclude">Include, Exclude</a></li>
<li><a href="#post_id_ids_post_parent">Post ID, "ids", Post Parent</a></li>
<li><a href="#author_author_name">Author, Author Name</a></li>
<li><a href="#category_parameters">Category Parameters</a></li>
<li><a href="#tag_parameters">Tag Parameters</a></li>
<li><a href="#taxonomy_parameters_tax_operator">Taxonomy Parameters, "tax_operator"</a></li>
<li><a href="#post_mime_type">Post MIME Type</a></li>
<li><a href="#post_type_post_status">Post Type, Post Status</a></li>
<li><a href="#pagination_parameters">Pagination Parameters</a></li>
<li><a href="#time_parameters">Time Parameters</a></li>
<li><a href="#custom_field_parameters">Custom Field Parameters</a></li>
<li><a href="#search_keywords">Search Keywords</a></li>
<li><a href="#debugging_output">Debugging Output</a></li>
</ul>
</li>
<li>
<a href="#mla_output"><strong>Support for Alternative Gallery Output</strong></a>
</li>
<li>
<a href="#alt_shortcode"><strong>Support for Other Gallery-generating Shortcodes</strong></a>
</li>
<li>
<a href="#photonic_gallery"><strong>Support for &#8220;Photonic Gallery&#8221;</strong></a>
</li>
<li>
<a href="#mla_gallery_templates"><strong>Style and Markup Templates</strong></a>
</li>
<li>
<a href="#mla_style_parameters"><strong>Substitution parameters for style templates</strong></a>
</li>
<li>
<a href="#mla_markup_parameters"><strong>Substitution parameters for markup templates</strong></a>
</li>
<li>
<a href="#mla_attachment_parameters"><strong>Attachment-specific substitution parameters for markup templates</strong></a>
</li>
<li>
<a href="#mla_variable_parameters"><strong>Field-level markup substitution parameters</strong></a>
</li>
<li>
<a href="#mla_table_example"><strong>A table-based template example</strong></a>
</li>
<li>
<a href="#mla_views"><strong>Library Views/Post MIME Type Processing</strong></a>
</li>
<li>
<a href="#mla_uploads"><strong>File Extension and MIME Type Processing</strong></a>
</li>
<li>
<a href="#mla_optional_uploads"><strong>Searching for Upload MIME Types</strong></a>
</li>
<li>
<a href="#mla_custom_field_mapping"><strong>Custom Field Processing Options</strong></a>
</li>
<li>
<a href="#mla_custom_field_parameters"><strong>Data sources for custom field mapping</strong></a>
</li>
<li>
<a href="#mla_iptc_exif_mapping"><strong>IPTC &amp; EXIF Processing Options</strong></a>
</li>
<li>
<a href="#mla_iptc_identifiers"><strong>IPTC identifiers and friendly names</strong></a>
</li>
</ul>
<h3>Plugin Code Documentation</h3>
<p>
If you are a developer interested in how this plugin is put together, you should
have a look at the <a title="Consult the phpDocs documentation" href="[+phpDocs_url+]" target="_blank" style="font-size:14px; font-weight:bold">phpDocs documentation</a>.
</p>
<a name="mla_gallery"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>MLA Gallery Shortcode</h3>
<p>
The <code>[mla_gallery]</code> shortcode is used in a post, page or custom post type to add a gallery of images and/or other Media Library items (such as PDF documents). MLA Gallery is a superset of the <code>[gallery]</code> shortcode in the WordPress core; it is compatible with <code>[gallery]</code> and provides many enhancements. These include:
</p>
<ul class="mla_settings">
<li>Full support for WordPress categories, tags and custom taxonomies. You can select items with any of the taxonomy parameters documented in the WP_Query class.</li>
<li>Support for all post_mime_type values, not just images.</li>
<li>Media Library items need not be "attached" to the post. You can build a gallery with any combination of items in the Library using taxonomy terms, custom fields and more.</li>
<li>Control over the styles, markup and content of each gallery using the Style and Markup Templates documented below.</li>
<li>You can combine <code>[mla_gallery]</code> data selection with other popular gallery-generating plugins to get the best of both.
</li>
</ul>
<p>
All of the options/parameters documented for the <code>[gallery]</code> shortcode are supported by the <code>[mla_gallery]</code> shortcode; you can find them in the <a href="http://codex.wordpress.org/Gallery_Shortcode" title="WordPress Codex link" target="_blank">WordPress Codex</a>. Most of the parameters documented for the WP_Query class are also supported; see the <a href="http://codex.wordpress.org/Class_Reference/WP_Query" title="WordPress Codex link" target="_blank">Codex WP_Query class reference</a>. Because the <code>[mla_gallery]</code> shortcode is designed to work with Media Library items, there are some parameter differences and extensions; these are documented below.
<a name="gallery_display_style"></a>
</p>
<h4>Gallery Display Style</h4>
<p>
Two <code>[mla_gallery]</code> parameters provide a way to apply custom style and markup templates to your <code>[mla_gallery]</code> display. These parameters replace the default style and/or markup templates with templates you define on the "MLA Gallery" tab of the Settings page. On the "MLA Gallery" tab you can also select one of your custom templates to replace the built-in default template for all <code>[mla_gallery]</code> shortcodes the do not contain one of these parameters.
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_style</td>
<td>replaces the default style template for an <code>[mla_gallery]</code> shortcode. You can code "none" to suppress the addition of CSS inline styles entirely.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_markup</td>
<td>replaces the default markup template for an <code>[mla_gallery]</code> shortcode</td>
</tr>
</table>
<p>
Three <code>[mla_gallery]</code> parameters provide control over the placement, size and spacing of gallery items without requiring the use of custom Style templates.
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_float</td>
<td>specifies the float attribute of the ".gallery-item" style. Acceptable values are "left", "none", "right"; the default value is "right" if current locale is RTL, "left" on LTR (left-to-right inline flow, e.g., English).</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_margin</td>
<td>specifies the margin attribute (in percent) of the ".gallery-item" style. The default value is "1.5" percent.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_itemwidth</td>
<td>specifies the width attribute (in percent) of the ".gallery-item" style. The default value is calculated by subtracting twice the margin from 100%, then dividing by the number of gallery columns. For example, the default value is "32", or (100 - (2 * 1.5)) / 3.</td>
</tr>
</table>
<p>
These parameters are only important if the gallery thumbnails are too large to fit within the width of the page on which they appear. For example, if you code <code>[mla_gallery size=full]</code>, the browser will automatically scale down large images to fit within the width attribute (in percent) of the ".gallery-item" style. The default 1.5% margin will ensure that the images do not overlap; you can increase it to add more space between the gallery items. You can also reduce the itemwidth parameter to increase the left and right space between the items.
<a name="gallery_display_content"></a>
</p>
<h4>Gallery Display Content</h4>
<p>
Nine <code>[mla_gallery]</code> parameters provide an easy way to control the contents of gallery items without requiring the use of custom Markup templates.  
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_output</td>
<td>completely replaces gallery output with links to the "previous" or "next" item. Complete documentation is in the <a href="#mla_output"><strong>Support for Alternative Gallery Output</strong></a> section below.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_link_attributes</td>
<td>adds one or more HTML attributes to the hyperlink for each gallery item; see below</td>
</tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_link_href</td>
<td>replaces the HTML "href" attribute in the hyperlink for each gallery item; see below</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_link_text</td>
<td>replaces the thumbnail image or attachment title text displayed for each gallery item</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_rollover_text</td>
<td>replaces the HTML "title" attribute in the hyperlink for each gallery item. This is the attachment title text displayed when the mouse rolls or hovers over the gallery thumbnail</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_image_attributes</td>
<td>adds one or more HTML attributes to the "img" tag of the thumbnail image or icon displayed for each gallery item</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_image_class</td>
<td><strong>adds</strong> one or more values to the HTML "class" attribute in the "img" tag of the thumbnail image or icon displayed for each gallery item. Existing class values are retained, not replaced.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_image_alt</td>
<td>replaces the HTML "alt" attribute in the "img" tag of the thumbnail image or icon displayed for each gallery item</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_caption</td>
<td>replaces the attachment caption text displayed beneath the thumbnail of each gallery item</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_target</td>
<td>adds an HTML "target" attribute to the hyperlink for each gallery item; see below</td>
</tr>
<tr>
</table>
<p>
The first eight parameters support the <a href="#mla_markup_parameters">Markup</a> and <a href="#mla_attachment_parameters">Attachment-specific</a> substitution arguments defined for Markup Templates. For example, if you code "<code>mla_rollover_text='{+date+} : {+description+}'</code>, the rollover text will contain the upload date, a colon, and the full description of each gallery item. Simply add "{+" before the substitution parameter name and add "+}" after the name. Note that the enclosing delimiters are different than those used in the templates, since the WordPress shortcode parser reserves square brackets ("[" and "]") for its own use.
</p>
<p>
The "mla_link_href" parameter is a great way to change the destination your gallery item links to or add arguments to the link for later processing. For example, to make a gallery item link back to the page/post it is attached to, you can code: <code>mla_link_href='{+site_url+}/?page_id={+parent+}'</code>. You can also add arguments to the link, e.g., <code>mla_link_href='{+link_url+}&amp;amp;myarg=myvalue'</code>. Note the use of the HTML entity name "&amp;amp;" to put an ampersand in the value; the WordPress "visual" post editor will replace "&", "<" and ">" with "&amp;amp;", "&amp;lt;" and "&amp;gt;" whether you like it not. The <strong>only</strong> markup parameter modified by this parameter is "link". Other markup parameters such as "pagelink", "filelink" and "link_url" are not modified.
</p>
<p>
The "mla_link_attributes" and "mla_image_attributes" parameters accept any value and adds it to the "&lt;a&gt;" or "&lt;img&gt;" tags for the gallery item. For example, you can create a Shadowbox JS (plugin) album by adding <code>mla_link_attributes='rel="shadowbox{sbalbum-{+instance+}};player=img"'</code> to your shortcode query (note the use of single quotes around the parameter value and the double quotes within the parameter). <strong>IMPORTANT:</strong> since the shortcode parser reserves square brackets ("[" and "]") for its own use, <strong>you must substitute curly braces for square brackets</strong> if your attributes require brackets (as this example does). In this case, the actual attribute added to the link will be <code>rel="shadowbox[sbalbum-1];player=img"</code>. If you must code a curly brace in your attribute value, preface it with <strong>two backslash characters</strong>, e.g., "\\{" or "\\}". If you code an attribute already present in the tag, your value will override the existing value.
</p>
<p>
The "mla_target" parameter accepts any value and adds an HTML "target" attribute to the hyperlink with that value. For example, if you code <code>mla_target="_blank"</code> the item will open in a new window or tab. You can also use "_self", "_parent", "_top" or the "<em>framename</em>" of a named frame.
<a name="google_file_viewer_support"></a>
</p>
<h4>Google File Viewer Support</h4>
<p>
Four <code>[mla_gallery]</code> parameters provide an easy way to generate thumbnail images for the non-image file types.
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_viewer</td>
<td>must be "true" to enable thumbnail generation</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_viewer_extensions</td>
<td>a comma-delimited list of the file extensions to be processed; the default is "pdf,txt,doc,xls,ppt" (do not include the dot (".") preceding the file extension). You may add or remove extensions, but these are known to generate reasonable thumbnail images. Sadly, the newer "docx,xlsx,pptx" extensions do not work well with the Google File Viewer.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_viewer_page</td>
<td>the page number (default "1") to be used for the thumbnail image. If you specify a value greater than the number of pages in the file, no image is generated.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_viewer_width</td>
<td>the width in pixels (default "150") of the generated thumbnail image. The height will be set automatically and cannot be specified.</td>
</tr>
</table>
<p>
When this feature is active, gallery items for which WordPress can generate a thumbnail image are not altered. If WordPress generation fails, the gallery thumbnail is replaced by an "img" html tag whose "src" attribute contains a url reference to the Google File Viewer. The Google File Viewer arguments include the url of the source file, the page number and the width. Note that the source file must be Internet accessible; files behind firewalls and on local servers will not generate a thumbnail image.
<a name="order_orderby"></a>
</p>
<h4>Order, Orderby</h4>
<p>
The Orderby parameter specifies which database field(s) are used to sort the gallery. You can sort the gallery by one or more of these values (there is additional information on some of these values in the <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" title="WordPress Codex link" target="_blank">Codex WP_Query class reference</a>):
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">none</td>
<td>No order.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">ID</td>
<td>Order by post id. Note capitalization.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">author</td>
<td>Order by author (id, not display name).</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">date</td>
<td>Order by date uploaded.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">description, content</td>
<td>Order by attachment description.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">title</td>
<td>Order by attachment title.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">caption, excerpt</td>
<td>Order by attachment caption. </td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">slug, name</td>
<td>Order by attachment name.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">modified</td>
<td>Order by last modified date.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent</td>
<td>Order by post/page parent id.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">menu_order</td>
<td>Order by page order.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mime_type</td>
<td>Order by attachment MIME type.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">comment_count</td>
<td>Order by number of comments.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">rand</td>
<td>Random order.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">&lt;keyname&gt;, meta_value,<br />meta_value_num</td>
<td style="vertical-align: top;">Order by custom field value. Note that a <em>'meta_key=keyname'</em> must also be present in the query.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">post__in</td>
<td>Preserve order given in the <em>ids, include</em> or <em>post__in</em> array.</td>
</tr>
</table>
<p>
You can sort on more than one value, e.g., <code>orderby="author, date DESC"</code> and you can specify ASC/DESC on a value by value basis. <strong>NOTE: multiple orderby values are separated by commas, not spaces.</strong> This is a change from WP_Query. </p>
<p>
The order parameter (default ASC) can give an ASC/DESC default for any value that doesn't have a specific choice. For example, <code>orderby="author, date DESC, mime_type" order=ASC</code> is the same as <code>orderby="author ASC, date DESC, mime_type ASC"</code>.
<a name="size"></a>
</p>
<h4>Size</h4>
<p>
The Size parameter specifies the image size to use for the thumbnail display. Valid values include "thumbnail", "medium", "large", "full" and any additional image size that was registered with add_image_size(). The default value is "thumbnail". You can use "none" or "" to suppress thumbnail display and substitute the item title string for the image/icon.
</p>
<p>
The <code>[mla_gallery]</code> shortcode supports an additional Size value, "icon", which shows a 60x60 (or 64x64) pixel thumbnail for image items and an appropriate icon for non-image items such as PDF or text files.
<a name="link"></a>
</p>
<h4>Link</h4>
<p>
The Link parameter specifies the target for the link from the gallery to the attachment. The default value, "permalink" (or its synonym "post"), links to the attachment's media page. The "file" and "full" values link directly to the attachment file.
</p>
<p>
For image attachments you can also specify the size of the image file you want to link to. Valid values include "thumbnail", "medium", "large" and any additional image size that was registered with add_image_size(). If the specified size is not available or if the attachment is not an image, the link will go directly to the attachment file.
<a name="include_exclude"></a>
</p>
<h4>Include, Exclude</h4>
<p>
You can use <code>post_parent=all</code> to include or exclude attachments regardless of which post or page they are attached to. You can use <code>post_mime_type=all</code> to include or exclude attachments of all MIME types, not just images.
<a name="post_id_ids_post_parent"></a>
</p>
<h4>Post ID, "ids", Post Parent</h4>
<p>
The "id" parameter lets you specify a post ID for your query. If the "id" parameter is not specified, the <code>[mla_gallery]</code> behavior differs from the <code>[gallery]</code> behavior. If your query uses taxonomy or custom field parameters, "author", "author_name" or "s" (search term), then the query will NOT be restricted to items attached to the current post. This lets you build a gallery with any combination of Media Library items that match the parameters.
</p>
<p>
For WordPress 3.5 and later, the "ids" parameter lets you specify a list of Post IDs. The attachment(s) matching the "ids" values will be displayed in the order specified by the list.
</p>
<p>
You can use the "post_parent" to override the default behavior. If you set "post_parent" to a specific post ID, only the items attached to that post are displayed. If you set "post_parent" to <strong>"current"</strong>, only the items attached to the current post are displayed. If you set "post_parent" to <strong>"all"</strong>, the query will not have a post ID or post_parent parameter.
</p>
<p>
Two other "post_parent" values let you restrict the gallery to attached or unattached items. If you set "post_parent" to <strong>"any"</strong>, only the items attached to a post or page are displayed. If you set "post_parent" to <strong>"none"</strong>, only the unattached items are displayed.
</p>
<p>
For example, <code>[mla_gallery tag="artisan"]</code> will display all images having the specified tag value, regardless of which post (if any) they are attached to. If you use <code>[mla_gallery tag="artisan" post_parent="current"]</code> it will display images having the specified tag value only if they are attached to the current post.
<a name="author_author_name"></a>
</p>
<h4>Author, Author Name</h4>
<p>
You can query by author's id or the "user_nicename" value (not the "display_name" value). Multiple author ID values are allowed, but only one author name value can be entered.
<a name="category_parameters"></a>
</p>
<h4>Category Parameters</h4>
<p>
The Category parameters search in the WordPress core &quot;Categories&quot; taxonomy. Remember to use <code>post_parent=current</code> if you want to restrict your query to items attached to the current post.
<a name="tag_parameters"></a>
</p>
<h4>Tag Parameters</h4>
<p>
The Tag parameters search in the WordPress core &quot;Tags&quot; taxonomy. Remember to use <code>post_parent=current</code> if you want to restrict your query to items attached to the current post.
</p>
<p>
Note that the "tag_id" parameter requires exactly one tag ID; multiple IDs are not allowed. You can use the "tag__in" parameter to query for multiple values.
<a name="taxonomy_parameters_tax_operator"></a>
</p>
<h4>Taxonomy Parameters, "tax_operator"</h4>
<p>
The <code>[mla_gallery]</code> shortcode supports the simple "{tax} (string)" values (deprecated as of WordPress version 3.1) as well as the more powerful "<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters" title="WordPress Codex Documentation for tax_query" target="_blank">tax_query</a>" value. 
</p>
<p>
For simple queries, enter the taxonomy name and the term(s) that must be matched, e.g.:
</p>
<ul class="mla_settings">
<li><code>[mla_gallery attachment_category='separate-category,another-category']</code></li>
</ul>
<p>
Note that you must use the name/slug strings for taxonomy and terms, not the "title" strings. If you are using the "Att. Tag" taxonomy built in to MLA then your shortcode should be something like:
</p>
<ul class="mla_settings">
<li><code>[mla_gallery attachment_tag=artisan post_parent=all]</code></li>
</ul>
<p>
In this example, "attachment_tag" is the WordPress taxonomy name/slug for the taxonomy. If you're using "Att. Category", the slug would be "attachment_category".
</p>
<p>
The default behavior of the simple taxonomy query will match any of the terms in the list. MLA enhances the simple taxonomy query form by providing an additional parameter, "tax_operator", which can be "IN", "NOT IN" or "AND". If you specify a "tax_operator", MLA will convert your query to the more powerful "tax_query" form, searching on the "slug" field and using the operator you specify. For example, a query for two terms in which <strong>both</strong> terms must match would be coded as:
</p>
<ul class="mla_settings">
<li><code>[mla_gallery attachment_category='separate-category,another-category' tax_operator=AND]</code></li>
</ul>
<p>
More complex queries can be specified by using <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters" title="WordPress Codex Documentation for tax_query" target="_blank">WP_Query's "tax_query"</a>, e.g.:
</p>
<ul class="mla_settings">
<li><code>[mla_gallery tax_query="array(array('taxonomy' => 'attachment_tag','field' => 'slug','terms' => 'artisan'))"]</code></li>
<li><code>[mla_gallery tax_query="array(array('taxonomy' => 'attachment_category','field' => 'id','terms' => array(11, 12)))" post_parent=current post_mime_type='']</code></li>
</ul>
<p>
The first example is equivalent to the simple query <code>attachment_tag=artisan</code>. The second example matches items of all MIME types, attached to the current post, having an attachment_category ID of 11 or 12.
</p>
<p>
When embedding the shortcode in the body of a post, be very careful when coding the tax_query; it must be a valid PHP array specification. Splitting your query over multiple lines or using the "Visual" editor will introduce HTML markup and escape sequences that can render your query invalid. MLA will clean up most of the damage, but if your query fails use the "mla_debug=true" parameter to see if your query has been corrupted. 
</p>
<p>
Remember to use <code>post_parent=current</code> if you want to restrict your query to items attached to the current post.
<a name="post_mime_type"></a>
</p>
<h4>Post MIME Type</h4>
<p>
For compatibility with the WordPress <code>[gallery]</code> shortcode, this parameter defaults to <code>post_mime_type=image</code>. You can override the default to, for example, display PDF documents (<code>post_mime_type=application/pdf</code>) or all MIME types (<code>post_mime_type=all</code>). You can select several MIME types with a comma-separated list, e.g., <code>post_mime_type='audio,video'</code>. Wildcard specifications are also supported. For example, <code>post_mime_type='*/mpeg'</code> to select audio and video mpeg formats or <code>post_mime_type='application/*ms*'</code> to select all Microsoft application formats (Word, Excel, etc.).
<a name="post_type_post_status"></a>
</p>
<h4>Post Type, Post Status</h4>
<p>
For compatibility with the WordPress <code>[gallery]</code> shortcode, these parameters default to <code>post_type=attachment</code>, <code>post_status=inherit</code>. You can override the defaults to, for example, display items in the trash (<code>post_status=trash</code>). I'm not sure why you'd want to override "post_type", but you are welcome to experiment and let me know what you find.
<a name="pagination_parameters"></a>
</p>
<h4>Pagination Parameters</h4>
<p>
The <code>[mla_gallery]</code> shortcode supplies <code>nopaging=true</code> as a default parameter. If you are working with a template that supports pagination you can replace this with specific values for "numberposts", "posts_per_page", "posts_per_archive_page", "paged" and/or "offset" . You can also pass "paged=current" to suppress the "nopaging" default; "current" will be replaced by the appropriate value (get_query_var('paged')).
<a name="time_parameters"></a>
</p>
<h4>Time Parameters</h4>
<p>
Support for time parameters is not included in the current version. I may add it later - let me know if you need it.
<a name="custom_field_parameters"></a>
</p>
<h4>Custom Field Parameters</h4>
<p>
The <code>[mla_gallery]</code> shortcode supports the simple custom field parameters as well as the more powerful <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters" title="WordPress Codex documentation for meta_query" target="_blank">"WP_Query meta_query"</a> parameters made available as of WordPress 3.1.
</p>
<p>
When embedding the shortcode in the body of a post, be very careful when coding the meta_query; it must be a valid PHP array specification. In particular, code the query on one line; splitting it across lines can insert HTML <br> tags and corrupt your query. MLA will clean up most of the damage, but if your query fails use the <code>mla_debug=true</code> parameter to see if your query has been corrupted. 
</p>
<p>
Remember to use <code>post_parent=current</code> if you want to restrict your query to items attached to the current post.
<a name="search_keywords"></a>
</p>
<h4>Search Keywords</h4>
<p>
The search parameter ("s=keyword") will perform a keyword search. A cursory inspection of the code in /wp-includes/query.php reveals that the search includes the "post_title" and "post_content" (Description) fields but not the "post_excerpt" (Caption) field. An SQL "LIKE" clause is composed and added to the search criteria. I haven't done much testing of this parameter.
<a name="debugging_output"></a>
</p>
<h4>Debugging Output</h4>
<p>
The "mla_debug" parameter controls the display of information about the query parameters and SQL statements used to retrieve gallery items. If you code <code>mla_debug=true</code> you will see a lot of information added to the post or page containing the gallery. Of course, this parameter should <strong><em>ONLY</em></strong> be used in a development/debugging environment; it's quite ugly.
</p>
<a name="mla_output"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Support for Alternative Gallery Output</h3>
<p>
The <code>[mla_gallery]</code> shortcode can be used to provide "Previous" and "Next" links that support moving among the individual items in a gallery. 
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_output</td>
<td>the type of output the shortcode will return. The default value, "gallery", returns the traditional gallery of image thumbnails, etc. The "next_link" value returns a link to the next gallery item, and "previous_link" returns a link to the previous gallery item. The optional ",wrap" qualifier determines what happens at each end of the gallery. If you omit the qualifier, an empty string is returned for the "previous_link" from the first gallery item, and for the "next_link" from the last item in the gallery. If you code the ",wrap" qualifier, "previous_link" from the first gallery item will be to the last gallery item and the "next_link" from the last item will be to the first gallery item.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">id</td>
<td>(optional) the ID of the "current" gallery item. If you omit this parameter, the default value is the ID of the current "post". The default value is only useful if you are enhancing the PHP code of the "image.php" template for the "Attachment Page" associated with a Media Library item.</td>
</tr>
</table>
<p>
The link returned is drawn from the attachment-specific "link" substitution parameter for the next or previous gallery item. This means you can use all of the <a href="#gallery_display_content">Gallery Display Content</a> parameters to control each element of the link. For example, you can code <code>mla_rollover_text='&amp;larr; Previous'</code> to replace the thumbnail image with a generic text link to the "previous_link" item. You can also add HTML arguments to the link to pass values along from one page to the next.
</p>
<p>
For example, you can select images using the MLA Att. Tag taxonomy and have each gallery item link to a page (page_id=893 in this case) that displays a larger version of the single image:
</p>
<code>
[mla_gallery attachment_tag="sample" mla_caption="{+title+}" mla_link_href="{+site_url+}?page_id=893&amp;current_id={+attachment_ID+}&amp;attachment_tag={+query:attachment_tag+}"]
</code>
<p>
Note the use of <code>attachment_tag={+query:attachment_tag+}</code> in the href to pass the tag value from the gallery page to the single-image page. The single-image page would have three <code>[mla+gallery]</code> shortcodes; one to display the image and two for the "Previous Sample" and "Next Sample" links:
</p>
<code>
[mla_gallery columns=1 ids="{+request:current_id+}" size=medium]
<br />&nbsp;<br>
&lt;div style="clear: both; float: left"&gt;<br />
[mla_gallery mla_output="previous_link,wrap" mla_link_text='&larr; Previous Sample' attachment_tag="{+request:attachment_tag+}" id="{+request:current_id+}" mla_caption="{+title+}" mla_link_href="{+site_url+}?page_id=893&amp;current_id={+attachment_ID+}&amp;attachment_tag={+query:attachment_tag+}"]<br>
&lt;/div&gt;<br>
&lt;div style="float: right"&gt;<br>
[mla_gallery mla_output="next_link,wrap" mla_link_text='Next Sample &rarr;' attachment_tag="{+request:attachment_tag+}" id="{+request:current_id+}" mla_caption="{+title+}" mla_link_href="{+site_url+}?page_id=893&amp;current_id={+attachment_ID+}&amp;attachment_tag={+query:attachment_tag+}"]<br>
&lt;/div&gt;
</code>
<p>
Note the following points:
</p>
<ol>
<li>The "ids" parameter in the first <code>[mla_gallery]</code> takes the "current_id" value (for the single image to be displayed) from the HTML $_REQUEST array. 
</li>
<li>The "id" parameters in the second and third <code>[mla_gallery]</code> take the "current_id" value from the HTML $_REQUEST array. In these "galleries" the "current_id" is the item from which "previous" and "next" are calculated.
</li>
<li>The "attachment_tag" parameters in the second and third <code>[mla_gallery]</code> take the their value from the HTML $_REQUEST array as well. The Att. Tag value is used to reconstruct the original gallery for the previous/next calculation.
</li>
</ol>
<p>
This example shows the power of the substitution parameters and in particular the "query" and "request" prefixes that can be used to pass information into an <code>[mla_gallery]</code> and from one page to the next. All of this without modifying PHP templates or requiring other code modifications!
</p>
<a name="alt_shortcode"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Support for Other Gallery-generating Shortcodes</h3>
<p>
The <code>[mla_gallery]</code> shortcode can be used in combination with other gallery-generating shortcodes to give you the data selection power of <code>[mla_gallery]</code> and the formatting/display power of popular alternatives such as the WordPress.com Jetpack Carousel and Tiled Galleries modules. Any shortcode that accepts "ids=" or a similar parameter listing the attachment ID values for the gallery can be used. Two parameters implement this feature:
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_alt_shortcode</td>
<td>the name of the shortcode to be called for gallery format and display</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_alt_ids_name</td>
<td>(optional, default "ids") the name of the parameter used to pass a list of attachment ID values to the alternate shortcode</td>
</tr>
</table>
<p>
For example, if you want to select images using the MLA Att. Category taxonomy but want to display a "Tiled Mosaic" gallery, you can code:
</p>
<code>
[mla_gallery attachment_category=vegetable tax_operator="NOT IN" mla_alt_shortcode=gallery type="rectangular" mla_alt_ids_name=include]
</code>
<p>
This example selects all the images that are "NOT IN" the Att. Category "vegetable". The selected images are passed to the <code>[gallery]</code> shortcode in an "include" parameter, along with the "type=rectangular" parameter. The result is as if you had coded:
</p>
<code>
[gallery include="1,2,3" type="rectangular"]
</code>
<p>
In the above example, the <code>mla_alt_ids_name=include</code> parameter isn't really necessary, since the <code>[gallery]</code> shortcode accepts the "ids" parameter. It was included in the example just to show how the "mla_alt_ids_name" might be used for some other shortcode that requires a different name for the parameter.
</p>
<p>
You can pass any parameters you need through the <code>[mla_gallery]</code> shortcode and on to the alternate shortcode you're using. Here's another example, using the Photonic Gallery plugin:
</p>
<code>
[mla_gallery attachment_tag=fauna orderby=rand mla_alt_shortcode=gallery type=default style=strip-below slideshow_height=320 slide_size=medium]
</code>
<p>
Here, <code>[mla_gallery]</code> selects the images with an Att. Tag of "fauna" and sorts them in a random order. It then calls on the <code>[gallery]</code> shortcode (which Photonic also uses), as if you had coded:
</p>
<code>
[gallery ids="3,1,4,2,7" type=default style=strip-below slideshow_height=320 slide_size=medium]
</code>
<p>
Photonic recognizes the <code>type=default</code> parameter and takes over, using the other three parameters to format its results. This example is a less convenient but more flexible alternative to the native Photonic support built-in to <code>[mla_gallery]</code> (see next section).
</p>
<p>
<strong>NOTE:</strong> When you use "mla_alt_shortcode" to pass format/display responsibility off to another shortcode you will lose the <code>[mla_gallery]</code> Gallery Display Style (e.g. "mla_float") and Gallery Display Content (e.g. "mla_caption") parameters. There is no reliable way for <code>[mla_gallery]</code> to pass this information on to the other shortcode you've specified.
</p>
<p>
<a name="photonic_gallery"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Support for &#8220;Photonic Gallery for Flickr, Picasa, SmugMug, 500px and Instagram&#8221;</h3>
<p>
The <a href="http://wordpress.org/extend/plugins/photonic/" title="Photonic Gallery plugin directory page" target="_blank">Photonic Gallery for Flickr, Picasa, SmugMug, 500px and Instagram</a> plugin adds several new parameters to the <code>[mla_gallery]</code> shortcode to enhance your galleries. All you have to do is install the plugin, then add a "style=" parameter to your <code>[mla_gallery]</code> shortcode to use the Photonic styling and markup in place of the native <code>[mla_gallery]</code> style and markup templates.
</p>
<p>
You can use the "Photonic" screen of the Insert Media dialog to build the display portion of your shortcode parameters. After you click "Insert into post", change the shortcode name from "gallery" to "mla_gallery" and add the query parameters you need to select the attachments for the gallery. The <code>[mla_gallery]</code> code will compile the list of attachments for your gallery, then hand control over to Photonic to format the results.
</p>
<a name="mla_gallery_templates"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>MLA Gallery Style and Markup Templates</h3>
<p>
The Style and Markup templates give you great flexibility for the content and format of each <code>[mla_gallery]</code>. You can define as many templates as you need.
</p>
<p>
Style templates provide gallery-specific CSS inline styles (you can code <code>mla_style=none</code> to suppress the addition of CSS inline styles entirely). Markup templates provide the HTML markup for 1) the beginning of the gallery, 2) the beginning of each row, 3) each gallery item, 4) the end of each row and 5) the end of the gallery. The attachment-specific markup parameters let you choose among most of the attachment fields, not just the caption.
</p>
<p>
The MLA Gallery tab on the Settings page lets you add, change and delete custom templates. The default templates are also displayed on this tab for easy reference.
</p>
<p>
In a template, substitution parameters are surrounded by opening ('[+') and closing ('+]') delimiters to separate them from the template text; see the default templates for many examples.
</p>
<a name="mla_style_parameters"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h4>Substitution parameters for style templates</h4>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_style</td>
<td>shortcode parameter, default = 'default'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_markup</td>
<td>shortcode parameter, default = 'default'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">instance</td>
<td>starts at '1', incremented for each additional shortcode in the post/page</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">id</td>
<td>post_ID of the post/page in which the gallery appears</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">itemtag</td>
<td>shortcode parameter, default = 'dl'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">icontag</td>
<td>shortcode parameter, default = 'dt'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">captiontag</td>
<td>shortcode parameter, default = 'dd'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">columns</td>
<td>shortcode parameter, default = '3'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">itemwidth</td>
<td>shortcode parameter, default = '97' if 'columns' is zero, or 97/columns, e.g., '32' if columns is '3'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">margin</td>
<td>shortcode parameter, default = '1.5' (percent)</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">float</td>
<td>'right' if current locale is RTL, 'left' if not</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">selector</td>
<td>"mla_gallery-{$instance}", e.g., mla_gallery-1</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_class</td>
<td>shortcode 'size' parameter, default = 'thumbnail'</td>
</tr>
</table>
<a name="mla_markup_parameters"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h4>Substitution parameters for markup templates</h4>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_style</td>
<td>shortcode parameter, default = 'default'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_markup</td>
<td>shortcode parameter, default = 'default'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">instance</td>
<td>starts at '1', incremented for each additional shortcode in the post/page</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">id</td>
<td>post_ID of the post/page in which the gallery appears</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">itemtag</td>
<td>shortcode parameter, default = 'dl'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">icontag</td>
<td>shortcode parameter, default = 'dt'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">captiontag</td>
<td>shortcode parameter, default = 'dd'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">columns</td>
<td>shortcode parameter, default = '3'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">itemwidth</td>
<td>shortcode parameter, default = '97' if 'columns' is zero, or 97/columns, e.g., '32' if columns is '3'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">margin</td>
<td>shortcode parameter, default = '1.5' (percent)</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">float</td>
<td>'right' if current locale is RTL, 'left' if not</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">selector</td>
<td>"mla_gallery-{$instance}", e.g., mla_gallery-1</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_class</td>
<td>shortcode 'size' parameter, default = "thumbnail". If this parameter contains "none" or an empty string (size="") the attachment title will be displayed instead of the image/icon.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">site_url</td>
<td>absolute URL to the site directory, without trailing slash</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">base_url</td>
<td>absolute URL to the upload directory, without trailing slash</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">base_dir</td>
<td>full path to the upload directory, without trailing slash</td>
</tr>
</table>
<a name="mla_attachment_parameters"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h4>Attachment-specific substitution parameters for markup templates</h4>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">index</td>
<td>starts at '1', incremented for each attachment in the gallery</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">caption</td>
<td>if captiontag is not empty, contains caption/post_excerpt</td>
</tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">excerpt</td>
<td>always contains post_excerpt</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">attachment_ID</td>
<td>attachment post_ID</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mime_type</td>
<td>attachment post_mime_type</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">menu_order</td>
<td>attachment menu_order</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">date</td>
<td>attachment post_date</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">modified</td>
<td>attachment post_modified</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent</td>
<td>attachment post_parent (ID)</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_title</td>
<td>post_title of the parent, or '(unattached)'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_type</td>
<td>'post', 'page' or custom post type of the parent</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_date</td>
<td>upload date of the parent</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">title</td>
<td>attachment post_title</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">slug</td>
<td>attachment post_name</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">width</td>
<td>width in pixels, for image types</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">height</td>
<td>height in pixels, for image types</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">image_meta</td>
<td>image metadata, for image types</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">image_alt</td>
<td>ALT text, for image types</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">base_file</td>
<td>path and file name relative to uploads directory</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">path</td>
<td>path portion of base_file</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">file</td>
<td>file name portion of base_file</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">description</td>
<td>attachment post_content</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">file_url</td>
<td>attachment guid</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">author_id</td>
<td>attachment post_author</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">author</td>
<td>author display_name, or 'unknown'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">link</td>
<td>hyperlink to the attachment page (default) or file (shortcode 'link' parameter = "file")</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">pagelink</td>
<td>always contains a hyperlink to the attachment page</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">filelink</td>
<td>always contains a hyperlink to the attachment file</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">link_url</td>
<td>the URL portion of <em>link</em></td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">pagelink_url</td>
<td>the URL portion of <em>pagelink</em></td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">filelink_url</td>
<td>the URL portion of <em>filelink</em></td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">thumbnail_content</td>
<td>complete content of the gallery item link. This will either be an "&lt;img ... &gt;" tag<br />or a text string for non-image items</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">thumbnail_width</td>
<td>for image/icon items, width of the gallery image/icon</td>
</tr>
<tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">thumbnail_height</td>
<td>for image/icon items, height of the gallery image/icon</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">thumbnail_url</td>
<td>for image/icon items, URL of the gallery image/icon</td>
</tr>
</table>
<a name="mla_variable_parameters"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Field-level Markup Substitution Parameters</h3>
<p>
Field-level substitution parameters let you access query arguments, custom fields, taxonomy terms, IPTC metadata and EXIF metadata for display in an MLA gallery. For these parameters, the value you code within the surrounding the ('[+') and ('+]') delimiters has three parts; the prefix, the field name and, if desired, a formatting option.
</p>
<p>
The <strong>prefix</strong> defines which type of field-level data you are accessing. It must immediately follow the opening ('[+') delimiter and end with a colon (':'). There can be no spaces in this part of the parameter.
</p>
<p>
The <strong>field name</strong> defines which field-level data element you are accessing. It must immediately follow the colon (':'). There can be no spaces between the colon and the field name. Spaces are allowed within the field name to accommodate custom field names that contain them. <strong>Compound names</strong> are used to access elements within arrays, e.g., &quot;<strong>sizes.thumbnail.file</strong>&quot; is used to specify the file name for the thumbnail version of an image.</p>
<p>
If no formatting option is present, fields with multiple values are formatted as a comma-delimited text list. The formatting option, if present, immediately follows the field name using a comma (,) separator and ends with the closing delimiter ('+]'). There can be no spaces in this part of the parameter.</p>
<p>
The <strong>",single" option</strong> defines how to handle fields with multiple values. If this option is present, only the first value of the field will be returned. Use this option to limit the data returned for a custom field, taxonomy or metadata field that can have many values. For example, if you code <code>[+meta:sizes.thumbnail,single+]</code> the result will be "20120313-ASK_5605-150x150.jpg".
</p>
<p>
The <strong>",export" option</strong> changes the display of array fields with multiple values. If this option is present, the PHP <code>var_export</code> function is used to return a string representation of all the elements in an array field. For example, if you code <code>[+meta:sizes.thumbnail,export+]</code> the result will be "array ('file' => '20120313-ASK_5605-150x150.jpg', 'width' => 150, 'height' => 150, 'mime-type' => 'image/jpeg'".
</p>
<p>
There are seven prefix values for field-level data. Prefix values must be coded as shown; all lowercase letters.
</p>
<table>
	<tr>
		<td style="padding-right: 10px; vertical-align: top; font-weight:bold">request</td>
		<td>The parameters defined in the <code>$_REQUEST</code> array; the "query strings" sent from the browser. The PHP $_REQUEST variable is a superglobal Array that contains the contents of both $_GET, $_POST, and $_COOKIE arrays. It can be used to collect data sent with both the GET and POST methods. For example, if the URL is <code>http://www.mysite.com/mypage?myarg=myvalue</code> you can access the query string as <code>[+request:myarg+]</code>, which has the value "myvalue".</td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align: top; font-weight:bold">query</td>
		<td>The parameters defined in the <code>[mla_gallery]</code> shortcode. For example, if your shortcode is <code>[mla gallery attachment_tag=my-tag div-class=some_class]</code> you can access the parameters as <code>[+query:attachment_tag+]</code> and <code>[+query:div-class+]</code> respectively. Only the parameters actually coded in the shortcode are accessible; default values for parameters not actually coded are not available. You can define your own parameters, e.g., "div-class"; they will be accessible as field-level data but will otherwise be ignored.</td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align: top; font-weight:bold">custom</td>
		<td>WordPress Custom Fields, which you can define and populate on the Edit Media screen or map from various sources on the Settings/Media Library Assistant Custom and IPTC/EXIF tabs. The field name, or key, can contain spaces and some punctuation characters. You <strong><em>cannot use the plus sign ('+')</em></strong> in a field name you want to use with <code>[mla_gallery]</code>. Custom field names are case-sensitive; "client" and "Client" are not the same.</td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align: top; font-weight:bold">terms</td>
		<td>WordPress Category, tag or custom taxonomy terms. For this category, you code the name of the taxonomy as the field name. The term(s) associated with the attachment will be displayed in the <code>[mla_gallery]</code>. Note that you must use the name/slug string for taxonomy, not the "title" string. For example, use "attachment_category" or "attachment_tag", not "Att. Category" or "Attachment Category".</td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align: top; font-weight:bold">meta</td>
		<td>WordPress attachment metadata, if any, embedded in the image/audio/video file. For this category, you can code any of the field names embedded in the _wp_attachment_metadata array. The "Attachment Metadata" display in the Media/Edit Media screen will show you the names and values of these fields. Note that the fields available differ among image, audio and video attachments.</td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align: top; font-weight:bold">iptc</td>
		<td>
		The IPTC (International Press Telecommunications Council) metadata, if any, embedded in the image file. For this category, you can code any of the IPTC DataSet tag and field identifiers, e.g., "2#025" for the Keywords field. You can also use the "friendly name" MLA defines for most of the IPTC fields; see the <a href="#mla_iptc_identifiers">table of identifiers and friendly names</a> below. <br />
		&nbsp;<br />
		You can find more information in the <a href="http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">IPTC-NAA Information Interchange Model Version No. 4.1 specification</a>.</td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align: top; font-weight:bold">exif</td>
		<td>
		The EXIF (EXchangeable Image File) metadata, if any, embedded in a JPEG DCT or TIFF Rev 6.0 image file. 
		Though the specification is not currently maintained by any industry or standards organization, almost all camera manufacturers use it. It is also supported by many image editing programs such as Adobe PhotoShop.
		For this category, you can code any of the field names embedded in the image by the camera or editing software. There is no official list of standard field names, so you just have to know the names your camera and software use; field names are case-sensitive.
		<br />&nbsp;<br />
		You can find more information in the <a href="http://en.wikipedia.org/wiki/Exchangeable_image_file_format" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">Exchangeable image file format</a> article on Wikipedia. You can find External Links to EXIF standards and tag listings at the end of the Wikipedia article.
		<br />&nbsp;<br />
		MLA uses a standard PHP function, <a href="http://php.net/manual/en/function.exif-read-data.php" title="PHP Manual page for exif_read_data" target="_blank">exif_read_data</a>, to extract EXIF data from images. The function returns three arrays in addition to the raw EXIF data; COMPUTED, THUMBNAIL and COMMENT. You can access the array elements by prefacing the element you want with the array name. For example, the user comment text is available as "COMPUTED.UserComment" and "COMPUTED.UserCommentEncoding". You can also get "COMPUTED.Copyright" and its two parts (if present), "COMPUTED.Copyright.Photographer" and "COMPUTED.Copyright.Editor". The THUMBNAIL and COMMENT arrays work in a similar fashion.
		<br />&nbsp;<br />
		Two special exif "pseudo-values" are available; <strong>ALL_IPTC</strong> (<code>[+exif:ALL_IPTC+]</code>) and <strong>ALL_EXIF</strong> (<code>[+exif:ALL_EXIF+]</code>). These return a string representation of all IPTC or EXIF data respectively. You can use these pseudo-values to examine the metadata in an image, find field names and see what values are embedded in the image.
		<br />&nbsp;<br />
		The ALL_EXIF value is altered in two ways. First, values of more than 256 characters are truncated to 256 characters. This prevents large fields such as image thumbnails from dominating the display. Second, array values are shown once, at their expanded level. For example the "COMPUTED" array is displayed as 'COMPUTED' => '(ARRAY)' and then 'COMPUTED.Width' => "2816", etc.</td>
	</tr>
</table>

<a name="mla_table_example"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>A Table-based Template Example</h3>
<p>
Here's a small example that shows a gallery using <code>&lt;table&gt;</code> markup.
The Item markup section shows how to use the "terms", "custom", "iptc" and "exif" substitution parameters.
</p>
<h4>Style Template</h4>
<code>
&lt;style type='text/css'&gt;<br />
&nbsp;&nbsp;#[+selector+] {<br />
&nbsp;&nbsp;&nbsp;&nbsp;margin: auto;<br />
&nbsp;&nbsp;}<br />
&nbsp;&nbsp;#[+selector+] .gallery-row {<br />
&nbsp;&nbsp;&nbsp;&nbsp;float: [+float+];<br />
&nbsp;&nbsp;&nbsp;&nbsp;margin-top: 10px;<br />
&nbsp;&nbsp;&nbsp;&nbsp;border-top: 1px solid #ddd;<br />
&nbsp;&nbsp;&nbsp;&nbsp;text-align: center;<br />
&nbsp;&nbsp;&nbsp;&nbsp;width: [+itemwidth+]%;<br />
&nbsp;&nbsp;}<br />
&nbsp;&nbsp;#[+selector+] .gallery-row td.gallery-icon {<br />
&nbsp;&nbsp;&nbsp;&nbsp;width: 60;<br />
&nbsp;&nbsp;&nbsp;&nbsp;height: 60;<br />
&nbsp;&nbsp;&nbsp;&nbsp;vertical-align: top;<br />
&nbsp;&nbsp;}<br />
&nbsp;&nbsp;#[+selector+] .gallery-row .gallery-icon img {<br />
&nbsp;&nbsp;&nbsp;&nbsp;border: 2px solid #cfcfcf;<br />
&nbsp;&nbsp;}<br />
&nbsp;&nbsp;#[+selector+] .gallery-caption {<br />
&nbsp;&nbsp;&nbsp;&nbsp;margin-left: 0;<br />
&nbsp;&nbsp;&nbsp;&nbsp;vertical-align: top;<br />
&nbsp;&nbsp;}<br />
&lt;/style&gt;
</code>
<h4>Markup Template</h4>
<table width="700" border="0" cellpadding="5">
	<tr>
		<td style="vertical-align: top; font-weight:bold">Open</td>
		<td><code>&lt;table id='[+selector+]' class='gallery galleryid-[+id+]<br />gallery-columns-[+columns+] gallery-size-[+size_class+]'&gt;</code></td>
	</tr>
	<tr>
		<td style="vertical-align: top; font-weight:bold">Row Open</td>
		<td><code>&lt;tr class='gallery-row'&gt;</code></td>
	</tr>
	<tr>
		<td style="vertical-align: top; font-weight:bold">Item</td>
		<td><code>&lt;td class='gallery-icon'&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+link+]<br />
	&lt;/td&gt;<br />
	&lt;td class='wp-caption-text gallery-caption'&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&lt;strong&gt;[+title+]&lt;/strong&gt;&lt;br /&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+description+]&lt;br /&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+date+]<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+custom:client,single+]<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+terms:category+]<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+iptc:caption-or-abstract+]<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+iptc:2#025,single+]<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+exif:Artist+]
	&lt;/td&gt;</code>
</td>
	</tr>
	<tr>
		<td style="vertical-align: top; font-weight:bold">Row Close</td>
		<td><code>&lt;/tr&gt;</code></td>
	</tr>
	<tr>
		<td style="vertical-align: top; font-weight:bold">Close</td>
		<td><code>&lt;/table&gt;</code></td>
	</tr>
</table>
<a name="mla_views"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Library Views/Post MIME Type Processing</h3>
<p>
WordPress uses <em><strong>Post MIME Types</strong></em> (a terrible name; they have nothing to do with Posts or Pages), to define the views for the Media/Library screen and the Media Manager/Add Media "media items" drop down list. MLA's Media/Assistant screen uses an enhanced version of the list, <em><strong>Table Views</strong></em>, to support views with multiple MIME Types (e.g., "audio,video") and wildcard specifications (e.g. "*/*ms*").
</p>
<p>
The first time it is invoked, MLA will retrieve the current list of Post MIME Types and use it to initialize the list. MLA will add any custom items it finds added to the list by other plugins and code. Once the list is initialized, MLA's list will be used and other plugins and code will have no effect. You can disable MLA handling of the list by clearing the <em><strong>Enable View and Post MIME Type Support</strong></em> checkbox at the bottom of the screen and clicking "Save Changes".
</p>
<h4>Post MIME Type</h4>
<p>
The Post MIME Type list uses the Slug, Singular Label and Plural Label values. The Slug is most important; it uniquely identifies the list entry. The Slug value must be all lowercase and contain only letters, numbers, periods (.), slashes (/) and hyphens (-). For &ldquo;Post MIME Type&rdquo; items, the slug is also the MIME type specification and must be a single, valid MIME specification, e.g., &ldquo;image&rdquo; or &ldquo;image/jpeg&rdquo;. The labels are displayed in various admin screens.
</p>
<h4>Use Within WordPress</h4>
<p>
Within WordPress, the Post MIME Types list is returned from <code>/wp-includes/post.php, function get_post_mime_types()</code>. That function is called from:
</p>
<ul class="mla_settings">
<li><code>/wp-admin/includes/media.php function get_media_item()</code>, to validate the type of an attachment when it is edited,</li>
<li><code>/wp-admin/includes/post.php, function wp_edit_attachments_query()</code> to count the number of attachments of each type, and</li>
<li><code>/wp-includes/media.php function wp_enqueue_media()</code>, to populate the the Media Manager/Add Media "media items" drop down list.</li>
</ul>
<h4>Table View</h4>
<p>
The Table View list adds several enhancements to the Post MIME Type list. In the Specification field you can select several MIME types with a comma-separated list, e.g., "audio,video". Wildcard specifications are also supported. For example, "*/mpeg" to select audio and video mpeg formats or "application/*ms*" to select all Microsoft application formats (Word, Excel, etc.). In the Menu Order field you can enter numeric values to re-arrange the order in which the list entries are displayed in, for example, the Media/Assistant screen.
</p>
<p>
The Table View list also supports custom field queries. You can choose from three forms of the custom field specification:
</p>
<ul class="mla_settings">
<li>To return all items that have a non-NULL value in the field, simply enter the prefix "custom:" followed by the custom field name. For example, <code>custom:My Featured Items</code></li>
<li>To return all items that have a NULL value in the field, enter the prefix "custom:" followed by the custom field name and then ",null". For example, <code>custom:My Featured Items,null</code></li>
<li>To return all items that match one or more values, enter the prefix "custom:" followed by the custom field name and then "=" followed by a list of values. For example, <code>custom:Color=red</code> or <code>custom:Color=red,green,blue</code>. Wildcard specifications are also supported; for example, "*post" to match anything ending in "post" or "th*da*" to match values like "the date" and "this day".</li>
</ul>
<p>
If you have enabled the <em><strong>Media Manager Enhanced MIME Type filter</strong></em>, the Table View list will also be available in the Media Manager/Add Media "media items" drop down list.
</p>
<a name="mla_uploads"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>File Extension and MIME Type Processing</h3>
<p>
The file extension/MIME Type associations are used by WordPress to decide what kind of files can be uploaded to the Media Library and to fill in the <code>post_mime_type</code> value for files added to the Media Library. To upload a file, the file extension must be in this list and be active. 
</p>
<p>
The first time it is invoked, MLA will retrieve the current list of extensions and  MIME Types and use it to initialize the list. MLA will add any custom items it finds added to the list by other plugins and code. Once the list is initialized, MLA's list will be used and other plugins and code will have no effect. You can disable MLA handling of the list by clearing the <em><strong>Enable Upload MIME Type Support</strong></em> checkbox at the bottom of the screen and clicking "Save Changes".
</p>
<h4>Extension and MIME Type</h4>
<p>
The Extension is the file extension for this type, and unique key for the item. It must be all lowercase and contain only letters, numbers and hyphens (-). The MIME Type value must be all lowercase and contain only letters, numbers, periods (.), slashes (/) and hyphens (-). The MIME type specification must be a single, valid MIME specification, e.g., "image" or "image/jpeg". These two values are used to compose the list of valid extension/type associations for use within WordPress.
<p>
When a file is uploaded to your Media Library the MIME type associated with that file extension is saved in the WordPress database record for the item. Later, you can use the <code>post_mime_type</code> to, for example, include or exclude the item from an <code>[mla_gallery]</code> display. You can think of the MIME Type as another way to categorize items in the Media Library. Once assigned, the MIME Type is not changed if you later alter the association between file extension and MIME Type. Think twice, therefore, when adding a new association to the list or changing the MIME type associated with an existing extension on the list.
</p>
<h4>Icons and Icon Types</h4>
<p>WordPress maintains a list of "file types" which associate file extensions with type names used to select an icon image. For example, an "audio" file type is associated with an image of musical notes. There are nine of these types: archive, audio, code, default, document, interactive, spreadsheet, text and video. MLA has a much longer list; 112 icon types/images in all. If the "Enable MLA File Type Icons Support" checkbox at the bottom of the Settings screen, Uploads tab  is checked, the enhanced icon images will be used in place of the WordPress images.</p>
<p>The MLA icon images are slightly larger than the default images and square; 64x64 pixels. The images are drawn (with permission) from the <a href="http://www.softicons.com/free-icons/system-icons/crystal-project-icons-by-everaldo-coelho" target="_blank">Crystal Project Icons</a>, created by <a href="http://www.everaldo.com" target="_blank">Everaldo Coelho</a>, founder of <a href="http://www.yellowicon.com/" target="_blank">Yellowicon</a>. You can find the images in the <code>/plugins/media-library-assistant/images/crystal</code> directory.</p>
<p>You can change the icon image associated with any file extension by selecting a new value from the dropdown list on the Edit Upload MIME Type screen or in the Quick Edit area. You can change the icon image for several extensions at once using the Bulk Edit action.</p>
<p>If you have some other plugin or mechanism for handling the Upload MIME Type items, you can disable MLA support entirely. Clear the checkbox at the bottom-left corner of the screen and click "Save Changes".</p>
<h4>Source and Status</h4>
<p>
The "Source" of an Upload MIME Type reveals where the extension/MIME Type association comes from:
<ul class="mla_settings">
<li><strong>core</strong>: WordPress defines a core set of extensions and associated MIME types, and this list changes with new WordPress releases. These are the "official" items. You can't delete them, but you can inactivate them so they are not used to validate file uploads.</li>
<li><strong>mla</strong>: Media Library Assistant adds several more extension/type items, drawing from the most popular items found in other plugins and web sites. They are initialized as "inactive" items, so you must explicitly decide to activate them for use in file upload validation.</li>
<li><strong>custom</strong>: Defined by some other plugin or code, or by manual action. When MLA first builds its list it will automatically add anything it finds in your current list as a new, active custom item. After that, you can use MLA to manage them.</li>
</ul>
<p>
The "Status" of an item determines whether it is used by WordPress to validate file uploads and assign MIME types to attachments in your Media Library. Only "active" items are used in this way; making an item "inactive" will prevent further uploads with that extension but will NOT affect any attachments already in your Media Library.
</p>
<h4>Use Within WordPress</h4>
<p>
Within WordPress, the Uploads list is returned from two different functions. In the current MLA release, the same list is returned from both functions. It appears that <code>wp_get_mime_types()</code> is designed to be more liberal than <code>get_allowed_mime_types()</code>. If you have a reason to return different results from one or the other function, let me know and I will consider enhancing the plugin.
</p>
<p>
The first function is <code>/wp-includes/functions.php, function wp_get_mime_types()</code>. That function is called from:
</p>
<ul class="mla_settings">
<li><code>/wp-includes/class-wp-image-editor.php function get_mime_type()</code>, to validate the type of an attachment when it is edited,</li>
<li><code>/wp-includes/class-wp-image-editor.php function get_extension()</code>, to validate the type of an attachment when it is edited,</li>
<li><code>/wp-includes/functions.php, function do_enclose()</code> to "check content for video and audio links to add as enclosures", and</li>
<li><code>/wp-includes/functions.php, function get_allowed_mime_types()</code>, to populate the the Media Manager/Add Media "media items" drop down list.</li>
</ul>
<p>
The second function is <code>/wp-includes/functions.php, function get_allowed_mime_types()</code>. That function is called from:
</p>
<ul class="mla_settings">
<li><code>/wp-includes/formatting.php function sanitize_file_name()</code>, to validate the name of a file, and</li>
<li><code>/wp-includes/functions.php, function wp_check_filetype()</code>, to validate the type of a file before it is uploaded</li>
</ul>
<p>
The list is also used in <code>/wp-includes/ms-functions.php, function check_upload_mimes()</code>, which "is used to filter that list against the filetype whitelist provided by Multisite Super Admins at <code>/wp-admin/network/settings.php</code>." Multisite installs must respect this restriction, so any list we produce will be passed thru that function if it exists.
</p>
<p>
The Icon Type information is used primarily in a function that matches a file extension to an icon image:
</p>
<ul class="mla_settings">
<li><code>/wp-includes/post.php, function wp_mime_type_icon()</code></li>
</ul>
<p>
The icon images can be returned in a <code>[gallery]</code> or an <code>[mla_gallery]</code> shortcode when <code>size=icon</code> is specified. The icons also appear on the Media/Library and Media/Assistant submenu tables.
</p>
<a name="mla_optional_uploads"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Searching for Upload MIME Types</h3>
<p>
The association between file extensions and MIME Types is an inexact science, as you might imagine. Over the years many companies, standards bodies and other organizations have laid claim to file extensions for different purposes and defined MIME Types to suit their needs. As this is written there are hundreds of web sites with lists of extensions and corresponding MIME Types; most of them give no explanation or justification for their choices. 
</p>
<p>
If you come across a new file extension, or if the existing extension/MIME type association does not suit you, you can search the MLA list of over 1,500 alternatives. The list was compiled from several Internet sources and a vigorous attempt was made to get a Description for each choice. If you find a mistake or an entry missing from the list, let me know!
</p>
<p>
The <em><strong>Known File Extension/MIME Type Associations</strong></em> list will often list several MIME Types for a given file extension. Think carefully before choosing one that differs from the WordPress and MLA Types, if they exist. Once you choose an association and upload files of that type, think <strong>very carefully</strong> before changing it, because changes will <strong>not</strong> be applied to existing Media Library items.
</p>
<p>
For your own research, I suggest starting with these resources:
<ul class="mla_settings">
<li><a href="http://www.iana.org/assignments/media-types" target="_blank">Internet Assigned Number Authority MIME Media Types</a></li>
<li><a href="http://en.wikipedia.org/wiki/Internet_media_type" target="_blank">Wikipedia: Internet Media Type</a></li>
<li><a href="http://en.wikipedia.org/wiki/Mailcap" target="_blank">Wikipedia: Mailcap</a></li>
<li><a href="http://filext.com/" target="_blank">FILExt: A free online resource by Uniblue</a></li>
</ul>
<p>
Put on your boots and have a paddle handy - it's a swamp! Good luck.
</p>
<a name="mla_custom_field_mapping"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Custom Field Processing Options</h3>
<p>
On the Custom Fields tab of the Settings screen you can define the rules for mapping several types of file and image metadata to WordPress custom fields. Custom field mapping can be applied automatically when an attachment is added to the Media Library. You can refresh the mapping for <strong><em>ALL</em></strong> attachments using the command buttons on the screen. You can selectively apply the mapping in the bulk edit area of the Media/Assistant submenu table and/or on the Edit Media screen for a single attachment.
</p>
<p>
This is a powerful tool, but it comes at the price of additional database storage space and processing time to maintain and retrieve the data. <strong><em>Think carefully about your needs before you use this tool.</em></strong> You can disable or delete any rules you create, so you might want to set up some rules for a special project or analysis of your library and then discard them when you're done. That said, the advantages of mapping metadata to custom fields are:
</p>
<ul class="mla_settings">
<li>You can add the data to an <code>[mla_gallery]</code> with a field-level markup substitution parameter. For example, add the image dimensions or a list of all the intermediate sizes available for the image.</li>
<li>You can add the data as a sortable, searchable column to the Media/Assistant submenu table. For example, you can find all the "orphans" in your library by adding "reference_issues" and then sorting by that column. You can also click on any value in the column to filter the display on a single custom field value.</li>
</ul>
<p>
Most of the data elements are static, i.e., they do not change after the attachment is added to the Media Library.
The parent/reference information (parent_type, parent_name, parent_issues, reference_issues) and the "where-used" information (featured in, inserted in, gallery in and MLA gallery in) is dynamic; it will change as you define galleries, insert images in posts, define featured images, etc. Because of the database processing required to update this information, <strong><em>parent, where-used and reference data are NOT automatically refreshed</em></strong>. If you use these elements, you must manually refresh them with the "map data" buttons on the Settings screen, the bulk edit area or the Edit Media screen.
</p>
<p>
Several of the data elements are sourced from the WordPress "image_meta" array. The credit, caption, copyright and title elements are taken from the IPTC/EXIF metadata (if any), but they go through a number of filtering rules that are not easy to replicate with the MLA IPTC/EXIF processing rules. You may find these "image_meta" elements more useful than the raw IPTC/EXIF metadata.
</p>
<p>
If you just want to add a custom field to the Media/Assistant submenu, the quick edit area and/or the bulk edit area you can bypass the mapping logic by leaving the Data Source value as "-- None (select a value) --".
</p>
<a name="mla_custom_field_parameters"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h4>Data sources for custom field mapping</h4>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">-- None (select a value) --</td>
<td>nothing, i.e., no change to existing value (if any). Use this source if you just want to add a custom field to the Media/Assistant submenu and/or the inline edit areas.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">-- Metadata (see below) --</td>
<td>WordPress attachment metadata, from the <em>_wp_attachment_metadata</em> array. Enter the field you want in the text box below the dropdown list. More coding guidelines are given below this table.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">path</td>
<td>path portion of the base_file value, e.g., 2012/11/</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">file_name</td>
<td>file name portion of the base_file value, e.g., image.jpg</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">extension</td>
<td>extension portion of the base_file value, e.g., jpg</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">file_size</td>
<td>file size in bytes</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">upload_date</td>
<td>date and time attachment was added to the Media Library</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">dimensions</td>
<td>for image types, width x height, e.g., 1024x768</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">pixels</td>
<td>for image types, size in pixels, e.g., 307200 for a 640x480 image</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">width</td>
<td>for image types, width in pixels</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">height</td>
<td>for image types, height in pixels</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">hwstring_small</td>
<td>HTML dimensions of a "small" image, i.e., 128 or less width, 96 or less height. Not computed for images uploaded in WordPress 3.5 and later.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_keys</td>
<td>image size names for thumbnail versions of the image, e.g., "thumbnail, medium, large"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_names</td>
<td>image file names for thumbnail versions of the image, e.g., "image-150x150.jpg, image-300x225.jpg, image-600x288.jpg"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_bytes</td>
<td>file size in bytes for thumbnail versions of the image, e.g., "5127, 11829, 33968"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_pixels</td>
<td>image size in pixels for thumbnail versions of the image, e.g., "22500, 67500, 172800"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_dimensions</td>
<td>image dimensions for thumbnail versions of the image, e.g., "150x150, 300x225, 600x288"</td>
</tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_name[size]</td>
<td>image file name for a specific thumbnail version, e.g., size_name[medium] = "image-300x225.jpg"; set to empty string if the specified size does not exist. There will be a [size] choice for every thumbnail version registered with WordPress for the site.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_bytes[size]</td>
<td>file size in bytes for a specific thumbnail version, e.g., size_bytes[medium] = "11829"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_pixels[size]</td>
<td>image size in pixels for a specific thumbnail version, e.g., size_pixels[medium] = "67500"</td>
</tr>
<tr>
<tr>
<td style="width: 12em; padding-right: 10px; vertical-align: top; font-weight:bold">size_dimensions[size]</td>
<td>image dimensions for a specific thumbnail version, e.g., size_dimensions[medium] = "300x225"; set to empty string if the specified size does not exist. There will be a [size] choice for every thumbnail version registered with WordPress for the site.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_date</td>
<td>for "attached" (post_parent not zero) objects, "published on" date of the parent object</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_type</td>
<td>for "attached" (post_parent not zero) objects, post type of the parent object</td>
</tr>
<tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_name</td>
<td>for "attached" (post_parent not zero) objects, post title of the parent object</td>
</tr>
<tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_issues</td>
<td>summary of parent status (only) "issues", e.g., bad parent, invalid parent, unattached</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">reference_issues</td>
<td>summary of all reference and parent status "issues", e.g., orphan, bad parent, invalid parent, unattached</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">featured_in</td>
<td>the title, post/page type and id number of each post/page for which this item is the "featured image"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">featured_in_title</td>
<td>the title of each post/page for which this item is the "featured image"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">inserted_in</td>
<td>the title, post/page type and id number of each post/page where this item is inserted in the post/page content</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">inserted_in_title</td>
<td>the title of each post/page where this item is inserted in the post/page content</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">gallery_in</td>
<td>the title, post/page type and id number of each post/page where this item appears in a <code>[gallery]</code> shortcode</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">gallery_in_title</td>
<td>the title of each post/page where this item appears in a <code>[gallery]</code> shortcode</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_gallery_in</td>
<td>the title, post/page type and id number of each post/page where this item appears in an <code>[mla_gallery]</code> shortcode</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_gallery_in_title</td>
<td>the title of each post/page where this item appears in an <code>[mla_gallery]</code> shortcode</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">aperture</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">credit</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">camera</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">caption</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">created_timestamp</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">copyright</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">focal_length</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">iso</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">shutter_speed</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">title</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
</table>
<h4>Existing Text dropdown</h4>
<p>
If the custom field already has values for one or more items, you can use "Keep" to retain them or "Replace" to delete them. For format options other than "Multiple", "Keep" means that an item with a non-blank value in the field will be unchanged, and new values will be stored only in those items that do not have an existing value. For the "Multiple" option, the existing value(s) will be retained and any new values will be added as separate instances. 
</p>
<p>
You can combine "Keep" and "Multiple" in useful ways. For example, to create a "Used in" custom field, you could first define a rule to map the "Featured in" data source with the "Replace", "Multiple" and "Delete NULL values" parameters. Then, map the same field using the "Inserted in" data source with the "Keep" and "Multiple" parameters. That will add the Inserted in values to the Featured in values giving you a single column with both results.
</p>
<h4>Format dropdown</h4>
<p>
Sorting by custom fields in the Media/Assistant submenu is by string values. For numeric data this can cause odd-looking results, e.g., dimensions of "1200x768" will sort before "640x480". Numeic data sources are converted to strings and padded on the left with spaces if you use the "commas" format. This padding makes them sort more sensibly. The following example shows the results for the &quot;size_bytes[large]&quot; data source, sorted in ascending order:</p>
<table style="margin-left: 80px">
<thead>
<tr>
<td style="width: 80px; text-align:left">
Native
</td>
<td style="width: 80px; text-align:right">
Commas
</td>
</tr>
</thead>
<tbody>
<tr>
<td>374871</td>
<td style="width: 80px; text-align:right">4,096</td>
</tr>
<tr>
<td>4096</td>
<td style="width: 80px; text-align:right">8,192</td>
</tr>
<tr>
<td>440242</td>
<td style="width: 80px; text-align:right">374,871</td>
</tr>
<tr>
<td>8192</td>
<td style="width: 80px; text-align:right">440,242</td>
</tr>
</tbody>
</table>
<p>
Four data sources, "file_size", "pixels", "width" and "height", are <strong>always</strong> padded on the left with spaces, even if you use the "Native" format.
<h4>Option dropdown</h4>
<p>
Several data sources can return more than one value. For example, the "Inserted in" source can return a list of posts/pages that contain references to Media Library items. The format option dropdown can further refine your specification where multiple values exist. There are four options:
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">Text</td>
<td>stores a list of the data source values, separated by commas</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">Single</td>
<td>stores the first value and discards any additional values, e.g., "mp4" for the "audio" example below</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">Export</td>
<td>for single values, same as Text. For multiple values, stores all the field names and values (including nested arrays). For example, the below audio data would be returned in Export format as "array ('dataformat' => 'mp4', 'codec' => 'ISO/IEC 14496-3 AAC', 'sample_rate' => 48000, 'channels' => 2, 'bits_per_sample' => 16, 'lossless' => false, 'channelmode' => 'stereo')".</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">Multiple</td>
<td>stores each unique value in a separate instance (database row) of the custom field. This is the most flexible format, as explained below.</td>
</tr>
</table>
<p>
The "Multiple" option takes advantage of WordPress' ability to store multiple values for a given custom field name (key) as distinct instances (database rows). For example, consider an image that has been inserted in two different posts, "First Post" and "Second Post". The "Text" option would store both titles in a single custom field instance; "First Post,Second Post". The "Multiple" option would store two instances, "First Post" and "Second Post". If the custom field is added to the Media/Assistant submenu table as a column, you could click on either of the two values to filter the table listing by value. That would show you all the items inserted in First Post or all the items inserted in Second Post.
</p>
<h4>Delete NULL values Checkbox</h4>
<p>
The "Delete NULL values" checkbox lets you control what happens if the data source you've selected does not have a value for every attachment. If the checkbox is cleared (not checked), a single space character will be stored for each attachment if the data source value is empty. If the checkbox is set (checked), there will be no entry/row at all in the metadata table for empty data source values and the custom field will not appear in the Edit Media screen for those attachments. This saves space but might confuse other applications that expect a value to be present for all attachments.
</p>
<p>
If you use the "Multiple" format option you will almost certainly want to use the "Delete NULL values" option as well.
</p>
<h4>Custom field mapping for metadata fields</h4>
<p>
If you select "<strong>-- Metadata (see below) --</strong>" as the data source you must specify the name of the field you want in the text box below the data source dropdown box. Any of the fields in the <em>_wp_attachment_metadata</em> array may be named, including the new audio/video fields available with WordPress 3.6 and later. For example, "length_formatted" will return the length of a video attachment. You can specify fields within an array with a compound name, e.g., "audio.sample_rate" to get the sampling rate field from the "audio" array of a video attachment. If you simply specify "audio", you will get the values of every array element, e.g., "mp4,ISO/IEC 14496 AAC,48000,2,16,false,stereo". 
</p>
<a name="mla_iptc_exif_mapping"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>IPTC &amp; EXIF Processing Options</h3>
<p>
Some image file formats such as JPEG DCT or TIFF Rev 6.0 support the addition of data about the image, or <em>metadata</em>, in the image file. Many popular image processing programs such as Adobe PhotoShop allow you to populate metadata fields with information such as a copyright notice, caption, the image author and keywords that categorize the image in a larger collection. WordPress uses some of this information to populate the Title, Slug and Description fields when you add an image to the Media Library.
</p>
<p>
The Media Library Assistant has powerful tools for copying image metadata to:
<ul style="line-height: 1em; list-style-type:disc; margin-left: 20px ">
<li>the WordPress standard fields, e.g., the Caption</li>
<li>taxonomy terms, e.g., in categories, tags or custom taxonomies</li>
<li>WordPress Custom Fields</li>
</ul>
You can define the rules for mapping metadata on the "IPTC/EXIF" tab of the Settings page. You can choose to automatically apply the rules when new media are added to the Library (or not). You can click the "Map IPTC/EXIF metadata" button on the Edit Media/Edit Single Item screen or in the bulk edit area to selectively apply the rules to one or more images. You can click the "Map All Attachments Now" to apply the rules to <strong><em>ALL of the images in your library</em></strong> at one time.
</p>
<h4>Mapping tables</h4>
<p>
The three mapping tables on the IPTC/EXIF tab have the following columns:
<dl>
<dt>Field Title</dt>
<dd>The standard field title, taxonomy name or Custom Field name. In the Custom Field table you can define a new field by entering its name in the blank box at the bottom of the list; the value will be saved when you click "Save Changes" at the bottom of the screen.
</dd>
<dt>IPTC Value</dt>
<dd>The IPTC (International Press Telecommunications Council) metadata, if any, embedded in the image file. For this category, you can select any of the IPTC DataSet tag and field identifiers, e.g., "2#025" for the Keywords field. The dropdown list has the identifier and the "friendly name" MLA defines for most of the IPTC fields; see the table of identifiers and friendly names in the table below. You can find more information in the <a href="http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">IPTC-NAA Information Interchange Model Version No. 4.1 specification</a>.
</dd>
<dt>EXIF Value</dt>
<dd>The EXIF (EXchangeable Image File) metadata, if any, embedded in a JPEG DCT or TIFF Rev 6.0 image file. 
 Though the specification is not currently maintained by any industry or standards organization, almost all camera manufacturers use it. For this category, you can code any of the field names embedded in the image by the camera or editing software. There is no official list of standard field names, so you just have to know the names your camera and software use; field names are case-sensitive. You can find more information in the <a href="http://en.wikipedia.org/wiki/Exchangeable_image_file_format" title="Exchangeable image file format Wikipedia article" target="_blank">Exchangeable image file format</a> article on Wikipedia. You can find External Links to EXIF standards and tag listings at the end of the Wikipedia article.
		<br />&nbsp;<br />
		MLA uses a standard PHP function, <a href="http://php.net/manual/en/function.exif-read-data.php" title="PHP Manual page for exif_read_data" target="_blank">exif_read_data</a>, to extract EXIF data from images. The function returns three arrays in addition to the raw EXIF data; COMPUTED, THUMBNAIL and COMMENT. You can access the array elements by prefacing the element you want with the array name. For example, the user comment text is available as "COMPUTED.UserComment" and "COMPUTED.UserCommentEncoding". You can also get "COMPUTED.Copyright" and its two parts (if present), "COMPUTED.Copyright.Photographer" and "COMPUTED.Copyright.Editor". The THUMBNAIL and COMMENT arrays work in a similar fashion.
		<br />&nbsp;<br />
		Two special exif "pseudo-values" are available; <strong>ALL_IPTC</strong> and <strong>ALL_EXIF</strong>. These return a string representation (in &quot;export&quot; format) of all IPTC or EXIF data respectively. You can use these pseudo-values to examine the metadata in an image, find field names and see what values are embedded in the image.
</dd>
<dt>Priority</dt>
<dd>If both the IPTC Value and the EXIF Value are non-blank for a particular image, you can select which of the values will be used for the mapping.
</dd>
<dt>Existing Text</dt>
<dd>Images already in the Media Library will have non-blank values in many fields and may have existing terms in a taxonomy. You can select "Keep" to retain these values or "Replace" to always map a metadata value into the field. For a taxonomy, "Keep" will retain any terms already assigned to the item and "Replace" will delete any existing terms before assigning metadata values as terms.
</dd>
<dt>Parent</dt>
<dd>For hierarchical taxonomies such as Categories you can select one of the existing terms in the taxonomy as the parent term for any terms you are mapping from metadata values. For example, you could define "IPTC Keywords" as a parent and then assign all of the 2#025 values under that parent term.
</dd>
</dl>
<h4>Map All Attachments Now</h4>
<p>
To the right of each table heading is a "Map All Attachments Now" button. When you click one of these buttons, the mapping rules in that table are applied to <strong><em>ALL of the images in the Media Library</em></strong>. This is a great way to bring your media items up to date, but it is <strong><em>NOT REVERSIBLE</em></strong>, so think carefully before you click!
Each button applies the rules in just one category, so you can update taxonomy terms without disturbing standard or custom field values.
</p>
<p>
These buttons <strong><em>DO NOT</em></strong> save any rules changes you've made, so you can make a temporary rule change and process your attachments without disturbing the standing rules.
</p>
<h4>Other mapping techniques</h4>
<p>
There are two other ways you can perform metadata mapping to one or more existing Media Library images:
<dl>
<dt>Single Item Edit/Edit Media screen</dt>
<dd>For WordPress 3.5 and later, you can click the "Map IPTC/EXIF metadata" link in the "Image Metadata" postbox to apply the standing mapping rules to a single attachment.  For WordPress 3.4.x and earlier, you can click the "Map IPTC/EXIF metadata" button on the Single Item Edit screen to apply the standing mapping rules.
</dd>
<dt>Bulk Action edit area</dt>
<dd>To perform mapping for a group of attachments you can use the Bulk Action facility on the main Assistant screen. Check the attachments you want to map, select "edit" from the Bulk Actions dropdown list and click "Apply". The bulk edit area will open with a list of the checked attachments in the left-hand column. You can click the "Map IPTC/EXIF metadata" button in the lower left corner of the area to apply the standing mapping rules to the attachments in the list.
</dd>
</dl>
</p>
<h4>WordPress default title, slug and description mapping</h4>
<p>
When WordPress uploads a new image file that contains IPTC and EXIF metadata it automatically maps metadata values to the title (post_title), name/slug (post_name) and description (post_content) fields. This happens before the MLA mapping rules are applied, so if you want to override the default mapping you must select "Replace" in the "Existing Text" column.
</p>
<p>
The WordPress rules are somewhat complex; consult the source code if you need exact details. Roughly speaking, the priority order for mapping the post_title and post_name values from non-blank IPTC/EXIF metadata is:
<ol style="line-height: 1.25em;  margin-left: 20px ">
<li>EXIF "Title"</li>
<li>EXIF "ImageDescription" (if less than 80 characters)</li>
<li>IPTC 2#105 "headline"</li>
<li>IPTC 2#005 "object-name"</li>
<li>IPTC 2#120 "caption-or-abstract" (if less than 80 characters)</li>
</ol>
The priority order for mapping the post_content value from non-blank IPTC/EXIF metadata is:
<ol style="line-height: 1.25em;  margin-left: 20px ">
<li>EXIF "ImageDescription" (if different from post_title)</li>
<li>IPTC 2#120 "caption-or-abstract" (if different from post_title)</li>
</ol>
</p>
<a name="mla_iptc_identifiers"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>IPTC Identifiers and Friendly Names</h3>
<table>
<tr><td colspan="3" style="font-weight:bold">Envelope Record</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">model-version</td><td style="padding-right: 10px; vertical-align: top">1#000</td><td style="padding-right: 10px; vertical-align: top">2 octet binary IIM version number</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">destination</td><td style="padding-right: 10px; vertical-align: top">1#005</td><td style="padding-right: 10px; vertical-align: top">Max 1024 characters of Destination (ISO routing information); repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">file-format</td><td style="padding-right: 10px; vertical-align: top">1#020</td><td style="padding-right: 10px; vertical-align: top">2 octet binary file format number, see IPTC-NAA V4 Appendix A</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">file-format-version</td><td style="padding-right: 10px; vertical-align: top">1#022</td><td style="padding-right: 10px; vertical-align: top">2 octet binary file format version number</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">service-identifier</td><td style="padding-right: 10px; vertical-align: top">1#030</td><td style="padding-right: 10px; vertical-align: top">Max 10 characters of Service Identifier and product</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">envelope-number</td><td style="padding-right: 10px; vertical-align: top">1#040</td><td style="padding-right: 10px; vertical-align: top">8 Character Envelope Number</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">product-id</td><td style="padding-right: 10px; vertical-align: top">1#050</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters subset of provider's overall service; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">envelope-priority</td><td style="padding-right: 10px; vertical-align: top">1#060</td><td style="padding-right: 10px; vertical-align: top">1 numeric character of envelope handling priority (not urgency)</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">date-sent</td><td style="padding-right: 10px; vertical-align: top">1#070</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of Date Sent by service - CCYYMMDD</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">time-sent</td><td style="padding-right: 10px; vertical-align: top">1#080</td><td style="padding-right: 10px; vertical-align: top">11 characters of Time Sent by service - HHMMSS±HHMM</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">coded-character-set</td><td style="padding-right: 10px; vertical-align: top">1#090</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters of control functions, etc.</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">uno</td><td style="padding-right: 10px; vertical-align: top">1#100</td><td style="padding-right: 10px; vertical-align: top">14 to 80 characters of eternal, globally unique identification for objects</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">arm-identifier</td><td style="padding-right: 10px; vertical-align: top">1#120</td><td style="padding-right: 10px; vertical-align: top">2 octet binary Abstract Relationship Model Identifier</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">arm-version</td><td style="padding-right: 10px; vertical-align: top">1#122</td><td style="padding-right: 10px; vertical-align: top">2 octet binary Abstract Relationship Model Version</td></tr>
<tr><td colspan="3" style="font-weight:bold">&nbsp;<br />Application Record</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">record-version</td><td style="padding-right: 10px; vertical-align: top">2#000</td><td style="padding-right: 10px; vertical-align: top">2 octet binary Information Interchange Model, Part II version number</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">object-type-reference</td><td style="padding-right: 10px; vertical-align: top">2#003</td><td style="padding-right: 10px; vertical-align: top">3 to 67 Characters of Object Type Reference number and optional text</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">object-attribute-reference</td><td style="padding-right: 10px; vertical-align: top">2#004</td><td style="padding-right: 10px; vertical-align: top">3 to 67 Characters of Object Attribute Reference number and optional text; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">object-name</td><td style="padding-right: 10px; vertical-align: top">2#005</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters of the object name or shorthand reference</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">edit-status</td><td style="padding-right: 10px; vertical-align: top">2#007</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters of the status of the objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">editorial-update</td><td style="padding-right: 10px; vertical-align: top">2#008</td><td style="padding-right: 10px; vertical-align: top">2 numeric characters of the type of update this object provides</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">urgency</td><td style="padding-right: 10px; vertical-align: top">2#010</td><td style="padding-right: 10px; vertical-align: top">1 numeric character of the editorial urgency of content</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">subject-reference</td><td style="padding-right: 10px; vertical-align: top">2#012</td><td style="padding-right: 10px; vertical-align: top">13 to 236 characters of a structured definition of the subject matter; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">category</td><td style="padding-right: 10px; vertical-align: top">2#015</td><td style="padding-right: 10px; vertical-align: top">Max 3 characters of the subject of the objectdata, DEPRECATED</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">supplemental-category</td><td style="padding-right: 10px; vertical-align: top">2#020</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters (each) of further refinement of subject, DEPRECATED; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">fixture-identifier</td><td style="padding-right: 10px; vertical-align: top">2#022</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters identifying recurring, predictable content</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">keywords</td><td style="padding-right: 10px; vertical-align: top">2#025</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters (each) of tags; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">content-location-code</td><td style="padding-right: 10px; vertical-align: top">2#026</td><td style="padding-right: 10px; vertical-align: top">3 characters of ISO3166 country code or IPTC-assigned code; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">content-location-name</td><td style="padding-right: 10px; vertical-align: top">2#027</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters of publishable country/geographical location name; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">release-date</td><td style="padding-right: 10px; vertical-align: top">2#030</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of Release Date (earliest use) - CCYYMMDD</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">release-time</td><td style="padding-right: 10px; vertical-align: top">2#035</td><td style="padding-right: 10px; vertical-align: top">11 characters of Release Time (earliest use) - HHMMSS±HHMM</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">expiration-date</td><td style="padding-right: 10px; vertical-align: top">2#037</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of Expiration Date (latest use) -  CCYYMDD</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">expiration-time</td><td style="padding-right: 10px; vertical-align: top">2#038</td><td style="padding-right: 10px; vertical-align: top">11 characters of Expiration Time (latest use) - HHMMSS±HHMM</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">special-instructions</td><td style="padding-right: 10px; vertical-align: top">2#040</td><td style="padding-right: 10px; vertical-align: top">Max 256 Characters of editorial instructions, e.g., embargoes and warnings</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">action-advised</td><td style="padding-right: 10px; vertical-align: top">2#042</td><td style="padding-right: 10px; vertical-align: top">2 numeric characters of type of action this object provides to a previous object</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">reference-service</td><td style="padding-right: 10px; vertical-align: top">2#045</td><td style="padding-right: 10px; vertical-align: top">Max 10 characters of the Service ID (1#030) of a prior envelope; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">reference-date</td><td style="padding-right: 10px; vertical-align: top">2#047</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of prior envelope Reference Date (1#070) - CCYYMMDD; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">reference-number</td><td style="padding-right: 10px; vertical-align: top">2#050</td><td style="padding-right: 10px; vertical-align: top">8 characters of prior envelope Reference Number (1#040); repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">date-created</td><td style="padding-right: 10px; vertical-align: top">2#055</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of intellectual content Date Created - CCYYMMDD</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">time-created</td><td style="padding-right: 10px; vertical-align: top">2#060</td><td style="padding-right: 10px; vertical-align: top">11 characters of intellectual content Time Created - HHMMSS±HHMM</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">digital-creation-date</td><td style="padding-right: 10px; vertical-align: top">2#062</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of digital representation creation date - CCYYMMDD</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">digital-creation-time</td><td style="padding-right: 10px; vertical-align: top">2#063</td><td style="padding-right: 10px; vertical-align: top">11 characters of digital representation creation time - HHMMSS±HHMM</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">originating-program</td><td style="padding-right: 10px; vertical-align: top">2#065</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters of the program used to create the objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">program-version</td><td style="padding-right: 10px; vertical-align: top">2#070</td><td style="padding-right: 10px; vertical-align: top">Program Version - Max 10 characters of the version of the program used to create the objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">object-cycle</td><td style="padding-right: 10px; vertical-align: top">2#075</td><td style="padding-right: 10px; vertical-align: top">1 character where a=morning, p=evening, b=both</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">by-line</td><td style="padding-right: 10px; vertical-align: top">2#080</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters of the name of the objectdata creator, e.g., the writer, photographer; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">by-line-title</td><td style="padding-right: 10px; vertical-align: top">2#085</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters of the title of the objectdata creator; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">city</td><td style="padding-right: 10px; vertical-align: top">2#090</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters of the city of objectdata origin</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">sub-location</td><td style="padding-right: 10px; vertical-align: top">2#092</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters of the location within the city of objectdata origin</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">province-or-state</td><td style="padding-right: 10px; vertical-align: top">2#095</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters of the objectdata origin Province or State</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">country-or-primary-location-code</td><td style="padding-right: 10px; vertical-align: top">2#100</td><td style="padding-right: 10px; vertical-align: top">3 characters of ISO3166 or IPTC-assigned code for Country of objectdata origin</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">country-or-primary-location-name</td><td style="padding-right: 10px; vertical-align: top">2#101</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters of publishable country/geographical location name; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">original-transmission-reference</td><td style="padding-right: 10px; vertical-align: top">2#103</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters of a code representing the location of original transmission</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">headline</td><td style="padding-right: 10px; vertical-align: top">2#105</td><td style="padding-right: 10px; vertical-align: top">Max 256 Characters of a publishable entry providing a synopsis of the contents of the objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">credit</td><td style="padding-right: 10px; vertical-align: top">2#110</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters that identifies the provider of the objectdata (Vs. the owner/creator)</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">source</td><td style="padding-right: 10px; vertical-align: top">2#115</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters that identifies the original owner of the intellectual content</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">copyright-notice</td><td style="padding-right: 10px; vertical-align: top">2#116</td><td style="padding-right: 10px; vertical-align: top">Max 128 Characters that contains any necessary copyright notice</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">contact</td><td style="padding-right: 10px; vertical-align: top">2#118</td><td style="padding-right: 10px; vertical-align: top">Max 128 characters that identifies the person or organization which can provide further background information; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">caption-or-abstract</td><td style="padding-right: 10px; vertical-align: top">2#120</td><td style="padding-right: 10px; vertical-align: top">Max 2000 Characters of a textual description of the objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">caption-writer-or-editor</td><td style="padding-right: 10px; vertical-align: top">2#122</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters that the identifies the person involved in the writing, editing or correcting the objectdata or caption/abstract; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">rasterized-caption</td><td style="padding-right: 10px; vertical-align: top">2#125</td><td style="padding-right: 10px; vertical-align: top">7360 binary octets of the rasterized caption - 1 bit per pixel, 460x128-pixel image</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">image-type</td><td style="padding-right: 10px; vertical-align: top">2#130</td><td style="padding-right: 10px; vertical-align: top">2 characters of color composition type and information</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">image-orientation</td><td style="padding-right: 10px; vertical-align: top">2#131</td><td style="padding-right: 10px; vertical-align: top">1 alphabetic character indicating the image area layout - P=portrait, L=landscape, S=square</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">language-identifier</td><td style="padding-right: 10px; vertical-align: top">2#135</td><td style="padding-right: 10px; vertical-align: top">2 or 3 alphabetic characters containing the major national language of the object, according to the ISO 639:1988 codes</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">audio-type</td><td style="padding-right: 10px; vertical-align: top">2#150</td><td style="padding-right: 10px; vertical-align: top">2 characters identifying monaural/stereo and exact type of audio content</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">audio-sampling-rate</td><td style="padding-right: 10px; vertical-align: top">2#151</td><td style="padding-right: 10px; vertical-align: top">6 numeric characters representing the audio sampling rate in hertz (Hz)</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">audio-sampling-resolution</td><td style="padding-right: 10px; vertical-align: top">2#152</td><td style="padding-right: 10px; vertical-align: top">2 numeric characters representing the number of bits in each audio sample</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">audio-duration</td><td style="padding-right: 10px; vertical-align: top">2#153</td><td style="padding-right: 10px; vertical-align: top">6 numeric characters of the Audio Duration - HHMMSS</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">audio-outcue</td><td style="padding-right: 10px; vertical-align: top">2#154</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters of the content of the end of an audio objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">objectdata-preview-file-format</td><td style="padding-right: 10px; vertical-align: top">2#200</td><td style="padding-right: 10px; vertical-align: top">2 octet binary file format of the ObjectData Preview</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">objectdata-preview-file-format-version</td><td style="padding-right: 10px; vertical-align: top">2#201</td><td style="padding-right: 10px; vertical-align: top">2 octet binary particular version of the ObjectData Preview File Format</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">objectdata-preview-data</td><td style="padding-right: 10px; vertical-align: top">2#202</td><td style="padding-right: 10px; vertical-align: top">Max 256000 binary octets containing the ObjectData Preview data</td></tr>
<tr><td colspan="3" style="font-weight:bold">&nbsp;<br />Pre ObjectData Descriptor Record</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">size-mode</td><td style="padding-right: 10px; vertical-align: top">7#010</td><td style="padding-right: 10px; vertical-align: top">1 numeric character - 0=objectdata size not known, 1=objectdata size known at beginning of transfer</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">max-subfile-size</td><td style="padding-right: 10px; vertical-align: top">7#020</td><td style="padding-right: 10px; vertical-align: top">4 octet binary maximum subfile dataset(s) size</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">objectdata-size-announced</td><td style="padding-right: 10px; vertical-align: top">7#090</td><td style="padding-right: 10px; vertical-align: top">4 octet binary objectdata size if known at beginning of transfer</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">maximum-objectdata-size</td><td style="padding-right: 10px; vertical-align: top">7#095</td><td style="padding-right: 10px; vertical-align: top">4 octet binary largest possible objectdata size</td></tr>
<tr><td colspan="3" style="font-weight:bold">&nbsp;<br />ObjectData</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">subfile</td><td style="padding-right: 10px; vertical-align: top">8#010</td><td style="padding-right: 10px; vertical-align: top">Subfile DataSet containing the objectdata itself; repeatable</td></tr>
<tr><td colspan="3" style="font-weight:bold">&nbsp;<br />Post ObjectData Descriptor Record</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">confirmed-objectdata-size</td><td style="padding-right: 10px; vertical-align: top">9#010</td><td style="padding-right: 10px; vertical-align: top">4 octet binary total objectdata size</td></tr>
</table>
<p>
<a href="#backtotop">Go to Top</a>
</p>
</div>