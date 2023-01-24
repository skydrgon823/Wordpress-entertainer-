<?php
// Exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a list og valid view options
 *
 * @return array
 */
function bp_fm_get_views_options() {
	$options = array(
		'list'    => __( 'List', 'bp-featured-members' ),
		'slider'  => __( 'Slider', 'bp-featured-members' ),
		'default' => __( 'Theme Default', 'bp-featured-members' ),
	);

	return $options;
}

/**
 * Print slider data attributes.
 *
 * @param array $settings array of atts.
 */
function bp_members_slider_data_attributes( $settings = array() ) {
	foreach ( $settings as $key => $val ) {
		echo "data-$key='" . esc_attr( $val ) . "' ";
	}
}

/**
 * Load featured members list.
 *
 * @param string $view_type selected view type.
 * @param string $context widget/shortcode etc.
 * @param bool   $load load or return.
 *
 * @return mixed|string|void
 */
function bp_fm_load_members_list( $view_type, $context = 'widget', $load = true ) {

	if ( ! in_array( $view_type, array( 'default', 'list', 'slider' ) ) ) {
		$view_type = 'list';// fallback to list if invalid view type.
	}

	// in case of default view type, we load it from theme.
	if ( $view_type == 'default' ) {
		$located = bp_locate_template( array( 'members/members-loop.php' ), false, false );
	} else {

		$templates = array(
			"members/featured/{$context}/members-loop-{$view_type}.php",
			"members/featured/members-loop-{$view_type}.php",
		);

		$located = bp_locate_template( $templates, false, false );

		if ( ! $located ) {
			$located = bp_featured_members()->get_path() . 'templates/members-loop-' . $view_type . '.php';
		}
	}

	$located = apply_filters( 'bp_featured_members_located_template', $located, $view_type, $context );

	if ( $load ) {
		require $located;
	} else {
		return $located;
	}
}

/**
 * Get avatar args
 *
 * @return array
 */
function bp_fm_get_avatar_args() {

	$avatar_size = bp_featured_members()->get( 'avatar_size' );
	$avatar_type = bp_featured_members()->get( 'avatar_type' );

	return array(
		'type'   => $avatar_type,
		'width'  => $avatar_size,
		'height' => $avatar_size,
	);
}
