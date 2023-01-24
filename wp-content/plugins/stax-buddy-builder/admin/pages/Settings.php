<?php

namespace Buddy_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Buddy_Builder\Library\Documents\BuddyPress;
use WP_Query;

/**
 * Class Settings
 *
 * @package Buddy_Builder
 */
class Settings extends Base {

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		$this->current_slug = 'settings';

		if ( Helpers::get_instance()->is_current_page( $this->current_slug ) ) {
			add_filter( BPB_HOOK_PREFIX . 'current_slug', [ $this, 'set_page_slug' ] );
			add_filter( BPB_HOOK_PREFIX . 'welcome_wrapper_class', [ $this, 'set_wrapper_classes' ] );

			add_action( BPB_HOOK_PREFIX . $this->current_slug . '_page_content_before', [ $this, 'save_notification' ] );
			add_action( BPB_HOOK_PREFIX . $this->current_slug . '_page_content', [ $this, 'panel_content' ] );
		}

		add_filter( BPB_HOOK_PREFIX . 'admin_menu', [ $this, 'add_menu_item' ] );
		add_action( 'admin_post_bpb_settings', [ $this, 'handle_settings_request' ] );
	}

	/**
	 * Handle settings save
	 */
	public function handle_settings_request() {
		if ( $_SERVER['REQUEST_METHOD'] !== 'POST' || ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$action   = sanitize_text_field( $_POST['action'] );
		$did_save = '';

		if ( ! empty( $action ) && $action === 'bpb_settings' ) {
			$settings = bpb_get_settings();

			$simple_array_settings = [
				'templates',
			];

			foreach ( $simple_array_settings as $item ) {
				foreach ( $settings[ $item ] as $field => $value ) {
					if ( isset( $_POST[ $field ] ) ) {
						$settings[ $item ][ $field ] = sanitize_text_field( $_POST[ $field ] );
					}
				}
			}

			foreach ( $settings['templates'] as $template ) {
				if ( $template ) {
					ElementorHooks::get_instance()->save_buddypress_options( $template, null );
				}
			}

			update_option( 'bpb_settings', $settings );
			$did_save = '&did_save';
		}

		wp_redirect( admin_url( 'admin.php?page=' . BPB_ADMIN_PREFIX . $this->current_slug . $did_save ) );
	}

	/**
	 * Save settings notification
	 */
	public function save_notification() {
		if ( isset( $_GET['did_save'] ) ) {
			Helpers::load_template(
				'admin/pages/templates/parts/notification-success',
				[
					'message' => __( 'YEY! Your settings have been saved successfully!', 'stax-buddy-builder' ),
				]
			);
		}
	}

	/**
	 * Panel template
	 */
	public function panel_content() {
		$settings = bpb_get_settings();

		$templates = [
			'member-profile'    => [
				'title' => __( 'Member Profile', 'stax-buddy-builder' ),
				'order' => 2,
			],
			'members-directory' => [
				'title' => __( 'Members Directory', 'stax-buddy-builder' ),
				'order' => 1,
			],
			'group-profile'     => [
				'title' => __( 'Group Profile', 'stax-buddy-builder' ),
				'order' => 4,
			],
			'groups-directory'  => [
				'title' => __( 'Groups Directory', 'stax-buddy-builder' ),
				'order' => 3,
			],
			'sitewide-activity' => [
				'title' => __( 'Site-Wide Activity', 'stax-buddy-builder' ),
				'order' => 5,
			],
		];

		$templates = apply_filters( 'buddy_builder/admin/templates_list', $templates );

		// set active template
		foreach ( $templates as $key => $item ) {
			if ( isset( $settings['templates'][ $key ] ) ) {
				$templates[ $key ]['template'] = (int) $settings['templates'][ $key ];
			}
		}

		$data = [];

		$args = [
			'post_type'  => 'elementor_library',
			'meta_query' => [
				[
					'key'     => '_elementor_template_type',
					'value'   => 'bpb-buddypress',
					'compare' => '=',
				],
			],
		];

		foreach ( $templates as $slug => $item ) {
			$query_args                 = $args;
			$query_args['meta_query'][] = [
				'key'     => BuddyPress::REMOTE_CATEGORY_META_KEY,
				'value'   => $slug,
				'compare' => '=',
			];

			$query = new WP_Query( $query_args );
			$posts = [];

			foreach ( $query->posts as $post ) {
				$posts[] = [
					'id'     => $post->ID,
					'title'  => $post->post_title,
					'status' => $post->ID === $item['template'],
				];
			}

			$data[ $slug ] = [
				'title' => $item['title'],
				'pro'   => isset( $item['pro'] ) && $item['pro'],
				'order' => $item['order'],
				'posts' => $posts,
			];

			wp_reset_query();
		}

		// Restructure templates.
		$new_data = [];

		$new_data['members'] = [
			'inner' => [
				'members-directory' => $data['members-directory'],
			],
			'order' => $data['members-directory']['order'],
		];

		$new_data['groups'] = [
			'inner' => [
				'groups-directory' => $data['groups-directory'],
			],
			'order' => $data['groups-directory']['order'],
		];

		$new_data['sitewide'] = [
			'inner' => [
				'sitewide-activity' => $data['sitewide-activity'],
			],
			'order' => $data['sitewide-activity']['order'],
		];

		unset( $data['members-directory'], $data['groups-directory'], $data['sitewide-activity'] );

		$new_data = apply_filters( 'buddy_builder/admin/templates_list/after_restructure', array_merge( $new_data, $data ) );

		uasort(
			$new_data,
			static function ( $a, $b ) {
				return $a['order'] - $b['order'];
			}
		);

		Helpers::load_template(
			'admin/pages/templates/settings',
			[
				'data' => $new_data,
			]
		);
	}

	/**
	 * Panel menu item
	 *
	 * @param $menu
	 *
	 * @return array
	 */
	public function add_menu_item( $menu ) {
		$menu[] = [
			'slug'     => $this->current_slug,
			'name'     => __( 'Settings', 'stax-buddy-builder' ),
			'link'     => admin_url( 'admin.php?page=' . BPB_ADMIN_PREFIX . $this->current_slug ),
			'query'    => BPB_ADMIN_PREFIX . $this->current_slug,
			'priority' => 1,
		];

		return $menu;
	}


}

Settings::get_instance();
