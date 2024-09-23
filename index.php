<!-- PHP Script -->
<?php
require_once('config.php'); // Koneksi database
include('functions/visitors.php');

// Ambil data berita dari database
$stmt = $pdo->prepare("SELECT id, title, content, image, created_at FROM articles ORDER BY created_at DESC");
$stmt->execute();
$news_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil gambar dari database yang ditandai untuk ditampilkan
$stmt = $pdo->prepare("SELECT image FROM gallery WHERE is_displayed = 1 ORDER BY created_at DESC LIMIT 9");
$stmt->execute();
$gallery_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch beranda data
$stmt = $pdo->query("SELECT * FROM beranda LIMIT 1");
$beranda = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data dari tabel about_info
$stmt = $pdo->prepare("SELECT * FROM about_info LIMIT 1");
$stmt->execute();
$about = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data button
$pdo = new PDO($dsn, $user, $pass, $options);
$stmt = $pdo->query("SELECT * FROM custom_navbar_button WHERE is_visible = 1 LIMIT 1");
$customButton = $stmt->fetch();

// ambil data dari tabel social media links
try {
   $stmt = $pdo->query("SELECT * FROM social_media_links WHERE is_active = TRUE");
   $socialLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
   $socialLinks = [];
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
   $image = "default-image.jpg";
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
$stmt = $pdo->prepare("SELECT id, title FROM skills ORDER BY id ASC");
$stmt->execute();
$skillsDropdown = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!-- Akhir PHP Script -->

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>SMK NEGERI 1 BOLAANG</title>

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
            /* Memberikan indentasi pada item dropdown */
         }
      }
   </style>
   <!-- style pop up image -->
   <style>
      /* Gallery pop-up styles */
      .image-popup {
         display: none;
         position: fixed;
         z-index: 1000;
         left: 0;
         top: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.9);
         opacity: 0;
         transition: opacity 0.3s ease;
      }

      .image-popup.show {
         opacity: 1;
      }

      .popup-inner {
         position: absolute;
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%);
         max-width: 90%;
         max-height: 90%;
         width: auto;
         height: auto;
      }

      .popup-content {
         display: block;
         max-width: 100%;
         max-height: 90vh;
         width: auto;
         height: auto;
         object-fit: contain;
         box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
         border-radius: 4px;
         transform: scale(0.9);
         transition: transform 0.3s ease;
      }

      .show .popup-content {
         transform: scale(1);
      }

      .close {
         position: absolute;
         top: -40px;
         right: 0;
         color: #f1f1f1;
         font-size: 40px;
         font-weight: bold;
         transition: 0.3s;
         background: none;
         border: none;
         cursor: pointer;
         outline: none;
         padding: 0;
         z-index: 1001;
      }

      .close:hover,
      .close:focus {
         color: #bbb;
         text-decoration: none;
      }

      /* Responsive adjustments */
      @media screen and (max-width: 768px) {
         .popup-inner {
            width: 90%;
         }

         .popup-content {
            width: 100%;
            height: auto;
         }
      }
   </style>

   <style>
      section {
         padding-top: calc(var(--header-height) + 2rem);
      }

      html {
         scroll-padding-top: var(--header-height);
      }
   </style>

   <!-- AI Response formater -->
   <style>
      .ai-response {
         font-family: Arial, sans-serif;
         line-height: 1.6;
         color: #333;
         background-color: #f9f9f9;
         border-radius: 8px;
         padding: 15px;
         margin-bottom: 15px;
         max-width: 100%;
         overflow-wrap: break-word;
      }

      .ai-response strong {
         color: #0056b3;
         font-weight: bold;
      }

      .ai-response ul {
         padding-left: 20px;
         margin-top: 10px;
         margin-bottom: 10px;
         list-style-type: disc;
      }

      .ai-response li {
         margin-bottom: 5px;
      }

      .ai-response p {
         margin-bottom: 10px;
      }
   </style>
   <!-- Custom Button in Navbar -->
   <style>
      .nav__item--custom-button {
         margin-left: .2rem;
      }

      .nav__custom-button {
         display: inline-block;
         padding: 0.2rem .5rem;
         border-radius: 0.25rem;
         font-size: 0.9rem;
         font-weight: 600;
         text-align: center;
         transition: opacity 0.3s, transform 0.3s;
      }

      .nav__custom-button:hover {
         opacity: 0.9;
         transform: translateY(-2px);
      }

      @media screen and (max-width: 1024px) {
         .nav__item--custom-button {
            margin-left: 0;
         }

         .nav__custom-button {
            padding: 0.75rem 1rem;
         }
      }

      @media screen and (max-width: 380px) {
         .nav__custom-button {
            font-size: 0.8rem;
         }
      }
   </style>
</head>

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
                  <a href="#home" class="nav__link active-link">Beranda</a>
               </li>

               <li class="nav__item">
                  <a href="#about" class="nav__link">Tentang Kami</a>
               </li>

               <li class="nav__item">
                  <a href="#news" class="nav__link">Berita</a>
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

               <!-- <li class="nav__item">
                  <a href="#contact" class="nav__link">Kontak</a>
               </li> -->

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

               <?php if ($customButton && $customButton['is_visible']): ?>
                  <li class="nav__item nav__item--custom-button">
                     <a href="<?php echo htmlspecialchars($customButton['url']); ?>"
                        class="nav__link nav__custom-button"
                        style="background-color: <?php echo htmlspecialchars($customButton['button_color']); ?>;
                  color: <?php echo htmlspecialchars($customButton['text_color']); ?>;"
                        target="_blank" rel="noopener noreferrer">
                        <?php echo htmlspecialchars($customButton['text']); ?>
                     </a>
                  </li>
               <?php endif; ?>
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
               <?php echo htmlspecialchars($beranda['title'] ?? 'SMKN 1 Bolaang'); ?>
            </h1>

            <div class="home__perfil">
               <div class="home__image">
                  <iframe src="<?php echo htmlspecialchars($beranda['youtube_link'] ?? 'https://www.youtube.com/embed/p48DjKj-JyI?si=c-O2boxorz6np0Vr'); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

                  <div class="geometric-box"></div>
               </div>

               <div class="home__social">
                  <?php foreach ($socialLinks as $link): ?>
                     <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" class="home__social-link">
                        <i class="<?php echo htmlspecialchars($link['icon']); ?>"></i>
                     </a>
                  <?php endforeach; ?>
               </div>
            </div>

            <div class="home__info">
               <p class="home__description">
                  <?php echo nl2br(htmlspecialchars($beranda['description'] ?? '')); ?>
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
               <span><?php echo htmlspecialchars($about['tabea_text']); ?></span>
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
                  <a href="https://wa.me/<?php echo htmlspecialchars(str_replace('+', '', $about['whatsapp'])); ?>" target="_blank" class="button">
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
               <div class="img-container">
                  <?php foreach ($gallery_images as $image): ?>
                     <div class="img">
                        <span><img src="assets/img/<?php echo htmlspecialchars($image['image']); ?>" alt="Galeri" class="w-full h-full object-cover gallery-img"></span>
                     </div>
                  <?php endforeach; ?>
               </div>
            </div>
         </div>
      </section>

      <!-- Pop-up container -->
      <div id="imagePopup" class="image-popup">
         <div class="popup-inner">
            <img class="popup-content" id="popupImage">
            <button class="close">&times;</button>
         </div>
      </div>

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
      <?php include 'components/bubblechat.php'; ?>
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
   <a href="#home" class="scrollup" id="scroll-up">
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

   <!-- Script pop up image -->
   <script>
      // Get all gallery images
      const galleryImages = document.querySelectorAll('.gallery-img');
      const popup = document.getElementById('imagePopup');
      const popupImg = document.getElementById('popupImage');
      const closeBtn = document.querySelector('.close');

      // Function to open popup
      function openPopup(imageSrc) {
         popupImg.src = imageSrc;
         popup.style.display = 'block';
         setTimeout(() => {
            popup.classList.add('show');
         }, 50);
      }

      // Function to close popup
      function closePopup() {
         popup.classList.remove('show');
         setTimeout(() => {
            popup.style.display = 'none';
         }, 300);
      }

      // Add click event to each gallery image
      galleryImages.forEach(img => {
         img.addEventListener('click', function() {
            openPopup(this.src);
         });
      });

      // Close the popup when clicking the close button
      closeBtn.addEventListener('click', closePopup);

      // Close the popup when clicking outside the image
      popup.addEventListener('click', function(event) {
         if (event.target === popup) {
            closePopup();
         }
      });

      // Prevent closing when clicking on the image
      popupImg.addEventListener('click', function(event) {
         event.stopPropagation();
      });

      // Close popup when pressing ESC key
      document.addEventListener('keydown', function(event) {
         if (event.key === 'Escape') {
            closePopup();
         }
      });
   </script>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         // Smooth scrolling
         document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
               e.preventDefault();

               const targetId = this.getAttribute('href').substring(1);
               const targetElement = document.getElementById(targetId);

               if (targetElement) {
                  window.scrollTo({
                     top: targetElement.offsetTop - document.querySelector('.header').offsetHeight,
                     behavior: 'smooth'
                  });
               }
            });
         });

         // Active link
         const sections = document.querySelectorAll('section[id]');

         function changeLinkState() {
            let index = sections.length;

            while (--index && window.scrollY + 100 < sections[index].offsetTop) {}

            document.querySelectorAll('.nav__link').forEach((link) => link.classList.remove('active-link'));
            document.querySelector(`.nav__link[href*="${sections[index].id}"]`)?.classList.add('active-link');
         }

         window.addEventListener('scroll', changeLinkState);
      });
   </script>
</body>

</html>