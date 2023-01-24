<?php
if (!defined('ABSPATH')) die('No direct access.');
/**
 * Easy Updates Manager Plugins List Table class.
 *
 * @package WordPress
 * @subpackage MPSUM_List_Table
 * @since 5.0.0
 * @access private
 */
class MPSUM_Plugins_List_Table extends MPSUM_List_Table {

	private $tab = 'plugins';

	private $status = 'all';

	private $page = '1';

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct($args = array()) {

		parent::__construct(array(
			'singular' => 'plugin',
			'plural' => 'plugins',
			'screen' => isset($args['screen']) ? $args['screen'] : 'eum_plugins_tab',
			'ajax' => true
		));

		// Get plugins transient
		$this->plugins_transient = get_site_transient('update_plugins');

		if (isset($_REQUEST['action']) && 'eum_ajax' === $_REQUEST['action']) {
			$this->status = 'all';
			if (isset($_REQUEST['view']) && in_array($_REQUEST['view'], array('update_disabled', 'update_enabled', 'automatic'))) {
				$this->status = $_REQUEST['view'];
			}

			if (isset($_REQUEST['s']))
				$_SERVER['REQUEST_URI'] = add_query_arg('s', wp_unslash($_REQUEST['s']));

			$this->page = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : '1';
		} else {
			$this->status = 'all';
			if (isset($args['view']) && in_array($args['view'], array('update_disabled', 'update_enabled', 'automatic'))) {
				$this->status = $args['view'];
			}

			$this->page = isset($args['paged']) ? $args['paged'] : '1';
		}
	}

	/**
	 * Get table classes
	 *
	 * @return array
	 */
	protected function get_table_classes() {
		return array('widefat', $this->_args['plural']);
	}

	/**
	 * Ajax user capability check
	 *
	 * @return boolean
	 */
	public function ajax_user_can() {
		return current_user_can('activate_plugins');
	}

	/**
	 * Prepares plugin items to display
	 *
	 * Prepares plugin items by setting pagination variables, order, filter
	 */
	public function prepare_items() {

		global $orderby, $order, $totals;
		$order = 'DESC';
		$orderby = 'Name';

		/**
		 * Filter the full array of plugins to list in the Plugins list table.
		 *
		 * @since 3.0.0
		 *
		 * @see get_plugins()
		 *
		 * @param array $plugins An array of plugins to display in the list table.
		 */
		if (!function_exists('get_plugins')) {
			include_once ABSPATH . "wp-admin/includes/plugin.php";
		}

		$plugins = array(
			'all' => apply_filters('all_plugins', get_plugins()),
			'update_enabled' => array(),
			'update_disabled' => array(),
			'automatic' => array()
		);

		$plugin_info = get_site_transient('update_plugins');

		$plugin_options = MPSUM_Updates_Manager::get_options('plugins');
		$plugin_automatic_options = MPSUM_Updates_Manager::get_options('plugins_automatic');
		foreach ((array) $plugins['all'] as $plugin_file => $plugin_data) {
			// Extra info if known. array_merge() ensures $plugin_data has precedence if keys collide.
			if (isset($plugin_info->response[$plugin_file])) {
				$plugins['all'][$plugin_file] = $plugin_data = array_merge((array) $plugin_info->response[$plugin_file], $plugin_data);
			} elseif (isset($plugin_info->no_update[$plugin_file])) {
				$plugins['all'][$plugin_file] = $plugin_data = array_merge((array) $plugin_info->no_update[$plugin_file], $plugin_data);
			}


			if (false !== $key = array_search($plugin_file, $plugin_options)) {// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- $key is unused
				$plugins['update_disabled'][$plugin_file] = $plugin_data;
			} else {
				$plugins['update_enabled'][$plugin_file] = $plugin_data;
				if (in_array($plugin_file, $plugin_automatic_options)) {
					$plugins['automatic'][$plugin_file] = $plugin_data;
				}
			}
		}

		$totals = array();
		foreach ($plugins as $type => $list)
			$totals[$type] = count($list);

		// Disable the automatic updates view
		$core_options = MPSUM_Updates_Manager::get_options('core');
		if (isset($core_options['plugin_updates']) && 'individual' !== $core_options['plugin_updates']) {
			unset($totals['automatic']);
			$plugins['automatic'] = array();
		}

		if (empty($plugins[$this->status]))
			$this->status = 'all';

		$this->items = array();
		foreach ($plugins[$this->status] as $plugin_file => $plugin_data) {
			// Translate, Don't Apply Markup, Sanitize HTML
			remove_action("after_plugin_row_$plugin_file", 'wp_plugin_update_row', 10, 2);
			$this->items[$plugin_file] = _get_plugin_data_markup_translate($plugin_file, $plugin_data, false, true);
		}

		$total_this_page = $totals[$this->status];

		// Get plugins per page
		$user_id = get_current_user_id();
		$plugins_per_page = get_user_meta($user_id, 'mpsum_items_per_page', true);
		if (! is_numeric($plugins_per_page)) {
			$plugins_per_page = 100;
		}

		$start = ($this->page - 1) * $plugins_per_page;

		if ($total_this_page > $plugins_per_page)
			$this->items = array_slice($this->items, $start, $plugins_per_page);

		$this->set_pagination_args(array(
			'total_items' => $total_this_page,
			'per_page' => $plugins_per_page,
			'total_pages'	=> ceil($total_this_page / $plugins_per_page),
			// Set plugin status value (useful for AJAX)
			'view'	=> $this->status,
			'tab' => $this->tab,
			'paged' => $this->page
		));
	}

	/**
	 * Captures response content of ajax call and returns it
	 */
	public function ajax_response() {

		$this->prepare_items();
		extract($this->_args);
		extract($this->_pagination_args, EXTR_SKIP);

		ob_start();
		$this->views();
		$views = ob_get_clean();

		ob_start();
		if (!empty($_REQUEST['no_placeholder'])) {
			$this->display_rows();
		} else {
			$this->display_rows_or_placeholder();
		}
		$rows = ob_get_clean();

		// We don't have to update column header, but may be needed later, if sorting is introduced
		ob_start();
		$this->print_column_headers();
		$headers = ob_get_clean();

		ob_start();
		$this->pagination('top');
		$pagination_top = ob_get_clean();

		ob_start();
		$this->pagination('bottom');
		$pagination_bottom = ob_get_clean();
		$response = array();
		$response['views'] = array($views);
		$response['rows'] = array($rows);
		$response['pagination']['top'] = $pagination_top;
		$response['pagination']['bottom'] = $pagination_bottom;
		$response['headers'] = $headers;

		// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable -- Both $total_items and $total_pages can be ignored as they are extracted as part of $this->_pagination_args on line 186

		if (isset($total_items)) {
			$response['total_items_i18n'] = sprintf(_n('1 plugin', '%s plugins', $total_items), number_format_i18n($total_items));
		}

		if (isset($total_pages)) {
			$response['total_pages'] = $total_pages;
			$response['total_pages_i18n'] = number_format_i18n($total_pages);
		}

		// phpcs:enable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

		wp_send_json($response);
	}

	/**
	 * Call back function for search functionality
	 *
	 * @static string $term Term to search
	 * @param array $plugin Plugin name
	 * @return boolean Returns true if term found, otherwise false
	 */
	public function _search_callback($plugin) {
		static $term;
		if (is_null($term))
			$term = wp_unslash($_REQUEST['s']);

		foreach ($plugin as $value) {
			if (false !== stripos(strip_tags($value), $term)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Callback order
	 *
	 * @global string $orderby
	 * @global string $order
	 * @param array $plugin_a Plugin A
	 * @param array $plugin_b Plugin B
	 * @return int
	 */
	public function _order_callback($plugin_a, $plugin_b) {
		global $orderby, $order;

		$a = $plugin_a[$orderby];
		$b = $plugin_b[$orderby];

		if ($a == $b)
			return 0;

		if ('DESC' == $order) {
			return ($a < $b) ? 1 : -1;
		} else {
			return ($a < $b) ? -1 : 1;
		}
	}

	/**
	 * No items
	 *
	 * @return void
	 */
	public function no_items() {
		global $plugins;

		if (!empty($plugins['all'])) {
			_e('No plugins found.', 'stops-core-theme-and-plugin-updates');
		} else {
			_e('You do not appear to have any plugins available at this time.', 'stops-core-theme-and-plugin-updates');
		}
	}

		/**
		 * Get columns
		 *
		 * @return array
		 */
	public function get_columns() {
		return array(
			'cb'          => !in_array($this->status, array('mustuse', 'dropins')) ? '<input type="checkbox" />' : '',
			'name'        => __('Plugin', 'stops-core-theme-and-plugin-updates'),
			'description' => __('Description', 'stops-core-theme-and-plugin-updates'),
		);
	}

	/**
	 * Get sotable columns
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array();
	}

	/**
	 * Get views
	 *
	 * @return array
	 */
	protected function get_views() {
		global $totals;

		$status_links = array();
		foreach ($totals as $type => $count) {
			if (!$count)
				continue;

			switch ($type) {
				case 'all':
					$text = _nx('All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $count, 'plugins');
					break;
				case 'update_enabled':
					$text = _n('Updates enabled <span class="count">(%s)</span>', 'Updates enabled <span class="count">(%s)</span>', $count, 'stops-core-theme-and-plugin-updates');
					break;
				case 'update_disabled':
					$text = _n('Updates disabled <span class="count">(%s)</span>', 'Updates disabled <span class="count">(%s)</span>', $count, 'stops-core-theme-and-plugin-updates');
					break;
				case 'automatic':
					$text = _n('Automatic updates <span class="count">(%s)</span>', 'Automatic updates <span class="count">(%s)</span>', $count, 'stops-core-theme-and-plugin-updates');
					break;
			}

			if ('search' != $type) {
				$plugin_url = MPSUM_Admin::get_url();
				$query_args = array(
					'tab' => $this->tab,
					'view' => $type
				);
				$status_links[$type] = sprintf("<a href='%s' data-view='%s' %s>%s</a>",
					add_query_arg($query_args, $plugin_url),
					$this->status,
					($type == $this->status) ? ' class="current"' : '',
					sprintf($text, number_format_i18n($count))
				);
			}
		}

		return $status_links;
	}

	/**
	 * Get bulk actions
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		$actions = array();

		$actions['allow-update-selected'] = esc_html__('Plugin updates on', 'stops-core-theme-and-plugin-updates');
		$actions['disallow-update-selected'] = esc_html__('Plugin updates off', 'stops-core-theme-and-plugin-updates');
		$core_options = MPSUM_Updates_Manager::get_options('core');
		if (isset($core_options['plugin_updates']) && 'individual' == $core_options['plugin_updates']) {
			$actions['allow-automatic-selected'] = esc_html__('Automatic updates on', 'stops-core-theme-and-plugin-updates');
			$actions['disallow-automatic-selected'] = esc_html__('Automatic updates off', 'stops-core-theme-and-plugin-updates');
		}

		return $actions;
	}

	/**
	 * Bulk action
	 *
	 * @param string $which Specify which bulk action
	 * @return null
	 */
	public function bulk_actions($which = '') {

		if (in_array($this->status, array('mustuse', 'dropins')))
			return;

		parent::bulk_actions($which);
	}

	/**
	 * Extra table Nav
	 *
	 * @param string $which Specify which table nav
	 * @return null
	 */
	protected function extra_tablenav($which) {

		if (! in_array($this->status, array('recently_activated', 'mustuse', 'dropins')))
			return;

		echo '<div class="alignleft actions">';

		if (! $this->screen->in_admin('network') && 'recently_activated' == $this->status) {
			submit_button(__('Clear list', 'stops-core-theme-and-plugin-updates'), 'button', 'clear-recent-list', false);
		} elseif ('top' == $which && 'mustuse' == $this->status) {
			echo '<p>' .
				sprintf(
					__('Files in the %s directory are executed automatically.', 'stops-core-theme-and-plugin-updates'),
					'<code>' . str_replace(ABSPATH, '/', WPMU_PLUGIN_DIR) . '</code>'
				) .
				'</p>';
		} elseif ('top' == $which && 'dropins' == $this->status) {
			echo '<p>' .
				sprintf(
					__('Drop-ins are advanced plugins in the %s directory that replace WordPress functionality when present.', 'stops-core-theme-and-plugin-updates'),
					'<code>' . str_replace(ABSPATH, '', WP_CONTENT_DIR) . '</code>'
				) .
				'</p>';
		}

		echo '</div>';
	}

	/**
	 * Currect action
	 *
	 * @return string
	 */
	public function current_action() {
		if (isset($_POST['clear-recent-list']))
			return 'clear-recent-list';

		return parent::current_action();
	}

	/**
	 * Display rows
	 *
	 * @return void
	 */
	public function display_rows() {

		if (is_multisite() && ! $this->screen->in_admin('network') && in_array($this->status, array('mustuse', 'dropins')))
			return;

		foreach ($this->items as $plugin_file => $plugin_data)
			$this->single_row(array($plugin_file, $plugin_data));
	}

	/**
	 * Single row
	 *
	 * @global string $s
	 * @global array $totals
	 * @param array $item Single row item
	 */
	public function single_row($item) {

		list($plugin_file, $plugin_data) = $item;

		/**
		 * Filter the action links that show up under each plugin row.
		 *
		 * @since 5.0.0
		 *
		 * @param string    Relative plugin file path
		 * @param array  $plugin_data An array of plugin data.
		 * @param string   $this->status     Status of the plugin.
		 */
		$class = 'active';
		$plugin_options = MPSUM_Updates_Manager::get_options('plugins');
		if (false !== $key = array_search($plugin_file, $plugin_options)) {
			$class = 'inactive';
		}
		$checkbox_id = "checkbox_" . md5($plugin_data['Name']);
		$checkbox = "<label class='screen-reader-text' for='" . $checkbox_id . "' >" . sprintf(__('Select %s', 'stops-core-theme-and-plugin-updates'), $plugin_data['Name']) . "</label>"
				. "<input type='checkbox' name='checked[]' value='" . esc_attr($plugin_file) . "' id='" . $checkbox_id . "' />";
		$description = '<p>' . ($plugin_data['Description'] ? $plugin_data['Description'] : '&nbsp;') . '</p>';
		$plugin_name = $plugin_data['Name'];
		$plugin_slug = $item[0];

		$id = sanitize_title($plugin_name);

		echo "<tr id='$id' class='$class'>";

		list($columns, $hidden, $sortable, $primary) = $this->get_column_info();// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- THis can be ignored as it is a list and are used below

		foreach ($columns as $column_name => $column_display_name) {
			$style = '';
			if (in_array($column_name, $hidden))
				$style = ' style="display:none;"';

			switch ($column_name) {
				case 'cb':
					echo "<th scope='row' class='check-column'>$checkbox</th>";
					break;
				case 'name':
					echo "<td class='plugin-title'$style>";
					$icon = '<span class="dashicons dashicons-admin-plugins"></span>';
					$preferred_icons = array('svg', '1x', '2x', 'default');
					foreach ($preferred_icons as $preferred_icon) {
						if (isset($plugin_data['icons'][$preferred_icon]) && ! empty($plugin_data['icons'][$preferred_icon])) {
							$icon = '<img src="' . esc_url($plugin_data['icons'][$preferred_icon]) . '" alt="" />';
							break;
						}
					}
					echo $icon;
					echo '<div class="eum-plugins-name-actions">';
					echo "<h3 class='eum-plugins-name'>$plugin_name</h3>";
					echo '<div class="eum-plugins-wrapper">';
					printf('<h4>%s</h4>', esc_html__('Plugin updates', 'stops-core-theme-and-plugin-updates'));

					echo '<div class="toggle-wrapper toggle-wrapper-plugins">';

					$enable_class = $disable_class = '';
					$checked = 'false';
					$key = in_array($plugin_slug, $plugin_options);
					if (! $key) {
						$enable_class = 'eum-enabled eum-active';
						$checked = 'true';
					} else {
						$disable_class = 'eum-disabled eum-active';
					}

					printf('<input type="hidden" name="plugins[%s]" value="%s">',
						$plugin_slug,
						$checked
					);

					printf('<button aria-label="%s" class="eum-toggle-button eum-enabled %s" data-checked="%s" value="on">%s</button>',
						esc_attr__('Allow updates', 'stops-core-theme-and-plugin-updates'),
						esc_attr($enable_class),
						$plugin_slug,
						esc_html__('Allowed', 'stops-core-theme-and-plugin-updates')
					);

					printf('<button aria-label="%s" class="eum-toggle-button eum-disabled %s" data-checked="%s value="off">%s</button>',
						esc_attr__('Disallow updates', 'stops-core-theme-and-plugin-updates'),
						esc_attr($disable_class),
						$plugin_slug,
						esc_html__('Blocked', 'stops-core-theme-and-plugin-updates')
					);

					echo '</div></div>';

					// Automatic Link
					$plugin_automatic_options = MPSUM_Updates_Manager::get_options('plugins_automatic');
					$core_options = MPSUM_Updates_Manager::get_options('core');
					if (isset($core_options['plugin_updates']) && 'individual' == $core_options['plugin_updates']) {
						printf('<div class="eum-plugins-automatic-wrapper" %s>', ($key) ? 'style="display: none;"' : '');
						printf('<h4>%s</h4>', esc_html__('Automatic updates', 'stops-core-theme-and-plugin-updates'));
						echo '<div class="toggle-wrapper toggle-wrapper-plugins-automatic">';
						$enable_class = $disable_class = '';
						$checked = 'false';
						if (in_array($plugin_slug, $plugin_automatic_options)) {
							$enable_class = 'eum-active';
							$checked = 'true';
						} else {
							$disable_class = 'eum-active';
						}

						printf('<input type="hidden" name="plugins_automatic[%s]" value="%s">',
							$plugin_slug,
							$checked
						);

						printf('<button aria-label="%s" class="eum-toggle-button eum-enabled %s" data-checked="%s" value="on">%s</button>',
							esc_html__('Enable automatic updates', 'stops-core-theme-and-plugin-updates'),
							esc_attr($enable_class),
							$plugin_slug,
							esc_html__('On', 'stops-core-theme-and-plugin-updates')
						);

						printf('<button aria-label="%s" class="eum-toggle-button eum-disabled %s" data-checked="%s" value="off">%s</button>',
							esc_attr__('Enable automatic updates', 'stops-core-theme-and-plugin-updates'),
							esc_attr($disable_class),
							$plugin_slug,
							esc_html__('Off', 'stops-core-theme-and-plugin-updates')
						);

						echo '</div></div>';
					}
					echo '</div>';
					echo "</td>";
					break;
				case 'description':
					echo "<td class='column-description desc'$style>
						<div class='plugin-description'>$description</div>
						<div class='$class second plugin-version-author-uri'>";

					$plugin_meta = array();
					if (!empty($plugin_data['Version']))
						$plugin_meta[] = sprintf(__('Version %s', 'stops-core-theme-and-plugin-updates'), $plugin_data['Version']);
					if (!empty($plugin_data['Author'])) {
						$author = $plugin_data['Author'];
						if (!empty($plugin_data['AuthorURI']))
							$author = '<a href="' . $plugin_data['AuthorURI'] . '">' . $plugin_data['Author'] . '</a>';
						$plugin_meta[] = sprintf(__('By %s', 'stops-core-theme-and-plugin-updates'), $author);
					}

					// Details link using API info, if available
					if (isset($plugin_data['slug']) && current_user_can('install_plugins')) {
						$plugin_meta[] = sprintf('<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s" data-title="%s">%s</a>',
							esc_url(network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $plugin_data['slug'] .
								'&eum_action=EUM_modal&TB_iframe=true&width=600&height=550')),
							esc_attr(sprintf(__('More information about %s', 'stops-core-theme-and-plugin-updates'), $plugin_name)),
							esc_attr($plugin_name),
							__('View details', 'stops-core-theme-and-plugin-updates')
						);
					} elseif (! empty($plugin_data['PluginURI'])) {
						$plugin_meta[] = sprintf('<a href="%s">%s</a>',
							esc_url($plugin_data['PluginURI']),
							__('Visit plugin site', 'stops-core-theme-and-plugin-updates')
						);
					}


					/**
					 * Filter the array of row meta for each plugin in the Plugins list table.
					 *
					 * @since 2.8.0
					 *
					 * @param array  $plugin_meta An array of the plugin's metadata,
					 *                            including the version, author,
					 *                            author URI, and plugin URI.
					 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
					 * @param array  $plugin_data An array of plugin data.
					 * @param string $this->status      Status of the plugin. Defaults are 'All', 'Active',
					 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
					 *                            'Drop-ins', 'Search'.
					 */
					$plugin_meta = apply_filters('plugin_row_meta', $plugin_meta, $plugin_file, $plugin_data, $this->status);
					echo implode(' | ', $plugin_meta);

					// Premium only - Check if plugin has been removed from the repo
					if (MPSUM_Updates_Manager::get_instance()->is_premium()) {
						MPSUM_Check_Plugins_Removed::get_instance()->check_if_plugin_removed($plugin_file);
					}

					// Show active status for blogs
					if (is_multisite()) {
						if (is_plugin_active_for_network($plugin_file)) {
							printf('<div class="mpsum-success mpsum-bold">%s</div>', esc_html__('This plugin is active for your network.', 'stops-core-theme-and-plugin-updates'));
						} else {
							printf('<div class="mpsum-notice mpsum-regular"><a href="#" data-plugin-file="%s" class="eum-list-plugins-action">%s</a><div class="eum-list-plugins"></div></div>', esc_attr($plugin_file), esc_html__('View all sites that have this plugin installed.', 'stops-core-theme-and-plugin-updates'));
						}
					} else {
						if (is_plugin_active($plugin_file)) {
							printf('<div class="mpsum-success mpsum-bold">%s</div>', esc_html__('This plugin is active for your site.', 'stops-core-theme-and-plugin-updates'));
						} else {
							printf('<div class="mpsum-error mpsum-bold">%s</div>', esc_html__('This plugin is inactive for your site. Consider removing it.', 'stops-core-theme-and-plugin-updates'));
						}
					}

					// Show safe mode options if enabled
					if (MPSUM_Updates_Manager::get_instance()->is_premium()) {
						$core_options = MPSUM_Updates_Manager::get_options('core');
						if (isset($core_options['safe_mode']) && 'on' === $core_options['safe_mode']) {
							$safe_mode_instance = MPSUM_Safe_Mode::get_instance();
							$plugin_object = $safe_mode_instance->perform_api_check($plugin_file);
							$safe_mode_instance->maybe_output_check_safe_mode($plugin_object);
						}
					}
					echo "</div></td>";
					break;
				default:
					echo "<td class='$column_name column-$column_name'$style>";

					/**
					 * Fires inside each custom column of the Plugins list table.
					 *
					 * @since 3.1.0
					 *
					 * @param string $column_name Name of the column.
					 * @param string $plugin_file Path to the plugin file.
					 * @param array  $plugin_data An array of plugin data.
					 */
					do_action('manage_plugins_custom_column', $column_name, $plugin_file, $plugin_data);
					echo "</td>";
			}
		}

		echo "</tr>";

	}
}
