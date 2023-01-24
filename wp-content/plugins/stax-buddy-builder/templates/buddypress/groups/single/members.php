<?php
/**
 * BuddyPress - Groups Members
 *
 * @since 3.0.0
 * @version 3.0.0
 */

$show_list_toggle = apply_filters( 'buddy_builder/widget/filters/list_toggle/enabled', false );
?>

<div class="subnav-filters filters clearfix no-subnav">
	<?php if ( $show_list_toggle && in_array( bp_current_component(), [ 'members', 'groups', 'friends' ] ) ) : ?>

		<div class="bpb-listing-type" data-component="members">
		<span class="bpb-list-mode">
			<span class="dashicons dashicons-list-view"></span>
		</span>
			<span class="bpb-grid-mode bpb-active">
			<span class="dashicons dashicons-grid-view "></span>
		</span>
		</div>

		<?php wp_enqueue_script( 'bpb-grid-list-view' ); ?>

	<?php else : ?>
		<script>
			var storeType = 'bp-<?php echo esc_attr( bp_current_component() ); ?>';
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

	<?php bp_nouveau_search_form(); ?>

	<?php bp_get_template_part( 'common/filters/groups-screens-filters' ); ?>

</div>


<h2 class="bp-screen-title">
	<?php esc_html_e( 'Membership List', 'buddypress' ); ?>
</h2>


<div id="members-group-list" class="group_members dir-list" data-bp-list="group_members">

	<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'group-members-loading' ); ?></div>

</div><!-- .group_members.dir-list -->
