<?php

/*
 * BP Profile Search - member details template 'bps-details'
 *
 * This template works just like any other BuddyPress template.
 * To override this template, copy it to the 'buddypress/members' directory in your theme's root, then edit the new copy
 * according to your needs. If you edit the original file, your changes will be overwritten during the next BP Profile Search update.
 *
 */

$F = bps_escaped_details_data ();

foreach ($F->fields as $f)
{
?>
	<div class="<?php echo $f->code; ?>_details item-meta">
		<span class="activity">
<?php

	$label = $f->d_label;
	$value = $f->d_value;

	switch ($f->d_format)
	{
	default:
		/* translators: %1$s label, %2$s value */
		printf (__('%1$s: %2$s', 'bp-profile-search'), $label, $value);
		break;

	case 'age':
		/* translators: %1$s label, %2$d age */
		printf (_n('%1$s: %2$d year', '%1$s: %2$d years', $value, 'bp-profile-search'), $label, $value);
		break;

	case 'date':
		$value = strtotime ($value);
		if (!empty ($value))  $value = date_i18n ('F j, Y', $value);
		printf (__('%1$s: %2$s', 'bp-profile-search'), $label, $value);
		break;

	case 'array':
		$value = implode (', ', $value);
		printf (__('%1$s: %2$s', 'bp-profile-search'), $label, $value);
		break;

	case 'distance':
		/* translators: %1$s label, %2$s location, %3$d distance */
		$distance = round ($f->d_distance);
		if ($f->d_units == 'km')
			printf (__('%1$s: %2$s (distance %3$d km)', 'bp-profile-search'), $label, $value, $distance);
		else
			printf (__('%1$s: %2$s (distance %3$d miles)', 'bp-profile-search'), $label, $value, $distance);
		break;
	}
?>
		</span>
	</div>
<?php
}

// BP Profile Search - end of template
