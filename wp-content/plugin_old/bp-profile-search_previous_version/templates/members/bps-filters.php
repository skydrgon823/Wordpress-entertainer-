<?php

/*
 * BP Profile Search - active filters template 'bps-filters'
 *
 * This template works just like any other BuddyPress template.
 * To override this template, copy it to the 'buddypress/members' directory in your theme's root, then edit the new copy
 * according to your needs. If you edit the original file, your changes will be overwritten during the next BP Profile Search update.
 *
 */

$F = bps_escaped_filters_data ($version = '5.4');
?>

<p class='bps-filters'>

<?php
foreach ($F->fields as $f)
{
	$label = $f->label;
	$mode = $f->mode;
	$value = $f->value;
	$filter = $f->filter;

	switch ($filter)
	{
	case 'range':
	case 'age_range':
		$min = __('min', 'bp-profile-search');
		$max = __('max', 'bp-profile-search');
?>
		<?php if ($f->value['max'] === '') { ?>
			<strong><?php echo $label; ?></strong> <span><?php echo $min; ?>: <?php echo $value['min']; ?></span><br>
		<?php } else if ($f->value['min'] === '') { ?>
			<strong><?php echo $label; ?></strong> <span><?php echo $max; ?>: <?php echo $value['max']; ?></span><br>
		<?php } else { ?>
			<strong><?php echo $label; ?></strong> <span><?php echo $min; ?>: <?php echo $value['min']; ?>,
			                                             <?php echo $max; ?>: <?php echo $value['max']; ?></span><br>
		<?php } ?>
<?php
		break;

	case 'contains':
	case '':
	case 'like':
?>
		<?php if (!empty ($mode)) { ?>
			<strong><?php echo $label; ?></strong> <span><?php echo $mode; ?>: <?php echo $value; ?></span><br>
		<?php } else { ?>
			<strong><?php echo $label; ?></strong><span>: <?php echo $value; ?></span><br>
		<?php } ?>
<?php
		break;

	case 'distance':
		$units = $value['units'];
		$distance = $value['distance'];
		$location = $value['location'];
?>
		<?php if ($units == 'km') { ?>
			<strong><?php echo $label; ?></strong>
				<span><?php printf (__('is within: %1$s km of %2$s', 'bp-profile-search'), $distance, $location); ?></span><br>
		<?php } else { ?>
			<strong><?php echo $label; ?></strong>
				<span><?php printf (__('is within: %1$s miles of %2$s', 'bp-profile-search'), $distance, $location); ?></span><br>
		<?php } ?>
<?php
		break;

	case 'one_of':
	case 'match_any':
	case 'match_all':
		$values = implode (', ', $value);
?>
		<?php if (!empty ($mode) && count ($value) > 1) { ?>
			<strong><?php echo $label; ?></strong> <span><?php echo $mode; ?>: <?php echo $values; ?></span><br>
		<?php } else { ?>
			<strong><?php echo $label; ?></strong><span>: <?php echo $values; ?></span><br>
		<?php } ?>
<?php
		break;

	default:
		$output = apply_filters ('bps_filters_template_field', 'none', $f);
?>
		<?php if ($output != 'none') { ?>
			<strong><?php echo $label; ?></strong> <span><?php echo $output; ?></span><br>
		<?php } else { ?>
			<strong><?php echo $label; ?></strong> <span><?php printf ('BP Profile Search: undefined filter <em>%s</em>', $filter); ?></span><br>
		<?php } ?>
<?php
		break;
	}
}
?>

<?php $k=0; foreach ($F->links as $path => $label) { ?>
	<span><?php if ($k++) echo '|'; ?></span> <a href='<?php echo $path; ?>'><?php echo $label; ?></a>
<?php } ?>

</p>

<?php
// BP Profile Search - end of template
