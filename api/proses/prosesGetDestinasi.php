<?php
require_once __DIR__ . '/../server/koneksi.php';
header('Content-Type: application/json');

$result = mysqli_query($koneksi, "SELECT id, nama, lokasi, harga, rating, imgUrl, deskripsi FROM destinasi ORDER BY id DESC");
$destinasi = [];
while ($row = mysqli_fetch_assoc($result)) {
    $destinasi[] = $row;
}
echo json_encode($destinasi);
?>