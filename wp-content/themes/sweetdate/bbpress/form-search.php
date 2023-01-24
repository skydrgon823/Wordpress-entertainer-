<?php

/**
 * Search 
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<form role="search" method="get" id="bbp-search-form" action="<?php bbp_search_url(); ?>">
	<div class="row collapse" >
		<!--<label class="screen-reader-text hidden" for="bbp_search"><?php _e( 'Search for:', 'bbpress' ); ?></label>-->
		<input type="hidden" name="action" value="bbp-search-request" />
        <div class="nine columns">
            <input tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" name="bbp_search" id="bbp_search" />
        </div>
        <div class="three columns">
            <input tabindex="<?php bbp_tab_index(); ?>" class="button secondary radius expand postfix" type="submit" id="bbp_search_submit" value="<?php esc_attr_e( 'Search', 'bbpress' ); ?>" />
        </div>
    </div>
</form>
