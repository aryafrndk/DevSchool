<?php
session_start();
include 'config.php';

$nama = $_POST['nama'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$konfirmasi_password = $_POST['konfirmasi-password'] ?? '';

if (empty($nama) || empty($email) || empty($password) || empty($konfirmasi_password)) {
    $_SESSION['error'] = 'Semua kolom harus diisi.';
    header("Location: ../register.php?error=emptyfields");
    exit();
}

if ($password !== $konfirmasi_password) {
    $_SESSION['error'] = 'Kata sandi tidak cocok.';
    header("Location: ../register.php?error=passwordmismatch");
    exit();
}

// Cek apakah email sudah ada
$stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['error'] = 'Email sudah digunakan.';
    header("Location: ../register.php?error=emailtaken");
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($koneksi, "INSERT INTO user (nama, email, password, role) VALUES (?, ?, ?, ?)");
$role = 2; 
mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $hashedPassword, $role);


if (mysqli_stmt_execute($stmt)) {
    $_SESSION['registration_success'] = true;

    header("Location: ../login.php?signup=success");
    exit();
} else {
    $_SESSION['error'] = 'Terjadi kesalahan saat pendaftaran.';
    header("Location: ../register.php?error=failed");
    exit();
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>
