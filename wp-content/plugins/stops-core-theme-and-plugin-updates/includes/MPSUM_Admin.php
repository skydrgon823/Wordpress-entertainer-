<?php
if (!defined('ABSPATH')) die('No direct access.');
/**
 * Easy Updates Manager admin controller.
 * Initializes the admin panel options and load the admin dependencies
 *
 * @package WordPress
 * @since 5.0.0
 */
class MPSUM_Admin {

	/**
	 * Holds the class instance.
	 *
	 * @since 5.0.0
	 * @access static
	 * @var MPSUM_Admin $instance
	 */
	private static $instance = null;

	/**
	 * Holds the URL to the admin panel page
	 *
	 * @since 5.0.0
	 * @access static
	 * @var string $url
	 */
	private static $url = '';

	/**
	 * Holds the slug to the admin panel page
	 *
	 * @since 5.0.0
	 * @access static
	 * @var string $slug
	 */
	private static $slug = 'mpsum-update-options';

	/**
	 * Set a class instance.
	 *
	 * Set a class instance.
	 *
	 * @since 5.0.0
	 * @access static
	 */
	public static function run() {
		if (null == self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	} //end get_instance

	/**
	 * Class constructor.
	 *
	 * Initialize the class
	 *
	 * @since 5.0.0
	 * @access private
	 */
	private function __construct() {
		add_action('init', array( $this, 'init' ), 9);
		add_filter('set-screen-option', array( $this, 'add_screen_option_save' ), 10, 3);
	} //end constructor

	/**
	 * Save the screen options.
	 *
	 * @since 6.2.0
	 * @param string $status Screen option status
	 * @param string $option Screen option
	 * @param string $value  Screen value
	 * @return string Returns value if succeeds, otherwise status
	 */
	public function add_screen_option_save( $status, $option, $value ) {
		return MPSUM_Admin_Screen_Options::save_options($status, $option, $value);
	}

	/**
	 * Return the URL to the admin panel page.
	 *
	 * Return the URL to the admin panel page.
	 *
	 * @since 5.0.0
	 * @access static
	 *
	 * @return string URL to the admin panel page.
	 */
	public static function get_url() {
		$url = self::$url;
		if (empty($url)) {
			if (is_multisite()) {
				$url = add_query_arg(array( 'page' => self::get_slug() ), network_admin_url('index.php'));
			} else {
				$url = add_query_arg(array( 'page' => self::get_slug() ), admin_url('index.php'));
			}
			self::$url = $url;
		}
		return $url;
	}

	/**
	 * Return the slug for the admin panel page.
	 *
	 * Return the slug for the admin panel page.
	 *
	 * @since 5.0.0
	 * @access static
	 *
	 * @return string slug to the admin panel page.
	 */
	public static function get_slug() {
		return self::$slug;
	}

	/**
	 * Initialize the admin menu.
	 *
	 * Initialize the admin menu.
	 *
	 * @since 5.0.0
	 * @access public
	 * @see __construct
	 * @internal Uses init action
	 */
	public function init() {

		// Plugin and Theme actions
		if (is_multisite()) {
			add_action('network_admin_menu', array($this, 'init_network_admin_menus'));
			add_action('wp_before_admin_bar_render', array($this, 'add_networkadmin_page'));
		} else {
			add_action('admin_menu', array($this, 'init_single_site_admin_menus'));
		}
		add_action('admin_bar_menu', array($this, 'add_admin_bar'), 100);

		// Disable information bar in modal popup
		add_action('admin_head', array($this, 'maybe_disable_plugin_information_bar'));

		// todo - maybe load these conditionally based on $_REQUEST[ 'tab' ] param
		new MPSUM_Admin_Dashboard(self::get_slug());
		new MPSUM_Admin_Plugins(self::get_slug());
		new MPSUM_Admin_Themes(self::get_slug());
		new MPSUM_Admin_Logs(self::get_slug());
		new MPSUM_Admin_Core(self::get_slug());
		new MPSUM_Admin_Advanced(self::get_slug());
		if (!Easy_Updates_Manager()->is_premium()) {
			new MPSUM_Advanced_Premium();
		}

		MPSUM_Admin_Screen_Options::maybe_save_dashboard_screen_option();

	}

	/**
	 * Initializes the help screen.
	 *
	 * Initializes the help screen.
	 *
	 * @since 5.0.0
	 * @access public
	 * @see init
	 * @internal Uses load_{$hook} action
	 */
	public function init_help_screen() {
		new MPSUM_Admin_Help();
	}

	/**
	 * Initializes the screen options.
	 *
	 * Initializes the screen options.
	 *
	 * @since 6.2
	 * @access public
	 * @see init
	 * @internal Uses load_{$hook} action
	 */
	public function init_screen_options() {
		MPSUM_Admin_Screen_Options::run();
	}

	/**
	 * Returns formatted array of EUM options
	 *
	 * Returns formatted array of EUM options.
	 *
	 * @since 6.3
	 * @access public
	 *
	 * @return array EUM implementation options
	 */
	private function get_options_for_default() {
		$options = MPSUM_Updates_Manager::get_options();
		if (! isset($options['core'])) {
			$options['core'] = MPSUM_Admin_Core::get_defaults();
		}
		if (! isset($options['plugins'])) {
			$options['plugins'] = array();
		}
		if (! isset($options['themes'])) {
			$options['themes'] = array();
		}
		if (! isset($options['plugins_automatic'])) {
			$options['plugins_automatic'] = array();
		}
		if (! isset($options['themes_automatic'])) {
			$options['themes_automatic'] = array();
		}
		if (!isset($options['logs'])) {
			$options['logs'] = array();
		}
		if (!isset($options['advanced'])) {
			$options['advanced'] = array();
		}

		return $options;

	}

	/**
	 * When more details modal pops up, maybe disable the footer
	 *
	 * @return void
	 */
	public function maybe_disable_plugin_information_bar() {

		// When the more details modal shows up on the plugins tab, it displays an information footer for active
		// or inactive installs. It also shows when a plugin is available to update.
		// This will inject styles into the modal hiding the information bar to prevent
		// any kind of confusion.
		if (isset($_GET['eum_action']) && 'EUM_modal' === $_GET['eum_action']) {
			echo '<style>#plugin-information-footer{display:none;}</style>';
		}
	}

	/**
	 * Enqueue scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		// Get active page and active tab fore enqueuing
		$pagenow = isset($_GET['page']) ? $_GET['page'] : false;
		$is_active_tab = isset($_GET['tab']) ? $_GET['tab'] : false;

		// Check to make sure we're on the mpsum admin page
		if ('mpsum-update-options' != $pagenow) {
			return;
		}
		$min_or_not = ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) ? '' : '.min';

		// Get user data
		$user_id = get_current_user_id();
		$dashboard_showing = get_user_meta($user_id, 'mpsum_dashboard', true);

		// Get options
		$options = MPSUM_Updates_Manager::get_options('core');

		// Modal dependencies on the plugins tab
		if ('plugins' === $is_active_tab) {
			wp_enqueue_script('plugin-install');
			wp_enqueue_script('updates');
			wp_enqueue_script('common');
			wp_enqueue_style('common');
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
		}

		wp_enqueue_script('jquery-blockui', MPSUM_Updates_Manager::get_plugin_url('/js/jquery.blockUI' . $min_or_not . '.js'), array('jquery'), EASY_UPDATES_MANAGER_VERSION, true);
		wp_enqueue_script('jquery-serializejson', MPSUM_Updates_Manager::get_plugin_url('/js/jquery.serializejson' . $min_or_not . '.js'), array('jquery'), EASY_UPDATES_MANAGER_VERSION, true);
		wp_enqueue_script('mpsum_dashboard_js', MPSUM_Updates_Manager::get_plugin_url('/js/eum-admin' . $min_or_not .'.js'), array( 'jquery', 'wp-ajax-response' ), EASY_UPDATES_MANAGER_VERSION, true);
		wp_enqueue_script('mpsum_dashboard_react', MPSUM_Updates_Manager::get_plugin_url('/js/admin' . $min_or_not . '.js'), array( 'jquery', 'mpsum_dashboard_js' ), EASY_UPDATES_MANAGER_VERSION, true);


		$user_id = get_current_user_id();
		$dashboard_showing = get_user_meta($user_id, 'mpsum_dashboard', true);
		if (! $dashboard_showing) {
			$dashboard_showing = 'on';
		}

		$options = MPSUM_Updates_Manager::get_options();

		$I18N = array(
			'default'                                    => _x('Default', 'Option as default', 'stops-core-theme-and-plugin-updates'),
			'on'                                         => _x('On', 'Option enabled', 'stops-core-theme-and-plugin-updates'),
			'off'                                        => _x('Off', 'Option disabled', 'stops-core-theme-and-plugin-updates'),
			'nothing'                                         => __('Nothing', 'stops-core-theme-and-plugin-updates'),
			'everything'                                        => __('Everything', 'stops-core-theme-and-plugin-updates'),
			'custom'                                     => _x('Custom', 'Option allows for configuration', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_on'                       => __('Auto update everything', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_off'                       => __('Disable auto updates', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_custom_tooltip'           => __('Customize each option individually', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_default'                  => __('WordPress default settings', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_default_tooltip'          => __('WordPress acts like this plugin is not installed. Only minor core updates, translations, and severe security patches will be automatically updated.', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_off_tooltip'             => __('No automatic updates should occur.', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_default_status'           => __('You have selected default. WordPress will behave as if this plugin is not installed for automatic updates.', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_on_status'                => __('Automatic updates are on for everything.', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_on_tooltip'               => __('Auto update everything.', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_off_status'               => __('Automatic updates are off for everything.', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_custom_status'            => __('You have selected to customize the updates below.', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates'                          => __('Quick configuration actions', 'stops-core-theme-and-plugin-updates'),
			'automatic_updates_description'              => __('Press a button below for quick configuration; this is a quick way to change several other settings below in one go.', 'stops-core-theme-and-plugin-updates'),
			'major_releases'                             => __('Major WordPress releases', 'stops-core-theme-and-plugin-updates'),
			'major_releases_description'                 => __('Automatically update to new major releases of WordPress (e.g., 4.1, 4.2, 4.3).', 'stops-core-theme-and-plugin-updates'),
			'major_releases_label_on'                    => __('Enable major releases', 'stops-core-theme-and-plugin-updates'),
			'major_releases_label_on_status'             => __('Automatic major release updates are now turned on.', 'stops-core-theme-and-plugin-updates'),
			'major_releases_label_off'                   => __('Disable major releases', 'stops-core-theme-and-plugin-updates'),
			'major_releases_label_off_status'            => __('Automatic major release updates are now turned off.', 'stops-core-theme-and-plugin-updates'),
			'minor_releases'                             => _x('Minor WordPress releases', 'Minor releases for WordPress', 'stops-core-theme-and-plugin-updates'),
			'minor_releases_description'                 => __('Automatically update to new minor releases in your current series (e.g., 4.1.1, 4.1.2, 4.1.3).', 'stops-core-theme-and-plugin-updates'),
			'minor_releases_label_on'                    => __('Enable minor releases', 'stops-core-theme-and-plugin-updates'),
			'minor_releases_label_on_status'             => __('Automatic minor release updates are now turned on.', 'stops-core-theme-and-plugin-updates'),
			'minor_releases_label_off'                   => __('Disable minor releases', 'stops-core-theme-and-plugin-updates'),
			'minor_releases_label_off_status'            => __('Automatic minor release updates are now turned off.', 'stops-core-theme-and-plugin-updates'),
			'development_releases'                       => _x('Development updates (Core)', 'Beta and RC releases for WordPress core', 'stops-core-theme-and-plugin-updates'),
			'development_releases_description'           => __('Allow your install to receive development updates for WordPress core (for advanced users only)', 'stops-core-theme-and-plugin-updates'),
			'development_releases_label_on'              => __('Include WordPress development updates', 'stops-core-theme-and-plugin-updates'),
			'development_releases_label_on_status'       => __('Automatic development release updates are now turned on.', 'stops-core-theme-and-plugin-updates'),
			'development_releases_label_off'             => __('Disable development updates', 'stops-core-theme-and-plugin-updates'),
			'development_releases_label_off_status'      => __('Automatic development release updates are now turned off.', 'stops-core-theme-and-plugin-updates'),
			'translation_releases'                       => _x('Translation updates', 'Enable or disable translation updates', 'stops-core-theme-and-plugin-updates'),
			'translation_releases_description'           => __('Automatically update your translations.', 'stops-core-theme-and-plugin-updates'),
			'translation_releases_label_on'              => __('Enable translation updates', 'stops-core-theme-and-plugin-updates'),
			'translation_releases_label_on_status'       => __('Automatic translation updates are now turned on.', 'stops-core-theme-and-plugin-updates'),
			'translation_releases_label_off'             => __('Disable translation updates', 'stops-core-theme-and-plugin-updates'),
			'translation_releases_label_off_status'      => __('Automatic translation updates are now turned off.', 'stops-core-theme-and-plugin-updates'),
			'select_individually'                        => __('Select individually', 'stops-core-theme-and-plugin-updates'),
			'automatic_plugin_updates'                   => __('Automatic plugin updates', 'stops-core-theme-and-plugin-updates'),
			'automatic_plugin_updates_description'       => __('Automatically update your plugins. Select always on, always off, the WordPress default, or select plugins individually using the plugins tab.', 'stops-core-theme-and-plugin-updates'),
			'automatic_plugin_updates_default_status'    => __('Automatic updates for plugins are now at their default setting (default is off).', 'stops-core-theme-and-plugin-updates'),
			'automatic_plugin_updates_on_status'         => __('Automatic updates for plugins are now on.', 'stops-core-theme-and-plugin-updates'),
			'automatic_plugin_updates_off_status'        => __('Automatic updates for plugins are now off.', 'stops-core-theme-and-plugin-updates'),
			'automatic_plugin_updates_individual_status' => __('Automatic updates for plugins can be customized in the plugins tab.', 'stops-core-theme-and-plugin-updates'),
			'automatic_theme_updates'                    => __('Automatic theme updates', 'stops-core-theme-and-plugin-updates'),
			'automatic_theme_updates_description'        => __('Automatically update your themes. Select always on, always off, the WordPress default, or select themes individually using the themes tab.', 'stops-core-theme-and-plugin-updates'),
			'automatic_theme_updates_default_status'     => __('Automatic updates for themes are now at their default setting (default is off).', 'stops-core-theme-and-plugin-updates'),
			'automatic_theme_updates_on_status'          => __('Automatic updates for themes are now on.', 'stops-core-theme-and-plugin-updates'),
			'automatic_theme_updates_off_status'         => __('Automatic updates for themes are now off.', 'stops-core-theme-and-plugin-updates'),
			'automatic_theme_updates_individual_status'  => __('Automatic updates for themes can be customized in the themes tab.', 'stops-core-theme-and-plugin-updates'),
			'disable_updates'                            => __('Disable all updates', 'stops-core-theme-and-plugin-updates'),
			'disable_updates_description'                => __('This is a master switch and will enable or disable updates for the WordPress installation. Switching updates off is not recommended.', 'stops-core-theme-and-plugin-updates'),
			'disable_updates_label_on'                   => __('Enable all updates', 'stops-core-theme-and-plugin-updates'),
			'disable_updates_label_on_status'            => __('Updates are allowed; however, you still need to configure the updates below.', 'stops-core-theme-and-plugin-updates'),
			'disable_updates_label_off'                  => __('Disable all updates', 'stops-core-theme-and-plugin-updates'),
			'disable_updates_label_off_status'           => __('All updates are disabled. Individual updates settings (i.e. for automatic updates and for plugin/theme/translation updates) below will be ignored.', 'stops-core-theme-and-plugin-updates'),
			'logs'                                       => _x('Logs', 'Log what is stored when assets update', 'stops-core-theme-and-plugin-updates'),
			'logs_description'                           => __('Logs will show you what assets have updated and will show up in the logs tab.', 'stops-core-theme-and-plugin-updates'),
			'logs_url'                                   => sprintf('<a href="%s" class="%s" id="%s">%s</a>', esc_url(add_query_arg(array('tab' => 'logs'), MPSUM_Admin::get_url())), 'nav-tab', 'eum-logs', esc_html__('Logs', 'stops-core-theme-and-plugin-updates')),
			'logs_label_on'                              => __('Enable logs', 'stops-core-theme-and-plugin-updates'),
			'logs_label_on_status'                       => __('Logs are enabled. You will find logs in the logs tab.', 'stops-core-theme-and-plugin-updates'),
			'logs_label_off'                             => __('Disable logs', 'stops-core-theme-and-plugin-updates'),
			'logs_label_off_status'                      => __('Logs are disabled.', 'stops-core-theme-and-plugin-updates'),
			'ratings_nag'                                => _x('Please give us a rating', 'Asks a user to give a rating for the plugin.', 'stops-core-theme-and-plugin-updates'),
			'ratings_nag_description'                    => __('We work very hard on this plugin. Please show your appreciation and rate the plugin as you see fit.', 'stops-core-theme-and-plugin-updates'),
			'ratings_nag_label_on'                       => __('Rate the plugin on WordPress.org', 'stops-core-theme-and-plugin-updates'),
			'ratings_nag_label_off'                      => __('I have already left a rating', 'stops-core-theme-and-plugin-updates'),
			'emails'                                     => __('Core notification e-mails', 'stops-core-theme-and-plugin-updates'),
			'emails_description'                         => __('WordPress periodically sends update notification e-mails, such as in the case of automatic updates. By default, the e-mail address used is the one in Settings->General, but you can override this below.', 'stops-core-theme-and-plugin-updates'),
			'emails_label_on'                            => __('Enable core notification e-mails', 'stops-core-theme-and-plugin-updates'),
			'emails_label_on_status'                     => __('E-mail notifications are on. You can configure which e-mail addresses are sent to below.', 'stops-core-theme-and-plugin-updates'),
			'emails_label_off'                           => __('Disable core notification e-mails', 'stops-core-theme-and-plugin-updates'),
			'emails_label_off_status'                    => __('E-mail notifications are off', 'stops-core-theme-and-plugin-updates'),
			'emails_placeholder'                         => __('Add an e-mail address', 'stops-core-theme-and-plugin-updates'),
			'emails_input_label'                         => __('Enter comma-separated e-mail addresses', 'stops-core-theme-and-plugin-updates'),
			'emails_invalid'                             => __('One or more e-mail addresses are invalid.', 'stops-core-theme-and-plugin-updates'),
			'emails_saveed'                              => __('Your e-mail address settings have been successfully saved.', 'stops-core-theme-and-plugin-updates'),
			'emails_save'                                => __('Save e-mail addresses', 'stops-core-theme-and-plugin-updates'),
			'emails_save_empty'                          => __('Please enter an e-mail address', 'stops-core-theme-and-plugin-updates'),
			'emails_saving'                              => __('Saving...', 'stops-core-theme-and-plugin-updates'),
			'core_updates'                               => __('WordPress core updates', 'stops-core-theme-and-plugin-updates'),
			'core_updates_description'                   => __('This allows you to configure how WordPress updates are handled, including automatic updates.', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_on'                      => __('Manually update', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_on_tooltip'              => __('Update WordPress manually through the updates screen. Minor WordPress versions will still automatically update.', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_on_status'               => __('Core updates are set to manual. Update WordPress from the updates screen.', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_off'                     => __('Disable core updates', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_off_tooltip'             => __('Turn off all core WordPress updates and prevent update notices from being shown.', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_auto_disabled'           => __('Disable auto updates', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_auto_disabled_tooltip'   => __('Turn off all automatic updates for WordPress core.', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_automatic'               => __('Auto update all releases', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_automatic_tooltip'       => __('Update WordPress core and minor versions automatically.', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_automatic_minor'         => __('Auto update all minor versions', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_automatic_minor_tooltip' => __('Default behavior. WordPress will automatically update minor versions.', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_automatic_status'        => __('Choose how to automatically update WordPress.', 'stops-core-theme-and-plugin-updates'),
			'core_updates_label_off_status'              => __('Core updates are disabled and will not show up on the updates screen.', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates'                             => __('Plugin updates', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_description'                 => __('This allows you to disable or enable all plugin updates, including automatic updates.', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_on'                    => __('Manually update', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_on_tooltip'            => __('Manually update your plugins. Some auto updates may still occur such as security patches.', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_on_status'             => __('Plugin updates are enabled.', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_off'                   => __('Disable plugin updates', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_off_tooltip'           => __('Turn off all plugin updates and prevent update notices from being shown.', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_automatic'             => __('Enable auto updates', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_automatic_tooltip'     => __('Automatically upgrade all plugins.', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_auto_disabled'         => __('Disable auto updates', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_auto_disabled_tooltip' => __('Turn off automatic updates for plugins.', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_individually'          => __('Choose per plugin', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_individually_tooltip'  => __('Select which plugins receive automatic updates in the plugins tab.', 'stops-core-theme-and-plugin-updates'),
			'plugin_updates_label_off_status'            => __('Plugin updates are disabled.', 'stops-core-theme-and-plugin-updates'),
			'theme_updates'                              => __('Theme updates', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_description'                  => __('This allows you to disable or enable all theme updates. Disabling this option will also disable automatic updates.', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_on'                     => __('Manually update', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_on_tooltip'             => __('Manually update your themes. Some auto updates may still occur such as security patches.', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_on_status'              => __('Theme updates are enabled.', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_off'                    => __('Disable theme updates', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_off_tooltip'            => __('Turn off all theme updates and prevent update notices from being shown.', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_off_status'             => __('Theme updates are disabled.', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_automatic'              => __('Enable auto updates', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_automatic_tooltip'      => __('Enable automatic updates for all themes.', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_auto_disabled'          => __('Disable auto updates', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_auto_disabled_tooltip'  => __('Turn off automatic updates for themes.', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_individually'           => __('Choose per theme', 'stops-core-theme-and-plugin-updates'),
			'theme_updates_label_individually_tooltip'   => __('Select which themes receive automatic updates in the themes tab.', 'stops-core-theme-and-plugin-updates'),
			'translation_updates'                        => __('Translation updates', 'stops-core-theme-and-plugin-updates'),
			'translation_updates_description'            => __('This allows you to disable or enable all translations. Choose automatic to automatically update your translations.', 'stops-core-theme-and-plugin-updates'),
			'translation_updates_label_on'               => __('Manually update', 'stops-core-theme-and-plugin-updates'),
			'translation_updates_label_on_tooltip'       => __('Manually update your translations and receive periodic translations from the WordPress automatic update component.', 'stops-core-theme-and-plugin-updates'),
			'translation_updates_label_on_status'        => __('Translation updates are enabled.', 'stops-core-theme-and-plugin-updates'),
			'translation_updates_label_off'              => __('Disable translation updates', 'stops-core-theme-and-plugin-updates'),
			'translation_updates_label_off_tooltip'      => __('Turn off all translation updates and prevent update notices from being shown.', 'stops-core-theme-and-plugin-updates'),
			'translation_updates_label_automatic'        => __('Enable auto updates', 'stops-core-theme-and-plugin-updates'),
			'translation_updates_automatic_tooltip'      => __('Automatically update all translations as they are ready.', 'stops-core-theme-and-plugin-updates'),
			'translation_updates_label_auto_disabled'    => __('Disable auto updates', 'stops-core-theme-and-plugin-updates'),
			'translation_updates__auto_disabled_tooltip' => __('Turn off automatic updates for translations.', 'stops-core-theme-and-plugin-updates'),
			'translation_updates_label_off_status'       => __('Translation updates are disabled.', 'stops-core-theme-and-plugin-updates'),
			'general_section_title_updates_settings'     => __('Updates settings', 'stops-core-theme-and-plugin-updates'),
			'general_section_title_notifications'        => __('Notifications', 'stops-core-theme-and-plugin-updates'),
			'general_section_title_others'               => __('Others', 'stops-core-theme-and-plugin-updates'),
			'general_section_title_rating'               => __('Rating', 'stops-core-theme-and-plugin-updates'),
		);

		// Show the ratings? Based on a constant and a default option that a user can select.
		$maybe_show_ratings_nag = 'on';
		if (isset($options['core']['ratings_nag'])) {
			$maybe_show_ratings_nag = $options['core']['ratings_nag'];
		}
		if (defined('EUM_ENABLE_RATINGS_NAG') && !EUM_ENABLE_RATINGS_NAG) {
			$maybe_show_ratings_nag = 'off';
		}

		wp_localize_script('mpsum_dashboard_react', 'mpsum', apply_filters('eum_i18n', array(
			'spinner'             => MPSUM_Updates_Manager::get_plugin_url('/images/spinner.gif'),
			'tabs'                => _x('Tabs', 'Show or hide admin tabs', 'stops-core-theme-and-plugin-updates'),
			'dashboard'           => _x('Show dashboard', 'Show or hide the dashboard', 'stops-core-theme-and-plugin-updates'),
			'dashboard_showing'   => $dashboard_showing,
			'enabled'             => __('Enabled', 'stops-core-theme-and-plugin-updates'),
			'disabled'            => __('Disabled', 'stops-core-theme-and-plugin-updates'),
			'admin_nonce'         => wp_create_nonce('mpsum_options_save'),
			'eum_nonce'           => wp_create_nonce('eum_nonce'),
			'ajax_url'            => admin_url('admin-ajax.php'),
			'unexpected_response' => __('Unexpected response:', 'stops-core-theme-and-plugin-updates'),
			'I18N'                => $I18N,
			'saving'              => __('Saving...', 'stops-core-theme-and-plugin-updates'),
			'working'             => __('Working...', 'stops-core-theme-and-plugin-updates'),
			'logo'                => MPSUM_Updates_Manager::get_plugin_url('/images/site_icon.png'),
			'is_premium'          => MPSUM_Updates_Manager::get_instance()->is_premium() ? 'true' : 'false',
			'is_debug'            => (defined('WP_DEBUG') && WP_DEBUG) ? 'true' : 'false',
			'ratings_nag'         => $maybe_show_ratings_nag
		)));
		wp_enqueue_style('mpsum_dashboard', MPSUM_Updates_Manager::get_plugin_url('/css/style.css'), array(), EASY_UPDATES_MANAGER_VERSION);
	}

	/**
	 * Add the "Easy Updates Manager" menu.
	 *
	 * @since 8.0.1
	 *
	 * @param WP_Admin_Bar $wp_admin_bar WordPress Admin bar Instance
	 */
	public function add_admin_bar($wp_admin_bar) {

		if (defined('EASY_UPDATES_MANAGER_ADMIN_BAR') && !EASY_UPDATES_MANAGER_ADMIN_BAR) return;

		// Check for valid permissions
		if (is_multisite() && !current_user_can('manage_network')) {
			return;
		}
		if (!is_multisite() && !current_user_can('update_plugins')) {
			return;
		}

		// Get core options
		$options = MPSUM_Updates_Manager::get_options('core');

		// Check to see if logs are enabled
		$admin_bar_enabled = true;
		if (isset($options['enable_admin_bar']) && 'off' === $options['enable_admin_bar']) {
			$admin_bar_enabled = false;
		}
		if ($admin_bar_enabled) :
			// Add parent menu
			$wp_admin_bar->add_menu(array(
				'id'    => 'easy-updates-manager-admin-bar',
				'title' => __('Updates', 'stops-core-theme-and-plugin-updates'),
				'href'  => self::get_url(),
			));

			// Add General Tab
			$wp_admin_bar->add_menu(array(
				'id'     => 'easy-updates-manager-admin-bar-general',
				'title'  => __('General', 'stops-core-theme-and-plugin-updates'),
				'href'   => add_query_arg(array('tab' => 'general'), self::get_url()),
				'parent' => 'easy-updates-manager-admin-bar'
			));

			// Add plugins tab
			$wp_admin_bar->add_menu(array(
				'id'     => 'easy-updates-manager-admin-bar-plugins',
				'title'  => __('Plugins', 'stops-core-theme-and-plugin-updates'),
				'href'   => add_query_arg(array('tab' => 'plugins'), self::get_url()),
				'parent' => 'easy-updates-manager-admin-bar'
			));

			// Add Themes tab
			$wp_admin_bar->add_menu(array(
				'id'     => 'easy-updates-manager-admin-bar-themes',
				'title'  => __('Themes', 'stops-core-theme-and-plugin-updates'),
				'href'   => add_query_arg(array('tab' => 'themes'), self::get_url()),
				'parent' => 'easy-updates-manager-admin-bar'
			));

			// Add logs tab
			$wp_admin_bar->add_menu(array(
				'id'     => 'easy-updates-manager-admin-bar-logs',
				'title'  => __('Logs', 'stops-core-theme-and-plugin-updates'),
				'href'   => add_query_arg(array('tab' => 'logs'), self::get_url()),
				'parent' => 'easy-updates-manager-admin-bar'
			));

			$wp_admin_bar->add_menu(array(
				'id'     => 'easy-updates-manager-admin-bar-advanced',
				'title'  => __('Advanced', 'stops-core-theme-and-plugin-updates'),
				'href'   => add_query_arg(array('tab' => 'advanced'), self::get_url()),
				'parent' => 'easy-updates-manager-admin-bar'
			));

			if (!MPSUM_Updates_Manager::get_instance()->is_premium()) {
				$wp_admin_bar->add_menu(array(
					'id'     => 'easy-updates-manager-admin-bar-premium',
					'title'  => __('Premium', 'stops-core-theme-and-plugin-updates'),
					'href'   => add_query_arg(array('tab' => 'premium'), self::get_url()),
					'parent' => 'easy-updates-manager-admin-bar'
				));
			}
		endif;
	}

	/**
	 * Adds a sub-menu page for multisite.
	 *
	 * Adds a sub-menu page for multisite.
	 *
	 * @since 5.0.0
	 * @access public
	 * @see init
	 * @internal Uses network_admin_menu action
	 */
	public function init_network_admin_menus() {
		$hook = add_dashboard_page(__('Updates options', 'stops-core-theme-and-plugin-updates'), __('Updates options', 'stops-core-theme-and-plugin-updates'), 'manage_options', self::get_slug(), array( $this, 'output_admin_interface' ));
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_scripts' ));
		add_action("load-$hook", array( $this, 'init_help_screen' ));
		add_action("load-$hook", array( $this, 'init_screen_options' ));
	}

	/**
	 * Adds a sub-menu page for single-site.
	 *
	 * Adds a sub-menu page for single-site.
	 *
	 * @since 5.0.0
	 * @access public
	 * @see init
	 * @internal Uses admin_menu action
	 */
	public function init_single_site_admin_menus() {
		$hook = add_dashboard_page(__('Updates options', 'stops-core-theme-and-plugin-updates'), __('Updates options', 'stops-core-theme-and-plugin-updates'), 'manage_options', self::get_slug(), array( $this, 'output_admin_interface' ));
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_scripts' ));
		add_action("load-$hook", array( $this, 'init_help_screen' ));
		add_action("load-$hook", array( $this, 'init_screen_options' ));
	}

	/**
	 * Outputs admin interface for sub-menu.
	 *
	 * Outputs admin interface for sub-menu.
	 *
	 * @since 5.0.0
	 * @access public
	 * @see init_network_admin_menus, init_single_site_admin_menus
	 */
	public function output_admin_interface() {
		?>
		<div class="wrap">
			<h1 id="eum-main-heading">
			<?php
			$eum_white_label = apply_filters('eum_whitelabel_name', __('Easy Updates Manager', 'stops-core-theme-and-plugin-updates'));
			echo esc_html($eum_white_label);
			?>
			</h1>
			<?php
			$tabs = array();

			$tabs[] = array(
				'url'    => add_query_arg(array( 'tab' => 'general' ), self::get_url()), /* URL to the tab */
				'label'  => esc_html__('General', 'stops-core-theme-and-plugin-updates'),
				'get'    => 'general', /*$_GET variable*/
				'action' => 'mpsum_admin_tab_dashboard' /* action variable in do_action */
			);

			$tabs[] = array(
				'url'    => add_query_arg(array( 'tab' => 'plugins' ), self::get_url()), /* URL to the tab */
				'label'  => esc_html__('Plugins', 'stops-core-theme-and-plugin-updates'),
				'get'    => 'plugins', /*$_GET variable*/
				'action' => 'mpsum_admin_tab_plugins' /* action variable in do_action */
			);
			$tabs[] = array(
				'url'    => add_query_arg(array( 'tab' => 'themes' ), self::get_url()), /* URL to the tab */
				'label'  => esc_html__('Themes', 'stops-core-theme-and-plugin-updates'),
				'get'    => 'themes', /*$_GET variable*/
				'action' => 'mpsum_admin_tab_themes' /* action variable in do_action */
			);
			$tabs[] = array(
				'url'    => add_query_arg(array( 'tab' => 'logs' ), self::get_url()), /* URL to the tab */
				'label'  => esc_html__('Logs', 'stops-core-theme-and-plugin-updates'),
				'get'    => 'logs', /*$_GET variable*/
				'action' => 'mpsum_admin_tab_logs' /* action variable in do_action */
			);
			$tabs[] = array(
				'url'    => add_query_arg(array( 'tab' => 'advanced' ), self::get_url()), /* URL to the tab */
				'label'  => esc_html__('Advanced', 'stops-core-theme-and-plugin-updates'),
				'get'    => 'advanced', /*$_GET variable*/
				'action' => 'mpsum_admin_tab_advanced' /* action variable in do_action */
			);
			if (!Easy_Updates_Manager()->is_premium()) {
				$tabs[] = array(
					'url'    => add_query_arg(array( 'tab' => 'premium' ), self::get_url()), /* URL to the tab */
					'label'  => esc_html__('Premium', 'stops-core-theme-and-plugin-updates'),
					'get'    => 'premium', /*$_GET variable*/
					'action' => 'mpsum_admin_tab_premium' /* action variable in do_action */
				);
			}
			$tabs_count = count($tabs);
			if ($tabs && !empty($tabs)) {
				$tab_html = '<h2 class="nav-tab-wrapper">';
				$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
				$is_tab_match = false;
				if ('general' == $active_tab) {
					$active_tab = 'general';
				} else {
					foreach ($tabs as $tab) {
						$tab_get = isset($tab['get']) ? $tab['get'] : '';
						if ($active_tab === $tab_get) {
							$is_tab_match = true;
						}
					}
					if (!$is_tab_match) {
						$active_tab = 'general';
					}
				}
				$do_action = false;
				foreach ($tabs as $tab) {
					$classes = array( 'nav-tab' );
					$tab_get = isset($tab['get']) ? $tab['get'] : '';
					if ($active_tab == $tab_get) {
						$classes[] = 'nav-tab-active';
						$do_action = isset($tab['action']) ? $tab['action'] : false;
					} elseif (!$is_tab_match && 'general' === $tab_get) {
						$classes[] = 'nav-tab-active';
						$do_action = isset($tab['action']) ? $tab['action'] : false;
					}
					$tab_url = isset($tab['url']) ? $tab['url'] : '';
					$tab_html .= sprintf('<a href="%s" class="%s" id="eum-%s">%s</a>', esc_url($tab_url), esc_attr(implode(' ', $classes)), esc_attr($tab_get), esc_html($tab['label']));
				}
				$tab_html .= '</h2>';
				if ($tabs_count > 1) {
					echo $tab_html;
				}
				if ($do_action) {

					/**
					 * Perform a tab action.
					 *
					 * Perform a tab action.
					 *
					 * @since 5.0.0
					 *
					 * @param string $action Can be mpsum_admin_tab_main, mpsum_admin_tab_plugins, mpsum_admin_tab_themes, and mpsum_admin_tab_advanced.
					 */
					do_action($do_action);
				}
			}
			?>

		</div><!-- .wrap -->
		<?php
	} //end output_admin_interface

	/**
	 * Adds plugin settings page link to plugin links in WordPress Dashboard Plugins Page
	 *
	 * @since 5.0.0
	 * @access public
	 * @see __construct
	 * @param array $settings Uses $prefix . "plugin_action_links_$plugin_file" action
	 * @return array Array of settings
	 */
	public function plugin_settings_link( $settings ) {
		$admin_anchor = sprintf('<a href="%s">%s</a>', esc_url($this->get_url()), esc_html__('Configure', 'stops-core-theme-and-plugin-updates'));

		if (! is_array($settings)) {
			return array( $admin_anchor );
		} else {
			return array_merge(array( $admin_anchor ), $settings);
		}
	}

	/**
	 * Adds Easy Updates Manager option to the admin bar
	 *
	 * @since 8.0.1
	 * @access public
	 * @see init
	 */
	public function add_networkadmin_page() {
		global $wp_admin_bar;

		if (!is_object($wp_admin_bar) || !is_super_admin() || !function_exists('is_admin_bar_showing') || !is_admin_bar_showing()) {
			return;
		}

		$wp_admin_bar->add_node(array(
			'parent' => 'network-admin',
			'id' => 'eum-admin-settings',
			'title' => __('Easy Updates Manager', 'stops-core-theme-and-plugin-updates'),
			'href' => self::get_url()
		));
	}
}
