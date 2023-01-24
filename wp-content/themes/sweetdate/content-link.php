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
<?php $link = get_cfield('link');?>
  <!-- Begin Article -->
  <div class="row<?php if(get_cfield('centered_text') == 1) echo ' text-center'; ?>">
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

 
          
        <div class="twelve columns">
            
            <h2 class="article-title">
                <a target="_blank" href="<?php echo esc_attr(get_the_content());?>" rel="bookmark"><?php the_title(); ?></a>
            </h2>
            
            <?php edit_post_link( esc_html__( 'Edit', 'sweetdate' ), '<span class="edit-link">', '</span>' ); ?>

            
            
        </div><!--end twelve-->

      </article>
  </div><!--end row-->
  <!-- End  Article -->

  <hr>      
