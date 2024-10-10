<?php
$server = "127.0.0.1"; 
$username = "root"; 
$password = ""; 
$database = "db_lembaga_pelatihan";

$koneksi = mysqli_connect($server, $username, $password, $database);

if (mysqli_connect_errno()) {
    echo "Koneksi Gagal: " . mysqli_connect_error();
    exit(); 
}
?>
