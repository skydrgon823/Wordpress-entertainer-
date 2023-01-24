<?php

add_filter ('bp_get_template_stack', 'bps_template_stack', 20);
function bps_template_stack ($stack)
{
	$stack[] = dirname (__FILE__). '/templates';
	return $stack;
}

function bps_templates ()
{
	$templates = array ('members/bps-form-default');
	return apply_filters ('bps_templates', $templates);
}

function bps_default_template ()
{
	$templates = bps_templates ();
	return $templates[0];
}

function bps_is_template ($template)
{
	$templates = bps_templates ();
	return in_array ($template, $templates);
}

function bps_valid_template ($template)
{
	return bps_is_template ($template)? $template: bps_default_template ();
}

function bps_template_info ($template)
{
	$located = bp_locate_template ($template. '.php');
	if ($located === false)
	{
		echo '<strong style="color:red;">'. $template. '</strong><br>'. __('template not found', 'bp-profile-search');
		return false;
	}

	if (dirname ($located) == dirname (__FILE__). '/templates/members')
	{
		echo '<strong style="color:green;">'. $template. '</strong><br>'. __('built-in template', 'bp-profile-search');
		return $located;
	}

	ob_start ();
	$response = include $located;
	ob_get_clean ();

	$path = str_replace (WP_CONTENT_DIR. '/', '', $located);
	$path = str_replace ($template. '.php', '', $path);

	if ($response == 'end_of_options 4.9')
		echo '<strong style="color:blue;">'. $template. '</strong><br>'. sprintf (__('custom template located in: %1$s', 'bp-profile-search'), $path);
	else
	{
		echo '<strong style="color:red;">'. $template. '</strong><br>'. sprintf (__('unsupported template located in: %1$s', 'bp-profile-search'), $path);
		echo '<br><a href="https://dontdream.it/bp-profile-search-5-3/">'. __('more information...', 'bp-profile-search'). '</a>';
	}
	return $located;
}

function bps_check_enqueued_scripts ($form, $located)
{
	$done = array ('distance' => false, 'radio' => false);
	list ($fields, ) = bps_get_form_fields ($form);

	foreach ($fields as $f)
	{
		switch ($f->display)
		{
		case 'distance':

			if ($done['distance'])  break;
			$done['distance'] = true;

			if (!wp_script_is ($f->script_handle, 'enqueued'))
				wp_enqueue_script ($f->script_handle);

			if (!wp_script_is ('bps-template', 'enqueued'))
				wp_enqueue_script ('bps-template', plugins_url ('bp-profile-search/bps-template.js'), array (), BPS_VERSION);

			break;

		case 'radio':

			if ($done['radio'])  break;
			$done['radio'] = true;

			if (!wp_script_is ('bps-template', 'enqueued'))
				wp_enqueue_script ('bps-template', plugins_url ('bp-profile-search/bps-template.js'), array (), BPS_VERSION);

			break;
		}
	}
}

function bps_call_template ($template, $args = array ())
{
	$located = bp_locate_template ($template. '.php');

	if ($located === false)
		return bps_error ('template_not_found', $template);

	$GLOBALS['bps_template_args'][] = $args;

	echo "\n<!-- BP Profile Search ". BPS_VERSION. " $template -->\n";
	if (bps_debug ())
	{
		$path = str_replace (WP_CONTENT_DIR, '', $located);
		echo "<!--\n";
		echo "path $path\n";
		echo "args "; print_r ($args);
		echo "-->\n";
	}

	include $located;

	echo "\n<!-- BP Profile Search end $template -->\n";
	array_pop ($GLOBALS['bps_template_args']);

	return true;
}

function bps_call_form_template ($form, $location)
{
	$meta = bps_meta ($form);

	if (empty ($meta['field_code']))
		return bps_error ('form_empty_or_nonexistent', $form);

	$args = array ($form, $location);
	$template = bps_valid_template ($meta['template']);
	$located = bp_locate_template ($template. '.php');

	if ($located === false)
		return bps_error ('template_not_found', $template);

	$GLOBALS['bps_template_args'][] = $args;
	$options = isset ($meta['template_options'][$template])? $meta['template_options'][$template]: array ();

	echo "\n<!-- BP Profile Search ". BPS_VERSION. " $template -->\n";
	if (bps_debug ())
	{
		$path = str_replace (WP_CONTENT_DIR, '', $located);
		echo "<!--\n";
		echo "path $path\n";
		echo "args "; print_r ($args);
		echo "options "; print_r ($options);
		echo "-->\n";
	}

	include $located;
//	bps_check_enqueued_scripts ($form, $located);

	echo "\n<!-- BP Profile Search end $template -->\n";
	array_pop ($GLOBALS['bps_template_args']);

	return true;
}

function bps_template_args ()
{
	return end ($GLOBALS['bps_template_args']);
}

function bps_jquery_ui_themes ()
{
	$themes = array (
		'' => __('no jQuery UI', 'bp-profile-search'),
		'base' => 'Base',
		'black-tie' => 'Black Tie',
		'blitzer' => 'Blitzer',
		'cupertino' => 'Cupertino',
		'dark-hive' => 'Dark Hive',
		'dot-luv' => 'Dot Luv',
		'eggplant' => 'Eggplant',
		'excite-bike' => 'Excite Bike',
		'flick' => 'Flick',
		'hot-sneaks' => 'Hot Sneaks',
		'humanity' => 'Humanity',
		'le-frog' => 'Le Frog',
		'mint-choc' => 'Mint Choc',
		'overcast' => 'Overcast',
		'pepper-grinder' => 'Pepper Grinder',
		'redmond' => 'Redmond',
		'smoothness' => 'Smoothness',
		'south-street' => 'South Street',
		'start' => 'Start',
		'sunny' => 'Sunny',
		'swanky-purse' => 'Swanky Purse',
		'trontastic' => 'Trontastic',
		'ui-darkness' => 'UI darkness',
		'ui-lightness' => 'UI lightness',
		'vader' => 'Vader',
	);	

	return apply_filters ('bps_jquery_ui_themes', $themes);
}

function bps_escaped_form_data ($version = '')
{
	if ($version == '4.9')	return bps_escaped_form_data49 ();

	return false;
}

function bps_escaped_filters_data ($version = '')
{
	if ($version == '5.4')	return bps_escaped_filters_data54 ();

	return false;
}

function bps_set_hidden_field ($name, $value)
{
	$new = new stdClass;
	$new->display = 'hidden';
	$new->html_name = $name;
	$new->value = $value;

	return $new;
}

function bps_unique_id ($id)
{
	static $k = array ();

	$k[$id] = isset ($k[$id])? $k[$id] + 1: 0;
	$unique = $k[$id]? $id. '_'. $k[$id]: $id;
	
	return apply_filters ('bps_unique_id', $unique, $id);
}
