<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionGroup extends MobileMenuOption {


	/**
	 * The defaults of the settings specific to this option.
	 *
	 * @var array
	 */
	public $defaultSecondarySettings = array(
		'options' => array(),
	);


	/**
	 * Holds the options of this group.
	 *
	 * @var array
	 */
	public $options = array();


	/**
	 * Override the constructor to include the creation of the options within
	 * the group.
	 *
	 * @param array                   $settings The settings of the option.
	 * @param MobileMenuAdminPage $owner The owner of the option.
	 */
	function __construct( $settings, $owner ) {
		parent::__construct( $settings, $owner );
		$this->init_group_options();
	}


	/**
	 * Creates the options contained in the group. Mimics how Admin pages
	 * create options.
	 *
	 * @return void
	 */
	public function init_group_options() {
		if ( ! empty( $this->settings['options'] ) ) {

			if ( is_array( $this->settings['options'] ) ) {

				foreach ( $this->settings['options'] as $settings ) {

					if ( ! apply_filters( 'mm_create_option_continue_mobmenu' , true, $settings ) ) {
						continue;
					}

					$obj = MobileMenuOption::factory( $settings, $this->owner );
					$this->options[] = $obj;

					do_action( 'mm_create_option_mobmenu', $obj );
				}
			}
		}
	}


	/**
	 * Display for options and meta
	 */
	public function display() {

		$this->echoOptionHeader();

		if ( ! empty( $this->options ) ) {
			foreach ( $this->options as $option ) {

				// Display the name of the option.
				$name = $option->getName();
				if ( ! empty( $name ) && ! $option->getHidden() ) {
					echo '<span class="mm-group-name">' . esc_html( $name ) . '</span> ';
				}

				// Disable wrapper printing.
				$option->echo_wrapper = false;

				// Display the option field.
				echo '<span class="mm-group-option">';
				$option->display();
				echo '</span>';
			}
		}

		$this->echoOptionFooter();
	}
}
