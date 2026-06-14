<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mencegah SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Cek kecocokan akun di database
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Jika cocok, buat sesi login
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user'] = $username;
        
        // Pindah ke halaman admin
        header("Location: index.php");
        exit;
    } else {
        // Jika salah, munculkan pop-up dan kembalikan ke login
        echo "<script>alert('Peringatan: Username atau Password Anda salah!'); window.location.href='login.html';</script>";
        exit;
    }
}
?>