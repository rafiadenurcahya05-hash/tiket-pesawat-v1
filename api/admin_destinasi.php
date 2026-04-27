<?php
session_start();
require 'server/koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil semua destinasi dari database
$query = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY id DESC");
$destinasi = [];
while ($row = mysqli_fetch_assoc($query)) {
    $destinasi[] = $row;
}

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Kelola Destinasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background: #f3f4f6; font-family: 'Segoe UI', system-ui; }
        .card-hover:hover { transform: translateY(-5px); transition: 0.3s; box-shadow: 0 20px 25px -12px rgba(0,0,0,0.1); }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: white; border-radius: 1.5rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; padding: 1.5rem; }
        .btn-primary { background: #f39c12; }
        .btn-primary:hover { background: #e67e22; }
        .btn-danger { background: #ef4444; }
        .btn-danger:hover { background: #dc2626; }
        .btn-success { background: #10b981; }
        .btn-success:hover { background: #059669; }
        .preview-img { width: 100px; height: 100px; object-fit: cover; border-radius: 0.5rem; margin-top: 0.5rem; }
    </style>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
    <div class="flex items-center gap-2">
        <i class="fas fa-compass text-2xl text-[#f39c12]"></i>
        <h1 class="text-2xl font-bold text-[#1e3c5c]">Admin Panel - DDELTIKET</h1>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-gray-700">Halo, <?= htmlspecialchars($_SESSION['nama']) ?></span>
        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">Logout</a>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header + Tombol Tambah -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-[#1e3c5c]">Destinasi Terfavorit</h2>
        <button onclick="openTambahModal()" class="bg-[#f39c12] hover:bg-[#e67e22] text-white px-5 py-2 rounded-full shadow-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Destinasi
        </button>
    </div>

    <!-- Pesan Sukses / Error -->
    <?php if ($message): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Grid Card Destinasi -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($destinasi as $dest): ?>
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover transition-all border border-gray-100">
            <!-- Gambar -->
            <div class="h-48 overflow-hidden relative">
                <img src="uploads/<?= htmlspecialchars($dest['imgUrl']) ?>" 
                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                     onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                <div class="absolute top-3 right-3 bg-white/80 backdrop-blur-sm px-2 py-1 rounded-full text-sm font-bold text-yellow-600">
                    <i class="fas fa-star text-yellow-500 mr-1"></i> <?= $dest['rating'] ?>
                </div>
            </div>
            <!-- Konten -->
            <div class="p-5">
                <h3 class="text-xl font-bold text-gray-800 mb-1"><?= htmlspecialchars($dest['nama']) ?></h3>
                <p class="text-gray-500 text-sm mb-2"><i class="fas fa-map-marker-alt text-[#f39c12] mr-1"></i> <?= htmlspecialchars($dest['lokasi']) ?></p>
                <p class="text-2xl font-bold text-[#1e3c5c] mt-2">Rp <?= number_format($dest['harga'], 0, ',', '.') ?> <span class="text-sm font-normal text-gray-400">/ tiket</span></p>
                
                <!-- Tombol Aksi -->
                <div class="flex gap-3 mt-5">
                    <button onclick='openEditModal(<?= json_encode($dest) ?> )' class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition flex items-center justify-center gap-1">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <a href="proses/prosesHapusDestinasi.php?id=<?= $dest['id'] ?>" onclick="return confirm('Yakin hapus destinasi ini?')" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg transition flex items-center justify-center gap-1">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- MODAL TAMBAH DESTINASI -->
<div id="modalTambah" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-bold text-[#1e3c5c] mb-4 flex items-center gap-2"><i class="fas fa-plus-circle text-[#f39c12]"></i> Tambah Destinasi</h3>
        <form action="proses/prosesTambahDestinasi.php" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama Destinasi</label>
                <input type="text" name="nama" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#f39c12]">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Lokasi</label>
                <input type="text" name="lokasi" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#f39c12]">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Harga (Rp)</label>
                <input type="number" name="harga" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Rating (0-5)</label>
                <input type="number" step="0.1" name="rating" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Gambar Destinasi</label>
                <input type="file" name="gambar" accept="image/*" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                <img id="previewTambah" class="preview-img hidden" alt="Preview">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2"></textarea>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#f39c12] text-white rounded-lg hover:bg-[#e67e22]">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT DESTINASI -->
<div id="modalEdit" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-bold text-[#1e3c5c] mb-4 flex items-center gap-2"><i class="fas fa-edit text-[#f39c12]"></i> Edit Destinasi</h3>
        <form action="proses/prosesEditDestinasi.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama Destinasi</label>
                <input type="text" name="nama" id="edit_nama" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Lokasi</label>
                <input type="text" name="lokasi" id="edit_lokasi" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Harga (Rp)</label>
                <input type="number" name="harga" id="edit_harga" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Rating (0-5)</label>
                <input type="number" step="0.1" name="rating" id="edit_rating" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Gambar Baru (opsional)</label>
                <input type="file" name="gambar" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                <img id="previewEdit" class="preview-img" alt="Preview Gambar Saat Ini">
                <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah gambar.</p>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2"></textarea>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalEdit')" class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview gambar untuk modal tambah
    document.querySelector('#modalTambah input[name="gambar"]').addEventListener('change', function(e) {
        const preview = document.getElementById('previewTambah');
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.src = ev.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(e.target.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    });

    // Preview untuk modal edit
    document.querySelector('#modalEdit input[name="gambar"]').addEventListener('change', function(e) {
        const preview = document.getElementById('previewEdit');
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.src = ev.target.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    function openTambahModal() {
        document.getElementById('modalTambah').style.display = 'flex';
        // Reset form dan preview
        document.getElementById('modalTambah').querySelector('form').reset();
        document.getElementById('previewTambah').classList.add('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function openEditModal(dest) {
        document.getElementById('edit_id').value = dest.id;
        document.getElementById('edit_nama').value = dest.nama;
        document.getElementById('edit_lokasi').value = dest.lokasi;
        document.getElementById('edit_harga').value = dest.harga;
        document.getElementById('edit_rating').value = dest.rating;
        document.getElementById('edit_deskripsi').value = dest.deskripsi || '';
        const preview = document.getElementById('previewEdit');
        if (dest.imgUrl) {
            preview.src = 'uploads/' + dest.imgUrl;
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
        document.getElementById('modalEdit').style.display = 'flex';
    }

    window.onclick = function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    }
</script>
</body>
</html>