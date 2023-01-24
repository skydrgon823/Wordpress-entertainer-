<?php
/*
 * BP Profile Search - form template 'bps-form-horizontal'
 *
 * See http://dontdream.it/bp-profile-search/form-templates/ if you wish to modify this template or develop a new one.
 *
 * Move new or modified templates to the 'buddypress/members' directory in your theme's root,
 * otherwise they will be overwritten during the next BP Profile Search update.
 *
 */

// 1st section: set the default value of the template options

if ( ! isset ( $options['collapsible'] ) ) {
	$options['collapsible'] = 'No';
}

// 2nd section: display the form to select the template options

if ( is_admin() ) {
	?>
	<p><strong><?php _e( 'Collapsible Form', 'bp-profile-search' ); ?></strong></p>
	<select name="options[collapsible]">
		<option
			value='Yes' <?php selected( $options['collapsible'], 'Yes' ); ?>><?php esc_html_e( 'Yes', 'bp-profile-search' ); ?></option>
		<option
			value='No' <?php selected( $options['collapsible'], 'No' ); ?>><?php esc_html_e( 'No', 'bp-profile-search' ); ?></option>
	</select>
	<?php
	return 'end_of_options 4.9';
}

// 3rd section: display the search form

$F = bps_escaped_form_data( '4.9' );

$toggle_id = 'bps_toggle' . $F->unique_id;
$form_id   = 'bps_' . $F->location . $F->unique_id;

echo '<div id="search-bar">';
echo '<div class="row">';

if ( $F->location != 'directory' ) {
	echo "<div id='buddypress'>";
} elseif ( $options['collapsible'] == 'Yes' ) {

	?>
	<div class="item-list-tabs bps_header twelve columns">
		<ul>
			<li><?php esc_html_e( 'Advanced Search', 'sweetdate' ); ?></li>
			<li class="last">
				<input id="<?php echo esc_attr( $toggle_id ); ?>" type="submit"
				       value="<?php esc_html_e( 'Toggle filters', 'sweetdate' ); ?>">
			</li>
		</ul>

		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$('#<?php echo esc_attr( $form_id ); ?>').hide();
				$('#<?php echo esc_attr( $toggle_id ); ?>').click(function () {
					$('#<?php echo esc_attr( $form_id ); ?>').toggle('slow');
				});
			});
		</script>
		<div class="clear clearfix"></div>
	</div>
	<?php
}

echo "<form action='$F->action' method='$F->method' id='$form_id' class='" . apply_filters( 'bp_search_horiz_extra_class', 'custom' ) . " dir-form twelve columns custom 123'>\n";

echo '<div class="row">';
$j = 0;
foreach ( $F->fields as $f ) {
	$id      = $f->unique_id;
	$name    = $f->html_name;
	$value   = $f->value;
	$display = $f->display;

	if ( $display == 'none' ) {
		continue;
	}

	if ( $display == 'hidden' ) {
		?>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>">
		<?php
		continue;
	}

	switch ( $display ) {
		case 'range':
		case 'integer-range':

			if ( $f->type == 'datebox' ) {

				$from = ( $value['min'] == '' ) ? sq_option( 'buddypress_age_start', 18 ) : $value['min'];
				$to   = ( $value['max'] == '' ) ? sq_option( 'buddypress_age_end', 75 ) : $value['max'];

				echo '<div class="two columns hz-agerange hz-from">';
				echo '<select name="' . $name . '[min]" class="expand customDropdown">';
				echo '<option value="">' . sweet_translate_dynamic( $f->label, 'sweetdate' ) . ' ' . esc_html__( 'from', 'sweetdate' ) . '</option>';
				for ( $i = sq_option( 'buddypress_age_start', 18 ); $i <= sq_option( 'buddypress_age_end', 75 ); $i ++ ) {
					echo '<option value="' . $i . '" ' . get_selected( '-', $i, $from ) . ' >' . $i . '</option>';
				}
				echo '</select>
                </div>';
				echo '<div class="two columns hz-agerange hz-to">
                    <select name="' . $name . '[max]" class="expand customDropdown">';
				echo '<option value="">' . sweet_translate_dynamic( $f->label, 'sweetdate' ) . ' ' . esc_html__( 'to', 'sweetdate' ) . '</option>';
				for ( $i = sq_option( 'buddypress_age_start', 18 ); $i <= sq_option( 'buddypress_age_end', 75 ); $i ++ ) {
					echo '<option value="' . $i . '" ' . get_selected( '-', $i, $to ) . ' >' . $i . '</option>';
				}
				echo ' </select>
                  </div>';
			} else {
				echo '<div class="two columns hz-numrange hz-from">';
				echo ' <input type="text" name="' . $name . '[min]" value="' . $value['min'] . '" placeholder="' . sweet_translate_dynamic( $f->label, 'sweetdate' ) . ' ' . esc_html__( 'from', 'sweetdate' ) . '">
                </div>';
				echo '<div class="two columns hz-numrange hz-to">
                      <input type="text" name="' . $name . '[max]" value="' . $value['max'] . '" placeholder="' . sweet_translate_dynamic( $f->label, 'sweetdate' ) . ' ' . esc_html__( 'to', 'sweetdate' ) . '">
                  </div>';
			}
			break;

		case 'range-select':
			$from = $value['min'];
			$to   = $value['max'];

			echo '<div class="two columns hz-agerange hz-from">';
			echo '<select name="' . $name . '[min]" class="expand customDropdown">';
			echo '<option value="">' . sweet_translate_dynamic( $f->label, 'sweetdate' ) . ' ' . esc_html__( 'from', 'sweetdate' ) . '</option>';
			foreach ( $f->options as $key => $label ) {
				echo '<option value="' . $key . '" ' . get_selected( '-', $key, $from ) . ' >' . $label . '</option>';
			}
			echo '</select>
                </div>';
			echo '<div class="two columns hz-agerange hz-to">
                    <select name="' . $name . '[max]" class="expand customDropdown">';
			echo '<option value="">' . sweet_translate_dynamic( $f->label, 'sweetdate' ) . ' ' . esc_html__( 'to', 'sweetdate' ) . '</option>';
			foreach ( $f->options as $key => $label ) {
				echo '<option value="' . $key . '" ' . get_selected( '-', $key, $to ) . ' >' . $label . '</option>';
			}
			echo ' </select>
                  </div>';
			break;

		case 'textarea':
			echo "<div class='two columns hz-textarea'>";
			echo "<input type='text' name='$name' id='$id' value='$value' placeholder='" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "' >" .
			     "</div>";
			break;

		case 'selectbox':

			echo "<div class='two columns hz-select'>";
			echo "<select class='expand' name='$name' id='$id'>";

			$select_options = $f->options;
			foreach ( $select_options as $k => $option ) {
				if ( '' == $option ) {
					unset( $select_options[ $k ] );
				}
			}

			$no_selection = apply_filters( 'bps_field_selectbox_no_selection', $f->label, $f );
			if ( is_string( $no_selection ) ) {
				echo "<option  value=''>$no_selection</option>\n";
			}

			foreach ( $select_options as $key => $label ) {
				$selected = ( $key == $value ) ? "selected='selected'" : "";
				echo "<option $selected value='$key'>" . sweet_translate_dynamic( $label, 'sweetdate' ) . "</option>";
			}
			echo "</select></div>";
			break;

		case 'multiselectbox':

			echo "<div class='two columns hz-multiselect'>";
			echo "<select " . apply_filters( 'kleo_bp_search_multiselect_attributes', "multiple='multiple' data-customforms='disabled'" ) . " class='expand' name='{$name}[]' id='$id'>";
			echo "<option value=''>" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "</option>";
			foreach ( $f->options as $key => $label ) {
				$selected = in_array( $key, (array) $value ) ? "selected='selected'" : "";
				echo "<option $selected value='$key'>" . sweet_translate_dynamic( $label, 'sweetdate' ) . "</option>";
			}
			echo "</select></div>";
			break;

		case 'radio':

			echo "<div class='two columns hz-radio'>" .
			     "<label>" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "</label>";
			echo "</div>";


			$count = count( $f->options );
			$i     = 1;
			foreach ( $f->options as $key => $label ) {
				$class = '';
				if ( $count == $i ) {
					$class = ' last';
				}
				$checked = in_array( $key, (array) $value ) ? "checked='checked'" : "";

				echo "<div class='two columns bglabel" . $class . "'>";
				echo "<label><input $checked type='radio' name='$name' value='$key'> " . sweet_translate_dynamic( $label, 'sweetdate' ) . "</label>";
				echo '</div>';
			}
			break;

		case 'checkbox':

			echo "<div class='two columns hz-checkbox-name'>" .
			     "<label>" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "</label>" .
			     "</div>";

			$count = count( $f->options );
			$i     = 1;
			foreach ( $f->options as $key => $label ) {
				$class = '';
				if ( $count == $i ) {
					$class = ' last';
				}
				$checked = in_array( $key, (array) $value ) ? "checked='checked'" : "";

				echo "<div class='two columns bglabel hz-checkbox" . $class . "'>";
				echo "<label>" .
				     "<input $checked type='checkbox' name='{$name}[]' value='$key'> " . sweet_translate_dynamic( $label, 'sweetdate' ) .
				     "</label>";
				echo "</div>";
				$i ++;
			}

			break;

		case 'number':
			echo "<div class='two columns hz-textbox'>" .
			     "<input type='number' name='$name' id='$id' value='$value' placeholder='" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "'>" .
			     "</div>";
			break;

		case 'url':
			echo "<div class='two columns hz-textbox'>" .
			     "<input type='number' inputmode='url' name='$name' id='$id' value='$value' placeholder='" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "'>" .
			     "</div>";
			break;

		case 'textbox':

			echo "<div class='two columns hz-textbox'>";
			echo "<input type='text' name='$name' id='$id' value='$value' placeholder='" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "'>" .
			     "</div>";
			break;
		default:

			$field_template = 'members/bps-' . $display . '-form-field.php';
			$located        = bp_locate_template( $field_template );
			if ( $located ) {
				include $located;
			}
			else {
				?>
                <p class="bps-error"><?php echo "BP Profile Search: unknown display <em>$display</em> for field <em>$f->name</em>."; ?></p>
				<?php
			}
			break;
	}

	do_action( 'kleo_bp_search_add_data' );

	/*if ( ! empty ( $f->description ) && $f->description != '-' ) {
		echo "<div class='seven mobile-four columns kleo-text'>";
		echo "<label class='inline'>$f->description</label>\n";
		echo "</div>";
	}*/

}
?>
	<div class="two columns hz-submit">
		<button class="small button radius"><i class="icon-search"></i></button>
	</div>
	</div>

	<span class="notch"></span>
<?php

echo "</form>\n";

if ( $F->location != 'directory' ) {
	echo "</div>\n";
}

echo "</div>\n";
echo "</div>\n";

return 'end_of_template 4.9';
