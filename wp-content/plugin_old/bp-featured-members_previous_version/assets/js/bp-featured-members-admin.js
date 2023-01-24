jQuery(document).ready(function($){

    $(document).on('change','.bpfm-widget-admin-widget-view-options', function ( $el ) {
        var $this = $(this);
        var $options = $this.parents( '.widget-inside' ).find('.bpfm-widget-admin-widget-slide-options');
        if ( 'slider' == $this.val() ) {
            $options.show();
        } else {
            $options.hide();
        }
    });

});