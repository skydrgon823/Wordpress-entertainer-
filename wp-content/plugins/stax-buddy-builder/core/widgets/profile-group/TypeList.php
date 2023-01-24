<?php

namespace Buddy_Builder\Widgets\ProfileGroup;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class TypeList extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-profile-group-type-list';
	}

	public function get_title() {
		return esc_html__( 'Type List', 'stax-buddy-builder' );
	}

	public function get_icon() {
		return 'sq-icon-bp_status sq-widget-label';
	}

	public function get_categories() {
		return [ 'buddy-builder-elements' ];
	}

	protected function _register_controls() {

	}

	protected function render() {
		parent::render();
		if ( bpb_is_elementor_editor() ) {
			bpb_load_template( 'preview/profile-group/type' );
		} else {
			bp_member_type_list(
				bp_displayed_user_id(),
				array(
					'label'        => array(
						'plural'   => __( 'Member Types', 'buddypress' ),
						'singular' => __( 'Member Type', 'buddypress' ),
					),
					'list_element' => 'span',
				)
			);
		}
	}

}
