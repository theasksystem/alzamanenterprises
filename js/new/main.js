'use strict';
$(window).on('load', function() {
    $("#preloder").delay(200).fadeOut("slow");
    $(".loads").fadeOut(300, function() {
        $(".loads").animate({
            display: "none"
        }, function() {})
    })
});
(function($) {
    $('.main-menu').slicknav({
        prependTo: '.main-navbar .container',
        closedSymbol: '<i class="flaticon-right-arrow"></i>',
        openedSymbol: '<i class="flaticon-down-arrow"></i>'
    });
    $(".cart-table-warp, .product-thumbs").niceScroll({
        cursorborder: "",
        cursorcolor: "#afafaf",
        boxzoom: !1
    });
    $('.category-menu > li').hover(function(e) {
        $(this).addClass('active');
        e.preventDefault()
    });
    $('.category-menu').mouseleave(function(e) {
        $('.category-menu li').removeClass('active');
        e.preventDefault()
    });
    $('.set-bg').each(function() {
        var bg = $(this).data('setbg');
        $(this).css('background-image', 'url(' + bg + ')')
    });
    var hero_s = $(".hero-slider");
    hero_s.owlCarousel({
        loop: !0,
        margin: 0,
        nav: !0,
        items: 1,
        dots: !0,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        smartSpeed: 1200,
        autoHeight: !1,
        autoplay: !0,
        onInitialized: function() {
            var a = this.items().length;
            $("#snh-1").html("<span>1</span><span>" + a + "</span>")
        }
    }).on("changed.owl.carousel", function(a) {
        var b = --a.item.index,
            a = a.item.count;
        $("#snh-1").html("<span> " + (1 > b ? b + a : b > a ? b - a : b) + "</span><span>" + a + "</span>")
    });
    hero_s.append('<div class="slider-nav-warp"><div class="slider-nav"></div></div>');
    $(".hero-slider .owl-nav, .hero-slider .owl-dots").appendTo('.slider-nav');
    $('.product-slider').owlCarousel({
        loop: !0,
        nav: !0,
        dots: !1,
        margin: 5,
        autoplay: !0,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        responsive: {
            0: {
                items: 2,
            },
            480: {
                items: 2,
            },
            768: {
                items: 4,
            },
            1200: {
                items: 6,
            }
        }
    });
    $('.popular-services-slider').owlCarousel({
        loop: !0,
        dots: !1,
        margin: 5,
        autoplay: !0,
        nav: !0,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        responsive: {
            0: {
                items: 2,
            },
            768: {
                items: 3,
            },
            991: {
                items: 4
            }
        }
    });
    $('.panel-link').on('click', function(e) {
        $('.panel-link').removeClass('active');
        var $this = $(this);
        if (!$this.hasClass('active')) {
            $this.addClass('active')
        }
        e.preventDefault()
    });
    var rangeSlider = $(".price-range"),
        minamount = $("#minamount"),
        maxamount = $("#maxamount"),
        minPrice = rangeSlider.data('min'),
        maxPrice = rangeSlider.data('max');
    rangeSlider.slider({
        range: !0,
        min: minPrice,
        max: maxPrice,
        values: [minPrice, maxPrice],
        slide: function(event, ui) {
            minamount.val('$' + ui.values[0]);
            maxamount.val('$' + ui.values[1])
        }
    });
    minamount.val('$' + rangeSlider.slider("values", 0));
    maxamount.val('$' + rangeSlider.slider("values", 1));
    var proQty = $('.pro-qty');
    proQty.prepend('<span class="dec qtybtn">-</span>');
    proQty.append('<span class="inc qtybtn">+</span>');
    proQty.on('click', '.qtybtn', function() {
        var $button = $(this);
        var oldValue = $button.parent().find('input').val();
        if ($button.hasClass('inc')) {
            var newVal = parseFloat(oldValue) + 1
        } else {
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1
            } else {
                newVal = 0
            }
        }
        $button.parent().find('input').val(newVal)
    });
    $('.product-thumbs-track > .pt').on('click', function() {
        $('.product-thumbs-track .pt').removeClass('active');
        $(this).addClass('active');
        var imgurl = $(this).data('imgbigurl');
        var bigImg = $('.product-big-img').attr('src');
        if (imgurl != bigImg) {
            $('.product-big-img').attr({
                src: imgurl
            });
            $('.zoomImg').attr({
                src: imgurl
            })
        }
    });
   // $('.product-pic-zoom').zoom()
})(jQuery);
var acc = document.getElementsByClassName("accordion");
var i;
for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none"
        } else {
            panel.style.display = "block"
        }
    })
}