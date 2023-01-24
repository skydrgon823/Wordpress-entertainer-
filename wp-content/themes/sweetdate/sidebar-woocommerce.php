<?php
/**
 * The sidebar containing the shop widget
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.3
 */
?>
<!-- SIDEBAR SECTION
================================================ -->
<?php
$sidebar_class = 'four';

/* Calculate general sidebar class */
$woo_layout = sq_option('woo_sidebar', 'right');
$six_templates = array('3ll', '3rr', '3lr');

if ($woo_layout == 'right' || $woo_layout == 'left' ) {
	$sidebar_class = 'four'; 
}
elseif (in_array($woo_layout, $six_templates)) {
	$sidebar_class = 'three';
}
?>
<aside class="<?php echo apply_filters( 'kleo_woo_sidebar_class', $sidebar_class );?> columns">

    <div class="widgets-container sidebar_location">
        <?php
        if ( function_exists( 'generated_dynamic_sidebar' ) ) {
	        generated_dynamic_sidebar( 'shop-1' );
        } else {
	        dynamic_sidebar( 'shop-1' );
        }
        ?>
    </div>
    
</aside> <!--end four columns-->
<!--END SIDEBAR SECTION-->