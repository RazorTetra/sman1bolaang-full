document.addEventListener('DOMContentLoaded', function() {
    // Event listener untuk form struktur
    document.getElementById('strukturForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
    
        fetch('manage_struktur.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data struktur.');
        });
    });

    // Event listener untuk form tupoksi
    document.getElementById('tupoksiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
    
        fetch('manage_struktur.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data tupoksi.');
        });
    });
});

function openStrukturModal(mode, id = null) {
    document.getElementById('strukturModal').classList.remove('hidden');
    document.getElementById('strukturModalLabel').innerText = mode === 'add' ? 'Tambah Struktur Organisasi' : 'Edit Struktur Organisasi';
    document.getElementById('strukturForm').reset();
    document.getElementById('struktur_id').value = id || '';
    document.getElementById('strukturForm').elements['aksi'].value = mode === 'add' ? 'tambah_struktur' : 'edit_struktur';
    if (mode === 'edit' && id) {
        fetch(`get_struktur_detail.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('struktur_judul').value = data.judul;
                // Isi field lainnya jika ada
            })
            .catch(error => console.error('Error:', error));
    }
}

function closeStrukturModal() {
    document.getElementById('strukturModal').classList.add('hidden');
}

function openTupoksiModal(mode, id = null) {
    document.getElementById('tupoksiModal').classList.remove('hidden');
    document.getElementById('tupoksiModalLabel').innerText = mode === 'add' ? 'Tambah Tupoksi Staff' : 'Edit Tupoksi Staff';
    document.getElementById('tupoksiForm').reset();
    document.getElementById('tupoksi_id').value = id || '';
    document.getElementById('tupoksiForm').elements['aksi'].value = mode === 'add' ? 'tambah_tupoksi' : 'edit_tupoksi';
    if (mode === 'edit' && id) {
        fetch(`get_tupoksi_detail.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('tupoksi_judul').value = data.judul;
                document.getElementById('tupoksi_link').value = data.google_drive_link;
            })
            .catch(error => console.error('Error:', error));
    }
}

function closeTupoksiModal() {
    document.getElementById('tupoksiModal').classList.add('hidden');
}

function editStruktur(id) {
    openStrukturModal('edit', id);
}

function editTupoksi(id) {
    openTupoksiModal('edit', id);
}

function deleteStruktur(id) {
    if (confirm('Apakah Anda yakin ingin menghapus struktur ini?')) {
        const formData = new FormData();
        formData.append('aksi', 'hapus_struktur');
        formData.append('id', id);
        
        fetch('manage_struktur.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus struktur.');
        });
    }
}

function deleteTupoksi(id) {
    if (confirm('Apakah Anda yakin ingin menghapus tupoksi ini?')) {
        const formData = new FormData();
        formData.append('aksi', 'hapus_tupoksi');
        formData.append('id', id);
        
        fetch('manage_struktur.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus tupoksi.');
        });
    }
}