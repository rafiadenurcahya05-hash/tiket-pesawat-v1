<?php
// Tetap gunakan session_start sebagai backup, meski di Vercel kurang stabil
session_start();
include '../server/koneksi.php'; // Mengacu pada variabel $koneksi[cite: 16]

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

$login    = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Cari user berdasarkan username ATAU email
$stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verifikasi Password[cite: 16]
    if (password_verify($password, $user['password'])) {
        
        // --- SOLUSI VERCEL: Gunakan Cookie untuk Persistence ---
        // Berlaku selama 1 hari (86400 detik) di seluruh domain ("/")
        setcookie("user_id", $user['id'], time() + 86400, "/");
        setcookie("username", $user['username'] ?? $user['email'], time() + 86400, "/");
        setcookie("role", $user['role'], time() + 86400, "/");

        // Simpan ke Session (untuk fallback)[cite: 16]
        $_SESSION['id']       = $user['id'];
        $_SESSION['nama']     = $user['nama'];
        $_SESSION['role']     = $user['role'];

        // Tentukan path redirect berdasarkan struktur foldermu
        $redirect = ($user['role'] === 'admin') ? '../server/dashboard_admin.php' : '../server/dashboard_user.php';

        if ($isAjax) {
            echo json_encode([
                'status'   => 'success',
                'message'  => 'Login berhasil',
                'role'     => $user['role'],
                'redirect' => $redirect
            ]);
            exit();
        } else {
            header("Location: $redirect");
            exit();
        }
    } else {
        $error = "Password salah!";
    }
} else {
    $error = "Username atau Email tidak ditemukan!";
}

// Handling Error
if ($isAjax) {
    echo json_encode(['status' => 'error', 'message' => $error]);
    exit();
} else {
    // Kembali ke login.php di folder server[cite: 16]
    echo "<script>alert('$error'); window.location.href='../server/login.php';</script>";
}
?>