<?php

/**
 * Date Option Class
 *
 * @author Ardalan Naghshineh (www.ardalan.me)
 * @package Mobile Menu Settings Core
 **/

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
/**
 * Date Option Class
 *
 * @since	1.0
 **/
class MobileMenuOptionDate extends MobileMenuOption {

	// Default settings specific to this option
	public $defaultSecondarySettings = array(
		'date' => true,
		'time' => false,
	);

	private static $date_epoch;

	/**
	 * Constructor
	 *
	 * @since	1.4
	 */
	function __construct( $settings, $owner ) {
		parent::__construct( $settings, $owner );

		mm_add_action_once( 'admin_enqueue_scripts', array( $this, 'enqueueDatepicker' ) );
		mm_add_action_once( 'customize_controls_enqueue_scripts', array( $this, 'enqueueDatepicker' ) );
		add_action( 'admin_head', array( __CLASS__, 'createCalendarScript' ) );

		if ( empty( self::$date_epoch ) ) {
			self::$date_epoch = date( 'Y-m-d', 0 );
		}
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
		if ( ! $this->settings['date'] && $this->settings['time'] ) {
			$value = self::$date_epoch . ' ' . $value;
		}
		return strtotime( $value );
	}

	/**
	 * Cleans the value for getOption
	 *
	 * @param	string $value The raw value of the option
	 * @return	mixes The cleaned value
	 * @since	1.4
	 */
	public function cleanValueForGetting( $value ) {
		if ( $value == 0 ) {
			return '';
		}
		return $value;
	}

	/**
	 * Enqueues the jQuery UI scripts
	 *
	 * @return	void
	 * @since	1.4
	 */
	public function enqueueDatepicker() {
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'mm-jquery-ui-timepicker-addon', MobileMenuOptions::getURL( '../js/min/jquery-ui-timepicker-addon-min.js', __FILE__ ), array( 'jquery-ui-datepicker', 'jquery-ui-slider' ) );
	}


	/**
	 * Prints out the script the initializes the jQuery Datepicker
	 *
	 * @return	void
	 * @since	1.4
	 */
	public static function createCalendarScript() {
		?>
		<script>
		jQuery(document).ready(function($) {
			"use strict";

			var datepickerSettings = {
					dateFormat: 'yy-mm-dd',

					beforeShow: function(input, inst) {
						$('#ui-datepicker-div').addClass('mm-date-datepicker');

						// Fix the button styles
						setTimeout( function() {
							jQuery('#ui-datepicker-div')
							.find('[type=button]').addClass('button').end()
							.find('.ui-datepicker-close[type=button]').addClass('button-primary');
						}, 0);
					},

					// Fix the button styles
					onChangeMonthYear: function() {
						setTimeout( function() {
							jQuery('#ui-datepicker-div')
							.find('[type=button]').addClass('button').end()
							.find('.ui-datepicker-close[type=button]').addClass('button-primary');
						}, 0);
					}
				};
			$('.mm-date input[type=text]').each(function() {
				var $this = $(this);
				if ( $this.hasClass('date') && ! $this.hasClass('time') ) {
					$this.datepicker( datepickerSettings );
				} else if ( ! $this.hasClass('date') && $this.hasClass('time') ) {
					$this.timepicker( datepickerSettings );
				} else {
					$this.datetimepicker( datepickerSettings );
				}
			});
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
		$dateFormat = 'Y-m-d H:i';
		$placeholder = 'YYYY-MM-DD HH:MM';
		if ( $this->settings['date'] && ! $this->settings['time'] ) {
			$dateFormat = 'Y-m-d';
			$placeholder = 'YYYY-MM-DD';
		} else if ( ! $this->settings['date'] && $this->settings['time'] ) {
			$dateFormat = 'H:i';
			$placeholder = 'HH:MM';
		}

		printf('<input class="input-date%s%s" name="%s" placeholder="%s" id="%s" type="text" value="%s" /> <p class="description">%s</p>',
			( $this->settings['date'] ? ' date' : '' ),
			( $this->settings['time'] ? ' time' : '' ),
			$this->getID(),
			$placeholder,
			$this->getID(),
			esc_attr( ($this->getValue() > 0) ? date( $dateFormat, $this->getValue() ) : '' ),
			$this->settings['desc']
		);
		$this->echoOptionFooter( false );
	}
}
