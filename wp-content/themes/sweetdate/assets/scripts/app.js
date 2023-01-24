// DEFAULTS FORM FOUNDATION FRAMEWORK, GENERALY YOU DON'T NEED TO EDIT THIS

;(function ($, window, undefined) {
    'use strict';

    var $doc = $(document),
        Modernizr = window.Modernizr;

    $(document).ready(function () {
        $.fn.foundationAlerts ? $doc.foundationAlerts() : null;
        $.fn.foundationButtons ? $doc.foundationButtons() : null;
        $.fn.foundationAccordion ? $doc.foundationAccordion() : null;
        $.fn.foundationNavigation ? $doc.foundationNavigation() : null;
        $.fn.foundationTopBar ? $doc.foundationTopBar() : null;
        //if($("html").hasClass('touch') || ($("html").hasClass('no-touch') && $(window).width() > 480)) {
        $.fn.foundationCustomForms ? $doc.foundationCustomForms() : null;
        //}

        $.fn.foundationMediaQueryViewer ? $doc.foundationMediaQueryViewer() : null;
        $.fn.foundationTabs ? $doc.foundationTabs({callback: $.foundation.customForms.appendCustomMarkup}) : null;
        //$.fn.foundationTooltips         ? $doc.foundationTooltips() : null;
        //$.fn.foundationMagellan         ? $doc.foundationMagellan() : null;
        $.fn.foundationClearing ? $doc.foundationClearing() : null;

        $.fn.placeholder ? $('input, textarea').placeholder() : null;
    });

    // UNCOMMENT THE LINE YOU WANT BELOW IF YOU WANT IE8 SUPPORT AND ARE USING .block-grids
    // $('.block-grid.two-up>li:nth-child(2n+1)').css({clear: 'both'});
    // $('.block-grid.three-up>li:nth-child(3n+1)').css({clear: 'both'});
    // $('.block-grid.four-up>li:nth-child(4n+1)').css({clear: 'both'});
    // $('.block-grid.five-up>li:nth-child(5n+1)').css({clear: 'both'});

    // Hide address bar on mobile devices (except if #hash present, so we don't mess up deep linking).
    if (Modernizr.touch && !window.location.hash) {
        $(window).load(function () {
            setTimeout(function () {
                window.scrollTo(0, 1);
            }, 0);
        });
    }

})(jQuery, this);


// EDIT BELOW THIS LINE FOR CUSTOM EFFECTS, TWEACKS AND INITS

/*--------------------------------------------------

Custom overwiev:

1.  Mobile specific			
2.  CarouFredSel - Responsive Carousel
3.  Titles - Loading slow effect
//4.  Fit text - Resize big titles on small device platform
5.  Video - Show/hide promo video
6.  Twitter Feed
7.  PrettyPhoto
8.  Mailchimp integration
9.  Button - Go Up
10. Circular Match Bar
11. Accordion
12. Toggle Message Form
13. Foundation Orbit - Profile Sliders
14.	Foundation Orbit - Blog Sliders
15.	Cross-browser tweaks ;)
16. Inview images loading
17. Ajax search
18. Ajax login
19. Carousels
---------------------------------------------------*/

//You should bind all actions in document.ready, because you should wait till the document is fully loaded.
jQuery(document).ready(function ($) {
    forMobile();
    loadingSlow();
    hideVideo();
    videoModal();
    goupPage();

    // START - disable match animation for IE8
    if ($("html").hasClass("lt-ie9")) {
        //js for IE8 if needed
    } else {
        circularMatch();
        circularMembers();
    }
    // END - disable match animation for IE8

    accordionInfo();
    toggleForm();
    orbitProfileSlider();
    orbitBlogSlider();
    initCrossBrowser();
    if (!isMobile()) {
        initInviewImages();
    }
    tosCheck();
    kleoAjaxLogin();
    searchButton();
    if ($(".profile-thumbs").length > 0) {
        profilesCarousel();

        $(document).on('click.fndtn', '.tabs a', function (e) {
            $('.profile-thumbs').trigger('updateSizes');
        });

    }
    if ($(".feature-stories").length > 0) {
        storiesCarousel();

        $(document).on('click.fndtn', '.tabs a', function (e) {
            $('.feature-stories').trigger('updateSizes');
        });
    }

    new jQuery.kleoAjaxSearch();

});


/***************************************************
 1. Mobile specific
 ***************************************************/
function forMobile() {
    if (isMobile()) {
        //Hide quote you don't need
        jQuery('.hide-on-mobile').hide();
    } else {
        // Initialize Quovolver - Quote rotator
        jQuery('.testimonials-carousel li').quovolver(400, 7000);
        //Initialize tooltips
        jQuery(document).foundationTooltips();
    }
}

function isMobile() {
    if (
        (navigator.userAgent.indexOf('iPhone') != -1) || (navigator.userAgent.indexOf('iPod') != -1)
        || (navigator.userAgent.indexOf('iPad') != -1)
        || ((navigator.userAgent.indexOf('Android') != -1) && (navigator.userAgent.indexOf('Mobile') != -1))
    ) {
        return true;
    } else {
        return false;
    }
}

/***************************************************
 3. Titles - Loading slow effect
 ***************************************************/
function loadingSlow() {
    if (jQuery('#call-to-actions h1').length) {
        jQuery('#call-to-actions h1').animate({opacity: 1}, 1200, function () {
            jQuery('#call-to-actions .lead').animate({opacity: 1}, 1500);
        });
    } else {
        jQuery('#call-to-actions .lead').animate({opacity: 1}, 1500);
    }
}


/***************************************************
 5. Video - Show/hide promo video
 ***************************************************/
function hideVideo() {
    jQuery(".videoLoad iframe").attr('src', '');
    jQuery(".kleo-video").slideUp(700, function () {
        jQuery(".kleo-video .videoLoad").fadeOut('slow');
    });
    jQuery('.button.play').removeClass('disabled');
}

jQuery("a.play").click(function (e) {
    var videoId = jQuery(this).attr('data-videoid');
    e.preventDefault();
    jQuery(".videoLoad").hide();
    var videotoload = jQuery(this).attr("href"),
        viewportHeight = (jQuery(window).height()) - 533,
        scrolltovideo = jQuery("#markerPoint-" + videoId).offset().top;
    if (viewportHeight > 100) {
        scrolltovideo = scrolltovideo - (viewportHeight / 2);
    } else {
        scrolltovideo = scrolltovideo - 55;
    }

    jQuery('.play').show();
    jQuery(this).addClass('disabled');

    jQuery("html, body").animate({
        scrollTop: scrolltovideo
    }, 500, function () {
        jQuery("#" + videoId + " iframe").attr('src', videotoload);
        jQuery("#" + videoId + " .videoLoad").fadeIn('slow');
        jQuery("#" + videoId).slideDown(900);
    });
});

jQuery(".videoClose").click(function (e) {
    e.preventDefault();
    hideVideo();
});

function videoModal() {
    jQuery('a.video-modal').on('click', function () {
        var id = jQuery(this).data('id');
        var url = jQuery(this).data('url');
        if (!jQuery('#reveal-' + id).length) {
            jQuery('body').append(
                '<div id="reveal-' + id + '" class="reveal-modal video-modal xlarge">' +
                '<div id="' + id + '" class="kleo-video">' +
                '<div class="central">' +
                '<a href="#" class="videoClose"><i class="icon-off icon-2x"></i></a>' +
                '<div class="videoLoad flex-video widescreen">' +
                '<iframe src=""></iframe>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>'
            );
        }
        jQuery("#reveal-" + id).reveal();
        jQuery("#" + id + ".kleo-video").show();
        jQuery("#" + id + " iframe").attr('src', url);
        jQuery("#" + id + " .videoLoad").fadeIn('slow');
        jQuery("#" + id + " .videoClose").on('click', function () {
            jQuery('#reveal-' + id).trigger('reveal:close');
            return false;
        });

        return false;
    });
}

/***************************************************
 7. PrettyPhoto - Replace 'data-rel' with 'rel'
 'rel' attribute it's not a valid tag anymore
 ***************************************************/
jQuery('a[data-rel]').each(function () {
    jQuery(this).attr('rel', jQuery(this).data('rel'));
});

//PrettyPhoto settings
jQuery("a[rel^='prettyPhoto']").prettyPhoto({
    animation_speed: 'fast', /* fast/slow/normal */
    slideshow: false, /* false OR interval time in ms */
    autoplay_slideshow: false, /* true/false */
    opacity: 0.80, /* Value between 0 and 1 */
    show_title: true, /* true/false */
    allow_resize: true, /* Resize the photos bigger than viewport. true/false */
    default_width: 500,
    default_height: 344,
    counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
    theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
    hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
    wmode: 'opaque', /* Set the flash wmode attribute */
    autoplay: true, /* Automatically start videos: True/False */
    modal: false, /* If set to true, only the close button will close the window */
    overlay_gallery: false, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
    keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
    deeplinking: false,
    social_tools: false
});


/***************************************************
 9. Button - Go Up
 ***************************************************/
function goupPage() {
    // hide #btnGoUp first
    jQuery("#btnGoUp").hide();

    // fade in #btnGoUp
    jQuery(function () {
        jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() > 100) {
                jQuery('#btnGoUp').fadeIn();
            } else {
                jQuery('#btnGoUp').fadeOut();
            }
        });

        // scroll body to 0px on click
        jQuery('#btnGoUp').click(function () {
            jQuery('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });
}


/***************************************************
 10. Circular Match Bar
 ***************************************************/
function circularMatch() {
    jQuery('.greenCircle').one('inview', function (event, visible) {

        var matchBg = kleoFramework.mainColor;
        var matchFg = '#ffffff';
        if (kleoFramework.hasOwnProperty("bpMatchBg") && kleoFramework.bpMatchBg != '') {
            matchBg = kleoFramework.bpMatchBg;
        }
        if (kleoFramework.hasOwnProperty("bpMatchFg") && kleoFramework.bpMatchFg != '') {
            matchFg = kleoFramework.bpMatchFg;
        }

        jQuery(".greenCircle").knob({
            'min': 0,
            'max': 100,
            'readOnly': true,
            'width': 60,
            'height': 60,
            //'fgColor': '#0296c0',
            'bgColor': matchBg,
            'fgColor': matchFg,
            'dynamicDraw': true,
            'thickness': 0.10,
            'tickColorizeValues': true

        })
    });
}

function circularMembers() {
    jQuery('.pinkCircle').one('inview', function (event, visible) {

        var totalMembers = 100;
        if (kleoFramework.hasOwnProperty("totalMembers")) {
            totalMembers = kleoFramework.totalMembers;
        }

        jQuery(".pinkCircle").knob({
            'min': 0,
            'max': kleoFramework.totalMembers,
            'readOnly': true,
            'width': 60,
            'height': 60,
            'fgColor': kleoFramework.mainColor,
            'dynamicDraw': true,
            'thickness': 0.10,
            'tickColorizeValues': true
        })
    });
}


/***************************************************
 11. Accordion
 ***************************************************/
function accordionInfo() {
    var cur_stus;
    var tabNo = 0;

    //close all on default
    jQuery('.accordion .accordion-content').hide();
    jQuery('.accordion .accordion-title').attr('stus', '');

    //open default data
    jQuery('.accordion').each(function () {
        if (jQuery(this).attr('data-default-opened') !== undefined) {
            tabNo = jQuery(this).attr('data-default-opened') - 1;
        }
        if (jQuery(this).attr('data-default-opened') != 'none') {
            jQuery('.accordion-title:eq(' + tabNo + ')', this).attr('stus', 'active').addClass('active').next().slideDown();
        }

    });

    jQuery('.accordion .accordion-title').click(function () {
        myContext = jQuery(this).closest(".accordion");
        cur_stus = jQuery(this).attr('stus');
        if (cur_stus != "active") {
            //reset everything - content and attribute
            jQuery('.accordion-content', myContext).slideUp();
            jQuery('.accordion-title', myContext).attr('stus', '').removeClass('active');

            //then open the clicked data
            jQuery(this).next().slideDown();
            jQuery(this).attr('stus', 'active').addClass('active');
        }
        //Remove else part if do not want to close the current opened data
        else {
            jQuery(this).next().slideUp();
            jQuery(this).attr('stus', '').removeClass('active');
        }
        return false;
    });
}


/***************************************************
 12. Toggle Message Form
 ***************************************************/
function toggleForm() {
    //if already visible - slideDown
    //if not visible - slideUp
    jQuery('.toggle-title').click(
        function () {
            jQuery(this).stop(true, true).next('.toggle-content').slideToggle();
            return false;
        }
    );
}


/***************************************************
 13. Foundation Orbit - Profile Sliders
 ***************************************************/
function orbitProfileSlider() {
    jQuery('#profile-slider').orbit({
        animation: 'fade',        // fade, horizontal-slide, vertical-slide, horizontal-push
        animationSpeed: 800,                // how fast animtions are
        timer: false,        // true or false to have the timer
        resetTimerOnClick: false,           // true resets the timer instead of pausing slideshow progress
        advanceSpeed: 4000,     // if timer is enabled, time between transitions
        pauseOnHover: false,      // if you hover pauses the slider
        startClockOnMouseOut: false,    // if clock should start on MouseOut
        startClockOnMouseOutAfter: 1000,    // how long after MouseOut should the timer start again
        directionalNav: false,     // manual advancing directional navs
        captions: false,       // do you want captions?
        captionAnimation: 'fade',     // fade, slideOpen, none
        captionAnimationSpeed: 800,   // if so how quickly should they animate in
        bullets: true,       // true or false to activate the bullet navigation
        bulletThumbs: true,    // thumbnails for the bullets
        bulletThumbLocation: '',    // location from this file where thumbs will be
        afterSlideChange: function () {
        },   // empty function
    });
}


/***************************************************
 14. Foundation Orbit - Blog Sliders
 ***************************************************/
function orbitBlogSlider() {
    jQuery('.blog-slider').orbit({
        animation: 'horizontal-push',        // fade, horizontal-slide, vertical-slide, horizontal-push
        animationSpeed: 800,                // how fast animtions are
        timer: false,        // true or false to have the timer
        resetTimerOnClick: false,           // true resets the timer instead of pausing slideshow progress
        advanceSpeed: 4000,     // if timer is enabled, time between transitions
        pauseOnHover: true,      // if you hover pauses the slider
        startClockOnMouseOut: false,    // if clock should start on MouseOut
        startClockOnMouseOutAfter: 1000,    // how long after MouseOut should the timer start again
        directionalNav: true,     // manual advancing directional navs
        captions: false,       // do you want captions?
        captionAnimation: 'fade',     // fade, slideOpen, none
        captionAnimationSpeed: 800,   // if so how quickly should they animate in
        bullets: false,       // true or false to activate the bullet navigation
        bulletThumbs: false,    // thumbnails for the bullets
        bulletThumbLocation: '',    // location from this file where thumbs will be
        afterSlideChange: function () {
        },   // empty function
    });
}


/***************************************************
 15. Cross-browser tweacks
 ***************************************************/
function initCrossBrowser() {

    //Background Size - Hack for IE8 but used for all browsers
    jQuery(".profile-slider-wrapp ul.orbit-bullets li.has-thumb").css({backgroundSize: "cover"});

    //Small fix for foundation top-bar
    jQuery(".top-bar li.name").click(function () {
        jQuery(".top-bar .toggle-topbar").trigger('click');
        return false;
    });
}


/***************************************************
 16. Inview images loading
 ***************************************************/

function initInviewImages() {
    jQuery(".activity-list img.avatar").each(function (i) {
        jQuery(this).attr('data-src', jQuery(this).attr('src'));
        jQuery(this).attr('src', kleoFramework.blank_img);
        jQuery(this).closest('.activity-avatar').attr('style', 'border:none;-webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none;');
    });

    jQuery('.activity-list img.avatar').one('inview', function (event, visible) {
        if (visible) {
            var element = jQuery(this);
            var delayInterval = 250; // milliseconds
            jQuery(this).fadeOut(function () {
                element.attr('src', jQuery(this).attr('data-src'));
                element.fadeIn();
                jQuery(this).closest('.activity-avatar').removeAttr('style');
            });
        }
    });
}

function tosCheck() {
    jQuery("#register_form, #register_form_front, #signup_form").submit(function () {
        if (jQuery(".tos_register", this).length > 0) {
            if (!jQuery(".tos_register", this).is(":checked")) {
                alert(kleoFramework.tosAlert);
                return false;
            }
        }
    });
}

/***************************************************
 17. Ajax search
 ***************************************************/
function searchButton() {
    jQuery('.search-trigger').click(function () {
        if (jQuery('#ajax_search_container').hasClass('searchHidden')) {
            jQuery('#ajax_search_container').removeClass('searchHidden').addClass('show_search_pop');
        }
        return false;
    });
}

jQuery.kleoAjaxSearch = function (options) {
    var defaults = {
        delay: 350,                //delay in ms for typing
        minChars: 3,               //no. of characters after we start the search
        scope: '#header'
    };

    this.options = jQuery.extend({}, defaults, options);
    this.scope = jQuery(this.options.scope);
    this.body = jQuery("body");
    this.timer = false;
    this.doingSearch = false;
    this.lastVal = "";
    this.bind_ev = function () {
        this.scope.on('keyup', '#ajax_s', jQuery.proxy(this.test_search, this));
        this.body.on('mousedown', jQuery.proxy(this.hide_search, this));
    };
    this.test_search = function (e) {
        clearTimeout(this.timer);
        if (e.currentTarget.value.length >= this.options.minChars && this.lastVal != jQuery.trim(e.currentTarget.value)) {
            this.timer = setTimeout(jQuery.proxy(this.search, this, e), this.options.delay);
        }
    };
    this.hide_search = function (e) {
        element = jQuery(e.target);
        if (!element.is('#ajax_search_container') && element.parents('#ajax_search_container').length == 0) {
            jQuery('#ajax_search_container').addClass('searchHidden').removeClass('show_search_pop');
        }
    };
    this.search = function (e) {
        var form = jQuery("#ajax_searchform"),
            results = jQuery(".kleo_ajax_results"),
            values = form.serialize(),
            buton = jQuery("#kleo_ajaxsearch"),
            icon = jQuery("#kleo_ajaxsearch").html();

        values += "&action=kleo_ajax_search";

        //if last result had no items
        if (!this.doingSearch && results.find('.ajax_not_found').length && e.currentTarget.value.indexOf(this.lastVal) != -1) return;

        this.lastVal = e.currentTarget.value;

        jQuery.ajax({
            url: kleoFramework.ajaxurl,
            type: "POST",
            data: values,
            beforeSend: function () {
                buton.html('<i class="icon icon-refresh icon-spin"></i>');
                buton.attr('disabled', true);
                this.doingSearch = true;
            },
            success: function (response) {
                if (response == 0) response = "";

                results.html(response);
            },
            complete: function () {
                jQuery("#kleo_ajaxsearch").html(icon);
                buton.attr('disabled', false);
                this.doingSearch = false;
            }
        });
    };

    //do search...
    this.bind_ev();
}


/***************************************************
 18. Ajax login
 ***************************************************/
function kleoAjaxLogin() {
    jQuery('form#login_form', '#login_panel').on('submit', function (e) {
        jQuery('#kleo-login-result').show().html(kleoFramework.loadingmessage);
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: kleoFramework.ajaxurl,
            data: {
                'action': 'kleoajaxlogin',
                'log': jQuery('form#login_form #username').val(),
                'pwd': jQuery('form#login_form #password').val(),
                'rememberme': (jQuery('form#login_form #rememberme').is(':checked') ? true : false),
                'security': jQuery('form#login_form #security').val(),
                'g-recaptcha-response': jQuery('[name="g-recaptcha-response"]').val()

            },
            success: function (data) {
                jQuery('#kleo-login-result').html(data.message);
                if (data.loggedin == true) {
                    if (data.redirecturl == null) {
                        document.location.reload();
                    } else {
                        document.location.href = data.redirecturl;
                    }
                }
            },
            complete: function () {

            },
            error: function () {
                jQuery('form#login_form, #login_panel').off('submit');
                jQuery("#login_form").submit();
            }
        });
        e.preventDefault();
    });
}

/***************************************************
 19. Carousels
 ***************************************************/
function profilesCarousel() {
    jQuery('.profile-thumbs').carouFredSel({
        responsive: true,
        width: '100%',
        mousewheel: true,
        //swipe: false,
        scroll: {
            items: 1,
            duration: 500,
            fx: "directscroll",
            //timeoutDuration: 500,
            //pauseOnHover: 'immediate',
        },
        auto: {
            pauseOnHover: 'resume'
        },
        prev: {
            button: function () {
                return jQuery(this).closest(".kleo_members_carousel").find(".profile-thumbs-prev");
            }
        },
        next: {
            button: function () {
                return jQuery(this).closest(".kleo_members_carousel").find(".profile-thumbs-next");
            }
        },
        items: {
            //width: 120,
            height: '100%',	//	optionally resize item-height
            visible: {
                min: 3,
                max: 8
            }
        }
    });
    jQuery('.profile-thumbs').swipe({
        excludedElements: "button, input, select, textarea, .noSwipe",
        swipeLeft: function () {
			jQuery('.profile-thumbs').trigger('next', 1);
        },
        swipeRight: function () {
			jQuery('.profile-thumbs').trigger('prev', 1);
        },
        tap: function (event, target) {

        	var el = jQuery( target );

        	if ( ! el.is('a') ) {
        		el = el.closest('a');
        		if (el.length > 0) {
        			el = jQuery(el[0]);
				} else  {
        			el = null;
				}
			}

            if ( el !== null && el.attr('href') !== '#') {
                window.location.href= jQuery(el).attr('href');
            }

        }
    });
}

function storiesCarousel() {
    jQuery('.feature-stories').carouFredSel({
        responsive: true,
        width: '100%',
        //mousewheel: true,
        swipe: {
            //onMouse: true,
            onTouch: true
        },
        scroll: {
            items: 1,
            duration: 500,
            fx: "directscroll"
        },
        auto: false,
        prev: {
            button: function () {
                return jQuery(this).closest(".kleo-carousel").find(".story-prev");
            }
        },
        next: {
            button: function () {
                return jQuery(this).closest(".kleo-carousel").find(".story-next");
            }
        },
        items: {
            width: 310,
            height: "variable",
            visible: {
                min: 1,
                max: 3
            }
        }
    });
}

function ucwords(str) {
    return (str + '').replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
        return $1.toUpperCase();
    });
}
