<?php
/*
 *
 * Require the framework class before doing anything else, so we can use the defined URLs and directories.
 * If you are running on Windows you may have URL problems which can be fixed by defining the framework url first.
 *
 */

if ( ! defined( 'SWEET_OPTIONS_URL' ) ) {
	define( 'SWEET_OPTIONS_URL', get_template_directory_uri() . '/assets/admin/' );
}

$patterns = array(
	'black' => array( 'title' => 'Black', 'img' => SWEET_OPTIONS_URL . 'img/patterns/black_pattern.png' ),
	'blue'  => array( 'title' => 'Blue', 'img' => SWEET_OPTIONS_URL . 'img/patterns/blue_pattern.png' ),
	'gray'  => array( 'title' => 'Gray', 'img' => SWEET_OPTIONS_URL . 'img/patterns/gray_pattern.png' ),
	'green' => array( 'title' => 'Green', 'img' => SWEET_OPTIONS_URL . 'img/patterns/green_pattern.png' ),
	'pink'  => array( 'title' => 'Pink', 'img' => SWEET_OPTIONS_URL . 'img/patterns/pink_pattern.png' ),
	'p1'    => array( 'title' => 'Pattern 1', 'img' => SWEET_OPTIONS_URL . 'img/patterns/pattern1.gif' ),
);

/*
 *
 * Most of your editing will be done in this section.
 *
 * Here you can override default values, uncomment args and change their values.
 * No $args are required, but they can be overridden if needed.
 *
 */
function setup_framework_options() {
	$args = array();

	// Setting dev mode to true allows you to view the class settings/info in the panel.
	// Default: true
	$args['dev_mode'] = false;

	$args['dev_mode_icon_class'] = 'icon-large';

	$args['footer_text'] = '';

	// Set footer/credit line.
	$args['footer_credit'] = '';

	// Setup custom links in the footer for share icons
	$args['share_icons']['twitter']  = array(
		'link'  => 'https://twitter.com/SeventhQueen',
		'title' => esc_html__( 'Follow me on Twitter', 'sweetdate' ),
		'img'   => SWEET_OPTIONS_URL . 'img/social/Twitter.png'
	);
	$args['share_icons']['facebook'] = array(
		'link'  => 'https://www.facebook.com/seventhqueen.themes',
		'title' => esc_html__( 'Find me on Facebook', 'sweetdate' ),
		'img'   => SWEET_OPTIONS_URL . 'img/social/Facebook.png'
	);
	$args['share_icons']['dribbble'] = array(
		'link'  => 'https://dribbble.com/seventhqueen',
		'title' => esc_html__( 'Find me on Dribbble', 'sweetdate' ),
		'img'   => SWEET_OPTIONS_URL . 'img/social/Dribbble.png'
	);


	$args['import_icon_class'] = 'icon-large';

	// Set a custom option name. Don't forget to replace spaces with underscores!
	$args['opt_name'] = KLEO_DOMAIN;

	// Set a custom menu icon.
	$args['menu_icon'] = SWEET_OPTIONS_URL . '/img/sweetdate_menu_icon.jpg';

	// Set a custom title for the options page.
	// Default: Options
	$args['menu_title'] = esc_html__( 'Sweetdate', 'sweetdate' );

	// Set a custom page title for the options page.
	// Default: Options
	$args['page_title'] = esc_html__( 'Sweetdate', 'sweetdate' );

	// Set a custom page slug for options page (wp-admin/themes.php?page=***).
	// Default: kleo_options
	$args['page_slug'] = 'sweetdate_options';

	// Set ANY custom page help tabs, displayed using the new help tab API. Tabs are shown in order of definition.
	$args['help_tabs'][] = array(
		'id'      => 'squeen-opts-1',
		'title'   => esc_html__( '7thQueen Support', 'sweetdate' ),
		'content' => '<p>' .
		             wp_kses_post( sprintf( __( 'Please visit our <a href="%s">support portal</a>', 'sweetdate' ), 'https://my.seventhqueen.com/docs/sweetdate' ) ) .
		             '</p>'
	);

	global $sweetdate_options, $sweetdate_sections;

	if ( class_exists( 'Kleo_Options' ) ) {
		$sweetdate_options = new Kleo_Options( $sweetdate_sections, $args, [] );
	}

}

add_action( 'init', 'setup_framework_options', 0 );


function bp_customize_form( $field, $value ) {
	if ( ! isset( $value ) ) {
		$value = $field['std'];
	}

	echo '<div style="width:45%;float:left"><h4>MAIN FORM</h4>';
	echo '<strong>' . __( 'Text before form', 'sweetdate' ) . '</strong>';
	echo '<textarea name="' . KLEO_DOMAIN . '[' . $field['id'] . '][before_form]" class="large-text" rows="4">' . ( isset( $value['before_form'] ) ? $value['before_form'] : '' ) . '</textarea>';

	echo '<strong>' . __( 'Select your form fields', 'sweetdate' ) . '</strong><br>';
	kleo_selected_form_fields( KLEO_DOMAIN . '[' . $field['id'] . '][fields]', ( isset( $value['fields'] ) ? $value['fields'] : '' ) );
	echo '</div>';

	echo '<div style="width:45%;float:left;margin-left:15px"><h4>HORIZONTAL FORM</h4>';
	echo '<strong>' . __( 'Text before form', 'sweetdate' ) . '</strong>';
	echo '<textarea name="' . KLEO_DOMAIN . '[' . $field['id'] . '][before_form_horizontal]" class="large-text" rows="4">' . ( isset( $value['before_form_horizontal'] ) ? $value['before_form_horizontal'] : '' ) . '</textarea>';

	echo '<strong>' . __( 'Select your form fields', 'sweetdate' ) . '</strong><br>';
	kleo_selected_form_fields( KLEO_DOMAIN . '[' . $field['id'] . '][fields_horizontal]', ( isset( $value['fields_horizontal'] ) ? $value['fields_horizontal'] : '' ) );
	echo '<br><label><input type="checkbox" name="' . KLEO_DOMAIN . '[' . $field['id'] . '][button_show]' . '" ' . ( isset( $value['button_show'] ) && $value['button_show'] == 1 ? 'checked="checked"' : '' ) . ' value="1"> Display search button at end(if not checked then the form will autosubmit on field change)</label>';
	echo '</div>';

	echo '<div style="clear:both"></div>';
	echo '<hr style="border:none;border-bottom:1px solid #DFDFDF;" >';

	$optional = esc_html__( '(optional)', 'sweetdate' );

	echo '<h4>' . sprintf( esc_html__( 'Select Age Range Field %s', 'sweetdate' ), '<span style="font-size:0.8em">' . $optional . '</span>' ) . '</h4>';
	kleo_bp_agerange( KLEO_DOMAIN . '[' . $field['id'] . '][agerange]', ( isset( $value['agerange'] ) ? $value['agerange'] : '' ) );

	echo '<br>' . esc_html__( 'Age label', 'sweetdate' ) . '<br>';
	echo '<input type="text" name="' . KLEO_DOMAIN . '[' . $field['id'] . '][agelabel]" value="' . ( isset( $value['agelabel'] ) ? $value['agelabel'] : '' ) . '">';
	echo '<hr style="border:none;border-bottom:1px solid #DFDFDF;" >';

	echo '<h4>' . sprintf( esc_html__( 'Select Numerical Field %s</h4>', 'sweetdate' ), '<span style="font-size:0.8em">' . $optional . '</span>' ) . '</h4>';
	kleo_bp_numrange( KLEO_DOMAIN . '[' . $field['id'] . '][numrange]', ( isset( $value['numrange'] ) ? $value['numrange'] : '' ) );
	echo '<hr style="border:none;border-bottom:1px solid #DFDFDF;" >';

	echo '<h4>' . sprintf( esc_html__( 'Select Matching Fields %s', 'sweetdate' ), '<span style="font-size:0.8em">' . $optional . '</span>' ) . '</h4>';
	esc_html_e( 'When you search by first field it will look in the matching one. Example: I am a -> Looking for a', 'sweetdate' );
	kleo_bp_numrange( KLEO_DOMAIN . '[' . $field['id'] . '][match1][1]', ( isset( $value['match1'][1] ) ? $value['match1'][1] : '' ) );
	kleo_bp_numrange( KLEO_DOMAIN . '[' . $field['id'] . '][match1][2]', ( isset( $value['match1'][2] ) ? $value['match1'][2] : '' ) );
	echo '<hr style="border:none;border-bottom:1px solid #DFDFDF;" >';

	echo '<br>To show the form you have different options:<br>
        <strong>Shortcode</strong><br>[kleo_search_members] or [kleo_search_members_horizontal]<br> 
        <strong>Widget</strong><br>Go to Appearance -> Widgets to set it up<br>
        <strong>In PHP files</strong><br>Paste the following code where you want the forms to show:<br>
        <code>do_action(\'kleo_bp_search_form\'); </code><br>or<br><code>do_action(\'kleo_search_form_horizontal\');</code>';
}

//Profile fields callback
function bp_profile_field( $field, $value ) {
	if ( ! isset( $value ) ) {
		$value = $field['std'];
	}

	kleo_bp_numrange( KLEO_DOMAIN . '[' . $field['id'] . ']', ( isset( $value ) ? $value : '' ) );
}

//Profile date fields callback
function bp_profile_date_field( $field, $value ) {
	if ( ! isset( $value ) ) {
		$value = $field['std'];
	}

	kleo_bp_agerange( KLEO_DOMAIN . '[' . $field['id'] . ']', ( isset( $value ) ? $value : '' ) );
}

//pmpro settings callback
function pmpro_data_set( $field, $value ) {
	global $kleo_pay_settings, $wpdb;
	$sqlQuery = "SELECT * FROM $wpdb->pmpro_membership_levels ";
	$levels   = $wpdb->get_results( $sqlQuery, OBJECT );
	echo '<table class="membership-settings">';
	foreach ( $kleo_pay_settings as $pays ) :
		?>
		<tr>
			<td scope="row" valign="top">
				<label for="<?php echo esc_attr( $pays['name'] ); ?>"><?php echo esc_html( $pays['title'] ); ?></label>
			</td>
			<td>
				<select id="<?php echo esc_attr( $pays['name'] ); ?>"
				        name="<?php echo KLEO_DOMAIN . '[' . $field['id'] . ']'; ?>[<?php echo esc_attr( $pays['name'] ); ?>][type]"
				        onchange="pmpro_update<?php echo esc_attr( $pays['name'] ); ?>TRs();">
					<option value="0"
					        <?php if ( ! isset( $value[ $pays['name'] ]['type'] ) ) { ?>selected="selected"<?php } ?>><?php esc_html_e( 'No', 'pmpro' ); ?></option>
					<option value="1"
					        <?php if ( isset( $value[ $pays['name'] ]['type'] ) && $value[ $pays['name'] ]['type'] == 1 ) { ?>selected="selected"<?php } ?>><?php esc_html_e( 'Restrict All Members', 'pmpro' ); ?></option>
					<option value="2"
					        <?php if ( isset( $value[ $pays['name'] ]['type'] ) && $value[ $pays['name'] ]['type'] == 2 ) { ?>selected="selected"<?php } ?>><?php esc_html_e( 'Restrict Certain Levels', 'pmpro' ); ?></option>
				</select>
			</td>
		</tr>
		<tr id="<?php echo esc_attr( $pays['name'] ); ?>levels_tr"
		    <?php if ( isset( $value[ $pays['name'] ]['type'] ) && $value[ $pays['name'] ]['type'] != 2 ) { ?>style="display: none;"<?php } ?>>
			<td scope="row" valign="top">
				<label
					for="<?php echo KLEO_DOMAIN . '[' . $field['id'] . ']'; ?>[<?php echo esc_attr( $pays['name'] ); ?>][levels][]"><?php _e( 'Choose Levels to Restrict', 'pmpro' ); ?>
					:</label>
			</td>
			<td>
				<div class="checkbox_box"
				     <?php if ( count( $levels ) > 3 ) { ?>style="height: 100px; overflow: auto;"<?php } ?>>
					<div class="clickable">
						<label>
							<input type="checkbox" id="<?php echo esc_attr( $pays['name'] ); ?>levels_guest"
							       name="<?php echo KLEO_DOMAIN . '[' . $field['id'] . ']'; ?>[<?php echo esc_attr( $pays['name'] ); ?>][guest]"
							       value="1"
							       <?php if ( isset( $value[ $pays['name'] ]['guest'] ) && $value[ $pays['name'] ]['guest'] == 1 ) { ?>checked="checked"<?php } ?>> <?php esc_html_e( "Not logged in", 'sweetdate' ); ?>
						</label></div>
					<div class="clickable">
						<label>
							<input type="checkbox" id="<?php echo esc_attr( $pays['name'] ); ?>levels_not_member"
							       name="<?php echo KLEO_DOMAIN . '[' . $field['id'] . ']'; ?>[<?php echo esc_attr( $pays['name'] ); ?>][not_member]"
							       value="1"
							       <?php if ( isset( $value[ $pays['name'] ]['not_member'] ) && $value[ $pays['name'] ]['not_member'] == 1 ) { ?>checked="checked"<?php } ?>> <?php esc_html_e( "Not members", 'sweetdate' ); ?>
						</label>
					</div>
					<?php
					if ( isset( $value[ $pays['name'] ]['levels'] ) ) {
						if ( ! is_array( $value[ $pays['name'] ]['levels'] ) ) {
							$value[ $pays['name'] ]['levels'] = explode( ",", $value[ $pays['name'] ]['levels'] );
						}
					} else {
						$value[ $pays['name'] ]['levels'] = array();
					}
					foreach ( $levels as $level ) {
						?>
						<div class="clickable">
							<label>
								<input type="checkbox"
								       id="<?php echo esc_attr( $pays['name'] ); ?>levels_<?php echo esc_attr( $level->id ); ?>"
								       name="<?php echo KLEO_DOMAIN . '[' . $field['id'] . ']'; ?>[<?php echo esc_attr( $pays['name'] ); ?>][levels][]"
								       value="<?php echo esc_attr( $level->id ); ?>"
								       <?php if ( in_array( $level->id, $value[ $pays['name'] ]['levels'] ) ) { ?>checked="checked"<?php } ?>> <?php echo esc_html( $level->name ); ?>
							</label>
						</div>
						<?php
					}
					?>
				</div>
			</td>
		</tr>
		<tr class="td-bottom-border">
			<td scope="row" valign="top">
				<label><?php esc_html_e( "Show field in memberships table", 'sweetdate' ); ?></label>
			</td>
			<td>
				<select
					name="<?php echo KLEO_DOMAIN . '[' . $field['id'] . ']'; ?>[<?php echo esc_attr( $pays['name'] ); ?>][showfield]">
					<option value="1"
					        <?php if ( isset( $value[ $pays['name'] ]['showfield'] ) && $value[ $pays['name'] ]['showfield'] != 2 ) { ?>selected="selected"<?php } ?>><?php _e( 'Yes', 'pmpro' ); ?></option>
					<option value="2"
					        <?php if ( isset( $value[ $pays['name'] ]['showfield'] ) && $value[ $pays['name'] ]['showfield'] == 2 ) { ?>selected="selected"<?php } ?>><?php _e( 'No', 'pmpro' ); ?></option>
				</select>
			</td>
		</tr>

		<script>
			function pmpro_update<?php echo esc_attr( $pays['name'] );?>TRs() {
				var <?php echo esc_attr( $pays['name'] );?> =
				jQuery('#<?php echo esc_attr( $pays['name'] ); ?>').val();
				if ( <?php echo esc_attr( $pays['name'] );?> == 2
			)
				{
					jQuery('#<?php echo esc_attr( $pays['name'] ); ?>levels_tr').show();
				}
			else
				{
					jQuery('#<?php echo esc_attr( $pays['name'] ); ?>levels_tr').hide();
				}

				if ( <?php echo esc_attr( $pays['name'] );?> >
				0
			)
				{
					jQuery('#<?php echo esc_attr( $pays['name'] ); ?>_explanation').show();
				}
			else
				{
					jQuery('#<?php echo esc_attr( $pays['name'] ); ?>_explanation').hide();
				}
			}

			pmpro_update<?php echo esc_attr( $pays['name'] ); ?>TRs();
		</script>
	<?php endforeach; ?>
	<tr>
		<td scope="row" valign="top">
			<label><?php esc_html_e( "Popular level", 'sweetdate' ); ?></label>
		</td>
		<td>
			<select name="<?php echo KLEO_DOMAIN . '[' . esc_attr( $field['id'] ) . ']'; ?>[kleo_membership_popular]">
				<option value='0'><?php esc_html_e( "None", 'pmpro' ); ?></option>
				<?php
				if ( $levels ) {
					foreach ( $levels as $level ) {
						?>
						<option value="<?php echo esc_attr( $level->id ); ?>"
						        <?php if ( $level->id == $value['kleo_membership_popular'] ) { ?>selected="selected"<?php } ?>>
							<?php echo esc_html( $level->name ); ?>
						</option>
						<?php
					}
				}
				?>
			</select>
		</td>
	</tr>

	<?php

	echo '</table>';
}
