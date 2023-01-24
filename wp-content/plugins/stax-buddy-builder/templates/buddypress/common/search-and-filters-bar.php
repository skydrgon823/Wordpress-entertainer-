<?php
/**
 * BP Nouveau Search & filters bar
 *
 * @since 3.0.0
 * @version 3.1.0
 */
?>
<div class="subnav-filters filters no-ajax" id="subnav-filters">

	<?php
	$show_list_toggle  = apply_filters( 'buddy_builder/widget/filters/list_toggle/enabled', false );
	$current_component = bp_current_component();

	if ( $show_list_toggle && in_array( $current_component, [ 'members', 'groups', 'friends' ] ) ) :
		?>
		<?php
		if ( $current_component === 'friends' ) {
			$current_component = 'members';
		}
		?>
		<div class="bpb-listing-type" data-component="<?php echo esc_attr( $current_component ); ?>">
			<span class="bpb-list-change bpb-list-mode" data-list-type="list">
				<span class="dashicons dashicons-list-view"></span>
			</span>
			<span class="bpb-list-change bpb-grid-mode bpb-active" data-list-type="grid">
				<span class="dashicons dashicons-grid-view"></span>
			</span>
		</div>

		<?php wp_enqueue_script( 'bpb-grid-list-view' ); ?>

	<?php else : ?>
		<script>
			var storeType = 'bp-<?php echo esc_attr( $current_component ); ?>';
			var storeData = sessionStorage.getItem( storeType );

			if ( storeData ) {
				storeData = JSON.parse( storeData );
				if ( undefined !== storeData[ 'bpb-list-mode' ] && undefined === storeData[ 'bpb-list-hidden' ] ) {
					storeData[ 'bpb-list-hidden' ] = 1;
					sessionStorage.setItem( storeType, JSON.stringify( storeData ) )
				}
			}
		</script>
	<?php endif; ?>

	<?php if ( 'friends' !== bp_current_component() ) : ?>
		<div class="subnav-search clearfix">

			<?php if ( 'activity' === bp_current_component() ) : ?>
				<div class="feed">
					<a href="<?php bp_sitewide_activity_feed_link(); ?>" class="bp-tooltip"
					   data-bp-tooltip="<?php esc_attr_e( 'RSS Feed', 'buddypress' ); ?>">
						<span class="bp-screen-reader-text">
							<?php esc_html_e( 'RSS', 'buddypress' ); ?>
						</span>
					</a>
				</div>
			<?php endif; ?>

			<?php bp_nouveau_search_form(); ?>

		</div>
	<?php endif; ?>

	<?php if ( bp_is_user() && ! bp_is_current_action( 'requests' ) ) : ?>
		<?php bp_get_template_part( 'common/filters/user-screens-filters' ); ?>
	<?php elseif ( 'groups' === bp_current_component() ) : ?>
		<?php bp_get_template_part( 'common/filters/groups-screens-filters' ); ?>
	<?php else : ?>
		<?php bp_get_template_part( 'common/filters/directory-filters' ); ?>
	<?php endif; ?>

</div><!-- search & filters -->
