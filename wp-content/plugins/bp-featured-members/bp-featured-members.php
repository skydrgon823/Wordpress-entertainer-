<?php
/**
 * Plugin Name: BuddyPress Featured Members
 * Description: BuddyPress add-ons which allow site admin to mark members as featured or un-featured.
 * Plugin URI: https://buddydev.com/plugins/bp-featured-members/
 * Author: BuddyDev
 * Author URI: https://buddydev.com
 * Version: 1.1.1
 * Text Domain: bp-featured-members
 * Domain Path: /languages
 */

/**
 * Contributor:
 *  - Ravi Sharma
 *  - Brajesh Singh
 */

// exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class BP_Featured_Members
 */
class BP_Featured_Members {

	/**
	 * Singleton instance.
	 *
	 * @var BP_Featured_Members
	 */
	private static $instance = null;

	/**
	 * Data array for storing arbitrary data.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Absolute path of plugin directory
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Absolute url of this plugin's directory
	 *
	 * @var string
	 */
	private $url;

	/**
	 * BP_Featured_Members constructor.
	 */
	private function __construct() {
		// initializing the class variables.
		$this->path = plugin_dir_path( __FILE__ );
		$this->url  = plugin_dir_url( __FILE__ );

		$this->setup();
	}

	/**
	 * Singleton method
	 *
	 * @return BP_Featured_Members|null
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * This function setups the application
	 */
	private function setup() {
		add_action( 'bp_loaded', array( $this, 'load' ) );
		add_action( 'bp_init', array( $this, 'load_text_domain' ) );
		// css/js.
		add_action( 'bp_enqueue_scripts', array( $this, 'load_assets' ) );
		// load admin scripts.
		add_action( 'admin_print_scripts-widgets.php', array( $this, 'load_admin_assets' ) );
	}

	/**
	 * This function load the necessary files of plugin
	 */
	public function load() {

		$files = array(
			'core/bp-featured-members-functions.php',
			'core/class-bp-featured-members-ajax-handler.php',
			'core/class-bp-featured-members-widget.php',
			'core/class-featured-members-template-helper.php',
			'core/bp-featured-members-shortcode.php',
			'core/bp-featured-members-filters.php',
		);

		foreach ( $files as $file ) {
			require_once $this->path . $file;
		}
	}

	/**
	 * This function will load plugin assets
	 */
	public function load_assets() {

		wp_register_style( 'lightslider', $this->url . 'assets/css/lightslider.min.css' );
		wp_register_script( 'lightslider', $this->url . 'assets/js/lightslider.min.js', array( 'jquery' ) );

		wp_register_script( 'bp-featured-members', $this->url . 'assets/js/bp-featured-members.js', array( 'jquery' ) );

		wp_enqueue_script( 'bp-featured-members' );
	}

	/**
	 * Load admin assets.
	 */
	public function load_admin_assets() {
		wp_register_script( 'bp-featured-members-admin', $this->url . 'assets/js/bp-featured-members-admin.js', array( 'jquery' ) );

		wp_enqueue_script( 'bp-featured-members-admin' );
	}

	/**
	 * Enqueue slider assets.
	 */
	public function load_slider() {
		wp_enqueue_style( 'lightslider' );
		wp_enqueue_script( 'lightslider' );
	}

	/**
	 * Load translations
	 */
	public function load_text_domain() {
		load_plugin_textdomain( 'bp-featured-members', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Get the abs path to this plugin directory
	 *  e.g /home/xyz/public_html/wp-content/plugins/bp-featured-members/
	 *
	 * @return string abs path to this plugin directory
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * Get absolute url to this plugin directory
	 *  e.g http://example.com/wp-contnet/plugins/bp-featured-members/
	 *
	 * @return string absolute url to this plugin directory
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * Can current user toggle member status to featured?
	 *
	 * @return bool
	 */
	public function current_user_can_toggle_member_status() {

		if ( is_user_logged_in() && is_super_admin() ) {
			$can = true;
		} else {
			$can = false;
		}

		return $can;
	}

	/**
	 * Add user to featured members list.
	 *
	 * @param int $user_id user id.
	 *
	 * @return bool|int meta id or true/false on update/failure
	 */
	public function add_user( $user_id ) {
		$done = update_user_meta( $user_id, '_is_featured', 1 );

		// is it an add?
		if ( is_numeric( $done ) ) {
			$this->update_count( 1 );
		}

		do_action( 'bp_featured_members_user_added', $user_id );

		return $done;
	}

	/**
	 * Remove the user from the featured users list
	 *
	 * @param int $user_id user id.
	 *
	 * @return boolean
	 */
	public function remove_user( $user_id ) {
		$deleted = delete_user_meta( $user_id, '_is_featured' );

		if ( $deleted ) {
			$this->update_count( - 1 );
		}
		do_action( 'bp_featured_members_user_removed', $user_id );

		return $deleted;
	}

	/**
	 * Keep track of the count of featured members
	 *
	 * @param int $by how many.
	 */
	private function update_count( $by = 1 ) {

		$count = get_site_option( 'bp-featured-members-count', 0 );
		$count = $count + $by;

		if ( $count < 0 ) {
			$count = 0;
		}

		update_site_option( 'bp-featured-members-count', $count );
	}

	/**
	 * Total number of featured members
	 *
	 * @return int
	 */
	public function get_count() {
		return absint( get_site_option( 'bp-featured-members-count', 0 ) );
	}

	/**
	 * Is the given user featured?
	 *
	 * @param int $user_id user id.
	 *
	 * @return bool true if featured else false
	 */
	public function is_featured( $user_id ) {
		return get_user_meta( $user_id, '_is_featured', true ) == 1;
	}

	/**
	 * Toggle the entry of a user in  feaured members list
	 *
	 * @param int $user_id user id.
	 *
	 * @return bool true this operation never fails
	 */
	public function toggle( $user_id ) {

		if ( $this->is_featured( $user_id ) ) {
			$this->remove_user( $user_id );
		} else {
			$this->add_user( $user_id );
		}

		return true;
	}

	/**
	 * Mark the beginning of the featured members loop
	 */
	public function start_loop() {
		$this->set( 'loop', true );
	}

	/**
	 * Mark the ending of featured members loop
	 */
	public function end_loop() {
		$this->data = array(); // unset all data.
	}

	/**
	 * Save loop args
	 *
	 * @param mixed $args data to save.
	 */
	public function save_args( $args ) {
		$this->set( 'args', $args );
	}

	/**
	 * Get current loop args
	 *
	 * @return mixed|string
	 */
	public function get_args() {
		return $this->get( 'args' );
	}

	/**
	 * Check if inside the featured members loop
	 *
	 * @return bool
	 */
	public function in_the_loop() {
		return $this->has( 'loop' );
	}

	/**
	 * Save some data
	 *
	 * @param string $key unique key.
	 * @param mixed  $val value associated.
	 */
	public function set( $key, $val ) {
		$this->data[ $key ] = $val;
	}

	/**
	 * Get the stored data.
	 *
	 * @param string $key unique key.
	 *
	 * @return mixed|string
	 */
	public function get( $key ) {

		if ( isset( $this->data[ $key ] ) ) {
			return $this->data[ $key ];
		}

		return '';
	}

	/**
	 * Delete the data by given key
	 *
	 * @param string $key unique key.
	 */
	public function delete( $key ) {
		unset( $this->data[ $key ] );
	}

	/**
	 * Check if data exists for the given key
	 *
	 * @param string $key unique key.
	 *
	 * @return bool
	 */
	public function has( $key ) {
		return isset( $this->data[ $key ] );
	}
}

/**
 * Helper function to access the singleton instance.
 *
 * @return BP_Featured_Members
 */
function bp_featured_members() {
	return BP_Featured_Members::get_instance();
}

// initialize.
bp_featured_members();
