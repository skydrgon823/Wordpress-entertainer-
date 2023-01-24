<?php
if (!defined('ABSPATH')) die('No direct access.');
/**
 * Controls the advanced tab and handles the saving of its options.
 *
 * @package WordPress
 * @since 5.0.0
 */
class MPSUM_Admin_Advanced {

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
	private $tab = 'advanced';

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
		MPSUM_Exclude_Users::get_instance();
		MPSUM_Force_Updates::get_instance();
		MPSUM_Admin_Bar::get_instance();
		MPSUM_Reset_Options::get_instance();
		if (!Easy_Updates_Manager()->is_premium()) {
			MPSUM_Admin_Advanced_Preview::get_instance();
		}
		add_action('mpsum_admin_tab_advanced', array( $this, 'tab_output' ));
	}

	/**
	 * Output the HTML interface for the advanced tab.
	 *
	 * Output the HTML interface for the advanced tab.
	 *
	 * @since 5.0.0
	 * @access public
	 * @see __construct
	 * @internal Uses the mpsum_admin_tab_main action
	 */
	public function tab_output() {
		Easy_Updates_Manager()->include_template('admin-tab-advanced.php');
	} //end tab_output
}
