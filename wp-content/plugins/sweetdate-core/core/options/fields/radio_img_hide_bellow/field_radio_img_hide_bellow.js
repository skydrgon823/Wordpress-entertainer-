function kleo_radio_img_hide_bellow_select(relid, labelclass) {
    jQuery(this).prev('input[type="radio"]').prop('checked');
    jQuery('.squeen-radio-img-' + labelclass).removeClass('squeen-radio-img-selected');
    jQuery('label[for="' + relid + '"]').addClass('squeen-radio-img-selected');
}

/*global jQuery, document*/
jQuery(document).ready(function () {
    jQuery('.squeen-opts-radio_img-hide-below').each(function () {
        var amount = jQuery(this).data('amount');
        if (jQuery(this).is(":checked") && jQuery(this).attr('data-allow') === 'false') {
            jQuery(this).closest('tr').nextAll('tr').slice(0,amount).hide();
        }
    });

    jQuery('.squeen-opts-radio_img-hide-below').change(function () {
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
