(function($) {
  $(document).ready(function(){

    // initiate touch-friendly responsive navbar for multi-level menu
    $( '#nav li:has(ul)' ).doubleTapToGo();

    //+++Click function for tabs
    $('#miga-tabs a').click(function (e) {
        e.preventDefault()
        $(this).tab('show');
        $('#miga-tabs a').not(this).removeClass('active'); // important! because otherwise repeatable click on navigation links wouldn't work
    });

    /*
    $('.navbar-toggle, .navbar-toggle-static').on('click', function(){
      $('.navbar-toggle, .navbar-toggle-static').toggleClass('menu-opened');
      if($('.navbar-toggle, .navbar-toggle-static').hasClass('menu-opened')) {
          $('#menu-main-menu').show(350);
      } else {
          $('#menu-main-menu').hide(400);
      }
    });

    $('#menu-main-menu a').on('click', function(){
      $('.navbar-toggle, .navbar-toggle-static').toggleClass('menu-opened');
      if($('.navbar-toggle, .navbar-toggle-static').hasClass('menu-opened')) {
          $('#menu-main-menu').show(350);
      } else {
          $('#menu-main-menu').hide(400);
      }
    });*/

    // Filter Gallery
    $('.hidden').css('display','none');

    $('#filter button').each(function() {

      $(this).on("click", function(){

           var filter = $(this).attr('class');

        if ( $(this).attr('class') == 'all' ) {
           $('.hidden').contents().appendTo('#filter-cards').hide().show('slow');
           $( "#filter button" ).removeClass('active');
           $(this).addClass('active');
           $("#filter button").attr("disabled", false);
           $(this).attr("disabled", true);
        }
        else {
           $('#filter-cards .card').appendTo('.hidden');
           $('.hidden').contents().appendTo('#filter-cards').hide().show('slow');
           $('#filter-cards .card:not(.' + filter + ')').appendTo('.hidden').hide('slow');
           $( "#filter button" ).removeClass('active');
           $(this).addClass('active');
           $("#filter button").attr("disabled", false);
           $(this).attr("disabled", true);
        };

        });

    });

    //+++To display the active link on sub pages+++
    $(function(){

    var url = window.location.pathname,
        urlRegExp = new RegExp(url.replace(/\/$/,'') + "$"); // create regexp to match current url pathname and remove trailing slash if present as it could collide with the link in navigation in case trailing slash wasn't present there
        // now grab every link from the navigation
        $('nav a').each(function(){
            // and test its normalized href against the url pathname regexp
            if(urlRegExp.test(this.href.replace(/\/$/,''))){
                $(this).addClass('active');
            }
        });

    });

    //+++Change navbar background+++
    /*
    $(window).scroll(function(){
    if($('.navbar').offset().top > 50){
        $('.navbar-fixed-top').addClass('top-nav-collapse');
    } else {
        $('.navbar-fixed-top').removeClass('top-nav-collapse');
    }
    });*/

    // Fullpage nav
    $('#toggle').click(function() {
     $(this).toggleClass('active');
     $('#overlay').toggleClass('open');
    });

    //+++to hide collapsed nav on mobile after clicking a link+++
    $('nav li a').click(function(event) {
    $('#toggle').removeClass('active');
    $('#overlay').removeClass('open');
    });

    //+++Scrolling effect for navigation+++

    $(function(){
        $('.page-scroll a').bind('click', function(){
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top
            }, 2000, 'easeInOutExpo');
            //event.preventDefault();
        });
    });


    //+++Start scroll to the top+++

    var scrollButton= $('#scroll-top');

    //Check to see if the window is top if not then display button
    $(window).scroll(function(){
      $(this).scrollTop() > 700 ? scrollButton.fadeIn() : scrollButton.fadeOut();
    });

    //Click event to scroll to top
    scrollButton.click(function(){
      $('html, body').animate({scrollTop : 0},600);
    });


    // Lightbox for gallery images
    $('.gallery-icon a').featherlightGallery({
        previousIcon: '«',
        nextIcon: '»',
        galleryFadeIn: 900,
        galleryFadeOut: 600,
        openSpeed: 600
    });

    // Carousel for post type testimonials
    $('#testimonials').owlCarousel({

        autoPlay: 7000, //Set AutoPlay to 7 seconds

        items : 1,
        itemsDesktop : false,
        itemsDesktopSmall : false

    });

    // Display option "carousel" for miga theme
    $('#miga-slider').owlCarousel({

        autoPlay: 7000, //Set AutoPlay to 7 seconds

        items : 2,
        itemsDesktop : false,
        itemsDesktopSmall : false

    });

  });
})(jQuery);
