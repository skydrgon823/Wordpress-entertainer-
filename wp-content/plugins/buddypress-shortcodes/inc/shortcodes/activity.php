<?php

// [webim_buddypress_activity]
function webim_buddypress_activity($atts, $content = null) {
	extract( shortcode_atts( array(
                "title" => '',
				"count" => 12
			), $atts ));
	ob_start();
	?>
<div class="container"> 
			  <?php if($title){?> 
				      <h3 class="section-title"><span><?php echo $title ?></span></h3>
		      <?php } ?>
			    <div id="buddypress">
                <div class="activity" role="main">
						<ul id="activity-stream" class="activity-list item-list">
						
                <?php if ( class_exists( 'buddypress' ) ) { ?>  
                  <?php
                    $args = array(
					 'per_page' => $count
                    ); ?>
					<?php if ( bp_has_activities( $args ) ): ?>
						<?php while ( bp_activities() ) : bp_the_activity(); ?>
						<?php bp_get_template_part( 'activity/entry' ); ?>
						<?php endwhile; ?>
					<?php endif; ?>
					</ul>
						</div>
						</div>
			 <?php } ?> 
</div><!-- .container -->
<div class="clearfix"></div>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("webim_buddypress_activity", "webim_buddypress_activity");