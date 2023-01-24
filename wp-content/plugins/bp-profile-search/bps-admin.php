<?php

add_action ('add_meta_boxes', 'bps_add_meta_boxes');
function bps_add_meta_boxes ()
{
	add_meta_box ('bps_fields_box', __('Form Fields', 'bp-profile-search'), 'bps_fields_box', 'bps_form', 'normal');
	add_meta_box ('bps_attributes', __('Form Settings', 'bp-profile-search'), 'bps_attributes', 'bps_form', 'side');
	add_meta_box ('bps_template', __('Form Template', 'bp-profile-search'), 'bps_template', 'bps_form', 'side');
	add_meta_box ('bps_persistent', __('Persistent Search', 'bp-profile-search'), 'bps_persistent', 'bps_form', 'side');
}

function bps_fields_box ($post)
{
	$meta = bps_meta ($post->ID);
?>
	<div id="field_box" class="field_box">
		<p>
			<span class="bps_col1"></span>
			<span class="bps_col2"><strong>&nbsp;<?php _e('Field', 'bp-profile-search'); ?></strong></span>&nbsp;
			<span class="bps_col3"><strong>&nbsp;<?php _e('Label', 'bp-profile-search'); ?></strong></span>&nbsp;
			<span class="bps_col4"><strong>&nbsp;<?php _e('Description', 'bp-profile-search'); ?></strong></span>&nbsp;
			<span class="bps_col5"><strong>&nbsp;<?php _e('Search Mode', 'bp-profile-search'); ?></strong></span>
		</p>

	<?php foreach ($meta['field_code'] as $k => $code) { ?>
		<div id="field_div<?php echo $k; ?>" class="sortable"><?php bps_field_row ($code, $k, $meta); ?></div>
	<?php } ?>

	</div>
	<input type="hidden" id="field_next" value="<?php echo count ($meta['field_code']); ?>" />
	<p><input id="add_field" type="submit" value="<?php _e('Add Field', 'bp-profile-search'); ?>"></p>
<?php
}

function bps_field_row ($code, $k, $meta = false)
{
	list ($groups, $fields) = bps_get_fields ();
	if (empty ($fields[$code]))  return false;

	$field = $fields[$code];
	$label = $meta? esc_attr ($meta['field_label'][$k]): '';
	$default = esc_attr ($field->name);
	$showlabel = empty ($label)? "placeholder=\"$default\"": "value=\"$label\"";
	$desc = $meta? esc_attr ($meta['field_desc'][$k]): '';
	$default = esc_attr ($field->description);
	$showdesc = empty ($desc)? "placeholder=\"$default\"": "value=\"$desc\"";
?>
	<span class="bps_col1" title="<?php _e('drag and drop to reorder fields', 'bp-profile-search'); ?>">&nbsp;&#x21C5;</span>
	<?php _bps_field_select ($groups, "bps_options[field_name][$k]", "field_name$k", $code); ?>
	<input class="bps_col3" type="text" name="bps_options[field_label][<?php echo $k; ?>]" id="field_label<?php echo $k; ?>" <?php echo $showlabel; ?> />
	<input class="bps_col4" type="text" name="bps_options[field_desc][<?php echo $k; ?>]" id="field_desc<?php echo $k; ?>" <?php echo $showdesc; ?> />
	<?php _bps_filter_select ($field, "bps_options[field_mode][$k]", "field_mode$k", $meta? $meta['field_mode'][$k]: 'none'); ?>

	<a class="remove_field delete" href="javascript:void(0)"><?php _e('Remove', 'bp-profile-search'); ?></a>
	<span class="spinner"></span>
<?php
	return true;
}

function bps_field_selector ($k)
{
	list ($groups, ) = bps_get_fields ();
?>
	<div id="field_div<?php echo $k; ?>" class="sortable">
		<span class="bps_col1" title="<?php _e('drag and drop to reorder fields', 'bp-profile-search'); ?>">&nbsp;&#x21C5;</span>
		<?php _bps_field_select ($groups, "bps_options[field_name][$k]", "field_name$k", $code, true); ?>
		<a class="remove_field delete" href="javascript:void(0)"><?php _e('Remove', 'bp-profile-search'); ?></a>
		<span class="spinner"></span>
	</div>
<?php
}

function _bps_field_select ($groups, $name, $id, $value, $first = false)
{
	echo "<select class='bps_col2' name='$name' id='$id'>\n";
	if ($first)
		echo "<option value='0'>". __('select a field', 'bp-profile-search'). "</option>\n";

	foreach ($groups as $group => $fields)
	{
		$group = esc_attr ($group);
		echo "<optgroup label='$group'>\n";
		foreach ($fields as $field)
		{
			$selected = $field['id'] == $value? " selected='selected'": '';
			echo "<option value='$field[id]'$selected>$field[name]</option>\n";
		}
		echo "</optgroup>\n";
	}
	echo "</select>\n";
}

function _bps_filter_select ($f, $name, $id, $value)
{
	$filters = bps_Fields::get_filters ($f);

	echo "<select class='bps_col5' name='$name' id='$id'>\n";
	foreach ($filters as $key => $label)
	{
		$selected = $value == $key? " selected='selected'": '';
		echo "<option value='$key'$selected>$label</option>\n";
	}
	echo "</select>\n";
}

function bps_attributes ($post)
{
	$options = bps_meta ($post->ID);
?>
	<p><strong><?php _e('Target Directory', 'bp-profile-search'); ?></strong></p>
	<select name="options[action]" id="action">
<?php
	$dirs = bps_directories ();
	foreach ($dirs as $dir)
	{
?>
		<option value='<?php echo $dir->id; ?>' <?php selected ($options['action'], $dir->id); ?>><?php echo esc_html($dir->title); ?></option>
<?php
	}
?>
	</select>

	<p><strong><?php _e('Add Form to Directory', 'bp-profile-search'); ?></strong></p>
	<select name="options[directory]" id="directory">
		<option value='Yes' <?php selected ($options['directory'], 'Yes'); ?>><?php _e('Yes', 'bp-profile-search'); ?></option>
		<option value='No' <?php selected ($options['directory'], 'No'); ?>><?php _e('No', 'bp-profile-search'); ?></option>
	</select>

	<p><strong><?php _e('Form Method', 'bp-profile-search'); ?></strong></p>
	<select name="options[method]" id="method">
		<option value='POST' <?php selected ($options['method'], 'POST'); ?>><?php _e('POST', 'bp-profile-search'); ?></option>
		<option value='GET' <?php selected ($options['method'], 'GET'); ?>><?php _e('GET', 'bp-profile-search'); ?></option>
	</select>

	<p><?php _e('Need help? Use the Help tab above the screen title.'); ?></p>
<?php
}

function bps_template ($post)
{
	$form = $post->ID;
	$meta = bps_meta ($form);
	$current_template = bps_valid_template ($meta['template']);
?>
	<p><strong><?php _e('Form Template', 'bp-profile-search'); ?></strong></p>
	<select id="template" name="options[template]">
	<?php foreach (bps_templates() as $template) { ?>
		<option value='<?php echo $template; ?>' <?php selected ($current_template, $template); ?>><?php echo $template; ?></option>
	<?php } ?>
	</select>
	<span class="spinner"></span>

	<div id="template_options">
		<?php bps_template_options ($form, $current_template); ?>
	</div>
	<input type="hidden" id="form_id" value="<?php echo $form; ?>">
<?php
}

add_action ('wp_ajax_bps_field_selector', 'bps_ajax_field_selector');
function bps_ajax_field_selector ()
{
	bps_field_selector ($_POST['counter']);
	wp_die ();
}

add_action ('wp_ajax_bps_field_row', 'bps_ajax_field_row');
function bps_ajax_field_row ()
{
	$counter = str_replace ('field_div', '', $_POST['container']);
	bps_field_row ($_POST['field'], $counter);
	wp_die ();
}

add_action ('wp_ajax_bps_template_options', 'bps_ajax_template_options');
function bps_ajax_template_options ()
{
	bps_template_options ($_POST['form'], $_POST['template']);
	wp_die ();
}

function bps_template_options ($form, $template)
{
	$located = bps_template_info ($template);
	if ($located === false)  return false;

	$meta = bps_meta ($form);
	$options = isset ($meta['template_options'][$template])? $meta['template_options'][$template]: array ();

	ob_start ();
	$response = include $located;
	$output = ob_get_clean ();

	if ($response == 'end_of_options 4.9')
	{
		echo $output;
		$located = str_replace (WP_CONTENT_DIR, '', $located);
		echo "<!-- by $located -->";
	}

	return true;
}

function bps_persistent ($post)
{
	$persistent = bps_get_option ('persistent', '1');
?>
	<select name="options[persistent]" id="persistent">
		<option value='1' <?php selected ($persistent, '1'); ?>><?php _e('Yes', 'bp-profile-search'); ?></option>
		<option value='0' <?php selected ($persistent, '0'); ?>><?php _e('No', 'bp-profile-search'); ?></option>
	</select>
<?php
}

add_action ('save_post', 'bps_update_meta', 10, 3);
function bps_update_meta ($form, $post, $update)
{
	if ($post->post_type != 'bps_form' || $post->post_status != 'publish')  return false;
	if (empty ($_POST['options']) && empty ($_POST['bps_options']))  return false;

	$old_meta = bps_meta ($form);

	$meta = array ();
	$meta['field_code'] = array ();
	$meta['field_label'] = array ();
	$meta['field_desc'] = array ();
	$meta['field_mode'] = array ();

	$codes = array ('0');
	$posted = isset ($_POST['bps_options'])? $_POST['bps_options']: array ();
	if (isset ($posted['field_name']))  foreach ($posted['field_name'] as $k => $code)
	{
		if (in_array ($code, $codes))  continue;

		$codes[] = $code;
		$meta['field_code'][] = $code;
		$meta['field_label'][] = stripslashes ($posted['field_label'][$k]);
		$meta['field_desc'][] = stripslashes ($posted['field_desc'][$k]);
		$meta['field_mode'][] = $posted['field_mode'][$k];

		bps_set_wpml ($form, $code, 'label', end ($meta['field_label']));
		bps_set_wpml ($form, $code, 'comment', end ($meta['field_desc']));
	}

	bps_set_option ('persistent', $_POST['options']['persistent']);
	unset ($_POST['options']['persistent']);

	foreach (array ('method', 'action', 'directory', 'template') as $key)
	{
		$meta[$key] = stripslashes ($_POST['options'][$key]);
		unset ($_POST['options'][$key]);
	}

	if (bps_is_template ($meta['template']))
	{
		$template_options = stripslashes_deep ($_POST['options']);
		$meta['template_options'] = $old_meta['template_options'];
		$meta['template_options'][$meta['template']] = $template_options;
	}

	bps_set_wpml ($form, '-', 'title', $post->post_title);
	update_post_meta ($form, 'bps_options', $meta);

	return true;
}

function bps_set_option ($name, $value)
{
	$settings = get_option ('bps_settings');
	if ($settings === false)
		$settings = new stdClass;

	$settings->{$name} = $value;
	update_option ('bps_settings', $settings);
}

function bps_get_option ($name, $default)
{
	$settings = get_option ('bps_settings');
	return isset ($settings->{$name})? $settings->{$name}: $default;
}
