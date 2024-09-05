<?php
require_once('config.php'); // Koneksi database

// Ambil data berita dari database untuk artikel terkait
$stmt = $pdo->prepare("SELECT id, title, content, image, created_at FROM articles ORDER BY created_at DESC LIMIT 3");
$stmt->execute();
$related_news_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/styles.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .news-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
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
        }
    </style>
    </style>
    <title>Detail Berita</title>
</head>

<body>
    <!-- Header -->
    <?php include 'components/header.php'; ?>

    <!-- Content of news_detail.php -->
    <main class="pt-[calc(var(--header-height)+2rem)] md:pt-[calc(var(--header-height)+4rem)] px-4 sm:px-6 lg:px-8 mb-8">
        <div class="max-w-7xl mx-auto md:flex md:space-x-8">
            <!-- Main Article -->
            <article class="md:w-2/3 mb-8 md:mb-0">
                <?php
                require_once('config.php');

                $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

                $stmt = $pdo->prepare("SELECT title, content, image, created_at FROM articles WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $news = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($news):
                ?>
                    <h2 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($news['title']); ?></h2>
                    <p class="text-sm mb-4">Dibuat tanggal <?php echo date('d F Y', strtotime($news['created_at'])); ?></p>
                    <?php if ($news['image']): ?>
                        <img src="<?php echo htmlspecialchars($news['image']); ?>" alt="News Image" class="w-full h-auto object-cover rounded-lg mb-6">
                    <?php endif; ?>
                    <div class="prose max-w-none">
                        <?php echo nl2br(htmlspecialchars($news['content'])); ?>
                    </div>
                <?php else: ?>
                    <p class="text-center">Berita tidak ditemukan.</p>
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

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

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
</body>



</html>