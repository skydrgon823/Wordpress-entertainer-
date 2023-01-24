<?php
/**
 * Strong Testimonials admin functions.
 *
 * 1. Check for required WordPress version.
 * 2. Check for plugin update.
 * 3. Initialize.
 */

/**
 * Check for required WordPress version.
 */
function wpmtst_version_check() {
	global $wp_version;
	$require_wp_version = "3.7";

	if ( version_compare( $wp_version, $require_wp_version ) == -1 ) {
		deactivate_plugins( WPMTST_PLUGIN );
		/* translators: %s is the name of the plugin. */
		$message = '<h2>' . sprintf( esc_html_x( 'Unable to load %s', 'installation', 'strong-testimonials' ), 'Strong Testimonials' ) . '</h2>';
		/* translators: %s is a WordPress version number. */
		$message .= '<p>' . sprintf( _x( 'This plugin requires <strong>WordPress %s</strong> or higher so it has been deactivated.', 'installation', 'strong-testimonials' ), $require_wp_version ) . '</p>';
		$message .= '<p>' . esc_html_x( 'Please upgrade WordPress and try again.', 'installation', 'strong-testimonials' ) . '</p>';
		$message .= '<p>' . sprintf( _x( 'Back to the WordPress <a href="%s">Plugins page</a>', 'installation', 'strong-testimonials' ), get_admin_url( null, 'plugins.php' ) ) . '</p>';
		wp_die( $message );
	}
}

add_action( 'admin_init', 'wpmtst_version_check', 1 );

/**
 * Check for plugin update.
 *
 * @since 2.28.4 Before other admin_init actions.
 */
function wpmtst_update_check() {
	$version = get_option( 'wpmtst_plugin_version', false );
	if ( $version == WPMTST_VERSION ) {
		return;
	}
	// Redirect to About page afterwards. On new install or (de)activation only.
	if ( false === $version ) {
		add_option( 'wpmtst_do_activation_redirect', true );
	}

	Strong_Testimonials_Updater::update();
}

add_action( 'admin_init', 'wpmtst_update_check', 5 );

/**
 * Initialize.
 */
function wpmtst_admin_init() {
	/**
	 * Redirect to About page for new installs only
	 *
	 * @since 2.28.4
	 */
	wpmtst_activation_redirect();

	/**
	 * Custom action hooks
	 *
	 * @since 1.18.4
	 */
	if ( isset( $_REQUEST['action'] ) && '' != $_REQUEST['action'] ) {
		$action = sanitize_text_field( $_REQUEST['action'] );
		do_action( 'wpmtst_' . $action );
	}
}

add_action( 'admin_init', 'wpmtst_admin_init' );

/**
 * Custom action link in admin notice.
 *
 * @since 2.29.0
 */
function wpmtst_action_captcha_options_changed() {
	wpmtst_delete_admin_notice( 'captcha-options-changed' );
    wp_redirect( admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings&tab=form#captcha-section' ) );
    exit;
}

add_action( 'admin_action_captcha-options-changed', 'wpmtst_action_captcha_options_changed' );

/**
 * Redirect to About page.
 *
 * @since 2.28.4
 */
function wpmtst_activation_redirect() {
	if ( get_option( 'wpmtst_do_activation_redirect', false ) ) {
		delete_option( 'wpmtst_do_activation_redirect' );
		wp_redirect( admin_url( 'index.php?page=wpmtst-getting-started' ) );
		exit;
	}
}

/**
 * Are we on a testimonial admin screen?
 *
 * Used by add-ons too!
 *
 * @return bool
 */
function wpmtst_is_testimonial_screen() {
	$screen = get_current_screen();
	return ( $screen && 'wpm-testimonial' == $screen->post_type );
}

/**
 * Add pending numbers to post types on admin menu.
 * Thanks http://wordpress.stackexchange.com/a/105470/32076
 *
 * @param $menu
 *
 * @return mixed
 */
function wpmtst_pending_indicator( $menu ) {
	if ( ! current_user_can( 'edit_posts' ) )
		return $menu;

	$options = get_option( 'wpmtst_options' );
	if ( ! isset( $options['pending_indicator'] ) || ! $options['pending_indicator'] )
		return $menu;

	$types  = array( 'wpm-testimonial' );
	$status = 'pending';
	foreach ( $types as $type ) {
		$num_posts     = wp_count_posts( $type, 'readable' );
		$pending_count = 0;
		if ( ! empty( $num_posts->$status ) )
			$pending_count = $num_posts->$status;

		if ( $type == 'post' )
			$menu_str = 'edit.php';
		else
			$menu_str = 'edit.php?post_type=' . $type;

		foreach ( $menu as $menu_key => $menu_data ) {
			if ( $menu_str != $menu_data[2] )
				continue;
			$menu[ $menu_key ][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n( $pending_count ) . '</span></span>';
		}
	}

	return $menu;
}
add_filter( 'add_menu_classes', 'wpmtst_pending_indicator' );

/**
 * The [restore default] icon.
 *
 * @param $for
 *
 * @since 2.18.0
 */
function wpmtst_restore_default_icon( $for ) {
	if ( !$for ) return;
	?>
	<input type="button" class="button secondary restore-default"
		   title="<?php esc_html_e( 'restore default', 'strong-testimonials' ); ?>"
		   value="&#xf171"
		   data-for="<?php echo esc_attr( $for ); ?>"/>
	<?php
}



/**
 * Check for configuration issues when options are updated.
 *
 * @since 2.24.0
 * @param $option
 * @param $old_value
 * @param $value
 */
function wpmtst_updated_option( $option, $old_value, $value ) {
	if ( 'wpmtst_' == substr( $option, 0, 7 ) ) {
		do_action( 'wpmtst_check_config' );
	}
}
add_action( 'updated_option', 'wpmtst_updated_option', 10, 3 );


/**
 * Store configuration error.
 *
 * @since 2.24.0
 * @param $key
 */
function wpmtst_add_config_error( $key ) {
	$errors = get_option( 'wpmtst_config_errors', array() );
	$errors[] = $key;
	update_option( 'wpmtst_config_errors', array_unique( $errors ), 'no' );

	wpmtst_add_admin_notice( $key, true );
}


/**
 * Delete configuration error.
 *
 * @since 2.24.0
 * @param $key
 */
function wpmtst_delete_config_error( $key ) {
	$errors = get_option( 'wpmtst_config_errors', array() );
	$errors = array_diff( $errors, array ( $key ) );
	update_option( 'wpmtst_config_errors', $errors, 'no' );

	wpmtst_delete_admin_notice( $key );
}


/**
 * Save a View.
 *
 * @param        $view
 * @param string $action
 * @usedby Strong_Testimonials_Update:update_views
 *
 * @return bool|false|int
 */
function wpmtst_save_view( $view, $action = 'edit' ) {
	global $wpdb;

	if ( ! $view ) return false;

	$table_name = $wpdb->prefix . 'strong_views';
	$serialized = serialize( $view['data'] );

	if ( 'add' == $action || 'duplicate' == $action ) {
		$sql = "INSERT INTO {$table_name} (name, value) VALUES (%s, %s)";
		$sql = $wpdb->prepare( $sql, $view['name'], $serialized );
		$wpdb->query( $sql );
		$view['id'] = $wpdb->insert_id;
		$return  = $view['id'];
	}
	else {
		$sql = "UPDATE {$table_name} SET name = %s, value = %s WHERE id = %d";
		$sql = $wpdb->prepare( $sql, $view['name'], $serialized, intval( $view['id'] ) );
		$wpdb->query( $sql );
		$return = $wpdb->last_error ? 0 : 1;
	}

	do_action( 'wpmtst_view_saved', $view );

	return $return;
}


/**
 * @param $field
 *
 * @return mixed
 */
function wpmtst_get_field_label( $field ) {
	if ( isset( $field['field'] ) ) {
		$custom_fields = wpmtst_get_custom_fields();
		if ( isset( $custom_fields[ $field['field'] ]['label'] ) ) {
			return $custom_fields[ $field['field'] ]['label'];
		}
		$builtin_fields = wpmtst_get_builtin_fields();
		if ( isset( $builtin_fields[ $field['field'] ]['label'] ) ) {
			return $builtin_fields[ $field['field'] ]['label'];
		}
	}

	return '';
}

function wpmtst_get_field_text( $field ) {
	if ( isset( $field['field'] ) ) {
		$custom_fields = wpmtst_get_custom_fields();
		if ( isset( $custom_fields[ $field['field'] ]['text'] ) ) {
			return $custom_fields[ $field['field'] ]['text'];
		}
		$builtin_fields = wpmtst_get_builtin_fields();
		if ( isset( $builtin_fields[ $field['field'] ]['text'] ) ) {
			return $builtin_fields[ $field['field'] ]['text'];
		}
	}

	return '';
}


/**
 * @param string $field_name
 *
 * @return mixed
 */
function wpmtst_get_field_by_name( $field_name = '' ) {
	if ( $field_name ) {
		$custom_fields = wpmtst_get_custom_fields();
		if ( isset( $custom_fields[ $field_name ] ) ) {
			return $custom_fields[ $field_name ];
		}
	}

	return '';
}

/**
 * Returns true if at least one extension is installed
 */
function wpmtst_extensions_installed() {

	if ( defined( 'WPMTST_CUSTOM_FIELDS_VERSION' ) ) {
		return true;
	}
	if ( defined( 'WPMTST_ASSIGNMENT_VERSION' ) ) {
		return true;
	}
	if ( defined( 'WPMTST_MULTIPLE_FORMS_VERSION' ) ) {
		return true;
	}
	if ( defined( 'WPMTST_PROPERTIES_VERSION' ) ) {
		return true;
	}
	if ( defined( 'WPMTST_COUNTRY_SELECTOR_VERSION' ) ) {
		return true;
	}
	if ( defined( 'WPMTST_REVIEW_MARKUP_VERSION' ) ) {
		return true;
	}
	if ( defined( 'WPMTST_ADVANCED_VIEWS_VERSION' ) ) {
		return true;
	}

	return false;
}