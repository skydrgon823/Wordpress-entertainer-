<?php
/**
 * Before content wrap
 * Used in all templates
 */
?>

<!-- MAIN SECTION
================================================ -->
<section class="<?php echo apply_filters('kleo_main_section_class', '');?>">
	<div id="main">

		<?php
		/**
		 * Before main part - action
		 */
		do_action('kleo_before_main');
		?>

		<div class="row">

			<?php /* Before content - action */ ?>
			<?php do_action('kleo_before_content'); ?>

			<!--begin content-->
			<?php 
			$kleo_layout = sq_option('global_sidebar', 'right');
			$six_templates = array('3ll', '3rr', '3lr');
			
			if ($kleo_layout == 'no') {
				$content_class = 'twelve'; 
			}
			elseif (in_array($kleo_layout, $six_templates)) {
				$content_class = 'six';
			}
			else {
				$content_class = 'eight';
			}
			?>
			<div id="main-content" class="<?php echo apply_filters('kleo_content_class',$content_class); ?> columns">