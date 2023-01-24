<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
/**
 * Code Option Class
 *
 * @since	1.4
 **/
class MobileMenuOptionSortable extends MobileMenuOption {

	// Default settings specific to this option
	public $defaultSecondarySettings = array(
		'options' => array(),
		'visible_button' => true,
	);

	private static $firstLoad = true;


	/**
	 * Constructor
	 *
	 * @since	1.4
	 */
	function __construct( $settings, $owner ) {
		parent::__construct( $settings, $owner );

		add_action( 'admin_head', array( __CLASS__, 'createSortableScript' ) );
		mm_add_action_once( 'admin_enqueue_scripts', array( $this, 'enqueueSortable' ) );
		mm_add_action_once( 'customize_controls_enqueue_scripts', array( $this, 'enqueueSortable' ) );
	}


	/**
	 * Enqueues the jQuery UI scripts
	 *
	 * @return	void
	 * @since	1.4
	 */
	public function enqueueSortable() {
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
	}


	/**
	 * Creates the javascript needed for sortable to run
	 *
	 * @return	void
	 * @since	1.4
	 */
	public static function createSortableScript() {
		if ( ! self::$firstLoad ) {
			return;
		}
		self::$firstLoad = false;

		?>
		<script>
		jQuery(document).ready(function($) {
			"use strict";

			// initialize
			$('.mm-sortable > ul ~ input').each(function() {
				var value = $(this).val();
				try {
					value = unserialize( value );
				} catch (err) {
					return;
				}

				var ul = $(this).siblings('ul:eq(0)');
				ul.find('li').addClass('mm-invisible').find('i.visibility').toggleClass('dashicons-visibility-faint');
				$.each(value, function(i, val) {
					ul.find('li[data-value=' + val + ']').removeClass('mm-invisible').find('i.visibility').toggleClass('dashicons-visibility-faint');
				});
			});

			$('.mm-sortable > ul').each(function() {
				$(this).sortable()
				.disableSelection()
				.on( "sortstop", function( event, ui ) {
					tfUpdateSortable(ui.item.parent());
				})
				.find('li').each(function() {
					$(this).find('i.visibility').click(function() {
						$(this).toggleClass('dashicons-visibility-faint').parents('li:eq(0)').toggleClass('mm-invisible');
					});
				})
				.click(function() {
					tfUpdateSortable( $(this).parents('ul:eq(0)') );
				})
			});
		});

		function tfUpdateSortable(ul) {
			"use strict";
			var $ = jQuery;

			var values = [];

			ul.find('li').each(function() {
				if ( ! $(this).is('.mm-invisible') ) {
					values.push( $(this).attr('data-value') );
				}
			});

			ul.siblings('input').eq(0).val( serialize( values ) ).trigger('change');
		}
		</script>
		<?php
	}


	/**
	 * Displays the option in admin panels and meta boxes
	 *
	 * @return	void
	 * @since	1.4
	 */
	public function display() {
		if ( ! is_array( $this->settings['options'] ) ) {
			return;
		}
		if ( ! count( $this->settings['options'] ) ) {
			return;
		}

		$this->echoOptionHeader( true );

		$values = $this->getValue();
		if ( $values == '' ) {
			$values = array_keys( $this->settings['options'] );
		}
		if ( count( $values ) != count( $this->settings['options'] ) ) {
			$this->settings['visible_button'] = true;
		}

		$visibleButton = '';
		$orientation = '';
		
		if ( $this->settings['visible_button'] == true ) {
			$visibleButton = "<i class='dashicons dashicons-visibility visibility'></i>";
		}



		if ( isset( $this->settings['orientation'] ) ) {
			$orientation = $this->settings['orientation'];
		} 


		?>
		<ul class="<?php echo $orientation;?>">
			<?php
			foreach ( $values as $dummy => $value ) {
				if ( isset( $this->settings['options'][ $value ] ) ) {
					printf( "<li data-value='%s'><i class='dashicons dashicons-menu'></i>%s%s</li>",
						esc_attr( $value ),
						$visibleButton,
						$this->settings['options'][ $value ]
					);
				}
			}

			$invisibleKeys = array_diff( array_keys( $this->settings['options'] ), $values );
			foreach ( $invisibleKeys as $dummy => $value ) {
				if ( isset( $this->settings['options'][ $value ] ) ) {
					printf( "<li data-value='%s'><i class='dashicons dashicons-menu'></i>%s%s</li>",
						esc_attr( $value ),
						$visibleButton,
						$this->settings['options'][ $value ]
					);
				}
			}
			?>
		</ul>
		<div class='clear: both'></div>
		<?php

		if ( ! is_serialized( $values ) ) {
			$values = serialize( $values );
		}

		printf( "<input type='hidden' name=\"%s\" id=\"%s\" value=\"%s\" />",
			$this->getID(),
			$this->getID(),
			esc_attr( $values )
		);

		$this->echoOptionFooter( false );
	}


	/**
	 * Cleans up the serialized value before saving
	 *
	 * @param	string $value The serialized value
	 * @return	string The cleaned value
	 * @since	1.4
	 */
	public function cleanValueForSaving( $value ) {
		if ( is_array( $value ) ) {
			return serialize( $value );
		}
		return stripslashes( $value );
	}


	/**
	 * Cleans the raw value for getting
	 *
	 * @param	string $value The raw value
	 * @return	string The cleaned value
	 * @since	1.4
	 */
	public function cleanValueForGetting( $value ) {
		if ( is_array( $value ) ) {
			return $value;
		}
		if ( is_serialized( stripslashes( $value ) ) ) {
			return unserialize( $value );
		}
		return $value;
	}


}
