<?php
if (!defined('ABSPATH')) die('No direct access.');

echo '<div class="eum-advanced-settings-container exclude-users" style="display: block;">';
printf('<h3>%s</h3>', esc_html__('Exclude users', 'stops-core-theme-and-plugin-updates'));
printf('<p>%s</p>', esc_html__('Select users who will be forbidden to access the settings of this plugin.', 'stops-core-theme-and-plugin-updates'));
printf('<p>%s</p>', esc_html__('This option is useful if, for example, you would like to disable updates, but have a user account that can still update WordPress.', 'stops-core-theme-and-plugin-updates'));
printf('<p><strong>%s</strong></p>', esc_html__('Users to be forbidden', 'stops-core-theme-and-plugin-updates'));

// Code from wp-admin/includes/class-wp-ms-users-list-table
$users = array();
if (is_multisite()) {
	global $wpdb;
	$logins = implode("', '", array_map('esc_sql', get_super_admins()));
	$users = $wpdb->get_col("SELECT ID FROM $wpdb->users WHERE user_login IN ('$logins') GROUP BY user_login");
} else {
	/**
	 * Determine which role gets queried for admin users.
	 *
	 * Determine which role gets queried for admin users.
	 *
	 * @since 5.0.0
	 *
	 * @param string	$var administrator.
	 */
	$role = apply_filters('mpsum_admin_role', 'administrator');
	$users = get_users(array('role' => $role, 'orderby' => 'display_name', 'order' => 'ASC', 'fields' => 'ID'));
}
if (is_array($users) && !empty($users)) {
	echo '<input type="hidden" value="0" name="mpsum_excluded_users[]" />';
	$options = MPSUM_Updates_Manager::get_options('advanced');
	$excluded_users = isset($options['excluded_users']) ? $options['excluded_users'] : array();
	foreach ($users as $index => $user_id) {
		$user = get_userdata($user_id);
		$disabled = get_current_user_id() === absint($user_id) ? 'disabled="true"' : '';
		printf('<input type="checkbox" name="mpsum_excluded_users[]" id="mpsum_user_%1$d" value="%1$d" %3$s %4$s />&nbsp;<label for="mpsum_user_%1$d">%2$s</label><br />', esc_attr($user_id), esc_html($user->display_name), checked(true, in_array($user_id, $excluded_users), false), $disabled);
	}
}
printf('<p class="submit"><input type="submit" name="submit" id="save-excluded-users" class="button button-primary" value="%s"></p>', esc_attr__('Save users', 'stops-core-theme-and-plugin-updates'));
echo '</div>';
