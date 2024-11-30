// SWIPER LOGIC
var swiper = new Swiper('.swiper-container', {
   effect : "coverflow",
   grabCursor :true,
   centeredSlides : true,
   coverflowEffect : {
    rotate:0,
    srtretch :0,
    depth: 100,
    modifier :3,
    slideShadows :true
   },
   pagination: {
      el: '.swiper-pagination',
      clickable: true, 
  },
   navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
   },
   loop:true,
})


// Dropdown toggle functionality
document.getElementById('dropdown-toggle').addEventListener('click', function () {
   const dropdownMenu = document.getElementById('dropdown-menu');
   dropdownMenu.classList.toggle('active');
});

// Close dropdown if clicked outside
document.addEventListener('click', function (event) {
   const dropdown = document.querySelector('.dropdown');
   const dropdownMenu = document.getElementById('dropdown-menu');

   if (!dropdown.contains(event.target)) {
       dropdownMenu.classList.remove('active');
   }
});


document.getElementById('prev').addEventListener('click', function() {
   swiper.slidePrev(); // Navigasi ke slide sebelumnya
});

document.getElementById('next').addEventListener('click', function() { 
   swiper.slideNext(); // Navigasi ke slide berikutnya
});