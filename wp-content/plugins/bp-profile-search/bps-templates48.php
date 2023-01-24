<?php

function bps_escaped_form_data49 ()
{
	list ($form, $location) = bps_template_args ();

	$meta = bps_meta ($form);
	list ($fields, $errors) = bps_get_form_fields ($form);

	$F = new stdClass;
	$F->id = $form;
	$F->title = bps_wpml ($form, '-', 'title', get_the_title ($form));
	$F->location = $location;
	$F->unique_id = bps_unique_id ('form_'. $form);
	$F->errors = $errors;

	$dirs = bps_directories ();
	$F->action = $dirs[bps_wpml_id ($meta['action'])]->path;
	$F->method = $meta['method'];

	$platform = bps_platform ();
	$F->strings['clear'] = esc_html (($platform == 'buddypress')? __('Clear', 'buddypress'): __('Clear', 'buddyboss'));
	$F->strings['search'] = esc_html (($platform == 'buddypress')? __('Search', 'buddypress'): __('Search', 'buddyboss'));

	$F->fields = $fields;
	$F->fields[] = bps_set_hidden_field (BPS_FORM, $form);
	$F->fields[] = bps_set_hidden_field ('bps_form_page', bps_current_page ());

	do_action ('bps_before_search_form', $F);

	foreach ($F->fields as $f)
	{
		$f->unique_id = bps_unique_id ($f->html_name);

		if (!is_array ($f->value))
			$f->value = esc_attr (stripslashes ($f->value));
		else foreach ($f->value as $k => $value)
			$f->value[$k] = esc_attr (stripslashes ($value));
		if ($f->display == 'hidden')  continue;

		$f->label = esc_html ($f->label);
		$f->description = esc_html ($f->description);
		$f->error_message = esc_html ($f->error_message);

		$options = array ();
		foreach ($f->options as $key => $label)
			$options[esc_attr ($key)] = esc_html ($label);
		$f->options = $options;
	}

	return $F;
}

function bps_escaped_filters_data54 ()
{
	$F = new stdClass;

	$action = parse_url ($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$action = add_query_arg (BPS_FORM, 'clear', $action);
	$F->links[esc_url ($action)] = esc_html ((bps_platform () == 'buddypress')? __('Clear', 'buddypress'): __('Clear', 'buddyboss'));
	
	$form_page = bps_get_request ('filters')['bps_form_page'];
	if ($form_page != bps_current_page ())
		$F->links[esc_url ($form_page)] = esc_html (__('New Search', 'bp-profile-search'));

	$F->fields = bps_get_filters_fields ();

	do_action ('bps_before_filters', $F);

	foreach ($F->fields as $f)
	{
		$f->label = esc_html ($f->label);
		$f->mode = esc_html ($f->mode);
		if (!is_array ($f->value))
			$f->value = esc_html (stripslashes ($f->value));
		else foreach ($f->value as $k => $value)
			$f->value[$k] = esc_html (stripslashes ($value));
	}

	return $F;
}

function bps_escaped_details_data ()
{
	$F = new stdClass;
	$F->fields = array ();

	$details = bps_get_details ();
	foreach ($details as $code)
	{
		$f = bps_parsed_field ($code);
		if (!isset ($f->get_value) || !is_callable ($f->get_value))  continue;

		$f->d_label = (isset ($f->filter) && isset ($f->label))? $f->label: $f->name;
		call_user_func ($f->get_value, $f);

		$f->d_label = esc_html ($f->d_label);
		if (!is_array ($f->d_value))
			$f->d_value = esc_html (stripslashes ($f->d_value));
		else foreach ($f->d_value as $k => $value)
			$f->d_value[$k] = esc_html (stripslashes ($value));

		do_action ('bps_field_before_details', $f);
		$F->fields[] = $f;
	}

	do_action ('bps_before_details', $F);
	return $F;
}
