<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionSelect extends MobileMenuOption {

	public $defaultSecondarySettings = array(
		'options' => array(),
	);

	/**
	 * Check if this instance is the first load of the option class
	 *
	 * @since 1.9.3
	 * @var bool $firstLoad
	 */
	private static $firstLoad = true;

	/**
	 * Constructor
	 *
	 * @param array  $settings Option settings
	 * @param string $owner    Namespace
	 *
	 * @since    1.9.3
	 */
	function __construct( $settings, $owner ) {
		parent::__construct( $settings, $owner );

		mm_add_action_once( 'admin_enqueue_scripts', array( $this, 'load_select_scripts' ) );
		//mm_add_action_once( 'customize_controls_enqueue_scripts', array( $this, 'load_select_scripts' ) );

		mm_add_action_once( 'admin_head', array( $this, 'init_select_script' ) );
		//mm_add_action_once( 'customize_controls_print_footer_scripts', array( $this, 'init_select_script' ) );
	}


	/*
	 * Display for options and meta
	 */
	public function display() {
		$this->echoOptionHeader();
		$multiple = isset( $this->settings['multiple'] ) && true == $this->settings['multiple'] ? 'multiple' : '';

		$name = $this->getID();
		$val  = (array) $this->getValue();

		if ( ! empty( $multiple ) ) {
			$name = "{$name}[]";
		}

		?><select name="<?php echo $name; ?>" <?php echo $multiple; ?>><?php
		mm_parse_select_options( $this->settings['options'], $val );
		?></select><?php

		$this->echoOptionFooter();
	}

	/**
	 * Register and load the select2 script
	 *
	 * @since 1.9.3
	 * @return void
	 */
	public function load_select_scripts() {

		wp_enqueue_script( 'mm-select2', MobileMenuOptions::getURL( '../js/select2/select2.min.js', __FILE__ ), array( 'jquery' ), WP_MOBILE_MENU_VERSION, true );
		wp_enqueue_style( 'mm-select2-style', MobileMenuOptions::getURL( '../css/select2/select2.min.css', __FILE__ ), null, WP_MOBILE_MENU_VERSION, 'all' );
		wp_enqueue_style( 'mm-select-option-style', MobileMenuOptions::getURL( '../css/class-option-select.css', __FILE__ ), null, WP_MOBILE_MENU_VERSION, 'all' );

	}


	/**
	 * Initialize the select2 field
	 *
	 * @since 1.9.3
	 * @return void
	 */
	public function init_select_script() {

		if ( ! self::$firstLoad ) {
			return;
		}

		self::$firstLoad = false;

		?>
		<script>
		jQuery( document ).ready( function () {
			'use strict';

			/**
			 * Select2
			 * @see https://select2.github.io/
			 */
			if ( jQuery().select2 ) {
				jQuery( 'select.mm-select, [class*="mm-select"] select' ).select2();
			}
		});
		</script>
		<?php
	}

}


/**
 * Helper function for parsing select options
 *
 * This function is used to reduce duplicated code between the TF option
 * and the customizer control.
 *
 * @since 1.9
 *
 * @param array $options List of options
 * @param array $val     Current value
 *
 * @return void
 */
function mm_parse_select_options( $options, $val = array() ) {

	// No options? Duh...
	if ( empty( $options ) ) {
		return;
	}

	// Make sure the current value is an array (for multiple select).
	if ( ! is_array( $val ) ) {
		$val = (array) $val;
	}
	foreach ( $options as $value => $label ) {

		// This is if we have option groupings.
		if ( is_array( $label ) ) {

			?>
			<optgroup label="<?php echo $value ?>"><?php
			foreach ( $label as $subValue => $subLabel ) {

				printf( '<option value="%s" %s %s>%s</option>',
					$subValue,
					in_array( $subValue, $val ) ? 'selected="selected"' : '',
					disabled( stripos( $subValue, '!' ), 0, false ),
					$subLabel
				);
			}
			?></optgroup><?php
			// This is for normal list of options.
		} else {
			printf( '<option value="%s" %s %s>%s</option>',
				$value,
				in_array( $value, $val ) ? 'selected="selected"' : '',
				disabled( stripos( $value, '!' ), 0, false ),
				$label
			);
		}
	}

}
