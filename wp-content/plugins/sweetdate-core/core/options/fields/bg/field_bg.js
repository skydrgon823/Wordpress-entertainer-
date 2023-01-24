/*global jQuery, document*/
jQuery(document).ready(function () {
    /* Background field hide specific areas on change
     * 
     */
    jQuery(".type",".bg_container").change(function() {
        jQuery(".kleo-grouped", jQuery(this).parent(".bg_container")).hide();
        jQuery("."+jQuery(this).val(), jQuery(this).parent(".bg_container")).fadeIn('slow'); 

    });
    /* Background field hide specific areas on load
     * 
     */
    jQuery(".type",".bg_container").each(function( index ) {
      jQuery("."+jQuery(this).val(), jQuery(this).parent(".bg_container")).fadeIn('slow'); 
    });  
});
