<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}

class MobileMenuOption {

	const TYPE_META = 'meta';
	const TYPE_ADMIN = 'option';
	const TYPE_CUSTOMIZER = 'customizer';

	public $settings;
	public $type; // One of the TYPE_* constants above
	public $owner;
	public $echo_wrapper = true;

	private static $rowIndex = 0;


	/**
	 * Default settings across all options
	 * @var array
	 */
	private static $defaultSettings = array(

		'type'        => 'text',
		'name'        => '',
		'desc'        => '',
		'id'          => '',
		'default'     => '',
		'livepreview' => '',
		'css'         => '',
		'hidden'      => false,
		'transport'   => '',
		'example'     => '', // An example value for this field, will be displayed in a <code>
		'sanitize_callback' => '',
	);

	public $defaultSecondarySettings = array();

	public static function factory( $settings, $owner ) {
		$settings = array_merge( self::$defaultSettings, $settings );

		$className = 'MobileMenuOption' . str_replace( ' ', '', ucwords( str_replace( '-', ' ', $settings['type'] ) ) );

		if ( class_exists( $className ) ) {
			$obj = new $className( $settings, $owner );
			return $obj;
		}

		$className = $settings['type'];
		$obj = new $className( $settings, $owner );
		return $obj;
	}

	function __construct( $settings, $owner ) {
		$this->owner = $owner;

		$this->settings = array_merge( self::$defaultSettings, $this->defaultSecondarySettings );
		$this->settings = array_merge( $this->settings, $settings );

		$this->type = is_a( $owner, 'MobileMenuMetaBox' ) ? self::TYPE_META : self::TYPE_ADMIN;

		// Generate a unique ID depending on the settings for those without IDs
		if ( empty( $this->settings['id'] ) && $this->settings['type'] != 'save' ) {
			$this->settings['id'] = substr( md5( serialize( $this->settings ) . serialize( $this->owner->settings ) ), 0, 16 );
		}
	}

	
	public function getClass( $postID = null ) {

		if ( ! empty( $this->settings['class'] ) ) {
			return $this->settings['class'];
		}
		return '';
	}

	public function getValue( $postID = null ) {

		$value = false;

		if ( empty( $this->settings['id'] ) ) {
			return $value;
		}

		if ( $this->type == self::TYPE_ADMIN ) {

			$value = $this->getMobmenu()->getInternalAdminPageOption( $this->settings['id'], $this->settings['default'] );

		} else if ( $this->type == self::TYPE_META ) {

			if ( empty( $postID ) ) {
				$postID = $this->owner->postID;
			}
			// If no $postID is given, try and get it if we are in a loop.
			if ( empty( $postID ) && ! is_admin() && get_post() != null ) {
				$postID = get_the_ID();
			}

			// for meta options, use the default value for new posts/pages
			if ( metadata_exists( 'post', $postID, $this->getID() ) ) {
				$value = get_post_meta( $postID, $this->getID(), true );
			} else {
				$value = $this->settings['default'];
			}
		} else if ( $this->type == self::TYPE_CUSTOMIZER ) {
			$value = get_theme_mod( $this->getID(), $this->settings['default'] );
		}

		// Apply cleaning method for the value (for serialized data, slashes, etc).
		$value = $this->cleanValueForGetting( $value );

		return $value;
	}


	/**
	 *
	 */
	public function setValue( $value, $postID = null ) {

		// Apply cleaning method for the value (for serialized data, slashes, etc).
		$value = $this->cleanValueForSaving( $value );

		if ( $this->type == self::TYPE_ADMIN ) {

			$this->getMobmenu()->setInternalAdminPageOption( $this->settings['id'], $value );

		} else if ( $this->type == self::TYPE_META ) {

			if ( empty( $postID ) ) {
				$postID = $this->owner->postID;
			}
			// If no $postID is given, try and get it if we are in a loop.
			if ( empty( $postID ) && ! is_admin() && get_post() != null ) {
				$postID = get_the_ID();
			}

			update_post_meta( $postID, $this->getID(), $value );

		} else if ( $this->type == self::TYPE_CUSTOMIZER ) {

			set_theme_mod( $this->getID(), $value );

		}

		return true;
	}


	protected function getMobmenu() {
		if ( is_a( $this->owner, 'MobileMenuAdminTab' ) ) {
			// a tab's parent is an admin panel
			return $this->owner->owner->owner;
		} else {
			// a theme customizer's parent
			return $this->owner->owner;
		}
	}

	public function getOptionNamespace() {
		return $this->getMobmenu()->optionNamespace;
	}

	public function getID() {
		return $this->getOptionNamespace() . '_' . $this->settings['id'];
	}

	public function __call( $name, $args ) {
		$default = is_array( $args ) && count( $args ) ? $args[0] : '';
		if ( stripos( $name, 'get' ) == 0 ) {
			$setting = strtolower( substr( $name, 3 ) );
			return empty( $this->settings[ $setting ] ) ? $default : $this->settings[ $setting ];
		}
		return $default;
	}

	protected function echoOptionHeader( $showDesc = false ) {

		if ( ! $this->echo_wrapper ) {
			if ( $this->getHidden() ) {
				echo '<div style="display: none;">';
			}
			return;
		}

		$id          = $this->getID();
		$name        = $this->getName();
		$optionClass = $this->getClass();
		$evenOdd     = self::$rowIndex++ % 2 == 0 ? 'odd' : 'even';
		$style       = $this->getHidden() == true ? 'style="display: none"' : '';

		?>
		<tr valign="top" class="row-<?php echo self::$rowIndex ?> <?php echo $id ?> <?php echo $evenOdd ?> <?php echo $optionClass; ?>" <?php echo $style ?>>
		<th scope="row" class="first">
			<label for="<?php echo ! empty( $id ) ? $id : '' ?>"><?php echo ! empty( $name ) ? $name : '' ?></label>
		</th>
		<td class="second mm-<?php echo $this->settings['type'] ?>">
		<?php

		$desc = $this->getDesc();
		if ( ! empty( $desc ) && $showDesc ) :
			?>
			<p class='description'><?php echo $desc ?></p>
			<?php
		endif;
	}

	protected function echoOptionHeaderBare() {

		if ( ! $this->echo_wrapper ) {
			if ( $this->getHidden() ) {
				echo '<div style="display: none;">';
			}
			return;
		}

		$id      = $this->getID();
		$name    = $this->getName();
		$evenOdd = self::$rowIndex++ % 2 == 0 ? 'odd' : 'even';
		$style   = $this->getHidden() == true ? 'style="display: none"' : '';

		?>
		<tr valign="top" class="row-<?php echo self::$rowIndex ?> <?php echo $id ?> <?php echo $evenOdd ?>" <?php echo $style ?>>
			<td class="second mm-<?php echo $this->settings['type'] ?>">
		<?php
	}

	protected function echoOptionFooter( $showDesc = true ) {

		if ( ! $this->echo_wrapper ) {
			if ( $this->getHidden() ) {
				echo '</div>';
			}
			return;
		}

		$desc = $this->getDesc();
		if ( ! empty( $desc ) && $showDesc ) :
			?>
			<p class='description'><?php echo $desc ?></p>
			<?php
		endif;

		$example = $this->getExample();
		if ( ! empty( $example ) ) :
			?>
			<p class="description"><code><?php echo htmlentities( $example ) ?></code></p>
			<?php
		endif;

		?>
		</td>
		</tr>
		<?php
	}

	protected function echoOptionFooterBare( $showDesc = true ) {

		if ( ! $this->echo_wrapper ) {
			if ( $this->getHidden() ) {
				echo '</div>';
			}
			return;
		}

		?>
		</td>
		</tr>
		<?php
	}

	/* overridden */
	public function display() {
	}

	/* overridden */
	public function cleanValueForSaving( $value ) {
		return $value;
	}

	/* overridden */
	public function cleanValueForGetting( $value ) {
		if ( is_array( $value ) ) {
			return $value;
		}
		return stripslashes( $value );
	}

}
