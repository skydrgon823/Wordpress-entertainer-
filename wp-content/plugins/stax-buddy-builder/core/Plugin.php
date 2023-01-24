<?php
/**
 * Add plugin main class.
 *
 * @package Buddy_Builder
 * @since 1.0.0
 */

namespace Buddy_Builder;

defined( 'ABSPATH' ) || die();

/**
 * Plugin class.
 *
 * @since 1.0.0
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
final class Plugin {

	/**
	 * Plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var Plugin
	 */
	public static $instance;

	/**
	 * Modules.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var object
	 */
	public $modules = [];

	/**
	 * The plugin name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $plugin_name;

	/**
	 * The plugin version number.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $plugin_version;

	/**
	 * The minimum Elementor version number required.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $minimum_elementor_version = '2.0.0';

	/**
	 * The plugin directory.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $plugin_path;

	/**
	 * The plugin URL.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $plugin_url;

	/**
	 * The plugin assets URL.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $plugin_assets_url;

	/**
	 * Disables class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'stax-buddy-builder' ), BPB_VERSION );
	}

	/**
	 * Disables unserializing of the class.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'stax-buddy-builder' ), BPB_VERSION );
	}

	/**
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @return Plugin An instance of the class.
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();

			do_action( 'buddy_builder/loaded' );
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __construct() {

		if ( ! defined( 'YOUZER_LATE_LOAD' ) ) {
			define( 'YOUZER_LATE_LOAD', 2 );
		}

		add_action( 'plugins_loaded', [ $this, 'load_plugin' ], 0 );
		add_action( 'bp_enqueue_scripts', [ $this, 'bp_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'front_css' ], 12 );

		include_once BPB_BASE_PATH . 'core/Singleton.php';

		include_once BPB_BASE_PATH . 'core/Helpers.php';
		include_once BPB_BASE_PATH . 'core/Notices.php';
		include_once BPB_BASE_PATH . 'core/Upgrades.php';

		Helpers::get_instance();
		Notices::get_instance();
		Upgrades::get_instance();

		register_activation_hook( BPB_FILE, [ $this, 'set_default_bp_data' ] );
	}

	/**
	 * Set default Buddypress data
	 */
	public function set_default_bp_data() {
		$settings = get_option( 'bp_nouveau_appearance', [] );

		$settings['group_front_page'] = 0;
		$settings['user_front_page']  = 0;

		update_option( 'bp_nouveau_appearance', $settings );
	}

	/**
	 * Checks Elementor version compatibility.
	 *
	 * First checks if Elementor is installed and active,
	 * then checks Elementor version compatibility.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function load_plugin() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			add_action( 'admin_notices', [ Notices::get_instance(), 'elementor_notice' ] );

			return;
		}

		if ( ! function_exists( 'bp_is_active' ) ) {
			add_action( 'admin_notices', [ Notices::get_instance(), 'buddypress_notice' ] );

			return;
		}

		// Check for the minimum required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::$minimum_elementor_version, '>=' ) ) {
			add_action( 'admin_notices', [ Notices::get_instance(), 'minimum_elementor_version_notice' ] );
		}

		spl_autoload_register( [ $this, 'autoload' ] );

		$this->define_constants();
		$this->load_components();
		$this->add_hooks();

		do_action( 'buddy_builder/init' );

		/*
		 * @deprecated
		 * @use buddy_builder/init
		 */
		do_action( 'buddybuilder_init' );

		add_action( 'admin_notices', [ Notices::get_instance(), 'rate_us_notice' ] );
	}

	/**
	 * Load compatibility
	 */
	public function load_compat() {
		require_once self::$plugin_path . 'core/compat/index.php';
	}

	/**
	 * Load components
	 */
	public function load_components() {
		require_once self::$plugin_path . 'functions.php';

		// Admin pages
		include_once self::$plugin_path . '/admin/Admin.php';
	}

	/**
	 * Autoload classes based on namespace.
	 *
	 * @param string $class Name of class.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function autoload( $class ) {
		// Return if Buddy_Builder name space is not set.
		if ( false === strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		/**
		 * Prepare filename.
		 *
		 * @todo Refactor to use preg_replace.
		 */
		$filename = str_replace(
			[ __NAMESPACE__ . '\\', '\\', '_' ],
			[
				'',
				DIRECTORY_SEPARATOR,
				'-',
			],
			$class
		);
		$filename = __DIR__ . '/' . strtolower( $filename ) . '.php';

		// Return if file is not found.
		if ( ! is_readable( $filename ) ) {
			return;
		}

		include $filename;
	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_constants() {
		self::$plugin_path       = trailingslashit( plugin_dir_path( BPB_FILE ) );
		self::$plugin_url        = trailingslashit( plugin_dir_url( BPB_FILE ) );
		self::$plugin_assets_url = trailingslashit( self::$plugin_url . 'assets' );
	}

	/**
	 * Adds required hooks.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function add_hooks() {
		include_once BPB_BASE_PATH . 'core/hooks/ElementorHooks.php';
		include_once BPB_BASE_PATH . 'core/hooks/BuddypressHooks.php';
		include_once BPB_BASE_PATH . 'core/hooks/CustomizerHooks.php';

		ElementorHooks::get_instance();
		BuddypressHooks::get_instance();
		CustomizerHooks::get_instance();

		add_action( 'plugins_loaded', [ $this, 'load_compat' ], 12 );
	}

	/**
	 * Elements
	 *
	 * @return array
	 */
	public function get_elements() {
		$elements = [];

		// Groups directory

		$elements['groups-directory/Listing'] = [
			'name'     => 'bpb-groups-directory-list',
			'class'    => 'GroupsDirectory\Listing',
			'template' => 'groups-directory',
		];

		$elements['groups-directory/Filters'] = [
			'name'     => 'bpb-groups-directory-filters',
			'class'    => 'GroupsDirectory\Filters',
			'template' => 'groups-directory',
		];

		$elements['groups-directory/Navigation'] = [
			'name'     => 'bpb-groups-directory-navigation',
			'class'    => 'GroupsDirectory\Navigation',
			'template' => 'groups-directory',
		];

		// Group profile

		$elements['profile-group/Name'] = [
			'name'     => 'bpb-profile-group-name',
			'class'    => 'ProfileGroup\Name',
			'template' => 'group-profile',
		];

		$elements['profile-group/Avatar'] = [
			'name'     => 'bpb-profile-group-avatar',
			'class'    => 'ProfileGroup\Avatar',
			'template' => 'group-profile',
		];

		$elements['profile-group/Buttons'] = [
			'name'     => 'bpb-profile-group-buttons',
			'class'    => 'ProfileGroup\Buttons',
			'template' => 'group-profile',
		];

		$elements['profile-group/Cover'] = [
			'name'     => 'bpb-profile-group-cover',
			'class'    => 'ProfileGroup\Cover',
			'template' => 'group-profile',
		];

		$elements['profile-group/Description'] = [
			'name'     => 'bpb-profile-group-description',
			'class'    => 'ProfileGroup\Description',
			'template' => 'group-profile',
		];

		$elements['profile-group/Leadership'] = [
			'name'     => 'bpb-profile-group-leadership',
			'class'    => 'ProfileGroup\Leadership',
			'template' => 'group-profile',
		];

		$elements['profile-group/LastActivity'] = [
			'name'     => 'bpb-profile-group-last-activity',
			'class'    => 'ProfileGroup\LastActivity',
			'template' => 'group-profile',
		];

		$elements['profile-group/Navigation'] = [
			'name'     => 'bpb-profile-group-navigation',
			'class'    => 'ProfileGroup\Navigation',
			'template' => 'group-profile',
		];

		$elements['profile-group/Status'] = [
			'name'     => 'bpb-profile-group-status',
			'class'    => 'ProfileGroup\Status',
			'template' => 'group-profile',
		];

		$elements['profile-group/Content'] = [
			'name'     => 'bpb-profile-group-content',
			'class'    => 'ProfileGroup\Content',
			'template' => 'group-profile',
		];

		// Members directory

		$elements['members-directory/Listing'] = [
			'name'     => 'bpb-members-directory-list',
			'class'    => 'MembersDirectory\Listing',
			'template' => 'members-directory',
		];

		$elements['members-directory/Filters'] = [
			'name'     => 'bpb-members-directory-filters',
			'class'    => 'MembersDirectory\Filters',
			'template' => 'members-directory',
		];

		$elements['members-directory/Navigation'] = [
			'name'     => 'bpb-members-directory-navigation',
			'class'    => 'MembersDirectory\Navigation',
			'template' => 'members-directory',
		];

		// Member profile

		$elements['profile-member/Avatar'] = [
			'name'     => 'bpb-profile-member-avatar',
			'class'    => 'ProfileMember\Avatar',
			'template' => 'member-profile',
		];

		$elements['profile-member/Cover'] = [
			'name'     => 'bpb-profile-member-cover',
			'class'    => 'ProfileMember\Cover',
			'template' => 'member-profile',
		];

		$elements['profile-member/Buttons'] = [
			'name'     => 'bpb-profile-member-buttons',
			'class'    => 'ProfileMember\Buttons',
			'template' => 'member-profile',
		];

		$elements['profile-member/Content'] = [
			'name'     => 'bpb-profile-member-content',
			'class'    => 'ProfileMember\Content',
			'template' => 'member-profile',
		];

		$elements['profile-member/Meta'] = [
			'name'     => 'bpb-profile-member-meta',
			'class'    => 'ProfileMember\Meta',
			'template' => 'member-profile',
		];

		$elements['profile-member/Username'] = [
			'name'     => 'bpb-profile-member-username',
			'class'    => 'ProfileMember\Username',
			'template' => 'member-profile',
		];

		$elements['profile-member/Navigation'] = [
			'name'     => 'bpb-profile-member-navigation',
			'class'    => 'ProfileMember\Navigation',
			'template' => 'member-profile',
		];

		$elements['profile-member/LastActivity'] = [
			'name'     => 'bpb-profile-member-last-activity',
			'class'    => 'ProfileMember\LastActivity',
			'template' => 'member-profile',
		];

		// Sitewide activity

		$elements['sitewide-activity/Form'] = [
			'name'     => 'bpb-sitewide-form',
			'class'    => 'Sitewide\Form',
			'template' => 'sitewide-activity',
		];

		$elements['sitewide-activity/Filters'] = [
			'name'     => 'bpb-sitewide-filters',
			'class'    => 'Sitewide\Filters',
			'template' => 'sitewide-activity',
		];

		$elements['sitewide-activity/Content'] = [
			'name'     => 'bpb-sitewide-content',
			'class'    => 'Sitewide\Content',
			'template' => 'sitewide-activity',
		];

		$elements['sitewide-activity/Navigation'] = [
			'name'     => 'bpb-sitewide-navigation',
			'class'    => 'Sitewide\Navigation',
			'template' => 'sitewide-activity',
		];

		// General

		$elements['general/MembersListing'] = [
			'name'  => 'bpb-general-members-list',
			'class' => 'General\MembersListing',
		];

		$elements['general/GroupsListing'] = [
			'name'  => 'bpb-general-groups-list',
			'class' => 'General\GroupsListing',
		];

		$elements['general/ActivityListing'] = [
			'name'  => 'bpb-general-activity-list',
			'class' => 'General\ActivityListing',
		];

		foreach ( $elements as &$element ) {
			$element['template_base_path']   = BPB_BASE_PATH . '/core/widgets/';
			$element['class_base_namespace'] = '\Buddy_Builder\Widgets\\';
		}

		return apply_filters( 'buddy_builder/get_elements', $elements );
	}

	/**
	 * Enqueue Front CSS
	 */
	public function front_css() {
		$min = '.min';

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$min = '';
		}

		wp_register_style(
			'stax-buddy-builder-front',
			BPB_ASSETS_URL . 'css/index' . $min . '.css',
			[ 'stax-buddy-builder-bp' ],
			BPB_VERSION
		);

		if ( bpb_is_elementor_editor() ) {
			wp_enqueue_style(
				'stax-buddy-builder-avatar',
				buddypress()->plugin_url . 'bp-core/css/avatar' . $min . '.css',
				[],
				BPB_VERSION
			);
		}

		if ( ! bp_is_blog_page() ) {
			wp_enqueue_style( 'dashicons' );
		}

		if ( isset( $_GET['elementor-preview'] ) ||
			 bpb_is_edit_frame() ||
			 bpb_is_preview_mode() ||
			 bpb_is_front_library()
		) {
			wp_enqueue_style( 'stax-buddy-builder-front' );
		}
	}

	/**
	 * Enqueue Front CSS
	 */
	public function bp_scripts() {
		$min = '.min';

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$min = '';
		}

		wp_register_style(
			'stax-buddy-builder-bp',
			BPB_BASE_URL . 'templates/buddypress/css/buddypress' . $min . '.css',
			[],
			BPB_VERSION
		);
		wp_register_script(
			'bpb-grid-list-view',
			BPB_ASSETS_URL . 'js/grid-list-view.js',
			BPB_VERSION,
			true
		);
	}

	/**
	 * @param $texts
	 *
	 * @return false|string
	 */
	public function go_pro_template( $texts ) {
		ob_start();

		?>
		<div class="elementor-nerd-box">
			<div class="elementor-nerd-box-title"><?php echo $texts['title']; ?></div>
			<?php foreach ( $texts['messages'] as $message ) : ?>
				<div class="elementor-nerd-box-message"><?php echo $message; ?></div>
			<?php endforeach; ?>

			<?php if ( $texts['link'] ) : ?>
				<a class="elementor-button elementor-panel-scheme-title" href="<?php echo $texts['link']; ?>"
				   target="_blank">
					<?php echo __( 'Go PRO', 'stax-buddy-builder' ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Get install time
	 *
	 * @return int
	 */
	public function get_install_time() {
		$installed_time = get_option( '_bpb_installed_time' );

		if ( ! $installed_time ) {
			$installed_time = time();

			update_option( '_bpb_installed_time', $installed_time );
		}

		return (int) $installed_time;
	}

}
