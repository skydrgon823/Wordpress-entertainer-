<?php
/**
 * Rest API Class, where we register/execute any REST API Routes
 *
 * @since 1.8.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rest Api class.
 *
 * @since 1.8.0
 */
class OMAPI_RestApi {

	/**
	 * The Base OMAPI Object
	 *
	 *  @since 1.8.0
	 *
	 * @var OMAPI
	 */
	protected $base;

	/**
	 * The REST API Namespace
	 *
	 *  @since 1.8.0
	 *
	 * @var string The namespace
	 */
	protected $namespace = 'omapp/v1';

	/**
	 * Whether request was given a valid api key.
	 *
	 *  @since 2.0.0
	 *
	 * @var null|bool
	 */
	protected $has_valid_api_key = null;

	/**
	 * Whether Access-Control-Allow-Headers header was set/updated by us.
	 *
	 *  @since 1.9.12
	 *
	 * @var bool
	 */
	protected $allow_header_set = false;

	/**
	 * Build our object.
	 *
	 * @since 1.8.0
	 */
	public function __construct() {
		$this->base = OMAPI::get_instance();
		$this->register_rest_routes();
	}

	/**
	 * Registers our Rest Routes for this App
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function register_rest_routes() {

		// Filter only available in WP 5.5.
		add_filter( 'rest_allowed_cors_headers', array( $this, 'set_allow_headers' ), 999 );

		// Fall-through to check if we still need to set header (WP < 5.5).
		add_filter( 'rest_send_nocache_headers', array( $this, 'fallback_set_allow_headers' ), 999 );

		// Fetch some quick info about this WP installation.
		register_rest_route(
			$this->namespace,
			'info',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'output_info' ),
			)
		);

		// Fetch in-depth support info about this WP installation.
		register_rest_route(
			$this->namespace,
			'support',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'support_info' ),
			)
		);

		// Route for triggering refreshing/syncing of all campaigns.
		register_rest_route(
			$this->namespace,
			'campaigns/refresh',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'has_valid_api_key' ),
				'callback'            => array( $this, 'refresh_campaigns' ),
			)
		);

		// Route for fetching the campaign data for specific campaign.
		register_rest_route(
			$this->namespace,
			'campaigns/(?P<id>\w+)',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'get_campaign_data' ),
			)
		);

		// Route for updating the campaign data.
		register_rest_route(
			$this->namespace,
			'campaigns/(?P<id>\w+)',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'has_valid_api_key' ),
				'callback'            => array( $this, 'update_campaign_data' ),
			)
		);

		// Route for triggering refreshing/syncing of a single campaign.
		register_rest_route(
			$this->namespace,
			'campaigns/(?P<id>[\w-]+)/sync',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'has_valid_api_key' ),
				'callback'            => array( $this, 'sync_campaign' ),
			)
		);

		// Route for fetching data/resources needed for the campaigns.
		register_rest_route(
			$this->namespace,
			'resources',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'get_wp_resources' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'notifications',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'get_notifications' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'notifications/dismiss',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'dismiss_notification' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'notifications/create',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'create_event_notification' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'plugins',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'get_am_plugins_list' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'plugins',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'handle_plugin_action' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'woocommerce/autogenerate',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'can_update_settings' ),
				'callback'            => array( $this, 'woocommerce_autogenerate' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'woocommerce/save',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'can_update_settings' ),
				'callback'            => array( $this, 'woocommerce_save' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'woocommerce/disconnect',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'can_update_settings' ),
				'callback'            => array( $this, 'woocommerce_disconnect' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'woocommerce/key',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'woocommerce_get_key' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'api',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'can_store_api_key' ),
				'callback'            => array( $this, 'init_api_key_connection' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'api',
			array(
				'methods'             => 'DELETE',
				'permission_callback' => array( $this, 'can_delete_api_key' ),
				'callback'            => array( $this, 'disconnect' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'settings',
			array(
				'methods'             => 'GET',
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'get_settings' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'settings',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'can_update_settings' ),
				'callback'            => array( $this, 'update_settings' ),
			)
		);
	}

	/**
	 * Filters the list of request headers that are allowed for CORS requests,
	 * and ensures our API key is allowed.
	 *
	 * @since 1.9.12
	 *
	 * @param string[] $allow_headers The list of headers to allow.
	 *
	 * @return string[]
	 */
	public function set_allow_headers( $allow_headers ) {
		$allow_headers[]        = 'X-OptinMonster-ApiKey';
		$this->allow_header_set = true;

		// remove fall-through.
		remove_filter( 'rest_send_nocache_headers', array( $this, 'fallback_set_allow_headers' ), 999 );

		return $allow_headers;
	}

	/**
	 * Fallback to make sure we set the allow headers.
	 *
	 * @since  1.9.12
	 *
	 * @param bool $rest_send_nocache_headers Whether to send no-cache headers.
	 *                                        We ignore this, because we're simply using this
	 *                                        as an action hook.
	 *
	 * @return bool Unchanged result.
	 */
	public function fallback_set_allow_headers( $rest_send_nocache_headers ) {
		if ( ! $this->allow_header_set && ! headers_sent() ) {
			foreach ( headers_list() as $header ) {
				if ( 0 === strpos( $header, 'Access-Control-Allow-Headers: ' ) ) {

					list( $key, $value ) = explode( 'Access-Control-Allow-Headers: ', $header );
					if ( false === strpos( $value, 'X-OptinMonster-ApiKey' ) ) {
						header( 'Access-Control-Allow-Headers: ' . $value . ', X-OptinMonster-ApiKey' );
					}

					$this->allow_header_set = true;
					break;
				}
			}
		}

		return $rest_send_nocache_headers;
	}

	/**
	 * Triggers refreshing our campaigns.
	 *
	 * Route: POST omapp/v1/campaigns/refresh
	 *
	 * @since 1.9.10
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function refresh_campaigns() {
		$result = $this->base->refresh->refresh();

		return is_wp_error( $result )
			? $result
			: new WP_REST_Response(
				array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
				200
			);
	}

	/**
	 * Fetch some quick info about this WP installation
	 * (WP version, plugin version, rest url, home url, WooCommerce version)
	 *
	 * Route: GET omapp/v1/info
	 *
	 * @since  1.9.10
	 *
	 * @return WP_REST_Response
	 */
	public function output_info() {
		return new WP_REST_Response( $this->base->refresh->get_info_args(), 200 );
	}

	/**
	 * Fetch in-depth support info about this WP installation.
	 * Used for the debug PDF, but can also be requested by support staff with the right api key.
	 *
	 * Route: GET omapp/v1/support
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response
	 */
	public function support_info( $request ) {
		$support = new OMAPI_Support();

		$format = $request->get_param( 'format' );
		if ( empty( $format ) ) {
			$format = 'raw';
		}

		return new WP_REST_Response( $support->get_support_data( $format ), 200 );
	}

	/**
	 * Triggering refreshing/syncing of a single campaign.
	 *
	 * Route: POST omapp/v1/campaigns/(?P<id>[\w-]+)/sync
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 * @return WP_REST_Response The API Response
	 */
	public function sync_campaign( $request ) {
		$campaign_id = $request->get_param( 'id' );

		if ( empty( $campaign_id ) ) {
			return new WP_REST_Response(
				array( 'message' => esc_html__( 'No campaign ID given.', 'optin-monster-api' ) ),
				400
			);
		}

		$this->base->refresh->sync( $campaign_id, $request->get_param( 'legacy' ) );

		return new WP_REST_Response(
			array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
			200
		);
	}

	/**
	 * Gets all the data needed for the campaign dashboard for a given campaign.
	 *
	 * Route: GET omapp/v1/campaigns/(?P<id>\w+)
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 * @return WP_REST_Response The API Response
	 */
	public function get_campaign_data( $request ) {
		try {
			$campaign_id = $request->get_param( 'id' );

			if ( empty( $campaign_id ) ) {
				return new WP_REST_Response(
					array( 'message' => esc_html__( 'No campaign ID given.', 'optin-monster-api' ) ),
					400
				);
			}

			$campaign = $this->base->get_optin_by_slug( $campaign_id );

			if ( empty( $campaign->ID ) ) {
				$this->base->refresh->sync( $campaign_id );
				if ( is_wp_error( $this->base->refresh->error ) ) {
					$e = new OMAPI_WpErrorException();
					throw $e->setWpError( $this->base->refresh->error );
				}

				$campaign = $this->base->get_optin_by_slug( $campaign_id );
			}

			if ( empty( $campaign->ID ) ) {
				return new WP_REST_Response(
					array(
						/* translators: %s: the campaign post id. */
						'message' => sprintf( esc_html__( 'Could not find campaign by given ID: %s. Are you sure campaign is associated with this site?', 'optin-monster-api' ), $campaign_id ),
					),
					404
				);
			}

			// Get Campaigns Data.
			$data = $this->base->collect_campaign_data( $campaign );
			$data = apply_filters( 'optin_monster_api_setting_ui_data_for_campaign', $data, $campaign );

			return new WP_REST_Response( $data, 200 );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Updates data for given campaign.
	 *
	 * Route: PUT omapp/v1/campaigns/(?P<id>\w+)
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function update_campaign_data( $request ) {
		$campaign_id = $request->get_param( 'id' );

		// If no campaign_id, return error.

		$campaign = $this->base->get_optin_by_slug( $campaign_id );

		// If no campaign, return 404.

		// Get the Request Params.
		$fields = json_decode( $request->get_body(), true );

		if ( ! empty( $fields['taxonomies'] ) ) {

			if ( isset( $fields['taxonomies']['categories'] ) ) {
				$fields['categories'] = $fields['taxonomies']['categories'];
			}

			// Save the data from the regular taxonomies fields into the WC specific tax field.
			// For back-compatibility.
			$fields['is_wc_product_category'] = isset( $fields['taxonomies']['product_cat'] )
				? $fields['taxonomies']['product_cat']
				: array();
			$fields['is_wc_product_tag']      = isset( $fields['taxonomies']['product_tag'] )
				? $fields['taxonomies']['product_tag']
				: array();
		}

		// Escape Parameters as needed.
		// Update Post Meta.
		foreach ( $fields as $key => $value ) {
			$value = $this->sanitize( $value );

			switch ( $key ) {
				default:
					update_post_meta( $campaign->ID, '_omapi_' . $key, $value );
			}
		}

		return new WP_REST_Response(
			array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
			200
		);
	}

	/**
	 * Gets all the data/resources needed for the campaigns.
	 *
	 * Route: GET omapp/v1/resources
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 * @return WP_REST_Response The API Response
	 */
	public function get_wp_resources( $request ) {
		global $wpdb;

		$excluded = $request->get_param( 'excluded' );
		$excluded = ! empty( $excluded )
			? explode( ',', $excluded )
			: array();

		if ( $request->get_param( 'refresh' ) ) {
			$result = $this->base->refresh->refresh();
			if ( is_wp_error( $result ) ) {
				return $result;
			}
		}

		$campaign_data = array();
		if ( ! in_array( 'campaigns', $excluded, true ) ) {
			// Get Campaigns Data.
			$campaigns = $this->base->get_optins( array( 'post_status' => 'any' ) );
			$campaigns = ! empty( $campaigns ) ? $campaigns : array();

			foreach ( $campaigns as $campaign ) {
				$campaign_data[] = $this->base->collect_campaign_data( $campaign );
			}
		}

		$has_woo  = OMAPI::is_woocommerce_active();
		$mailpoet = $this->base->is_mailpoet_active();

		$taxonomy_map = array();
		if ( ! in_array( 'taxonomies', $excluded, true ) ) {

			// Get Taxonomies Data.
			$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
			$taxonomies = apply_filters( 'optin_monster_api_setting_ui_taxonomies', $taxonomies );

			foreach ( $taxonomies as $taxonomy ) {
				if ( 'category' === $taxonomy->name ) {
					$cats                       = get_categories();
					$taxonomy_map['categories'] = array(
						'name'  => 'categories',
						'label' => ucwords( $taxonomy->label ),
						'terms' => is_array( $cats ) ? array_values( $cats ) : array(),
						'for'   => $taxonomy->object_type,
					);
					continue;
				}

				$terms = get_terms(
					array(
						'taxonomy' => $taxonomy->name,
						'get'      => 'all',
					)
				);

				$taxonomy_map[ $taxonomy->name ] = array(
					'name'  => $taxonomy->name,
					'label' => ucwords( $taxonomy->label ),
					'terms' => is_array( $terms ) ? array_values( $terms ) : array(),
					'for'   => $taxonomy->object_type,
				);
			}
		}

		$posts = array();
		if ( ! in_array( 'posts', $excluded, true ) ) {

			// Posts query.
			$post_types = sanitize_text_field( implode( '","', get_post_types( array( 'public' => true ) ) ) );
			$posts      = $wpdb->get_results( "SELECT ID AS `value`, post_title AS `name` FROM {$wpdb->prefix}posts WHERE post_type IN (\"{$post_types}\") AND post_status IN('publish', 'future') ORDER BY post_title ASC", ARRAY_A );
		}

		$post_types = ! in_array( 'post_types', $excluded, true )
			? array_values( get_post_types( array( 'public' => true ), 'object' ) )
			: array();

		// Get "Config" data.
		$config = array(
			'hasMailPoet'    => $mailpoet,
			'isWooActive'    => $has_woo,
			'isWooConnected' => OMAPI_WooCommerce::is_connected(),
			'mailPoetLists'  => $mailpoet && ! in_array( 'mailPoetLists', $excluded, true )
				? $this->base->mailpoet->get_lists()
				: array(),
			'mailPoetFields' => $mailpoet && ! in_array( 'mailPoetFields', $excluded, true )
				? $this->base->mailpoet->get_custom_fields()
				: array(),
		);

		$response_data = apply_filters(
			'optin_monster_api_setting_ui_data',
			array(
				'config'     => $config,
				'campaigns'  => $campaign_data,
				'taxonomies' => $taxonomy_map,
				'posts'      => $posts,
				'post_types' => $post_types,
				'siteId'     => $this->base->get_site_id(),
				'siteIds'    => $this->base->get_site_ids(),
			)
		);

		return new WP_REST_Response( $response_data, 200 );
	}

	/**
	 * Gets the list of AM notifications.
	 *
	 * Route: GET omapp/v1/notifications
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function get_notifications( $request ) {
		add_filter( 'optin_monster_api_admin_notifications_has_access', array( $this, 'maybe_allow' ) );

		// Make sure we have all the required user parameters.
		$this->base->actions->maybe_fetch_missing_data();

		if ( ! $this->base->notifications->has_access() ) {
			return new WP_REST_Response( array(), 206 );
		}

		return new WP_REST_Response( $this->base->notifications->get( true ), 200 );
	}

	/**
	 * Dismiss a given notifications.
	 *
	 * Route: POST omapp/v1/notifications/dismiss
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function dismiss_notification( $request ) {
		add_filter( 'optin_monster_api_admin_notifications_has_access', array( $this, 'maybe_allow' ) );

		$ids = $request->get_json_params();
		if ( $this->base->notifications->dismiss( $ids ) ) {
			return new WP_REST_Response( $this->base->notifications->get( true ), 200 );
		}

		return new WP_REST_Response(
			array(
				'message' => sprintf(
					/* translators: %s: the notification id(s). */
					esc_html__( 'Could not dismiss: %s', 'optin-monster-api' ),
					implode( ', ', $ids )
				),
			),
			400
		);
	}

	/**
	 * Dismiss a given notifications.
	 *
	 * Route: POST omapp/v1/notifications/create
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function create_event_notification( $request ) {
		add_filter( 'optin_monster_api_admin_notifications_has_access', array( $this, 'maybe_allow' ) );

		$payload = $request->get_json_params();

		$errors = array();
		foreach ( $payload as $notification ) {
			$added = $this->base->notifications->add_event( $notification );
			if ( is_wp_error( $added ) ) {
				$errors[] = $added;
			}
		}

		$updated = $this->base->notifications->get( true );

		if ( ! empty( $errors ) ) {
			$message = count( $payload ) > 1
				? sprintf(
					/* translators: %s: "Some" or "one". */
					esc_html__( 'Could not create %s of the event notifications!', 'optin-monster-api' ),
					count( $errors ) > 1 ? esc_html__( 'some', 'optin-monster-api' ) : esc_html__( 'one', 'optin-monster-api' )
				)
				: esc_html__( 'Could not create event notification!', 'optin-monster-api' );

			foreach ( $errors as $error ) {
				$message .= '<br>- ' . $error->get_error_message();
			}

			return new WP_REST_Response(
				array(
					'message'       => $message,
					'notifications' => $updated,
				),
				400
			);
		}

		return new WP_REST_Response( $updated, 200 );
	}

	/**
	 * Maybe allow api-key authenticted user to see notifications.
	 *
	 * @since  2.0.0
	 *
	 * @param  bool $access If current user has access to notifications.
	 *
	 * @return bool          Maybe modified access.
	 */
	public function maybe_allow( $access ) {
		if ( ! $access && $this->has_valid_api_key ) {

			$access = ! $this->base->get_option( 'hide_announcements' );
		}

		return $access;
	}

	/**
	 * Gets the list of AM plugins.
	 *
	 * Route: GET omapp/v1/plugins
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function get_am_plugins_list( $request ) {
		$plugins = new OMAPI_Plugins();
		$data    = $plugins->get_list( true );

		$install_nonce  = wp_create_nonce( 'install_plugin' );
		$activate_nonce = wp_create_nonce( 'activate_plugin' );

		foreach ( $data as $plugin_id => $plugin ) {
			$data[ $plugin_id ]['install_nonce']  = $install_nonce;
			$data[ $plugin_id ]['activate_nonce'] = $activate_nonce;
		}

		return new WP_REST_Response( array_values( $data ), 200 );
	}

	/**
	 * Handles installing or activating an AM plugin.
	 *
	 * Route: POST omapp/v1/plugins
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function handle_plugin_action( $request ) {
		try {

			$nonce = $request->get_param( 'nonce' );
			if ( empty( $nonce ) ) {
				throw new Exception( esc_html__( 'Missing security token!', 'optin-monster-api' ), rest_authorization_required_code() );
			}

			$action       = $request->get_param( 'installAction' );
			$nonce_action = 'install' === $action ? 'install' : 'activate';

			// Check the nonce.
			$result = wp_verify_nonce( $nonce, $nonce_action . '_plugin' );
			if ( ! $result ) {
				throw new Exception( esc_html__( 'Security token invalid!', 'optin-monster-api' ), rest_authorization_required_code() );
			}

			$plugins = new OMAPI_Plugins();
			$url     = $request->get_param( 'url' );

			if ( 'install' === $nonce_action ) {
				if ( empty( $url ) ) {
					throw new Exception( esc_html__( 'Plugin install URL required.', 'optin-monster-api' ), 400 );
				}

				return new WP_REST_Response( $plugins->install_plugin( $url ), 200 );
			}

			$id = $request->get_param( 'id' );
			if ( empty( $id ) ) {
				throw new Exception( esc_html__( 'Plugin Id required.', 'optin-monster-api' ), 400 );
			}

			return new WP_REST_Response( $plugins->activate_plugin( $id ), 200 );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Handles auto-generating the WooCommerce API key/secret.
	 *
	 * Route: POST omapp/v1/woocommerce/autogenerate
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function woocommerce_autogenerate( $request ) {
		try {

			$auto_generated_keys = $this->base->save->woocommerce_autogenerate();
			if ( is_wp_error( $auto_generated_keys ) ) {
				$e = new OMAPI_WpErrorException();
				throw $e->setWpError( $auto_generated_keys );
			}

			if ( empty( $auto_generated_keys ) ) {
				throw new Exception( esc_html__( 'WooCommerce REST API keys could not be auto-generated on your behalf. Please try again.', 'optin-monster-api' ), 400 );
			}

			$data = $this->base->get_option();

			// Merge data array, with auto-generated keys array.
			$data = array_merge( $data, $auto_generated_keys );

			$this->base->save->woocommerce_connect( $data );

			if ( ! empty( $this->base->save->error ) ) {
				throw new Exception( $this->base->save->error, 400 );
			}

			return $this->woocommerce_get_key( $request );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Handles saving the WooCommerce API key/secret.
	 *
	 * Route: POST omapp/v1/woocommerce/save
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function woocommerce_save( $request ) {
		try {

			$woo_key = $request->get_param( 'key' );
			if ( empty( $woo_key ) ) {
				throw new Exception( esc_html__( 'Consumer key missing!', 'optin-monster-api' ), 400 );
			}

			$woo_secret = $request->get_param( 'secret' );
			if ( empty( $woo_secret ) ) {
				throw new Exception( esc_html__( 'Consumer secret missing!', 'optin-monster-api' ), 400 );
			}

			$data = array(
				'consumer_key'    => $woo_key,
				'consumer_secret' => $woo_secret,
			);

			$this->base->save->woocommerce_connect( $data );

			if ( ! empty( $this->base->save->error ) ) {
				throw new Exception( $this->base->save->error, 400 );
			}

			return $this->woocommerce_get_key( $request );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Handles disconnecting the WooCommerce API key/secret.
	 *
	 * Route: POST omapp/v1/woocommerce/disconnect
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function woocommerce_disconnect( $request ) {
		try {

			$this->base->save->woocommerce_disconnect( array() );

			if ( ! empty( $this->base->save->error ) ) {
				throw new Exception( $this->base->save->error, 400 );
			}

			return new WP_REST_Response(
				array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
				200
			);

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Gets the associated WooCommerce API key data.
	 *
	 * Route: GET omapp/v1/woocommerce/key
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function woocommerce_get_key( $request ) {
		try {

			$keys_tab       = OMAPI_WooCommerce::version_compare( '3.4.0' ) ? 'advanced' : 'api';
			$keys_admin_url = admin_url( "admin.php?page=wc-settings&tab={$keys_tab}&section=keys" );

			if ( ! OMAPI_WooCommerce::is_minimum_version() && OMAPI_WooCommerce::is_connected() ) {

				$error = '<p>' . esc_html( sprintf( __( 'OptinMonster requires WooCommerce %s or above.', 'optin-monster-api' ), OMAPI_WooCommerce::MINIMUM_VERSION ) ) . '</p>'
					. '<p>' . esc_html_x( 'This site is currently running: ', 'the current version of WooCommerce: "WooCommerce x.y.z"', 'optin-monster-api' )
					. '<code>WooCommerce ' . esc_html( OMAPI_WooCommerce::version() ) . '</code>.</p>'
					. '<p>' . esc_html__( 'Please upgrade to the latest version of WooCommerce to enjoy deeper integration with OptinMonster.', 'optin-monster-api' ) . '</p>';

				throw new Exception( $error, 404 );
			}

			if ( ! OMAPI_WooCommerce::is_connected() ) {
				$error = '<p>' . sprintf( __( 'In order to integrate WooCommerce with the Display Rules in the campaign builder, OptinMonster needs <a href="%s" target="_blank">WooCommerce REST API credentials</a>. OptinMonster only needs Read access permissions to work.', 'optin-monster-api' ), esc_url( $keys_admin_url ) ) . '</p>';

				throw new Exception( $error, 404 );
			}

			// Set some default key details.
			$defaults = array(
				'key_id'        => '',
				'description'   => esc_html__( 'no description found', 'optin-monster-api' ),
				'truncated_key' => esc_html__( 'no truncated key found', 'optin-monster-api' ),
			);

			// Get the key details.
			$key_id  = $this->base->get_option( 'woocommerce', 'key_id' );
			$details = OMAPI_WooCommerce::get_key_details_by_id( $key_id );
			$r       = wp_parse_args( array_filter( $details ), $defaults );

			return new WP_REST_Response(
				array(
					'id'          => $key_id,
					'description' => esc_html( $r['description'] ),
					'truncated'   => esc_html( $r['truncated_key'] ),
					'editUrl'     => esc_url_raw( add_query_arg( 'edit-key', $r['key_id'], $keys_admin_url ) ),
				),
				200
			);

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Determine if we can store settings.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function can_update_settings( $request ) {
		$result = $this->check_dev_server_request( $request );
		if ( is_bool( $result ) ) {
			return $result;
		}

		try {

			$this->verify_request_nonce( $request );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}

		return true;
	}

	/**
	 * Handles storing the API key and initiating the API connection.
	 *
	 * Route: POST omapp/v1/api
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function init_api_key_connection( $request ) {
		try {
			$apikey = $request->get_param( 'key' );
			if ( empty( $apikey ) ) {
				throw new Exception( esc_html__( 'API Key Missing!', 'optin-monster-api' ), 400 );
			}

			$result = OMAPI_ApiKey::init_connection( $apikey );
			if ( is_wp_error( $result ) ) {
				$e = new OMAPI_WpErrorException();
				throw $e->setWpError( $result );
			}

			return new WP_REST_Response( $result, 200 );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Determine if we can store the given api key.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function can_store_api_key( $request ) {
		$result = $this->check_dev_server_request( $request );
		if ( is_bool( $result ) ) {
			return $result;
		}

		try {

			$this->verify_request_nonce( $request );

			$apikey = $request->get_param( 'key' );
			if ( empty( $apikey ) ) {
				throw new Exception( esc_html__( 'API Key Missing!', 'optin-monster-api' ), 400 );
			}

			$result = OMAPI_ApiKey::verify( $apikey );

			if ( is_wp_error( $result ) ) {
				$e = new OMAPI_WpErrorException();
				throw $e->setWpError( $result );
			}
		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}

		return true;
	}

	/**
	 * Handles disconnecting the API key.
	 *
	 * Route: DELETE omapp/v1/api
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function disconnect( $request ) {
		try {

			OMAPI_ApiKey::disconnect();

			return new WP_REST_Response(
				array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
				204
			);

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Determine if we can disconnect the api key.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function can_delete_api_key( $request ) {
		$result = $this->check_dev_server_request( $request );
		if ( is_bool( $result ) ) {
			return $result;
		}

		try {

			$this->verify_request_nonce( $request );

			if ( ! OMAPI_ApiKey::has_credentials() ) {
				throw new Exception( esc_html__( 'API Key Missing!', 'optin-monster-api' ), 400 );
			}
		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}

		return true;
	}

	/**
	 * Handles getting the misc. settings.
	 *
	 * Route: GET omapp/v1/settings
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function get_settings( $request ) {

		$defaults = $this->base->default_options();
		$options  = $this->base->get_option();

		$misc_settings = array();
		foreach ( array( 'auto_updates', 'usage_tracking', 'hide_announcements' ) as $key ) {
			$misc_settings[ $key ] = isset( $options[ $key ] )
				? $options[ $key ]
				: $defaults[ $key ];
		}

		return new WP_REST_Response( $misc_settings, 200 );
	}

	/**
	 * Handles updating settings.
	 *
	 * Route: POST omapp/v1/settings
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function update_settings( $request ) {
		try {

			$settings = $request->get_param( 'settings' );
			if ( empty( $settings ) ) {
				throw new Exception( esc_html__( 'Settings Missing!', 'optin-monster-api' ), 400 );
			}

			$allowed_settings = array(
				'auto_updates'       => array(
					'validate' => 'is_string',
				),
				'usage_tracking'     => array(
					'validate' => 'is_bool',
				),
				'hide_announcements' => array(
					'validate' => 'is_bool',
				),
			);

			$options      = $this->base->get_option();
			$has_settings = false;

			foreach ( $settings as $setting => $value ) {
				if ( empty( $allowed_settings[ $setting ] ) ) {
					continue;
				}

				$has_settings = true;

				if ( isset( $options[ $setting ] ) && $value === $options[ $setting ] ) {
					continue;
				}

				$validator = $allowed_settings[ $setting ]['validate'];

				if ( call_user_func( $validator, $value ) ) {
					switch ( $validator ) {
						case 'is_bool':
							$options[ $setting ] = ! ! $value;
							break;
						case 'is_string':
							$options[ $setting ] = sanitize_text_field( $value );
							break;
					}
				}
			}

			if ( ! $has_settings ) {
				throw new Exception( esc_html__( 'Invalid Settings!', 'optin-monster-api' ), 400 );
			}

			// Save the updated option.
			$this->base->save->update_option( $options );

			return $this->get_settings( $request );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Sanitize value recursively.
	 *
	 * @since  1.9.10
	 *
	 * @param  mixed $value The value to sanitize.
	 *
	 * @return mixed The sanitized value.
	 */
	public function sanitize( $value ) {
		if ( empty( $value ) ) {
			return $value;
		}

		if ( is_scalar( $value ) ) {
			return sanitize_text_field( $value );
		}

		if ( is_array( $value ) ) {
			return array_map( array( $this, 'sanitize' ), $value );
		}
	}

	/**
	 * Determine if OM API key is provided and valid.
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function has_valid_api_key( $request ) {
		$header = $request->get_header( 'X-OptinMonster-ApiKey' );

		// Use this API Key to validate.
		if ( ! $this->validate_api_key( $header ) ) {
			return new WP_Error(
				'omapp_rest_forbidden',
				esc_html__( 'Could not verify your API Key.', 'optin-monster-api' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return $this->has_valid_api_key;
	}

	/**
	 * Determine if logged in or OM API key is provided and valid.
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function logged_in_or_has_api_key( $request ) {
		if (
			! empty( $_SERVER['HTTP_REFERER'] )
			&& false !== strpos( $_SERVER['HTTP_REFERER'], 'https://wp.app.optinmonster.test' )
			&& 'OPTIONS' === $_SERVER['REQUEST_METHOD']
		) {
			return true;
		}

		return is_user_logged_in() || true === $this->has_valid_api_key( $request );
	}

	/**
	 * Validate this API Key
	 * We validate an API Key by fetching the Sites this key can fetch
	 * And then confirming that this key has access to at least one of these sites
	 *
	 * @since 1.8.0
	 *
	 * @param string $apikey The OM api key.
	 *
	 * @return bool True if the Key can be validated
	 */
	public function validate_api_key( $apikey ) {
		$this->has_valid_api_key = OMAPI_ApiKey::validate( $apikey );

		return $this->has_valid_api_key;
	}

	/**
	 * Convert an exception to a REST API response object.
	 *
	 * @since  2.0.0
	 *
	 * @param  Exception $e The exception.
	 *
	 * @return WP_REST_Response
	 */
	protected function exception_to_response( Exception $e ) {

		// Return WP_Error objects directly.
		if ( $e instanceof OMAPI_WpErrorException && $e->getWpError() ) {
			return $e->getWpError();
		}

		$data = ! empty( $e->data ) ? $e->data : array();

		if ( rest_authorization_required_code() === $e->getCode() ) {

			$data = wp_parse_args(
				$data,
				array(
					'status' => $e->getCode(),
				)
			);

			return new WP_Error( 'omapp_rest_forbidden', $e->getMessage(), $data );
		}

		$data = wp_parse_args(
			$data,
			array(
				'message' => $e->getMessage(),
			)
		);

		return new WP_REST_Response( $data, $e->getCode() );
	}

	/**
	 * Whether request is being generated from the Vue dev server.
	 *
	 * @since  2.0.0
	 *
	 * @return boolean
	 */
	public function is_dev_server_request() {
		return ! empty( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], 'https://wp.app.optinmonster.test' );
	}

	/**
	 * Check if initial logged-in check passes (or it's a dev server OPTIONS request).
	 *
	 * @since  2.0.0
	 *
	 * @param  WP_REST_Request $request The REST request.
	 *
	 * @return bool|null       True if passed, false if not, null if we can continue checking.
	 */
	protected function check_dev_server_request( $request ) {
		$dev_server_request = $this->is_dev_server_request();

		$dev_server_options_request = $dev_server_request && 'OPTIONS' === $_SERVER['REQUEST_METHOD'];

		// If this is an OPTIONS requst from the dev server, then pass it.
		if ( $dev_server_options_request ) {
			return true;
		}

		// To to this action, user has to be logged in.
		if ( ! is_user_logged_in() && ! $dev_server_request ) {
			return false;
		}

		return null;
	}

	/**
	 * Verify the request nonce and throw an exception if verification fails.
	 *
	 * @since  2.0.0
	 *
	 * @param  WP_REST_Request $request The REST request.
	 *
	 * @return void
	 */
	public function verify_request_nonce( $request ) {
		$nonce = $request->get_param( 'nonce' );
		if ( empty( $nonce ) ) {
			$nonce = $request->get_header( 'X-WP-Nonce' );
		}

		$dev_server_request = $this->is_dev_server_request();

		if ( ! $dev_server_request && empty( $nonce ) ) {
			throw new Exception( esc_html__( 'Missing security token!', 'optin-monster-api' ), rest_authorization_required_code() );
		}

		// Check the nonce.
		$result = wp_verify_nonce( $nonce, 'wp_rest' );
		if ( ! $dev_server_request && ! $result ) {
			throw new Exception( esc_html__( 'Security token invalid!', 'optin-monster-api' ), rest_authorization_required_code() );
		}
	}
}
