<?php
include_once "../admin/function_manage_struktur.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Struktur Organisasi - Admin</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">
    <style>
        .table-cell-truncate {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Manage Struktur Organisasi</h1>

        <!-- Struktur Organisasi Section -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">Kelola Struktur Organisasi</h2>
            <button class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg mb-4 transition duration-300" onclick="openStrukturModal('add')">
                Tambah Struktur Baru
            </button>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b text-left">Judul</th>
                            <th class="py-2 px-4 border-b text-left">Gambar</th>
                            <th class="py-2 px-4 border-b text-left">Tanggal Upload</th>
                            <th class="py-2 px-4 border-b text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($strukturData['data'] as $item): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($item['judul']); ?></td>
                                <td class="py-2 px-4 border-b"><img src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="Struktur" class="w-20 h-auto"></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($item['tanggal_upload']); ?></td>
                                <td class="py-2 px-4 border-b">
                                    <button onclick="editStruktur(<?php echo $item['id']; ?>)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded text-xs mr-1">Edit</button>
                                    <button onclick="deleteStruktur(<?php echo $item['id']; ?>)" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded text-xs">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <?php for ($i = 1; $i <= $strukturData['totalPages']; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Tupoksi Section -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">Kelola Tupoksi Staff</h2>
            <button class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg mb-4 transition duration-300" onclick="openTupoksiModal('add')">
                Tambah Tupoksi Baru
            </button>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b text-left">Judul</th>
                            <th class="py-2 px-4 border-b text-left">Link Google Drive</th>
                            <th class="py-2 px-4 border-b text-left">Tanggal Upload</th>
                            <th class="py-2 px-4 border-b text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tupoksiData['data'] as $item): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($item['judul']); ?></td>
                                <td class="py-2 px-4 border-b"><a href="<?php echo htmlspecialchars($item['google_drive_link']); ?>" target="_blank" class="text-blue-500 hover:underline">Lihat PDF</a></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($item['tanggal_upload']); ?></td>
                                <td class="py-2 px-4 border-b">
                                    <button onclick="editTupoksi(<?php echo $item['id']; ?>)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded text-xs mr-1">Edit</button>
                                    <button onclick="deleteTupoksi(<?php echo $item['id']; ?>)" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded text-xs">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <?php for ($i = 1; $i <= $tupoksiData['totalPages']; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Staff Profiles Section -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">Kelola Profil Staff</h2>
            <button class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg mb-4 transition duration-300" onclick="openModal()">
                Tambah Staff Baru
            </button>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b text-left">Foto</th>
                            <th class="py-2 px-4 border-b text-left">Nama</th>
                            <th class="py-2 px-4 border-b text-left">Jabatan</th>
                            <th class="py-2 px-4 border-b text-left">Pendidikan</th>
                            <th class="py-2 px-4 border-b text-left">Status</th>
                            <th class="py-2 px-4 border-b text-left">Mata Pelajaran</th>
                            <th class="py-2 px-4 border-b text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result_staff as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border-b">
                                    <img src="../assets/img/<?php echo htmlspecialchars($row['lokasi_foto']); ?>" alt="Foto <?php echo htmlspecialchars($row['nama']); ?>" class="w-6 h-8 object-cover">
                                </td>
                                <td class="py-2 px-4 border-b table-cell-truncate" title="<?php echo htmlspecialchars($row['nama']); ?>"><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td class="py-2 px-4 border-b table-cell-truncate" title="<?php echo htmlspecialchars($row['jabatan']); ?>"><?php echo htmlspecialchars($row['jabatan']); ?></td>
                                <td class="py-2 px-4 border-b table-cell-truncate" title="<?php echo htmlspecialchars($row['riwayat_pendidikan']); ?>"><?php echo htmlspecialchars($row['riwayat_pendidikan']); ?></td>
                                <td class="py-2 px-4 border-b table-cell-truncate" title="<?php echo htmlspecialchars($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></td>
                                <td class="py-2 px-4 border-b table-cell-truncate" title="<?php echo htmlspecialchars($row['mata_pelajaran']); ?>"><?php echo htmlspecialchars($row['mata_pelajaran']); ?></td>
                                <td class="py-2 px-4 border-b">
                                    <button onclick="viewStaffDetail(<?php echo $row['id']; ?>)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded text-xs mr-1">Lihat</button>
                                    <button onclick="editStaff(<?php echo $row['id']; ?>)" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-2 rounded text-xs mr-1">Edit</button>
                                    <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded text-xs">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Struktur Modal -->
    <div id="strukturModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="strukturModalLabel">Tambah/Edit Struktur Organisasi</h3>
                <form id="strukturForm" method="POST" enctype="multipart/form-data" class="mt-2">
                    <input type="hidden" name="aksi" value="tambah_struktur">
                    <input type="hidden" name="id" id="struktur_id">
                    <div class="mt-2">
                        <label for="struktur_judul" class="block text-sm font-medium text-gray-700 text-left">Judul:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="struktur_judul" name="judul" required>
                    </div>
                    <div class="mt-2">
                        <label for="struktur_image" class="block text-sm font-medium text-gray-700 text-left">Gambar:</label>
                        <input type="file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="struktur_image" name="image" accept="image/*">
                    </div>
                    <div class="mt-4 flex justify-between">
                        <button type="button" onclick="closeStrukturModal()" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tupoksi Modal -->
    <div id="tupoksiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="tupoksiModalLabel">Tambah/Edit Tupoksi Staff</h3>
                <form id="tupoksiForm" method="POST" class="mt-2">
                    <input type="hidden" name="aksi" value="tambah_tupoksi">
                    <input type="hidden" name="id" id="tupoksi_id">
                    <div class="mt-2">
                        <label for="tupoksi_judul" class="block text-sm font-medium text-gray-700 text-left">Judul:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="tupoksi_judul" name="judul" required>
                    </div>
                    <div class="mt-2">
                        <label for="tupoksi_link" class="block text-sm font-medium text-gray-700 text-left">Link Google Drive:</label>
                        <input type="url" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="tupoksi_link" name="google_drive_link" required>
                    </div>
                    <div class="mt-4 flex justify-between">
                        <button type="button" onclick="closeTupoksiModal()" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Staff Modal -->
    <div id="staffModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="staffModalLabel">Tambah/Edit Profil Staff</h3>
                <form id="staffForm" method="POST" enctype="multipart/form-data" class="mt-2">
                    <input type="hidden" name="aksi" value="tambah_staff">
                    <input type="hidden" name="id" id="staff_id">
                    <div class="mt-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700 text-left">Nama:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="nama" name="nama" required>
                    </div>
                    <!-- <div class="mt-2">
                        <label for="gelar" class="block text-sm font-medium text-gray-700 text-left">Gelar:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="gelar" name="gelar">
                    </div> -->
                    <div class="mt-2">
                        <label for="jabatan" class="block text-sm font-medium text-gray-700 text-left">Jabatan:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="jabatan" name="jabatan" required>
                    </div>
                    <div class="mt-2">
                        <label for="riwayat_pendidikan" class="block text-sm font-medium text-gray-700 text-left">Riwayat Pendidikan:</label>
                        <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="riwayat_pendidikan" name="riwayat_pendidikan" required></textarea>
                    </div>
                    <div class="mt-2">
                        <label for="status" class="block text-sm font-medium text-gray-700 text-left">Status:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="status" name="status" required>
                    </div>
                    <div class="mt-2">
                        <label for="mata_pelajaran" class="block text-sm font-medium text-gray-700 text-left">Mata Pelajaran:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="mata_pelajaran" name="mata_pelajaran" required>
                    </div>
                    <div class="mt-2">
                        <label for="lama_mengajar" class="block text-sm font-medium text-gray-700 text-left">Lama Mengajar (Tahun):</label>
                        <input type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="lama_mengajar" name="lama_mengajar" required>
                    </div>
                    <div class="mt-2">
                        <label for="pangkat" class="block text-sm font-medium text-gray-700 text-left">Pangkat:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="pangkat" name="pangkat" required>
                    </div>
                    <div class="mt-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 text-left">Alamat:</label>
                        <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="alamat" name="alamat" required></textarea>
                    </div>
                    <div class="mt-2">
                        <label for="motto" class="block text-sm font-medium text-gray-700 text-left">Motto:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="motto" name="motto" required>
                    </div>
                    <div class="mt-2">
                        <label for="foto" class="block text-sm font-medium text-gray-700 text-left">Foto:</label>
                        <input type="file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="foto" name="foto">
                    </div>
                    <div class="mt-4 flex justify-between">
                        <button type="button" onclick="closeModal()" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- See all data -->
    <div id="staffDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-6 border w-11/12 max-w-md min-w-[300px] shadow-lg rounded-md bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900" id="staffDetailName"></h3>
                <button onclick="closeStaffDetailModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex flex-col gap-4" id="staffDetailContent">
                <!-- Content will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div id="confirmDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus profil ini?</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmDeleteButton" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md mr-2 shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Hapus
                    </button>
                    <button onclick="closeConfirmDeleteModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Pesan</h3>
                <div class="mt-2 px-7 py-3">
                    <p id="modalMessage" class="text-sm text-gray-500"></p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeMessageModal" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mesaage Modal style 2 -->
    <div id="notificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Notifikasi</h3>
                <div class="mt-2 px-7 py-3">
                    <p id="notificationMessage" class="text-sm text-gray-500"></p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeNotificationModal" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script staff profil -->
    <script src="assets/js.manage_struktur.js"></script>
    <script src="assets/js.manage_struktur_tupoksi.js"></script>

    <script>
        <?php if (isset($message) && $message): ?>
            alert("<?php echo addslashes($message); ?>");
        <?php endif; ?>
    </script>



</body>

</html>