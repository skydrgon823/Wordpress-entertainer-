<?php

function bps_set_directory ()
{
	global $shortcode_tags;

	if ($dir = bps_is_directory ())  if (!empty ($dir->content))
	{
		$saved_shortcodes = $shortcode_tags;
		$shortcode_tags = array ('bps_directory' => 'bps_set_directory_data');
		do_shortcode ($dir->content);
		$shortcode_tags = $saved_shortcodes;

		wp_enqueue_script ('bps-directory', plugins_url ('bps-directory.js', __FILE__), array ('bp-jquery-cookie'), BPS_VERSION);
		$_COOKIE['bp-members-scope'] = 'all';
		unset ($_COOKIE['bp-members-filter']);
	}
}

function bps_is_directory ()
{
	$dirs = bps_directories ();
	$current = bps_current_page ();

	foreach ($dirs as $dir)
		if ($dir->path == $current)  return $dir;

	return false;
}

function bps_directories ()		// published interface, 20190324
{
	static $dirs = array ();

	if (count ($dirs))  return $dirs;

	$bp_pages = bp_get_option ('bp-pages', array ());
	if (isset ($bp_pages['members']))
	{
		$members = $bp_pages['members'];
		$members = bps_wpml_id ($members);
		$dirs[$members] = new stdClass;
		$dirs[$members]->id = $members;
		$dirs[$members]->title = get_the_title ($members);
		$dirs[$members]->path = parse_url (get_page_link ($members), PHP_URL_PATH);
	}

	$pages = get_pages ();
	foreach ($pages as $page)  if (has_shortcode ($page->post_content, 'bps_directory'))
	{
		$dirs[$page->ID] = new stdClass;
		$dirs[$page->ID]->id = $page->ID;
		$dirs[$page->ID]->title = $page->post_title;
		$dirs[$page->ID]->content = $page->post_content;
		$dirs[$page->ID]->path = parse_url (get_page_link ($page->ID), PHP_URL_PATH);
		$dirs[$page->ID]->replace = true;
	}

	$dirs = apply_filters ('bps_add_directory', $dirs);		// published interface, 20190324
	return $dirs;
}

add_shortcode ('bps_directory', function() {return '';});
function bps_set_directory_data ($attr, $content)
{
	global $bps_directory_data;

	$bps_directory_data = array ();
	$bps_directory_data['page'] = bps_current_page ();

	if (isset ($attr['template']))
	{
		$templates = explode (',', $attr['template']);
		$bps_directory_data['template'] = isset ($templates[0])? trim ($templates[0]): '';
		$bps_directory_data['ajax_template'] = isset ($templates[1])? trim ($templates[1]): '';
	}

	if (isset ($attr['show']))
		$bps_directory_data['show'] = $attr['show'];

	if (isset ($attr['order_by']))
		$bps_directory_data['order_by'] = $attr['order_by'];

	list (, $fields) = bps_get_fields ();
	$split = isset ($attr['split'])? $attr['split']: ',';

	if (is_array ($attr))  foreach ($attr as $key => $value)
	{
		if (in_array ($key, array ('template', 'show', 'order_by')))  continue;

		$k = bps_match_key ($key, $fields);
		if ($k === false)  continue;

		$f = $fields[$k];
		$filter = ($key == $f->code)? '': substr ($key, strlen ($f->code) + 1);
		if (!bps_Fields::is_filter ($f, $filter))  continue;

		$selector = $filter. (count ($f->options)? '/e': '');
		switch ($selector)
		{
		case 'contains':
		case '':
		case 'like':
			$value = str_replace ('&amp;', '&', trim (addslashes ($value)));
			if ($value !== '')  $bps_directory_data[$key] = $value;
			break;

		case 'range':
		case 'age_range':
			list ($min, $max) = explode ($split, $value);
			$values = array ();
			if (($min = trim ($min)) !== '')  $values['min'] = $min;
			if (($max = trim ($max)) !== '')  $values['max'] = $max;
			if (!empty ($values))  $bps_directory_data[$key] = $values;
			break;

		case '/e':
			$flip = array_flip ($f->options);
			$value = str_replace ('&amp;', '&', trim ($value));
			$bps_directory_data[$key] = isset ($flip[$value])? addslashes ($flip[$value]): "_wrong_{$value}_";
			break;

		case 'one_of/e':
		case 'match_any/e':
		case 'match_all/e':
			$flip = array_flip ($f->options);
			$values = explode ($split, $value);
			$keys = array ();
			foreach ($values as $value)
			{
				$value = str_replace ('&amp;', '&', trim ($value));
				$keys[] = isset ($flip[$value])? addslashes ($flip[$value]): "_wrong_{$value}_";
			}
			if (!empty ($keys))  $bps_directory_data[$key] = $keys;
			break;
		}
	}

	$cookie = apply_filters ('bps_cookie_name', 'bps_directory');
	setcookie ($cookie, http_build_query ($bps_directory_data), 0, COOKIEPATH);

	return '';
}

function bps_get_directory_data ()
{
	global $bps_directory_data;

	$data = array ();
	$cookie = apply_filters ('bps_cookie_name', 'bps_directory');

	if (!defined ('DOING_AJAX'))
		$data = isset ($bps_directory_data)? $bps_directory_data: array ();
	else if (isset ($_COOKIE[$cookie]))
	{
		$current = bps_current_page ();
		parse_str (stripslashes ($_COOKIE[$cookie]), $data);
		if ($data['page'] != $current)  $data = array ();
	}

	return apply_filters ('bps_directory_data', $data);
}

function bps_get_hidden_filters ()
{
	$data = bps_get_directory_data ();
	unset ($data['page'], $data['template'], $data['ajax_template'], $data['show'], $data['order_by']);

	return apply_filters ('bps_hidden_filters', $data);
}

add_action ('bp_before_directory_members_content', 'bps_before_directory');
function bps_before_directory ()
{
	$fields = bps_parsed_fields ();
	foreach ($fields as $f)
	{
		if (!bps_Fields::show_details ($f))  continue;
		if (empty ($f->id) || $f->id != 1)  bps_set_sort_options ($f->code);
	}

	$data = bps_get_directory_data ();
	if (isset ($data['order_by']))  bps_set_sort_options ($data['order_by']);

	add_action ('bp_members_directory_order_options', 'bps_display_sort_options');

	$request = bps_get_request ('filters');
	if (!empty ($request))
		bps_call_template ('members/bps-filters');
}

function bps_set_sort_options ($options)
{
	global $bps_sort_options;

	$options = explode (',', $options);
	foreach ($options as $option)
	{
		$option = trim (preg_replace ('/\s+/', ' ', $option));
		$option = explode (' ', $option);

		$code = $option[0];
		$f = bps_parsed_field ($code);
		if (!isset ($f->sort_directory) || !is_callable ($f->sort_directory))  continue;

		$order = ($f->format == 'date')? 'desc': 'asc';
		$order = isset ($option[1])? $option[1]: $order;
		if (!in_array ($order, array ('asc', 'desc', 'both')))  continue;

		$label = (isset ($f->filter) && isset ($f->label))? $f->label: $f->name;

		if ($order == 'asc')
		{
			$bps_sort_options[$code] = $label;
		}
		else if ($order == 'desc')
		{
			$bps_sort_options['-'. $code] = $label;
		}
		else if ($order == 'both')
		{
			$bps_sort_options[$code] = $label. " &#x21E1;";
			$bps_sort_options['-'. $code] = $label. " &#x21E3;";
		}
	}
}

function bps_display_sort_options ()
{
	global $bps_sort_options;

	$version = BPS_VERSION;
	echo "\n<!-- BP Profile Search $version -->\n";

	if (!isset ($bps_sort_options))  $bps_sort_options = array ();
	$sort_options = apply_filters ('bps_sort_options', $bps_sort_options);
	foreach ($sort_options as $code => $label)
	{
?>
		<option value="<?php echo esc_attr($code); ?>"><?php echo esc_html($label); ?></option>
<?php
	}

	echo "\n<!-- BP Profile Search end -->\n";
}

add_action ('bp_before_members_loop', 'bps_before_loop');
function bps_before_loop ()
{
	$fields = bps_parsed_fields ();
	foreach ($fields as $f)
	{
		if (!bps_Fields::show_details ($f))  continue;
		if (empty ($f->id) || $f->id != 1)  bps_set_details ($f->code);
	}

	$data = bps_get_directory_data ();
	if (isset ($data['show']))  bps_set_details ($data['show']);

	add_filter ('bp_user_query_uid_clauses', 'bps_uid_clauses', 99, 2);
	add_action ('bp_directory_members_item', 'bps_display_details');
}

function bps_uid_clauses ($sql, $object)
{
	$code = $object->query_vars['type']; 
	$order = 'ASC';
	if ($code[0] == '-')
	{
		$code = substr ($code, 1);
		$order = 'DESC';
	}

	$f = bps_parsed_field ($code);
	if (isset ($f->sort_directory) && is_callable ($f->sort_directory))
	{
		$sql = call_user_func ($f->sort_directory, $sql, $object, $f, $order);
		if (empty ($f->id) || $f->id != 1)  bps_set_details ($code);
	}

	return $sql;
}

function bps_set_details ($codes)
{
	global $bps_details;

	$codes = explode (',', $codes);
	foreach ($codes as $code)
	{
		$code = trim ($code);
		$bps_details[$code] = $code;
	}
}

function bps_get_details ()
{
	global $bps_details;

	$details = isset ($bps_details)? $bps_details: array ();
	$details = apply_filters ('bps_details', $details);

	return $details;
}

function bps_display_details ()
{
	$details = bps_get_details ();
	if (!empty ($details))
		bps_call_template ('members/bps-details');
}

add_filter ('bp_core_get_directory_page_ids', 'bps_custom_directory');
function bps_custom_directory ($page_ids)
{
	global $bps_directory_name;

	if ($dir = bps_is_directory ())  if (!empty ($dir->replace))
	{
		$bps_directory_name = get_post_field ('post_name', $page_ids['members']);
		add_filter ('bp_get_members_root_slug', function($slug) {return $GLOBALS['bps_directory_name'];});
		$page_ids['members'] = $dir->id;
	}
	return $page_ids;
}

add_filter ('bp_get_template_part', 'bps_directory_index', 10, 2);
function bps_directory_index ($templates, $slug)
{
	$data = bps_get_directory_data ();
	if (!empty ($data['template']) && $slug == 'members/index')
		$templates = array ($data['template']. '.php');

	if (bps_debug ())
	{
		echo "<!--\n";
		foreach ($templates as $template)
		{
			$path = str_replace (WP_CONTENT_DIR, '', bp_locate_template ($template));
			echo "path $path\n";
		}
		echo "-->\n";
	}

	return $templates;
}

add_filter ('bp_legacy_object_template_path', 'bps_directory_ajax');
add_filter ('bp_nouveau_object_template_path', 'bps_directory_ajax');
function bps_directory_ajax ($template_path)
{
	$data = bps_get_directory_data ();
	if (!empty ($data['ajax_template']))
		$template_path = bp_locate_template ($data['ajax_template']. '.php');

	if (bps_debug ())
	{
		$path = str_replace (WP_CONTENT_DIR, '', $template_path);
		echo "<!--\n";
		echo "path $path\n";
		echo "-->\n";
	}

	return $template_path;
}
