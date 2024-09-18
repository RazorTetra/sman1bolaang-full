<?php
include('config.php');
include('functions/visitors.php');

// ambil data dari tabel social media links
try {
   $stmt = $pdo->query("SELECT * FROM social_media_links WHERE is_active = TRUE");
   $socialLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
   $socialLinks = [];
   // error_log('Database error: ' . $e->getMessage());
}
// Fetch struktur organisasi image path
$stmt = $pdo->query("SELECT image_path FROM struktur_organisasi");
$struktur = $stmt->fetch(PDO::FETCH_ASSOC);
$image_path = $struktur['image_path'];

// Take link from google drive
function extractDriveFileId($url)
{
   preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $url, $matches);
   return isset($matches[1]) ? $matches[1] : '';
}


function getContactInfo()
{
   global $pdo;
   $stmt = $pdo->query("SELECT * FROM contact_info WHERE is_active = TRUE");
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// // Fetch struktur organisasi image path
// $stmt = $pdo->query("SELECT image_path FROM struktur_organisasi WHERE id = 1");
// $struktur = $stmt->fetch(PDO::FETCH_ASSOC);
// $image_path = $struktur['image_path'];

// Fetch staff profiles
$stmt = $pdo->query("SELECT * FROM profil_staff ORDER BY id");
$staff_profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all struktur organisasi
$stmt = $pdo->query("SELECT judul, image_path, tanggal_upload FROM struktur_organisasi ORDER BY tanggal_upload DESC");
$strukturList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all Tupoksi PDFs
$stmt = $pdo->query("SELECT judul, google_drive_link, tanggal_upload FROM tupoksi_staff ORDER BY tanggal_upload DESC");
$tupoksiList = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!--=============== FAVICON ===============-->
   <link rel="shortcut icon" href="assets/img/logo-smk.png" type="image/x-icon">

   <!--=============== REMIXICONS ===============-->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.4.0/remixicon.css" crossorigin="">

   <!--=============== CSS ===============-->
   <link rel="stylesheet" href="assets/css/styles.css">
   <link rel="stylesheet" href="assets/css/dropdown.css">
   <style>
      .glassmorphism {
         background: rgba(255, 255, 255, 0.25);
         box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
         backdrop-filter: blur(4px);
         -webkit-backdrop-filter: blur(4px);
         border-radius: 10px;
         border: 1px solid rgba(255, 255, 255, 0.18);
      }

      .section-container {
         padding: 2rem;
         max-width: 1120px;
         margin-bottom: 2rem;
         margin-left: auto;
         margin-right: auto;
      }

      /* Styles for mobile view */
      .mobile-view {
         display: block;
      }

      .desktop-view {
         display: none;
      }

      .staff-grid {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
         gap: 1rem;
      }

      .staff-image {
         width: 150px;
         height: 150px;
         border-radius: 10%;
         object-fit: cover;
         margin: 0 auto 1rem;
      }

      /* Tambahan untuk memperbaiki tampilan teks */
      .staff-info h3 {
         text-align: center;
         margin-bottom: 0.5rem;
      }

      .staff-info p {
         margin: 0.25rem 0;
         text-align: center;
      }

      @media (max-width: 768px) {
         .staff-grid {
            grid-template-columns: 1fr;
         }
      }

      /* Styles for desktop view */
      @media (min-width: 769px) {
         .mobile-view {
            display: none;
         }

         .desktop-view {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
         }

         .staff-member {
            width: 30%;
            margin-bottom: 2rem;
         }

         .staff-image-container {
            margin-bottom: 1rem;
         }

         .staff-image {
            width: 100%;
            max-width: 300px;
            height: auto;
            filter: grayscale(100%);
            transition: filter 0.3s ease;
         }

         .staff-image:hover {
            filter: grayscale(0%);
         }

         .staff-name {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
         }

         .staff-position {
            font-weight: bold;
            margin-bottom: 0.5rem;
         }

         .staff-info p {
            margin: 0.25rem 0;
         }

         .staff-container.desktop-view {
            display: flex;
            flex-direction: column;
            align-items: center;
         }

         .staff-member.kepala-sekolah {
            width: 100%;
            max-width: 600px;
            margin-bottom: 2rem;
         }

         .staff-row {
            display: flex;
            justify-content: center;
            gap: 2rem;
            width: 100%;
            margin-bottom: 2rem;
         }

         .staff-row .staff-member {
            width: calc(50% - 1rem);
            max-width: 400px;
         }
      }

      /* Styles PDF Viewer */
      .pdf-container {
         width: 100%;
         max-width: 800px;
         margin: 0 auto;
         background: #f0f0f0;
         padding: 20px;
         border-radius: 8px;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .pdf-container iframe {
         border: none;
      }

      @media (max-width: 768px) {
         .pdf-container {
            padding: 10px;
         }

         .pdf-container iframe {
            height: 500px;
         }
      }
   </style>

   <!-- style tupoksi -->
   <style>
      .struktur-tupoksi__container {
         max-width: 1120px;
         margin: 0 auto;
         padding: 0 1rem;
         text-align: center;
      }

      .struktur-tupoksi__section {
         padding: 4rem 0;
      }

      .struktur__title,
      .tupoksi__title {
         margin-top: 1rem;
         margin-bottom: 1rem;
         font-size: 1.5rem;
         font-weight: bold;
         text-align: center;
      }

      .struktur__image-container,
      .tupoksi__pdf-container {
         margin-top: 2rem;
         display: flex;
         justify-content: center;
         align-items: center;
      }

      .struktur__image {
         max-width: 100%;
         height: auto;
         display: block;
      }

      .tupoksi__pdf-container {
         position: relative;
         padding-bottom: 56.25%;
         /* 16:9 Aspect Ratio */
         height: 0;
         overflow: hidden;
         max-width: 800px;
         margin-left: auto;
         margin-right: auto;
      }

      .tupoksi__pdf-container iframe {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         border: 0;
      }

      @media (max-width: 768px) {
         .struktur-tupoksi__section {
            padding: 2rem 0;
         }

         .tupoksi__pdf-container {
            padding-bottom: 75%;
         }
      }
   </style>

   <!-- style pop up image -->
   <style>
      .image-popup {
         display: none;
         position: fixed;
         z-index: 1000;
         left: 0;
         top: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.9);
         justify-content: center;
         align-items: center;
      }

      .popup-inner {
         position: relative;
         max-width: 90%;
         max-height: 90%;
      }

      .popup-content {
         display: block;
         max-width: 100%;
         max-height: 90vh;
         object-fit: contain;
         transition: transform 0.3s ease;
      }

      .close {
         position: absolute;
         top: -40px;
         right: 0;
         color: #f1f1f1;
         font-size: 40px;
         font-weight: bold;
         cursor: pointer;
         background: none;
         border: none;
         outline: none;
      }

      .close:hover,
      .close:focus {
         color: #bbb;
      }

      .struktur__image {
         cursor: pointer;
         transition: transform 0.3s ease;
      }

      .struktur__image:hover {
         transform: scale(1.05);
      }
   </style>

   <!-- Style pdf i-frame -->
   <style>
      .pdf-container {
         position: relative;
         padding-bottom: 56.25%;
         /* 16:9 Aspect Ratio */
         height: 0;
         overflow: hidden;
      }

      .pdf-container iframe {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         border: 0;
      }

      @media (max-width: 768px) {
         .pdf-container {
            padding-bottom: 75%;
            /* Adjust for mobile, closer to 4:3 ratio */
         }
      }
   </style>

   <title>SMK NEGERI 1 BOLAANG</title>
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
                  <a href="#struktur" class="nav__link active-link">Struktur Organisasi</a>
               </li>

               <li class="nav__item">
                  <a href="#tupoksi" class="nav__link">Tupoksi Staff</a>
               </li>

               <li class="nav__item">
                  <a href="#profil-staff" class="nav__link">Profil Staff</a>
               </li>

               <li class="nav__item">
                  <a href="#contact" class="nav__link">Kontak</a>
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
      <!--==================== STRUKTUR ====================-->
      <section class="struktur-tupoksi__section" id="struktur">
         <div class="struktur-tupoksi__container">
            <h2 class="section__title-1">
               <span>Struktur Organisasi</span>
            </h2>
            <?php foreach ($strukturList as $struktur): ?>
               <div class="struktur__item">
                  <h3 class="struktur__title"><?php echo htmlspecialchars($struktur['judul']); ?></h3>
                  <!-- <p class="struktur__date">Tanggal Upload: <?php echo htmlspecialchars($struktur['tanggal_upload']); ?></p> -->
                  <div class="struktur__image-container">
                     <img class="struktur__image" src="<?php echo htmlspecialchars($struktur['image_path']); ?>" alt="Struktur Organisasi" style="cursor: pointer;">
                  </div>
               </div>
            <?php endforeach; ?>
            <?php if (empty($strukturList)): ?>
               <p>Belum ada data struktur organisasi.</p>
            <?php endif; ?>
         </div>
      </section>

      <!-- Tupoksi Section -->
      <section class="struktur-tupoksi__section" id="tupoksi">
         <div class="struktur-tupoksi__container">
            <h2 class="section__title-1">Tupoksi Staff</h2>
            <?php foreach ($tupoksiList as $tupoksi): ?>
               <div class="tupoksi__item">
                  <h3 class="tupoksi__title"><?php echo htmlspecialchars($tupoksi['judul']); ?></h3>
                  <!-- <p class="tupoksi__date">Tanggal Upload: <?php echo htmlspecialchars($tupoksi['tanggal_upload']); ?></p> -->
                  <div class="tupoksi__pdf-container">
                     <iframe src="https://drive.google.com/file/d/<?php echo extractDriveFileId($tupoksi['google_drive_link']); ?>/preview" allow="autoplay"></iframe>
                  </div>
               </div>
            <?php endforeach; ?>
            <?php if (empty($tupoksiList)): ?>
               <p>Belum ada data tupoksi staff.</p>
            <?php endif; ?>
         </div>
      </section>

      <!-- Profil Staff Section -->
      <section class="struktur section-container" id="profil-staff">
         <h2 class="section__title-1">Profil Staff Manajemen</h2>

         <!-- Tampilan Mobile -->
         <div class="staff-container mobile-view">
            <div class="staff-grid">
               <?php foreach ($staff_profiles as $staff): ?>
                  <div class="">
                     <img src="assets/img/<?php echo htmlspecialchars($staff['lokasi_foto']); ?>" alt="<?php echo htmlspecialchars($staff['nama']); ?>" class="staff-image">
                     <div class="staff-info">
                        <h3><?php echo htmlspecialchars($staff['nama']); ?></h3>
                        <p><strong><?php echo htmlspecialchars($staff['jabatan']); ?></strong></p>
                        <p>Status: <?php echo htmlspecialchars($staff['status']); ?></p>
                        <p>Mata Pelajaran: <?php echo htmlspecialchars($staff['mata_pelajaran']); ?></p>
                        <p>Lama Mengajar: <?php echo htmlspecialchars($staff['lama_mengajar']); ?> tahun</p>
                        <p>Pangkat: <?php echo htmlspecialchars($staff['pangkat']); ?></p>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>

         <!-- Tampilan Desktop -->
         <div class="staff-container desktop-view">
            <?php
            $kepala_sekolah = null;
            $other_staff = [];
            foreach ($staff_profiles as $staff) {
               if (strtolower($staff['jabatan']) === 'kepala sekolah') {
                  $kepala_sekolah = $staff;
               } else {
                  $other_staff[] = $staff;
               }
            }
            ?>

            <?php if ($kepala_sekolah): ?>
               <!-- Kepala Sekolah -->
               <div class="staff-member kepala-sekolah">
                  <div class="staff-image-container">
                     <img src="assets/img/<?php echo htmlspecialchars($kepala_sekolah['lokasi_foto']); ?>" alt="<?php echo htmlspecialchars($kepala_sekolah['nama']); ?>" class="staff-image">
                  </div>
                  <div class="staff-info">
                     <h3 class="staff-name"><?php echo htmlspecialchars($kepala_sekolah['nama']); ?></h3>
                     <p class="staff-position"><?php echo htmlspecialchars($kepala_sekolah['jabatan']); ?></p>
                     <p><?php echo htmlspecialchars($kepala_sekolah['status']); ?></p>
                     <p>Mata Pelajaran: <?php echo htmlspecialchars($kepala_sekolah['mata_pelajaran']); ?></p>
                     <p>Lama Mengajar: <?php echo htmlspecialchars($kepala_sekolah['lama_mengajar']); ?> Tahun</p>
                     <p>Pangkat: <?php echo htmlspecialchars($kepala_sekolah['pangkat']); ?></p>
                  </div>
               </div>
            <?php endif; ?>

            <!-- Staff Lainnya -->
            <?php
            $count = 0;
            foreach ($other_staff as $staff):
               if ($count % 2 == 0) echo '<div class="staff-row">';
            ?>
               <div class="staff-member">
                  <div class="staff-image-container">
                     <img src="assets/img/<?php echo htmlspecialchars($staff['lokasi_foto']); ?>" alt="<?php echo htmlspecialchars($staff['nama']); ?>" class="staff-image">
                  </div>
                  <div class="staff-info">
                     <h3 class="staff-name"><?php echo htmlspecialchars($staff['nama']); ?></h3>
                     <p class="staff-position"><?php echo htmlspecialchars($staff['jabatan']); ?></p>
                     <p><?php echo htmlspecialchars($staff['status']); ?></p>
                     <p>Mata Pelajaran: <?php echo htmlspecialchars($staff['mata_pelajaran']); ?></p>
                     <p>Lama Mengajar: <?php echo htmlspecialchars($staff['lama_mengajar']); ?> Tahun</p>
                     <p>Pangkat: <?php echo htmlspecialchars($staff['pangkat']); ?></p>
                  </div>
               </div>
            <?php
               $count++;
               if ($count % 2 == 0 || $count == count($other_staff)) echo '</div>';
            endforeach;
            ?>
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
   <script src="https://cdn.jsdelivr.net/npm/medium-zoom/dist/medium-zoom.min.js"></script>


   <!--=============== MAIN JS ===============-->
   <script src="assets/js/main.js"></script>
   <script src="assets/js/dropdown.js"></script>

   <!-- Script Contact -->
   <script>
      ScrollReveal().reveal('.section-container', {
         delay: 300,
         distance: '50px'
      });
      ScrollReveal().reveal('.', {
         delay: 300,
         interval: 100
      });

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
   <!-- Script Popup Image -->
   <div id="imagePopup" class="image-popup">
      <div class="popup-inner">
         <img class="popup-content" id="popupImage">
         <button class="close">&times;</button>
      </div>
   </div>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const popup = document.getElementById('imagePopup');
         const popupImg = document.getElementById('popupImage');
         const closeBtn = document.querySelector('.close');

         let scale = 1;

         function setTransform() {
            popupImg.style.transform = `scale(${scale})`;
         }

         function openPopup(imageSrc) {
            popupImg.src = imageSrc;
            popup.style.display = 'flex';
            scale = 1;
            setTransform();
         }

         function closePopup() {
            popup.style.display = 'none';
            scale = 1;
         }

         closeBtn.addEventListener('click', closePopup);

         popup.addEventListener('click', function(e) {
            if (e.target === popup) {
               closePopup();
            }
         });

         popupImg.addEventListener('wheel', function(e) {
            e.preventDefault();
            const delta = Math.sign(e.deltaY);
            scale += delta * -0.1;
            scale = Math.min(Math.max(0.5, scale), 3);
            setTransform();
         });

         document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
               closePopup();
            }
         });

         // Tambahkan event listener untuk semua gambar struktur organisasi
         document.querySelectorAll('.struktur__image').forEach(img => {
            img.addEventListener('click', function() {
               openPopup(this.src);
            });
         });
      });
   </script>
   <!-- Script Navbar -->
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const sections = document.querySelectorAll('section[id]');
         const navLinks = document.querySelectorAll('.nav__link');

         function changeLinkState() {
            let index = sections.length;

            while (--index && window.scrollY + 50 < sections[index].offsetTop) {}

            navLinks.forEach((link) => link.classList.remove('active-link'));

            // Cari link yang sesuai dengan section saat ini
            const currentSectionId = sections[index] ? sections[index].id : null;
            const currentLink = Array.from(navLinks).find(link => link.getAttribute('href') === `#${currentSectionId}`);

            if (currentLink) {
               currentLink.classList.add('active-link');
            } else if (index === 0) {
               // Jika di atas semua section, aktifkan link Struktur Organisasi
               navLinks[1].classList.add('active-link'); // Indeks 1 karena 0 adalah Beranda
            }
         }

         window.addEventListener('scroll', changeLinkState);

         // Smooth scrolling untuk link internal
         navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
               const href = this.getAttribute('href');

               if (href.startsWith('#')) {
                  e.preventDefault();
                  const targetSection = document.querySelector(href);

                  if (targetSection) {
                     targetSection.scrollIntoView({
                        behavior: 'smooth'
                     });
                  }
               }
            });
         });

         // Set Struktur Organisasi sebagai aktif saat halaman dimuat
         navLinks[1].classList.add('active-link');
      });
   </script>


</body>

</html>