jQuery(document).ready(function($){
    $('.banner-carousel').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots:true,
        arrows: true,
        autoplay: true,
        autoplaySpeed: 2000,
        prevArrow: '<a class="arrow arrow--left" href="#" role="button">‹</span></a>',
        nextArrow: '<a class="arrow arrow--right" href="#" role="button">›</span></a>',
        responsive:[
        {
            breakpoint: 769,
            settings: {
                dots: true,
                arrows: false,
                slidesToShow: 1,
            }
        },
        {
            breakpoint: 480,
            settings: {
                dots: true,
                arrows: false,
                slidesToShow: 1,
            }
        }
        ]
    });

    $('.nav-tabs').slick({
        slidesToShow: 7,
        slidesToScroll: 7,
        dots:false,
        arrows: true,
        prevArrow: '<a class="arrow arrow--left" href="#" role="button">‹</span></a>',
        nextArrow: '<a class="arrow arrow--right" href="#" role="button">›</span></a>',
        responsive:[
        {
            breakpoint: 769,
            settings: {
                dots: true,
                arrows: false,
                slidesToShow: 3,
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 480,
            settings: {
                dots: true,
                arrows: false,
                slidesToShow: 2,
                slidesToShow: 2,
            }
        }
        ]
    });
    
    // $('.filters ul li').click(function(){
    //     $('.filters ul li').removeClass('active');
    //     $(this).addClass('active');
        
    //     var data = $(this).attr('data-filter');
    //     $grid.isotope({
    //       filter: data
    //     })
    //   });
      
    //   var $grid = $(".grid").isotope({
    //     itemSelector: ".all",
    //     percentPosition: true,
    //     masonry: {
    //       columnWidth: ".all"
    //     }
    //   });
});

;

function myMap() {
    var mapProp= {
        center:new google.maps.LatLng(51.508742,-0.120850),
        zoom:5,
    };
    var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
}
