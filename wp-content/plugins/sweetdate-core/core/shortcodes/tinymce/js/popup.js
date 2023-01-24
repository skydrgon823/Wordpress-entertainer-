
// start the popup specific scripts
// safe to use $
jQuery(document).ready(function($) {
    var kleos = {
    	loadVals: function()
    	{
    		var shortcode = $('#_kleo_shortcode').text(),
    			uShortcode = shortcode;
    		
    		// fill in the gaps eg {{param}}
    		$('.kleo-input').each(function() {
    			var input = $(this);
					if (input.attr('id') !== undefined) {
							id = input.attr('id');
					} else {
							id = input.attr('data-id');
					}
					var id = id.replace('kleo_', ''),		// gets rid of the kleo_ prefix
					re = new RegExp("{{"+id+"}}","g");

					if (input.attr('type') == 'checkbox' || input.attr('type') == 'radio')
					{
							if (input.is(":checked"))
							{
									uShortcode = uShortcode.replace(re, input.val());
							}
							else
							{
									uShortcode = uShortcode.replace(re, 0);
							}

					}
					else
					{
							uShortcode = uShortcode.replace(re, input.val());
					}       
                               
    		});
    		
    		// adds the filled-in shortcode as hidden input
    		$('#_kleo_ushortcode').remove();
    		$('#kleo-sc-form-table').prepend('<div id="_kleo_ushortcode" class="hidden">' + uShortcode + '</div>');
    	},
    	cLoadVals: function()
    	{
    		var shortcode = $('#_kleo_cshortcode').text(),
    			pShortcode = '';
    			shortcodes = '';
    		
    		// fill in the gaps eg {{param}}
    		$('.child-clone-row').each(function() {
    			var row = $(this),
    				rShortcode = shortcode;
    			
    			$('.kleo-cinput', this).each(function() {
    				var input = $(this);
						if (input.attr('id') !== undefined) {
							id = input.attr('id');
						} else {
							id = input.attr('data-id');
						}
						var id = id.replace('kleo_', '')		// gets rid of the kleo_ prefix
						re = new RegExp("{{"+id+"}}","g");

						if (input.attr('type') == 'checkbox' || input.attr('type') == 'radio')
						{
								if (input.is(":checked"))
								{
									rShortcode = rShortcode.replace(re, input.val());
								}
								else
								{
									rShortcode = rShortcode.replace(re, 0);
								}

						}
						else
						{
							rShortcode = rShortcode.replace(re, input.val());
						}      
    			});
    	
    			shortcodes = shortcodes + rShortcode + "\n";
    		});
    		
    		// adds the filled-in shortcode as hidden input
    		$('#_kleo_cshortcodes').remove();
    		$('.child-clone-rows').prepend('<div id="_kleo_cshortcodes" class="hidden">' + shortcodes + '</div>');
    		
    		// add to parent shortcode
    		this.loadVals();
    		pShortcode = $('#_kleo_ushortcode').text().replace('{{child_shortcode}}', shortcodes);
    		
    		// add updated parent shortcode
    		$('#_kleo_ushortcode').remove();
    		$('#kleo-sc-form-table').prepend('<div id="_kleo_ushortcode" class="hidden">' + pShortcode + '</div>');
    	},
    	children: function()
    	{
    		// assign the cloning plugin
    		$('.child-clone-rows').appendo({
    			subSelect: '> div.child-clone-row:last-child',
    			allowDelete: false,
    			focusFirst: false
    		});
    		
    		// remove button
    		$('.child-clone-row-remove').on('click', function() {
    			var	btn = $(this),
    				row = btn.parent();
    			
    			if( $('.child-clone-row').size() > 1 )
    			{
    				row.remove();
    			}
    			else
    			{
    				alert('You need a minimum of one row');
    			}
    			
    			return false;
    		});
    		
    		// assign jUI sortable
    		$( ".child-clone-rows" ).sortable({
				placeholder: "sortable-placeholder",
				items: '.child-clone-row'
				
			});
    	},
    	resizeTB: function()
    	{
			var	ajaxCont = $('#TB_ajaxContent'),
				tbWindow = $('#TB_window'),
				kleoPopup = $('#kleo-popup');

            tbWindow.css({
                height: kleoPopup.outerHeight() + 50,
                width: kleoPopup.outerWidth(),
                marginLeft: -(kleoPopup.outerWidth()/2)
            });

			ajaxCont.css({
				paddingTop: 0,
				paddingLeft: 0,
				paddingRight: 0,
				height: (tbWindow.outerHeight()-47),
				overflow: 'auto', // IMPORTANT
				width: kleoPopup.outerWidth()
			});
			
			$('#kleo-popup').addClass('no_preview');
    	},
    	load: function()
    	{
    		var	kleos = this,
    			popup = $('#kleo-popup'),
    			form = $('#kleo-sc-form', popup),
    			shortcode = $('#_kleo_shortcode', form).text(),
    			popupType = $('#_kleo_popup', form).text(),
    			uShortcode = '';
    		
    		// resize TB
    		kleos.resizeTB();
    		$(window).resize(function() { kleos.resizeTB() });
    		
    		// initialise
    		kleos.loadVals();
    		kleos.children();
    		kleos.cLoadVals();
    		
    		// update on children value change
    		$('.kleo-cinput', form).on('change', function() {
    			kleos.cLoadVals();
    		});
    		
    		// update on value change
    		$('.kleo-input', form).change(function() {
    			kleos.loadVals();
    		});
    		
    		// when insert is clicked
    		$('.kleo-insert', form).click(function() {    		 			
    			if(window.tinyMCE)
				{
					kleos.cLoadVals();
					if ( parseInt(window.tinyMCE.majorVersion) > 3) {
						tinymce.get("content").execCommand('mceInsertContent', false, $('#_kleo_ushortcode', form).html());
					}
					else {
						window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, $('#_kleo_ushortcode', form).html());
					}
					
					tb_remove();
				}
    		});
    	}
	}
    
    // run
    $('#kleo-popup').livequery( function() { kleos.load(); } );
   
   
    $("body").on('click', '.sc_upload_image_button', function( event ) {
                var activeFileUploadContext = $(this).parent().parent();
                var relid = jQuery(this).attr('rel-id');
                event.preventDefault();

                // If the media frame already exists, reopen it.
                /*if ( typeof(custom_file_frame)!=="undefined" ) {
                    custom_file_frame.open();
                    return;
                }*/

                // if its not null, its broking custom_file_frame's onselect "activeFileUploadContext"
                custom_file_frame = null;

                // Create the media frame.
                custom_file_frame = wp.media.frames.customHeader = wp.media({
                    // Set the title of the modal.
                    title: $(this).data("choose"),

                    // Tell the modal to show only images. Ignore if want ALL
                    library: {
                        type: 'image'
                    },
                    // Customize the submit button.
                    button: {
                        // Set the text of the button.
                        text: $(this).data("update")
                    }
                });

                custom_file_frame.on( "select", function() {
                    // Grab the selected attachment.
                    var attachment = custom_file_frame.state().get("selection").first();

                    // Update value of the targetfield input with the attachment url.
                    //$('.squeen-opts-screenshot',activeFileUploadContext).attr('src', attachment.attributes.url);
                    $('input[type=text]', activeFileUploadContext ).val(attachment.attributes.url).trigger('change');

                    //jQuery('.squeen-opts-upload',activeFileUploadContext).hide();
                    //$('.squeen-opts-screenshot',activeFileUploadContext).show();
                    //$('.squeen-opts-upload-remove',activeFileUploadContext).show();
            });

            custom_file_frame.open();
        });

});