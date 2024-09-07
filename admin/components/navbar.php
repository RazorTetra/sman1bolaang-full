<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../assets/css/output.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">
    <header class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex items-center justify-between">
            <h1 class="text-xl font-semibold">Admin Dashboard</h1>
            <button id="menu-toggle" class="lg:hidden flex items-center px-3 py-2 border rounded text-gray-200 border-gray-700 hover:text-white hover:border-white">
                <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M3 6h14M3 12h14M3 18h14" />
                </svg>
            </button>
            <nav id="menu" class="hidden lg:flex lg:items-center lg:space-x-6">
                <ul class="flex space-x-6">
                    <li><a href="index.php" class="hover:text-gray-400">Dashboard</a></li>
                    <li><a href="../admin/manage_about.php" class="hover:text-gray-400">Kelola About</a></li>
                    <li><a href="../admin/manage_news.php" class="hover:text-gray-400">Kelola Berita</a></li>
                    <li><a href="../admin/manage_gallery.php" class="hover:text-gray-400">Kelola Galeri</a></li>
                    <li><a href="../admin/kotak_masukan.php" class="hover:text-gray-400">Kotak Masukan</a></li>
                    <li><a href="../admin/manage_sosmed.php" class="hover:text-gray-400">Kelola Link</a></li>
                    <li><a href="../admin/manage_struktur.php" class="hover:text-gray-400">Kelola Struktur</a></li>
                    <li><a href="../functions/logout.php" class="hover:text-gray-400">Logout</a></li>
                </ul>
            </nav>
        </div>
        <div id="mobile-menu" class="lg:hidden fixed top-0 left-0 w-full bg-gray-800 text-white p-4 z-50 hidden">
            <button id="menu-close" class="flex items-center px-3 py-2 border rounded text-gray-200 border-gray-700 hover:text-white hover:border-white absolute top-4 right-4">
                <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M6 6L14 14M6 14L14 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <ul class="space-y-4 mt-8">
                <li><a href="index.php" class="hover:text-gray-400">Dashboard</a></li>
                <li><a href="../admin/manage_about.php" class="hover:text-gray-400">Kelola About</a></li>
                <li><a href="../admin/manage_news.php" class="hover:text-gray-400">Kelola Berita</a></li>
                <li><a href="../admin/manage_gallery.php" class="hover:text-gray-400">Kelola Galeri</a></li>
                <li><a href="../admin/kotak_masukan.php" class="hover:text-gray-400">Kotak Masukan</a></li>
                <li><a href="../admin/manage_sosmed.php" class="hover:text-gray-400">Kelola Link</a></li>
                <li><a href="../admin/manage_struktur.php" class="hover:text-gray-400">Kelola Struktur</a></li>
                <li><a href="../functions/logout.php" class="hover:text-gray-400">Logout</a></li>
            </ul>
        </div>
    </header>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            var mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        document.getElementById('menu-close').addEventListener('click', function() {
            var mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.add('hidden');
        });
    </script>
</body>

</html>