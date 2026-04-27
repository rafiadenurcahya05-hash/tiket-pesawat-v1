<?php
function uploadGambar($file, $folder = "../uploads/") {
    $targetDir = $folder;
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $fileName = time() . '_' . basename($file['name']);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($imageFileType, $allowedTypes)) {
        return ['error' => 'Hanya file gambar (JPG, JPEG, PNG, GIF, WEBP) yang diperbolehkan.'];
    }
    if ($file['size'] > 2 * 1024 * 1024) { // 2MB
        return ['error' => 'Ukuran gambar maksimal 2MB.'];
    }
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ['success' => $fileName];
    } else {
        return ['error' => 'Gagal mengupload gambar.'];
    }
}
?>