import get from 'lodash/get';

import { hasSites } from './sites';

/**
 * Get available inline campaign select options.
 *
 * @since  [since]
 *
 * @param  {string}  type       The campaign generic type (inline or other).
 * @param  {string}  slug       The campaign slug.
 * @param  {boolean} checkSites Whether to check for sites (return empty result if no sites connected).
 *
 * @returns {Array}              Array of campaign options for select elements.
 *                              Includes value, label, and selected/disabled properties.
 */
export const getOptions = (type, slug = null, checkSites = true) => {
	if (checkSites && !hasSites()) {
		return [];
	}

	const campaigns = get(OMAPI, `campaigns.${type}`, {});
	if (Object.keys(campaigns).length < 1 || !OMAPI.omUserId) {
		return [];
	}

	let available = Object.keys(campaigns).map((value) => {
		let label = get(campaigns, `${value}.title`, '');
		if (get(campaigns, `${value}.pending`)) {
			label += ' [Pending]';
		}

		const selected = null !== slug && slug === value;
		const disabled = null !== slug && get(OMAPI, `_usedSlugs.${value}`) && value !== slug;

		return { value, label, selected, disabled };
	});

	if (available.length > 0) {
		available.unshift({ value: '', label: OMAPI.i18n.campaign_select });
	}

	return available;
};

/**
 * Get the global campaign object from OM API for given slug.
 *
 * @since 2.3.0
 *
 * @param  {string} slug The campaign slug.
 *
 * @returns {Object|null} The global campaign object or null.
 */
export const getCampaign = (slug = '') => {
	const key = `om${slug}`;
	return window[key] ? window[key] : null;
};
