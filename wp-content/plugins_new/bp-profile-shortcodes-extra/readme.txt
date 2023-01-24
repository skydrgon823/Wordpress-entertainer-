=== BP Profile Shortcodes Extra ===
Contributors: Venutius
Tags: BuddyPress, shortcode, shortcodes, profile, social, groups, members
Tested up to: 5.2
Stable tag: 2.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate Link: paypal.me/GeorgeChaplin
Plugin URI: www.wordpress.org/plugins/bp-profile-shortcodes-extra/

Insert Group or User Profile details into a page or post, recreate many group and profile page options.

== Description ==

The ambition of this plugin is to provide all the shortcodes needed to create group and member profile dashboards using shortcodes. In addition is has wider features supporting general groups and member lists and the Activity "What's New" form.

This is an extremely powerful plugin with many shortcode options allowing you to display a range of aspects from member profiles and groups.

It includes an button to insert the shortcodes into the Classic WordPress editor and a range of Gutenberg Blocks where appropriate.

For the Block Editor support for shortcodes is either via blocks where appropriate or via the Classic block, from here you will see the BP Profile Shortcodes Extra dropdown menu to select the appropriate code. The classic editor does not render shortcodes as part of it's preview capability currently, this may change as Gutenberg develops.


**Profile Shortcodes**

* Displayname - As text or a link to the members profile page.

* Username - As text or a link to the members profile page.

* Avatar or Profile Image - As an image or a link to the members profile homepage.

* Cover Image - As an image or a link to the members profile homepage.

* Profile Header - A collection of cover image, avatar and username, with the username being a link to that members profile.

* Profile Fields - You can display information from any of the profiles Xprofile fields.

* Profile URL - shows the url, button or a link to user specific profile pages using the text of your choice.

* Profile Edit Link - A link to the users edit profile page.

* Profile Lists - Creates lists of users friends and groups and also lists of friend and group suggestions as well as general site member and group lists.

* Private Message Link - displays a link to private message this selected user.

* If no user_id is specified in the shortcode, the details of the member viewing that page will be displayed.

** Group Shortcodes**

* Avatar Image - As an image or a link to the group homepage.

* Cover Image - As an image or a link to the group homepage.

* Profile Header - A collection of cover image, avatar and group name, with the group name being a link to that group homepage.

* Group URL - shows the URL for the group or a link with the text specified by you.

* Group Member list - shows either a collection of avatars or a list of members names, both are links to each members profile.

* Group Description - displays the group description.

* Group Field - displays various fields used by eac group.

** Activity Shortcodes

* What's New - adds the activity What's New inut form to a page.

BP Profile Shortcodes Extra provides an updated range of BuddyPress shortcodes, it was build upon the BuddyPress Profile shortcodes plugin and supports all of it's features plus many more.

Most of the shortcodes accept many parameters to allow the output to be customized and tailored to meet your needs.

= The following shortcodes are available: =

**[bpps_profile_displayname]**

* Shows the display name.
* Can have a parameter of user_id=<id> to get it for a specific user.
* Accepts user_id="" for input.
* If no user_id is specified then the logged in user id will be used.
* If mention_name="" is set the users mention name ( username ) is used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* Accepts display_error="No" argument to suppress the user not found error.

**[bpps_profile_displayname user_id="1" class="member-name"]**

* Displays the profile displayname for user id 1, the css class for that element will be set to "member-name".

**[bpps_profile_email]**

* Same as above except for the email.
* The user_id parameter can be used optionally as well.
* Accepts user_id="" for input.
* If no user_id is specified then the logged in user id will be used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* If mention_name="" is set the users mention name ( username ) is used.
* Accepts error_msg="" inorder to provide an alternative user not found message.
* Accepts display_error="No" argument to suppress the user not found error.

**[bpps_profile_email user_id="bob" ]**

**[bpps_profile_username]**

* Same as above except for the BuddyPress username
* The user_id parameter can be used optionally as well.
* Accepts user_id="" for input.
* If no user_id is specified then the logged in user id will be used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* If mention_name="" is set the users mention name ( username ) is used.
* Accepts error_msg="" inorder to provide an alternative user not found message.
* Accepts display_error="No" argument to suppress the user not found error.

**[bpps_profile_private_message_link]**

* Returns a link to private message the user.
* accepts user_id="" as input.

**[bpps_profile_url]**

* Will get the url for where the user's profile is.
* The user_id parameter can be used optionally as well.
* IF you use show = no then it will show as a link.
* You can use before = or after = parameters to define text to show before or after the link or before or after the text to add customization options.
* This will accept the parameter “profile_page” to have the url for any page for the profile specified. i.e. “settings” will go to the settings page.
* text="name" will show the users displayname ("username" for WP Username ) as a link to their profile page. Otherwise any other text will be used as a as the text for the link.
* Accepts user_id="" text, button="button", before, profile_page, after, rel, target, style, class, attribute_id, title, tabindex, and attributes for inputs.
* If no user_id is specified then the logged in user id will be used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* If mention_name="" is set the users mention name ( username ) is used.
* Accepts error_msg="" inorder to provide an alternative user not found message.
* Accepts display_error="No" argument to suppress the user not found error.

**[bpps_profile_url text="Link to Profile"]**

* Returns the link to the profile, the text specified is used as the anchor text.

**[bpps_profile_url text="Link to Profile" button="button" ]**

* Returns the link to the profile as a button, the text specified is used as the anchor text.

**[bpps_profile_url text="My Forums" profile_page="forums" user_id="bob"]**

* Returns a link to Bob's My forums page.

**[bpps_profile_field field="fieldname" tab="section"]**

* Will get the field from the specified tab (profile group).
* If the tab parameter is not used then it will get it from the primary / base tab profile group.
* the user_id parameter can be used optionally as well.
* The "field" would be the title and the "section" would be the tab / section.
* The "shortcode" parameter, if set to 1 will assume the field holds a shortcode and will execute it.
* The parameter "empty" will allow you to set what will show if the field is empty. Will return "Empty Text" by default.
* Accepts user_id="", field="" empty="" shortcode="" tab="" and option="Two" for inputs.
* If mention_name="" is set the users mention name ( username ) is used.
* If no user_id is specified then the logged in user id will be used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* Accepts error_msg="" inorder to provide an alternative user not found message.
* Accepts display_error="No" argument to suppress the user not found error.

* There are now two different methods of displaying the users profile fields built into this shortcode:

** [bpps_profile_field tab="Base" field="Name"] uses the original method to display the profile field.
** [bpps_profile_field option="Two" field="Name"] uses the option two method to display the profile field.

**[bpps_profile_field field="Name" user_id="2"]**

* Displays displays the Name field from the Base profile group for user 2.

**[bpps_profile_field field="Details" user_id="bill" tab="More"]**

* Displays the Details field from the More profile group for the user Bob.

**[bpps_profile_avatar]**

* Displays the members profile image.
* Can use the "dimension" parameter to change the dimensions of the avatar.
* You can use a height or width parameter to define the height or width of it as an alternative.
* The user_id parameter can be used optionally as well.
* Accepts user_id="" id, rel, style, class, attribute_id, dimension, height, width and alt inputs.
* If no user_id is specified then the logged in user id will be used.
* If mention_name="" is set the users mention name ( username ) is used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* Accepts error_msg="" inorder to provide an alternative user not found message.
* Accepts display_error="No" argument to suppress the user not found error.

**[bpps_profile_avatar_url]**

* Will get the profile/avatar image url.
* The user_id parameter can be used optionally as well.
* If you use show = no as a parameter it works similar to bp_profile_avatar.
* Accepts user_id="" as input.
* If no user_id is specified then the logged in user id will be used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If mention_name="" is set the users mention name ( username ) is used.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* Accepts error_msg="" inorder to provide an alternative user not found message.
* Accepts display_error="No" argument to suppress the user not found error.

**[bpps_profile_avatar_link]**

* Will get the avatar image as a link to the members profile.
* The user_id parameter can be used optionally as well.
* If you use show = no as a parameter it works similar to bp_profile_gravatar.
* Accepts user_id="" id, profile_page, style, class, attribute_id, dimension, height, width and alt inputs.
* If no user_id is specified then the logged in user id will be used.
* If mention_name="" is set the users mention name ( username ) is used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* Accepts error_msg="" inorder to provide an alternative user not found message.
* Accepts display_error="No" argument to suppress the user not found error.

**[bpps_profile_cover_image]**

* Displays the members profile cover image.
* You can use a height or width parameter to define the height or width.
* The user_id parameter can be used optionally as well.
* Accepts user_id="" id, profile_page, rel, style, class, attribute_id, height, width and alt inputs.
* If no user_id is specified then the logged in user id will be used.
* If mention_name="" is set the users mention name ( username ) is used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* Accepts error_msg="" inorder to provide an alternative user not found message.
* Accepts display_error="No" argument to suppress the user not found error.

**[bpps_profile_cover_image_url]**

* Will get the profile cover image url.
* The user_id parameter can be used optionally as well.
* If you use show = no as a parameter it works similar to bp_profile_gravatar.
* Accepts user_id="" as input.
* If no user_id is specified then the logged in user id will be used.
* If mention_name="" is set the users mention name ( username ) is used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* Accepts error_msg="" inorder to provide an alternative user not found message.
* Accepts display_error="No" argument to suppress the user not found error.

**[bpps_profile_cover_image_link]**

* Will get the profile cover image as a link to the members profile.
* The user_id parameter can be used optionally as well.
* If you use show = no as a parameter it works similar to bp_profile_gravatar.
* Accepts user_id="" id, rel, style, class, attribute_id, height, width and alt inputs.
* If no user_id is specified then the logged in user id will be used.
* If mention_name="" is set the users mention name ( username ) is used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* Accepts error_msg="" inorder to provide an alternative user not found message.
* Accepts display_error="No" argument to suppress the user not found error.

**[bpps_profile_edit_url]**

* Will get the url for editing the user's buddypress profile.
* The user_id parameter can be used optionally as well.
* You can use before = or after = parameters to define text to show before or after the link or before or after the text to add customizability.
* Accepts user_id="" id, style, and alt for inputs.
* If no user_id is specified then the logged in user id will be used.
* If mention_name="" is set the users mention name ( username ) is used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* Accepts error_msg="" inorder to provide an alternative user not found message.
* Accepts display_error="No" argument to suppress the user not found error.

**[bpps_profile_edit_url text="Edit Profile"]**

* Will get the link for editing the user's buddypress profile.

**[bpps_profile_header]**

* Returns a collection of the Cover Image, Profile Image and @Username with the Username as a link to the members profile homepage.
* In the current release, it's not possible to pass css parameters into the collection elements, so instead each element has been given css id's and classes, so you can add your own custom css to style the output.
* The collection is contained within a div with an id of "bppse-header".
* The cover image is held in a div with an id of "bppse-header-cover-image-cont" the image itself has an id of "bppse-header-cover-image".
* The profile image is held in a div with an id of "bppse-header-avatar", the image iteslf has a class of "bppse-header-avatar".
* The Username has the "@" appended to it and is held in a div with an id of "bppse-profile-link", the name is within h2 tags with a class of "bppse-header-nicename", 
* Accepts user_id="" input.
* If no user_id is specified then the logged in user id will be used.
* If mention_name="" is set the users mention name ( username ) is used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.

* Example css:

`#bppse-header {
	background-color: #f5f5f5;
	height: 320px;
}
.bppse-header-avatar {
	float: left;
	position: relative;
	top: -75px;
	left: 20px;
}
#bppse-profile-link {
	position: relative;
	left: 30px;
}`

**[bpps_profile_lists]**

* Returns a list of friends or groups for the selected user.
* Supports four list formats; option="list" - linked text list, option="avatar-grid" - grid of group or friend avatars, option="avatar-grid-name" - Grid of group avatars with a name link, option="avatar-list" - List of links including Avatar and name.
* Groups List (list_type="groups") can show users groups (default), groups user created (created="true") and groups user is administrator (admin="true").
* Includes optional count of total members, friends or groups ( display_count="true" / "false" ).
* per_page="" will set the maximum items for the list to display. Default is 20.
* supports list_type="friends_suggest", list_type="members", list_type="group-lists" and list_type="groups_suggest" to display suggested friends and groups.
* friends-suggest and groups-suggest list types analyse the users friends and their groups and makes suggestions based on those.
* groups list type displays the users groups, groups they are admin of or groups they created.
* group-lists list type displays lists of the sites groups and does not support a user_id, the groups displayed can be set by selecting groups_sort="" with active, newest, random, alphabetical and popular as options.
* For site members lists and additional option is the members_sort="" with possible options of newest, active, alphabetical, popular, random and online.
* For friends and groups suggestions, Group Join and Add Friend links are provided with css classes of bpse-join-group and bpse-add-friends respectively.
* Also, if no suggestions for groups or friends are found, a link to the groups and members directory is provided.
* group_desc="false" will remove the group description from the Group list views, this is contained in a span element with a class of bpps-grp-desc.
* latest_update="false" will romove the users latest activity update and view link from the friends list items display.
* promoted_groups="2,5,9" accepts the id's of any groups you want to highlight in the group suggestions list, rand="true" will ensure a randomized list of suggestions.
* s_title="default", s_title adds a title of your choice in an h4 tag, entering default will include the users name in the title ( ie. "username's friends" etc.).
* Accepts user_id="", alt="", members_sort="", groups_sort="", class="", per_page="", option="", list_type="", width="" (default 100px), height="" (default 100px), s_title="no" admin="", group_desc="false",
  latest_update="" and created="" as inputs.
* If no user_id is specified then the logged in user id will be used.
* If displayed="Yes" then the displayed user id will be used. This will only work on BP pages.
* If displayed="Author" is used then the profile of the post author will be displayed. Only works on single posts and pages.
* If mention_name="" is set the users mention name ( username ) is used.

**[bpps_profile_lists user_id="bob" list_type="groups" created="true"]**

* Will display up to 20 groups created by bob, a count of all groups created by bob with a link to bob's My Groups page.

**[bpps_profile_lists list_type=”members” members_sort=”newest” per_page=”4″ option=”avatar-grid-name”]**

* Will display the sites four newest members in a horizontal grid.

**[ bpps_profile_lists list_type=”group-lists” groups_sort=”active”]**

* Will display the sites 20 most recently active groups in a horizontal grid.

**[bpps_profile_lists option=”avatar-grid” per_page=”3″ promoted_groups=”1,6,8″]**

* Will display groups 1,6 and 8 in an avatar grid.

**[bpps_group_url slug=""]**

* Will get the url for the group.
* You can use group_id="" to pass the group id or slug="" to pass the group slug in order to identify the required group. Only use one of these settings, these are mandatory fields.
* Group id's can be found in the Admin->Groups page
* IF you use show="no" then it will show as a link.
* You can use before = or after = parameters to define text to show before or after the link or before or after the text to add customizability.
* This will accept the parameter page="" to return a link to any group tab, ie page="forum" will return a link to the groups forum page. user page="home" to return a lin to the group homepage.
* Accepts group_id="", slug, text, before, page, after, rel, target, style, class, attribute_id, title, tabindex, and attributes for inputs.

**[bpps_group_url text="Link to Group" slug="test-group" ]**

* Returns the link to the group homepage for the group with a slug of test-group, the text specified is used as the anchor text.

**[bpps_group_url text="Group Forum" page="forum" group_id="2" ]**

* Returns a link to the forum page of group with an id of 2.

**[bpps_group_avatar]**

* Displays the groups avatar image.
* Can use the "dimension" parameter to change the dimensions of the avatar.
* You can use a height or width parameter to define the height or width of it as an alternative.
* Use of either the group_id or slug parameters is mandatory.
* Accepts group_id="", slug, id, rel, style, class, attribute_id, dimension, height, width and alt inputs.

**[bpps_group_avatar_url]**

* Will get the avatar image url.
* Use of either the group_id or slug parameters is mandatory.
* Accepts group_id and slug as inputs.

**[bpps_group_avatar_link]**

* Will get the avatar image as a link to the groups homepage.
* Use of either the group_id or slug parameters is mandatory.
* Accepts group_id="", slug id, style, class, attribute_id, dimension, height, width and alt inputs.

**[bpps_group_cover_image]**

* Displays the groups cover image.
* You can use a height or width parameter to define the height or width of the image.
* Use of either the group_id or slug parameters is mandatory.
* Accepts group_id="", slug, id, rel, style, class, attribute_id, height, width and alt inputs.

**[bpps_group_cover_image_url]**

* Will get the cover image url.
* Use of either the group_id or slug parameters is mandatory.
* If you use show = no as a parameter it works similar to bp_profile_gravatar.
* Accepts group_id="" and slug as inputs.

**[bpps_group_cover_image_link]**

* Will get the group cover image as a link to the group homepage.
* Use of either the group_id or slug parameters is mandatory.
* Accepts user_id="" id, rel, style, class, attribute_id, height, width and alt inputs.

**[bpps_group_header]**

* Returns a collection of the Cover Image, Avatar and Group Name with a link to the group homepage.
* Use of either the group_id or slug parameters is mandatory.
* In the current release, it's not possible to pass css parameters into the collection elements, so instead each element has been given css id's and classes, so you can add your own custom css to style the output.
* The collection is contained within a div with an id of "bppse-grp-header".
* The cover image is held in a div with an id of "bppse-grp-header-cover-image-cont" the image itself has an id of "bppse-grp-header-cover-image".
* The profile image is held in a div with an id of "bppse-grp-header-avatar", the image itself has a class of "bppse-grp-header-avatar".
* The Group Name is held in a div with an id of "bppse-grp-group-link", the name is within h2 tags with a class of "bppse-grp-group-header-nicename", 
* Accepts group_id="" and slug input.

* Example css:

`#bppse-grp-header {
	display: block;
	height: 350px;
	background-color: #f5f5f5;
}
#bppse-grp-header-cover-image {
	width: 100%
}
.bppse-grp-header-avatar {
	float: left;
	position: relative;
	top: -75px;
	left: 20px;
}
#bppse-grp-group-link {
	position: relative;
	left: 30px;
}`

**[bpps_group_members]**

* Displays a list of group members.
* Use of either the group_id or slug parameters is mandatory.
* Can display simple list (option="list"), a grid of group avatars ( option="avatar-grid" ) or a linked list with avatars ( option="avatar-list" ).
* per_page="20" sets the number of members to display (default 20).
* includes options count of all group members with a link to the group members page ( display_count="yes" / "no" ).
* s_title="default", s_title adds a title of your choice in an h4 tag, entering default will include the groups name in the title ( ie. "Members of Groupname" etc.).
* Accepts Group_id="", slug, alt, before, after, height, width, class, s_title, per_page and display_count as inputs.
* if no group_id or slug is specified, the shortcode will try to pick up the current group id from BuddyPress, but this means it must be used on a BuddyPress Group page.
* For the avatar-list option, content_1_field, content_2_field and content_3_field allow additional content from profile fields to be added under the members name.
* So content_1_field="Date of Birth" would display one profile field and content_2_field and content_3_field can be used for others.


**[bpps_whats_new]**

* Displays the "What's New" activity input form in the page.
* Currently no additional attributes are supported.
* Currently BP-Nouveau is not supported.

**[bpps_group_description]**

* Displays the description text of the selected group.
* Uses group_id="" to choose the group id, this needs to be numeric.
* Without a group id the shortcode will display the description for the group being viewed, only if used in a group page.

**[bpps_group_field]**

* Displays any of Name, ID, Description, Creator ID Creator Username, Creator Displayname, Slug, Status and Date Created.
* Uses group_id="" to choose the group id,
* Without a group id the shortcode will display the description for the group being viewed, only if used in a group page.
* Uses field_id="" to choose the options from - id, name, description, creator_id, creator_username, creator_displayname, date_created.
  
== Installation ==

Option 1.

1. From the Admin>>Plugins>>Add New page, search for BP Profile Shortcodes Extra.
2. When you have located the plugin, click on "Install" and then "Activate".
4. Edit your post or page to add the shortcodes

With the zip file:

Option 2

1. Upzip the plugin into it's directory/file structure
2. Upload BP Profile Shortcodes Extra's structure to the /wp-content/plugins/ directory.
3. Activate the plugin through the Admin>>Plugins menu.
4. Edit your post or page to add the shortcodes

Option 3

1. Go to Admin>>Plugins>>Add New>>Upload page.
2. Select the zip file and choose upload.
3. Activate the plugin.
4. Edit your post or page to add the shortcodes
 

== Frequently Asked Questions ==


== Changelog ==

= 2.5.2 =

* 13/05/2019

* Fix: Moved group members title to it's own div with a class of bpse-group-members-title.

= 2.5.1 =

* 07/05/2019

* New: Provided additional profile field data items in bpps_group_members.

= 2.4.1 =

* 07/05/2019

* Fix: Prevent avatar links showing 'image' instead of 'profie'.

= 2.4.0 =

* 13/04/2019

* New: Added the ability to use the mention name as a selector for the profile based shortcodes.

= 2.3.5 =

* 11/04/2019

* New: Added French translation, thanks to Bruno Verrier.

= 2.3.4 =

* 15/03/2019

* Fix: Corrected error preventing custom error message from showing.

= 2.3.3 =

* 15/03/2019

* New: Added error_msg="" to most of the profile shortcodes.

= 2.3.2 =

* 14/03/2019

* Fix: corrected error with $user_id in profile message link.

= 2.3.1 =

* 13/03/2019

* Fix: Removed hard-coding of avatar size, set default to 90 px.

= 2.3.0 =

* 16/02/2019

* New: Added group-lists list type to the profile lists shortcode.

= 2.2.0 =

* New: Added members list type to the profile lists shortcode.

= 2.1.0 =

* Fix: Corrected error with per_page options in profile lists shortcode.
* New: Introduced option="avatar-grid-name" for profile lists shortcode.

= 2.0.0 =

* 12/02/2019

* New: Added bpps_group_field shortcode.

= 1.9.3 =

* 19/01/2019

* Fix: Corrected error with profile field shortcode.

= 1.9.2 =

* 18/01/2019

* New: Added support for pages with Author option

= 1.9.1 =

* 18/01/2019

* Fix: Corrected typos in if statements, refactored author_id function.

= 1.9.0 =

* 17/01/2019

* New: Added displayed="Author" feature to profile shortcodes to allow links to be created between post authors and their BP Profiles.

= 1.8.0 =

* 08/01/2019

* New: revamped What's New shortcode to include template pages for both the Nouveau and Legacy What's New Shortcode.
* New: introduced BPPSE specific action filters for template pages.

= 1.7.6 =

* 08/01/2019

* Fix: added before and after to What's New shortcode. (Depricated).

= 1.7.5 =

* 07/01/2019

* Fix: corrected error with username check in profile_url

= 1.7.4 =

* 05/01/2019

* New: added display_error option on Profile Shortcodes to suppress the "User not Found" error message.

= 1.7.3 =

* 23/12/2018

* Fix: Added isset test for group_id to prevent non object error.
* Fix: Whats New now works with BP Nouveau.

= 1.7.2 =

* 17/12/2018

* Fix: corrected typo in readme file.
* New: Added ability to auto detect group in group members shortcode.

= 1.7.1 =

* 09/12/2018

* Fix: Updated Blocks to work with WP 5.0.

= 1.6.2 =

* 05/12/2018

* New: Added displayed user id functionality.

= 1.6.1 =

* 04/12/2018

* New - Profile Field now supports Radio button created arrays for output.

= 1.6.0 =

* 29/11/2018

* New: Added group description shortcode

= 1.4.5 =

* 16/10/2018

* Fix: removed 1.4.4 change

= 1.4.4 =

* 16/10/2018

* Fix: more refined fix for quotes filters.

= 1.4.3 =

* 15/10/2018

* Fix: remove_filter(‘the_content’, ‘wptexturize’); added to prevent quotes being converted by this filter in Visual tab.

= 1.4.2 =

* 07/06/2018

* Fix: Corrected link for profile edit url.

= 1.4.1 =

* 03/06/2018

* Fix: Added attribute support for button.

= 1.4.0 =

* 02/06/2018

* New: Added button="button" feature to [bpps_profile_url] shortcode to display a button.

= 1.0.0 = 

* 08/04/2018

= Added Profile Lists shortcode with options for Group or Friends lists.
= Added Group shortcodes - Avatar, URL, Cover-Image, Member List and Header.
= Added Profile Cover Image Shortcodes.
= Added Profile Header Shortcodes.
= Fixed Class Constructor deprecation notice.
= Fixed profile fields username is_string() condition error.
= Added error checking for instances of no valid username supplied.

== forked as BP Profile Shortcodes Extra ==


* Initial version - BuddyPress Profile Shortcodes

== Screenshots ==

1. screenshot-1.png Profile Header shortcode output
2. screenshot-2.png Profile List shortcode Group list outputs
3. screenshot-3.png Profile List shortcode friends list outputs
4. screenshot-4.png Profile List shortcode suggested groups list
5. screenshot-5.png Profile List shortcode suggested groups avatar-list

== Upgrade Notice ==

= 2.4.0 =
* Added ability to use mention name as the user selector for profile shorcodes.