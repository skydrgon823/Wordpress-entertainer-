<?php

if ( ! defined( 'SWEET_OPTIONS_URL' ) ) {
	define( 'SWEET_OPTIONS_URL', get_template_directory_uri() . '/assets/admin/' );
}

$sections = [];

$sections[] = array(
	// Font Awesome iconfont to supply default icons.
	// If $args['icon_type'] = 'iconfont', this should be the icon name minus 'icon-'.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	// Icons can also be overridden on a section-by-section basis by defining 'icon_type' => 'image'
	'icon_type'  => 'image',
	'icon'       => SWEET_OPTIONS_URL . '/img/sweetdate_menu_icon.jpg',
	// Set the class for this icon.
	// This field is ignored unless $args['icon_type'] = 'iconfont'
	'icon_class' => 'icon-large',
	'title'      => esc_html__( 'General settings', 'sweetdate' ),
	'desc'       => __( '<p class="description">Here you will set your site-wide preferences.</p>', 'sweetdate' ),
	'fields'     => array(

		array(
			'id'       => 'logo',
			'type'     => 'upload',
			'title'    => esc_html__( 'Logo', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Upload your own logo (294x108 recommended size).', 'sweetdate' ),
		),
		array(
			'id'       => 'logo_retina',
			'type'     => 'upload',
			'title'    => esc_html__( 'Logo Retina', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Upload retina logo. This is optional and should be double in size than normal logo.', 'sweetdate' ),
		),
		array(
			'id'       => 'small_logo',
			'type'     => 'upload',
			'title'    => esc_html__( 'Small Logo', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Upload your small logo for sticky main menu.', 'sweetdate' ),
		),
		array(
			'id'       => 'favicon',
			'type'     => 'upload',
			'title'    => esc_html__( 'Favicon', 'sweetdate' ),
			'sub_desc' => esc_html__( '.ico image that will be used as favicon (32px32px).', 'sweetdate' ),
			'std'      => get_template_directory_uri() . '/assets/images/icons/favicon.ico'
		),
		array(
			'id'       => 'apple57',
			'type'     => 'upload',
			'title'    => esc_html__( 'Apple Iphone Icon', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Apple Iphone Icon (57px57px).', 'sweetdate' ),
			'std'      => get_template_directory_uri() . '/assets/images/icons/apple-touch-icon-57x57.png'
		),
		array(
			'id'       => 'apple114',
			'type'     => 'upload',
			'title'    => esc_html__( 'Apple Iphone Retina Icon', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Apple Iphone Retina Icon (114px114px).', 'sweetdate' ),
			'std'      => get_template_directory_uri() . '/assets/images/icons/apple-touch-icon-114x114.png'
		),
		array(
			'id'       => 'apple72',
			'type'     => 'upload',
			'title'    => esc_html__( 'Apple iPad Icon', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Apple Iphone Retina Icon (72px72px).', 'sweetdate' ),
			'std'      => get_template_directory_uri() . '/assets/images/icons/apple-touch-icon-72x72.png'
		),
		array(
			'id'       => 'apple144',
			'type'     => 'upload',
			'title'    => esc_html__( 'Apple iPad Retina Icon', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Apple iPad Retina Icon (144px144px).', 'sweetdate' ),
			'std'      => get_template_directory_uri() . '/assets/images/icons/apple-touch-icon-144x144.png'
		),

		array(
			'id'       => 'analytics',
			'type'     => 'textarea',
			'title'    => esc_html__( 'Analytics code', 'sweetdate' ),
			'sub_desc' => __( 'Paste your Google Analytics or other tracking code.<br/> This will be loaded in the footer.', 'sweetdate' ),
			'desc'     => ''
		),

	)
);

$sections[] = array(
	'icon'       => 'th-large',
	'icon_class' => 'icon-large',
	'title'      => esc_html__( 'Layout settings', 'sweetdate' ),
	'desc'       => __( '<p class="description">Here you set options for the layout.</p>', 'sweetdate' ),

	'fields' => array(
		array(
			'id'       => 'responsive_design',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Responsive Design', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Enable or disable responsive design', 'sweetdate' ),
			'switch'   => true,
			'std'      => '1' // 1 = checked | 0 = unchecked
		),
		array(
			'id'           => 'site_style',
			'type'         => 'radio_img_hide_bellow',
			'title'        => esc_html__( 'Site Layout', 'sweetdate' ),
			'sub_desc'     => esc_html__( 'Select between wide or boxed site layout', 'sweetdate' ),
			'options'      => array(
				'wide-style'  => array(
					'title' => 'Wide',
					'img'   => SWEET_OPTIONS_URL . 'img/wide_layout.png',
					'allow' => 'false'
				),
				'boxed-style' => array(
					'title' => 'Boxed',
					'img'   => SWEET_OPTIONS_URL . 'img/boxed_layout.png',
					'allow' => 'true'
				),
			),
			'std'          => 'wide-style',
			'next_to_hide' => '1'
		),

		//BOXED BACKGROUND
		array(
			'id'       => 'boxed_background',
			'type'     => 'bg',
			'title'    => __( 'Boxed background type', 'sweetdate' ),
			'sub_desc' => __( 'Select the type of background you want to use for boxed background.', 'sweetdate' ),
			'desc'     => '',
			'std'      => array(
				'type'           => 'pattern', //image,pattern,color
				'pattern'        => 'p1',
				'image'          => '',
				'img_repeat'     => 'repeat',
				'img_vertical'   => 'top',
				'img_horizontal' => 'center',
				'img_size'       => 'auto',
				'img_attachment' => 'scroll',
				'color'          => '#EEEEEE'
			)
		),

		array(
			'id'       => 'global_sidebar',
			'type'     => 'radio_img',
			'title'    => __( 'Default layout', 'sweetdate' ),
			'sub_desc' => __( 'Select the layout to use by default. This can be changed individualy on page/post edit', 'sweetdate' ),
			'options'  => array(
				'no'    => array( 'title' => 'No sidebar', 'img' => SWEET_OPTIONS_URL . 'img/1col.png' ),
				'left'  => array( 'title' => 'Left Sidebar', 'img' => SWEET_OPTIONS_URL . 'img/2cl.png' ),
				'right' => array( 'title' => 'Right Sidebar', 'img' => SWEET_OPTIONS_URL . 'img/2cr.png' ),
				'3ll'   => array( 'title' => 'Two Left Sidebars', 'img' => SWEET_OPTIONS_URL . 'img/3ll.png' ),
				'3rr'   => array( 'title' => 'Two Right Sidebars', 'img' => SWEET_OPTIONS_URL . 'img/3rr.png' ),
				'3lr'   => array(
					'title' => 'Right and Left Sidebars',
					'img'   => SWEET_OPTIONS_URL . 'img/3lr.png'
				),

			), // Must provide key => value(array:title|img) pairs for radio options
			'std'      => 'right'
		),
		array(
			'id'       => 'sticky_menu',
			'type'     => 'checkbox',
			'title'    => __( 'Sticky Menu', 'sweetdate' ),
			'sub_desc' => __( 'Enable or disable the main menu to stay at top of the screen while you scroll down.', 'sweetdate' ),
			'switch'   => true,
			'std'      => '1' // 1 = checked | 0 = unchecked
		),
		array(
			'id'       => 'ajax_search',
			'type'     => 'checkbox',
			'title'    => __( 'Ajax Search in menu', 'sweetdate' ),
			'sub_desc' => __( 'Enable or disable the button for search.', 'sweetdate' ),
			'switch'   => true,
			'std'      => '1' // 1 = checked | 0 = unchecked
		),


	)

);


$sections[] = array(
	'icon'       => 'adjust',
	'icon_class' => 'icon-large',
	'title'      => __( 'Styling options', 'sweetdate' ),
	'desc'       => __( '<p class="description">Customize theme appearance</p>', 'sweetdate' ),
	'fields'     => array(
		//header
		array(
			'id'   => 'info_header',
			'type' => 'info',
			'desc' => __( '<h4 class="subtitle">Header section</h4>', 'sweetdate' )
		),
		array(
			'id'       => 'header_background',
			'type'     => 'bg',
			'title'    => esc_html__( 'Background', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Select the background you want to use for header.', 'sweetdate' ),
			'desc'     => '',
			'std'      => array(
				'type'           => 'pattern', //image,pattern,color
				'pattern'        => 'blue',
				'image'          => '',
				'img_repeat'     => 'repeat',
				'img_vertical'   => 'top',
				'img_horizontal' => 'center',
				'img_size'       => 'auto',
				'img_attachment' => 'scroll',
				'color'          => '#0076A3'
			)
		),

		//header colors
		array(
			'id'       => 'header_font_color',
			'type'     => 'color',
			'title'    => esc_html__( 'Header Text color', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Select text color to use in header. This color also applies to home search form text because it resides in header section', 'sweetdate' ),
			'std'      => '#ffffff'
		),
		array(
			'id'       => 'header_primary_color',
			'type'     => 'color',
			'title'    => esc_html__( 'Top Menu Link Color', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Select your main color to use for top menu links and other elements.', 'sweetdate' ),
			'std'      => '#ffffff'
		),
		array(
			'id'       => 'menu_color_enabled',
			'type'     => 'color',
			'title'    => esc_html__( 'Top Menu Link Color when visible background', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Select your link color to use when the background is visible.', 'sweetdate' ),
			'std'      => '#ffffff'
		),
		array(
			'id'       => 'menu_primary_color',
			'type'     => 'color',
			'title'    => esc_html__( 'Top Menu Background Color', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Select your main color to use for top menu', 'sweetdate' ),
			'std'      => '#1FA8D1'
		),
		array(
			'id'       => 'header_secondary_color',
			'type'     => 'color',
			'title'    => esc_html__( 'Top Menu Link Color on hover', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Select your color to use for top menu links on hover.', 'sweetdate' ),
			'std'      => '#ffffff'
		),
		array(
			'id'       => 'menu_secondary_color',
			'type'     => 'color',
			'title'    => esc_html__( 'Top Menu Background Color on hover ', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Select your background color to use for top menu on mouse hover.', 'sweetdate' ),
			'std'      => '#37b8dd'
		),


		//breadcrumb section
		array(
			'id'   => 'info_breadcrumb',
			'type' => 'info',
			'desc' => __( '<h4 class="subtitle">Breadcrumb section</h4>', 'sweetdate' )
		),
		array(
			'id'       => 'breadcrumb_status',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Enable breadcrumb', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Enable or disable breadcrumb.', 'sweetdate' ),
			'switch'   => true,
			'std'      => '1' // 1 = checked | 0 = unchecked
		),

		array(
			'id'       => 'breadcrumb_background',
			'type'     => 'bg',
			'title'    => esc_html__( 'Background', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Select the background you want to use for breadcrumb.', 'sweetdate' ),
			'desc'     => '',
			'std'      => array(
				'type'           => 'color', //image,pattern,color
				'pattern'        => 'blue',
				'image'          => '',
				'img_repeat'     => 'repeat',
				'img_vertical'   => 'top',
				'img_horizontal' => 'center',
				'img_size'       => 'auto',
				'img_attachment' => 'scroll',
				'color'          => '#0095C2'
			)
		),
		//main colors
		array(
			'id'       => 'breadcrumb_font_color',
			'type'     => 'color',
			'title'    => esc_html__( 'Text color', 'sweetdate' ),
			'sub_desc' => esc_html__( 'Select text color to use in main content area', 'sweetdate' ),
			'std'      => '#f0f0f0'
		),
		array(
			'id'       => 'breadcrumb_primary_color',
			'type'     => 'color',
			'title'    => __( 'Primary Color', 'sweetdate' ),
			'sub_desc' => __( 'Select your main color to use for links and other elements.', 'sweetdate' ),
			'std'      => '#ffffff'
		),
		array(
			'id'       => 'breadcrumb_secondary_color',
			'type'     => 'color',
			'title'    => __( 'Highlight Color', 'sweetdate' ),
			'sub_desc' => __( 'Select your secondary color to use for hover links and other elements.', 'sweetdate' ),
			'std'      => '#7de0fe'
		),

		//body section
		array(
			'id'   => 'info_body',
			'type' => 'info',
			'desc' => __( '<h4 class="subtitle">Main section</h4>', 'sweetdate' )
		),
		array(
			'id'       => 'body_background',
			'type'     => 'bg',
			'title'    => __( 'Background', 'sweetdate' ),
			'sub_desc' => __( 'Select the background you want to use for main area.', 'sweetdate' ),
			'desc'     => '',
			'std'      => array(
				'type'           => 'color', //image,pattern,color
				'pattern'        => 'gray',
				'image'          => '',
				'img_repeat'     => 'repeat',
				'img_vertical'   => 'top',
				'img_horizontal' => 'center',
				'img_size'       => 'auto',
				'img_attachment' => 'scroll',
				'color'          => '#ffffff'
			)
		),
		//body colors
		array(
			'id'       => 'body_font_color',
			'type'     => 'color',
			'title'    => __( 'Text color', 'sweetdate' ),
			'sub_desc' => __( 'Select text color to use.', 'sweetdate' ),
			'std'      => '#777777'
		),
		array(
			'id'       => 'body_primary_color',
			'type'     => 'color',
			'title'    => __( 'Primary Color', 'sweetdate' ),
			'sub_desc' => __( 'Select your main color to use for links and other elements.', 'sweetdate' ),
			'std'      => '#333333'
		),
		array(
			'id'       => 'body_secondary_color',
			'type'     => 'color',
			'title'    => __( 'Highlight Color', 'sweetdate' ),
			'sub_desc' => __( 'Select your secondary color to use for hover links and other elements.', 'sweetdate' ),
			'std'      => '#0296C0'
		),
		//sidebar colors
		array(
			'id'       => 'sidebar_font_color',
			'type'     => 'color',
			'title'    => __( 'Sidebar Text color', 'sweetdate' ),
			'sub_desc' => __( 'Select text color to use for sidebar.', 'sweetdate' ),
			'std'      => '#777777'
		),
		array(
			'id'       => 'sidebar_primary_color',
			'type'     => 'color',
			'title'    => __( 'Sidebar Primary Color', 'sweetdate' ),
			'sub_desc' => __( 'Select your main color to use for links and other elements in sidebar.', 'sweetdate' ),
			'std'      => '#666666'
		),
		array(
			'id'       => 'sidebar_secondary_color',
			'type'     => 'color',
			'title'    => __( 'Sidebar Highlight Color', 'sweetdate' ),
			'sub_desc' => __( 'Select your secondary color to use for hover links and other elements in sidebar.', 'sweetdate' ),
			'std'      => '#0296C0'
		),


		//footer bg
		array(
			'id'   => 'info_footer',
			'type' => 'info',
			'desc' => __( '<h4 class="subtitle">Footer section</h4>', 'sweetdate' )
		),
		array(
			'id'       => 'footer_background',
			'type'     => 'bg',
			'title'    => __( 'Background', 'sweetdate' ),
			'sub_desc' => __( 'Select the background you want to use for footer.', 'sweetdate' ),
			'desc'     => '',
			'std'      => array(
				'type'           => 'pattern', //image,pattern,color
				'pattern'        => 'black',
				'image'          => '',
				'img_repeat'     => 'repeat',
				'img_vertical'   => 'top',
				'img_horizontal' => 'center',
				'img_size'       => 'auto',
				'img_attachment' => 'scroll',
				'color'          => '#171717'
			)
		),
		//footer colors
		array(
			'id'       => 'footer_font_color',
			'type'     => 'color',
			'title'    => __( 'Footer Text color', 'sweetdate' ),
			'sub_desc' => __( 'Select text color to use in footer', 'sweetdate' ),
			'std'      => '#777777'
		),
		array(
			'id'       => 'footer_primary_color',
			'type'     => 'color',
			'title'    => __( 'Footer Primary Color', 'sweetdate' ),
			'sub_desc' => __( 'Select your main color to use for links and other elements.', 'sweetdate' ),
			'std'      => '#F00056'
		),
		array(
			'id'       => 'footer_secondary_color',
			'type'     => 'color',
			'title'    => __( 'Footer Highlight Color', 'sweetdate' ),
			'sub_desc' => __( 'Select your secondary color to use for hover links and other elements.', 'sweetdate' ),
			'std'      => '#0296C0'
		),


		/* BUTTONS */
		array(
			'id'   => 'info_buttons',
			'type' => 'info',
			'desc' => __( '<h4 class="subtitle">Buttons section</h4>', 'sweetdate' )
		),
		//PRIMARY BUTTON
		array(
			'id'       => 'button_bg_color',
			'type'     => 'color',
			'title'    => __( 'Button Background Color', 'sweetdate' ),
			'sub_desc' => __( 'Select the background color for your primary button.', 'sweetdate' ),
			'std'      => '#0296c0'
		),
		array(
			'id'       => 'button_text_color',
			'type'     => 'color',
			'title'    => __( 'Button Text Color', 'sweetdate' ),
			'sub_desc' => __( 'Select the text color for your primary button.', 'sweetdate' ),
			'std'      => '#ffffff'
		),
		array(
			'id'       => 'button_bg_color_hover',
			'type'     => 'color',
			'title'    => __( 'Button Hover Background Color', 'sweetdate' ),
			'sub_desc' => __( 'Select the background color for your primary button on hover.', 'sweetdate' ),
			'std'      => '#1FA8D1'
		),
		array(
			'id'       => 'button_text_color_hover',
			'type'     => 'color',
			'title'    => __( 'Button Hover Text Color', 'sweetdate' ),
			'sub_desc' => __( 'Select the text color for your primary button on hover.', 'sweetdate' ),
			'std'      => '#ffffff'
		),
		//SECONDARY BUTTON
		array(
			'id'       => 'button_secondary_bg_color',
			'type'     => 'color',
			'title'    => __( 'Secondary Button Background Color', 'sweetdate' ),
			'sub_desc' => __( 'Select the background color for your secondary button.', 'sweetdate' ),
			'std'      => '#E6E6E6'
		),
		array(
			'id'       => 'button_secondary_text_color',
			'type'     => 'color',
			'title'    => __( 'Secondary Button Text Color', 'sweetdate' ),
			'sub_desc' => __( 'Select the text color for your secondary button.', 'sweetdate' ),
			'std'      => '#1D1D1D'
		),
		array(
			'id'       => 'button_secondary_bg_color_hover',
			'type'     => 'color',
			'title'    => __( 'Secondary Button Hover Background Color', 'sweetdate' ),
			'sub_desc' => __( 'Select the background color for your secondary button on hover.', 'sweetdate' ),
			'std'      => '#DDDCDC'
		),
		array(
			'id'       => 'button_secondary_text_color_hover',
			'type'     => 'color',
			'title'    => __( 'Secondary Button Hover Text Color', 'sweetdate' ),
			'sub_desc' => __( 'Select the text color for your secondary button on hover.', 'sweetdate' ),
			'std'      => '#1D1D1D'
		),

		//headings
		array(
			'id'   => 'info_heading',
			'type' => 'info',
			'desc' => __( '<h4 class="subtitle">Heading section</h4>', 'sweetdate' )
		),
		array(
			'id'       => 'heading',
			'type'     => 'typography',
			'title'    => __( 'Headings Font', 'sweetdate' ),
			'sub_desc' => __( 'Select font to use', 'sweetdate' ),
			'desc'     => '',
			'std'      => array(
				'h1'   => array(
					'size'  => '46px',
					'style' => 'normal',
					'color' => '#222222',
					'font'  => 'Lato:regular'
				),
				'h2'   => array(
					'size'  => '30px',
					'style' => 'normal',
					'color' => '#222222',
					'font'  => 'Lato:regular'
				),
				'h3'   => array(
					'size'  => '26px',
					'style' => 'normal',
					'color' => '#222222',
					'font'  => 'Lato:regular'
				),
				'h4'   => array(
					'size'  => '20px',
					'style' => 'normal',
					'color' => '#222222',
					'font'  => 'Open Sans:regular'
				),
				'h5'   => array(
					'size'  => '17px',
					'style' => 'normal',
					'color' => '#222222',
					'font'  => 'Open Sans:regular'
				),
				'h6'   => array(
					'size'  => '14px',
					'style' => 'normal',
					'color' => '#222222',
					'font'  => 'Open Sans:regular'
				),
				'body' => array(
					'size'  => '13px',
					'style' => 'normal',
					'color' => '#777777',
					'font'  => 'Open Sans:regular'
				),
			)
		),
		array(
			'id'       => 'quick_css',
			'type'     => 'textarea',
			'title'    => __( 'Quick css', 'sweetdate' ),
			'sub_desc' => __( 'Place you custom css here', 'sweetdate' ),
			'desc'     => ''
		),


	)
);

//revoluton sliders
$revsliders = kleo_revslide_sliders();


if(sq_option('old_home') == 1 ) {
    $sections[] = array(
        'icon' => 'home',
        'icon_class' => 'icon-large',
        'title' => __('Homepage(deprecated)', 'sweetdate'),
        'desc' => __('<p class="description">Since version 3.0 this will not be used.
                                You should import the new Homepage from Appearance - Demo data and customize it using the Visual builder</p>', 'sweetdate'),
        'fields' => array(

            array(
                'id' => 'home_search',
                'type' => 'select_hide_below',
                'title' => __('Search Form', 'sweetdate'),
                'sub_desc' => __('Choose what to display in the Homepage form:<br> Disabled - Do not show any form<br>Search form - Show a configurable search form<br>Register form - Display a register form<br> Mixed form - If the user is logged in show a search form, else show the register form ', 'sweetdate'),
                'options' => array(
                    '0' => array('name' => "Disabled", 'allow' => 'false'),
                    '1' => array('name' => "Search form", 'allow' => 'true'),
                    '2' => array('name' => "Register form", 'allow' => 'true'),
                    '3' => array('name' => "Mixed form", 'allow' => 'true')
                ),
                'std' => '1'

            ),
            array(
                'id' => 'home_search_members',
                'type' => 'checkbox',
                'title' => __('Latest Members carousel', 'sweetdate'),
                'sub_desc' => __('Enable or disable members carousel in search form.', 'sweetdate'),
                'switch' => true,
                'std' => '1' // 1 = checked | 0 = unchecked
            ),
            array(
                'id' => 'home_rev',
                'type' => 'select_hide_below',
                'title' => __('Revolution Slider', 'sweetdate'),
                'sub_desc' => __('Enable Revolution slider in Homepage header', 'sweetdate'),
                'options' => array(
                    '0' => array('name' => "Disabled", 'allow' => 'false'),
                    '1' => array('name' => "Enabled", 'allow' => 'true')
                ),
                'std' => '0',
                'next_to_hide' => 2
            ),
            array(
                'id' => 'home_rev_slide',
                'type' => 'select',
                'title' => __('Slider', 'sweetdate'),
                'sub_desc' => __('Choose the slider to show', 'sweetdate'),
                'options' => $revsliders,
                'std' => ''
            ),

            array(
                'id' => 'home_rev_transparent',
                'type' => 'checkbox',
                'title' => __('Transparent header', 'sweetdate'),
                'sub_desc' => __('If enabled the slider will start from the very top of the page and the header will go over the slider.', 'sweetdate'),
                'switch' => true,
                'std' => '1' // 1 = checked | 0 = unchecked
            ),

            array(
                'id' => 'home_pic_background',
                'type' => 'bg',
                'title' => __('Home Image', 'sweetdate'),
                'sub_desc' => __('Select the image that appears beside the search form.', 'sweetdate'),
                'desc' => '',
                'std' => array(
                    'type' => 'image', //image,pattern,color
                    'pattern' => 'gray',
                    'image' => get_template_directory_uri() . '/assets/images/header_image_bg.png',
                    'img_repeat' => 'no-repeat',
                    'img_vertical' => 'bottom',
                    'img_horizontal' => 'center',
                    'img_size' => 'contain',
                    'img_attachment' => 'scroll',
                    'color' => 'transparent'
                )
            )
        )
    );
}
if ( function_exists( 'bp_is_active' ) ):

	$sections[] = array(
		'icon'       => 'user',
		'icon_class' => 'icon-large',
		'title'      => __( 'Buddypress', 'sweetdate' ),
		'desc'       => __( '<p class="description">Here you can customize Buddypress settings</p><p><a class="button button-primary" id="bp_import_fields" href="#">Import Buddypress profile fields</a></p>', 'sweetdate' ),
		'fields'     => array(

			/*array(
			'id' => 'bp_album',
			'type' => 'checkbox',
			'title' => __('BP-Album', 'sweetdate'),
			'sub_desc' => __('DEPRECATED. Please use rtMedia plugin.', 'sweetdate'),
			'switch' => true,
			'std' => '0' // 1 = checked | 0 = unchecked
		),*/
			array(
				'id'       => 'bp_plugins_hook',
				'type'     => 'checkbox',
				'title'    => __( 'Buddypress Plugins Hook', 'sweetdate' ),
				'sub_desc' => __( 'Show on register pop-up hook option [bp_before_registration_submit_buttons] (EX: Catpcha,Antispam plugins)', 'sweetdate' ),
				'switch'   => true,
				'std'      => '0' // 1 = checked | 0 = unchecked
			),
			array(
				'id'       => 'bp_online_status',
				'type'     => 'checkbox',
				'title'    => __( 'Online status', 'sweetdate' ),
				'sub_desc' => __( 'Show users online status in Members page (colored dot over the avatar)', 'sweetdate' ),
				'switch'   => true,
				'std'      => '0' // 1 = checked | 0 = unchecked
			),
			array(
				'id'       => 'buddypress_sidebar',
				'type'     => 'radio_img',
				'title'    => __( 'General Buddypress Pages Layout', 'sweetdate' ),
				'sub_desc' => __( 'Select the layout to by default use in Buddypress pages.', 'sweetdate' ),
				'options'  => array(
					'no'    => array( 'title' => 'No sidebar', 'img' => SWEET_OPTIONS_URL . 'img/1col.png' ),
					'left'  => array( 'title' => 'Left Sidebar', 'img' => SWEET_OPTIONS_URL . 'img/2cl.png' ),
					'right' => array( 'title' => 'Right Sidebar', 'img' => SWEET_OPTIONS_URL . 'img/2cr.png' ),
					'3ll'   => array( 'title' => 'Two Left Sidebars', 'img' => SWEET_OPTIONS_URL . 'img/3ll.png' ),
					'3rr'   => array(
						'title' => 'Two Right Sidebars',
						'img'   => SWEET_OPTIONS_URL . 'img/3rr.png'
					),
					'3lr'   => array(
						'title' => 'Right and Left Sidebars',
						'img'   => SWEET_OPTIONS_URL . 'img/3lr.png'
					),
				), // Must provide key => value(array:title|img) pairs for radio options
				'std'      => 'right'
			),
			array(
				'id'       => 'bp_members_sidebar',
				'type'     => 'select',
				'title'    => __( 'Members Directory Layout', 'sweetdate' ),
				'sub_desc' => __( 'Select the layout to use in Members Directory page.', 'sweetdate' ),
				'options'  => array(
					'no'    => 'No sidebar',
					'left'  => 'Left Sidebar',
					'right' => 'Right Sidebar',
					'3ll'   => 'Two Left Sidebars',
					'3rr'   => 'Two Right Sidebars',
					'3lr'   => 'Right and Left Sidebars',
				),
				'std'      => 'no'
			),
			array(
				'id'       => 'bp_member_sidebar',
				'type'     => 'select',
				'title'    => __( 'Single Member Page Layout', 'sweetdate' ),
				'sub_desc' => __( 'Select the layout to use in Single Member page.', 'sweetdate' ),
				'options'  => array(
					'default' => 'Default as above',
					'no'      => 'No sidebar',
					'left'    => 'Left Sidebar',
					'right'   => 'Right Sidebar',
					'3ll'     => 'Two Left Sidebars',
					'3rr'     => 'Two Right Sidebars',
					'3lr'     => 'Right and Left Sidebars',
				),
				'std'      => 'default'
			),

			array(
				'id'       => 'bp_members_search',
				'type'     => 'checkbox',
				'title'    => __( 'BuddyPress Members Search input', 'sweetdate' ),
				'sub_desc' => __( 'Enable default Buddypress Search form in members directory', 'sweetdate' ),
				'switch'   => true,
				'std'      => '0' // 1 = checked | 0 = unchecked
			),
			array(
				'id'       => 'bp_ajax_pagination',
				'type'     => 'checkbox',
				'title'    => __( 'BuddyPress Members directory ajax pagination', 'sweetdate' ),
				'sub_desc' => __( 'Set this OFF if you want to have pagination url in members directory ex : /page/2 instead ajax pagination', 'sweetdate' ),
				'switch'   => true,
				'std'      => '1' // 1 = checked | 0 = unchecked
			),

			array(
				'id'       => 'bp_search_form',
				'type'     => 'text',
				'title'    => __( 'Search form customization(DEPRECATED)', 'sweetdate' ),
				'sub_desc' => __( 'Use BP Profile search plugin. Edit Homepage using Visual page builder and select the search form to show.', 'sweetdate' ),
				'std'      => array(
					'before_form' => 'Serious dating with <strong>Sweet date</strong><br>Your perfect match is just a click away',
					'agelabel'    => 'Age',

				),
				'callback' => 'bp_customize_form'
			),
			array(
				'id'       => 'bp_sex_field',
				'type'     => 'text',
				'title'    => __( 'Sex field(DEPRECATED)', 'sweetdate' ),
				'sub_desc' => __( 'Select you sex field. This is used for man,woman online statistics', 'sweetdate' ),
				'std'      => 'I am a',
				'callback' => 'bp_profile_field'
			),
			array(
				'id'       => 'bp_age_field',
				'type'     => 'text',
				'title'    => __( 'Age field', 'sweetdate' ),
				'sub_desc' => __( 'Select you Age field. This is used to calculate members age', 'sweetdate' ),
				'std'      => 'Birthday',
				'callback' => 'bp_profile_date_field'
			),
			array(
				'id'       => 'bp_birthdate_to_age',
				'type'     => 'checkbox',
				'title'    => __( 'Show age instead of birthdate', 'sweetdate' ),
				'sub_desc' => __( 'Enable to show members age insted of Birtdate in user profile.', 'sweetdate' ),
				'switch'   => true,
				'std'      => '0' // 1 = checked | 0 = unchecked
			),


			array(
				'id'           => 'bp_autocomplete',
				'type'         => 'checkbox_hide_below',
				'title'        => __( 'City autocomplete', 'sweetdate' ),
				'sub_desc'     => __( 'Enable or disable City autocomplete based on Country field<br>Your City profile Field Type must be set as Text Box<br>This uses http://geonames.org API', 'sweetdate' ),
				'switch'       => true,
				'std'          => '0', // 1 = checked | 0 = unchecked
				'next_to_hide' => 4
			),
			array(
				'id'       => 'bp_city_username',
				'type'     => 'text',
				'title'    => __( 'Geonames username', 'sweetdate' ),
				'sub_desc' => 'This field is required for the service to work. Get one from geonames.org and enable the free service from your account',
				'std'      => ''
			),

			array(
				'id'       => 'bp_city_field',
				'type'     => 'text',
				'title'    => __( 'City field', 'sweetdate' ),
				'sub_desc' => __( 'Select which is your City field that will autocomplete', 'sweetdate' ),
				'std'      => '0',
				'callback' => 'bp_profile_field'
			),
			array(
				'id'       => 'bp_country_field',
				'type'     => 'text',
				'title'    => __( 'Country field', 'sweetdate' ),
				'sub_desc' => __( 'Select you Country field. Based on this, the City field will populate.<br> If you do not have one, set the Country Code bellow', 'sweetdate' ),
				'std'      => '0',
				'callback' => 'bp_profile_field'
			),
			array(
				'id'       => 'bp_country_code',
				'type'     => 'text',
				'title'    => __( 'Country Code', 'sweetdate' ),
				'sub_desc' => __( "If you don't have a Country field then set here the ISO-3166 alpha2 code of you Country for which you want to enable the autocomplete.<br>http://www.geonames.org/countries/", 'sweetdate' ),
				'std'      => ''
			),
			array(
				'id'       => 'buddypress_age_start',
				'type'     => 'text',
				'title'    => __( 'Members search - Inferior age search limit', 'sweetdate' ),
				'sub_desc' => __( 'Enter the inferior age limit to search members for.', 'sweetdate' ),
				'validate' => 'numeric',
				'msg'      => 'Please enter a numeric value',
				'std'      => '18'
			),
			array(
				'id'       => 'buddypress_age_end',
				'type'     => 'text',
				'title'    => __( 'Members search - Superior age search limit', 'sweetdate' ),
				'sub_desc' => __( 'Enter the superior age limit to search members for', 'sweetdate' ),
				'validate' => 'numeric',
				'msg'      => 'Please enter a numeric value',
				'std'      => '75'
			),
			array(
				'id'       => 'buddypress_perpage',
				'type'     => 'text',
				'title'    => __( 'Members per page', 'sweetdate' ),
				'sub_desc' => __( 'Enter the number of profiles per page to show on members listing.', 'sweetdate' ),
				'validate' => 'numeric',
				'msg'      => 'Please enter a numeric value',
				'std'      => '12'
			),

			//buddy header bg
			array(
				'id'   => 'bp_header',
				'type' => 'info',
				'desc' => __( '<h4 class="subtitle">Profile header</h4>', 'sweetdate' )
			),

			array(
				'id'       => 'bp_header_background',
				'type'     => 'bg',
				'title'    => __( 'Background', 'sweetdate' ),
				'sub_desc' => __( 'Select the background you want to use for buddypress header.', 'sweetdate' ),
				'desc'     => '',
				'std'      => array(
					'type'           => 'color', //image,pattern,color
					'pattern'        => 'gray',
					'image'          => '',
					'img_repeat'     => 'repeat',
					'img_vertical'   => 'top',
					'img_horizontal' => 'center',
					'img_size'       => 'auto',
					'img_attachment' => 'scroll',
					'color'          => '#0095c2'
				)
			),
			//buddy colors
			array(
				'id'       => 'bp_header_font_color',
				'type'     => 'color',
				'title'    => __( 'Text color', 'sweetdate' ),
				'sub_desc' => __( 'Select text color to use.', 'sweetdate' ),
				'std'      => '#ffffff'
			),
			array(
				'id'       => 'bp_header_primary_color',
				'type'     => 'color',
				'title'    => __( 'Primary Color', 'sweetdate' ),
				'sub_desc' => __( 'Select your main color to use for links and other elements.', 'sweetdate' ),
				'std'      => '#ffffff'
			),
			array(
				'id'       => 'bp_header_secondary_color',
				'type'     => 'color',
				'title'    => __( 'Highlight Color', 'sweetdate' ),
				'sub_desc' => __( 'Select your secondary color to use for hover links and other elements.', 'sweetdate' ),
				'std'      => '#09a9d9'
			),

			array(
				'id'       => 'bp_items_transparency',
				'type'     => 'select',
				'title'    => __( 'Items transparency', 'sweetdate' ),
				'sub_desc' => __( 'Select the transparency for profile header elements.', 'sweetdate' ),
				'options'  => array(
					'0.1' => '0.1',
					'0.2' => '0.2',
					'0.3' => '0.3',
					'0.4' => '0.4',
					'0.5' => '0.5',
					'0.6' => '0.6',
					'0.7' => '0.7',
					'0.8' => '0.8',
					'0.9' => '0.9',
					'1'   => '1'
				),
				'std'      => '0.1'
			),

			array(
				'id'       => 'bp_match_bg_color',
				'type'     => 'color',
				'title'    => __( 'Matching Background Color', 'sweetdate' ),
				'sub_desc' => __( 'Select your Matching percentage animation background color.', 'sweetdate' ),
				'std'      => ''
			),
			array(
				'id'       => 'bp_match_fg_color',
				'type'     => 'color',
				'title'    => __( 'Matching Foreground Color', 'sweetdate' ),
				'sub_desc' => __( 'Select your Matching percentage animation background color.', 'sweetdate' ),
				'std'      => ''
			),
		)
	);

	$sections[] = array(
		'icon'       => 'user',
		'icon_class' => 'icon-large',
		'title'      => __( 'BP Profile Tabs', 'sweetdate' ),
		'desc'       => __( '<p class="description">Here you can customize Buddypress tabs that show next to user profile.</p>', 'sweetdate' ),
		'fields'     => array(

			array(
				'id'           => 'bp_tabs',
				'type'         => 'checkbox_hide_below',
				'title'        => __( 'Enable profile tabs customisation', 'sweetdate' ),
				'sub_desc'     => '',
				'switch'       => true,
				'next_to_hide' => 1,
				'std'          => '0' // 1 = checked | 0 = unchecked
			),
			array(
				'id'       => 'bp_tabs_data',
				'type'     => 'text',
				'title'    => __( 'Select the tabs to show', 'sweetdate' ),
				'sub_desc' => __( 'Profile fields groups are defined in WP admin -Users - Profile fields<br>You can also check to show images if you have rtMedia plugin installed', 'sweetdate' ),

				'callback' => 'kleo_bp_tabs_options'
			),

		)
	);


	$sections[] = array(
		'icon'       => 'heart',
		'icon_class' => 'icon-large',
		'title'      => __( 'Profiles matching', 'sweetdate' ),
		'desc'       => __( '<p class="description">Here you can customize Buddypress profiles matching functionality.</p>', 'sweetdate' ),
		'fields'     => array(

			array(
				'id'           => 'bp_match',
				'type'         => 'checkbox_hide_below',
				'title'        => __( 'Activate custom matching settings', 'sweetdate' ),
				'sub_desc'     => '',
				'switch'       => true,
				'next_to_hide' => 7,
				'std'          => '0' // 1 = checked | 0 = unchecked
			),

			array(
				'id'       => 'bp_match_start_percent',
				'type'     => 'text',
				'title'    => __( 'Starting percentage', 'sweetdate' ),
				'sub_desc' => 'From this value the matching will start',
				'std'      => '1',
			),

			array(
				'id'       => 'bp_comp_fields',
				'type'     => 'checkbox',
				'title'    => 'Enable complementary fields',
				'sub_desc' => esc_html__( 'Algorithm will look one field value in the matching one. Example: I am a -> Looking for a', 'sweetdate' ),
				'switch'   => true,
				'std'      => '0' // 1 = checked | 0 = unchecked
			),

			array(
				'id'       => 'bp_comp_field1',
				'type'     => 'text',
				'title'    => esc_html__( 'First Complementary Field', 'sweetdate' ),
				'sub_desc' => '',
				'std'      => '',
				'callback' => 'bp_profile_field'
			),
			array(
				'id'       => 'bp_comp_field2',
				'type'     => 'text',
				'title'    => esc_html__( 'Second Complementary field', 'sweetdate' ),
				'sub_desc' => '',
				'std'      => '',
				'callback' => 'bp_profile_field'
			),
			array(
				'id'       => 'bp_comp_percent',
				'type'     => 'text',
				'title'    => esc_html__( 'Complementary fields percentage', 'sweetdate' ),
				'sub_desc' => '',
				'std'      => '49',
			),
			array(
				'id'       => 'bp_comp_mandatory',
				'type'     => 'checkbox',
				'switch'   => true,
				'title'    => esc_html__( 'Complementary fields mandatory matching', 'sweetdate' ),
				'sub_desc' => 'If is set to mandatory then if the complementary matching fails, the rest of the fields are no longer checked and he mathing result will be zero. ' .
				              '<br>Example: If the fields are set to "I am a/Looking for a" then the rest of fields do not matter if the sex is not what they are looking for',
				'std'      => '1',
			),

			array(
				'id'       => 'bp_match_data',
				'type'     => 'text',
				'title'    => esc_html__( 'Matching fields & Percentages', 'sweetdate' ),
				'sub_desc' => esc_html__( 'Profile fields are defined in WP admin - Users - Profile fields', 'sweetdate' ) .
				              esc_html__( 'Set a percentage impact for each field you want to consider and make sure they sum up 100%', 'sweetdate' ),

				'callback' => 'kleo_bp_match_options'
			),

		)
	);


	//theme options get fields
	function kleo_bp_match_options( $field, $values ) {
		if ( bp_is_active( 'xprofile' ) ) :
			if ( function_exists( 'bp_has_profile' ) ) :
				if ( bp_has_profile( 'hide_empty_fields=0' ) ) :

					echo '<ul class="bp-matches-data">';

					while ( bp_profile_groups() ) : bp_the_profile_group();

						while ( bp_profile_fields() ) : bp_the_profile_field();

							switch ( bp_get_the_profile_field_type() ) {

								case 'multiselectbox':
								case 'checkbox':
									$field_type = 'multi';
									break;

								default:
									$field_type = 'single';
									break;
							}
							?>
							<li class="clearfix"><label>
									<input type="checkbox"
									       name="<?php echo KLEO_DOMAIN . '[' . $field['id'] . '][' . $field_type . '][]'; ?>"
									       value="<?php echo esc_attr( bp_get_the_profile_field_id() ); ?>"
										<?php if ( is_array( $values ) && ! empty( $values[ $field_type ] ) && in_array( bp_get_the_profile_field_id(), (array) $values[ $field_type ] ) ) {
											echo ' checked="checked"';
										} ?> />
									<?php bp_the_profile_field_name(); ?>
								</label><br>
								<input type="text" size="5"
								       name="<?php echo KLEO_DOMAIN . '[' . $field['id'] . '][percentages][' . bp_get_the_profile_field_id() . ']'; ?>"
								       value="<?php if ( is_array( $values ) && ! empty( $values['percentages'] ) && isset( $values['percentages'][ bp_get_the_profile_field_id() ] ) ) {
									       echo esc_attr( $values['percentages'][ bp_get_the_profile_field_id() ] );
								       } ?>"> Percentage
							</li>

						<?php
						endwhile;
					endwhile;
					echo '</ul>';

				endif;
			endif;
		endif;

		return true;
	}


	function kleo_bp_tabs_options( $field, $value ) {
		if ( bp_is_active( 'xprofile' ) ) {

			wp_enqueue_script(
				'squeen-opts-field-social-links-js',
				SWEET_OPTIONS_URL . 'js/sortable.js',
				array( 'jquery' ),
				time(),
				true
			);

			echo '<ul class="text_sortable bp-tabs-data">';


			if ( isset( $value['fields'] ) && ! empty( $value['fields'] ) ) {
				foreach ( $value['fields'] as $checked ) {

					if ( strpos( $checked, 'group' ) !== false ) {
						$group = xprofile_get_field_group( substr( $checked, 6 ) );
						if ( $group ) {
							$name = "Group - " . $group->name;
						} else {
							$name = ucwords( str_replace( "_", " ", $checked ) );
						}
					} else {
						$name = ucwords( str_replace( "_", " ", $checked ) );
					}

					echo '<li class="clearfix">' .
					     '<label style="width: auto;"><input type="checkbox" checked="checked" name="' . KLEO_DOMAIN . '[' . $field['id'] . '][fields][]" value="' . $checked . '">' .
					     '<strong>' . $name . '</strong>' .
					     ' </label><br>' .
					     'Label <input type="text" name="' . KLEO_DOMAIN . '[' . $field['id'] . '][labels][' . $checked . ']" value="' . ( isset( $value['labels'][ $checked ] ) ? $value['labels'][ $checked ] : '' ) . '">' .
					     '&nbsp;&nbsp;<span class="drag"><i class="icon-move icon-large"></i></span>';
					if ( strpos( $checked, 'group' ) !== false ) {
						echo '<br>Type: ';
						echo '<input name="' . KLEO_DOMAIN . '[' . $field['id'] . '][types][' . $checked . ']" type="radio"' . ( isset( $value['types'][ $checked ] ) && $value['types'][ $checked ] == '' ? ' checked="checked"' : '' ) . ' value=""> Regular';
						echo ' <input name="' . KLEO_DOMAIN . '[' . $field['id'] . '][types][' . $checked . ']" type="radio"' . ( isset( $value['types'][ $checked ] ) && $value['types'][ $checked ] == 'cite' ? ' checked="checked"' : '' ) . ' value="cite"> Cite';
					}
					echo '</li>';
				}
			}


			foreach ( bp_profile_get_field_groups() as $k => $v ) {

				if ( isset( $value['fields'] ) && ! empty( $value['fields'] ) && in_array( 'group-' . $v->id, $value['fields'] ) ) {
					continue;
				}

				echo '<li class="clearfix"><label style="width: auto;">' .
				     '<input type="checkbox" name="' . KLEO_DOMAIN . '[' . $field['id'] . '][fields][]" value="group-' . $v->id . '">' .
				     '<strong>Group - ' . $v->name . '</strong>' .
				     ' </label><br>' .
				     'Label <input type="text" name="' . KLEO_DOMAIN . '[' . $field['id'] . '][labels][group-' . $v->id . ']" value="' . ( isset( $value['labels'][ 'group-' . $v->id ] ) ? $value['labels'][ 'group-' . $v->id ] : '' ) . '">' .
				     '&nbsp;&nbsp;<span class="drag"><i class="icon-move icon-large"></i></span>' .
				     '<br>Type: ' .
				     '<input name="' . KLEO_DOMAIN . '[' . $field['id'] . '][types][group-' . $v->id . ']" type="radio"' . ( isset( $value['types'][ 'group-' . $v->id ] ) && $value['types'][ 'group-' . $v->id ] == '' ? ' checked="checked"' : '' ) . ' value=""> Regular' .
				     ' <input name="' . KLEO_DOMAIN . '[' . $field['id'] . '][types][group-' . $v->id . ']" type="radio"' . ( isset( $value['types'][ 'group-' . $v->id ] ) && $value['types'][ 'group-' . $v->id ] == 'cite' ? ' checked="checked"' : '' ) . ' value="cite"> Cite' .
				     '</li>';
			}
			if ( class_exists( 'RTMedia' ) && ( ( isset( $value['fields'] ) && ! empty( $value['fields'] ) && ! in_array( 'rt_media', $value['fields'] ) && ! in_array( 'bp_album', $value['fields'] ) ) || ! isset( $value['fields'] ) ) ) {
				echo '<li class="clearfix"><label style="width: auto;"><input type="checkbox" name="' . KLEO_DOMAIN . '[' . $field['id'] . '][fields][]" value="rt_media">' .
				     '<strong>Rt Media</strong>' .
				     ' </label><br>' .
				     'Label <input type="text" name="' . KLEO_DOMAIN . '[' . $field['id'] . '][labels][' . $field['id'] . ']" value="' . ( isset( $value['labels']['rt_media'] ) ? $value['labels']['rt_media'] : '' ) . '">&nbsp;&nbsp;' .
				     '<span class="drag"><i class="icon-move icon-large"></i></span>' .
				     '</li>';
			} elseif ( function_exists( 'bp_album_query_pictures' ) && ( ( isset( $value['fields'] ) && ! empty( $value['fields'] ) && ! in_array( 'bp_album', $value['fields'] ) && ! in_array( 'rt_media', $value['fields'] ) ) || ! isset( $value['fields'] ) ) ) {
				echo '<li class="clearfix"><label style="width: auto;"><input type="checkbox" name="' . KLEO_DOMAIN . '[' . $field['id'] . '][fields][]" value="bp_album">' .
				     '<strong>Bp Album</strong>' .
				     ' </label><br>' .
				     'Label <input type="text" name="' . KLEO_DOMAIN . '[' . $field['id'] . '][labels][' . $field['id'] . ']" value="' . ( isset( $value['labels']['bp_album'] ) ? $value['labels']['bp_album'] : '' ) . '">&nbsp;&nbsp;' .
				     '<span class="drag"><i class="icon-move icon-large"></i></span>' .
				     '</li>';
			}
			echo '<ul>';
			echo '<style>.bp-tabs-data li {margin-bottom: 15px; border: 1px dashed #DFDFDF; padding: 5px;} .bp-tabs-data .drag {float:right;}</style>';
		} else {
			echo 'You must have Buddypress Extended Profiles Component active';
		}
	}


endif;

if ( class_exists('WooCommerce') ) {

	$sections[] = array(
		'icon'       => 'shopping-cart',
		'icon_class' => 'icon-large',
		'title'      => __( 'Woocommerce', 'sweetdate' ),
		'desc'       => '',
		'fields'     => array(
			array(
				'id'       => 'woo_sidebar',
				'type'     => 'select',
				'title'    => __( 'Woocommerce Pages Layout', 'sweetdate' ),
				'sub_desc' => __( 'Select the layout to use in Woocommerce pages.', 'sweetdate' ),
				'options'  => array(
					'default' => 'Default Site Setting',
					'no'      => 'No sidebar',
					'left'    => 'Left Sidebar',
					'right'   => 'Right Sidebar',
					'3ll'     => 'Two Left Sidebars',
					'3rr'     => 'Two Right Sidebars',
					'3lr'     => 'Right and Left Sidebars',
				),
				'std'      => 'default'
			)
		)
	);
}

if ( class_exists( 'bbPress' ) ) {

	$sections[] = array(
		'icon'       => 'comments',
		'icon_class' => 'icon-large',
		'title'      => __( 'bbPress', 'sweetdate' ),
		'desc'       => '',
		'fields'     => array(
			array(
				'id'       => 'bbpress_sidebar',
				'type'     => 'select',
				'title'    => __( 'bbPress Pages Layout', 'sweetdate' ),
				'sub_desc' => __( 'Select the layout to use in bbPress pages.', 'sweetdate' ),
				'options'  => array(
					'default' => 'Default Site Setting',
					'no'      => 'No sidebar',
					'left'    => 'Left Sidebar',
					'right'   => 'Right Sidebar',
					'3ll'     => 'Two Left Sidebars',
					'3rr'     => 'Two Right Sidebars',
					'3lr'     => 'Right and Left Sidebars',
				),
				'std'      => 'default'
			)
		)
	);
}


$sections[] = array(
	'icon'       => 'envelope',
	'icon_class' => 'icon-large',
	'title'      => __( 'Contact info &amp; Social', 'sweetdate' ),
	'desc'       => __( '<p class="description">Here you can set your contact info.</p>', 'sweetdate' ),
	'fields'     => array(

		array(
			'id'       => 'social_top',
			'type'     => 'checkbox',
			'title'    => __( 'Top Social Bar', 'sweetdate' ),
			'sub_desc' => __( 'Enable or disable top toolbar with social and contact info.', 'sweetdate' ),
			'switch'   => true,
			'std'      => '1' // 1 = checked | 0 = unchecked
		),

		array(
			'id'       => 'owner_email',
			'type'     => 'text',
			'title'    => __( 'Email', 'sweetdate' ),
			'sub_desc' => __( 'This will be displayed all over the site.', 'sweetdate' ),
			'validate' => 'email',
			'msg'      => 'Please enter a valid email address'
		),
		array(
			'id'       => 'owner_phone',
			'type'     => 'text',
			'title'    => __( 'Phone', 'sweetdate' ),
			'sub_desc' => __( 'This will be displayed all over the site.', 'sweetdate' ),
		),
		array(
			'id'       => 'phone_on_top',
			'type'     => 'checkbox',
			'title'    => __( 'Show Phone number on Top Social Bar', 'sweetdate' ),
			'sub_desc' => __( 'Enable or disable phone number on top toolbar.', 'sweetdate' ),
			'switch'   => true,
			'std'      => '0' // 1 = checked | 0 = unchecked
		),
		array(
			'id'       => 'twitter',
			'type'     => 'text',
			'title'    => __( 'Twitter address', 'sweetdate' ),
			'sub_desc' => __( 'Your Twitter URL address.', 'sweetdate' ),
			'validate' => 'url',
			'msg'      => 'Please enter a valid URL'
		),
		array(
			'id'       => 'youtube',
			'type'     => 'text',
			'title'    => __( 'Youtube address', 'sweetdate' ),
			'sub_desc' => __( 'Your Youtube URL address.', 'sweetdate' ),
			'validate' => 'url',
			'msg'      => 'Please enter a valid URL'
		),
		array(
			'id'       => 'instagram',
			'type'     => 'text',
			'title'    => __( 'Instagram address', 'sweetdate' ),
			'sub_desc' => __( 'Your Youtube URL address.', 'sweetdate' ),
			'validate' => 'url',
			'msg'      => 'Please enter a valid URL'
		),
		array(
			'id'       => 'facebook',
			'type'     => 'text',
			'title'    => __( 'Facebook address', 'sweetdate' ),
			'sub_desc' => __( 'Your Facebook URL address.', 'sweetdate' ),
			'validate' => 'url',
			'msg'      => 'Please enter a valid URL'
		),
		array(
			'id'       => 'googleplus',
			'type'     => 'text',
			'title'    => __( 'Google+ address', 'sweetdate' ),
			'sub_desc' => __( 'Your Google+ URL address.', 'sweetdate' ),
			'validate' => 'url',
			'msg'      => 'Please enter a valid URL'
		),
		array(
			'id'       => 'pinterest',
			'type'     => 'text',
			'title'    => __( 'Pinterest address', 'sweetdate' ),
			'sub_desc' => __( 'Your Pinterest URL address.', 'sweetdate' ),
			'validate' => 'url',
			'msg'      => 'Please enter a valid URL'
		),
		array(
			'id'       => 'linkedin',
			'type'     => 'text',
			'title'    => __( 'LinkedIn address', 'sweetdate' ),
			'sub_desc' => __( 'Your LinkedIn URL address.', 'sweetdate' ),
			'validate' => 'url',
			'msg'      => 'Please enter a valid URL'
		),
		array(
			'id'       => 'gps_lat',
			'type'     => 'text',
			'title'    => __( 'GPS Latitude', 'sweetdate' ),
			'sub_desc' => __( 'GPS Latitude used for Contact page - Google Maps.Ex: 32.990236', 'sweetdate' ),
		),
		array(
			'id'       => 'gps_lon',
			'type'     => 'text',
			'title'    => __( 'GPS Longitude', 'sweetdate' ),
			'sub_desc' => __( 'GPS Longitude used for Contact page - Google Maps.Ex: -96.679687', 'sweetdate' ),
		),
		array(
			'id'       => 'gps_key',
			'type'     => 'text',
			'title'    => __( 'Google maps API KEY', 'sweetdate' ),
			'sub_desc' => sprintf( __( 'See <a href="%s" target="_blank">this link</a> to generate your key.', 'sweetdate' ), 'https://developers.google.com/maps/documentation/javascript/get-api-key' ),
		)

	)
);

$sections[] = array(
	// Font Awesome iconfont to supply default icons.
	// If $args['icon_type'] = 'iconfont', this should be the icon name minus 'icon-'.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	// Icons can also be overridden on a section-by-section basis by defining 'icon_type' => 'image'
	'icon'       => 'cogs',
	'icon_type'  => 'iconfont',
	'icon_class' => 'icon-large',
	'title'      => __( 'Miscellaneous', 'sweetdate' ),
	'desc'       => __( '<p class="description">Facebook, Mailchimp, Themeforest settings</p>', 'sweetdate' ),
	'fields'     => array(
		array(
			'id'       => 'admin_bar',
			'type'     => 'checkbox',
			'title'    => __( 'Admin toolbar', 'sweetdate' ),
			'sub_desc' => __( 'Enable or disable wordpress default top toolbar', 'sweetdate' ),
			'switch'   => true,
			'std'      => '1' // 1 = checked | 0 = unchecked
		),
		array(
			'id'       => 'dev_mode',
			'type'     => 'checkbox',
			'title'    => __( 'Development mode', 'sweetdate' ),
			'sub_desc' => __( 'If you enable this, CSS and JS resources will not be loaded minified', 'sweetdate' ),
			'switch'   => true,
			'std'      => '1', // 1 = checked | 0 = unchecked
		),
        array(
			'id'       => 'old_home',
			'type'     => 'checkbox',
			'title'    => __( 'Enable Old Homepge Settings Section', 'sweetdate' ),
			'sub_desc' => __( 'This options addresses only to the users that run < 3.0 theme version', 'sweetdate' ),
			'switch'   => true,
			'std'      => '0', // 1 = checked | 0 = unchecked
		),
		array(
			'id'       => 'squared_images',
			'type'     => 'checkbox',
			'title'    => __( 'Use squared avatar images', 'sweetdate' ),
			'sub_desc' => __( 'Enable to show square avatars instead of rounded', 'sweetdate' ),
			'switch'   => true,
			'std'      => '0' // 1 = checked | 0 = unchecked
		),

		array(
			'id'       => 'login_redirect',
			'type'     => 'select',
			'title'    => __( 'Login redirect for Popup', 'sweetdate' ),
			'subtitle' => __( 'Select the redirect action taken when members login from the popup window.', 'sweetdate' ),
			'options'  => array(
				'default' => __( 'Default WordPress redirect', 'sweetdate' ),
				'reload'  => __( 'Reload the current page', 'sweetdate' ),
			),
			'default'  => 'default'
		),

		array(
			'id'           => 'facebook_login',
			'type'         => 'checkbox_hide_below',
			'title'        => __( 'Facebook integration', 'sweetdate' ),
			'sub_desc'     => __( 'Enable or disable Login/Register with Facebook', 'sweetdate' ),
			'switch'       => true,
			'std'          => '0', // 1 = checked | 0 = unchecked
			'next_to_hide' => 3
		),
		array(
			'id'       => 'fb_app_id',
			'type'     => 'text',
			'title'    => __( 'Facebook APP ID', 'sweetdate' ),
			'sub_desc' => __( 'In order to integrate with Facebook you need to enter your Facebook APP ID<br/>If you don\'t have one, you can create it from: <a target="_blank" href="https://developers.facebook.com/apps">HERE</a> ', 'sweetdate' ),
			'std'      => ''
		),
		array(
			'id'       => 'facebook_avatar',
			'type'     => 'checkbox',
			'title'    => __( 'Show Facebook avatar', 'sweetdate' ),
			'sub_desc' => __( 'If you enable this, users that registered with Facebook will display Facebook profile image as avatar.', 'sweetdate' ),
			'switch'   => true,
			'std'      => '0' // 1 = checked | 0 = unchecked
		),
		array(
			'id'       => 'facebook_register',
			'type'     => 'checkbox',
			'title'    => __( 'Enable Registration via Facebook', 'sweetdate' ),
			'sub_desc' => __( 'If you enable this, users will be able to register a new account using Facebook. This skips the registration page including required profile fields', 'sweetdate' ),
			'switch'   => true,
			'std'      => '0' // 1 = checked | 0 = unchecked
		),
		array(
			'id'       => 'mailchimp_api',
			'type'     => 'text',
			'title'    => __( 'Mailchimp API KEY', 'sweetdate' ),
			'sub_desc' => __( 'To use mailchimp newsletter subscribe widget you have to enter your API KEY', 'sweetdate' ),
			'std'      => ''
		),
		array(
			'id'       => 'mailchimp_opt_in',
			'type'     => 'select',
			'title'    => __( 'Mailchimp Opt in', 'sweetdate' ),
			'sub_desc' => __( 'If you want users to confirm their email by clicking a link on the email', 'sweetdate' ),
			'options'  => array( 'yes' => 'Yes', 'no' => 'No' ),
			'std'      => 'yes'
		),
		array(
			'id'       => 'terms_page',
			'type'     => 'pages_select',
			'title'    => __( 'Terms and conditions page', 'sweetdate' ),
			'sub_desc' => __( 'Select the page that is used for terms and conditions.<br/>This will be used in register modal', 'sweetdate' ),
			'std'      => '#',
			'args'     => array()
		),
		array(
			'id'       => 'privacy_page',
			'type'     => 'pages_select',
			'title'    => __( 'Privacy page', 'sweetdate' ),
			'sub_desc' => __( 'Select the page that is used for privacy info.<br/>This will be used in login modal', 'sweetdate' ),
			'std'      => '#',
			'args'     => array()
		),

		array(
			'id'       => 'tf_username',
			'type'     => 'text',
			'title'    => __( 'Themeforest Username', 'sweetdate' ),
			'sub_desc' => sprintf( wp_kses_post( __( 'Deprecated. You need to use Envato Market plugin. Read the tutorial <a target="_blank" href="%s">here</a>.', 'sweetdate' ) ), 'https://seventhqueen.com/support/general/article/update-themes-automatically-using-envato-market-plugin' ),
			'std'      => ''
		),
		array(
			'id'       => 'tf_apikey',
			'type'     => 'text',
			'title'    => __( 'Themeforest API KEY', 'sweetdate' ),
			'sub_desc' => sprintf( wp_kses_post( __( 'Deprecated. You need to use Envato Market plugin. Read the tutorial <a target="_blank" href="%s">here</a>.', 'sweetdate' ) ), 'https://seventhqueen.com/support/general/article/update-themes-automatically-using-envato-market-plugin' ),
			'std'      => ''
		),

		array(
			'id'       => 'tdf_consumer_key',
			'type'     => 'text',
			'title'    => __( 'Twitter Consumer Key', 'sweetdate' ),
			'sub_desc' => '',

		),
		array(
			'id'       => 'tdf_consumer_secret',
			'type'     => 'text',
			'title'    => __( 'Twitter Consumer Secret', 'sweetdate' ),
			'sub_desc' => '',

		),
		array(
			'id'       => 'tdf_access_token',
			'type'     => 'text',
			'title'    => __( 'Twitter Access Token', 'sweetdate' ),
			'sub_desc' => ''
		),
		array(
			'id'       => 'tdf_access_token_secret',
			'type'     => 'text',
			'title'    => __( 'Twitter Token Secret', 'sweetdate' ),
			'sub_desc' => ''
		),
		array(
			'id'       => 'tdf_user_timeline',
			'type'     => 'text',
			'title'    => __( 'Twitter username', 'sweetdate' ),
			'sub_desc' => ''
		),
		array(
			'id'       => 'tdf_cache_expire',
			'type'     => 'text',
			'title'    => __( 'Tweets cache time', 'sweetdate' ),
			'sub_desc' => 'Recommended 1 hour = 3600 seconds',
			'std'      => '3600'
		),
        array(
            'id'       => 'footer_copyright',
            'type'     => 'textarea',
            'title'    => esc_html__( 'Footer Copyright text/Html', 'sweetdate' ),
            'sub_desc' => __( 'Add your custom copyright text in footer.<br/> HTML allowed', 'sweetdate' ),
            'desc'     => '',
            'std'      => ''
        )
	)
);

global $sweetdate_sections;

$sweetdate_sections = $sections;
