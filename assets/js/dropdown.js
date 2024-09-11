document.addEventListener('DOMContentLoaded', function() {
   const navMenu = document.getElementById('nav-menu');
   const navToggle = document.getElementById('nav-toggle');
   const navClose = document.getElementById('nav-close');
   const dropdownToggles = document.querySelectorAll('.dropdown__toggle');
   const navLinks = document.querySelectorAll('.nav__link:not(.dropdown__toggle)');

   // Toggle menu
   if (navToggle) {
      navToggle.addEventListener('click', () => {
         navMenu.classList.add('show-menu');
      });
   }

   if (navClose) {
      navClose.addEventListener('click', () => {
         navMenu.classList.remove('show-menu');
      });
   }

   // Handle dropdown toggles
   dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', function(e) {
         e.preventDefault();
         e.stopPropagation();
         const dropdownMenu = this.nextElementSibling;
         dropdownMenu.classList.toggle('show');
         // console.log('Dropdown clicked'); // Debugging
      });
   });

   // Handle regular nav links (close menu on mobile)
   navLinks.forEach(link => {
      link.addEventListener('click', () => {
         if (window.innerWidth <= 767) { // Adjust this breakpoint as needed
            navMenu.classList.remove('show-menu');
         }
      });
   });

   // Close dropdowns when clicking outside
   document.addEventListener('click', function(e) {
      if (!e.target.closest('.dropdown')) {
         document.querySelectorAll('.dropdown__menu.show').forEach(menu => {
            menu.classList.remove('show');
         });
      }
   });

   // Close menu when clicking dropdown items on mobile
   const dropdownLinks = document.querySelectorAll('.dropdown__link');
   dropdownLinks.forEach(link => {
      link.addEventListener('click', () => {
         if (window.innerWidth <= 767) {
            navMenu.classList.remove('show-menu');
         }
      });
   });
});
