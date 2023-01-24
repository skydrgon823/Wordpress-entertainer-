<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */

get_header(); ?>

<?php
// 404 page filter
if (is_404())
{
  add_filter('kleo_main_section_class', 'kleo_custom1' );
  function kleo_custom1() { return 'text-center'; }
  add_filter('kleo_content_class', 'kleo_custom2' );
  function kleo_custom2() { return 'twelve'; } 
  remove_action('kleo_before_content', 'kleo_sidebar');
  remove_action('kleo_after_content', 'kleo_sidebar');
  remove_action('kleo_before_content', 'kleo_extra_sidebar');
  remove_action('kleo_after_content', 'kleo_extra_sidebar');
}
  
?>

<?php get_template_part('page-parts/general-before-wrap');?>


<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404_image.png' );?>"><br>
<h2 class="article-title"><?php esc_html_e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'sweetdate' ); ?></h2>
<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'sweetdate' ); ?></p>

<div class="six columns centered">
    <?php get_search_form(); ?>
</div>
<br><br><br>

<?php get_template_part('page-parts/general-after-wrap');?>
     
<?php get_footer(); ?>