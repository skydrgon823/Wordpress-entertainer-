<?php
/**
 * Members list template
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

$view = bp_featured_members()->get( 'view' );
?>
<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) . '&scope=featured' ) ) : ?>
	<ul class="item-list featured-members-list featured-members-<?php echo esc_attr( $view ); ?>" >
	<?php while ( bp_members() ) : bp_the_member(); ?>
		<li class="featured-member-item ">
			<div class="item-avatar">
				<a href="<?php bp_member_permalink() ?>" title="<?php echo esc_attr( bp_get_member_name() ); ?>">
					<?php bp_member_avatar( bp_fm_get_avatar_args() ); ?>
				</a>
			</div>
			<div class="item">
				<div class="item-title">
					<a href="<?php bp_member_permalink(); ?>" title="<?php echo esc_attr( bp_get_member_name() ); ?>">
						<?php bp_member_name(); ?>
					</a>
				</div>
				<div class="item-meta">
				<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_member_last_active( array( 'relative' => false ) ) ); ?>">
					<?php bp_member_last_active(); ?>
				</span>
				</div>
			</div>
		</li>
	<?php endwhile; ?>
	</ul>

<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, no members were found.', 'bp-featured-members' ); ?></p>
	</div>

<?php endif; ?>
