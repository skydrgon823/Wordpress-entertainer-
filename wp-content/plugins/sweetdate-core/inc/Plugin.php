<?php

namespace Sweetcore;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sweetcore plugin.
 *
 * The main plugin handler class is responsible for initializing Sweetcore. The
 * class registers and all the components required to run the plugin.
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'sweetcore' ), '1.0.0' );
	}

	/**
	 * Wakeup.
	 *
	 * Disable unserializing of the class.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'sweetcore' ), '1.0.0' );
	}

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			/**
			 * Sweetcore loaded.
			 *
			 * Fires when Sweetcore was fully loaded and instantiated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'sweetcore/loaded' );
		}

		return self::$instance;
	}

	/**
	 * Plugin constructor.
	 *
	 * Initializing Sweetcore plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __construct() {
		$this->register_autoloader();

		add_action( 'plugins_loaded', [ $this, 'init_components' ] );

		add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ] );

		add_action( 'widgets_init', [ $this, 'register_widgets' ] );

		add_action( 'init', [ $this, 'init' ] );

	}


	/**
	 * Init.
	 *
	 * Initialize Sweetcore Plugin. Register Sweetcore support for all the
	 * supported post types and initialize Sweetcore components.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		new Post_Types();

		/**
		 * Sweetcore init.
		 *
		 * Fires on Sweetcore init, after Sweetcore has finished loading but
		 * before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		do_action( 'sweetcore/init' );
	}

	public function after_setup_theme() {
		if ( ! function_exists( 'sq_option' ) ) {
			return;
		}
		require_once SWEETCORE_PATH . 'core/after-theme.php';
	}

	/**
	 * Init components.
	 *
	 * Initialize Sweetcore components. Register actions.
	 * initialize admin components.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	public function init_components() {

		if ( ! file_exists( get_template_directory() . '/framework/classes/SQueen.php' ) ) {
			return;
		}

		// Helper functions
		require_once SWEETCORE_PATH . 'core/helpers.php';

		// Importer helper
		require_once SWEETCORE_PATH . 'lib/sq-framework/import/import.php';

		// Load shortcodes
		if ( ! class_exists( 'KleoShortcodes' ) ) {
			require_once SWEETCORE_PATH . 'core/shortcodes/kleo-shortcodes.php';
		}

		// Theme options framework
		if ( ! class_exists( 'Kleo_Options' ) ) {
			require_once( SWEETCORE_PATH . 'core/options/defaults.php' );
		}

		// Include metabox functions if we are in admin
		if ( is_admin() && ! function_exists( 'kleo_metaboxes' ) ) {
			require_once( SWEETCORE_PATH . 'core/metaboxes/metabox_functions.php' );
		}

	}

	public function register_widgets() {

		/* Bail if theme is not active */
		if ( ! function_exists( 'sq_option' ) ) {
			return;
		}

		register_widget( "\Sweetcore\Widgets\About_Us" );
		register_widget( "\Sweetcore\Widgets\Mailchimp" );
		register_widget( "\Sweetcore\Widgets\Recent_Posts" );
		register_widget( "\Sweetcore\Widgets\Search_Form" );
		register_widget( "\Sweetcore\Widgets\Support_Box" );
		register_widget( "\Sweetcore\Widgets\Testimonials" );
		register_widget( "\Sweetcore\Widgets\Twitter" );
	}


	/**
	 * Register autoloader.
	 *
	 * Sweetcore autoloader loads all the classes needed to run the plugin.
	 *
	 * @since 1.6.0
	 * @access private
	 */
	private function register_autoloader() {
		require SWEETCORE_PATH . 'inc/Autoloader.php';

		Autoloader::run();
	}

}

Plugin::instance();
