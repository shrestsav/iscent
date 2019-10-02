$(function() {

    new WOW().init();

    $('#slider').ulslide({
        effect: {
            type: 'crossfade', // slide or fade
            axis: 'x', // x, y
            showCount: 0,
            distance: 0
        },
        pager: '#slide-pager a',
        nextButton: '.right_btn',
        prevButton: '.left_btn',
        duration: 1000,
        mousewheel: false,
        autoslide: 5000,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
    });

    $(".tabs").tabs();

    $('.owl').owlCarousel({
        loop: true,
        margin: 40,
        navigation: true,
        autoplay: true,
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        items: 2,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: true,
                margin: 10
            },
            300: {
                items: 1,
                nav: false,
                margin: 0
            },
            400: {
                items: 1,
                nav: false,
                margin: 0
            },
            500: {
                items: 2,
                nav: false,
                margin: 20
            },
            600: {
                items: 2,
                nav: false,
                margin: 20
            },
            700: {
                items: 2,
                nav: false,
                margin: 30,
            },
            800: {
                items: 2,
                nav: false,
                margin: 40,
            }
        }
    });
    $(".lft").click(function() {
        var owl = $(".owl1").data('owlCarousel');
        owl.next() // Go to next slide
    });
    $(".rgt").click(function() {
        var owl = $(".owl1").data('owlCarousel');
        owl.prev() // Go to previous slide
    });



    $("#menu").mmenu({
        "extensions": ["effect-menu-zoom", "effect-panels-zoom", "pagedim-black", "theme-dark"],
        "offCanvas": {
            "position": "right"
        },
        "counters": true,
        "iconPanels": true,
        "navbars": [{
            "position": "top"
        }]
    });
$(window).scroll(function(){
var sticky = $('.header'),
            scroll = $(window).scrollTop();

        if (scroll >= 100) sticky.addClass('sticky');
        else sticky.removeClass('sticky');

        if ($(this).scrollTop()> 200) {
        $('#toTop').fadeIn();
    } else {
        $('#toTop').fadeOut();
    }
});

$("#toTop").click(function() {
    $("html, body").animate({scrollTop: 0}, 1000);
 });


});


setTimeout("end_session()", 1000000);

function end_session() {

    $.ajax({
        url: '../ajax_call.php?page=SessionStart',
        type: 'post'
    }).done(function(data) {
        if(data == '1'){
        console.log("session start");
        setTimeout("end_session()", 1000000);
        }   
    });

}