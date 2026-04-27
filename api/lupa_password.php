<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Sandi | DDELTIKET</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Quicksand', sans-serif; background: #f0f5fa; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 m-4">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-[#1e3c5c]">Lupa Sandi?</h1>
            <p class="text-gray-500 mt-2">Masukkan email Anda, kami akan kirimkan link reset password.</p>
        </div>

        <?php if (isset($_SESSION['reset_success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['reset_success']; unset($_SESSION['reset_success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['reset_error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['reset_error']; unset($_SESSION['reset_error']); ?>
            </div>
        <?php endif; ?>

        <form action="proses/prosesLupaPassword.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Alamat Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#f39c12]">
            </div>
            <button type="submit" class="w-full bg-[#f39c12] hover:bg-[#e67e22] text-white font-bold py-2 rounded-lg transition">Kirim Link Reset</button>
        </form>

        <div class="text-center mt-6">
            <a href="index.php" class="text-[#2b6c94] hover:underline">← Kembali ke Login</a>
        </div>
    </div>
</body>
</html>