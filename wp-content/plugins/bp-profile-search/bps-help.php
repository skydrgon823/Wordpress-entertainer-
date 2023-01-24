<?php

function bps_help ()
{
    $screen = get_current_screen ();

	$title_00 = __('Display a Form', 'bp-profile-search');
	$content_00 = '
<p>'. __('After you build your search form, you can display it:', 'bp-profile-search'). '</p>
<ul>
<li>'. sprintf (__('In its target directory, using the option %s', 'bp-profile-search'), '<em>'. __('Add Form to Directory', 'bp-profile-search'). '</em>'). '</li>
<li>'. sprintf (__('In a sidebar or widget area, using the widget %s', 'bp-profile-search'), '<em>'. __('Profile Search', 'bp-profile-search'). '</em>'). '</li>
<li>'. sprintf (__('In a post or page, using the shortcode: %s (*)', 'bp-profile-search'), "<br><strong>[bps_form id=form_id]</strong>"). '</li>
</ul>
<p>'. sprintf (__('(*) Replace %s with the actual ID of your form.', 'bp-profile-search'), "<em>form_id</em>"). '</p>';

	$title_01 = __('Form Fields', 'bp-profile-search');
	$content_01 = '
<p>'. __('Select the profile fields to show in your search form.', 'bp-profile-search'). '</p>
<ul>
<li>'. __('Customize the field label and description, or leave them empty to use the default', 'bp-profile-search'). '</li>
<li>'. __('Select the field search mode from the <em>Search Mode</em> drop-down list', 'bp-profile-search'). '</li>
<li>'. __('To reorder the fields in the form, drag them up or down by the handle on the left', 'bp-profile-search'). '</li>
<li>'. __('To remove a field from the form, click <em>Remove</em> on the right', 'bp-profile-search'). '</li>
<li>'. __('To leave a field description blank, enter a single dash (-) character', 'bp-profile-search'). '</li>
</ul>';

	$title_02 = __('Form Template', 'bp-profile-search');
	$content_02 = '
<p>'. __('Select how to display your search form.', 'bp-profile-search'). '</p>
<ul>
<li>'. __('Select the form template', 'bp-profile-search'). '</li>
<li>'. __('Specify the template options, if any', 'bp-profile-search'). '</li>
</ul>';

	$title_03 = __('Form Settings', 'bp-profile-search');
	$content_03 = '
<p>
<strong>'. __('Target Directory', 'bp-profile-search'). '</strong><br>'.
__('Select the member directory that will be filtered using this search form. You can choose:', 'bp-profile-search'). '
</p>
<ul>
<li>'. __('The BuddyPress Members directory', 'bp-profile-search'). '</li>
<li>'. __('One of the member directories built with this plugin', 'bp-profile-search'). '</li>
</ul>
<p>'. sprintf (__('You can create a member directory using the shortcode %1$s. To learn more, see the %2$s page.', 'bp-profile-search'), '<strong>[bps_directory]</strong>', '<a href="https://dontdream.it/bp-profile-search/custom-directories/" target="_blank">Custom Directories</a>'). '</p>
<p>
<strong>'. __('Add Form to Directory', 'bp-profile-search'). '</strong><br>'.
__('Choose whether to display this form in its target directory.', 'bp-profile-search'). '
</p>
<p>
<strong>'. __('Form Method', 'bp-profile-search'). '</strong><br>'.
__('Select your form’s <em>method</em> attribute.', 'bp-profile-search'). '
</p>
<ul>
<li>'. __('POST: the form data are not visible in the URL and it’s not possible to bookmark the search results', 'bp-profile-search'). '</li>
<li>'. __('GET: the form data are visible in the URL and it’s possible to bookmark the search results', 'bp-profile-search'). '</li>
</ul>';

	$title_04 = __('Persistent Search', 'bp-profile-search');
	$content_04 = '
<p>'. __('Enable or disable the <em>persistent search</em> feature.', 'bp-profile-search'). '</p>
<ul>
<li>'. __('If enabled, a search is cleared when the user hits the <em>Clear</em> button', 'bp-profile-search'). '</li>
<li>'. __('If disabled, a search is cleared when the user hits the <em>Clear</em> button, or navigates away from the target directory', 'bp-profile-search'). '</li>
</ul>
<p>'. __('This selection applies to all your forms at once.', 'bp-profile-search'). '</p>';

	$title_05 = __('Create a form', 'bp-profile-search');
	$content_05 = '
<p>'. sprintf (__('To create a form, use the button %s.', 'bp-profile-search'), '<em>'. __('Add New'). '</em>'). '</p>
<p>'. __('You can then add the form fields, specify the form settings and select the form template.', 'bp-profile-search'). '</p>';

	$sidebar = '
<p><strong>'. __('For more information:', 'bp-profile-search'). '</strong></p>
<p><a href="https://dontdream.it/bp-profile-search/" target="_blank">'. __('Documentation', 'bp-profile-search'). '</a></p>
<p><a href="https://dontdream.it/bp-profile-search/search-modes/" target="_blank">'. __('Search Modes', 'bp-profile-search'). '</a></p>
<p><a href="https://dontdream.it/bp-profile-search/troubleshooting/" target="_blank">'. __('Troubleshooting', 'bp-profile-search'). '</a></p>
<p><a href="https://dontdream.it/bp-profile-search/incompatible-plugins/" target="_blank">'. __('Incompatible plugins', 'bp-profile-search'). '</a></p>
<p><a href="https://dontdream.it/support/forum/bp-profile-search-forum/" target="_blank">'. __('Support Forum', 'bp-profile-search'). '</a></p>
<br><br>';

	$screen->add_help_tab (array ('id' => 'bps_05', 'title' => $title_05, 'content' => $content_05));
	$screen->add_help_tab (array ('id' => 'bps_01', 'title' => $title_01, 'content' => $content_01));
	$screen->add_help_tab (array ('id' => 'bps_03', 'title' => $title_03, 'content' => $content_03));
	$screen->add_help_tab (array ('id' => 'bps_02', 'title' => $title_02, 'content' => $content_02));
	$screen->add_help_tab (array ('id' => 'bps_04', 'title' => $title_04, 'content' => $content_04));
	$screen->add_help_tab (array ('id' => 'bps_00', 'title' => $title_00, 'content' => $content_00));

	$screen->set_help_sidebar ($sidebar);

	return true;
}
