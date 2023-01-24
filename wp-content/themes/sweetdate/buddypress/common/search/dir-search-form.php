<?php
/**
 * Output the search form markup.
 *
 * @since 2.7.0
 */
?>

<div id="<?php echo esc_attr( bp_current_component() ); ?>-dir-search" class="dir-search" role="search">
	
	<form action="" method="get" id="search-<?php echo esc_attr( bp_current_component() ); ?>-form" class="custom row collapse">
	
		<label class="nine columns">
			<input type="text" name="<?php echo esc_attr( bp_core_get_component_search_query_arg() ); ?>" id="<?php bp_search_input_name(); ?>" placeholder="<?php bp_search_placeholder(); ?>"/>
		</label>
	
		<input class="three columns button small radius secondary postfix" type="submit" id="<?php echo esc_attr( bp_get_search_input_name() ); ?>_submit"
		       name="<?php bp_search_input_name(); ?>_submit" value="<?php esc_attr_e( 'Search', 'buddypress' ) ?>"/>

	</form>
	
</div><!-- #<?php echo esc_attr( bp_current_component() ); ?>-dir-search -->
