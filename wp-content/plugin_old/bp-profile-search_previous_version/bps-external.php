<?php

add_filter ('bps_add_fields', 'bps_groups_setup', 99);
function bps_groups_setup ($fields)
{
	global $groups_template;

	if (!bp_is_active ('groups'))  return $fields;

	$options = array ();
	$args = array ('user_id' => 0, 'show_hidden' => true, 'type' => 'alphabetical');
	if (bp_has_groups ($args))  while (bp_groups ())
	{
		bp_the_group ();
		if ($groups_template->group->user_has_access)
			$options[bp_get_group_id ()] = bp_get_group_name ();
	}
	if (empty ($options))  return $fields;

	$f = new stdClass;
	$f->group = __('User groups', 'bp-profile-search');
	$f->code = 'groups';
	$f->name = __('Groups', 'bp-profile-search');
	$f->description = __('User groups', 'bp-profile-search');

	$f->format = 'set';
	$f->options = $options;
	$f->filters = array ('match_any');

	$f->search = 'bps_groups_search';
	$f->get_value = 'bps_groups_get_value';

	$fields[] = $f;

	return $fields;
}

function bps_groups_search ($f)
{
	global $bp, $wpdb;

	$value = $f->value;
	$filter = $f->format. '_'.  ($f->filter == ''? 'is': $f->filter);

	$sql = array ('select' => '', 'where' => array ());
	$sql['select'] = "SELECT DISTINCT user_id FROM {$bp->groups->table_name_members}";
	$sql['where']['confirmed'] = "is_confirmed = 1";
	$sql['where']['banned'] = "is_banned = 0";
	
	switch ($filter)
	{
	case 'set_match_any':
		$values = implode (', ', (array)$value);
		$sql['where'][$filter] = "group_id IN ($values)";
		break;

	default:
		return array ();
	}

	$sql = apply_filters ('bps_field_sql', $sql, $f);
	$query = $sql['select']. ' WHERE '. implode (' AND ', $sql['where']);

	$results = $wpdb->get_col ($query);
	return $results;
}

function bps_groups_get_value ($f)
{
	global $members_template;

	$values = array ();
	$groups = groups_get_user_groups ($members_template->member->ID);
	$groups = $groups['groups'];
	foreach ($groups as $group)
		if (isset ($f->options[$group]))  $values[] = $f->options[$group];
	sort ($values);

	$f->d_format = 'array';
	$f->d_value = $values;
}

add_filter ('bps_add_fields', 'bps_users_setup', 99);
function bps_users_setup ($fields)
{
	$columns = array
	(
		'ID'					=> 'integer',
		'user_login'			=> 'text',
//		'user_pass'				=> 'text',
//		'user_nicename'			=> 'text',
		'user_email'			=> 'text',
		'user_url'				=> 'text',
		'user_registered'		=> 'date',
//		'user_activation_key'	=> 'text',
//		'user_status'			=> 'integer',
		'display_name'			=> 'text',
	);

	$columns = apply_filters ('bps_users_columns', $columns);
	foreach ($columns as $column => $format)
	{
		$f = new stdClass;
		$f->group = __('Users data', 'bp-profile-search');
		$f->code = $column;
		$f->name = $column;
		$f->description = '';

		$f->format = $format;
		$f->options = array ();
		$f->search = 'bps_users_search';

		$fields[] = $f;
	}

	return $fields;
}

function bps_users_search ($f)
{
	global $wpdb;

	$filter = $f->format. '_'.  ($f->filter == ''? 'is': $f->filter);
	$value = $f->value;

	$sql = array ('select' => '', 'where' => array ());
	$sql['select'] = "SELECT ID FROM {$wpdb->users}";

	switch ($filter)
	{
	case 'text_contains':
		$value = stripslashes ($value);
		$sql['where'][$filter] = bps_sql_expression ("{$f->code} LIKE %s", $value, true);
		break;

	case 'text_is':
		$value = stripslashes ($value);
		$sql['where'][$filter] = bps_sql_expression ("{$f->code} = %s", $value);
		break;

	case 'text_like':
		$value = str_replace ('\\\\%', '\\%', $value);
		$value = str_replace ('\\\\_', '\\_', $value);
		$sql['where'][$filter] = bps_sql_expression ("{$f->code} LIKE %s", $value);
		break;

	case 'integer_is':
		$sql['where'][$filter] = bps_sql_expression ("{$f->code} = %d", $value);
		break;

	case 'integer_range':
		if (isset ($value['min']))  $sql['where']['min'] = $wpdb->prepare ("{$f->code} >= %d", $value['min']);
		if (isset ($value['max']))  $sql['where']['max'] = $wpdb->prepare ("{$f->code} <= %d", $value['max']);
		break;

	case 'date_is':
		$sql['where'][$filter] = bps_sql_expression ("DATE({$f->code}) = %s", $value);
		break;

	case 'date_range':
		if (isset ($value['min']))  $sql['where']['min'] = $wpdb->prepare ("DATE({$f->code}) >= %s", $value['min']);
		if (isset ($value['max']))  $sql['where']['max'] = $wpdb->prepare ("DATE({$f->code}) <= %s", $value['max']);
		break;

	case 'date_age_range':
		$day = date ('j');
		$month = date ('n');
		$year = date ('Y');

		if (isset ($value['max']))
		{
			$ymin = $year - $value['max'] - 1; 
			$sql['where']['age_min'] = $wpdb->prepare ("DATE({$f->code}) > %s", "$ymin-$month-$day");
		}
		if (isset ($value['min']))
		{
			$ymax = $year - $value['min'];
			$sql['where']['age_max'] = $wpdb->prepare ("DATE({$f->code}) <= %s", "$ymax-$month-$day");
		}
		break;

	default:
		return array ();
	}

	$sql = apply_filters ('bps_field_sql', $sql, $f);
	$query = $sql['select']. ' WHERE '. implode (' AND ', $sql['where']);

	$results = $wpdb->get_col ($query);
	return $results;
}

add_filter ('bps_add_fields', 'bps_usermeta_setup', 99);
function bps_usermeta_setup ($fields)
{
	$meta_keys = array
	(
		'first_name'			=> 'text',
		'last_name'				=> 'text',
		'role'					=> array ('text', bps_get_roles ()),
		'roles'					=> array ('set', bps_get_roles ()),
	);

	if (bp_is_active ('friends'))  $meta_keys['total_friend_count'] = 'integer';
	if (bp_is_active ('groups'))  $meta_keys['total_group_count'] = 'integer';

	$meta_keys = apply_filters ('bps_usermeta_keys', $meta_keys);
	foreach ($meta_keys as $meta_key => $format)
	{
		$f = new stdClass;
		$f->group = __('Usermeta data', 'bp-profile-search');
		$f->code = $meta_key;
		$f->name = $meta_key;
		$f->description = '';

		$format = (array) $format;
		$f->format = $format[0];
		$f->options = isset ($format[1])? $format[1]: array ();
		$f->search = 'bps_usermeta_search';

		$fields[] = $f;
	}

	return $fields;
}

function bps_usermeta_search ($f)
{
	global $wpdb;

	$filter = $f->format. '_'.  ($f->filter == ''? 'is': $f->filter);
	if ($f->code == 'role')  $filter = 'set_match_any';

	$value = $f->value;

	$sql = array ('select' => '', 'where' => array ());
	$sql['select'] = "SELECT user_id FROM {$wpdb->usermeta}";
	
	if (in_array ($f->code, array ('role', 'roles')))
		$sql['where']['meta_key'] = $wpdb->prepare ("meta_key = %s", $wpdb->prefix. 'capabilities');
	else
		$sql['where']['meta_key'] = $wpdb->prepare ("meta_key = %s", $f->code);

	switch ($filter)
	{
	case 'text_contains':
		$value = stripslashes ($value);
		$sql['where'][$filter] = bps_sql_expression ("meta_value LIKE %s", $value, true);
		break;

	case 'text_is':
		$value = stripslashes ($value);
		$sql['where'][$filter] = bps_sql_expression ("meta_value = %s", $value);
		break;

	case 'text_like':
		$value = str_replace ('\\\\%', '\\%', $value);
		$value = str_replace ('\\\\_', '\\_', $value);
		$sql['where'][$filter] = bps_sql_expression ("meta_value LIKE %s", $value);
		break;

	case 'integer_is':
		$sql['where'][$filter] = bps_sql_expression ("meta_value = %d", $value);
		break;

	case 'integer_range':
		if (isset ($value['min']))  $sql['where']['min'] = $wpdb->prepare ("meta_value >= %d", $value['min']);
		if (isset ($value['max']))  $sql['where']['max'] = $wpdb->prepare ("meta_value <= %d", $value['max']);
		break;

	case 'decimal_is':
		$sql['where'][$filter] = bps_sql_expression ("meta_value = %f", $value);
		break;

	case 'decimal_range':
		if (isset ($value['min']))  $sql['where']['min'] = $wpdb->prepare ("meta_value >= %f", $value['min']);
		if (isset ($value['max']))  $sql['where']['max'] = $wpdb->prepare ("meta_value <= %f", $value['max']);
		break;

	case 'date_is':
		$sql['where'][$filter] = bps_sql_expression ("DATE(meta_value) = %s", $value);
		break;

	case 'date_range':
		if (isset ($value['min']))  $sql['where']['min'] = $wpdb->prepare ("DATE(meta_value) >= %s", $value['min']);
		if (isset ($value['max']))  $sql['where']['max'] = $wpdb->prepare ("DATE(meta_value) <= %s", $value['max']);
		break;

	case 'date_age_range':
		$day = date ('j');
		$month = date ('n');
		$year = date ('Y');

		if (isset ($value['max']))
		{
			$ymin = $year - $value['max'] - 1; 
			$sql['where']['age_min'] = $wpdb->prepare ("DATE(meta_value) > %s", "$ymin-$month-$day");
		}
		if (isset ($value['min']))
		{
			$ymax = $year - $value['min'];
			$sql['where']['age_max'] = $wpdb->prepare ("DATE(meta_value) <= %s", "$ymax-$month-$day");
		}
		break;

	case 'text_one_of':
		$values = (array)$value;
		$parts = array ();
		foreach ($values as $value)
		{
			$value = stripslashes ($value);
			$parts[] = $wpdb->prepare ("meta_value = %s", $value);
		}
		$sql['where'][$filter] = '('. implode (' OR ', $parts). ')';
		break;

	case 'set_match_any':
	case 'set_match_all':
		$values = (array)$value;
		$parts = array ();
		foreach ($values as $value)
		{
			$escaped = '%:"'. bps_esc_like ($value). '";%';
			$parts[] = $wpdb->prepare ("meta_value LIKE %s", $escaped);
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

function bps_get_roles ()
{
	return wp_roles()->get_names ();
}

add_filter ('bps_add_fields', 'bps_taxonomies_setup', 99);
function bps_taxonomies_setup ($fields)
{
	$taxonomies = get_object_taxonomies ('user', 'objects');
	$taxonomies = apply_filters ('bps_taxonomies', $taxonomies);

	foreach ($taxonomies as $taxonomy => $object)
	{
		$f = new stdClass;
		$f->group = __('User taxonomies', 'bp-profile-search');
		$f->code = $taxonomy;
		$f->name = $object->labels->singular_name;
		$f->description = $object->description;
		if ($taxonomy == 'bp_member_type')
		{
			$f->name = __('Member type', 'bp-profile-search');
			$f->description = __('Select the member type', 'bp-profile-search');
		}

		$f->format = 'text';
		$f->options = array ();
		$terms = get_terms (array ('taxonomy' => $taxonomy, 'hide_empty' => false));
		foreach ($terms as $term)
			$f->options[$term->term_id] = $term->name;

		if ($taxonomy == 'bp_member_type')
		{
			$terms = bp_get_member_types (array (), 'objects');
			foreach ($f->options as $k => $option)
			{
				if (isset ($terms[$option]))
					$f->options[$k] = $terms[$option]->labels['singular_name'];
				else
					unset ($f->options[$k]);
			}
		}

		if (empty ($f->options))  continue;

		$f->search = 'bps_taxonomies_search';
		$fields[] = $f;
	}

	return $fields;
}

function bps_taxonomies_search ($f)
{
	$results = get_objects_in_term ($f->value, $f->code);
	return is_array ($results)? $results: array ();
}
