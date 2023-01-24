=== BP Profile Search ===
Contributors: dontdream
Tags: buddypress, directory, members, users, search, filter
Requires at least: 4.9
Tested up to: 5.7
Stable tag: 5.4

Member search and member directories for BuddyPress.

== Description ==

BP Profile Search is a member search and member directories plugin for BuddyPress. It provides:

<ul>
	<li>A form builder to build the member search forms</li>

	<li>The [bps_directory] shortcode to customize the BuddyPress Members directory, or to build additional member directories</li>
</ul>

Each search form has a <em>target directory</em>. When you run a search, the form's target directory is filtered according to your search.

= Build a search form =

With the form builder you can:

<ul>
	<li>Add, edit, reorder, and remove the search fields</li>

	<li>Use as search fields the BuddyPress profile fields, the <em>users</em> and <em>usermeta</em> data (including roles), the BuddyPress user groups, and the user taxonomies (including BuddyPress member types)</li>

	<li>Use <em>search by distance</em> fields when you install the free companion plugin <a href="https://wordpress.org/plugins/bp-distance-search/">BP Distance Search</a></li>

	<li>Select, for each search field, one of the available search modes</li>

	<li>Select the BuddyPress Members directory, or one of the member directories built with this plugin, as the target directory</li>

	<li>Select the form template to display your form</li>

	<li>If in doubt, use the <em>Help</em> tab above the screen title</li>
</ul>

The form template works just like any other BuddyPress template. To override a form template, copy it to the <em>buddypress/members</em> directory in your theme's root, then edit the new copy according to your needs.

= Display a search form =

After you build your search form, you can display it:

<ul>
	<li>In its target directory, using the option <em>Add Form to Directory</em> in the form settings</li>

	<li>In a sidebar or widget area, using the widget <em>Profile Search</em></li>

	<li>In a post or page, using the shortcode [bps_form]</li>
</ul>

= Run a search =

On the front-end, when you hit the <em>Search</em> button in a form, BP Profile Search shows the form's target directory filtered according to your search. Both the <em>All Members</em> tab and the <em>My Friends</em> tab are filtered.

Additionally, the plugin:

<ul>
	<li>Displays an <em>active filters</em> section containing the active search filters and a <em>Clear</em> button to clear them</li>

	<li>Displays for each member a <em>member details</em> section containing the values of the searched fields</li>

	<li>Adds to the <em>Order By</em> drop-down the options to sort the directory by the searched fields</li>
</ul>

The <em>active filters</em> section and the <em>member details</em> section are displayed by two dedicated templates, that can be overridden just like any other BuddyPress template.

= Build a member directory =

With the [bps_directory] shortcode you can:

<ul>
	<li>Customize the BuddyPress Members directory, or build additional member directories</li>

	<li>Add hidden filters to a directory</li>

	<li>Add more sort options to a directory</li>

	<li>Show additional member information in each <em>member details</em> section, e.g. the value of profile fields</li>

	<li>Use a different Members directory template for each directory</li>
</ul>

You can enter the shortcode in an empty page to build a new member directory, or you can enter it in the BuddyPress Members page to customize the BuddyPress Members directory.

= Additional documentation =

<ul>
	<li><a href="https://www.dontdream.it/bp-profile-search/form-builder/">Form Builder</a></li>

	<li><a href="https://dontdream.it/bp-profile-search/search-modes/">Search Modes</a></li>

	<li><a href="https://dontdream.it/bp-profile-search/custom-directories/">Custom Directories</a></li>

	<li><a href="https://dontdream.it/bp-profile-search/form-templates/">Form Templates</a></li>
</ul>

In the screenshots below, the <em>City</em> field is provided by the free companion plugin <a href="https://wordpress.org/plugins/bp-distance-search/">BP Distance Search</a>.

== Installation ==

See the standard installation procedure, in [Managing Plugins](https://wordpress.org/support/article/managing-plugins/#installing-plugins).

== Screenshots ==

1. The Profile Search Forms admin page
2. The Edit Form admin page
3. Configuration of a Profile Search widget
4. The Members directory page with a Profile Search widget
5. The Members directory page with search results

== Changelog ==

= 5.4 =
* Breaking change: custom form templates must enqueue the scripts they need
* Breaking change: new filters template
* See [BP Profile Search 5.4](https://dontdream.it/bp-profile-search-5-4/) for details
= 5.3.5 =
* Fixed: member-type specific fields are no longer incorrectly hidden
= 5.3.4 =
* Added: support for the BuddyBoss *Gender* profile field type
= 5.3.3 =
* Fixed: removed incorrect call to get_the_content()
* Breaking change: custom form templates must enqueue the scripts they need
* See [BP Profile Search 5.3.3](https://dontdream.it/bp-profile-search-5-3-3/) for details
= 5.3.2 =
* Fixed: fields duplication in search form
= 5.3.1 =
* Fixed: do not search if search form has errors
* Fixed: autocomplete in location search field
= 5.3 =
* Removed support for old form templates
* Added required fields, default field values, and custom field validation
* Fixed pagination of search results in member directories not using AJAX
* Fixed conflict with the group members template, introduced in 5.2
* See [BP Profile Search 5.3](https://dontdream.it/bp-profile-search-5-3/) for details
= 5.2.4 =
* Last version to support old form templates, see [New form template structure](https://dontdream.it/new-form-template-structure/)
* Fixed bug with quotes in *users* and *usermeta* searches
= 5.2.3 =
* Fixed bug preventing adding new search fields with Firefox
* Minor changes to the form builder UI
= 5.2.2 =
* Improved the form builder UI
* Added workaround to prevent conflict with *WC Vendors Marketplace*
= 5.2.1 =
* Fixed conflict with the BP Legacy group members template, introduced in 5.2
= 5.2 =
* Added search by group, to find members belonging to the selected group(s)
= 5.1 =
* Added the bps-details template, to customize the member details section
* Removed the bps-field-value template, replaced by bps-details
= 5.0.5 =
* Fixed bug with *range* and *age_range* in old templates, introduced in 5.0.2
= 5.0.4 =
* Made the AND and OR keywords translatable in searches
* Fixed bug with the *bps_match_all* hook
* Made the multiple select size adjustable in search forms
* Minor adjustment to the bps-form-default template
* See [BP Profile Search 5.0.4](https://dontdream.it/bp-profile-search-5-0-4/) for details
= 5.0.3 =
* Fixed compatibility with *GEO my WordPress*
* Fixed bug in the [bps_directory] shortcode with values containing ampersand (&)
= 5.0.2 =
* Added display of profile fields in the [bps_directory] shortcode
* Added display of selected profile fields in search results
* Added option to sort search results by selected profile fields
* Fixed a potential privacy problem involving unauthorized searches
* Improved compatibility with *GEO my WordPress*
* See [BP Profile Search 5.0.2](https://dontdream.it/bp-profile-search-5-0-2/) for details
= 5.0.1 =
* Fixed critical bug in the [bps_directory] shortcode - wrong links to user profiles
= 5.0 =
* Fixed the [bps_directory] shortcode to work with BP Legacy and BP Nouveau
* Added search modes *is* and *range* for date fields
* Improved compatibility with *BP xProfile Location*
* Added notice for users of outdated form templates
= 4.9.8 =
* Added search by distance with [BP Distance Search](https://wordpress.org/plugins/bp-distance-search/)
= 4.9.7 =
* Fixed workaround to prevent a theme conflict
= 4.9.6 =
* Minor adjustment to the bps-form-default template
* Workaround to prevent a plugin conflict
= 4.9.5 =
* Restored the bps-form-nouveau template, following user requests
= 4.9.4 =
* Fixed CSS bug with bps-form-default
* Fixed bug with the Member Type search field
= 4.9.3 =
* Added support for AND and OR expressions in search fields
* Retired the bps-form-nouveau template, replaced by bps-form-default
= 4.9.2 =
* Fixed bug introduced in 4.9.1 affecting older form templates
* Added admin error notice when BuddyPress is not active
= 4.9.1 =
* Fixed PHP Warnings in form templates
* Fixed bug in WPML support
= 4.9 =
* Introduced a new default form template, to gradually replace older templates
* See [Form Templates](https://dontdream.it/bp-profile-search/form-templates/) for details
= 4.8.6 =
* Added search by user taxonomies (including BP member types)
* Added column in the *Search Forms* page showing the current template and its location
= 4.8.5 =
* Added choice of jQuery UI theme for the bps-form-nouveau template
= 4.8.4 =
* Added ability to search by data in the *usermeta* table
* See [BP Profile Search 4.8.4](https://dontdream.it/bp-profile-search-4-8-4/) for details
= 4.8.3 =
* Added a new form template compatible with BP Nouveau
* Revised the plugin's contextual help
* The shortcode [bps_directory] still doesn't work with BP Nouveau
= 4.8.2 =
* Fixed issue when calling a template inside a template
= 4.8.1 =
* Added ability to search by data in the *users* table
* Added option to enable or disable *persistent search*
* Removed the old interface for custom field types
* See [BP Profile Search 4.8.1](https://dontdream.it/bp-profile-search-4-8-1/) for details
= 4.8 =
* Introduced *hidden filters* for custom directories, see [Custom Directories](https://dontdream.it/bp-profile-search/custom-directories/)
* Developers: please switch to the [new interface for custom field types](https://dontdream.it/bp-profile-search/custom-profile-field-types/)
= 4.7.9 =
* Introduced new search mode *is one of*
= 4.7.8 =
* Improved display of the active filters
* For developers: [new interface for custom field types](https://dontdream.it/bp-profile-search/custom-profile-field-types/)
= 4.7.7 =
* No longer clears the search when leaving the members directory
* Removed PHP Warning during first activation
* Added HTML class to forms in form templates
* Added configuration file for WPML
= 4.7.6 =
* Fixed bug with custom field types introduced in 4.7.5
= 4.7.5 =
* Moved the search mode selection to the field level
= 4.7.4 =
* Fixed bugs in WPML support
= 4.7.3 =
* Added filter to change the cookie name
= 4.7.2 =
* Fixed bug with custom field types introduced in 4.7.1
= 4.7.1 =
* Modified the *Form Fields* settings UI to enable further development
= 4.7 =
* Added ability to sort a Members directory using a profile field
* See [BP Profile Search 4.7](https://dontdream.it/bp-profile-search-4-7/) for details
= 4.6.3 =
* Added support for WPGlobus
* Updated templates for the *Twenty Seventeen* theme
* Added the plugin icon - by Alexei Ryazancev
= 4.6.2 =
* Added ability to search for member types
* Added the filters *bps_clear_search* and *bps_match_all*
= 4.6.1 =
* Added support for member-type directories
* Updated templates to allow member-type directories as results pages
= 4.6 =
* Removed insecure code - thanks to Robert Rowley at pagely.com
= 4.5.3 =
* Fixed hardcoded strings in a form template
= 4.5.2 =
* Fixed bug in *Age Range* display introduced in version 4.5
* Fixed bug in label display introduced in version 4.5.1
= 4.5.1 =
* Fixed order of search conditions in directory pages
* Improved support for WPML
= 4.5 =
* Added generic search field to search every profile field
= 4.4.4 =
* Added basic support for WPML
= 4.4.3 =
* Fixed the *Form Action (Results Directory)* drop down list
= 4.4.2 =
* Fixed bug with member-type specific fields
= 4.4.1 =
* Fixed bug in wildcard searching
= 4.4 =
* Updated to use WP language packs
= 4.3.1 =
* Fixed rendering of hidden fields in form templates
= 4.3 =
* Updated templates to better support custom field types
* Updated [documentation](https://dontdream.it/bp-profile-search/custom-profile-field-types/) for custom field types authors
= 4.2.4 =
* Updated for WordPress 4.3
= 4.2.3 =
* Restricted capability to create forms to admin only
* Added the filters *bps_form_order* and *bps_form_caps*
* Changed the name of a few functions
= 4.2.2 =
* Updated templates to work in member-type directories
= 4.2.1 =
* Fixed bug when searching in a *multiselectbox* profile field type
= 4.2 =
* Added ability to use form templates
= 4.1.1 =
* Fixed bug with field labels containing quotes
= 4.1 =
* Added ability to create custom Members directory pages
* Added ability to use them as custom search results pages
= 4.0.3 =
* Fixed PHP fatal error when BP component *Extended Profiles* was not active
* Replaced deprecated like_escape()
= 4.0.2 =
* Fixed PHP warning when using the *SAME* search mode
= 4.0.1 =
* Fixed bug with field options not respecting sort order
* Fixed bug with search strings containing ampersand (&)
= 4.0 =
* Added support for multiple forms
* Added ability to export/import forms
* Added selection of the form *method* attribute
* Updated Italian and Russian translations
= 3.6.6 =
* Added French translation
= 3.6.5 =
* Fixed bug when searching in a *number* profile field type
= 3.6.4 =
* Added support for custom profile field types, see [documentation](https://dontdream.it/bp-profile-search/custom-profile-field-types/)
= 3.6.3 =
* Reduced the number of database queries
= 3.6.2 =
* Updated for the *number* profile field type (BP 2.0)
= 3.6.1 =
* Fixed PHP warnings after upgrade
= 3.6 =
* Redesigned settings page, added Help section
* Added customization of field label and description
* Added *Value Range Search* for multiple numeric fields
* Added *Age Range Search* for multiple date fields
* Added reordering of form fields
* Updated Italian translation
* Updated Russian translation
= 3.5.6 =
* Replaced deprecated $wpdb->escape() with esc_sql()
* Added *Clear* link to reset the search filters
= 3.5.5 =
* Fixed the CSS for widget forms and shortcode generated forms
= 3.5.4 =
* Added Serbo-Croatian translation
= 3.5.3 =
* Added Spanish, Russian and Italian translations
= 3.5.2 =
* Fixed a pagination bug introduced in 3.5.1
= 3.5.1 =
* Fixed a few conflicts with other plugins and themes
= 3.5 09/04/2013 =
* Added the *Add to Directory* option
* Fixed a couple of bugs with multisite installations
* Ready for localization
* Requires BuddyPress 1.8 or higher
= 3.4.1 07/03/2013 =
* Added *selectbox* profile fields as candidates for the *Value Range Search*
= 3.4 06/30/2013 =
* Added the *Value Range Search* option - thanks to Florian Shieﬂl
= 3.3 05/12/2013 =
* Added pagination for search results
* Added searching in the *My Friends* tab of the Members directory
* Removed the *Filtered Members List* option in the *Advanced Options* tab
* Requires BuddyPress 1.7 or higher
= 3.2 11/27/2012 =
* Updated for BuddyPress 1.6
* Requires BuddyPress 1.6 or higher
= 3.1 06/10/2012 =
* Fixed the search when field options contain trailing spaces
* Fixed the search when field type is changed after creation
= 3.0 04/19/2012 =
* Added the *Profile Search* widget
* Added the [bp_profile_search_form] shortcode
= 2.8 03/12/2012 =
* Fixed the *Age Range Search*
* Fixed the search form for required fields
* Removed field descriptions from the search form
* Requires BuddyPress 1.5 or higher
= 2.7 09/23/2011 =
* Updated for BuddyPress 1.5 multisite
* Requires BuddyPress 1.2.8 or higher
= 2.6 09/22/2011 =
* Updated for BuddyPress 1.5
= 2.5 03/14/2011 =
* Updated for BuddyPress 1.2.8 multisite installations
= 2.4 02/14/2011 =
* Added the *Filtered Members List* option in the *Advanced Options* tab
= 2.3 01/24/2011 =
* Added the choice between *Partial match* and *Exact match* for text searches
= 2.2 01/03/2011 =
* Added the *Age Range Search* option
= 2.1 12/13/2010 =
* Added the *Toggle Form* option to show/hide the search form
* Fixed a bug where no results were found in some installations
= 2.0 11/14/2010 =
* Added support for *multiselectbox* and *checkbox* profile fields
* Added support for % and _ wildcard characters in text searches
= 1.0 06/29/2010 =
* First version released to the WordPress Plugin Directory

== Upgrade Notice ==

= 4.6 =
Security release, please update immediately!
