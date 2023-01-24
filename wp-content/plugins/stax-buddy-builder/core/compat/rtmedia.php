<?php
defined( 'ABSPATH' ) || die();

add_filter(
	'buddy_builder/tpl/profile-member/content/render',
	function () {
		if ( bp_current_component() === 'media' ) {
			?>
			<?php do_action( 'bp_before_member_body' ); ?>
			<?php do_action( 'bp_before_member_media' ); ?>
		<nav class="<?php bp_nouveau_single_item_subnav_classes(); ?>" id="subnav" role="navigation"
			 aria-label="<?php esc_attr_e( 'rtMedia menu', 'buddypress-media' ); ?>">
			<ul class="subnav">

				<?php rtmedia_sub_nav(); ?>

				<?php do_action( 'rtmedia_sub_nav' ); ?>

			</ul>
		</nav><!-- .item-list-tabs#subnav -->

			<?php
			rtmedia_load_template();

			do_action( 'bp_after_member_media' );
			do_action( 'bp_after_member_body' );
			?>
			<?php
			return false;
		}

		return true;
	}
);


add_filter(
	'buddy_builder/tpl/profile-group/content/render',
	function () {

		if ( bp_current_component() === 'groups' && bp_current_action() === 'media' ) {
			?>
			<?php
			do_action( 'bp_before_group_body' );
			do_action( 'bp_before_group_media' );

			$bp_is_group_home = bp_is_group_home();
			if ( $bp_is_group_home && ! bp_current_user_can( 'groups_access_group' ) ) {
				/**
				 * Fires before the display of the group status message.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_before_group_status_message' );
				?>

			<div id="message" class="info">
				<p><?php bp_group_status_message(); ?></p>
			</div>

				<?php

				/**
				 * Fires after the display of the group status message.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_after_group_status_message' );
			} else {
				?>
			<nav class="<?php bp_nouveau_single_item_subnav_classes(); ?>" id="subnav" role="navigation"
				 aria-label="<?php esc_attr_e( 'rtMedia menu', 'buddypress-media' ); ?>">
				<ul class="subnav">
					<?php rtmedia_sub_nav(); ?>
					<?php do_action( 'rtmedia_sub_nav' ); ?>
				</ul>
			</nav><!-- .item-list-tabs#subnav -->
				<?php

				rtmedia_load_template();
			}

			do_action( 'bp_after_group_media' );
			do_action( 'bp_after_group_body' );
			?>
			<?php
			return false;
		}

		return true;
	}
);
