<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'the_content', 'buddyboss_global_search_search_page_content', 9 );

function buddyboss_global_search_search_page_content( $content ){
    /**
     * Reportedly, on some installations, the remove_filter call below, doesn't work and this filter is called over and over again.
     * Possibly due to some other plugin/theme.
     * 
     * Lets add another precautionary measure, a global flag.
     * @since 1.1.3
     */
    global $bpgs_main_content_filter_has_run;
    
	if( !is_admin() && is_search() && 'yes' != $bpgs_main_content_filter_has_run ){
			remove_filter( 'the_content', 'buddyboss_global_search_search_page_content', 9 );
            $bpgs_main_content_filter_has_run = 'yes';
			//setup search resutls and all..
			buddyboss_global_search()->search->prepare_search_page();
			$content .= buddyboss_global_search_buffer_template_part( 'results-page', '', false );
	}
	
	return $content;
}

function buddyboss_global_search_load_template( $template, $variation=false ){
	$file = $template;
	
	if( $variation ){
		$file .= '-' . $variation;
	}
	$file .= '.php';
	
	$file_found = false;
	//first try to load template-variation.php
	if( file_exists(STYLESHEETPATH.'/buddypress-global-search/'.$file ) ){
        include (STYLESHEETPATH.'/buddypress-global-search/'.$file);
		$file_found = true;
	} else if(file_exists(TEMPLATEPATH.'/buddypress-global-search/'.$file)){
        include (TEMPLATEPATH.'/buddypress-global-search/'.$file);
		$file_found = true;
	} else if(file_exists(BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DIR.'templates/'.$file)){
        include (BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DIR.'templates/'.$file);
		$file_found = true;
	}
	
	if( !$file_found && $variation != '' ){
		//then try to load template.php
		$file = $template . '.php';
		if( file_exists(STYLESHEETPATH.'/buddypress-global-search/'.$file ) ){
			include (STYLESHEETPATH.'/buddypress-global-search/'.$file);
		} else if(file_exists(TEMPLATEPATH.'/buddypress-global-search/'.$file)){
			include (TEMPLATEPATH.'/buddypress-global-search/'.$file);
		} else if(file_exists(BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DIR.'templates/'.$file)){
			include (BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DIR.'templates/'.$file);
		}
	}
}

function buddyboss_global_search_buffer_template_part( $template, $variation='', $echo=true ){
	ob_start();
	
	buddyboss_global_search_load_template( $template, $variation );
	// Get the output buffer contents
	$output = ob_get_clean();

	// Echo or return the output buffer contents
	if ( true === $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

function buddyboss_global_search_filters(){
	buddyboss_global_search()->search->print_tabs();
}

function buddyboss_global_search_results(){
	buddyboss_global_search()->search->print_results();
}