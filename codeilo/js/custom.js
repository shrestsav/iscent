$(function() {
    //wow
    new WOW().init();
     
     //burgermenu
    $('#burger-menu').click(function() {
        $(this).parents('.navbar').toggleClass('fixed');
        $('#header').toggleClass('fixed-bg');
        // $('#header-wrap').addClass('fixed'); 
      setTimeout(function() {
      }, 100);
      
    });
    "use strict";

    var toggles = document.querySelectorAll(".c-hamburger");

    for (var i = toggles.length - 1; i >= 0; i--) {
      var toggle = toggles[i];
      toggleHandler(toggle);
    };

    function toggleHandler(toggle) {
      toggle.addEventListener( "click", function(e) {
        e.preventDefault();
        (this.classList.contains("is-active") === true) ? this.classList.remove("is-active") : this.classList.add("is-active");
      });
    }

   

    $('.feat-slider').slick({
      dots: true,
      arrows: true,
    });
   
    

      $('.responsive').slick({
          dots: true,
          infinite: false,
          speed: 300,
          arrows: false,
          slidesToShow: 6,
          slidesToScroll: 6,
          responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 4,
                infinite: true,
                dots: true
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
          ]
        });
      $('.testimonial-slider').slick({
          dots: true,
          infinite: false,
          speed: 300,
          arrows: false,
          slidesToShow: 3,
          slidesToScroll: 3,
          responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
          ]
        });
    

   
    $(window).scroll(function(){
      if($(this).scrollTop()>0)
      {
        $('.header-container').addClass('fixed-head');
        $('#header').addClass('fixed');
      }
      else{
        $('.header-container').removeClass('fixed-head');
        $('#header').removeClass('fixed');
      } 
  });

    if ($(window).width() < 991) {
      if($(this).scrollTop()>0) {
        $('.top-header').addClass('show');
      }
      $('.navbar-nav li a').click(function(){
        $('#burger-menu').toggleClass('is-active');
        $('.navbar').toggleClass('fixed');
        $('#header').toggleClass('fixed-bg');
      });
    }
    
    
    //file upload
    // $('.fileUpload input[type=file]').change(function() {
    //     var filename = $('.fileUpload input[type=file]').val().replace(/C:\\fakepath\\/i, '');
    //     $('.small-font').html(filename);
    // });
});