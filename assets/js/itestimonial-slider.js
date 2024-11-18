jQuery(document).ready(function ($) {
    $.each(itestimonialInstances, function (index, instance) {
        let attributes = instance.settings;
        var defaultSettings = {
            dots: false,
            infinite: true,
            speed: attributes.speed > 0 ? attributes.speed : 300,
            autoplay: attributes.autoplay,
            autoplaySpeed: attributes.autoplayspeed > 0 ? attributes.autoplayspeed : 3000,
            centerMode: false,
            centerPadding: '0',
            cssEase: 'ease-in-out',
            // easing: 'cubic-bezier(0,1.15,1,.06)',
            waitForAnimate: true,
            pauseOnHover: true,
            pauseOnFocus: false,
            draggable: false,
            swipeToSlide: false,
            swipe: false,
            touchMove: false,
            arrows: false,
            variableWidth: false,
            adaptiveHeight: false,
            accessibility: false,
            focusOnSelect: false,
            variableWidth: false,
            mobileFirst: false,
            edgeFriction: 0.25,
            prevArrow: '<svg class="slick-prev custom-arrow" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" /> </svg>',
            nextArrow: '<svg class="slick-next custom-arrow" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" /> </svg>'
        };

        let slickSettings = $.extend({}, defaultSettings, {
            slidesToShow: parseInt(attributes.slidestoshow) > 0 ? parseInt(attributes.slidestoshow) : 1,
            slidesToScroll: parseInt(attributes.slidestoscroll) > 0 ? parseInt(attributes.slidestoscroll) : 1,
            rows: 1,
            dots: attributes.dots,
            arrows: attributes.arrows,
        });

        let gridSettings = $.extend({}, defaultSettings, {
            slidesToScroll: parseInt(attributes.slidestoscroll) > 0 ? parseInt(attributes.slidestoscroll) : 1,
            slidesPerRow: parseInt(attributes.slidesperrow) > 0 ? parseInt(attributes.slidesperrow) : 1,
            rows: parseInt(attributes.rows) > 1 ? parseInt(attributes.rows) : 2,
            dots: attributes.dots,
            arrows: attributes.arrows,
            adaptiveHeight: true,
        });

        let selector = '#itestimonial-instance-' + index;

        if (attributes.view === 'slider') {
            $(selector).slick(slickSettings);
        } else if (attributes.view === 'grid') {
            $(selector).slick(gridSettings);
            let maxHeight = 0;
            $('.slick-slider.itestimonial-grid .itestimonial-content').each(function () {
                let height = $(this).outerHeight();
                if (height > maxHeight) {
                    maxHeight = height;
                }
            });
            $('.slick-slider.itestimonial-grid .itestimonial-content').css('height', maxHeight + 'px');
            $('.slick-slide').each(function () {
                $(this).children('div').addClass('itestimonial-drag');
            });
        }
    });

    var $lastExpandedContent = null;

    $('.itestimonial-read-more').on('click', function () {
        let $this = $(this);
        let $content = $this.siblings('.itestimonial-content');
        let $isExpanded = $content.hasClass('expanded');
        $content.css('max-height', $isExpanded ? '' : $content.prop('scrollHeight') + 'px');
        $this.text($isExpanded ? $this.data('rm') : $this.data('rl'));
        $content.toggleClass('expanded');

        if ($lastExpandedContent && $lastExpandedContent.length && $lastExpandedContent[0] !== $content[0]) {
            $lastExpandedContent.removeClass('expanded');
            $lastExpandedContent.css('max-height', '');
            $lastExpandedContent.siblings('.itestimonial-read-more').text($lastExpandedContent.siblings('.itestimonial-read-more').data('rm'));
        }

        $lastExpandedContent = $content;
    });
});
