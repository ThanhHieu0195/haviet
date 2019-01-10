jQuery(document).ready(function($){
    $('.banner-carousel').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots:true,
        arrows: true,
        // autoplay: true,
        // autoplaySpeed: 2000,
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

   window.getCategoriesProductData = function(slug='') {
        let ajax_url = wpa_wcpb.ajaxurl;
        $.get(ajax_url, {action:'front', method:'get_data_product', slug: slug, filters: filters}, function(json) {
            html = '';
            if (json.length > 0) {
                for(let i=0; i<json.length; i++) {  
                    let item = json[i];
                    let ratingHtml = '';
                    let num_rate = 0
                    if (item['rating']) {
                       num_rate = item['rating'];
                    }

                     for(let i=0; i<5; i++) {
                            if (i < num_rate) {
                                ratingHtml += '<i class="iconcom-txtstar"></i>';
                            } else {
                                ratingHtml += '<i class="iconcom-txtunstar"></i>';
                            }
                        }

                    html += `
                        <div class="col-sm-3 all corporate game item-list">
                            <div class="item">
                              <div class="product product-item-new product-item"><a class="product-item-link" href="${item['permalink']}">
                                  <div class="product-item-image">
                                    <div class="inner-image"><img alt="${item['post_title']}" src="${item['thumbnail_url']}"><span class="ico ico-gift"></span><span class="flag flag-status-1t1 lblstatus"></span></div>
                                  </div>
                                  <div class="product-item-info">
                                    <h3>${item['post_title']}</h3>
                                    <div class="price-box price-final_price" data-role="priceBox">
                                    <span class="price-container price-final_price tax weee">
                                    <span class="price-wrapper" data-price-type="finalPrice">
                                    <span class="price">${item['price']} ₫</span></span></span></div><span class="rtp">
                                    ${ratingHtml}
                                    </span>
                                    <div class="product-attribute">
                                      <ul>
                                       ${item['post_excerpt']}
                                      </ul>
                                    </div>
                                  </div></a></div>
                            </div>
                          </div>
                    `;
                }
            }
            $('.js-sc-tab-product').html(html);
        });
    };

    window.getCategoriesProductData();

    var filters = [];
    $('.js-filter-cat li').on('click', function() {
        let key = $(this).data('filter');
        if (filters.indexOf(key) >= 0) {
            $(this).removeClass('active');
            filters.splice(filters.indexOf(key), 1);
        } else {
            $(this).addClass('active');
            filters.push(key);
        }
        getCategoriesProductData();
    });
      
      var $grid = $(".grid").isotope({
        itemSelector: ".all",
        percentPosition: true,
        masonry: {
          columnWidth: ".all"
        }
      })
});


function myMap() {
    var mapProp= {
        center:new google.maps.LatLng(51.508742,-0.120850),
        zoom:5,
    };
    var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
}


/* Add new */
$('.slick-carousel-image').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    dots:true,
    arrows: true,
    prevArrow: '<a class="arrow arrow--left" href="#" role="button">‹</span></a>',
    nextArrow: '<a class="arrow arrow--right" href="#" role="button">›</span></a>',
    responsive:[
    {
        breakpoint: 769,
        settings: {
            dots: true,
            arrows: false,
            slidesToShow: 1,
            slidesToShow: 1,
        }
    },
    {
        breakpoint: 480,
        settings: {
            dots: true,
            arrows: false,
            slidesToShow: 1,
            slidesToShow: 1,
        }
    }
    ]
});


jQuery(document).ready(function($){
    //  When user clicks on tab, this code will be executed
    $("#fs-dttabsl li").click(function() {
        //  First remove class "active" from currently active tab
        $("#fs-dttabsl li").removeClass('active');

        //  Now add class "active" to the selected/clicked tab
        $(this).addClass("active");

        //  Hide all tab content
        $(".fs-dttabi").hide();

        //  Here we get the href value of the selected tab
        var selected_tab = $(this).find("a").attr("href");

        //  Show the selected tab content
        $(selected_tab).fadeIn();

        //  At the end, we add return false so that the click on the link is not executed
        return false;
    });

    $('.slick-post-carousel').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
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
            slidesToShow: 2,
            slidesToShow: 2,
        }
    },
    {
        breakpoint: 480,
        settings: {
            dots: true,
            arrows: false,
            slidesToShow: 1,
            slidesToShow: 1,
        }
    }
    ]
});
});
/* End add new */
