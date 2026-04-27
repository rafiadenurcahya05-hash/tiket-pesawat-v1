<?php
session_start();
// Simulasi login sementara jika ingin testing tanpa login.php
// $_SESSION['username'] = "Asisten Dosen"; 

if (!isset($_SESSION['username'])) {
    header("Location: prosesLogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Interactive</title>
    <style>
        :root {
            --bg-color: #f4f7f6;
            --text-color: #2c3e50;
            --card-bg: #ffffff;
            --nav-bg: #2c3e50;
            --nav-text: #ffffff;
            --shadow: rgba(0,0,0,0.1);
        }

        body.dark-mode {
            --bg-color: #1a1a2e;
            --text-color: #e0e0e0;
            --card-bg: #16213e;
            --nav-bg: #0f3460;
            --shadow: rgba(0,0,0,0.5);
        }

        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            background-color: var(--bg-color); 
            color: var(--text-color);
            transition: background-color 0.4s ease, color 0.4s ease;
        }
        
        .navbar {
            background: var(--nav-bg);
            color: var(--nav-text);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px var(--shadow);
            transition: background 0.4s ease;
        }

        .user-section { display: flex; align-items: center; gap: 20px; }
        
        .btn-action {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            text-decoration: none;
        }

        .btn-darkmode { background: #f39c12; color: white; }
        .btn-darkmode:hover { background: #e67e22; }

        .btn-logout { background: #e74c3c; color: white; }
        .btn-logout:hover { background: #c0392b; }

        .container { 
            padding: 50px 20px; 
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        
        .card {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px var(--shadow);
            transition: transform 0.3s ease, background-color 0.4s ease;
            width: 100%;
            max-width: 500px;
        }

        .card:hover { transform: translateY(-5px); }

        #clock { 
            font-size: 3.5rem; 
            font-weight: 800; 
            background: linear-gradient(45deg, #3498db, #8e44ad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 15px 0; 
        }

        #greeting { font-size: 1.5rem; font-weight: 600; }
        
        #quote-container {
            margin-top: 20px;
            font-style: italic;
            color: #7f8c8d;
            font-size: 1rem;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-box {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 10px 30px var(--shadow);
            transform: translateY(-20px);
            transition: transform 0.3s ease;
            max-width: 400px;
            width: 90%;
        }

        .modal-overlay.show .modal-box {
            transform: translateY(0);
        }

        .modal-box h3 { margin-top: 0; color: var(--text-color); font-size: 1.5rem; }
        .modal-box p { color: #7f8c8d; margin-bottom: 25px; line-height: 1.5; }
        
        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-cancel { background: #95a5a6; color: white; }
        .btn-cancel:hover { background: #7f8c8d; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo"><strong>DashbboardL</strong></div>
        <div class="user-section">
            <span id="nav-greeting"></span>
            <span><strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            <button id="toggle-dark" class="btn-action btn-darkmode">🌙 Dark Mode</button>
            <a href="proses/logout.php" class="btn-action btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div id="greeting">Memuat...</div>
            <div id="clock">00:00:00</div>
            <div id="quote-container">Memuat kutipan hari ini...</div>
        </div>
    </div>

    <script src="./assets/script.js"></script> 

    <div id="logout-modal" class="modal-overlay">
        <div class="modal-box">
            <h3>Konfirmasi Logout 🚪</h3>
            <p>Apakah Anda yakin ingin mengakhiri sesi dan keluar dari dashboard?</p>
            <div class="modal-actions">
                <button id="btn-cancel" class="btn-action btn-cancel">Batal</button>
                <a href="proses/logout.php" class="btn-action btn-logout">Ya, Logout</a>
            </div>
        </div>
    </div>

</body>
</html>