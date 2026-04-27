<?php
session_start();
require '../server/koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = htmlspecialchars(trim($_POST['nama']      ?? ''));
    $lokasi    = htmlspecialchars(trim($_POST['lokasi']    ?? ''));
    $provinsi  = htmlspecialchars(trim($_POST['provinsi']  ?? ''));
    $kategori  = htmlspecialchars(trim($_POST['kategori']  ?? ''));
    $harga     = (int)($_POST['harga']   ?? 0);
    $rating    = (float)($_POST['rating'] ?? 0);
    $imgUrl    = htmlspecialchars(trim($_POST['imgUrl']    ?? ''));
    $deskripsi = htmlspecialchars(trim($_POST['deskripsi'] ?? ''));

    $stmt = $koneksi->prepare(
        "INSERT INTO destinasi (nama, lokasi, provinsi, kategori, harga, rating, imgUrl, deskripsi)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssiids", $nama, $lokasi, $provinsi, $kategori, $harga, $rating, $imgUrl, $deskripsi);

    if ($stmt->execute()) {
        $_SESSION['message']      = "Destinasi '$nama' berhasil ditambahkan!";
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message']      = "Gagal menambahkan destinasi.";
        $_SESSION['message_type'] = 'error';
    }
}

header("Location: ../dashboard_admin.php?section=destinasi");
exit();
?>
