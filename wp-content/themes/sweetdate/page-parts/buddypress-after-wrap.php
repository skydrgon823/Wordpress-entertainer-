<?php
/**
 * After content wrap
 * Used in all templates
 */
?>

            </div><!--end content-->
  
            <?php /* Before content - action */ ?>
            <?php do_action('kleo_buddypress_after_content'); ?>

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