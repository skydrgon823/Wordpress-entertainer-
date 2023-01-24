<?php

namespace WPForms\Admin\Education;

/**
 * Education core.
 *
 * @since 1.6.6
 */
class Core {

	/**
	 * Indicate if Education core is allowed to load.
	 *
	 * @since 1.6.6
	 *
	 * @return bool
	 */
	public function allow_load() {

		return wp_doing_ajax() || wpforms_is_admin_page() || wpforms_is_admin_page( 'builder' );
	}

	/**
	 * Init.
	 *
	 * @since 1.6.6
	 */
	public function init() {

		// Only proceed if allowed.
		if ( ! $this->allow_load() ) {
			return;
		}

		$this->hooks();
	}

	/**
	 * Hooks.
	 *
	 * @since 1.6.6
	 */
	protected function hooks() {

		if ( wp_doing_ajax() ) {
			add_action( 'wp_ajax_wpforms_education_dismiss', [ $this, 'ajax_dismiss' ] );

			return;
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueues' ] );
	}

	/**
	 * Load enqueues.
	 *
	 * @since 1.6.6
	 */
	public function enqueues() {

		$min = wpforms_get_min_suffix();

		wp_enqueue_script(
			'wpforms-admin-education-core',
			WPFORMS_PLUGIN_URL . "assets/js/components/admin/education/core{$min}.js",
			[ 'jquery', 'jquery-confirm' ],
			WPFORMS_VERSION,
			true
		);

		wp_localize_script(
			'wpforms-admin-education-core',
			'wpforms_education',
			(array) apply_filters( 'wpforms_admin_education_strings', $this->get_js_strings() )
		);
	}

	/**
	 * Localize common strings.
	 *
	 * @since 1.6.6
	 *
	 * @return array
	 */
	protected function get_js_strings() {

		$strings = [];

		$strings['ok']       = esc_html__( 'Ok', 'wpforms-lite' );
		$strings['cancel']   = esc_html__( 'Cancel', 'wpforms-lite' );
		$strings['close']    = esc_html__( 'Close', 'wpforms-lite' );
		$strings['ajax_url'] = admin_url( 'admin-ajax.php' );
		$strings['nonce']    = wp_create_nonce( 'wpforms-education' );

		$strings['upgrade'] = [
			'pro'   => [
				'title'   => esc_html__( 'is a PRO Feature', 'wpforms-lite' ),
				'message' => '<p>' . esc_html__( 'We\'re sorry, the %name% is not available on your plan. Please upgrade to the PRO plan to unlock all these awesome features.', 'wpforms-lite' ) . '</p>',
				'doc'     => '<a href="https://wpforms.com/docs/upgrade-wpforms-lite-paid-license/?utm_source=WordPress&amp;utm_medium=link&amp;utm_campaign=liteplugin&amp;utm_content=upgrade-pro" target="_blank" rel="noopener noreferrer" class="already-purchased">' . esc_html__( 'Already purchased?', 'wpforms-lite' ) . '</a>',
				'button'  => esc_html__( 'Upgrade to PRO', 'wpforms-lite' ),
				'url'     => wpforms_admin_upgrade_link( 'builder-modal', 'upgrade-pro' ),
				'modal'   => wpforms_get_upgrade_modal_text( 'pro' ),
			],
			'elite' => [
				'title'   => esc_html__( 'is an Elite Feature', 'wpforms-lite' ),
				'message' => '<p>' . esc_html__( 'We\'re sorry, the %name% is not available on your plan. Please upgrade to the Elite plan to unlock all these awesome features.', 'wpforms-lite' ) . '</p>',
				'doc'     => '<a href="https://wpforms.com/docs/upgrade-wpforms-lite-paid-license/?utm_source=WordPress&amp;utm_medium=link&amp;utm_campaign=liteplugin&amp;utm_content=upgrade-elite" target="_blank" rel="noopener noreferrer" class="already-purchased">' . esc_html__( 'Already purchased?', 'wpforms-lite' ) . '</a>',
				'button'  => esc_html__( 'Upgrade to Elite', 'wpforms-lite' ),
				'url'     => wpforms_admin_upgrade_link( 'builder-modal', 'upgrade-elite' ),
				'modal'   => wpforms_get_upgrade_modal_text( 'elite' ),
			],
		];

		$strings['upgrade_bonus'] = wpautop(
			wp_kses(
				__( '<strong>Bonus:</strong> WPForms Lite users get <span>50% off</span> regular price, automatically applied at checkout.', 'wpforms-lite' ),
				[
					'strong' => [],
					'span'   => [],
				]
			)
		);

		return $strings;
	}

	/**
	 * Ajax handler for the education dismiss buttons.
	 *
	 * @since 1.6.6
	 */
	public function ajax_dismiss() {

		// Run a security check.
		check_ajax_referer( 'wpforms-education', 'nonce' );

		// Check for permissions.
		if ( ! wpforms_current_user_can() ) {
			wp_send_json_error(
				[ 'error' => esc_html__( 'You do not have permission to perform this action.', 'wpforms-lite' ) ]
			);
		}

		$dismissed = get_user_meta( get_current_user_id(), 'wpforms_dismissed', true );

		if ( empty( $dismissed ) ) {
			$dismissed = [];
		}

		// Section is the identifier of the education feature.
		// For example: in Builder/DidYouKnow feature used 'builder-did-you-know-notifications' and 'builder-did-you-know-confirmations'.
		$section = ! empty( $_POST['section'] ) ? sanitize_key( wp_unslash( $_POST['section'] ) ) : '';

		if ( empty( $section ) ) {
			wp_send_json_error(
				[ 'error' => esc_html__( 'Please specify a section.', 'wpforms-lite' ) ]
			);
		}

		$dismissed[ 'edu-' . $section ] = time();

		update_user_meta( get_current_user_id(), 'wpforms_dismissed', $dismissed );
		wp_send_json_success();
	}
}
