<?php

namespace Buddy_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Helpers
 *
 * @package Buddy_Builder
 */
class Helpers extends Singleton {

	/**
	 * Load template
	 *
	 * @param $name
	 * @param array $args
	 * @param bool  $echo
	 *
	 * @return false|string|void
	 */
	public static function load_template( $name, $args = [], $echo = true ) {
		if ( ! $name ) {
			return;
		}

		extract( $args );

		ob_start();
		include BPB_BASE_PATH . trim( $name ) . '.php';

		if ( $echo ) {
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}

	/**
	 * Check current page
	 *
	 * @param $page
	 *
	 * @return bool
	 */
	public function is_current_page( $page ) {
		$page = BPB_ADMIN_PREFIX . $page;

		return isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) === $page;
	}

	/**
	 * Get plugin slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return BPB_ADMIN_PREFIX . 'settings';
	}

	/**
	 * @param $plugin_slug
	 *
	 * @return bool
	 */
	public function is_plugin_active( $plugin_slug ) {
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		foreach ( $active_plugins as $plugin ) {
			if ( $plugin === $plugin_slug ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $plugin_path
	 *
	 * @return bool
	 */
	public function is_plugin_installed( $plugin_path ) {
		$plugins = get_plugins();

		return isset( $plugins[ $plugin_path ] );
	}

}
