<?php
// FILE SEMENTARA - HAPUS SETELAH DIPAKAI
require __DIR__ . '/server/koneksi.php';

$password_admin = 'admin123';
$password_user  = 'user123';

$hash_admin = password_hash($password_admin, PASSWORD_DEFAULT);
$hash_user  = password_hash($password_user, PASSWORD_DEFAULT);

// Langsung update ke database
$stmt1 = $koneksi->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
$stmt1->bind_param("s", $hash_admin);
$ok1 = $stmt1->execute();

$stmt2 = $koneksi->prepare("UPDATE users SET password = ? WHERE username = 'user'");
$stmt2->bind_param("s", $hash_user);
$ok2 = $stmt2->execute();

// Verifikasi langsung
$stmt3 = $koneksi->prepare("SELECT username, password FROM users WHERE username IN ('admin','user')");
$stmt3->execute();
$res = $stmt3->get_result();

echo "<h3>Hasil Update:</h3>";
echo "admin => " . ($ok1 ? "✅ Berhasil" : "❌ Gagal") . "<br>";
echo "user  => " . ($ok2 ? "✅ Berhasil" : "❌ Gagal") . "<br><br>";

echo "<h3>Verifikasi password_verify():</h3>";
while ($row = $res->fetch_assoc()) {
    $pw = $row['username'] === 'admin' ? $password_admin : $password_user;
    $ok = password_verify($pw, $row['password']);
    echo $row['username'] . " + password '" . $pw . "' => " . ($ok ? "✅ COCOK" : "❌ TIDAK COCOK") . "<br>";
}

echo "<br><b>Selesai. Hapus file ini setelah login berhasil.</b>";
?>
