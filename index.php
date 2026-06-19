<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_logged'])) { header("Location: login.html"); exit; }
require_once 'koneksi.php';

// Logic CRUD
if (isset($_POST['simpan_tim'])) {
    $nama_tim = mysqli_real_escape_string($conn, $_POST['nama_tim']);
    $nama_manajer = mysqli_real_escape_string($conn, $_POST['nama_manajer']);
    $file_name = !empty($_FILES['berkas']['name']) ? time().'_'.$_FILES['berkas']['name'] : "";
    if($file_name) move_uploaded_file($_FILES['berkas']['tmp_name'], 'uploads/'.$file_name);
    mysqli_query($conn, "INSERT INTO tim_peserta (nama_tim, nama_manajer, berkas_pemain, status_berkas) VALUES ('$nama_tim', '$nama_manajer', '$file_name', 'Menunggu')");
    header("Location: index.php"); exit;
}
if (isset($_POST['update_tim'])) {
    mysqli_query($conn, "UPDATE tim_peserta SET nama_tim='{$_POST['nama_tim']}', nama_manajer='{$_POST['nama_manajer']}' WHERE id_tim=".intval($_POST['id_edit']));
    header("Location: index.php"); exit;
}
if (isset($_POST['image_base64'])) {
    mysqli_query($conn, "UPDATE tim_peserta SET ttd_digital='".mysqli_real_escape_string($conn, $_POST['image_base64'])."', status_berkas='Lengkap' WHERE id_tim=".intval($_POST['id_tim_ttd']));
    header("Location: index.php"); exit;
}
if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM tim_peserta WHERE id_tim = ".intval($_GET['hapus']));
    header("Location: index.php"); exit;
}
$data = mysqli_query($conn, "SELECT * FROM tim_peserta ORDER BY id_tim DESC");
$total_tim = mysqli_num_rows($data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Futsal Master</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>.glass { background: rgba(17, 24, 39, 0.85); backdrop-filter: blur(10px); } #ttdCanvas { background: white; }</style>
</head>
<body class="text-white bg-slate-900">
    <video autoplay muted loop playsinline class="fixed inset-0 w-full h-full object-cover z-[-2] opacity-30"><source src="assets/bg_video.mp4" type="video/mp4"></video>
    <audio id="bgm" loop><source src="assets/bgm_music.mp3" type="audio/mpeg"></audio>

    <aside class="fixed w-64 h-full glass border-r border-gray-700 p-6 z-50 flex flex-col justify-between">
        <div>
            <h1 class="text-xl font-bold text-green-500 mb-8">FUTSAL ADMIN</h1>
            <nav class="space-y-4">
                <a href="index.php" class="block text-green-500"><i class="fa-solid fa-house mr-2"></i> Dashboard</a>
                <a href="atur_pertandingan.php" class="block text-gray-300 hover:text-green-400"><i class="fa-solid fa-plus mr-2"></i> Atur Pertandingan</a>
                <a href="bagan.php" class="block text-gray-300 hover:text-green-400"><i class="fa-solid fa-trophy mr-2"></i> Bagan Pertandingan</a>
            </nav>
        </div>
        <div class="space-y-4">
            <button onclick="const b=document.getElementById('bgm'); b.paused ? b.play() : b.pause();" class="text-green-500"><i class="fa-solid fa-music mr-2"></i> Musik BGM</button>
            <a href="logout.php" class="block text-red-500 font-bold"><i class="fa-solid fa-right-from-bracket mr-2"></i> Logout</a>
        </div>
    </aside>

    <main class="ml-64 p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="glass p-4 rounded-xl border border-green-500/50">
                <p class="text-gray-400 text-sm">Total Tim Terdaftar</p>
                <h2 class="text-3xl font-bold text-green-400"><?= $total_tim ?> Tim</h2>
            </div>
        </div>

        <div class="glass p-6 rounded-xl border border-gray-700">
            <div class="mb-4 space-x-2">
                <button onclick="$('#modalDaftar').removeClass('hidden')" class="bg-green-600 px-4 py-2 rounded">+ Daftar Tim</button>
                <button onclick="$('#modalTTD').removeClass('hidden')" class="bg-blue-600 px-4 py-2 rounded">TTD Digital</button>
            </div>
            <table id="tabel" class="w-full text-white">
                <thead><tr><th>Nama Tim</th><th>Manajer</th><th>TTD</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php while($r = mysqli_fetch_assoc($data)) { ?>
                    <tr>
                        <td><?= $r['nama_tim'] ?></td>
                        <td><?= $r['nama_manajer'] ?></td>
                        <td><?php if($r['ttd_digital']) { ?><img src="<?= $r['ttd_digital'] ?>" class="w-20 h-10 bg-white rounded"><?php } else { echo "-"; } ?></td>
                        <td><?= $r['status_berkas'] ?></td>
                        <td>
                            <button onclick="editModal(<?= $r['id_tim'] ?>, '<?= $r['nama_tim'] ?>', '<?= $r['nama_manajer'] ?>')" class="text-blue-400">Edit</button> | 
                            <a href="index.php?hapus=<?= $r['id_tim'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus tim <?= $r['nama_tim'] ?>?')" class="text-red-500">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <div id="modalDaftar" class="fixed inset-0 hidden flex items-center justify-center bg-black/80 z-[100]"><form method="POST" enctype="multipart/form-data" class="bg-slate-800 p-6 rounded-xl w-80"><input type="text" name="nama_tim" placeholder="Nama Tim" required class="w-full p-2 mb-2 bg-black rounded"><input type="text" name="nama_manajer" placeholder="Manajer" required class="w-full p-2 mb-2 bg-black rounded"><input type="file" name="berkas" class="w-full mb-4 text-sm"><button name="simpan_tim" class="bg-green-600 w-full p-2 rounded">Simpan</button><button type="button" onclick="$('#modalDaftar').addClass('hidden')" class="bg-red-600 w-full p-2 rounded mt-2">Batal</button></form></div>
    <div id="modalEdit" class="fixed inset-0 hidden flex items-center justify-center bg-black/80 z-[100]"><form method="POST" class="bg-slate-800 p-6 rounded-xl w-80"><input type="hidden" name="id_edit" id="id_edit"><input type="text" name="nama_tim" id="edit_tim" class="w-full p-2 mb-2 bg-black rounded"><input type="text" name="nama_manajer" id="edit_manajer" class="w-full p-2 mb-4 bg-black rounded"><button name="update_tim" class="bg-blue-600 w-full p-2 rounded">Update</button><button type="button" onclick="$('#modalEdit').addClass('hidden')" class="bg-red-600 w-full p-2 rounded mt-2">Batal</button></form></div>
    <div id="modalTTD" class="fixed inset-0 hidden flex items-center justify-center bg-black/80 z-[100]"><form method="POST" id="formTTD" class="bg-slate-800 p-6 rounded-xl"><select name="id_tim_ttd" class="w-full p-2 mb-4 bg-black rounded"><?php $o=mysqli_query($conn,"SELECT * FROM tim_peserta"); while($r=mysqli_fetch_assoc($o)) echo "<option value='".$r['id_tim']."'>".$r['nama_tim']."</option>"; ?></select><canvas id="ttdCanvas" width="300" height="150" class="mb-4 bg-white"></canvas><input type="hidden" name="image_base64" id="image_base64"><button type="button" onclick="const canvas=document.getElementById('ttdCanvas'); document.getElementById('image_base64').value=canvas.toDataURL(); document.getElementById('formTTD').submit();" class="bg-blue-600 w-full p-2 rounded">Simpan TTD</button><button type="button" onclick="$('#modalTTD').addClass('hidden')" class="bg-red-600 w-full p-2 rounded mt-2">Batal</button></form></div>

    <script>
        $(document).ready(function() { 
            $('#tabel').DataTable({ 
                dom: 'lBfrtip', 
                lengthMenu: [ [10, 25, 50, -1], ['10 Data', '25 Data', '50 Data', 'Semua Data'] ],
                buttons: ['pdf', 'excel'] 
            }); 
        });
        function editModal(id, tim, manajer) { $('#id_edit').val(id); $('#edit_tim').val(tim); $('#edit_manajer').val(manajer); $('#modalEdit').removeClass('hidden'); }
        const canvas = document.getElementById('ttdCanvas'); 
        const ctx = canvas.getContext('2d'); 
        let drawing = false;
        canvas.onmousedown = () => drawing = true; 
        canvas.onmouseup = () => drawing = false; 
        canvas.onmousemove = (e) => {
            if (drawing) {
                ctx.lineTo(e.offsetX, e.offsetY);
                ctx.stroke();
            }
        };
    </script>
</body>
</html>