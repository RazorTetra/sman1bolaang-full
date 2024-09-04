<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.7/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <header class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-semibold">Admin Dashboard</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="index.php" class="hover:text-gray-400">Dashboard</a></li>
                    <li><a href="../admin/manage_news.php" class="hover:text-gray-400">Kelola Berita</a></li>
                    <li><a href="../admin/manage_gallery.php" class="hover:text-gray-400">Kelola Galeri</a></li>
                    <li><a href="../functions/logout.php" class="hover:text-gray-400">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
</body>
</html>
