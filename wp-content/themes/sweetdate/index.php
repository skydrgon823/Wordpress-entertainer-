<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */

get_header(); ?>

<?php get_template_part('page-parts/general-before-wrap');?>

<?php if ( have_posts() ) : ?>

    <?php /* Start the Loop */ ?>
    <?php while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'content', get_post_format() ); ?>
    <?php endwhile; ?>

    <?php kleo_pagination(); ?>

<?php else : ?>

    <!-- Begin Article -->
    <div class="row">
        <div class="twelve columns">
            <h2 class="article-title"><?php esc_html_e( 'Nothing Found', 'sweetdate' ); ?></h2>
        </div><!--end twelve-->

        <div class="twelve columns">
            <div class="article-content">
                <p><?php esc_html_e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'sweetdate' ); ?></p>
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>
    <!-- End Article -->
    
<?php endif; ?>
        
<?php get_template_part('page-parts/general-after-wrap');?>

<?php get_footer(); ?>