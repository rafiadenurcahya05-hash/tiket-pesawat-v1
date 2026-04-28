<?php
// FILE DEBUG SEMENTARA - HAPUS SETELAH SELESAI DEBUG
session_start();
require 'server/koneksi.php';

header('Content-Type: application/json');

$hasil = [
    'session'  => $_SESSION ?? [],
    'cookie'   => $_COOKIE ?? [],
    'db_test'  => null,
    'user_test' => null,
];

// Tes koneksi DB
if ($koneksi) {
    $hasil['db_test'] = 'KONEKSI DB OK';

    // Tes ambil user dari DB
    $stmt = $koneksi->prepare("SELECT id, nama, username, email, role, LEFT(password,10) as password_preview FROM users LIMIT 5");
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $hasil['user_test'][] = $row;
    }
} else {
    $hasil['db_test'] = 'KONEKSI DB GAGAL';
}

// Tes password_verify dengan hash dari DB
if (!empty($_GET['email']) && !empty($_GET['pw'])) {
    $stmt2 = $koneksi->prepare("SELECT password FROM users WHERE email = ? OR username = ? LIMIT 1");
    $stmt2->bind_param("ss", $_GET['email'], $_GET['email']);
    $stmt2->execute();
    $r = $stmt2->get_result()->fetch_assoc();
    if ($r) {
        $hasil['verify_test'] = [
            'hash_di_db'      => $r['password'],
            'password_input'  => $_GET['pw'],
            'verify_result'   => password_verify($_GET['pw'], $r['password']) ? 'BENAR ✅' : 'SALAH ❌',
        ];
    } else {
        $hasil['verify_test'] = 'User tidak ditemukan';
    }
}

echo json_encode($hasil, JSON_PRETTY_PRINT);
?>
