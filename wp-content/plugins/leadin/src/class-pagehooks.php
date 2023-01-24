<?php

namespace Leadin;

use Leadin\LeadinFilters;
use Leadin\AssetsManager;
use Leadin\wp\User;
use Leadin\auth\OAuth;
use Leadin\admin\Connection;
use Leadin\options\AccountOptions;
use Leadin\options\LeadinOptions;
use Leadin\utils\ShortcodeRenderUtils;

/**
 * Class responsible of adding the script loader to the website, as well as rendering forms, live chat, etc.
 */
class PageHooks {
	/**
	 * Class constructor, adds the necessary hooks.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_content_type_meta' ) );

		add_action( 'wp_head', array( $this, 'add_page_analytics' ) );
		if ( Connection::is_connected() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'add_frontend_scripts' ) );
		}
		add_filter( 'script_loader_tag', array( $this, 'add_id_to_tracking_code' ), 10, 2 );
		add_filter( 'script_loader_tag', array( $this, 'add_defer_to_forms_script' ), 10, 2 );
		add_shortcode( 'hubspot', array( $this, 'leadin_add_hubspot_shortcode' ) );
	}

	/**
	 * Register meta key for content type
	 */
	public function register_content_type_meta() {
		register_post_meta(
			'',
			'content-type', // meta key.
			array(
				'object_subtype' => 'post', // specify a post type here.
				'type'           => 'string',
				'single'         => true,
				'show_in_rest'   => true,
				'auth_callback'  => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Adds the script loader to the page.
	 */
	public function add_frontend_scripts() {
		if ( LeadinOptions::get( 'disable_internal_tracking' ) && ( is_user_logged_in() || current_user_can( 'install_plugins' ) ) ) {
			return;
		}

		if ( is_single() ) {
			$page_type = 'post';
		} elseif ( is_front_page() ) {
			$page_type = 'home';
		} elseif ( is_archive() ) {
			$page_type = 'archive';
		} elseif ( is_page() ) {
			$page_type = 'page';
		} else {
			$page_type = 'other';
		}

		$leadin_wordpress_info = array(
			'userRole'            => User::get_role(),
			'pageType'            => $page_type,
			'leadinPluginVersion' => LEADIN_PLUGIN_VERSION,
		);

		AssetsManager::enqueue_script_loader( $leadin_wordpress_info );
	}

	/**
	 * Adds the script containing the information needed by the script loader.
	 */
	public function add_page_analytics() {
		$portal_id = AccountOptions::get_portal_id();

		if ( empty( $portal_id ) ) {
			echo '<!-- HubSpot WordPress Plugin v' . esc_html( LEADIN_PLUGIN_VERSION ) . ': embed JS disabled as a portalId has not yet been configured -->';
		} else {
			$content_type = LeadinFilters::get_page_content_type();
			$page_id      = get_the_ID();
			$post_meta    = get_post_meta( $page_id );
			if ( isset( $post_meta['content-type'][0] ) && '' !== $post_meta['content-type'][0] ) {
				$content_type = $post_meta['content-type'][0];
			} elseif ( is_plugin_active( 'elementor/elementor.php' ) ) {
				$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
				$page_settings_model   = $page_settings_manager->get_model( $page_id );
				$content_type          = $page_settings_model->get_settings( 'content_type' );
			}

			?>
			<!-- DO NOT COPY THIS SNIPPET! Start of Page Analytics Tracking for HubSpot WordPress plugin v<?php echo esc_html( LEADIN_PLUGIN_VERSION ); ?>-->
			<script type="text/javascript" class="hsq-set-content-id" data-content-id="<?php echo esc_html( $content_type ); ?>">
				var _hsq = _hsq || [];
				_hsq.push(["setContentType", "<?php echo esc_html( $content_type ); ?>"]);
			</script>
			<!-- DO NOT COPY THIS SNIPPET! End of Page Analytics Tracking for HubSpot WordPress plugin -->
			<?php
		}
	}

	/**
	 * Add the required id to the script loader <script>
	 *
	 * @param String $tag tag name.
	 * @param String $handle handle.
	 */
	public function add_id_to_tracking_code( $tag, $handle ) {
		if ( AssetsManager::TRACKING_CODE === $handle ) {
			$tag = str_replace( "id='" . $handle . "-js'", "async defer id='hs-script-loader'", $tag );
		}
		return $tag;
	}

	/**
	 * Add defer to leadin forms plugin
	 *
	 * @param String $tag tag name.
	 * @param String $handle handle.
	 */
	public function add_defer_to_forms_script( $tag, $handle ) {
		if ( AssetsManager::FORMS_SCRIPT === $handle ) {
			$tag = str_replace( 'src', 'defer src', $tag );
		}
		return $tag;
	}

	/**
	 * Parse leadin shortcodes
	 *
	 * @param array $attributes Shortcode attributes.
	 */
	public function leadin_add_hubspot_shortcode( $attributes ) {
		return ShortcodeRenderUtils::render_shortcode( $attributes );
	}

}
