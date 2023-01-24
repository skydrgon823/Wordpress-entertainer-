<?php

/**
 * Admin class.
 *
 * @package Buddy_Builder
 * @since 1.0.0
 */

namespace Buddy_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Admin
 *
 * @package Buddy_Builder
 */
final class Admin extends Singleton {

	/**
	 * @var string
	 */
	public static $plugin_path;

	/**
	 * @var string
	 */
	private $current_slug = '';

	/*
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );

		if ( empty( self::$plugin_path ) ) {
			self::$plugin_path = trailingslashit( plugin_dir_path( BPB_FILE ) );
		}

		// Admin pages.
		require_once self::$plugin_path . '/admin/pages/Base.php';
		// require_once self::$plugin_path . '/admin/pages/Dashboard.php';
		require_once self::$plugin_path . '/admin/pages/Templates.php';
		require_once self::$plugin_path . '/admin/pages/Settings.php';
		// require_once self::$plugin_path . '/admin/pages/Help.php';
		// require_once self::$plugin_path . '/admin/pages/Widgets.php';
		// require_once self::$plugin_path . '/admin/pages/Plugins.php';
		// require_once self::$plugin_path . '/admin/pages/Modules.php';
	}

	/**
	 * Init
	 */
	public function init() {
		add_action( 'admin_menu', [ $this, 'register_menu' ], 10 );
		add_action( 'admin_menu', [ $this, 'admin_menu_change_name' ], 200 );
		add_filter( 'admin_body_class', [ $this, 'add_admin_body_class' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		add_action( BPB_HOOK_PREFIX . 'panel_action', [ $this, 'main_panel' ] );
	}

	/**
	 * Adds the menu item in the admin panel
	 */
	public function register_menu() {
		add_menu_page(
			__( 'BuddyBuilder - Dashboard', 'stax-buddy-builder' ),
			__( 'BuddyBuilder', 'stax-buddy-builder' ),
			'manage_options',
			Helpers::get_instance()->get_slug(),
			[ $this, 'settings_template' ],
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxNTM2IDE1MzYiPjx0aXRsZT5icDwvdGl0bGU+PHBhdGggZD0iTTc1OC43MiwyMjVjMi45MSwxLjA5LDE0LjUzLDQuNzIsMjUuNDIsOC4zNSw4My41MiwyNS43OCwxNjQuODYsOTUuNSwyMDQuODEsMTc1LjM4LDUwLjExLDk5LjEzLDUwLjExLDIyMS4xMywwLDMyMC42Mi02LjksMTMuNDQtMTIuMzUsMjUuNDItMTIuMzUsMjYuMTQsMCwyLjU0LDI1Ljc4LTUuMDgsNDguNjYtMTQuODksODMuNTItMzQuODYsMTQ3LjA3LTExMCwxNjkuNTgtMjAwLjgsNi41NC0yNS43OCw3LjYzLTM4LjEzLDYuMTctNzkuODgtMS40NS00Mi44NS0zLjI3LTUzLTEyLjcxLTgwLjYxLTM0Ljg2LTEwMC4yMi0xMjIuNzQtMTc3LjU2LTIyMC43OS0xOTUtNTIuNjUtOS4wOC05OS41LTYuMTctMTQ1LjI1LDguNzFDNzg2LjY4LDIwNC42OCw3NTEuMSwyMjIuNDgsNzU4LjcyLDIyNVoiIHN0eWxlPSJmaWxsOiNmZmYiLz48cGF0aCBkPSJNMzk0LjUsNjU4LjU2QzQzNi4yNiw3ODQuMiw1NDMuMzgsODYzLDY3Mi4zLDg2M2M0OC4zLDAsODIuMDctNi45LDEyMS42NS0yNS40Miw4MS0zNy43NiwxMzMuNjMtOTcuNjcsMTYwLjg3LTE4My4zNywxMC4xNy0zMi42OCwxMy44LTk2Ljk1LDcuMjYtMTMzLjYyQzk0Mi40Nyw0MDguNzUsODU4LjU4LDMxOC4zMyw3NDYsMjg3LjgzYy0yNS40Mi02LjktOTQuNDItMTAuNTMtMTE4Ljc1LTUuODEtMTE0LjM5LDIwLjctMjAzLjcyLDEwMS4zMS0yMzYsMjEyLjQyLTYuOSwyNC42OS04LjM1LDM3LTgsNzkuODhDMzgzLjI0LDYyMC44LDM4NC4zMyw2MjguMDYsMzk0LjUsNjU4LjU2WiIgc3R5bGU9ImZpbGw6I2ZmZiIvPjxwYXRoIGQ9Ik0xMTk5LjkzLDc4Ni43NGMtMTYuMzQtNS4wOC0zOS4yMi04LjcxLTYzLjU1LTkuOGwtMzguNDktMi4xOC0yNi4xNSwyOS43N2MtMTQuNTMsMTYuMzQtMjYuMTUsMzAuODYtMjYuMTUsMzIuNjgsMCwxLjQ1LDYuOSw1LjA4LDE1LjYyLDcuNjMsOTEuNTEsMjcuMjMsMTY5Ljk1LDExOC43MywyMDUuOSwyNDAsOC4zNSwyOCwyMS43OSw5OC40LDIxLjc5LDExMy42NSwwLDEuMDksMjguNjksMi4xOCw2My41NSwyLjE4LDU1LjIsMCw2My45MS0uNzMsNjYuMDktNS44MSwzLjYzLTkuNDQtNS44MS0xMTIuOTMtMTMuNDQtMTUwLjMyQzEzNzUuNjksOTA0Ljc1LDEzMDkuMjQsODIxLjIzLDExOTkuOTMsNzg2Ljc0WiIgc3R5bGU9ImZpbGw6I2ZmZiIvPjxwYXRoIGQ9Ik0xMTEzLjUxLDk1MS4yMmMtNTEuOTMtNDkuMzgtMTIzLjQ3LTgyLjA2LTE5OC42NC05MS41bC0yNy4yNC0zLjI3TDgxNSw5MjhjLTM5Ljk0LDM5LjIxLTg3Ljg4LDg2LjA1LTEwNi40LDEwMy44NWwtMzMuNDEsMzIuNjgtOTMuNjktOTIuNTlDNDUzLjY5LDg0NS4yLDQ2OC45NCw4NTYuNDUsNDMzLjcxLDg2MC4wOCwyNTEuNDIsODc5LjY5LDE0MC42NiwxMDAxLjMzLDExOS4yNCwxMjA1Yy00LjM2LDM5Ljk0LTIuOTEsMTQwLjE2LDIuMTgsMTQ3Ljc4LDEuNDUsMi45MSwxNjMuNDEsNCw1NTMuNDIsNGg1NTEuNmwyLjE4LTExLjI2YzQuMzYtMjEuNzksMi4xOC0xMjguNTQtMy4yNy0xNjQuODVDMTIxMC40NiwxMDgyLjMsMTE3NS4yNCwxMDA5LjY4LDExMTMuNTEsOTUxLjIyWiIgc3R5bGU9ImZpbGw6I2ZmZiIvPjwvc3ZnPg==',
			'58.9'
		);

		// Submenus.
		$sub_menus = apply_filters( BPB_HOOK_PREFIX . 'admin_menu', [] );

		if ( ! empty( $sub_menus ) ) {
			foreach ( $sub_menus as $submenu ) {
				$submenu_slug = 'buddy-builder-' . $submenu['slug'];

				if ( $submenu_slug === Helpers::get_instance()->get_slug() ) {
					continue;
				}
				add_submenu_page(
					Helpers::get_instance()->get_slug(),
					sprintf( __( 'STAX Elementor - %s', 'stax-buddy-builder' ), $submenu['name'] ),
					$submenu['name'],
					'manage_options',
					$submenu_slug,
					[ $this, 'settings_template' ]
				);
			}
		}
	}

	/**
	 * Change fist menu item name
	 */
	public function admin_menu_change_name() {
		global $submenu;

		if ( isset( $submenu[ Helpers::get_instance()->get_slug() ] ) ) {
			$submenu[ Helpers::get_instance()->get_slug() ][0][0] = __( 'Dashboard', 'stax-buddy-builder' );
		}
	}

	/**
	 * Settings template
	 */
	public function settings_template() {
		$site_url      = apply_filters( BPB_HOOK_PREFIX . 'admin_site_url', 'https://staxwp.com' );
		$wrapper_class = apply_filters( BPB_HOOK_PREFIX . 'welcome_wrapper_class', [ $this->current_slug ] );
		$menu          = apply_filters( BPB_HOOK_PREFIX . 'admin_menu', [] );
		$has_pro       = function_exists( 'bpb_is_pro' );

		if ( ! empty( $menu ) ) {
			usort(
				$menu,
				static function ( $a, $b ) {
					return $a['priority'] - $b['priority'];
				}
			);
		}

		Helpers::load_template(
			'admin/layout',
			[
				'site_url'      => $site_url,
				'wrapper_class' => $wrapper_class,
				'menu'          => $menu,
				'has_pro'       => $has_pro,
			]
		);
	}

	/**
	 * Add body class when on plugin's settings page
	 *
	 * @param $classes
	 *
	 * @return string
	 */
	public function add_admin_body_class( $classes ) {
		if ( isset( $_GET['page'] ) && strpos( sanitize_text_field( $_GET['page'] ), 'bpb-elementor' ) !== false ) {
			$classes .= ' bpb-elementor-admin-page';
		}

		return $classes;
	}

	/**
	 * Main template actions
	 */
	public function main_panel() {
		$current_slug = apply_filters( BPB_HOOK_PREFIX . 'current_slug', $this->current_slug );

		Helpers::load_template(
			'admin/actions',
			[
				'current_slug' => $current_slug,
			]
		);
	}

	/**
	 * Load scripts & styles
	 */
	public function admin_scripts() {
		$min = '.min';

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$min = '';
		}

		if ( isset( $_GET['page'] ) && strpos( sanitize_text_field( $_GET['page'] ), BPB_ADMIN_PREFIX ) !== false ) {
			wp_register_style(
				'stax-buddy-addons-tw',
				BPB_ADMIN_ASSETS_URL . 'css/admin' . $min . '.css',
				[],
				BPB_VERSION,
				'all'
			);

			wp_enqueue_style( 'stax-buddy-addons-tw' );
		}

		wp_register_style(
			'stax-buddy-notice',
			BPB_ADMIN_ASSETS_URL . 'css/notice' . $min . '.css',
			[],
			BPB_VERSION,
			'all'
		);

		wp_enqueue_style( 'stax-buddy-notice' );

		wp_enqueue_script(
			'stax-buddy-admin',
			BPB_ADMIN_ASSETS_URL . 'js/admin.js',
			[ 'jquery' ],
			BPB_VERSION,
			true
		);
	}

}

Admin::get_instance();
