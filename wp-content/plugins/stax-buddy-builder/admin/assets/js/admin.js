var BPBAdmin = BPBAdmin || {};

(function ($) {

    'use strict';

    BPBAdmin.main = {
        init: function() {
            BPBAdmin.main.initNotices();
        },

        initNotices: function() {
            $('div.notice.bpb-dismissable').on('click', '.bpb-dismiss-button', function (event) {
                event.preventDefault();
                $.post(ajaxurl, {
                    action: 'bpb_admin_notice_viewed',
                    notice_id: $(this).closest('.bpb-dismissable').data('notice_id')
                });
                var $wrapperElm = $(this).closest('.bpb-dismissable');
                $wrapperElm.fadeTo(100, 0, function () {
                    $wrapperElm.slideUp(100, function () {
                        $wrapperElm.remove();
                    });
                });
            });
        }
    }

    $(document).ready(function () {
        BPBAdmin.main.init();
    });

})(jQuery);