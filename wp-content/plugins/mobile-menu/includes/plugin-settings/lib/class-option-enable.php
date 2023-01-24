<?php
/**
 * Enable option
 *
 * @package Mobile Menu Settings
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}

/**
 * Enable Option
 *
 * A heading for separating your options in an admin page or meta box
 *
 * <strong>Creating a heading option with a description:</strong>
 * <pre>$adminPage->createOption( array(
 *     'name' => __( 'Enable Feature', 'default' ),
 *     'type' => 'enable',
 *     'default' => true,
 *     'desc' => __( 'You can disable this feature if you do not like it', 'default' ),
 * ) );</pre>
 *
 * @since 1.0
 * @type enable
 * @availability Admin Pages|Meta Boxes|Customizer
 */
class MobileMenuOptionEnable extends MobileMenuOption {

	private static $firstLoad = true;

	/**
	 * Default settings specific for this option
	 * @var array
	 */
	public $defaultSecondarySettings = array(

		/**
		 * (Optional) The label to display in the enable portion of the buttons
		 *
		 * @since 1.0
		 * @var string
		 */
		'enabled' => '',

		/**
		 * (Optional) The label to display in the disable portion of the buttons
		 *
		 * @since 1.0
		 * @var string
		 */
		'disabled' => '',
	);

	/*
	 * Display for options and meta
	 */
	public function display() {
		$this->echoOptionHeader();

		if ( empty( $this->settings['enabled'] ) ) {
			$this->settings['enabled'] = __( 'Enabled', 'mobile-menu' );
		}
		if ( empty( $this->settings['disabled'] ) ) {
			$this->settings['disabled'] = __( 'Disabled', 'mobile-menu' );
		}

		?>
		<input name="<?php echo $this->getID() ?>" type="checkbox" id="<?php echo $this->getID() ?>" value="1" <?php checked( $this->getValue(), 1 ) ?>>
		<span class="button button-<?php echo checked( $this->getValue(), 1, false ) ? 'primary' : 'secondary' ?>"><?php echo $this->settings['enabled'] ?></span><span class="button button-<?php echo checked( $this->getValue(), 1, false ) ? 'secondary' : 'primary' ?>"><?php echo $this->settings['disabled'] ?></span>
		<?php

		// load the javascript to init the colorpicker
		if ( self::$firstLoad ) :
			?>
			<script>
			jQuery(document).ready(function($) {
				"use strict";
				$('body').on('click', '.mm-enable .button-secondary', function() {
					$(this).parent().find('.button').toggleClass('button-primary button-secondary');
					var checkBox = $(this).parents('.mm-enable').find('input');
					if ( checkBox.is(':checked') ) {
						checkBox.removeAttr('checked');
					} else {
						checkBox.attr('checked', 'checked');
					}
					checkBox.trigger('change');
				});
			});
			</script>
			<?php
		endif;

		$this->echoOptionFooter();

		self::$firstLoad = false;
	}

	public function cleanValueForSaving( $value ) {
		return $value != '1' ? '0' : '1';
	}

	public function cleanValueForGetting( $value ) {
		if ( is_bool( $value ) ) {
			return $value;
		}

		return $value == '1' ? true : false;
	}
}
