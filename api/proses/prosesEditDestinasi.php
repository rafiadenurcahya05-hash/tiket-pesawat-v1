<?php
session_start();
require '../server/koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = (int)($_POST['id']       ?? 0);
    $nama      = htmlspecialchars(trim($_POST['nama']      ?? ''));
    $lokasi    = htmlspecialchars(trim($_POST['lokasi']    ?? ''));
    $provinsi  = htmlspecialchars(trim($_POST['provinsi']  ?? ''));
    $kategori  = htmlspecialchars(trim($_POST['kategori']  ?? ''));
    $harga     = (int)($_POST['harga']    ?? 0);
    $rating    = (float)($_POST['rating'] ?? 0);
    $imgUrl    = htmlspecialchars(trim($_POST['imgUrl']    ?? ''));
    $deskripsi = htmlspecialchars(trim($_POST['deskripsi'] ?? ''));

    $stmt = $koneksi->prepare(
        "UPDATE destinasi SET nama=?, lokasi=?, provinsi=?, kategori=?, harga=?, rating=?, imgUrl=?, deskripsi=?
         WHERE id=?"
    );
    $stmt->bind_param("ssssidss i", $nama, $lokasi, $provinsi, $kategori, $harga, $rating, $imgUrl, $deskripsi, $id);

    if ($stmt->execute()) {
        $_SESSION['message']      = "Destinasi '$nama' berhasil diperbarui!";
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message']      = "Gagal memperbarui destinasi.";
        $_SESSION['message_type'] = 'error';
    }
}

header("Location: ../dashboard_admin.php?section=destinasi");
exit();
?>
