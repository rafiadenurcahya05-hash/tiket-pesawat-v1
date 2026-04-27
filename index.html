<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
$loggedInUser = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jelajah.In | Pemesanan Tiket Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: { preflight: false },
        theme: {
          extend: {
            colors: { primary: '#2b6c94', secondary: '#f39c12' },
            borderRadius: { '3xl': '1.5rem', '4xl': '2rem' }
          }
        }
      }
    </script>
    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font-family: "Quicksand", sans-serif; background: #fdfdfd; color: #2d3e50; line-height: 1.6; overflow-x: hidden; transition: background 0.3s ease, color 0.2s ease; }
      
      /* --- NAVBAR PREMIUM --- */
      .navbar {
          background: rgba(177, 172, 172, 0.85); 
          backdrop-filter: blur(12px);
          -webkit-backdrop-filter: blur(12px);
          padding: 1rem 5%; 
          box-shadow: 0 4px 20px rgba(0,0,0,0.05); 
          display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; 
          position: sticky; top: 0; z-index: 50; 
          transition: all 0.3s ease;
      }
      .logo { font-size: 1.8rem; font-weight: 700; color: #2b6c94; cursor: pointer; transition: transform 0.3s; }
      .logo:hover { transform: scale(1.05); }
      .logo i { color: #f39c12; margin-right: 5px; animation: spin-slow 10s linear infinite; }
      @keyframes spin-slow { 100% { transform: rotate(360deg); } }
      .nav-links { display: flex; gap: 2rem; align-items: center; flex-wrap: wrap; }
      .nav-links a { text-decoration: none; color: #2d3e50; font-weight: 600; transition: all 0.3s ease; cursor: pointer; position: relative; }
      .nav-links a::after {
          content: ''; position: absolute; width: 0; height: 2px; bottom: -4px; left: 0;
          background-color: #f39c12; transition: width 0.3s ease;
      }
      .nav-links a:hover { color: #2b6c94; }
      .nav-links a:hover::after { width: 100%; }
      .btn-outline { border: 2px solid #2b6c94; padding: 0.5rem 1.2rem; border-radius: 50px; color: #2b6c94; font-weight: 600; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: transparent; cursor: pointer; display: inline-block; text-decoration: none; }
      .btn-outline:hover { background: #2b6c94; color: white; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(43, 108, 148, 0.2); }

      
      /* --- HERO SECTION FULL SCREEN dengan CAROUSEL --- */
      .hero {
          position: relative;
          height: 100vh;
          min-height: 550px;
          width: 100%;
          display: flex;
          align-items: center;
          justify-content: center;
          text-align: center;
          padding: 0 1rem;
          background-size: cover;
          background-position: center;
          background-repeat: no-repeat;
          transition: background-image 1s ease-in-out;
      }
      .hero-overlay {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: linear-gradient(rgba(0, 20, 40, 0.7), rgba(0, 40, 70, 0.5));
          z-index: 1;
      }
      .hero-content {
          position: relative;
          z-index: 2;
          color: white;
          max-width: 800px;
          margin: 0 auto;
          padding: 0 20px;
      }
      .hero-content h1 { 
          font-size: 3.5rem; 
          margin-bottom: 0.5rem; 
          text-shadow: 2px 4px 10px rgba(0,0,0,0.5); 
          min-height: 120px; 
          animation: slideDown 0.8s ease-out; 
      }
      .hero-content p { 
          font-size: 1.2rem; 
          margin-bottom: 2.5rem; 
          animation: fadeIn 1.2s ease-out; 
      }
      @keyframes slideDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
      @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
      
      .cursor { display: inline-block; width: 4px; margin-left: 2px; background-color: #f39c12; animation: blink 0.7s infinite; }
      @keyframes blink { 0%, 50% { opacity: 1; } 51%, 100% { opacity: 0; } }
      
      .search-box { background: white; border-radius: 60px; padding: 0.5rem 0.5rem 0.5rem 2rem; display: inline-flex; align-items: center; flex-wrap: wrap; gap: 10px; box-shadow: 0 15px 40px rgba(0,0,0,0.3); transform: translateY(0); transition: all 0.3s; animation: slideUpFade 1s ease-out; }
      .search-box:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(0,0,0,0.4); }
      .search-box input { border: none; padding: 0.8rem 0; font-size: 1.1rem; min-width: 300px; outline: none; background: transparent; color: #1e3c5c; }
      .search-box button { background: #f39c12; border: none; padding: 1rem 2.5rem; border-radius: 50px; color: white; font-weight: bold; cursor: pointer; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
      .search-box button:hover { background: #e67e22; transform: scale(1.05); }

      /* --- SECTION TITLES & CARDS --- */
      .section-title { text-align: center; margin: 4rem 0 3rem; font-size: 2.5rem; font-weight: 800; color: #1e3c5c; position: relative; }
      .section-title:after { content: ""; display: block; width: 80px; height: 5px; background: #f39c12; margin: 1rem auto; border-radius: 10px; transition: width 0.3s; }
      .section-title:hover:after { width: 120px; }
      
      .destinasi-container, .paket-container { max-width: 1300px; margin: 0 auto; padding: 0 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 35px; }
      
      .card, .paket-card { background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); cursor: pointer; border: 2px solid transparent; position: relative; top: 0; }
      .card:hover, .paket-card:hover { top: -12px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); border-color: rgba(243, 156, 18, 0.3); }
      
      .card-img { height: 220px; display: flex; align-items: flex-start; justify-content: space-between; padding: 15px; color: white; font-weight: bold; position: relative; overflow: hidden; }
      .card-img img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; transition: transform 0.6s ease; }
      .card:hover .card-img img { transform: scale(1.1); }
      .card-img::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); z-index: 2; }
      .rating { background: rgba(255,255,255,0.2); backdrop-filter: blur(5px); padding: 6px 12px; border-radius: 50px; font-size: 0.95rem; z-index: 3; position: relative; display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.3); }
      
      .card-content { padding: 25px; }
      .card-content h3 { font-size: 1.5rem; margin-bottom: 8px; color: #1e3c5c; font-weight: 700; transition: color 0.3s; }
      .card:hover .card-content h3 { color: #f39c12; }
      .location { color: #6c7a89; margin-bottom: 15px; font-size: 1rem; }
      .location i { color: #f39c12; width: 20px; }
      .price { font-size: 1.6rem; font-weight: 800; color: #2b6c94; margin: 15px 0; }
      .price small { font-size: 0.95rem; font-weight: 600; color: #95a5a6; }
      
      .btn-card { background: linear-gradient(135deg, #f39c12, #e67e22); color: white; border: none; padding: 12px 0; width: 100%; border-radius: 14px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: all 0.3s ease; margin-top: 10px; box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3); }
      .btn-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(243, 156, 18, 0.5); }

      /* Paket Section */
      .paket-section { background: #f8fafc; padding: 4rem 5%; margin-top: 2rem; position: relative; }
      .paket-card { padding: 2.5rem; text-align: center; z-index: 1; }
      .paket-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #ffffff 0%, #f0f5fa 100%); z-index: -1; transition: opacity 0.3s; opacity: 0; }
      .paket-card:hover::before { opacity: 1; }
      .paket-card i { font-size: 3rem; color: #f39c12; margin-bottom: 1.5rem; z-index: 2; position: relative; transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
      .paket-card:hover i { transform: scale(1.2) rotate(10deg); color: #e67e22; }

      .tw-input { width: 100%; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 12px; outline: none; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); font-weight: 500; background: white; color: #1f2937; }
      .tw-input:focus { border-color: #f39c12; box-shadow: 0 5px 15px rgba(243, 156, 18, 0.15); transform: translateY(-2px); }
      .tw-label { display: block; font-weight: 700; margin-bottom: 8px; color: #374151; }

      /* Footer & Toast */
      .kontak-section { background: linear-gradient(135deg, #1e3c5c, #2b6c94); color: white; padding: 4rem 5%; }
      .kontak-container { max-width: 1300px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; }
      .kontak-item i { margin-right: 12px; color: #f39c12; font-size: 1.2rem; }
      
      .toast { visibility: hidden; min-width: 300px; background: rgba(43, 108, 148, 0.95); backdrop-filter: blur(10px); color: white; text-align: center; border-radius: 50px; padding: 18px 24px; position: fixed; bottom: 40px; left: 50%; transform: translate(-50%, 20px); z-index: 999; box-shadow: 0 10px 30px rgba(0,0,0,0.3); opacity: 0; transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55); font-weight: 600; font-size: 1.1rem; }
      .toast.show { visibility: visible; opacity: 1; transform: translate(-50%, 0); }

    </style>
</head>
<body>

<div id="page-home" class="page-view active">
    <nav class="navbar">
        <div class="logo" onclick="navigateTo('page-home')">
            <i class="fas fa-compass"></i> Jelajah.In!
        </div>
        <div class="nav-links">
            <a onclick="scrollToSection('beranda')">Beranda</a>
            <a onclick="scrollToSection('destinasi')">Destinasi</a>
            <a onclick="scrollToSection('paket')">Paket</a>
            <a onclick="scrollToSection('kontak')">Kontak</a>
            <?php if ($isLoggedIn): ?>
                <span class="text-primary font-bold bg-blue-50 px-4 py-2 rounded-full border border-blue-100 shadow-sm transition hover:shadow-md hover:-translate-y-1">
                    <i class="fas fa-user-check text-[#f39c12] mr-2"></i>Halo, <?= htmlspecialchars($loggedInUser) ?>
                </span>
                <a href="proses/logout.php" class="text-red-500 hover:text-red-700 font-bold" onclick="return confirm('Yakin ingin logout?')">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            <?php else: ?>
                <a href="login.php" class="btn-outline">
                    <i class="fas fa-user-circle mr-1"></i> Masuk / Daftar
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="hero" id="beranda">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1><span id="typing-text"></span><span class="cursor">|</span></h1>
            <p>Eksplorasi destinasi terbaik dengan harga termurah. Liburan impian Anda dimulai dari sini!</p>
            <div class="search-box">
                <i class="fas fa-search text-gray-400 ml-2"></i>
                <input type="text" placeholder="Mau liburan kemana bulan ini?" id="searchInput" />
                <button onclick="search()">Cari Destinasi</button>
            </div>
        </div>
    </section>

    <h2 class="section-title" id="destinasi">Destinasi Terfavorit</h2>
    <div class="destinasi-container" id="destinasiContainer"></div>

    <section class="paket-section" id="paket">
        <h2 class="section-title">Paket Wisata Spesial</h2>
        <div class="paket-container" id="paketContainer"></div>
    </section>

    <section class="kontak-section" id="kontak">
        <div class="kontak-container">
            <div class="kontak-item flex items-center"><i class="fas fa-map-marker-alt"></i> Jl. flamboyan 1 gandasuli</div>
            <div class="kontak-item flex items-center"><i class="fas fa-phone"></i> +62 858 6724 3030</div>
            <div class="kontak-item flex items-center"><i class="fas fa-envelope"></i> info@Jeljahin.In</div>
            <div class="kontak-item flex items-center">
                </a>
            </div>
        </div>
        <div class="text-center mt-12 text-blue-200 text-sm border-t border-white/20 pt-6">
            &copy; 2026 JELAJAH.IN Hak Cipta Dilindungi.
        </div>
    </section>
</div>

<div id="page-destinasi" class="page-view bg-gray-50 min-h-screen pb-12 relative z-10">
    <div class="bg-white/80 backdrop-blur-md shadow-sm p-4 sticky top-0 z-40 border-b border-gray-100">
        <div class="max-w-6xl mx-auto flex items-center">
            <button onclick="navigateTo('page-home')" class="text-[#2b6c94] font-bold hover:text-[#f39c12] transition flex items-center bg-blue-50 px-4 py-2 rounded-full hover:bg-orange-50">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </button>
        </div>
    </div>
    <div class="max-w-6xl mx-auto px-4 mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden border border-gray-100">
                <div id="destHeroImg" class="h-96 bg-cover bg-center transition-transform duration-700 hover:scale-105"></div>
                <div class="p-10 relative bg-white">
                    <div class="absolute -top-8 right-10 bg-white p-4 rounded-2xl shadow-xl flex flex-col items-center border border-gray-100">
                        <i class="fas fa-star text-yellow-400 text-2xl mb-1"></i>
                        <span id="destRating" class="font-black text-2xl text-gray-800">5.0</span>
                    </div>
                    <div class="mb-6">
                        <h1 id="destTitle" class="text-4xl font-extrabold text-[#1e3c5c] mb-3">Nama Destinasi</h1>
                        <p id="destLocation" class="text-gray-500 text-lg font-medium bg-gray-50 inline-block px-4 py-1.5 rounded-full"><i class="fas fa-map-marker-alt text-[#f39c12] mr-2"></i> Lokasi</p>
                    </div>
                    <hr class="my-6 border-gray-200" />
                    <h3 class="text-2xl font-bold mb-4 flex items-center text-[#1e3c5c]"><i class="fas fa-info-circle text-[#f39c12] mr-3"></i> Informasi Destinasi</h3>
                    <p id="destDesc" class="text-gray-600 leading-relaxed text-lg font-medium">Deskripsi panjang destinasi akan muncul di sini.</p>
                </div>
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white rounded-[2rem] shadow-xl p-8 border-t-8 border-[#2b6c94] sticky top-24">
                <h3 class="text-2xl font-black text-[#1e3c5c] mb-6 flex items-center"><i class="fas fa-ticket-alt text-[#f39c12] mr-3 text-3xl"></i> Beli Tiket</h3>
                <div class="space-y-5">
                    <div><label class="tw-label">Tanggal Kunjungan</label><input type="date" id="bookDate" class="tw-input" /></div>
                    <div><label class="tw-label">Jumlah Tiket</label><input type="number" id="bookQty" min="1" value="1" class="tw-input text-xl font-bold" onchange="calculateDestTotal()" /></div>
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 space-y-4">
                        <label class="flex items-center space-x-3 cursor-pointer group"><input type="checkbox" id="bookGuide" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 transition" onchange="calculateDestTotal()"><span class="text-gray-700 font-medium group-hover:text-[#2b6c94] transition">Pemandu Wisata <span class="text-sm text-[#f39c12] block">+Rp 75.000</span></span></label>
                        <hr class="border-gray-200">
                        <label class="flex items-center space-x-3 cursor-pointer group"><input type="checkbox" id="bookMeal" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 transition" onchange="calculateDestTotal()"><span class="text-gray-700 font-medium group-hover:text-[#2b6c94] transition">Makan Siang <span class="text-sm text-[#f39c12] block">+Rp 50.000</span></span></label>
                    </div>
                    <div><label class="tw-label">Metode Pembayaran</label><select id="destPaymentMethod" class="tw-input font-medium"><option value="transfer">Transfer Bank (BCA/Mandiri/BNI)</option><option value="ewallet">E-Wallet (GoPay/OVO/Dana)</option><option value="qris">QRIS (Scan Bebas)</option></select></div>
                    
                    <div class="bg-gradient-to-br from-[#f6fafd] to-[#eef7fc] p-6 rounded-2xl border border-blue-100 mt-6 shadow-inner">
                        <div class="flex justify-between items-center mb-3 text-gray-600 font-medium"><span>Harga Dasar</span><span id="bookPricePer" class="font-bold">Rp 0</span></div>
                        <div class="flex justify-between items-end text-xl font-black text-[#1e3c5c] border-t-2 border-blue-200 pt-4 mt-2"><span>Total Bayar</span><span id="bookTotal" class="text-3xl text-[#f39c12]">Rp 0</span></div>
                    </div>
                    <button onclick="processPayment('destinasi')" class="w-full bg-gradient-to-r from-[#2b6c94] to-[#1e4f6e] hover:from-[#1e4f6e] hover:to-[#12364f] text-white font-bold py-4 rounded-xl shadow-[0_10px_20px_rgba(43,108,148,0.3)] hover:shadow-[0_15px_30px_rgba(43,108,148,0.5)] transform hover:-translate-y-1 transition-all duration-300 flex justify-center items-center text-lg mt-6"><i class="fas fa-lock mr-2"></i> Bayar Aman Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="page-paket" class="page-view bg-gray-50 min-h-screen pb-12 relative z-10">
    <div class="bg-white/80 backdrop-blur-md shadow-sm p-4 sticky top-0 z-40 border-b border-gray-100">
        <div class="max-w-6xl mx-auto flex items-center">
            <button onclick="navigateTo('page-home')" class="text-[#2b6c94] font-bold hover:text-[#f39c12] transition flex items-center bg-blue-50 px-4 py-2 rounded-full hover:bg-orange-50"><i class="fas fa-arrow-left mr-2"></i> Kembali</button>
        </div>
    </div>
    <div class="max-w-6xl mx-auto px-4 mt-8">
        <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-gray-100">
            <div class="w-full md:w-3/5 p-8 lg:p-14 bg-[#1e3c5c] text-white relative bg-cover bg-center overflow-hidden" style="background-image: linear-gradient(rgba(30, 60, 92, 0.85), rgba(30, 60, 92, 0.95)), url('https://images.unsplash.com/photo-1596751303335-cb442b109e4d?q=80&w=800&auto=format&fit=crop');">
                <div class="absolute -right-10 -bottom-10 opacity-10 text-[15rem] transform rotate-12 transition-transform duration-1000 hover:rotate-0" id="pkgIconBg"><i class="fas fa-users"></i></div>
                <div class="relative z-10">
                    <div class="inline-block bg-gradient-to-r from-[#f39c12] to-[#e67e22] text-white px-5 py-2 rounded-full text-sm font-black tracking-wider mb-8 shadow-lg uppercase"><i class="fas fa-crown mr-2"></i> Paket Spesial</div>
                    <h1 id="pkgTitle" class="text-4xl lg:text-6xl font-black mb-6 drop-shadow-lg">Nama Paket</h1>
                    <p id="pkgFitur" class="text-2xl text-blue-200 mb-10 font-medium bg-white/10 inline-block px-6 py-3 rounded-2xl backdrop-blur-sm border border-white/10"><i class="fas fa-check-circle mr-3 text-[#f39c12]"></i>Fitur utama paket</p>
                    
                    <div class="space-y-6">
                        <div class="bg-white/10 p-8 rounded-3xl backdrop-blur-md border border-white/10 hover:bg-white/15 transition duration-300">
                            <h3 class="text-2xl font-bold mb-4 flex items-center"><i class="fas fa-info-circle text-[#f39c12] mr-3"></i> Detail Perjalanan</h3>
                            <p id="pkgDesc" class="text-gray-200 leading-relaxed text-lg font-medium">Deskripsi detail mengenai paket wisata ini.</p>
                        </div>
                        <div class="bg-yellow-500/20 p-8 rounded-3xl border border-yellow-500/30 backdrop-blur-md hover:bg-yellow-500/30 transition duration-300">
                            <h3 class="text-2xl font-bold mb-4 text-yellow-300 flex items-center"><i class="fas fa-lightbulb mr-3"></i> Tips & Persiapan</h3>
                            <p id="pkgSaran" class="text-gray-100 leading-relaxed text-lg font-medium">Saran persiapan untuk memaksimalkan liburan Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-2/5 p-8 lg:p-12 bg-white flex flex-col justify-center">
                <h2 class="text-3xl font-black text-[#1e3c5c] mb-8">Reservasi Paket</h2>
                <div class="space-y-6">
                    <div>
                        <label class="tw-label"><i class="far fa-calendar-alt text-[#f39c12] mr-2"></i> Tanggal Keberangkatan</label>
                        <input type="date" id="pkgDate" class="tw-input bg-gray-50" />
                    </div>
                    <div class="p-5 border-2 border-gray-100 rounded-2xl bg-gray-50">
                        <label class="tw-label mb-3"><i class="fas fa-user text-[#f39c12] mr-2"></i> Info Pemesan Utama</label>
                        <input type="text" placeholder="Nama Lengkap KTP" class="tw-input mb-4 bg-white" />
                        <input type="tel" placeholder="Nomor WhatsApp Aktif" class="tw-input bg-white" />
                    </div>
                    <div>
                        <label class="tw-label"><i class="fas fa-wallet text-[#f39c12] mr-2"></i> Metode Pembayaran</label>
                        <select id="pkgPaymentMethod" class="tw-input font-medium bg-gray-50">
                            <option value="transfer">Transfer Bank (BCA/Mandiri/BNI)</option>
                            <option value="cc">Kartu Kredit / Debit</option>
                            <option value="qris">QRIS (Scan Langsung)</option>
                        </select>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200 mt-8 shadow-inner text-center">
                        <p class="text-gray-500 text-sm mb-2 font-bold uppercase tracking-wider">Total Biaya Paket</p>
                        <p id="pkgPrice" class="text-4xl font-black text-[#2b6c94]">Rp 0</p>
                    </div>
                    <button onclick="processPayment('paket')" class="w-full bg-gradient-to-r from-[#f39c12] to-[#e67e22] hover:from-[#e67e22] hover:to-[#d35400] text-white font-black py-4.5 rounded-xl shadow-[0_10px_20px_rgba(243,156,18,0.3)] hover:shadow-[0_15px_30px_rgba(243,156,18,0.5)] transform hover:-translate-y-1 transition-all duration-300 text-xl mt-6 flex justify-center items-center py-4">
                        <i class="fas fa-check-circle mr-2"></i> Konfirmasi & Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"><i class="fas fa-check-circle mr-2"></i> Pesan berhasil ditambahkan</div>

<script>
// Data Destinasi & Paket (hardcode)
const destinations = [
    { id: 1, name: "Pantai Kuta", location: "Bali", price: 50000, rating: 4.8, imgUrl: "https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&q=80", description: "Nikmati keindahan sunset dan ombak yang mempesona di Pantai Kuta. Tempat sempurna untuk berselancar, berjemur, atau sekadar menikmati kelapa muda di pinggir pantai bersama orang tersayang." },
    { id: 2, name: "Gunung Bromo", location: "Jawa Timur", price: 75000, rating: 4.9, imgUrl: "https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&q=80", description: "Saksikan pemandangan matahari terbit (sunrise) yang paling spektakuler di atas lautan pasir Gunung Bromo. Pengalaman offroad menggunakan Jeep yang tak terlupakan." },
    { id: 3, name: "Candi Borobudur", location: "Jawa Tengah", price: 40000, rating: 4.7, imgUrl: "https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80", description: "Jelajahi keagungan Candi Buddha terbesar di dunia peninggalan Dinasti Syailendra. Pelajari ribuan panel relief yang menceritakan perjalanan kehidupan." },
    { id: 4, name: "Museum Nasional", location: "Jakarta", price: 25000, rating: 4.5, imgUrl: "https://images.unsplash.com/photo-1566127444979-b3d2b654e3d7?auto=format&fit=crop&q=80", description: "Pusat koleksi sejarah dan budaya Nusantara dari zaman prasejarah hingga masa modern. Destinasi yang sangat cocok untuk wisata edukasi keluarga." },
    { id: 5, name: "Taman Safari", location: "Bogor", price: 95000, rating: 4.8, imgUrl: "https://images.unsplash.com/photo-1534567153574-2b12153a87f0?auto=format&fit=crop&q=80", description: "Berkendara menyusuri habitat satwa liar dari berbagai belahan dunia. Nikmati interaksi langsung memberi makan hewan dari kaca mobil Anda." },
    { id: 6, name: "Raja Ampat", location: "Papua Barat", price: 150000, rating: 5.0, imgUrl: "https://images.unsplash.com/photo-1516690553959-71a414d6b9b6?auto=format&fit=crop&q=80", description: "Kepingan surga yang jatuh di bumi. Raja Ampat menawarkan gugusan pulau karang menawan dan kekayaan biota laut bawah air nomor satu di dunia untuk diving." }
];

const paketWisata = [
    { id: 1, nama: "Paket Keluarga", harga: 350000, icon: "fa-users", fitur: "Tiket 4 orang + makan siang", desc: "Nikmati kemudahan liburan santai bersama keluarga tercinta tanpa pusing memikirkan akomodasi dasar. Kami urus tiket masuk dan hidangan makan siang yang lezat.", saran: "Bawa pakaian ganti ekstra untuk anak-anak, sedia obat-obatan pribadi, dan jangan lupa topi serta tabir surya." },
    { id: 2, nama: "Paket Romantis", harga: 850000, icon: "fa-heart", fitur: "Tiket 2 orang + makan malam", desc: "Ciptakan momen tak terlupakan berdua bersama pasangan. Meliputi akses VIP ke destinasi dan ditutup dengan makan malam romantis dengan pemandangan indah.", saran: "Kenakan pakaian yang nyaman namun rapi. Bawa kamera atau siapkan memori HP untuk mengabadikan momen berdua." },
    { id: 3, nama: "Paket Group", harga: 1250000, icon: "fa-people-group", fitur: "Tiket 10 orang + bus + guide", desc: "Pilihan terbaik untuk rombongan kantor, reuni, atau arisan. Harga super hemat sudah termasuk transportasi bus nyaman dan pemandu wisata profesional yang ceria.", saran: "Tentukan satu koordinator rombongan, sepakati titik kumpul dan waktu yang tepat agar perjalanan sesuai jadwal." },
    { id: 4, nama: "Paket Backpacker", harga: 150000, icon: "fa-backpack", fitur: "Tiket + transport lokal", desc: "Eksplorasi destinasi unggulan dengan cara paling otentik. Menggunakan moda transportasi lokal yang menyatu dengan masyarakat sekitar.", saran: "Bawa barang seringkas mungkin dalam satu ransel. Siapkan uang tunai pecahan kecil dan selalu sedia botol minum." }
];

// State
let isLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;
let activeDestPrice = 0;
let historyPage = 'page-home';

// Hero Background Carousel
const heroImages = [
    "https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80",
    "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80",
    "https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&q=80",
    "https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&q=80",
    "https://images.unsplash.com/photo-1516690553959-71a414d6b9b6?auto=format&fit=crop&q=80"
];
let currentIndex = 0;
const heroSection = document.querySelector('.hero');

function changeHeroBackground() {
    currentIndex = (currentIndex + 1) % heroImages.length;
    heroSection.style.backgroundImage = `url('${heroImages[currentIndex]}')`;
}

// Set initial background
heroSection.style.backgroundImage = `url('${heroImages[0]}')`;
heroSection.style.transition = "background-image 1s ease-in-out";
setInterval(changeHeroBackground, 5000);



// Fungsi Navigasi
function navigateTo(pageId) {
    document.querySelectorAll('.page-view').forEach(p => p.classList.remove('active'));
    document.getElementById(pageId).classList.add('active');
    
    if(pageId !== 'page-auth') {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}
function navigateBack() { navigateTo(historyPage); }
function scrollToSection(id) {
    if(document.getElementById('page-home').classList.contains('active')) {
        const yOffset = -80;
        const element = document.getElementById(id);
        const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;
        window.scrollTo({top: y, behavior: 'smooth'});
    } else {
        navigateTo('page-home');
        setTimeout(() => scrollToSection(id), 400);
    }
}

// Render data home
function renderHome() {
    const destCont = document.getElementById("destinasiContainer");
    destCont.innerHTML = "";
    destinations.forEach((dest, i) => {
        const card = document.createElement("div");
        card.className = "card";
        card.style.animation = `slideUpFade 0.6s ease forwards ${i * 0.1}s`;
        card.style.opacity = '0';
        card.innerHTML = `
            <div class="card-img"><img src="${dest.imgUrl}" alt="${dest.name}"><span class="rating"><i class="fas fa-star text-yellow-400 mr-1"></i> ${dest.rating}</span></div>
            <div class="card-content"><h3>${dest.name}</h3><div class="location"><i class="fas fa-map-marker-alt"></i> ${dest.location}</div><div class="price">Rp ${dest.price.toLocaleString("id-ID")} <small>/ tiket</small></div><button class="btn-card mt-3"><i class="fas fa-bolt mr-1"></i> Pesan Sekarang</button></div>
        `;
        card.onclick = () => openDestinasiPage(dest);
        destCont.appendChild(card);
    });

    const pakCont = document.getElementById("paketContainer");
    pakCont.innerHTML = "";
    paketWisata.forEach((paket, i) => {
        const card = document.createElement("div");
        card.className = "paket-card";
        card.style.animation = `slideUpFade 0.6s ease forwards ${i * 0.1}s`;
        card.style.opacity = '0';
        card.innerHTML = `<i class="fas ${paket.icon}"></i><h3 class="text-2xl font-extrabold mt-2 text-[#1e3c5c]">${paket.nama}</h3><p class="text-gray-500 mt-2 font-medium">${paket.fitur}</p><div style="font-size:1.8rem; color:#2b6c94; margin:1.5rem 0; font-weight:900;">Rp ${paket.harga.toLocaleString("id-ID")}</div><button class="btn-outline w-full hover:bg-[#2b6c94] hover:text-white border-2">Lihat Detail Paket</button>`;
        card.onclick = () => openPaketPage(paket);
        pakCont.appendChild(card);
    });
}

function openDestinasiPage(dest) {
    if (!isLoggedIn) {
        window.location.href = 'login.php';
        return;
    }
    document.getElementById('destHeroImg').style.backgroundImage = `url('${dest.imgUrl}')`;
    document.getElementById('destTitle').textContent = dest.name;
    document.getElementById('destLocation').innerHTML = `<i class="fas fa-map-marker-alt text-[#f39c12] mr-2"></i> ${dest.location}`;
    document.getElementById('destRating').textContent = dest.rating;
    document.getElementById('destDesc').textContent = dest.description;
    document.getElementById('bookPricePer').textContent = `Rp ${dest.price.toLocaleString('id-ID')} / tiket`;
    const today = new Date().toISOString().split("T")[0];
    document.getElementById('bookDate').min = today;
    document.getElementById('bookDate').value = today;
    document.getElementById('bookQty').value = 1;
    document.getElementById('bookGuide').checked = false;
    document.getElementById('bookMeal').checked = false;
    activeDestPrice = dest.price;
    calculateDestTotal();
    historyPage = 'page-home';
    navigateTo('page-destinasi');
}

function openPaketPage(paket) {
    if (!isLoggedIn) {
        window.location.href = 'login.php';
        return;
    }
    document.getElementById('pkgTitle').textContent = paket.nama;
    document.getElementById('pkgFitur').innerHTML = `<i class="fas fa-check-circle mr-2 text-[#f39c12]"></i> ${paket.fitur}`;
    document.getElementById('pkgDesc').textContent = paket.desc;
    document.getElementById('pkgSaran').textContent = paket.saran;
    document.getElementById('pkgIconBg').innerHTML = `<i class="fas ${paket.icon}"></i>`;
    document.getElementById('pkgPrice').textContent = `Rp ${paket.harga.toLocaleString('id-ID')}`;
    const today = new Date().toISOString().split("T")[0];
    document.getElementById('pkgDate').min = today;
    document.getElementById('pkgDate').value = today;
    historyPage = 'page-home';
    navigateTo('page-paket');
}

function calculateDestTotal() {
    let qty = parseInt(document.getElementById('bookQty').value) || 1;
    if(qty < 1) { document.getElementById('bookQty').value = 1; qty = 1; }
    const base = activeDestPrice * qty;
    const guide = document.getElementById('bookGuide').checked ? 75000 : 0;
    const meal = document.getElementById('bookMeal').checked ? 50000 : 0;
    const total = base + guide + meal;
    document.getElementById('bookTotal').textContent = `Rp ${total.toLocaleString('id-ID')}`;
}

function processPayment(type) {
    if (!isLoggedIn) {
        window.location.href = 'login.php';
        return;
    }
    showToast("Memproses pembayaran otomatis...", false);
    setTimeout(() => { showToast(`✅ Pembayaran ${type === 'destinasi' ? 'Tiket' : 'Paket'} Berhasil! Terima kasih.`, false); navigateTo('page-home'); }, 2000);
}

function showToast(message, isError = false) {
    const toast = document.getElementById("toast");
    toast.innerHTML = isError ? `<i class="fas fa-exclamation-triangle mr-2"></i> ${message}` : `<i class="fas fa-check-circle mr-2"></i> ${message}`;
    toast.style.background = isError ? "rgba(231, 76, 60, 0.95)" : "rgba(39, 174, 96, 0.95)";
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 3500);
} 

function search() { 
    const val = document.getElementById('searchInput').value; 
    if(val) showToast(`Mencari rute liburan ke "${val}"...`, false); 
    else showToast("Masukkan kata kunci destinasi terlebih dahulu.", true); 
}

// Typing effect
const typingElement = document.getElementById("typing-text");
const phrases = ["Bingung Liburan Kemana?", "Di Jelajah.In!", "Pesan Tiket Murah & Cepat!", "Eksplor Nusantara Bersama Kami!"];
let phraseIdx = 0, charIdx = 0, isDeleting = false;
function typeEffect() {
    const cur = phrases[phraseIdx];
    typingElement.textContent = isDeleting ? cur.substring(0, charIdx - 1) : cur.substring(0, charIdx + 1);
    charIdx += isDeleting ? -1 : 1;
    let speed = isDeleting ? 40 : 100;
    if(!isDeleting && charIdx === cur.length) { isDeleting = true; speed = 2000; }
    else if(isDeleting && charIdx === 0) { isDeleting = false; phraseIdx = (phraseIdx + 1) % phrases.length; speed = 600; }
    setTimeout(typeEffect, speed);
}

// INIT
document.addEventListener("DOMContentLoaded", () => {
    renderHome();
    setTimeout(typeEffect, 1000);
});
</script>
<style>
.page-view { display: none; opacity: 0; }
.page-view.active { display: block; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
@keyframes slideUpFade {
    from { opacity: 0; transform: translateY(40px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
</style>
</body>
</html>