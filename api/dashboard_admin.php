<?php
session_start();
require __DIR__ . '/server/koneksi.php';

// Proteksi Admin: Cek Cookie atau Session
$role = $_SESSION['role'] ?? $_COOKIE['role'] ?? null;

if ($role !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil semua destinasi
$result  = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY id DESC");
$destinasi = [];
while ($row = mysqli_fetch_assoc($result)) {
    $destinasi[] = $row;
}

$message = $_SESSION['message'] ?? '';
$message_type = $_SESSION['message_type'] ?? 'success';
unset($_SESSION['message'], $_SESSION['message_type']);

$BPS_API_KEY = '10f149869798c369c50319f51333657d';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Jelajah.In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Quicksand', sans-serif; }
        .sidebar { width: 260px; min-height: 100vh; background: linear-gradient(160deg, #1e3c5c 0%, #2b6c94 100%); }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 1000; overflow-y: auto; }
        .modal.active { display: flex; align-items: flex-start; justify-content: center; padding: 2rem 1rem; }
    </style>
</head>
<body class="bg-gray-50 flex">

<aside class="sidebar flex flex-col py-6 px-4 text-white">
    <div class="flex items-center gap-3 mb-8 px-2">
        <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center">
            <i class="fas fa-compass text-white text-lg"></i>
        </div>
        <span class="font-bold text-xl">Jelajah Admin</span>
    </div>

    <nav class="flex-1 space-y-1">
        <a href="?section=dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition font-semibold">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="?section=destinasi" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition font-semibold">
            <i class="fas fa-map-marked-alt"></i> Kelola Destinasi
        </a>
    </nav>

    <div class="mt-auto">
        <a href="logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-red-500/20 hover:bg-red-500/40 transition font-semibold">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</aside>

<main class="flex-1">
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-40">
        <h1 class="text-xl font-bold text-[#1e3c5c]">Panel Admin</h1>
        <div class="text-sm font-bold bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">
            Admin: <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?>
        </div>
    </header>

    <div class="p-6">
        <?php if ($message): ?>
            <div class="mb-4 px-4 py-3 rounded-xl bg-green-100 text-green-700 border border-green-300">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Content Area: Silakan masukkan tabel dan filter BPS dari kode lama kamu di sini -->
        <h2 class="text-lg font-bold mb-4">Daftar Destinasi</h2>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($destinasi as $d): ?>
                    <tr class="border-t">
                        <td class="px-4 py-3 font-bold"><?= htmlspecialchars($d['nama']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($d['lokasi']) ?></td>
                        <td class="px-4 py-3">Rp <?= number_format($d['harga']) ?></td>
                        <td class="px-4 py-3">
                            <a href="proses/prosesHapusDestinasi.php?id=<?= $d['id'] ?>" class="text-red-500 font-bold" onclick="return confirm('Hapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
        <!-- =================== SECTION: DATA BPS =================== -->
        <section id="section-bps" class="hidden">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-[#1e3c5c]">Data BPS – Pariwisata Indonesia</h2>
                <p class="text-sm text-gray-500">Ambil data destinasi wisata langsung dari API BPS (bps.go.id)</p>
            </div>

            <!-- Filter BPS -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-filter text-yellow-500"></i> Filter Data BPS
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Domain / Wilayah</label>
                        <select id="bps-domain" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400">
                            <option value="0000">Indonesia (Nasional)</option>
                            <option value="3100">DKI Jakarta</option>
                            <option value="3200">Jawa Barat</option>
                            <option value="3300">Jawa Tengah</option>
                            <option value="3400">DI Yogyakarta</option>
                            <option value="3500">Jawa Timur</option>
                            <option value="1100">Aceh</option>
                            <option value="1200">Sumatera Utara</option>
                            <option value="1300">Sumatera Barat</option>
                            <option value="1400">Riau</option>
                            <option value="1500">Jambi</option>
                            <option value="1600">Sumatera Selatan</option>
                            <option value="1700">Bengkulu</option>
                            <option value="1800">Lampung</option>
                            <option value="1900">Bangka Belitung</option>
                            <option value="2100">Kepulauan Riau</option>
                            <option value="6100">Kalimantan Barat</option>
                            <option value="6200">Kalimantan Tengah</option>
                            <option value="6300">Kalimantan Selatan</option>
                            <option value="6400">Kalimantan Timur</option>
                            <option value="6500">Kalimantan Utara</option>
                            <option value="5100">Bali</option>
                            <option value="5200">NTB</option>
                            <option value="5300">NTT</option>
                            <option value="7100">Sulawesi Utara</option>
                            <option value="7200">Sulawesi Tengah</option>
                            <option value="7300">Sulawesi Selatan</option>
                            <option value="7400">Sulawesi Tenggara</option>
                            <option value="7500">Gorontalo</option>
                            <option value="7600">Sulawesi Barat</option>
                            <option value="8100">Maluku</option>
                            <option value="8200">Maluku Utara</option>
                            <option value="9100">Papua Barat</option>
                            <option value="9400">Papua</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Tahun Data</label>
                        <select id="bps-year" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400">
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button onclick="fetchBPS()" class="w-full bg-[#f39c12] hover:bg-[#e67e22] text-white py-2.5 rounded-xl font-bold flex items-center justify-center gap-2 transition">
                            <i class="fas fa-search"></i> Ambil Data BPS
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hasil BPS -->
            <div id="bps-loading" class="hidden bg-white rounded-2xl shadow-sm p-10 text-center">
                <div class="loader mx-auto mb-4"></div>
                <p class="text-gray-500 font-semibold">Mengambil data dari BPS...</p>
            </div>

            <div id="bps-result" class="hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-[#1e3c5c] text-lg" id="bps-result-title">Hasil Data BPS</h3>
                    <div class="flex gap-2">
                        <button onclick="selectAllBPS()" class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg font-semibold text-gray-700 transition">
                            <i class="fas fa-check-double"></i> Pilih Semua
                        </button>
                        <button onclick="importSelectedBPS()" id="btn-import-bps" class="text-xs bg-[#2b6c94] hover:bg-[#1e3c5c] text-white px-4 py-1.5 rounded-lg font-bold transition hidden">
                            <i class="fas fa-file-import"></i> Import Terpilih (<span id="selected-count">0</span>)
                        </button>
                    </div>
                </div>
                <div id="bps-items" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[500px] overflow-y-auto pr-1"></div>
            </div>

            <div id="bps-error" class="hidden bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
                <i class="fas fa-exclamation-circle text-red-400 text-4xl mb-3"></i>
                <p class="text-red-600 font-bold text-lg">Gagal mengambil data BPS</p>
                <p class="text-red-500 text-sm mt-1" id="bps-error-msg"></p>
                <p class="text-sm text-gray-500 mt-3">Pastikan API key valid dan koneksi internet tersedia.</p>
            </div>
        </section>

    </div><!-- /flex-1 p-6 -->
</main>

<!-- =================== MODAL TAMBAH MANUAL =================== -->
<div id="modal-tambah" class="modal">
    <div class="modal-box">
        <div class="bg-[#1e3c5c] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-lg"><i class="fas fa-plus-circle mr-2"></i>Tambah Destinasi</h3>
            <button onclick="closeModal('modal-tambah')" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
        </div>
        <form action="proses/prosesTambahDestinasi.php" method="POST" class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Nama Destinasi *</label>
                    <input name="nama" required placeholder="Candi Borobudur" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Lokasi *</label>
                    <input name="lokasi" required placeholder="Magelang, Jawa Tengah" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Provinsi</label>
                    <select name="provinsi" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400">
                        <option value="">-- Pilih Provinsi --</option>
                        <option>DKI Jakarta</option><option>Jawa Barat</option><option>Jawa Tengah</option>
                        <option>DI Yogyakarta</option><option>Jawa Timur</option><option>Banten</option>
                        <option>Aceh</option><option>Sumatera Utara</option><option>Sumatera Barat</option>
                        <option>Riau</option><option>Jambi</option><option>Sumatera Selatan</option>
                        <option>Bengkulu</option><option>Lampung</option><option>Bangka Belitung</option>
                        <option>Kepulauan Riau</option><option>Kalimantan Barat</option><option>Kalimantan Tengah</option>
                        <option>Kalimantan Selatan</option><option>Kalimantan Timur</option><option>Kalimantan Utara</option>
                        <option>Bali</option><option>NTB</option><option>NTT</option>
                        <option>Sulawesi Utara</option><option>Sulawesi Tengah</option><option>Sulawesi Selatan</option>
                        <option>Sulawesi Tenggara</option><option>Gorontalo</option><option>Sulawesi Barat</option>
                        <option>Maluku</option><option>Maluku Utara</option><option>Papua Barat</option><option>Papua</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Kategori</label>
                    <select name="kategori" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400">
                        <option value="">-- Pilih Kategori --</option>
                        <option>Alam</option><option>Budaya</option><option>Bahari</option>
                        <option>Religi</option><option>Kuliner</option><option>Hiburan</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Harga Tiket (Rp) *</label>
                    <input name="harga" type="number" min="0" required placeholder="50000" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400">
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Rating (0–5)</label>
                    <input name="rating" type="number" min="0" max="5" step="0.1" placeholder="4.5" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400">
                </div>
            </div>
            <div>
                <label class="text-sm font-bold text-gray-700 mb-1 block">URL Gambar</label>
                <input name="imgUrl" placeholder="https://..." class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400">
            </div>
            <div>
                <label class="text-sm font-bold text-gray-700 mb-1 block">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat destinasi..." class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400 resize-none"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-tambah')" class="px-5 py-2 border border-gray-200 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-6 py-2 bg-[#f39c12] hover:bg-[#e67e22] text-white rounded-xl font-bold transition">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =================== MODAL EDIT =================== -->
<div id="modal-edit" class="modal">
    <div class="modal-box">
        <div class="bg-blue-700 px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-lg"><i class="fas fa-edit mr-2"></i>Edit Destinasi</h3>
            <button onclick="closeModal('modal-edit')" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
        </div>
        <form action="proses/prosesEditDestinasi.php" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id" id="edit-id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Nama Destinasi *</label>
                    <input name="nama" id="edit-nama" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Lokasi *</label>
                    <input name="lokasi" id="edit-lokasi" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Provinsi</label>
                    <select name="provinsi" id="edit-provinsi" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                        <option value="">-- Pilih Provinsi --</option>
                        <option>DKI Jakarta</option><option>Jawa Barat</option><option>Jawa Tengah</option>
                        <option>DI Yogyakarta</option><option>Jawa Timur</option><option>Banten</option>
                        <option>Aceh</option><option>Sumatera Utara</option><option>Sumatera Barat</option>
                        <option>Riau</option><option>Jambi</option><option>Sumatera Selatan</option>
                        <option>Bengkulu</option><option>Lampung</option><option>Bangka Belitung</option>
                        <option>Kepulauan Riau</option><option>Kalimantan Barat</option><option>Kalimantan Tengah</option>
                        <option>Kalimantan Selatan</option><option>Kalimantan Timur</option><option>Kalimantan Utara</option>
                        <option>Bali</option><option>NTB</option><option>NTT</option>
                        <option>Sulawesi Utara</option><option>Sulawesi Tengah</option><option>Sulawesi Selatan</option>
                        <option>Sulawesi Tenggara</option><option>Gorontalo</option><option>Sulawesi Barat</option>
                        <option>Maluku</option><option>Maluku Utara</option><option>Papua Barat</option><option>Papua</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Kategori</label>
                    <select name="kategori" id="edit-kategori" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                        <option value="">-- Pilih Kategori --</option>
                        <option>Alam</option><option>Budaya</option><option>Bahari</option>
                        <option>Religi</option><option>Kuliner</option><option>Hiburan</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Harga Tiket (Rp) *</label>
                    <input name="harga" id="edit-harga" type="number" min="0" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Rating (0–5)</label>
                    <input name="rating" id="edit-rating" type="number" min="0" max="5" step="0.1" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                </div>
            </div>
            <div>
                <label class="text-sm font-bold text-gray-700 mb-1 block">URL Gambar</label>
                <input name="imgUrl" id="edit-imgUrl" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
            </div>
            <div>
                <label class="text-sm font-bold text-gray-700 mb-1 block">Deskripsi</label>
                <textarea name="deskripsi" id="edit-deskripsi" rows="3" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400 resize-none"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-edit')" class="px-5 py-2 border border-gray-200 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =================== MODAL KONFIRMASI IMPORT BPS =================== -->
<div id="modal-import-bps" class="modal">
    <div class="modal-box max-w-lg">
        <div class="bg-[#2b6c94] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-lg"><i class="fas fa-file-import mr-2"></i>Konfirmasi Import</h3>
            <button onclick="closeModal('modal-import-bps')" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4">Lengkapi informasi berikut sebelum mengimpor data BPS:</p>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Harga Tiket Default (Rp)</label>
                    <input type="number" id="import-harga" value="25000" min="0" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                    <p class="text-xs text-gray-400 mt-1">Dapat diubah nanti di halaman edit</p>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Kategori Default</label>
                    <select id="import-kategori" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                        <option>Alam</option><option>Budaya</option><option>Bahari</option>
                        <option>Religi</option><option>Kuliner</option><option>Hiburan</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">URL Gambar Default</label>
                    <input type="text" id="import-imgUrl" placeholder="https://... (kosongkan untuk gambar placeholder)" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                </div>
            </div>
            <div id="import-list" class="mt-4 border border-gray-100 rounded-xl p-3 bg-gray-50 max-h-48 overflow-y-auto text-sm space-y-1"></div>
            <div class="flex justify-end gap-3 mt-4">
                <button onclick="closeModal('modal-import-bps')" class="px-5 py-2 border border-gray-200 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">Batal</button>
                <button onclick="doImportBPS()" class="px-6 py-2 bg-[#2b6c94] hover:bg-[#1e3c5c] text-white rounded-xl font-bold transition">
                    <i class="fas fa-check mr-1"></i> Import Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const BPS_API_KEY = '<?= $BPS_API_KEY ?>';
let bpsData = [];
let selectedBPS = [];

// ============ NAVIGASI ============
function showSection(name) {
    ['dashboard','destinasi','bps'].forEach(s => {
        document.getElementById('section-' + s).classList.toggle('hidden', s !== name);
    });
    document.querySelectorAll('.nav-link').forEach(a => {
        a.classList.toggle('bg-white/20', a.dataset.section === name);
        a.classList.toggle('font-bold', a.dataset.section === name);
    });
    const titles = { dashboard: 'Dashboard Admin', destinasi: 'Kelola Destinasi', bps: 'Data BPS Pariwisata' };
    document.getElementById('page-title').textContent = titles[name] || '';
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('sidebar-collapsed');
    document.querySelectorAll('.sidebar-text').forEach(el => el.classList.toggle('hidden'));
}

// ============ MODAL ============
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
document.querySelectorAll('.modal').forEach(m => {
    m.addEventListener('click', function(e) { if(e.target === this) this.classList.remove('active'); });
});

function openEditModal(data) {
    document.getElementById('edit-id').value      = data.id;
    document.getElementById('edit-nama').value    = data.nama;
    document.getElementById('edit-lokasi').value  = data.lokasi;
    document.getElementById('edit-harga').value   = data.harga;
    document.getElementById('edit-rating').value  = data.rating;
    document.getElementById('edit-imgUrl').value  = data.imgUrl || '';
    document.getElementById('edit-deskripsi').value = data.deskripsi || '';
    setSelectValue('edit-provinsi', data.provinsi);
    setSelectValue('edit-kategori', data.kategori);
    openModal('modal-edit');
}

function setSelectValue(id, val) {
    const sel = document.getElementById(id);
    for (let o of sel.options) if (o.value === val) { o.selected = true; return; }
}

// ============ SEARCH TABLE ============
function filterTable() {
    const q = document.getElementById('searchDest').value.toLowerCase();
    document.querySelectorAll('#destTableBody tr').forEach(row => {
        row.style.display = row.dataset.nama.includes(q) ? '' : 'none';
    });
}

// ============ BPS API ============
async function fetchBPS() {
    const domain = document.getElementById('bps-domain').value;
    const year   = document.getElementById('bps-year').value;

    document.getElementById('bps-loading').classList.remove('hidden');
    document.getElementById('bps-result').classList.add('hidden');
    document.getElementById('bps-error').classList.add('hidden');

    // Variabel BPS untuk Kunjungan Wisatawan / Objek Wisata
    // Menggunakan endpoint subject/listVar untuk pariwisata
    const url = `https://webapi.bps.go.id/v1/api/list/model/data/domain/${domain}/var/1718/key/${BPS_API_KEY}`;

    try {
        const resp = await fetch(url);
        const json = await resp.json();

        document.getElementById('bps-loading').classList.add('hidden');

        if (!json || json.status === 'ERROR' || !json.datacontent) {
            throw new Error(json?.message || 'Data tidak ditemukan');
        }

        // Parse data BPS
        const datacontent = json.datacontent || {};
        const labelkol    = json.labelkol || {};
        const labelview   = json.labelview || {};

        bpsData = [];
        // Ambil data sebagai array destinasi dari BPS
        Object.keys(datacontent).forEach((key, idx) => {
            const row = datacontent[key];
            if (typeof row === 'object') {
                Object.keys(row).forEach(subkey => {
                    const label = labelkol[subkey] || labelview[subkey] || `Data ${idx+1}`;
                    const value = row[subkey];
                    if (value !== null && value !== undefined && label.length > 3) {
                        bpsData.push({
                            id: `bps-${domain}-${key}-${subkey}`,
                            nama: label,
                            lokasi: getDomainName(domain),
                            provinsi: getDomainName(domain),
                            nilai: value,
                            tahun: year
                        });
                    }
                });
            }
        });

        if (bpsData.length === 0) {
            throw new Error('Tidak ada data yang ditemukan untuk wilayah ini.');
        }

        renderBPSItems();
        document.getElementById('bps-result-title').textContent = `Data BPS – ${getDomainName(domain)} (${year}) — ${bpsData.length} item`;
        document.getElementById('bps-result').classList.remove('hidden');

    } catch(err) {
        document.getElementById('bps-loading').classList.add('hidden');
        document.getElementById('bps-error').classList.remove('hidden');
        document.getElementById('bps-error-msg').textContent = err.message;
    }
}

function getDomainName(code) {
    const map = {
        '0000':'Indonesia','3100':'DKI Jakarta','3200':'Jawa Barat','3300':'Jawa Tengah',
        '3400':'DI Yogyakarta','3500':'Jawa Timur','1100':'Aceh','1200':'Sumatera Utara',
        '1300':'Sumatera Barat','1400':'Riau','1500':'Jambi','1600':'Sumatera Selatan',
        '1700':'Bengkulu','1800':'Lampung','1900':'Bangka Belitung','2100':'Kepulauan Riau',
        '6100':'Kalimantan Barat','6200':'Kalimantan Tengah','6300':'Kalimantan Selatan',
        '6400':'Kalimantan Timur','6500':'Kalimantan Utara','5100':'Bali','5200':'NTB',
        '5300':'NTT','7100':'Sulawesi Utara','7200':'Sulawesi Tengah','7300':'Sulawesi Selatan',
        '7400':'Sulawesi Tenggara','7500':'Gorontalo','7600':'Sulawesi Barat',
        '8100':'Maluku','8200':'Maluku Utara','9100':'Papua Barat','9400':'Papua'
    };
    return map[code] || code;
}

function renderBPSItems() {
    selectedBPS = [];
    updateSelectedCount();
    const container = document.getElementById('bps-items');
    container.innerHTML = bpsData.map((item, i) => `
        <div class="bps-item border border-gray-200 rounded-xl p-3 flex items-start gap-3" 
             id="bps-item-${i}" onclick="toggleBPSItem(${i})">
            <div class="w-5 h-5 border-2 border-gray-300 rounded mt-0.5 flex items-center justify-center flex-shrink-0 check-box">
                <i class="fas fa-check text-xs text-[#f39c12] hidden"></i>
            </div>
            <div class="min-w-0">
                <p class="font-bold text-gray-800 text-sm leading-tight">${escHtml(item.nama)}</p>
                <p class="text-xs text-gray-500 mt-0.5">
                    <i class="fas fa-map-pin text-yellow-500 mr-1"></i>${escHtml(item.lokasi)}
                    <span class="ml-2 text-blue-500 font-semibold">Nilai: ${item.nilai}</span>
                </p>
            </div>
        </div>
    `).join('');
}

function toggleBPSItem(i) {
    const el  = document.getElementById(`bps-item-${i}`);
    const idx = selectedBPS.indexOf(i);
    if (idx === -1) {
        selectedBPS.push(i);
        el.classList.add('selected');
        el.querySelector('.check-box i').classList.remove('hidden');
    } else {
        selectedBPS.splice(idx, 1);
        el.classList.remove('selected');
        el.querySelector('.check-box i').classList.add('hidden');
    }
    updateSelectedCount();
}

function selectAllBPS() {
    selectedBPS = bpsData.map((_, i) => i);
    document.querySelectorAll('.bps-item').forEach((el, i) => {
        el.classList.add('selected');
        el.querySelector('.check-box i').classList.remove('hidden');
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    document.getElementById('selected-count').textContent = selectedBPS.length;
    document.getElementById('btn-import-bps').classList.toggle('hidden', selectedBPS.length === 0);
}

function importSelectedBPS() {
    if (selectedBPS.length === 0) return;
    const listEl = document.getElementById('import-list');
    listEl.innerHTML = selectedBPS.map(i => `
        <div class="flex items-center gap-2 text-gray-700">
            <i class="fas fa-map-pin text-yellow-500 text-xs"></i>
            <span class="truncate">${escHtml(bpsData[i].nama)}</span>
            <span class="text-xs text-gray-400 ml-auto">${escHtml(bpsData[i].lokasi)}</span>
        </div>
    `).join('');
    openModal('modal-import-bps');
}

async function doImportBPS() {
    const harga    = document.getElementById('import-harga').value   || '25000';
    const kategori = document.getElementById('import-kategori').value || 'Alam';
    const imgUrl   = document.getElementById('import-imgUrl').value   || '';

    const items = selectedBPS.map(i => bpsData[i]);

    try {
        const resp = await fetch('proses/prosesImportBPS.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ items, harga, kategori, imgUrl })
        });
        const result = await resp.json();
        closeModal('modal-import-bps');
        if (result.status === 'success') {
            alert(`✅ ${result.message}`);
            window.location.href = 'dashboard_admin.php?section=destinasi';
        } else {
            alert('❌ ' + result.message);
        }
    } catch(e) {
        alert('❌ Gagal menghubungi server: ' + e.message);
    }
}

function escHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Init section
const urlSection = new URLSearchParams(location.search).get('section') || 'dashboard';
showSection(urlSection);
</script>
</body>
</html>