<?php

namespace Leadin\admin;

use Leadin\wp\User;
use Leadin\admin\Connection;
use Leadin\admin\ReviewController;
use Leadin\admin\AdminConstants;
use Leadin\admin\ReviewBanner;

/**
 * Class responsible for rendering the admin notices.
 */
class NoticeManager {

	/**
	 * Class constructor, adds the necessary hooks.
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'leadin_action_required_notice' ) );
	}

	/**
	 * Render the disconnected banner.
	 */
	private function leadin_render_disconnected_banner() {
		?>
			<div id="leadin-disconnected-banner" class="leadin-banner notice notice-warning is-dismissible">
				<p>
					<img src="<?php echo esc_attr( LEADIN_PATH . '/assets/images/sprocket.svg' ); ?>" height="16" style="margin-bottom: -3px" />
					&nbsp;
					<?php
						echo sprintf(
							esc_html( __( 'The HubSpot plugin isn’t connected right now. To use HubSpot tools on your WordPress site, %1$sconnect the plugin now%2$s.', 'leadin' ) ),
							'<a class="leadin-banner__link" href="admin.php?page=leadin&bannerClick=true">',
							'</a>'
						);
					?>
				</p>
			</div>
		<?php
	}

	/**
	 * Find what notice (if any) needs to be rendered
	 */
	public function leadin_action_required_notice() {
		$current_screen = get_current_screen();
		if ( User::is_admin() ) {
			if ( self::should_show_disconnected_notice() ) {
				$this->leadin_render_disconnected_banner();
			} elseif ( self::should_show_review_notice() ) {
				$review_banner = new ReviewBanner();
				$review_banner->leadin_render_review_banner();
			}
		}
	}

	/**
	 * Find if disconnected notice should be shown
	 */
	public function should_show_disconnected_notice() {
		$current_screen = get_current_screen();
		return ! Connection::is_connected() && 'leadin' !== $current_screen->parent_base;
	}

	/**
	 * Find if review notice should be shown
	 */
	public function should_show_review_notice() {
		$current_screen = get_current_screen();
		$is_dashboard   = 'index' === $current_screen->parent_base;
		return $is_dashboard && Connection::is_connected() &&
		! ReviewController::is_reviewed_or_skipped() && ReviewController::is_after_introductary_period()
		&& ReviewController::has_contacts_created_since_activation();
	}

}
