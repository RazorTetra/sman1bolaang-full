<!-- components/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet"> <!-- Remix Icon CDN -->
    <title>News Detail</title>
</head>
<body>
    <!--==================== HEADER & NAV ====================-->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="index.php" class="nav__logo">
                <span class="nav__logo-circle"><img src="assets/img/logo-smk.png" alt="Logo SMK"></span>
                <span>smkn1bolaang</span>
            </a>

            <div class="nav__menu" id="nav-menu">
                <span class="nav__title">Menu</span>
                
                <h3 class="nav__name">SMKN 1 Bolaang</h3>
                
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="index.php#home" class="nav__link">Beranda</a>
                    </li>
                    <li class="nav__item">
                        <a href="index.php#about" class="nav__link">Tentang Kami</a>
                    </li>
                    <li class="nav__item">
                        <a href="index.php#news" class="nav__link">Berita</a>
                    </li>
                    <li class="nav__item">
                        <a href="index.php#skills" class="nav__link">Keahlian</a>
                    </li>
                    <li class="nav__item">
                        <a href="index.php#struktur" class="nav__link">Struktur</a>
                    </li>
                    <li class="nav__item">
                        <a href="index.php#contact" class="nav__link">Kontak</a>
                    </li>
                </ul>

                <!-- Close button -->
                <div class="nav__close" id="nav-close">
                    <i class="ri-close-line"></i>
                </div>
            </div>

            <div class="nav__buttons">
                <!-- Theme button -->
                <i class="ri-moon-line change-theme" id="theme-button"></i>

                <!-- Toggle button -->
                <div class="nav__toggle" id="nav-toggle">
                    <i class="ri-menu-4-line"></i>
                </div>
            </div>
        </nav>
    </header>

    <script>
        /*=============== SHOW MENU ===============*/
        const navMenu = document.getElementById('nav-menu'),
              navToggle = document.getElementById('nav-toggle'),
              navClose = document.getElementById('nav-close')

        /* Menu show */
        if(navToggle){
            navToggle.addEventListener('click', () =>{
                navMenu.classList.add('show-menu')
            })
        }

        /* Menu hidden */
        if(navClose){
            navClose.addEventListener('click', () =>{
                navMenu.classList.remove('show-menu')
            })
        }

        /*=============== REMOVE MENU MOBILE ===============*/
        const navLink = document.querySelectorAll('.nav__link')

        const linkAction = () =>{
            const navMenu = document.getElementById('nav-menu')
            // When we click on each nav__link, we remove the show-menu class
            navMenu.classList.remove('show-menu')
        }
        navLink.forEach(n => n.addEventListener('click', linkAction))

        /*=============== SHADOW HEADER ===============*/
        const shadowHeader = () =>{
            const header = document.getElementById('header')
            // When the scroll is greater than 50 viewport height, add the shadow-header class to the header tag
            this.scrollY >= 50 ? header.classList.add('shadow-header') 
                               : header.classList.remove('shadow-header')
        }
        window.addEventListener('scroll', shadowHeader)

        /*=============== DARK LIGHT THEME ===============*/ 
        const themeButton = document.getElementById('theme-button')
        const darkTheme = 'dark-theme'
        const iconTheme = 'ri-sun-line'

        // Previously selected theme (if user selected it)
        const selectedTheme = localStorage.getItem('selected-theme')
        const selectedIcon = localStorage.getItem('selected-icon')

        // We obtain the current theme that the interface has by validating the dark-theme class
        const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light'
        const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-moon-line' : 'ri-sun-line'

        // We validate if the user previously chose a theme
        if (selectedTheme) {
            document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme)
            themeButton.classList[selectedIcon === 'ri-sun-line' ? 'add' : 'remove'](iconTheme)
        }

        // Activate / deactivate the theme manually with the button
        themeButton.addEventListener('click', () => {
            // Add or remove the dark / icon theme
            document.body.classList.toggle(darkTheme)
            themeButton.classList.toggle(iconTheme)
            // We save the theme and the current icon that the user chose
            localStorage.setItem('selected-theme', getCurrentTheme())
            localStorage.setItem('selected-icon', getCurrentIcon())
        })
    </script>
</body>
</html>