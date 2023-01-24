<?php
/**
 * Template Name: 3 Columns - Left and Right sidebars
 *
 * Description: Show a page template with left and right sidebars
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */

get_header(); ?>

<!-- MAIN SECTION
================================================ -->
<section>
    <div id="main">
        
        <?php
        /**
         * Before main part - action
         */
        do_action('kleo_before_main');
        ?>
        
        <div class="row">
            
			<!-- SIDEBAR SECTION
			================================================ -->
			<aside class="three columns">

				<div class="widgets-container sidebar_location">
					<?php
					if ( function_exists( 'generated_dynamic_sidebar' ) ) {
						generated_dynamic_sidebar();
					} else {
						dynamic_sidebar( 'sidebar-1' );
					}
					?>
				</div>

			</aside> <!--end four columns-->
			<!--END SIDEBAR SECTION-->
			
            <!--begin content-->
            <div id="main-content" class="six columns">
                <?php /* Start the Loop */ ?>
                <?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<!-- Begin Comments -->
					<?php comments_template( '', true ); ?>
					<!-- End Comments -->

                <?php endwhile; ?>

            </div><!--end content-->
			
			<!-- SIDEBAR SECTION
			================================================ -->
			<aside class="three columns">

				<div class="widgets-container sidebar_location">
					<?php
					if ( function_exists( 'generated_dynamic_sidebar' ) ) {
						generated_dynamic_sidebar( 'extra' );
					} else {
						dynamic_sidebar( 'extra' );
					}
					?>
				</div>

			</aside> <!--end four columns-->
			<!--END SIDEBAR SECTION-->
			
        </div><!--end row-->
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