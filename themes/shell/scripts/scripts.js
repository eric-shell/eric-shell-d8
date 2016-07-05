(function ($) {

  $(window).load(function(){
    $('#preloader').fadeOut('slow',function(){$(this).remove();});
  });

  $(document).ready(function($) {

    // Toggle main menu
    $('#menu-toggle').click(function() {
      $('body').toggleClass('menu-active');
    });

    $('#menu-toggle').hover(function() {
      $('#menu-toggle').addClass('menu-toggle-active');
    });

    // Floating form item labels
    $('main input[value=""], main textarea').addClass('empty');
      $('input, textarea').keyup(function(){
        if( $(this).val() == ""){
          $(this).addClass("empty");
        }
        else{
          $(this).removeClass("empty");
        }
    });

  });

  var lastScrollTop = 0;  
  $(window).scroll(function(event){
    var st = $(this).scrollTop();

    if($('body.menu-active').length === 0){
      if (st > lastScrollTop && st > 0){
        $('#menu-toggle').removeClass('menu-toggle-active');
      }
      else {
        $('#menu-toggle').addClass('menu-toggle-active');
      }
    }

    lastScrollTop = st;
  });

})(jQuery);
