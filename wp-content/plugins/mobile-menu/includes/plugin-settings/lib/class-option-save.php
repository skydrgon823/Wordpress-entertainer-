<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionSave extends MobileMenuOption {

	public $defaultSecondarySettings = array(
		'save'   => '',
		'action' => 'save',
	);

	public function display() {
		if ( ! empty( $this->owner->postID ) ) {
			return;
		}

		if ( empty( $this->settings['save'] ) ) {
			$this->settings['save'] = __( 'Save Changes', 'mobile-menu' );
		}

		?>
		</tbody>
		</table>

		<p class='submit'>
			<button name="action" value="<?php echo $this->settings['action'] ?>" class="button button-primary">
				<?php echo $this->settings['save'] ?>
			</button>
		</p>

		<table class='form-table'>
			<tbody>
		<?php
	}
}
