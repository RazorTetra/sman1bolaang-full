<?php
require_once('config.php');


// Fungsi untuk mendapatkan info kontak (diperlukan untuk footer)
function getContactInfo()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM contact_info WHERE is_active = TRUE");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ambil data social media links (diperlukan untuk footer)
try {
    $stmt = $pdo->query("SELECT * FROM social_media_links WHERE is_active = TRUE");
    $socialLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $socialLinks = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($skill['title']); ?> - SMK NEGERI 1 BOLAANG</title>

    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="assets/img/logo-smk.png" type="image/x-icon">

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.4.0/remixicon.css" crossorigin="">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="assets/css/output.css" rel="stylesheet">
    <style>
        .nav__buttons {
            padding-right: 20px;
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

        .skill-detail-section {
            padding: 4rem 1.5rem;
        }

        .skill-detail-container {
            max-width: 64rem;
            margin: 0 auto;
        }

        .skill-detail-image-wrapper {
            margin-bottom: 3rem;
        }

        .skill-detail-image {
            width: 100%;
            height: 16rem;
            object-fit: cover;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .skill-detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        .skill-detail-title {
            font-size: 2rem;
            font-weight: bold;
        }

        .skill-detail-icon {
            font-size: 2.5rem;
            color: var(--first-color);
        }

        .skill-detail-content {
            background-color: var(--container-color);
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 3rem;
        }

        .skill-detail-description {
            font-size: 1rem;
            line-height: 1.5;
        }

        .skill-detail-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .skill-detail-back-button {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border: 1px solid currentColor;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .skill-detail-back-button:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .skill-detail-share-button {
            padding: 0.5rem;
            border-radius: 9999px;
            transition: all 0.3s;
        }

        .skill-detail-share-button:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        @media screen and (min-width: 768px) {
            .skill-detail-section {
                padding: 6rem 1.5rem;
            }

            .skill-detail-title {
                font-size: 2.5rem;
            }

            .skill-detail-content {
                padding: 2rem;
            }

            .skill-detail-description {
                font-size: 1.125rem;
            }

            .skill-detail-back-button {
                font-size: 1rem;
            }
        }

        @media screen and (min-width: 1024px) {
            .skill-detail-title {
                font-size: 3rem;
            }
        }

        @media (min-width: 1150px) {
            .nav__toggle {
                display: none;
            }
        }
    </style>
    
</head>

<body>
    <!--==================== HEADER ====================-->
    <?php include 'components/header.php'; ?>
    <?php
    // Ambil ID skill dari parameter URL
    $skill_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $skill = getSkillDetails($pdo, $skill_id);
    // Jika skill tidak ditemukan, redirect ke halaman utama
    if (!$skill) {
        header("Location: index.php");
        exit();
    }

    ?>
    <!--==================== MAIN ====================-->
    <main class="main">
        <section class="section skill-detail-section">
            <div class="skill-detail-container">
                <?php if (!empty($skill['image'])): ?>
                    <div class="skill-detail-image-wrapper">
                        <img src="assets/img/<?php echo htmlspecialchars($skill['image']); ?>" alt="<?php echo htmlspecialchars($skill['title']); ?>" class="skill-detail-image">
                    </div>
                <?php endif; ?>

                <div class="skill-detail-header">
                    <h1 class="skill-detail-title"><?php echo htmlspecialchars($skill['title']); ?></h1>
                    <?php if (!empty($skill['icon'])): ?>
                        <i class="<?php echo htmlspecialchars($skill['icon']); ?> skill-detail-icon"></i>
                    <?php endif; ?>
                </div>

                <div class="skill-detail-content">
                    <p class="skill-detail-description">
                        <?php echo !empty($skill['description']) ? nl2br(htmlspecialchars($skill['description'])) : 'Deskripsi tidak tersedia.'; ?>
                    </p>
                </div>

                <div class="skill-detail-footer">
                    <a href="index.php#skills" class="skill-detail-back-button">
                        <i class="ri-arrow-left-line"></i> Kembali ke Daftar Keahlian
                    </a>

                    <div class="skill-detail-share">
                        <button class="skill-detail-share-button">
                            <i class="ri-share-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!--==================== FOOTER ====================-->
    <footer class="footer">
        <div class="footer__container container grid">
            <div>
                <a href="index.php" class="footer__logo">
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
                            <a href="index.php#about" class="footer__link">Tentang Kami</a>
                        </li>

                        <li>
                            <a href="index.php#skills" class="footer__link">Keahlian</a>
                        </li>

                        <li>
                            <a href="index.php#news" class="footer__link">Berita</a>
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

    <!--=============== MAIN JS ===============-->
    <script src="assets/js/main.js"></script>
</body>

</html>