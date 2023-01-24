<?php
/**
 * Admin Settings Pages Helper.
 *
 * @package    BP_Profile_Completion
 * @subpackage Admin
 * @copyright  Copyright (c) 2018, BuddyDev.Com
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Ravi Sharma, Brajesh Singh
 * @since      1.0.0
 */

namespace BP_Profile_Completion\Admin;

use \Press_Themes\PT_Settings\Page;

// Exit if file accessed directly over web.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Settings
 */
class Admin_Settings {

	/**
	 * Admin Menu slug
	 *
	 * @var string
	 */
	private $menu_slug;

	/**
	 * Used to keep a reference of the Page, It will be used in rendering the view.
	 *
	 * @var \Press_Themes\PT_Settings\Page
	 */
	private $page;

	/**
	 * Boot settings
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Setup settings
	 */
	public function setup() {

		$this->menu_slug = 'bp-profile-completion';

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	/**
	 * Show/render the setting page
	 */
	public function render() {
		$this->page->render();
	}

	/**
	 * Is it the setting page?
	 *
	 * @return bool
	 */
	private function needs_loading() {

		global $pagenow;

		// We need to load on options.php otherwise settings won't be registered.
		if ( 'options.php' === $pagenow ) {
			return true;
		}

		if ( isset( $_GET['page'] ) && $_GET['page'] === $this->menu_slug ) {
			return true;
		}

		return false;
	}

	/**
	 * Initialize the admin settings panel and fields
	 */
	public function init() {

		if ( ! $this->needs_loading() ) {
			return;
		}

		$page = new Page( 'bpprocn_settings', __( 'BuddyPress Profile Completion', 'buddypress-profile-completion' ) );

		// General settings tab.
		$panel = $page->add_panel( 'settings', _x( 'Settings', 'Admin settings panel title', 'buddypress-profile-completion' ) );

		$required_criteria = $panel->add_section( 'required_criteria', _x( 'Required criteria for profile completion', 'Admin settings section title', 'buddypress-profile-completion' ) );

		$defaults = bpprocn_get_default_options();

		$required_criteria->add_field(
			array(
				'name'    => 'required_criteria',
				'label'   => _x( 'Required criteria', 'Admin settings', 'buddypress-profile-completion' ),
				'type'    => 'multicheck',
				'options' => array(
					'all_req_fields'    => __( 'Must fill all required fields', 'buddypress-profile-completion' ),
					'req_profile_photo' => __( 'Must have profile photo', 'buddypress-profile-completion' ),
					'req_profile_cover' => __( 'Must have profile cover', 'buddypress-profile-completion' ),
				),
				'default' => $defaults['required_criteria'],
				'desc'    => __( 'User will need to fill all the selected criteria for profile completion', 'buddypress-profile-completion' ),
			)
		);

		$profile_actions = $panel->add_section( 'incomplete_profile_actions', _x( 'Profile incomplete actions', 'Admin settings section title', 'buddypress-profile-completion' ) );

		$fields = array(
			array(
				'name'    => 'restrict_access_to_profile_only',
				'label'   => _x( 'Restrict to profile only', 'Admin settings', 'buddypress-profile-completion' ),
				'type'    => 'radio',
				'options' => array(
					1 => __( 'Yes', 'buddypress-profile-completion' ),
					0 => __( 'No', 'buddypress-profile-completion' ),
				),
				'default' => $defaults['restrict_access_to_profile_only'],
				'desc'    => __( 'If enabled and the profile is not complete, user will be restricted to their own profile. Does not apply to admin users.', 'buddypress-profile-completion' ),
			),
			array(
				'name'    => 'show_profile_incomplete_message',
				'label'   => _x( 'Show profile incomplete message', 'Admin settings', 'buddypress-profile-completion' ),
				'type'    => 'radio',
				'options' => array(
					1 => __( 'Yes', 'buddypress-profile-completion' ),
					0 => __( 'No', 'buddypress-profile-completion' ),
				),
				'default' => $defaults['show_profile_incomplete_message'],
			),
			array(
				'name'    => 'required_fields_incomplete_message',
				'label'   => _x( 'Required field incomplete message', 'Admin settings', 'buddypress-profile-completion' ),
				'type'    => 'textarea',
				'default' => $defaults['required_fields_incomplete_message'],
			),
			array(
				'name'    => 'profile_photo_incomplete_message',
				'label'   => _x( 'Profile photo incomplete message', 'Admin settings', 'buddypress-profile-completion' ),
				'type'    => 'textarea',
				'default' => $defaults['profile_photo_incomplete_message'],
			),
			array(
				'name'    => 'profile_cover_incomplete_message',
				'label'   => _x( 'Profile cover incomplete message', 'Admin settings', 'buddypress-profile-completion' ),
				'type'    => 'textarea',
				'default' => $defaults['profile_cover_incomplete_message'],
			),
		);

		$profile_actions->add_fields( $fields );
		/*
		// Whitelisted settings tab.
		$whitelisted = $page->add_panel( 'whitelisted', _x( 'Whitelisted', 'Admin settings panel title', 'buddypress-profile-completion' ) );

		$whitelisted_roles_section = $whitelisted->add_section( 'roles_settings', _x( 'WordPress Roles Settings', 'Admin settings section title', 'buddypress-profile-completion' ) );

		$fields = array(
			array(
				'name'    => 'enable_whitelisted_roles',
				'label'   => _x( 'Enable roles', 'Admin settings', 'buddypress-profile-completion' ),
				'desc'    => __( 'If enabled, user with selected roles will be marked as whitelisted', 'buddypress-profile-completion' ),
				'type'    => 'radio',
				'options' => array(
					1 => __( 'Yes', 'buddypress-profile-completion' ),
					0 => __( 'No', 'buddypress-profile-completion' ),
				),
				'default' => $defaults['enable_whitelisted_roles'],
			),
			array(
				'name'    => 'whitelisted_roles',
				'label'   => _x( 'Whitelisted Roles', 'Admin settings', 'buddypress-profile-completion' ),
				'type'    => 'multicheck',
				'options' => bpprocn_get_roles(),
				'default' => $defaults['whitelisted_roles'],
			),
		);

		$whitelisted_roles_section->add_fields( $fields );

		$whitelisted_member_types_section = $whitelisted->add_section( 'member_types_settings', _x( 'BuddyPress Member Types Settings', 'Admin settings section title', 'buddypress-profile-completion' ) );

		$fields = array(
			array(
				'name'    => 'enable_whitelisted_member_types',
				'label'   => _x( 'Enable member types', 'Admin settings', 'buddypress-profile-completion' ),
				'desc'    => __( 'If enabled, user with selected member types will be marked as whitelisted', 'buddypress-profile-completion' ),
				'type'    => 'radio',
				'options' => array(
					1 => __( 'Yes', 'buddypress-profile-completion' ),
					0 => __( 'No', 'buddypress-profile-completion' ),
				),
				'default' => $defaults['enable_whitelisted_member_types'],
			),
			array(
				'name'    => 'whitelisted_member_types',
				'label'   => _x( 'Whitelisted Member Types', 'Admin settings', 'buddypress-profile-completion' ),
				'type'    => 'multicheck',
				'options' => bpprocn_get_member_types(),
				'default' => $defaults['enable_whitelisted_member_types'],
			),
		);

		$whitelisted_member_types_section->add_fields( $fields );
		*/
		$this->page = $page;

		// allow enabling options.
		$page->init();
	}

	/**
	 * Add Menu
	 */
	public function add_menu() {

		add_options_page(
			_x( 'BuddyPress Profile Completion', 'Admin settings page title', 'buddypress-profile-completion' ),
			_x( 'BuddyPress Profile Completion', 'Admin settings menu label', 'buddypress-profile-completion' ),
			'manage_options',
			$this->menu_slug,
			array( $this, 'render' )
		);
	}
}
