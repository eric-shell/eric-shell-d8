(function ($) {
  $( document ).ready(function( $ ) {

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
