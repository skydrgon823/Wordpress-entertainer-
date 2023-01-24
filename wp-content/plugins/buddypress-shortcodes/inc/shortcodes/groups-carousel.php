<?php

// [webim_groups_carousel]
function webim_groups_carousel($atts, $content = null) {
	$sliderrandomid = rand();
	extract( shortcode_atts( array(
        "title" => '',
		'visible' => '3',
		'type' => 'active',
		'count' => 10
			), $atts ));
	ob_start();
	?> 
	<script>
	jQuery(document).ready(function($) {
		$("#buddypress-<?php echo $sliderrandomid ?>").owlCarousel({
		items:<?php echo $visible ?>,
		itemsDesktop : [1200,<?php echo $visible ?>],
		itemsDesktopSmall : [1000,4],
		itemsTablet       : [768,3],
		itemsMobile : [400,2],
		lazyLoad : true,
     // Navigation
    navigation:true,
    navigationText: [
     "<i class='glyphicon glyphicon-chevron-left'></i>",
      "<i class='glyphicon glyphicon-chevron-right'></i>"
      ],
    // Responsive 
    responsive: true,
    responsiveRefreshRate : 200,
    responsiveBaseWidth: window,
		/*autoHeight : true,*/
	});
	});
	</script>
  <div class="container"> 
			 <?php if($title){?> 
				      <h3 class="section-title"><span><?php echo $title ?></span></h3>
		      <?php } ?>

           <div id="buddypress-<?php echo $sliderrandomid ?>" class="owl-carousel">
                  
                <?php if ( function_exists( 'bp_is_active' ) ) { ?>  
                  <?php
            
                    $args = array(
						'type' => $type,
                        'per_page' => $count
                    ); ?>
                    
            <?php if ( bp_has_groups( $args ) ) : ?>
			<?php while ( bp_groups() ) : bp_the_group(); ?>
			<div class="bp-webim-members-loop">
				 <div class="item-avatar text-center">
							<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=full&height=200&width=200' ); ?></a>
						</div>
				 <div class="item-title text-center">
						<h6><a title="<?php bp_group_name(); ?>" href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></h6>
						<p><?php bp_group_type(); ?></p><p> <?php bp_group_member_count(); ?></p>
					</div>
					</div>
			<?php endwhile; ?>
	        <?php endif; ?>
            <?php } ?>     
                    
              </div><!-- .owl-carousel -->
  </div><!-- .container -->
  <div class="clearfix"></div>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("webim_groups_carousel", "webim_groups_carousel");