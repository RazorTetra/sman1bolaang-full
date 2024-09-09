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

/*=============== REMOVE MENU MOBILE ===============*/
const navLink = document.querySelectorAll('.nav__link')

const linkAction = () => {
  const navMenu = document.getElementById('nav-menu')
  navMenu.classList.remove('show-menu')
}
navLink.forEach(n => n.addEventListener('click', linkAction))

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
  console.log('DOM fully loaded');

  const navLinks = document.querySelectorAll('.nav__list a');
  const sections = document.querySelectorAll('section');

  console.log('Nav links found:', navLinks.length);
  console.log('Sections found:', sections.length);

  function updateActiveLink() {
      const scrollPosition = window.scrollY;
      console.log('Current scroll position:', scrollPosition);

      sections.forEach((section, index) => {
          const sectionTop = section.offsetTop;
          const sectionHeight = section.offsetHeight;

          console.log(`Section ${section.id}: top=${sectionTop}, height=${sectionHeight}`);

          if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
              console.log(`Activating section: ${section.id}`);
              navLinks.forEach((link) => {
                  link.classList.remove('active-link');
                  console.log(`Removed active-link from: ${link.getAttribute('href')}`);
              });
              navLinks[index].classList.add('active-link');
              console.log(`Added active-link to: ${navLinks[index].getAttribute('href')}`);
          }
      });
  }

  window.addEventListener('scroll', function() {
      console.log('Scroll event detected');
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
