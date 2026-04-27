function updateClock() {
    const clockElement = document.getElementById('clock');
    if (clockElement) {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        clockElement.textContent = `${hours}:${minutes}:${seconds}`;
    }
}

function setGreeting() {
    const greetingElement = document.getElementById('greeting');
    const navGreeting = document.getElementById('nav-greeting');
    if (greetingElement) {
        const hour = new Date().getHours();
        let greet;
        
        if (hour < 12) greet = "Selamat Pagi ☀️";
        else if (hour < 15) greet = "Selamat Siang 🌤️";
        else if (hour < 18) greet = "Selamat Sore 🌅";
        else greet = "Selamat Malam 🌙";
        
        greetingElement.textContent = greet;
        if (navGreeting) navGreeting.textContent = greet;
    }
}

const quotes = [
    "Tidak peduli bagaimana kerasnya kehidupan yang kamu miliki di masa lalu, kamu selalu bisa memulainya kembali.",
    "Berjalanlah jangan berlari, karena hidup adalah perjalanan dan bukannya pelarian.",
    "Tidak peduli hal apapun yang kamu alami, selalu ada cahaya diujung terowongan.",
    "Tidak ada yang tidak mungkin. Batasan hanya ada dalam pikiran kita.",
    "Jangan biarkan masa lalu mencuri kebahagiaanmu saat ini."
];

function setRandomQuote() {
    const quoteElement = document.getElementById('quote-container');
    if (quoteElement) {
        // Mengambil indeks acak dari panjang array quotes
        const randomIndex = Math.floor(Math.random() * quotes.length);
        quoteElement.textContent = `"${quotes[randomIndex]}"`;
    }
}

function initDarkMode() {
    const darkModeBtn = document.getElementById('toggle-dark');
    
    // Cek apakah user sebelumnya sudah mengaktifkan dark mode
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
        darkModeBtn.textContent = '☀️ Light Mode';
    }

    // Event listener ketika tombol diklik
    darkModeBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        
        // Simpan preferensi ke Local Storage agar tidak hilang saat di-refresh
        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
            darkModeBtn.textContent = '☀️ Light Mode';
        } else {
            localStorage.setItem('theme', 'light');
            darkModeBtn.textContent = '🌙 Dark Mode';
        }
    });
}

function initLogoutModal() {
    // Ambil elemen tombol di navbar dengan menyeleksi href-nya
    const navLogoutBtn = document.querySelector('a[href="proses/logout.php"]'); 
    const modalOverlay = document.getElementById('logout-modal');
    const btnCancel = document.getElementById('btn-cancel');

    // 1. Mencegah logout langsung dan memunculkan modal
    if (navLogoutBtn) {
        navLogoutBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah browser pindah ke logout.php
            modalOverlay.classList.add('show'); // Munculkan kotak alert
        });
    }

    // 2. Jika nggak jadi logout, sembunyikan modal
    if (btnCancel) {
        btnCancel.addEventListener('click', function() {
            modalOverlay.classList.remove('show');
        });
    }

    // 3.Tutup modal jika user mengklik area gelap di luar kotak putih
    window.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            modalOverlay.classList.remove('show');
        }
    });
}

// Event DOMContentLoaded memastikan script berjalan setelah HTML selesai dimuat
document.addEventListener('DOMContentLoaded', () => {
    updateClock();
    setGreeting();
    setRandomQuote();
    initDarkMode();
    initLogoutModal();

    setInterval(updateClock, 1000);
});