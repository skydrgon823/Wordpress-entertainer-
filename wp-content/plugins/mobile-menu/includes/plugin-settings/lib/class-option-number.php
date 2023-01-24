<?php

/**
 * Number Option Class
 *
 **/

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
/**
 * Number Option Class
 *
 * @since	1.0
 **/
class MobileMenuOptionNumber extends MobileMenuOption {

	// Default settings specific to this option
	public $defaultSecondarySettings = array(
		'size' => 'small', // or medium or large
		'placeholder' => '', // show this when blank
		'min' => 0,
		'max' => 1000,
		'step' => 1,
		'default' => 0,
		'unit' => '',
	);


	/**
	 * Constructor
	 *
	 * @since	1.4
	 */
	function __construct( $settings, $owner ) {
		parent::__construct( $settings, $owner );

		mm_add_action_once( 'admin_enqueue_scripts', array( $this, 'enqueueSlider' ) );
		mm_add_action_once( 'customize_controls_enqueue_scripts', array( $this, 'enqueueSlider' ) );
		add_action( 'admin_head', array( __CLASS__, 'createSliderScript' ) );
	}


	/**
	 * Cleans up the serialized value before saving
	 *
	 * @param	string $value The serialized value
	 * @return	string The cleaned value
	 * @since	1.4
	 */
	public function cleanValueForSaving( $value ) {
		if ( $value == '' ) {
			return 0;
		}
		return $value;
	}


	/**
	 * Cleans the value for getOption
	 *
	 * @param	string $value The raw value of the option
	 * @return	mixes The cleaned value
	 * @since	1.4
	 */
	public function cleanValueForGetting( $value ) {
		if ( $value == '' ) {
			return 0;
		}
		return $value;
	}


	/**
	 * Enqueues the jQuery UI scripts
	 *
	 * @return	void
	 * @since	1.4
	 */
	public function enqueueSlider() {
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'underscore' );
	}


	/**
	 * Prints out the script the initializes the jQuery slider
	 *
	 * @return	void
	 * @since	1.4
	 */
	public static function createSliderScript() {
		?>
		<script>
		jQuery(document).ready(function($) {
			'use strict';

			$( '.mm-number input[type=number]' ).each(function() {
				if ( ! $( this ).prev().is( '.number-slider' ) ) {
					return;
				}
				$( this ).prev().slider( {
					max: Number( $( this ).attr('max') ),
					min: Number( $( this ).attr('min') ),
					step: Number( $( this ).attr('step') ),
					value: Number( $( this ).val() ),
					animate: 'fast',
					change: function( event, ui ) {
						var input = $( ui.handle ).parent().next();
						if ( ui.value !== input.val() ) {
							input.val( ui.value ).trigger( 'change' );
						}
					},
					slide: function( event, ui ) {
						var input = $( ui.handle ).parent().next();
						if ( ui.value !== input.val() ) {
							input.val( ui.value ).trigger( 'change' );
						}
					}
				} ).disableSelection();
			} );
			$( '.mm-number input[type=number]' ).on( 'keyup', _.debounce( function() {
				if ( $( this ).prev().slider( 'value' ).toString() !== $( this ).val().toString() ) {
					$( this ).prev().slider( 'value', $( this ).val() );
				}
			}, 500 ) );
		});
		</script>
		<?php
	}


	/**
	 * Displays the option for admin pages and meta boxes
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function display() {
		$this->echoOptionHeader();
		echo "<div class='number-slider'></div>";
		printf('<input class="%s-text" name="%s" placeholder="%s" id="%s" type="number" value="%s" min="%s" max="%s" step="%s" /> %s <p class="description">%s</p>',
			$this->settings['size'],
			$this->getID(),
			$this->settings['placeholder'],
			$this->getID(),
			esc_attr( $this->getValue() ),
			$this->settings['min'],
			$this->settings['max'],
			$this->settings['step'],
			$this->settings['unit'],
			$this->settings['desc']
		);
		$this->echoOptionFooter( false );
	}
}
