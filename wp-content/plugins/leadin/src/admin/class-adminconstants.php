<?php
namespace Leadin\admin;

use Leadin\LeadinFilters;
use Leadin\admin\Links;
use Leadin\admin\Routing;
use Leadin\auth\OAuth;
use Leadin\admin\utils\DeviceId;
use Leadin\utils\Versions;
use Leadin\wp\User;
use Leadin\wp\Website;
use Leadin\options\AccountOptions;
use Leadin\options\HubspotOptions;
use Leadin\admin\Connection;
use Leadin\admin\Impact;
use Leadin\admin\AdminUserMetaData;

/**
 * Class containing all the constants used for admin script localization.
 */
class AdminConstants {


	/**
	 * Development backdoor to enable the odyssey signup flow
	 */
	private static function is_odyssey_enabled() {
		return ! empty( get_option( 'hsdev_odyssey_enabled' ) );
	}

	/**
	 * Return utm_campaign to add to the signup link.
	 */
	private static function get_utm_campaign() {
		$wpe_template = HubspotOptions::get_wpe_template();
		if ( 'hubspot' === $wpe_template ) {
			return 'wp-engine-site-template';
		}
	}

	/**
	 * Return an array with the utm parameters for signup
	 */
	private static function get_utm_query_params_array() {
		$utm_params = array(
			'utm_source' => 'wordpress-plugin',
			'utm_medium' => 'marketplaces',
		);

		$utm_campaign = self::get_utm_campaign();
		if ( ! empty( $utm_campaign ) ) {
			$utm_params['utm_campaign'] = $utm_campaign;
		}
		return $utm_params;
	}

	/**
	 * Return a nonce used on the connection class
	 */
	private static function get_connection_nonce() {
		return wp_create_nonce( 'hubspot-nonce' );
	}

	/**
	 * Return an array with the user's pre-fill info for signup
	 */
	private static function get_signup_prefill_params_array() {
		$wp_user   = wp_get_current_user();
		$user_info = array(
			'firstName' => $wp_user->user_firstname,
			'lastName'  => $wp_user->user_lastname,
			'email'     => $wp_user->user_email,
			'company'   => get_bloginfo( 'name' ),
			'show_nav'  => 'true',
		);

		return $user_info;
	}

	/**
	 * Return an array of properties to be included in the signup search string
	 */
	public static function get_signup_query_params_array() {
		$signup_params                         = array();
		$signup_params['enableCollectedForms'] = 'true';
		$signup_params['leadinPluginVersion']  = constant( 'LEADIN_PLUGIN_VERSION' );
		$signup_params['trackConsent']         = AdminUserMetaData::get_track_consent();
		$user_prefill_params                   = self::get_signup_prefill_params_array();
		$signup_params                         = array_merge( $signup_params, $user_prefill_params );
		return $signup_params;
	}

	/**
	 * Return query params array for the iframe.
	 */
	public static function get_hubspot_query_params_array() {
		$wp_user        = wp_get_current_user();
		$hubspot_config = array(
			'l'            => get_locale(),
			'php'          => Versions::get_php_version(),
			'v'            => LEADIN_PLUGIN_VERSION,
			'wp'           => Versions::get_wp_version(),
			'theme'        => Website::get_theme(),
			'adminUrl'     => admin_url(),
			'websiteName'  => get_bloginfo( 'name' ),
			'domain'       => parse_url( get_site_url(), PHP_URL_HOST ),
			'wp_user'      => $wp_user->first_name ? $wp_user->first_name : $wp_user->user_nicename,
			'ajaxUrl'      => Website::get_ajax_url(),
			'nonce'        => self::get_connection_nonce(),
			'accountName'  => AccountOptions::get_account_name(),
			'hsdio'        => DeviceId::get(),
			'portalDomain' => AccountOptions::get_portal_domain(),
		);

		$utm_params     = self::get_utm_query_params_array();
		$hubspot_config = array_merge( $hubspot_config, $utm_params );

		if ( User::is_admin() ) {
			$hubspot_config['admin'] = '1';
		}

		if ( function_exists( 'get_avatar_url' ) ) {
			$hubspot_config['wp_gravatar'] = get_avatar_url( $wp_user->ID );
		}

		if ( OAuth::is_enabled() ) {
			$hubspot_config['oauth'] = true;

			if ( Routing::has_just_connected_with_oauth() ) {
				$hubspot_config['justConnected'] = true;
			}
			if ( Routing::is_new_portal_with_oauth() ) {
				$hubspot_config['isNewPortal'] = true;
			}
		}

		if ( ! Connection::is_connected() ) {
			$hubspot_config['oauth'] = true;

			if ( self::is_odyssey_enabled() ) {
				$hubspot_config['enableOdyssey'] = true;
			}

			$signup_params  = self::get_signup_query_params_array();
			$hubspot_config = array_merge( $hubspot_config, $signup_params, Impact::get_params() );
		}

		return $hubspot_config;
	}

	/**
	 * Returns a minimal version of leadinConfig, containing the data needed by the background iframe.
	 */
	public static function get_background_leadin_config() {
		$wp_user_id = get_current_user_id();

		$background_config = array(
			'adminUrl'              => admin_url(),
			'ajaxUrl'               => Website::get_ajax_url(),
			'restUrl'               => get_rest_url(),
			'backgroundIframeUrl'   => Links::get_background_iframe_src(),
			'deviceId'              => DeviceId::get(),
			'didDisconnect'         => true,
			'formsScript'           => LeadinFilters::get_leadin_forms_script_url(),
			'formsScriptPayload'    => LeadinFilters::get_leadin_forms_payload(),
			'meetingsScript'        => LeadinFilters::get_leadin_meetings_script_url(),
			'hublet'                => LeadinFilters::get_leadin_hublet(),
			'hubspotBaseUrl'        => LeadinFilters::get_leadin_base_url(),
			'leadinPluginVersion'   => constant( 'LEADIN_PLUGIN_VERSION' ),
			'locale'                => get_locale(),
			'ajaxNonce'             => wp_create_nonce( 'hubspot-ajax' ),
			'restNonce'             => wp_create_nonce( 'wp_rest' ),
			'routeNonce'            => wp_create_nonce( 'hubspot-route' ),
			'hubspotNonce'          => self::get_connection_nonce(),
			'redirectNonce'         => wp_create_nonce( Routing::REDIRECT_NONCE ),
			'phpVersion'            => Versions::get_wp_version(),
			'pluginPath'            => constant( 'LEADIN_PATH' ),
			'plugins'               => get_plugins(),
			'portalId'              => AccountOptions::get_portal_id(),
			'accountName'           => AccountOptions::get_account_name(),
			'portalDomain'          => AccountOptions::get_portal_domain(),
			'portalEmail'           => get_user_meta( $wp_user_id, 'leadin_email', true ),
			'reviewSkippedDate'     => AdminUserMetaData::get_skip_review(),
			'loginUrl'              => Links::get_login_url(),
			'routes'                => Links::get_routes_mapping(),
			'theme'                 => get_option( 'stylesheet' ),
			'wpVersion'             => Versions::get_wp_version(),
			'leadinQueryParamsKeys' => array_keys( self::get_hubspot_query_params_array() ),
			'connectionStatus'      => Connection::is_connected() ? 'Connected' : 'NotConnected',
		);

		if ( OAuth::is_enabled() ) {
			$background_config['oauth'] = 'true';
		}

		return $background_config;
	}

	/**
	 * Returns leadinConfig, containing all the data needed by the leadin javascript.
	 */
	public static function get_leadin_config() {
		$wp_user_id = get_current_user_id();

		$leadin_config = \array_merge(
			self::get_background_leadin_config(),
			array(
				'iframeUrl' => Links::get_iframe_src(),
			)
		);

		if ( ! Connection::is_connected() ) {
			if ( ! Impact::has_params() ) {
				$impact_link = Impact::get_affiliate_link();
				if ( ! empty( $impact_link ) ) {
					$leadin_config['impactLink'] = Impact::get_affiliate_link();
				}
			}
		}

		return $leadin_config;
	}
	/**
	 * Returns leadinI18n, containing all the translations needed on the frontend.
	 */
	public static function get_leadin_i18n() {
		return array(
			'chatflows'                   => __( 'Live Chat', 'leadin' ),
			'selectExistingForm'          => __( 'Select an existing form or create a new one from a template', 'leadin' ),
			'goToPlugin'                  => __( 'Go to plugin', 'leadin' ),
			'refreshForms'                => __( 'Refresh forms', 'leadin' ),
			'unauthorizedHeader'          => __( 'Your plugin isn\'t authorized', 'leadin' ),
			'unauthorizedMessage'         => __( 'Reauthorize your plugin to access your free HubSpot tools.', 'leadin' ),
			'formApiErrorHeader'          => __( 'There was a problem retrieving your forms', 'leadin' ),
			'formApiError'                => __( 'Please refresh your forms or try again in a few minutes.', 'leadin' ),
			'searchForForm'               => __( 'Search for a form', 'leadin' ),
			'formBlockTitle'              => __( 'HubSpot Form', 'leadin' ),
			'formBlockDescription'        => __( 'Select and embed a HubSpot form', 'leadin' ),
			'registrationForm'            => __( 'Registration Form', 'leadin' ),
			'contactUsForm'               => __( 'Contact us Form', 'leadin' ),
			'newsletterForm'              => __( 'Newsletter sign-up Form', 'leadin' ),
			'supportForm'                 => __( 'Support Form', 'leadin' ),
			'eventForm'                   => __( 'Event Registration Form', 'leadin' ),
			'templateForms'               => __( 'Templates', 'leadin' ),
			'meetingsBlockTitle'          => __( 'Hubspot Meetings Scheduler', 'leadin' ),
			'meetingsBlockDescription'    => __( 'Schedule meetings faster and forget the back-and-forth emails. Your calendar stays full, and you stay productive', 'leadin' ),
			'selectExistingMeeting'       => __( 'Select a meeting scheduling page', 'leadin' ),
			'selectMeeting'               => __( 'Select a meeting', 'leadin' ),
			'defaultMeeting'              => __( 'Default', 'leadin' ),
			'meetingName'                 => __( 'Meeting name', 'leadin' ),
			'ownCalendarNotConnected'     => __( 'Your calendar is not connected.', 'leadin' ),
			'ownCalendarNotConnectedDesc' => __( 'Please connect your calendar to activate your scheduling pages.', 'leadin' ),
			'connectCalendar'             => __( 'Connect calendar', 'leadin' ),
			'refreshMeetings'             => __( 'Refresh meetings', 'leadin' ),
			'meetingsApiErrorHeader'      => __( 'There was a problem retrieving your meetings', 'leadin' ),
			'meetingsApiError'            => __( 'Please refresh your meetings or try again in a few minutes.', 'leadin' ),
			'calendarNotConnected'        => __( 'Calendar is not connected.', 'leadin' ),
			'calendarNotConnectedDesc'    => __( 'Make sure that everybody in this meeting has connected their calendar from the Meetings page in HubSpot.', 'leadin' ),
			'blogPost'                    => __( 'Blog Post', 'leadin' ),
			'listingPage'                 => __( 'Listing Page', 'leadin' ),
			'standardPage'                => __( 'Standard Page', 'leadin' ),
			'contentType'                 => __( 'Content Type', 'leadin' ),
			'default'                     => __( 'Default', 'leadin' ),
			'landingPage'                 => __( 'Landing Page', 'leadin' ),
			'knowledgeArticle'            => __( 'Knowledge Article', 'leadin' ),
			'hubspotAnalytics'            => __( 'HubSpot Analytics', 'leadin' ),
			'detectAutomatically'         => __( 'Detect Automatically', 'leadin' ),
			'contentTypeSelectLabel'      => __( 'Select the content type HubSpot Analytics uses to track this page.', 'leadin' ),
			'connectHubLink'              => sprintf(
				esc_html( __( 'The HubSpot plugin isn’t connected right now. To use HubSpot tools on your WordPress site, %1$sconnect the plugin now%2$s.', 'leadin' ) ),
				'<a class="leadin-banner__link" href="admin.php?page=leadin&bannerClick=true">',
				'</a>'
			),
		);
	}
}
