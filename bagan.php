<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'koneksi.php';

if (isset($_POST['update_skor'])) {
    $id = intval($_POST['id_match']);
    $skor_kiri = intval($_POST['skor_kiri']);
    $skor_kanan = intval($_POST['skor_kanan']);
    $pemenang = ($skor_kiri > $skor_kanan) ? $_POST['tim_kiri'] : $_POST['tim_kanan'];
    mysqli_query($conn, "UPDATE pertandingan SET skor_kiri=$skor_kiri, skor_kanan=$skor_kanan, pemenang='$pemenang' WHERE id_match=$id");
}

$matches = mysqli_query($conn, "SELECT * FROM pertandingan ORDER BY id_match ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tournament Bracket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white p-8">
    <div class="flex justify-between items-center mb-10">
        <h1 class="text-3xl font-bold text-yellow-500">Tournament Bracket</h1>
        <a href="index.php" class="bg-slate-700 px-6 py-2 rounded-lg hover:bg-slate-600">Kembali ke Dashboard</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
        <?php while($m = mysqli_fetch_assoc($matches)) { ?>
        <div class="bg-slate-900 border border-slate-700 p-4 rounded-xl shadow-xl">
            <div class="text-center text-[10px] text-slate-400 uppercase font-bold mb-3"><?= $m['babak'] ?></div>
            <form method="POST" class="space-y-2">
                <input type="hidden" name="id_match" value="<?= $m['id_match'] ?>">
                <input type="hidden" name="tim_kiri" value="<?= $m['tim_kiri'] ?>">
                <input type="hidden" name="tim_kanan" value="<?= $m['tim_kanan'] ?>">
                <div class="flex justify-between bg-slate-800 p-2 rounded"><span><?= $m['tim_kiri'] ?></span><input type="number" name="skor_kiri" value="<?= $m['skor_kiri'] ?>" class="w-12 bg-black text-center rounded"></div>
                <div class="flex justify-between bg-slate-800 p-2 rounded"><span><?= $m['tim_kanan'] ?></span><input type="number" name="skor_kanan" value="<?= $m['skor_kanan'] ?>" class="w-12 bg-black text-center rounded"></div>
                <button name="update_skor" class="w-full bg-blue-600 py-1 rounded text-sm hover:bg-blue-500">Update Skor</button>
            </form>
            <div class="mt-3 text-center text-xs font-bold text-green-400">Pemenang: <?= $m['pemenang'] ?? '...' ?></div>
        </div>
        <?php } ?>
    </div>
</body>
</html>