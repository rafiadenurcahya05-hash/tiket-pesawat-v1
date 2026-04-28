<?php
session_start();
require __DIR__ . '/server/koneksi.php';

// Proteksi Halaman: Cek Cookie atau Session
$user_id = $_SESSION['id'] ?? $_COOKIE['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit;
}

// Pastikan data session terisi jika login via cookie
if (!isset($_SESSION['nama']) && isset($_COOKIE['username'])) {
    $_SESSION['id'] = $_COOKIE['user_id'];
    $_SESSION['nama'] = $_COOKIE['username'];
    $_SESSION['role'] = $_COOKIE['role'] ?? 'user';
}

// Jika admin nyasar ke sini, lempar ke dashboard admin
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: dashboard_admin.php");
    exit();
}

// Ambil data destinasi
$result = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY rating DESC");
$destinasi = [];
while ($row = mysqli_fetch_assoc($result)) {
    $destinasi[] = $row;
}

// Ambil daftar provinsi unik
$provinsiList = array_unique(array_filter(array_column($destinasi, 'provinsi')));
sort($provinsiList);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Jelajah.In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Quicksand', sans-serif; background: #f8fafc; }
        .navbar { background: linear-gradient(135deg, #1e3c5c 0%, #2b6c94 100%); }
        .hero-user { background: linear-gradient(135deg, #1e3c5c 0%, #2b6c94 60%, #f39c12 100%); }
        .card-dest { transition: all 0.3s cubic-bezier(0.175,0.885,0.32,1.275); }
        .card-dest:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.12); }
        .badge { display: inline-flex; align-items: center; border-radius: 999px; font-size: 0.7rem; font-weight: 700; padding: 2px 10px; }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 1000; overflow-y: auto; }
        .modal.active { display: flex; align-items: flex-start; justify-content: center; padding: 2rem 1rem; }
        .modal-box { background: white; border-radius: 1.5rem; width: 100%; max-width: 600px; animation: slideUp 0.3s ease; }
        @keyframes slideUp { from { transform: translateY(30px); opacity:0; } to { transform: translateY(0); opacity:1; } }
        .img-cover { object-fit: cover; }
        .filter-btn { transition: all 0.2s; }
        .filter-btn.active { background: #f39c12; color: white; border-color: #f39c12; }
        .toast-user { position: fixed; bottom: 2rem; right: 2rem; z-index: 9999; transform: translateX(120%); transition: transform 0.4s cubic-bezier(0.175,0.885,0.32,1.275); }
        .toast-user.show { transform: translateX(0); }
        .wishlist-btn.wishlisted { background: #ef4444; color: white; }
    </style>
</head>
<body>

<nav class="navbar text-white px-6 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 bg-yellow-400 rounded-xl flex items-center justify-center">
            <i class="fas fa-compass text-white"></i>
        </div>
        <span class="font-bold text-xl">Jelajah<span class="text-yellow-400">.In</span></span>
    </div>
    <div class="flex items-center gap-4">
        <a href="index.html" class="text-white/80 hover:text-white text-sm font-semibold transition hidden sm:block">
            <i class="fas fa-home mr-1"></i> Beranda
        </a>
        <div class="relative group">
            <button class="flex items-center gap-2 bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl transition">
                <div class="w-7 h-7 bg-yellow-400 rounded-full flex items-center justify-center font-bold text-sm text-white">
                    <?= strtoupper(substr($_SESSION['nama'] ?? 'U', 0, 1)) ?>
                </div>
                <span class="text-sm font-semibold hidden sm:block"><?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?></span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                <a href="logout.php" class="flex items-center gap-2 px-4 py-3 text-red-600 hover:bg-red-50 text-sm font-semibold transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="hero-user text-white py-14 px-6 text-center relative overflow-hidden">
    <div class="relative z-10 max-w-2xl mx-auto">
        <p class="text-yellow-300 font-bold text-sm mb-2 tracking-widest uppercase">Selamat Datang 👋</p>
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Halo, <?= htmlspecialchars(explode(' ', $_SESSION['nama'] ?? 'Traveler')[0]) ?>!</h1>
        <p class="text-white/80 text-lg mb-6">Temukan destinasi wisata terbaik di seluruh Indonesia</p>
        
        <div class="flex items-center bg-white rounded-2xl shadow-lg overflow-hidden max-w-xl mx-auto">
            <input type="text" id="searchInput" oninput="filterDest()" placeholder="🔍 Cari destinasi wisata..." class="flex-1 px-5 py-3.5 text-gray-700 text-sm outline-none font-semibold">
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-6">
    <div class="flex flex-wrap gap-2 items-center">
        <span class="text-sm font-bold text-gray-600 mr-2">Filter:</span>
        <button class="filter-btn active border border-gray-300 px-4 py-1.5 rounded-full text-sm font-semibold text-gray-700" onclick="filterByKategori(this,'')">Semua</button>
        <?php
        $kategoris = array_unique(array_filter(array_column($destinasi, 'kategori')));
        foreach($kategoris as $kat): ?>
        <button class="filter-btn border border-gray-300 px-4 py-1.5 rounded-full text-sm font-semibold text-gray-700" onclick="filterByKategori(this,'<?= htmlspecialchars($kat) ?>')"><?= htmlspecialchars($kat) ?></button>
        <?php endforeach; ?>

        <select onchange="filterByProvinsi(this.value)" class="ml-auto border border-gray-300 rounded-full px-4 py-1.5 text-sm font-semibold text-gray-700 focus:outline-none">
            <option value="">🗺️ Semua Provinsi</option>
            <?php foreach($provinsiList as $p): ?>
            <option value="<?= htmlspecialchars($p) ?>"><?= htmlspecialchars($p) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 pb-12">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="dest-grid">
        <?php foreach($destinasi as $d): 
            // Update path di sini: tambahkan ../assets/img/ di depan nama file
            if (stripos($d['nama'], 'Raja Ampat') !== false) { $d['imgUrl'] = '../assets/img/Raja-Ampat.jpg'; }
            elseif (stripos($d['nama'], 'Borobudur') !== false) { $d['imgUrl'] = '../assets/img/Borobudur.jpg'; }
            elseif (stripos($d['nama'], 'Bromo') !== false) { $d['imgUrl'] = '../assets/img/Bromo.jpg'; }
            elseif (stripos($d['nama'], 'Toba') !== false) { $d['imgUrl'] = '../assets/img/download.jpg'; } 
            elseif (stripos($d['nama'], 'Kuta') !== false) { $d['imgUrl'] = '../assets/img/Kuta-Beach.jpg'; }
            elseif (stripos($d['nama'], 'Nasional') !== false) { $d['imgUrl'] = '../assets/img/Munas.jpg'; }
        ?>
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden card-dest border border-gray-100"
             data-nama="<?= strtolower(htmlspecialchars($d['nama'])) ?>"
             data-kategori="<?= htmlspecialchars($d['kategori'] ?? '') ?>"
             data-provinsi="<?= htmlspecialchars($d['provinsi'] ?? '') ?>"
             data-rating="<?= $d['rating'] ?>"
             data-harga="<?= $d['harga'] ?>">
            
            <div class="relative overflow-hidden h-52">
                <img src="<?= htmlspecialchars($d['imgUrl'] ?? '') ?>" class="w-full h-full img-cover" onerror="this.src='https://placehold.co/400x200?text=🏝️+No+Image'">
                <div class="absolute top-3 left-3 bg-white/90 px-2.5 py-1 rounded-full text-xs font-bold text-yellow-600 shadow">
                    ⭐ <?= number_format($d['rating'], 1) ?>
                </div>
            </div>

            <div class="p-4">
                <h3 class="font-bold text-gray-800 text-base mb-1 truncate"><?= htmlspecialchars($d['nama']) ?></h3>
                <p class="text-gray-500 text-xs mb-2 flex items-center gap-1">
                    <i class="fas fa-map-pin text-yellow-500"></i> <?= htmlspecialchars($d['lokasi']) ?>
                </p>
                <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100">
                    <div>
                        <span class="text-xs text-gray-400">Harga tiket</span>
                        <p class="font-bold text-[#2b6c94] text-base">Rp <?= number_format($d['harga'],0,',','.') ?></p>
                    </div>
                    <button onclick='openDetailModal(<?= json_encode($d) ?>)' class="bg-[#f39c12] text-white px-4 py-2 rounded-xl text-xs font-bold transition hover:bg-[#e67e22]">Detail</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

    <!-- Empty state -->
    <div id="empty-state" class="hidden text-center py-20">
        <div class="text-6xl mb-4">🔍</div>
        <h3 class="text-xl font-bold text-gray-700">Destinasi tidak ditemukan</h3>
        <p class="text-gray-400 mt-2">Coba ubah filter atau kata kunci pencarian</p>
    </div>
</div>

<!-- ============ MODAL DETAIL ============ -->
<div id="modal-detail" class="modal">
    <div class="modal-box max-w-2xl">
        <div class="relative h-64 overflow-hidden rounded-t-2xl">
            <img id="detail-img" src="" class="w-full h-full img-cover" onerror="this.src='https://placehold.co/600x300?text=No+Image'">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <button onclick="closeModal('modal-detail')" class="absolute top-4 right-4 w-9 h-9 bg-white/20 backdrop-blur-sm text-white rounded-full hover:bg-white/40 transition flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
            <div class="absolute bottom-4 left-5 text-white">
                <h2 class="text-2xl font-bold" id="detail-nama"></h2>
                <p class="text-white/80 text-sm" id="detail-lokasi"></p>
            </div>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap gap-3 mb-4">
                <span class="badge bg-yellow-100 text-yellow-700 text-sm px-3 py-1" id="detail-rating"></span>
                <span class="badge bg-blue-100 text-blue-700 text-sm px-3 py-1" id="detail-provinsi"></span>
                <span class="badge bg-green-100 text-green-700 text-sm px-3 py-1" id="detail-kategori"></span>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed mb-5" id="detail-deskripsi"></p>
            
            <div class="bg-gradient-to-r from-[#1e3c5c] to-[#2b6c94] rounded-2xl p-4 text-white flex justify-between items-center">
                <div>
                    <p class="text-white/70 text-xs">Harga Tiket Masuk</p>
                    <p class="text-2xl font-bold" id="detail-harga"></p>
                </div>
                <button onclick="pesanTiket()" class="bg-[#f39c12] hover:bg-[#e67e22] text-white px-6 py-3 rounded-xl font-bold transition shadow-lg">
                    <i class="fas fa-ticket-alt mr-2"></i>Pesan Tiket
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast-user" class="toast-user bg-green-500 text-white px-5 py-3.5 rounded-2xl shadow-xl font-bold flex items-center gap-2 min-w-[250px]">
    <i class="fas fa-check-circle"></i>
    <span id="toast-msg">Berhasil!</span>
</div>

<div class="max-w-7xl mx-auto px-6 mb-12">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-12 h-12 bg-[#1e3c5c] text-white rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-xl text-gray-800">Statistik Kunjungan Wisman</h3>
                <p class="text-sm text-gray-500 font-medium">Data Terkini dari BPS Indonesia</p>
            </div>
        </div>

        <div class="bg-slate-50 p-6 rounded-2xl border border-gray-50">
            <h4 class="text-sm font-bold text-gray-600 mb-4 text-center">Visualisasi Tren Kunjungan</h4>
            
            <div class="relative w-full h-[250px]">
                <canvas id="bpsChart"></canvas>
            </div>
            
        </div>

            <div class="flex flex-col">
                <h4 class="text-sm font-bold text-gray-600 mb-4">Detail Data Pintu Masuk</h4>
                <div class="overflow-hidden border border-gray-100 rounded-2xl">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-100 text-gray-700">
                            <tr>
                                <th class="px-5 py-3 font-bold">Pintu Masuk</th>
                                <th class="px-5 py-3 font-bold text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="bps-table-body" class="divide-y divide-gray-50">
                            <tr>
                                <td colspan="2" class="px-5 py-10 text-center text-gray-400 italic">Memproses data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentDest = {};
let wishlist = JSON.parse(localStorage.getItem('jelajahin-wishlist') || '[]');

// Tandai wishlist saat load
wishlist.forEach(id => {
    const btn = document.querySelector(`.wishlist-btn[onclick*="toggleWishlist(this, ${id},"]`);
    if (btn) btn.classList.add('wishlisted');
});

function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
document.querySelectorAll('.modal').forEach(m => {
    m.addEventListener('click', function(e) { if(e.target === this) this.classList.remove('active'); });
});

function openDetailModal(data) {
    currentDest = data;
    document.getElementById('detail-img').src       = data.imgUrl || '';
    document.getElementById('detail-nama').textContent    = data.nama;
    document.getElementById('detail-lokasi').textContent  = '📍 ' + data.lokasi;
    document.getElementById('detail-rating').textContent  = '⭐ ' + data.rating + ' / 5.0';
    document.getElementById('detail-provinsi').textContent = '🗺️ ' + (data.provinsi || '-');
    document.getElementById('detail-kategori').textContent = data.kategori || 'Umum';
    document.getElementById('detail-deskripsi').textContent = data.deskripsi || 'Tidak ada deskripsi tersedia untuk destinasi ini.';
    document.getElementById('detail-harga').textContent = 'Rp ' + parseInt(data.harga).toLocaleString('id-ID');
    openModal('modal-detail');
}

function pesanTiket() {
    closeModal('modal-detail');
    showToast(`🎫 Tiket ke ${currentDest.nama} berhasil dipesan! Terima kasih.`, 'green');
}

function toggleWishlist(btn, id, nama) {
    const idx = wishlist.indexOf(id);
    if (idx === -1) {
        wishlist.push(id);
        btn.classList.add('wishlisted');
        showToast(`❤️ ${nama} ditambahkan ke wishlist!`, 'red');
    } else {
        wishlist.splice(idx, 1);
        btn.classList.remove('wishlisted');
        showToast(`💔 ${nama} dihapus dari wishlist`, 'gray');
    }
    localStorage.setItem('jelajahin-wishlist', JSON.stringify(wishlist));
}

function showToast(msg, color='green') {
    const toast = document.getElementById('toast-user');
    const colors = { green: 'bg-green-500', red: 'bg-red-500', gray: 'bg-gray-500', blue: 'bg-blue-500' };
    toast.className = `toast-user ${colors[color]||'bg-green-500'} text-white px-5 py-3.5 rounded-2xl shadow-xl font-bold flex items-center gap-2 min-w-[250px]`;
    document.getElementById('toast-msg').textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// Filter & sort
let activeKategori = '';
let activeProvinsi = '';
let searchQ = '';
let sortMode = 'rating';

function filterDest() {
    searchQ = document.getElementById('searchInput').value.toLowerCase();
    applyFilter();
}
function filterByKategori(btn, kat) {
    activeKategori = kat;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    applyFilter();
}
function filterByProvinsi(val) { activeProvinsi = val; applyFilter(); }
function sortDest(val) { sortMode = val; applyFilter(); }

function applyFilter() {
    const cards = Array.from(document.querySelectorAll('#dest-grid .card-dest'));
    let visible = cards.filter(card => {
        const nama     = card.dataset.nama;
        const kat      = card.dataset.kategori;
        const prov     = card.dataset.provinsi;
        const matchQ   = !searchQ || nama.includes(searchQ);
        const matchKat = !activeKategori || kat === activeKategori;
        const matchProv = !activeProvinsi || prov === activeProvinsi;
        return matchQ && matchKat && matchProv;
    });

    // Sort
    visible.sort((a, b) => {
        if (sortMode === 'rating')      return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
        if (sortMode === 'harga-asc')   return parseFloat(a.dataset.harga)  - parseFloat(b.dataset.harga);
        if (sortMode === 'harga-desc')  return parseFloat(b.dataset.harga)  - parseFloat(a.dataset.harga);
        if (sortMode === 'nama')        return a.dataset.nama.localeCompare(b.dataset.nama);
        return 0;
    });

    // Hide all, re-append visible in order
    cards.forEach(c => c.style.display = 'none');
    const grid = document.getElementById('dest-grid');
    visible.forEach(c => { c.style.display = ''; grid.appendChild(c); });

    document.getElementById('dest-count').textContent = visible.length + ' destinasi ditemukan';
    document.getElementById('empty-state').classList.toggle('hidden', visible.length > 0);
}

async function loadBpsStatistics() {
    try {
        const response = await fetch('proses/getBpsData.php');
        const json = await response.json();

        if (json.status === 'OK') {
            const tableBody = document.getElementById('bps-table-body');
            const dataContent = json.datacontent;
            const labelsInfo = json.vervar; // Info label pintu masuk
            
            let tableHtml = '';
            let chartLabels = [];
            let chartData = [];
            
            // Ambil 6 data teratas untuk ditampilkan
            let count = 0;
            for (const key in dataContent) {
                if (count >= 6) break;
                
                // Mendapatkan nama label pintu masuk
                const labelObj = labelsInfo.find(l => l.val == key.split('')[0]);
                const labelName = labelObj ? labelObj.label : "Pintu Lainnya";
                
                // Mendapatkan nilai (mengambil nilai pertama di dalam objek baris)
                const value = Object.values(dataContent[key])[0]; 

                // Masukkan ke array untuk Grafik
                chartLabels.push(labelName);
                chartData.push(value);

                // Buat baris Tabel
                tableHtml += `
                    <tr class="hover:bg-blue-50/30 transition">
                        <td class="px-5 py-3.5 font-semibold text-gray-700">${labelName}</td>
                        <td class="px-5 py-3.5 text-right font-bold text-[#2b6c94]">${parseInt(value).toLocaleString('id-ID')}</td>
                    </tr>
                `;
                count++;
            }
            
            // Update Tabel
            tableBody.innerHTML = tableHtml;

            // Gambar Grafik (Chart.js)
            const ctx = document.getElementById('bpsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Jumlah Kunjungan',
                        data: chartData,
                        backgroundColor: 'rgba(30, 60, 92, 0.8)', // Warna biru sesuai brand Jelajah.In
                        borderColor: '#1e3c5c',
                        borderWidth: 1,
                        borderRadius: 8,
                        hoverBackgroundColor: '#f39c12' // Warna kuning saat di-hover
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    } catch (error) {
        document.getElementById('bps-table-body').innerHTML = `
            <tr><td colspan="2" class="px-5 py-5 text-center text-red-500">Gagal memuat data statistik BPS.</td></tr>
        `;
    }
}

document.addEventListener('DOMContentLoaded', loadBpsStatistics);

</script>
</body>
</html>