<?php
session_start();
require '../server/koneksi.php';

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = htmlspecialchars(trim($_POST['username'] ?? $_POST['nama'] ?? ''));
    $email    = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (empty($nama) || empty($email) || empty($password)) {
        $resp = ['status' => 'error', 'message' => 'Semua field harus diisi!'];
        if ($isAjax) { echo json_encode($resp); exit(); }
    }

    // Cek email sudah terdaftar
    $stmt = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $resp = ['status' => 'error', 'message' => 'Email sudah digunakan!'];
        if ($isAjax) { echo json_encode($resp); exit(); }
        header("Location: ../login.php");
        exit();
    }

    $hashed  = password_hash($password, PASSWORD_DEFAULT);
    $username = explode('@', $email)[0]; // username dari email
    $role    = 'user';

    $stmt2 = $koneksi->prepare("INSERT INTO users (nama, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param("sssss", $nama, $username, $email, $hashed, $role);

    if ($stmt2->execute()) {
        $resp = ['status' => 'success', 'message' => 'Registrasi berhasil! Silakan login.'];
        if ($isAjax) { echo json_encode($resp); exit(); }
        header("Location: ../login.php");
    } else {
        $resp = ['status' => 'error', 'message' => 'Gagal mendaftar. Coba lagi.'];
        if ($isAjax) { echo json_encode($resp); exit(); }
        header("Location: ../login.php");
    }
    exit();
}
?>
