<?php
require_once('config.php');

$title = isset($_GET['title']) ? $_GET['title'] : '';
$original_title = str_replace('-', ' ', urldecode($title));

// Ambil data artikel
$stmt = $pdo->prepare("SELECT id, title, content, image, created_at FROM articles WHERE LOWER(title) = LOWER(:title)");
$stmt->execute(['title' => $original_title]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    die("Article not found");
}

$id = $news['id'];

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
}
?>

<!-- Share Social Media -->
<?php
function generateShareLinks($url, $title, $content, $imageUrl = null)
{
    $encodedUrl = urlencode($url);
    $encodedTitle = urlencode($title);

    // Create a compelling description
    $description = createCompellingDescription($content);
    $encodedDescription = urlencode($description);

    // Create an engaging WhatsApp message
    $whatsappMessage = createWhatsAppMessage($title, $description, $url);
    $encodedWhatsappMessage = urlencode($whatsappMessage);

    $facebookUrl = "https://www.facebook.com/sharer/sharer.php?u={$encodedUrl}&quote={$encodedTitle}";
    $whatsappUrl = "https://api.whatsapp.com/send?text={$encodedWhatsappMessage}";

    return [
        'facebook' => $facebookUrl,
        'whatsapp' => $whatsappUrl
    ];
}

function createCompellingDescription($content)
{
    $cleanContent = strip_tags($content);
    $words = str_word_count($cleanContent, 1);
    $shortDescription = implode(' ', array_slice($words, 0, 20));
    return $shortDescription . "...";
}

function createWhatsAppMessage($title, $description, $url)
{
    $emoji = getRelevantEmoji($title);
    $hashtag = createHashtag($title);

    // return "*{$title}*\n\n" .
    //     "{$emoji} *{$title}*\n\n" .
    //     "🔗 Baca selengkapnya: {$url}";
    return
        "{$emoji} *{$title}*\n\n" .
        "{$description}\n\n" .
        "🔗 Baca selengkapnya: {$url}\n\n" .
        "Jangan lupa share ke teman-temanmu ya! 👍\n" .
        "{$hashtag}";
    // return $url;
}

function getRelevantEmoji($title)
{
    $keywords = [
        'prestasi' => '🏆',
        'lomba' => '🥇',
        'teknologi' => '💻',
        'olahraga' => '⚽',
        'seni' => '🎨',
        'wisuda' => '🎓'
    ];

    foreach ($keywords as $keyword => $emoji) {
        if (stripos($title, $keyword) !== false) {
            return $emoji;
        }
    }

    return '📢';
}

function createHashtag($title)
{
    $words = explode(' ', strtolower($title));
    $hashtag = '#SMKN1Bolaang';
    if (count($words) > 0) {
        $hashtag .= ucfirst($words[0]);
    }
    return $hashtag;
}

// Usage:
$url_friendly_title = urlencode(strtolower(str_replace(' ', '-', $news['title'])));
$currentUrl = "https://$_SERVER[HTTP_HOST]" . dirname($_SERVER['PHP_SELF']) . "/news_detail.php?title=" . $url_friendly_title;
$shareLinks = generateShareLinks($currentUrl, $news['title'], $news['content']);
$fullImageUrl = 'https://mahasiswa-it.com/Sekolah/' . $news['image'];
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="<?php echo htmlspecialchars($news['title']); ?> - SMKN 1 Bolaang">
    <meta property="og:description" content="<?php echo htmlspecialchars(substr(strip_tags($news['content']), 0, 200)) . '...'; ?>">
    <meta property="og:image" content="<?php echo $fullImageUrl; ?>">
    <meta property="og:url" content="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="fb:app_id" content="827402309596938">

    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="assets/img/logo-smk.png" type="image/x-icon">

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.4.0/remixicon.css" crossorigin="">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

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
    <title><?php echo htmlspecialchars($news['title']); ?> - SMKN 1 Bolaang</title>


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
                        <img src="<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" class="w-full h-auto object-cover rounded-lg mb-6">
                    <?php endif; ?>
                    <div class="prose max-w-none mb-6">
                        <?php echo nl2br(htmlspecialchars($news['content'])); ?>
                    </div>

                    <div class="mt-6 mb-6 p-4 flex flex-wrap items-center justify-end gap-4">
                        <h3 class="text-lg font-bold">Bagikan Artikel :</h3>
                        <div class="flex flex-wrap gap-4">
                            <a href="<?php echo $shareLinks['facebook']; ?>" target="_blank" class="hover:opacity-80 transition duration-300" title="Bagikan ke Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z" fill="#1877F2" />
                                </svg>
                            </a>
                            <a href="<?php echo $shareLinks['whatsapp']; ?>" target="_blank" class="hover:opacity-80 transition duration-300" title="Bagikan ke WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 1.856.001 3.598.723 4.907 2.034 1.31 1.311 2.031 3.054 2.03 4.908-.001 3.825-3.113 6.938-6.937 6.938z" fill="#25D366" />
                                </svg>
                            </a>
                            <button id="copyLinkBtn" class="hover:opacity-80 transition duration-300" title="Salin Link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#000000">
                                    <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z" />
                                </svg>
                            </button>
                        </div>
                    </div>


                    <!-- Comment form -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-4">Tambahkan Komentar</h3>
                        <form id="commentForm">
                            <input type="hidden" name="article_id" value="<?php echo $id; ?>">
                            <div class="mb-4">
                                <label for="user_name" class="block mb-2">Nama:</label>
                                <input type="text" id="user_name" name="user_name" required class="w-full p-2 border rounded">
                            </div>
                            <div class="mb-4">
                                <label for="comment" class="block mb-2 ">Komentar:</label>
                                <textarea id="comment" name="comment" required class="w-full p-2 border rounded   resize-none h-32"></textarea>
                            </div>
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Kirim Komentar</button>
                        </form>
                    </div>

                    <!-- Comments section -->
                    <div id="commentsSection">
                        <h3 class="text-xl font-bold mb-4">Komentar (<?php echo count($comments); ?>)</h3>
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            <?php foreach ($comments as $comment): ?>
                                <div class="p-4 rounded bg-white">
                                    <p class="font-bold text-black"><?php echo htmlspecialchars($comment['user_name']); ?></p>
                                    <p class="text-sm text-black"><?php echo date('d F Y H:i', strtotime($comment['created_at'])); ?></p>
                                    <p class="mt-2 text-black"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
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
                                <?php
                                $url_friendly_title = urlencode(strtolower(str_replace(' ', '-', $article['title'])));
                                ?>
                                <a href="news_detail.php?title=<?php echo $url_friendly_title; ?>" class="inline-block font-semibold hover:underline">Baca Selengkapnya</a>
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
    <script>
        // Handle copy link button
        $('#copyLinkBtn').click(function() {
            var dummy = document.createElement('input'),
                text = window.location.href;

            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);

            Swal.fire({
                icon: 'success',
                title: 'Link Disalin!',
                text: 'Link artikel telah disalin ke clipboard.',
                showConfirmButton: false,
                timer: 1500
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var whatsappLink = document.querySelector('a[href^="https://wa.me/"]');
            if (whatsappLink) {
                whatsappLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    var width = 550;
                    var height = 420;
                    var left = (screen.width / 2) - (width / 2);
                    var top = (screen.height / 2) - (height / 2);
                    window.open(this.href, 'whatsapp-share', 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top);
                });
            }
        });
    </script>
</body>



</html>