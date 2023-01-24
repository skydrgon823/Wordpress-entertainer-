<?php
/**
 * Template Name: Left Sidebar Page Template
 *
 * Description: Show a page template with left sidebar
 *
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
            
					<?php /* Sidebar */ ?>
					<?php add_filter('kleo_sidebar_class', function() { return "four"; } ); ?>
					<?php get_sidebar(); ?>
            
            <!--begin content-->
            <div id="main-content" class="eight columns">


                <?php /* Start the Loop */ ?>
                <?php while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'content', 'page' ); ?>
                
                        <!-- Begin Comments -->
                        <?php comments_template( '', true ); ?>
                        <!-- End Comments -->

                <?php endwhile; ?>

            </div><!--end content-->

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