<?php

if (!defined('ABSPATH')) die('No direct access allowed');

/**
 * All commands that are intended to be available for calling from any sort of control interface (e.g. wp-admin, UpdraftCentral) go in here. All public methods should either return the data to be returned, or a WP_Error with associated error code, message and error data.
 */
class MPSUM_Commands {

	/**
	 * Holds instance of MPSUM_Admin_Ajax class
	 *
	 * @var object
	 */
	private $admin_ajax;

	/**
	 * Holds instance of MPSUM_Premium_Admin_Ajax class
	 *
	 * @var object
	 */
	private $premium_admin_ajax;

	/**
	 * MPSUM_Commands class constructor.
	 */
	public function __construct() {
		$this->admin_ajax = MPSUM_Admin_Ajax::get_instance();
		if (Easy_Updates_Manager()->is_premium()) {
			$this->premium_admin_ajax = MPSUM_Premium_Admin_Ajax::get_instance();
		}
	}

	/**
	 * Retrieve and return version of the plugin
	 *
	 * @return string Version of the plugin
	 */
	public function get_version() {
		return EASY_UPDATES_MANAGER_VERSION;
	}

	/**
	 * Retrieves core options and returns to construct general tab content
	 *
	 * @return array|string An array of core options or error message
	 */
	public function get_general_contents() {
		$options = MPSUM_Updates_Manager::get_options('core', true);
		if (empty($options)) {
			$options = MPSUM_Admin_Core::get_defaults();
		} elseif (is_array($options)) {
			$options = wp_parse_args($options, MPSUM_Admin_Core::get_defaults());
		}
		return $options;
	}

	/**
	 * Saves core options and return updated core options
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return array|string An array of core options or an error message
	 */
	public function save_general_options($data) {
		$options = $data['data']['data'];
		$decoded_options = $this->_get_decoded_options($options);
		$core_options = MPSUM_Updates_Manager::get_options('core', true);
		$decoded_options['email_addresses'] = $core_options['email_addresses'];
		MPSUM_Updates_Manager::update_options($decoded_options, 'core');
		return MPSUM_Updates_Manager::get_options('core');
	}

	/**
	 * Saves email notification emails
	 *
	 * @param string $email_addresses Email addresses (may be comma separated)
	 *
	 * @return string A success or error message.
	 */
	public function save_notification_emails($email_addresses) {
		$email_addresses = trim($email_addresses);

		// Check for empty amail addresses and save empty email options.
		if (empty($email_addresses)) {
			$options = MPSUM_Updates_Manager::get_options('core', true);
			$options['email_addresses'] = '';
			MPSUM_Updates_Manager::update_options($options, 'core');
			return __('Your e-mail addresses have been saved.', 'stops-core-theme-and-plugin-updates');
		}

		// Check for valid emails.
		$email_validation = MPSUM_Utils::validate_emails($email_addresses); // $email_addresses holds a string of email addresses. May be comma separated.

		// Save emails if valid.
		if (! $email_validation['errors']) {
			$options = MPSUM_Updates_Manager::get_options('core', true);
			$options['email_addresses'] = $email_validation['emails'];
			MPSUM_Updates_Manager::update_options($options, 'core');
			return __('Your e-mail addresses have been saved.', 'stops-core-theme-and-plugin-updates');
		}
		return __('One or more of the e-mail addresses is invalid.', 'stops-core-theme-and-plugin-updates');
	}

	/**
	 * UC Sends `id`s of button elements which is converted to option key here
	 *
	 * @param array $options An array of updated options from remote call
	 *
	 * @return array An array of decoded options
	 */
	private function _get_decoded_options($options) {
		$decoded_options = array();
		foreach ($options as $option) {
			$pos = strrpos($option, '-');
			$value = substr($option, $pos+1);
			$key = substr($option, 0, strlen($option) - (strlen($value) + 1));
			$key = str_replace('-', '_', $key);
			$decoded_options[$key] = $value;
		}
		return $decoded_options;
	}

	/**
	 * Plugin tab content template
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Returns plugin tab content as HTML string
	 */
	public function get_plugins_contents($data) {
		if (!current_user_can('update_plugins')) {
			return __('User has insufficient capability to update plugins', 'stops-core-theme-and-plugin-updates');
		}
		$args = $this->_get_paged_view_status($data);
		if (! isset($args['slug'])) {
			$args['slug'] = 'mpsum-update-options';
		}
		return Easy_Updates_Manager()->include_template('admin-tab-plugins.php', true, $args);
	}

	/**
	 * Saves plugin update options
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Returns plugin tab content as HTML string
	 */
	public function save_plugins_update_options($data) {
		$args = $this->_get_paged_view_status($data);
		parse_str($data['data']['data'], $updated_options);
		$this->admin_ajax->save_plugins_update_options($updated_options);
		return Easy_Updates_Manager()->include_template('admin-tab-plugins.php', true, $args);
	}

	/**
	 * Saves plugin options performed via bulk actions
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Returns plugin tab content as HTML string
	 */
	public function bulk_action_plugins_update_options($data) {
		$args = $this->_get_paged_view_status($data);
		parse_str($data['data']['data'], $updated_options);
		$this->admin_ajax->bulk_action_plugins_update_options($updated_options);
		return Easy_Updates_Manager()->include_template('admin-tab-plugins.php', true, $args);
	}

	/**
	 * Theme tab content template
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Returns theme tab content as HTML string
	 */
	public function get_themes_contents($data) {
		if (!current_user_can('update_themes')) {
			return __('User has insufficient capability to update themes', 'stops-core-theme-and-plugin-updates');
		}
		$args = $this->_get_paged_view_status($data);
		if (! isset($args['slug'])) {
			$args['slug'] = 'mpsum-update-options';
		}
		return Easy_Updates_Manager()->include_template('admin-tab-themes.php', true, $args);
	}

	/**
	 * Saves theme update options
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Returns theme tab content as HTML string
	 */
	public function save_themes_update_options($data) {
		$args = $this->_get_paged_view_status($data);
		parse_str($data['data']['data'], $updated_options);
		$this->admin_ajax->save_themes_update_options($updated_options);
		return Easy_Updates_Manager()->include_template('admin-tab-themes.php', true, $args);
	}

	/**
	 * Saves theme options performed via bulk actions
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Returns theme tab content as HTML string
	 */
	public function bulk_action_themes_update_options($data) {
		$args = $this->_get_paged_view_status($data);
		parse_str($data['data']['data'], $updated_options);
		$this->admin_ajax->bulk_action_themes_update_options($updated_options);
		return Easy_Updates_Manager()->include_template('admin-tab-themes.php', true, $args);
	}

	/**
	 * Logs tab content template
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Returns logs tab content as HTML string
	 */
	public function get_logs_contents($data) {

		$paged = isset($data['data']['paged']) ? $data['data']['paged'] : '1';
		$view = isset($data['data']['view']) ? $data['data']['view'] : 'all';
		$m = isset($data['data']['m']) ? $data['data']['m'] : 'all';
		$status = isset($data['data']['status']) ? $data['data']['status'] : 'all';
		$action_type = isset($data['data']['action_type']) ? $data['data']['action_type'] : 'all';
		$type = isset($data['data']['type']) ? $data['data']['type'] : 'all';
		$order = isset($data['data']['order']) ? $data['data']['order'] : 'DESC';
		$is_search = isset($data['data']['is_search']) ? $data['data']['is_search'] : false;
		$search_term = isset($data['data']['search_term']) ? $data['data']['search_term'] : '';

		$args = array('paged' => $paged, 'view' => $view, 'status' => $status, 'action_type' => $action_type, 'type' => $type, 'm' => $m, 'is_search' => $is_search, 'search_term' => $search_term, 'order' => $order);
		if (! isset($args['slug'])) {
			$args['slug'] = 'mpsum-update-options';
		}
		return Easy_Updates_Manager()->include_template('admin-tab-logs.php', true, $args);
	}

	/**
	 * Advanced tab content template
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Returns advanced tab content as HTML string
	 */
	public function get_advanced_contents() {
		new MPSUM_Admin_Advanced();
		if (Easy_Updates_Manager()->is_premium()) {
			new MPSUM_Premium();
		}
		return Easy_Updates_Manager()->include_template('admin-tab-advanced.php', true);
	}

	/**
	 * Checks whether logging is enabled or not
	 *
	 * @return bool Return true if logging is enabled, otherwise false
	 */
	public function is_logs_enabled() {
		$options = MPSUM_Updates_Manager::get_options('core');
		if (isset($options['logs']) && 'on' == $options['logs']) {
			return true;
		}
		return false;
	}

	/**
	 * Forces updates immediately
	 *
	 * @return string Confirmation message of forced updates
	 */
	public function force_updates() {
		if (!$this->user_can_update()) {
			return __('User has insufficient capability to do updates', 'stops-core-theme-and-plugin-updates');
		}
		return $this->admin_ajax->force_updates();
	}

	/**
	 * Saves logs settings
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Confirmation message of saved log option
	 */
	public function save_logs_settings($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to save options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->save_logs_settings($data);
	}

	/**
	 * Export settings as json file
	 *
	 * @return string Confirmation message
	 */
	public function export_settings() {
		if (!$this->user_can_update()) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->export_settings();
	}

	/**
	 * Export settings as json file
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Confirmation message
	 */
	public function import_settings($data) {
		if (!$this->user_can_update()) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->import_settings($data['data']);
	}

	/**
	 * Saves excluded users option
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Confirmation message of saved users
	 */
	public function save_excluded_users($data) {
		if (!current_user_can('promote_users')) {
			return __('User has insufficient capability to promote users', 'stops-core-theme-and-plugin-updates');
		}
		return $this->admin_ajax->save_excluded_users($data);
	}

	/**
	 * Enable auto back
	 *
	 * @return string Confirmation message of enabling auto backup
	 */
	public function enable_auto_backup() {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to take backups', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->enable_auto_backup();
	}

	/**
	 * Disable auto back
	 *
	 * @return string Confirmation message of enabling auto backup
	 */
	public function disable_auto_backup() {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to take backups', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->disable_auto_backup();
	}

	/**
	 * Save update cron schedule
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Returns next schedule message
	 */
	public function save_cron_schedule($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to take backups', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->save_cron_schedule($data);
	}

	/**
	 * Save delayed update duration
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Returns success message
	 */
	public function save_delay_updates($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->save_delay_updates($data);
	}

	/**
	 * Save anonymize update options
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return string Returns success message
	 */
	public function save_anonymize_updates($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->save_anonymize_updates($data);
	}


	/**
	 * Enables logging
	 *
	 * @return string Confirmation message
	 */
	public function enable_logs() {
		return $this->admin_ajax->enable_logs();
	}

	/**
	 * Checks to see if plugins are in the WordPress Directory
	 *
	 * @param array $data An array of updated options
	 *
	 * @return string|array Result of ajax call
	 */
	public function check_plugins($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->check_plugins($data);
	}

	/**
	 * Enables White-list
	 *
	 * @param array $data An array of updated options
	 *
	 * @return string Confirmation message
	 */
	public function whitelist_save($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->whitelist_save($data['data']);
	}

	/**
	 * Reset White-list
	 *
	 * @param array $data An array of updated options
	 *
	 * @return string Confirmation message
	 */
	public function whitelist_reset($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->whitelist_reset($data);
	}

	/**
	 * Enables webhook
	 *
	 * @param array $data An array of updated options
	 *
	 * @return string Confirmation message
	 */
	public function enable_webhook($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->enable_webhook($data);
	}

	/**
	 * Disables webhook
	 *
	 * @param array $data An array of updated options
	 *
	 * @return string Confirmation message
	 */
	public function disable_webhook($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->disable_webhook($data);
	}

	/**
	 * Enables safe mode
	 *
	 * @param array $data An array of updated options
	 *
	 * @return string Confirmation message
	 */
	public function enable_safe_mode($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->enable_safe_mode($data);
	}

	/**
	 * Disables safe mode
	 *
	 * @param array $data An array of updated options
	 *
	 * @return string Confirmation message
	 */
	public function disable_safe_mode($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->disable_safe_mode($data);
	}

	/**
	 * Enables Version Control Protection
	 *
	 * @param array $data An array of updated options
	 *
	 * @return string Confirmation message
	 */
	public function enable_version_control($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->enable_version_control_protection($data);
	}

	/**
	 * Disables Version Control Protection
	 *
	 * @param array $data An array of updated options
	 *
	 * @return string Confirmation message
	 */
	public function disable_version_control($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->disable_version_control_protection($data);
	}

	/**
	 * Enables Unmaintained Plugin Check
	 *
	 * @param array $data An array of updated options
	 *
	 * @return string Confirmation message
	 */
	public function enable_unmaintained_plugins_check($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->enable_unmaintained_plugins($data);
	}

	/**
	 * Disables Unmaintained Plugin Check
	 *
	 * @param array $data An array of updated options
	 *
	 * @return string Confirmation message
	 */
	public function disable_unmaintained_plugins_check($data) {
		if (!current_user_can('manage_options')) {
			return __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		}
		return $this->premium_admin_ajax->disable_unmaintained_plugins($data);
	}

	/**
	 * Clears logs
	 *
	 * @return string Confirmation message
	 */
	public function clear_logs() {
		return $this->admin_ajax->clear_logs();
	}

	/**
	 * Resets all options
	 *
	 * @return string Confirmation message
	 */
	public function reset_options() {
		return $this->admin_ajax->reset_options();
	}

	/**
	 * Returns paged argument and view filter argument
	 *
	 * @param array $data - Data from the remote call
	 *
	 * @return array
	 */
	private function _get_paged_view_status($data) {
		$paged = isset($data['data']['paged']) ? $data['data']['paged'] : '1';
		$view = isset($data['data']['view']) ? $data['data']['view'] : 'all';
		return array('paged' => $paged, 'view' => $view);
	}

	/**
	 * Checks whether you can do updates to core, plugins and themes
	 *
	 * @return bool True if user can do updates, false otherwise
	 */
	private function user_can_update() {
		if (current_user_can('update_themes') && current_user_can('update_plugins') && current_user_can('update_core')) return true;
		return false;
	}
}
