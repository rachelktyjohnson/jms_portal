$(document).ready(function(){

  if (screen.width < 768) {
    $('.nav-links').hide();
  } else {
    $('.nav-links').show();
  }

  $('.trigram').click(function(){
    $('.nav-links').slideToggle();
  })

});
