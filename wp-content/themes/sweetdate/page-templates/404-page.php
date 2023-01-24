<?php
/**
 * Template Name: 404 Page
 *
 * Description: Use this page template to show a 404 page
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */

get_header(); ?>

<?php
// 404 page filter
add_filter('kleo_main_section_class', 'kleo_custom1' );
function kleo_custom1() { return 'text-center'; }
add_filter('kleo_content_class', 'kleo_custom2' );
function kleo_custom2() { return 'twelve'; } 
remove_action('kleo_before_content', 'kleo_sidebar');
remove_action('kleo_after_content', 'kleo_sidebar');
remove_action('kleo_before_content', 'kleo_extra_sidebar');
remove_action('kleo_after_content', 'kleo_extra_sidebar');
?>

<?php get_template_part('page-parts/general-before-wrap');?>
<div class="twelve columns">
    <img src="<?php echo get_template_directory_uri();?>/assets/images/404_image.png"><br>
    <h2 class="article-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'sweetdate' ); ?></h2>

    <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'sweetdate' ); ?></p>
</div>
<div class="six columns centered">
    <?php get_search_form(); ?>
</div>
<br><br><br>

<div class="twelve columns">
<?php /* Start the Loop */ ?>
<?php while ( have_posts() ) : the_post(); ?>

        <?php get_template_part( 'content', 'page' ); ?>

<?php endwhile; ?>
</div><!--end twelve-->


<?php get_template_part('page-parts/general-after-wrap');?>
     
<?php get_footer(); ?>