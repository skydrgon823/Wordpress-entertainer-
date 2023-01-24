<?php
/**
 * The template for displaying Tag pages.
 *
 * Used to display archive-type pages for posts in a tag.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @since 1.0
 */

get_header(); ?>

<?php get_template_part('page-parts/general-before-wrap');?>
                
<?php if ( have_posts() ) : ?>

    <?php
    /* Start the Loop */
    while ( have_posts() ) : the_post();

        /* Include the post format-specific template for the content. If you want to
         * this in a child theme then include a file called called content-___.php
         * (where ___ is the post format) and that will be used instead.
         */
        get_template_part( 'content', get_post_format() );

    endwhile;

    kleo_pagination();
    ?>

<?php else : ?>
    <?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>

<?php get_template_part('page-parts/general-after-wrap');?>
               
<?php get_footer(); ?>