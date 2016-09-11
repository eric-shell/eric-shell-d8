(function ($) {

  if($('body.path-frontpage').length !== 0){

    $(window).load(function(){
      $('#hero-imagery').addClass('animate');
      $('#hero-content').addClass('animate');
      $('#preloader').fadeOut('slow',function(){
        $(this).remove();
      });
    });
  }

  $(document).ready(function($) {

    // Open sidebar main menu
    $('#menu-toggle').click(function() {
      $('body').toggleClass('menu-active');
    });

    // Display sidebar menu menu icon on hover
    $('#menu-toggle').hover(function() {
      $('#menu-toggle').addClass('menu-toggle-active');
    });

    // Floating form item labels
    $('input, textarea').keyup(function(){
      if( $(this).val() == ""){
        $(this).addClass("empty");
      }
      else{
        $(this).removeClass("empty");
      }
    });

    // Autogrow textareas
    $('textarea').autogrow();

    // Prettify syntax highlighting
    if($('.field--name-field-body .prettyprint').length) {
      $('body').attr('onload', 'prettyPrint()');
    }

  });

  // Menu button visibility on scroll
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
