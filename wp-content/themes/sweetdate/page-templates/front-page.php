<?php
/**
 * Template Name: Front Page(DEPRECATED)
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page.
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */

get_header();?>

<?php /* Start the Loop */ ?>
<?php while ( have_posts() ) : the_post(); ?>

        <?php the_content(); ?>

<?php endwhile; ?>

<?php get_footer(); ?>