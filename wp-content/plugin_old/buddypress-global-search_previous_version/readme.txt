=== BuddyPress Global Search ===
Contributors: buddyboss
Donate link: https://www.buddyboss.com/donate/
Tags: buddypress, search, social networking, activity, profiles, messaging, friends, groups, forums, notifications, settings, social, community, networks, networking
Requires at least: 3.8
Tested up to: 5.2.2
Stable tag: 1.2.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

BuddyPress Global Search allows for a global, unified search of all BuddyPress components, with a live dropdown as you type.

== Description ==

Let your members search through every BuddyPress component, along with pages and posts and custom post types of your choice, all in one unified search bar with a live dropdown of results.

Just activate the plugin, and every WordPress search input will instantly search your entire BuddyPress site, returning the results in a native tabbed interface right on your default Search Results template, automatically styled by BuddyPress to fit with your theme.

BuddyPress Global Search is built by the experienced developers at BuddyBoss who also offer premium [BuddyPress themes](https://www.buddyboss.com/themes/ "BuddyPress themes from BuddyBoss") and [plugins](https://www.buddyboss.com/plugins/ "BuddyPress plugins from BuddyBoss") to build your social network.

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'BuddyPress Global Search'
3. Activate BuddyPress Global Search from your Plugins page.

= From WordPress.org =

1. Download BuddyPress Global Search.
2. Upload the 'buddypress-global-search' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, etc...)
3. Activate BuddyPress Global Search from your Plugins page.

= Configuration =

1. Visit 'Settings > BP Global Search' and select which components should be searchable.
2. Adjust the CSS of your theme as needed, to make everything pretty.

== Frequently Asked Questions ==

= Where can I find documentation and tutorials? =

For help setting up and configuring any BuddyBoss plugin please refer to our [tutorials](https://www.buddyboss.com/tutorials/).

= Does this plugin require BuddyPress? =

Yes, it requires [BuddyPress](https://wordpress.org/plugins/buddypress/) to work.

= Will it work with my theme? =

Yes, BuddyPress Global Search should work with any theme, and will adopt your BuddyPress styling for search results. It may require some styling to make it match perfectly, depending on your theme.

= How do I code a search input into my theme? =

BuddyPress Global Search displays results in the default WordPress search inputs, meaning you can use the standard methods for [adding a search into your theme](http://codex.wordpress.org/Function_Reference/get_search_form). Usually, this will work: `<?php get_search_form( $echo ); ?>`

= Does it come with a language translation file? =

Yes, as well as translations for English, German, and Swedish

= Where can I request customizations? =

For BuddyPress customizations, submit your request at [BuddyBoss](https://www.buddyboss.com/buddypress-developers/).

== Screenshots ==

1. **Dropdown** - live dropdown showing results from all BuddyPress components
2. **Admin** - set which components should be searchable

== Changelog ==

= 1.2.1 =
* Fix - inactive members in group members were not listing in search results
* Fix - conflict with ThickBox and opening iframes on the page
* Tweak - 'bboss_global_search_query_results' filter added to query results, for developers to hook into

= 1.2.0 =
* Tested with WordPress 5.2.1

= 1.1.9 =
* New - Added Support for BuddyPress Nouveau template

= 1.1.8 =
* Fix - Subset per page items fix
* Fix - Label changes caps to lower case
* Fix - Words with Apostrophe are not searchable
* Fix - Post category search
* Fix - Remove slashes from View all link
* Fix - Escaping fix
* Fix - esc_like added
* Fix - Group search unset search_terms
* Fix - Links are not clickable on the search result page
* Fix - (send to) input field remembers name after inputting first name
* Fix - Add option to Select Post and Pages options separate
* Fix - A post title and post content aren’t coming into the Ajax search result when it contain a shortcode
* Fix - Strip shortcode in Ajax search result
* Fix - autosuggest location search result page fix

= 1.1.7 =
* New - Compatibility with Groups location search when using [Location Autocomplete for BuddyPress](https://www.buddyboss.com/product/locations-for-buddypress/)
* Fix - Optimized member profile search query

= 1.1.6 =
* New - Ability to search multiple profile fields at once (requires re-saving profiles)
* New - Better compatibility with [Location Autocomplete for BuddyPress](https://www.buddyboss.com/product/locations-for-buddypress/)

= 1.1.5 =
* Fix - Sanitize user input
* Fix - Use WordPress settings as posts_per_page
* New - AutoSuggest number of items admin setting added
* Localization - Russian translations added, credits to Airat Halitov
* Localization - French translations added, credits to Jean-Pierre Michaud

= 1.1.4 = 
* Tweak - Forum search query separation
* Fix - Show topic title in suggestions
* Fix - Search result issues

= 1.1.3 = 
* Added filter, for easier theme overriding

= 1.1.2 = 
* Added filter to search
* Made individual search queries filterable
* Fixed Message Compose Autocomplete conflict
* Fixed CSS conflicts

= 1.1.1 = 
* Fixed plugin conflict with Invite Anyone
* Fixed plugin conflict with Form Maker
* Added support for Advanced Custom Fields (ACF) plugin
* Added topic title to forum search
* Added patch for XSS Vulnerability
* CSS fixes for Ajax search results
* Fixed warning messages with latest BuddyPress
* Added French translations - credits to Jean-Pierre Michaud
* Added Brazilian Portuguese translations - credits to Filipi Zimermann

= 1.1.0 = 
* Member search by xProfile fields (username, email, etc.)
* Properly handle hidden groups
* Remove dependency on BP activity component
* Added Italian translations - credits to Massimiliano Napoli

= 1.0.9 = 
* Added support for Another Wordpress Classifieds Plugin (AWPCP)

= 1.0.8 = 
* Multisite compatibility, no longer requires network activation
* Added links to Group titles in dropdown
* Fixed page Shortcodes displaying in dropdown

= 1.0.7 = 
* Fixed unformatted "Nothing Found" dropdown in WP Toolbar search

= 1.0.6 = 
* Fixed dropdown hovering

= 1.0.5 = 
* Added Persian translation files - credits to Mahdiar Amani

= 1.0.4 = 
* Added option to disable AutoSuggest search dropdown
* Formatting adjustments
* Allow search to work if site is loaded in iframe

= 1.0.3 =
* Forum search results now displaying in dropdown
* Added Swedish translations - credits to Anton Andreasson
* Added German translations - credits to Marianne Taubl

= 1.0.2 =
* Updated readme

= 1.0.1 =
* Minor update

= 1.0.0 =
* Initial public release
* Removed WP-Updates, now updating via WP-Repo

= 0.0.3 =
* Minor bug fixes

= 0.0.2 =
* Removed empty <a> tag from autosuggest dropdown items
* Made pagination links dynamic, instead of page refresh

= 0.0.1 =
* Initial beta version
