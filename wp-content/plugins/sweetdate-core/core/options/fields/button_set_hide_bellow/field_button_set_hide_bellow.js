/*global jQuery, document*/
jQuery(document).ready(function () {
    jQuery('.buttonset_hide').buttonset();
    
    
    jQuery('.squeen-opts-buttonset-hide-below').each(function () {

        if (jQuery(this).is(':checked') && jQuery(this).attr('data-allow') === 'false') {
            jQuery(this).closest('tr').nextAll('tr').slice(0,jQuery(this).data('amount')).hide();
        }
    });

    jQuery('.squeen-opts-buttonset-hide-below').change(function () {

        var amount = jQuery(this).data('amount');
				
        if (jQuery(this).attr('data-allow') === 'false') {
            if (jQuery(this).closest('tr').next('tr').is(':visible')) {
            		jQuery(this).closest('tr').nextAll('tr').slice(0,amount).fadeOut('slow');
                //jQuery(this).closest('tr').next('tr').fadeOut('slow');
            }
        } else {
            if (jQuery(this).closest('tr').next('tr').is(':hidden')) {
            		jQuery(this).closest('tr').nextAll('tr').slice(0,amount).fadeIn('slow');
                //jQuery(this).closest('tr').next('tr').fadeIn('slow');
            }
        }
    });
    
    
    
    
});
