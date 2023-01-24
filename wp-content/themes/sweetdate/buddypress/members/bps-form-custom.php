<?php

/*
 * BP Profile Search - form template 'bps-form-custom'
 *
 * See http://dontdream.it/bp-profile-search/form-templates/ if you wish to modify this template or develop a new one.
 *
 * Move new or modified templates to the 'buddypress/members' directory in your theme's root,
 * otherwise they will be overwritten during the next BP Profile Search update.
 *
 */

// 1st section: set the default value of the template options

if (!isset ($options['theme']))  $options['theme'] = 'base';
if (!isset ($options['collapsible']))  $options['collapsible'] = 'Yes';

// 2nd section: display the form to select the template options

if (is_admin ())
{
?>
	<p><strong><?php _e('jQuery UI Theme', 'bp-profile-search'); ?></strong></p>
	<select id="ui_theme" name="options[theme]">
	<?php foreach (bps_jquery_ui_themes() as $theme => $name) { ?>
		<option value="<?php echo $theme; ?>" <?php selected ($options['theme'], $theme); ?>><?php echo $name; ?></option>
	<?php } ?>
	</select>

	<p><strong><?php _e('Collapsible Form', 'bp-profile-search'); ?></strong></p>
	<select id="ui_collapsible" name="options[collapsible]">
		<option value='Yes' <?php selected ($options['collapsible'], 'Yes'); ?>><?php _e('Yes', 'bp-profile-search'); ?></option>
		<option value='No' <?php selected ($options['collapsible'], 'No'); ?>><?php _e('No', 'bp-profile-search'); ?></option>
	</select>

	<script>
		jQuery(function ($) {
			var update_collapsible = function () {
				if ($('#ui_theme').val() == '') {
					$('#ui_collapsible').val('No');
					$('#ui_collapsible').attr('disabled', true);
				}
				else {
					$('#ui_collapsible').attr('disabled', false);
				}
			};
			update_collapsible();
			$('#ui_theme').change(update_collapsible);
		});
	</script>
<?php
	return 'end_of_options 4.9';
}

// 3rd section: display the search form

$F = bps_escaped_form_data ($version = '4.9');
wp_register_script ('bps-template', plugins_url ('bp-profile-search/bps-template.js'), array (), BPS_VERSION);
?>

<style>
	.bps-form label {
		display: inline;
	}
	.bps-form input {
		display: inline;
	}
	.bps-form input[type=number] {
		width: 5em;
	}
	.bps-form .bps-error {
		color: red;
	}
</style>
<?php

$toggle_id = 'bps_toggle' . $F->unique_id;
$form_id   = 'bps_' . $F->location . $F->unique_id;

if (!empty ($options['theme']))
{
	$accordion = 'bps_accordion_'. $F->unique_id;
	if ($F->errors)  $options['collapsible'] = 'No';
	wp_enqueue_script ('jquery-ui-accordion');
	wp_enqueue_style ('jquery-ui-theme', 'https://code.jquery.com/ui/1.12.1/themes/'. $options['theme']. '/jquery-ui.min.css');
?>
<script>
	jQuery(function ($) {
		$('#<?php echo $accordion; ?>').accordion({
			icons: {
				"header": "ui-icon-plus",
				"activeHeader": "<?php echo ($options['collapsible'] == 'Yes')? 'ui-icon-minus': 'ui-icon-blank'; ?>",
			},
			active: false,
			collapsible: <?php echo ($options['collapsible'] == 'Yes')? 'true': 'false'; ?>,
		});
	});
</script>

<div id="<?php echo $accordion; ?>">
	<span class="bps-form-title"> <?php  echo $F->title; ?></span>
<?php
}
?>
	<form action="<?php echo $F->action; ?>" method="<?php echo $F->method; ?>" id="<?php echo $F->unique_id; ?>" class="bps-form">

<?php
foreach ($F->fields as $f)
{
	$id = $f->unique_id;
	$name = $f->html_name;
	$value = $f->value;
	$display = $f->display;

	if ($display == 'none')  continue;

	if ($display == 'hidden')
	{
?>
		<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
<?php
		continue;
	}
?>
		<div id="<?php echo $id; ?>_wrap" class="bps-<?php echo $display; ?>">
			<label for="<?php echo $id; ?>" class="bps-label hidden">
				<strong><?php echo $f->label; ?></strong><span> <?php echo $f->mode; ?></span>
			</label><br>
<?php
	if (!empty ($f->error_message))
	{
?>
			<span class="bps-error"><?php echo $f->error_message; ?></span><br>
<?php
	}

	switch ($display)
	{
	case 'range':
?>
			<input type="text" style="width: 5em;" id="<?php echo $id; ?>" name="<?php echo $name.'[min]'; ?>" value="<?php echo $value['min']; ?>">
			<span> - </span>
			<input type="text" style="width: 5em;" name="<?php echo $name.'[max]'; ?>" value="<?php echo $value['max']; ?>"><br>
<?php
	break;
	case 'integer-range':
?>
			<input type="number" id="<?php echo $id; ?>" name="<?php echo $name.'[min]'; ?>" value="<?php echo $value['min']; ?>">
			<span> - </span>
			<input type="number" name="<?php echo $name.'[max]'; ?>" value="<?php echo $value['max']; ?>"><br>
<?php
	break;
	case 'date-range':
?>
			<input type="date" id="<?php echo $id; ?>" name="<?php echo $name.'[min]'; ?>" value="<?php echo $value['min']; ?>">
			<span> - </span>
			<input type="date" name="<?php echo $name.'[max]'; ?>" value="<?php echo $value['max']; ?>"><br>
<?php
	break;
	case 'range-select':
?>
			<select style="min-width: 5em;" id="<?php echo $id; ?>" name="<?php echo $name.'[min]'; ?>">
			<?php foreach ($f->options as $key => $label) { ?>
				<option <?php if (strval ($key) == $value['min']) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $label; ?> </option>
			<?php } ?>
			</select>
			<span> - </span>
			<select style="min-width: 5em;" name="<?php echo $name.'[max]'; ?>">
			<?php foreach ($f->options as $key => $label) { ?>
				<option <?php if (strval ($key) == $value['max']) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $label; ?> </option>
			<?php } ?>
			</select><br>
<?php
	break;
	case 'textbox':
?>
			<input type="search" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>"><br>
<?php
	break;
	case 'integer':
?>
			<input type="number" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>"><br>
<?php
	break;
	case 'date':
?>
			<input type="date" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>"><br>
<?php
	break;
	case 'distance':
			$of = esc_html__( 'of', 'bp-profile-search' );
			$km          = esc_html__( 'km', 'bp-profile-search' );
			$miles       = esc_html__( 'miles', 'bp-profile-search' );
			$placeholder = esc_html__( 'Start typing, then select a location', 'bp-profile-search' );
			$icon_url    = plugins_url( 'bp-profile-search/templates/members/locator.png' );
			$icon_title  = esc_html__( 'Get current location', 'bp-profile-search' );

			$distance = array('10'=>'10', '25'=>'25', '50'=>'50','100'=>'100', '150'=>'150', '200'=>'200','250'=>'250','500'=>'500');

			echo '<div class="search-header">' .
			     "<label class='left inline'>" . $f->label . "</label>" .
			     '</div>';
			?>
            <div class="distance-search-content">
                <div class="row">
                    <div class="twelve mobile-twelve columns kleo-text">
                        <div class="field_type_bds_location" style="position: relative;">
							<input type="text" id="<?php echo esc_attr( $id ); ?>" class="bps-location-input" name="<?php echo esc_attr( $name).'[location]'; ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" aria-labelledby="<?php echo esc_attr( $name ).'[location]'; ?>-1" aria-describedby="<?php echo esc_attr( $name ).'[location]'; ?>-3">
							<img class="bps-locator-icon" id="<?php echo esc_attr( $id ); ?>_icon" style="cursor: pointer;" src="<?php echo esc_attr( $icon_url ); ?>" title="<?php echo esc_attr( $icon_title ); ?>">                          
							<small>Click on locator icon to Get current location</small>
                        </div>
					</div> 
				</div>
				<div style="clear:both"></div>
				<label>Distance</label>
				<div style="clear:both"></div>
				<div class="row">
					<div class="eight mobile-twelve columns kleo-selectbox">
						<select class='expand' name="<?php echo $name.'[distance]'; ?>">
						<option value="">---</option>
						<?php 
							foreach($distance as $key => $val){
								$selected = ( $val == $value['distance'] ) ? "selected='selected'" : "";
							  echo '<option value="'. $val .'" ' . $selected . ' >'. $key .'</option>';
						 }
						?>
					 </select>
					</div>
					<div class="four mobile-twelve columns kleo-selectbox">
						<select class='expand' name="<?php echo $name.'[units]'; ?>">
							<option value="km" <?php selected ($value['units'], "km"); ?>><?php echo $km; ?></option>
							<option value="miles" <?php selected ($value['units'], "miles"); ?>><?php echo $miles; ?></option>
						</select>
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
	case 'selectbox':
?>
			<select id="<?php echo $id; ?>" name="<?php echo $name; ?>">
			<?php foreach ($f->options as $key => $label) { ?>
				<option <?php if (strval ($key) == $value) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $label; ?> </option>
			<?php } ?>
			</select><br>
<?php
	break;
	case 'multiselectbox':
?>
			<select id="<?php echo $id; ?>" name="<?php echo $name.'[]'; ?>" multiple="multiple" size="<?php echo $f->multiselect_size; ?>">
			<?php foreach ($f->options as $key => $label) { ?>
				<option <?php if (in_array ($key, $value)) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $label; ?></option>
			<?php } ?>
			</select><br>
<?php
	break;
	case 'radio':

		wp_enqueue_script ('bps-template');
?>
			<?php foreach ($f->options as $key => $label) { ?>
				<label><input type="radio" <?php if (strval ($key) == $value) echo 'checked="checked"'; ?>
					name="<?php echo $name; ?>" value="<?php echo $key; ?>"> <?php echo $label; ?></label><br>
			<?php } ?>
			<a href="javascript:bps_clear_radio('<?php echo $id; ?>_wrap')"><?php echo $F->strings['clear']; ?></a><br>
<?php
	break;
	case 'checkbox':
?>
			<?php foreach ($f->options as $key => $label) { ?>
				<label><input type="checkbox" <?php if (in_array ($key, $value)) echo 'checked="checked"'; ?>
					name="<?php echo $name.'[]'; ?>" value="<?php echo $key; ?>"> <?php echo $label; ?></label><br>
			<?php } ?>
<?php
	break;
	default:

		$field_template = 'members/bps-'. $display. '-form-field.php';
		$located = bp_locate_template ($field_template);
		if ($located)
			include $located;
		else
		{
?>
			<p class="bps-error"><?php echo "BP Profile Search: unknown display <em>$display</em> for field <em>$f->name</em>."; ?></p>
<?php
		}
	break;
	}
?>
			<em class="bps-description hidden"><?php echo $f->description; ?></em>
		</div><br>
<?php
}
?>
<span id="list"></span>
<div>
			<button type="submit" class="btn btn-info"><?php echo $F->strings['search']; ?></button>
		</div>
	</form>
<script>
jQuery(document).ready(function(){ 
	$(document).on("change","#<?php echo $form_id; ?>  input , #<?php echo $form_id; ?>  select",function(e) {	
		$.ajax({
    		  url: ajaxurl,
	          type: 'POST',
			  data:$("#<?php echo $form_id; ?>").serialize() + "&action=" + "custom_members_filter",			  
	          dataType: 'html',
				success: function(data, textStatus, jqXHR) {	  	 
					console.log(data);					
					$('#list').html(data);
				}
		});		
	});
})
</script>
<?php
if (!empty ($options['theme'])) { ?>
</div><br>
<?php }
return 'end_of_template 4.9';