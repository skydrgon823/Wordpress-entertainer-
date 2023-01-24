/**
 * Controls the behaviours of custom metabox fields.
 *
 * @author Andrew Norcross
 * @author Jared Atchison
 * @author Bill Erickson
 * @author Justin Sternberg
 * @see    https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

/*jslint browser: true, devel: true, indent: 4, maxerr: 50, sub: true */
/*global jQuery, tb_show, tb_remove */

/**
 * Custom jQuery for Custom Metaboxes and Fields
 */
jQuery(document).ready(function ($) {
	'use strict';

	var formfield;
        var formLabel;

	/**
	 * Initialize timepicker (this will be moved inline in a future release)
	 */
	$('.cmb_timepicker').each(function () {
		$('#' + jQuery(this).attr('id')).timePicker({
			startTime: "07:00",
			endTime: "22:00",
			show24Hours: false,
			separator: ':',
			step: 30
		});
	});

	/**
	 * Initialize jQuery UI datepicker (this will be moved inline in a future release)
	 */
	$('.cmb_datepicker').each(function () {
		$('#' + jQuery(this).attr('id')).datepicker();
		// $('#' + jQuery(this).attr('id')).datepicker({ dateFormat: 'yy-mm-dd' });
		// For more options see http://jqueryui.com/demos/datepicker/#option-dateFormat
	});
	// Wrap date picker in class to narrow the scope of jQuery UI CSS and prevent conflicts
	$("#ui-datepicker-div").wrap('<div class="cmb_element" />');

	/**
	 * Initialize color picker
	 */
	if (typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function') {
		$('input:text.cmb_colorpicker').wpColorPicker();
	} else {
		$('input:text.cmb_colorpicker').each(function (i) {
			$(this).after('<div id="picker-' + i + '" style="z-index: 1000; background: #EEE; border: 1px solid #CCC; position: absolute; display: block;"></div>');
			$('#picker-' + i).hide().farbtastic($(this));
		})
		.focus(function () {
			$(this).next().show();
		})
		.blur(function () {
			$(this).next().hide();
		});
	}

	/**
	 * File and image upload handling
	 */
	$('.cmb_upload_file').change(function () {
		formfield = $(this).attr('id');
		$('#' + formfield + '_id').val("");
	});

	$('body').on('click', '.cmb_upload_button', function (event) {
        /*
		var buttonLabel;
		formLabel = $(this).attr("data-label");
        formfield = $(this).attr("data-field");
		buttonLabel = 'Use as ' + $('label[for="' + formLabel + '"]').text();
		tb_show('', 'media-upload.php?post_id=' + $('#post_ID').val() + '&type=file&cmb_force_send=true&cmb_send_label=' + buttonLabel + '&TB_iframe=true');
		return false;
        */
        var formfield = $(this).attr("data-field");
        
        var activeFileUploadContext = jQuery(this).parent();
        event.preventDefault();

        // if its not null, its broking custom_file_frame's onselect "activeFileUploadContext"
        var custom_file_frame = null;

        // Create the media frame.
        custom_file_frame = wp.media.frames.customHeader = wp.media({
            // Set the title of the modal.
            title: "Choose file",

            // Customize the submit button.
            button: {
                // Set the text of the button.
                text: "Select"
            }
        });

        custom_file_frame.on( "select", function() {
            // Grab the selected attachment.
            var attachment = custom_file_frame.state().get("selection").first();

            // Update value of the targetfield input with the attachment url.
            //jQuery('.cmb_upload_file',activeFileUploadContext).attr('src', attachment.attributes.url);
            jQuery('.cmb_upload_file',activeFileUploadContext).val(attachment.attributes.url).trigger('change');
            
			var image = /(jpe?g|png|gif|ico)$/gi;
            var img_url = attachment.attributes.url;
            
			if (img_url.match(image)) {
                var uploadStatus = '<div class="img_status"><img src="' + attachment.attributes.url + '" alt="" /><a href="#" class="cmb_remove_file_button" rel="' + formfield + '">Remove Image</a></div>';
			} else {
				// No output preview if it's not an image
				// Standard generic output if it's not an image.
				html = '<a href="' + attachment.attributes.url + '" target="_blank" rel="external">View File</a>';
				var uploadStatus = '<div class="no_image"><span class="file_link">' + html + '</span>&nbsp;&nbsp;&nbsp;<a href="#" class="cmb_remove_file_button" rel="' + formfield + '">Remove</a></div>';
			}

			//$('#' + formfield).val(itemurl);
			//$('#' + formfield + '_id').val(itemid);

			$('#' + formfield).siblings('.cmb_media_status').slideDown().html(uploadStatus);
            
            
        });

        custom_file_frame.open();
       
	});

	$('body').on('click', '.cmb_remove_file_button', function () {
		formfield = $(this).attr('rel');
		$('input#' + formfield).val('');
		$('input#' + formfield + '_id').val('');
		$(this).parent().remove();
		return false;
	});
    
   
   
	/**
	 * Ajax oEmbed display
	 */

	// ajax on paste
	$('.cmb_oembed').bind('paste', function (e) {
		var pasteitem = $(this);
		// paste event is fired before the value is filled, so wait a bit
		setTimeout(function () {
			// fire our ajax function
			doCMBajax(pasteitem, 'paste');
		}, 100);
	}).blur(function () {
		// when leaving the input
		setTimeout(function () {
			// if it's been 2 seconds, hide our spinner
			$('.postbox table.cmb_metabox .cmb-spinner').hide();
		}, 2000);
	});

	// ajax when typing
	$('.cmb_metabox').on('keyup', '.cmb_oembed', function (event) {
		// fire our ajax function
		doCMBajax($(this), event);
	});

	// function for running our ajax
	function doCMBajax(obj, e) {
		// get typed value
		var oembed_url = obj.val();
		// only proceed if the field contains more than 6 characters
		if (oembed_url.length < 6)
			return;

		// only proceed if the user has pasted, pressed a number, letter, or whitelisted characters
		if (e === 'paste' || e.which <= 90 && e.which >= 48 || e.which >= 96 && e.which <= 111 || e.which == 8 || e.which == 9 || e.which == 187 || e.which == 190) {

			// get field id
			var field_id = obj.attr('id');
			// get our inputs context for pinpointing
			var context = obj.parents('.cmb_metabox tr td');
			// show our spinner
			$('.cmb-spinner', context).show();
			// clear out previous results
			$('.embed_wrap', context).html('');
			// and run our ajax function
			setTimeout(function () {
				// if they haven't typed in 500 ms
				if ($('.cmb_oembed:focus').val() == oembed_url) {
					$.ajax({
						type : 'post',
						dataType : 'json',
						url : window.ajaxurl,
						data : {
							'action': 'cmb_oembed_handler',
							'oembed_url': oembed_url,
							'field_id': field_id,
							'post_id': window.cmb_ajax_data.post_id,
							'cmb_ajax_nonce': window.cmb_ajax_data.ajax_nonce
						},
						success: function (response) {
							// if we have a response id
							if (typeof response.id !== 'undefined') {
								// hide our spinner
								$('.cmb-spinner', context).hide();
								// and populate our results from ajax response
								$('.embed_wrap', context).html(response.result);
							}
						}
					});
				}
			}, 500);
		}
	}      

    /* Tabs */
    
    if (jQuery("#tabContainer").length > 0)
    {
      // get tab container
      var container = document.getElementById("tabContainer");
      // set current tab
      var navitem = container.querySelector(".tabs ul li");
      //store which tab we are on
      var ident = navitem.id.split("_")[1];
      navitem.parentNode.setAttribute("data-current",ident);
      //set current tab with class of activetabheader
      $(navitem).addClass("tabActiveHeader");

      //hide two tab contents we don't need
      var pages = container.querySelectorAll(".tabpage");
      for (var i = 1; i < pages.length; i++) {
        pages[i].style.display="none";
      }

      //this adds click event to tabs
      var tabs = container.querySelectorAll(".tabs ul li");
      for (var i = 0; i < tabs.length; i++) {
        tabs[i].onclick=displayPage;
      }
    }

});

/* Tabs */
// on click of one of tabs
function displayPage() {
  var current = jQuery(this).parent().attr("data-current");
  //remove class of activetabheader and hide old contents
  jQuery("#tabHeader_" + current).removeClass("tabActiveHeader");
  jQuery("#tabpage_" + current).hide();

  var ident = this.id.split("_")[1];
  //add class of activetabheader to new active tab and show contents
  jQuery(this).addClass("tabActiveHeader");
  jQuery("#tabpage_" + ident).show();
  jQuery(this).parent().attr("data-current",ident);
}