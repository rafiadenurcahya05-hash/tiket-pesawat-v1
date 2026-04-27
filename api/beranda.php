<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$query = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jelajahin.Ind - Dashboard User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-[#1e3c5c]">DDELTIKET</h1>
        <div class="flex items-center gap-4">
            <span>Halo, <?= htmlspecialchars($_SESSION['nama']) ?></span>
            <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Logout</a>
        </div>
    </nav>
    <div class="max-w-7xl mx-auto px-6 py-8">
        <h2 class="text-3xl font-bold text-[#1e3c5c] mb-6">✨ Destinasi Wisata Populer</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while($row = mysqli_fetch_assoc($query)): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
                <img src="<?= htmlspecialchars($row['imgUrl']) ?>" class="w-full h-48 object-cover" onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                <div class="p-5">
                    <h3 class="text-xl font-bold text-[#1e3c5c]"><?= htmlspecialchars($row['nama']) ?></h3>
                    <p class="text-gray-600"><i class="fas fa-map-marker-alt text-[#f39c12]"></i> <?= htmlspecialchars($row['lokasi']) ?></p>
                    <p class="text-yellow-500">⭐ <?= $row['rating'] ?></p>
                    <p class="text-lg font-bold text-[#1e3c5c] mt-2">Rp <?= number_format($row['harga'],0,',','.') ?></p>
                    <button onclick="alert('Demo: Tiket ke <?= addslashes($row['nama']) ?> berhasil dipesan!')" class="mt-4 w-full bg-[#f39c12] hover:bg-[#e67e22] text-white font-bold py-2 rounded-lg transition">Pesan Tiket</button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>