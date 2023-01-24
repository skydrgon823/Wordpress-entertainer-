<?php

namespace Buddy_Builder\Widgets;

use Buddy_Builder\Library\Documents\BuddyPress;
use Buddy_Builder\Plugin;

class Base extends \Elementor\Widget_Base {

	/**
	 * @return array|mixed|void
	 */
	public function get_script_depends() {
		return apply_filters( 'buddy_builder/' . $this->get_name() . '/widget_script_depends', [] );
	}

	/**
	 * @inheritDoc
	 */
	public function get_name() {
	}

	/**
	 * @return bool
	 */
	public function show_in_panel() {
		global $post;

		$template_type = get_post_meta( $post->ID, BuddyPress::REMOTE_CATEGORY_META_KEY, true );
		$show          = false;

		foreach ( Plugin::get_instance()->get_elements() as $element ) {
			if ( $element['name'] === $this->get_name() ) {
				if ( ! isset( $element['template'] ) && ! $template_type ) {
					// Display general widgets only on non buddypress pages
					$show = true;
				} elseif ( isset( $element['template'] ) && $template_type ) {
					if ( is_array( $element['template'] ) && in_array( $template_type, $element['template'], true ) ) {
						$show = true;
					} elseif ( $element['template'] === $template_type ) {
						$show = true;
					}
				}
			}
		}

		return $show;
	}

	/**
	 * Render
	 */
	protected function render() {
		wp_print_styles( [ 'stax-buddy-builder-front' ] );
	}

}
