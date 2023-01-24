<?php

/*-----------------------------------------------------------------------------------*/
/*	Button Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['button'] = array(
	'no_preview'  => true,
	'params'      => array(
		'url'           => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Button URL', 'sweetdate' ),
			'desc'  => esc_html__( 'Add the button\'s url eg http://example.com', 'sweetdate' )
		),
		'style'         => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Button Style', 'sweetdate' ),
			'desc'    => esc_html__( 'Select the button\'s style, ie the button\'s colour', 'sweetdate' ),
			'options' => array(
				'standard'  => 'Primary color',
				'secondary' => 'Secondary color',
				'success'   => 'Green',
				'alert'     => 'Red'
			)
		),
		'size'          => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Button Size', 'sweetdate' ),
			'desc'    => esc_html__( 'Select the button\'s size', 'sweetdate' ),
			'options' => array(
				'standard' => 'Standard',
				'large'    => 'Large',
				'medium'   => 'Medium',
				'small'    => 'Small',
				'tiny'     => 'Tiny'
			)
		),
		'round'         => array(
			'std'     => '0',
			'type'    => 'select',
			'label'   => esc_html__( 'Rounded corners', 'sweetdate' ),
			'desc'    => esc_html__( 'Check if you want the button to have rounded corners', 'sweetdate' ),
			'options' => array(
				'0'      => 'No',
				'radius' => 'A bit Rounded',
				'round'  => 'Rounded'
			)

		),
		'icon'          => array(
			'std'     => '',
			'type'    => 'select',
			'label'   => esc_html__( 'Icon', 'sweetdate' ),
			'desc'    => wp_kses_post( sprintf( __( 'Select an icon to display inside button. View all icons <a target="_blank" href="%s">here</a>', 'sweetdate' ), 'http://fortawesome.github.io/Font-Awesome/cheatsheet/' ) ),
			'options' => awesome_array()
		),
		'icon_position' => array(
			'std'     => 'before',
			'type'    => 'select',
			'label'   => esc_html__( 'Icon position', 'sweetdate' ),
			'desc'    => esc_html__( 'Select icon position', 'sweetdate' ),
			'options' => array(
				'before' => 'Before text',
				'after'  => 'After text'
			)
		),
		'target'        => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Button Target', 'sweetdate' ),
			'desc'    => esc_html__( '_self = open in same window. _blank = open in new window', 'sweetdate' ),
			'options' => array(
				'_self'  => '_self',
				'_blank' => '_blank'
			)
		),
		'content'       => array(
			'std'   => 'Button Text',
			'type'  => 'text',
			'label' => esc_html__( 'Button\'s Text', 'sweetdate' ),
			'desc'  => esc_html__( 'Add the button\'s text', 'sweetdate' ),
		)
	),
	'shortcode'   => '[kleo_button url="{{url}}" style="{{style}}" size="{{size}}" round="{{round}}" icon="{{icon}},{{icon_position}}" target="{{target}}"] {{content}} [/kleo_button]',
	'popup_title' => esc_html__( 'Insert Button Shortcode', 'sweetdate' )
);

/*-----------------------------------------------------------------------------------*/
/*	Alert Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['alert'] = array(
	'no_preview'  => true,
	'params'      => array(
		'style'   => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Alert Style', 'sweetdate' ),
			'desc'    => esc_html__( 'Select the alert\'s style, ie the alert colour', 'sweetdate' ),
			'options' => array(
				'standard'  => 'Standard',
				'success'   => 'Green',
				'alert'     => 'Red',
				'secondary' => 'Secondary',

			)
		),
		'content' => array(
			'std'   => 'Your Alert!',
			'type'  => 'textarea',
			'label' => esc_html__( 'Alert Text', 'sweetdate' ),
			'desc'  => esc_html__( 'Add the alert\'s text', 'sweetdate' ),
		)
	),
	'shortcode'   => '[kleo_alert style="{{style}}"] {{content}} [/kleo_alert]',
	'popup_title' => esc_html__( 'Insert Alert Shortcode', 'sweetdate' )
);

/*-----------------------------------------------------------------------------------*/
/*	Toggle Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['toggle'] = array(
	'no_preview'  => true,
	'params'      => array(
		'title'   => array(
			'type'  => 'text',
			'label' => esc_html__( 'Toggle Content Title', 'sweetdate' ),
			'desc'  => esc_html__( 'Add the title that will go above the toggle content', 'sweetdate' ),
			'std'   => 'Title'
		),
		'content' => array(
			'std'   => 'Content',
			'type'  => 'textarea',
			'label' => esc_html__( 'Toggle Content', 'sweetdate' ),
			'desc'  => esc_html__( 'Add the toggle content. Will accept HTML', 'sweetdate' ),
		),
		'opened'  => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Toggle State', 'sweetdate' ),
			'desc'    => esc_html__( 'Select the state of the toggle on page load', 'sweetdate' ),
			'options' => array(
				'yes' => 'Opened',
				'no'  => 'Closed'
			)
		),

	),
	'shortcode'   => '[kleo_toggle title="{{title}}" opened="{{opened}}"] {{content}} [/kleo_toggle]',
	'popup_title' => esc_html__( 'Insert Toggle Content Shortcode', 'sweetdate' )
);

/*-----------------------------------------------------------------------------------*/
/*	Tabs Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['tabs'] = array(
	'params'          => array(),
	'no_preview'      => true,
	'shortcode'       => '[kleo_tabs centered="{{centered}}"] {{child_shortcode}}  [/kleo_tabs]',
	'popup_title'     => esc_html__( 'Insert Tab Shortcode', 'sweetdate' ),
	'params'          => array(
		'centered' => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Centered tabs', 'sweetdate' ),
			'desc'    => esc_html__( 'Enable centered tabs title', 'sweetdate' ),
			'options' => array(
				''    => 'No',
				'yes' => 'Yes'
			)
		),

	),
	'child_shortcode' => array(
		'params'       => array(
			'title'   => array(
				'std'   => 'Title',
				'type'  => 'text',
				'label' => esc_html__( 'Tab Title', 'sweetdate' ),
				'desc'  => esc_html__( 'Title of the tab', 'sweetdate' ),
			),
			'content' => array(
				'std'   => 'Tab Content',
				'type'  => 'textarea',
				'label' => esc_html__( 'Tab Content', 'sweetdate' ),
				'desc'  => esc_html__( 'Add the tabs content', 'sweetdate' )
			),
			'active'  => array(
				'std'   => 'Active',
				'type'  => 'radio',
				'name'  => 'activetab',
				'label' => esc_html__( 'Active', 'sweetdate' ),
				'desc'  => esc_html__( 'If the tab should be active by default', 'sweetdate' ),
			),


		),
		'shortcode'    => '[kleo_tab title="{{title}}" active={{active}}] {{content}} [/kleo_tab]',
		'clone_button' => esc_html__( 'Add Tab', 'sweetdate' )
	)
);


/*-----------------------------------------------------------------------------------*/
/*	Row Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['row'] = array(

	'popup_title' => esc_html__( 'Insert Row Shortcode', 'sweetdate' ),
	'no_preview'  => true,
	'params'      => array(
		'content' => array(
			'std'   => '',
			'type'  => 'textarea',
			'label' => esc_html__( 'Row Content', 'sweetdate' ),
			'desc'  => esc_html__( 'Add the row content.', 'sweetdate' ),
		)
	),
	'shortcode'   => '[kleo_row] {{content}} [/kleo_row]'
);


/*-----------------------------------------------------------------------------------*/
/*	Columns Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['columns'] = array(
	'params'          => array(),
	'shortcode'       => ' {{child_shortcode}} ', // as there is no wrapper shortcode
	'popup_title'     => esc_html__( 'Insert Columns Shortcode', 'sweetdate' ),
	'no_preview'      => true,

	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params'       => array(
			'column'  => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Column Type', 'sweetdate' ),
				'desc'    => esc_html__( 'Select the type, ie width of the column.', 'sweetdate' ),
				'options' => array(
					'kleo_one_third'    => 'One Third',
					'kleo_two_third'    => 'Two Thirds',
					'kleo_one_fourth'   => 'One Fourth',
					'kleo_three_fourth' => 'Three Fourth',
					'kleo_one_half'     => 'One Half',
					'kleo_one'          => 'One',
				)
			),
			'content' => array(
				'std'   => '',
				'type'  => 'textarea',
				'label' => esc_html__( 'Column Content', 'sweetdate' ),
				'desc'  => esc_html__( 'Add the column content.', 'sweetdate' ),
			)
		),
		'shortcode'    => '[{{column}}] {{content}} [/{{column}}] ',
		'clone_button' => esc_html__( 'Add Column', 'sweetdate' )
	)
);


/*-----------------------------------------------------------------------------------*/
/*	Section
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['section'] = array(
	'no_preview'  => true,
	'params'      => array(

		'bg'       => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Background', 'sweetdate' ),
			'desc'  => '<input rel-id="kleo_bg" type="button" class="sc_upload_image_button" value="Upload"> <br>' .
			           esc_html__( 'Display a background for this section', 'sweetdate' ),

		),
		'centered' => array(
			'std'   => '0',
			'type'  => 'checkbox',
			'label' => esc_html__( 'Centered text', 'sweetdate' ),
			'desc'  => esc_html__( 'Check if you want to have centered text', 'sweetdate' ),

		),
		'border'   => array(
			'std'   => '0',
			'type'  => 'checkbox',
			'label' => esc_html__( 'Show Border', 'sweetdate' ),
			'desc'  => esc_html__( 'Add a bottom border to the section', 'sweetdate' ),

		),

		'content' => array(
			'std'   => '',
			'type'  => 'textarea',
			'label' => esc_html__( 'Content', 'sweetdate' ),
			'desc'  => esc_html__( 'Section content', 'sweetdate' ),
		)
	),
	'shortcode'   => '[kleo_section bg="{{bg}}" centered={{centered}} border={{border}}]{{content}}[/kleo_section]',
	'popup_title' => esc_html__( 'Insert Section Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Panel Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['panel'] = array(
	'no_preview'  => true,
	'params'      => array(

		'style'   => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Panel Style', 'sweetdate' ),
			'desc'    => '',
			'options' => array(
				'standard' => 'Standard',
				'callout'  => 'Callout',
			)
		),
		'round'   => array(
			'std'   => '',
			'type'  => 'checkbox',
			'label' => esc_html__( 'Rounded corners', 'sweetdate' ),
			'desc'  => esc_html__( 'Check if you want the panel to have rounded corners', 'sweetdate' ),

		),
		'content' => array(
			'std'   => 'Panel content',
			'type'  => 'textarea',
			'label' => esc_html__( 'Panel content', 'sweetdate' ),
			'desc'  => esc_html__( 'Add the panel\'s content', 'sweetdate' ),
		)
	),
	'shortcode'   => '[kleo_panel style="{{style}}" round="{{round}}"] {{content}} [/kleo_panel]',
	'popup_title' => esc_html__( 'Insert Panel Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Pricing table Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['pricing_table'] = array(
	'params'      => array(
		'title'       => array(
			'std'   => 'Title',
			'type'  => 'text',
			'label' => esc_html__( 'Title', 'sweetdate' ),
			'desc'  => '',
		),
		'price'       => array(
			'std'   => 'Price',
			'type'  => 'text',
			'label' => esc_html__( 'Price', 'sweetdate' ),
			'desc'  => '',
		),
		'description' => array(
			'std'   => 'Description',
			'type'  => 'textarea',
			'label' => esc_html__( 'Description', 'sweetdate' ),
			'desc'  => ''
		)
	),
	'no_preview'  => true,
	'shortcode'   => '[kleo_pricing_table title="{{title}}" price="{{price}}" description="{{description}}"] {{child_shortcode}}  [/kleo_pricing_table]',
	'popup_title' => esc_html__( 'Insert Pricing table Shortcode', 'sweetdate' ),

	'child_shortcode' => array(
		'params'       => array(

			'content' => array(
				'std'   => 'Item',
				'type'  => 'textarea',
				'label' => esc_html__( 'Item', 'sweetdate' ),
				'desc'  => ''
			)

		),
		'shortcode'    => '[kleo_pricing_item] {{content}} [/kleo_pricing_item]',
		'clone_button' => esc_html__( 'Add Item', 'sweetdate' )
	)
);


/*-----------------------------------------------------------------------------------*/
/*	Progress bar
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['progress_bar'] = array(
	'no_preview'  => true,
	'params'      => array(
		'style' => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Bar Style', 'sweetdate' ),
			'desc'    => esc_html__( 'Select the bar\'s colour', 'sweetdate' ),
			'options' => array(
				'standard'  => 'Primary color',
				'secondary' => 'Secondary color',
				'success'   => 'Green',
				'alert'     => 'Red'
			)
		),
		'round' => array(
			'std'   => '',
			'type'  => 'checkbox',
			'label' => esc_html__( 'Rounded corners', 'sweetdate' ),
			'desc'  => esc_html__( 'Check if you want the button to have rounded corners', 'sweetdate' ),

		),

		'width' => array(
			'std'   => '50',
			'type'  => 'text',
			'label' => esc_html__( 'Completed percentage', 'sweetdate' ),
			'desc'  => esc_html__( 'Set the completed percentage for the progress bar', 'sweetdate' ),
		)
	),
	'shortcode'   => '[kleo_progress_bar style="{{style}}" round="{{round}}" width="{{width}}"]',
	'popup_title' => esc_html__( 'Insert Progress bar Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Accordion Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['accordion'] = array(
	'params'      => array(
		'opened' => array(
			'std'   => '1',
			'type'  => 'text',
			'label' => esc_html__( 'Default opened', 'sweetdate' ),
			'desc'  => esc_html__( 'Enter the accordion number you want to be opened by default. Enter none if you want to have all closed.', 'sweetdate' ),
		)
	),
	'no_preview'  => true,
	'shortcode'   => '[kleo_accordion opened="{{opened}}"] {{child_shortcode}}  [/kleo_accordion]',
	'popup_title' => esc_html__( 'Insert Accordion Shortcode', 'sweetdate' ),

	'child_shortcode' => array(
		'params'       => array(
			'title'   => array(
				'std'   => 'Title',
				'type'  => 'text',
				'label' => esc_html__( 'Accordion Item Title', 'sweetdate' ),
				'desc'  => esc_html__( 'Title of the Accordion Item', 'sweetdate' ),
			),
			'content' => array(
				'std'   => 'Accordion Item Content',
				'type'  => 'textarea',
				'label' => esc_html__( 'Accordion Item Content', 'sweetdate' ),
				'desc'  => esc_html__( 'Add the accordion\'s item content', 'sweetdate' )
			)
		),
		'shortcode'    => '[kleo_accordion_item title="{{title}}"] {{content}} [/kleo_accordion_item]',
		'clone_button' => esc_html__( 'Add Accordion Item', 'sweetdate' )
	)
);


/*-----------------------------------------------------------------------------------*/
/*      Colored text Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['colored_text'] = array(
	'no_preview'  => true,
	'params'      => array(
		'color'   => array(
			'std'   => '#F00056',
			'type'  => 'text',
			'label' => esc_html__( 'Text Color', 'sweetdate' ),
			'desc'  => esc_html__( 'Select the text\'s color', 'sweetdate' ),

		),
		'content' => array(
			'std'   => 'Your Text!',
			'type'  => 'textarea',
			'label' => esc_html__( 'Your text', 'sweetdate' ),
			'desc'  => ''
		)


	),
	'shortcode'   => '[kleo_colored_text color="{{color}}"] {{content}} [/kleo_colored_text]',
	'popup_title' => esc_html__( 'Insert Colored Text Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Home - Call to action Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['call_to_action'] = array(
	'no_preview'  => true,
	'params'      => array(
		'bg'      => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Background', 'sweetdate' ),
			'desc'  => '<input rel-id="kleo_bg" type="button" class="sc_upload_image_button" value="Upload"> <br>' .
			           esc_html__( 'Change the default background', 'sweetdate' ),

		),
		'content' => array(
			'std'   => '',
			'type'  => 'textarea',
			'label' => esc_html__( 'Content', 'sweetdate' ),
			'desc'  => ''
		)


	),
	'shortcode'   => '[kleo_call_to_action bg="{{bg}}"] {{content}} [/kleo_call_to_action]',
	'popup_title' => esc_html__( 'Insert Call to Action Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Lead Paragraph
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['lead_paragraph'] = array(
	'no_preview'  => true,
	'params'      => array(
		'content' => array(
			'std'   => 'Your Text!',
			'type'  => 'textarea',
			'label' => esc_html__( 'Your text', 'sweetdate' ),
			'desc'  => ''
		)
	),
	'shortcode'   => '[kleo_lead_paragraph] {{content}} [/kleo_lead_paragraph]',
	'popup_title' => esc_html__( 'Insert Lead Paragraph Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Headings
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['h1'] = array(
	'no_preview'  => true,
	'params'      => array(
		'content' => array(
			'std'   => 'Your Text!',
			'type'  => 'text',
			'label' => esc_html__( 'Your text', 'sweetdate' ),
			'desc'  => ''
		)
	),
	'shortcode'   => '[kleo_h1] {{content}} [/kleo_h1]',
	'popup_title' => esc_html__( 'Insert H1 Shortcode', 'sweetdate' )
);
$kleo_shortcodes['h2'] = array(
	'no_preview'  => true,
	'params'      => array(
		'content' => array(
			'std'   => 'Your Text!',
			'type'  => 'text',
			'label' => esc_html__( 'Your text', 'sweetdate' ),
			'desc'  => ''
		)
	),
	'shortcode'   => '[kleo_h2] {{content}} [/kleo_h2]',
	'popup_title' => esc_html__( 'Insert H2 Shortcode', 'sweetdate' )
);
$kleo_shortcodes['h3'] = array(
	'no_preview'  => true,
	'params'      => array(
		'content' => array(
			'std'   => 'Your Text!',
			'type'  => 'text',
			'label' => esc_html__( 'Your text', 'sweetdate' ),
			'desc'  => ''
		)
	),
	'shortcode'   => '[kleo_h3] {{content}} [/kleo_h3]',
	'popup_title' => esc_html__( 'Insert H3 Shortcode', 'sweetdate' )
);
$kleo_shortcodes['h4'] = array(
	'no_preview'  => true,
	'params'      => array(
		'content' => array(
			'std'   => 'Your Text!',
			'type'  => 'text',
			'label' => esc_html__( 'Your text', 'sweetdate' ),
			'desc'  => ''
		)
	),
	'shortcode'   => '[kleo_h4] {{content}} [/kleo_h4]',
	'popup_title' => esc_html__( 'Insert H4 Shortcode', 'sweetdate' )
);
$kleo_shortcodes['h5'] = array(
	'no_preview'  => true,
	'params'      => array(
		'content' => array(
			'std'   => 'Your Text!',
			'type'  => 'text',
			'label' => esc_html__( 'Your text', 'sweetdate' ),
			'desc'  => ''
		)
	),
	'shortcode'   => '[kleo_h5] {{content}} [/kleo_h5]',
	'popup_title' => esc_html__( 'Insert H5 Shortcode', 'sweetdate' )
);
$kleo_shortcodes['h6'] = array(
	'no_preview'  => true,
	'params'      => array(
		'content' => array(
			'std'   => 'Your Text!',
			'type'  => 'text',
			'label' => esc_html__( 'Your text', 'sweetdate' ),
			'desc'  => ''
		)
	),
	'shortcode'   => '[kleo_h6] {{content}} [/kleo_h6]',
	'popup_title' => esc_html__( 'Insert H6 Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Video Button Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['button_video'] = array(
	'no_preview'  => true,
	'params'      => array(
		'url'           => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Button URL', 'sweetdate' ),
			'desc'  => esc_html__( 'Add video\'s embed url that will open in popup eg http://www.youtube.com/embed/FtquI061bag', 'sweetdate' )
		),
		'style'         => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Button Style', 'sweetdate' ),
			'desc'    => esc_html__( 'Select the button\'s colour', 'sweetdate' ),
			'options' => array(
				'standard'  => 'Primary color',
				'secondary' => 'Secondary color',
				'success'   => 'Green',
				'alert'     => 'Red'
			)
		),
		'size'          => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Button Size', 'sweetdate' ),
			'desc'    => esc_html__( 'Select the button\'s size', 'sweetdate' ),
			'options' => array(
				'standard' => 'Standard',
				'large'    => 'Large',
				'medium'   => 'Medium',
				'small'    => 'Small',
				'tiny'     => 'Tiny'
			)
		),
		'round'         => array(
			'std'     => '0',
			'type'    => 'select',
			'label'   => esc_html__( 'Rounded corners', 'sweetdate' ),
			'desc'    => esc_html__( 'Check if you want the button to have rounded corners', 'sweetdate' ),
			'options' => array(
				'0'      => 'No',
				'radius' => 'A bit Rounded',
				'round'  => 'Rounded'
			)

		),
		'icon'          => array(
			'std'     => '',
			'type'    => 'select',
			'label'   => esc_html__( 'Icon', 'sweetdate' ),
			'desc'    => wp_kses_post( sprintf( __( 'Select an icon to display inside button. View all icons <a target="_blank" href="%s">here</a>', 'sweetdate' ), 'http://fortawesome.github.io/Font-Awesome/cheatsheet/' ) ),
			'options' => awesome_array()
		),
		'icon_position' => array(
			'std'     => 'before',
			'type'    => 'select',
			'label'   => esc_html__( 'Icon position', 'sweetdate' ),
			'desc'    => esc_html__( 'Select icon position', 'sweetdate' ),
			'options' => array(
				'before' => 'Before text',
				'after'  => 'After text'
			)
		),

		'content' => array(
			'std'   => 'Button Text',
			'type'  => 'text',
			'label' => esc_html__( 'Button\'s Text', 'sweetdate' ),
			'desc'  => esc_html__( 'Add the button\'s text', 'sweetdate' ),
		)
	),
	'shortcode'   => '[kleo_button_video url="{{url}}" style="{{style}}" size="{{size}}" round="{{round}}" icon="{{icon}},{{icon_position}}"] {{content}} [/kleo_button_video]',
	'popup_title' => esc_html__( 'Insert Video Button Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Status icons Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['status_icon'] = array(
	'no_preview'  => true,
	'popup_title' => esc_html__( 'Insert Status Icons Shortcode', 'sweetdate' ),
	'params'    => array(
		'type'     => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Type', 'sweetdate' ),
			'desc'    => '',
			'options' => array(
				'total'          => 'Total members',
				'members_online' => 'Members online',
				'custom'         => 'Custom'
			)
		),
		'image'    => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Image', 'sweetdate' ),
			'desc'  => '<input type="button" class="sc_upload_image_button" value="Upload">',
		),
		'field'    => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Field name', 'sweetdate' ),
			'desc'  => esc_html__( 'Enter the field name to get members by. Works only when Type is set to custom', 'sweetdate' ),
		),
		'value'    => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Field value', 'sweetdate' ),
			'desc'  => esc_html__( 'Enter the field value to get members by. Works only when Type is set to custom', 'sweetdate' ),
		),
		'online'   => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Online', 'sweetdate' ),
			'desc'    => esc_html__( 'Get only online members or not. Works only when Type is set to custom', 'sweetdate' ),
			'options' => array(
				'yes' => 'Yes',
				'no'  => 'No'
			)
		),
		'subtitle' => array(
			'std'   => 'Subtitle Text',
			'type'  => 'text',
			'label' => esc_html__( 'Subtitle Text', 'sweetdate' ),
			'desc'  => '',
		)
	),
	'shortcode' => '[kleo_status_icon type="{{type}}" image="{{image}}" subtitle="{{subtitle}}" field="{{field}}" value="{{value}}" online="{{online}}"]'


);

/*-----------------------------------------------------------------------------------*/
/*	Members online
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['members_online'] = array(
	'no_preview'  => true,
	'params'      => array(
		'field_name' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Field name', 'sweetdate' ),
			'desc'  => 'Field name to filter members by.',
		),
		'field'      => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Field Value', 'sweetdate' ),
			'desc'  => 'The value of the above fields to get the members after.',
		),
	),
	'shortcode'   => '[kleo_members_online field_name="{{field_name}} field="{{field}}"]',
	'popup_title' => esc_html__( 'Insert Members online Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Image rounded Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['img_rounded'] = array(
	'no_preview'  => true,
	'params'      => array(
		'src' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Image', 'sweetdate' ),
			'desc'  => '<input type="button" class="sc_upload_image_button" value="Upload">',
		),
	),
	'shortcode'   => '[kleo_img_rounded src="{{src}}"]',
	'popup_title' => esc_html__( 'Insert Rounded Image Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Blog Carousel
/*-----------------------------------------------------------------------------------*/
$bc_cats        = array();
$bc_cats['all'] = 'All';
foreach ( get_categories() as $bcat ) {
	$bc_cats[ $bcat->term_id ] = $bcat->name;
}

$kleo_shortcodes['posts_carousel'] = array(
	'no_preview'  => true,
	'params'      => array(
		'cat'   => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Category', 'sweetdate' ),
			'desc'    => esc_html__( 'Show posts only from the selected category', 'sweetdate' ),
			'options' => $bc_cats
		),
		'limit' => array(
			'type'  => 'text',
			'label' => esc_html__( 'Limit', 'sweetdate' ),
			'desc'  => esc_html__( 'Limit the posts in carousel', 'sweetdate' ),
		)

	),
	'shortcode'   => '[kleo_posts_carousel cat="{{cat}}" limit="{{limit}}"]',
	'popup_title' => esc_html__( 'Insert Posts Carousel Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Blog Articles
/*-----------------------------------------------------------------------------------*/
$bc_cats        = array();
$bc_cats['all'] = 'All';
foreach ( get_categories() as $bcat ) {
	$bc_cats[ $bcat->term_id ] = $bcat->name;
}

$kleo_shortcodes['articles'] = array(
	'no_preview'  => true,
	'params'      => array(
		'display'      => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Display type', 'sweetdate' ),
			'desc'    => esc_html__( 'Display in a normal or grid view', 'sweetdate' ),
			'options' => array(
				''     => 'Normal',
				'grid' => 'Grid'
			),
			'std'     => ''
		),
		'columns'      => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Number of columns', 'sweetdate' ),
			'desc'    => esc_html__( 'Applies only for Grid Display style', 'sweetdate' ),
			'options' => array(
				'four'  => 'Four',
				'three' => 'Three',
				'two'   => 'Two'
			),
			'std'     => 'four'
		),
		'show_meta'    => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Show post meta', 'sweetdate' ),
			'desc'    => '',
			'options' => array(
				'disable' => 'Disable',
				'enable'  => 'Enable'
			),
			'std'     => 'disable'
		),
		'cat'          => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Category', 'sweetdate' ),
			'desc'    => esc_html__( 'Show posts only from the selected category', 'sweetdate' ),
			'options' => $bc_cats,
			'std'     => ''
		),
		'post_types'   => array(
			'type'  => 'text',
			'label' => esc_html__( 'Post types', 'sweetdate' ),
			'desc'  => esc_html__( 'Show only certain post types', 'sweetdate' ),
			'std'   => 'post'
		),
		'post_formats' => array(
			'type'  => 'text',
			'label' => esc_html__( 'Post Formats', 'sweetdate' ),
			'desc'  => esc_html__( 'Show only certain post formats', 'sweetdate' ),
			'std'   => 'all'
		),
		'limit'        => array(
			'type'  => 'text',
			'label' => esc_html__( 'Limit', 'sweetdate' ),
			'desc'  => esc_html__( 'Limit the posts number', 'sweetdate' ),
			'std'   => ''
		)

	),
	'shortcode'   => '[kleo_articles display="{{display}}" columns="{{columns}}" cat="{{cat}}" limit="{{limit}}" post_types="{{post_types}}" post_formats="{{post_formats}}" show_meta="{{show_meta}}"]',
	'popup_title' => esc_html__( 'Insert Articles Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/*	Slider Config
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['slider'] = array(
	'params'      => array(),
	'no_preview'  => true,
	'shortcode'   => '[kleo_slider] {{child_shortcode}}  [/kleo_slider]',
	'popup_title' => esc_html__( 'Insert Slider Shortcode', 'sweetdate' ),

	'child_shortcode' => array(
		'params'       => array(
			'src' => array(
				'std'   => '',
				'type'  => 'text',
				'label' => esc_html__( 'Image', 'sweetdate' ),
				'desc'  => '<input type="button" class="sc_upload_image_button" value="Upload"> <br>' .
				           esc_html__( 'Select your image', 'sweetdate' ),
			),

		),
		'shortcode'    => '[kleo_slider_image src="{{src}}"]',
		'clone_button' => esc_html__( 'Add Slider Image', 'sweetdate' )
	)
);

/*-----------------------------------------------------------------------------------*/
/* Icon Config
/*-----------------------------------------------------------------------------------*/


$kleo_shortcodes['icon'] = array(
	'no_preview'  => true,
	'params'      => array(
		'icon' => array(
			'std'     => '',
			'type'    => 'select',
			'label'   => esc_html__( 'Icon', 'sweetdate' ),
			'desc'    => wp_kses_post( sprintf( __( 'Select an icon to display. View all icons <a target="_blank" href="%s">here</a>', 'sweetdate' ), 'http://fortawesome.github.io/Font-Awesome/cheatsheet/' ) ),
			'options' => awesome_array()
		),
		'size' => array(
			'std'     => '',
			'type'    => 'select',
			'label'   => esc_html__( 'Size', 'sweetdate' ),
			'desc'    => esc_html__( 'Select the icon size', 'sweetdate' ),
			'options' => array(
				'normal' => 'Normal',
				'large'  => 'Large',
				'2x'     => '2x',
				'3x'     => '3x',
				'4x'     => '4x'
			)
		),
	),
	'shortcode'   => '[kleo_icon icon="{{icon}}" size={{size}}]',
	'popup_title' => esc_html__( 'Insert Icon Shortcode', 'sweetdate' )
);


/*-----------------------------------------------------------------------------------*/
/* Search members
/*-----------------------------------------------------------------------------------*/


$kleo_shortcodes['search_members'] = array(
	'no_preview'  => false,
	'params'      => array(
		'before'   => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Before text', 'sweetdate' ),
			'desc'  => esc_html__( 'Text that appears before search form', 'sweetdate' )
		),
		'profiles' => array(
			'std'   => '1',
			'type'  => 'checkbox',
			'label' => esc_html__( 'Show profiles', 'sweetdate' ),
			'desc'  => esc_html__( 'Check if you want to show profiles carousel in form footer', 'sweetdate' ),

		),

	),
	'shortcode'   => '[kleo_search_members before="{{before}}" profiles={{profiles}}]',
	'popup_title' => esc_html__( 'Insert Search Form Shortcode', 'sweetdate' )
);

/*-----------------------------------------------------------------------------------*/
/* Search members horizontal
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['search_members_horizontal'] = array(
	'no_preview'  => false,
	'params'      => array(
		'before' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => esc_html__( 'Before text', 'sweetdate' ),
			'desc'  => esc_html__( 'Text that appears before search form', 'sweetdate' )
		),
		'button' => array(
			'std'   => '1',
			'type'  => 'checkbox',
			'label' => esc_html__( 'Show search button', 'sweetdate' ),
			'desc'  => esc_html__( 'Check if you want to show a search button. Otherwise it will automatically refresh on form change', 'sweetdate' ),

		),

	),
	'shortcode'   => '[kleo_search_members_horizontal before="{{before}}" button={{button}}]',
	'popup_title' => esc_html__( 'Insert Vertical Search Form Shortcode', 'sweetdate' )
);

/*-----------------------------------------------------------------------------------*/
/* Register form
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['register_form'] = array(
	'no_preview'  => false,
	'params'      => array(
		'profiles' => array(
			'std'     => '1',
			'type'    => 'select',
			'label'   => esc_html__( 'Show Profiles', 'sweetdate' ),
			'desc'    => esc_html__( 'Display Latest Profiles Carousel below the form', 'sweetdate' ),
			'options' => array(
				'1' => 'Yes',
				'0' => 'No'
			)
		),
		'title'    => array(
			'std'   => esc_html__( "Create an Account", 'sweetdate' ),
			'type'  => 'text',
			'label' => esc_html__( 'Title', 'sweetdate' ),
			'desc'  => esc_html__( 'Title that appears in the form', 'sweetdate' )
		),
		'details'  => array(
			'std'   => esc_html__( "Registering for this site is easy, just fill in the fields below and we will get a new account set up for you in no time.", 'sweetdate' ),
			'type'  => 'text',
			'label' => esc_html__( 'Details', 'sweetdate' ),
			'desc'  => esc_html__( 'Details to show below the title', 'sweetdate' )
		)
	),
	'shortcode'   => '[kleo_register_form profiles={{profiles}} title="{{title}}" details="{{details}}"]',
	'popup_title' => esc_html__( 'Insert Register form Shortcode', 'sweetdate' )
);

/*-----------------------------------------------------------------------------------*/
/* Members carousel
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['members_carousel'] = array(
	'no_preview'  => false,
	'params'      => array(
		'type'  => array(
			'std'     => 'newest',
			'type'    => 'select',
			'label'   => esc_html__( 'Type', 'sweetdate' ),
			'desc'    => esc_html__( 'What members to show', 'sweetdate' ),
			'options' => array(
				'newest'  => 'Newests',
				'active'  => 'Most Active',
				'popular' => 'Most Popular'
			)
		),
		'total' => array(
			'std'   => '12',
			'type'  => 'text',
			'label' => esc_html__( 'Total', 'sweetdate' ),
			'desc'  => esc_html__( 'The number of members to get', 'sweetdate' )
		),
		'width' => array(
			'std'   => '94',
			'type'  => 'text',
			'label' => esc_html__( 'Image width', 'sweetdate' ),
			'desc'  => esc_html__( 'Member avatar size', 'sweetdate' )
		),

	),
	'shortcode'   => '[kleo_members_carousel type="{{type}}" total={{total}} width={{width}}]',
	'popup_title' => esc_html__( 'Insert Members Carousel Shortcode', 'sweetdate' )
);

/*-----------------------------------------------------------------------------------*/
/* bbPress statistics
/*-----------------------------------------------------------------------------------*/

$kleo_shortcodes['bbpress_stats'] = array(
	'no_preview'  => false,
	'params'      => array(
		'type' => array(
			'std'     => 'forums',
			'type'    => 'select',
			'label'   => esc_html__( 'Type', 'sweetdate' ),
			'desc'    => esc_html__( 'What statistic to show', 'sweetdate' ),
			'options' => array(
				'forums'  => 'Total Forums',
				'topics'  => 'Total Topics',
				'replies' => 'Total Replies'
			)
		),

	),
	'shortcode'   => '[kleo_bbpress_stats type="{{type}}"]',
	'popup_title' => esc_html__( 'Insert bbPress Stats Shortcode', 'sweetdate' )
);
?>