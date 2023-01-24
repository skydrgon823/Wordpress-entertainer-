<?php
/**
 * The Header for our theme.
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */
?><!DOCTYPE html>

<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!-->

<html class="no-js" <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width"/>

	<link rel="profile" href="http://gmpg.org/xfn/11"/>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>

	<!--[if IE 7]>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/assets/styles/font-awesome-ie7.min.css">
	<script src="<?php echo get_template_directory_uri();?>/assets/scripts/ie6/warning.js"></script>
	<![endif]-->

	<!--Favicons-->
	<?php if ( sq_option( 'favicon' ) ) { ?>
		<link rel="shortcut icon" href="<?php echo sq_option( 'favicon' ); ?>">
	<?php } ?>
	<?php if ( sq_option( 'apple57' ) ) { ?>
		<link rel="apple-touch-icon" href="<?php echo sq_option( 'apple57' ); ?>">
	<?php } ?>
	<?php if ( sq_option( 'apple57' ) ) { ?>
		<link rel="apple-touch-icon" sizes="57x57" href="<?php echo sq_option( 'apple57' ); ?>">
	<?php } ?>
	<?php if ( sq_option( 'apple72' ) ) { ?>
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo sq_option( 'apple72' ); ?>">
	<?php } ?>
	<?php if ( sq_option( 'apple114' ) ) { ?>
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo sq_option( 'apple114' ); ?>">
	<?php } ?>
	<?php if ( sq_option( 'apple144' ) ) { ?>
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo sq_option( 'apple144' ); ?>">
	<?php } ?>
	
	<?php if ( function_exists( 'bp_is_active' ) ) {
		bp_head();
	} ?>
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'kleo_after_body' ); ?>

<!-- Page
================================================ -->
<!--Attributes-->
<!--class = kleo-page wide-style / boxed-style-->
<div class="kleo-page <?php echo sq_option( 'site_style', 'wide-style' ); ?>">

	<?php
	/**
	 * Render our header here
	 *
	 * @hooked sweetdate_show_header
	 */
	do_action( 'sweetdate_header' );
	?>

<?php
/**
 * Hook kleo_before_page
 *
 * @hooked kleo_show_breadcrumb - 9
 */
do_action( 'kleo_before_page' );
