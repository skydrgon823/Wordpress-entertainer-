<?php

/*
 * BP Profile Search - form template 'bps-form-default'
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
	<span class="bps-form-title"> <?php echo $F->title; ?></span>
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
			<label for="<?php echo $id; ?>" class="bps-label">
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

		wp_enqueue_script ($f->script_handle);
		wp_enqueue_script ('bps-template');

		$of = __('of', 'bp-profile-search');
		$km = __('km', 'bp-profile-search');
		$miles = __('miles', 'bp-profile-search');
		$placeholder = __('Start typing, then select a location', 'bp-profile-search');
		$icon_title = __('get current location', 'bp-profile-search');
?>
			<input type="number" min="1" name="<?php echo $name.'[distance]'; ?>" value="<?php echo $value['distance']; ?>">
			<select name="<?php echo $name.'[units]'; ?>">
				<option value="km" <?php selected ($value['units'], "km"); ?>><?php echo $km; ?></option>
				<option value="miles" <?php selected ($value['units'], "miles"); ?>><?php echo $miles; ?></option>
			</select>
			<span><?php echo $of; ?></span>
			<input type="search" id="<?php echo $id; ?>" name="<?php echo $name.'[location]'; ?>" value="<?php echo $value['location']; ?>"
				placeholder="<?php echo $placeholder; ?>"><span id="<?php echo $id; ?>_icon" title="<?php echo $icon_title; ?>"
				style="vertical-align: text-bottom; cursor: pointer;" class="dashicons dashicons-location"></span><br>
			<input type="hidden" id="<?php echo $id; ?>_lat" name="<?php echo $name.'[lat]'; ?>" value="<?php echo $value['lat']; ?>">
			<input type="hidden" id="<?php echo $id; ?>_lng" name="<?php echo $name.'[lng]'; ?>" value="<?php echo $value['lng']; ?>">

			<script>
				jQuery(function ($) {
					bps_autocomplete('<?php echo $id; ?>', '<?php echo $id; ?>_lat', '<?php echo $id; ?>_lng');
					$('#<?php echo $id; ?>_icon').click(function () {
						bps_locate('<?php echo $id; ?>', '<?php echo $id; ?>_lat', '<?php echo $id; ?>_lng')
					});
				});
			</script>
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
			<em class="bps-description"><?php echo $f->description; ?></em>
		</div><br>
<?php
}
?>
		<div>
			<button type="submit"><?php echo $F->strings['search']; ?></button>
		</div>
	</form>

<?php
if (!empty ($options['theme']))
{
?>
</div><br>
<?php
}

return 'end_of_template 4.9';
