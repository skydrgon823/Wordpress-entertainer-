
  /*
    *
    *   Javascript Functions
    *   ------------------------------------------------
    *   WP Mobile Menu PRO
    *   Copyright WP Mobile Menu 2017 - http://www.wpmobilemenu.com
    *
    *
    *
    */

    
 "use strict";
  var searchTerm = '';
 

 (function ($) {

 jQuery( document ).ready( function(){
    
    $( '#mobmenu_custom_css' ).click();
    function hideFieldsNotNeeded(){

        
        // General options.
        if ( $( "[data-link-id='general-options']" ).hasClass('active') ) {

            if ( $( '#mobmenu_enable_right_menu' ).parent().find( '.button-primary' ).text() == 'Yes' ) {
                $( '.mobmenu_right_menu' ).show();
            } else {
                $( '.mobmenu_right_menu' ).hide();
            }

            if ( $( '#mobmenu_enable_left_menu' ).parent().find( '.button-primary' ).text() == 'Yes' ) {
                $( '.mobmenu_left_menu' ).show();
            } else {
                $( '.mobmenu_left_menu' ).hide();
            }
        }

        // Left Panel options.
        if ( $( "[data-link-id='left-panel-options']" ).hasClass('active') ) {
            if ( $('#mobmenu_left_menu_bg_image').val() == '' ) {
                $( '.mobmenu_left_menu_bg_opacity' ).hide();
                $( '.mobmenu_left_menu_bg_image_size' ).hide();
                $( '.mobmenu_left_menu_bg_gradient' ).show();
            } else {
                $( '.mobmenu_left_menu_bg_opacity' ).show();
                $( '.mobmenu_left_menu_bg_image_size' ).show();
                $( '.mobmenu_left_menu_bg_gradient' ).hide();
            }
            if ( $( '#mobmenu_left_menu_width_units' ).parent().find( '.button-primary' ).text() == 'Pixels' ) {
                $( '.mobmenu_left_menu_width' ).show();
                $( '.mobmenu_left_menu_width_percentage' ).hide();
            } else {
                $( '.mobmenu_left_menu_width' ).hide();
                $( '.mobmenu_left_menu_width_percentage' ).show();
            }
        }

        // Right Panel options.
        if ( $( "[data-link-id='right-panel-options']" ).hasClass('active') ) {
            if ( $('#mobmenu_right_menu_bg_image').val() == '' ) {
                $( '.mobmenu_right_menu_bg_opacity' ).hide();
                $( '.mobmenu_right_menu_bg_image_size' ).hide();
                $( '.mobmenu_right_menu_bg_gradient' ).show();
            } else {
                $( '.mobmenu_right_menu_bg_opacity' ).show();
                $( '.mobmenu_right_menu_bg_image_size' ).show();
                $( '.mobmenu_right_menu_bg_gradient' ).hide();
            }
            if ( $( '#mobmenu_right_menu_width_units' ).parent().find( '.button-primary' ).text() == 'Pixels' ) {
                $( '.mobmenu_right_menu_width' ).show();
                $( '.mobmenu_right_menu_width_percentage' ).hide();
            } else {
                $( '.mobmenu_right_menu_width' ).hide();
                $( '.mobmenu_right_menu_width_percentage' ).show();
            }
        }

        // Right Menu options.
        if ( $( "[data-link-id='right-menu-options']" ).hasClass('active')  ) {
            
            if ( $( '#mobmenu_enable_right_menu' ).parent().find( '.button-primary' ).text() == 'Yes' ) {
                $( '.mobmenu_right_menu' ).show();
                $( '.mobmenu_right_menu_content_position' ).show();
                $( '.mobmenu_right_menu_parent_link_submenu' ).show();
                $( '.mobmenu_enable_right_menu_logged_in' ).show();
                $( '.mobmenu_sliding_submenus' ).show();
                $( '.mobmenu_autoclose_submenus' ).show();
                $( '.mobmenu_menu_items_border_size' ).show();
            } else {
                $( '.mobmenu_right_menu' ).hide();
                $( '.mobmenu_right_menu_content_position' ).hide();
                $( '.mobmenu_right_menu_parent_link_submenu' ).hide();
                $( '.mobmenu_enable_right_menu_logged_in' ).hide();
                $( '.mobmenu_sliding_submenus' ).hide();
                $( '.mobmenu_autoclose_submenus' ).hide();
                $( '.mobmenu_menu_items_border_size' ).hide();
            }
        }

        // Left Menu options.
        if ( $( "[data-link-id='left-menu-options']" ).hasClass('active') ) {
            if ( $( '#mobmenu_enable_left_menu' ).parent().find( '.button-primary' ).text() == 'Yes' ) {
                $( '.mobmenu_left_menu' ).show();
                $( '.mobmenu_left_menu_content_position' ).show();
                $( '.mobmenu_left_menu_parent_link_submenu' ).show();
                $( '.mobmenu_enable_left_menu_logged_in' ).show();
                $( '.mobmenu_sliding_submenus' ).show();
                $( '.mobmenu_autoclose_submenus' ).show();
                $( '.mobmenu_menu_items_border_size' ).show();
            } else {
                $( '.mobmenu_left_menu' ).hide();
                $( '.mobmenu_left_menu_content_position' ).hide();
                $( '.mobmenu_left_menu_parent_link_submenu' ).hide();
                $( '.mobmenu_enable_left_menu_logged_in' ).hide();
                $( '.mobmenu_sliding_submenus' ).hide();
                $( '.mobmenu_autoclose_submenus' ).hide();
                $( '.mobmenu_menu_items_border_size' ).hide();
            }
        }

        if ( $( "[data-link-id='right-tabbed-menusicon']" ).hasClass('active') ) {
            if ( $( '#mobmenu_right_menu_tabbed_menus' ).parent().find( '.button-primary' ).text() == 'Yes' ) {
                $( '.mobmenu_right_tab_title_1' ).show();
                $( '.mobmenu_right_menu_tab_1' ).show();
                $( '.mobmenu_right_tab_title_2' ).show();
                $( '.mobmenu_right_menu_tab_2' ).show();
                $( '.mobmenu_right_menu_tab_margin_top' ).show();
            } else {
                $( '.mobmenu_right_tab_title_1' ).hide();
                $( '.mobmenu_right_menu_tab_1' ).hide();
                $( '.mobmenu_right_tab_title_2' ).hide();
                $( '.mobmenu_right_menu_tab_2' ).hide();
                $( '.mobmenu_right_menu_tab_margin_top' ).hide();
            }
        }

        if ( $( "[data-link-id='header-banner-options']" ).hasClass('active') ) {
            if ( $( '#mobmenu_enable_header_banner' ).parent().find( '.button-primary' ).text() == 'Yes' ) {
                $( '.mobmenu_header_banner_position' ).show();
                $( '.mobmenu_header_banner_content' ).show();
                $( '.mobmenu_header_banner_height' ).show();
                $( '.mobmenu_header_banner_align' ).show();
                $( '.mobmenu_header_banner_left_padding' ).show();
                $( '.mobmenu_page_title_header_global' ).show();
                $( '.mobmenu_header_banner_right_padding' ).show();
            } else {
                $( '.mobmenu_header_banner_position' ).hide();
                $( '.mobmenu_header_banner_content' ).hide();
                $( '.mobmenu_header_banner_height' ).hide();
                $( '.mobmenu_header_banner_align' ).hide();
                $( '.mobmenu_header_banner_left_padding' ).hide();
                $( '.mobmenu_page_title_header_global' ).hide();
                $( '.mobmenu_header_banner_right_padding' ).hide();
            }
        }

        if ( $( "[data-link-id='header-search-options']" ).hasClass('active') ) {
            if ( $( '#mobmenu_enable_header_search' ).parent().find( '.button-primary' ).text() == 'Yes' ) {
                $( '.mobmenu_header_ajax_search' ).show();
                $( '.mobmenu_header_search_results_align' ).show();
                $( '.mobmenu_search_icon_image' ).show();
                $( '.mobmenu_search_icon_top_margin' ).show();
                $( '.mobmenu_search_icon_font_size' ).show();
                $( '.mobmenu_search_icon_text' ).show();
                $( '.mobmenu_placeholder_text' ).show();
            } else {
                $( '.mobmenu_header_ajax_search' ).hide();
                $( '.mobmenu_header_search_results_align' ).hide();
                $( '.mobmenu_search_icon_image' ).hide();
                $( '.mobmenu_search_icon_top_margin' ).hide();
                $( '.mobmenu_search_icon_font_size' ).hide();
                $( '.mobmenu_search_icon_text' ).hide();
                $( '.mobmenu_placeholder_text' ).hide();
            }
        }

        if ( $( "[data-link-id='left-tabbed-menus']" ).hasClass('active') ) {
            if ( $( '#mobmenu_left_menu_tabbed_menus' ).parent().find( '.button-primary' ).text() == 'Yes' ) {
                $( '.mobmenu_left_tab_title_1' ).show();
                $( '.mobmenu_left_menu_tab_1' ).show();
                $( '.mobmenu_left_tab_title_2' ).show();
                $( '.mobmenu_left_menu_tab_2' ).show();
                $( '.mobmenu_left_menu_tab_margin_top' ).show();
            } else {
                $( '.mobmenu_left_tab_title_1' ).hide();
                $( '.mobmenu_left_menu_tab_1' ).hide();
                $( '.mobmenu_left_tab_title_2' ).hide();
                $( '.mobmenu_left_menu_tab_2' ).hide();
                $( '.mobmenu_left_menu_tab_margin_top' ).hide();
            }
        }

        if ( $( "[data-link-id='left-menu-icon']" ).hasClass('active') ) {

            // Icon.
            if ( $( '.mobmenu_left_menu_icon_new .select2-hidden-accessible').val() == 'icon' ) {
                $( '.mobmenu_left_menu_icon_animation' ).hide();
                $( '.mobmenu_left_menu_icon_font' ).show();
                $( '.mobmenu_left_icon_font_size' ).show();
                $( '.mobmenu_left_menu_icon' ).hide();
            }

            // Animated Icon.
            if ( $( '.mobmenu_left_menu_icon_new .select2-hidden-accessible').val() == 'animated-icon' ) {
                $( '.mobmenu_left_menu_icon_animation' ).show();
                $( '.mobmenu_left_menu_icon_font' ).hide();
                $( '.mobmenu_left_icon_font_size' ).show();
                $( '.mobmenu_left_menu_icon' ).hide();
            }

            // Image.
            if ( $( '.mobmenu_left_menu_icon_new .select2-hidden-accessible').val() == 'image' ) {
                $( '.mobmenu_left_menu_icon_animation' ).hide();
                $( '.mobmenu_left_menu_icon_font' ).hide();
                $( '.mobmenu_left_icon_font_size' ).hide();
                $( '.mobmenu_left_menu_icon' ).show();
            }

            // If the icon opens a link.
            if ( $( '#mobmenu_left_menu_icon_action' ).parent().find( '.button-primary' ).text() == 'Open Menu' ) {
                $( '.mobmenu_left_icon_url' ).hide();
                $( '.mobmenu_left_icon_url_target' ).hide();
            } else {
                $( '.mobmenu_left_icon_url' ).show();
                $( '.mobmenu_left_icon_url_target' ).show();
            }

        }

        if ( $( "[data-link-id='right-menu-icon']" ).hasClass('active') ) {

              // Icon.
              if ( $( '.mobmenu_right_menu_icon_new .select2-hidden-accessible').val() == 'icon' ) {
                $( '.mobmenu_right_menu_icon_animation' ).hide();
                $( '.mobmenu_right_menu_icon_font' ).show();
                $( '.mobmenu_right_icon_font_size' ).show();
                $( '.mobmenu_right_menu_icon' ).hide();
            }

            // Animated Icon.
            if ( $( '.mobmenu_right_menu_icon_new .select2-hidden-accessible').val() == 'animated-icon' ) {
                $( '.mobmenu_right_menu_icon_animation' ).show();
                $( '.mobmenu_right_menu_icon_font' ).hide();
                $( '.mobmenu_right_icon_font_size' ).show();
                $( '.mobmenu_right_menu_icon' ).hide();
            }

            // Image.
            if ( $( '.mobmenu_right_menu_icon_new .select2-hidden-accessible').val() == 'image' ) {
                $( '.mobmenu_right_menu_icon_animation' ).hide();
                $( '.mobmenu_right_menu_icon_font' ).hide();
                $( '.mobmenu_right_icon_font_size' ).hide();
                $( '.mobmenu_right_menu_icon' ).show();
            }

             // If the icon opens a link.
             if ( $( '#mobmenu_right_menu_icon_action' ).parent().find( '.button-primary' ).text() == 'Open Menu' ) {
                $( '.mobmenu_right_icon_url' ).hide();
                $( '.mobmenu_right_icon_url_target' ).hide();
            } else {
                $( '.mobmenu_right_icon_url' ).show();
                $( '.mobmenu_right_icon_url_target' ).show();
            }
        }

        if ( $( "[data-link-id='header-options']" ).hasClass('active') ) {
            if ( $( '#mobmenu_enabled_naked_header' ).parent().find( '.button-primary' ).text() == 'Hamburger Menu' ) {
                $( '.mobmenu_header_shadow' ).hide();
                $( '.mobmenu_header_text' ).hide();
                $( '.mobmenu_header_height' ).hide();
                $( '.mobmenu_header_text_align' ).hide();
                $( '.mobmenu_header_font_size' ).hide();
                $( '.mobmenu_header_text_left_margin' ).hide();
                $( '.mobmenu_header_text_logo_spacing' ).hide();
                $( '.mobmenu_header_text_right_margin' ).hide();
            } else {
                $( '.mobmenu_header_shadow' ).show();
                $( '.mobmenu_header_text' ).show();
                $( '.mobmenu_header_height' ).show();
                $( '.mobmenu_header_text_align' ).show();
                $( '.mobmenu_header_font_size' ).show();
                $( '.mobmenu_header_text_left_margin' ).show();
                $( '.mobmenu_header_text_logo_spacing' ).show();
                $( '.mobmenu_header_text_right_margin' ).show();
            }
        }

        if ( $( "[data-link-id='logo-options']" ).hasClass('active') ) {
            if ( $( '.mobmenu_header_branding .select2-hidden-accessible').val() == 'text' ) {
                $( '.mobmenu_logo_img' ).hide();
                $( '.mobmenu_logo_img_retina' ).hide();
                $( '.mobmenu_logo_height' ).hide();
            }
            if ( $( '.mobmenu_header_branding .select2-hidden-accessible').val() == 'logo' || $( '.mobmenu_header_branding .select2-hidden-accessible').val() == 'logo-text' || $( '.mobmenu_header_branding .select2-hidden-accessible').val() == 'text-logo' ) {
                $( '.mobmenu_logo_img' ).show();
                $( '.mobmenu_logo_img_retina' ).show();
                $( '.mobmenu_logo_height' ).show();
                
            }
        }

        if ( $( "[data-tab-id='footer-options']" ).hasClass('active') ) {
            if ( $( '#mobmenu_enable_footer_icons' ).parent().find( '.button-primary' ).text() == 'Yes' ) {
                $('.mobmenu_footer_menu').show();
                $('.mobmenu_enable_footer_menu_logged_in').show();
                $('.mobmenu_autohide_footer').show();
                $('.mobmenu_footer_style').show();
                $('.mobmenu_footer_padding').show();
                $('.mobmenu_footer_icon_font_size').show();
            }
            else {
                $('.mobmenu_footer_menu').hide();
                $('.mobmenu_enable_footer_menu_logged_in').hide();
                $('.mobmenu_autohide_footer').hide();
                $('.mobmenu_footer_style').hide();
                $('.mobmenu_footer_padding').hide();
                $('.mobmenu_footer_icon_font_size').hide();
            }
        }

        if ( $( "[data-link-id='cart-icon']" ).hasClass('active') ) {
            if ( $( '#mobmenu_mm_woo_menu_icon_opt' ).parent().find( '.button-primary' ).text() == 'Default Cart SVG Icon' ) {
                $('.mobmenu_mm_woo_menu_icon_font' ).hide();
                $('.mobmenu_mm_woo_menu_icon_font_size' ).hide();
            } else {
                $('.mobmenu_mm_woo_menu_icon_font' ).show();
                $('.mobmenu_mm_woo_menu_icon_font_size' ).show();
            }
        }
        if ( $( "[data-link-id='woocommerce-options']" ).hasClass('active') ) {
            if ( $( '#mobmenu_enable_mm_woo_menu' ).parent().find( '.button-primary' ).text() == 'Yes' ) {
                $( '.mobmenu_enable_mm_woo_cart_page' ).show();
                $( '.mobmenu_enable_mm_woo_open_cart_menu' ).show();
                $( '.mobmenu_enable_mm_woo_menu_account' ).show();
                $( '.mobmenu_enable_mm_woo_cart_total_footer' ).show();
            } else {
                $( '.mobmenu_enable_mm_woo_cart_page' ).hide();
                $( '.mobmenu_enable_mm_woo_open_cart_menu' ).hide();
                $( '.mobmenu_enable_mm_woo_menu_account' ).hide();
                $( '.mobmenu_enable_mm_woo_cart_total_footer' ).hide();
            }
        }

        if ( $( "[data-link-id='cart-panel']" ).hasClass('active') ) {
            if ( $('#mobmenu_mm_woo_menu_bg_image').val() == '' ) {
                $('.mobmenu_mm_woo_menu_bg_opacity' ).hide();
                $('.mobmenu_mm_woo_menu_bg_image_size' ).hide();
                $('.mobmenu_mm_woo_menu_bg_gradient' ).show();
            } 
            else {
                $('.mobmenu_mm_woo_menu_bg_opacity' ).show();
                $('.mobmenu_mm_woo_menu_bg_image_size' ).show();
                $('.mobmenu_mm_woo_menu_bg_gradient' ).hide();
            }
            if ( $( '#mobmenu_mm_woo_menu_width_units' ).parent().find( '.button-primary' ).text() == 'Pixels' ) {
                $( '.mobmenu_mm_woo_menu_width' ).show();
                $( '.mobmenu_mm_woo_menu_width_percentage' ).hide();
            } else {
                $( '.mobmenu_mm_woo_menu_width' ).hide();
                $( '.mobmenu_mm_woo_menu_width_percentage' ).show();
            }
        }

        if ( $( "[data-link-id='product-filter']" ).hasClass('active') ) {
            if ( $( '#mobmenu_enable_mm_woo_product_filter' ).parent().find( '.button-primary' ).text() == 'Yes' ) {
                $('.mobmenu_enable_mm_woo_widget_product_filter' ).show();
                $('.mobmenu_mm_woo_filter_icon_font' ).show();
                $('.mobmenu_mm_woo_filter_icon_font_size' ).show();
                $('.mobmenu_shop_filter_top_margin' ).show();
                $('.mobmenu_mm_woo_shop_filter_location' ).show();
            }
            else {
                $('.mobmenu_enable_mm_woo_widget_product_filter' ).hide();
                $('.mobmenu_mm_woo_filter_icon_font' ).hide();
                $('.mobmenu_mm_woo_filter_icon_font_size' ).hide();
                $('.mobmenu_shop_filter_top_margin' ).hide();
                $('.mobmenu_mm_woo_shop_filter_location' ).hide();
            }
            
        }
        
    }

    setTimeout(function(){

         hideFieldsNotNeeded();

    }, 1000);
    
    var editorSettings = null;

    const tour = new Shepherd.Tour({
        defaultStepOptions: {
          classes: 'shadow-md bg-purple-dark',
          scrollTo: true,
          cancelIcon: {
            enabled: true,
          },
          useModalOverlay: true
        },
        confirmCancel: true,
      });
      
      //Construct the steps
      const steps = [{
              title: 'Welcome to Mobile Menu - Lets improve your website navigation: Step One',
              text: 'Lets choose the type of mobile header',
              attachTo: {
                  element: '#mobmenu_enabled_naked_header',
                  on: 'bottom'
              },
              classes: 'my-awesome-additional-class',
              buttons: [{
                  text: 'Next',
                  action: tour.next
              }]
          },
      
          {
              title: 'My Awesome Tour Guide : Step Two',
              text: 'This step is attached to the bottom of the <code>.entry-content</code> element. If no such element is found, the step appears in the center of the screen.',
              attachTo: {
                  element: '.entry-content',
                  on: 'bottom'
              },
              classes: 'my-awesome-additional-class',
              buttons: [{
                      text: 'Back',
                      action: tour.back
                  },
                  {
                      text: 'Finish',
                      classes: 'shepherd-button-close',
                      action: tour.hide
                  }
              ]
          },
      
      ]
      
      tour.addSteps(steps);
      
      // Initiate the tour
      //tour.start();

    $( '#mobmenu_hide_elements' ).after( '<a href="#" class="mobmenu-find-element">Find element</a>' );
    $('body').append('<iframe class="mobmenu-preview-iframe" scrolling="no" id="mobmenu-preview-iframe" width="380" height="650" >');
    setTimeout(function(){ 
        const urlParams = new URLSearchParams( window.location.search );
        var subMenu     = urlParams.get( 'tab' );

        if ( subMenu == null ) {
          subMenu = 'general-options';
        }
        $( '.nav-tab-wrapper .nav-tab' ).removeClass( 'active' );
        $( '[data-link-id=' + subMenu + ']' ).parent().parent().addClass( 'active' );
        $( '[data-link-id=' + subMenu + ']' ).click();

    }, 100);
    setTimeout(function(){ 
    // Initilialize the CodeMirror on the custom CSS option.
    if ( $('#mobmenu_custom_css').length > 0 && wp.codeEditor != undefined ) {
        editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};

        editorSettings.codemirror = _.extend(
        {},
        editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2,
                mode: 'css'

            }
        );
        wp.codeEditor.initialize($('#mobmenu_custom_css'), editorSettings);
    }

    // Initilialize the CodeMirror on the custom JS option.
    if ( $('#mobmenu_custom_js').length > 0 && wp.codeEditor != undefined ) {
        
   
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2,
                mode: 'javascript',
                lint: false
            }
        );
        wp.codeEditor.initialize($('#mobmenu_custom_js'), editorSettings);
    }
}, 2000);

    
    
    //Hide deprecated field.
    $( '#mobmenu_header_font_size' ).parent().parent().hide();
    $( '#mobmenu_enabled_logo' ).parent().parent().hide();

    var icon_key;

    $( '.mobmenu-icon-holder' ).each( function() {

        if ( $( this ).parent().find('input').length) {
            icon_key = $( this ).parent().find('input').val();
            $( this ).html( '<span class="mobmenu-item mob-icon-' + icon_key + '" data-icon-key="' + icon_key + '"></span>');
        }
    });

    $( document ).on( 'change', '.mm-form-table', hideFieldsNotNeeded );
         
    $( document ).on( 'click', '.mm-search-settings-results li' , function ( e ) {

        e.preventDefault();
        var dataTarget = jQuery( this ).find('a').attr( 'data-target-id' );
        jQuery('[data-link-id=' + dataTarget ).parent().click();
        jQuery('[data-link-id=' + dataTarget ).click();
        $( '.mm-search-settings-results' ).css( 'opacity', '0');
        $( '.mm-search-settings-results' ).html( '' );
        $( '#mm_search_settings').val('');
      });

        $( document ).on( "click", ".mobmenu-close-overlay" , function () {
        
            $( ".mobmenu-icons-overlay" ).fadeOut();
            $( ".mobmenu-icons-content" ).fadeOut();
            $( "#mobmenu_search_icons" ).attr( "value", "" );
            $( ".mobmenu-icons-content .mobmenu-item" ).removeClass( "mobmenu-hide-icons" );
            $( ".mobmenu-icons-remove-selected" ).hide();

            return false;
    
        });

        // Export settings.
        $( document ).on( 'click', '.export-mobile-menu-settings' , function () {
            location.href += '&mobmenu-action=download-settings';
            return false;
        });

        // Import settings.
        $( document ).on( 'click', '.import-mobile-menu-settings' , function () {
            location.href += '&mobmenu-action=import-settings';
            return false;
        });

        // Import Demos.
        $( document ).on( 'click', '.mobile-menu-import-demo' , function () {
            var demo = $( this ).attr( 'data-demo-id' );
            location.href += '&mobmenu-action=import-settings&demo=' + demo;
            return false;
        });

        $( document ).on( 'click', '.mobmenu-icons-remove-selected' , function () {

             $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'save_menu_item_icon',
                    menu_item_id: $( '.mobmenu-icons-content' ).attr( 'data-menu-item-id' ),
                    menu_item_icon: ""
                    },
               
                success: function( response ) {

                    $( '.mobmenu-item-selected' ).removeClass( 'mobmenu-item-selected' );
                    $( '.mobmenu-icons-remove-selected' ).hide();

                }
            });
        
            return false;
    
        });
        
        $( document ).on( 'click', ".toplevel_page_mobile-menu-options #mobmenu-modal-body .mobmenu-item" , function() {
            
            var icon_key = $( this ).attr( "data-icon-key" );
            $( ".mobmenu-icon-holder.selected-option" ).html( '<span class="mobmenu-item mob-icon-' + icon_key + '" data-icon-key="' + icon_key + '"></span>');
            $( ".mobmenu-close-overlay" ).trigger( "click" );
            $( ".mobmenu-icon-holder.selected-option" ).parent().find('input').val( icon_key );
            $( ".mobmenu-icon-holder.selected-option" ).removeClass( 'selected-option' );

        });

        $( document ).on( "click", ".nav-menus-php #mobmenu-modal-body .mobmenu-item" , function() {

            
            var icon_key = $( this ).attr( "data-icon-key" );
            
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "save_menu_item_icon",
                    menu_item_id: $( ".mobmenu-icons-content" ).attr( "data-menu-item-id" ),
                    menu_item_icon: icon_key
                    },
               
                success: function( response ) {
                                               
                    $( "#mobmenu-modal-body" ).append( response );   
                                                                 
                }
            });

            $( '#mobmenu-modal-body .mobmenu-item' ).removeClass( 'mobmenu-item-selected' );
            $( this ).addClass( 'mobmenu-item-selected' );
            $( '.mobmenu-icons-remove-selected' ).show();
                        
        });

      
        $( document ).on( 'mouseleave', '.mm-mobile-header-type img',  function() {
            $(this).removeClass( 'active');
        });

        $( document ).on( 'mouseenter', '.mm-mobile-header-type img',  function() {
            $(this).addClass( 'active');
        });

        $( document ).on( 'click', '.mm-mobile-header-type img',  function() {

            if ( $( this ).hasClass('hamburger-menu') ) {
                $( '#mobmenu_enabled_naked_header' ).next().click();
            } else {
                $( '#mobmenu_enabled_naked_header' ).next().next().click();
            }
        });

        $( document ).on( 'input', '#mobmenu_search_icons',  function() {

            var foundResults = false;

            if ( $( this ).val().length > 1 ) {

                var str = $( this ).val();
                str = str.toLowerCase().replace(
                    /\b[a-z]/g, function( letter ) {
                        return letter.toLowerCase();
                    } 
                );

                var txAux = str; 
                
                $( '#mobmenu-modal-body .mobmenu-item' ).each(
                    function() {

                        if ( $( this ).attr( 'data-icon-key' ).indexOf( txAux ) < 0 ) {
                            $( this ).addClass( "mobmenu-hide-icons" );
                        } else {
                            $( this ).removeClass( "mobmenu-hide-icons" );
                            foundResults = true;
                    
                        }

                    }
                );
            } else {
                $( '#mobmenu-modal-body .mobmenu-item' ).removeClass( 'mobmenu-hide-icons' );
            }

            if ( $( this ).val() === '' || !foundResults ) {
                $( '#mobmenu-modal-body .mobmenu-item' ).removeClass( 'mobmenu-hide-icons' );
                
            }

            if ( $( this ).val() !== '' &&  $( this ).val().length >= 3  && !foundResults ) {
                $( '#mobmenu-modal-body .mobmenu-item' ).addClass( 'mobmenu-hide-icons' );
            }

        });  

    $( document ).on( 'click', '.mobmenu-icon-picker' , function( e ) {
          
          e.preventDefault();
        
          var full_content = '';
          var selected_icon = '';
          var menu_id = 0;
          var id = 0;

          $( this ).prev().addClass( 'selected-option' );

          if (  $( '.mobmenu-icons-overlay' ).length ) {
                full_content = 'no';
          } else {
                full_content = 'yes';
          }

          $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: "get_icons_html",
                        menu_item_id: 0,
                        menu_id: 0,
                        full_content: full_content
                        },
               
                    success: function( response ) {
                        if ( full_content == 'yes' ) {
                                                    
                            $( 'body' ).append( response );   
                            selected_icon = $( '.mobmenu-icons-holder' ).attr( 'data-selected-icon' );
                                                
                        } else {

                            $( '.mobmenu-icons-overlay' ).fadeIn();
                            $( '.mobmenu-icons-content' ).fadeIn();
                            $( '#mobmenu-modal-body .mobmenu-item' ).removeClass( 'mobmenu-item-selected' );
                            selected_icon = $( response ).attr( 'data-selected-icon' );

                        }

                        if ( selected_icon != '' && selected_icon != undefined ) {
                            $( ".mob-icon-" + selected_icon ).addClass( "mobmenu-item-selected" );
                            $( ".mobmenu-icons-remove-selected" ).show();
                            //$( ".mobmenu-icon-picker" ).before( $( ".mob-icon-" + selected_icon ).html() );
                        }                       
                        
                    }

                });
    });

    $( document ).on( 'click', '.mm-scan-alerts a' , function( e ) {
        e.preventDefault();
        $( '[data-link-id=general-alerts]' ).click();
    });

    $( document ).on( 'click', '.mobmenu-item-settings' , function( e ) {
             
             e.preventDefault();

             var menu_item = $( this ).parent().parent().parent().parent();
             var menu_title = $(this).parent().parent().find('.menu-item-title').text();
             var menu_id = $( "#menu" ).val();
             var selected_icon = '';
             var full_content = '';
             var id = parseInt( menu_item.attr( 'id' ).match(/[0-9]+/)[0], 10);
             
             if (  $( ".mobmenu-icons-overlay" ).length ) {
                full_content = 'no';                                    
             } else {
                full_content = 'yes';
             }

             $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: "get_icons_html",
                        menu_item_id: id,
                        menu_id: menu_id,
                        menu_title: menu_title,
                        full_content: full_content
                        },
               
                    success: function( response ) {
                        if ( full_content == 'yes' ) {
                                                    
                            $( "body" ).append( response );   
                            selected_icon = $( ".mobmenu-icons-holder" ).attr( "data-selected-icon" );
                                                
                        } else {

                            $( ".mobmenu-icons-overlay" ).fadeIn();
                            $( ".mobmenu-icons-content" ).fadeIn();
                            $( "#mobmenu-modal-body .mobmenu-item" ).removeClass( "mobmenu-item-selected" );        
                            selected_icon = $( response ).attr( "data-selected-icon" );
                            $( "#mobmenu-modal-header h2").html( $( response ).attr( "data-title" ) );

                        }

                        if ( selected_icon != '' && selected_icon != undefined ) {
                            $( ".mob-icon-" + selected_icon ).addClass( "mobmenu-item-selected" );
                            $( ".mobmenu-icons-remove-selected" ).show();
                        }

                        $( "#mobmenu-modal-body" ).scrollTop( $( ".mobmenu-item-selected" ).offset() - 250 );
                        $( ".mobmenu-icons-content" ).attr( "data-menu-id", menu_id );
                        $( ".mobmenu-icons-content" ).attr( "data-menu-item-id" , id );
                    }

                });

                $( "#mobmenu_search_icons" ).focus();
    });

    $( document ).on( 'click', '.nav-tab-wrapper ul li', function(e) {

        e.preventDefault();
        var dataLinkId = $(this).attr( 'data-link-id' );

        $( '.nav-tab-wrapper .nav-tab li' ).removeClass( 'active' );
        $(this).addClass( 'active' );
        $( '.mobmenu-settings-panel-wrap .mm-form-table tr' ).hide();
        $( '.' + dataLinkId ).show();
        hideFieldsNotNeeded();
        const url = new URL(window.location);
        url.searchParams.set('tab', dataLinkId);
        window.history.pushState({}, '', url);

        return false;
    });

    $( document ).on( 'click', '.mobmenu-settings-panel-wrap .nav-tab-wrapper .nav-tab', function(e) {
        e.preventDefault();
        $( '.nav-tab-wrapper .nav-tab.active ul' ).hide();
        $( '.nav-tab-wrapper .nav-tab' ).removeClass( 'active' );
        $(this).find( 'ul' ).first().show();
        $(this).addClass( 'active' );
        $( '.nav-tab-wrapper .nav-tab li' ).removeClass( 'active' );
        $(this).find( 'ul li' ).first().addClass( 'active' );
        $( '.mobmenu-settings-panel-wrap .mm-form-table tr' ).hide();
        $( '.' + $(this).attr( 'data-tab-id' ) ).show();
        hideFieldsNotNeeded();
        
    });
    
    $( "#menu-to-edit li.menu-item" ).each( function() {

        var menu_item = $(this);
        var menu_id = $( "input#menu" ).val();
        var title = menu_item.find( ".menu-item-title" ).text();
        var id = parseInt(menu_item.attr( "id" ).match(/[0-9]+/)[0], 10);
        var selected_icon = '';
        var full_content = '';

        $( ".item-title", menu_item ).append( $( "<i class='mobmenu-item-settings mob-icon-mobile-2'><span>Set Icon</span></i>" ) );

    });

    $( document ).on( 'click', ' .mobmenu-find-element' , function( e ) {

        e.preventDefault();
        var href    = window.location.href;
        var index   = href.indexOf('/wp-admin');
        var homeUrl = href.substring(0, index);
        $( '#mobmenu-preview-iframe' ).attr( 'src', homeUrl + '/?mobmenu-action=find-element' );
        $( '#mobmenu-preview-iframe' ).show();
    });

});

}(jQuery));
// In Parent
function receivePickedElement (el) {
    var hideElements = jQuery( '#mobmenu_hide_elements').val().trim();
    if ( hideElements == '' ) { 
        jQuery( '#mobmenu_hide_elements').val( el );
    } else {
        jQuery( '#mobmenu_hide_elements').val( hideElements + ' , ' + el );
    }
  }