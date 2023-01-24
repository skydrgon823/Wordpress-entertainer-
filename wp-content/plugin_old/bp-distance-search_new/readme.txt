=== BP Distance Search ===
Contributors: dontdream
Tags: buddypress, members, location, distance, filter, search
Requires at least: 4.8
Tested up to: 5.5
Stable tag: 1.2

Adds a Google Place Autocomplete profile field type for BuddyPress, and enables search by distance with BP Profile Search.

== Description ==

BP Distance Search adds a new <em>Google Place Autocomplete</em> field type to your BuddyPress extended profiles, and registers this field type with <a href="https://wordpress.org/plugins/bp-profile-search/">BP Profile Search</a>.

After installing this plugin:

<ol>
<li>Admin can create new profile field(s) with type <em>Google Place Autocomplete</em></li>
<li>Members can fill in the new profile field(s) on their <em>Profile Edit</em> page</li>
<li>Admin can add the new profile field(s) to a BP Profile Search form, selecting either the <em>distance</em> search mode or one of the usual text search modes <em>contains</em>, <em>is</em>, or <em>is like</em></li>
<li>Visitors can use the search form with the new profile field(s)</li>
</ol>

See the screenshots below depicting the above steps.

== Installation ==

* Get a [Google API key](https://developers.google.com/maps/documentation/javascript/get-api-key) (can’t do that for you, sorry)

Get a *Maps JavaScript API* key, and enable the *Geocoding API* and the *Places API*.

* Follow the standard plugin installation procedure, see [Managing Plugins](https://wordpress.org/support/article/managing-plugins/#installing-plugins)

This plugin doesn't require any configuration, you'll need your API key when you create your first *Google Place Autocomplete* profile field.

== Screenshots ==

1. Admin creates a new profile field with type <em>Google Place Autocomplete</em>
2. Members fill in the new profile field on their <em>Profile Edit</em> page
3. Admin adds the new profile field to a BP Profile Search form
4. Visitors use the search form with the new profile field

== Changelog ==

= 1.2 =
* Added ability to sort the search results by distance
* Added ability to show the distance in the member details area
* Updated for BP Profile Search 5.1
= 1.1 =
* Added location pin to get the current location
= 1.0.3 =
* Fixed bug in back-end profile editing
= 1.0.2 =
* Fixed conflict with the BuddyPress *Date Selector* field type
= 1.0.1 =
* Added call to *load_plugin_textdomain*
= 1.0 =
* Initial version released to the WordPress Plugin Directory
