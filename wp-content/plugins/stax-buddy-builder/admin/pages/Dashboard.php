<?php

namespace Buddy_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Dashboard
 *
 * @package Buddy_Builder
 */
class Dashboard extends Base {

	/**
	 * Dashboard constructor.
	 */
	public function __construct() {
		$this->current_slug = 'dashboard';

		if ( Helpers::get_instance()->is_current_page( $this->current_slug ) ) {
			add_filter( BPB_HOOK_PREFIX . 'current_slug', [ $this, 'set_page_slug' ] );
			add_filter( BPB_HOOK_PREFIX . 'welcome_wrapper_class', [ $this, 'set_wrapper_classes' ] );
			add_action( BPB_HOOK_PREFIX . $this->current_slug . '_page_content', [ $this, 'panel_content' ] );
		}

		add_filter( BPB_HOOK_PREFIX . 'admin_menu', [ $this, 'add_menu_item' ] );
	}

	public function panel_content() {
		Helpers::load_template( 'admin/pages/templates/dashboard' );
	}

	public function add_menu_item( $menu ) {
		$menu[] = [
			'slug'     => $this->current_slug,
			'name'     => __( 'Dashboard', 'stax-buddy-builder' ),
			'link'     => admin_url( 'admin.php?page=' . BPB_ADMIN_PREFIX . $this->current_slug ),
			'query'    => BPB_ADMIN_PREFIX . $this->current_slug,
			'priority' => 1,
		];

		return $menu;
	}

}

Dashboard::get_instance();
