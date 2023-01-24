/* ==========================================================
 * trustpulse.js
 * ==========================================================
 * Copyright 2020 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */
jQuery(document).ready(function ($) {
	$('.install-plugin-form').submit((e) => {
		e.preventDefault();

		const $install = $('.button-install');
		const $activate = $('.button-activate');
		const installText = $install.html();
		const activateText = $activate.html();

		$install.html('Installing...');
		$activate.html('Activating...');

		$('#om-plugin-alerts').hide();

		$.ajax({
			type: 'POST',
			beforeSend: function (request) {
				request.setRequestHeader('X-WP-Nonce', omapiTp.restNonce);
			},
			url: omapiTp.restUrl + 'omapp/v1/plugins/',
			data: {
				id: 'trustpulse-api/trustpulse.php',
				url: omapiTp.pluginUrl,
				nonce: omapiTp.action === 'install' ? omapiTp.installNonce : omapiTp.activateNonce,
				installAction: omapiTp.action,
			},
			success: function (data) {
				window.location.reload();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				$install.html(installText);
				$activate.html(activateText);

				let message = 'Something went wrong!';
				if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
					message += '<br>Error found: ' + jqXHR.responseJSON.message;
				}
				if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
					try {
						message += `<br>(data: ${JSON.stringify(jqXHR.responseJSON.data)})`;
					} catch (e) {}
				}

				// eslint-disable-next-line no-console
				console.error(`Could not ${omapiTp.action} the TrustPulse plugin`, { jqXHR, textStatus, errorThrown });

				$('#om-plugin-alerts').show().html($('<p/>').html(message));
			},
		});
	});
});
