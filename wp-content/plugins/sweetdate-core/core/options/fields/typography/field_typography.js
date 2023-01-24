jQuery(document).ready(function () {
     // Google fonts preview
    jQuery(".kleo-font, .kleo-font-style, .kleo-font-size").change(function() {
        if (jQuery(this).hasClass('kleo-font')) {
            var font = jQuery(this).val();
            var font_obj= jQuery(this);
        }
        else {
            var font = jQuery(this).siblings('.kleo-font').val();
            var font_obj= jQuery(this).siblings('.kleo-font');
        }
        
        if (jQuery(this).hasClass('kleo-font-size')) {
            var font_size = jQuery(this).val();
        }
        else {
            var font_size = jQuery(this).siblings('.kleo-font-size').val();
        }
        
        if (jQuery(this).hasClass('kleo-font-style')) {
            var font_style = jQuery(this).val();
        }
        else {
            var font_style = jQuery(this).siblings('.kleo-font-style').val();
        }
    
        var color = jQuery(this).parent().find(".popup-colorpicker").val();
    
        var googlefontlink = "http://fonts.googleapis.com/css?family=" + font;

        if (jQuery("link[href*='" + font + "']").length === 0 && !jQuery("option:selected",font_obj).hasClass('web-safe')){
            jQuery('link:last').after('<link href="' + googlefontlink + '" rel="stylesheet" type="text/css">');
        }
        
        var font_split = font.split(':');
        jQuery(this).siblings('.preview_zone').attr('style', 'font: '+ font_style + " " + font_size + " '" + font_split[0] +"'; color: "+color );
        
    });
    
    
});
