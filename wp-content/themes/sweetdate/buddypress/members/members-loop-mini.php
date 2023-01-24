<?php

/**
 * BuddyPress - Members Loop Mini
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 */

?>

<?php do_action( 'bp_before_members_loop' ); ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ). '&per_page='.sq_option('buddypress_perpage') ) ) : ?>

	<?php do_action( 'bp_before_directory_members_list' ); ?>

	<div class="item-list friends-list" id="members-list">
	<?php while ( bp_members() ) : bp_the_member(); ?>

		<div class="four columns">
			<div class="friend-item">
				<div class="avatar">
				  <?php bp_member_avatar('type=thumb&width=94&height=94&class='); ?>
				</div>
				<div class="friend-meta">
				  <h5 class="author"><a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a></h5>
				</div>
			</div>
		</div>
            
	<?php endwhile; ?>
	</div>

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

        <!-- Pagination -->
        <div class="row">
            <div class="twelve columns pagination-centered">
                <div class="pagination">
                    <?php bp_members_pagination_links(); ?>
                </div>
            </div>
        </div>
        <!--end  Pagination-->

<?php else: ?>

	<div id="message" class="alert-box">
		<?php _e( "Sorry, no members were found.", 'sweetdate'); ?>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>