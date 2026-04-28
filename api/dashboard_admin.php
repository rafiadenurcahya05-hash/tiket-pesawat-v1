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
        .modal-box { animation: slideDown 0.3s ease-out; width: 100%; max-width: 800px; background: white; border-radius: 1rem; overflow: hidden;}
        @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        /* Scrollbar kustom untuk tabel */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 flex">

<aside class="sidebar flex flex-col py-6 px-4 text-white fixed h-full z-50">
    <div class="flex items-center gap-3 mb-8 px-2">
        <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center shadow-lg">
            <i class="fas fa-compass text-white text-lg"></i>
        </div>
        <span class="font-bold text-xl tracking-wide">Jelajah<span class="text-yellow-400">Admin</span></span>
    </div>

    <nav class="flex-1 space-y-2">
        <a href="?section=dashboard" data-section="dashboard" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition font-semibold">
            <i class="fas fa-chart-pie w-5"></i> Dashboard
        </a>
        <a href="?section=destinasi" data-section="destinasi" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition font-semibold">
            <i class="fas fa-map-marked-alt w-5"></i> Kelola Destinasi
        </a>
        <a href="?section=bps" data-section="bps" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition font-semibold">
            <i class="fas fa-database w-5"></i> Data BPS
        </a>
    </nav>

    <div class="mt-auto">
        <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-500/20 hover:bg-red-500/40 text-red-200 hover:text-white transition font-semibold">
            <i class="fas fa-sign-out-alt w-5"></i> Logout
        </a>
    </div>
</aside>

<main class="flex-1 ml-[260px]">
    <header class="bg-white shadow-sm px-8 py-5 flex justify-between items-center sticky top-0 z-40">
        <h1 class="text-2xl font-bold text-[#1e3c5c]" id="page-title">Panel Admin</h1>
        <div class="flex items-center gap-3">
            <div class="text-right hidden md:block">
                <p class="text-sm font-bold text-gray-800"><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></p>
                <p class="text-xs text-green-500 font-semibold"><i class="fas fa-circle text-[8px] mr-1"></i>Online</p>
            </div>
            <div class="w-10 h-10 bg-[#1e3c5c] rounded-full flex items-center justify-center text-white font-bold border-2 border-yellow-400 shadow-sm">
                <?= strtoupper(substr($_SESSION['nama'] ?? 'A', 0, 1)) ?>
            </div>
        </div>
    </header>

    <div class="p-8">
        <?php if ($message): ?>
            <div class="mb-6 px-5 py-4 rounded-xl bg-green-50 text-green-700 border border-green-200 flex items-center gap-3 shadow-sm">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                <span class="font-semibold"><?= htmlspecialchars($message) ?></span>
            </div>
        <?php endif; ?>

        <section id="section-dashboard" class="hidden animate-fade-in">
            <div class="bg-gradient-to-r from-[#1e3c5c] to-[#2b6c94] rounded-2xl p-8 text-white shadow-lg mb-8 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl font-bold mb-2">Selamat Datang di Panel Admin! 👋</h2>
                    <p class="text-white/80">Kelola semua destinasi wisata dengan mudah melalui halaman ini.</p>
                </div>
                <i class="fas fa-compass absolute -right-10 -bottom-10 text-[150px] text-white/10"></i>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold mb-1">Total Destinasi</p>
                        <p class="text-3xl font-bold text-[#1e3c5c]"><?= count($destinasi) ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section id="section-destinasi" class="hidden">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-[#1e3c5c]">Kelola Destinasi</h2>
                    <p class="text-sm text-gray-500 mt-1">Daftar destinasi wisata yang ditampilkan ke pengunjung</p>
                </div>
                
                <button onclick="openModal('modal-tambah')" class="bg-[#f39c12] hover:bg-[#e67e22] text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-yellow-500/30 flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Tambah Destinasi
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 font-bold">Destinasi</th>
                                <th class="px-6 py-4 font-bold">Lokasi</th>
                                <th class="px-6 py-4 font-bold">Harga Tiket</th>
                                <th class="px-6 py-4 font-bold">Rating</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php foreach($destinasi as $d): ?>
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0">
                                            <img src="<?= htmlspecialchars($d['imgUrl'] ?? '') ?>" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100?text=🗺️'">
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 text-base"><?= htmlspecialchars($d['nama']) ?></p>
                                            <span class="text-xs font-semibold px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full mt-1 inline-block">
                                                <?= htmlspecialchars($d['kategori'] ?? 'Umum') ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <i class="fas fa-map-pin text-gray-400 mr-1"></i> <?= htmlspecialchars($d['lokasi']) ?>
                                </td>
                                <td class="px-6 py-4 font-bold text-[#2b6c94]">
                                    Rp <?= number_format($d['harga'],0,',','.') ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1 text-yellow-500 font-bold">
                                        <i class="fas fa-star text-xs"></i> <?= number_format($d['rating'], 1) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick='openEditModal(<?= json_encode($d) ?>)' class="bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white w-8 h-8 rounded-lg flex items-center justify-center transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="proses/prosesHapusDestinasi.php?id=<?= $d['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus destinasi <?= htmlspecialchars($d['nama']) ?>?')" class="bg-red-100 hover:bg-red-600 text-red-600 hover:text-white w-8 h-8 rounded-lg flex items-center justify-center transition" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
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

        <section id="section-bps" class="hidden">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-[#1e3c5c]">Data BPS – Pariwisata Indonesia</h2>
                <p class="text-sm text-gray-500">Ambil data destinasi wisata langsung dari API BPS (bps.go.id)</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-filter text-yellow-500"></i> Filter Data BPS
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Domain / Wilayah</label>
                        <select id="bps-domain" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition">
                            <option value="0000">Indonesia (Nasional)</option>
                            <option value="3100">DKI Jakarta</option>
                            <option value="3200">Jawa Barat</option>
                            <option value="3300">Jawa Tengah</option>
                            <option value="3400">DI Yogyakarta</option>
                            <option value="3500">Jawa Timur</option>
                            <option value="5100">Bali</option>
                            <option value="5200">NTB</option>
                            <option value="5300">NTT</option>
                            <option value="9100">Papua Barat</option>
                            <option value="9400">Papua</option>
                            </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Tahun Data</label>
                        <select id="bps-year" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition">
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button onclick="fetchBPS()" class="w-full bg-[#f39c12] hover:bg-[#e67e22] text-white py-2.5 rounded-xl font-bold flex items-center justify-center gap-2 transition shadow-lg shadow-yellow-500/30">
                            <i class="fas fa-search"></i> Cari Data
                        </button>
                    </div>
                </div>
            </div>

            <div id="bps-result" class="hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <p class="text-gray-500">Hasil data BPS akan muncul di sini...</p>
            </div>
        </section>

    </div>
</main>

<div id="modal-tambah" class="modal">
    <div class="modal-box">
        <div class="bg-gradient-to-r from-[#1e3c5c] to-[#2b6c94] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-lg"><i class="fas fa-plus-circle mr-2 text-yellow-400"></i>Tambah Destinasi Baru</h3>
            <button onclick="closeModal('modal-tambah')" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
        </div>
        <form action="proses/prosesTambahDestinasi.php" method="POST" class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Nama Destinasi *</label>
                    <input name="nama" required placeholder="Cth: Candi Borobudur" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition">
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Lokasi *</label>
                    <input name="lokasi" required placeholder="Cth: Magelang, Jawa Tengah" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition">
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Kategori</label>
                    <select name="kategori" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400 transition">
                        <option value="">-- Pilih Kategori --</option>
                        <option>Alam</option><option>Budaya</option><option>Bahari</option>
                        <option>Religi</option><option>Kuliner</option><option>Hiburan</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Harga Tiket (Rp) *</label>
                    <input name="harga" type="number" min="0" required placeholder="50000" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400 transition">
                </div>
            </div>
            <div>
                <label class="text-sm font-bold text-gray-700 mb-1 block">Nama File Gambar / URL</label>
                <input name="imgUrl" placeholder="Cth: Borobudur.jpg" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400 transition">
            </div>
            <div>
                <label class="text-sm font-bold text-gray-700 mb-1 block">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Tuliskan deskripsi singkat..." class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-yellow-400 resize-none transition"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                <button type="button" onclick="closeModal('modal-tambah')" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-[#f39c12] hover:bg-[#e67e22] text-white rounded-xl font-bold transition shadow-lg shadow-yellow-500/30">
                    <i class="fas fa-save mr-1"></i> Simpan Destinasi
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit" class="modal">
    <div class="modal-box">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-lg"><i class="fas fa-edit mr-2"></i>Edit Destinasi</h3>
            <button onclick="closeModal('modal-edit')" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
        </div>
        <form action="proses/prosesEditDestinasi.php" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id" id="edit-id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Nama Destinasi</label>
                    <input name="nama" id="edit-nama" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Lokasi</label>
                    <input name="lokasi" id="edit-lokasi" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Harga Tiket (Rp)</label>
                    <input name="harga" id="edit-harga" type="number" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
                </div>
                 <div>
                    <label class="text-sm font-bold text-gray-700 mb-1 block">Kategori</label>
                    <select name="kategori" id="edit-kategori" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400 transition">
                        <option value="">-- Pilih Kategori --</option>
                        <option>Alam</option><option>Budaya</option><option>Bahari</option>
                        <option>Religi</option><option>Kuliner</option><option>Hiburan</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-sm font-bold text-gray-700 mb-1 block">Nama File Gambar / URL</label>
                <input name="imgUrl" id="edit-imgUrl" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                <button type="button" onclick="closeModal('modal-edit')" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition shadow-lg shadow-blue-500/30">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============ NAVIGASI ============
function showSection(name) {
    // Sembunyikan semua section
    ['dashboard', 'destinasi', 'bps'].forEach(s => {
        const el = document.getElementById('section-' + s);
        if(el) el.classList.toggle('hidden', s !== name);
    });

    // Ubah warna menu sidebar yang aktif
    document.querySelectorAll('.nav-link').forEach(a => {
        if (a.dataset.section === name) {
            a.classList.add('bg-white/20', 'text-yellow-400');
        } else {
            a.classList.remove('bg-white/20', 'text-yellow-400');
        }
    });

    // Ganti Judul Header
    const titles = { dashboard: 'Dashboard Admin', destinasi: 'Kelola Destinasi', bps: 'Data BPS Pariwisata' };
    document.getElementById('page-title').textContent = titles[name] || 'Panel Admin';
}

// ============ MODAL ============
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
// Tutup modal jika klik background gelap
document.querySelectorAll('.modal').forEach(m => {
    m.addEventListener('click', function(e) { if(e.target === this) this.classList.remove('active'); });
});

// Lempar data ke modal edit
function openEditModal(data) {
    document.getElementById('edit-id').value = data.id;
    document.getElementById('edit-nama').value = data.nama;
    document.getElementById('edit-lokasi').value = data.lokasi;
    document.getElementById('edit-harga').value = data.harga;
    document.getElementById('edit-imgUrl').value = data.imgUrl || '';
    
    // Set selected kategori
    const selKat = document.getElementById('edit-kategori');
    for (let o of selKat.options) {
        if (o.value === data.kategori) o.selected = true;
    }
    
    openModal('modal-edit');
}

// Init section saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    const urlSection = new URLSearchParams(location.search).get('section') || 'dashboard';
    showSection(urlSection);
});
</script>
</body>
</html>