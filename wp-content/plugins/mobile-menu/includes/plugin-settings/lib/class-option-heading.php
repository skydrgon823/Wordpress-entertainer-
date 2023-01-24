<?php
/**
 * Heading option
 *
 * @package Mobile Menu Settings
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}

/**
 * Heading Option
 *
 * A heading for separating your options in an admin page or meta box
 *
 * <strong>Creating a heading option with a description:</strong>
 * <pre>$adminPage->createOption( array(
 *     'name' => __( 'General Settings', 'default' ),
 *     'type' => 'heading',
 *     'desc' => __( 'Settings for the general usage of the plugin', 'default' ),
 * ) );</pre>
 *
 * @since 1.0
 * @type heading
 * @availability Admin Pages|Meta Boxes|Customizer
 * @no id,default,livepreview,css,hidden
 */
class MobileMenuOptionHeading extends MobileMenuOption {

	/**
	 * Display for options and meta
	 */
	public function display() {
		$headingID = str_replace( ' ', '-', strtolower( $this->settings['name'] ) );
		?>
		<tr valign="top" class="even first mm-heading">
			<th scope="row" class="first last" colspan="2">
				<h3 id="<?php echo esc_attr( $headingID ) ?>"><?php echo $this->settings['name'] ?></h3>
				<?php
				if ( ! empty( $this->settings['desc'] ) ) {
					?><p class='description'><?php echo $this->settings['desc'] ?></p><?php
				}
				?>
			</th>
		</tr>
		<?php
	}

}
