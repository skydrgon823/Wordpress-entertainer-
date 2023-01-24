/* ==========================================================
 * om-settings.js
 * ==========================================================
 * Copyright 2021 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */

'use strict';

import SidebarSettings from './Components/SidebarSettings';

wp.plugins.registerPlugin('om-global-post-settings', {
	render: SidebarSettings,
	icon: null,
	priority: 999, // Supported in the future: https://github.com/WordPress/gutenberg/pull/16384
});
