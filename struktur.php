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

function getContactInfo() {
   global $pdo;
   $stmt = $pdo->query("SELECT * FROM contact_info WHERE is_active = TRUE");
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
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
   <link href="/assets/css/output.css" rel="stylesheet">


   <title>SMK NEGERI 1 BOLAANG</title>
</head>

<body>
    <!--==================== HEADER ====================-->
    <?php include 'components/header.php'; ?>

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

      <!--==================== PROFIL SEKOLAH ====================-->
      <!-- <section class="profil section" id="profil">
         <div class="profil__container container grid">
            <h2 class="section__title-1">
               <span>Profil Sekolah</span>

            </h2>
         </div>
        </section> -->

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
</body>

</html>