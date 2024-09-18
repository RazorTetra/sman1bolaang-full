<?php
require_once('config.php');
$skillsDropdown = getSkillsForHeader($pdo);
?>

<!-- components/header.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dropdown.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet"> <!-- Remix Icon CDN -->
    <title><?php echo $pageTitle ?? 'SMKN 1 Bolaang'; ?></title>
    <style>
        @media screen and (max-width: 960px) {
            .container {
                margin-inline: 1rem;
            }

            .nav__toggle {
                display: flex;
                padding: 0.25rem;
                background-color: var(--container-color);
                border-radius: 50%;
                font-size: 1.25rem;
                color: var(--title-color);
                box-shadow: 0 4px 12px hsla(0, 0%, 20%, .1);
            }
        }

        /* Dropdown styles */
        .dropdown__menu {
            display: none;
            position: absolute;
            background-color: var(--container-color);
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown__menu.show {
            display: block;
        }

        .dropdown__link {
            color: var(--text-color);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown__link:hover {
            background-color: var(--first-color-lighten);
            color: orange;
        }

        /* For mobile view */
        @media screen and (max-width: 1150px) {
            .dropdown__menu {
                position: static;
                background-color: transparent;
                box-shadow: none;
                display: none;
            }

            .dropdown__menu.show {
                display: block;
            }

            .dropdown__link {
                padding-left: 2rem;
            }
        }
    </style>
</head>

<body>
    <!--==================== HEADER ====================-->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="index.php" class="nav__logo">
                <span class="nav__logo-circle"><img src="assets/img/logo-smk.png" alt=""></span>
                <span class="nav__logo-name">smkn1bolaang</span>
            </a>

            <div class="nav__menu" id="nav-menu">
                <span class="nav__title">Menu</span>

                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="index.php" class="nav__link">Beranda</a>
                    </li>

                    <li class="nav__item">
                        <a href="index.php#about" class="nav__link">Tentang Kami</a>
                    </li>

                    <li class="nav__item">
                        <a href="index.php#news" class="nav__link">Berita</a>
                    </li>

                    <li class="nav__item dropdown">
                        <a href="javascript:void(0)" class="nav__link dropdown__toggle">
                            Keahlian <i class="ri-arrow-down-s-line"></i>
                        </a>
                        <ul class="dropdown__menu">
                            <?php foreach ($skillsDropdown as $skill): ?>
                                <li><a href="skill_detail.php?id=<?php echo $skill['id']; ?>" class="dropdown__link"><?php echo htmlspecialchars($skill['title']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>

                    <li class="nav__item">
                        <a href="index.php#contact" class="nav__link">Kontak</a>
                    </li>

                    <li class="nav__item dropdown">
                        <a href="javascript:void(0)" class="nav__link dropdown__toggle">
                            Struktur <i class="ri-arrow-down-s-line"></i>
                        </a>
                        <ul class="dropdown__menu">
                            <li><a href="struktur.php#struktur" class="dropdown__link">Struktur Organisasi</a></li>
                            <li><a href="struktur.php#tupoksi" class="dropdown__link">Tupoksi Staff</a></li>
                            <li><a href="struktur.php#profil-staff" class="dropdown__link">Profil Staff</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Close button -->
                <div class="nav__close" id="nav-close">
                    <i class="ri-close-line"></i>
                </div>
            </div>

            <div class="nav__buttons">
                <!-- Theme Button -->
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
        if (navToggle) {
            navToggle.addEventListener('click', () => {
                navMenu.classList.add('show-menu')
            })
        }

        /* Menu hidden */
        if (navClose) {
            navClose.addEventListener('click', () => {
                navMenu.classList.remove('show-menu')
            })
        }

        /*=============== SHADOW HEADER ===============*/
        const shadowHeader = () => {
            const header = document.getElementById('header')
            // When the scroll is greater than 50 viewport height, add the shadow-header class to the header tag
            this.scrollY >= 50 ? header.classList.add('shadow-header') :
                header.classList.remove('shadow-header')
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

    <script src="assets/js/dropdown.js"></script>

</body>

</html>