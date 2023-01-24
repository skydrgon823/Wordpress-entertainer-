<?php
if (!defined('ABSPATH')) die('No direct access.');
/**
 * Controls the logs tab.
 *
 * @package WordPress
 * @since 6.0.0
 */
class MPSUM_Admin_Logs {

	/**
	 * Holds the slug to the admin panel page
	 *
	 * @since 5.0.0
	 * @access private
	 * @var string $slug
	 */
	private $slug = '';
	
	/**
	 * Holds the tab name
	 *
	 * @since 5.0.0
	 * @access static
	 * @var string $tab
	 */
	private $tab = 'logs';
	
	/**
	 * Class constructor.
	 *
	 * Initialize the class
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @param string $slug Slug to the admin panel page
	 */
	public function __construct( $slug = '' ) {
		$this->slug = $slug;
		// Admin Tab Actions
		add_action('mpsum_admin_tab_logs', array( $this, 'tab_output_logs' ));
	}
	
	/**
	 * Output the HTML interface for the logs tab.
	 *
	 * Output the HTML interface for the logs tab.
	 *
	 * @since 6.0.0
	 * @access public
	 * @see __construct
	 * @internal Uses the mpsum_admin_tab_logs action
	 */
	public function tab_output_logs() {
		// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable -- his are ignored as they are passed to the template
		$paged = isset($data['data']['paged']) ? $data['data']['paged'] : '1';
		$view = isset($data['data']['view']) ? $data['data']['view'] : 'all';
		$m = isset($data['data']['m']) ? $data['data']['m'] : 'all';
		$status = isset($data['data']['status']) ? $data['data']['status'] : 'all';
		$action_type = isset($data['data']['action_type']) ? $data['data']['action_type'] : 'all';
		$type = isset($data['data']['type']) ? $data['data']['type'] : 'all';
		$is_search = isset($data['data']['is_search']) ? $data['data']['is_search'] : false;
		$search_term = isset($data['data']['search_term']) ? $data['data']['search_term'] : '';
		$order = isset($data['data']['order']) ? $data['data']['order'] : 'DESC';
		// phpcs:enable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

		$args = array('paged' => $paged, 'view' => $view, 'status' => $status, 'action_type' => $action_type, 'type' => $type, 'm' => $m, 'is_search' => $is_search, 'search_term' => $search_term, 'order' => $order );
		Easy_Updates_Manager()->include_template('admin-tab-logs.php', false, $args);
	} //end tab_output_plugins
}
