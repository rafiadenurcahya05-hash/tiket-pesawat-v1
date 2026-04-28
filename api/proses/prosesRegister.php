<?php
session_start();
require __DIR__ . '/../server/koneksi.php'; // Mengacu pada variabel $koneksi[cite: 17]

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = htmlspecialchars(trim($_POST['nama'] ?? $_POST['username'] ?? ''));
    $email    = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (empty($nama) || empty($email) || empty($password)) {
        $resp = ['status' => 'error', 'message' => 'Semua field harus diisi!'];
        if ($isAjax) { echo json_encode($resp); exit(); }
        echo "<script>alert('Field kosong!'); window.history.back();</script>";
        exit();
    }

    // Cek apakah email sudah terdaftar[cite: 17]
    $stmt = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $resp = ['status' => 'error', 'message' => 'Email sudah digunakan!'];
        if ($isAjax) { echo json_encode($resp); exit(); }
        echo "<script>alert('Email sudah terdaftar!'); window.location.href='../server/login.php';</script>";
        exit();
    }

    // Hash password hanya sekali[cite: 17]
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $username_otomatis = explode('@', $email)[0]; 
    $role = 'user';

    // Simpan ke Database
    $stmt2 = $koneksi->prepare("INSERT INTO users (nama, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param("sssss", $nama, $username_otomatis, $email, $hashed_password, $role);

    if ($stmt2->execute()) {
        $resp = ['status' => 'success', 'message' => 'Registrasi berhasil! Silakan login.'];
        if ($isAjax) { echo json_encode($resp); exit(); }
        // Redirect ke login.php di folder server[cite: 17]
        header("Location: ../server/login.php?msg=success");
    } else {
        $resp = ['status' => 'error', 'message' => 'Gagal mendaftar.'];
        if ($isAjax) { echo json_encode($resp); exit(); }
        header("Location: ../server/login.php?msg=failed");
    }
    exit();
}
?>