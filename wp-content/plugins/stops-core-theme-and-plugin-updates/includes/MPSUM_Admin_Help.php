<?php
/**
 * Help Screen for Easy Updates Manager
 * Initializes and outputs the help screen for the plugin.
 *
 * @package WordPress
 * @since 5.0.0
 */
class MPSUM_Admin_Help {

	/**
	 * Class constructor.
	 *
	 * Initialize the class
	 *
	 * @since 5.0.0
	 * @access public
	 */
	public function __construct() {
		$screen = get_current_screen();

		$screen  = get_current_screen();
		$content1 = '<p>';
		$content1_strings = array(
			'website' => esc_html__('Our website', 'stops-core-theme-and-plugin-updates'),
			'donate' => esc_html__('Donate', 'stops-core-theme-and-plugin-updates'),
			'support' => esc_html__('Support on WordPress', 'stops-core-theme-and-plugin-updates'),
			'premium_support' => esc_html__('Premium support', 'stops-core-theme-and-plugin-updates'),
			'official' => esc_html__('Documentation', 'stops-core-theme-and-plugin-updates'),
		);
		$content1 = <<<CONTENT1
	<p>
	<a href="https://easyupdatesmanager.com" class="button">{$content1_strings['website']}</a>
	    <a href="http://wordpress.org/support/plugin/stops-core-theme-and-plugin-updates" class="button">{$content1_strings['support']}</a>
	    <a href="https://easyupdatesmanager.com/support/" class="button">{$content1_strings['premium_support']}</a>
	     <a href="https://easyupdatesmanager.com/documentation/" class="button">{$content1_strings['official']}</a>
    </p>
CONTENT1;
		$content1 .= esc_html__('This is the Easy Updates Manager settings help tab. Here you will find helpful information on what Easy Updates Manager does and how to use it.', 'stops-core-theme-and-plugin-updates');
		$content1 .= '</p>';
		$content1 .= sprintf('<div><p><strong>%s - </strong>%s</p></div>', esc_html__('Please note!', 'stops-core-theme-and-plugin-updates'), esc_html__('If either your WordPress core, theme, or plugins get too out of date, you may run into compatibility problems. Check the capability tab for more information.', 'stops-core-theme-and-plugin-updates'));

		$content2 = sprintf('<div><p><a href="https://easyupdatesmanager.com/documentation/">%s</a></p></div>', esc_html__('Check out our documentation for updated documentation and videos.', 'stops-core-theme-and-plugin-updates'));

		$content4_strings = array(
			'intro' => esc_html__('You will see multiple tabs where you can configure the update options.', 'stops-core-theme-and-plugin-updates'),
			'general' => sprintf('<strong>%s</strong> - %s', esc_html__('General', 'stops-core-theme-and-plugin-updates'), esc_html__('Use this screen to finely tune which updates and automatic updates you would like to see.', 'stops-core-theme-and-plugin-updates')),
			'plugins' => sprintf('<strong>%s</strong> - %s', esc_html__('Plugins', 'stops-core-theme-and-plugin-updates'), esc_html__('If plugin updates are enabled and/or automatic updates for plugins are enabled, you can configure which plugins will receive updates and/or automatic updates.', 'stops-core-theme-and-plugin-updates')),
			'themes' => sprintf('<strong>%s</strong> - %s', esc_html__('Themes', 'stops-core-theme-and-plugin-updates'), esc_html__('If theme updates are enabled and/or automatic updates for themes are enabled, you can configure which themes will receive updates and/or automatic updates.', 'stops-core-theme-and-plugin-updates')),
			'logs' => sprintf('<strong>%s</strong> - %s', esc_html__('Logs', 'stops-core-theme-and-plugin-updates'), esc_html__('Logs all plugin, theme, and core updates. This tab is visible by default.', 'stops-core-theme-and-plugin-updates')),
			'advanced' => sprintf('<strong>%s</strong> - %s', esc_html__('Advanced', 'stops-core-theme-and-plugin-updates'), esc_html__('Reset all options or allow certain users to see all updates regardless of what settings you have set.', 'stops-core-theme-and-plugin-updates')),

		);
		$content4 = <<<CONTENT4
<p>
{$content4_strings['intro']}
<br>
{$content4_strings['general']}
<br>
{$content4_strings['plugins']}
<br>
{$content4_strings['themes']}
<br>
{$content4_strings['logs']}
<br>
{$content4_strings['advanced']}
<br>
<br>
</p>

CONTENT4;

		$content6 = '<p>';
		$content6 .= esc_html__('WordPress encourages you to update your plugins, themes, and core to make sure that there are no bugs. Even though you most likely want to disable all the updates and never think about updating again, you should still consider updating every once in a while to avoid major bugs and errors on your WordPress website.', 'stops-core-theme-and-plugin-updates');
		$content6 .= sprintf('<h4>%s</h4>', esc_html__('This plugin is tested with the most recent versions of WordPress to ensure that there are no major issues.', 'stops-core-theme-and-plugin-updates'));
		$content6 .= '</p>';

		$screen->add_help_tab(array(
			'id'      => 'help_tab_content_1',
			'title'   => __('Overview',  'stops-core-theme-and-plugin-updates'),
			'content' => $content1,
		));

		$screen->add_help_tab(array(
			'id' => 'help_tab_content_4',
			'title' => __('Navigation',  'stops-core-theme-and-plugin-updates'),
			'content' => wpautop($content4),
		));

		$screen->add_help_tab(array(
			'id' => 'help_tab_content_2',
			'title' => __('Documentation',  'stops-core-theme-and-plugin-updates'),
			'content' => wpautop($content2),
		));

		$screen->add_help_tab(array(
			'id' => 'help_tab_content_6',
			'title' => __('Capability',  'stops-core-theme-and-plugin-updates'),
			'content' => wpautop($content6),
		));

	} //end constructor
}
