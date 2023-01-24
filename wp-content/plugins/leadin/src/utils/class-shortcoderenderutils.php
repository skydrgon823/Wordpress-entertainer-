<?php

namespace Leadin\utils;

use Leadin\LeadinFilters;
use Leadin\AssetsManager;

/**
 * Util Class responsible for rendering shortcodes.
 */
class ShortcodeRenderUtils {

	/**
	 * Render leadin shortcodes output
	 *
	 * @param array $shortcode_attrs Shortcode attributes.
	 */
	public static function render_shortcode( $shortcode_attrs ) {
		$parsed_attributes = shortcode_atts( array( 'type' => null ), $shortcode_attrs );

		$type = $parsed_attributes['type'];
		if ( ! isset( $type ) ) {
			return;
		}

		switch ( $type ) {
			case 'form':
				return self::render_form( $shortcode_attrs );
			case 'cta':
				return self::render_cta( $shortcode_attrs );
			case 'meeting':
				return self::render_meeting( $shortcode_attrs );
		}
	}

	/**
	 * Render form leadin shortcodes
	 *
	 * @param array $attrs Shortcode attributes.
	 */
	private static function render_form( $attrs ) {
		$parsed_attributes = shortcode_atts(
			array(
				'portal' => null,
				'id'     => null,
			),
			$attrs
		);

		$portal_id = $parsed_attributes['portal'];
		$id        = $parsed_attributes['id'];

		if ( ! isset( $portal_id ) || ! isset( $id ) || ! is_numeric( $portal_id ) ||
		! ( self::is_valid_uuid( $id ) || is_numeric( $id ) ) ) {
			return;
		}

		$form_div_uuid = self::generate_div_uuid();
		$hublet        = LeadinFilters::get_leadin_hublet();
		AssetsManager::enqueue_forms_script();
		return '
					<script>
						window.hsFormsOnReady = window.hsFormsOnReady || [];
						window.hsFormsOnReady.push(()=>{
							hbspt.forms.create({
								portalId: ' . $portal_id . ',
								formId: "' . $id . '",
								target: "#hbspt-form-' . $form_div_uuid . '",
								region: "' . $hublet . '",
								' . LeadinFilters::get_leadin_forms_payload() . '
						})});
					</script>
					<div class="hbspt-form" id="hbspt-form-' . $form_div_uuid . '"></div>';
	}

	/**
	 * Render cta leadin shortcodes
	 *
	 * @param array $attrs Shortcode attributes.
	 */
	private static function render_cta( $attrs ) {
		$parsed_attributes = shortcode_atts(
			array(
				'portal' => null,
				'id'     => null,
			),
			$attrs
		);

		$portal_id = $parsed_attributes['portal'];
		$id        = $parsed_attributes['id'];

		if ( ! isset( $portal_id ) || ! isset( $id ) || ! is_numeric( $portal_id ) ||
		! ( self::is_valid_uuid( $id ) || is_numeric( $id ) ) ) {
			return;
		}

		return '
		<!--HubSpot Call-to-Action Code -->
		<span class="hs-cta-wrapper" id="hs-cta-wrapper-' . $id . '">
		<span class="hs-cta-node hs-cta-' . $id . '" id="' . $id . '">
		<!--[if lte IE 8]>
		<div id="hs-cta-ie-element"></div>
		<![endif]-->
		<a href="https://cta-redirect.hubspot.com/cta/redirect/' . $portal_id . '/' . $id . '" >
		<img class="hs-cta-img" id="hs-cta-img-' . $id . '" style="border-width:0px;" src="https://no-cache.hubspot.com/cta/default/' . $portal_id . '/' . $id . '.png"  alt="New call-to-action"/>
		</a>
		</span>
		<' . 'script charset="utf-8" src="//js.hubspot.com/cta/current.js"></script>
		<script type="text/javascript">
		hbspt.cta.load(' . $portal_id . ', \'' . $id . '\', {});
		</script>
		</span>
		<!-- end HubSpot Call-to-Action Code -->
		';
	}

	/**
	 * Render meeting leadin shortcodes
	 *
	 * @param array $attrs Shortcode attributes.
	 */
	private static function render_meeting( $attrs ) {
		$parsed_attributes = shortcode_atts(
			array(
				'url' => null,
			),
			$attrs
		);

		$url = $parsed_attributes['url'];

		if ( filter_var( $url, FILTER_VALIDATE_URL ) === false ) {
			return;
		}

		AssetsManager::enqueue_meetings_script();

		return '
		<div class="meetings-iframe-container" data-src="' . $url . '?embed=true"></div>
		<script>
			if(window.hbspt && window.hbspt.meetings){
				window.hbspt.meetings.create(".meetings-iframe-container");
			}
			</script>
		';
	}

	/**
	 * Generates 10 characters long string with random values
	 */
	private static function get_random_number_string() {
		$result = '';
		for ( $i = 0; $i < 10; $i++ ) {
			$result .= wp_rand( 0, 9 );
		}
		return $result;
	}

	/**
	 * Generates a unique uuid
	 */
	private static function generate_div_uuid() {
		return time() * 1000 . '-' . self::get_random_number_string();
	}

	/**
	 * Checks if input is a valid uuid.
	 *
	 * @param String $uuid input to validate.
	 */
	private static function is_valid_uuid( $uuid ) {
		if ( ! is_string( $uuid ) || ( preg_match( '/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $uuid ) !== 1 ) ) {
			return false;
		}
		return true;
	}

}
