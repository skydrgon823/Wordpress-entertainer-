<?php
/**
 * Gutenberg Blocks registration class.
 *
 * @since 1.9.10
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gutenberg Blocks registration class.
 *
 * @since 1.9.10
 */
class OMAPI_Blocks {

	/**
	 * Holds the class object.
	 *
	 * @since 1.9.10
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.9.10
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.9.10
	 *
	 * @var object
	 */
	public $base;

	/**
	 * The data to be localized for JS.
	 *
	 * @since 2.2.0
	 *
	 * @var array
	 */
	protected $data_for_js = array();

	/**
	 * The campaign options list array.
	 *
	 * @var null|array
	 */
	protected static $campaigns_list = null;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.9.10
	 */
	public function __construct() {

		if ( function_exists( 'register_block_type' ) ) {

			// Set our object.
			$this->set();
			$this->register_blocks();

			add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );

			// Register the global post campaign switch meta.
			register_meta(
				'post',
				'om_disable_all_campaigns',
				array(
					'show_in_rest' => true,
					'single'       => true,
					'type'         => 'boolean',
				)
			);
		}
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.9.10
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Register OptinMonster Gutenberg blocks on the backend.
	 *
	 * @since 1.9.10
	 */
	public function register_blocks() {
		register_block_type(
			'optinmonster/campaign-selector',
			array(
				'attributes'      => array(
					'slug'        => array(
						'type' => 'string',
					),
					'followrules' => array(
						'type' => 'boolean',
					),
				),
				'render_callback' => array( $this, 'get_output' ),
			)
		);
	}

	/**
	 * Load OptinMonster Gutenberg block scripts.
	 *
	 * @since 1.9.10
	 */
	public function enqueue_block_editor_assets() {
		$version    = $this->base->asset_version();
		$css_handle = $this->base->plugin_slug . '-blocks-admin';
		wp_enqueue_style( $css_handle, $this->base->url . 'assets/dist/css/blocks-admin.min.css', array(), $version );

		if ( function_exists( 'wp_add_inline_style' ) ) {
			$data = get_post_type()
				? get_post_type_object( get_post_type() )
				: array();

			$css = $this->base->get_min_css_view_contents( 'disable-warning-css.php', (object) $data );
			wp_add_inline_style( $css_handle, $css );
		}

		$campaign_selector_handle = $this->base->plugin_slug . '-gutenberg-campaign-selector';
		wp_enqueue_script(
			$campaign_selector_handle,
			$this->base->url . 'assets/dist/js/campaign-selector.min.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			$version,
			true
		);

		OMAPI_Utils::add_inline_script( $campaign_selector_handle, 'OMAPI', $this->get_data_for_js() );

		wp_enqueue_script(
			$this->base->plugin_slug . '-gutenberg-sidebar-settings',
			$this->base->url . 'assets/dist/js/om-settings.min.js',
			array( $campaign_selector_handle, 'wp-plugins', 'wp-edit-post', 'wp-element' ),
			$version
		);

		wp_enqueue_script(
			$this->base->plugin_slug . '-gutenberg-format-button',
			$this->base->url . 'assets/dist/js/om-format.min.js',
			array( $campaign_selector_handle, 'wp-rich-text', 'wp-element', 'wp-editor' ),
			$version
		);
	}

	/**
	 * Get OptinMonster data for Gutenberg JS.
	 *
	 * @since 2.2.0
	 *
	 * @param string $key The js data to get, by key.
	 *
	 * @return array Array of data for JS.
	 */
	public function get_data_for_js( $key = null ) {
		if ( empty( $this->data_for_js ) ) {

			// For translation of strings.
			$i18n                = array(
				'title'                           => esc_html__( 'OptinMonster', 'optin-monster-api' ),
				'description'                     => esc_html__( 'Select and display one of your OptinMonster inline campaigns.', 'optin-monster-api' ),
				'campaign_select'                 => esc_html__( 'Select Campaign...', 'optin-monster-api' ),
				'campaign_select_display'         => esc_html__( 'Select and display your email marketing call-to-action campaigns from OptinMonster', 'optin-monster-api' ),
				'create_new_popup'                => esc_html__( 'Create a New Popup Campaign', 'optin-monster-api' ),
				'create_new_inline'               => esc_html__( 'Create a New Inline Campaign', 'optin-monster-api' ),
				'block_settings'                  => esc_html__( 'OptinMonster Block Settings', 'optin-monster-api' ),
				'settings'                        => esc_html__( 'OptinMonster Settings', 'optin-monster-api' ),
				'campaign_selected'               => esc_html__( 'Campaign', 'optin-monster-api' ),
				'followrules_label'               => esc_html__( 'Use Output Settings', 'optin-monster-api' ),
				/* translators: %s - Output Settings (linked).*/
				'followrules_help'                => esc_html__( 'Ensure this campaign follows any conditions you\'ve selected in its %s.', 'optin-monster-api' ),
				'output_settings'                 => esc_html__( 'Output Settings', 'optin-monster-api' ),
				'no_sites'                        => esc_html__( 'Please create a free account or connect an existing account to use an OptinMonster block.', 'optin-monster-api' ),
				'no_sites_button_create_account'  => esc_html__( 'Create a Free Account', 'optin-monster-api' ),
				'no_sites_button_connect_account' => esc_html__( 'Connect an Existing Account', 'optin-monster-api' ),
				'no_inline_campaigns'             => esc_html__( 'You don’t have any inline campaigns yet!', 'optin-monster-api' ),
				'no_campaigns_help'               => esc_html__( 'Create an inline campaign to display in your posts and pages.', 'optin-monster-api' ),
				'create_inline_campaign'          => esc_html__( 'Create Your First Inline Campaign', 'optin-monster-api' ),
				'create_popup_campaign'           => esc_html__( 'Create Your First Popup', 'optin-monster-api' ),
				'no_campaigns_button_help'        => esc_html__( 'Learn how to create your first campaign', 'optin-monster-api' ),
				'found_error'                     => esc_html__( 'An error was encountered', 'optin-monster-api' ),
				'disable_all'                     => esc_html__( 'Disable all OptinMonster campaigns.', 'optin-monster-api' ),
				'view_all'                        => esc_html__( 'View All Campaigns', 'optin-monster-api' ),
				'not_connected'                   => esc_html__( 'You Have Not Connected with OptinMonster', 'optin-monster-api' ),
				'no_campaigns_yet'                => esc_html__( 'You don’t have any campaigns created yet.', 'optin-monster-api' ),
				'update_selected_popup'           => esc_html__( 'Update Selected OptinMonster Campaign', 'optin-monster-api' ),
				'open_popup'                      => esc_html__( 'Open an OptinMonster Popup', 'optin-monster-api' ),
				'remove_popup'                    => esc_html__( 'Remove Campaign Link', 'optin-monster-api' ),
			);
			$i18n['description'] = html_entity_decode( $i18n['description'], ENT_COMPAT, 'UTF-8' );

			$campaigns = $this->get_campaign_options();
			$site_ids  = $this->base->get_site_ids();

			$this->data_for_js = array(
				'logoUrl'           => $this->base->url . 'assets/css/images/icons/archie-icon.svg',
				'i18n'              => $i18n,
				'campaigns'         => array(
					'inline' => ! empty( $campaigns['inline'] ) ? $campaigns['inline'] : array(),
					'other'  => ! empty( $campaigns['other'] ) ? $campaigns['other'] : array(),
				),
				'site_ids'          => ! empty( $site_ids ) ? $site_ids : array(),
				'post'              => get_post(),
				'omEnv'             => defined( 'OPTINMONSTER_ENV' ) ? OPTINMONSTER_ENV : '',
				'templatesUri'      => OMAPI_Urls::templates(),
				'campaignsUri'      => OMAPI_Urls::campaigns(),
				'settingsUri'       => OMAPI_Urls::settings(),
				'wizardUri'         => OMAPI_Urls::wizard(),
				'apiUrl'            => esc_url_raw( OPTINMONSTER_APIJS_URL ),
				'omUserId'          => $this->base->get_option( 'userId' ),
				'outputSettingsUrl' => OMAPI_Urls::campaign_output_settings( '%s' ),
				'editUrl'           => OMAPI_Urls::om_app(
					'campaigns/--CAMPAIGN_SLUG--/edit/',
					rawurlencode( OMAPI_Urls::campaign_output_settings( '--CAMPAIGN_SLUG--' ) )
				),
				'monsterlink'       => esc_url_raw( OPTINMONSTER_SHAREABLE_LINK ) . '/c/',
			);
		}

		if ( $key ) {
			return isset( $this->data_for_js[ $key ] ) ? $this->data_for_js[ $key ] : null;
		}

		return $this->data_for_js;
	}

	/**
	 * Does the user have any associated OM sites registered?
	 *
	 * @since 2.2.0
	 *
	 * @return boolean
	 */
	public function has_sites() {
		$site_ids = $this->base->get_site_ids();

		return ! empty( $site_ids );
	}

	/**
	 * Get campaign options.
	 *
	 * @since 2.2.0
	 *
	 * @param  $titles_only Whether to include titles only, or separate data as array.
	 *
	 * @return array Array of campaign options.
	 */
	public function get_campaign_options( $titles_only = false ) {
		if ( null === self::$campaigns_list ) {
			$campaigns_list = array(
				'inline' => array(),
				'other'  => array(),
			);

			if ( $this->has_sites() ) {
				$campaigns = $this->base->get_campaigns();

				if ( ! empty( $campaigns ) ) {
					foreach ( $campaigns as $campaign ) {
						$title  = mb_strlen( $campaign->post_title, 'UTF-8' ) > 100
							? mb_substr( $campaign->post_title, 0, 97, 'UTF-8' ) . '...'
							: $campaign->post_title;
						$title .= ' (' . $campaign->post_name . ')';

						$type = 'inline' === $campaign->campaign_type ? 'inline' : 'other';

						$campaigns_list[ $type ][ $campaign->post_name ] = array(
							'title'   => $title,
							'pending' => empty( $campaign->enabled ),
						);
					}
				}
			}

			self::$campaigns_list = $campaigns_list;
		}

		if ( $titles_only && ! empty( self::$campaigns_list ) ) {
			$list = array();
			foreach ( self::$campaigns_list as $type => $type_list ) {
				foreach ( $type_list as $campaign_name => $args ) {
					$list[ $type ][ $campaign_name ] = $args['title'] . ( $args['pending'] ? ' [Pending]' : '' );
				}
			}

			return $list;
		}

		return self::$campaigns_list;
	}

	/**
	 * Get form HTML to display in a OptinMonster Gutenberg block.
	 *
	 * @param array $atts Attributes passed by OptinMonster Gutenberg block.
	 *
	 * @since 1.9.10
	 *
	 * @return string
	 */
	public function get_output( $atts ) {
		$is_rest  = defined( 'REST_REQUEST' ) && REST_REQUEST;
		$context  = ! empty( $_REQUEST['context'] ) ? sanitize_text_field( $_REQUEST['context'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$is_gutes = $is_rest && 'edit' === $context;

		// Our Guten-block handles the embed output manually.
		if ( $is_gutes ) {
			return;
		}

		$output = $this->base->shortcode->shortcode( $atts );

		if (
			! empty( $output )
			&& ! wp_script_is( $this->base->plugin_slug . '-api-script', 'enqueued' )
		) {

			// Need to enqueue the base api script.
			$this->base->output->api_script();
		}

		// Just return the shortcode output to the frontend.
		return $output;
	}

}
