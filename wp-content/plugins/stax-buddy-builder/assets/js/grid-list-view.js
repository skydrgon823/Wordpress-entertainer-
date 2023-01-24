var BPB_GridListView = BPB_GridListView || {};

(function ($) {

    // USE STRICT
    "use strict";

    BPB_GridListView.fn = {
        init: function () {

            var $listToggle = $('.bpb-listing-type');
            var forceClass = 'grid-one-force';
            if( $listToggle.length > 0 ) {

                BPB_GridListView.fn.setStorage( 'bp-' + $listToggle.first().parent().data('component'), 'bpb-list-hidden' );

                var savedState = BPB_GridListView.fn.getStorage('bp-members', 'bpb-list-mode' );
                if (savedState !== false) {
                    $listToggle.find('.bpb-list-change').removeClass('bpb-active');
                    $listToggle.find('.bpb-'+ savedState +'-mode').addClass('bpb-active');
                }
            }

            $('body').on('click', '.bpb-list-change', function (e) {
                e.preventDefault();

                var membersList = $('#members-list');
                var groupsList = $('#groups-list');
                var mode = $(this).data('list-type');

                if (mode === 'list' ) {
                    if (membersList.length) {
                        membersList.addClass(forceClass);
                    }
                    if (groupsList.length) {
                        groupsList.addClass(forceClass);
                    }
                } else {
                    if (membersList.length) {
                        membersList.removeClass(forceClass);
                    }

                    if (groupsList.length) {
                        groupsList.removeClass(forceClass);
                    }
                }

                $('.bpb-list-change').removeClass('bpb-active');
                $(this).addClass('bpb-active');

                BPB_GridListView.fn.setStorage( 'bp-' + $(this).parent().data('component'), 'bpb-list-mode', mode );

                return false;
            });
        },
        getStorage: function( type, property ) {
            var store = sessionStorage.getItem( type );

            if ( store ) {
                store = JSON.parse( store );
            } else {
                store = {};
            }

            if ( undefined !== property ) {
                return store[property] || false;
            }

            return store;
        },

        setStorage: function( type, property, value ) {
            var store = this.getStorage( type );

            if ( undefined === value && undefined !== store[ property ] ) {
                delete store[ property ];
            } else {
                // Set property.
                store[ property ] = value;
            }

            sessionStorage.setItem( type, JSON.stringify( store ) );

            return sessionStorage.getItem( type ) !== null;
        }
    };

    $(document).ready(BPB_GridListView.fn.init());

})(jQuery);
