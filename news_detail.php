<?php
require_once('config.php'); // Koneksi database

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data artikel
$stmt = $pdo->prepare("SELECT title, content, image, created_at FROM articles WHERE id = :id");
$stmt->execute(['id' => $id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil jumlah like
$stmt = $pdo->prepare("SELECT COUNT(*) as like_count FROM article_likes WHERE article_id = :id");
$stmt->execute(['id' => $id]);
$like_count = $stmt->fetchColumn();

// Ambil komentar
$stmt = $pdo->prepare("SELECT * FROM article_comments WHERE article_id = :id ORDER BY created_at DESC");
$stmt->execute(['id' => $id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getContactInfo()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM contact_info WHERE is_active = TRUE");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ambil data dari tabel social media links
try {
    $stmt = $pdo->query("SELECT * FROM social_media_links WHERE is_active = TRUE");
    $socialLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $socialLinks = [];
    // error_log('Database error: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="assets/img/logo-smk.png" type="image/x-icon">

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.4.0/remixicon.css" crossorigin="">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/styles.css">

    <link href="assets/css/output.css" rel="stylesheet">

    <style>
        .news-container {
            max-width: 1120px;
            margin-inline: 1.5rem;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
        }

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

        .main-article {
            margin-bottom: 2rem;
        }

        .article-content {
            background-color: var(--container-color);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .article-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .article-date {
            font-size: 0.9rem;
            color: var(--text-color-light);
            margin-bottom: 1rem;
        }

        .article-image {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .article-body {
            line-height: 1.6;
        }

        .related-articles {
            background-color: var(--container-color);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .related-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .related-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .related-item {
            display: flex;
            gap: 1rem;
        }

        .related-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .related-content {
            flex: 1;
        }

        .related-item-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .related-item-date {
            font-size: 0.8rem;
            color: var(--text-color-light);
            margin-bottom: 0.5rem;
        }

        .related-item-excerpt {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .read-more {
            color: var(--first-color);
            font-weight: bold;
            text-decoration: none;
        }

        .read-more:hover {
            text-decoration: underline;
        }


        @media (min-width: 768px) {
            .news-container {
                flex-direction: row;
                gap: 2rem;
            }

            .main-article {
                flex: 2;
                margin-bottom: 0;
            }

            .related-articles {
                flex: 1;
            }

            @media (min-width: 1150px) {
                .nav__toggle {
                    display: none;
                }
            }
        }
    </style>
    <title>Detail Berita</title>
</head>

<body>
    <!--==================== HEADER ====================-->
    <?php include 'components/header.php'; ?>

    <!-- Content of news_detail.php -->
    <main class="pt-[calc(var(--header-height)+2rem)] md:pt-[calc(var(--header-height)+4rem)] px-4 sm:px-6 lg:px-8 mb-8">
        <div class="max-w-7xl mx-auto md:flex md:space-x-8">
            <!-- Main Article -->
            <article class="md:w-2/3 mb-8 md:mb-0">
                <?php if ($news): ?>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-3xl font-bold"><?php echo htmlspecialchars($news['title']); ?></h2>
                        <button id="likeButton" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <span>Like</span>
                            <span id="likeCount" class="ml-2">(<?php echo $like_count; ?>)</span>
                        </button>
                    </div>
                    <p class="text-sm mb-4">Dibuat tanggal <?php echo date('d F Y', strtotime($news['created_at'])); ?></p>
                    <?php if ($news['image']): ?>
                        <img src="<?php echo htmlspecialchars($news['image']); ?>" alt="News Image" class="w-full h-auto object-cover rounded-lg mb-6">
                    <?php endif; ?>
                    <div class="prose max-w-none mb-6">
                        <?php echo nl2br(htmlspecialchars($news['content'])); ?>
                    </div>



                    <!-- Comment form -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Tambahkan Komentar</h3>
                        <form id="commentForm">
                            <input type="hidden" name="article_id" value="<?php echo $id; ?>">
                            <div class="mb-4">
                                <label for="user_name" class="block mb-2 dark:text-white">Nama:</label>
                                <input type="text" id="user_name" name="user_name" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
                            </div>
                            <div class="mb-4">
                                <label for="comment" class="block mb-2 dark:text-white">Komentar:</label>
                                <textarea id="comment" name="comment" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white dark:border-gray-600 resize-none h-32"></textarea>
                            </div>
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Kirim Komentar</button>
                        </form>
                    </div>

                    <!-- Comments section -->
                    <div id="commentsSection">
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Komentar (<?php echo count($comments); ?>)</h3>
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            <?php foreach ($comments as $comment): ?>
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded">
                                    <p class="font-bold dark:text-white"><?php echo htmlspecialchars($comment['user_name']); ?></p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo date('d F Y H:i', strtotime($comment['created_at'])); ?></p>
                                    <p class="mt-2 dark:text-white"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                <?php else: ?>
                    <p class="text-center dark:text-white">Berita tidak ditemukan.</p>
                <?php endif; ?>
            </article>

            <!-- Related Articles Section -->
            <aside class="md:w-1/3">
                <h3 class="text-2xl font-bold mb-6">Baca Juga Artikel Lainnya</h3>
                <div class="space-y-6">
                    <?php
                    $stmt = $pdo->prepare("SELECT id, title, content, image, created_at FROM articles WHERE id != :id ORDER BY created_at DESC LIMIT 3");
                    $stmt->execute(['id' => $id]);
                    $related_news = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($related_news as $article):
                    ?>
                        <div class="flex items-start space-x-4">
                            <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Related Article Image" class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                            <div class="flex-grow">
                                <h4 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($article['title']); ?></h4>
                                <p class="text-sm mb-2"><?php echo date('d F Y', strtotime($article['created_at'])); ?></p>
                                <p class="text-sm mb-4"><?php echo substr(htmlspecialchars($article['content']), 0, 100); ?>...</p>
                                <a href="news_detail.php?id=<?php echo $article['id']; ?>" class="inline-block font-semibold hover:underline">Baca Selengkapnya</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </aside>
        </div>
    </main>

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

    <!-- Script komnetar dan like -->
    <script>
        $(document).ready(function() {
            // Handle like button click
            $('#likeButton').click(function() {
                $.post('functions/article_likes.php', {
                    article_id: <?php echo $id; ?>
                }, function(response) {
                    if (response.success) {
                        $('#likeCount').text(response.likes);
                    }
                }, 'json');
            });
            // Handle add comment
            $('#commentForm').submit(function(e) {
                e.preventDefault();
                $.post('functions/add_comment.php', $(this).serialize(), function(response) {
                    if (response.success) {
                        // Add the new comment to the comments section
                        $('#commentsSection .space-y-4').prepend(
                            '<div class="bg-gray-100 dark:bg-gray-700 p-4 rounded">' +
                            '<p class="font-bold dark:text-white">' + response.user_name + '</p>' +
                            '<p class="text-sm text-gray-600 dark:text-gray-400">' + response.created_at + '</p>' +
                            '<p class="mt-2 dark:text-white">' + response.comment + '</p>' +
                            '</div>'
                        );
                        // Clear the form
                        $('#commentForm')[0].reset();
                        // Update comment count
                        var currentCount = parseInt($('#commentsSection h3').text().match(/\d+/)[0]);
                        $('#commentsSection h3').text('Komentar (' + (currentCount + 1) + ')');
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Komentar Anda telah berhasil ditambahkan.',
                            confirmButtonColor: '#3085d6',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal menambahkan komentar. Silakan coba lagi.',
                            confirmButtonColor: '#3085d6',
                        });
                    }
                }, 'json');
            });
        });
    </script>
</body>



</html>