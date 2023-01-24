<?php
/**
 * Add Customizer Hooks
 *
 * @package Buddy_Builder
 * @since 1.0.0
 */

namespace Buddy_Builder;

defined( 'ABSPATH' ) || die();

/**
 * Class CustomizerHooks
 *
 * @package Buddy_Builder
 */
class CustomizerHooks extends Singleton {

	/**
	 * CustomizerHooks constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'customize_save_after', [ $this, 'update_elementor_templates' ] );
	}

	/**
	 * Update Elementor settings when updating Customizer
	 *
	 * @param $wp_customize
	 */
	public function update_elementor_templates( $wp_customize ) {
		$bp_appearance = bpb_get_appearance();
		$bpb_settings  = bpb_get_settings();

		foreach ( $bpb_settings['templates'] as $type => $id ) {
			$document = \Elementor\Plugin::$instance->documents->get( $id );

			if ( $document ) {
				$elements = \Elementor\Plugin::$instance->db->iterate_data(
					$document->get_elements_data(),
					static function ( $element ) use ( $bp_appearance ) {
						if ( empty( $element['widgetType'] ) ) {
							return $element;
						}

						if ( $element['widgetType'] === 'bpb-profile-member-navigation' && isset( $element['settings']['show_home_tab'] ) ) {
							$element['settings']['show_home_tab'] = $bp_appearance['user_front_page'] ? 'yes' : 'no';
						}

						if ( $element['widgetType'] === 'bpb-profile-group-navigation' && isset( $element['settings']['show_home_tab'] ) ) {
							$element['settings']['show_home_tab'] = $bp_appearance['group_front_page'] ? 'yes' : 'no';
						}

						return $element;
					}
				);

				if ( is_array( $elements ) ) {
					$editor_data = $document->get_elements_raw_data( $elements );
					$json_value  = wp_slash( wp_json_encode( $editor_data ) );
					update_metadata( 'post', $id, '_elementor_data', $json_value );

					\Elementor\Plugin::$instance->db->save_plain_text( $id );
				}
			}
		}
	}

}
