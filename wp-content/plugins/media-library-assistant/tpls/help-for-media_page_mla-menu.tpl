<!-- template="mla-overview" -->
<!-- title="Overview" order="1" -->
<p>All the files you&#8217;ve uploaded are listed in the Media/Assistant submenu table, ordered by the Title field. You can change the sort order by clicking on one of the blue column names. You can change the default sort order on the Settings screen.</p>
<p>You can use the Screen Options tab to customize the display of this screen. You can choose any combination of the columns available for display. You can also choose how many items appear on each page of the display.</p>
<p>You can narrow the list by file type/status using the text link filters at the top of the screen. You also can refine the list by month using the dropdown menu above the media table.</p>
<p>If you have selected taxonomy support, e.g., &#8220;Att. Categories&#8221;, you can filter the list by selecting &#8220;All Categories&#8221;, &#8220;No Categories&#8221; or a specific category from the drop down list. If you select a category that has child categories beneath it, attachments in any of the child categories will also appear in the filtered list. You can select the taxonomy you want to filter by on the Settings page.</p>
<p><strong>NOTE:</strong> Month and category filters are &#8220;sticky&#8221;, i.e., they will persist as you resort the display or change the file type/status view.</p>
<!-- template="mla-featured-inserted" -->
<!-- title="Featured/Inserted" order="2" -->
<p>The &#8220;Featured in&#8221; and &#8220;Inserted in&#8221; columns are a powerful tool for managing your attachments. They show you where each attachment is used in a post or page as a &#8220;Featured Image&#8221; or as an embedded image or link.</p>
<p>You can also use the information in the &#8220;Title/Name&#8221; column to identify &#8220;Orphan&#8221; items that are not used in any post or page and items with a &#8220;Bad Parent&#8221; (a parent that does contain any reference to the item) or an &#8220;Invalid Parent&#8221; (a parent that does not exist).</p>
<p>If performance is a concern, you can go to the Settings page and disable either or both of these columns.</p>
<!-- template="mla-gallery-in" -->
<!-- title="Gallery/MLA Gallery" order="3" -->
<p>The &#8220;Gallery in&#8221; and &#8220;MLA Gallery in&#8221; columns are a powerful tool for managing your attachments. They show you where each attachment is returned by a <code>[gallery]</code> or <code>[mla_gallery]</code> shortcode in a post or page. These columns do <strong>not</strong> use the post_parent (attached to) status of the item; they actually execute each shortcode and tabulate the attachments they return.</p>
<p>You can also use the information in the &#8220;Title/Name&#8221; column to identify &#8220;Orphan&#8221; items that are not used in any post or page and items with a &#8220;Bad Parent&#8221; (a parent that does contain any reference to the item) or an &#8220;Invalid Parent&#8221; (a parent that does not exist).</p>
<p>If performance is a concern, you can go to the Settings page and disable either or both of these columns. You can also adjust the settings to cache the results for fifteen minutes between updates. Results are automatically updated after a post, page or attachment is added or updated.</p>
<!-- template="mla-categories-tags" -->
<!-- title="Taxonomy Support" order="4" -->
<p>The &#8220;taxonomy&#8221; columns help you to group attachments by subject and keyword values. The columns list any categories and tags associated with the item. You can click on one of the displayed values to get a list of all items associated with that value.</p>
<p>The Media Library Assistant provides two pre-defined taxonomies, &#8220;Att. Categories&#8221; and &#8220;Att. Tags&#8221; which are enabled by default. You can add or remove support for any registered taxonomy on the Settings screen. The standard WordPress Categories and Tags as well as any custom taxonomies can be supported.</p>
<p>When you add support for a taxonomy it is visible on the main screen. If you want to hide the column simply use the Screen Options to uncheck the Show on screen box.</p>
<p>Supported taxonomies also appear as submenus below the Media menu at the left of the screen. You can edit the taxonomy terms by clicking these submenus. The taxonomy edit screens include an &#8220;Attachments&#8221; column which displays the number of attachment objects for each term. You can display a filtered list of the attachments by clicking on the number in this column.</p>
<!-- template="mla-custom-fields" -->
<!-- title="Custom Fields" order="5" -->
<p>You can add sortable columns to the Media/Assistant submenu table by visiting the Custom Fields tab on the Settings page. There you define rules that map attachment metadata such as file size to WordPress custom fields. The &#8220;MLA Column&#8221; checkbox for each rule lets you select which rules will appear on this screen.</p>
<!-- template="mla-search-media" -->
<!-- title="Search Media" order="6" -->
<p>The &#8220;Search Media&#8221; box supports a keyword search of several attachment fields; enter words and/or phrases in the box, separated by spaces. Click the Search Media button for a case-insensitive "SQL LIKE" search. Each keyword in the search phrase is matched independently, so the order of search words does not have to match the order in the text. For example, searching on "friend" and "best" will match "Best Friend". If you put quotes around a search phrase then word order is required for a match (and spaces between words must match as well). You can also match on partial words, e.g., "rien" will match "friend".</p>
<p>Once you&#8217;ve entered the terms you want, use the options below the box to tailor your search. You can pick the connector used between search terms; "or" means any of the terms will match, "and" means all of the terms must match. Use the checkboxes to extend your search to more fields in the database.</p>
<p>If you enter a numeric value (only) in the search box, it is interpreted as a search by attachment ID or parent ID (post_parent). You can search for a numeric value in the text fields, e.g., title, by putting quotes around the value.</p>
<!-- template="mla-bulk-actions" -->
<!-- title="Bulk Actions" order="7" -->
<p>The &#8220;Bulk Actions&#8221; dropdown list works with the check box column to let you make changes to many items at once. Click the check box in the column title row to select all items on the page, or click the check box in a row to select items individually.</p>
<p>Once you&#8217;ve selected the items you want, pick an action from the dropdown list and click Apply to perform the action on the selected items. The available actions will vary depending on the file type/status view you have picked.</p>
<p>If you have enabled Trash support (define MEDIA_TRASH in wp-config.php) you can use bulk actions to move items to and from the Trash or delete them permamently.</p>
<p>When using Bulk Edit, you can change the metadata (author, parent, taxonomy terms) for all selected attachments at once. To remove an attachment from the grouping, just click the x next to its name in the left column of the Bulk Edit area.</p>
<p>Bulk Edit support for taxonomy terms allows you to <strong>add, remove or completely replace</strong> terms for the selected attachments. Below each taxonomy edit box are three radio buttons that let you select the action you&#8217;d like to perform.</p>
<p>The taxonomies that appear in the Bulk Edit area can be a subset of the taxonomies supported on the single item edit screen. You can select which taxonomies appear by entering your choice(s) on the Media Libray Assistant Settings screen.</p>
<!-- template="mla-available-actions" -->
<!-- title="Available Actions" order="8" -->
<p>Hovering over a row reveals action links such as Edit, Quick Edit, Move to Trash and Delete Permanently. Clicking Edit displays a simple screen to edit that individual file&#8217;s metadata. Clicking Move to Trash will assign the file to the Trash pile but will not affect pages or posts to which it is attached. Clicking Delete Permanently will delete the file from the media library (as well as from any posts to which it is currently attached). Clicking Quick Edit displays an inline form to edit the file's metadata without leaving the menu screen.</p>
<p>The taxonomies that appear in the Quick Edit area can be a subset of the taxonomies supported on the single item edit screen. You can select which taxonomies appear by entering your choice(s) on the Media Libray Assistant Settings screen.</p>
<!-- template="mla-attaching-files" -->
<!-- title="Attaching Files" order="9" -->
<p>If a media file has not been attached to any post, you will see (unattached) in the Attached To column. You can click on the Edit or Quick Edit action to attach the file by assigning a value to the Parent ID field.</p>
<!-- template="sidebar" -->
<p><strong>For more information:</strong></p>
<p><a href="http://codex.wordpress.org/Media_Library_Screen" target="_blank">Documentation on Media Library</a></p>
<p><a href="http://wordpress.org/support/" target="_blank">Support Forums</a></p>