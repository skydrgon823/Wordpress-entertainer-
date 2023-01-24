<?php
defined( 'ABSPATH' ) || die();

if ( ! function_exists( 'bp_nouveau_after_directory_page' ) ) {
	/**
	 * Template tag to wrap the Legacy actions that was used
	 * after the components directory page.
	 *
	 * @since 6.0.0
	 */
	function bp_nouveau_after_directory_page() {
		$component = bp_current_component();

		/**
		 * Fires at the bottom of the activity, members, groups and blogs directory template file.
		 *
		 * @since 1.5.0 Added to the members, groups directory template file.
		 * @since 2.3.0 Added to the blogs directory template file.
		 * @since 6.0.0 Added to the activity directory template file.
		 */
		do_action( "bp_after_directory_{$component}_page" );
	}
}

if ( ! function_exists( 'bp_nouveau_group_description_excerpt' ) ) {
	/**
	 * Output the group description excerpt
	 *
	 * @param object $group Optional. The group being referenced.
	 *                      Defaults to the group currently being iterated on in the groups loop.
	 * @param int    $length Optional. Length of returned string, including ellipsis. Default: 100.
	 *
	 * @return string Excerpt.
	 * @since 3.0.0
	 */
	function bp_nouveau_group_description_excerpt( $group = null, $length = null ) {
		echo bp_nouveau_get_group_description_excerpt( $group, $length );
	}
}


if ( ! function_exists( 'bp_nouveau_get_group_description_excerpt' ) ) {
	/**
	 * Filters the excerpt of a group description.
	 *
	 * Checks if the group loop is set as a 'Grid' layout and returns a reduced excerpt.
	 *
	 * @param object $group Optional. The group being referenced. Defaults to the group currently being
	 *                      iterated on in the groups loop.
	 * @param int    $length Optional. Length of returned string, including ellipsis. Default: 100.
	 *
	 * @return string Excerpt.
	 * @since 3.0.0
	 */
	function bp_nouveau_get_group_description_excerpt( $group = null, $length = null ) {
		global $groups_template;

		if ( ! $group ) {
			$group =& $groups_template->group;
		}

		/**
		 * If this is a grid layout but no length is passed in set a shorter
		 * default value otherwise use the passed in value.
		 * If not a grid then the BP core default is used or passed in value.
		 */
		if ( bp_nouveau_loop_is_grid() && 'groups' === bp_current_component() ) {
			if ( ! $length ) {
				$length = 100;
			} else {
				$length = $length;
			}
		}

		/**
		 * Filters the excerpt of a group description.
		 *
		 * @param string $value Excerpt of a group description.
		 * @param object $group Object for group whose description is made into an excerpt.
		 *
		 * @since 3.0.0
		 */
		return apply_filters( 'bp_nouveau_get_group_description_excerpt', bp_create_excerpt( $group->description, $length ), $group );
	}
}


if ( ! function_exists( 'bp_member_latest_update' ) ) {
	/**
	 * Output the latest update of the current member in the loop.
	 *
	 * @since 1.2.0
	 *
	 * @param array|string $args {@see bp_get_member_latest_update()}.
	 */
	function bp_member_latest_update( $args = '' ) {
		echo bp_get_member_latest_update( $args );
	}
}

if ( ! function_exists( 'bp_get_member_latest_update' ) ) {
	/**
	 * Get the latest update from the current member in the loop.
	 *
	 * @since 1.2.0
	 *
	 * @param array|string $args {
	 *     Array of optional arguments.
	 *     @type int  $length    Truncation length. Default: 225.
	 *     @type bool $view_link Whether to provide a 'View' link for
	 *                           truncated entries. Default: false.
	 * }
	 * @return string
	 */
	function bp_get_member_latest_update( $args = '' ) {
		global $members_template;

		$defaults = [
			'length'    => 225,
			'view_link' => true,
		];

		$r = wp_parse_args( $args, $defaults );
		extract( $r );

		if ( ! bp_is_active( 'activity' ) || empty( $members_template->member->latest_update ) || ! $update = maybe_unserialize( $members_template->member->latest_update ) ) {
			return false;
		}

		/**
		 * Filters the excerpt of the latest update for current member in the loop.
		 *
		 * @since 1.2.5
		 * @since 2.6.0 Added the `$r` parameter.
		 *
		 * @param string $value Excerpt of the latest update for current member in the loop.
		 * @param array  $r     Array of parsed arguments.
		 */
		$update_content = apply_filters( 'bp_get_activity_latest_update_excerpt', trim( strip_tags( bp_create_excerpt( $update['content'], $length ) ) ), $r );

		/* translators: %s: the member latest activity update */
		$update_content = sprintf( _x( '- &quot;%s&quot;', 'member latest update in member directory', 'buddypress' ), $update_content );

		// If $view_link is true and the text returned by bp_create_excerpt() is different from the original text (ie it's
		// been truncated), add the "View" link.
		if ( $view_link && ( $update_content != $update['content'] ) ) {
			$view = __( 'View', 'buddypress' );

			$update_content .= '<span class="activity-read-more"><a href="' . bp_activity_get_permalink( $update['id'] ) . '" rel="nofollow">' . $view . '</a></span>';
		}

		/**
		 * Filters the latest update from the current member in the loop.
		 *
		 * @since 1.2.0
		 * @since 2.6.0 Added the `$r` parameter.
		 *
		 * @param string $update_content Formatted latest update for current member.
		 * @param array  $r              Array of parsed arguments.
		 */
		return apply_filters( 'bp_get_member_latest_update', $update_content, $r );
	}
}

