
jQuery(function ($) {
	$('#field_box').sortable ({
		items: 'div.sortable',
		tolerance: 'pointer',
		axis: 'y',
		handle: 'span'
	});

	$('#template').change(function () {
		var spinner = $('#bps_template .spinner');
		var save_button = $('#publish');
		var data = {
			'action': 'bps_template_options',
			'form': $('#form_id').val(),
			'template': $('#template option:selected').val()
		};

		save_button.attr('disabled', 'disabled');
		spinner.addClass('is-active');

		$.post (ajaxurl, data, function (new_options) {
			$('#template_options').html(new_options);
			spinner.removeClass('is-active');
			save_button.removeAttr('disabled');
		});
	});

	$('#add_field').click(function () {
		var save_button = $('input[type=submit]');
		var counter = $('#field_next').val();
		var data = {
			'action': 'bps_field_selector',
			'counter': counter
		};

		save_button.attr('disabled', 'disabled');

		$.post (ajaxurl, data, function (field_selector) {
			$('#field_box').append(field_selector);
			$('#field_name'+counter).focus();
			$('#field_next').val(+counter+1);
			save_button.removeAttr('disabled');
		});

		return false;
	});

	$('#field_box').on('change', 'select.bps_col2', function () {
		var spinner = $(this).siblings('.spinner');
		var save_button = $('#publish');
		var container = $(this).parent().attr('id');
		var data = {
			'action': 'bps_field_row',
			'field': this.value,
			'container': container
		};

		save_button.attr('disabled', 'disabled');
		spinner.addClass('is-active');

		$.post (ajaxurl, data, function (field_row) {
			$('#'+container).html(field_row);
			spinner.removeClass('is-active');
			save_button.removeAttr('disabled');
		});
	});

	$('#field_box').on('click', 'a.remove_field', function () {
		$(this).parent().remove();
	});
});
