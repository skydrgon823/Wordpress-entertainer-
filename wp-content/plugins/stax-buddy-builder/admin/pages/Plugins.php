<?php

namespace Buddy_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Plugins
 *
 * @package Buddy_Builder
 */
class Plugins extends Base {

	/**
	 * Plugins constructor.
	 */
	public function __construct() {
		$this->current_slug = 'plugins';

		if ( Helpers::get_instance()->is_current_page( $this->current_slug ) ) {
			add_filter( BPB_HOOK_PREFIX . 'current_slug', [ $this, 'set_page_slug' ] );
			add_filter( BPB_HOOK_PREFIX . 'welcome_wrapper_class', [ $this, 'set_wrapper_classes' ] );
			add_action( BPB_HOOK_PREFIX . $this->current_slug . '_page_content', [ $this, 'panel_content' ] );
		}

		add_filter( BPB_HOOK_PREFIX . 'admin_menu', [ $this, 'add_menu_item' ] );
		add_action( 'admin_post_stax_plugin_activation', [ $this, 'toggle_plugins' ] );
	}

	/**
	 * Toggle plugins
	 */
	public function toggle_plugins() {
		if ( ! isset( $_POST['action'] ) || sanitize_text_field( $_POST['action'] ) !== 'stax_plugin_activation' ) {
			wp_redirect( admin_url( 'admin.php?page=' . BPB_ADMIN_PREFIX . $this->current_slug ) );
		}

		wp_redirect( admin_url( 'admin.php?page=' . BPB_ADMIN_PREFIX . $this->current_slug ) );
		exit();
	}

	/**
	 * Panel content
	 */
	public function panel_content() {
		Helpers::load_template(
			'core/admin/pages/templates/plugins',
			[
				'plugins' => [],
			]
		);
	}

	public function add_menu_item( $menu ) {
		$menu[] = [
			'slug'     => $this->current_slug,
			'name'     => __( 'Plugins', 'stax-buddy-builder' ),
			'link'     => admin_url( 'admin.php?page=' . BPB_ADMIN_PREFIX . $this->current_slug ),
			'query'    => BPB_ADMIN_PREFIX . $this->current_slug,
			'priority' => 3,
		];

		return $menu;
	}

}

Plugins::get_instance();
