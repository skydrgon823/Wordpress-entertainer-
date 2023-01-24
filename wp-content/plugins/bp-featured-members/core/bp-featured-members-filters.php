<?php
/**
 * List filtering.
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Filter bp_has_members() transparently for listing our members
 *
 * @param array $args args array.
 *
 * @return mixed
 */
function bp_featured_members_filter_members_list( $args ) {
	// our scope must be featured.
	if ( bp_featured_members()->in_the_loop() || ( isset( $args['scope'] ) && $args['scope'] == 'featured' ) ) {

		$args['meta_key']   = '_is_featured';
		$args['meta_value'] = 1;

		// which other params are we allowing?
		$max         = bp_featured_members()->get( 'max' );
		$member_type = bp_featured_members()->get( 'member_type' );

		if ( $max ) {
			$args['per_page'] = absint( $max );
			$args['max']      = absint( $max );
		}

		if ( $member_type ) {
			$args['member_type'] = $member_type;
		}
	}

	return $args;
}

add_filter( 'bp_after_has_members_parse_args', 'bp_featured_members_filter_members_list' );
