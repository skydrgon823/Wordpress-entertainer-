<?php
if (!defined('ABSPATH')) die('No direct access.');
/**
 * Controls the main (general) tab and handles the saving of its options.
 *
 * @package WordPress
 * @since 5.0.0
 */
class MPSUM_Admin_Dashboard {

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
	 * @access private
	 * @var string $tab
	 */
	private $tab = 'main';

	/**
	 * Class constructor.
	 *
	 * Initialize the class
	 *
	 * @since 5.0.0
	 * @access public
	 *
	 * @param string $slug Slug to the admin panel page
	 */
	public function __construct( $slug = '' ) {
		$this->slug = $slug;
		// Admin Tab Actions
		add_action('mpsum_admin_tab_dashboard', array( $this, 'tab_output' ));
	}


	/**
	 * Output the HTML interface for the main tab.
	 *
	 * Output the HTML interface for the main tab.
	 *
	 * @since 5.0.0
	 * @access public
	 * @see __construct
	 * @internal Uses the mpsum_admin_tab_main action
	 */
	public function tab_output() {
		Easy_Updates_Manager()->include_template('admin-tab-main.php');
		return; // Squiz.PHP.NonExecutableCode.ReturnNotRequired
	} //end tab_output_plugins
}
