<?php
/**
 * Plugin Name: OptinMonster
 * Plugin URI:  https://optinmonster.com
 * Description: OptinMonster is the best WordPress popup plugin that helps you grow your email list and sales with email popups, exit intent popups, floating bars and more!
 * Author:      OptinMonster Team
 * Author URI:  https://optinmonster.com
 * Version:     2.3.1
 * Text Domain: optin-monster-api
 * Domain Path: languages
 * WC requires at least: 3.2.0
 * WC tested up to:      4.7.0
 *
 * OptinMonster is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * OptinMonster is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OptinMonster. If not, see <https://www.gnu.org/licenses/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Autoload the class files.
spl_autoload_register( 'OMAPI::autoload' );

// Store base file location.
define( 'OMAPI_FILE', __FILE__ );

/**
 * Main plugin class.
 *
 * @since 1.0.0
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */
class OMAPI {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = '2.3.1';

	/**
	 * The name of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $plugin_name = 'OptinMonster';

	/**
	 * Unique plugin slug identifier.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $plugin_slug = 'optinmonster';

	/**
	 * Plugin file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * The assets base URL for this plugin.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * OMAPI_Ajax object
	 *
	 * @var OMAPI_Ajax
	 */
	public $ajax;

	/**
	 * OMAPI_Type object
	 *
	 * @var OMAPI_Type
	 */
	public $type;

	/**
	 * OMAPI_Output object
	 *
	 * @var OMAPI_Output
	 */
	public $output;

	/**
	 * OMAPI_Shortcode object
	 *
	 * @var OMAPI_Shortcode
	 */
	public $shortcode;

	/**
	 * OMAPI_WooCommerce object.
	 *
	 * @var OMAPI_WooCommerce
	 */
	public $woocommerce;

	/**
	 * OMAPI_Elementor object.
	 *
	 * @var OMAPI_Elementor
	 */
	public $elementor;

	/**
	 * OMAPI_ClassicEditor object.
	 *
	 * @var OMAPI_ClassicEditor
	 */
	public $classicEditor;

	/**
	 * OMAPI_MailPoet object.
	 *
	 * @var OMAPI_MailPoet;
	 */
	public $mailpoet;

	/**
	 * OMAPI_Actions object (loaded only in the admin)
	 *
	 * @var OMAPI_Actions
	 */
	public $actions;

	/**
	 * OMAPI_Menu object (loaded only in the admin)
	 *
	 * @var OMAPI_Menu
	 */
	public $menu;

	/**
	 * OMAPI_Save object (loaded only in the admin)
	 *
	 * @var OMAPI_Save
	 */
	public $save;

	/**
	 * OMAPI_Refresh object (loaded only in the admin)
	 *
	 * @var OMAPI_Refresh
	 */
	public $refresh;

	/**
	 * OMAPI_Sites object (loaded only in the REST API and admin)
	 *
	 * @var OMAPI_Sites
	 */
	public $sites;

	/**
	 * OMAPI_Validate object (loaded only in the admin)
	 *
	 * @var OMAPI_Validate
	 */
	public $validate;

	/**
	 * OMAPI_Welcome object (loaded only in the admin)
	 *
	 * @var OMAPI_Welcome
	 */
	public $welcome;

	/**
	 * OMAPI_TrustPulse object (loaded only in the admin)
	 *
	 * @var OMAPI_TrustPulse
	 */
	public $trustpulse;

	/**
	 * OMAPI_Review object (loaded only in the admin)
	 *
	 * @var OMAPI_Review
	 */
	public $review;

	/**
	 * OMAPI_RestApi object (loaded only in the REST API)
	 *
	 * @var OMAPI_RestApi
	 */
	public $rest_api;

	/**
	 * OMAPI_Notifications object (loaded only in the admin/REST API)
	 *
	 * @var OMAPI_Notifications
	 */
	public $notifications;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Load the plugin textdomain.
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		// Load the plugin widgets.
		add_action( 'widgets_init', array( $this, 'widgets' ) );

		// Define Constants.
		add_action( 'init', array( $this, 'define_constants' ) );

		// Load the plugin.
		add_action( 'init', array( $this, 'init' ) );

		// Hide the unrelated admin notices.
		add_action( 'admin_print_scripts', array( $this, 'hide_unrelated_admin_notices' ) );

		// PHP version check.
		add_action( 'admin_init', array( $this, 'check_php_version' ) );

		// Filter the WooCommerce category/tag REST API responses.
		add_filter( 'woocommerce_rest_prepare_product_cat', 'OMAPI_WooCommerce::add_category_base_to_api_response' );
		add_filter( 'woocommerce_rest_prepare_product_tag', 'OMAPI_WooCommerce::add_tag_base_to_api_response' );
	}

	/**
	 * Loads the plugin textdomain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = 'optin-monster-api';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Registers the OptinMonster widgets.
	 *
	 * @since 1.0.0
	 */
	public function widgets() {

		// To do: add widgets.
		register_widget( 'OMAPI_Widget' );

	}

	/**
	 * Set the OM constants.
	 *
	 * @since 2.0.2
	 */
	public function define_constants() {
		$this->url = plugin_dir_url( __FILE__ );

		do_action( 'optin_monster_before_define_constants', $this );

		// Define necessary plugin constants.
		if ( ! defined( 'OPTINMONSTER_APP_URL' ) ) {
			define( 'OPTINMONSTER_APP_URL', 'https://app.optinmonster.com' );
		}

		if ( ! defined( 'OPTINMONSTER_API_URL' ) ) {
			define( 'OPTINMONSTER_API_URL', 'https://api.omwpapi.com' );
		}

		if ( ! defined( 'OPTINMONSTER_CDN_URL' ) ) {
			define(
				'OPTINMONSTER_CDN_URL',
				is_admin()
					? 'https://a.omwpapi.com'
				: 'https://a.omappapi.com'
			);
		}

		if ( ! defined( 'OPTINMONSTER_VUE_ASSETS_URL' ) ) {
			define( 'OPTINMONSTER_VUE_ASSETS_URL', OPTINMONSTER_CDN_URL . '/app/wp-plugin/build' );
		}

		if ( ! defined( 'OPTINMONSTER_VUE_ASSETS_PATH' ) ) {
			define( 'OPTINMONSTER_VUE_ASSETS_PATH', '' );
		}

		if ( ! defined( 'OPTINMONSTER_APIJS_URL' ) ) {
			define( 'OPTINMONSTER_APIJS_URL', OPTINMONSTER_CDN_URL . '/app/js/api.min.js' );
		}

		if ( ! defined( 'OPTINMONSTER_SHAREABLE_LINK' ) ) {
			define( 'OPTINMONSTER_SHAREABLE_LINK', 'https://app.monstercampaigns.com' );
		}
	}

	/**
	 * Loads the plugin into WordPress.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Load our global option.
		$this->load_option();

		// Load global components.
		$this->load_global();

		add_action( 'rest_api_init', array( $this, 'load_rest' ) );

		// Load admin only components.
		if ( is_admin() ) {
			$this->load_admin();
		}

		// Run hook once OptinMonster has been fully loaded.
		do_action( 'optin_monster_api_loaded' );
	}

	/**
	 * Sets our options if not found in the DB.
	 *
	 * @since 1.0.0
	 */
	public function load_option() {

		// Check/set the plugin options.
		$option = get_option( 'optin_monster_api' );
		if ( empty( $option ) ) {
			$option = self::default_options();
			update_option( 'optin_monster_api', $option );
		}

		$review           = get_option( 'omapi_review' );
		$review_installed = ! empty( $review['time'] ) && is_numeric( $review['time'] );
		$review_dismissed = ! empty( $review['dismissed'] );

		if ( ! $review_installed ) {
			$review = array(
				'time'      => time(),
				'dismissed' => $review_dismissed,
			);
			update_option( 'omapi_review', $review );
		}

		// Check/set the installation date.
		if ( empty( $option['installed'] ) ) {

			// If the review was dismissed, we know the plugin was installed at least a day
			// before the notice was shown and dismissed. Otherwise, the review timestamp
			// should be pretty close to the install date.
			$option['installed'] = $review_dismissed ? $review['time'] - DAY_IN_SECONDS : $review['time'];

			// Store the plugin install date.
			update_option( 'optin_monster_api', $option );
		}
	}

	/**
	 * Loads all global related classes into scope.
	 *
	 * @since 1.0.0
	 */
	public function load_global() {

		// Register global components.
		$this->ajax        = new OMAPI_Ajax();
		$this->blocks      = new OMAPI_Blocks();
		$this->type        = new OMAPI_Type();
		$this->output      = new OMAPI_Output();
		$this->shortcode   = new OMAPI_Shortcode();
		$this->woocommerce = new OMAPI_WooCommerce();
		$this->elementor   = new OMAPI_Elementor();
		$this->mailpoet    = new OMAPI_MailPoet();

		// Fire a hook to say that the global classes are loaded.
		do_action( 'optin_monster_api_global_loaded' );

	}

	/**
	 * Loads all REST API related classes into scope.
	 *
	 * @since 1.8.0
	 */
	public function load_rest() {

		// Register global components.
		$this->actions       = new OMAPI_Actions();
		$this->sites         = new OMAPI_Sites();
		$this->rest_api      = new OMAPI_RestApi();
		$this->refresh       = new OMAPI_Refresh();
		$this->save          = new OMAPI_Save();
		$this->notifications = new OMAPI_Notifications();

		// Fire a hook to say that the global classes are loaded.
		do_action( 'optin_monster_api_rest_loaded' );
	}

	/**
	 * Loads all admin related classes into scope.
	 *
	 * @since 1.0.0
	 */
	public function load_admin() {

		// Register admin components.
		$this->actions       = new OMAPI_Actions();
		$this->menu          = new OMAPI_Menu();
		$this->save          = new OMAPI_Save();
		$this->refresh       = new OMAPI_Refresh();
		$this->validate      = new OMAPI_Validate();
		$this->welcome       = new OMAPI_Welcome();
		$this->trustpulse    = new OMAPI_TrustPulse();
		$this->review        = new OMAPI_Review();
		$this->sites         = new OMAPI_Sites();
		$this->notifications = new OMAPI_Notifications();
		$this->classicEditor = new OMAPI_ClassicEditor();

		if ( OMAPI_Partners::has_partner_url() ) {
			$this->cc = new OMAPI_ConstantContact();
		}

		// Fire a hook to say that the admin classes are loaded.
		do_action( 'optin_monster_api_admin_loaded' );

	}

	/**
	 * Internal method that returns a optin based on ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id     The optin ID used to retrieve a optin.
	 * @return array|bool Array of optin data or false if none found.
	 */
	public function get_optin( $id ) {
		return get_post( absint( $id ) );
	}

	/**
	 * Internal method that returns a optin based on slug.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug The optin slug used to retrieve a optin.
	 * @return array|bool  Array of optin data or false if none found.
	 */
	public function get_optin_by_slug( $slug ) {
		return get_page_by_path( sanitize_text_field( $slug ), OBJECT, 'omapi' );
	}

	/**
	 * Get all data for given campaign (optin).
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_Post $campaign The campaign post object.
	 *
	 * @return array
	 */
	public function collect_campaign_data( $campaign ) {
		$meta = array();
		$keys = get_post_meta( $campaign->ID );

		if ( ! empty( $keys ) ) {
			foreach ( $keys as $key => $x ) {
				$val = get_post_meta( $campaign->ID, $key, true );
				switch ( $key ) {
					case '_omapi_never':
					case '_omapi_only':
						$val = OMAPI_Utils::unique_array( $val );
						break;
					case '_omapi_taxonomies':
						$val = ! empty( $val )
							? array_map( array( 'OMAPI_Utils', 'unique_array' ), $val )
							: array();
						break;
				}
				$meta[ $key ] = $val;
			}
		}

		$type = get_post_meta( $campaign->ID, '_omapi_type', true );

		return array(
			'id'        => $campaign->post_name,
			'post'      => $campaign,
			'type'      => $type,
			'inline'    => OMAPI_Utils::is_inline_type( $type ),
			'post_meta' => $meta,
		);
	}

	/**
	 * Internal method that returns all optins created on the site.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Array of args to modify the query for retreiving optins.
	 * @return array|bool Array of optin data or false if none found.
	 */
	public function get_optins( $args = array() ) {

		$optins = get_posts(
			wp_parse_args(
				$args,
				array(
					'no_found_rows'          => true,
					'nopaging'               => true,
					'post_type'              => 'omapi',
					'posts_per_page'         => -1,
					'update_post_term_cache' => false,
				)
			)
		);

		if ( empty( $optins ) ) {
			return false;
		}

		foreach ( $optins as $optin ) {
			$optin->campaign_type = get_post_meta( $optin->ID, '_omapi_type', true );
			$optin->enabled       = ! ! get_post_meta( $optin->ID, '_omapi_enabled', true );
		}

		// Return the optin data.
		return $optins;
	}

	/**
	 * Returns all local campaigns. Cached.
	 *
	 * @since 2.2.0
	 *
	 * @return array|bool Array of optin data or false if none found.
	 */
	public function get_campaigns() {
		static $campaigns = null;
		if ( null === $campaigns ) {
			$campaigns = $this->get_optins();
		}

		return $campaigns;
	}

	/**
	 * Returns the main option for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $key      The option value to get for given key.
	 * @param  string $subkey   The option value to get for given key and sub-key.
	 * @param  mixed  $fallback The fallback value.
	 *
	 * @return mixed            The main option array for the plugin, or requsted value.
	 */
	public function get_option( $key = '', $subkey = '', $fallback = false ) {

		$option = get_option( 'optin_monster_api' );

		if ( ! empty( $key ) ) {
			if ( ! isset( $option[ $key ] ) ) {
				return $fallback;
			}

			if ( ! empty( $subkey ) ) {
				return isset( $option[ $key ], $option[ $key ][ $subkey ] )
					? $option[ $key ][ $subkey ]
					: $fallback;
			}

			return $option[ $key ];
		}

		return $option;
	}

	/**
	 * Returns the API credentials for OptinMonster.
	 *
	 * @since 1.0.0
	 *
	 * @return array|bool $creds The user's API creds for OptinMonster.
	 */
	public function get_api_credentials() {

		// Prepare variables.
		$option = $this->get_option();
		$key    = false;
		$user   = false;
		$apikey = false;

		// Attempt to grab the new API Key.
		if ( empty( $option['api']['apikey'] ) ) {
			if ( defined( 'OPTINMONSTER_REST_API_LICENSE_KEY' ) ) {
				$apikey = OPTINMONSTER_REST_API_LICENSE_KEY;
			}
		} else {
			$apikey = $option['api']['apikey'];
		}

		// Attempt to grab the Legacy API key and API user.
		if ( empty( $option['api']['key'] ) ) {
			if ( defined( 'OPTINMONSTER_API_LICENSE_KEY' ) ) {
				$key = OPTINMONSTER_API_LICENSE_KEY;
			}
		} else {
			$key = $option['api']['key'];
		}

		if ( empty( $option['api']['user'] ) ) {
			if ( defined( 'OPTINMONSTER_API_USER' ) ) {
				$user = OPTINMONSTER_API_USER;
			}
		} else {
			$user = $option['api']['user'];
		}

		// Check if we have any of the authentication data.
		if ( ! $apikey ) {
			// Do we at least have Legacy API Key and User.
			if ( ! $key || ! $user ) {
				return false;
			}
		}

		// Return the API credentials.
		return apply_filters(
			'optin_monster_api_creds',
			array(
				'key'    => $key,
				'user'   => $user,
				'apikey' => $apikey,
			)
		);

	}

	/**
	 * Returns the API credentials for OptinMonster.
	 *
	 * @since 1.9.2
	 *
	 * @return string The API url to use for embedding on the page.
	 */
	public function get_api_url() {
		return OMAPI_Urls::om_api();
	}

	/**
	 * Check if the  main WooCommerce class is active.
	 *
	 * @since 1.1.9
	 *
	 * @return bool
	 */
	public static function is_woocommerce_active() {
		return class_exists( 'WooCommerce', true );
	}

	/**
	 * Return the WooCommerce versions string.
	 *
	 * @since 1.6.5
	 *
	 * @return string
	 */
	public static function woocommerce_version() {
		return OMAPI_WooCommerce::version();
	}

	/**
	 * Determines if the passed version string passes the operator compare
	 * against the currently installed version of WooCommerce.
	 *
	 * Defaults to checking if the current WooCommerce version is greater than
	 * the passed version.
	 *
	 * @since 1.7.0
	 *
	 * @param string $version  The version to check.
	 * @param string $operator The operator to use for comparison.
	 *
	 * @return string
	 */
	public static function woocommerce_version_compare( $version = '', $operator = '>=' ) {
		return OMAPI_WooCommerce::version_compare( $version, $operator );
	}

	/**
	 * Check to see if Mailpoet is active.
	 *
	 * @since 1.2.3
	 *
	 * @return bool
	 */
	public static function is_mailpoet_active() {
		return OMAPI_MailPoet::is_active();
	}

	/**
	 * Returns possible API key error flag.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if there are API key errors, false otherwise.
	 */
	public function get_api_key_errors() {
		$option = $this->get_option();
		return isset( $option['is_expired'] ) && $option['is_expired'] || isset( $option['is_disabled'] ) && $option['is_disabled'] || isset( $option['is_invalid'] ) && $option['is_invalid'];
	}

	/**
	 * Get and include a view file for output.
	 *
	 * @since  1.9.0
	 *
	 * @param  string $file The view file.
	 * @param  mixed  $data Arbitrary data to be made available to the view file.
	 *
	 * @return void
	 */
	public function output_view( $file, $data = array() ) {
		require dirname( $this->file ) . '/views/' . $file;
	}

	/**
	 * Get view file output content.
	 *
	 * @since 2.3.0
	 *
	 * @param  string $file The view file.
	 * @param  mixed  $data Arbitrary data to be made available to the view file.
	 *
	 * @return string The view html content.
	 */
	public function get_view_contents( $file, $data = array() ) {
		ob_start();
		$this->output_view( $file, $data );
		return ob_get_clean();
	}

	/**
	 * Get and include a view file with css and minify the output.
	 *
	 * @since 2.3.0
	 *
	 * @param  string $file The view file.
	 * @param  mixed  $data Arbitrary data to be made available to the view file.
	 *
	 * @return void
	 */
	public function get_min_css_view_contents( $file, $data = array() ) {
		$contents = $this->get_view_contents( $file, $data );
		return str_replace( array( "\n", "\r", "\t" ), '', $contents );
	}

	/**
	 * Get and include a view file with css and minify the output.
	 *
	 * @since  1.9.0
	 *
	 * @param  string $file The view file.
	 * @param  mixed  $data Arbitrary data to be made available to the view file.
	 *
	 * @return void
	 */
	public function output_min_css( $file, $data = array() ) {
		echo $this->get_min_css_view_contents( $file, $data );
	}

	/**
	 * Return the level of the OM user.
	 *
	 * @since  2.0.0
	 *
	 * @return string  The level.
	 */
	public function get_level() {
		return $this->get_option( 'currentLevel', '', '' );
	}

	/**
	 * Check if the OM user has a custom plan.
	 *
	 * @since  2.0.0
	 *
	 * @return string  The level.
	 */
	public function is_custom_plan() {
		return 'vbp_custom' === $this->get_option( 'plan' );
	}

	/**
	 * Loads the default plugin options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of default plugin options.
	 */
	public static function default_options() {

		$options = array(
			'api'                => array(),
			'optins'             => array(),
			'is_expired'         => false,
			'is_disabled'        => false,
			'is_invalid'         => false,
			'installed'          => time(),
			'connected'          => '',
			'beta'               => false,
			'auto_updates'       => '',
			'usage_tracking'     => false,
			'hide_announcements' => false,
			'welcome'            => array(
				'status' => 'none',
			),
		);

		return apply_filters( 'optin_monster_api_default_options', $options );
	}

	/**
	 * PRS-0 compliant autoloader.
	 *
	 * @since 1.0.0
	 *
	 * @param string $classname The classname to check with the autoloader.
	 */
	public static function autoload( $classname ) {

		// Return early if not the proper classname.
		if ( 'OMAPI' !== mb_substr( $classname, 0, 5, 'UTF-8' ) ) {
			return;
		}

		// Check if the file exists. If so, load the file.
		$filename = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . str_replace( '_', DIRECTORY_SEPARATOR, $classname ) . '.php';
		if ( file_exists( $filename ) ) {
			require $filename;
		}

	}

	/**
	 * Gets all site IDs associated with this site
	 *
	 * @since 1.9.10
	 *
	 * @return array
	 */
	public function get_site_ids() {
		$option = $this->get_option();
		return ! empty( $option['siteIds'] ) ? (array) $option['siteIds'] : array();
	}

	/**
	 * Gets the site ID associated with this site
	 *
	 * @since 1.9.10
	 *
	 * @return string
	 */
	public function get_site_id() {
		$option = $this->get_option();
		return ! empty( $option['siteId'] ) ? (string) $option['siteId'] : '';
	}

	/**
	 * Hides unrelated admin notices.
	 *
	 * @since 1.9.7
	 */
	public function hide_unrelated_admin_notices() {
		// Bail if we're not on a OptinMonster screen.
		if ( empty( $_REQUEST['page'] ) || ! preg_match( '/optin-monster-/', esc_html( $_REQUEST['page'] ) ) ) {
			return;
		}

		global $wp_filter;

		$notices_type = array(
			'user_admin_notices',
			'admin_notices',
			'all_admin_notices',
		);

		foreach ( $notices_type as $type ) {
			if ( empty( $wp_filter[ $type ]->callbacks ) || ! is_array( $wp_filter[ $type ]->callbacks ) ) {
				continue;
			}

			foreach ( $wp_filter[ $type ]->callbacks as $priority => $hooks ) {
				foreach ( $hooks as $name => $arr ) {
					if ( is_object( $arr['function'] ) && $arr['function'] instanceof Closure ) {
						unset( $wp_filter[ $type ]->callbacks[ $priority ][ $name ] );
						continue;
					}

					$class = ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) ? strtolower( get_class( $arr['function'][0] ) ) : '';

					if ( ! empty( $class ) && preg_match( '/^(?:omapi|am_notification)/', $class ) ) {
						continue;
					}

					if ( ! empty( $name ) && ! preg_match( '/^(?:omapi|am_notification)/', $name ) ) {
						unset( $wp_filter[ $type ]->callbacks[ $priority ][ $name ] );
					}
				}
			}
		}
	}

	public function check_php_version() {

		// Display for PHP below 5.6.
		if ( version_compare( PHP_VERSION, '5.5', '>=' ) ) {
			return;
		}

		// Display for admins only.
		if ( ! is_super_admin() ) {
			return;
		}

		// Display on Dashboard page only.
		if ( isset( $GLOBALS['pagenow'] ) && 'index.php' !== $GLOBALS['pagenow'] ) {
			return;
		}

		// Do not double up on WPForms notice.
		if ( function_exists( 'wpforms_check_php_version' ) ) {
			return;
		}

		// Display the notice, finally.
		echo '<div id="message" class="notice notice-error">' .
		'<p>' .
		sprintf(
			wp_kses(
				/* translators: %1$s - OptinMonster API plugin name; %2$s - optinmonster.com URL to a related doc. */
				__( 'Your site is running an outdated version of PHP that is no longer supported and may cause issues with the %1$s plugin. <a href="%2$s" target="_blank" rel="noopener noreferrer">Read more</a> for additional information.', 'optin-monster-api' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
						'rel'    => array(),
					),
				)
			),
			'<strong>OptinMonster API</strong>',
			'https://optinmonster.com/docs/supported-php-version/'
		) .
		'<br><br><em>' .
		wp_kses(
			__( '<strong>Please Note:</strong> Support for PHP 5.5 will be discontinued in 2020. After this, if no further action is taken, OptinMonster functionality will be disabled.', 'optin-monster-api' ),
			array(
				'strong' => array(),
				'em'     => array(),
			)
		) .
		'</em></p>' .
		'</div>';
	}

	/**
	 * Get the asset version for enqueued assets.
	 *
	 * @since  1.9.10
	 *
	 * @return mixed
	 */
	public function asset_version() {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			return time();
		}

		if ( defined( 'OPTINMONSTER_ENV' ) && 'dev' === strtolower( OPTINMONSTER_ENV ) ) {
			return time();
		}

		return $this->beta_enabled() && $this->beta_version( 'U' )
			? $this->beta_version( 'U' )
			: $this->version;
	}

	/**
	 * Check if beta is enabled.
	 *
	 * @since  2.0.0
	 *
	 * @return bool
	 */
	public function beta_enabled() {
		$option = $this->get_option();

		return apply_filters( 'optin_monster_beta_enabled', ! empty( $option['beta'] ) );
	}

	/**
	 * Get beta version.
	 *
	 * @since  2.0.0
	 *
	 * @param string $format The php date format.
	 *
	 * @return bool
	 */
	public function beta_version( $format = 'd M Y H:i:s' ) {
		$version = false;
		if ( false !== strpos( $this->version, 'beta' ) ) {
			$file = plugin_dir_path( __FILE__ ) . '.betaversion';
			if ( file_exists( $file ) ) {
				ob_start();
				include plugin_dir_path( __FILE__ ) . '.betaversion';
				$timestamp = ob_get_clean();

				if ( ! empty( $timestamp ) ) {
					$version = date( $format, (int) $timestamp );
				}
			}
		}

		return $version;
	}

	/**
	 * The access capability required for access to OptinMonster pages/settings.
	 *
	 * @since  2.0.0
	 *
	 * @param  string|null $slug The menu slug. Null by default.
	 *
	 * @return string The access capability.
	 */
	public function access_capability( $slug = null ) {
		return apply_filters( 'optin_monster_api_menu_cap', 'manage_options', $slug );
	}

	/**
	 * Check if user has access capability required for access to OptinMonster pages/settings.
	 *
	 * @since  2.0.0
	 *
	 * @param  string|null $slug The menu slug. Null by default.
	 *
	 * @return bool Whether user has access.
	 */
	public function can_access( $slug = null ) {
		return current_user_can( $this->access_capability( $slug ) );
	}

	/**
	 * Get app url, with proper query args set to ensure going to correct account, and setting return
	 * query arg to come back (if relevant on the destination page).
	 *
	 * @since  2.0.0
	 *
	 * @param  string $path The path on the app.
	 * @param  string $return_url Url to return. Will default to wp_get_referer().
	 *
	 * @return string        The app url.
	 */
	public function app_url( $path, $return_url = '' ) {
		return OMAPI_Urls::om_app( $path, $return_url );
	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return OMAPI
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof OMAPI ) ) {
			self::$instance = new OMAPI();
		}

		return self::$instance;
	}
}

register_activation_hook( __FILE__, 'optin_monster_api_activation_hook' );
/**
 * Fired when the plugin is activated.
 *
 * @since 1.0.0
 *
 * @global int $wp_version      The version of WordPress for this install.
 * @global object $wpdb         The WordPress database object.
 * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false otherwise.
 */
function optin_monster_api_activation_hook( $network_wide ) {

	global $wp_version;
	if ( version_compare( $wp_version, '4.7.0', '<' ) && ! defined( 'OPTINMONSTER_FORCE_ACTIVATION' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( sprintf( __( 'Sorry, but your version of WordPress does not meet OptinMonster\'s required version of <strong>4.7.0</strong> to run properly. The plugin has been deactivated. <a href="%s">Click here to return to the Dashboard</a>.', 'optin-monster-api' ), get_admin_url() ) );
	}

	$instance = OMAPI::get_instance();

	global $wpdb;
	if ( is_multisite() && $network_wide ) {
		$site_list = $wpdb->get_results( "SELECT * FROM $wpdb->blogs ORDER BY blog_id" );
		foreach ( (array) $site_list as $site ) {
			switch_to_blog( $site->blog_id );

			// Set default option.
			$instance->load_option();

			restore_current_blog();
		}
	} else {
		// Set default option.
		$instance->load_option();
	}

	// If we don't have api credentials, set up the redirect on plugin activation.
	if ( ! $instance->get_api_credentials() ) {
		$options                      = $instance->get_option();
		$options['welcome']['status'] = 'none';
		update_option( 'optin_monster_api', $options );
	}
}

register_uninstall_hook( __FILE__, 'optin_monster_api_uninstall_hook' );
/**
 * Fired when the plugin is uninstalled.
 *
 * @since 1.0.0
 *
 * @global object $wpdb The WordPress database object.
 */
function optin_monster_api_uninstall_hook() {

	$instance = OMAPI::get_instance();

	global $wpdb;
	if ( is_multisite() ) {
		$site_list = $wpdb->get_results( "SELECT * FROM $wpdb->blogs ORDER BY blog_id" );
		foreach ( (array) $site_list as $site ) {
			switch_to_blog( $site->blog_id );
			delete_option( 'optin_monster_api' );
			restore_current_blog();
		}
	} else {
		delete_option( 'optin_monster_api' );
	}

}

// Load the plugin.
$optin_monster_api = OMAPI::get_instance();

// Conditionally load the template tag.
if ( ! function_exists( 'optin_monster' ) ) {
	/**
	 * Primary template tag for outputting OptinMonster optins in templates.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $id     The ID of the optin to load.
	 * @param string $type   The type of field to query.
	 * @param array  $args   Associative array of args to be passed.
	 * @param bool   $return Flag to echo or return the optin HTML.
	 */
	function optin_monster( $id, $type = 'id', $args = array(), $return = false ) {

		// If we have args, build them into a shortcode format.
		$args_string = '';
		if ( ! empty( $args ) ) {
			foreach ( (array) $args as $key => $value ) {
				$args_string .= ' ' . $key . '="' . $value . '"';
			}
		}

		// Build the shortcode.
		$shortcode = ! empty( $args_string ) ? '[optin-monster ' . $type . '="' . $id . '"' . $args_string . ']' : '[optin-monster ' . $type . '="' . $id . '"]';

		// Return or echo the shortcode output.
		if ( $return ) {
			return do_shortcode( $shortcode );
		} else {
			echo do_shortcode( $shortcode );
		}

	}
}

// Backwards compat for the v1 template tag.
if ( ! function_exists( 'optin_monster_tag' ) ) {
	/**
	 * Primary template tag for outputting OptinMonster optins in templates (v1).
	 *
	 * @since 1.0.0
	 *
	 * @param int  $string The post name of the optin to load.
	 * @param bool $return Flag to echo or return the optin HTML.
	 */
	function optin_monster_tag( $id, $return = false ) {

		// Return the v2 template tag.
		return optin_monster( $id, 'slug', array(), $return );

	}
}
