<?php
// Baris session_start() sudah dihapus dari sini agar tidak konflik
$host     = "localhost";
$user     = "root"; 
$password = ""; 
$db       = "db_turnamen_futsal";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>