$(document).ready(function(){
    if($(window).width() <=768) {
        $('.sc-carousel-layout-2 .carousel-slide-for .nav-tabs').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: true,
            prevArrow: '<a class="arrow arrow--left" href="#" role="button"><span class="ion-ios-arrow-left"></span></a>',
            nextArrow: '<a class="arrow arrow--right" href="#" role="button"><span class="ion-ios-arrow-right"></span></a>',
        });

    }
//     'use strict';
//     // slider-for
//     // slider-nav
    // $('.sc-carousel-layout-2 .carousel-slide-nav .tab-content').slick({
    //     slidesToShow: 1,
    //     slidesToScroll: 1,
    //     dots:false,
    //     arrows: false,
    //     focusOnSelect: true,
    //     asNavFor: '.carousel-slide-for',
    //     responsive:[
    //     {
    //         breakpoint: 768,
    //         settings: {
    //             dots: true,
    //             arrows: false,
    //         }
    //     }
    //     ]
    // });
});
