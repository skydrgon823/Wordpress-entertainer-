<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */

get_header(); ?>

<?php if ( bp_is_user() ) : ?>

	<?php get_template_part( 'page-parts/buddypress-profile-header' ); ?>

<?php endif; ?>

<?php get_template_part( 'page-parts/buddypress-before-wrap' ); ?>

<?php /* Start the Loop */ ?>
<?php while ( have_posts() ) : the_post(); ?>

	<div class="row">
		<div class="twelve columns">
			<div class="article-content">
				<?php the_content(); ?>
			</div><!--end article-content-->
		</div><!--end twelve-->
	</div><!--end row-->
	<!-- End  Article -->
<?php endwhile; ?>

<?php get_template_part( 'page-parts/buddypress-after-wrap' );?>

<?php get_footer(); ?>