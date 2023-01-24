
  /*
    *
    *   Javascript Functions
    *   ------------------------------------------------
    *   WP Mobile Menu
    *   Copyright WP Mobile Menu 2018 - http://www.wpmobilemenu.com
    *
    */

    "use strict";
    function getSelector(el){
      var $el = jQuery(el);
  
      var id = $el.attr("id");
      if (id) { //"should" only be one of these if theres an ID
          return "#"+ id;
      }
  
      var selector = $el.parents()
                  .map(function() { return this.tagName; })
                  .get().reverse().join(" ");
  
      if (selector) {
          selector += " "+ $el[0].nodeName;
      }
  
      var classNames = $el.attr("class");
      if (classNames) {
          selector += "." + jQuery.trim(classNames).replace(/\s/gi, ".");
      }
  
      var name = $el.attr('name');
      if (name) {
          selector += "[name='" + name + "']";
      }
      if (!name){
          var index = $el.index();
          if (index) {
              index = index + 1;
              selector += ":nth-child(" + index + ")";
          }
      }
      return selector;
    }

    function enableMobileMenuElementPicker(){
      const p = new Picker({
          elm: document.getElementById('elm1'),
          mode: 'cover',
          excludeElmName: ['body'],
          events: [{
              key: 'contextmenu',
              fn(event) {
                  event.preventDefault();
              },
          }],
          onInit() {
          },
          onClick(event) {
            var selector = getSelector(event.target).toLowerCase();
            window.parent.receivePickedElement(selector);
            jQuery(selector).hide();
          },
          onHover(event) {
          },
      });

      document.getElementById('m_on').addEventListener('click', () => {
          p.on();
      });

      document.getElementById('m_off').addEventListener('click', () => {
          p.off();
      });

      document.getElementById('m_cover').addEventListener('click', () => {
          p.changeMode('cover');
      });

      document.getElementById('m_target').addEventListener('click', () => {
          p.changeMode('target');
      });

    }
    jQuery( document ).ready( function($) {

      const urlParams = new URLSearchParams( window.location.search );

      if ( urlParams.get( 'mobmenu-action' ) == 'find-element' ) {
        enableMobileMenuElementPicker();
      }

      function mobmenuOpenSubmenus( menu ) {
        var submenu = $(menu).parent().next();

        if ( $(menu).parent().next().hasClass( 'show-sub-menu' )  ) {
          $(menu).find('.show-sub-menu' ).hide();
          $(menu).toggleClass( 'show-sub');
        } else {
          if ( ! $( menu ).parents('.show-sub-menu').prev().hasClass('mob-expand-submenu') && submenu[0] !== $('.show-sub-menu')[0] && $( menu ).parent('.sub-menu').length <= 0 ) {
  
            $(menu).parent().find( '.show-submenu' ).first().hide().toggleClass( 'show-sub-menu' );
            $(menu).toggleClass( 'show-sub');
  
          }
        }

        if ( !$( menu ).parent().next().hasClass( 'show-sub-menu' ) ) {
          submenu.fadeIn( 'slow' );
        } else {  
          submenu.hide();
        }

        if ( ! $('body').hasClass('mob-menu-sliding-menus') ) {
          $( menu ).find('.open-icon').toggleClass('hide');
          $( menu ).find('.close-icon').toggleClass('hide');
        }

        submenu.toggleClass( 'show-sub-menu');
        

      }

      if ( $( 'body' ).find( '.mobmenu-push-wrap' ).length <= 0 &&  $( 'body' ).hasClass('mob-menu-slideout') ) {

        $( 'body' ).wrapInner( '<div class="mobmenu-push-wrap"></div>' );
        $( '.mobmenu-push-wrap' ).after( $( '.mobmenu-left-alignment' ).detach() );
        $( '.mobmenu-push-wrap' ).after( $( '.mobmenu-right-alignment' ).detach() );
        $( '.mobmenu-push-wrap' ).after( $( '.mob-menu-header-holder' ).detach() ); 
        $( '.mobmenu-push-wrap' ).after( $( '.mobmenu-footer-menu-holder' ).detach() ); 
        $( '.mobmenu-push-wrap' ).after( $( '.mobmenu-overlay' ).detach() ); 
        $( '.mobmenu-push-wrap' ).after( $( '#wpadminbar' ).detach() );

        if ( $('.mob-menu-header-holder' ).attr( 'data-detach-el' ) != '' ) {
          $( '.mobmenu-push-wrap' ).after( $(   $('.mob-menu-header-holder' ).attr( 'data-detach-el' ) ).detach() );
        }

      }
      // Double Check the the menu display classes where added to the body.
      var menu_display_type = $( '.mob-menu-header-holder' ).attr( 'data-menu-display' );

      if ( menu_display_type != '' && !$( 'body' ).hasClass( 'mob-menu-slideout' ) && !$( 'body' ).hasClass( 'mob-menu-slideout-over' ) && !$( 'body' ).hasClass( 'mob-menu-slideout-top' ) && !$( 'body' ).hasClass( 'mob-menu-overlay' ) ) {
        $( 'body' ).addClass( menu_display_type );
      }

      $( 'video' ).each( function(){
        if( 'autoplay' === $( this ).attr('autoplay') ) {
          $( this )[0].play();
        } 
      });

      var submenu_open_icon  = $( '.mob-menu-header-holder' ).attr( 'data-open-icon' );
      var submenu_close_icon = $( '.mob-menu-header-holder' ).attr( 'data-close-icon' );

      $( '.mobmenu-content .sub-menu' ).each( function(){

        $( this ).prev().append('<div class="mob-expand-submenu"><i class="mob-icon-' + submenu_open_icon + ' open-icon"></i><i class="mob-icon-' + submenu_close_icon + ' close-icon hide"></i></div>');

        if ( 0 < $( this ).parents( '.mobmenu-parent-link' ).length  ) {
          $( this ).prev().attr('href', '#');
        }

      });
      
      $( document ).on( 'click', '.mobmenu-parent-link .menu-item-has-children' , function ( e ) {
        
        if ( e.target.parentElement != this) return;
        
        e.preventDefault();
        $(this).find('a').find('.mob-expand-submenu').first().trigger('click');
        e.stopPropagation();
        
      });
      $( document ).on( 'click', '.show-nav-left .mobmenu-push-wrap,  .show-nav-left .mobmenu-overlay', function ( e ) { 
  
        e.preventDefault();
        $( '.mobmenu-left-bt' ).first().trigger( 'click' );
        e.stopPropagation();

      });
      
      $( document ).on( 'click', '.mob-expand-submenu' , function ( e ) {

        // Check if any menu is open and close it.
        if ( 1 == $( '.mob-menu-header-holder' ).attr( 'data-autoclose-submenus' ) && ! $(this).parent().next().hasClass( 'show-sub-menu' ) ) {
          if ( 0 < $( '.mob-expand-submenu.show-sub' ).length &&  $(this).parents('.show-sub-menu').length <= 0 ) {
            mobmenuOpenSubmenus( $( '.mob-expand-submenu.show-sub' ) );
          }
        }

        mobmenuOpenSubmenus( $(this) );
        e.preventDefault();
        e.stopPropagation();

      });

      $( document ).on( 'keyup', '.mobmenu-left-bt', function(e){
        if( e.type != 'click' && e.which != 13 || e.which == 9 ) {
          return;
        }

        mobmenuClosePanel( 'mobmenu-left-panel' );
        e.stopPropagation();
      });

      $( document ).on( 'keyup', '.mobmenu-right-bt', function(e){
        if( e.type != 'click' && e.which != 13 || e.which == 9 ) {
          return;
        }

        mobmenuClosePanel( 'mobmenu-right-panel' );
        e.stopPropagation();
      });

      
     
      $( document ).on( 'click', '.mobmenu-panel.show-panel .mob-cancel-button, .show-nav-right .mobmenu-overlay, .show-nav-left .mobmenu-overlay', function ( e ) { 
        
        

        e.preventDefault();
        mobmenuClosePanel( 'show-panel' );
        if ( $('body').hasClass('mob-menu-sliding-menus') ) {
          $( '.mobmenu-trigger-action .hamburger' ).toggleClass('is-active');
        }

      });

      $( document ).on( 'click', '.mobmenu-trigger-action', function(e){
        e.preventDefault();
        
        var targetPanel = $( this ).attr( 'data-panel-target' );
        
        if ( ! $( 'body' ).hasClass( 'show-nav-left' ) &&  ! $( 'body' ).hasClass( 'show-nav-right' )  ) {
          if ( 'mobmenu-filter-panel' !==  targetPanel ) {
            mobmenuOpenPanel( targetPanel );
          }
        }

      });

      $( document ).on( 'click', '.hamburger', function(e){
        var targetPanel = $(this).parent().attr('data-panel-target');
        e.preventDefault();
        e.stopPropagation();
        
        $(this).toggleClass( 'is-active' );
        
        setTimeout(function(){ 
          if ( $( 'body' ).hasClass('show-nav-left') ) {
            if ( $('body').hasClass('mob-menu-sliding-menus') ) {
              $( '.mobmenu-trigger-action .hamburger' ).toggleClass('is-active');
            }
            mobmenuClosePanel( targetPanel );
            
          } else {
            mobmenuOpenPanel( targetPanel );
          }
            
        }, 400);
        

      });
     
      $('.mobmenu a[href*="#"], .mobmenu-panel a[href*="#"]')
        // Remove links that don't actually link to anything
        .not('[href="#0"]')
        .on( 'click', function(event) {
          // On-page links  
  
        if (
          location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
          && 
          location.hostname == this.hostname
          &&
          $(this).parents('.mobmenu-content').length > 0
        ) {
          // Figure out element to scroll to.
          var target;

          try {
	          target = decodeURIComponent( this.hash );
          } catch(e) {
 	          target = this.hash;
          }

          $( 'html' ).css( 'overflow', '' );

          // Does a scroll target exist?
          if (target.length) {

            
          if ( 0 < $(this).parents('.mobmenu-left-panel').length ) {
            mobmenuClosePanel( 'mobmenu-left-panel' );
          } else {
            mobmenuClosePanel( 'mobmenu-right-panel' );
          }

            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

            $('body,html').animate({
              scrollTop: target.offset().top - $(".mob-menu-header-holder").height() - 50
            }, 1000);
          }
        }
      });
      function mobmenuClosePanel( target ) {

        $( '.' + target ).toggleClass( 'show-panel' );
        $( 'html' ).removeClass( 'show-mobmenu-filter-panel' );
        $( 'body' ).removeClass( 'show-nav-right' );
        $( 'body' ).removeClass( 'show-nav-left' );
        $( 'html' ).removeClass( 'mob-menu-no-scroll' ); 

        setTimeout(function(){
          $( '.mob-menu-sliding-menus [data-menu-level]' ).scrollTop( '0' );
          if ( 1 == $( '.mob-menu-header-holder' ).attr( 'data-autoclose-submenus' )  ) {
            $( '.mob-expand-submenu.show-sub' ).click();
            $( '.mobmenu-content .show-sub-menu' ).removeClass( 'show-sub-menu' );
          }
          
        }, 400);

      }
    
      function mobmenuOpenPanel( target) {
        $( '.mobmenu-content' ).scrollTop(0);
        $( 'html' ).addClass( 'mob-menu-no-scroll' ); 
    
        if ( $('.' + target ).hasClass( 'mobmenu-left-alignment' ) ) {
          $('body').addClass('show-nav-left');
        }
        if ( $('.' + target ).hasClass( 'mobmenu-right-alignment' ) ) {
          $('body').addClass('show-nav-right');
        }
    
        $('.' + target ).addClass( 'show-panel' );
    
      }
    });

window.Picker = class Picker {
  constructor(options = {}) {
      this.elm = options.elm || document.querySelector('body');
      this.mode = options.mode || 'target';
      this.excludeElmName = options.excludeElmName || [];
      this.switch = typeof options.switch === 'boolean' ? options.switch : true;

      this.events = options.events || [];
      this.onInit = options.onInit;
      this.onClick = options.onClick ? options.onClick.bind(this) : null;
      this.onHover = options.onHover ? options.onHover.bind(this) : null;


      // Internal handler
      this.fn_bind_clickHandle = null;
      this.fn_bind_hoverHandle = null;
      this.fn_bind_contextmenuHandle = null;
      this._init();
  }
  on() {
      this.switch = true;
  }
  off() {
      this.switch = false;
      this._removeTargetShowPos();
      this._removeCoverShowPos();
  }
  changeMode(mode) {
      let modeArr = ['cover', 'target'];
      if (modeArr.includes(mode)) {
          this.mode = mode;
          this._removeTargetShowPos();
          this._removeCoverShowPos();
      } else {
          console.error(`Mode error, only includes [ ${modeArr.join(" | ")} ]`);
      }
  }
  destroy() {
      this.events.forEach((eo) => {
          eo.fn_bind = eo.fn.bind(this);
          this.elm.removeEventListener(eo.key, this[`_${eo.key}_Handle`], false);
      });

      this.elm.removeEventListener('mouseover', this.fn_bind_hoverHandle, false);
      this.elm.removeEventListener('click', this.fn_bind_clickHandle, false);

      this._removeTargetShowPos();
      document.querySelector("#_picker_cover_wrap_box").remove();
  }
  _init() {
      let wrapDom = document.createElement('div');
      wrapDom.setAttribute("id", "_picker_cover_wrap_box");
      wrapDom.innerHTML = '<svg></svg>';
      document.body.appendChild(wrapDom);
      this._initEvent();
      this.onInit && this.onInit();
  }
  _initEvent() {
      this.events.forEach((eo) => {
          this[`_${eo.key}_Handle`] = (event) => {
              if (this._triggerEvent(event) === false) return;
              eo.fn && eo.fn(event);
          };
          eo.fn_bind = this[`_${eo.key}_Handle`].bind(this);
          this.elm.addEventListener(eo.key, this[`_${eo.key}_Handle`], false);
      });

      this.fn_bind_hoverHandle = this._hoverHandle.bind(this);
      this.fn_bind_clickHandle = this._clickHandle.bind(this);

      this.elm.addEventListener('mouseover', this.fn_bind_hoverHandle, false);
      this.elm.addEventListener('click', this.fn_bind_clickHandle, false);

  }
  _triggerEvent(event) {
      let tipsDom = document.querySelector("#_pick_tips_content");
      if (
          this.switch &&
          !this.excludeElmName.includes(event.target.localName.toLocaleLowerCase()) &&
          !(tipsDom ? tipsDom.contains(event.target) : 0)
      ) {
          event.stopPropagation();
          event.preventDefault();
          return true;
      } else {
          return false;
      }
  }
  _hoverHandle(event) {
      if (this._triggerEvent(event) === false) return;
      switch (this.mode) {
          case 'cover':
              this._coverShowPos(event);
              break;
          case 'target':
              this._targetShowPos(event);
              break;
      }
      this.onHover && this.onHover(event);
  }
  _clickHandle(event) {
      if (this._triggerEvent(event) === false) return;
      this.onClick && this.onClick(event);
  }
  _targetShowPos(event) {
      this._removeTargetShowPos();
      if (event.target.localName === 'body') return;
      event.target.classList.add("_picker_target_elm");
  }
  _removeTargetShowPos() {
      document.querySelectorAll("._picker_target_elm").forEach((elm) => {
          elm.classList.remove("_picker_target_elm");
      });
  }
  _coverShowPos(event) {
      let elm = event.target;
      let W_W = window.screen.availWidth;
      let W_H = window.screen.availHeight;
      let pos = elm.getBoundingClientRect();
      let p = {
          tX: pos.left > 0 ? pos.left : 0,
          tY: pos.top > 0 ? pos.top : 0,
          w: pos.right - pos.left,
          h: pos.bottom - pos.top,
      };
      let path_W = `M 0 0 h ${W_W} v ${W_H} h -${W_W} Z`;
      let path_box = `M ${p.tX} ${p.tY} h ${p.w} v ${p.h} h -${p.w} Z`;
      let elm_path1 = `<path d="${path_W} ${path_box}"></path>`;
      let elm_path2 = `<path d="${path_box}"></path>`;
      document.querySelector("#_picker_cover_wrap_box svg").innerHTML = elm_path1 + elm_path2;
  }
  _removeCoverShowPos() {
      document.querySelector("#_picker_cover_wrap_box svg").innerHTML = '';
  }
};
  

