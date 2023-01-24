<?php

namespace Leadin;

use Leadin\LeadinFilters;
use Leadin\admin\AdminConstants;
use Leadin\options\AccountOptions;

/**
 * Class responsible of managing all the plugin assets.
 */
class AssetsManager {
	const ADMIN_CSS          = 'leadin-css';
	const BRIDGE_CSS         = 'leadin-bridge-css';
	const ADMIN_JS           = 'leadin-js';
	const MENU_JS            = 'menu-js';
	const FEEDBACK_CSS       = 'leadin-feedback-css';
	const FEEDBACK_JS        = 'leadin-feedback';
	const TRACKING_CODE      = 'leadin-script-loader-js';
	const GUTENBERG          = 'leadin-gutenberg';
	const MEETINGS_GUTENBERG = 'leadin-meetings-gutenberg';
	const FORMS_SCRIPT       = 'leadin-forms-v2';
	const MEETINGS_SCRIPT    = 'leadin-meeting';
	const LEADIN_CONFIG      = 'leadinConfig';
	const LEADIN_I18N        = 'leadinI18n';
	const REVIEW_BANNER      = 'leadin-review-banner';
	const ELEMENTOR          = 'leadin-elementor';

	/**
	 * Register and localize all assets.
	 */
	public static function register_assets() {
		wp_register_style( self::ADMIN_CSS, LEADIN_PATH . '/assets/style/leadin.css', array(), LEADIN_PLUGIN_VERSION );
		wp_register_script( self::ADMIN_JS, LEADIN_JS_BASE_PATH . '/leadin.js', array( 'jquery' ), LEADIN_PLUGIN_VERSION, true );
		wp_register_script( self::MENU_JS, LEADIN_JS_BASE_PATH . '/menu.js', array( 'jquery' ), LEADIN_PLUGIN_VERSION, true );
		wp_localize_script( self::ADMIN_JS, self::LEADIN_CONFIG, AdminConstants::get_leadin_config() );
		wp_localize_script( self::ADMIN_JS, self::LEADIN_I18N, AdminConstants::get_leadin_i18n() );
		wp_register_script( self::FEEDBACK_JS, LEADIN_JS_BASE_PATH . '/feedback.js', array( 'jquery', 'thickbox' ), LEADIN_PLUGIN_VERSION, true );
		wp_localize_script( self::FEEDBACK_JS, self::LEADIN_CONFIG, AdminConstants::get_background_leadin_config() );
		wp_register_style( self::FEEDBACK_CSS, LEADIN_PATH . '/assets/style/leadin-feedback.css', array(), LEADIN_PLUGIN_VERSION );
		wp_register_style( self::BRIDGE_CSS, LEADIN_PATH . '/assets/style/leadin-bridge.css?', array(), LEADIN_PLUGIN_VERSION );
	}

	/**
	 * Enqueue the assets needed in the admin section.
	 */
	public static function enqueue_admin_assets() {
		wp_enqueue_style( self::ADMIN_CSS );
		wp_enqueue_script( self::MENU_JS );
	}

	/**
	 * Enqueue the assets needed to render the deactivation feedback form.
	 */
	public static function enqueue_feedback_assets() {
		wp_enqueue_style( self::FEEDBACK_CSS );
		wp_enqueue_script( self::FEEDBACK_JS );
	}

	/**
	 * Enqueue the assets needed to correctly render the plugin's iframe.
	 */
	public static function enqueue_bridge_assets() {
		wp_enqueue_style( self::BRIDGE_CSS );
		wp_enqueue_script( self::ADMIN_JS );
	}

	/**
	 * Register and enqueue the HubSpot's script loader (aka tracking code), used to collect data from your visitors.
	 * https://knowledge.hubspot.com/account/how-does-hubspot-track-visitors
	 *
	 * @param Object $leadin_wordpress_info Object used to pass to the script loader.
	 */
	public static function enqueue_script_loader( $leadin_wordpress_info ) {
		$embed_domain = LeadinFilters::get_leadin_script_loader_domain();
		$portal_id    = AccountOptions::get_portal_id();
		$embed_url    = "https://$embed_domain/$portal_id.js?integration=WordPress";
		wp_register_script( self::TRACKING_CODE, $embed_url, array( 'jquery' ), LEADIN_PLUGIN_VERSION, true );
		wp_localize_script( self::TRACKING_CODE, 'leadin_wordpress', $leadin_wordpress_info );
		wp_enqueue_script( self::TRACKING_CODE );
	}

	/**
	 * Register and enqueue forms script
	 */
	public static function enqueue_forms_script() {
		wp_enqueue_script(
			self::FORMS_SCRIPT,
			LeadinFilters::get_leadin_forms_script_url(),
			array(),
			LEADIN_PLUGIN_VERSION,
			true
		);
	}

	/**
	 * Register and enqueue forms script
	 */
	public static function enqueue_meetings_script() {
		wp_enqueue_script(
			self::MEETINGS_SCRIPT,
			'https://static.hsappstatic.net/MeetingsEmbed/ex/MeetingsEmbedCode.js',
			array(),
			LEADIN_PLUGIN_VERSION,
			true
		);
	}

	/**
	 * Register and localize the Gutenberg scripts.
	 */
	public static function localize_gutenberg() {
		wp_register_style( self::ELEMENTOR, LEADIN_JS_BASE_PATH . '/gutenberg.css', array(), LEADIN_PLUGIN_VERSION );
		wp_enqueue_style( self::ELEMENTOR );
		wp_register_script( self::GUTENBERG, LEADIN_JS_BASE_PATH . '/gutenberg.js', array( 'wp-blocks', 'wp-element' ), LEADIN_PLUGIN_VERSION, true );
		wp_localize_script( self::GUTENBERG, self::LEADIN_CONFIG, AdminConstants::get_background_leadin_config() );
		wp_localize_script( self::GUTENBERG, self::LEADIN_I18N, AdminConstants::get_leadin_i18n() );
	}

	/**
	 * Register and localize the Meetings Gutenberg scripts.
	 */
	public static function localize_meetings_gutenberg() {
		wp_register_script( self::MEETINGS_GUTENBERG, LEADIN_JS_BASE_PATH . '/meetings.js', array( 'wp-blocks', 'wp-element' ), LEADIN_PLUGIN_VERSION, true );
		wp_localize_script( self::MEETINGS_GUTENBERG, self::LEADIN_CONFIG, AdminConstants::get_background_leadin_config() );
		wp_localize_script( self::MEETINGS_GUTENBERG, self::LEADIN_I18N, AdminConstants::get_leadin_i18n() );
	}

	/**
	 * Register and enqueue a new script for tracking review banner events.
	 */
	public static function enqueue_review_banner_tracking_script() {
		wp_register_script( self::REVIEW_BANNER, LEADIN_JS_BASE_PATH . '/reviewBanner.js', array( 'jquery' ), LEADIN_PLUGIN_VERSION, true );
		wp_localize_script( self::REVIEW_BANNER, self::LEADIN_CONFIG, AdminConstants::get_background_leadin_config() );
		wp_enqueue_script( self::REVIEW_BANNER );
	}

	/**
	 * Register and enqueue a new script/style for elementor.
	 */
	public static function enqueue_elementor_script() {
		wp_register_style( self::ELEMENTOR, LEADIN_JS_BASE_PATH . '/elementor.css', array(), LEADIN_PLUGIN_VERSION );
		wp_enqueue_style( self::ELEMENTOR );
		wp_register_script( self::ELEMENTOR, LEADIN_JS_BASE_PATH . '/elementor.js', array(), LEADIN_PLUGIN_VERSION, true );
		wp_localize_script( self::ELEMENTOR, self::LEADIN_CONFIG, AdminConstants::get_background_leadin_config() );
		wp_localize_script( self::ELEMENTOR, self::LEADIN_I18N, AdminConstants::get_leadin_i18n() );
		wp_enqueue_script( self::ELEMENTOR );
	}

}
