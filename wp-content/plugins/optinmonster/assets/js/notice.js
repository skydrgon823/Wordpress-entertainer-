jQuery(document).ready(function ($) {
	$(document).on('click', '.om-review-btns', function (event) {
		event.preventDefault();

		var target = $(this).data('res');

		$('.om-steps').hide();
		$('.om-step-' + target).show();
	});

	$(document).on('click', '.om-dismiss-review-notice, .om-review-notice .notice-dismiss', function (event) {
		if ('#' === $(this).attr('href')) {
			event.preventDefault();
		}

		var later = false;

		if ($(this).hasClass('notice-dismiss') || $(this).hasClass('om-dismiss-review-notice-delay')) {
			later = true;
		}

		$.post(
			ajaxurl,
			{
				action: 'omapi_dismiss_review',
				nonce: omNotice.nonce,
				later: later,
			},
			function (response) {}
		);

		$('.om-review-notice').remove();
	});
});
