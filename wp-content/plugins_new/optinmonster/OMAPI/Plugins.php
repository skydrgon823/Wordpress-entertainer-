<?php
/**
 * AM Plugins class.
 *
 * @since 1.9.10
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AM Plugins class.
 *
 * @since 1.9.10
 */
class OMAPI_Plugins {

	/**
	 * The Base OMAPI Object
	 *
	 *  @since 1.8.0
	 *
	 * @var OMAPI
	 */
	protected $base;

	/**
	 * Constructor.
	 *
	 * @since 1.8.0
	 */
	public function __construct() {
		$this->base = OMAPI::get_instance();
	}

	/**
	 * Gets the list of AM plugins.
	 *
	 * @since 2.0.0
	 *
	 * @param  boolean $include_status Whether to include plugin status (installed/activated).
	 *
	 * @return array List of AM plugins.
	 */
	public function get_list( $include_status = false ) {
		$data = array(
			'wpforms-lite/wpforms.php'                    => array(
				'icon'  => $this->base->url . 'assets/images/about/plugin-wp-forms.png',
				'class' => 'wpforms-litewpformsphp',
				'check' => array( 'function' => 'wpforms' ),
				'name'  => 'WPForms',
				'desc'  => __( 'WPForm’s easy drag & drop WordPress form builder allows you to create contact forms, online surveys, donation forms, order forms and morein just a few minutes without writing any code.', 'optin-monster-api' ),
				'url'   => 'https://downloads.wordpress.org/plugin/wpforms-lite.zip',
				'pro'   => array(
					'plugin' => 'wpforms-premium/wpforms.php',
					'name'   => 'WPForms Pro',
					'url'    => 'https://wpforms.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
				),
			),
			'google-analytics-for-wordpress/googleanalytics.php' => array(
				'icon'  => $this->base->url . 'assets/images/about/plugin-mi.png',
				'class' => 'google-analytics-for-wordpressgoogleanalyticsphp',
				'check' => array( 'function' => 'MonsterInsights' ),
				'name'  => 'MonsterInsights',
				/* translators: %s - MonsterInsights Plugin name.*/
				'desc'  => sprintf( __( '%s makes it effortless to properly connect your WordPress site with Google Analytics, so you can start making data-driven decisions to grow your business.', 'optin-monster-api' ), 'MonsterInsights' ),
				'url'   => 'https://downloads.wordpress.org/plugin/google-analytics-for-wordpress.zip',
				'pro'   => array(
					'plugin' => 'google-analytics-premium/googleanalytics-premium.php',
					'name'   => 'MonsterInsights Pro',
					'url'    => 'https://www.monsterinsights.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
				),
			),
			'rafflepress/rafflepress.php'                 => array(
				'icon'  => $this->base->url . 'assets/images/about/plugin-rafflepress.png',
				'class' => 'rafflepressrafflepressphp',
				'check' => array(
					'constant' => array(
						'RAFFLEPRESS_VERSION',
						'RAFFLEPRESS_PRO_VERSION',
					),
				),
				'name'  => 'RafflePress',
				'desc'  => __( 'Turn your visitors into brand ambassadors! Easily grow your email list, website traffic, and social media followers with powerful viral giveaways & contests.', 'optin-monster-api' ),
				'url'   => 'https://downloads.wordpress.org/plugin/rafflepress.zip',
				'pro'   => array(
					'plugin' => '',
					'name'   => 'RafflePress',
					'url'    => 'https://rafflepress.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
				),
			),
			'wp-mail-smtp/wp_mail_smtp.php'               => array(
				'icon'  => $this->base->url . 'assets/images/about/plugin-wp-mail-smtp.png',
				'class' => 'wp-mail-smtpwp-mail-smtpphp',
				'check' => array( 'function' => 'wp_mail_smtp' ),
				'name'  => 'WP Mail SMTP',
				'desc'  => __( 'Make sure your website’s emails reach the inbox. Our goal is to make email deliverability easy and reliable. Trusted by over 1 MILLION websites.', 'optin-monster-api' ),
				'url'   => 'https://downloads.wordpress.org/plugin/wp-mail-smtp.zip',
				'pro'   => array(
					'plugin' => 'wp-mail-smtp-pro/wp_mail_smtp.php',
					'name'   => 'WP Mail SMTP',
					'url'    => 'https://wpmailsmtp.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
				),
			),
			'all-in-one-seo-pack/all_in_one_seo_pack.php' => array(
				'icon'  => $this->base->url . 'assets/images/about/plugin-aioseo.png',
				'class' => 'all-in-one-seo-packall-in-one-seo-packphp',
				'check' => array(
					'constant' => array(
						'AIOSEOP_VERSION',
						'AIOSEO_VERSION',
					),
				),
				'name'  => 'AIOSEO',
				/* translators: %s - AIOSEO Plugin name.*/
				'desc'  => sprintf( __( 'Easily set up proper SEO foundations for your site in less than 10 minutes with %s. It’s the most powerful and user-friendly WordPress SEO plugin, used by over 2 MILLION sites.', 'optin-monster-api' ), 'All-in-One SEO' ),
				'url'   => 'https://downloads.wordpress.org/plugin/all-in-one-seo-pack.zip',
				'pro'   => array(
					'plugin' => '',
					'name'   => 'All-in-One SEO Pack',
					'url'    => 'https://aioseo.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
				),
			),
			'coming-soon/coming-soon.php'                 => array(
				'icon'  => $this->base->url . 'assets/images/about/plugin-seedprod.png',
				'class' => 'coming-sooncoming-soonphp',
				'check' => array(
					'constant' => array(
						'SEEDPROD_PRO_VERSION',
						'SEEDPROD_VERSION',
					),
				),
				'name'  => 'SeedProd',
				'desc'  => __( 'Professionally design landing page templates like coming soon pages and sales pages that get you up and going with just a few clicks of a mouse. Used on over 1 MILLION sites! ', 'optin-monster-api' ),
				'url'   => 'https://downloads.wordpress.org/plugin/coming-soon.zip',
				'pro'   => array(
					'plugin' => '',
					'name'   => 'SeedProd',
					'url'    => 'https://www.seedprod.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
				),
			),
			'trustpulse-api/trustpulse.php'               => array(
				'icon'  => $this->base->url . 'assets/images/about/plugin-trustpulse.png',
				'class' => 'trustpulse-apitrustpulsephp',
				'check' => array( 'class' => 'TPAPI' ),
				'name'  => 'TrustPulse',
				'desc'  => __( 'TrustPulse is the honest marketing platform that leverages and automates the real power of social proof to instantly increase trust, conversions and sales.', 'optin-monster-api' ),
				'url'   => 'https://downloads.wordpress.org/plugin/trustpulse-api.zip',
				'pro'   => array(
					'plugin' => '',
					'name'   => 'TrustPulse',
					'url'    => 'https://trustpulse.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
				),
			),
		);

		foreach ( $data as $plugin_id => $plugin ) {
			$plugin['id']             = $plugin_id;
			$data[ $plugin_id ]['id'] = $plugin_id;

			if ( $include_status ) {

				list( $installed, $active ) = $this->plugin_exists_checks( $plugin );

				$data[ $plugin_id ]['status'] = $installed ?
					$active ?
						__( 'Active', 'optin-monster-api' ) :
						__( 'Inactive', 'optin-monster-api' )
					: __( 'Not Installed', 'optin-monster-api' );

				$data[ $plugin_id ]['installed'] = $installed;
				$data[ $plugin_id ]['active']    = $installed && $active;
			}
		}

		return $data;
	}

	/**
	 * Check if plugin is active/installed.
	 *
	 * @since  2.0.0
	 *
	 * @param  array $plugin Array of plugin data.
	 *
	 * @return bool
	 */
	public function plugin_exists_checks( $plugin ) {

		// Check if plugin is active by checking if class/function/constant exists.
		// This gets around limitations with the normal `is_plugin_active` checks.
		// Those limitations include:
		// - The install path could be modified (e.g. using -beta version, or version downloaded from github)
		// - The plugin is considered "active", but the actual plugin has been deleted from the server.
		$active = $this->plugin_code_exists_checks( $plugin );

		// If plugin is active, it's definitely installed.
		$installed = $active
			// Otherwise, check if it exists in the list of plugins.
			|| $this->plugin_installed( $plugin );

		return array( $installed, $active );
	}

	/**
	 * Check if plugin is active via code checks.
	 *
	 * @since  2.0.0
	 *
	 * @param  array $plugin Array of plugin data.
	 *
	 * @return bool
	 */
	protected function plugin_code_exists_checks( $plugin ) {

		// Loop through all checks.
		foreach ( $plugin['check'] as $check_type => $to_check ) {

			// Now loop through all the things to checks.
			foreach ( (array) $to_check as $thing_to_check ) {
				switch ( $check_type ) {
					case 'function':
						if ( function_exists( $thing_to_check ) ) {
							return true;
						}
						break;
					case 'class':
						if ( class_exists( $thing_to_check ) ) {
							return true;
						}
						break;
					case 'constant':
						if ( defined( $thing_to_check ) ) {
							return true;
						}
						break;
				}
			}
		}

		return false;
	}

	/**
	 * Check if plugin is installed (exists in array of plugin data).
	 *
	 * @since  2.0.0
	 *
	 * @param  array $plugin Array of plugin data.
	 *
	 * @return bool
	 */
	protected function plugin_installed( $plugin ) {
		static $all_plugins = null;

		if ( null === $all_plugins ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$all_plugins = get_plugins();
		}

		return array_key_exists( $plugin['id'], $all_plugins )
			|| array_key_exists( $plugin['pro']['plugin'], $all_plugins );
	}

	/**
	 * Installs and activates a plugin for a given url (if user is allowed).
	 *
	 * @since 2.0.0
	 *
	 * @param string $plugin_url The Plugin URL.
	 * @return array On success.
	 * @throws Exception On error.
	 */
	public function install_plugin( $plugin_url ) {

		$not_allowed_exception = new Exception( esc_html__( 'Sorry, not allowed!', 'optin-monster-api' ), rest_authorization_required_code() );

		// Check for permissions.
		if ( ! current_user_can( 'install_plugins' ) ) {
			throw $not_allowed_exception;
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		$creds = request_filesystem_credentials( admin_url( 'admin.php' ), '', false, false, null );

		// Check for file system permissions.
		if ( false === $creds ) {
			throw $not_allowed_exception;
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$skin = version_compare( $GLOBALS['wp_version'], '5.3', '>=' )
			? new OMAPI_InstallSkin()
			: new OMAPI_InstallSkinCompat();

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( $skin );

		// Error check.
		if ( ! method_exists( $installer, 'install' ) ) {
			throw new Exception( esc_html__( 'Missing required installer!', 'optin-monster-api' ), 500 );
		}

		$result = $installer->install( esc_url_raw( $plugin_url ) ); // phpcs:ignore

		if ( ! $installer->plugin_info() ) {
			throw new Exception( esc_html__( 'Plugin failed to install!', 'optin-monster-api' ), 500 );
		}

		$plugin_basename = $installer->plugin_info();

		// Activate the plugin silently.
		try {
			$this->activate_plugin( $plugin_basename );

			return array(
				'message'      => esc_html__( 'Plugin installed & activated.', 'optin-monster-api' ),
				'is_activated' => true,
				'basename'     => $plugin_basename,
			);

		} catch ( \Exception $e ) {

			return array(
				'message'      => esc_html__( 'Plugin installed.', 'optin-monster-api' ),
				'is_activated' => false,
				'basename'     => $plugin_basename,
			);
		}
	}

	/**
	 * Activates a plugin with a given plugin name (if user is allowed).
	 *
	 * @since 2.0.0
	 *
	 * @param string $plugin_id
	 * @return array On success.
	 * @throws Exception On error.
	 */
	public function activate_plugin( $plugin_id ) {

		// Check for permissions.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			throw new Exception( esc_html__( 'Sorry, not allowed!', 'optin-monster-api' ), rest_authorization_required_code() );
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$activate = activate_plugins( sanitize_text_field( $plugin_id ) );

		if ( is_wp_error( $activate ) ) {
			$e = new OMAPI_WpErrorException();
			throw $e->setWpError( $activate );
		}

		return array(
			'message'  => esc_html__( 'Plugin activated.', 'optin-monster-api' ),
			'basename' => $plugin_id,
		);
	}

}
