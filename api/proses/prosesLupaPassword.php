<?php
session_start();
include '../server/koneksi.php';

$email = $_POST['email'] ?? '';

if (empty($email)) {
    $_SESSION['reset_error'] = "Email harus diisi.";
    header("Location: ../lupa_password.php");
    exit();
}

// Cek apakah email terdaftar
$check = mysqli_query($koneksi, "SELECT id FROM users WHERE email = '$email'");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['reset_error'] = "Email tidak terdaftar.";
    header("Location: ../lupa_password.php");
    exit();
}

// Hapus token lama untuk email ini (jika ada)
mysqli_query($koneksi, "DELETE FROM password_resets WHERE email = '$email'");

// Buat token unik
$token = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Simpan token ke database
$insert = mysqli_query($koneksi, "INSERT INTO password_resets (email, token, expires_at) VALUES ('$email', '$token', '$expires')");

if ($insert) {
    // SIMULASI: tampilkan link reset langsung di halaman (tanpa email)
    // Untuk versi sesungguhnya, kirim email berisi link berikut:
    $reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME'], 2) . "/reset_password.php?token=" . $token;
    
    $_SESSION['reset_success'] = "Link reset telah dibuat. Klik link berikut untuk reset password (simulasi): <br><a href='$reset_link' target='_blank'>$reset_link</a>";
    header("Location: ../lupa_password.php");
} else {
    $_SESSION['reset_error'] = "Gagal memproses permintaan. Silakan coba lagi.";
    header("Location: ../lupa_password.php");
}
exit();
?>