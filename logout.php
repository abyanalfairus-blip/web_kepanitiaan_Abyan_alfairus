<?php
// Menghapus data sesi
session_start();
session_destroy();
?>

<script>
    window.location.href = 'login.html';
</script>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="0;url=login.html">
    <style>
        body { background-color: #111827; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif; }
        a { color: #16a34a; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <p>Berhasil keluar. <a href="login.html">Klik di sini untuk kembali ke halaman Login</a></p>
</body>
</html>