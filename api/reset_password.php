<?php
session_start();
include 'server/koneksi.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Token tidak valid.");
}

// Cek token di database
$query = mysqli_query($koneksi, "SELECT * FROM password_resets WHERE token = '$token' AND expires_at > NOW()");
if (mysqli_num_rows($query) == 0) {
    die("Token sudah kadaluwarsa atau tidak valid.");
}

$reset = mysqli_fetch_assoc($query);
$email = $reset['email'];

// Proses reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($new_password !== $confirm) {
        $error = "Password dan konfirmasi tidak cocok.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update = mysqli_query($koneksi, "UPDATE users SET password = '$hashed' WHERE email = '$email'");
        if ($update) {
            // Hapus token yang sudah digunakan
            mysqli_query($koneksi, "DELETE FROM password_resets WHERE email = '$email'");
            $_SESSION['reset_success'] = "Password berhasil diubah. Silakan login.";
            header("Location: index.php");
            exit();
        } else {
            $error = "Gagal mengupdate password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | DDELTIKET</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 m-4">
        <h2 class="text-2xl font-bold text-center text-[#1e3c5c] mb-6">Buat Password Baru</h2>
        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Password Baru</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Konfirmasi Password</label>
                <input type="password" name="confirm_password" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            <button type="submit" class="w-full bg-[#f39c12] hover:bg-[#e67e22] text-white font-bold py-2 rounded-lg transition">Reset Password</button>
        </form>
    </div>
</body>
</html>