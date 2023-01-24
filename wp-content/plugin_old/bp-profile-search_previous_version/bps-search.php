<?php

function bps_get_request ($type, $form=0)		// published interface, 20190324
{
	if ($type == 'form')  return bps_get_request2 ($type, $form);
	if ($type == 'filters')  return bps_get_request2 ($type);

	$current = bps_current_page ();
	$hidden_filters = bps_get_hidden_filters ();
	$showing_errors = isset ($_REQUEST['bps_errors']);

	$cookie = apply_filters ('bps_cookie_name', 'bps_request');
	$request = isset ($_REQUEST[BPS_FORM])? $_REQUEST: array ();
	if (empty ($request) && isset ($_COOKIE[$cookie]))
		parse_str (stripslashes ($_COOKIE[$cookie]), $request);

	switch ($type)
	{
	case 'form':
		if (isset ($request[BPS_FORM]) && $request[BPS_FORM] != $form)  $request = array ();
		break;

	case 'filters':
		if (isset ($request['bps_directory']) && $request['bps_directory'] != $current)  $request = array ();
		if ($showing_errors)  $request = array ();
		foreach ($hidden_filters as $key => $value)  unset ($request[$key]);
		break;

	case 'search':
		if (isset ($request['bps_directory']) && $request['bps_directory'] != $current)  $request = array ();
		if ($showing_errors)  $request = array ();
		foreach ($hidden_filters as $key => $value)  $request[$key] = $value;
		break;
	}

	return apply_filters ('bps_request', $request, $type, $form);
}

function bps_current_page ()
{
	$current = defined ('DOING_AJAX')?
		parse_url ($_SERVER['HTTP_REFERER'], PHP_URL_PATH):
		parse_url ($_SERVER['REQUEST_URI'], PHP_URL_PATH);

	return apply_filters ('bps_current_page', $current);		// published interface, 20190324
}

add_filter ('bp_ajax_querystring', 'bps_filter_members', 99, 2);
function bps_filter_members ($qs, $object)
{
	if ($object != 'members')  return $qs;

	$request = bps_get_request ('search');
	if (empty ($request))  return $qs;

	$results = bps_search ($request);
	if ($results['validated'])
	{
		$args = wp_parse_args ($qs);
		$users = $results['users'];

		if (isset ($args['include']) && $args['include'] !== '')
		{
			$included = explode (',', $args['include']);
			$users = array_intersect ($users, $included);
			if (count ($users) == 0)  $users = array (0);
		}

		$users = apply_filters ('bps_search_results', $users);
		$args['include'] = implode (',', $users);
		$qs = build_query ($args);
	}

	return $qs;
}

function bps_search ($request, $users=null)		// published interface, 20190324
{
	$results = array ('users' => array (0), 'validated' => true);

	$fields = bps_parse_request ($request);
	foreach ($fields as $f)
	{
		if (!isset ($f->filter))  continue;
		if (!is_callable ($f->search))  continue;

		do_action ('bps_field_before_query', $f);
		$found = call_user_func ($f->search, $f);
		$found = apply_filters ('bps_field_search_results', $found, $f);

		$match_all = apply_filters ('bps_match_all', true);
		if ($match_all)
		{
			$users = isset ($users)? array_intersect ($users, $found): $found;
			if (count ($users) == 0)  return $results;
		}
		else
		{
			$users = isset ($users)? array_merge ($users, $found): $found;
		}
	}

	if (isset ($users))
	{
		if (count ($users) == 0)  return $results;
		$results['users'] = $users;
	}
	else
	{
		$results['validated'] = false;
	}

	return $results;
}

add_action ('bps_field_before_query', 'bps_field_before_query', 99, 1);
function bps_field_before_query ($f)
{
	if (bps_debug ())
	{
		echo "<!--\n";
		echo "query "; print_r ($f);
		echo "-->\n";
	}
}

add_filter ('bps_field_sql', 'bps_field_sql', 99, 2);
function bps_field_sql ($sql, $f)
{
	global $wpdb;

	if (bps_debug ())
	{
		$where = implode (' AND ', $sql['where']);
		$where = $wpdb->remove_placeholder_escape ($where);
		echo "<!--\n";
		echo "where $where\n";
		echo "-->\n";
	}
	
	return $sql;
}

add_filter ('bps_field_search_results', 'bps_field_search_results', 99, 2);
function bps_field_search_results ($found, $f)
{
	if (bps_debug ())
	{
		$ids = implode (',', $found);
		echo "<!--\n";
		echo "found $ids\n";
		echo "-->\n";
	}
	
	return $found;
}

if (!defined ('BPS_AND'))  define ('BPS_AND', ' AND ');
if (!defined ('BPS_OR'))  define ('BPS_OR', ' OR ');

function bps_is_expression ($value)
{
	$and = explode (BPS_AND, $value);
	$or = explode (BPS_OR, $value);

	if (count ($and) > 1 && count ($or) > 1)  return 'mixed';
	if (count ($and) > 1)  return 'and';
	if (count ($or) > 1)  return 'or';

	return false;
}

function bps_sql_expression ($format, $value, $escape=false)
{
	global $wpdb;

	foreach (array (BPS_AND, BPS_OR) as $split)
	{
		$values = explode ($split, $value);
		if (count ($values) > 1)  break;
	}

	$parts = array ();
	foreach ($values as $value)
	{
		if ($escape)  $value = '%'. bps_esc_like ($value). '%';
		$parts[] = $wpdb->prepare ($format, $value);
	}

	$join = ($split == BPS_AND)? ' AND ': ' OR ';
	return '('. implode ($join, $parts). ')';
}

function bps_esc_like ($text)
{
    return addcslashes ($text, '_%\\');
}
