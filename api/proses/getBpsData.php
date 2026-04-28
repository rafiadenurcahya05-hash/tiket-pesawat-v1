<?php
header('Content-Type: application/json');

// Gunakan API Key BPS-mu
$apiKey = '10f149869798c369c50319f51333657d'; 
$url = "https://webapi.bps.go.id/v1/api/list/model/data/lang/ind/domain/0000/var/1470/th/126/key/" . $apiKey;

// Gunakan cURL karena Vercel terkadang memblokir file_get_contents
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Mencegah error sertifikat SSL di cloud
$response = curl_exec($ch);
curl_close($ch);

if (!$response) {
    // Jika gagal terhubung ke BPS, kirim status ERROR
    echo json_encode(["status" => "ERROR", "message" => "Gagal menghubungi server BPS"]);
    exit;
}

echo $response;
?>