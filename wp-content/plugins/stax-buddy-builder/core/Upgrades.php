<?php

namespace Buddy_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WP_Error;

/**
 * Class Upgrades
 *
 * @package Buddy_Builder
 */
class Upgrades extends Singleton {

	/**
	 * Option name that gets saved in the options database table
	 *
	 * @var string
	 */
	private $option_name = 'buddybuilder_db_version';

	/**
	 * Current plugin version
	 *
	 * @var string
	 */
	private $version = BPB_VERSION;

	/**
	 * Keep track if the data was updated during current request
	 *
	 * @var bool
	 */
	private $updated = false;

	/**
	 * Upgrade versions and method callbacks
	 *
	 * @var array
	 */
	private $upgrades = [
		'1.2.4' => [
			'method'             => '_upgrade_124',
			'skip_fresh_install' => true,
		],
	];

	/**
	 * Upgrades constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'admin_notices', [ $this, 'admin_notice' ], 20 );
	}

	/**
	 * Handle all the versions upgrades
	 */
	public function run() {
		$old_upgrades    = get_option( $this->option_name, [] );
		$current_version = $this->version;

		foreach ( $this->upgrades as $version => $upgrade ) {
			if ( ! isset( $old_upgrades[ $version ] ) && version_compare( $current_version, $version, '>=' ) ) {

				// Run the upgrade.
				$upgrade_result = $this->{$upgrade['method']}();

				// Early exit the loop if an error occurs.
				if ( $upgrade_result === true ) {
					$old_upgrades[ $version ] = true;
					$this->updated            = true;
				}
			}
		}

		// Save successful upgrades.
		update_option( $this->option_name, $old_upgrades );
	}

	/**
	 * Show admin notice to update
	 */
	public function admin_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( $this->is_new_update() ) {

			$this->process_update_action();

			// If we still need to show the message
			if ( ! $this->updated ) {
				Notices::get_instance()->buddybuilder_upgrade_db_notice();
			}
		}
	}

	/**
	 * Check if we have a new version update
	 *
	 * @return bool
	 */
	private function is_new_update() {
		// Check for database version.
		$old_upgrades    = get_option( $this->option_name ) ?: [];
		$current_version = $this->version;

		foreach ( $this->upgrades as $version => $upgrade ) {

			// fresh install
			if ( isset( $upgrade['skip_fresh_install'] ) && $upgrade['skip_fresh_install'] &&
				 ( empty( $old_upgrades ) && ! get_option( 'bpb_settings' ) ) ) {
				$old_upgrades[ $version ] = true;
				update_option( $this->option_name, $old_upgrades );

				continue;
			}

			if ( ! isset( $old_upgrades[ $version ] ) && version_compare( $current_version, $version, '>=' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Call the upgrade function and conditionally show admin notice
	 */
	private function process_update_action() {
		if ( isset( $_REQUEST['buddybuilder_db_update'] ) ) {
			$nonce = $_REQUEST['_wpnonce'];

			if ( wp_verify_nonce( $nonce, 'action' ) ) {
				$this->run();
			}

			if ( $this->updated === true ) {
				Notices::get_instance()->buddybuilder_upgrade_db_success_notice();
			} else {
				Notices::get_instance()->buddybuilder_upgrade_db_failed_notice();
			}
		}
	}

	/**
	 * @return bool|WP_Error
	 */
	private function _upgrade_124() {
		$settings = bpb_get_settings();

		foreach ( $settings['templates'] as $type => $post_id ) {
			if ( ! $post_id || ! in_array( $type, [ 'member-profile', 'group-profile' ] ) ) {
				continue;
			}

			$document = \Elementor\Plugin::$instance->documents->get( $post_id );

			if ( $document ) {
				$elements = \Elementor\Plugin::$instance->db->iterate_data(
					$document->get_elements_data(),
					static function ( $element ) {
						if ( empty( $element['widgetType'] ) || $element['elType'] !== 'widget' ) {
							return $element;
						}

						if ( $element['widgetType'] === 'bpb-profile-member-cover' || $element['widgetType'] === 'bpb-profile-group-cover' ) {
							if ( isset( $element['settings']['_position'] ) ) {
								$element['settings']['position'] = $element['settings']['_position'];
							}

							if ( isset( $element['settings']['_offset_orientation_h'] ) ) {
								$element['settings']['offset_orientation_h'] = $element['settings']['_offset_orientation_h'];
							}

							if ( isset( $element['settings']['_offset_x'] ) ) {
								$element['settings']['offset_x'] = $element['settings']['_offset_x'];
							}

							if ( isset( $element['settings']['_offset_x_end'] ) ) {
								$element['settings']['offset_x_end'] = $element['settings']['_offset_x_end'];
							}

							if ( isset( $element['settings']['_offset_orientation_v'] ) ) {
								$element['settings']['offset_orientation_v'] = $element['settings']['_offset_orientation_v'];
							}

							if ( isset( $element['settings']['_offset_y'] ) ) {
								$element['settings']['offset_y'] = $element['settings']['_offset_y'];
							}

							if ( isset( $element['settings']['_offset_y_end'] ) ) {
								$element['settings']['offset_y_end'] = $element['settings']['_offset_y_end'];
							}

							$element['settings']['_position'] = '';
						}

						return $element;
					}
				);

				if ( is_array( $elements ) ) {
					$editor_data = $document->get_elements_raw_data( $elements );
					$json_value  = wp_slash( wp_json_encode( $editor_data ) );
					update_metadata( 'post', $post_id, '_elementor_data', $json_value );

					\Elementor\Plugin::$instance->db->save_plain_text( $post_id );
				}
			}
		}

		return true;
	}

}
