<?php
/**
 * Mobile Menu Options class
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Mobile Menu Options  class
 *
 */
class MobileMenuOptions {

	/**
	 * All Mobile Menu instances
	 *
	 * @var array
	 */
	private static $instances = array();

	/**
	 * The current blog id
	 *
	 * @var string
	 */
	private $blogId;

	/**
	 * The current option namespace.
	 * Options will be prefixed with this in the database
	 *
	 * @var string
	 */
	public $optionNamespace;

	/**
	 * All main containers (admin pages, meta boxes, customizer section)
	 *
	 * @var array of MobileMenuAdminPage, MobileMenuMetaBox
	 */
	private $mainContainers = array();

	/**
	 * All Google Font options used. This is for enqueuing Google Fonts for the frontend
	 * TODO Move this to the MobileMenuOptionSelectGooglefont class and let it enqueue from there
	 *
	 * @var array MobileMenuOptionSelectGooglefont
	 */
	private $googleFontsOptions = array();

	/**
	 * We store option ids which should not be created here
	 *
	 * @var array
	 *
	 * @see removeOption()
	 */
	private $optionsToRemove = array();
	private $adminOptions;

	/**
	 * The CSS class instance used
	 *
	 * @var MobileMenuCSS
	 */
	public $cssInstance;

	/**
	 * We store the options (with IDs) here, used for ensuring our serialized option
	 * value doesn't get cluttered with unused options
	 *
	 * @var array
	 */
	public $optionsUsed = array();

	/**
	 * The current list of settings
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Default settings
	 *
	 * @var array
	 */
	private $defaultSettings = array(
		'css' => 'generate',
	);

	/**
	 *
	 * @param string $optionNamespace The namespace to get options from.
	 *
	 * @return MobileMenuOptions
	 */
	public static function getInstance( $optionNamespace ) {

		// Clean namespace.
		$optionNamespace = str_replace( ' ', '-', trim( strtolower( $optionNamespace ) ) );

		foreach ( self::$instances as $instance ) {
			if ( $instance->optionNamespace == $optionNamespace ) {
				return $instance;
			}
		}

		$newInstance = new MobileMenuOptions( $optionNamespace );
		self::$instances[] = $newInstance;
		return $newInstance;
	}


	/**
	 * Gets all active instances of Mobile Menu Options
	 *
	 *
	 * @return array An array of MobileMenuOptions objects
	 */
	public static function getAllInstances() {
		return self::$instances;
	}


	/**
	 * Creates a new MobileMenuOptions object
	 *
	 * @param string $optionNamespace The namespace to get options from.
	 */
	function __construct( $optionNamespace ) {

		// Set current blog
		$this->blogId = get_current_blog_id();

		// Clean namespace.
		$optionNamespace = str_replace( ' ', '-', trim( strtolower( $optionNamespace ) ) );

		$this->optionNamespace = $optionNamespace;
		$this->settings        = $this->defaultSettings;
		$this->cssInstance     = new MobileMenuCSS( $this );

		add_action( 'admin_enqueue_scripts', array( $this, 'loadAdminScripts' ) );
		add_action( 'mm_create_option_mobmenu', array( $this, 'rememberAllOptions' ) );
		
	}

	/**
	 * Action hook on tf_create_option to remember all the options, used to ensure that our
	 * serialized option does not get cluttered with unused options
	 *
	 * @param MobileMenuOption $option The option that was just created.
	 *
	 * @return void
	 */
	public function rememberAllOptions( $option ) {
		if ( ! empty( $option->settings['id'] ) ) {

			if ( is_admin() && isset( $this->optionsUsed[ $option->settings['id'] ] ) ) {
				self::displayError(
					sprintf( __( 'All option IDs per namespace must be unique. The id %s has been used multiple times.', 'mobile-menu' ),
						'<code>' . $option->settings['id'] . '</code>'
					)
				);
			}

			$this->optionsUsed[ $option->settings['id'] ] = $option;
		}
	}


	/**
	 * Loads all the admin scripts used by Mobile Menu Options
	 *
	 * @param string $hook The slug of admin page that called the enqueue.
	 *
	 * @return void
	 */
	public function loadAdminScripts( $hook ) {

		// Get all options panel IDs.
		$panel_ids = array();
		if ( ! empty( $this->mainContainers['admin-page'] ) ) {
			foreach ( $this->mainContainers['admin-page'] as $admin_panel ) {
				$panel_ids[] = $admin_panel->panelID;
			}
		}

		// Only enqueue scripts if we're on a Mobile Menu Options page.
		if ( in_array( $hook, $panel_ids ) || ! empty( $this->mainContainers['meta-box'] ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'mm-serialize', MobileMenuOptions::getURL( '../js/min/serialize-min.js', __FILE__ ) );
			wp_enqueue_script( 'mm-styling', MobileMenuOptions::getURL( '../js/min/admin-styling-min.js', __FILE__ ) );
			wp_enqueue_style( 'mm-admin-styles', MobileMenuOptions::getURL( '../css/admin-styles.css', __FILE__ ) );
		}
	}

	/**
	 * Gets all the admin page options (not meta & customizer) and loads them from the database into
	 * a class variable. This is needed because all our admin page options are contained in a single entry.
	 *
	 * @return array All admin options currently in the instance
	 */
	protected function getInternalAdminOptions() {

		// Reload options if blog has been switched
		if ( empty( $this->adminOptions ) || get_current_blog_id() !== $this->blogId ) {
			$this->adminOptions = array();
		}

		if ( ! empty( $this->adminOptions ) ) {
			return $this->adminOptions;
		}

		// Check if we have options saved already.
		$currentOptions = get_option( 'mobmenu_options' );

		// First time run, this action hook can be used to trigger something.
		if ( false === $currentOptions ) {
			do_action( 'mm_init_no_options_mobmenu' );
		}

		// Put all the available options in our global variable for future checking.
		if ( ! empty( $currentOptions ) && ! count( $this->adminOptions ) ) {
			$this->adminOptions = unserialize( $currentOptions );
		}

		if ( empty( $this->adminOptions ) ) {
			$this->adminOptions = array();
		}

		return $this->adminOptions;
	}


	/**
	 * Gets the admin page option that's loaded into the instance, used by the option class
	 *
	 * @param string $optionName The ID of the option (not namespaced).
	 * @param mixed  $defaultValue The default value to return if the option isn't available yet.
	 *
	 * @return mixed The option value
	 *
	 * @see MobileMenuOption->getValue()
	 */
	public function getInternalAdminPageOption( $optionName, $defaultValue = false ) {

		// Run this first to ensure that adminOptions carries all our admin page options.
		$this->getInternalAdminOptions();

		if ( is_array($this->adminOptions) && array_key_exists( $optionName, $this->adminOptions ) ) {
			return $this->adminOptions[ $optionName ];
		} else {
			return $defaultValue;
		}
	}


	/**
	 * Sets the admin page option that's loaded into the instance, used by the option class.
	 * Doesn't perform a save, only sets the value in the class variable.
	 *
	 * @param string $optionName The ID of the option (not namespaced).
	 * @param mixed  $value The value to set.
	 *
	 * @return bool Always returns true
	 *
	 * @see MobileMenuOption->setValue()
	 */
	public function setInternalAdminPageOption( $optionName, $value ) {

		// Run this first to ensure that adminOptions carries all our admin page options.
		$this->getInternalAdminOptions();

		$this->adminOptions[ $optionName ] = $value;
		return true;
	}


	/**
	 * Saves all the admin (not meta & customizer) options which are currently loaded into this instance
	 *
	 * @return array All admin options currently in the instance
	 */
	public function saveInternalAdminPageOptions() {

		// Run this first to ensure that adminOptions carries all our admin page options.
		$this->getInternalAdminOptions();
		update_option( 'mobmenu_options', serialize( $this->adminOptions ) );

		return $this->adminOptions;
	}


	/**
	 * Create a admin page
	 *
	 * @param array $settings The arguments for creating the admin page.
	 *
	 * @return MobileMenuAdminPage The created admin page
	 */
	public function createAdminPanel( $settings ) {
		return $this->createAdminPage( $settings );
	}

	/**
	 * Create a admin page
	 *
	 * @param array $settings The arguments for creating the admin page.
	 *
	 * @return MobileMenuAdminPage The created admin page
	 */
	public function createAdminPage( $settings ) {
		$settings['type'] = 'admin-page';
		$container = $this->createContainer( $settings );

		return $container;
	}


	/**
	 * Create a meta box
	 *
	 * @param array $settings The arguments for creating the meta box.
	 *
	 * @return MobileMenuMetaBox The created meta box
	 */
	public function createMetaBox( $settings ) {
		$settings['type'] = 'meta-box';
		return $this->createContainer( $settings );
	}

	/**
	 * Creates a container (e.g. admin page, meta box, customizer section) depending
	 * on the `type` parameter given in $settings
	 *
	 * @param array $settings The arguments for creating the container.
	 *
	 * @return MobileMenuAdminPage|MobileMenuMetaBox The created container
	 */
	public function createContainer( $settings ) {
		if ( empty( $settings['type'] ) ) {
			self::displayError( sprintf( __( '%s needs a %s parameter.', 'mobile-menu' ), '<code>' . __FUNCTION__ . '</code>', '<code>type</code>' ) );
			return;
		}

		$type      = strtolower( $settings['type'] );
		$class     = 'MobileMenu' . str_replace( ' ', '', ucfirst( str_replace( '-', ' ', $settings['type'] ) ) );
		$action    = str_replace( '-', '_', $type );
		$container = false;

		if ( ! class_exists( $class ) ) {
			self::displayError( sprintf( __( 'Container of type %s, does not exist.', 'mobile-menu' ), '<code>' . $settings['type'] . '</code>' ) );
			return;
		}

		// Create the container object.
		$container = new $class( $settings, $this );
		if ( empty( $this->mainContainers[ $type ] ) ) {
			$this->mainContainers[ $type ] = array();
		}

		$this->mainContainers[ $type ][] = $container;

		do_action( 'tf_' . $action . '_created_' . $this->optionNamespace, $container );

		return $container;
	}

	/**
	 * Get an option
	 *
	 * @param string $optionName The name of the option.
	 * @param int    $postID The post ID if this is a meta option.
	 *
	 * @return mixed The option value
	 */
	public function getOption( $optionName, $postID = null ) {
		$serialized_options = array( 'mm_woo_menu_font', 'footer_text_font', 'header_banner_font', 'header_menu_font', 'left_menu_font', 'left_menu_copy_font', 'text_after_left_icon_font', 'text_before_right_icon_font', 'right_menu_font' );
		$value              = false;

		if ( count( $this->optionsUsed ) > 5 ) {
			// Get the option value.
			if ( array_key_exists( $optionName, $this->optionsUsed ) ) {
				$option = $this->optionsUsed[ $optionName ];
				$value = $option->getValue( $postID );
			}

			return $value;
		} else {
			$value = $this->getInternalAdminPageOption( $optionName );

			if ( in_array( $optionName, $serialized_options ) ) {
				$value = unserialize( $value );
			}

			return $value;
		}
	}

	/**
	 * Gets a set of options. Pass an associative array containing the option names as keys and
	 * the values you want to be retained if the option names are not implemented.
	 *
	 * @param array $optionArray An associative array containing option names as keys.
	 * @param int   $postID The post ID if this is a meta option.
	 *
	 * @return array An array containing the values saved.
	 *
	 * @see $this->getOption()
	 */
	public function getOptions( $optionArray, $postID = null ) {
		foreach ( $optionArray as $optionName => $originalValue ) {
			if ( array_key_exists( $optionName, $this->optionsUsed ) ) {
				$optionArray[ $optionName ] = $this->getOption( $optionName, $postID );
			}
		}
		return apply_filters( 'tf_get_options_' . $this->optionNamespace, $optionArray, $postID );
	}

	/**
	 * Sets an option
	 *
	 * @param string $optionName The name of the option to save.
	 * @param mixed  $value The value of the option.
	 * @param int    $postID The ID of the parent post if this is a meta box option.
	 *
	 * @return boolean Always returns true
	 */
	public function setOption( $optionName, $value, $postID = null ) {

		// Get the option value.
		if ( array_key_exists( $optionName, $this->optionsUsed ) ) {
			$option = $this->optionsUsed[ $optionName ];
			$option->setValue( $value, $postID );
		}

		do_action( 'tf_set_option_' . $this->optionNamespace, $optionName, $value, $postID );

		return true;
	}

	/**
	 * Generates style rules which can use options as their values
	 *
	 * @param string $CSSString The styles to render.
	 *
	 * @return void
	 */
	public function createCSS( $CSSString ) {
		$this->cssInstance->addCSS( $CSSString );
	}

	/**
	 * Displays an error notice
	 *
	 * @param string       $message The error message to display.
	 * @param array|object $errorObject The object to dump inside the error message.
	 *
	 * @return void
	 */
	public static function displayError( $message, $errorObject = null ) {
		// Clean up the debug object for display. e.g. If this is a setting, we can have lots of blank values.
		if ( is_array( $errorObject ) ) {
			foreach ( $errorObject as $key => $val ) {
				if ( '' === $val ) {
					unset( $errorObject[ $key ] );
				}
			}
		}

		// Display an error message.
		?>
		<div style='margin: 20px; text-align: center;'><strong>Mobile Menu Options Error:</strong>
			<?php echo $message; ?>
			<?php
			if ( ! empty( $errorObject ) ) :
				?>
				<pre><code style="display: inline-block; padding: 10px"><?php echo print_r( $errorObject, true ) ?></code></pre>
				<?php
			endif;
			?>
		</div>
		<?php
	}

	/**
	 *
	 * @param string $script the script to get the url to, relative to $file.
	 * @param string $file the current file, should be __FILE__.
	 *
	 * @return string the url to $script
	 */
	public static function getURL( $script, $file ) {

		$file = str_replace( '\\', '/', $file );
		$url = '';
		$url = plugins_url( $script, $file );

		// Replace /foo/../ with '/'.
		$url = preg_replace( '/\/(?!\.\.)[^\/]+\/\.\.\//', '/', $url );

		return $url;
	}

	/**
	 * Sets a value in the $setting class variable
	 *
	 * @param string $setting The name of the setting.
	 * @param string $value The value to set.
	 *
	 * @return void
	 */
	public function set( $setting, $value ) {
		$oldValue = $this->settings[ $setting ];
		$this->settings[ $setting ] = $value;

		do_action( 'tf_setting_' . $setting . '_changed_' . $this->optionNamespace, $value, $oldValue );
	}

	public function generateCSS() {
		return $this->cssInstance->generateCSS();
	}

}
