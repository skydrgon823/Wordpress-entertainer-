/* global _envatoMarket, tb_click */

/**
 * Envato Market sripts.
 *
 * @since 1.0.0
 */
(function( $ ) {
  'use strict';
  var dialog, envatoMarket = {

    cache: {},

    init: function() {
      this.bindEvents();
    },

    bindEvents: function() {
      var self = this;

      self.addItem();
      self.removeItem();
      self.tabbedNav();

      $( document ).on( 'click', '.envato-card a.thickbox', function() {
        tb_click.call( this );
        $( '#TB_title' ).css({ 'background-color': '#23282d', 'color': '#cfcfcf' });
        return false;
      });
    },

    addItem: function() {
      $( document ).on( 'click', '.add-envato-market-item', function( event ) {
        var id = 'envato-market-dialog-form';
        event.preventDefault();

        if ( 0 === $( '#' + id ).length ) {
          $( 'body' ).append( wp.template( id ) );
        }

        dialog = $( '#' + id ).dialog({
          autoOpen: true,
          modal: true,
          width: 350,
          buttons: {
            Save: {
              text: _envatoMarket.i18n.save,
              click: function() {
                var form = $( this ),
                  request, token, input_id;

                form.on( 'submit', function( event ) {
                  event.preventDefault();
                });

                token = form.find( 'input[name="token"]' ).val();
                input_id = form.find( 'input[name="id"]' ).val();

                request = wp.ajax.post( _envatoMarket.action + '_add_item', {
                  nonce: _envatoMarket.nonce,
                  token: token,
                  id: input_id
                });

                request.done(function( response ) {
                  var item = wp.template( 'envato-market-item' ),
                    card = wp.template( 'envato-market-card' ),
                    button = wp.template( 'envato-market-auth-check-button' );

                  $( '.nav-tab-wrapper' ).find( '[data-id="' + response.type + '"]' ).removeClass( 'hidden' );

                  response.item.type = response.type;
                  $( '#' + response.type + 's' ).append( card( response.item ) ).removeClass( 'hidden' );

                  $( '#envato-market-items' ).append( item({
                    name: response.name,
                    token: response.token,
                    id: response.id,
                    key: response.key,
                    type: response.type,
                    authorized: response.authorized
                  }) );

                  if ( 0 === $( '.auth-check-button' ).length ) {
                    $( 'p.submit' ).append( button );
                  }

                  dialog.dialog( 'close' );
                  envatoMarket.addReadmore();
                });

                request.fail(function( response ) {
                  var template = wp.template( 'envato-market-dialog-error' ),
                    data = {
                      message: ( response.message ? response.message : _envatoMarket.i18n.error )
                    };

                  dialog.find( '.notice' ).remove();
                  dialog.find( 'form' ).prepend( template( data ) );
                  dialog.find( '.notice' ).fadeIn( 'fast' );
                });
              }
            },
            Cancel: {
              text: _envatoMarket.i18n.cancel,
              click: function() {
                dialog.dialog( 'close' );
              }
            }
          },
          close: function() {
            dialog.find( '.notice' ).remove();
            dialog.find( 'form' )[0].reset();
          }
        });
      });
    },

    removeItem: function() {
      $( document ).on( 'click', '#envato-market-items .item-delete', function( event ) {
        var self = this, id = 'envato-market-dialog-remove';
        event.preventDefault();

        if ( 0 === $( '#' + id ).length ) {
          $( 'body' ).append( wp.template( id ) );
        }

        dialog = $( '#' + id ).dialog({
          autoOpen: true,
          modal: true,
          width: 350,
          buttons: {
            Save: {
              text: _envatoMarket.i18n.remove,
              click: function() {
                var form = $( this ),
                  request, id;

                form.on( 'submit', function( submit_event ) {
                  submit_event.preventDefault();
                });

                id = $( self ).parents( 'li' ).data( 'id' );

                request = wp.ajax.post( _envatoMarket.action + '_remove_item', {
                  nonce: _envatoMarket.nonce,
                  id: id
                });

                request.done(function() {
                  var item = $( '.col[data-id="' + id + '"]' ),
                    type = item.find( '.envato-card' ).hasClass( 'theme' ) ? 'theme' : 'plugin';

                  item.remove();

                  if ( 0 === $( '#' + type + 's' ).find( '.col' ).length ) {
                    $( '.nav-tab-wrapper' ).find( '[data-id="' + type + '"]' ).addClass( 'hidden' );
                    $( '#' + type + 's' ).addClass( 'hidden' );
                  }

                  $( self ).parents( 'li' ).remove();

                  $( '#envato-market-items li' ).each(function( index ) {
                    $( this ).find( 'input' ).each(function() {
                      $( this ).attr( 'name', $( this ).attr( 'name' ).replace( /\[\d\]/g, '[' + index + ']' ) );
                    });
                  });

                  if ( 0 !== $( '.auth-check-button' ).length && 0 === $( '#envato-market-items li' ).length ) {
                    $( 'p.submit .auth-check-button' ).remove();
                  }

                  dialog.dialog( 'close' );
                });

                request.fail(function( response ) {
                  var template = wp.template( 'envato-market-dialog-error' ),
                    data = {
                      message: response.message ? response.message : _envatoMarket.i18n.error
                    };

                  dialog.find( '.notice' ).remove();
                  dialog.find( 'form' ).prepend( template( data ) );
                  dialog.find( '.notice' ).fadeIn( 'fast' );
                });
              }
            },
            Cancel: {
              text: _envatoMarket.i18n.cancel,
              click: function() {
                dialog.dialog( 'close' );
              }
            }
          }
        });
      });
    },

    tabbedNav: function() {
      var self = this,
        $wrap = $( '.about-wrap' );

      // Hide all panels
      $( 'div.panel', $wrap ).hide();

      var tab = self.getParameterByName( 'tab' ),
        hashTab = window.location.hash.substr( 1 );

      // Listen for the click event.
      $( document, $wrap ).on( 'click', '.nav-tab-wrapper a', function() {

        // Deactivate and hide all tabs & panels.
        $( '.nav-tab-wrapper a', $wrap ).removeClass( 'nav-tab-active' );
        $( 'div.panel', $wrap ).hide();

        // Activate and show the selected tab and panel.
        $( this ).addClass( 'nav-tab-active' );
        $( 'div' + $( this ).attr( 'href' ), $wrap ).show();

        self.maybeLoadhealthcheck();

        return false;
      });

      if ( tab ) {
        $( '.nav-tab-wrapper a[href="#' + tab + '"]', $wrap ).click();
      } else if ( hashTab ) {
        $( '.nav-tab-wrapper a[href="#' + hashTab + '"]', $wrap ).click();
      } else {
        $( 'div.panel:not(.hidden)', $wrap ).first().show();
      }

    },

    getParameterByName: function( name ) {
      var regex, results;
      name = name.replace( /[\[]/, '\\[' ).replace( /[\]]/, '\\]' );
      regex = new RegExp( '[\\?&]' + name + '=([^&#]*)' );
      results = regex.exec( location.search );
      return null === results ? '' : decodeURIComponent( results[1].replace( /\+/g, ' ' ) );
    },

    maybeLoadhealthcheck: function() {
      // We only load the health check ajax call when the envato-market-healthcheck div is visible on the page.
      var $healthCheckOutput = $( '.envato-market-healthcheck' );
      if( $healthCheckOutput.is( ':visible') ) {
        $healthCheckOutput.text('Loading...');

        // Use our existing wp.ajax.post pattern from above to call the healthcheck API endpoint
        var request = wp.ajax.post( _envatoMarket.action + '_healthcheck', {
          nonce: _envatoMarket.nonce
        });

        request.done(function( response ) {
          if( response && response.limits ) {
            var $healthCheckUL = $( '<ul></ul>' );
            var limits = Object.keys( response.limits );
            for( var i = 0; i < limits.length; i++ ) {
              var $healthCheckLI = $( '<li></li>' );
              var healthCheckItem = response.limits[limits[i]];
              $healthCheckLI.addClass( healthCheckItem.ok ? 'healthcheck-ok' : 'healthcheck-error' );
              $healthCheckLI.attr( 'data-limit', limits[i] );
              $healthCheckLI.append( '<span class="healthcheck-item-title">' + healthCheckItem.title + '</span>' );
              $healthCheckLI.append( '<span class="healthcheck-item-message">' + healthCheckItem.message + '</span>' );
              $healthCheckUL.append( $healthCheckLI );
            }
            $healthCheckOutput.html( $healthCheckUL );
          }else{
            window.console.log( response );
            $healthCheckOutput.text('Health check failed to load. Please check console for errors.');
          }
        });

        request.fail(function( response ) {
          window.console.log( response );
          $healthCheckOutput.text('Health check failed to load. Please check console for errors.');
        });
      }
    }

  };

  $( window ).on('load', function() {
    envatoMarket.init();
  });

})( jQuery );
