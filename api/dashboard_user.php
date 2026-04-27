<?php
session_start();
require 'server/koneksi.php';

// Hanya user yang bisa akses (redirect admin ke admin dashboard)
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] === 'admin') {
    header("Location: dashboard_admin.php");
    exit();
}

// Ambil destinasi
$result   = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY rating DESC");
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

<!-- ============ NAVBAR ============ -->
<nav class="navbar text-white px-6 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 bg-yellow-400 rounded-xl flex items-center justify-center">
            <i class="fas fa-compass text-white"></i>
        </div>
        <span class="font-bold text-xl">Jelajah<span class="text-yellow-400">.In</span></span>
    </div>
    <div class="flex items-center gap-4">
        <a href="index.php" class="text-white/80 hover:text-white text-sm font-semibold transition hidden sm:block">
            <i class="fas fa-home mr-1"></i> Beranda
        </a>
        <div class="relative group">
            <button class="flex items-center gap-2 bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl transition">
                <div class="w-7 h-7 bg-yellow-400 rounded-full flex items-center justify-center font-bold text-sm text-white">
                    <?= strtoupper(substr($_SESSION['nama'], 0, 1)) ?>
                </div>
                <span class="text-sm font-semibold hidden sm:block"><?= htmlspecialchars($_SESSION['nama']) ?></span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="font-bold text-gray-800 text-sm"><?= htmlspecialchars($_SESSION['nama']) ?></p>
                    <p class="text-xs text-gray-500"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></p>
                    <span class="badge bg-blue-100 text-blue-700 mt-1">USER</span>
                </div>
                <a href="logout.php" class="flex items-center gap-2 px-4 py-3 text-red-600 hover:bg-red-50 text-sm font-semibold transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- ============ HERO ============ -->
<div class="hero-user text-white py-14 px-6 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-20 w-32 h-32 bg-white rounded-full"></div>
        <div class="absolute bottom-5 right-32 w-20 h-20 bg-white rounded-full"></div>
        <div class="absolute top-20 right-10 w-14 h-14 bg-yellow-300 rounded-full"></div>
    </div>
    <div class="relative z-10 max-w-2xl mx-auto">
        <p class="text-yellow-300 font-bold text-sm mb-2 tracking-widest uppercase">Selamat Datang 👋</p>
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Halo, <?= htmlspecialchars(explode(' ', $_SESSION['nama'])[0]) ?>!</h1>
        <p class="text-white/80 text-lg mb-6">Temukan destinasi wisata terbaik di seluruh Indonesia</p>
        
        <!-- Search -->
        <div class="flex items-center bg-white rounded-2xl shadow-lg overflow-hidden max-w-xl mx-auto">
            <input type="text" id="searchInput" oninput="filterDest()" placeholder="🔍  Cari destinasi wisata..."
                class="flex-1 px-5 py-3.5 text-gray-700 text-sm outline-none font-semibold">
            <button class="bg-[#f39c12] hover:bg-[#e67e22] text-white px-5 py-3.5 font-bold text-sm transition">
                Cari
            </button>
        </div>

        <!-- Stats -->
        <div class="flex justify-center gap-8 mt-8">
            <div class="text-center">
                <p class="text-2xl font-bold"><?= count($destinasi) ?></p>
                <p class="text-white/70 text-xs">Destinasi</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold"><?= count($provinsiList) ?></p>
                <p class="text-white/70 text-xs">Provinsi</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold"><?= count(array_unique(array_filter(array_column($destinasi,'kategori')))) ?>+</p>
                <p class="text-white/70 text-xs">Kategori</p>
            </div>
        </div>
    </div>
</div>

<!-- ============ FILTER ============ -->
<div class="max-w-7xl mx-auto px-6 py-6">
    <div class="flex flex-wrap gap-2 items-center">
        <span class="text-sm font-bold text-gray-600 mr-2">Filter:</span>
        <button class="filter-btn active border border-gray-300 px-4 py-1.5 rounded-full text-sm font-semibold text-gray-700" onclick="filterByKategori(this,'')">
            Semua
        </button>
        <?php
        $kategoris = array_unique(array_filter(array_column($destinasi, 'kategori')));
        $icons = ['Alam'=>'fa-mountain','Budaya'=>'fa-landmark','Bahari'=>'fa-water','Religi'=>'fa-mosque','Kuliner'=>'fa-utensils','Hiburan'=>'fa-star'];
        foreach($kategoris as $kat): ?>
        <button class="filter-btn border border-gray-300 px-4 py-1.5 rounded-full text-sm font-semibold text-gray-700" onclick="filterByKategori(this,'<?= htmlspecialchars($kat) ?>')">
            <i class="fas <?= $icons[$kat] ?? 'fa-tag' ?> mr-1"></i> <?= htmlspecialchars($kat) ?>
        </button>
        <?php endforeach; ?>

        <!-- Filter Provinsi -->
        <select onchange="filterByProvinsi(this.value)" class="ml-auto border border-gray-300 rounded-full px-4 py-1.5 text-sm font-semibold text-gray-700 focus:outline-none focus:border-yellow-400">
            <option value="">🗺️ Semua Provinsi</option>
            <?php foreach($provinsiList as $p): ?>
            <option value="<?= htmlspecialchars($p) ?>"><?= htmlspecialchars($p) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Sort -->
        <select onchange="sortDest(this.value)" class="border border-gray-300 rounded-full px-4 py-1.5 text-sm font-semibold text-gray-700 focus:outline-none focus:border-yellow-400">
            <option value="rating">⭐ Rating Tertinggi</option>
            <option value="harga-asc">💰 Harga Terendah</option>
            <option value="harga-desc">💎 Harga Tertinggi</option>
            <option value="nama">🔤 Nama A-Z</option>
        </select>
    </div>
</div>

<!-- ============ DESTINASI GRID ============ -->
<div class="max-w-7xl mx-auto px-6 pb-12">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-[#1e3c5c]">Destinasi Wisata</h2>
        <span class="text-sm text-gray-500" id="dest-count"><?= count($destinasi) ?> destinasi ditemukan</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="dest-grid">
        <?php foreach($destinasi as $d): 
            $katIcons = ['Alam'=>'🏔️','Budaya'=>'🏛️','Bahari'=>'🌊','Religi'=>'🕌','Kuliner'=>'🍜','Hiburan'=>'🎡'];
            $katEmoji = $katIcons[$d['kategori'] ?? ''] ?? '📍';
        ?>
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden card-dest border border-gray-100"
             data-nama="<?= strtolower(htmlspecialchars($d['nama'])) ?>"
             data-kategori="<?= htmlspecialchars($d['kategori'] ?? '') ?>"
             data-provinsi="<?= htmlspecialchars($d['provinsi'] ?? '') ?>"
             data-rating="<?= $d['rating'] ?>"
             data-harga="<?= $d['harga'] ?>">
            
            <!-- Gambar -->
            <div class="relative overflow-hidden h-52">
                <img src="<?= htmlspecialchars($d['imgUrl'] ?? '') ?>" 
                     class="w-full h-full img-cover transition-transform duration-500 hover:scale-110"
                     onerror="this.src='https://placehold.co/400x200?text=🏝️+No+Image'">
                <!-- Badge Rating -->
                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-full text-xs font-bold text-yellow-600 shadow">
                    ⭐ <?= number_format($d['rating'], 1) ?>
                </div>
                <!-- Kategori badge -->
                <?php if($d['kategori']): ?>
                <div class="absolute top-3 right-3 bg-[#1e3c5c]/80 backdrop-blur-sm px-2.5 py-1 rounded-full text-xs font-bold text-white">
                    <?= $katEmoji ?> <?= htmlspecialchars($d['kategori']) ?>
                </div>
                <?php endif; ?>
                <!-- Wishlist btn -->
                <button onclick="toggleWishlist(this, <?= $d['id'] ?>, '<?= addslashes($d['nama']) ?>')" 
                        class="wishlist-btn absolute bottom-3 right-3 w-9 h-9 bg-white/90 rounded-full flex items-center justify-center shadow hover:bg-red-500 hover:text-white transition text-gray-500">
                    <i class="fas fa-heart text-sm"></i>
                </button>
            </div>

            <!-- Konten -->
            <div class="p-4">
                <h3 class="font-bold text-gray-800 text-base leading-tight mb-1 line-clamp-1"><?= htmlspecialchars($d['nama']) ?></h3>
                <p class="text-gray-500 text-xs mb-1 flex items-center gap-1">
                    <i class="fas fa-map-pin text-yellow-500"></i>
                    <span class="truncate"><?= htmlspecialchars($d['lokasi']) ?></span>
                </p>
                <?php if($d['provinsi']): ?>
                <span class="badge bg-blue-50 text-blue-700 mb-2"><?= htmlspecialchars($d['provinsi']) ?></span>
                <?php endif; ?>
                <?php if($d['deskripsi']): ?>
                <p class="text-gray-400 text-xs leading-relaxed mt-1 line-clamp-2"><?= htmlspecialchars($d['deskripsi']) ?></p>
                <?php endif; ?>
                <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100">
                    <div>
                        <span class="text-xs text-gray-400">Harga tiket</span>
                        <p class="font-bold text-[#2b6c94] text-base">Rp <?= number_format($d['harga'],0,',','.') ?></p>
                    </div>
                    <button onclick='openDetailModal(<?= json_encode($d) ?>)' 
                            class="bg-[#f39c12] hover:bg-[#e67e22] text-white px-4 py-2 rounded-xl text-xs font-bold transition">
                        Detail & Pesan
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
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
</script>
</body>
</html>
