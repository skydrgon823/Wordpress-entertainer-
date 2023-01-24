=== BuddyPress Profile Completion ===
Contributors: buddydev,sbrajesh,raviousprime
Tags: buddypress, user, profile
Requires at least: 4.5
Tested up to: 5.7.0
Requires PHP: 5.3
Stable tag: 1.0.8
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

BuddyPress Profile Completion Plugin allows site admins to force BuddyPress site members to fill all required profile fields, upload profile photo and profile cover.

== Description ==

BuddyPress Profile Completion Plugin allows site admin to force site members to fill all required profile fields, avatar and profile cover.

= Features:- =

1. Site admin can force members to fill all required BuddyPress profile fields before using other features.
2. Site admin can force members to upload an avatar(profile photo).
3. Site admin can force members to upload a cover photo for their profile.
4. Plugin allows site admin to restrict users to their own profile unless they complete their profile.
5. Plugin allows site admin to customize message for required fields, profile photo and cover.

Please see our release post for more details and understanding how this plugin works.

**Important links:-**
1. [Blog Post](https://buddydev.com/introducing-buddypress-user-profile-completion/)
2. [Support Forums](https://buddydev.com/support/forums/)
3. [More BuddyPress Plugin](https://buddydev.com/plugins/)

Looking to extend BuddyPress more, Please have a look at our
1. [Free BuddyPress Plugin](https://buddydev.com/plugins/category/free-buddypress-plugins/)
2. [Premium BuddyPress plugins](https://buddydev.com/plugins/category/buddypress-premium-plugins/)

Thank you for using this plugin.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.
1. Download the zip file and extract
1. Upload `bp-profile-completion` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu.
1. Visit Settings->BuddyPress Profile Completion and update the option.
1. Enjoy

== Frequently Asked Questions ==

= Does This plugin work without BuddyPress =
No, It needs you to have BuddyPress installed and activated. Please make sure that you have BuddyPress enabled to use this plugin.

= Where do I get Support? =
We provide support via BuddyDev forums. For a better support response and assistance, Please use [BuddyDev Forums](https://buddydev.com/support/forums/).

= I need more features, will you help? =
If you need customization or extending the plugin, please use our [WordPress/BuddyPress plugin customization Service](https://buddydev.com/buddypress-plugin-customization-service/).

== Screenshots ==

1. Admin settings screenshot-1.png
2. Error message for required profile fields screenshot-2.png
3. Error message for required profile photo screenshot-3.png
4. Error message for required profile cover screenshot-4.png

== Changelog ==

= 1.0.8 =
* Fix PMPro skip case

= 1.0.7 =
* Enable settings menu in My Account menu in admin bar

= 1.0.6 =
* Added support of BuddyPress Avatar Moderator avatar restored case.
* Fix error notification even after completion of profile(was shown on 1 more load) .

= 1.0.5 =
* Force recheck on profile update, cover upload and avatar upload.

= 1.0.4 =
* Do not redirect if PMPro is active and the user is on one of the pages from PMPro.

= 1.0.3 =
* Fix compatibility issue with field availability due to member types settings(if member type is enabled).

= 1.0.2 =
* Avoid testing on activation. Helps with redirection.

= 1.0.1 =
* Introduce new filters to the plugin for developers end.

= 1.0 =
* Initial release
