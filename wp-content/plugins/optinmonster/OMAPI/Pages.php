<?php
/**
 * Pages class.
 *
 * @since 1.9.10
 *
 * @package OMAPI
 * @author  Erik Jonasson
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pages class.
 *
 * @since 1.9.10
 *
 * @package OMAPI
 * @author  Erik Jonasson
 */
class OMAPI_Pages {

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
	 * @var OMAPI
	 */
	public $base;

	/**
	 * The admin title tag format.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $title_tag = '';

	/**
	 * The registered pages.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	protected $pages = array();

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.9.10
	 */
	public function __construct() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Setup any hooks.
	 *
	 * @since 2.0.0
	 */
	public function setup() {
		add_filter( 'admin_title', array( $this, 'store_admin_title' ), 999999, 2 );
		add_filter( 'admin_body_class', array( $this, 'admin_body_classes' ) );
	}

	/**
	 * Stores the admin title tag format to be used in JS.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $admin_title
	 * @param  string $title
	 *
	 * @return string
	 */
	public function store_admin_title( $admin_title, $title ) {
		$this->title_tag = str_replace( $title, '{replaceme}', $admin_title );

		return $admin_title;
	}

	/**
	 * Returns an array of our registered pages.
	 * If we need more pages, add them to this array
	 *
	 * @return array Array of page objects.
	 */
	public function get_registered_pages() {
		if ( empty( $this->pages ) ) {
			$this->pages['optin-monster-campaigns'] = array(
				'name'     => __( 'Campaigns', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-templates'] = array(
				'name'     => __( 'Templates', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-monsterleads'] = array(
				'name'     => __( 'Subscribers', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-trustpulse'] = array(
				'name' => __( 'TrustPulse', 'optin-monster-api' ),
			);

			$this->pages['optin-monster-settings'] = array(
				'name'     => __( 'Settings', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-personalization'] = array(
				'name'     => __( 'Personalization', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-university'] = array(
				'name'     => __( 'University', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-about'] = array(
				'name'     => __( 'About Us', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-onboarding-wizard'] = array(
				'name'     => __( 'Onboarding Wizard', 'optin-monster-api' ),
				'callback' => array( $this, 'render_app_loading_page' ),
				'hidden'   => true,
			);

			foreach ( $this->pages as $slug => $page ) {
				$this->pages[ $slug ]['slug'] = $slug;
			}
		}

		return $this->pages;
	}

	/**
	 * Returns an array of our registered JS app pages.
	 *
	 * @return array Array of page objects.
	 */
	public function get_registered_app_pages() {
		return wp_list_filter( $this->get_registered_pages(), array( 'app' => true ) );
	}

	/**
	 * Whether given page slug is one of our registered JS app pages.
	 *
	 * @param string $page_slug Page slug.
	 *
	 * @return boolean
	 */
	public function is_registered_app_page( $page_slug ) {
		$pages   = wp_list_pluck( $this->get_registered_app_pages(), 'slug' );
		$pages[] = 'optin-monster-api-settings';
		$pages[] = 'optin-monster-dashboard';

		return in_array( $page_slug, $pages, true );
	}

	/**
	 * Registers our submenu pages
	 *
	 * @param string $parent_page_name The Parent Page Name
	 *
	 * @return array Array of hook ids.
	 */
	public function register_submenu_pages( $parent_page_name ) {
		$pages = $this->get_registered_pages();
		$hooks = array();

		foreach ( $pages as $page ) {
			if ( ! empty( $page['callback'] ) ) {
				$parent_slug = $parent_page_name;

				if ( ! empty( $page['hidden'] ) ) {
					$parent_slug .= '-hidden';
				}

				$hooks[] = $hook = add_submenu_page(
					$parent_slug, // $parent_slug
					$page['name'], // $page_title
					! empty( $page['menu'] ) ? $page['menu'] : $page['name'], // $menu_title
					$this->base->access_capability( $page['slug'] ),
					$page['slug'],
					$page['callback']
				);

				if ( ! empty( $page['redirect'] ) ) {
					add_action( 'load-' . $hook, array( $this, 'handle_redirect' ), 999 );
				}
			}
		}

		return $hooks;
	}

	/**
	 * Handle redirect for registered page.
	 *
	 * @since  2.0.0
	 *
	 * @return void
	 */
	public function handle_redirect() {
		global $plugin_page;

		$pages = $this->get_registered_pages();
		if (
			empty( $pages[ $plugin_page ]['redirect'] )
			|| is_bool( $pages[ $plugin_page ]['redirect'] )
		) {
			return $this->base->menu->redirect_to_dashboard();
		}

		// TODO: wp_redirect() found. Using wp_safe_redirect(), along with the
		// `allowed_redirect_hosts` filter if needed, can help avoid any chances
		// of malicious redirects within code.
		wp_redirect( esc_url_raw( $pages[ $plugin_page ]['redirect'] ) );
		exit;
	}

	/**
	 * Adds om app admin body classes
	 *
	 * @since  2.0.0
	 *
	 * @param  string $classes
	 *
	 * @return string
	 */
	public function admin_body_classes( $classes ) {
		global $plugin_page;

		$classes = explode( ' ', $classes );
		$classes = array_filter( $classes );
		$classes = array_map( 'trim', $classes );

		if ( $this->is_registered_app_page( $plugin_page ) ) {
			$classes[] = 'omapi-app';
			$classes[] = 'omapi-app-' . str_replace( 'optin-monster-', '', $plugin_page );
		}

		$classes = implode( ' ', $classes );

		return $classes;

	}

	/**
	 * Registers our submenu pages, but redirects to main page when navigating to them.
	 *
	 * @since  1.9.10
	 *
	 * @param string $parent_page_name The Parent Page Name
	 * @return void
	 */
	public function register_submenu_redirects( $parent_page_name ) {
		$hooks = $this->register_submenu_pages( $parent_page_name . '-hidden' );
		foreach ( $hooks as $hook ) {
			add_action( 'load-' . $hook, array( $this->base->menu, 'redirect_to_dashboard' ) );
		}
	}

	/**
	 * Outputs the OptinMonster about-us page.
	 *
	 * @since 1.9.10
	 */
	public function render_app_loading_page() {
		$this->load_scripts();
		echo '<div id="om-app">';
		echo $this->base->output_view( 'archie-loading.php' );
		echo '</div>';
	}

	public function load_scripts( $args = array() ) {
		$path   = 'vue/dist';
		$loader = new OMAPI_AssetLoader( trailingslashit( dirname( $this->base->file ) ) . $path );
		try {

			add_filter( 'optin_monster_should_enqueue_asset', array( $this, 'should_enqueue' ), 10, 2 );
			$loader->enqueue(
				array(
					'base_url' => $this->base->url . $path,
					'version'  => $this->base->asset_version(),
				)
			);

			$pages = array(
				'optin-monster-dashboard' => __( 'Dashboard', 'optin-monster-api' ),
			);
			foreach ( $this->get_registered_pages() as $page ) {
				$pages[ $page['slug'] ] = ! empty( $page['title'] ) ? $page['title'] : $page['name'];
			}

			$creds = $this->base->get_api_credentials();

			$admin_parts = OMAPI_Utils::parse_url( admin_url( 'admin.php' ) );
			$url_parts   = OMAPI_Utils::parse_url( $this->base->url );

			$current_user = wp_get_current_user();

			$js_args = wp_parse_args(
				$args,
				array(
					'key'             => ! empty( $creds['apikey'] ) ? $creds['apikey'] : '',
					'nonce'           => wp_create_nonce( 'wp_rest' ),
					'siteId'          => $this->base->get_site_id(),
					'siteIds'         => $this->base->get_site_ids(),
					'wpUrl'           => trailingslashit( site_url() ),
					'adminUrl'        => OMAPI_Urls::admin(),
					'restUrl'         => rest_url(),
					'adminPath'       => $admin_parts['path'],
					'apijsUrl'        => OPTINMONSTER_APIJS_URL,
					'omAppUrl'        => untrailingslashit( OPTINMONSTER_APP_URL ),
					'omAppApiUrl'     => untrailingslashit( OPTINMONSTER_API_URL ),
					'omAppCdnURL'     => untrailingslashit( OPTINMONSTER_CDN_URL ),
					'newCampaignUrl'  => untrailingslashit( esc_url_raw( admin_url( 'admin.php?page=optin-monster-templates' ) ) ),
					'pluginPath'      => $url_parts['path'],
					'omStaticDataKey' => 'omWpApi',
					'isItWp'          => true,
					// 'scriptPath'   => $path,
					'pages'           => $pages,
					'titleTag'        => $this->title_tag,
					'isWooActive'     => OMAPI::is_woocommerce_active(),
					'isWooConnected'  => OMAPI_WooCommerce::is_connected(),
					'blogname'        => esc_attr( get_option( 'blogname' ) ),
					'userEmail'       => esc_attr( $current_user->user_email ),
					'userFirstName'   => esc_attr( $current_user->user_firstname ),
					'userLastName'    => esc_attr( $current_user->user_lastname ),
					'betaVersion'     => $this->base->beta_version(),
					'partnerId'       => OMAPI_Partners::get_id(),
					'partnerUrl'      => OMAPI_Partners::has_partner_url(),
				)
			);

			$js_args = apply_filters( 'optin_monster_campaigns_js_api_args', $js_args );

			$loader->localize( $js_args );

			wp_enqueue_script( $this->base->plugin_slug . '-api-script', OPTINMONSTER_APIJS_URL, $loader->handles['js'], null, true );
			add_filter( 'script_loader_tag', array( $this, 'filter_api_script' ), 10, 2 );

			return $loader;

		} catch ( \Exception $e ) {
		}

		return false;
	}

	/**
	 * Filters the API script tag to add the preview user/account data attributes.
	 *
	 * @since 2.0.0
	 *
	 * @param string $tag    The HTML script output.
	 * @param string $handle The script handle to target.
	 * @return string $tag   Amended HTML script with our ID attribute appended.
	 */
	public function filter_api_script( $tag, $handle ) {

		// If the handle is not ours, do nothing.
		if ( $this->base->plugin_slug . '-api-script' !== $handle ) {
			return $tag;
		}

		// Adjust the output to add our custom script ID.
		return str_replace(
			' src',
			sprintf(
				' data-account="56690" data-user="50374" async %s src',
				defined( 'OPTINMONSTER_ENV' ) ? 'data-env="' . OPTINMONSTER_ENV . '"' : ''
			),
			$tag
		);
	}

	/**
	 * Determine if given asset should be enqueued.
	 *
	 * We only want app/common, since remaining assets are chunked/lazy-loaded.
	 *
	 * @since  2.0.0
	 *
	 * @param  bool   $should Whether asset should be enqueued.
	 * @param  string $handle The asset handle.
	 *
	 * @return bool           Whether asset should be enqueued.
	 */
	public function should_enqueue( $should, $handle ) {
		$allowed = array(
			'wp-om-app',
			'wp-om-common',
		);

		foreach ( $allowed as $search ) {
			if ( 0 === strpos( $handle, $search ) ) {
				return true;
			}
		}

		return false;
	}

}
