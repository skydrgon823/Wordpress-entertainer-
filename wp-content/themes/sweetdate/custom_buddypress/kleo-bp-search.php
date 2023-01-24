<?php
/**
 * Custom Members Search for Buddypress
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.3
 */

add_action( 'kleo_bp_search_form', 'kleo_bp_search_form' );

/* Render search form */
if ( ! function_exists( 'kleo_bp_search_form' ) ):

	/**
	 * Renders a members profile search form
	 * @global array $bp_search_fields Options form Theme options
	 *
	 * @param bool $show_profiles Display members carouses under the form
	 * @param string $before_form Text to display before the form fields
	 *
	 * @return string
	 */
	function kleo_bp_search_form( $show_profiles = false, $before_form = false ) {
		global $bp_search_fields;
		if ( function_exists( 'bp_is_active' ) && bp_is_active( 'xprofile' ) ) {
		} else {
			echo esc_html__( "Please enable Buddypress and Extended profiles component for the search form!", 'sweetdate' );

			return;
		}

		if ( ! isset( $bp_search_fields['fields'] ) || count( (array) $bp_search_fields['fields'] ) == 0 ) {
			echo "<p>" . esc_html__( "Please configure your form fields under Sweetdate - Buddypress", 'sweetdate' ) . "</p>";

			return;
		}

		?>
		<!--Form wrapper-->
		<div class="form-wrapper">
			<div class="form-header">
				<?php if ( $before_form || ( isset( $bp_search_fields['before_form'] ) && ! empty( $bp_search_fields['before_form'] ) ) ) : ?>
					<p class="lead"><?php if ( $before_form ) {
							echo sweet_translate_dynamic( $before_form, 'sweetdate' );
						} else {
							echo sweet_translate_dynamic( $bp_search_fields['before_form'], 'sweetdate', false );
						} ?></p>
				<?php endif; ?>
			</div>
			<!--Search form-->
			<form action="<?php echo bp_get_root_domain() . '/' . bp_get_members_root_slug() . '/' ?>" method="get"
			      class="<?php echo apply_filters( 'bp_search_extra_class', 'custom' ); ?> form-search">
				<?php

				foreach ( $bp_search_fields['fields'] as $sf ) {
					$fname     = 'field_' . $sf;
					$posted    = isset( $_GET[ $fname ] ) ? $_GET[ $fname ] : '';
					$posted_to = isset( $_GET[ $fname . '_to' ] ) ? $_GET[ $fname . '_to' ] : '';

					$field   = new BP_XProfile_Field ( $sf );
					$options = $field->get_children();
					if ( isset( $bp_search_fields['agerange'] ) && $sf == $bp_search_fields['agerange'] ) {
						$from = ( $posted == '' ) ? sq_option( 'buddypress_age_start' ) : (int) $posted;
						$to   = ( $posted_to == '' ) ? sq_option( 'buddypress_age_end' ) : (int) $posted_to;
						if ( $to < $from ) {
							$to = $from;
						}

						echo '<div class="row">
                  <div class="five mobile-one columns">';
						echo "<label  class='right inline'>" . sweet_translate_dynamic( ( isset( $bp_search_fields['agelabel'] ) ? $bp_search_fields['agelabel'] : $field->name ), 'sweetdate' ) . "</label>";
						echo '</div>';
						echo '<div class="three mobile-one columns">
                    <select name="' . $fname . '" class="expand customDropdown">';
						echo apply_filters( 'kleo_bp_searchform_before_all_li', '<option value=""> </option>' );
						for ( $i = sq_option( 'buddypress_age_start' ); $i <= sq_option( 'buddypress_age_end' ); $i ++ ) {
							echo '<option value="' . $i . '" ' . get_selected( $fname, $i, $from ) . ' >' . $i . '</option>';
						}
						echo '</select>
                  </div>
                  <div class="one mobile-one columns text-center">
                    <label class="inline"> - </label>
                  </div>';
						echo '<div class="three mobile-one columns">
                    <select name="' . $fname . '_to" class="expand customDropdown">';
						echo apply_filters( 'kleo_bp_searchform_before_all_li', '<option value=""> </option>' );
						for ( $i = sq_option( 'buddypress_age_start' ); $i <= sq_option( 'buddypress_age_end' ); $i ++ ) {
							echo '<option value="' . $i . '" ' . get_selected( $fname . '_to', $i, $to ) . ' >' . $i . '</option>';
						}
						echo ' </select>
                  </div>
                </div><!--end row-->';
						continue;
					}

					if ( $sf == $bp_search_fields['numrange'] ) {
						$from = ( $posted == '' && $posted_to == '' ) ? '' : (float) $posted;
						$to   = ( $posted_to == '' ) ? $from : (float) $posted_to;
						if ( $to < $from ) {
							$to = $from;
						}

						echo '<div class="row">
                  <div class="five mobile-one columns">';
						echo "<label class='right inline'>" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "</label>";
						echo '</div>';
						echo '<div class="three mobile-one columns">
                  <input type="text" name="' . $fname . '" value="' . $from . '" >
                  </div>
                  <div class="one mobile-one columns text-center">
                    <label class="inline"> - </label>
                  </div>';
						echo '<div class="three mobile-one columns">
                      <input type="text" name="' . $fname . '_to" value="' . $to . '" >
                  </div>
                </div><!--end row-->';

						continue;
					}

					echo '<div class="row">';


					switch ( $field->type ):

						case 'textarea':
							$value = esc_attr( stripslashes( $posted ) );
							echo "<div class='five mobile-four columns'><label class='right inline' for='$fname'>" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "</label></div>";
							echo "<div class='seven mobile-four columns kleo-textarea'><input type='text' name='$fname' id='$fname' value='$value' /></div>";
							break;

						case 'selectbox':
							echo "<div class='five mobile-four columns'><label class='right inline' for='$fname'>" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "</label></div>";
							echo "<div class='seven mobile-four columns kleo-selectbox'><select class='expand' name='$fname' id='$fname'>";
							echo "<option value=''> </option>";
							foreach ( $options as $option ) {
								$option->name = trim( $option->name );
								$value        = esc_attr( stripslashes( $option->name ) );
								$selected     = ( $option->name == $posted || $option->is_default_option == 1 ) ? "selected='selected'" : "";
								echo "<option $selected value='$value'>" . sweet_translate_dynamic( $value, 'sweetdate' ) . "</option>";
							}
							echo "</select></div>";
							break;

						case 'multiselectbox':
							echo "<div class='five mobile-four columns'><label class='right inline' for='$fname'>" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "</label></div>";
							echo "<div class='seven mobile-four columns kleo-multiselectbox'><select " . apply_filters( 'kleo_bp_search_multiselect_attributes', "multiple='multiple' data-customforms='disabled'" ) . " class='expand' name='{$fname}[]' id='$fname'>";
							foreach ( $options as $option ) {
								$option->name = trim( $option->name );
								$value        = esc_attr( stripslashes( $option->name ) );
								$selected     = ( in_array( $option->name, (array) $posted ) || $option->is_default_option == 1 ) ? "selected='selected'" : "";
								echo "<option $selected value='$value'>" . sweet_translate_dynamic( $value, 'sweetdate' ) . "</option>";
							}
							echo "</select></div>";
							break;

						case 'radio':
							echo "<div class='five mobile-four columns'><label class='right'>" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "<br>";
							echo "<a class='clear-value-" . esc_attr( $fname ) . "' href='#'><small class='kleo-clear-radio'><i class='icon icon-remove'></i> " . esc_html__( 'Clear', 'sweetdate' ) . "</small></a>";
							?>
							<script type="text/javascript">jQuery('.clear-value-<?php echo esc_attr( $fname );?>').click(function () {
									jQuery('input[name=<?php echo esc_attr( $fname );?>]').attr('checked', false);
									jQuery('.field_<?php echo esc_attr( $fname );?> .custom.radio').removeClass('checked');
									return false;
								});</script>
							<?php
							echo "</label></div>";
							echo "<div class='seven mobile-four columns kleo-radio field_" . esc_attr( $fname ) . "'>";

							foreach ( $options as $option ) {
								$option->name = trim( $option->name );
								$value        = esc_attr( stripslashes( $option->name ) );
								$selected     = ( $option->name == $posted || $option->is_default_option == 1 ) ? "checked='checked'" : "";
								echo "<label><input $selected type='radio' name='$fname' value='$value'> " . sweet_translate_dynamic( $value, 'sweetdate' ) . "</label>";
							}
							echo '</div>';
							break;

						case 'checkbox':
							echo "<div class='five mobile-four columns'><label class='right'>" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "</label></div>";
							echo "<div class='seven mobile-four columns kleo-checkbox'>";

							foreach ( $options as $option ) {
								$option->name = trim( $option->name );
								$value        = esc_attr( stripslashes( $option->name ) );
								$selected     = ( in_array( $option->name, (array) $posted ) || $option->is_default_option == 1 ) ? "checked='checked'" : "";
								echo "<label><input $selected type='checkbox' name='{$fname}[]' value='$value'> " . sweet_translate_dynamic( $value, 'sweetdate' ) . "</label>";
							}
							echo '</div>';
							break;

						case 'textbox':
						default:
							$value = esc_attr( stripslashes( $posted ) );
							echo "<div class='five mobile-four columns'><label class='right inline' for='$fname'>" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "</label></div>";
							echo "<div class='seven mobile-four columns kleo-text'><input type='text' name='$fname' id='$fname' value='$value' /></div>";
							break;

					endswitch;
					echo '</div><!--end row-->';
				}

				echo "<input type='hidden' name='bs' value=' ' />";
				do_action( 'kleo_bp_search_add_data' );
				?>
				<div class="row">
					<div class="seven offset-by-five columns">
						<button class="button radius"><i class="icon-search"></i>
							&nbsp;<?php _e( "SEARCH", 'sweetdate' ); ?></button>
					</div>
				</div>
				<span class="notch"></span>
			</form>
			<!--end search form-->

			<?php if ( $show_profiles ): ?>
				<!--Form footer-->
				<div class="form-footer">

					<?php do_action( 'kleo_bps_before_carousel' ); ?>

					<?php echo do_shortcode( '[kleo_members_carousel]' ); ?>

				</div><!--end form-footer-->
			<?php else: ?>
				<?php $main_color_rgb = hexToRGB( sq_option( 'button_bg_color_hover' ) ); ?>
				<div class="form-footer"
				     style="border-left:none;border-right: none;padding:0;background: <?php echo sq_option( 'button_bg_color' ); ?>; <?php echo 'border-bottom: 10px solid rgba(' . $main_color_rgb['r'] . ', ' . $main_color_rgb['g'] . ', ' . $main_color_rgb['b'] . ', 0.3)'; ?>"></div>

			<?php endif; ?>

		</div><!--end form-wrapper-->

		<?php
	}
endif;


/* Render search form horizontal */
add_action( 'kleo_search_form_horizontal', 'kleo_bp_search_form_horizontal', 10, 2 );

if ( ! function_exists( 'kleo_bp_search_form_horizontal' ) ):
	function kleo_bp_search_form_horizontal( $show_button = true, $before_form = false ) {
		global $bp_search_fields;
		if ( ! function_exists( 'bp_is_active' ) || ! bp_is_active( 'xprofile' ) ) {
			echo apply_filters( 'kleo_search_horizontal_no_buddypress', '' );

			return;
		}

		if ( ! isset( $bp_search_fields['fields_horizontal'] ) || count( (array) $bp_search_fields['fields_horizontal'] ) == 0 ) {
			echo apply_filters( 'kleo_search_horizontal_no_fields', '' );

			return;
		}

		?>
		<div id="search-bar">
			<div class="row">
				<!--Search form-->
				<form id="horizontal_search"
				      action="<?php echo bp_get_root_domain() . '/' . bp_get_members_root_slug() . '/' ?>" method="get"
				      class="<?php echo apply_filters( 'bp_search_horiz_extra_class', 'custom' ); ?> dir-form twelve columns custom">
					<div class="row">

						<?php if ( $before_form || ( isset( $bp_search_fields['before_form_horizontal'] ) && ! empty( $bp_search_fields['before_form_horizontal'] ) ) ) : ?>
							<div class="two columns">
								<label class="bp_search_form_filter"><?php if ( $before_form ) {
										echo sweet_translate_dynamic( $before_form, 'sweetdate' );
									} else {
										echo sweet_translate_dynamic( $bp_search_fields['before_form_horizontal'], 'sweetdate', false );
									} ?></label>
							</div>
						<?php endif; ?>

						<?php
						$count = 0;
						foreach ( $bp_search_fields['fields_horizontal'] as $sf ) {
							$count ++;


							$fname     = 'field_' . $sf;
							$posted    = isset( $_GET[ $fname ] ) ? $_GET[ $fname ] : '';
							$posted_to = isset( $_GET[ $fname . '_to' ] ) ? $_GET[ $fname . '_to' ] : '';

							$field   = new BP_XProfile_Field ( $sf );
							$options = $field->get_children();

							if ( isset( $bp_search_fields['agerange'] ) && $sf == $bp_search_fields['agerange'] ) {
								$from = ( $posted == '' ) ? '' : (int) $posted;
								$to   = ( $posted_to == '' ) ? '' : (int) $posted_to;
								if ( $to != '' && $to < $from ) {
									$to = $from;
								}

								echo '<div class="two columns hz-agerange hz-from">
                                <select name="' . $fname . '" class="expand customDropdown">';
								echo '<option value="">' . sweet_translate_dynamic( ( isset( $bp_search_fields['agelabel'] ) ? $bp_search_fields['agelabel'] : $field->name ), 'sweetdate' ) . ' ' . esc_html__( 'from', 'sweetdate' ) . '</option>';
								for ( $i = sq_option( 'buddypress_age_start' ); $i <= sq_option( 'buddypress_age_end' ); $i ++ ) {
									echo '<option value="' . $i . '" ' . get_selected( $fname, $i, $from ) . ' >' . $i . '</option>';
								}
								echo '</select>
                              </div>';

								echo '<div class="two columns hz-agerange hz-to">
                                <select name="' . $fname . '_to" class="expand customDropdown">';
								echo '<option value="">' . sweet_translate_dynamic( ( isset( $bp_search_fields['agelabel'] ) ? $bp_search_fields['agelabel'] : $field->name ), 'sweetdate' ) . ' ' . esc_html__( 'to', 'sweetdate' ) . '</option>';
								for ( $i = sq_option( 'buddypress_age_start' ); $i <= sq_option( 'buddypress_age_end' ); $i ++ ) {
									echo '<option value="' . $i . '" ' . get_selected( $fname . '_to', $i, $to ) . ' >' . $i . '</option>';
								}
								echo ' </select>
                              </div>';
								continue;
							}

							if ( $sf == $bp_search_fields['numrange'] ) {
								$from = ( $posted == '' && $posted_to == '' ) ? '' : (float) $posted;
								$to   = ( $posted_to == '' ) ? $from : (float) $posted_to;
								if ( $to < $from ) {
									$to = $from;
								}


								echo '<div class="two columns hz-numrange hz-from">
                              <input type="text" name="' . $fname . '" value="' . $from . '" placeholder="' . sweet_translate_dynamic( $field->name, 'sweetdate' ) . ' ' . esc_html__( 'from', 'sweetdate' ) . '" >
                              </div>';
								echo '<div class="two columns hz-numrange hz-to">
                                  <input type="text" name="' . $fname . '_to" value="' . $to . '" placeholder="' . sweet_translate_dynamic( $field->name, 'sweetdate' ) . ' ' . esc_html__( 'to', 'sweetdate' ) . '" >
                              </div>';

								continue;
							}

							switch ( $field->type ):

								case 'textarea':
									$value = esc_attr( stripslashes( $posted ) );
									echo "<div class='two columns hz-textarea'><input type='text' name='$fname' id='$fname' value='$value' placeholder='" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "' /></div>";
									break;

								case 'selectbox':
									echo "<div class='two columns hz-select'><select class='expand' name='$fname' id='$fname'>";
									echo "<option value=''>" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "</option>";
									foreach ( $options as $option ) {
										$option->name = trim( $option->name );
										$value        = esc_attr( stripslashes( $option->name ) );
										$selected     = ( $option->name == $posted ) ? "selected='selected'" : "";
										echo "<option $selected value='$value'>" . sweet_translate_dynamic( $value, 'sweetdate' ) . "</option>";
									}
									echo "</select></div>";
									break;

								case 'multiselectbox':
									echo "<div class='two columns hz-multiselect'><select " . apply_filters( 'kleo_bp_search_multiselect_attributes', "multiple='multiple' data-customforms='disabled'" ) . " class='expand' name='{$fname}[]' id='$fname'>";
									echo "<option value=''>" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "</option>";
									foreach ( $options as $option ) {
										$option->name = trim( $option->name );
										$value        = esc_attr( stripslashes( $option->name ) );
										$selected     = ( in_array( $option->name, (array) $posted ) ) ? "selected='selected'" : "";
										echo "<option $selected value='$value'>" . sweet_translate_dynamic( $value, 'sweetdate' ) . "</option>";
									}
									echo "</select></div>";
									break;

								case 'radio':
									echo "<div class='two columns hz-radio'>";
									echo "<label>" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "</label>";
									echo '</div>';

									foreach ( $options as $option ) {
										$option->name = trim( $option->name );
										$value        = esc_attr( stripslashes( $option->name ) );
										$selected     = ( $option->name == $posted ) ? "checked='checked'" : "";
										echo "<div class='two columns bglabel'>";
										echo "<label><input $selected type='radio' name='$fname' value='$value'> " . sweet_translate_dynamic( $value, 'sweetdate' ) . "</label>";
										echo '</div>';
									}
									break;

								case 'checkbox':
									echo "<div class='two columns hz-checkbox-name'>";
									echo "<label>" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "</label>";
									echo '</div>';

									foreach ( $options as $option ) {
										$option->name = trim( $option->name );
										$value        = esc_attr( stripslashes( $option->name ) );
										$selected     = ( in_array( $option->name, (array) $posted ) ) ? "checked='checked'" : "";
										echo "<div class='two columns bglabel hz-checkbox'>";
										echo "<label><input $selected type='checkbox' name='{$fname}[]' value='$value'> " . sweet_translate_dynamic( $value, 'sweetdate' ) . "</label>";
										echo '</div>';
									}
									break;

								case 'textbox':
								default:
									$value = esc_attr( stripslashes( $posted ) );

									echo "<div class='two columns hz-textbox'><input type='text' name='$fname' id='$fname' value='$value' placeholder='" . sweet_translate_dynamic( $field->name, 'sweetdate' ) . "' /></div>";
									break;

							endswitch;

						}

						echo "<input type='hidden' name='bs' value=' ' />";
						do_action( 'kleo_bp_search_add_data' );
						?>
						<?php if ( $show_button ): ?>
							<div class="two columns hz-submit">
								<button class="small button radius"><i class="icon-search"></i></button>
							</div>
						<?php else: ?>
							<script type="text/javascript">
								jQuery(document).ready(function ($) {
									$("input,select,radio,checkbox", "#horizontal_search").change(function () {
										$("#horizontal_search").submit();
									});
								});
							</script>
						<?php endif; ?>

					</div>
				</form>
				<!--end search form-->
			</div><!--end row-->
		</div><!--end Search bar-->
		<?php
	}
endif;


//theme options get fields
function kleo_selected_form_fields( $name, $values ) {
	global $field, $bp_search_fields, $kleo_bp_dateboxes, $kleo_bp_textboxes, $kleo_bp_multi;

	if ( bp_is_active( 'xprofile' ) ) :
		if ( function_exists( 'bp_has_profile' ) ) :
			if ( bp_has_profile( 'hide_empty_fields=0' ) ) :
				$kleo_bp_dateboxes = array( '' );
				$kleo_bp_textboxes = array( '' );
				$kleo_bp_multi = array( '' );
				echo '<ul class="text_sortable gaf_bp_fields">';
				if ( is_array( $values ) && ! empty( $values ) ) {
					foreach ( $values as $value ) {
						$selectedfield = new BP_XProfile_Field ( $value );
						?>
						<li class="clearfix">
							<label>
								<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[]" value="<?php echo esc_attr( $selectedfield->id ); ?>"
										<?php if ( in_array( $selectedfield->id, (array) $values ) ) {
											echo ' checked="checked"';
										} ?> />
								<?php echo esc_html( $selectedfield->name ); ?>
								<?php if ( bp_get_the_profile_field_is_required() ) {
									_e( ' (required) ', 'buddypress' );
								} else {
									_e( ' (optional) ', 'buddypress' );
								} ?>
							</label>
							<span class="drag"><i class="icon-move icon-large"></i></span>
						</li>
						<?php
					}
				}

				while ( bp_profile_groups() ) :
					bp_the_profile_group();

					//echo '<strong>'. bp_get_the_profile_group_name (). ':</strong><br />';
					while ( bp_profile_fields() ) :
						bp_the_profile_field();


						switch ( bp_get_the_profile_field_type() ) {
							case 'datebox':
							case 'birthdate':
								$kleo_bp_dateboxes[ bp_get_the_profile_field_id() ] = bp_get_the_profile_field_name();
								break;
							case 'textbox':
							case 'selectbox':
							case 'radio':
								$kleo_bp_textboxes[ bp_get_the_profile_field_id() ] = bp_get_the_profile_field_name();
								break;

							case 'multiselectbox':
								$kleo_bp_multi[ bp_get_the_profile_field_id() ] = bp_get_the_profile_field_name();
								break;
						}

						if ( is_array( $values ) && in_array( bp_get_the_profile_field_id(), $values ) ) {
							continue;
						}
						?>
						<li class="clearfix">
							<label>
								<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[]" value="<?php echo esc_attr( $field->id ); ?>"
									<?php if ( in_array( $field->id, (array) $values ) ) {
										echo ' checked="checked"';
									} ?> />
								<?php bp_the_profile_field_name(); ?>
								<?php if ( bp_get_the_profile_field_is_required() ) {
									esc_html_e( ' (required) ', 'buddypress' );
								} else {
									esc_html_e( ' (optional) ', 'buddypress' );
								} ?>
							</label> <span class="drag"><i class="icon-move icon-large"></i></span></li>

					<?php
					endwhile;
				endwhile;
				echo '</ul>';
				wp_enqueue_script(
					'squeen-opts-field-social-links-js',
					SQUEEN_OPTIONS_URL . 'fields/text_sortable/field_text_sortable.js',
					array( 'jquery' ),
					time(),
					true
				);

			endif;
		endif;
	endif;

	return true;
}

function kleo_bp_agerange( $name, $value ) {
	global $kleo_bp_dateboxes;

	if ( count( $kleo_bp_dateboxes ) > 1 ) {
		echo "<select name=\"$name\">\n";
		foreach ( $kleo_bp_dateboxes as $fid => $fname ) {
			echo "<option value=\"$fid\"";
			if ( $fid == $value ) {
				echo " selected=\"selected\"";
			}
			echo ">$fname &nbsp;</option>\n";
		}
		echo "</select>\n";
	} else {
		echo esc_html__( 'There is no date field', 'sweetdate' );
	}

	return true;
}

function kleo_bp_numrange( $name, $value, $multi = false ) {
	global $kleo_bp_textboxes, $kleo_bp_multi;

	if ( $multi === true && ! empty( $kleo_bp_multi ) ) {
		$fields = $kleo_bp_textboxes + $kleo_bp_multi;
	} else {
		$fields = $kleo_bp_textboxes;
	}

	if ( count( $fields ) > 1 ) {
		echo "<select name=\"$name\">\n";
		foreach ( $fields as $fid => $fname ) {
			echo "<option value=\"$fid\"";
			if ( $fid == $value ) {
				echo " selected=\"selected\"";
			}
			echo ">$fname &nbsp;</option>\n";
		}
		echo "</select>\n";
	} else {
		echo esc_html__( 'There are no textbox or selectbox fields in your profile', 'sweetdate' );
	}

	return true;
}


/* -------------------------------------------------*/

add_action( 'wp_loaded', 'kleo_bp_cookies' );
function kleo_bp_cookies() {
	global $bp_results;

	if ( isset( $_GET['bs'] ) ) {
		$bp_results = bp_members_search( $_GET );
		setcookie( 'bp-members-search', serialize( $_GET ), 0, COOKIEPATH );
	} elseif ( isset( $_COOKIE['bp-members-search'] ) ) {
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'members_filter'
		     && isset( $_POST['extras'] ) && $_POST['extras'] == 'paginated'
		) {
			$bp_results = bp_members_search( unserialize( stripslashes( $_COOKIE['bp-members-search'] ) ) );
		} elseif ( bp_is_members_component() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			setcookie( 'bp-members-search', '', 0, COOKIEPATH );
		}
	}
}

if ( ! function_exists( 'bp_members_search' ) ):
	function bp_members_search( $posted ) {
		global $bp, $wpdb, $bp_search_fields;

		$emptyform = true;
		$results   = array( 'users' => array( 0 ), 'validated' => true );
		if ( ! function_exists( 'bp_has_profile' ) ) {
			return $results;
		}

		if ( bp_has_profile( 'hide_empty_fields=0' ) ):
			while ( bp_profile_groups() ):
				bp_the_profile_group();
				while ( bp_profile_fields() ):
					bp_the_profile_field();

					$id    = bp_get_the_profile_field_id();
					$value = isset( $posted["field_$id"] ) ? $posted["field_$id"] : '';
					$to    = isset( $posted["field_{$id}_to"] ) ? $posted["field_{$id}_to"] : '';

					//matching fields logic
					if ( isset( $bp_search_fields['match1'] ) && ! empty( $bp_search_fields['match1'][1] ) && ! empty( $bp_search_fields['match1'][2] ) ) {
						if ( $bp_search_fields['match1'][1] == $id ) {
							$id = $bp_search_fields['match1'][2];
						} elseif ( $bp_search_fields['match1'][2] == $id ) {
							$id = $bp_search_fields['match1'][1];
						}
					}

					if ( $value == '' && $to == '' ) {
						continue;
					}

					$sql = "SELECT DISTINCT {$bp->profile->table_name_data}.user_id FROM {$bp->profile->table_name_data}"
					       . " JOIN " . $wpdb->base_prefix . "usermeta um ON um.user_id = {$bp->profile->table_name_data}.user_id";

					switch ( bp_get_the_profile_field_type() ):
						case 'selectbox':
						case 'radio':
							$sql .= $wpdb->prepare( " WHERE field_id = %d AND value = %s", $id, $value );
							break;

						case 'multiselectbox':
						case 'checkbox':
							$values = $posted["field_$id"];

							if ( ! empty( $values ) && $values[0] != '' ) {
								$sql .= $wpdb->prepare( " WHERE field_id = %d", $id );


								$like = array();
								foreach ( $values as $value ) {
									if ( $value != '' ) {
										$escaped = '%"' . $wpdb->esc_like( esc_sql( $value ) ) . '"%';
										$like[]  = $wpdb->prepare( "value = %s OR value LIKE %s", $value, $escaped );
									}
								}
								$sql .= ' AND (' . implode( ' OR ', $like ) . ')';
							}
							break;

						case 'datebox':
						case 'birthdate':
							if ( $id != $bp_search_fields['agerange'] ) {
								continue 2;
							}

							$time  = time();
							$day   = date( "j", $time );
							$month = date( "n", $time );
							$year  = date( "Y", $time );

							if ( $to == '' ) {
								$ymax = $year - $value;
								$sql  .= $wpdb->prepare( " WHERE field_id = %d AND (DATE(value) <= %s)", $id, "$ymax-$month-$day" );

							} elseif ( $value == '' ) {
								$ymin = $year - $to - 1;
								$sql  .= $wpdb->prepare( " WHERE field_id = %d AND (DATE(value) > %s)", $id, "$ymin-$month-$day" );
							} else {
								$ymin = $year - $to - 1;
								$ymax = $year - $value;
								$sql  .= $wpdb->prepare( " WHERE field_id = %d AND (DATE(value) > %s AND DATE(value) <= %s)", $id, "$ymin-$month-$day", "$ymax-$month-$day", $time, $time );
							}
							break;

						case 'textbox':
							if ( $id == $bp_search_fields['numrange'] ) {
								if ( $value == '' ) {
									$sql .= $wpdb->prepare( " WHERE field_id = %d AND value <= %d", $id, $to );
								} elseif ( $to == '' ) {
									$sql .= $wpdb->prepare( " WHERE field_id = %d AND value >= %d", $id, $value );
								} else {
									$sql .= $wpdb->prepare( " WHERE field_id = %d AND value >= %d AND value <= %d", $id, $value, $to );
								}
								break;
							}
						case 'textarea':
						default:
							$escaped = '%' . $wpdb->esc_like( esc_sql( $value ) ) . '%';

							$sql .= $wpdb->prepare( " WHERE field_id = %d AND value LIKE %s", $id, $escaped );
							// $sql .= $wpdb->prepare (" WHERE field_id = %d AND value = %s", $id, $value);
							break;
					endswitch;

					$extra = '';
					$obj   = new stdClass();
					do_action_ref_array( 'bp_pre_user_query_construct', array( &$obj ) );
					if ( isset( $obj->query_vars ) && $obj->query_vars['exclude'] && is_array( $obj->query_vars['exclude'] ) && ! empty( $obj->query_vars['exclude'] ) ) {
						$extra = " AND {$bp->profile->table_name_data}.user_id NOT IN (" . implode( ",", $obj->query_vars['exclude'] ) . ")";
					}

					$sql .= " AND um.meta_key = 'last_activity'" . $extra;

					$sql   = apply_filters( 'kleo_bp_field_query', $sql, $id, $value );
					$found = $wpdb->get_col( $sql );
					if ( ! isset ( $users ) ) {
						$users = $found;
					} else {
						$users = array_intersect( $users, $found );
					}

					$emptyform = false;
					if ( count( $users ) == 0 ) {
						return $results;
					}

				endwhile;
			endwhile;
		endif;

		if ( $emptyform == true ) {
			$results['validated'] = false;

			return $results;
		}

		$results['users'] = $users;

		return $results;
	}
endif;

add_action( 'bp_before_members_loop', 'kleo_bp_search_add_filter' );
function kleo_bp_search_add_filter() {
	add_filter( 'bp_pre_user_query_construct', 'kleo_bp_user_query' );
}

add_action( 'bp_after_members_loop', 'kleo_bp_search_rm_filter' );
function kleo_bp_search_rm_filter() {
	remove_filter( 'bp_pre_user_query_construct', 'kleo_bp_user_query' );
}

function kleo_bp_user_query( $query ) {
	global $bp_results;

	if ( isset ( $bp_results ) && $bp_results['validated'] ) {
		$users = $bp_results['users'];

		if ( $query->query_vars['user_id'] ) {
			$friends = friends_get_friend_user_ids( $query->query_vars['user_id'] );

			$users = array_intersect( $users, $friends );
			if ( count( $users ) == 0 ) {
				$users = array( 0 );
			}
		}

		$query->query_vars['include'] = $users;
	}

	return $query;
}
