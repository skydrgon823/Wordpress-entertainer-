/* ==========================================================
 * global.js
 * ==========================================================
 * Copyright 2021 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */
window.OMAPI_Global = window.OMAPI_Global || {};

(function (window, document, $, app, undefined) {
	'use strict';

	app.init = function () {
		// If the app is running, we don't need to proceed.
		if (window.omWpApi) {
			return;
		}
		$.ajax({
			async: true,
			url: app.url,
			headers: {
				'x-wp-nonce': app.nonce,
			},
		}).done(function (response) {
			// If the app is running, we don't need to proceed.
			if (window.omWpApi) {
				return;
			}

			var total = response.length;
			var $menu = $(document.getElementById('toplevel_page_optin-monster-dashboard'));
			var $name = $menu.find('.toplevel_page_optin-monster-dashboard .wp-menu-name');
			var $count = $name.find('.om-notifications-count');
			var countString = String(total);
			var classes = 'om-notifications-count update-plugins count-' + countString;

			if ($count.length) {
				$count.attr('class', classes);
				$count.find('.plugin-count').text(countString);
			} else {
				$name.html(
					'OptinMonster <span class="' +
						classes +
						'"><span class="plugin-count">' +
						countString +
						'</span></span>'
				);
			}
		});
	};

	$(app.init);
})(window, document, jQuery, window.OMAPI_Global);
