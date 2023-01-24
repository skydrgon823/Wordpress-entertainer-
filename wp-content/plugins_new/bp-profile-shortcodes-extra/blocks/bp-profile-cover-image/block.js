/**
 * WordPress dependencies
 */
const { Dashicon } = wp.components;
const { withInstanceId } = wp.compose;
const { Component } = wp.element;
const { __ } = wp.i18n;
const { PlainText } = wp.editor;

/**
 * Internal dependencies
 */
import ShortcodePreview from './preview';

export class Shortcode extends Component {
	constructor() {
		super( ...arguments );
		this.state = {};
	}

	render() {
		const { instanceId, setAttributes, attributes, isSelected } = this.props;
		const inputId = `blocks-shortcode-input-${ instanceId }`;
		const shortcodeContent = ( attributes.text || '[bpps_profile_cover_image]' ).trim();

		if ( ! isSelected ) {
			return [
				<div className="wp-block" key="preview">
					<ShortcodePreview
						shortcode={ shortcodeContent }
					/>
				</div>,
			];
		}

		return [
			<div className="wp-block-shortcode" key="placeholder">
				<label htmlFor={ inputId }>
					<Dashicon icon="editor-code" />
					{ __( 'BP Profile Cover Image' ) }
				</label>
				<PlainText
					id={ inputId }
					value={ ( attributes.text || '[bpps_profile_cover_image]').trim() }
					placeholder={ __( 'Write shortcode here…' ) }
					onChange={ ( text ) => setAttributes( { text } ) }
				/>
			</div>,
		];
	}
}

export default withInstanceId( Shortcode );
