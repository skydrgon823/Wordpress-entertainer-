<?php
if (!defined('ABSPATH')) die('No direct access.');
/**
 * Easy Updates Manager Themes List Table class.
 *
 * @package Easy Updates Manager
 * @subpackage MPSUM_List_Table
 * @since 5.0.0
 * @access private
 */
class MPSUM_Themes_List_Table extends MPSUM_List_Table {

	public $site_id;

	public $is_site_themes;

	private $tab = 'themes';

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
			'singular' => 'theme',
			'plural' => 'themes',
			'screen' => isset($args['screen']) ? $args['screen'] : 'eum_themes_tab',
			'ajax' => true
		));

		if (isset($_REQUEST['action']) && 'eum_ajax' === $_REQUEST['action']) {
			$this->status = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'all';
			if (!in_array($this->status, array('all', 'update_disabled', 'update_enabled', 'automatic')))
				$this->status = 'all';

			$this->page = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : '1';
		} else {
			$this->status = isset($args['view']) ? $args['view'] : 'all';
			if (!in_array($this->status, array('all', 'update_disabled', 'update_enabled', 'automatic')))
				$this->status = 'all';

			$this->page = isset($args['paged']) ? $args['paged'] : 1;
		}

		$this->is_site_themes = ('site-themes-network' == $this->screen->id) ? true : false;

		if ($this->is_site_themes)
			$this->site_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	}

	/**
	 * Get table classes
	 *
	 * @return array
	 */
	protected function get_table_classes() {
		// todo: remove and add CSS for .themes
		return array('widefat', 'plugins');
	}

	/**
	 * Ajax user capability check
	 *
	 * @return boolean
	 */
	public function ajax_user_can() {
		if ($this->is_site_themes) {
			return current_user_can('manage_sites');
		} else {
			return current_user_can('manage_network_themes');
		}
	}

	/**
	 * Prepares theme items to display
	 *
	 * Prepares theme items by setting pagination variables, order, filter
	 */
	public function prepare_items() {
		global $totals;
		$order = 'DESC';
		$orderby = 'Name';

		$themes = array(
			/**
			 * Filter the full array of WP_Theme objects to list in the Multisite
			 * themes list table.
			 *
			 * @since 3.1.0
			 *
			 * @param array $all An array of WP_Theme objects to display in the list table.
			 */
			'all' => apply_filters('all_themes', wp_get_themes()),
			'update_enabled' => array(),
			'update_disabled' => array(),
			'automatic' => array()

		);

		$theme_options = MPSUM_Updates_Manager::get_options('themes');
		$theme_automatic_options = MPSUM_Updates_Manager::get_options('themes_automatic');
		foreach ((array) $themes['all'] as $theme => $theme_data) {
			if (false !== $key = array_search($theme, $theme_options)) {// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- Need to double check this one as I cant see Key being used and wonder if it needs to be $theme?
				$themes['update_disabled'][$theme] = $theme_data;
			} else {
				$themes['update_enabled'][$theme] = $theme_data;
				if (in_array($theme, $theme_automatic_options)) {
					$themes['automatic'][$theme] = $theme_data;
				}
			}
		}

		$totals = array();

		foreach ($themes as $type => $list)
			$totals[$type] = count($list);

		// Disable the automatic updates view
		$core_options = MPSUM_Updates_Manager::get_options('core');
		if (isset($core_options['theme_updates']) && 'individual' !== $core_options['theme_updates']) {
			unset($totals['automatic']);
			$themes['automatic'] = array();
		}

		if (empty($themes[$this->status]))
			$this->status = 'all';

		$this->items = $themes[$this->status];
		WP_Theme::sort_by_name($this->items);

		$this->has_items = ! empty($themes['all']);
		$total_this_page = $totals[$this->status];

		if ($orderby) {
			$orderby = ucfirst($orderby);
			$order = strtoupper($order);

			if ('Name' == $orderby) {
				if ('ASC' == $order)
					$this->items = array_reverse($this->items);
			} else {
				uasort($this->items, array($this, '_order_callback'));
			}
		}

		// Get themes per page
		$user_id = get_current_user_id();
		$themes_per_page = get_user_meta($user_id, 'mpsum_items_per_page', true);
		if (! is_numeric($themes_per_page)) {
			$themes_per_page = 100;
		}

		$start = ($this->page - 1) * $themes_per_page;

		if ($total_this_page > $themes_per_page)
			$this->items = array_slice($this->items, $start, $themes_per_page, true);

		$this->set_pagination_args(array(
			'total_items' => $total_this_page,
			'per_page' => $themes_per_page,
			'total_pages' => ceil($total_this_page / $themes_per_page),
			'view' => $this->status,
			'tab' => $this->tab,
			'paged' => $this->page
		));
	}

	/**
	 * Search Callback
	 *
	 * @staticvar string $term
	 * @param string $theme WP Theme
	 * @return bool
	 */
	public function _search_callback($theme) {
		static $term;
		if (is_null($term))
			$term = wp_unslash($_REQUEST['s']);

		foreach (array('Name', 'Description', 'Author', 'Author', 'AuthorURI') as $field) {
			// Don't mark up; Do translate.
			if (false !== stripos($theme->display($field, false, true), $term))
				return true;
		}

		if (false !== stripos($theme->get_stylesheet(), $term))
			return true;

		if (false !== stripos($theme->get_template(), $term))
			return true;

		return false;
	}

	/**
	 * Order callback
	 * Not used by any core columns.
	 *
	 * @global string $orderby
	 * @global string $order
	 * @param array $theme_a Theme A
	 * @param array $theme_b Theme B
	 * @return int
	 */
	public function _order_callback($theme_a, $theme_b) {
		global $orderby, $order;

		$a = $theme_a[$orderby];
		$b = $theme_b[$orderby];

		if ($a == $b)
			return 0;

		if ('DESC' == $order) {
			return ($a < $b) ? 1 : -1;
		} else {
			return ($a < $b) ? -1 : 1;
		}
	}

	/**
	 * No Items
	 *
	 * @return void
	 */
	public function no_items() {
		if (! $this->has_items) {
			_e('No themes found.', 'stops-core-theme-and-plugin-updates');
		} else {
			_e('You do not appear to have any themes available at this time.', 'stops-core-theme-and-plugin-updates');
		}
	}

	/**
	 * Get columns
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'          => '<input type="checkbox" />',
			'name'        => __('Theme', 'stops-core-theme-and-plugin-updates'),
			'description' => __('Description', 'stops-core-theme-and-plugin-updates'),
		);
	}

	/**
	 * Get sortable columns
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
					$text = _nx('All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $count, 'stops-core-theme-and-plugin-updates');
					break;
				case 'update_disabled':
					$text = _n('Updates disabled <span class="count">(%s)</span>', 'Updates Disabled <span class="count">(%s)</span>', $count, 'stops-core-theme-and-plugin-updates');
					break;
				case 'update_enabled':
					$text = _n('Updates enabled <span class="count">(%s)</span>', 'Updates enabled <span class="count">(%s)</span>', $count, 'stops-core-theme-and-plugin-updates');
					break;
				case 'automatic':
					$text = _n('Automatic updates <span class="count">(%s)</span>', 'Automatic updates <span class="count">(%s)</span>', $count, 'stops-core-theme-and-plugin-updates');
					break;
			}

			if ('search' != $type) {
				$theme_url = MPSUM_Admin::get_url();
				$query_args = array(
					'tab' => $this->tab,
					'view' => $type
				);

				$status_links[$type] = sprintf("<a href='%s' data-view='%s' %s>%s</a>",
					add_query_arg($query_args, $theme_url),
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

		$actions['allow-update-selected'] = esc_html__('Theme updates on', 'stops-core-theme-and-plugin-updates');
		$actions['disallow-update-selected'] = esc_html__('Theme updates off', 'stops-core-theme-and-plugin-updates');
		$core_options = MPSUM_Updates_Manager::get_options('core');
		if (isset($core_options['theme_updates']) && 'individual' == $core_options['theme_updates']) {
			$actions['allow-automatic-selected'] = esc_html__('Automatic updates on', 'stops-core-theme-and-plugin-updates');
			$actions['disallow-automatic-selected'] = esc_html__('Automatic updates off', 'stops-core-theme-and-plugin-updates');
		}

		return $actions;
	}

	/**
	 * Display rows
	 *
	 * @return void
	 */
	public function display_rows() {
		foreach ($this->items as $theme)
			$this->single_row($theme);
	}

	/**
	 * Single row theme
	 *
	 * @param object $theme The specified WP_Theme object
	 */
	public function single_row($theme) {
		$this->status = 'all';
		$stylesheet = $theme->get_stylesheet();
		remove_action("after_theme_row_$stylesheet", 'wp_theme_update_row', 10, 2);

		/**
		* Filter the action links that show up under each theme row.
		*
		* @since 5.0.0
		*
		* @param array    Array of action links
		* @param WP_Theme   $theme WP_Theme object
		* @param string   $this->status     Status of the theme.
		*/
		$checkbox_id = "checkbox_" . md5($theme->get('Name'));
		$checkbox = "<input type='checkbox' name='checked[]' value='" . esc_attr($stylesheet) . "' id='" . $checkbox_id . "' /><label class='screen-reader-text' for='" . $checkbox_id . "' >" . __('Select', 'stops-core-theme-and-plugin-updates') . " " . $theme->display('Name') . "</label>";

		$id = sanitize_html_class($theme->get_stylesheet());
		$class = 'active';
		$theme_options = MPSUM_Updates_Manager::get_options('themes');
		if (false !== $key = array_search($stylesheet, $theme_options)) {
			$class = 'inactive';
		}
		echo "<tr id='$id' class='$class'>";

		list($columns, $hidden) = $this->get_column_info();

		foreach ($columns as $column_name => $column_display_name) {
			$style = '';
			if (in_array($column_name, $hidden))
				$style = ' style="display:none;"';

			switch ($column_name) {
				case 'cb':
					echo "<th scope='row' class='check-column'>$checkbox</th>";
					break;
				case 'name':
					echo "<td class='theme-title'$style>";
					echo "<img src='" . esc_url($theme->get_screenshot()) . "' width='85' height='64' class='updates-table-screenshot' alt='' />";
					echo '<div class="eum-themes-name-actions">';
					echo "<h3 class='eum-themes-name'>" . $theme->display('Name') . "</h3>";
					echo '<div class="eum-themes-wrapper">';
					printf('<h4>%s</h4>', esc_html__('Theme updates', 'stops-core-theme-and-plugin-updates'));

					echo '<div class="toggle-wrapper toggle-wrapper-themes">';

					$enable_class = $disable_class = '';
					$checked = 'false';
					$key = in_array($stylesheet, $theme_options);
					if (! $key) {
						$enable_class = 'eum-enabled eum-active';
						$checked = 'true';
					} else {
						$disable_class = 'eum-disabled eum-active';
					}

					printf('<input type="hidden" name="themes[%s]" value="%s">',
						$stylesheet,
						$checked
					);

					printf('<button aria-label="%s" class="eum-toggle-button eum-enabled %s" data-checked="%s">%s</button>',
						esc_attr__('Allow updates', 'stops-core-theme-and-plugin-updates'),
						esc_attr($enable_class),
						$stylesheet,
						esc_html__('Allowed', 'stops-core-theme-and-plugin-updates')
					);

					printf('<button aria-label="%s" class="eum-toggle-button eum-disabled %s" data-checked="%s">%s</button>',
						esc_attr__('Disallow updates', 'stops-core-theme-and-plugin-updates'),
						esc_attr($disable_class),
						$stylesheet,
						esc_html__('Blocked', 'stops-core-theme-and-plugin-updates')
					);

					echo '</div></div>';

					// Automatic Link
					$theme_automatic_options = MPSUM_Updates_Manager::get_options('themes_automatic');
					$core_options = MPSUM_Updates_Manager::get_options('core');
					if (isset($core_options['theme_updates']) && 'individual' == $core_options['theme_updates']) {
						printf('<div class="eum-themes-automatic-wrapper" %s>', ($key) ? 'style="display: none;"' : '');
						printf('<h4>%s</h4>', esc_html__('Automatic updates', 'stops-core-theme-and-plugin-updates'));
						echo '<div class="toggle-wrapper toggle-wrapper-themes-automatic">';
						$enable_class = $disable_class = '';
						if (in_array($stylesheet, $theme_automatic_options)) {
							$enable_class = 'eum-active';
							$checked = 'true';
						} else {
							$disable_class = 'eum-active';
							$checked = 'false';
						}

						printf('<input type="hidden" name="themes_automatic[%s]" value="%s">',
							$stylesheet,
							$checked
						);

						printf('<button aria-label="%s" class="eum-toggle-button eum-enabled %s" data-checked="%s">%s</button>',
							esc_html__('Enable automatic updates', 'stops-core-theme-and-plugin-updates'),
							esc_attr($enable_class),
							$stylesheet,
							esc_html__('On', 'stops-core-theme-and-plugin-updates')
						);

						printf('<button aria-label="%s" class="eum-toggle-button eum-disabled %s" data-checked="%s">%s</button>',
							esc_attr__('Enable automatic updates', 'stops-core-theme-and-plugin-updates'),
							esc_attr($disable_class),
							$stylesheet,
							esc_html__('Off', 'stops-core-theme-and-plugin-updates')
						);

						echo '</div></div>';
					}
					echo '</div>';
					echo "</td>";
					break;
				case 'description':
					echo "<td class='column-description desc'$style>";
					if ($theme->errors()) {
						$pre = 'broken' == $this->status ? __('Broken theme:', 'stops-core-theme-and-plugin-updates') . ' ' : '';
						echo '<p><strong class="attention">' . $pre . $theme->errors()->get_error_message() . '</strong></p>';
					}
					echo "<div class='theme-description'><p>" . $theme->display('Description') . "</p></div>
						<div class='second theme-version-author-uri'>";

					$theme_meta = array();

					if ($theme->get('Version'))
						$theme_meta[] = sprintf(__('Version %s', 'stops-core-theme-and-plugin-updates'), $theme->display('Version'));

					$theme_meta[] = sprintf(__('By %s', 'stops-core-theme-and-plugin-updates'), $theme->display('Author'));

					if ($theme->get('ThemeURI'))
						$theme_meta[] = '<a href="' . $theme->display('ThemeURI') . '" title="' . esc_attr__('Visit theme homepage', 'stops-core-theme-and-plugin-updates') . '">' . __('Visit theme site', 'stops-core-theme-and-plugin-updates') . '</a>';

					/**
					 * Filter the array of row meta for each theme in the Multisite themes
					 * list table.
					 *
					 * @since 3.1.0
					 *
					 * @param array    $theme_meta An array of the theme's metadata,
					 *                             including the version, author, and
					 *                             theme URI.
					 * @param string   $stylesheet Directory name of the theme.
					 * @param WP_Theme $theme      WP_Theme object.
					 * @param string   $this->status     Status of the theme.
					 */
					$theme_meta = apply_filters('theme_row_meta', $theme_meta, $stylesheet, $theme, $this->status);
					echo implode(' | ', $theme_meta);

					// Show active status for blogs
					if (is_multisite()) {
						$themes = wp_get_themes(array('allowed' => true));
						$is_allowed_theme = array();
						foreach ($themes as $style => $theme_data) {
							if ($style === $stylesheet) {
								$is_allowed_theme[] = $stylesheet;
							}
						}
						$is_allowed_stylesheet = false;
						foreach ($is_allowed_theme as $allowed_stylesheet) {
							if ($allowed_stylesheet === $stylesheet) {
								$is_allowed_stylesheet = true;
							}
						}
						if ($is_allowed_stylesheet) {
							printf('<div class="mpsum-success mpsum-regular"><a href="#" data-theme-file="%s" class="eum-list-themes-action">%s</a><div class="eum-list-themes"></div></div>', esc_attr($stylesheet), esc_html__('View all sites that have this theme installed.', 'stops-core-theme-and-plugin-updates'));
						} else {
							printf('<div class="mpsum-notice mpsum-regular">%s<br /><a href="#" data-theme-file="%s" class="eum-list-themes-action">%s</a><div class="eum-list-themes"></div></div>', esc_html__('This theme is not allowed to be activated on your network of sites, but may be enabled for specific sites.', 'stops-core-theme-and-plugin-updates'), esc_attr($stylesheet), esc_html__('View all sites that have this theme installed.', 'stops-core-theme-and-plugin-updates'));
						}
					} else {
						if (get_stylesheet() === $stylesheet) {
							printf('<div class="mpsum-success mpsum-regular">%s</div>', esc_html__('This theme is active for your site.', 'stops-core-theme-and-plugin-updates'));
						} else {
							printf('<div class="mpsum-error mpsum-bold">%s</div>', esc_html__('This theme is inactive for your site.', 'stops-core-theme-and-plugin-updates'));
						}
					}

					echo "</div></td>";
					break;

				default:
					echo "<td class='$column_name column-$column_name'$style>";

					/**
					 * Fires inside each custom column of the Multisite themes list table.
					 *
					 * @since 3.1.0
					 *
					 * @param string   $column_name Name of the column.
					 * @param string   $stylesheet  Directory name of the theme.
					 * @param WP_Theme $theme       Current WP_Theme object.
					 */
					do_action('manage_themes_custom_column', $column_name, $stylesheet, $theme);
					echo "</td>";
			}
		}

		echo "</tr>";

		if ($this->is_site_themes)
			remove_action("after_theme_row_$stylesheet", 'wp_theme_update_row');

		/**
		 * Fires after each row in the Multisite themes list table.
		 *
		 * @since 3.1.0
		 *
		 * @param string   $stylesheet Directory name of the theme.
		 * @param WP_Theme $theme      Current WP_Theme object.
		 * @param string   $this->status     Status of the theme.
		 */
		do_action('after_theme_row', $stylesheet, $theme, $this->status);

		/**
		 * Fires after each specific row in the Multisite themes list table.
		 *
		 * The dynamic portion of the hook name, `$stylesheet`, refers to the
		 * directory name of the theme, most often synonymous with the template
		 * name of the theme.
		 *
		 * @since 3.5.0
		 *
		 * @param string   $stylesheet Directory name of the theme.
		 * @param WP_Theme $theme      Current WP_Theme object.
		 * @param string   $this->status     Status of the theme.
		 */
		do_action("after_theme_row_$stylesheet", $stylesheet, $theme, $this->status);
	}

	/**
	 * Captures response of ajax calls and returns it
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

		// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable -- $total_items and $total_pages are being exc tracted on line 585

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
}
