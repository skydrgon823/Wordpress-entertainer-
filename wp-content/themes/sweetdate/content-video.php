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

          <?php if(get_cfield('title_checkbox') != 1): ?>
              <?php if ( is_single() ) : ?>
              <h2 class="article-title">
                  <a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
              </h2>
              <?php else : ?>
              <h2 class="article-title">
                      <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'sweetdate' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
              </h2>
              <?php endif; // is_single() ?>
          <?php endif;?>

          <?php if(get_cfield('meta_checkbox') != 1): ?>
          <div class="article-meta clearfix">
            <ul class="link-list">
                <?php sweetdate_entry_meta(); ?>
            </ul>
          </div><!--end article-meta-->
          <?php endif;?>

      </div><!--end twelve-->

      <?php 
      $video = get_cfield('embed');

      if(!empty($video)) { ?>
      <div class="twelve columns">
        <div class="article-media clearfix">
         <?php echo wp_oembed_get($video); ?>
        </div><!--end article-media-->
      </div><!--end twelve-->
      <?php } ?>

      <div class="twelve columns">
        <div class="article-content">
      <?php if ( !is_single() ) : // Only display Excerpts for Search ?>

              <?php the_excerpt(); ?>

              <?php if (get_the_excerpt()): ?>
                  <p><a class="radius small button secondary" href="<?php the_permalink()?>"><?php esc_html_e("Continue reading", 'sweetdate');?></a></p>
              <?php endif; ?>

      <?php else : ?>

                  <?php the_content( esc_html__( 'Continue reading <span class="meta-nav">&rarr;</span>', 'sweetdate' ) ); ?>

      <?php endif; ?>

              <?php edit_post_link( esc_html__( 'Edit', 'sweetdate' ), '<span class="edit-link">', '</span>' ); ?>
        </div><!--end article-content-->
      </div><!--end twelve-->
    </article>
</div><!--end row-->
<!-- End  Article -->

<hr>      
