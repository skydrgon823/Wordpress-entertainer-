<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionCheckbox extends MobileMenuOption {

	/*
	 * Display for options and meta
	 */
	public function display() {
		$this->echoOptionHeader();

		?>
		<label for="<?php echo $this->getID() ?>">
		<input name="<?php echo $this->getID() ?>" type="checkbox" id="<?php echo $this->getID() ?>" value="1" <?php checked( $this->getValue(), 1 ) ?>>
		<?php echo $this->getDesc( '' ) ?>
		</label>
		<?php

		$this->echoOptionFooter( false );
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
