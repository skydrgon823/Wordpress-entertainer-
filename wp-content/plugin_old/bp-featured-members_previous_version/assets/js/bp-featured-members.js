jQuery(document).ready(function(){

    var jq = jQuery;

    jq(document).on( 'click', 'div.bp-featured-members-button a', function() {
        var $this       = jq(this),
            user_id   = $this.data('user-id'),
            nonce       = $this.data('nonce');

        jq.post(
            ajaxurl, {
                action: 'bp_process_featured_members_status',
                user_id: user_id,
                _wpnonce: nonce
            }, function(resp) {
                if ( resp.success ) {
                    $this.text( resp.data.btn_label );
                }
            }, 'json'
        );
        return false;
    });

    // for slider.
    if ( typeof jq.fn.lightSlider !== 'undefined' ) {
        jq( '.featured-members-slider').each( function() {
          var $this = jq(this);

          //read settings from element
          var data = $this.data();

            var boolProps = ['loop', 'autoWidth', 'useCSS', 'auto', 'pauseOnHover',
                'keyPress', 'controls', 'rtl', 'adaptiveHeight', 'vertical',
                'pager', 'gallery', 'enableTouch', 'enableDrag', 'freeMove'];

            for ( var i = 0; i< boolProps.length; i++ ) {

              if( data[ boolProps[i] ] !== undefined ) {
                  data[ boolProps[i] ] = !! data[ boolProps[i] ];
              }
          }
          console.log(data);

          $this.lightSlider( data );
        } );
    }
});