<?php
session_start();
include 'config.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nama'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_photo'] = $user['foto'];
        $_SESSION['user_role'] = $user['role'];
    
        header('Location: ../index.php?login=success');
        exit();
    } else {
        header('Location: ../login.php?login=failed');
        exit();
    }
} else {
    $_SESSION['error'] = "Email atau kata sandi salah.";
    header("Location: ../login.php?login=failed");
    exit();
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>
