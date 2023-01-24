<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */
?>

<!-- Begin Article -->
<div class="row<?php if(get_cfield('centered_text') == 1) echo ' text-center'; ?>">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <div class="twelve columns">
        <div class="article-content">
          <div class="avatar">
              <?php echo get_avatar( get_the_author_meta( 'ID' ), 70 ); ?>   
          </div>
              <div class="status-info">
                  <small class="status-time"><?php esc_html_e( 'By ', 'sweetdate' ); echo get_the_author_meta('first_name') ." ". get_the_author_meta('last_name') .' '; _e( 'On ', 'sweetdate' ); the_time('F j, Y g:i a'); ?></small>
                  <?php the_content(); ?>
              </div>

              <?php edit_post_link( esc_html__( 'Edit', 'sweetdate' ), '<span class="edit-link">', '</span>' ); ?>
        </div><!--end article-content-->
      </div><!--end twelve-->
  </article>
</div><!--end row-->
<!-- End  Article -->

<hr>      
