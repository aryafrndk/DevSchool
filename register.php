<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DevSchool Indonesia</title>
    <link rel="stylesheet" href="styles/daftar.css" />
    <link rel="stylesheet" href="styles/header.css" />
    <link rel="stylesheet" href="styles/footer.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Header Section -->
    <?php include 'components/header.php'; ?>

    <!-- Daftar Section -->
    <section id="daftar">
        <div class="form-container">
            <h2>Daftar ke DevSchool</h2>
            <form id="register-form" method="POST" action="php/register-process.php">
                <div class="input-group">
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama" placeholder="Nama Lengkap" required />
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Alamat Email" required />
                </div>
                <div class="input-group">
                    <label for="password">Kata Sandi:</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan Kata Sandi Baru" required />
                </div>
                <div class="input-group">
                    <label for="konfirmasi-password">Konfirmasi Kata Sandi:</label>
                    <input type="password" id="konfirmasi-password" name="konfirmasi-password" placeholder="Ulangi Kata Sandi Baru" required />
                </div>
                <input type="submit" value="Daftar" />
            </form>
            <p class="or-divider">atau</p>
            <button class="btn-google">
                <img src="assets/google.png" alt="Google Icon" /> Daftar dengan Google
            </button>
            <p class="login-link">Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
            <hr />
            <p class="terms-conditions">
                Dengan melakukan pendaftaran, Anda setuju dengan
                <a href="#">syarat & ketentuan DevSchool</a>.<br />
                This site is protected by reCAPTCHA and the Google
                <a href="#">Privacy Policy</a> and
                <a href="#">Terms of Service</a> apply.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

    <?php
    if (isset($_GET['signup']) && $_GET['signup'] == 'failed') {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Pendaftaran gagal!',
                text: 'Silakan coba lagi.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                  window.history.replaceState(null, null, window.location.pathname);
            });
        </script>";
    } elseif (isset($_GET['error']) && $_GET['error'] == 'emailtaken') {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Email sudah digunakan!',
                text: 'Silakan gunakan email lain.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                  window.history.replaceState(null, null, window.location.pathname);
            });
        </script>";
    } elseif (isset($_GET['error']) && $_GET['error'] == 'passwordmismatch') {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Kata sandi tidak cocok!',
                text: 'Silakan periksa kembali kata sandi.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                  window.history.replaceState(null, null, window.location.pathname);
            });
        </script>";
    }
?>

</body>
</html>
