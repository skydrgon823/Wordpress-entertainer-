<?php
/**
 * BuddyPress - Groups Members
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php if ( bp_group_has_members( bp_ajax_querystring( 'group_members' ) ) ) : ?>

	<?php

	/**
	 * Fires before the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_content' ); ?>

	<?php

	/**
	 * Fires before the display of the group members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_list' ); ?>

	<ul id="member-list" class="item-list">

		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

			<li class="message">
				<div class="avatar">
					<a href="<?php bp_group_member_domain(); ?>">
	
						<?php bp_group_member_avatar_thumb(); ?>
	
					</a>
				</div>
				<div class="item">
					<h5><?php bp_group_member_link(); ?></h5>
					<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_member_joined_since( array( 'relative' => false ) ) ); ?>"><?php bp_group_member_joined_since(); ?></span>
	
					<?php
	
					/**
					 * Fires inside the listing of an individual group member listing item.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_group_members_list_item' ); ?>
				</div>

				<?php if ( bp_is_active( 'friends' ) ) : ?>

					<div class="action">

						<?php bp_add_friend_button( bp_get_group_member_id(), bp_get_group_member_is_friend() ); ?>

						<?php

						/**
						 * Fires inside the action section of an individual group member listing item.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_group_members_list_item_action' ); ?>

					</div>

				<?php endif; ?>
			</li>

		<?php endwhile; ?>

	</ul>

	<?php

	/**
	 * Fires after the display of the group members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_members_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php

	/**
	 * Fires after the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>

	<div id="message" class="alert-box">
		<?php _e( 'No members were found.', 'buddypress' ); ?>
	</div>

<?php endif;
