<?php
session_start();
require '../server/koneksi.php';

header('Content-Type: application/json');

// Hanya admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['items'])) {
    echo json_encode(['status' => 'error', 'message' => 'Tidak ada data untuk diimpor']);
    exit();
}

$items    = $input['items'];
$harga    = (int)($input['harga']    ?? 25000);
$kategori = htmlspecialchars($input['kategori'] ?? 'Alam');
$imgUrl   = htmlspecialchars($input['imgUrl']   ?? '');
$rating   = 4.0;

$success = 0;
$errors  = 0;

$stmt = $koneksi->prepare(
    "INSERT INTO destinasi (nama, lokasi, provinsi, harga, rating, imgUrl, kategori, bps_id, deskripsi)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE updated_at = NOW()"
);

foreach ($items as $item) {
    $nama      = htmlspecialchars(substr($item['nama']    ?? 'Destinasi BPS', 0, 200));
    $lokasi    = htmlspecialchars(substr($item['lokasi']  ?? '-', 0, 200));
    $provinsi  = htmlspecialchars(substr($item['provinsi'] ?? '-', 0, 100));
    $bps_id    = htmlspecialchars($item['id'] ?? '');
    $deskripsi = "Data pariwisata dari BPS – {$provinsi} (Nilai: {$item['nilai']}, Tahun: {$item['tahun']})";
    $imgFinal  = $imgUrl ?: 'https://placehold.co/600x400?text=' . urlencode($nama);

    $stmt->bind_param("sssidsss s",
        $nama, $lokasi, $provinsi,
        $harga, $rating,
        $imgFinal, $kategori,
        $bps_id, $deskripsi
    );

    if ($stmt->execute()) {
        $success++;
    } else {
        $errors++;
    }
}

echo json_encode([
    'status'  => 'success',
    'message' => "{$success} destinasi berhasil diimpor dari BPS." . ($errors ? " {$errors} gagal." : ""),
    'success' => $success,
    'errors'  => $errors
]);
?>
