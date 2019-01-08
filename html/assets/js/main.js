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

    window.getCategoriesProductData = function(slug='') {
        let ajax_url = wpa_wcpb.ajaxurl;
        $.get(ajax_url, {action:'get_data_product', slug: slug}, function(json) {
            html = '';
            if (json.length > 0) {
                for(let i=0; i<json.length; i++) {  
                    let item = json[i];
                    html += `
                        <div class="col-sm-3 all corporate game item-list">
                            <div class="item">
                              <div class="product product-item-new product-item"><a class="product-item-link" href="https://didongviet.vn/iphone-5s-16gb-quoc-te-like-new.html">
                                  <div class="product-item-image">
                                    <div class="inner-image"><img alt="${item['post_title']}" src="https://didongviet.vn/pub/media/catalog/product//i/p/iphone-5s-vang-didongviet_1_2.jpg"><span class="ico ico-gift"></span><span class="flag flag-status-1t1 lblstatus"></span></div>
                                  </div>
                                  <div class="product-item-info">
                                    <h3>${item['post_title']}</h3>
                                    <div class="price-box price-final_price" data-role="priceBox" data-product-id="442"><span class="price-container price-final_price tax weee"><span class="price-wrapper" id="product-price-442" data-price-amount="2179000" data-price-type="finalPrice"><span class="price">2.179.000 ₫</span></span></span></div><span class="rtp"><i class="iconcom-txtstar"></i><i class="iconcom-txtstar"></i><i class="iconcom-txtstar"></i><i class="iconcom-txtstar"></i><i class="iconcom-txtunstar">      </i></span>
                                    <div class="product-attribute">
                                      <ul>
                                        <li>Màn hình: 4 inch</li>
                                        <li>HĐH: iOS 10</li>
                                        <li>CPU: Apple A7 2 nhân 64-bit</li>
                                        <li>RAM: 1 GB, ROM: 16 GB</li>
                                        <li>Camera: 8MP, Selfie: 1,2 MP</li>
                                        <li>Pin: 1560 mAh</li>
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
});

function myMap() {
    var mapProp= {
        center:new google.maps.LatLng(51.508742,-0.120850),
        zoom:5,
    };
    var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
}
