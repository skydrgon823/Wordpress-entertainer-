<?php
/*
 * BP Profile Search - form template 'bps-form-legacy'
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

if ( is_admin() && ! ( isset( $_GET['elementor_preview'] ) || ( isset( $_GET['action'] ) && $_GET['action'] == 'elementor' ) ) ) {
	?>
    <p><strong><?php esc_html_e( 'Collapsible Form', 'bp-profile-search' ); ?></strong></p>
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

if ( $F->location != 'directory' ) {
	echo "<div id='buddypress'>";
} elseif ( $options['collapsible'] == 'Yes' ) {

	?>
    <div class="item-list-tabs bps_header">
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

echo "<form action='$F->action' method='$F->method' id='$form_id' class='" . apply_filters( 'bp_search_extra_class', 'custom' ) . " form-search form-memberss '>\n";

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

	$string = strtolower(preg_replace('/\s+/', '', $f->label));
	$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	$labelid =  preg_replace('/-+/', '-', $string);

	$alt   = ( $j ++ % 2 ) ? 'alt' : '';
	$class = "editfield $id field_$name $alt twelve search-box clear clearfix";



	echo "<div class='$class' id='$labelid'>\n";

	switch ( $display ) {
		case 'range':
			if ( $f->type == 'datebox' ) {

				$from = ( $value['min'] == '' ) ? sq_option( 'buddypress_age_start', 18 ) : $value['min'];
				$to   = ( $value['max'] == '' ) ? sq_option( 'buddypress_age_end', 75 ) : $value['max'];

				echo '<div class="search-header">' .
				     "<label class='left inline'>" . $f->label . "</label>" .
				     '</div>';
                echo '<div class="search-content">';
				echo '<div class="three mobile-two columns">

                <select name="' . $name . '[min]" class="expand customDropdown">';

				echo apply_filters( 'kleo_bp_searchform_before_all_li', '<option value=""> </option>' );

				for ( $i = sq_option( 'buddypress_age_start', 18 ); $i <= sq_option( 'buddypress_age_end', 75 ); $i ++ ) {
					echo '<option value="' . $i . '" ' . get_selected( '-', $i, $from ) . ' >' . $i . '</option>';
				}
				echo '</select>
              </div>
              
              <div class="one mobile-one columns text-center bps-range-separator">
                <label class="inline"> - </label>
              </div>';

				echo '<div class="three mobile-two columns">
                <select name="' . $name . '[max]" class="expand customDropdown">';

				echo apply_filters( 'kleo_bp_searchform_before_all_li', '<option value=""> </option>' );

				for ( $i = sq_option( 'buddypress_age_start', 18 ); $i <= sq_option( 'buddypress_age_end', 75 ); $i ++ ) {
					echo '<option value="' . $i . '" ' . get_selected( '-', $i, $to ) . ' >' . $i . '</option>';
				}
				echo ' </select>
              </div><div style="clear:both"></div></div>';

			} else {

				echo '<div class="search-header">';
				echo "<label class='left inline'>" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "</label>";
				echo '</div>';
				echo '<div class="search-content">';
				echo '<div class="three mobile-one columns">
              <input type="text" name="' . esc_attr( $name ) . '[min]" value="' . $value['min'] . '">
              </div>
              <div class="one mobile-one columns text-center">
                <label class="inline"> - </label>
              </div>';
				echo '<div class="three mobile-one columns">
                  <input type="text" name="' . esc_attr( $name ) . '[max]" value="' . $value['max'] . '">
              </div><div style="clear:both"></div></div>';
			}
			break;

		case 'range-select':
			$from = $value['min'];
			$to   = $value['max'];

			echo '<div class="search-header">' .
			     "<label class='left inline'>" . $f->label . "</label>" .
			     '</div>';
            echo '<div class="search-content">';
			echo '<div class="three mobile-one columns">

            <select name="' . $name . '[min]" class="expand customDropdown">';

			echo apply_filters( 'kleo_bp_searchform_before_all_li', '<option value=""> </option>' );

			foreach ( $f->options as $key => $label ) {
				echo '<option value="' . $key . '" ' . get_selected( '-', $key, $from ) . ' >' . $label . '</option>';
			}
			echo '</select>
              </div>
              
              <div class="one mobile-one columns text-center">
                <label class="inline"> - </label>
              </div>';

			echo '<div class="three mobile-one columns">
                <select name="' . $name . '[max]" class="expand customDropdown">';

			echo apply_filters( 'kleo_bp_searchform_before_all_li', '<option value=""> </option>' );

			foreach ( $f->options as $key => $label ) {
				echo '<option value="' . $key . '" ' . get_selected( '-', $key, $to ) . ' >' . $label . '</option>';
			}
			echo ' </select>
              </div><div style="clear:both"></div></div>';

			break;

		case 'integer-range':

			if ( $f->type == 'datebox' ) {

				$from = ( $value['min'] == '' ) ? sq_option( 'buddypress_age_start', 18 ) : $value['min'];
				$to   = ( $value['max'] == '' ) ? sq_option( 'buddypress_age_end', 75 ) : $value['max'];

				echo '<div class="search-header">' .
				     "<label class='left inline'>" . $f->label . "</label>" .
				     '</div>';
                echo '<div class="search-content">';
				echo '<div class="three mobile-two columns">

                <select name="' . $name . '[min]" class="expand customDropdown">';

				echo apply_filters( 'kleo_bp_searchform_before_all_li', '<option value=""> </option>' );

				for ( $i = sq_option( 'buddypress_age_start', 18 ); $i <= sq_option( 'buddypress_age_end', 75 ); $i ++ ) {
					echo '<option value="' . $i . '" ' . get_selected( '-', $i, $from ) . ' >' . $i . '</option>';
				}
				echo '</select>
              </div>
              
              <div class="one mobile-one columns text-center bps-range-separator">
                <label class="inline"> - </label>
              </div>';

				echo '<div class="three mobile-two columns">
                <select name="' . $name . '[max]" class="expand customDropdown">';

				echo apply_filters( 'kleo_bp_searchform_before_all_li', '<option value=""> </option>' );

				for ( $i = sq_option( 'buddypress_age_start', 18 ); $i <= sq_option( 'buddypress_age_end', 75 ); $i ++ ) {
					echo '<option value="' . $i . '" ' . get_selected( '-', $i, $to ) . ' >' . $i . '</option>';
				}
				echo ' </select>
              </div><div style="clear:both"></div></div>';

			} else {
				echo '<div class="search-header">';
				echo "<label class='left inline'>" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "</label>";
				echo '</div>';
				echo '<div class="search-content">';
				echo '<div class="three mobile-one columns">
				        <input type="text" name="' . $name . '[min]" value="' . $value['min'] . '" >
			        </div>
                    <div class="one mobile-one columns text-center">
                        <label class="inline"> - </label>
                    </div>';

				echo '<div class="three mobile-one columns">
				        <input type="text" name="' . $name . '[max]" value="' . $value['max'] . '" >
			        </div><div style="clear:both"></div></div>';
			}

			break;
		case 'distance':

			$of = esc_html__( 'of', 'bp-profile-search' );
			$km          = esc_html__( 'km', 'bp-profile-search' );
			$miles       = esc_html__( 'miles', 'bp-profile-search' );
			$placeholder = esc_html__( 'Type your location', 'bp-profile-search' );
			$icon_url    = plugins_url( 'bp-profile-search/templates/members/locator.png' );
			$icon_title  = esc_html__( 'Get current location', 'bp-profile-search' );

			$distance = array('10'=>'10', '25'=>'25', '50'=>'50','100'=>'100', '150'=>'150', '200'=>'200','250'=>'250','500'=>'500');			
			?>
            <div class="distance-search-content">
                <div class="row">
				<?php
					echo '<div class="four mobile-twelve columns search-header">' .
						"<label class='left inline'>" . $f->label . "</label>" .
						'</div>'; ?>
                    <div class="eight mobile-twelve columns kleo-text">
                        <div class="field_type_bds_location" style="position: relative;">
							<input type="text" id="<?php echo esc_attr( $id ); ?>" class="bps-location-input" name="<?php echo esc_attr( $name).'[location]'; ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" aria-labelledby="<?php echo esc_attr( $name ).'[location]'; ?>-1" aria-describedby="<?php echo esc_attr( $name ).'[location]'; ?>-3">
							<img class="bps-locator-icon" id="<?php echo esc_attr( $id ); ?>_icon" style="cursor: pointer;" src="<?php echo esc_attr( $icon_url ); ?>" title="<?php echo esc_attr( $icon_title ); ?>">
                        </div>
					</div> 
				</div>
				<div style="clear:both"></div>
				<div class="row">
				<div class='four mobile-twelve columns search-header'>
					<label class='left inline' for='<?php echo esc_attr($id); ?>_distance'>Distance</label>
			     </div>
					<div class="eight mobile-twelve columns kleo-selectbox">
						<select id="<?php echo esc_attr($id); ?>_distance" class='expand' name="<?php echo $name.'[distance]'; ?>">
						<option value="">Miles</option>
						<?php 
							foreach($distance as $key => $val){
								$selected = ( $val == $value['distance'] ) ? "selected='selected'" : "";
							  echo '<option value="'. $val .'" ' . $selected . ' >'. $key .' miles</option>';
						 }
						?>
					 </select>
					 <input id="<?php echo esc_attr( $id ); ?>_units" name="<?php echo $name.'[units]'; ?>" type="hidden" value="miles">
					</div>
				</div>
					<input id="<?php echo esc_attr( $id ); ?>_city" name="<?php echo esc_attr($name); ?>[city]" type="hidden">
					<input id="<?php echo esc_attr( $id ); ?>_state" name="<?php echo esc_attr($name); ?>[state]" type="hidden">
					<input id="<?php echo esc_attr( $id ); ?>_country" name="<?php echo esc_attr($name); ?>[country]" type="hidden">

					<input type="hidden" id="<?php echo esc_attr($id); ?>_lat" name="<?php echo esc_attr($name).'[lat]'; ?>" value="<?php echo esc_attr($value['lat']); ?>">
					<input type="hidden" id="<?php echo esc_attr($id); ?>_lng" name="<?php echo esc_attr($name).'[lng]'; ?>" value="<?php echo esc_attr($value['lng']); ?>">                                       
                
				<script>
					jQuery(function ($) {
						/* $(window).load(function() {
						 	bds_locate_search('<?php echo esc_attr($id); ?>', '<?php echo esc_attr($id); ?>_lat', '<?php echo esc_attr($id); ?>_lng');
						}); */

						var myId = $("#<?php echo esc_attr( $id ); ?>.bps-location-input").attr('id');
						var lat = '<?php echo esc_attr( $id ); ?>_lat';
						var lng	= '<?php echo esc_attr( $id ); ?>_lng';
						bds_autocomplete_search(myId, lat, lng);
						$('#<?php echo esc_attr($id); ?>_icon').click(function () {
							bds_locate_search('<?php echo esc_attr($id); ?>', '<?php echo esc_attr($id); ?>_lat', '<?php echo esc_attr($id); ?>_lng');
						});

						function bds_autocomplete_search(input, lat, lng) {
							input = document.getElementById(input);
							var options = {types: ['geocode']};
							var autocomplete = new google.maps.places.Autocomplete(input, options);
							google.maps.event.addListener(autocomplete, 'place_changed', function() {
								var place = autocomplete.getPlace();
								document.getElementById(lat).value = place.geometry.location.lat();
								document.getElementById(lng).value = place.geometry.location.lng();

								$('input#<?php echo esc_attr($id); ?>_country').val(findComponent(place, 'country')); 
								$('input#<?php echo esc_attr($id); ?>_state').val(findComponent(place, 'administrative_area_level_1')); 
								$('input#<?php echo esc_attr($id); ?>_city').val(findComponent(place, 'administrative_area_level_3') || findComponent(place, 'locality'));
								console.log(place);
							});
						}
						function bds_locate_search(input, lat, lng) {
							if (navigator.geolocation) {
								var options = {timeout: 5000};
								navigator.geolocation.getCurrentPosition(function(position) {
									document.getElementById(lat).value = position.coords.latitude;
									document.getElementById(lng).value = position.coords.longitude;
									bds_address_search(position, input);
								}, function(error) {
									alert('ERROR ' + error.code + ': ' + error.message);
								}, options);
							} else {
								alert('ERROR: Geolocation is not supported by this browser');
							}
						}

						function bds_address_search(position, input) {
							var geocoder = new google.maps.Geocoder;
							var latlng = {lat: position.coords.latitude, lng: position.coords.longitude};
							geocoder.geocode({'location': latlng}, function(results, status) {
								if (status === 'OK') {
									if (results[0]) {
										document.getElementById(input).value = results[0].formatted_address;
									} else {
										alert('ERROR: Geocoder found no results');
									}
								} else {
									alert('ERROR: Geocoder status: ' + status);
								}
							});
						}
						
						function findComponent(result, type) {
						  var component = _.find(result.address_components, function(component) {
							return _.include(component.types, type);
						  });
						  return component && component.long_name;
						}
					});
				</script>
            </div>
			<?php
			break;	

		case 'textarea':
			echo "<div class='search-header'>" .
			     "<label class='left inline' for='$id'>" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "</label>" .
			     "</div>";
			echo "<div class='search-content kleo-textarea'>" .
			     "<input type='text' name='$name' id='$id' value='$value' />" .
			     "</div>";
			break;

		case 'selectbox':			

			echo "<div class='four mobile-four columns search-header'>" .
			     "<label class='left inline' for='$id'>" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "</label>" .
			     "</div>";
			echo "<div class='eight mobile-four columns kleo-selectbox'>" .
			     "<select class='expand' name='$name' id='$id'>";

			$select_options = $f->options;

			foreach ( $select_options as $key => $label ) {
				$selected = ( $key == $value ) ? "selected='selected'" : "";
				echo "<option $selected value='$key'>" . sweet_translate_dynamic( $label, 'sweetdate' ) . "</option>";
			}
			echo "</select></div><div style='clear:both'></div>";
			break;

		case 'multiselectbox':

			echo "<div class='search-header'>" .
			     "<label class='left inline' for='$id'>" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "</label>" .
			     "</div>";
			echo "<div class='search-content kleo-multiselectbox'>" .
			     "<select " .
			     apply_filters( 'kleo_bp_search_multiselect_attributes', "multiple='multiple' data-customforms='disabled'" ) .
			     " class='expand' name='{$name}[]' id='$id'>";
			foreach ( $f->options as $key => $label ) {
				$selected = in_array( $key, (array) $value ) ? "selected='selected'" : "";
				echo "<option $selected value='$key'>" . sweet_translate_dynamic( $label, 'sweetdate' ) . "</option>";
			}
			echo "</select></div>";
			break;

		case 'radio':

			echo "<div class='search-header'>" .
			     "<label class='left'>" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "<br>";
		//	echo "";
			?>
            <script type="text/javascript">jQuery('.clear-value-<?php echo esc_attr( $id ); ?>').click(function () {
                    jQuery('input[name=<?php echo esc_attr( $name ); ?>]').attr('checked', false);
                    jQuery('.field_<?php echo esc_attr( $id ); ?> .custom.radio').removeClass('checked');
                    return false;
                });</script>
			<?php
			echo "</label></div>";
			echo "<div class='search-content kleo-radio field_" . esc_attr( $id ) . "'>";

			foreach ( $f->options as $key => $label ) {
				$checked = in_array( $key, (array) $value ) ? "checked='checked'" : "";
				echo "<label class='six mobile-six columns'><input $checked type='radio' name='$name' value='$key'> " . sweet_translate_dynamic( $label, 'sweetdate' ) . "</label>";
			}
			echo '<div style="clear:both"></div>';
			echo "<a class='clear-value-" . $id . "' href='#'>" .
			     "<small class='kleo-clear-radio'><i class='icon icon-remove'></i> " . esc_html__( 'Clear', 'sweetdate' ) . "</small>" .
			     "</a>";
			echo '</div>';
			
			break;

		case 'checkbox':

			echo "<div class='search-header'>" .
			     "<label >" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "</label>" .
			     "</div>";
			echo "<div class='kleo-checkbox' id='kleo_checkbox_".$id."'>";
			$checkcnt = 0;
			foreach ( $f->options as $key => $label ) {
			    $checkalt = ( $checkcnt % 2 ) ? '' : 'checkalt';
				$checkcnt++;
				$checked = in_array( $key, $value ) ? "checked='checked'" : "";
				echo "<label class='six mobile-six columns $checkalt'><input $checked type='checkbox' name='{$name}[]' value='$key'> " . sweet_translate_dynamic( $label, 'sweetdate' ) . "</label>";
			}
			echo '<div style="clear:both"></div>';
			echo '</div>';

			break;

		case 'number':
			echo "<div class='two columns hz-textbox'>" .
			     "<input type='number' name='$name' id='$id' value='$value' placeholder='" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "' />" .
			     "</div>";
			break;

		case 'url':
			echo "<div class='two columns hz-textbox'>" .
			     "<input type='number' inputmode='url' name='$name' id='$id' value='$value' placeholder='" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "' />" .
			     "</div>";
			break;

		case 'textbox':

			echo "<div class='four mobile-four columns'>" .
			     "<label class='left inline' for='$id'>" . sweet_translate_dynamic( $f->label, 'sweetdate' ) . "</label>" .
			     "</div>";
			echo "<div class='eight mobile-four columns kleo-text'>" .
			     "<input type='text' name='$name' id='$id' value='$value' />" .
			     "</div>";
			break;
		default:

			$field_template = 'members/bps-' . $display . '-form-field.php';
			$located     = bp_locate_template( $field_template );
			if ( $located ) {
				include $located;
			} else {
				?>
                <p class="bps-error"><?php echo "BP Profile Search: unknown display <em>$display</em> for field <em>$f->name</em>."; ?></p>
				<?php
			}
			break;
	}

	do_action( 'kleo_bp_search_add_data' );

	if ( ! empty ( $f->description ) && $f->description != '-' ) {
		echo "<div class='seven mobile-four columns kleo-text'>";
		echo "<label class='inline'>$f->description</label>\n";
		echo "</div>";
	}

	echo "</div>\n";
}

echo "<div class='submit'>\n"; ?>
    <div class="row">
        <!-- <div class="six columns">
			<a id="btnreset" class="button btn-default" href="javascript:void(0);"><?php _e( "RESET", 'sweetdate' ); ?></a>
		</div> -->
		<div class="six columns">
            <button type="submit" class="button radius btn-red" ><?php _e( "FIND", 'sweetdate' ); ?></button>
        </div>
    </div>
<?php
echo "</div>\n";
echo "</form>\n"; ?>
<script>
jQuery(document).ready(function(){
	$('select#field_1619').on('change', function (e) {
		var optionSelected = $("option:selected", this);
		var valueSelected = this.value;
		str = valueSelected.replace(/\s/g, '').toLowerCase();
		str = str.replace("\\", "");
		str = str.replace("\/", "");
		str = str.replace("/", "");
		$('div#foodtype, div#specialty, div#musical, div#varietyentertainment, div#equipmentpersonel,div#art, div#crafts').hide();
		$('div#'+str).show();
	});

		jQuery("#btnreset").click(function() {
		// alert('Clear');
		jQuery(".form-search")[0].reset();		
		jQuery(".custom.dropdown ul li").removeClass("selected");	
		jQuery(".custom.dropdown .current").html();
	});	
	
	$(document).on("change","#<?php echo $form_id; ?>  input , #<?php echo $form_id; ?>  select",function(e) {	
		$.ajax({
    		  url: ajaxurl,
	          type: 'POST',
			  data:$("#<?php echo $form_id; ?>").serialize() + "&action=" + "sp_members_filter_custom",			  
			  /* 
	          data: {                        
                    action  : 'sp_members_filter_custom',     
                }, */
	          dataType: 'html',
				success: function(data, textStatus, jqXHR) {	  	 
					console.log(data);					
					$('#members-list').html(data);
				}
		});		
	});
})
</script>
<style type="text/css">
	div#foodtype, div#specialty, div#musical, div#varietyentertainment, div#equipmentpersonel, div#art, div#crafts {display: none;}
</style>
<?php
if ( $F->location != 'directory' ) {
	echo "</div>\n";
}
return 'end_of_template 4.9';
