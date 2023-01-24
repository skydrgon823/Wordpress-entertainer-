<?php
if (!defined('ABSPATH')) die('No direct access.');
/**
 * Controls the plugins tab and handles the saving of its options.
 *
 * @package WordPress
 * @since 5.0.0
 */
class MPSUM_Admin_Plugins {

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
	private $tab = 'plugins';

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
		add_action('mpsum_admin_tab_plugins', array($this, 'tab_output'));
	}

	/**
	 * Determine whether the plugins can be updated or not.
	 *
	 * Determine whether the plugins can be updated or not.
	 *
	 * @since 5.0.0
	 *
	 * @return bool True if the plugins can be updated, false if not.
	 */
	public static function can_update_plugins() {
		$core_options = MPSUM_Updates_Manager::get_options('core');
		if (isset($core_options['all_updates']) && 'off' == $core_options['all_updates']) {
			return false;
		}
		if (isset($core_options['plugin_updates']) && 'off' == $core_options['plugin_updates']) {
			return false;
		}
		return true;
	}

	/**
	 * Output the HTML interface for the plugins tab.
	 *
	 * Output the HTML interface for the plugins tab.
	 *
	 * @since 5.0.0
	 * @access public
	 * @see __construct
	 * @internal Uses the mpsum_admin_tab_plugins action
	 */
	public function tab_output() {
		$params = array(
			'can_update' => self::can_update_plugins(),
			'slug' => $this->slug,
			'tab' => $this->tab,
			'paged' => '1',
			'view' => 'all'
		);
		Easy_Updates_Manager()->include_template('admin-tab-plugins.php', false, $params);
	} //end tab_output_plugins
}
