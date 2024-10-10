<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk - DevSchool</title>
    <link rel="stylesheet" href="styles/masuk.css" />
    <link rel="stylesheet" href="styles/header.css" />
    <link rel="stylesheet" href="styles/footer.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
</head>
<body>
    <!-- Header Section -->
    <?php include 'components/header.php'; ?>

    <!-- Masuk Section -->
    <section id="masuk">
        <div class="form-container">
            <h2>Masuk ke DevSchool</h2>
            <form id="form" method="POST" action="php/login-process.php">
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Alamat Email"
                        required
                    />
                </div>
                <div class="input-group">
                    <label for="password">Kata Sandi:</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan Kata Sandi"
                        required
                    />
                </div>
                <input type="submit" value="Masuk" />
            </form>

            <p class="or-divider">atau</p>
            <button class="btn-google">
                <img src="assets/google.png" alt="Google Icon" /> Masuk dengan Google
            </button>
            <p class="register-link">
                Belum punya akun? <a href="daftar.php">Daftar di sini</a>
            </p>
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
    if (isset($_GET['login']) && $_GET['login'] == 'failed') {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Email atau Kata Sandi Salah!',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                  window.history.replaceState(null, null, window.location.pathname);
            });
        </script>";

    }?>

    <?php
    if (isset($_GET['signup']) && $_GET['signup'] == 'success') {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Pendaftaran berhasil!',
                    text: 'Silakan login untuk melanjutkan.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                  window.history.replaceState(null, null, window.location.pathname);
            });
            </script>";
        }?>
</body>
</html>
