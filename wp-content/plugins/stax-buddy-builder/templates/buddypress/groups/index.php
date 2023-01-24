<?php
/**
 * BP Nouveau - Groups Directory
 *
 * @since 3.0.0
 * @version 3.0.0
 */

$directory = bpb_get_shortcode_str( 'groups-directory' );
$render    = bpb_is_template_populated( 'groups-directory' );

?>

<?php bp_nouveau_before_groups_directory_content(); ?>

<?php bp_nouveau_template_notices(); ?>

<?php if ( $render ) : ?>
	<?php echo do_shortcode( $directory ); ?>
	<?php bp_nouveau_after_groups_directory_content(); ?>
<?php else : ?>

	<?php if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>

		<?php bp_get_template_part( 'common/nav/directory-nav' ); ?>

	<?php endif; ?>

	<div class="screen-content">

		<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>

		<div id="groups-dir-list" class="groups dir-list" data-bp-list="groups">
			<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-groups-loading' ); ?></div>
		</div>

		<?php bp_nouveau_after_groups_directory_content(); ?>
	</div>
<?php endif; ?>
