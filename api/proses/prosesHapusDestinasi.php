<?php
session_start();
require '../server/koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $koneksi->prepare("DELETE FROM destinasi WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message']      = "Destinasi berhasil dihapus.";
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message']      = "Gagal menghapus destinasi.";
        $_SESSION['message_type'] = 'error';
    }
}

header("Location: ../dashboard_admin.php?section=destinasi");
exit();
?>
