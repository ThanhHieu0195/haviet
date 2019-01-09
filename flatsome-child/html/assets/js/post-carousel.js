$(document).ready(function(){
    'use strict';
    // slider-for
    $('.carousel-post .carousel-slide-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.carousel-slide-nav'
    });

    // slider-nav
    $('.carousel-post .carousel-slide-nav').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots:true,
        arrows: true,
        prevArrow: '<a class="arrow arrow--left" href="#" role="button"><span class="ion-ios-arrow-thin-left"></span></a>',
        nextArrow: '<a class="arrow arrow--right" href="#" role="button"><span class="ion-ios-arrow-thin-right"></span></a>',
        focusOnSelect: true,
        asNavFor: '.carousel-slide-for',
        responsive:[
        {
            breakpoint: 769,
            settings: {
                dots: true,
                arrows: false,
            }
        }
        ]
    });
});
