<?php
session_start();
require 'server/koneksi.php';

// Hanya admin yang bisa akses
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil semua destinasi dari database
$result  = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY id DESC");
$destinasi = [];
while ($row = mysqli_fetch_assoc($result)) {
    $destinasi[] = $row;
}

$message     = $_SESSION['message']      ?? '';
$message_type = $_SESSION['message_type'] ?? 'success';
unset($_SESSION['message'], $_SESSION['message_type']);

$BPS_API_KEY = '10f149869798c369c50319f51333657d';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | DDELTIKET</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Quicksand', sans-serif; }
        .sidebar { width: 260px; min-height: 100vh; background: linear-gradient(160deg, #1e3c5c 0%, #2b6c94 100%); transition: all 0.3s; }
        .sidebar-collapsed { width: 70px; }
        .main-content { flex: 1; overflow-x: hidden; }
        .card-stat:hover { transform: translateY(-4px); transition: 0.3s; }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 1000; overflow-y: auto; }
        .modal.active { display: flex; align-items: flex-start; justify-content: center; padding: 2rem 1rem; }
        .modal-box { background: white; border-radius: 1.5rem; width: 100%; max-width: 680px; overflow: hidden; animation: slideUp 0.3s ease; }
        @keyframes slideUp { from { transform: translateY(30px); opacity:0; } to { transform: translateY(0); opacity:1; } }
        .bps-item { cursor: pointer; transition: all 0.2s; }
        .bps-item:hover { background: #fef3c7; border-color: #f39c12; }
        .bps-item.selected { background: #fef3c7; border-color: #f39c12; border-left-width: 4px; }
        .tab-btn { transition: all 0.2s; }
        .tab-btn.active { background: #f39c12; color: white; }
        .loader { border: 3px solid #f3f3f3; border-top: 3px solid #f39c12; border-radius: 50%; width: 30px; height: 30px; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .badge-admin { background: linear-gradient(135deg, #f39c12, #e67e22); }
        table { border-collapse: collapse; }
        th, td { white-space: nowrap; }
        .scrollable-table { overflow-x: auto; }
    </style>
</head>
<body class="bg-gray-50 flex">

<!-- ============ SIDEBAR ============ -->
<aside class="sidebar flex flex-col py-6 px-4 text-white" id="sidebar">
    <div class="flex items-center gap-3 mb-8 px-2">
        <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-compass text-white text-lg"></i>
        </div>
        <span class="font-bold text-xl sidebar-text">DDELTIKET</span>
    </div>

    <div class="px-2 mb-6 sidebar-text">
        <div class="bg-white/10 rounded-xl p-3 flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0">
                <?= strtoupper(substr($_SESSION['nama'], 0, 1)) ?>
            </div>
            <div class="min-w-0">
                <p class="font-bold text-sm truncate"><?= htmlspecialchars($_SESSION['nama']) ?></p>
                <span class="text-xs bg-yellow-400 text-gray-800 px-2 py-0.5 rounded-full font-bold">ADMIN</span>
            </div>
        </div>
    </div>

    <nav class="flex-1 space-y-1">
        <a href="#" onclick="showSection('dashboard')" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition font-semibold" data-section="dashboard">
            <i class="fas fa-chart-pie w-5 text-center"></i><span class="sidebar-text">Dashboard</span>
        </a>
        <a href="#" onclick="showSection('destinasi')" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition font-semibold" data-section="destinasi">
            <i class="fas fa-map-marked-alt w-5 text-center"></i><span class="sidebar-text">Kelola Destinasi</span>
        </a>
        <a href="#" onclick="showSection('bps')" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition font-semibold" data-section="bps">
            <i class="fas fa-database w-5 text-center"></i><span class="sidebar-text">Data BPS</span>
        </a>
    </nav>

    <div class="mt-auto space-y-1">
        <a href="index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition font-semibold text-sm">
            <i class="fas fa-globe w-5 text-center"></i><span class="sidebar-text">Lihat Website</span>
        </a>
        <a href="logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-red-500/20 hover:bg-red-500/40 transition font-semibold text-sm">
            <i class="fas fa-sign-out-alt w-5 text-center"></i><span class="sidebar-text">Logout</span>
        </a>
    </div>
</aside>

<!-- ============ MAIN CONTENT ============ -->
<main class="main-content flex flex-col min-h-screen">

    <!-- Topbar -->
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-40">
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-800 text-xl">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="text-xl font-bold text-[#1e3c5c]" id="page-title">Dashboard Admin</h1>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500 hidden md:block"><?= date('d M Y, H:i') ?> WIB</span>
            <div class="w-9 h-9 badge-admin rounded-full flex items-center justify-center text-white font-bold text-sm">
                <?= strtoupper(substr($_SESSION['nama'], 0, 1)) ?>
            </div>
        </div>
    </header>

    <div class="flex-1 p-6">

        <?php if ($message): ?>
        <div class="mb-4 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2
            <?= $message_type === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300' ?>">
            <i class="fas <?= $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
            <?= htmlspecialchars($message) ?>
        </div>
        <?php endif; ?>

        <!-- =================== SECTION: DASHBOARD =================== -->
        <section id="section-dashboard">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 card-stat">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-map-marked-alt text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">TOTAL</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800"><?= count($destinasi) ?></p>
                    <p class="text-sm text-gray-500 mt-1">Total Destinasi</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 card-stat">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-star text-yellow-500 text-xl"></i>
                        </div>
                        <span class="text-xs font-bold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">AVG</span>
                    </div>
                    <?php $avgRating = count($destinasi) > 0 ? array_sum(array_column($destinasi, 'rating')) / count($destinasi) : 0; ?>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($avgRating, 1) ?></p>
                    <p class="text-sm text-gray-500 mt-1">Rating Rata-rata</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 card-stat">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-province text-green-600 text-xl fa-map"></i>
                        </div>
                        <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">UNIK</span>
                    </div>
                    <?php $provinsi_unik = count(array_unique(array_filter(array_column($destinasi, 'provinsi')))); ?>
                    <p class="text-3xl font-bold text-gray-800"><?= $provinsi_unik ?></p>
                    <p class="text-sm text-gray-500 mt-1">Provinsi Tercakup</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 card-stat">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-ticket-alt text-purple-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">AVG</span>
                    </div>
                    <?php $avgHarga = count($destinasi) > 0 ? array_sum(array_column($destinasi, 'harga')) / count($destinasi) : 0; ?>
                    <p class="text-3xl font-bold text-gray-800">Rp <?= number_format($avgHarga/1000, 0) ?>K</p>
                    <p class="text-sm text-gray-500 mt-1">Harga Rata-rata</p>
                </div>
            </div>

            <!-- Recent Destinasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-[#1e3c5c] mb-4">Destinasi Terbaru</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach(array_slice($destinasi, 0, 3) as $d): ?>
                    <div class="border border-gray-100 rounded-xl overflow-hidden hover:shadow-md transition">
                        <img src="<?= htmlspecialchars($d['imgUrl'] ?? '') ?>" class="w-full h-36 object-cover" onerror="this.src='https://placehold.co/400x200?text=No+Image'">
                        <div class="p-3">
                            <h3 class="font-bold text-gray-800 truncate"><?= htmlspecialchars($d['nama']) ?></h3>
                            <p class="text-sm text-gray-500"><i class="fas fa-map-pin text-yellow-500 mr-1"></i><?= htmlspecialchars($d['lokasi']) ?></p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="font-bold text-[#2b6c94] text-sm">Rp <?= number_format($d['harga'],0,',','.') ?></span>
                                <span class="text-yellow-500 text-sm">⭐ <?= $d['rating'] ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button onclick="showSection('destinasi')" class="mt-4 text-sm text-[#2b6c94] font-semibold hover:underline">
                    Lihat semua destinasi →
                </button>
            </div>
        </section>

        <!-- =================== SECTION: KELOLA DESTINASI =================== -->
        <section id="section-destinasi" class="hidden">
            <div class="flex flex-wrap justify-between items-center mb-6 gap-3">
                <div>
                    <h2 class="text-2xl font-bold text-[#1e3c5c]">Kelola Destinasi</h2>
                    <p class="text-sm text-gray-500">Tambah, edit, atau hapus destinasi wisata</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="openModal('modal-tambah')" class="bg-[#f39c12] hover:bg-[#e67e22] text-white px-5 py-2 rounded-xl font-bold flex items-center gap-2 transition shadow-md">
                        <i class="fas fa-plus"></i> Tambah Manual
                    </button>
                    <button onclick="showSection('bps')" class="bg-[#2b6c94] hover:bg-[#1e3c5c] text-white px-5 py-2 rounded-xl font-bold flex items-center gap-2 transition shadow-md">
                        <i class="fas fa-database"></i> Import BPS
                    </button>
                </div>
            </div>

            <!-- Tabel Destinasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center gap-3">
                    <input type="text" id="searchDest" oninput="filterTable()" placeholder="🔍 Cari destinasi..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm flex-1 max-w-xs focus:outline-none focus:border-yellow-400">
                    <span class="text-sm text-gray-500"><?= count($destinasi) ?> destinasi</span>
                </div>
                <div class="scrollable-table">
                <table class="w-full text-sm" id="destTable">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Foto</th>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Lokasi</th>
                            <th class="px-4 py-3 text-left">Provinsi</th>
                            <th class="px-4 py-3 text-left">Harga</th>
                            <th class="px-4 py-3 text-left">Rating</th>
                            <th class="px-4 py-3 text-left">Kategori</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="destTableBody">
                    <?php foreach($destinasi as $d): ?>
                    <tr class="border-t border-gray-50 hover:bg-gray-50 transition" data-nama="<?= strtolower(htmlspecialchars($d['nama'])) ?>">
                        <td class="px-4 py-3">
                            <img src="<?= htmlspecialchars($d['imgUrl'] ?? '') ?>" class="w-14 h-10 object-cover rounded-lg" onerror="this.src='https://placehold.co/56x40?text=?'">
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-800"><?= htmlspecialchars($d['nama']) ?></td>
                        <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($d['lokasi']) ?></td>
                        <td class="px-4 py-3">
                            <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-full font-semibold"><?= htmlspecialchars($d['provinsi'] ?? '-') ?></span>
                        </td>
                        <td class="px-4 py-3 font-bold text-[#2b6c94]">Rp <?= number_format($d['harga'],0,',','.') ?></td>
                        <td class="px-4 py-3">
                            <span class="text-yellow-500 font-bold">⭐ <?= $d['rating'] ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <?php if($d['kategori']): ?>
                            <span class="bg-green-50 text-green-700 text-xs px-2 py-1 rounded-full"><?= htmlspecialchars($d['kategori']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick='openEditModal(<?= json_encode($d) ?>)' class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="proses/prosesHapusDestinasi.php?id=<?= $d['id'] ?>" onclick="return confirm('Hapus destinasi ini?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </section>

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
