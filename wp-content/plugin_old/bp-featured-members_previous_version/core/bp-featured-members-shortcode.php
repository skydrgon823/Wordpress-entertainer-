<?php
add_shortcode( 'bp-featured-members', 'bp_featured_members_shortcode' );
/**
 * Featured member as shortcde.
 *
 * @param array  $atts shortcode atts.
 * @param string $content content.
 *
 * @return string
 */
function bp_featured_members_shortcode( $atts, $content = '' ) {
	$atts = shortcode_atts( array(
		'view'                 => 'list', // list, slider, default.
		'max'                  => 5,
		'avatar_size'          => '',
		'member_type'          => '',
		'slide-item'           => 1,
		'slide-slide-margin'   => 0,
		'slide-mode'           => 'slide', // slide, fade.
		'slide-speed'          => 400,
		'slide-auto'           => true,
		'slide-pause-on-hover' => false,
		'slide-controls'       => false,
		'slide-loop'           => true,
	), $atts );

	$max         = $atts['max'];
	$avatar_size = $atts['avatar_size'];
	$view        = $atts['view'];

	bp_featured_members()->set( 'max', $max );
	bp_featured_members()->set( 'avatar_size', $avatar_size );
	bp_featured_members()->set( 'view', $view );
	bp_featured_members()->set( 'context', 'shortcode' );
	bp_featured_members()->set( 'member_type', isset( $atts['member_type'] ) ? $atts['member_type'] : '' );

	unset( $atts['max'] );
	unset( $atts['view'] );

	$new_settings = array();

	foreach ( $atts as $key => $val ) {
		$new_settings[ str_replace( 'slide-', '', $key ) ] = $val;
	}

	bp_featured_members()->set( 'slider-settings', $new_settings );

	if ( $avatar_size > BP_AVATAR_THUMB_WIDTH ) {
		bp_featured_members()->set( 'avatar_type', 'full' );
	} else {
		bp_featured_members()->set( 'avatar_type', 'thumb' );
	}

	// log loop start.
	bp_featured_members()->start_loop();

	ob_start();
	?>

	<div class="bp-featured-members-shortcode">
		<?php bp_fm_load_members_list( $view, 'shortcode' ); ?>
	</div>
	<?php
	bp_featured_members()->end_loop();// mark loop end.
	$content = ob_get_clean();
	return $content;
}
