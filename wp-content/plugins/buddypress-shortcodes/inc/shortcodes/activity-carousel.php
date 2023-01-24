<?php

// [webim_activity_carousel]
function webim_activity_carousel($atts, $content = null) {
    $sliderrandomid = rand();
	extract( shortcode_atts( array(
                'color' => '#000',
				"count" => 12
			), $atts ));
	ob_start();
	?>
	<script>
	jQuery(document).ready(function($) {
	$("#activity-carousel-<?php echo $sliderrandomid ?>").owlCarousel({
		singleItem:true,
		lazyLoad : true,
		autoPlay: 3000, //Set AutoPlay to 3 seconds
       // Navigation
		navigation:false,
		// Responsive 
		responsive: true,
		responsiveRefreshRate : 200,
		responsiveBaseWidth: window,
		autoHeight : true
		});
	});
	</script>
	<style>
	.activity-carousel .activity-header,.activity-carousel p,.activity-carousel .activity-header a {
		color: <?php echo $color; ?>;
	}
	</style>	
	<?php if ( class_exists( 'buddypress' ) ) { ?>  
                  <?php
            
                    $args = array(
					 'per_page' => $count
                    ); ?>
            
            <?php if ( bp_has_activities( $args ) ): ?>
			<div id="activity-carousel-<?php echo $sliderrandomid ?>" class="activity-carousel owl-carousel">
			<?php while ( bp_activities() ) : bp_the_activity(); ?>
				<div class="activity-content item">
				        <div class="container center">
						<a title="<?php _e( 'View Profile','webim' ); ?>" href="<?php bp_activity_user_link(); ?>">
							<?php bp_activity_avatar(); ?>
						</a>
						</div>
						<div class="container center">
							<div class="activity-header">
							<?php bp_activity_action(); ?>
							</div>
						<?php if ( bp_activity_has_content() ) : ?>
						<?php bp_activity_content_body(); ?>
						<?php endif; ?>
						<?php do_action( 'bp_activity_entry_content' ); ?>
						</div>
                </div><!-- .activity-content-->						
			 <?php endwhile; ?>
			</div><!-- .owl-carousel -->
	        <?php endif; ?>
			 <?php } ?> 
	        
	<div class="clearfix"></div>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode("webim_activity_carousel", "webim_activity_carousel");