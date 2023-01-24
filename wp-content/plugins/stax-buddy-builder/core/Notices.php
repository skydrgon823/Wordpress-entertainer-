<?php
/**
 * Add plugin notices.
 *
 * @package Buddy_Builder
 * @since 1.0.0
 */

namespace Buddy_Builder;

defined( 'ABSPATH' ) || die();

final class Notices extends Singleton {

	/**
	 * Notices constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_bpb_admin_notice_viewed', [ __CLASS__, 'bpb_admin_notice_viewed' ] );
		add_action( 'admin_post_bpb_admin_notice_viewed', [ __CLASS__, 'bpb_admin_notice_viewed' ] );
	}

	/**
	 * Mark notice as viewed.
	 *
	 * @return void
	 */
	public function bpb_admin_notice_viewed() {
		$notice_id = ! empty( $_REQUEST['notice_id'] ) ? sanitize_text_field( $_REQUEST['notice_id'] ) : '';

		if ( ! $notice_id ) {
			wp_die();
		}

		update_user_meta( get_current_user_id(), $notice_id, 1 );

		if ( ! wp_doing_ajax() ) {
			wp_safe_redirect( admin_url() );
			die;
		}

		wp_die();
	}

	/**
	 * Check if noticed was viewed
	 *
	 * @param string $notice_id
	 * @return boolean
	 */
	private function has_user_viewed_notice( $notice_id ) {
		return get_user_meta( get_current_user_id(), $notice_id, false );
	}

	/**
	 * Has one week passed
	 *
	 * @return boolean
	 */
	private function one_week_passed() {
		$install_time        = Plugin::get_instance()->get_install_time();
		$after_one_week_time = $install_time + 604800;

		if ( $after_one_week_time < time() ) {
			return true;
		}

		return false;
	}

	/**
	 * Elementor not installed notice
	 */
	public function elementor_notice() {
		$class = 'notice notice-warning';
		/* translators: %s: html tags */
		$message = sprintf( __( '%1$sBuddyBuilder%2$s requires %1$sElementor%2$s plugin installed & activated.', 'stax-buddy-builder' ), '<strong>', '</strong>' );

		$plugin = 'elementor/elementor.php';

		if ( Helpers::get_instance()->is_plugin_installed( $plugin ) ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$action_url   = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
			$button_label = __( 'Activate Elementor', 'stax-buddy-builder' );

		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$action_url   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
			$button_label = __( 'Install Elementor', 'stax-buddy-builder' );
		}

		$button = '<p><a href="' . $action_url . '" class="button-primary">' . $button_label . '</a></p><p></p>';

		printf( '<div class="%1$s"><p>%2$s</p>%3$s</div>', esc_attr( $class ), $message, $button );
	}

	/**
	 * Displays notice on the admin dashboard if Elementor version is lower than the
	 * required minimum.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function minimum_elementor_version_notice() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		if ( isset( $_GET['activate'] ) ) { // WPCS: CSRF ok, input var ok.
			unset( $_GET['activate'] ); // WPCS: input var ok.
		}

		$message = sprintf(
			'<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">'
			/* translators: 1: Plugin name 2: Elementor */
			. esc_html__( '%1$s requires version %3$s or greater of %2$s plugin.', 'stax-buddy-builder' )
			. '</span>',
			'<strong>' . esc_html__( 'BuddyBuilder', 'stax-buddy-builder' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'stax-buddy-builder' ) . '</strong>',
			Plugin::$minimum_elementor_version
		);

		$file_path   = 'elementor/elementor.php';
		$update_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );

		$message .= sprintf(
			'<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">' .
			'<a class="button-primary" href="%1$s">%2$s</a></span>',
			$update_link,
			esc_html__( 'Update Elementor Now', 'stax-buddy-builder' )
		);

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
	}

	/**
	 * Buddypress not installed notice
	 */
	public function buddypress_notice() {
		$class = 'notice notice-warning';
		/* translators: %s: html tags */
		$message = sprintf( __( '%1$sBuddyBuilder%2$s requires %1$sBuddyPress%2$s plugin installed & activated.', 'stax-buddy-builder' ), '<strong>', '</strong>' );

		$plugin = 'buddypress/bp-loader.php';

		if ( Helpers::get_instance()->is_plugin_installed( $plugin ) ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$action_url   = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
			$button_label = __( 'Activate BuddyPress', 'stax-buddy-builder' );

		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$action_url   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=buddypress' ), 'install-plugin_buddypress' );
			$button_label = __( 'Install BuddyPress', 'stax-buddy-builder' );
		}

		$button = '<p><a href="' . $action_url . '" class="button-primary">' . $button_label . '</a></p><p></p>';

		printf( '<div class="%1$s"><p>%2$s</p>%3$s</div>', esc_attr( $class ), $message, $button );
	}

	/**
	 * Upgrade DB notice
	 */
	public function buddybuilder_upgrade_db_notice() {
		?>
		<div class="notice notice-warning">
			<p>
				<?php echo wp_kses_post( __( '<strong>BuddyBuilder - BuddyPress Builder for Elementor</strong> needs to update your database to the latest version. Please make sure to create a backup first.', 'stax-buddy-builder' ) ); ?>
			</p>
			<p>
				<?php echo wp_kses_post( sprintf( __( '<a href="%s" class="button-primary">Update now</a>', 'stax-buddy-builder' ), wp_nonce_url( add_query_arg( 'buddybuilder_db_update', '' ), 'action' ) ) ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Upgrade DB success notice
	 */
	public function buddybuilder_upgrade_db_success_notice() {
		?>
		<div class="notice notice-success">
			<p><?php esc_html_e( 'Awesome! The database has been updated to the latest version!', 'stax-buddy-builder' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Upgrade DB failed notice
	 */
	public function buddybuilder_upgrade_db_failed_notice() {
		?>
		<div class="notice notice-warning">
			<p><?php esc_html_e( 'Something went wrong. Please check your server logs or contact StaxWP.', 'stax-buddy-builder' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Rate us notification
	 *
	 * @return void
	 */
	public function rate_us_notice() {
		$notice_id = 'bpb_rate_us';

		if ( ! current_user_can( 'manage_options' ) || $this->has_user_viewed_notice( $notice_id ) ) {
			return;
		}

		if ( ! $this->one_week_passed() ) {
			return;
		}

		$dismiss_url = add_query_arg(
			[
				'action'    => 'bpb_admin_notice_viewed',
				'notice_id' => esc_attr( $notice_id ),
			],
			admin_url( 'admin-post.php' )
		);

		?>
		<div class="notice bpb-notice-box bpb-notice-info bpb-dismissable" data-notice_id="<?php echo esc_attr( $notice_id ); ?>">
			<div class="bpb-notice-container">
				<div class="bpb-notice-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 66.75 78.81"><defs><style>.cls-1{fill:#2f3565;}.cls-2{fill:#03a9f4;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="StaxWP"><path class="cls-1" d="M62.48,42.82c-.45-.46-1-1-1.67-1.6C57.7,38.34,53,34,53,29v-.11a8.71,8.71,0,0,1,2.88-6.32c4.38-4.3,6.65-8.59,6.65-12.4a8.7,8.7,0,0,0-.66-3.38C60.17,2.73,55.38,0,49.9,0H11.72l-6,.14L4.59,3.31A11,11,0,0,1,5.68,4.67c1.81,2.77,1.51,6.68,1.38,8.69-1,15.58-5.28,35.46-7,45.83a6.61,6.61,0,0,0-.08,1,2,2,0,0,0,.32,1.24c.41.48,1.45.74,3,.74H31c6.15,0,13.78,0,16-3.33a4.77,4.77,0,0,0,.68-2.66A16.87,16.87,0,0,0,46,49.8c-4.25-9.39-14-13.13-14.15-13.17a2,2,0,0,0-.43-.07,7.82,7.82,0,0,0-1.18.18l-.44.06a13.81,13.81,0,0,1-1.82.14A13.38,13.38,0,1,1,41.34,23.55h0a12.58,12.58,0,0,1-.09,1.5,4.11,4.11,0,0,1-.07.47c0,.33-.1.65-.18,1s-.09.36-.13.53-.17.59-.26.88l-.19.53c-.12.29-.24.59-.37.84s-.15.32-.23.47c-.16.3-.32.59-.5.87l-.21.35a13.23,13.23,0,0,1-.84,1.13l-.15.16a11,11,0,0,1-.81.87l-.37.34-.68.59c-.06,0-.1.08-.09.13s0,.07.08.11A30.38,30.38,0,0,1,49.63,48.16a20.16,20.16,0,0,1,2.09,8.05A8.72,8.72,0,0,1,50.37,61c-3,4.64-10.07,5.11-16.89,5.16H31.26c-4.84,0-13.8-.31-19.92-.6a13.62,13.62,0,0,0-1,5.86,20.36,20.36,0,0,0,.28,3.21q.21,1.32.54,2.61l.26.89,1.6.65a7.47,7.47,0,0,0,.79-.6,5,5,0,0,1,1-.59,8.24,8.24,0,0,1,1.37-.48,11.39,11.39,0,0,1,2-.31c.83-.07,1.78-.12,2.91-.12s4.3-.09,9.16,0c5,.14,12.66.24,12.66.24C54.52,77,62.06,69,65,62.14a21.33,21.33,0,0,0,1.78-8.33A15.62,15.62,0,0,0,62.48,42.82ZM21.79,41.58c3.66,4.83-1.62,4.35-4.56,2.07C13.81,41,16.33,34.55,21.79,41.58ZM13.92,57.14c-7.38,3.21-15.68-6.62.66-9.26C25.73,46.21,20.26,54.38,13.92,57.14Z"/><path class="cls-2" d="M28,14.22a9.36,9.36,0,1,0,9.38,9.35h0A9.38,9.38,0,0,0,28,14.22Z"/></g></g></svg>
				</div>
				<div class="bpb-notice-content">
					<p><strong><?php echo __( 'We\'re happy when you\'re happy!', 'elementor' ); ?></strong> <?php _e( 'It\'s been a while since you\'re using BuddyBuilder and we are really grateful for that! You can boost our morale and confidence by leaving a five star review on WordPress.org.', 'stax-buddy-builder' ); ?></p>
					<p>
						<a href="https://staxwp.com/go/buddybuilder-rate-us/" target="_blank" class="button button-primary"><?php _e( 'Sure, will do', 'stax-buddy-builder' ); ?></a>
						<a href="<?php echo esc_url_raw( $dismiss_url ); ?>" class="button bpb-dismiss-button"><?php _e( 'Hide', 'stax-buddy-builder' ); ?></a>
					</p>
				</div>
			</div>
		</div>
		<?php
	}

}
