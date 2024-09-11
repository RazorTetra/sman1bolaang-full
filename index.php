<?php
require_once('config.php'); // Koneksi database
include('functions/visitors.php');

// Ambil data berita dari database
$stmt = $pdo->prepare("SELECT id, title, content, image, created_at FROM articles ORDER BY created_at DESC");
$stmt->execute();
$news_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil gambar dari database
// $stmt = $pdo->prepare("SELECT image FROM gallery ORDER BY created_at DESC");
// $stmt->execute();
// $gallery_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil gambar dari database yang ditandai untuk ditampilkan
$stmt = $pdo->prepare("SELECT image FROM gallery WHERE is_displayed = 1 ORDER BY created_at DESC LIMIT 9");
$stmt->execute();
$gallery_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil data dari tabel about_info
$stmt = $pdo->prepare("SELECT * FROM about_info LIMIT 1");
$stmt->execute();
$about = $stmt->fetch(PDO::FETCH_ASSOC);

// ambil data dari tabel social media links
try {
   $stmt = $pdo->query("SELECT * FROM social_media_links WHERE is_active = TRUE");
   $socialLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
   $socialLinks = [];
   // error_log('Database error: ' . $e->getMessage());
}

// Periksa apakah data about jika ditemukan
if ($about) {
   $description = $about['description'];
   $name = $about['name'];
   $image = $about['image'];
   $facebook = $about['facebook'];
   $instagram = $about['instagram'];
   $youtube = $about['youtube'];
} else {
   $description = "Deskripsi tidak tersedia.";
   $name = "Nama tidak tersedia.";
   $image = "default-image.jpg"; // Gambar default jika tidak ada
   $facebook = "#";
   $instagram = "#";
   $youtube = "#";
}

function getContactInfo()
{
   global $pdo;
   $stmt = $pdo->query("SELECT * FROM contact_info WHERE is_active = TRUE");
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Query untuk mengambil semua skills dari database menggunakan PDO
try {
   $stmt = $pdo->prepare("SELECT * FROM skills ORDER BY id ASC");
   $stmt->execute();
   $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
   die("Query failed: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!-- SEO Meta Tags -->
   <title>SMK NEGERI 1 BOLAANG - Pendidikan Kejuruan Berkualitas</title>
   <meta name="description" content="SMK NEGERI 1 BOLAANG menyediakan pendidikan kejuruan berkualitas untuk mempersiapkan siswa menghadapi dunia kerja dan industri. Temukan program studi dan fasilitas unggulan kami.">
   <meta name="keywords" content="SMK NEGERI 1 BOLAANG, pendidikan kejuruan, sekolah menengah kejuruan, Bolaang, program studi, fasilitas sekolah">
   <meta name="author" content="SMK NEGERI 1 BOLAANG">
   <meta name="robots" content="index, follow">

   <!-- Open Graph / Facebook -->
   <meta property="og:type" content="website">
   <meta property="og:url" content="https://www.smkn1bolaang.sch.id/">
   <meta property="og:title" content="SMK NEGERI 1 BOLAANG - Pendidikan Kejuruan Berkualitas">
   <meta property="og:description" content="SMK NEGERI 1 BOLAANG menyediakan pendidikan kejuruan berkualitas untuk mempersiapkan siswa menghadapi dunia kerja dan industri.">
   <meta property="og:image" content="https://www.smkn1bolaang.sch.id/assets/img/logo-smk.png">

   <!-- Twitter -->
   <meta property="twitter:card" content="summary_large_image">
   <meta property="twitter:url" content="https://www.smkn1bolaang.sch.id/">
   <meta property="twitter:title" content="SMK NEGERI 1 BOLAANG - Pendidikan Kejuruan Berkualitas">
   <meta property="twitter:description" content="SMK NEGERI 1 BOLAANG menyediakan pendidikan kejuruan berkualitas untuk mempersiapkan siswa menghadapi dunia kerja dan industri.">
   <meta property="twitter:image" content="https://www.smkn1bolaang.sch.id/assets/img/logo-smk.png">


   <!--=============== FAVICON ===============-->
   <link rel="shortcut icon" href="assets/img/logo-smk.png" type="image/x-icon">

   <!--=============== REMIXICONS ===============-->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.4.0/remixicon.css" crossorigin="">

   <!--=============== CSS ===============-->
   <link rel="stylesheet" href="assets/css/styles.css">
   <!-- <link href="assets/css/output.css" rel="stylesheet"> Tailwind CSS  -->


   <style>
      /* CSS untuk mengatur ukuran gambar */
      .news__img {
         width: 100%;
         /* Menyesuaikan lebar gambar dengan lebar container */
         height: 200px;
         /* Ukuran tetap untuk tinggi gambar */
         object-fit: cover;
         /* Menjaga aspek rasio gambar dan mengisi area tanpa distorsi */
         border-radius: 8px;
         /* Menambahkan sudut yang melengkung pada gambar */
      }

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

      /* Untuk tampilan mobile */
      @media screen and (max-width: 767px) {
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
            /* Memberikan indentasi pada item dropdown */
         }
      }
   </style>

   <title>SMK NEGERI 1 BOLAANG</title>
</head>

<!-- Google tag (gtag.js) -->
<!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-T4NN3BHD7T"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-T4NN3BHD7T');
</script> -->

<body>
   <!--==================== HEADER ====================-->
   <header class="header" id="header">
      <nav class="nav container">
         <a href="#" class="nav__logo">
            <span class="nav__logo-circle"><img src="assets/img/logo-smk.png" alt=""></span>
            <span class="nav__logo-name">smkn1bolaang</span>
         </a>

         <div class="nav__menu" id="nav-menu">
            <span class="nav__title">Menu</span>

            <ul class="nav__list">
               <li class="nav__item">
                  <a href="#home" class="nav__link">Beranda</a>
               </li>

               <li class="nav__item">
                  <a href="#about" class="nav__link">Tentang Kami</a>
               </li>

               <li class="nav__item">
                  <a href="#news" class="nav__link">Berita</a>
               </li>

               <li class="nav__item">
                  <a href="#skills" class="nav__link">Keahlian</a>
               </li>

               <li class="nav__item">
                  <a href="#contact" class="nav__link">Kontak</a>
               </li>

               <li class="nav__item dropdown">
                  <a href="javascript:void(0)" class="nav__link dropdown__toggle">
                     Struktur <i class="ri-arrow-down-s-line"></i>
                  </a>
                  <ul class="dropdown__menu">
                     <li><a href="struktur.php#organisasi" class="dropdown__link">Struktur Organisasi</a></li>
                     <li><a href="struktur.php#kurikulum" class="dropdown__link">Tupoksi Staff</a></li>
                     <li><a href="struktur.php#kesiswaan" class="dropdown__link">Profil Staff</a></li>
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

   <!--==================== MAIN ====================-->
   <main class="main">
      <!--==================== HOME ====================-->
      <section class="home section" id="home">
         <div class="home__container container grid">
            <h1 class="home__name">
               SMKN 1 Bolaang
            </h1>

            <div class="home__perfil">
               <div class="home__image">
                  <!-- <img src="assets/img/home-perfil-3.jpg" alt="image" class="home__img"> -->
                  <!-- <div class="home__shadow"></div> -->
                  <iframe src="https://www.youtube.com/embed/p48DjKj-JyI?si=c-O2boxorz6np0Vr" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

                  <!-- <img src="assets/img/curved-arrow.svg" alt="" class="home__arrow">
                     <img src="assets/img/random-lines.svg" alt="" class="home__line"> -->

                  <div class="geometric-box"></div>
               </div>

               <div class="home__social">
                  <a href="https://www.facebook.com/smkn1bolaang" target="_blank" class="home__social-link">
                     <i class="ri-facebook-circle-line"></i>
                  </a>

                  <a href="https://instagram.com/smkn1.bolaang" target="_blank" class="home__social-link">
                     <i class="ri-instagram-line"></i>
                  </a>

                  <a href="https://www.youtube.com/@SMKN1BolaangMattoa" target="_blank" class="home__social-link">
                     <i class="ri-youtube-line"></i>
                  </a>
               </div>
            </div>

            <div class="home__info">
               <p class="home__description">
                  <!-- <b>Lorem, ipsum.</b> -->
                  <b>MATTOA SmeckONEBol</b>
                  <br>
                  <b>" Menjadikan Aku Tangguh Terampil <br> Optimis Amanah "</b>

               </p>

               <a href="#about" class="home__scroll">
                  <div class="home__scroll-box">
                     <i class="ri-arrow-down-s-line"></i>
                  </div>

                  <span class="home__scroll-text">Scroll Kebawah</span>
               </a>
            </div>
         </div>
      </section>

      <!--==================== ABOUT ====================-->
      <section class="about section" id="about">
         <div class="about__container container grid">
            <h2 class="section__title-1">
               <span>Tabea !</span>
            </h2>

            <div class="about__perfil">
               <div class="about__image">
                  <img src="<?php echo htmlspecialchars($about['image']); ?>" alt="image" class="about__img">
                  <div class="about__shadow"></div>
                  <div class="geometric-box"></div>
                  <div class="about__box"></div>
               </div>
            </div>

            <div class="about__info">
               <div class="about__description">
                  <?php echo $about['description']; ?>
               </div>

               <div class="about__name">
                  <?php echo $about['name']; ?>
               </div>

               <div class="about__buttons mt-4">
                  <a href="#contact" class="button">
                     <i class="ri-whatsapp-line"></i> Kontak Saya
                  </a>

                  <a href="<?php echo htmlspecialchars($about['facebook']); ?>" target="_blank" class="button__ghost">
                     <i class="ri-facebook-box-line"></i>
                  </a>
                  <a href="<?php echo htmlspecialchars($about['instagram']); ?>" target="_blank" class="button__ghost">
                     <i class="ri-instagram-line"></i>
                  </a>
                  <a href="<?php echo htmlspecialchars($about['youtube']); ?>" target="_blank" class="button__ghost">
                     <i class="ri-youtube-line"></i>
                  </a>
               </div>
            </div>
         </div>
      </section>

      <script>
         function editContent(section) {
            window.location.href = 'admin/manage_about.php';
         }
      </script>


      <!--==================== NEWS / BERITA ====================-->
      <section class="news section" id="news">
         <h2 class="section__title-1">
            <span>Berita.</span>
         </h2>

         <div class="news__container container grid">
            <?php foreach ($news_items as $news): ?>
               <article class="news__card">
                  <div class="news__image">
                     <img src="<?php echo htmlspecialchars($news['image']); ?>" alt="image" class="news__img">
                     <a href="news_detail.php?id=<?php echo $news['id']; ?>" class="news__button button">
                        <i class="ri-arrow-right-up-line"></i>
                     </a>
                  </div>

                  <div class="news__content">
                     <h3 class="news__subtitle"><?php echo date('d F Y', strtotime($news['created_at'])); ?></h3>
                     <h2 class="news__title"><?php echo htmlspecialchars($news['title']); ?></h2>
                     <p class="news__description">
                        <?php echo substr(htmlspecialchars($news['content']), 0, 100); ?>...
                     </p>
                  </div>

                  <div class="news__buttons">
                     <a href="news_detail.php?id=<?php echo $news['id']; ?>" target="_blank" class="news__link">
                        <i class="ri-arrow-right-circle-line"></i> Baca Selengkapnya.
                     </a>
                  </div>
               </article>
            <?php endforeach; ?>
         </div>
      </section>

      <!--==================== SKILLS / KEAHLIAN ====================-->
      <section class="skills section" id="skills">
         <h2 class="section__title-2">
            <span>Konsentrasi Keahlian.</span>
         </h2>

         <div class="skills__container container grid">
            <?php foreach ($skills as $row) : ?>
               <article class="skills__card" onclick="window.location.href='skill_detail.php?id=<?php echo $row['id']; ?>'">
                  <div class="skills__border"></div>

                  <div class="skills__content">
                     <div class="skills__icon">
                        <div class="skills__box"></div>
                        <i class="<?php echo htmlspecialchars($row['icon']); ?>"></i>
                     </div>

                     <h2 class="skills__title"><?php echo htmlspecialchars($row['title']); ?></h2>

                     <p class="skills__description">
                        <?php
                        $description = htmlspecialchars($row['description']);
                        echo (strlen($description) > 100) ? substr($description, 0, 100) . '...' : $description;
                        ?>
                     </p>
                  </div>
               </article>
            <?php endforeach; ?>
         </div>
      </section>

      <!--==================== GALERI ====================-->
      <section class="galeri section" id="galeri">
         <h2 class="section__title-2">
            <span>Galeri.</span>
         </h2>
         <div class="galeri__container container">
            <div class="container-image">
               <div class="img-container grid grid-cols-3 gap-4">
                  <?php foreach ($gallery_images as $image): ?>
                     <div class="img">
                        <span><img src="assets/img/<?php echo htmlspecialchars($image['image']); ?>" alt="Galeri" class="w-full h-full object-cover"></span>
                     </div>
                  <?php endforeach; ?>
               </div>
            </div>
         </div>
      </section>

      <!--==================== CONTACT ====================-->
      <section class="contact section" id="contact">
         <div class="contact__container grid">
            <div class="contact__data">
               <h2 class="section__title-2">
                  <span>Kontak & Saran.</span>
               </h2>
               <p class="contact__description-1">
                  Kami akan membaca semua email masuk. Kirim kami pesan yang kamu inginkan.
               </p>
               <p class="contact__description-2">
                  Kami minta <b>Nama</b> dan <b>Email</b> Kamu, untuk mengirimkan pesan.
               </p>
               <div class="geometric-box"></div>
            </div>

            <div class="contact__mail">
               <h2 class="contact__title">
                  Kirim Sebuah Pesan Atau Saran Anda
               </h2>
               <form class="contact__form" id="contact-form">
                  <div class="contact__group">
                     <div class="contact__box">
                        <input type="text" name="user_name" class="contact__input" id="name" required placeholder="Masukkan Nama">
                        <label for="name" class="contact__label">Nama</label>
                     </div>
                     <div class="contact__box">
                        <input type="email" name="user_email" class="contact__input" id="email" required placeholder="Masukkan Email">
                        <label for="email" class="contact__label">Email</label>
                     </div>
                  </div>
                  <div class="contact__box">
                     <input type="text" name="user_subject" class="contact__input" id="subject" required placeholder="Subjek">
                     <label for="subject" class="contact__label">Subjek</label>
                  </div>
                  <div class="contact__box contact__area">
                     <textarea name="user_message" id="message" class="contact__input" required placeholder="Pesan Anda"></textarea>
                     <label for="message" class="contact__label">Masukkan Pesan</label>
                  </div>
                  <p class="contact__message" id="contact-message"></p>
                  <button type="submit" class="contact__button button">
                     <i class="ri-send-plane-line"></i>Kirim Pesan
                  </button>
               </form>
            </div>

            <div class="contact__social">
               <img src="assets/img/curved-arrow.svg" alt="" class="contact__social-arrow">
               <div class="contact__social-data">
                  <div>
                     <p class="contact__social-description-1">
                        Jika tidak mengirimkan email
                     </p>
                     <p class="contact__social-description-2">
                        Lihat kami di social media
                     </p>
                  </div>
                  <div class="contact__social-links">
                     <?php foreach ($socialLinks as $link): ?>
                        <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" class="contact__social-link">
                           <i class="<?php echo htmlspecialchars($link['icon']); ?>"></i>
                        </a>
                     <?php endforeach; ?>
                  </div>
               </div>
            </div>
         </div>
      </section>
   </main>

   <!--==================== MAPS ====================-->
   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.3664564000965!2d124.1294927!3d0.8623211!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x327e6d402993a729%3A0xae42902436ca6fbe!2sSMK%20Negeri%201%20Bolaang!5e0!3m2!1sen!2sid!4v1723741370300!5m2!1sen!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>


   <!--==================== FOOTER ====================-->
   <footer class="footer">
      <div class="footer__container container grid">
         <div>
            <a href="#" class="footer__logo">
               <i class="ri-graduation-cap-line"></i>
               <span>smkn1bolaang.</span>
            </a>

            <p class="footer__description">MATTOA SmeckONEBol</p>

            <?php
            $contactInfo = getContactInfo();
            foreach ($contactInfo as $info) {
               if ($info['type'] == 'email') {
                  echo "<address class='footer__email'>Email: {$info['value']}</address>";
               } elseif ($info['type'] == 'whatsapp') {
                  echo "<p class='footer__whatsapp'>Whatsapp: {$info['value']}</p>";
               }
            }
            ?>
         </div>

         <div class="footer__content grid">
            <div>
               <h3 class="footer__title">Sekolah</h3>

               <ul class="footer__links">
                  <li>
                     <a href="#about" class="footer__link">Tentang Kami</a>
                  </li>

                  <li>
                     <a href="#skills" class="footer__link">Keahlian</a>
                  </li>

                  <li>
                     <a href="#news" class="footer__link">Berita</a>
                  </li>
               </ul>
            </div>

            <div>
               <h3 class="footer__title">Alamat</h3>

               <ul class="footer__list">
                  <li>
                     <address class="footer__info">Jl. Inobonto - Kotamobagu,<br> Langangon</address>
                  </li>

                  <li>
                     <address class="footer__info">Kabupaten Bolaang Mongondow, <br> Sulawesi Utara</address>
                  </li>
               </ul>
            </div>

            <div>
               <h3 class="footer__title">Media Sosial</h3>

               <div class="footer__social">
                  <?php foreach ($socialLinks as $link): ?>
                     <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" class="footer__social-link">
                        <i class="<?php echo htmlspecialchars($link['icon']); ?>"></i>
                     </a>
                  <?php endforeach; ?>
               </div>
            </div>
         </div>
      </div>

      <span class="footer__copy">
         Copyright &#169; 2024. All Rights Reserved By
         <a href="#">SMK Negeri 1 Bolaang.</a>
      </span>
   </footer>

   <!--========== SCROLL UP ==========-->
   <a href="#" class="scrollup" id="scroll-up">
      <i class="ri-arrow-up-s-line"></i>
   </a>

   <!--=============== SCROLLREVEAL ===============-->
   <script src="assets/js/scrollreveal.min.js"></script>

   <!--=============== EMAIL JS ===============-->
   <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>


   <!--=============== MAIN JS ===============-->
   <script src="assets/js/main.js"></script>

   <!-- Script Contact -->
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const form = document.getElementById('contact-form');
         const message = document.getElementById('contact-message');

         form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('functions/send_feedback.php', {
                  method: 'POST',
                  body: formData
               })
               .then(response => response.json())
               .then(data => {
                  if (data.status === 'success') {
                     message.textContent = data.message;
                     message.style.color = 'green';
                     form.reset();
                  } else {
                     message.textContent = data.message;
                     message.style.color = 'red';
                  }
               })
               .catch(error => {
                  console.error('Error:', error);
                  message.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                  message.style.color = 'red';
               });
         });
      });
   </script>

   <script>
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
   </script>
</body>

</html>