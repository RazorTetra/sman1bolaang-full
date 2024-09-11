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
$stmt = $pdo->query("SELECT image_path FROM struktur_organisasi WHERE id = 1");
$struktur = $stmt->fetch(PDO::FETCH_ASSOC);
$image_path = $struktur['image_path'];

function getContactInfo()
{
   global $pdo;
   $stmt = $pdo->query("SELECT * FROM contact_info WHERE is_active = TRUE");
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch struktur organisasi image path
$stmt = $pdo->query("SELECT image_path FROM struktur_organisasi WHERE id = 1");
$struktur = $stmt->fetch(PDO::FETCH_ASSOC);
$image_path = $struktur['image_path'];

// Fetch Tupoksi PDF
$stmt = $pdo->query("SELECT lokasi_file FROM tupoksi_staff ORDER BY id DESC LIMIT 1");
$tupoksi = $stmt->fetch(PDO::FETCH_ASSOC);
$tupoksi_path = $tupoksi ? $tupoksi['lokasi_file'] : '';

// Fetch staff profiles
$stmt = $pdo->query("SELECT * FROM profil_staff ORDER BY id");
$staff_profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

      .staff-grid {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
         gap: 1rem;
      }

      .staff-card {
         padding: 1rem;
         text-align: center;
      }

      .staff-image {
         width: 150px;
         height: 150px;
         border-radius: 50%;
         object-fit: cover;
         margin: 0 auto 1rem;
      }
   </style>

   <title>SMK NEGERI 1 BOLAANG</title>
</head>

<body>
   <!--==================== HEADER ====================-->


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
                  <a href="index.php#contact" class="nav__link">Kontak</a>
               </li>

               <li class="nav__item dropdown">
                  <a href="javascript:void(0)" class="nav__link dropdown__toggle">
                     Struktur <i class="ri-arrow-down-s-line"></i>
                  </a>
                  <ul class="dropdown__menu">
                     <li><a href="struktur.php#struktur" class="dropdown__link">Struktur struktur</a></li>
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

   <!--==================== MAIN ====================-->
   <main class="main">
      <!--==================== STRUKTUR ====================-->
      <section class="struktur section" id="struktur">
         <div class="struktur__container container grid">
            <h2 class="section__title-1">
               <span>Struktur Organisasi</span>
            </h2>

            <img class="img-struktur mx-auto" src="<?php echo htmlspecialchars($image_path); ?>" alt="struktur">
         </div>
      </section>

      <!-- Tupoksi Section -->
      <section class="struktur section-container" id="tupoksi">
         <h2 class="section__title-1">Tupoksi Staff</h2>
         <?php if ($tupoksi_path): ?>
            <embed src="assets/pdf/<?php echo htmlspecialchars($tupoksi_path); ?>" type="application/pdf" width="100%" height="600px" />
         <?php else: ?>
            <p>Dokumen Tupoksi belum tersedia.</p>
         <?php endif; ?>
      </section>

      <!-- Profil Staff Section -->
      <section class="struktur section-container" id="profil-staff">
         <h2 class="section__title-1">Profil Staff</h2>
         <div class="staff-grid">
            <?php foreach ($staff_profiles as $staff): ?>
               <div class="staff-card">
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
   <script src="assets/js/dropdown.js"></script>
   <script>
      ScrollReveal().reveal('.section-container', {
         delay: 300,
         distance: '50px'
      });
      ScrollReveal().reveal('.staff-card', {
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
</body>

</html>