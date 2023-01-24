<?php
if (!defined('ABSPATH')) die('No direct access.');
/**
 * Easy Updates Manager Logs List Table class.
 *
 * @package WordPress
 * @subpackage MPSUM_List_Table
 * @since 6.0.0
 * @access private
 */
class MPSUM_Logs_List_Table extends MPSUM_List_Table {

	private $tab = 'logs';

	private $action_type = '';

	private $order = 'DESC';

	private $type = '';

	private $url = '';

	private $month = '0';

	private $page = '1';

	private $status = 'all';

	private $is_search = false;

	private $search_term = '';

	private $date_start = '';

	private $date_end = '';

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @global object $post_type_object
	 * @global wpdb   $wpdb
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct($args = array()) {

		parent::__construct(array(
			'singular'=> 'log',
			'plural' => 'logs',
			'screen' => isset($args['screen']) ? $args['screen'] : 'eum_logs_tab',
			'ajax' => true
		));

		if (isset($_REQUEST['action']) && ('eum_ajax' === $_REQUEST['action'] || 'eum_export_logs' === $_REQUEST['action'] || 'eum_export_csv' === $_REQUEST['action'] || 'eum_export_json' === $_REQUEST['action'])) {
			$this->action_type = isset($_REQUEST['action_type']) ? $_REQUEST['action_type'] : 'all';

			$this->type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'all';
			if (isset($_REQUEST['m']) && strlen($_REQUEST['m']) > 4) {
				$this->month = $_REQUEST['m'];
			}
			// Format start date
			if (isset($_REQUEST['date_start'])) {
				if ('' !== $_REQUEST['date_start']) {
					$this->date_start = strtotime(sanitize_text_field($_REQUEST['date_start']));
					$this->date_start = date('Y-m-d H:i:s', $this->date_start);
				}
			}

			// Format end date
			if (isset($_REQUEST['date_end'])) {
				$this->date_end = strtotime(sanitize_text_field($_REQUEST['date_end']));
				$this->date_end = date('Y-m-d H:i:s', $this->date_end);
			}

			$this->url = add_query_arg(array('tab' => 'logs'), MPSUM_Admin::get_url());

			if (isset($_REQUEST['status']) && in_array($_REQUEST['status'], array('all', '2', '1', '0'))) {
				$this->status = $_REQUEST['status'];
			}

			$this->page = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : '1';
			$this->order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';

			if (isset($_REQUEST['view']) && 'search' == $_REQUEST['view']) {
				$this->is_search = true;
				$this->search_term = isset($_REQUEST['search_term']) ? $_REQUEST['search_term'] : $_REQUEST['term'];
			} else {
				$this->is_search = false;
				$this->search_term = '';
			}
		} else {
			$this->action_type = isset($args['action_type']) ? $args['action_type'] : 'all';
			$this->type = isset($args['type']) ? $args['type'] : 'all';
			if (isset($args['m']) && strlen($args['m']) > 4) {
				$this->month = $args['m'];
			}

			$this->url = add_query_arg(array('tab' => 'logs'), MPSUM_Admin::get_url());

			if (isset($args['status']) && in_array($args['status'], array('all', '2', '1', '0'))) {
				$this->status = $args['status'];
			}

			$this->page = isset($args['paged']) ? $args['paged'] : '1';
			$this->order = isset($args['order']) ? $args['order'] : 'DESC';
			if (isset($args['view']) && 'search' == $args['view']) {
				$this->is_search = true;
				$this->search_term = isset($args['search_term']) ? $args['search_term'] : $args['term'];
			} else {
				$this->is_search = false;
				$this->search_term = '';
			}
		}
	}

	/**
	 * Prepare items
	 *
	 * @return void
	 */
	public function prepare_items() {
		global $wpdb;

		$tablename = $wpdb->base_prefix . 'eum_logs';

		// Get logs per page
		$user_id = get_current_user_id();
		$per_page = get_user_meta($user_id, 'mpsum_items_per_page', true);
		if (! is_numeric($per_page)) {
			$per_page = 100;
		}

		// Show the last thousand records if exporting
		if (isset($_REQUEST['action']) && ('eum_export_logs' === $_REQUEST['action'] || 'eum_export_csv' === $_REQUEST['action'] || 'eum_export_json' === $_REQUEST['action'])) {
			$per_page = 1000;
		}

		$offset = ($this->page - 1) * $per_page;
		if ($this->is_search) {

			// Try to get username
			$maybe_user = false;
			if (is_numeric($this->search_term)) {
				$maybe_user = get_user_by('id', $this->search_term);
			} elseif (is_email($this->search_term)) {
				$maybe_user = get_user_by('email', $this->search_term);
			} else {
				$maybe_user = get_user_by('slug', $this->search_term);
				if (!$maybe_user) {
					$maybe_user = get_user_by('login', $this->search_term);
				}
			}

			// If no user, search the name field
			if (!$maybe_user) {
				$wild = '%';
				$select = "select log_id, user_id, name, type, version_from, version, action, status, date, notes from $tablename WHERE 1=1 AND name LIKE %s";
				$term = $wild . $this->search_term . $wild;
				$orderby = " order by log_id DESC";
				$limit = " limit %d,%d";

				// Calculate no. of logs separately, because filters may be on
				$query = $select . $orderby;
				$prepared_query = $wpdb->remove_placeholder_escape($wpdb->prepare($query, $term));
				$wpdb->get_results($prepared_query);
				$log_count = $wpdb->num_rows;

				// Now perform the real query
				$query = $select . $orderby . $limit;
				$query = $wpdb->remove_placeholder_escape($wpdb->prepare($query, $term, $offset, $per_page));
				$this->items = $wpdb->get_results($query);
			} else {
				$user_id = $maybe_user->ID;
				$select = "select log_id, user_id, name, type, version_from, version, action, status, date, notes from $tablename WHERE 1=1 AND user_id = %d";

				$orderby = " order by log_id DESC";
				$limit = " limit %d,%d";

				// Calculate no. of logs separately, because filters may be on
				$query = $select . $orderby;
				$wpdb->get_results($wpdb->prepare($query, $user_id));
				$log_count = $wpdb->num_rows;

				// Now perform the real query
				$query = $select . $orderby . $limit;
				$query = $wpdb->prepare($query, $user_id, $offset, $per_page);
				$this->items = $wpdb->get_results($query);
			}
		} else {
			$where = '';
			if (isset($this->month) && strlen($this->month) > 4) {
				$where .= $wpdb->prepare(" AND YEAR($tablename.date)=%d", substr($this->month, 0, 4));
				if (strlen($this->month) > 5) {
					$where .= $wpdb->prepare(" AND MONTH($tablename.date)=%d", substr($this->month, 4, 2));
				}
			}

			if (isset($this->date_start) && '' !== $this->date_start && isset($this->date_end) && '' !== $this->date_end) {
				$where .= $wpdb->prepare(" AND (date BETWEEN %s AND %s)", $this->date_start, $this->date_end);
			}

			if (isset($this->status) && 'all' !== $this->status) {
				$where .= $wpdb->prepare(" and $tablename.status = %d ", absint($this->status));
			}

			if (isset($this->action_type) && 'all' !== $this->action_type) {
				$where .= $wpdb->prepare(" and $tablename.action = %s ", sanitize_text_field($this->action_type));
			}

			if (isset($this->type) && 'all' !== $this->type) {
				$where .= $wpdb->prepare(" and $tablename.type = %s ", sanitize_text_field($this->type));
			}


			$select = "select log_id, user_id, name, type, version_from, version, action, status, date, notes from $tablename WHERE 1=1 ";
			$orderby = ' order by ' . sanitize_sql_orderby("log_id {$this->order}");
			$limit = " limit %d,%d";

			// Calculate no. of logs separately, because filters may be on
			$query = $select . $where . $orderby;
			$wpdb->get_results($query);

			$log_count = $wpdb->num_rows;

			$query = $select . $where . $orderby . $limit;
			$query = $wpdb->prepare($query, $offset, $per_page);
			$this->items = $wpdb->get_results($query);
		}




		/* -- Register the Columns -- */
		$this->_column_headers = array(
			$this->get_columns(),		// columns
			array(),			// hidden
			$this->get_sortable_columns(),	// sortable
		);

		$this->set_pagination_args(array(
			'total_items' => $log_count,
			'per_page'    => $per_page,
			'total_pages' => ceil($log_count / $per_page),
			'paged'       => isset($this->page) ? $this->page : '1',
			'status'      => $this->status,
			'tab'         => $this->tab,
			'action_type' => isset($this->action_type) ? $this->action_type : 'all',
			'type'        => isset($this->type) ? $this->type : 'bottom',
			'view'        => empty($this->is_search) ? 'all' : 'search',
			'term'        => empty($this->search_term) ? '' : $this->search_term
		));
	}

	/**
	 * Generate the table navigation above or below the table
	 *
	 * @since 8.0.1
	 * @access protected
	 * @param string $which Which extra table nav to use
	 */
	protected function display_tablenav($which) {
		if ('top' == $which) {
			wp_nonce_field('bulk-' . $this->_args['plural']);
		}

?>
	<div class="tablenav <?php echo esc_attr($which); ?>">

		<div class="alignleft actions bulkactions">
			<?php $this->bulk_actions($which); ?>
		</div>
<?php
		$this->extra_tablenav($which);
		if ('bottom' == $which) {
			$this->pagination($which);
		}
?>

	</div>
<?php
	}

	/**
	 * Display a monthly dropdown for filtering items
	 *
	 * @since 6.0.0
	 * @access protected
	 * @global wpdb      $wpdb
	 */
	protected function months_dropdown($post_type) {// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- Declaration of MPSUM_Logs_List_Table::months_dropdown should be compatible with MPSUM_List_Table::months_dropdown so we can leave $post_type as its needed
		global $wpdb, $wp_locale;
		$tablename = $wpdb->base_prefix . 'eum_logs';
		$query = "SELECT DISTINCT YEAR(date) AS year, MONTH(date) AS month FROM $tablename ORDER BY date DESC";

		$months = $wpdb->get_results($query);

		$month_count = count($months);
		if (!$month_count || (1 == $month_count && 0 == $months[0]->month))
			return;

		?>
		<label for="filter-by-date" class="screen-reader-text"><?php _e('Filter by date', 'stops-core-theme-and-plugin-updates'); ?></label>
		<select name="m" id="filter-by-date">
			<option<?php selected($this->month, 0); ?> value="0"><?php _e('All dates', 'stops-core-theme-and-plugin-updates'); ?></option>
<?php
		foreach ($months as $arc_row) {
			if (0 == $arc_row->year)
				continue;

			$month = zeroise($arc_row->month, 2);
			$year = $arc_row->year;

			printf("<option %s value='%s'>%s</option>\n",
				selected($this->month, $year . $month, false),
				esc_attr($arc_row->year . $month),
				/* translators: 1: month name, 2: 4-digit year */
				sprintf(__('%1$s %2$d'), $wp_locale->get_month($month), $year)
			);
		}
?>
		</select>
<?php
	}

	/**
	 * Type order
	 *
	 * @return void
	 */
	private function order_dropdown() {
		?>
		<label for="filter-by-order" class="screen-reader-text"><?php _e('Order', 'stops-core-theme-and-plugin-updates'); ?></label>
		<select name="order" id="filter-by-order">
			<option<?php selected($this->order, 'ASC'); ?> value="ASC"><?php echo _x('ASC', 'Ascending type', 'stops-core-theme-and-plugin-updates'); ?></option>
			<option<?php selected($this->order, 'DESC'); ?> value="DESC"><?php echo _x('DESC', 'Descending type', 'stops-core-theme-and-plugin-updates'); ?></option>
		</select>
		<?php
	}

	/**
	 * Type dropdown
	 *
	 * @return void
	 */
	private function type_dropdown() {
		?>
		<label for="filter-by-type" class="screen-reader-text"><?php _e('Filter by upgrade type', 'stops-core-theme-and-plugin-updates'); ?></label>
		<select name="type" id="filter-by-type">
			<option<?php selected($this->type, 'all'); ?> value="all"><?php echo _x('All types', 'Upgrade types: translation, core, plugin, theme', 'stops-core-theme-and-plugin-updates'); ?></option>
			<option<?php selected($this->type, 'core'); ?> value="core"><?php echo _x('Core', 'Show WordPress core updates', 'stops-core-theme-and-plugin-updates'); ?></option>
			<option<?php selected($this->type, 'plugin'); ?> value="plugin"><?php echo _x('Plugins', 'Show WordPress plugin updates', 'stops-core-theme-and-plugin-updates'); ?></option>
			<option<?php selected($this->type, 'theme'); ?> value="theme"><?php echo _x('Themes', 'Show WordPress theme updates', 'stops-core-theme-and-plugin-updates'); ?></option>
			<option<?php selected($this->type, 'translation'); ?> value="translation"><?php echo _x('Translations', 'Show WordPress translation updates', 'stops-core-theme-and-plugin-updates'); ?></option>
		</select>
		<?php
	}

	/**
	 * Status dropdown
	 *
	 * @return void
	 */
	private function status_dropdown() {
		?>
		<label for="filter-by-success" class="screen-reader-text"><?php _e('Filter by status', 'stops-core-theme-and-plugin-updates'); ?></label>
		<select name="status" id="filter-by-success">
			<option<?php selected($this->status, 'all'); ?> value="all"><?php _e('All statuses', 'stops-core-theme-and-plugin-updates'); ?></option>
			<option<?php selected($this->status, 1); ?> value="1"><?php echo _x('Success', 'Show status updates that are successful', 'stops-core-theme-and-plugin-updates'); ?></option>
			<option<?php selected($this->status, 0); ?> value="0"><?php echo _x('Failures', 'Show status updates that are not successful', 'stops-core-theme-and-plugin-updates'); ?></option>
			<option<?php selected($this->status, 2); ?> value="2"><?php echo _x('Update not compatible', 'Show status updates that do not meet safe mode requirements', 'stops-core-theme-and-plugin-updates'); ?></option>
		</select>
		<?php
	}

	/**
	 * Action dropdown
	 *
	 * @return void
	 */
	private function action_dropdown() {
		?>
		<label for="filter-by-action" class="screen-reader-text"><?php _e('Filter by action', 'stops-core-theme-and-plugin-updates'); ?></label>
		<select name="action_type" id="filter-by-action">
			<option<?php selected($this->action_type, 'all'); ?> value="all"><?php _e('All actions', 'stops-core-theme-and-plugin-updates'); ?></option>
			<option<?php selected($this->action_type, 'automatic'); ?> value="automatic"><?php echo _x('Automatic updates', 'Show log items that are automatic updates only', 'stops-core-theme-and-plugin-updates'); ?></option>
			<option<?php selected($this->action_type, 'manual'); ?> value="manual"><?php echo _x('Manual updates', 'Show log items that are manual updates only', 'stops-core-theme-and-plugin-updates'); ?></option>
		</select>
		<?php
	}

	/**
	 * Extra table nav
	 *
	 * @global int $cat
	 * @param string $which Specify which table
	 */
	protected function extra_tablenav($which) {
	?>
		<form id="logs-filter" action="<?php echo esc_url($this->url); ?>" method="GET">
		<input type="hidden" name="page" value="mpsum-update-options" />
		<input type="hidden" name="tab" value="logs" />
		<div class="alignleft">
	<?php
		if ('top' === $which && !is_singular()) {

			$this->months_dropdown(isset($this->screen->post_type) ? $this->screen->post_type : '');
			$this->status_dropdown();
			$this->action_dropdown();
			$this->type_dropdown();
			$this->order_dropdown();

			submit_button(__('Filter', 'stops-core-theme-and-plugin-updates'), 'button', 'filter_action', false, array('id' => 'post-query-submit'));
		}
?>
		</div>
		</form>
		<?php
		if (MPSUM_Updates_Manager::get_instance()->is_premium() && 'bottom' === $which) {
		?>
		<form id="log-export" action="<?php echo esc_url($this->url); ?>" method="get">
			<div class="alignleft">
			<?php submit_button(__('Export', 'stops-core-theme-and-plugin-updates'), array('primary', 'large'), 'export_action', false, array('id' => 'log-export')); ?>
				<div class="export-date-range" style="display: none">
					<h3><?php esc_html_e('Begin export', 'stops-core-theme-and-plugin-updates'); ?></h3>
					<div class="eum-export-date">
						<input type="text" id="export_date_start" name="export_date_start" />
						<strong><label for="export_date_start" style="display:block;"><?php esc_html_e('Start', 'stops-core-theme-and-plugin-updates');?></label></strong>
					</div>
					<div class="eum-export-date">
						<input type="text" id="export_date_end" name="export_date_end" />
						<strong><label for="export_date_start" style="display:block;"><?php esc_html_e('End', 'stops-core-theme-and-plugin-updates');?></label></strong>
					</div>
					<div class="mpsum-notice mpsum-regular mpsum-clear"><?php esc_html_e('The date range is optional. If left blank, the last 1000 log entries will be exported.', 'stops-core-theme-and-plugin-updates'); ?></div>
					<div class="export-button">
						<input type="hidden" id="export-ajax-url" value="<?php echo esc_url(add_query_arg(array('action' => 'eum_export_logs', 'nonce' => wp_create_nonce('eum_export_logs'), 'action_type' => 'all'), admin_url('admin-ajax.php')));?>" />
						<a href="#" id="eum-export-go" class="button-primary thickbox open-plugin-details-modal" name="<?php esc_html_e('Export logs', 'stops-core-theme-and-plugin-updates');?>"><?php esc_html_e('Go', 'stops-core-theme-and-plugin-updates'); ?></a>&nbsp;<a href="#" class="button-secondary export-cancel"><?php esc_html_e('Cancel', 'stops-core-theme-and-plugin-updates'); ?></a>
					</div>
				</div><!-- .export-date-range -->
			</div><!-- .alignleft -->
		</form>
		<?php
		}
		?>
		<form id="search-filter" action="<?php echo esc_url($this->url); ?>" method="get">
		<?php
		if (MPSUM_Updates_Manager::get_instance()->is_premium() && 'top' === $which) {
			?>
			<div class="alignright">
			<input type="text" name="eum-log-search" id="eum-log-search" value="<?php echo esc_attr($this->search_term); ?>" /><?php submit_button(__('Search', 'stops-core-theme-and-plugin-updates'), 'button', 'search_action', false, array('id' => 'log-query-search')); ?>
			</div>
			<?php
		}
		?>
		</form><!-- #search-filter -->
<?php
		/**
		 * Fires immediately following the closing "actions" div in the tablenav for the posts
		 * list table.
		 *
		 * @since 4.4.0
		 *
		 * @param string $which The location of the extra table nav markup: 'top' or 'bottom'.
		 */
		do_action('manage_posts_extra_tablenav', $which);
	}

	/**
	 * Get table classes
	 *
	 * @return array
	 */
	protected function get_table_classes() {
		return array('widefat', 'fixed', 'striped');
	}

	/**
	 * Get columns
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'user'    => _x('User', 'Column header for logs', 'stops-core-theme-and-plugin-updates'),
			'name'    => _x('Name', 'Column header for logs', 'stops-core-theme-and-plugin-updates'),
			'type'    => _x('Type', 'Column header for logs', 'stops-core-theme-and-plugin-updates'),
			'version_from' => _x('From', 'Column header for version number in logs', 'stops-core-theme-and-plugin-updates'),
			'version' => _x('To', 'Column header for version number in logs', 'stops-core-theme-and-plugin-updates'),
			'action'  => _x('Action', 'Column header for logs', 'stops-core-theme-and-plugin-updates'),
			'status'  => _x('Status', 'Column header for logs', 'stops-core-theme-and-plugin-updates'),
			'date'    => _x('Date', 'Column header for logs', 'stops-core-theme-and-plugin-updates'),
			'notes'    => _x('Notes', 'Column header for notes', 'stops-core-theme-and-plugin-updates'),
		);
		return $columns;
	}

	/**
	 * Display rows
	 */
	public function display_rows() {
		foreach ($this->items as $record) {
			$this->single_row($record);
		}
	}

	/**
	 * Display CSV data
	 */
	public function display_csv() {
		$fp = fopen('php://temp', 'w+');
		foreach ($this->items as $record) {
			$row_columns = array();
			foreach ($record as $record_key => $record_data) {
				if ('log_id' == $record_key) continue;
				switch ($record_key) {
					case 'user_id':
						if (0 == $record_data) {
							$row_columns[] = _x('None', 'No user found', 'stops-core-theme-and-plugin-updates');
						} else {
							$user = get_user_by('id', $record_data);
							if ($user) {
								$row_columns[] = $user->user_nicename;
							}
						}
						break;
					case 'name':
						$row_columns[] = $record_data;
						break;
					case 'type':
						if ('core' == $record_data) {
							$row_columns[] = ucfirst(_x('core', 'update type', 'stops-core-theme-and-plugin-updates'));
						} elseif ('translation' == $record_data) {
							$row_columns[] = ucfirst(_x('translation', 'update type', 'stops-core-theme-and-plugin-updates'));
						} elseif ('plugin' == $record_data) {
							$row_columns[] =ucfirst(_x('plugin', 'update type', 'stops-core-theme-and-plugin-updates'));
						} elseif ('theme' == $record_data) {
							$row_columns[] = ucfirst(_x('theme', 'update type', 'stops-core-theme-and-plugin-updates'));
						} else {
							$row_columns[] = ucfirst($record_data);
						}
						break;
					case 'version_from':
						$row_columns[] = $record_data;
						break;
					case 'version':
						$row_columns[] = $record_data;
						break;
					case 'action':
						if ('manual' == $record_data) {
							$row_columns[] = ucfirst(_x('manual', 'update type - manual or automatic updates', 'stops-core-theme-and-plugin-updates'));
						} elseif ('automatic' == $record_data) {
							$row_columns[] = ucfirst(_x('automatic', 'update type - manual or automatic updates', 'stops-core-theme-and-plugin-updates'));
						} else {
							$row_columns[] = ucfirst($record_data);
						}
						break;
					case 'status':
						if (1 == $record_data) {
							$row_columns[] = __('Success', 'stops-core-theme-and-plugin-updates');
						} elseif (2 == $record_data) {
							$row_columns[] = _x('Update requirements not met', 'Show status updates that are in safe mode', 'stops-core-theme-and-plugin-updates');
						} else {
							$row_columns[] = __('Failure', 'stops-core-theme-and-plugin-updates');
						}
						break;
					case 'date':
						$row_columns[] = $record_data;
						break;
					case 'notes':
						$row_columns[] = $record_data;
						break;
					default:
						break;
				}
			}
			fputcsv($fp, $row_columns);
		}
		rewind($fp);
		$csv_contents = stream_get_contents($fp);
		fclose($fp);
		echo $csv_contents;
	}

	/**
	 * Display JSON data
	 *
	 * @param array $posts posts to be displayed
	 */
	public function display_json() {
		$json_array = array();
		foreach ($this->items as $record) {
			$log_id = 0;
			$row_data = array();


			foreach ($record as $record_key => $record_data) {
				if ('log_id' == $record_key) {
					$log_id = $record_data;
					continue;
				}
				$row_data[$record_key] = $record_data;
			}
			$json_array[$log_id] = $row_data;
		}
		echo json_encode($json_array);
	}

	/**
	 * Single row
	 *
	 * @param array $record log record
	 * @return void
	 */
	public function single_row($record) {

	?>
		<tr id="log-<?php echo $record->log_id; ?>">
			<?php
			foreach ($record as $record_key => $record_data) {
				if ('log_id' == $record_key) continue;
				echo '<td>';
				switch ($record_key) {
					case 'user_id':
						if (0 == $record_data) {
							echo _x('None', 'No user found', 'stops-core-theme-and-plugin-updates');
						} else {
							$user = get_user_by('id', $record_data);
							if ($user) {
								echo esc_html($user->user_nicename);
							}
						}
						break;
					case 'name':
						echo esc_html($record_data);
						break;
					case 'type':
						if ('core' == $record_data) {
							echo esc_html(ucfirst(_x('core', 'update type', 'stops-core-theme-and-plugin-updates')));
						} elseif ('translation' == $record_data) {
							echo esc_html(ucfirst(_x('translation', 'update type', 'stops-core-theme-and-plugin-updates')));
						} elseif ('plugin' == $record_data) {
						   echo esc_html(ucfirst(_x('plugin', 'update type', 'stops-core-theme-and-plugin-updates')));
						} elseif ('theme' == $record_data) {
							echo esc_html(ucfirst(_x('theme', 'update type', 'stops-core-theme-and-plugin-updates')));
						} else {
							echo esc_html(ucfirst($record_data));
						}
						break;
					case 'version_from':
						echo esc_html($record_data);
						break;
					case 'version':
						echo esc_html($record_data);
						break;
					case 'action':
						if ('manual' == $record_data) {
							echo esc_html(ucfirst(_x('manual', 'update type - manual or automatic updates', 'stops-core-theme-and-plugin-updates')));
						} elseif ('automatic' == $record_data) {
							echo esc_html(ucfirst(_x('automatic', 'update type - manual or automatic updates', 'stops-core-theme-and-plugin-updates')));
						} else {
							echo esc_html(ucfirst($record_data));
						}
						break;
					case 'status':
						if (1 == $record_data) {
							echo esc_html__('Success', 'stops-core-theme-and-plugin-updates');
						} elseif (2 == $record_data) {
							echo _x('Update requirements not met', 'Show status updates that are in safe mode', 'stops-core-theme-and-plugin-updates');
						} else {
							echo esc_html__('Failure', 'stops-core-theme-and-plugin-updates');
						}
						break;
					case 'date':
						echo esc_html($record_data);
						break;
					case 'notes':
						if (!empty($record_data)) {
							printf('<a href="#" class="eum-note-expand">%s</a>', esc_html__('Show notes', 'stops-core-theme-and-plugin-updates'));
							printf('<div style="display: none">%s</div>', wp_kses_post(wpautop($record_data)));
						}
						break;
					default:
						break;
				}
				echo '</td>';
			}

			?>
		</tr>
	<?php
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

		// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable -- Both $total_items and $total_pages can be ignored as they are extracted as part of $this->_pagination_args on line 714

		if (isset($total_items)) {
			$response['total_items_i18n'] = sprintf(_n('%s log', '%s logs', $total_items), number_format_i18n($total_items));
		}

		if (isset($total_pages)) {
			$response['total_pages'] = $total_pages;
			$response['total_pages_i18n'] = number_format_i18n($total_pages);
		}

		// phpcs:enable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

		wp_send_json($response);
	}

	/**
	 * Display the pagination.
	 *
	 * @since 3.1.0
	 * @access protected
	 *
	 * @param string $which Which extra table nav to use
	 */
	protected function pagination($which) {
		if (empty($this->_pagination_args)) {
			return;
		}

		$total_items = $this->_pagination_args['total_items'];
		$total_pages = $this->_pagination_args['total_pages'];
		$tab = $this->_pagination_args['tab'];
		$view = $this->_pagination_args['view'];
		$term  = $this->_pagination_args['term'];
		$current = $this->_pagination_args['paged'];

		$infinite_scroll = false;
		if (isset($this->_pagination_args['infinite_scroll'])) {
			$infinite_scroll = $this->_pagination_args['infinite_scroll'];
		}

		$output = '<span class="displaying-num">' . sprintf(_n('%s item', '%s items', $total_items), number_format_i18n($total_items)) . '</span>';

		$current_url = set_url_scheme('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

		$current_url = remove_query_arg(array('hotkeys_highlight_last', 'hotkeys_highlight_first'), $current_url);

		$page_links = array();

		$total_pages_before = '<span class="paging-input">';
		$total_pages_after  = '</span>';

		$disable_first = $disable_last = $disable_prev = $disable_next = false;

		if (1 == $current) {
			$disable_first = true;
			$disable_prev = true;
		}
		if (2 == $current) {
			$disable_first = true;
		}
		if ($current == $total_pages || 0 == $total_items) {
			$disable_last = true;
			$disable_next = true;
		}
		if ($current == $total_pages - 1) {
			$disable_last = true;
		}

		if ($disable_first) {
			$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>';
		} else {
			$page_links[] = sprintf("<a class='first-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url(add_query_arg(array('tab' => $tab, 'view' => $view, 'term' => $term, 'paged' => 1), $current_url)),
				__('First page'),
				'&laquo;'
			);
		}

		if ($disable_prev) {
			$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>';
		} else {
			$page_links[] = sprintf("<a class='prev-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url(add_query_arg(array('paged' => max(1, $current-1), 'tab' => $tab, 'view' => $view, 'term' => $term), $current_url)),
				__('Previous page'),
				'&lsaquo;'
			);
		}

		if ('bottom' == $which) {
			$html_current_page  = $current;
			$total_pages_before = '<span class="screen-reader-text">' . __('Current Page') . '</span><span id="table-paging" class="paging-input" data-tab="' . $tab . '">';
		} else {
			$html_current_page = sprintf("%s<input class='current-page' id='current-page-selector' type='text' name='paged' value='%s' size='%d' aria-describedby='table-paging' data-tab='%s' data-view='%s' />",
				'<label for="current-page-selector" class="screen-reader-text">' . __('Current Page') . '</label>',
				$current,
				strlen($total_pages),
				$tab,
				$view
			);
		}
		$html_total_pages = sprintf("<span class='total-pages'>%s</span>", number_format_i18n($total_pages));
		if (0 == $total_items) {
			$html_current_page = 0;
			$html_total_pages = 0;
		}
		$page_links[] = $total_pages_before . sprintf(_x('%1$s of %2$s', 'paging'), $html_current_page, $html_total_pages) . $total_pages_after;

		if ($disable_next) {
			$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>';
		} else {
			$page_links[] = sprintf("<a class='next-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url(add_query_arg(array('paged' => min($total_pages, $current+1), 'tab' => $tab, 'view' => $view, 'term' => $term), $current_url)),
				__('Next page'),
				'&rsaquo;'
			);
		}

		if ($disable_last) {
			$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>';
		} else {
			$page_links[] = sprintf("<a class='last-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url(add_query_arg(array('paged' => $total_pages, 'tab' => $tab, 'view' => $view, 'term' => $term), $current_url)),
				__('Last page'),
				'&raquo;'
			);
		}

		$pagination_links_class = 'pagination-links';
		if (! empty($infinite_scroll)) {
			$pagination_links_class = ' hide-if-js';
		}
		$output .= "\n<span class='$pagination_links_class'>" . join("\n", $page_links) . '</span>';

		if ($total_pages) {
			$page_class = $total_pages < 2 ? ' one-page' : '';
		} else {
			$page_class = ' no-pages';
		}
		$this->_pagination = "<div class='tablenav-pages{$page_class}'>$output</div>";

		echo $this->_pagination;
	}
}
