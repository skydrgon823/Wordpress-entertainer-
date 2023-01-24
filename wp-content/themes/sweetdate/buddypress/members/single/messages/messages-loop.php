<?php
/**
 * BuddyPress - Members Messages Loop
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

/**
 * Fires before the members messages loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_messages_loop' ); ?>

<?php if ( bp_has_message_threads( bp_ajax_querystring( 'messages' ) ) ) : ?>

	<h2 class="bp-screen-reader-text"><?php
		/* translators: accessibility text */
		_e( 'Starred messages', 'buddypress' );
	?></h2>
	
	<div class="pagination no-ajax" id="user-pag">

		<div class="pag-count" id="messages-dir-count">
			<?php bp_messages_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="messages-dir-pag">
			<?php bp_messages_pagination(); ?>
		</div>

	</div><!-- .pagination -->

	<?php

	/**
	 * Fires after the members messages pagination display.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_messages_pagination' ); ?>

	<?php

	/**
	 * Fires before the members messages threads.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_messages_threads' ); ?>

	<form action="<?php echo bp_displayed_user_domain() . bp_get_messages_slug() . '/' . bp_current_action() ?>/bulk-manage/" method="post" id="messages-bulk-management">

		<table id="message-threads" class="messages-notices">

			<tbody>

				<?php while ( bp_message_threads() ) : bp_message_thread(); ?>
					
					<tr id="m-<?php bp_message_thread_id(); ?>" class="<?php bp_message_css_class(); ?><?php if ( bp_message_thread_has_unread() ) : ?> unread<?php else: ?> read<?php endif; ?>">
						
						<td width="2%" class="thread-count">
							<span class="unread-count"><?php echo str_replace( array( '(', ')' ), '', bp_get_message_thread_total_and_unread_count() ); ?></span>
						</td>

						<td width="9%" class="thread-avatar"><?php bp_message_thread_avatar(); ?></td>
						
						<?php if ( bp_is_active( 'messages', 'star' ) ) : ?>
							<td class="thread-star">
								<?php bp_the_message_star_action_link( array( 'thread_id' => bp_get_message_thread_id(), 'text_star' => '<i class="icon-star-empty"></i>', 'text_unstar' => '<i class="icon-star"></i>' ) ); ?>
							</td>
						<?php endif; ?>
						
						<?php if ( 'sentbox' != bp_current_action() ) : ?>
							<td class="thread-from">
								<span class="from"><?php _e( 'From:', 'buddypress' ); ?></span> <?php bp_message_thread_from(); ?><br>
								<span class="activity"><?php bp_message_thread_last_post_date(); ?></span>
							</td>
						<?php else: ?>
							<td class="thread-from">
								<span class="to"><?php _e( 'To:', 'buddypress' ); ?></span> <?php bp_message_thread_to(); ?><br>
								<span class="activity"><?php bp_message_thread_last_post_date(); ?></span>
							</td>
						<?php endif; ?>

						<td class="thread-info">
							<p><a href="<?php bp_message_thread_view_link( bp_get_message_thread_id(), bp_displayed_user_id() ); ?>" class="bp-tooltip" data-bp-tooltip="<?php esc_attr_e( "View Message", 'buddypress' ); ?>" aria-label="<?php esc_attr_e( "View Message", 'buddypress' ); ?>"><?php bp_message_thread_subject(); ?></a></p>
							<p class="thread-excerpt"><?php bp_message_thread_excerpt(); ?></p>
						</td>

						<?php

						/**
						 * Fires inside the messages box table row to add a new column.
						 *
						 * This is to primarily add a <td> cell to the message box table. Use the
						 * related 'bp_messages_inbox_list_header' hook to add a <th> header cell.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_messages_inbox_list_item' ); ?>

						<td width="19%" class="thread-options">

							<input type="checkbox" name="message_ids[]" id="bp-message-thread-<?php bp_message_thread_id(); ?>" class="message-check" value="<?php bp_message_thread_id(); ?>" /><span class="bp-screen-reader-text"><?php
									/* translators: accessibility text */
									_e( 'Select this message', 'buddypress' );
									?></span>
							<a class="delete" href="<?php bp_message_thread_delete_link( bp_displayed_user_id() ); ?>"><i class="icon-remove-sign"></i</a>

							<?php

							/**
							 * Fires after the thread options links for each message in the messages loop list.
							 *
							 * @since 2.5.0
							 */
							do_action( 'bp_messages_thread_options' ); ?>
						</td>
					</tr>

				<?php endwhile; ?>

			</tbody>

		</table><!-- #message-threads -->

		<div class="messages-options-nav">
			<?php  bp_messages_bulk_management_dropdown(); ?>
		</div><!-- .messages-options-nav -->

		<?php wp_nonce_field( 'messages_bulk_nonce', 'messages_bulk_nonce' ); ?>
	</form>

	<?php

	/**
	 * Fires after the members messages threads.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_messages_threads' ); ?>

	<?php

	/**
	 * Fires and displays member messages options.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_messages_options' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, no messages were found.', 'buddypress' ); ?></p>
	</div>

<?php endif;?>

<?php

/**
 * Fires after the members messages loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_messages_loop' );
