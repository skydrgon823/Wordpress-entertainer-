=== BuddyPress User Info Widget ===
Contributors: buddydev, sbrajesh
Tags: buddypress, buddypress profile, xprofile, profile widget, buddypress profile widget
Requires at least: 3.0
Tested up to: 4.9.8
Stable tag: 1.2.9

BuddyPress User Info Widget allows easy listing of user profile info in the widget area.

== Description ==
BuddyPress User Info Widget( earlier BuddyPress Profile Widget for Blogs ) is a plugin for BuddyPress based site, which enables blog admins to show their profile on their blogs.

**Features** 

 * Show profile for users of current blog by their role(e.g list of admins, list of authors)
 * Show Avatar/Hide avatar
 * Show All xprofile fields/ only selected xprofile fields
 Fo more such plugins, please visit [BuddyDev](https://buddydev.com/)

== Installation ==

The plugin is simple to install:

1. Download `bp-profile-for-blogs.zip`
1. Unzip
1. Upload `bp-profile-for-blogs` directory to your `/wp-content/plugins` directory
1. Go to the plugin management page and enable the plugin, Activate the plugin sitewide

Otherwise, Use the Plugin browser, upload it and activate, you are done.
Please note, The plugin must be activated sitewide.

== Frequently Asked Questions ==
= How to Use =
Add the widget "BuddyPress Profile Info" and save the settings in the widget. That's it.

= I want to show authors profile instead of admins, Is it possible ? =
Yes. With version 1.2 You can list  members of any role, please go to the widget section and select a role, by default, It will list admins

= Can I only show the details of logged in member ? =
Yes. Please select "Logged In User" from the role list in the widget.

== Changelog ==
= Version 1.2.9 =
* Do not hide member type associated profile fields in widget settings.

= Version 1.2.8 =
* Allow using tags in widget title ( %username%, %first_name%, %display_name%).

= Version 1.2.7 =
* Added option to show the profile data for displayed user only.

= Version 1.2.6 =
* Fix multi valued fields showing as array.

= Version 1.2.5 =
* Allow displaying the details of logged in user
* Cleanup

= Version 1.2.4 =
* Code cleanup, fixes all notices

= Version 1.2.3 =
* Updated for Better performance

= Version 1.2.3 =
* Updated for Better performance

= Version 1.2.2 =
* Added support for WordPress 3.2 and BuddyPress 1.5+

= Version 1.2.1 =
* Added support for WordPress Multisite 3.0.1
* added support for listing the profiles of users by role
* Code level support for showing profile for any type of member by ther roles. 
 = Version 1.2 =
* Fixed a bug with finding admin user by level, now it works on role
* BuddyPress awareness is brought back, so should not cause issue when BuddyPress is disabled

 = Version 1.1 =
* Porfile Fields linking is removed
* Fixed a bug when admin users of a blog were not found

 = Version 1.0 released =

== Other ==

For more info visit us at [BuddyDev.com](http://buddydev.com/ "The best place for all buddypress based plugins,themes tutorials")