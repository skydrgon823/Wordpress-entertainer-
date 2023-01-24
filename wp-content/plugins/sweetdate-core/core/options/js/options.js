/*global $, jQuery, document, tabid:true, kleo_opts, confirm, relid:true*/

jQuery(document).ready(function () {

	if (jQuery('#last_tab').val() === '') {
		jQuery('.squeen-opts-group-tab:first').slideDown('fast');
		jQuery('#squeen-opts-group-menu li:first').addClass('active');
	} else {
		tabid = jQuery('#last_tab').val();
		jQuery('#' + tabid + '_section_group').slideDown('fast');
		jQuery('#' + tabid + '_section_group_li').addClass('active');
	}

	jQuery('input[name="' + kleo_opts.opt_name + '[defaults]"]').click(function () {
		if (!confirm(kleo_opts.reset_confirm)) {
			return false;
		}
	});

	jQuery('.squeen-opts-group-tab-link-a').click(function () {
		relid = jQuery(this).attr('data-rel');

		jQuery('#last_tab').val(relid);

		jQuery('.squeen-opts-group-tab').each(function () {
			if (jQuery(this).attr('id') === relid + '_section_group') {
				jQuery(this).delay(400).fadeIn(1200);
			} else {
				jQuery(this).fadeOut('fast');
			}
		});

		jQuery('.squeen-opts-group-tab-link-li').each(function () {
			if (jQuery(this).attr('id') !== relid + '_section_group_li' && jQuery(this).hasClass('active')) {
				jQuery(this).removeClass('active');
			}
			if (jQuery(this).attr('id') === relid + '_section_group_li') {
				jQuery(this).addClass('active');
			}
		});
	});

	if (jQuery('#squeen-opts-save').is(':visible')) {
		jQuery('#squeen-opts-save').delay(4000).slideUp('slow');
	}

	if (jQuery('#squeen-opts-imported').is(':visible')) {
		jQuery('#squeen-opts-imported').delay(4000).slideUp('slow');
	}

	jQuery('#squeen-opts-form-wrapper').on('change', ':input', function () {
		if (this.id === 'google_webfonts' && this.value === '') return;
		jQuery('#squeen-opts-save-warn').slideDown('slow');
	});

	jQuery('#squeen-opts-import-code-button').click(function () {
		if (jQuery('#squeen-opts-import-link-wrapper').is(':visible')) {
			jQuery('#squeen-opts-import-link-wrapper').fadeOut('fast');
			jQuery('#import-link-value').val('');
		}
		jQuery('#squeen-opts-import-code-wrapper').fadeIn('slow');
	});

	jQuery('#squeen-opts-import-link-button').click(function () {
		if (jQuery('#squeen-opts-import-code-wrapper').is(':visible')) {
			jQuery('#squeen-opts-import-code-wrapper').fadeOut('fast');
			jQuery('#import-code-value').val('');
		}
		jQuery('#squeen-opts-import-link-wrapper').fadeIn('slow');
	});

	jQuery('#squeen-opts-export-code-copy').click(function () {
		if (jQuery('#squeen-opts-export-link-value').is(':visible')) {
			jQuery('#squeen-opts-export-link-value').fadeOut('slow');
		}
		jQuery('#squeen-opts-export-code').toggle('fade');
	});

	jQuery('#squeen-opts-export-link').click(function () {
		if (jQuery('#squeen-opts-export-code').is(':visible')) {
			jQuery('#squeen-opts-export-code').fadeOut('slow');
		}
		jQuery('#squeen-opts-export-link-value').toggle('fade');
	});

	jQuery("#bp_import_fields").click(function () {

		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'bp_add_custom_fields'
			},
			success: function (data) {
				//$('#result').html(data).css('color', 'green');
				if (data == 0) {
					alert('Could not import. Please make sure you have Buddypress installed and activated.');
				}
				else {
					alert(data);
				}
			},
			error: function () {
				// $('#result').html('Sorry, an error occurred.').css('color', 'red');
				alert(data);
			}

		});

		return false;
	});


});



