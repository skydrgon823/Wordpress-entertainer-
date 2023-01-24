/**
 * WordPress dependencies
 */
const { RawHTML } = wp.element;
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

/**
 * Internal dependencies
 */
import Shortcode from './block';
import icon from './icon';

export const name = 'custom/shortcode9';

export const settings = {
	title: __( 'BP Whats New' ),

	description: __( 'Displays the BP Activity whats new input box. ' ),

	icon,

	category: 'widgets',

	attributes: {
		text: {
			type: 'string',
			source: 'text',
		},
	},

	transforms: {
		from: [
			{
				type: 'shortcode',
				// Per "Shortcode names should be all lowercase and use all
				// letters, but numbers and underscores should work fine too.
				// Be wary of using hyphens (dashes), you'll be better off not
				// using them." in https://codex.wordpress.org/Shortcode_API
				// Require that the first character be a letter. This notably
				// prevents footnote markings ([1]) from being caught as
				// shortcodes.
				tag: '[a-z][a-z0-9_-]*',
				attributes: {
					text: {
						type: 'string',
						shortcode: ( attrs, { content } ) => {
							return content;
						},
					},
				},
			},
		],
	},

	supports: {
		customClassName: false,
		className: false,
		html: false,
	},

	edit: Shortcode,

	save( { attributes } ) {
		return <RawHTML>{ attributes.text }</RawHTML>;
	},
};
registerBlockType( name, settings );
