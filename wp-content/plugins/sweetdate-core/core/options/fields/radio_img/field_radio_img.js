/*global jQuery*/
/*
 *
 * Kleo_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function kleo_radio_img_select(relid, labelclass) {
    jQuery(this).prev('input[type="radio"]').prop('checked');
    jQuery('.squeen-radio-img-' + labelclass).removeClass('squeen-radio-img-selected');
    jQuery('label[for="' + relid + '"]').addClass('squeen-radio-img-selected');
}
