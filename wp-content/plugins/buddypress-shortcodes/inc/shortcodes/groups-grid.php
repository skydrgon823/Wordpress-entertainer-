<?php

// [webim_groups_grid]
function webim_groups_grid($atts, $content = null) {
    $sliderrandomid = rand();
	extract( shortcode_atts( array(
                "title" => '',
				"type" => 'active',
				"count" => '' ,
				"col" => 6 // Maximum 12 
			), $atts ));
	ob_start();
	?> 
  <div class="container"> 
			  <?php if($title){?> 
				      <h3 class="section-title"><span><?php echo $title ?></span></h3>
		      <?php } ?>
             
                <?php if ( function_exists( 'bp_is_active' ) ) { ?>  
                  <?php
            
                    $args = array(
					 'type' => $type,
                     'per_page' => $count
                    ); ?>
                    
            <?php if ( bp_has_groups( $args ) ) : ?>
			<?php while ( bp_groups() ) : bp_the_group(); ?>
			<div class="col-md-<?php echo $col?> col-sm-<?php echo $col?>">
			<div class="bp-webim-members-loop">
				 <div class="item-avatar text-center">
							<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=full&height=200&width=200' ); ?></a>
						</div>
				 <div class="item-title text-center">
					
						<h5><a title="<?php bp_group_name(); ?>" href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></h5>
						<p><?php bp_group_type(); ?></p><p> <?php bp_group_member_count(); ?></p>
				</div>
			</div>
			</div>		
			<?php endwhile; ?>
	        <?php endif; ?>
            <?php } ?>     
  </div><!-- .container -->
  <div class="clearfix"></div>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("webim_groups_grid", "webim_groups_grid");