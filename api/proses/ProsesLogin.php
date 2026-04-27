<?php
session_start();
include '../server/koneksi.php';

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

$login    = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Cari user berdasarkan username ATAU email (prepared statement)
$stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['id']       = $user['id'];
        $_SESSION['nama']     = $user['nama'];
        $_SESSION['username'] = $user['username'] ?? $user['email'];
        $_SESSION['email']    = $user['email'];
        $_SESSION['role']     = $user['role'];

        if (!empty($_POST['remember'])) {
            setcookie("username", $user['username'], time() + (86400 * 30), "/");
        }

        $redirect = ($user['role'] === 'admin') ? '../dashboard_admin.php' : '../dashboard_user.php';

        if ($isAjax) {
            echo json_encode([
                'status'   => 'success',
                'message'  => 'Login berhasil',
                'role'     => $user['role'],
                'redirect' => $redirect
            ]);
            exit();
        } else {
            header("Location: $redirect");
            exit();
        }
    } else {
        $error = "Password salah!";
    }
} else {
    $error = "Username atau Email tidak ditemukan!";
}

if ($isAjax) {
    echo json_encode(['status' => 'error', 'message' => $error]);
    exit();
} else {
    echo $error . " <a href='../login.php'>Kembali</a>";
}
?>
