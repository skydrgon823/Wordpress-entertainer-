<?php
/**
 * Template Name: Full width - For Builder
 *
 * Description: Use this if you want to build your page using
 * Elementor or other page builder
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 3.0
 */

get_header(); ?>

<!-- MAIN SECTION
================================================ -->
<section>
	<div id="main" class="full-builder">
		
		<?php
		/**
		 * Before main part - action
		 */
		do_action('kleo_before_main');
		?>

			<div id="main-content">
				
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					
					<?php get_template_part( 'content', 'page' ); ?>
				
				<?php endwhile; ?>
			</div><!--end twelve-->
		
	</div><!--end main-->
	
	<?php
	/**
	 * After main part - action
	 */
	do_action('kleo_after_main');
	?>

</section>
<!--END MAIN SECTION-->

<?php get_footer(); ?>
