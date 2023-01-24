<?php
/*
 * Based on Redux Framework
 * 
 */

/***************************************************
 * :: Theme options link in Admin bar
 ***************************************************/

add_action( 'admin_bar_menu', 'sweet_add_adminbar_options', 100 );

function sweet_add_adminbar_options( $admin_bar ) {
	if ( is_super_admin() ) {
		$admin_bar->add_menu( array(
			'id'    => 'theme-options',
			'title' => esc_html__( 'Theme options', 'sweetdate' ),
			'href'  => get_admin_url() . 'admin.php?page=sweetdate_options',
			'meta'  => array(
				'title'  => esc_html__( 'Theme options', 'sweetdate' ),
				'target' => '_blank'
			),
		) );
	}

}

if ( ! class_exists( 'Kleo_Options' ) ) {

	// Windows-proof constants: replace backward by forward slashes - thanks to: https://github.com/peterbouwmeester
	$fslashed_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
	$fslashed_abs = trailingslashit( str_replace( '\\', '/', ABSPATH ) );

	if ( ! defined( 'Kleo_OPTIONS_DIR' ) ) {
		define( 'Kleo_OPTIONS_DIR', $fslashed_dir );
	}

	if ( ! defined( 'SQUEEN_OPTIONS_URL' ) ) {
		define( 'SQUEEN_OPTIONS_URL', SWEETCORE_URL . 'core/options/' );
	}

	class Kleo_Options {

		protected $framework_url = 'http://www.seventhqueen.com/';
		protected $framework_version = '1.0.0';

		public $dir = Kleo_OPTIONS_DIR;
		public $url = SQUEEN_OPTIONS_URL;
		public $page = '';
		public $args = array();
		public $sections = array();
		public $extra_tabs = array();
		public $errors = array();
		public $warnings = array();
		public $options = array();
		public $options_defaults = null;

		/**
		 * Class Constructor. Defines the args for the theme options class
		 *
		 * @since Kleo_Options 1.0.0
		 *
		 * @param $array $args Arguments. Class constructor arguments.
		 */
		function __construct( $sections = array(), $args = array(), $extra_tabs = array() ) {

			$defaults = array();

			$defaults['opt_name']           = ''; // Must be defined by theme/plugin
			$defaults['google_api_key']     = ''; // Must be defined for use with Google Webfonts field type
			$defaults['last_tab']           = '0';
			$defaults['menu_icon']          = SQUEEN_OPTIONS_URL . 'img/menu_icon.png';
			$defaults['menu_title']         = esc_html__( 'Options', 'sweetdate' );
			$defaults['page_icon']          = 'icon-themes';
			$defaults['page_title']         = esc_html__( 'Options', 'sweetdate' );
			$defaults['page_slug']          = '_options';
			$defaults['page_cap']           = 'manage_options';
			$defaults['page_type']          = 'menu';
			$defaults['page_parent']        = 'themes.php';
			$defaults['page_position']      = null;
			$defaults['icon_type']          = 'iconfont';
			$defaults['allow_sub_menu']     = true;
			$defaults['show_import_export'] = true;
			$defaults['dev_mode']           = true;
			$defaults['admin_stylesheet']   = 'standard';
			$defaults['footer_credit']      = '';
			$defaults['help_tabs']          = array();
			$defaults['help_sidebar']       = '';

			// The defaults are set so it will preserve the old behavior.
			$defaults['std_show'] = true; // If true, it shows the std value
			$defaults['std_mark'] = ''; // What to print by the field's title if the value shown is std

			// Get args
			$this->args = wp_parse_args( $args, $defaults );
			$this->args = apply_filters( 'squeen-opts-args-' . $this->args['opt_name'], $this->args );

			// Get sections
			$this->sections = apply_filters( 'squeen-opts-sections-' . $this->args['opt_name'], $sections );

			// Get extra tabs
			$this->extra_tabs = apply_filters( 'squeen-opts-extra-tabs-' . $this->args['opt_name'], $extra_tabs );

			// Set option with defaults
			add_action( 'init', array( $this, '_set_default_options' ) );

			// Options page
			add_action( 'admin_menu', array( $this, '_options_page' ) );

			// Register setting
			add_action( 'admin_init', array( $this, '_register_setting' ) );

			// Add the js for error handling before the form
			add_action( 'squeen-opts-page-before-form-' . $this->args['opt_name'], array( $this, '_errors_js' ), 1 );

			// Add the js for warning handling before the form
			add_action( 'squeen-opts-page-before-form-' . $this->args['opt_name'], array( $this, '_warnings_js' ), 2 );

			// Hook into the WP feeds for downloading exported settings
			add_action( 'do_feed_kleoopts-' . $this->args['opt_name'], array( $this, '_download_options' ), 1, 1 );

			// Get the options for use later on
			$this->options = get_option( $this->args['opt_name'] );
		}

		/**
		 * ->_get_std(); This is used to return the std value if std_show is set
		 *
		 * @since Kleo_Options 1.0.1
		 *
		 * @param string $opt_name : option name to return
		 * @param mixed $default (null): value to return if std not set
		 */
		function _get_std( $opt_name, $default = null ) {
			if ( $this->args['std_show'] == true ) {
				if ( is_null( $this->options_defaults ) ) {
					$this->_default_values();
				} // fill cache
				$default = array_key_exists( $opt_name, $this->options_defaults ) ? $this->options_defaults[ $opt_name ] : $default;
			}

			return $default;
		}

		/**
		 * ->get(); This is used to return an option value from the options array
		 *
		 * @since Kleo_Options 1.0.0
		 *
		 * @param string $opt_name : option name to return
		 * @param mixed $default (null): value to return if option not set
		 */
		function get( $opt_name, $default = null ) {
			return ( isset( $this->options[ $opt_name ] ) ) ? $this->options[ $opt_name ] : $this->_get_std( $opt_name, $default );
		}

		/**
		 * ->set(); This is used to set an arbitrary option in the options array
		 *
		 * @since Kleo_Options 1.0.0
		 *
		 * @param string $opt_name the name of the option being added
		 * @param mixed $value the value of the option being added
		 */
		function set( $opt_name = '', $value = '' ) {
			if ( $opt_name != '' ) {
				$this->options[ $opt_name ] = $value;
				update_option( $this->args['opt_name'], $this->options );
			}
		}

		/**
		 * ->show(); This is used to echo and option value from the options array
		 *
		 * @since Kleo_Options 1.0.0
		 *
		 * @param $array $args Arguments. Class constructor arguments.
		 */
		function show( $opt_name, $default = '' ) {
			$option = $this->get( $opt_name );
			if ( ! is_array( $option ) && $option != '' ) {
				echo $option;
			} elseif ( $default != '' ) {
				echo $this->_get_std( $opt_name, $default );
			}
		}

		/**
		 * Get default options into an array suitable for the settings API
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _default_values() {
			if ( ! is_null( $this->sections ) && is_null( $this->options_defaults ) ) {
				// fill the cache
				foreach ( $this->sections as $section ) {
					if ( isset( $section['fields'] ) ) {
						foreach ( $section['fields'] as $field ) {
							if ( isset( $field['std'] ) ) {
								$this->options_defaults[ $field['id'] ] = $field['std'];
							}
						}
					}
				}
			}

			return $this->options_defaults;
		}


		/**
		 * Set default options on admin_init if option doesn't exist
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _set_default_options() {
			if ( ! get_option( $this->args['opt_name'] ) ) {
				add_option( $this->args['opt_name'], $this->_default_values() );
			}
			$this->options = get_option( $this->args['opt_name'] );
		}

		/**
		 * Class Options Page Function, creates main options page.
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _options_page() {
			if ( $this->args['page_type'] == 'submenu' ) {
				$this->page = add_submenu_page(
					$this->args['page_parent'],
					$this->args['page_title'],
					$this->args['menu_title'],
					$this->args['page_cap'],
					$this->args['page_slug'],
					array( $this, '_options_page_html' )
				);
			} else {
				$this->page = add_menu_page(
					$this->args['page_title'],
					$this->args['menu_title'],
					$this->args['page_cap'],
					$this->args['page_slug'],
					array( $this, '_options_page_html' ),
					$this->args['menu_icon'],
					$this->args['page_position']
				);

				if ( true === $this->args['allow_sub_menu'] ) {

					// This is needed to prevent the top level menu item from showing in the submenu
					add_submenu_page( $this->args['page_slug'], $this->args['page_title'], '', $this->args['page_cap'],
						$this->args['page_slug'],
						function ( $a ) {
							return null;
						}
					);

					foreach ( $this->sections as $k => $section ) {
						add_submenu_page(
							$this->args['page_slug'],
							$section['title'],
							$section['title'],
							$this->args['page_cap'],
							$this->args['page_slug'] . '&tab=' . $k, function ( $a ) {
							return null;
						}
						);
					}

					if ( true === $this->args['show_import_export'] ) {
						add_submenu_page(
							$this->args['page_slug'],
							esc_html__( 'Import / Export', 'sweetdate' ),
							esc_html__( 'Import / Export', 'sweetdate' ),
							$this->args['page_cap'],
							$this->args['page_slug'] . '&tab=import_export_default', function ( $a ) {
							return null;
						}
						);
					}

					foreach ( $this->extra_tabs as $k => $tab ) {
						add_submenu_page(
							$this->args['page_slug'],
							$tab['title'],
							$tab['title'],
							$this->args['page_cap'],
							$this->args['page_slug'] . '&tab=' . $k, function ( $a ) {
							return null;
						}
						);
					}

					if ( true === $this->args['dev_mode'] ) {
						add_submenu_page(
							$this->args['page_slug'],
							esc_html__( 'Dev Mode Info', 'sweetdate' ),
							esc_html__( 'Dev Mode Info', 'sweetdate' ),
							$this->args['page_cap'],
							$this->args['page_slug'] . '&tab=dev_mode_default', function ( $a ) {
							return null;
						}
						);
					}
				}
			}

			add_action( 'admin_print_styles-' . $this->page, array( $this, '_enqueue' ) );
			add_action( 'load-' . $this->page, array( $this, '_load_page' ) );
		}

		/**
		 * enqueue styles/js for options page
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _enqueue() {
			global $wp_styles;

			wp_register_style(
				'squeen-opts-css',
				$this->url . 'css/options.css',
				array( 'farbtastic' ),
				time(),
				'all'
			);

			wp_register_style(
				'squeen-opts-custom-css',
				$this->url . 'css/custom.css',
				array( 'farbtastic' ),
				time(),
				'all'
			);

			wp_register_style(
				'squeen-lte-ie8',
				$this->url . 'css/lteie8.css',
				array( 'farbtastic' ),
				time(),
				'all'
			);

			$wp_styles->add_data( 'squeen-lte-ie8', 'conditional', 'lte IE 8' );

			wp_register_style(
				'squeen-font-awesome',
				$this->url . 'css/font-awesome.min.css',
				array(),
				time(),
				'all'
			);

			wp_register_style(
				'squeen-font-awesome-ie7',
				$this->url . 'css/font-awesome-ie7.min.css',
				array(),
				time(),
				'all'
			);

			$wp_styles->add_data( 'squeen-font-awesome-ie7', 'conditional', 'lte IE 7' );

			wp_register_style(
				'squeen-opts-jquery-ui-css',
				apply_filters( 'squeen-opts-ui-theme', $this->url . 'css/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css' ),
				'',
				time(),
				'all'
			);

			wp_enqueue_style( 'squeen-lte-ie8' );

			if ( $this->args['admin_stylesheet'] == 'standard' ) {
				wp_enqueue_style( 'squeen-opts-css' );
			} elseif ( $this->args['admin_stylesheet'] == 'custom' ) {
				wp_enqueue_style( 'squeen-opts-custom-css' );
			}

			wp_enqueue_style( 'squeen-font-awesome' );
			wp_enqueue_style( 'squeen-font-awesome-ie7' );

			wp_enqueue_script(
				'squeen-opts-js',
				$this->url . 'js/options.js',
				array( 'jquery' ),
				time(),
				true
			);

			wp_localize_script( 'squeen-opts-js', 'kleo_opts', array(
				'reset_confirm' => esc_html__( 'Are you sure? You will lose all custom values.', 'sweetdate' ),
				'opt_name'      => $this->args['opt_name']
			) );

			do_action( 'squeen-opts-enqueue-' . $this->args['opt_name'] );
			/*
			foreach($this->sections as $k => $section) {
				if(isset($section['fields'])) {
					foreach($section['fields'] as $fieldk => $field) {
						if(isset($field['type'])) {
							$field_class = 'Kleo_Options_' . $field['type'];
							if(!class_exists($field_class)) {
								$class_file = apply_filters('squeen-opts-typeclass-load', $this->dir . 'fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field_class);
								if($class_file)
									require_once($class_file);
							}

							if(class_exists($field_class) && method_exists($field_class, 'enqueue')) {
								$enqueue = new $field_class('','',$this);
								$enqueue->enqueue();
							}
						}
					}
				}
			}
			 *
			 *
			 */
			$myfields = array(
				'bg',
				'button_set',
				'color',
				'upload',
				'typography',
				'checkbox',
				'checkbox_hide_below',
				'radio_img',
				'radio_img_hide_bellow',
				'select_hide_below'
			);
			foreach ( $myfields as $fieldk => $field ) {
				$field_class = 'Kleo_Options_' . $field;
				if ( ! class_exists( $field_class ) ) {
					$class_file = $this->dir . 'fields/' . $field . '/field_' . $field . '.php';
					if ( $class_file ) {
						require_once( $class_file );
					}
				}
				if ( class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) {
					$enqueue = new $field_class( '', '', $this );
					$enqueue->enqueue();
				}
			}


		}

		/**
		 * Download the options file, or display it
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _download_options() {
			//-'.$this->args['opt_name']
			if ( ! isset( $_GET['secret'] ) || $_GET['secret'] != md5( AUTH_KEY . SECURE_AUTH_KEY ) ) {
				wp_die( 'Invalid Secret for options use' );
				exit;
			}

			if ( ! isset( $_GET['feed'] ) ) {
				wp_die( 'No Feed Defined' );
				exit;
			}

			$backup_options                       = get_option( str_replace( 'kleoopts-', '', $_GET['feed'] ) );
			$backup_options['squeen-opts-backup'] = '1';
			$content                              = '###' . serialize( $backup_options ) . '###';

			if ( isset( $_GET['action'] ) && $_GET['action'] == 'download_options' ) {
				header( 'Content-Description: File Transfer' );
				header( 'Content-type: application/txt' );
				header( 'Content-Disposition: attachment; filename="' . str_replace( 'kleoopts-', '', $_GET['feed'] ) . '_options_' . date( 'd-m-Y' ) . '.txt"' );
				header( 'Content-Transfer-Encoding: binary' );
				header( 'Expires: 0' );
				header( 'Cache-Control: must-revalidate' );
				header( 'Pragma: public' );
				echo $content;
				exit;
			} else {
				echo $content;
				exit;
			}
		}

		/**
		 * Show page help
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _load_page() {

			// Do admin head action for this page
			add_action( 'admin_head', array( $this, 'admin_head' ) );

			// Do admin footer text hook
			add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );

			$screen = get_current_screen();

			if ( is_array( $this->args['help_tabs'] ) ) {
				foreach ( $this->args['help_tabs'] as $tab ) {
					$screen->add_help_tab( $tab );
				}
			}

			if ( $this->args['help_sidebar'] != '' ) {
				$screen->set_help_sidebar( $this->args['help_sidebar'] );
			}

			do_action( 'squeen-opts-load-page-' . $this->args['opt_name'], $screen );
		}

		/**
		 * Do action squeen-opts-admin-head for options page
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function admin_head() {
			do_action( 'squeen-opts-admin-head-' . $this->args['opt_name'], $this );
		}

		function admin_footer_text( $footer_text ) {
			return $this->args['footer_credit'];
		}

		/**
		 * Register Option for use
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _register_setting() {
			register_setting( $this->args['opt_name'] . '_group', $this->args['opt_name'], array(
				$this,
				'_validate_options'
			) );

			if ( is_null( $this->sections ) ) {
				return;
			}

			foreach ( $this->sections as $k => $section ) {
				add_settings_section( $this->args['opt_name'] . $k . '_section', $section['title'], array(
					$this,
					'_section_desc'
				), $this->args['opt_name'] . $k . '_section_group' );

				if ( isset( $section['fields'] ) ) {
					foreach ( $section['fields'] as $fieldk => $field ) {
						if ( isset( $field['title'] ) ) {
							$std_mark = ( ! isset( $this->options[ $field['id'] ] ) && $this->args['std_show'] == true && isset( $field['std'] ) ) ? $this->args['std_mark'] : '';
							$th       = $field['title'] . $std_mark;
							if ( isset( $field['sub_desc'] ) ) {
								$th .= '<span class="description">' . $field['sub_desc'] . '</span>';
							}
						} else {
							$th = '';
						}

						add_settings_field( $fieldk . '_field', $th, array(
							$this,
							'_field_input'
						), $this->args['opt_name'] . $k . '_section_group', $this->args['opt_name'] . $k . '_section', $field ); // checkbox
					}
				}
			}
			do_action( 'squeen-opts-register-settings-' . $this->args['opt_name'] );
		}

		/**
		 * Validate the Options options before insertion
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _validate_options( $plugin_options ) {
			set_transient( 'squeen-opts-saved', '1', 1000 );

			if ( ! empty( $plugin_options['import'] ) ) {
				if ( $plugin_options['import_code'] != '' ) {
					$import = $plugin_options['import_code'];
				} elseif ( $plugin_options['import_link'] != '' ) {
					$import = wp_remote_retrieve_body( wp_remote_get( $plugin_options['import_link'] ) );
				}

				$imported_options = unserialize( trim( $import, '###' ) );
				if ( is_array( $imported_options ) && isset( $imported_options['squeen-opts-backup'] ) && $imported_options['squeen-opts-backup'] == '1' ) {
					$imported_options['imported'] = 1;

					return $imported_options;
				}
			}

			if ( ! empty( $plugin_options['defaults'] ) ) {
				$plugin_options = $this->_default_values();

				return $plugin_options;
			}

			// Validate fields (if needed)
			$plugin_options = $this->_validate_values( $plugin_options, $this->options );

			if ( $this->errors ) {
				set_transient( 'squeen-opts-errors-' . $this->args['opt_name'], $this->errors, 1000 );
			}

			if ( $this->warnings ) {
				set_transient( 'squeen-opts-warnings-' . $this->args['opt_name'], $this->warnings, 1000 );
			}

			do_action( 'squeen-opts-options-validate-' . $this->args['opt_name'], $plugin_options, $this->options );

			unset( $plugin_options['defaults'] );
			unset( $plugin_options['import'] );
			unset( $plugin_options['import_code'] );
			unset( $plugin_options['import_link'] );

			return $plugin_options;
		}

		/**
		 * Validate values from options form (used in settings api validate function)
		 * calls the custom validation class for the field so authors can override with custom classes
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _validate_values( $plugin_options, $options ) {
			foreach ( $this->sections as $k => $section ) {
				if ( isset( $section['fields'] ) ) {
					foreach ( $section['fields'] as $fieldk => $field ) {
						$field['section_id'] = $k;

						if ( isset( $field['type'] ) && ( $field['type'] == 'checkbox' || $field['type'] == 'checkbox_hide_below' || $field['type'] == 'checkbox_hide_all' ) ) {
							if ( ! isset( $plugin_options[ $field['id'] ] ) ) {
								$plugin_options[ $field['id'] ] = 0;
							}
						}

						if ( isset( $field['type'] ) && $field['type'] == 'multi_text' ) {
							continue;
						} // We can't validate this yet

						if ( ! isset( $plugin_options[ $field['id'] ] ) || $plugin_options[ $field['id'] ] == '' ) {
							continue;
						}

						// Force validate of custom field types
						if ( isset( $field['type'] ) && ! isset( $field['validate'] ) ) {
							if ( $field['type'] == 'color' || $field['type'] == 'color_gradient' ) {
								$field['validate'] = 'color';
							} elseif ( $field['type'] == 'date' ) {
								$field['validate'] = 'date';
							}
						}

						if ( isset( $field['validate'] ) ) {
							$validate = 'Kleo_Validation_' . $field['validate'];

							if ( ! class_exists( $validate ) ) {
								$class_file = apply_filters( 'squeen-opts-validateclass-load', $this->dir . 'validation/' . $field['validate'] . '/validation_' . $field['validate'] . '.php', $validate );
								if ( $class_file ) {
									require_once( $class_file );
								}
							}

							if ( class_exists( $validate ) ) {
								$validation                     = new $validate( $field, $plugin_options[ $field['id'] ], $this->get( $field['id'], '' ) );
								$plugin_options[ $field['id'] ] = $validation->value;
								if ( isset( $validation->error ) ) {
									$this->errors[] = $validation->error;
								}
								if ( isset( $validation->warning ) ) {
									$this->warnings[] = $validation->warning;
								}
								continue;
							}
						}

						if ( isset( $field['validate_callback'] ) && function_exists( $field['validate_callback'] ) ) {
							$callbackvalues                 = call_user_func( $field['validate_callback'], $field, $plugin_options[ $field['id'] ], $this->get( $field['id'], '' ) );
							$plugin_options[ $field['id'] ] = $callbackvalues['value'];
							if ( isset( $callbackvalues['error'] ) ) {
								$this->errors[] = $callbackvalues['error'];
							}
							if ( isset( $callbackvalues['warning'] ) ) {
								$this->warnings[] = $callbackvalues['warning'];
							}
						}
					}
				}
			}

			return $plugin_options;
		}

		/**
		 * HTML OUTPUT.
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _options_page_html() {
			echo '<div class="wrap">';
			echo '<div id="' . $this->args['page_icon'] . '" class="icon32"><br/></div>';
			echo '<h2 id="squeen-opts-heading">' . get_admin_page_title() . '</h2>';
			echo ( isset( $this->args['intro_text'] ) ) ? $this->args['intro_text'] : '';

			do_action( 'squeen-opts-page-before-form-' . $this->args['opt_name'] );

			echo '<form method="post" action="options.php" enctype="multipart/form-data" id="squeen-opts-form-wrapper">';
			settings_fields( $this->args['opt_name'] . '_group' );

			$this->options['last_tab'] = ( isset( $_GET['tab'] ) && ! get_transient( 'squeen-opts-saved' ) ) ? $_GET['tab'] : ( ( isset( $this->options['last_tab'] ) && get_transient( 'squeen-opts-saved' ) ) ? $this->options['last_tab'] : $this->args['last_tab'] );

			echo '<input type="hidden" id="last_tab" name="' . $this->args['opt_name'] . '[last_tab]" value="' . $this->options['last_tab'] . '" />';

			echo '<div id="squeen-opts-header">';
			submit_button( '', 'primary', '', false, array( 'id' => 'kleo-options-submit' ) );
			echo ' &nbsp; ';
			submit_button( esc_html__( 'Reset to Defaults', 'sweetdate' ), 'secondary', $this->args['opt_name'] . '[defaults]', false );
			echo '<div class="clear"></div><!--clearfix-->';
			echo '</div>';

			if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' && get_transient( 'squeen-opts-saved' ) == '1' ) {
				if ( isset( $this->options['imported'] ) && $this->options['imported'] == 1 ) {
					echo '<div id="squeen-opts-imported"><strong>' .
					     apply_filters( 'squeen-opts-imported-text-' . $this->args['opt_name'],
						     esc_html__( 'Settings Imported!', 'sweetdate' )
					     ) . '</strong></div>';
				} else {
					echo '<div id="squeen-opts-save"><strong>' .
					     apply_filters( 'squeen-opts-saved-text-' . $this->args['opt_name'],
						     esc_html__( 'Settings Saved!', 'sweetdate' )
					     ) . '</strong></div>';
				}
				delete_transient( 'squeen-opts-saved' );
			}

			echo '<div id="squeen-opts-save-warn">' .
			     '<strong>'  .
			     apply_filters( 'squeen-opts-changed-text-' . $this->args['opt_name'],
				     esc_html__( 'Settings have changed, you should save them!', 'sweetdate' )
			     ) . '</strong>' . '</div>';
			echo '<div id="squeen-opts-field-errors"><strong><span></span> ' . esc_html__( 'error(s) were found!', 'sweetdate' ) . '</strong></div>';
			echo '<div id="squeen-opts-field-warnings"><strong><span></span> ' . esc_html__( 'warning(s) were found!', 'sweetdate' ) . '</strong></div>';

			echo '<div class="clear"></div><!--clearfix-->';

			echo '<div id="squeen-opts-sidebar">';
			echo '<ul id="squeen-opts-group-menu">';
			foreach ( $this->sections as $k => $section ) {
				if ( $this->args['icon_type'] == 'image' || ( isset( $section['icon_type'] ) && $section['icon_type'] == 'image' ) ) {
					$icon = ( ! isset( $section['icon'] ) ) ? '' : '<img src="' . $section['icon'] . '" /> ';
				} elseif ( isset( $section['icon_type'] ) && $section['icon_type'] == 'iconfont' ) {
					$icon_class = ( ! isset( $section['icon_class'] ) ) ? '' : ' ' . $section['icon_class'];
					$icon       = ( ! isset( $section['icon'] ) ) ? '<i class="icon-cog' . $icon_class . '"></i> ' : '<i class="icon-' . $section['icon'] . $icon_class . '"></i> ';
				} else {
					$icon_class = ( ! isset( $section['icon_class'] ) ) ? '' : ' ' . $section['icon_class'];
					$icon       = ( ! isset( $section['icon'] ) ) ? '<i class="icon-cog' . $icon_class . '"></i> ' : '<i class="icon-' . $section['icon'] . $icon_class . '"></i> ';
				}
				echo '<li id="' . $k . '_section_group_li" class="squeen-opts-group-tab-link-li">';
				echo '<a href="javascript:void(0);" id="' . $k . '_section_group_li_a" class="squeen-opts-group-tab-link-a" data-rel="' . $k . '">' . $icon . '<span>' . $section['title'] . '</span></a>';
				echo '</li>';
			}

			echo '<li class="divide">&nbsp;</li>';

			do_action( 'squeen-opts-after-section-menu-items-' . $this->args['opt_name'], $this );

			if ( true === $this->args['show_import_export'] ) {
				echo '<li id="import_export_default_section_group_li" class="squeen-opts-group-tab-link-li">';
				if ( isset( $this->args['import_icon_type'] ) && $this->args['import_icon_type'] == 'image' ) {
					$icon = ( ! isset( $this->args['import_icon'] ) ) ? '' : '<img src="' . $this->args['import_icon'] . '" /> ';
				} else {
					$icon_class = ( ! isset( $this->args['import_icon_class'] ) ) ? '' : ' ' . $this->args['import_icon_class'];
					$icon       = ( ! isset( $this->args['import_icon'] ) ) ? '<i class="icon-refresh' . $icon_class . '"></i>' : '<i class="icon-' . $this->args['import_icon'] . $icon_class . '"></i> ';
				}
				echo '<a href="javascript:void(0);" id="import_export_default_section_group_li_a" class="squeen-opts-group-tab-link-a" data-rel="import_export_default">' . $icon . ' <span>' .
				     esc_html__( 'Import / Export', 'sweetdate' ) .
				     '</span></a>';
				echo '</li>';

				echo '<li class="divide">&nbsp;</li>';
			}

			if ( is_array( $this->extra_tabs ) ) {
				foreach ( $this->extra_tabs as $k => $tab ) {
					if ( $this->args['icon_type'] == 'image' || ( isset( $tab['icon_type'] ) && $tab['icon_type'] == 'image' ) ) {
						$icon = ( ! isset( $tab['icon'] ) ) ? '' : '<img src="' . $tab['icon'] . '" /> ';
					} elseif ( isset( $tab['icon_type'] ) && $tab['icon_type'] == 'iconfont' ) {
						$icon_class = ( ! isset( $tab['icon_class'] ) ) ? '' : ' ' . $tab['icon_class'];
						$icon       = ( ! isset( $tab['icon'] ) ) ? '<i class="icon-cog' . $icon_class . '"></i> ' : '<i class="icon-' . $tab['icon'] . $icon_class . '"></i> ';
					} else {
						$icon_class = ( ! isset( $tab['icon_class'] ) ) ? '' : ' ' . $tab['icon_class'];
						$icon       = ( ! isset( $tab['icon'] ) ) ? '<i class="icon-cog' . $icon_class . '"></i> ' : '<i class="icon-' . $tab['icon'] . $icon_class . '"></i> ';
					}
					echo '<li id="' . $k . '_section_group_li" class="squeen-opts-group-tab-link-li">';
					echo '<a href="javascript:void(0);" id="' . $k . '_section_group_li_a" class="squeen-opts-group-tab-link-a custom-tab" data-rel="' . $k . '">' . $icon . '<span>' . $tab['title'] . '</span></a>';
					echo '</li>';
				}
			}

			if ( true === $this->args['dev_mode'] ) {
				echo '<li id="dev_mode_default_section_group_li" class="squeen-opts-group-tab-link-li">';
				if ( isset( $this->args['dev_mode_icon_type'] ) && $this->args['dev_mode_icon_type'] == 'image' ) {
					$icon = ( ! isset( $this->args['dev_mode_icon'] ) ) ? '' : '<img src="' . $this->args['dev_mode_icon'] . '" /> ';
				} else {
					$icon_class = ( ! isset( $this->args['dev_mode_icon_class'] ) ) ? '' : ' ' . $this->args['dev_mode_icon_class'];
					$icon       = ( ! isset( $this->args['dev_mode_icon'] ) ) ? '<i class="icon-info-sign' . $icon_class . '"></i>' : '<i class="icon-' . $this->args['dev_mode_icon'] . $icon_class . '"></i> ';
				}
				echo '<a href="javascript:void(0);" id="dev_mode_default_section_group_li_a" class="squeen-opts-group-tab-link-a custom-tab" data-rel="dev_mode_default">' . $icon . ' <span>' .
				     esc_html__( 'Dev Mode Info', 'sweetdate' ) .
				     '</span></a>';
				echo '</li>';
			}

			echo '</ul>';
			echo '</div>';

			echo '<div id="squeen-opts-main">';

			foreach ( $this->sections as $k => $section ) {
				echo '<div id="' . $k . '_section_group' . '" class="squeen-opts-group-tab">';
				do_settings_sections( $this->args['opt_name'] . $k . '_section_group' );
				echo '</div>';
			}

			if ( true === $this->args['show_import_export'] ) {
				echo '<div id="import_export_default_section_group' . '" class="squeen-opts-group-tab">';

				echo '<h3>' . esc_html__( 'Import / Export Options', 'sweetdate' ) . '</h3>';
				echo '<h4>' . esc_html__( 'Import Options', 'sweetdate' ) . '</h4>';
				echo '<p><a href="javascript:void(0);" id="squeen-opts-import-code-button" class="button-secondary">' .
				     esc_html__( 'Import from text', 'sweetdate' ) .
				     '</a> <a href="javascript:void(0);" id="squeen-opts-import-link-button" class="button-secondary">' . __( 'Import from URL', 'sweetdate' ) . '</a></p>';

				echo '<div id="squeen-opts-import-code-wrapper">';

				echo '<div class="squeen-opts-section-desc">';
				echo '<p class="description" id="import-code-description">' .
				     apply_filters( 'squeen-opts-import-file-description',
						esc_html__( 'Input your backup file below and hit Import to restore your sites options from a backup.', 'sweetdate' )
				     ) .
				     '</p>';
				echo '</div>';

				echo '<textarea id="import-code-value" name="' . $this->args['opt_name'] . '[import_code]" class="large-text" rows="8"></textarea>';

				echo '</div>';

				echo '<div id="squeen-opts-import-link-wrapper">';

				echo '<div class="squeen-opts-section-desc">';
				echo '<p class="description" id="import-link-description">' .
				     apply_filters( 'squeen-opts-import-link-description',
						esc_html__( 'Input the URL to another sites options set and hit Import to load the options from that site.', 'sweetdate' )
				     ) . '</p>';
				echo '</div>';

				echo '<input type="text" id="import-link-value" name="' . $this->args['opt_name'] . '[import_link]" class="large-text" value="" />';

				echo '</div>';

				echo '<p id="squeen-opts-import-action">' .
				     '<input type="submit" id="squeen-opts-import" name="' . $this->args['opt_name'] . '[import]" class="button-primary" value="' .
				     esc_html__( 'Import', 'sweetdate' ) . '"> <span>' .
				     apply_filters( 'squeen-opts-import-warning',
					    esc_html__( 'WARNING! This will overwrite any existing options, please proceed with caution!', 'sweetdate' )
				     ) .
				     '</span></p>';
				echo '<div id="import_divide"></div>';

				echo '<h4>' . esc_html__( 'Export Options', 'sweetdate' ) . '</h4>';
				echo '<div class="squeen-opts-section-desc">';
				echo '<p class="description">' .
				     apply_filters( 'squeen-opts-backup-description',
					     esc_html__( 'Here you can copy/download your current option settings. Keep this safe as you can use it as a backup should anything go wrong, or you can use it to restore your settings on this site (or any other site).', 'sweetdate' )
				     ) . '</p>';
				echo '</div>';

				echo '<p><a href="javascript:void(0);" id="squeen-opts-export-code-copy" class="button-secondary">' .
				     esc_html__( 'Show export text', 'sweetdate' ) . '</a>';
				$backup_options                       = $this->options;
				$backup_options['squeen-opts-backup'] = '1';
				$encoded_options                      = '###' . serialize( $backup_options ) . '###';
				echo '<textarea class="large-text" id="squeen-opts-export-code" rows="8">';
				print_r( $encoded_options );
				echo '</textarea>';
				echo '<input type="text" class="large-text" id="squeen-opts-export-link-value" value="' . add_query_arg( array(
						'feed'   => 'squeenopts-' . $this->args['opt_name'],
						'secret' => md5( AUTH_KEY . SECURE_AUTH_KEY )
					), site_url() ) . '" />';

				echo '</div>';
			}

			if ( is_array( $this->extra_tabs ) ) {
				foreach ( $this->extra_tabs as $k => $tab ) {
					echo '<div id="' . $k . '_section_group' . '" class="squeen-opts-group-tab">';
					echo '<h3>' . $tab['title'] . '</h3>';
					echo $tab['content'];
					echo '</div>';
				}
			}

			if ( true === $this->args['dev_mode'] ) {
				echo '<div id="dev_mode_default_section_group' . '" class="squeen-opts-group-tab">';
				echo '<h3>' . esc_html__( 'Dev Mode Info', 'sweetdate' ) . '</h3>';
				echo '<div class="squeen-opts-section-desc">';
				echo '<textarea class="large-text" rows="24">' . print_r( $this, true ) . '</textarea>';
				echo '</div>';
				echo '</div>';
			}

			do_action( 'squeen-opts-after-section-items-' . $this->args['opt_name'], $this );

			echo '<div class="clear"></div><!--clearfix-->';
			echo '</div>';
			echo '<div class="clear"></div><!--clearfix-->';

			echo '<div id="squeen-opts-footer">';

			if ( isset( $this->args['share_icons'] ) ) {
				echo '<div id="squeen-opts-share">';
				foreach ( $this->args['share_icons'] as $link ) {
					echo '<a href="' . $link['link'] . '" title="' . $link['title'] . '" target="_blank"><img src="' . $link['img'] . '"/></a>';
				}
				echo '</div>';
			}

			submit_button( '', 'primary', '', false );
			echo ' &nbsp; ';
			submit_button( esc_html__( 'Reset to Defaults', 'sweetdate' ), 'secondary', $this->args['opt_name'] . '[defaults]', false );
			echo '<div class="clear"></div><!--clearfix-->';
			echo '</div>';

			echo '</form>';
			echo ( isset( $this->args['footer_text'] ) ) ? '<div id="squeen-opts-sub-footer">' . $this->args['footer_text'] . '</div>' : '';

			do_action( 'squeen-opts-page-after-form-' . $this->args['opt_name'] );

			echo '<div class="clear"></div><!--clearfix-->';
			echo '</div><!--wrap-->';
		}

		/**
		 * JS to display the errors on the page
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _errors_js() {
			if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' && get_transient( 'squeen-opts-errors-' . $this->args['opt_name'] ) ) {
				$errors         = get_transient( 'squeen-opts-errors-' . $this->args['opt_name'] );
				$section_errors = array();
				foreach ( $errors as $error ) {
					$section_errors[ $error['section_id'] ] = ( isset( $section_errors[ $error['section_id'] ] ) ) ? $section_errors[ $error['section_id'] ] : 0;
					$section_errors[ $error['section_id'] ] ++;
				}

				echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function(){';
				echo 'jQuery("#squeen-opts-field-errors span").html("' . count( $errors ) . '");';
				echo 'jQuery("#squeen-opts-field-errors").show();';

				foreach ( $section_errors as $sectionkey => $section_error ) {
					echo 'jQuery("#' . $sectionkey . '_section_group_li_a").append("<span class=\"squeen-opts-menu-error\">' . $section_error . '</span>");';
				}

				foreach ( $errors as $error ) {
					echo 'jQuery("#' . $error['id'] . '").addClass("squeen-opts-field-error");';
					echo 'jQuery("#' . $error['id'] . '").closest("td").append("<span class=\"squeen-opts-th-error\">' . $error['msg'] . '</span>");';
				}
				echo '});';
				echo '</script>';
				delete_transient( 'squeen-opts-errors-' . $this->args['opt_name'] );
			}
		}

		/**
		 * JS to display the warnings on the page
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _warnings_js() {
			if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' && get_transient( 'squeen-opts-warnings-' . $this->args['opt_name'] ) ) {
				$warnings         = get_transient( 'squeen-opts-warnings-' . $this->args['opt_name'] );
				$section_warnings = array();
				foreach ( $warnings as $warning ) {
					$section_warnings[ $warning['section_id'] ] = ( isset( $section_warnings[ $warning['section_id'] ] ) ) ? $section_warnings[ $warning['section_id'] ] : 0;
					$section_warnings[ $warning['section_id'] ] ++;
				}

				echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function(){';
				echo 'jQuery("#squeen-opts-field-warnings span").html("' . count( $warnings ) . '");';
				echo 'jQuery("#squeen-opts-field-warnings").show();';

				foreach ( $section_warnings as $sectionkey => $section_warning ) {
					echo 'jQuery("#' . $sectionkey . '_section_group_li_a").append("<span class=\"squeen-opts-menu-warning\">' . $section_warning . '</span>");';
				}

				foreach ( $warnings as $warning ) {
					echo 'jQuery("#' . $warning['id'] . '").addClass("squeen-opts-field-warning");';
					echo 'jQuery("#' . $warning['id'] . '").closest("td").append("<span class=\"squeen-opts-th-warning\">' . $warning['msg'] . '</span>");';
				}
				echo '});';
				echo '</script>';
				delete_transient( 'squeen-opts-warnings-' . $this->args['opt_name'] );
			}
		}

		/**
		 * Section HTML OUTPUT.
		 *
		 * @since Kleo_Options 1.0.0
		 */
		function _section_desc( $section ) {
			$id = trim( rtrim( $section['id'], '_section' ), $this->args['opt_name'] );

			if ( isset( $this->sections[ $id ]['desc'] ) && ! empty( $this->sections[ $id ]['desc'] ) ) {
				echo '<div class="squeen-opts-section-desc">' . $this->sections[ $id ]['desc'] . '</div>';
			}
		}

		/**
		 * Field HTML OUTPUT.
		 *
		 * Gets option from options array, then calls the specific field type class - allows extending by other devs
		 * @since Kleo_Options 1.0.0
		 */
		function _field_input( $field ) {
			if ( isset( $field['callback'] ) && function_exists( $field['callback'] ) ) {
				$value = $this->get( $field['id'], '' );
				do_action( 'squeen-opts-before-field-' . $this->args['opt_name'], $field, $value );
				call_user_func( $field['callback'], $field, $value );
				do_action( 'squeen-opts-after-field-' . $this->args['opt_name'], $field, $value );

				return;
			}

			if ( isset( $field['type'] ) ) {
				$field_class = 'Kleo_Options_' . $field['type'];

				if ( ! class_exists( $field_class ) ) {
					$class_file = apply_filters( 'squeen-opts-typeclass-load', $this->dir . 'fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field_class );
					if ( $class_file ) {
						require_once( $class_file );
					}
				}

				if ( class_exists( $field_class ) ) {
					$value = $this->get( $field['id'], '' );
					do_action( 'squeen-opts-before-field-' . $this->args['opt_name'], $field, $value );
					$render = '';
					$render = new $field_class( $field, $value, $this );
					$render->render();
					do_action( 'squeen-opts-after-field-' . $this->args['opt_name'], $field, $value );
				}
			}
		}
	}
}
