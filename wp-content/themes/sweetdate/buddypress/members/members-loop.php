<?php
/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires before the display of the members loop.
 *
 * @since 1.2.0
 */
//echo 'sasadfsaf';die();
do_action( 'bp_before_members_loop' ); ?>

<?php
$pag = '';
if ( sq_option( 'bp_ajax_pagination', 1 ) == 0 ) {
	$pag = ' no-ajax';
}
?>

<?php if ( bp_get_current_member_type() ) : ?>
    <p class="current-member-type"><?php bp_current_member_type_message() ?></p>
<?php endif; ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) . '&per_page=' . sq_option( 'buddypress_perpage' ) ) ) : ?>

    <div id="pag-top" class="pagination<?php echo esc_attr( $pag ); ?>">

        <div class="pag-count" id="member-dir-count-top">

			<?php bp_members_pagination_count(); ?>

        </div>

        <div class="pagination-links" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

        </div>

    </div>

	<?php

	/**
	 * Fires before the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_members_list' ); ?>

    <div id="members-list" class="item-list search-list" aria-live="assertive" aria-relevant="all">

		<?php while ( bp_members() ) : bp_the_member(); ?>
            <div class="three columns">
                <div <?php bp_member_class( array( 'search-item' ) ); ?>>
                    <div class="avatar">
                        <a href="<?php bp_member_permalink(); ?>">
							<?php bp_member_avatar( 'type=full&width=94&height=94&class=' ); ?>
                        </a>
						<?php do_action( 'bp_members_inside_avatar' ); ?>
                    </div>

                    <h5 class="author"><a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a></h5>

					<?php /* do_action( 'bp_members_meta' );  */?>

                    <div class="search-body">
						<?php
						/**
						 * Fires inside the display of a directory member item.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_directory_members_item' ); ?>

						<?php
						/***
						 * If you want to show specific profile fields here you can,
						 * but it'll add an extra query for each member in the loop
						 * (only one regardless of the number of fields you show):
						 *
						 * bp_member_profile_data( 'field=the field name' );
						 */
						?>
                    </div>
                    <div class="bp-member-dir-buttons">
						<?php do_action( 'bp_directory_members_item_last' ); ?>

                        <div class="action"><?php

							/**
							 * Fires inside the members action HTML markup to display actions.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_directory_members_actions' ); ?></div>
                    </div>
                </div>
            </div>

		<?php endwhile; ?>

    </div>

	<?php

	/**
	 * Fires after the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

    <div id="pag-bottom" class="pagination<?php echo esc_attr( $pag ); ?>">

        <div class="pag-count" id="member-dir-count-bottom">

			<?php bp_members_pagination_count(); ?>

        </div>

        <div class="pagination-links" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

        </div>

    </div>

<?php else: ?>

    <div id="message" class="info">
        <p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
    </div>

<?php endif; ?>

<?php

/**
 * Fires after the display of the members loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_members_loop' ); 