<?php
/**
 * Disables all VCS updates.
 *
 * @package WordPress
 * @since 8.0.6
 *
 * Credit: https://github.com/cliffordp/tk-exclude-vcs-updates
 */
class MPSUM_Disable_VCS {

	/**
	 * Store the VCS variables.
	 *
	 * @var array $vcs
	 */
	private $vcs = array();

	/**
	 * Store a list of excluded plugins.
	 *
	 * @var array $excluded_plugins
	 */
	private $excluded_plugins = array();

	/**
	 * Store a list of excluded themes.
	 *
	 * @var array $excluded_themes
	 */
	private $excluded_themes = array();

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->vcs = array(
			'.git',
			'.hg', // Mercurial
			'.svn',
		);

		// Exclude plugins from VCS
		add_filter('site_transient_update_plugins', array($this, 'process_plugin_updates'), 100);
		add_filter('site_transient_update_themes', array($this, 'process_theme_updates'), 100);
		add_action('admin_notices', array($this, 'notice'), 5);
		add_action('network_admin_notices', array($this, 'notice'), 5);
		add_action('eum_plugins_tab_header', array($this, 'show_eum_plugins_tab_warning'));
		add_action('eum_themes_tab_header', array($this, 'show_eum_themes_tab_warning'));
	}

	/**
	 * Exclude themes under version control.
	 *
	 * @param stdClass $value
	 *
	 * @return stdClass
	 */
	public function process_theme_updates($value) {
		if (isset($value->response)) {
			foreach ($value->response as $theme_name => $theme_data) {
				$theme_location = trailingslashit(WP_CONTENT_DIR) . 'themes/' . trailingslashit($theme_name);

				foreach ($this->vcs as $vcs_name) {
					$find = trailingslashit($theme_location) . $vcs_name;
					if (file_exists($find)) {
						$theme = wp_get_theme($theme_name);
						$this->excluded_themes[] = $theme->get('Name');
						unset($value->response[$theme_name]);
						continue;
					}
				}
			}
		}
		return $value;
	}
	
	/**
	 * Show a VCS warning on the EUM plugins tab
	 */
	public function show_eum_plugins_tab_warning() {
		$this->excluded_plugins = array_unique($this->excluded_plugins);
		if (!empty($this->excluded_plugins)) {
			$plugin_list = sprintf('<strong>%s</strong>', esc_html(implode($this->excluded_plugins, ', ')));
		}
		if (empty($plugin_list)) {
			return;
		}
		?>
		<div class="notice notice-warning">
			<?php
			echo '<p>' . sprintf(esc_html__('The following plugins are under version control and will not be updated: %s', 'stops-core-theme-and-plugin-updates'), $plugin_list) . '</p>';
			?>
		</div>
		<?php
	}
	
	/**
	 * Show a VCS warning on the EUM themes tab
	 */
	public function show_eum_themes_tab_warning() {
		$this->excluded_themes = array_unique($this->excluded_themes);
		if (!empty($this->excluded_themes)) {
			$theme_list = sprintf('<strong>%s</strong>', esc_html(implode($this->excluded_themes, ', ')));
		}
		if (empty($theme_list)) {
			return;
		}
		?>
		<div class="notice notice-warning">
			<?php
			echo '<p>' . sprintf(esc_html__('The following themes are under version control and will not be updated: %s', 'stops-core-theme-and-plugin-updates'), $theme_list) . '</p>';
			?>
		</div>
		<?php
	}

	/**
	 * Exclude plugins under version control.
	 *
	 * @param stdClass $value
	 *
	 * @return stdClass
	 */
	public function process_plugin_updates($value) {
		if (isset($value->response) && defined('WP_PLUGIN_DIR')) {
			foreach ($value->response as $plugin_file => $plugin_data) {
				$plugin_dir_name = plugin_dir_path($plugin_file);
				$plugin_location = trailingslashit(WP_PLUGIN_DIR) . $plugin_dir_name;

				foreach ($this->vcs as $vcs_name) {
					$find = trailingslashit($plugin_location) . $vcs_name;
					if (file_exists($find)) {
						if (!function_exists('get_plugins')) {
							require_once ABSPATH . 'wp-admin/includes/plugin.php';
						}
						$plugin = get_plugins();
						foreach ($plugin as $plugin_key => $plugin_array) {
							if ($plugin_key === $plugin_data->plugin) {
								$this->excluded_plugins[] = $plugin_array['Name'];
								break;
							}
						}
						unset($value->response[$plugin_file]);
						continue;
					}
				}
			}
		}
		return $value;
	}

	/**
	 * Show a notice in the upgrade screen that plugins/themes are under version control.
	 */
	public function notice() {
		$current_screen = get_current_screen();
		if (empty($this->excluded_plugins) && empty($this->excluded_themes)) {
			return;
		}

		$this->excluded_plugins = array_unique($this->excluded_plugins);
		$this->excluded_themes  = array_unique($this->excluded_themes);

		if ('update-core' === $current_screen->base || 'update-core-network' === $current_screen->base) {
			$plugin_list = '';
			$theme_list  = '';

			if (!empty($this->excluded_plugins)) {
				$plugin_list = sprintf('<strong>%s</strong>', implode($this->excluded_plugins, ', '));
			}
			if (!empty($this->excluded_themes)) {
				$theme_list = sprintf('<strong>%s</strong>', implode($this->excluded_themes, ', '));
			}
			?>
			<div class="notice notice-warning">
				<?php
				if (!empty($plugin_list)) {
					echo '<p>' . sprintf(esc_html__('The following plugins are under version control and will not be updated: %s', 'stops-core-theme-and-plugin-updates'), $plugin_list) . '</p>';
				}
				?>
				<?php
				if (!empty($theme_list)) {
					echo '<p>' . sprintf(esc_html__('The following themes are under version control and will not be updated: %s', 'stops-core-theme-and-plugin-updates'), $theme_list) . '</p>';
				}
				?>
			</div>
			<?php
		}
	}
}
