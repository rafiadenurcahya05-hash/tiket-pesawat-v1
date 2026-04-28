<?php
// FILE SEMENTARA - HAPUS SETELAH DIPAKAI

$passwords = [
    'admin123',
    'user123',
];

foreach ($passwords as $pw) {
    $hash = password_hash($pw, PASSWORD_DEFAULT);
    echo $pw . " => " . $hash . "<br><br>";
}
?>