<?php
/**
 * WooCommerce class.
 *
 * @since 1.7.0
 *
 * @package OMAPI
 * @author  Brandon Allen
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The WooCommerce class.
 *
 * @since 1.7.0
 */
class OMAPI_WooCommerce {

	/**
	 * Holds the class object.
	 *
	 * @since 1.7.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.7.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.7.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * The minimum WooCommerce version required.
	 *
	 * @since 1.9.0
	 *
	 * @var string
	 */
	const MINIMUM_VERSION = '3.2.0';

	/**
	 * Primary class constructor.
	 *
	 * @since 1.7.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();

		add_action( 'admin_enqueue_scripts', array( $this, 'handle_enqueuing_assets' ) );

		// Register WooCommerce Education Meta Boxes.
		add_action( 'add_meta_boxes', array( $this, 'register_metaboxes' ) );

		// Add custom OptinMonster note.
		add_action( 'admin_init', array( $this, 'maybe_store_note' ) );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.7.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Enqueue Metabox Assets
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function handle_enqueuing_assets() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( empty( $screen->id ) ) {
			return;
		}

		switch ( $screen->id ) {
			case 'shop_coupon':
			case 'product':
				return $this->enqueue_metabox_assets();
			case 'woocommerce_page_wc-admin':
				return $this->enqueue_marketing_education_assets();
		}
	}

	/**
	 * Enqueue Metabox Assets
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function enqueue_metabox_assets() {
		wp_enqueue_style(
			$this->base->plugin_slug . '-metabox',
			$this->base->url . 'assets/dist/css/metabox.min.css',
			array(),
			$this->base->asset_version()
		);

		wp_enqueue_script(
			$this->base->plugin_slug . '-metabox-js',
			$this->base->url . 'assets/dist/js/metabox.min.js',
			array(),
			$this->base->asset_version(),
			true
		);
	}

	/**
	 * Enqueue marketing box script.
	 * Adds an OM product education box on the WooCommerce Marketing page.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function enqueue_marketing_education_assets() {
		wp_enqueue_script(
			$this->base->plugin_slug . '-wc-marketing-box-js',
			$this->base->url . 'assets/dist/js/wc-marketing.min.js',
			array(),
			$this->base->asset_version(),
			true
		);

		add_action( 'admin_footer', array( $this, 'output_marketing_card_template' ) );
	}

	/**
	 * Handles outputting the marketing card html to the page.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function output_marketing_card_template() {
		$this->base->output_view( 'woocommerce-marketing-card.php' );
	}

	/**
	 * Connects WooCommerce to OptinMonster.
	 *
	 * @param array $data The array of consumer key and consumer secret.
	 *
	 * @since 1.7.0
	 *
	 * @returns WP_Error|array
	 */
	public function connect( $data ) {
		if ( empty( $data['consumerKey'] ) || empty( $data['consumerSecret'] ) ) {
			return new WP_Error(
				'omapi-invalid-woocommerce-keys',
				esc_html__( 'The consumer key or consumer secret appears to be invalid. Try again.', 'optin-monster-api' )
			);
		}

		$data['woocommerce'] = self::version();
		$data['restUrl']     = esc_url_raw( get_rest_url() );
		$data['homeUrl']     = esc_url_raw( home_url() );

		// Get the OptinMonster API credentials.
		$creds = $this->get_request_api_credentials();

		// Initialize the API class.
		$api = new OMAPI_Api( 'woocommerce/shop', $creds, 'POST', 'v2' );

		return $api->request( $data );
	}

	/**
	 * Disconnects WooCommerce from OptinMonster.
	 *
	 * @since 1.7.0
	 */
	public function disconnect() {

		// Get the OptinMonster API credentials.
		$creds = $this->get_request_api_credentials();

		// Get the shop.
		$shop = esc_attr( $this->base->get_option( 'woocommerce', 'shop' ) );

		if ( empty( $shop ) ) {
			return true;
		}

		// Initialize the API class.
		$api = new OMAPI_Api( 'woocommerce/shop/' . rawurlencode( $shop ), $creds, 'DELETE', 'v2' );

		return $api->request();
	}

	/**
	 * Returns the API credentials to be used in an API request.
	 *
	 * @since 1.7.0
	 *
	 * @return array
	 */
	public function get_request_api_credentials() {
		$creds = $this->base->get_api_credentials();

		// If set, return only the API key, not the legacy API credentials.
		if ( $creds['apikey'] ) {
			$_creds = array(
				'apikey' => $creds['apikey'],
			);
		} else {
			$_creds = array(
				'user' => $creds['user'],
				'key'  => $creds['key'],
			);
		}

		return $_creds;
	}

	/**
	 * Validates the passed consumer key and consumer secret.
	 *
	 * @since 1.7.0
	 *
	 * @param array $data The consumer key and consumer secret.
	 *
	 * @return array
	 */
	public function validate_keys( $data ) {
		$key    = isset( $data['consumer_key'] ) ? $data['consumer_key'] : '';
		$secret = isset( $data['consumer_secret'] ) ? $data['consumer_secret'] : '';

		if ( ! $key ) {
			return array(
				'error' => esc_html__( 'Consumer key is missing.', 'optin-monster-api' ),
			);
		}

		if ( ! $secret ) {
			return array(
				'error' => esc_html__( 'Consumer secret is missing.', 'optin-monster-api' ),
			);
		}

		// Attempt to find the passed consumer key in the database.
		$keys = $this->get_keys_by_consumer_key( $data['consumer_key'] );

		// If the consumer key is valid, then validate the consumer secret.
		if (
			empty( $keys['error'] )
			&& $this->is_consumer_secret_valid( $keys['consumer_secret'], $secret )
		) {
			$keys['consumer_key'] = $key;
		} else {
			$keys['error'] = esc_html__( 'Consumer secret is invalid.', 'optin-monster-api' );
		}

		return $keys;
	}

	/**
	 * Return the keys for the given consumer key.
	 *
	 * This is a rough copy of the same method used by WooCommerce.
	 *
	 * @since 1.7.0
	 *
	 * @param string $consumer_key The consumer key passed by the user.
	 *
	 * @return array
	 */
	private function get_keys_by_consumer_key( $consumer_key ) {
		global $wpdb;

		$consumer_key = wc_api_hash( sanitize_text_field( $consumer_key ) );

		$keys = $wpdb->get_row(
			$wpdb->prepare(
				"
					SELECT key_id, consumer_secret
					FROM {$wpdb->prefix}woocommerce_api_keys
					WHERE consumer_key = %s
				",
				$consumer_key
			),
			ARRAY_A
		);

		if ( empty( $keys ) ) {
			$keys = array(
				'error' => esc_html__( 'Consumer key is invalid.', 'optin-monster-api' ),
			);
		}

		return $keys;
	}

	/**
	 * Check if the consumer secret provided for the given user is valid
	 *
	 * This is a copy of the same method used by WooCommerce.
	 *
	 * @since 1.7.0
	 *
	 * @param string $keys_consumer_secret The consumer secret from the database.
	 * @param string $consumer_secret      The consumer secret passed by the user.
	 *
	 * @return bool
	 */
	private function is_consumer_secret_valid( $keys_consumer_secret, $consumer_secret ) {
		return hash_equals( $keys_consumer_secret, $consumer_secret );
	}

	/**
	 * Get WooCommerce API description and truncated key info by the key id.
	 *
	 * @since 1.7.0
	 *
	 * @param string $key_id The WooCommerce API key id.
	 *
	 * @return array
	 */
	public static function get_key_details_by_id( $key_id ) {
		if ( empty( $key_id ) ) {
			return array();
		}

		global $wpdb;

		$data = $wpdb->get_row(
			$wpdb->prepare(
				"
					SELECT key_id, description, truncated_key
					FROM {$wpdb->prefix}woocommerce_api_keys
					WHERE key_id = %d
				",
				absint( $key_id )
			),
			ARRAY_A
		);

		return $data;
	}

	/**
	 * Determines if the current site is has WooCommerce connected.
	 *
	 * Checks that the site stored in the OptinMonster option matches the
	 * current `siteurl` WP option, and that the saved key id still exists in
	 * the WooCommerce key table. If these two things aren't true, then the
	 * current site is not connected.
	 *
	 * @since 1.7.0
	 *
	 * @return boolean
	 */
	public static function is_connected() {
		// Get current site details.
		$site = OMAPI_Utils::parse_url( site_url() );
		$host = isset( $site['host'] ) ? $site['host'] : '';

		// Get any options we have stored.
		$option = OMAPI::get_instance()->get_option( 'woocommerce' );
		$shop   = isset( $option['shop'] ) ? $option['shop'] : '';
		$key_id = isset( $option['key_id'] ) ? $option['key_id'] : '';
		$key    = $key_id ? self::get_key_details_by_id( $key_id ) : array();

		return ! empty( $key['key_id'] ) && $host === $shop;
	}

	/**
	 * Add the category base to the category REST API response.
	 *
	 * @since 1.7.0
	 *
	 * @param WP_REST_Response $response The REST API response.
	 *
	 * @return WP_REST_Response
	 */
	public static function add_category_base_to_api_response( $response ) {
		return self::add_base_to_api_response( $response, 'category_rewrite_slug' );
	}

	/**
	 * Add the tag base to the tag REST API response.
	 *
	 * @since 1.7.0
	 *
	 * @param WP_REST_Response $response The REST API response.
	 *
	 * @return WP_REST_Response
	 */
	public static function add_tag_base_to_api_response( $response ) {
		return self::add_base_to_api_response( $response, 'tag_rewrite_slug' );
	}

	/**
	 * Add the category/tag base to the category/tag REST API response.
	 *
	 * @since 1.7.0
	 *
	 * @param WP_REST_Response $response The REST API response.
	 * @param string           $base     The base setting to retrieve.
	 *
	 * @return WP_REST_Response
	 */
	public static function add_base_to_api_response( $response, $base ) {
		$permalink_options = wc_get_permalink_structure();
		if ( isset( $permalink_options[ $base ] ) ) {
			$response->data['base'] = $permalink_options[ $base ];
		}

		return $response;
	}

	/**
	 * Return the WooCommerce versions string.
	 *
	 * @since 1.9.0
	 *
	 * @return string
	 */
	public static function version() {
		return defined( 'WC_VERSION' ) ? WC_VERSION : '0.0.0';
	}

	/**
	 * Determines if the passed version string passes the operator compare
	 * against the currently installed version of WooCommerce.
	 *
	 * Defaults to checking if the current WooCommerce version is greater than
	 * the passed version.
	 *
	 * @since 1.9.0
	 *
	 * @param string $version  The version to check.
	 * @param string $operator The operator to use for comparison.
	 *
	 * @return string
	 */
	public static function version_compare( $version = '', $operator = '>=' ) {
		return version_compare( self::version(), $version, $operator );
	}

	/**
	 * Determines if the current WooCommerce version meets the minimum version
	 * requirement.
	 *
	 * @since 1.9.0
	 *
	 * @return boolean
	 */
	public static function is_minimum_version() {
		return self::version_compare( self::MINIMUM_VERSION );
	}

	/**
	 * Add a OM product education metabox on the WooCommerce coupon and product pages.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function register_metaboxes() {
		add_meta_box(
			'woocommerce_promote_coupon_metabox',
			__( 'Promote this coupon', 'optin-monster-api' ),
			array( $this, 'output_coupon_metabox' ),
			'shop_coupon'
		);
		add_meta_box(
			'woocommerce_popup_metabox',
			__( 'Product Popups', 'optin-monster-api' ),
			array( $this, 'output_product_metabox' ),
			'product'
		);
	}

	/**
	 * Output the markup for the coupon metabox.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function output_coupon_metabox() {
		$args = $this->metabox_args();
		if ( ! $args['has_sites'] ) {
			$args['not_connected_message'] = esc_html__( 'Please create a Free Account or Connect an Existing Account to promote coupons.', 'optin-monster-api' );
		}
		$this->base->output_view( 'coupon-metabox.php', $args );
	}

	/**
	 * Output the markup for the product metabox.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function output_product_metabox() {
		$args = $this->metabox_args();
		if ( ! $args['has_sites'] ) {
			$args['not_connected_message'] = esc_html__( 'Please create a Free Account or Connect an Existing Account to use Product Popups.', 'optin-monster-api' );
		}
		$this->base->output_view( 'product-metabox.php', $args );
	}

	/**
	 * Get the site-connected args for the metaboxes.
	 *
	 * @since 2.3.0
	 *
	 * @return array  Array of site-connected args.
	 */
	protected function metabox_args() {
		$args = array(
			'has_sites' => $this->base->get_site_id(),
		);

		if ( ! $args['has_sites'] ) {
			$args['not_connected_title'] = esc_html__( 'You Have Not Connected with OptinMonster', 'optin-monster-api' );
		}

		return $args;
	}

	/**
	 * Adds a note to the WooCommerce inbox.
	 *
	 * @since 2.2.0
	 *
	 * @return int
	 */
	public function maybe_store_note() {

		// Check for Admin Note support.
		if ( ! class_exists( 'Automattic\WooCommerce\Admin\Notes\Notes' ) || ! class_exists( 'Automattic\WooCommerce\Admin\Notes\Note' ) ) {
			return;
		}

		// Make sure the WooCommerce Data Store is available.
		if ( ! class_exists( 'WC_Data_Store' ) ) {
			return;
		}

		$note_name = 'om-wc-grow-revenue';

		try {

			// Load the Admin Notes from the WooCommerce Data Store.
			$data_store = WC_Data_Store::load( 'admin-note' );

			$note_ids = $data_store->get_notes_with_name( $note_name );

		} catch ( Exception $e ) {
			return;
		}

		// This ensures we don't create a duplicate note.
		if ( ! empty( $note_ids ) ) {
			return;
		}

		// If we're here, we can create a new note.
		$note = new Automattic\WooCommerce\Admin\Notes\Note();
		$note->set_title( __( 'Grow your store revenue with OptinMonster', 'optin-monster-api' ) );
		$note->set_content( __( 'Create high-converting OptinMonster campaigns to promote product sales, reduce cart abandonment and incentivize purchases with time-sensitive coupon offers.', 'optin-monster-api' ) );
		$note->set_type( Automattic\WooCommerce\Admin\Notes\Note::E_WC_ADMIN_NOTE_INFORMATIONAL );
		$note->set_layout( 'plain' );
		$note->set_source( 'optinmonster' );
		$note->set_name( $note_name );
		$note->add_action(
			'om-note-primary',
			__( 'Create a campaign', 'optin-monster-api' ),
			'admin.php?page=optin-monster-templates',
			'unactioned',
			true
		);
		$note->add_action(
			'om-note-seconday',
			__( 'Learn more', 'optin-monster-api' ),
			'admin.php?page=optin-monster-about&selectedTab=getting-started',
			'unactioned',
			false
		);

		$note->save();
	}
}
