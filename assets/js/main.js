/*=============== SHOW MENU ===============*/
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close')

if(navToggle) {
  navToggle.addEventListener('click', () =>{
    navMenu.classList.add('show-menu')
  })
}

if(navClose) {
  navClose.addEventListener('click', () =>{
    navMenu.classList.remove('show-menu')
  })
}

/*=============== DROPDOWN FUNCTIONALITY ===============*/
const dropdownToggles = document.querySelectorAll('.dropdown__toggle');
const navLinks = document.querySelectorAll('.nav__link');

function toggleDropdown(toggle, event) {
  event.preventDefault();
  event.stopPropagation();
  const dropdownMenu = toggle.nextElementSibling;
  dropdownMenu.classList.toggle('show');
  toggle.setAttribute('aria-expanded', dropdownMenu.classList.contains('show'));
}

function closeAllDropdowns() {
  dropdownToggles.forEach(toggle => {
    const dropdownMenu = toggle.nextElementSibling;
    dropdownMenu.classList.remove('show');
    toggle.setAttribute('aria-expanded', 'false');
  });
}

dropdownToggles.forEach(toggle => {
  toggle.addEventListener('click', (e) => toggleDropdown(toggle, e));
});

/*=============== REMOVE MENU MOBILE ===============*/
const linkAction = (e) => {
  const navMenu = document.getElementById('nav-menu');
  
  // Jika yang diklik adalah dropdown toggle, jangan tutup menu
  if (e.target.classList.contains('dropdown__toggle')) {
    return;
  }
  
  // Jika yang diklik adalah dropdown link, tutup menu
  if (e.target.classList.contains('dropdown__link')) {
    navMenu.classList.remove('show-menu');
    closeAllDropdowns();
    return;
  }
  
  // Untuk link lainnya, tutup menu
  if (!e.target.closest('.dropdown')) {
    navMenu.classList.remove('show-menu');
    closeAllDropdowns();
  }
}

navLinks.forEach(n => n.addEventListener('click', linkAction));

// Menutup dropdown saat klik di luar
document.addEventListener('click', (e) => {
  if (!e.target.closest('.dropdown') && !e.target.closest('.nav__menu')) {
    closeAllDropdowns();
  }
});
/*=============== SHADOW HEADER ===============*/
const shadowHeader = () =>{
  const header = document.getElementById('header')
  this.scrollY >= 50 ? header.classList.add('shadow-header')
                     : header.classList.remove('shadow-header')
}
window.addEventListener('scroll', shadowHeader)

/*=============== EMAIL JS ===============*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

const sendEmail = (e) =>{
  e.preventDefault()

  emailjs.sendForm('service_rdyio9b', 'template_7u8d12n', '#contact-form', 'Lw9J8KAihTs_Skx6P')
  .then(() =>{

    // contactMessage.textContent = 'Message sent successfully!'

    setTimeout(() => {
      contactMessage.textContent = ''
    }, 5000)

    contactForm.reset()
  }, () =>{
    // Show error message
    // contactMessage.textContent = 'Message not sent (service error)!'
  })

}

contactForm.addEventListener('submit', sendEmail)

/*=============== SHOW SCROLL UP ===============*/ 
const scrollUp = () =>{
    const scrollUp = document.getElementById('scroll-up')

    this.scrollY >= 350 ? scrollUp.classList.add('show-scroll')
                        : scrollUp.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)

document.addEventListener('DOMContentLoaded', function() {
  // console.log('DOM fully loaded');

  const navLinks = document.querySelectorAll('.nav__list a');
  const sections = document.querySelectorAll('section');

  // console.log('Nav links found:', navLinks.length);
  // console.log('Sections found:', sections.length);

  function updateActiveLink() {
      const scrollPosition = window.scrollY;
      // console.log('Current scroll position:', scrollPosition);

      sections.forEach((section, index) => {
          const sectionTop = section.offsetTop;
          const sectionHeight = section.offsetHeight;

          // console.log(`Section ${section.id}: top=${sectionTop}, height=${sectionHeight}`);

          if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
              // console.log(`Activating section: ${section.id}`);
              navLinks.forEach((link) => {
                  link.classList.remove('active-link');
                  // console.log(`Removed active-link from: ${link.getAttribute('href')}`);
              });
              navLinks[index].classList.add('active-link');
              // console.log(`Added active-link to: ${navLinks[index].getAttribute('href')}`);
          }
      });
  }

  window.addEventListener('scroll', function() {
      // console.log('Scroll event detected');
      updateActiveLink();
  });

  // Initial call to set active link on page load
  updateActiveLink();
});

/*=============== DARK LIGHT THEME ===============*/ 
const themeButton = document.getElementById('theme-button')
const darkTheme = 'dark-theme'
const iconTheme = 'ri-sun-line'

const selectedTheme = localStorage.getItem('selected-theme')
const selectedIcon = localStorage.getItem('selected-icon')

const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light'
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-moon-line' : 'ri-sun-line'

if(selectedTheme) {
  document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme)
  themeButton.classList[selectedIcon === 'ri-moon-line' ? 'add' : 'remove'](iconTheme)
}

themeButton.addEventListener('click', () => {
  document.body.classList.toggle(darkTheme)
  themeButton.classList.toggle(iconTheme)

  localStorage.setItem('selected-theme', getCurrentTheme())
  localStorage.setItem('selected-icon', getCurrentIcon())
})

/*=============== SCROLL REVEAL ANIMATION ===============*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 2500,
    delay: 400,
    // reset:true // Animation repeat
})

sr.reveal(`.home__perfil, .about__image, .contact__mail`, {origin: 'right'})
sr.reveal(`.home__name, .home__info,
           .about__container .section__title-1, .about__info,
           .contact__social, .contact__data`, {origin: 'left'})
sr.reveal(`.skills__card, .news__card, .map, .galeri__container, .img-struktur`, {interval: 100})

// Lazy Load image untuk peforma memuat image lebih baik
document.addEventListener("DOMContentLoaded", function() {
  let lazyImages = [].slice.call(document.querySelectorAll(".lazy-load"));
  let active = false;

  const lazyLoad = function() {
      if (active === false) {
          active = true;

          lazyImages.forEach(function(img) {
              if (img.getBoundingClientRect().top <= window.innerHeight && img.getBoundingClientRect().bottom >= 0) {
                  img.src = img.dataset.src;
                  img.classList.remove("lazy-load");
                  lazyImages = lazyImages.filter(function(image) {
                      return image !== img;
                  });
                  if (lazyImages.length === 0) {
                      document.removeEventListener("scroll", lazyLoad);
                      window.removeEventListener("resize", lazyLoad);
                  }
              }
          });

          active = false;
      }
  };

  document.addEventListener("scroll", lazyLoad);
  window.addEventListener("resize", lazyLoad);
  lazyLoad();
});
/*=============== DROPDOWN FUNCTIONALITY ===============*/
document.addEventListener('DOMContentLoaded', function() {
  const dropdownToggles = document.querySelectorAll('.dropdown__toggle');

  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const dropdownMenu = this.nextElementSibling;
      dropdownMenu.classList.toggle('show');
      // console.log('Dropdown clicked'); // Untuk debugging
    });
  });

  // Menutup dropdown saat mengklik di luar
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
      document.querySelectorAll('.dropdown__menu.show').forEach(menu => {
        menu.classList.remove('show');
      });
    }
  });
});