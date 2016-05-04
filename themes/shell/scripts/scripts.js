(function ($) {

  $( document ).ready(function( $ ) {

    // Toggle main menu
    $('#menu-toggle').click(function() {
      $('body, header').toggleClass('menu-active');
    });

  });

})(jQuery);
