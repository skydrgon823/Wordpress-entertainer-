<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}

class MobileMenuCSS
{
    // Compression type to use
    const  SCSS_COMPRESSION = 'mobmenuscss_formatter_compressed' ;
    // Internal variables
    private  $mobmenuInstance ;
    private  $allOptionsWithIDs = array() ;
    // Keep all added CSS here
    private  $additionalCSS = array() ;
    function __construct( $mobmenuInstance )
    {
        $this->mobmenuInstance = $mobmenuInstance;
        $css = get_option( $this->getCSSSlug() );
        $generated_css = $this->getCSSFilePath();
        // Gather all the options
        add_action( 'mm_create_option_mobmenu', array( $this, 'getOptionsWithCSS' ) );
        // display our CSS
        add_action( 'wp_head', array( $this, 'printCSS' ), 99 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueueCSS' ) );
        // Trigger new compile when admin option settings were saved
        add_action( 'mm_admin_options_saved_mobmenu', array( $this, 'generateSaveCSS' ) );
        // Trigger compile when there are no default options saved yet
        add_action( 'mm_init_no_options_mobmenu', array( $this, 'generateMissingCSS' ) );
    }
    
    /**
     * Adds a CSS string to the list for CSS generation
     *
     * @param   string $cssString string CSS, can contain SaSS variables of optionIDs
     * @return  void
     */
    public function addCSS( $cssString )
    {
        $this->additionalCSS[] = $cssString;
    }
    
    /**
     * Prints the styles in the head tag. Used IF the CSS file could not be generated
     *
     * @return  void
     */
    public function printCSS()
    {
        // If the setting is 'generate css' and we can't just echo it out
        
        if ( $this->mobmenuInstance->settings['css'] == 'generate' ) {
            $css = get_option( $this->getCSSSlug() );
            if ( !empty($css) ) {
                echo  "<style id='mm-" . esc_attr( $this->mobmenuInstance->optionNamespace ) . "'>{$css}</style>" ;
            }
            // If the setting is 'print inline css', print it out if we have any
        } else {
            
            if ( $this->mobmenuInstance->settings['css'] == 'inline' ) {
                $css = $this->generateCSS();
                if ( !empty($css) ) {
                    echo  "<style id='mm-" . esc_attr( $this->mobmenuInstance->optionNamespace ) . "'>{$css}</style>" ;
                }
            }
        
        }
    
    }
    
    /**
     * Enqueues the generated CSS. Used IF the CSS file was successfully generated
     *
     * @return  void
     * @since   1.2
     */
    public function enqueueCSS()
    {
        $mobmenu_options = MobileMenuOptions::getInstance( 'mobmenu' );
        $is_mobile_only = $mobmenu_options->getOption( 'only_mobile_devices' );
        $is_testing_mode = $mobmenu_options->getOption( 'only_testing_mode' );
        $mobmenu_action = '';
        if ( isset( $_GET['mobmenu-action'] ) ) {
            $mobmenu_action = $_GET['mobmenu-action'];
        }
        if ( $mobmenu_action == 'find-element' || $is_testing_mode && current_user_can( 'administrator' ) || !$is_testing_mode && (!$is_mobile_only || $is_mobile_only && wp_is_mobile()) ) {
            // Only enqueue the generated css if we have the settings for it.
            
            if ( $this->mobmenuInstance->settings['css'] == 'generate' ) {
                $css = get_option( $this->getCSSSlug() );
                $generated_css = $this->getCSSFilePath();
                
                if ( file_exists( $generated_css ) ) {
                    
                    if ( !$mobmenu_options->getOption( 'cache_dynamic_css', false ) ) {
                        wp_enqueue_style(
                            'mm-compiled-options-' . $this->mobmenuInstance->optionNamespace,
                            $this->getCSSFileURL(),
                            '',
                            WP_MOBILE_MENU_VERSION . '-' . rand( 100, 999 )
                        );
                    } else {
                        wp_enqueue_style(
                            'mm-compiled-options-' . $this->mobmenuInstance->optionNamespace,
                            $this->getCSSFileURL(),
                            '',
                            WP_MOBILE_MENU_VERSION
                        );
                    }
                
                } else {
                    $plugin_settings = MobileMenuOptions::getInstance( 'mobmenu' );
                    echo  '<style id="dynamic-mobmenu-inline-css" type="text/css">' ;
                    $css = $this->generateCSS();
                    echo  $css . '</style>' ;
                }
            
            }
        
        }
    }
    
    /**
     * Gathers all options with IDs for generation of CSS rules
     *
     * @param   MobileMenuOption $option The option which was just added
     * @return  void
     * @since   1.2
     */
    public function getOptionsWithCSS( $option )
    {
        if ( !empty($option->settings['id']) ) {
            $this->allOptionsWithIDs[] = $option;
        }
    }
    
    /**
     * Generates a unique slug for our CSS generation
     *
     * @return  string a unique slug that uses the option namespace
     * @since   1.2
     */
    public function getCSSSlug()
    {
        return 'dynamic-' . str_replace( ' ', '-', trim( strtolower( $this->mobmenuInstance->optionNamespace ) ) );
    }
    
    /**
     * Returns the path of the generated CSS file
     *
     * @return  string The full path to the CSS file
     * @since   1.2
     */
    public function getCSSFilePath()
    {
        $upload_dir = wp_upload_dir();
        $uploadsFolder = $upload_dir['basedir'] . '/';
        $namespace = $this->mobmenuInstance->optionNamespace;
        return apply_filters( "tf_css_get_css_file_path_{$namespace}", $uploadsFolder . $this->getCSSSlug() . '.css' );
    }
    
    /**
     * Returns the URL of the generated CSS file
     *
     * @return  string The URL to the CSS file
     * @since   1.2
     */
    private function getCSSFileURL()
    {
        $uploads = wp_upload_dir();
        $url = trailingslashit( $uploads['baseurl'] ) . $this->getCSSSlug() . '.css';
        if ( is_ssl() ) {
            $url = str_replace( 'http://', 'https://', $url );
        }
        return $url;
    }
    
    /**
     * Forms CSS rules containing SaSS variables
     *
     * @param   string $id The id of an option
     * @param   string $value The value or CSS rule
     * @param   mixes  $key The key of the value, used for when the value is an array
     * @param   string $cssString The current CSS rules from a previous recursive call
     * @return  string CSS rules of SaSS variables
     * @since   1.2
     */
    private function formCSSVariables(
        $id,
        $type,
        $value,
        $key = false,
        $cssString = ''
    )
    {
        if ( is_serialized( $value ) ) {
            $value = unserialize( stripslashes( $value ) );
        }
        
        if ( is_array( $value ) ) {
            foreach ( $value as $subKey => $subValue ) {
                if ( $key != false ) {
                    $subKey = $key . '-' . $subKey;
                }
                $cssString = $this->formCSSVariables(
                    $id,
                    $type,
                    $subValue,
                    $subKey,
                    $cssString
                );
            }
        } else {
            $value = esc_attr( $value );
            // Compile as SCSS & minify
            require_once trailingslashit( dirname( dirname( __FILE__ ) ) ) . 'inc/scssphp/scss.inc.php';
            $scss = new mobmenuscssc();
            // If the value is a file address, wrap it in quotes
            if ( $type == 'upload' ) {
                $value = "'" . $value . "'";
            }
            // Compile checks.
            // Odd, in our newer copy of SCSSPHP, you need to add ';' to detect errors
            try {
                $testerForValidCSS = $scss->compile( ';$' . esc_attr( $id ) . ': ' . $value . ';' );
            } catch ( Exception $e ) {
                try {
                    $testerForValidCSS = $scss->compile( ';$' . esc_attr( $id ) . ": '" . $value . "';" );
                    $value = "'" . $value . "'";
                } catch ( Exception $e ) {
                    try {
                        $testerForValidCSS = $scss->compile( ';$' . esc_attr( $id ) . ": url('" . $value . "');" );
                        $value = "url('" . $value . "')";
                    } catch ( Exception $e ) {
                        return $cssString;
                    }
                }
            }
            
            if ( false == $key ) {
                $cssString .= '$' . esc_attr( $id ) . ': ' . $value . ";\n";
            } else {
                $cssString .= '$' . esc_attr( $id ) . '-' . esc_attr( $key ) . ': ' . $value . ";\n";
            }
        
        }
        
        return $cssString;
    }
    
    /**
     * Generates a CSS string of all the WP Mobile Menu options
     *
     * @return  string A CSS string of all the values
     * @since  2.6.2
     */
    public function generateMobileMenuCSS()
    {
        // Start collecting the output buffer.
        ob_start();
        require_once WP_MOBILE_MENU_PLUGIN_PATH . '/includes/dynamic-style.php';
        $output = ob_get_contents();
        ob_end_clean();
        return (string) $output;
    }
    
    /**
     * Generates a CSS string of all the options
     *
     * @return  string A CSS string of all the values
     * @since   1.2
     */
    public function generateCSS()
    {
        $cssString = '';
        $plugin_settings = MobileMenuOptions::getInstance( 'mobmenu' );
        // These are the option types which are not allowed.
        $noCSSOptionTypes = array( 'text', 'textarea', 'editor' );
        // Compile as SCSS & minify.
        require_once trailingslashit( dirname( dirname( __FILE__ ) ) ) . 'inc/scssphp/scss.inc.php';
        $scss = new mobmenuscssc();
        $my_custom_css = $plugin_settings->getOption( 'custom_css' );
        // Add additional CSS added via MobileMenuOptions::createCSS()
        foreach ( $this->additionalCSS as $css ) {
            $cssString .= $css . "\n";
        }
        // Compile as SCSS & minify.
        
        if ( !empty($cssString) ) {
            $scss->setFormatter( self::SCSS_COMPRESSION );
            try {
                $testerForValidCSS = $scss->compile( $cssString );
                $cssString = $testerForValidCSS;
            } catch ( Exception $e ) {
                $cssString = '';
            }
        }
        
        $mobile_menu_css = $this->generateMobileMenuCSS();
        $cssString .= $mobile_menu_css;
        $cssString .= $my_custom_css;
        return $cssString;
    }
    
    /**
     * Generates a the CSS file containing all the rules assigned to options, or created using
     *
     * @return	void
     */
    public function generateSaveCSS()
    {
        global  $mm_fs ;
        $cssString = $this->generateCSS();
        if ( empty($cssString) ) {
            return;
        }
        // Save our css.
        
        if ( $this->writeCSS( $cssString, $this->getCSSFilePath() ) ) {
            // If we were able to save, remove our CSS option if it exists.
            delete_option( $this->getCSSSlug() );
        } else {
            // If we were NOT able to save our generated CSS, save our CSS.
            // as an option, we'll load that in wp_head in a hook.
            update_option( $this->getCSSSlug(), $cssString );
        }
        
        // This is flag of control to detect updates.
        if ( get_option( 'mobmenu_latest_update_version', '' ) !== WP_MOBILE_MENU_VERSION ) {
            update_option( 'mobmenu_latest_update_version', WP_MOBILE_MENU_VERSION );
        }
    }
    
    /**
     * Update the SVG Icon colors.
     *
     * @since 2.7
     */
    private function updateSvgColors()
    {
        $plugin_settings = MobileMenuOptions::getInstance( 'mobmenu' );
        $upload_dir = wp_upload_dir();
        $uploadsFolder = $upload_dir['basedir'] . '/';
        // Change the SVG search icon color.
        $svg_file_path = $uploadsFolder . 'search.svg';
        $svg_color = $plugin_settings->getInternalAdminPageOption( 'search_icon_color', '#000000' );
        $this->writeSVG( $svg_color, $svg_file_path );
        
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            // Change the SVG cart icon color.
            $svg_file_path = $uploadsFolder . 'cart.svg';
            $svg_color = $plugin_settings->getInternalAdminPageOption( 'mm_woo_menu_icon_color', '#000000' );
            $this->writeSVG( $svg_color, $svg_file_path );
        }
    
    }
    
    /**
     * When the no options are saved yet (e.g. new install) create a CSS
     *
     * @return	void
     * @since	1.4.1
     */
    public function generateMissingCSS()
    {
        add_action( 'admin_init', array( $this, '_generateMissingCSS' ), 1000 );
    }
    
    /**
     * When the no options are saved yet (e.g. new install) create a CSS, called internally
     *
     * @return	void
     * @since	1.4.1
     */
    public function _generateMissingCSS()
    {
        // WP_Filesystem is only available in the admin.
        if ( !is_admin() ) {
            return;
        }
        global  $wp_filesystem ;
        $css_filename = $this->getCSSFilePath();
        WP_Filesystem();
        if ( get_option( 'mobmenu_latest_update_version', '' ) === WP_MOBILE_MENU_VERSION ) {
            // Check if the file exists.
            if ( $wp_filesystem->exists( $css_filename ) ) {
                return;
            }
        }
        // Verify directory.
        if ( !$wp_filesystem->is_dir( dirname( $css_filename ) ) ) {
            return;
        }
        if ( !$wp_filesystem->is_writable( dirname( $css_filename ) ) ) {
            return;
        }
        $this->generateSaveCSS();
    }
    
    /**
     * Writes the CSS file
     *
     * @return  boolean True if the CSS file was written successfully
     * @since   1.2
     */
    private function writeCSS( $parsedCSS, $cssFilename )
    {
        WP_Filesystem();
        global  $wp_filesystem ;
        // Verify that we can create the file.
        
        if ( $wp_filesystem->exists( $cssFilename ) ) {
            if ( !$wp_filesystem->is_writable( $cssFilename ) ) {
                return false;
            }
            if ( !$wp_filesystem->is_readable( $cssFilename ) ) {
                return false;
            }
        }
        
        // Verify directory.
        if ( !$wp_filesystem->is_dir( dirname( $cssFilename ) ) ) {
            return false;
        }
        if ( !$wp_filesystem->is_writable( dirname( $cssFilename ) ) ) {
            return false;
        }
        // Write our CSS.
        return $wp_filesystem->put_contents( $cssFilename, $parsedCSS, 0644 );
    }
    
    /**
     * Writes the SVG file
     *
     * @return  boolean True if the SVG file was written successfully
     * @since   2.7
     */
    private function writeSVG( $svg_color, $svgFilename )
    {
        global  $wp_filesystem ;
        WP_Filesystem();
        // Verify that we can create the file.
        
        if ( $wp_filesystem->exists( $svgFilename ) ) {
            if ( !$wp_filesystem->is_writable( $svgFilename ) ) {
                return false;
            }
            if ( !$wp_filesystem->is_readable( $svgFilename ) ) {
                return false;
            }
            // Verify directory.
            if ( !$wp_filesystem->is_dir( dirname( $svgFilename ) ) ) {
                return false;
            }
            if ( !$wp_filesystem->is_writable( dirname( $svgFilename ) ) ) {
                return false;
            }
            $svg_file_content = $wp_filesystem->get_contents( $svgFilename );
        } else {
            
            if ( strpos( $svgFilename, 'search.svg' ) > 0 ) {
                $svgFilenameOrig = WP_MOBILE_MENU_PLUGIN_PATH . 'includes/assets/svgs/search.svg';
            } else {
                $svgFilenameOrig = WP_MOBILE_MENU_PLUGIN_PATH . 'includes/assets/svgs/cart.svg';
            }
            
            $svg_file_content = $wp_filesystem->get_contents( $svgFilenameOrig );
        }
        
        $svg_color = str_pad( $svg_color, 7 );
        $svg_file_content = substr_replace(
            $svg_file_content,
            '' . $svg_color . '',
            strpos( $svg_file_content, 'fill' ) + 6,
            7
        );
        // Update our SVG.
        return $wp_filesystem->put_contents( $svgFilename, $svg_file_content, 0644 );
    }

}