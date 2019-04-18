=== WP Shopify ===
Contributors: andrewmrobbins
Donate link: https://wpshop.io/purchase/
Tags: shopify, ecommerce, store, sell, products, shop, purchase, buy, wpshopify
Requires at least: 4.7
Requires PHP: 5.6
Tested up to: 5.1.1
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sell and build custom Shopify experiences on WordPress.

== Description ==

WP Shopify allows you to sell your Shopify products on any WordPress site. Your store data is synced as custom post types giving you the ability to utilize the full power of native WordPress functionality. On the front-end we use the [Shopify Buy Button](https://www.shopify.com/buy-button) to create an easy to use cart experience without the use of any iFrames.

= Features =
* Sync your products and collections as native WordPress post
* Templates
* No iFrames
* Over 100+ actions and filters allowing you to customize any part of the storefront
* Display your products using custom pages and shortcodes
* Built-in cart experience using [Shopify's Buy Button](https://www.shopify.com/buy-button)
* SEO optimized
* Advanced access to your Shopify data saved in custom database tables

See the [full list of features here](https://wpshop.io/how/)

https://www.youtube.com/watch?v=lYm6G35e8sI

= WP Shopify Pro =
WP Shopify is also available in a Pro version which includes 80+ Templates, Automatic Syncing, Order and Customer Data, Cross-domain Tracking, Live Support, and much more functionality! [Learn more](https://wpshop.io/purchase)

= Links =
* [Website](https://wpshop.io/)
* [Documentation](https://wpshop.io/docs)
* [WP Shopify Pro](https://wpshop.io/)


== Installation ==
From your WordPress dashboard

1. Visit Plugins > Add New
2. Search for *WP Shopify*
3. Activate WP Shopify from your Plugins page
4. Create a [Shopify private app](https://wpshop.io/docs). More [info here](https://help.shopify.com/manual/apps/private-apps)
5. Back in WordPress, click on the menu item __WP Shopify__ and begin syncing your Shopify store to WordPress.
6. We've created a [guide](https://wpshop.io/docs) if you need help during the syncing process

== Screenshots ==
[https://wpshop.io/screenshots/1-syncing-cropped.jpg  Easy and fast syncing process]
[https://wpshop.io/screenshots/2-settings-cropped.jpg  Many settings and options to choose from]
[https://wpshop.io/screenshots/3-posts-cropped.jpg  Sync your store as native WordPress posts]


== Frequently Asked Questions ==

Read the [full list of FAQ](https://wpshop.io/faq/)

= How does this work? =
You can think of WordPress as the frontend and Shopify as the backend. You manage your store (add products, change prices, etc) from within Shopify and those changes sync into WordPress. WP Shopify also allows you to sell your products and is bundled with a cart experience using the [Shopify Buy Button SDK](https://www.shopify.com/buy-button).

After installing the plugin you connect your Shopify store to WordPress by filling in your Shopify API keys. After syncing, you can display / sell your products in various ways such as:

1. Using the default pages “yoursite.com/products” and “yoursite.com/collections“
2. Shortcodes [wps_products] and [wps_collections]

We also save your Shopify products as Custom Post Types enabling you to harness the native power of WordPress.

= Doesn’t Shopify already have a WordPress plugin? =
Technically yes but it [has been discontinued](https://wptavern.com/shopify-discontinues-its-official-plugin-for-wordpress).

Shopify has instead moved attention to their [Buy Button](https://www.shopify.ca/buy-button) which is an open-source library that allows you to embed products with snippets of HTML and JavaScript. The main drawback to this is that Shopify uses iFrames for the embeds which limit the ability for layout customizations.

WP Shopify instead uses a combination of the Buy Button and Shopify API to create an iFrame-free experience. This gives allows you to sync Shopify data directly into WordPress. We also save the products and collections as Custom Post Types which unlocks the native power of WordPress.

= Is this SEO friendly? =
We’ve gone to great lengths to ensure we’ve conformed to all the SEO best practices including semantic alt text, Structured Data, and indexable content.

= Does this work with third party Shopify apps? =
Unfortunately no. We rely on the main Shopify API which doesn’t expose third-party app data. However the functionality found in many of the Shopify apps can be reproduced by other WordPress plugins.

= How do I display my products? =
Documentation on how to display your products can be [found here](https://wpshop.io/docs/displaying).

= How does the checkout process work? =
WP Shopify does not handle any portion of the checkout process. When a customer clicks the checkout button within the cart, they’re redirected to the default Shopify checkout page to finish the process. The checkout page is opened in a new tab.

More information on the Shopify checkout process can be [found here](https://help.shopify.com/manual/sell-online/checkout-settings).

= Does this work with Shopify's Lite plan? =
Absolutely! In fact this is our recommendation if you intend to only sell on WordPress. More information on Shopify's [Lite plan](https://www.shopify.com/lite)


== Changelog ==

= 1.3.4 =

Hey everyone 👋,

This release contains two new features and many important bug fixes.

📦 **Feature:** Added support for ordering products by manual position set within custom collections
📦 **Feature (Pro only):** Local currency support
🛠 **Fixed:** Syncing error during webhooks caused by an invalid topic
🛠 **Fixed:** Bug preventing images from displaying when crop is set and width / height remain auto
🛠 **Fixed:** Issue with some hosts blocking HTTP DELETE requests
🛠 **Fixed:** Price inconsistency for some products on the collection single pages
🛠 **Fixed:** @babel/polyfill is loaded more than once on this page warning
🛠 **Fixed:** Plugin settings page sub nav links from changing container width during active state
🛠 **Fixed:** JS conflict preventing ACF fields from working on products and collections admin pages
📣 **Updated:** Better cart item spinner icon position
💻 **Dev:** Upgraded the Shopify JS Buy SDK to v2.0.1

= 1.3.3 =

🛠 **Fixed:** Pro / Free preprocessing issues
🛠 **Fixed:** Autoloading issues occurring on some WordPress installations
🛠 **Fixed:** Fixed broken height within the plugin's settings page coming from CSS within the plugin "PW-Pro-Slider-And-Carousel-For-VC"

= 1.3.2 =
Added ability to display cart icon as a fixed tab

🛠 **Fixed:** Issue causing $0 price to show when a product is out of stock. Instead will show the first non-zero price.
🛠 **Fixed:** Bug causing shortcode attribute orderby="manual" to fail
📦 **Added:** Added ability to display cart icon as a fixed tab
📦 **Added:** Added webhook 200 response during callback to prevent failing webhook notifications

= 1.3.1 =
Minor update to fix a plugin activation bug from 1.3.0

🛠 **Fixed:** Fatal error caused by WPS\Options during plugin update
📣 **Updated:** Removed rewrite_rules flush on plugin deactivation
📣 **Updated:** Refactored products shortcode arguments building
📦 **Added:** Ability to show product description when using [wps_products] via the description=“true” attribute
📦 **Added:** New WPS\Layout namespace
📦 **Added:** New filter: 'wps_products_add_to_cart_button_text'
💻 **Dev:** Added additional unit tests for WPS\Config and WPS\Layout
💻 **Dev:** Renamed constant WP_SHOPIFY_API_SLUG to WPS_SHOPIFY_API_SLUG
💻 **Dev:** Renamed constant WP_SHOPIFY_API_VERSION to WPS_SHOPIFY_API_VERSION

= 1.3.0 =
Hey everyone 👋,

This release contains a major improvement to the overall stability and reliability of the syncing process. Also included is WordPress 5.0 support, PHP 7.2 compatibility, and 13 bug fixes.

🛠 **Fixed:** Multi-site issues
🛠 **Fixed:** HTML leaking through on cart
🛠 **Fixed:** Missing products / collections on front-end
🛠 **Fixed:** Add to cart error "Sorry, it looks like this product is currently unavailable to purchase..."
🛠 **Fixed:** Syncing timeout issues caused by infinite loop when queue items were not removed via unset()
🛠 **Fixed:** Issue causing HTTP error messages not to save during the syncing process
🛠 **Fixed:** Issue causing syncing to timeout due to setting the “timeout” property within the wp_remote_post() function
🛠 **Fixed:** Issue in the “Items per request” setting that would sometimes default to 250
🛠 **Fixed:** Add to cart button would fail to show when using shortcode on third-party custom post type pages
🛠 **Fixed:** Issue causing errors when attempting to deactivate license keys remotely from the plugin settings
🛠 **Fixed:** Syncing issue causing data to overflow past 100%
🛠 **Fixed:** Poor UX / UI indicators when syncing fails
📣 **Updated:** Warning messages are now correctly styled in orange
📣 **Updated:** License key info now pulls directly from database instead of from cache
📣 **Updated:** Changed is_single() to is_singular() to prevent post type collisions
📦 **Added:** WordPress 5.0 compatibility
📦 **Added:** PHP 7.2 compatibility
📦 **Added:** WordPress version requirement to 4.7 or higher
📦 **Added:** Close icon to syncing modal
📦 **Added:** Checkout reference to the global WP Shopify object
📦 **Added:** Shopify API credentials check before syncing process begins to catch user typos
💻 **Dev:** Now loading all front-end and back-end assets in Header and not Footer (removed true from wp_enqueue_script)
💻 **Dev:** Increased first_name and last_name column lengths from varchar(255) to longtext
💻 **Dev:** Shopify SDK upgraded to version 1.11.0
💻 **Dev:** Added wp_cache_flush() to plugin activation to ensure tables are created successfully.


= 1.2.9 =
Hey everyone 👋,

The release this week contains three new features; two of which are related to product pricing. We now have the ability to show sales pricing, price ranges, and determining whether the checkout button opens in a new tab or not. Also along with these new features are four important bug fixes.

⭐️ **New Feature:** Checkout button target.
⭐️ **New Feature:** Product "compare at” pricing.
⭐️ **New Feature:** Product “range” pricing toggle.
🛠 **Fix:** Missing featured images for some products.
🛠 **Fix:** Bug during add to cart when variant value is of type integer and not string.
🛠 **Fix:** Bug causing hidden product and collection data on custom post edit page.
🛠 **Fix:** Bug in the HTTP response parser that coerced large int values into E numbers. Now using JSON_BIGINT_AS_STRING to convert them into strings instead.
🛠 **Fix:** Conflict with Divi theme.
🎁 **Update:** Shopify URL to cart image links.
🎁 **Update:** Removed ability to manually add new Products / Collection posts.
🎁 **Update:** Better vertical aligning for input fields on plugin settings page.
💻 **Dev:** Better security checks on plugin settings form fields.
💻 **Dev:** Removed on blur AJAX saving of plugin settings. All form fields now require form submission before saving.
💻 **Dev:** Plugin settings sub nav history is now persistent.


= 1.2.8 =
Hey everyone 👋,

This week’s release contains a good mixture of bug fixes and feature updates. Notable new features include: image sizing, custom checkout domains, and WordPress.com support!

🎁 **New Feature:** Added support for Wordpress.com
🎁 **New Feature:** Plugin setting for custom domain support. If enabled, WP Shopify cart will now direct users to the Checkout page using custom domain
🎁 **New Feature:** Plugin setting allowing for custom image sizing for Products, Collections, and Related Products
🔨 **Fix:** Incorrect error message when failing to select the required Shopify private app permissions
🔨 **Fix:** Missing placeholder images within cart line items
🔨 **Fix:** "Data too large" error caused by the “referring_site” column of the orders table
🔨 **Fix:** Bug that prevented products from showing when special characters existed inside the product handle
🔨 **Fix:** "Table missing" error during sync
🔨 **Fix:** Issue causing connection settings to persist even after disconnecting
🔨 **Fix:** Automatic sync bug when products are updated in Shopify and but do not already exist in WordPress
🔨 **Fix:** Bug causing currency code to disappear after selecting a product variant
📍 **Update:** Reorganized admin settings UI elements
📍 **Update:** Added new plugin settings sub nav items "Collections" and "Checkout"
📍 **Update:** Added better icons to plugin settings page
📍 **Update:** Text inside front-end notices are now centered
💻 **Dev:** Removed unused composer vendor packages
💻 **Dev:** Updated the shopify-buy SDK to version 1.9.1
💻 **Dev:** Started work on the WP Shopify REST API
💻 **Dev:** Added unit tests for new color feature
💻 **Dev:** Added unit tests for new image sizing feature

= 1.2.7 =
Hot fix for 1.2.6

= 1.2.6 =
Greetings! 👋

A long awaited feature has been released this week: the ability to easily change UI colors such as the add to cart button and cart icon. You can find these new color options within the subnav links "Products" and "Cart" inside the plugin settings.

In addition to more UI customizations coming in 1.2.7, there are also exciting features coming in a few months. One of these features include custom Gutenberg blocks which will create a really powerful way to show your products. Really excited about this. Shortcodes will remain functional, but the Gutenberg blocks will most likely replace shortcodes as the default way to show your Shopify products. Expect this to arrive in 3-5 months. Some other features include native featured images and a dedicated REST API. Stay tuned!

- **New Feature** Plugin setting allowing users to change colors of various UI elements like the add to cart button, cart icon, etc
- **Updated** Updated plugin settings UI layout
- **Updated** Updated icons on plugin settings page
- **Dev** Started work on the new WP Shopify REST API
- **Dev** Added a bunch of unit tests for color settings and database table operations
- **Dev** Refactored custom autoloader
- **Dev** Added React to admin JavaScript
- **Dev** Fixed broken Travis build

= 1.2.5 =
Hello!

This week's release brings two major feature additions: Multisite support and option cart terms.

- **New Feature** Multisite support
- **New Feature** Ability to enable a terms and conditions requirement during checkout
- **Fixed** Silent MySQL error when the “province” column of the Shop table is too long. Increased char length from 20 to 200
- **Fixed** Minor UX / layout issues with the plugin settings form inputs
- **Updated** WP Shopify radio inputs, checkboxes, and buttons to new Gutenberg CSS styles
- **Dev** Added a predicate catch for containsTrailingForwardSlash()
- **Dev** Added additional client-side integration tests
- **Dev** Added additional client-side unit tests

= 1.2.4 =
Greetings!

The major update in this release is regarding sales channel syncing. WP Shopify will now **only** sync products which are assigned to the custom sales channel you create during setup. This allows you to sync only the products you want to show on WordPress instead of your entire store. Moving forward, make sure any products you want to show on WordPress are assigned to your custom Sales Channel.

* New Feature: Ability to opt-in to Beta versions of WP Shopify. You can now turn this on within the plugin settings.
* Updated: Only products that are assigned to the WP Shopify sales channel will now sync.
* Fixed: Bug causing manually sorted products inside collections to display in the incorrect order.
* Fixed: Bug when attempting to send emails through the WP SMTP plugin
* Fixed: Bug preventing variant image from changing during selection
* Fixed: Issue where switching tabs within the plugin settings would fail with certain themes.

= 1.2.3 =
Hey everyone!

Included in this release are some important bug fixes so you'll want to update as soon as possible. Also keep an eye out during the next two weeks. I'm planning to release new plugin features surrounding the layout of products / collections.

Cheers!

* Fixed: Syncing issue caused by missing auto_increment on primary key columns
* Fixed: WooCommerce compatibility issue when using $product variable in template files.
* Fixed: Autoloading conflicts affecting WP Shopify composer dependencies.
* Updated: Removed extra border from the "add license key" message on plugins listing page.
* Updated: Sync by collection loading errors now show next to field instead of as a global admin notice.
* Updated: No longer showing migration notice if plugin hasn't updated.
* Added: Better error handling when not meeting PHP version requirements on plugin activation.
* Added: Improved caching performance.

= 1.2.2 =
Hey everyone! This release contains fixes for various compatibility issues and squashes a few minor bugs. If you're migrating from version 1.2.1, make sure to go through the table migration step found within the Misc. tab after the update.

* Fixed: Syncing issue caused by missing auto_increment on primary key columns
* Fixed: WooCommerce compatibility issue when using $product variable in template files.
* Fixed: Autoloading conflicts affecting WP Shopify composer dependencies.
* Updated: Removed extra border from the "add license key" message on plugins listing page.
* Updated: Sync by collection loading errors now show next to field instead of as a global admin notice.
* Updated: No longer showing migration notice if plugin hasn't updated.
* Added: Better error handling when not meeting PHP version requirements on plugin activation.
* Added: Improved caching performance.

= 1.2.1 =
* Fixed: Add to cart error when a product with multiple, but only one "available to purchase" variant is selected
* Fixed: A syncing error that would occasionally display a "Duplicate PRIMARY KEY" error

= 1.2.0 =
* Added: New syncing architecture
* Added: Pro version - Sync products by collections
* Added: Database table migration tool
* Added: New animations to front and backend UI elements
* Added: More consistent error handling during the syncing process
* Updated: Upgraded to the new Shopify JavaScript Buy SDK
* Updated: Reorganized the UI of the plugin settings page
* Updated: Items in cart now link to their respective WordPress pages
* Updated: Breadcrumbs template now contains all HTML instead of a function call
* Fixed: Issue preventing product image from changing after variant selection
* Fixed: Issue preventing product price from changing after variant selection
* Fixed: An issue causing preventing some users from displaying products correctly
* Dev: Implemented constants throughout codebase
* Dev: Updated NPM dependencies
* Dev: Added dependency injection throughout plugin
* Dev: Added a factory system throughout plugin
* Dev: Implemented timestamp versioning on all assets to ensure cache busting

= 1.1.5 =
* Fixed: Add to cart error preventing products with quotation marks in variant titles from working
* Fixed: "Products not found" error caused by database table creation bug

= 1.1.4 =
* Updated: Removed the "From plugin" text showing within single product headings
* Added: WordPress 4.9.6 compatibility

= 1.1.3 =
* Fixed: Lingering bug preventing new license keys from activation successfully
* Updated: Removed public slack channel invite link -- moved to Pro feature
