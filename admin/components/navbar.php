<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../assets/css/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #fff;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .active-page {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <header class="bg-gray-900 text-white shadow-lg" x-data="{ open: false, dropdown: false }">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold">
                    <a href="index.php" class="hover:text-gray-300 transition duration-150 ease-in-out">Admin Dashboard</a>
                </h1> <button @click="open = !open" class="lg:hidden focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
                <nav class="hidden lg:flex lg:items-center lg:space-x-8">
                    <a href="index.php" class="nav-link hover:text-gray-300 transition duration-150 ease-in-out" :class="{'active-page': window.location.pathname.includes('index.php')}">Dashboard</a>

                    <div @click.away="dropdown = false" class="relative" x-data="{ dropdown: false }">
                        <button @click="dropdown = !dropdown" class="nav-link hover:text-gray-300 transition duration-150 ease-in-out flex items-center">
                            <span>Halaman</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="dropdown" class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                            <a href="../admin/manage_about.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" :class="{'font-bold': window.location.pathname.includes('manage_about.php')}">Kelola About</a>
                            <a href="../admin/manage_news.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" :class="{'font-bold': window.location.pathname.includes('manage_news.php')}">Kelola Berita</a>
                            <a href="../admin/manage_struktur.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" :class="{'font-bold': window.location.pathname.includes('manage_struktur.php')}">Kelola Struktur</a>
                            <a href="../admin/manage_skills.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" :class="{'font-bold': window.location.pathname.includes('manage_skills.php')}">Kelola Skills</a>
                            <a href="../admin/manage_gallery.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" :class="{'font-bold': window.location.pathname.includes('manage_gallery.php')}">Kelola Gallery</a>
                        </div>
                    </div>

                    <div @click.away="dropdown = false" class="relative" x-data="{ dropdown: false }">
                        <button @click="dropdown = !dropdown" class="nav-link hover:text-gray-300 transition duration-150 ease-in-out flex items-center">
                            <span>Lainnya</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="dropdown" class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                            <a href="../admin/manage_sosmed.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" :class="{'font-bold': window.location.pathname.includes('manage_sosmed.php')}">Kelola Link</a>
                            <a href="../admin/kotak_masukan.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" :class="{'font-bold': window.location.pathname.includes('kotak_masukan.php')}">Kotak Masukan</a>
                        </div>
                    </div>

                    <a href="../functions/logout.php" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">Logout</a>
                </nav>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="open" class="lg:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="index.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-700 transition duration-150 ease-in-out" :class="{'bg-gray-900': window.location.pathname.includes('index.php')}">Dashboard</a>
                <a href="../admin/manage_about.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-700 transition duration-150 ease-in-out" :class="{'bg-gray-900': window.location.pathname.includes('manage_about.php')}">Kelola About</a>
                <a href="../admin/manage_news.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-700 transition duration-150 ease-in-out" :class="{'bg-gray-900': window.location.pathname.includes('manage_news.php')}">Kelola Berita</a>
                <a href="../admin/manage_struktur.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-700 transition duration-150 ease-in-out" :class="{'bg-gray-900': window.location.pathname.includes('manage_struktur.php')}">Kelola Struktur</a>
                <a href="../admin/manage_skills.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-700 transition duration-150 ease-in-out" :class="{'bg-gray-900': window.location.pathname.includes('manage_skills.php')}">Kelola Skills</a>
                <a href="../admin/manage_gallery.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-700 transition duration-150 ease-in-out" :class="{'bg-gray-900': window.location.pathname.includes('manage_gallery.php')}">Kelola Gallery</a>
                <a href="../admin/manage_sosmed.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-700 transition duration-150 ease-in-out" :class="{'bg-gray-900': window.location.pathname.includes('manage_sosmed.php')}">Kelola Link</a>
                <a href="../admin/kotak_masukan.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-700 transition duration-150 ease-in-out" :class="{'bg-gray-900': window.location.pathname.includes('kotak_masukan.php')}">Kotak Masukan</a>
                <a href="../functions/logout.php" class="block px-3 py-2 rounded-md text-base font-medium bg-red-600 hover:bg-red-700 transition duration-150 ease-in-out">Logout</a>
            </div>
        </div>
    </header>
</body>

</html>