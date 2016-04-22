(function ($) {
  $( document ).ready(function( $ ) {

    // Creating main menu force close functionality
    $('h2#block-shell-main-menu-menu').click(function() {
      $('#block-shell-main-menu').removeClass('hidden-main-menu');
    });

    // Adding class to body content that contains an image
    $('.field--name-body p img').each(function() {
      $(this).parent().addClass('image-container');
    }); 

  });

  var lastScrollTop = 0;  
  $(window).scroll(function(event){
    var st = $(this).scrollTop();

    if (st > lastScrollTop && st > 0){
      $('#block-shell-main-menu').removeClass('hidden-main-menu');
    }
    else {
      $('#block-shell-main-menu').addClass('hidden-main-menu');
    }

    lastScrollTop = st;
  });

})(jQuery);
