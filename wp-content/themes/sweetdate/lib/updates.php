<?php
/**
 * Theme Update logic
 */

remove_action( 'admin_notices', 'pmpro_license_nag' );

$translation_update = false;
if ( get_option( 'sweetdate-30' ) != '1' ) {
	/* Disable font settings */
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	update_option( 'elementor_page_title_selector', '.article-title' );

	update_option( 'sweetdate-30', '1' );
} else {
	$translation_update = true;
}

$installed_version = get_option( 'sweetdate-update' );

if ( ! $installed_version ) {
	if ( version_compare( $installed_version, '3.3.2', '<' ) ) {
		//rtmedia
		update_option( 'rtmedia_inspirebook_release_notice', 'hide' );
		update_option( 'rtmedia_premium_addon_notice', 'hide' );
		update_option( 'rtmedia-update-template-notice-v3_9_4', 'hide' );
		update_site_option( 'install_transcoder_admin_notice', '0' );

		update_option( 'sweetdate-update', '3.3.2' );
	}

	if ( version_compare( $installed_version, '3.4.6', '<=' ) ) {
		update_option( 'elementor_page_title_selector', '.article-title' );

		update_option( 'sweetdate-update', '3.4.6' );
	}

} else {
	$translation_update = true;
}

if ( $translation_update ) {
	sweetdate_333_translation_set_update();
} else {
	sweetdate_333_translation_set_update( 'off' );
}

function sweetdate_333_translation_set_update( $status = 'on' ) {
	if ( get_option( 'sweetdate-translation-update' ) ) {
		return;
	}
	update_option( 'sweetdate-translation-update', $status );
}

/**
 * Admin notices
 */

add_action( 'admin_notices', 'sweetdate_admin_notices' );
function sweetdate_admin_notices() {
	if ( current_user_can( 'list_users' ) ) {
		sweetdate_rename_mo_files();
	}
}

function sweetdate_rename_mo_files() {

	/* applies to old themes only */
	if ( ! get_option( 'sweetdate-translation-update' ) || get_option( 'sweetdate-translation-update' ) === 'off' ) {
		return;
	}

	$option_name = 'sweetdate_mo_files_49';
	$site_option = get_option( $option_name );

	if ( ! $site_option || 'hide' !== $site_option ) {
		update_option( $option_name, 'show' );

		?>
		<div class="updated sweetdate-translation-notice">
			<p>
				<span>
					<?php
					$message = esc_html__( 'Sweetdate needs to update your translation files saved under wp-content/languages/themes to match the new translation domain. Please create a backup of your files and database first.', 'sweetdate' );
					?>
					<?php echo wp_kses_post( $message ); ?>
					<?php
					$link = add_query_arg( array( 'sweetdate-update-domain' => '' ) );
					echo '<a href="' . esc_url( $link ) . '">' . esc_html__( 'Update', 'sweetdate' ) . '</a>';
					?>
				</span>
				<a href="#"
				   onclick="sweetdate_hide_translation_notice('<?php echo esc_js( wp_create_nonce( 'sweetdate_notice' ) ); ?>');"
				   style="float:right"><?php esc_html_e( 'Dismiss', 'sweetdate' ); ?></a>
			</p>
		</div>
		<?php

		?>
		<script type="text/javascript">
			function sweetdate_hide_translation_notice(nonce) {
				var data = {action: 'sweetdate_update_translation_notice', _sweetdate_nonce: nonce};
				jQuery.post(ajaxurl, data, function (response) {
					response = response.trim();

					if (response === "1")
						jQuery('.sweetdate-translation-notice').remove();
				});
			}
		</script>
		<?php
	}
}

add_action( 'admin_init', 'sweetdate_update_translation_files' );
function sweetdate_update_translation_files() {

	if ( ! isset( $_GET['sweetdate-update-domain'] ) || ! current_user_can( 'list_users' ) ) {
		return;
	}

	$option_name = 'sweetdate_mo_files_49';
	$site_option = get_option( $option_name );

	if ( ! $site_option || 'hide' !== $site_option ) {

		//WPML String translation plugin
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			global $wpdb;
			$wpdb->query( "UPDATE `" . $wpdb->prefix . "icl_strings` SET `domain_name_context_md5`=MD5(CONCAT(`context`,`name`,`gettext_context`)), `context`='sweetdate' WHERE `context`='kleo_framework'" );
		}

		// File languages rename
		$file_list      = list_files( WP_CONTENT_DIR . '/languages/themes' );
		$loco_file_list = list_files( WP_CONTENT_DIR . '/languages/loco/themes' );
		if ( ! empty( $loco_file_list ) ) {
			$file_list = array_merge( $file_list, $loco_file_list );
		}

		if ( ! empty( $file_list ) ) {
			foreach ( $file_list as $item ) {
				if ( strpos( $item, 'kleo_framework' ) !== false ) {
					$new_path = str_replace( 'kleo_framework', 'sweetdate', $item );
					copy( $item, $new_path );
				}
			}
		}

		update_option( $option_name, 'hide' );
	}
}

/*
 * Hide notice
 */

add_action( 'wp_ajax_sweetdate_update_translation_notice', 'sweetdate_update_translation_notice' );
function sweetdate_update_translation_notice() {
	if ( check_ajax_referer( 'sweetdate_notice', '_sweetdate_nonce' ) ) {
		update_option( 'sweetdate_mo_files_49', 'hide' );
		echo '1';
	} else {
		echo '0';
	}
	die();
}
