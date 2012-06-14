=== Advanced Custom Fields ===
Contributors: Elliot Condon
Tags: custom, field, custom field, advanced, simple fields, magic fields, more fields, repeater, matrix, post, type, text, textarea, file, image, edit, admin
Requires at least: 3.0
Tested up to: 3.3
Stable tag: 3.3

Fully customise WordPress edit screens with powerful fields. Boasting a professional interface and a powerfull API, it’s a must have for any web developer working with WordPress.Field types include: Wysiwyg, text, textarea, image, file, select, checkbox, page link, post object, date picker, color picker and more!

== Description ==

Advanced Custom Fields is the perfect solution for any wordpress website which needs more flexible data like other Content Management Systems. 

* Visually create your Fields
* Select from multiple input types (text, textarea, wysiwyg, image, file, page link, post object, relationship, select, checkbox, radio buttons, repeater, more to come)
* Assign your fields to multiple edit pages (via custom location rules)
* Easily load data through a simple and friendly API
* Uses the native WordPress custom post type for ease of use and fast processing
* Uses the native WordPress metadata for ease of use and fast processing

= Field Types =
* Text (type text, api returns text)
* Text Area (type text, api returns text with `<br />` tags)
* WYSIWYG (a wordpress wysiwyg editor, api returns html)
* Image (upload an image, api returns the url)
* File (upload a file, api returns the url)
* Select (drop down list of choices, api returns chosen item)
* Checkbox (tick for a list of choices, api returns array of choices)
* Page Link (select 1 or more page, post or custom post types, api returns the url)
* Post Object (select 1 or more page, post or custom post types, api returns post objects)
* Date Picker (jquery date picker, options for format, api returns string)
* True / False (tick box with message, api returns true or false)
* Repeater (ability to create repeatable blocks of fields!)
* Relationship	(select and order post objects with a tidy interface)
* Color Picker (Farbtastic!)

= Tested on =
* Mac Firefox 	:)
* Mac Safari 	:)
* Mac Chrome	:)
* PC Firefox	:)
* PC ie7	:S

= Website =
http://www.advancedcustomfields.com/

= Documentation =
http://www.advancedcustomfields.com/docs/getting-started/

= Field Type Info =
http://www.advancedcustomfields.com/docs/field-types/

= Bug Submission and Forum Support =
http://www.advancedcustomfields.com/support/

= Please Vote and Enjoy =
Your votes really make a difference! Thanks.


== Installation ==

1. Upload 'advanced-custom-fields' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on the new menu itme "Custom Fields" and create your first Custom Field Group!
4. Your custom field group will now appear on the page / post / template you specified in the field group's location rules!
5. Read the documentation to display your data: 


== Frequently Asked Questions ==

= Q. I have a question =
A. Chances are, someone else has asked it. Check out the support forum at: 
http://www.advancedcustomfields.com/support/


== Screenshots ==
1. Creating the Advanced Custom Fields

2. Adding the Custom Fields to a page and hiding the default meta boxes

3. The Page edit screen after creating the Advanced Custom Fields

4. Simple and intuitive API. Read the documentation at: http://www.advancedcustomfields.com/docs/functions/


== Changelog ==

= 3.2.5 =
* [IMPORTANT] Change field group option "Show on page" to "Hide on Screen" to allow for future proof adding new elements to list. Previously exported and registered field groups via PHP will still work as expected! This change will prompt you for a database upgrade.
* [Added] Add in edit button to upload image / file thickbox
* [Improved] Changed loading default values. Now behaves as expected!
* [Fixed] Test / Fix full screen mode dissapearing from editor - http://www.advancedcustomfields.com/support/discussion/2124/full-screen-button-for-zen-mode-is-gone
* [Fixed] get_field returning false for 0 - http://advancedcustomfields.com/support/discussion/2115/get_field-returns-false-if-field-has-value-0
* [Improved] Improve relationship sortable code with item param - http://www.advancedcustomfields.com/support/discussion/comment/3536#Comment_3536
* [Fixed] IE category js bug - http://www.advancedcustomfields.com/support/discussion/2127/ie-78-category-checkbox-bug
* [Fixed] Flexible content field row css bug - http://www.advancedcustomfields.com/support/discussion/2126/space-between-fields-is-a-little-tight-in-3.2.33.2.4
* [Fixed] Repeater row limit in flexible field bug - http://www.advancedcustomfields.com/support/discussion/1635/repeater-with-row-limit-of-1-inside-flexible-field-no-rows-show
* [Fixed] Fix update message - appears on first activation
* [Fixed] Fix options page sidebar drag area - no border needed
* [Fixed] Fix export options page activation - http://www.advancedcustomfields.com/support/discussion/2112/options-page-not-working-in-functions.php

= 3.2.4 =
* [Fixed] Remove translation from validation class - http://www.advancedcustomfields.com/support/discussion/2110/custom-validation-broken-in-other-languages
* [Fixed] Test fix WYSIWYG insert media issues
* [Added] Add Excerpt to the field group "show on page" options

= 3.2.3 =
* [Fixed] Include Wysiwyg scripts / styles through the editor class
* [Fixed] Wysiwyg in repeater not working
* [Fixed] Remove Swedish translation until string / js bugs are fixed
* [Fixed] Checkbox  array value issue: http://wordpress.org/support/topic/plugin-advanced-custom-fields-php-warning-in-corefieldscheckboxphp?replies=6
* [Added] Add inherit to relationship posts query - http://www.advancedcustomfields.com/support/discussion/comment/3826#Comment_3826
* [Fixed] Relationship shows deleted posts - http://www.advancedcustomfields.com/support/discussion/2080/strange-behavior-of-relationship-field-trash-posts
* [Fixed] Wysiwyg editor not working on taxonomy edit page 

= 3.2.2 =
* [Fixed] Fix layout bug: Nested repeaters of different layouts
* [Fixed] Fix strip slashes bug
* [Fixed] Fix nested repeater bug - http://www.advancedcustomfields.com/support/discussion/2068/latest-update-broken-editing-environment-
* [Fixed] Test / Fix add multiple images to repeater

= 3.2.1 =
* Field groups can now be added to options page with layout "side"
* Fixed debug error when saving a taxonomy:
* Fixed unnecessary code: Remove Strip Slashes on save functions
* Added new add row buttons to the repeater field and upgraded the css / js
* Fixed debug error caused by the WYSIWYG field: wp_tiny_mce is deprecated since version 3.3! Use wp_editor() instead.
* Fixed duplicate field error where all sub fields became repeater fields.
* Add Swedish translation: http://advancedcustomfields.com/support/discussion/1993/swedish-translation
* CSS improvements
* Fixed IE9 Bug not returning an image preview on upload / select
* Fixed Multi export php syntax bug.

= 3.2.0 =
* Fixed Browser bug with Flexible Field: Add Row button works again
* Added Brazilian Translation. Thanks to Marcelo Paoli Graciano - www.paolidesign.com.br
* Reverted input CSS to separate field label / instructions onto new lines.

= 3.1.9 =
* Updated Images / JS - Please hard refresh your browser to clear your cache
* Remove caching from acf_field_groups, replace with temp cache
* Add "Duplicate Field" on field group edit page
* Fix link to documentation on field group edit page
* add "update_value" to API
* Include new Polish translation
* Create a nicer style for flexible content
* Create a nicer style for repeater fields with row layout
* Create a nicer style for "no metabox" fields
* Add Spanish translation. Thanks to @hectorgarrofe
* Fix css for options page no metabox
* Added custom post_updated_messages
* Changed "Drag and drop to reorder" from an image to a string for translation

= 3.1.8 =
* Options page fields now save their data in the wp_options table. This will require a "Database Upgrade" when you update ACF. This upgrade will move your Options page data from the postmeta table to the options table.
* Added _e() and __() functions to more text throughout plugin
* Added new French translation. Thanks to Martin Vauchel @littlbr http://littleboyrunning.com
* Fixed duplicate WYSIWYG in chrome bug
* New Location rules: add fields to a user / taxonomy / attachment
* Bug Fix: Color picker now shows color on page load. Thanks to Kev http://www.popcreative.co.uk
* CSS tweaks File clearfix, new style for selects with optgroups
* Simplified get_value to return default value if value == ""
* API now allows for "option" and "options" for the $post_id value in API functions

= 3.1.7 =
* Bug fix: Image field returns correct url after selecting one or more images
* Translation: Added Polish translation. Thank you Bartosz Arendt - Digital Factory - www.digitalfactory.pl
* Update : Added id attribute to all div.field (id="acf-$field_name")

= 3.1.6 =
* New style for buttons
* Bug Fix: Repeater maximum row setting was disabling the "add row" button 1 row early.
* Performance: Field options are now loaded in via ajax. This results in much less HTML on the edit field group page
* Performance: Field inputs are now loaded in via ajax. Again, less HTML on edit screens improves load times / memory usage
* Bug Fix: Field groups registered by code were not showing on ajax change (category / page type / page template / etc). To fix this, your field group needs a unique ID. When you export a field group, you will now be given a unique ID to fix this issue. Field groups without a fixed id will still show on page load.
* New Option: Repeater field can now have a custom button label
* New Option: Flexible content field can now have a custom button label
* Improvement: Updated the HTML / CSS for file fields with icon
* Bug Fix: Fixed multi upload / select image in repeater. 
* Performance: Added caching to the get_field function. Templates will now render quicker.
* Bug Fix: Fixed Post formats location rule - it now works.
* Nested repeaters are now possible!

= 3.1.5 =
* Improvement: Redesigned the experience for uploading and selecting images / files in fields and sub fields. Image / File fields within a repeater can now add multiple images / files

= 3.1.4 =
* New Feature: Front end form (Please read documentation on website for usage)
* Performance: compiled all field script / style into 1 .js file
* Bug Fix: Editor now remembers mode (Visual / HTML) without causing errors when loading in HTML mode
* Improvement: Added draft / private labels to post objects in relationship, post object and page link fields

= 3.1.3 =
* Bug Fix: Options page fields were rendered invisible in v3.1.2 (now fixed)
* Updated POT file with new texts

= 3.1.2 =
* New Feature: Required field validation. Note: Repeater / Flexible content fields can be required but their sub fields can not.
* Field update: Select field: API now returns false when "null" is selected
* Field update: Radio button: When editing a post / page, the radio button will select the first choice if there is no saved value for the field
* Bug fix: You can now use a repeater field inside a flexible field! Please note that the_repeater_field will not work as expected. Please use get_sub_field to get the sub repeater field, then use php to loop through it.

= 3.1.1 =
* New Feature: Added shortcode support. usage: [acf field="field_name"]
* Bug Fix: Fixed menu disappearing by changing the function "add_menu" to "add_utility_page"
* Visual: Changed post object / page link fields to display post type label instead of post type name for the select optgroup label. Thanks to kevwaddell for the code

= 3.1.0 =
* New Field: Flexible Content Field (license required)
* Bug Fix: ACF data now saves for draft posts (please do a hard refresh on an edit screen to remove cached js)
* Bug fix: Fixed multiple content editors
 
= 3.0.7 =
* Added export / register support via PHP
* Moved menu position under Settings
* Improve speed / php memory by introducing cached data
* Temp bug fix: sets content editor to "visual mode" to stop wysiwyg breaking
* Visual: Removed "Screen Options" tab from the admin acf edit page. Added filter to always show 99 acf's
* Minor JS improvements

= 3.0.6 =
* Bug Fix: Location meta box now shows all pages / posts
* Bug Fix: upgrade and settings url should now work / avoid conflicts with other plugins

= 3.0.5 =
* Support: use wp native functions to add all user roles to location metabox
* Update: gave acf a css update + new menu structure
* Bug fix: fixed a few issues with wysiwyg js/css in wp3.3
* Bug fix:  fixed page_name conflicting with normal pages / posts by adding a "acf_" to the page_name on save / update
* Performance: location metabox - limited taxonomies to hierarchial only. Posts and Pages have now been limited to 25

= 3.0.4 =
* Bug fix: WYSIWYG is now compatible with WP 3.3 (May have incidentally added support for gravity forms media button! But not 100% sure...)
* Fix : Taxonomy Location rule now only shows hierarchal taxonomies to improve speed and reduce php memory issues

= 3.0.3 =
* New translation: French (thanks to Netactions)
* Support: added support for new wp3.3 editor
* Bug fix: fixed WYSIWYG editor localised errors
* Bug fix: removed trailing commas for ie7

= 3.0.2 =
* New Feature: Added Export tab to export a WP native .xml file
* New Option: Relationship / Post type - filter by taxonomy
* New Option: default values for checkbox, select and radio
* New Function: register_options_page - add custom options pages (Requires the option page addon)
* Bug fix: WYSIWYG + repeater button issues
* Bug fix: general house keeping

= 3.0.1 =
* Bug Fix - repeater + wysiwyg delete / add duplicate id error
* Bug fix - repeater + file - add file not working
* Bug Fix - image / file no longer need the post type to support "editor"
* WYSIWYG - fixed broken upload images
* misc updates to accommodate the soon to be released "Flexible Field"

= 3.0.0 =
* ACF doesn't use any custom tables anymore! All data is saved as post_meta!
* Faster and more stable across different servers
* Drag-able / order-able metaboxes
* Fields extend from a parent object! Now you can create you own field types!
* New location rule: Taxonomy
* New function: register_field($class, $url);
* New Field: Color Picker
* New Option: Text + Textarea formatting
* New Option: WYSIWYG Show / Hide media buttons, Full / Basic Toolbar buttons (Great for a basic wysiwyg inside a repeater for your clients)
* Lots of bug fixes

= 2.1.4 =
* Fixed add image tinymce error for options Page WYSIWYG
* API: added new function: update_the_field($field_name, $value, $post_id)
* New field: Relationship field
* New Option for Relationship + Post Object: filter posts via meta_key and meta_value
* Added new option: Image preview size (thumb, medium, large, full)
* Fixed duplicate posts double value problem
* API update: get_field($repeater) will return an array of values in order, or false (like it used to!)
* Radio Button: added labels around values
* Post object + Page Link: select drop down is now hierarchal
* Input save errors fixed
* Add 'return_id' option to get_field / get_sub_field
* Many bug fixes

= 2.1.3 =
* Fixed API returning true for repeater fields with no data
* Added get_fields back into the api!
* Fixed field type select from showing multiple repeater activation messages 

= 2.1.2 =
* Fixed repeater sortable bug on options page
* Fixed wysiwyg image insert on options page
* Fixed checkbox value error
* Tidied up javascript + wysiwyg functions


= 2.1.1 =
* Fixed Javascript bugs on edit pages

= 2.1.0 =
* Integrate acf_values and wp_postmeta! Values are now saved as custom fields!
* Ajax load in fields + update fields when the page / post is modified
* API has been completely re written for better performance
* Default Value - text / textarea
* New upgrade database message / system
* Separate upgrade / activate scripts
* Select / page link / post object add Null option
* Integrate with Duplicate Posts plugin
* New location rule: post format
* Repeater field attach image to post
* Location: add children to drop down menu for page parent
* Update script replaces image urls with their id's
* All images / Files save as id's now, api formats the value back into a url
* Simple CSS + JS improvements
* New Field: Radio Buttons (please note Firefox has a current bug with jquery and radio buttons with the checked attribute)

= 2.0.5 =
* New Feature: Import / Export
* Bug Fixed: Wysiwyg javascript conflicts
* Bug Fixed: Wysiwyg popups conflicting with the date picker field
* New style for the date picker field

= 2.0.4 = 
* New Addon: Options Page (available on the plugins store: http://plugins.elliotcondon.com/shop/) 
* API: all functions now accept 'options' as a second parameter to target the options page
* API: the_field() now implodes array's and returns as a string separated by comma's
* Fixed Bug: Image upload should now work on post types without editor
* Fixed Bug: Location rule now returns true if page_template is set to 'Default' and a new page is created
* General Housekeeping

= 2.0.3 =
* Added Option: Repeater Layout (Row / Table)
* Fixed bug: Now you can search for media in the image / file fields
* Added Option: Image field save format (image url / attachment id)
* Added Option: File field save format (file url / attachment id)
* Fixed bug: Location rules for post categories now work
* Added rule: Page parent
* Fixed bug: "what's new" button now shows the changelog
* included new css style to fit in with WordPress 3.2
* minor JS improvements

= 2.0.2 =
* Added new database table "acf_rules"
* Removed database table "ac_options"
* Updated location meta box to now allow for custom location queries
* Hid Activation Code from logged in users
* Fixed JS bugs with wp v3.2 beta 2
* Added new option "Field group layout" - you can now wrap your fields in a metabox!
* General housekeeping

= 2.0.1 =
* Added Field Option: Field Instructions
* Added Field Option: Is field searchable? (saves field value as a normal custom field so you can use the field against wp queries)
* Added Media Search / Pagination to Image / File thickbox
* Added Media Upload support to post types which do not have a Content Editor.
* Fixed "Select Image" / "Select File" text on thickbox buttons after upload
* Repeater field now returns null if no data was added

= 2.0.0 =
* Completely re-designed the ACF edit page
* Added repeater field (unlocked through external purchase)
* Fixed minor js bugs
* Fixed PHP error handling
* Fixed problem with update script not running
* General js + css improvements

= 1.1.4 =
* Fixed Image / File upload issues
* Location now supports category names
* Improved API - now it doesn't need any custom fields!
* Fixed table encoding issue
* Small CSS / Field changes to ACF edit screen


= 1.1.3 =
* Image Field now uses WP thickbox!
* File Field now uses WP thickbox!
* Page Link now supports multiple select
* All Text has been wrapped in the _e() / __() functions to support translations!
* Small bug fixes / housekeeping
* Added ACF_WP_Query API function

= 1.1.2 =
* Fixed WYSIWYG API format issue
* Fixed Page Link API format issue
* Select / Checkbox can now contain a url in the value or label
* Can now unselect all user types form field options
* Updated value save / read functions
* Lots of small bug fixes

= 1.1.1 =
* Fixed Slashes issue on edit screens for text based fields

= 1.1.0 =
* Lots of Field Type Bug Fixes
* Now uses custom database tables to save and store data!
* Lots of tidying up
* New help button for location meta box
* Added $post_id parameter to API functions (so you can get fields from any post / page)
* Added support for key and value for select and checkbox field types
* Re wrote most of the core files due to new database tables
* Update script should copy across your old data to the new data system
* Added True / False Field Type

= 1.0.5 =
* New Field Type: Post Object
* Added multiple select option to Select field type

= 1.0.4 =
* Updated the location options. New Override Option!
* Fixed un ticking post type problem
* Added JS alert if field has no type

= 1.0.3 =
* Heaps of js bug fixes
* API will now work with looped posts
* Date Picker returns the correct value
* Added Post type option to Page Link Field
* Fixed Image + File Uploads!
* Lots of tidying up!

= 1.0.2 =
* Bug Fix: Stopped Field Options from loosing data
* Bug Fix: API will now work with looped posts

= 1.0.1 =
* New Api Functions: get_fields(), get_field(), the_field()
* New Field Type: Date Picker
* New Field Type: File
* Bug Fixes
* You can now add multiple ACF's to an edit page
* Minor CSS + JS improvements

= 1.0.0 =
* Advanced Custom Fields.


== Upgrade Notice ==

= 3.0.0 =
* Editor is broken in WordPress 3.3

= 2.1.4 =
* Adds post_id column back into acf_values