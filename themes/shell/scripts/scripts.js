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
      $(this).toggleClass('menu-toggle-active');
    });

    // Display sidebar menu menu icon on hover
    $('#menu-toggle').hover(function() {
      $(this).addClass('menu-toggle-show');
    });

    // Remove active class from Work menu item
    $('.menu-item--expanded > a').removeClass('is-active');

    // Prevent click and toggle sub-menu
    $('.menu-item--expanded > a').click(function(e) {
      event.preventDefault(e);
      $(this).next('ul').toggleClass('expanded');
    });

    // Go back from submenu selection
    $('.menu-item--expanded .menu .back').click(function(){
      $(this).parent().removeClass('expanded');
    });

    // Floating form item labels
    $('input, textarea').keyup(function(){
      if($(this).val() == ""){
        $(this).addClass("empty");
      }
      else{
        $(this).removeClass("empty");
      }
    });

    if($('.form-item-mail input').length === 0){
      $('.form-item-mail').addClass('autofilled');
    }

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
        $('#menu-toggle').removeClass('menu-toggle-show');
      }
      else {
        $('#menu-toggle').addClass('menu-toggle-show');
      }
    }

    lastScrollTop = st;
  });

})(jQuery);
