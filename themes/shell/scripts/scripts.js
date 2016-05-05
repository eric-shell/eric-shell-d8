(function ($) {

  $(document).ready(function($) {

    // Toggle main menu
    $('#menu-toggle').click(function() {
      $('body').toggleClass('menu-active');
    });

  });

  var lastScrollTop = 0;  
  $(window).scroll(function(event){
    var st = $(this).scrollTop();

    if (st > lastScrollTop && st > 0){
      $('#menu-toggle').removeClass('menu-toggle-active');
    }
    else {
      $('#menu-toggle').addClass('menu-toggle-active');
    }

    lastScrollTop = st;
  });

})(jQuery);
