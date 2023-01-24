/* ================================================== */
/*  Webim Framework Shortcode Panel jQuery Function   */
/*================================================== */
/*jslint browser: true*/
/*global $, jQuery, send_to_editor, tb_remove, wp*/
jQuery(document).ready(function ($) {
    "use strict";
    $(document).ajaxComplete(function () {
         //send Buddypress Activity to editor
        $('#webim-send-buddypress-activity').unbind('click').click(function () {
		 send_to_editor('[webim_buddypress_activity'+ ' ' +'title="'+ $('#webim-option-buddypress-activity-title').val() + '"'
		 +' ' +'count="'+ $('#webim-option-buddypress-activity-number').val() + '"'
		 +']');
            tb_remove();
            return false;
        });
		//send Buddypress Activity Carousel to editor
        $('#webim-send-activity-carousel').unbind('click').click(function () {
		 send_to_editor('[webim_activity_carousel'+ ' ' +'count="'+ $('#webim-activity-carousel-number').val() + '"'
		 +' ' +'color="'+ $('#webim-activity-carousel-color').val() + '"'
		 +']');
            tb_remove();
            return false;
        });
		  //send Groups Carousel to editor
        $('#webim-send-buddypress-groups-carousel').unbind('click').click(function () {
		 send_to_editor('[webim_groups_carousel'+ ' ' +'title="'+ $('#webim-option-buddypress-groups-carousel-title').val() + '"'
        +' ' +'type="'+ $('#webim-option-buddypress-groups-carousel-type').val() + '"'	
        +' ' +'visible="'+ $('#webim-option-buddypress-groups-carousel-visible').val() + '"'	
		+' ' +'count="'+ $('#webim-option-buddypress-groups-carousel-number').val() + '"'
		 +']');
            tb_remove();
            return false;
        });
		 //send Groups Grid to editor
        $('#webim-send-buddypress-groups-grid').unbind('click').click(function () {
		 send_to_editor('[webim_groups_grid'+ ' ' +'title="'+ $('#webim-option-buddypress-groups-grid-title').val() + '"'
        +' ' +'type="'+ $('#webim-option-buddypress-groups-grid-type').val() + '"'	
        +' ' +'col="'+ $('#webim-option-buddypress-groups-per-row').val() + '"'	
		+' ' +'count="'+ $('#webim-option-buddypress-groups-grid-number').val() + '"'
		 +']');
            tb_remove();
            return false;
        });
		//send Members Carousel to editor
        $('#webim-send-buddypress-members-carousel').unbind('click').click(function () {
		 send_to_editor('[webim_members_carousel'+ ' ' +'title="'+ $('#webim-option-buddypress-members-carousel-title').val() + '"'
        +' ' +'type="'+ $('#webim-option-buddypress-members-carousel-type').val() + '"'	
		+' ' +'visible="'+ $('#webim-option-buddypress-members-carousel-visible').val() + '"'
		+' ' +'count="'+ $('#webim-option-buddypress-members-carousel-number').val() + '"'
		 +']');
            tb_remove();
            return false;
        });
		 //send Groups Grid to editor
        $('#webim-send-buddypress-members-grid').unbind('click').click(function () {
		 send_to_editor('[webim_members_grid'+ ' ' +'title="'+ $('#webim-option-buddypress-members-grid-title').val() + '"'
        +' ' +'type="'+ $('#webim-option-buddypress-members-grid-type').val() + '"'	
        +' ' +'col="'+ $('#webim-option-buddypress-members-per-row').val() + '"'	
		+' ' +'count="'+ $('#webim-option-buddypress-members-grid-number').val() + '"'
		 +']');
            tb_remove();
            return false;
        });
		//buddypress shortcode ends here
    });
});
/* ------------------------------------------------------------------------ */
/* EOF                                                                      */
/* ------------------------------------------------------------------------ */