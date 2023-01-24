<?php

namespace Buddy_Builder\Widgets\ProfileGroup;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class ExtraMeta extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-profile-group-extra-meta';
	}

	public function get_title() {
		return esc_html__( 'Extra Meta', 'stax-buddy-builder' );
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
			bpb_load_template( 'preview/profile-group/meta' );
		} else {
			bp_nouveau_group_hook( 'before', 'header_meta' );

			?>
			<?php if ( bp_nouveau_group_has_meta_extra() ) : ?>
				<div class="item-meta">
					<?php echo bp_nouveau_the_group_meta( array( 'keys' => 'extra' ) ); ?>
				</div>
			<?php endif; ?>
			<?php
		}
	}

}
