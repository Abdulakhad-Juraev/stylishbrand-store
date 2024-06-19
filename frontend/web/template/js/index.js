var amountScrolled = 200;

$(window).scroll(function() {
  if ( $(window).scrollTop() > amountScrolled ) {
    $('.navigation').addClass('active');
  } else {
    $('.navigation').removeClass('active');
  }
});

var btn = $('#button');

$(window).scroll(function() {
  if ($(window).scrollTop() > 400) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  $('html, body').animate({scrollTop:0}, '300');
});


$(function () {

  // $(".dark").click(function() {
  //   $("body").toggleClass("my-dark")
  //   $(this).toggleClass("active")
  // })
  
  $(".hb").click(function() {
    $(".my-menu").toggleClass("active")
  })

});


//scrollreveal

window.sr = ScrollReveal({ reset: false });
sr.reveal('.ab', { origin: 'bottom', opacity: 0, delay: 300, distance: '100px', duration: 1500 });
sr.reveal('.abb', { origin: 'bottom', opacity: 0,scale: 0.5, delay: 50, distance: '100px', duration: 1500 });
sr.reveal('.rot1', { origin: 'bottom', opacity: 0, delay: 400, 
rotate: {
  x: 0,
  z: 40
},
scale: 0.5,
duration: 2000 });
sr.reveal('.rot2', { origin: 'bottom', opacity: 0, delay: 1000, 
rotate: {
  x: 0,
  z: 40
},
scale: 0.5,
duration: 2100 });
sr.reveal('.rot3', { origin: 'bottom', opacity: 0, delay: 1200, 
rotate: {
  x: 0,
  z: 40
},
scale: 0.5,
duration: 2200 });





AOS.init({
  once: true,
});


