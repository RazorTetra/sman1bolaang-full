function openModal() {
  document.getElementById("staffModal").classList.remove("hidden");
}

function closeModal() {
  document.getElementById("staffModal").classList.add("hidden");
  document.getElementById("staffForm").reset();
}

function editStaff(id) {
  fetch(`get_staff_data.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      document.getElementById("staff_id").value = id;
      document.querySelector('#staffForm input[name="aksi"]').value =
        "edit_staff";
      document.getElementById("staffModalLabel").textContent =
        "Edit Profil Staff";

      document.getElementById("nama").value = data.nama;
      // document.getElementById('gelar').value = data.gelar;
      document.getElementById("jabatan").value = data.jabatan;
      document.getElementById("riwayat_pendidikan").value =
        data.riwayat_pendidikan;
      document.getElementById("status").value = data.status;
      document.getElementById("mata_pelajaran").value = data.mata_pelajaran;
      document.getElementById("lama_mengajar").value = data.lama_mengajar;
      document.getElementById("pangkat").value = data.pangkat;
      document.getElementById("alamat").value = data.alamat;
      document.getElementById("motto").value = data.motto;

      openModal();
    })
    .catch((error) => {
      console.error("Error:", error);
      showMessage("Terjadi kesalahan saat mengambil data staff");
    });
}

function confirmDelete(id) {
  document.getElementById("confirmDeleteModal").classList.remove("hidden");
  document.getElementById("confirmDeleteButton").onclick = function () {
    deleteStaff(id);
  };
}

function closeConfirmDeleteModal() {
  document.getElementById("confirmDeleteModal").classList.add("hidden");
}

function viewStaffDetail(id) {
  fetch(`get_staff_data.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      const contentDiv = document.getElementById("staffDetailContent");
      contentDiv.innerHTML = `
        <div class="w-full text-center">
            <div class="w-40 h-40 mx-auto mb-4">
                <img src="../assets/img/${data.lokasi_foto}" alt="Foto ${data.nama}" class="w-full h-full object-cover rounded-full border-4 border-blue-200">
            </div>
            <div class="w-full grid grid-cols-1 gap-2 text-sm">
                <div class="bg-gray-100 p-2 rounded"><span class="font-semibold">Jabatan:</span> ${data.jabatan}</div>
                <div class="bg-gray-100 p-2 rounded"><span class="font-semibold">Nama:</span> ${data.nama}</div>
                <div class="bg-gray-100 p-2 rounded"><span class="font-semibold">Pendidikan:</span> ${data.riwayat_pendidikan}</div>
                <div class="bg-gray-100 p-2 rounded"><span class="font-semibold">Status:</span> ${data.status}</div>
                <div class="bg-gray-100 p-2 rounded"><span class="font-semibold">Mata Pelajaran:</span> ${data.mata_pelajaran}</div>
                <div class="bg-gray-100 p-2 rounded"><span class="font-semibold">Lama Mengajar:</span> ${data.lama_mengajar} tahun</div>
                <div class="bg-gray-100 p-2 rounded"><span class="font-semibold">Pangkat:</span> ${data.pangkat}</div>
                <div class="bg-gray-100 p-2 rounded"><span class="font-semibold">Alamat:</span> ${data.alamat}</div>
                <div class="bg-gray-100 p-2 rounded"><span class="font-semibold">Motto:</span> ${data.motto}</div>
            </div>
        </div>
    `;
      document.getElementById("staffDetailModal").classList.remove("hidden");
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Terjadi kesalahan saat mengambil data staff");
    });
}

function closeStaffDetailModal() {
  document.getElementById("staffDetailModal").classList.add("hidden");
}

function deleteStaff(id) {
  const formData = new FormData();
  formData.append("aksi", "hapus_staff");
  formData.append("id", id);

  fetch("manage_struktur.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((result) => {
      showMessage(result.message);
      closeConfirmDeleteModal();
      location.reload();
    })
    .catch((error) => {
      console.error("Error:", error);
      showMessage("Terjadi kesalahan saat menghapus data");
    });
}

document.getElementById("staffForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch("manage_struktur.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((result) => {
      showMessage(result.message);
      closeModal();
      if (result.success) {
        location.reload();
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showMessage("Terjadi kesalahan saat menyimpan data");
    });
});

function showMessage(message) {
  document.getElementById("modalMessage").textContent = message;
  document.getElementById("messageModal").classList.remove("hidden");
}

document
  .getElementById("closeMessageModal")
  .addEventListener("click", function () {
    document.getElementById("messageModal").classList.add("hidden");
  });

// Bagian Upload Tupoksi
document.getElementById('tupoksiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('manage_struktur.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showNotification(data.message);
        if (data.success) {
            // Refresh halaman setelah beberapa detik jika sukses
            setTimeout(() => {
                location.reload();
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat mengunggah file.');
    });
});

function showNotification(message) {
    document.getElementById('notificationMessage').textContent = message;
    document.getElementById('notificationModal').classList.remove('hidden');
}

document.getElementById('closeNotificationModal').addEventListener('click', function() {
    document.getElementById('notificationModal').classList.add('hidden');
});
