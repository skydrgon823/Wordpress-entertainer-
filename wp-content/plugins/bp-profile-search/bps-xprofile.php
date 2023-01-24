<?php

add_filter ('bps_add_fields', 'bps_xprofile_setup');
function bps_xprofile_setup ($fields)
{ 
	global $group, $field;

	if (!bp_is_active ('xprofile'))  return $fields;

	$args = array ('hide_empty_fields' => false, 'member_type' => bp_get_member_types (), 'user_id' => false);
	if (bp_has_profile ($args))
	{
		while (bp_profile_groups ())
		{
			bp_the_profile_group ();
			$group_name = str_replace ('&amp;', '&', stripslashes ($group->name));

			while (bp_profile_fields ())
			{
				bp_the_profile_field ();
				$f = new stdClass;

				$f->group = $group_name;
				$f->id = $field->id;
				$f->code = 'field_'. $field->id;
				$f->name = str_replace ('&amp;', '&', stripslashes ($field->name));
				$f->name = bps_wpml (0, $f->id, 'name', $f->name);
				$f->description = str_replace ('&amp;', '&', stripslashes ($field->description));
				$f->description = bps_wpml (0, $f->id, 'description', $f->description);
				$f->type = $field->type;

				$f->format = bps_xprofile_format ($field->type, $field->id);
				$f->search = 'bps_xprofile_search';
				$f->sort_directory = 'bps_xprofile_sort_directory';
				$f->get_value = 'bps_xprofile_get_value';

				$f->options = bps_xprofile_options ($field);
				foreach ($f->options as $key => $label)
					$f->options[$key] = bps_wpml (0, $f->id, 'option', $label);

				if ($f->format == 'custom')
					do_action ('bps_custom_field', $f);

				if ($f->format == 'set')
					unset ($f->sort_directory);

				$fields[] = $f;
			}
		}
	}

	return $fields;
}

function bps_xprofile_search ($f)
{
	global $bp, $wpdb;
//echo '<pre>';print_r($f);die();
	$value = $f->value;
	$filter = $f->format. '_'.  ($f->filter == ''? 'is': $f->filter);

	$sql = array ('select' => '', 'where' => array ());
	$sql['select'] = "SELECT user_id FROM {$bp->profile->table_name_data}";
	$sql['where']['field_id'] = $wpdb->prepare ("field_id = %d", $f->id);

	switch ($filter)
	{
	case 'integer_range':
		if (isset ($value['min']))  $sql['where']['min'] = $wpdb->prepare ("value >= %d", $value['min']);
		if (isset ($value['max']))  $sql['where']['max'] = $wpdb->prepare ("value <= %d", $value['max']);
		break;

	case 'decimal_range':
		if (isset ($value['min']))  $sql['where']['min'] = $wpdb->prepare ("value >= %f", $value['min']);
		if (isset ($value['max']))  $sql['where']['max'] = $wpdb->prepare ("value <= %f", $value['max']);
		break;

	case 'date_range':
		if (isset ($value['min']))  $sql['where']['min'] = $wpdb->prepare ("DATE(value) >= %s", $value['min']);
		if (isset ($value['max']))  $sql['where']['max'] = $wpdb->prepare ("DATE(value) <= %s", $value['max']);
		break;

	case 'date_age_range':
		$day = date ('j');
		$month = date ('n');
		$year = date ('Y');

		if (isset ($value['max']))
		{
			$ymin = $year - $value['max'] - 1; 
			$sql['where']['age_min'] = $wpdb->prepare ("DATE(value) > %s", "$ymin-$month-$day");
		}
		if (isset ($value['min']))
		{
			$ymax = $year - $value['min'];
			$sql['where']['age_max'] = $wpdb->prepare ("DATE(value) <= %s", "$ymax-$month-$day");
		}
		break;

	case 'text_contains':
	case 'location_contains':
		$value = str_replace ('&', '&amp;', $value);
		$sql['where'][$filter] = bps_sql_expression ("value LIKE %s", $value, true);
		break;

	case 'text_like':
	case 'location_like':
		$value = str_replace ('&', '&amp;', $value);
		$value = str_replace ('\\\\%', '\\%', $value);
		$value = str_replace ('\\\\_', '\\_', $value);
		$sql['where'][$filter] = bps_sql_expression ("value LIKE %s", $value);
		break;

	case 'text_is':
	case 'location_is':
		$value = str_replace ('&', '&amp;', $value);
		$sql['where'][$filter] = bps_sql_expression ("value = %s", $value);
		break;

	case 'integer_is':
		$sql['where'][$filter] = bps_sql_expression ("value = %d", $value);
		break;

	case 'decimal_is':
		$sql['where'][$filter] = bps_sql_expression ("value = %f", $value);
		break;

	case 'date_is':
		$sql['where'][$filter] = bps_sql_expression ("DATE(value) = %s", $value);
		break;

	case 'text_one_of':
		$values = (array)$value;
		$parts = array ();
		foreach ($values as $value)
		{
			$value = str_replace ('&', '&amp;', $value);
			$parts[] = $wpdb->prepare ("value = %s", $value);
		}
		$sql['where'][$filter] = '('. implode (' AND ', $parts). ')';
		break;

	case 'set_match_any':
	case 'set_match_all':
		$values = (array)$value;
		$parts = array ();
		foreach ($values as $value)
		{
			$value = str_replace ('&', '&amp;', $value);
			$escaped = '%:"'. bps_esc_like ($value). '";%';
			$parts[] = $wpdb->prepare ("value LIKE %s", $escaped);
		}
		$match = ($filter == 'set_match_any')? ' OR ': ' AND ';
		$sql['where'][$filter] = '('. implode ($match, $parts). ')';
		break;

	default:
		return array ();
	}

	$sql = apply_filters ('bps_field_sql', $sql, $f);
	$query = $sql['select']. ' WHERE '. implode (' AND ', $sql['where']);
	
	$results = $wpdb->get_col ($query);
	return $results;
}

function bps_xprofile_sort_directory ($sql, $object, $f, $order)
{
	global $bp, $wpdb;

	$object->uid_name = 'user_id';
	$object->uid_table = $bp->profile->table_name_data;

	$sql['select'] = "SELECT u.user_id AS id FROM {$object->uid_table} u";
	$sql['where'] = str_replace ('u.ID', 'u.user_id', $sql['where']);
	$sql['where'][] = "u.user_id IN (SELECT ID FROM {$wpdb->users} WHERE user_status = 0)";
	$sql['where'][] = $wpdb->prepare ("u.field_id = %d", $f->id);
	$sql['orderby'] = "ORDER BY u.value";
	$sql['order'] = $order;

	return $sql;
}

function bps_xprofile_get_value ($f)
{
	global $members_template;

	if ($members_template->current_member == 0)
	{
		$users = wp_list_pluck ($members_template->members, 'ID');
		BP_XProfile_ProfileData::get_value_byid ($f->id, $users);
	}

	$value = BP_XProfile_ProfileData::get_value_byid ($f->id, $members_template->member->ID);

	if ($f->format == 'set')
	{
		$f->d_format = 'array';
		$f->d_value = unserialize ($value);
	}
	else if ($f->format == 'date' && isset ($f->filter) && $f->filter == 'age_range')
	{
		$f->d_format = 'age';
		$f->d_value = date_diff(date_create ($value), date_create ('today'))->y;
	}
	else
	{
		$f->d_format = $f->format;
		$f->d_value = isset ($f->options[$value])? $f->options[$value]: $value;
	}
}

function bps_xprofile_format ($type, $field_id)
{
	$formats = array
	(
		'textbox'			=> array ('text', 'decimal'),
		'number'			=> array ('integer'),
		'telephone'			=> array ('text'),
		'url'				=> array ('text'),
		'textarea'			=> array ('text'),
		'selectbox'			=> array ('text', 'decimal'),
		'radio'				=> array ('text', 'decimal'),
		'multiselectbox'	=> array ('set'),
		'checkbox'			=> array ('set'),
		'datebox'			=> array ('date'),
		'gender'			=> array ('text'),
	);

	if (!isset ($formats[$type]))  return 'custom';

	$formats = $formats[$type];
	$default = $formats[0];
	$format = apply_filters ('bps_xprofile_format', $default, $field_id);

	return in_array ($format, $formats)? $format: $default;
}

function bps_xprofile_options ($field)
{
	$options = array ();

	if ($field->type_obj->supports_options == false)  return $options;

	$rows = $field->get_children ();
	if (is_array ($rows))  foreach ($rows as $row)
	{
		if ($field->type == 'gender')
		{
			if ($row->option_order == 1)
				$options['his_'. stripslashes (trim ($row->name))] = stripslashes (trim ($row->name));
			elseif ($row->option_order == 2)
				$options['her_'. stripslashes (trim ($row->name))] = stripslashes (trim ($row->name));
			else
				$options['their_'. stripslashes (trim ($row->name))] = stripslashes (trim ($row->name));
		}
		else
		{
			$options[stripslashes (trim ($row->name))] = stripslashes (trim ($row->name));
		}
	}

	return $options;
}

add_filter ('bps_add_fields', 'bps_anyfield_setup');
function bps_anyfield_setup ($fields)
{
	if (!bp_is_active ('xprofile'))  return $fields;

	$f = new stdClass;
	$f->group = __('Any field', 'bp-profile-search');
	$f->code = 'field_any';
	$f->name = __('Any profile field', 'bp-profile-search');
	$f->description = __('Search every profile field', 'bp-profile-search');

	$f->format = 'text';
	$f->options = array ();
	$f->search = 'bps_anyfield_search';

	$fields[] = $f;
	return $fields;
}

function bps_anyfield_search ($f)
{
	global $bp, $wpdb;

	$filter = $f->filter;
	$value = str_replace ('&', '&amp;', $f->value);

	$sql = array ('select' => '', 'where' => array ());
	$sql['select'] = "SELECT DISTINCT user_id FROM {$bp->profile->table_name_data}";

	switch ($filter)
	{
	case 'contains':
		$sql['where'][$filter] = bps_sql_expression ("value LIKE %s", $value, true);
		break;

	case '':
		$sql['where'][$filter] = bps_sql_expression ("value = %s", $value);
		break;

	case 'like':
		$value = str_replace ('\\\\%', '\\%', $value);
		$value = str_replace ('\\\\_', '\\_', $value);
		$sql['where'][$filter] = bps_sql_expression ("value LIKE %s", $value);
		break;
	}

	$sql = apply_filters ('bps_field_sql', $sql, $f);
	$query = $sql['select']. ' WHERE '. implode (' AND ', $sql['where']);

	$results = $wpdb->get_col ($query);
	return $results;
}
