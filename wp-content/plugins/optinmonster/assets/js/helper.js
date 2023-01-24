/* ==========================================================
 * helper.js
 * ==========================================================
 * Copyright 2019 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */
document.addEventListener('om.Styles.positionFloating', function (event) {
	var campaign = event.detail.Campaign;
	if (
		'floating' === campaign.Types.type &&
		'top' === campaign.options.position &&
		document.getElementById('wpadminbar')
	) {
		const marginTop = window.matchMedia('(max-width: 782px)').matches ? '46px' : '32px';
		campaign.contain.style.marginTop = marginTop;
	}
});
