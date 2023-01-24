/*global jQuery, document, kleo_upload, formfield:true, preview:true, tb_show, window, imgurl:true, tb_remove, $relid:true*/
/*
This is the uploader for wordpress before version 3.5
*/
jQuery(document).ready(function () {
    "use strict";

    /*
     *
     * Kleo_Options_upload function
     * Adds media upload functionality to the page
     *
     */

    var header_clicked = false;
    var formfield;
    var preview;
    var $relid;
    var imgurl;

    jQuery("img[src='']").attr("src", kleo_upload.url);

    jQuery('.squeen-opts-upload').click(function () {
        header_clicked = true;
        formfield = jQuery(this).attr('rel-id');
        preview = jQuery(this).siblings('img');
        tb_show('', 'media-upload.php?type=image&amp;post_id=0&amp;TB_iframe=true');
        return false;
    });

    jQuery('.squeen-opts-upload-remove').click(function () {
        $relid = jQuery(this).attr('rel-id');
        jQuery('#' + $relid).val('');
        jQuery(this).siblings('.squeen-opts-upload').fadeIn('slow');
        jQuery(this).siblings('img').fadeOut('slow', function () { jQuery(this).attr("src", kleo_upload.url); });
        jQuery(this).fadeOut('slow');
    });

    // Store original function
    window.original_send_to_editor = window.send_to_editor;

    window.send_to_editor = function (html) {
        if (header_clicked) {
            imgurl = jQuery('img', html).attr('src');
            jQuery('#' + formfield).val(imgurl).trigger('change');
            jQuery('#' + formfield).siblings('img').fadeIn('slow');
            //jQuery('#' + formfield).next().next().fadeOut('slow');
            jQuery('#' + formfield).siblings('.squeen-opts-upload-remove').fadeIn('slow');
            jQuery(preview).attr('src', imgurl);
            tb_remove();
            header_clicked = false;
        } else {
            window.original_send_to_editor(html);
        }
    }
});
