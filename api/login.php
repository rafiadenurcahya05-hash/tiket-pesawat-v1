<?php
session_start();
// Jika sudah login, arahkan ke beranda
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Daftar | Jelajahin.Ind</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-image: url('https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .glass-input {
            width: 100%;
            padding: 14px 20px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            outline: none;
            transition: all 0.3s ease;
            color: white;
            font-weight: 600;
        }
        .glass-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
        }
        .glass-input:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: #f39c12;
            box-shadow: 0 0 0 4px rgba(243, 156, 18, 0.2);
            transform: translateY(-2px);
        }
        .glass-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }
        .toast {
            visibility: hidden;
            min-width: 300px;
            background: rgba(43, 108, 148, 0.95);
            backdrop-filter: blur(10px);
            color: white;
            text-align: center;
            border-radius: 50px;
            padding: 18px 24px;
            position: fixed;
            bottom: 40px;
            left: 50%;
            transform: translate(-50%, 20px);
            z-index: 999;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            font-weight: 600;
            font-size: 1.1rem;
        }
        .toast.show {
            visibility: visible;
            opacity: 1;
            transform: translate(-50%, 0);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-[3px]"></div>
    
    <div class="relative z-10 w-full max-w-md">
        <div class="glass-container rounded-[2rem] w-full overflow-hidden transition-all duration-500 hover:scale-[1.01]">
            <div class="p-10 pt-12 relative">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/30 backdrop-blur-md shadow-lg">
                        <i class="fas fa-plane-departure text-3xl text-white"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-2 drop-shadow-lg" id="authTitle">Selamat Datang</h2>
                    <p class="text-gray-200 mt-2 font-medium" id="authSubtitle">Masuk untuk melanjutkan perjalananmu.</p>
                </div>

                <form id="authForm" onsubmit="handleAuth(event)">
                    <div id="registerFields" class="hidden mb-5">
                        <label class="glass-label"><i class="fas fa-id-card mr-2 text-[#f39c12]"></i>Nama Lengkap</label>
                        <input type="text" id="authName" class="glass-input" placeholder="Masukkan nama Anda" />
                    </div>
                    <div class="mb-5">
                        <label class="glass-label"><i class="fas fa-envelope mr-2 text-[#f39c12]"></i>Alamat Email</label>
                        <input type="email" id="authEmail" required class="glass-input" placeholder="email@contoh.com" />
                    </div>
                    <div class="mb-5">
                        <label class="glass-label"><i class="fas fa-lock mr-2 text-[#f39c12]"></i>Kata Sandi</label>
                        <input type="password" id="authPassword" required class="glass-input" placeholder="••••••••" />
                    </div>
                    
                    <div id="rememberMeField" class="mb-6 flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer text-white text-sm font-medium">
                            <input type="checkbox" id="rememberMe" class="w-4 h-4 accent-[#f39c12] rounded" />
                            Ingat Saya
                        </label>
                        <a href="lupa_password.php" class="text-sm text-[#f39c12] font-bold hover:text-white transition hover:underline">Lupa Sandi?</a>
                    </div>
                    
                    <button type="submit" class="w-full bg-gradient-to-r from-[#f39c12] to-[#e67e22] hover:from-[#e67e22] hover:to-[#d35400] text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-[0_10px_20px_rgba(243,156,18,0.4)] hover:shadow-[0_15px_30px_rgba(243,156,18,0.6)] transform hover:-translate-y-1 text-lg">
                        <span id="authBtnText">Masuk Sekarang</span> <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </form>

                <div class="mt-8 text-center border-t border-white/20 pt-6">
                    <p class="text-gray-200 font-medium" id="authToggleText">
                        Belum punya akun? 
                        <button type="button" onclick="toggleAuthMode()" class="text-[#f39c12] font-bold hover:text-white transition hover:underline ml-1">Daftar di sini</button>
                    </p>
                </div>

                <div class="mt-6 text-center">
                    <a href="index.php" class="text-white/70 hover:text-white text-sm transition flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="toast" class="toast"><i class="fas fa-check-circle mr-2"></i> Pesan berhasil</div>

    <script>
        let isRegistering = false;

        function toggleAuthMode() {
            isRegistering = !isRegistering;
            
            const formContainer = document.getElementById('authForm');
            formContainer.style.opacity = '0';
            formContainer.style.transform = 'translateY(10px)';
            
            setTimeout(() => {
                document.getElementById('registerFields').classList.toggle('hidden', !isRegistering);
                document.getElementById('rememberMeField').classList.toggle('hidden', isRegistering);
                
                document.getElementById('authTitle').innerHTML = isRegistering ? "Daftar Akun Baru" : "Selamat Datang";
                document.getElementById('authSubtitle').textContent = isRegistering ? "Isi data diri Anda untuk membuat akun kece Anda." : "Masuk untuk melanjutkan perjalananmu.";
                document.getElementById('authBtnText').textContent = isRegistering ? "Buat Akun Sekarang" : "Masuk Sekarang";
                
                document.getElementById('authToggleText').innerHTML = isRegistering ? 
                    `Sudah punya akun? <button type="button" onclick="toggleAuthMode()" class="text-[#f39c12] font-bold hover:text-white transition hover:underline ml-1">Masuk di sini</button>` :
                    `Belum punya akun? <button type="button" onclick="toggleAuthMode()" class="text-[#f39c12] font-bold hover:text-white transition hover:underline ml-1">Daftar di sini</button>`;
                    
                formContainer.style.transition = 'all 0.4s ease';
                formContainer.style.opacity = '1';
                formContainer.style.transform = 'translateY(0)';
            }, 200);
        }

        async function handleAuth(e) {
            e.preventDefault();
            const email = document.getElementById('authEmail').value;
            const password = document.getElementById('authPassword').value;
            const remember = document.getElementById('rememberMe')?.checked ? 1 : 0;

            if (isRegistering) {
                const username = document.getElementById('authName').value;
                if (!username) {
                    showToast("⚠️ Nama lengkap harus diisi", true);
                    return;
                }
                const formData = new FormData();
                formData.append('username', username);
                formData.append('email', email);
                formData.append('password', password);
                try {
                    const response = await fetch('proses/prosesRegister.php', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const result = await response.json();
                    if (result.status === 'success') {
                        showToast("✅ " + result.message, false);
                        toggleAuthMode();
                        document.getElementById('authEmail').value = email;
                        document.getElementById('authPassword').value = '';
                    } else {
                        showToast("❌ " + result.message, true);
                    }
                } catch(err) {
                    showToast("✅ Simulasi: Pendaftaran berhasil untuk " + username + "!", false);
                    toggleAuthMode();
                }
            } else {
                const formData = new FormData();
                formData.append('username', email);
                formData.append('password', password);
                formData.append('remember', remember);
                try {
                    const response = await fetch('proses/prosesLogin.php', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const result = await response.json();
                    if (result.status === 'success') {
                        showToast("✅ Login berhasil! Selamat datang.", false);
                        setTimeout(() => window.location.href = result.redirect || 'index.php', 1000);
                    } else {
                        showToast("❌ " + result.message, true);
                    }
                } catch(err) {
                    showToast("✅ Simulasi: Login berhasil!", false);
                    setTimeout(() => window.location.href = 'dashboard_user.php', 1000);
                }
            }
        }

        function showToast(message, isError = false) {
            const toast = document.getElementById("toast");
            toast.innerHTML = isError ? `<i class="fas fa-exclamation-triangle mr-2"></i> ${message}` : `<i class="fas fa-check-circle mr-2"></i> ${message}`;
            toast.style.background = isError ? "rgba(231, 76, 60, 0.95)" : "rgba(39, 174, 96, 0.95)";
            toast.classList.add("show");
            setTimeout(() => toast.classList.remove("show"), 3500);
        }
    </script>
</body>
</html>