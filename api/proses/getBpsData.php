<?php
header('Content-Type: application/json');

// Gunakan API Key yang sudah kamu miliki
$apiKey = '10f149869798c369c50319f51333657d'; 
// URL dari dosen (pastikan Key di akhir sudah diganti)
$url = "https://webapi.bps.go.id/v1/api/list/model/data/lang/ind/domain/0000/var/1470/th/126/key/" . $apiKey;

// Ambil data dari BPS sesuai instruksi PPT
$response = file_get_contents($url);

if ($response === FALSE) {
    echo json_encode(["error" => "Gagal mengambil data dari BPS"]);
    exit;
}

// Kirim data ke frontend
echo $response;
?>