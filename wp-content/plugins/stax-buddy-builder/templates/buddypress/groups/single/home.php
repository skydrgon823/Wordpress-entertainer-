<?php
/**
 * BuddyPress - Groups Home
 *
 * @since 3.0.0
 * @version 3.0.0
 */

$group_profile = bpb_get_shortcode_str( 'group-profile' );
$render        = bpb_is_template_populated( 'group-profile' );

if ( bp_has_groups() ) :
	while ( bp_groups() ) :
		bp_the_group();
		?>

		<?php bp_nouveau_group_hook( 'before', 'home_content' ); ?>

		<?php if ( $render ) : ?>
            <div id="item-header" role="complementary" data-bp-item-id="<?php bp_group_id(); ?>"
                 data-bp-item-component="groups" class="groups-header">

                <?php echo do_shortcode( $group_profile ); ?>
            </div>
        <?php else : ?>
            <div id="item-header" role="complementary" data-bp-item-id="<?php bp_group_id(); ?>"
                 data-bp-item-component="groups" class="groups-header single-headers">

                <?php bp_nouveau_group_header_template_part(); ?>

            </div><!-- #item-header -->

            <div class="bp-wrap">

                <?php if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>

                    <?php bp_get_template_part( 'groups/single/parts/item-nav' ); ?>

                <?php endif; ?>

                <div id="item-body" class="item-body">

                    <?php bp_nouveau_group_template_part(); ?>

                </div><!-- #item-body -->

            </div><!-- // .bp-wrap -->
        <?php endif; ?>

		<?php bp_nouveau_group_hook( 'after', 'home_content' ); ?>

	<?php endwhile; ?>

	<?php
endif;
