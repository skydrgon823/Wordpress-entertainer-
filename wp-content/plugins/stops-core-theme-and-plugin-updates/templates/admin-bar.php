<?php
if (!defined('ABSPATH')) die('No direct access.');
echo '<div class="eum-advanced-settings-container admin-bar">';
printf('<h3>%s</h3>', esc_html__('Disable/enable the Easy Updates Manager admin bar menu', 'stops-core-theme-and-plugin-updates'));
printf('<p>%s</p>', esc_html__('The Easy Updates Manager admin bar displays at the top of your installation and allows quick actions to Easy Updates Manager options such as logs and advanced options.', 'stops-core-theme-and-plugin-updates'));
$options = MPSUM_Updates_Manager::get_options('core');
if (!isset($options['enable_admin_bar'])) {
	$options['enable_admin_bar'] = 'on';
}
echo '<div id="adminbar">';
if ('on' === $options['enable_admin_bar']) {
	printf('<p class="submit"><input type="submit" name="submit" id="disable-admin-bar" class="button button-primary" value="%s"></p>', esc_attr__('Disable', 'stops-core-theme-and-plugin-updates'));
} else {
	printf('<p class="submit"><input type="submit" name="submit" id="enable-admin-bar" class="button button-primary" value="%s"></p>', esc_attr__('Enable', 'stops-core-theme-and-plugin-updates'));
}
echo '</div>';
echo '</div>';
