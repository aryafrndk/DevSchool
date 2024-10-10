<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/index.css" />
    <link rel="stylesheet" href="styles/header.css" />
    <link rel="stylesheet" href="styles/footer.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <title>DevSchool - Pelatihan Software Engineering</title>
</head>

<body>
    <!-- Header Section -->
    <?php include 'components/header.php'; ?>

    <!-- Profil DevSchool -->
    <section id="profil">
        <div class="container">
            <div class="content">
                <div class="text-container">
                    <h1 class="title">Tingkatkan Skill & Karirmu ditahun 2024 bersama <span class="highlight"> DevSchool</span></h1>
                    <p class="description">
                        Persiapkan dirimu dari sekarang. Dimulai dari belajar skill yang dibutuhkan oleh startup dan industri saat ini. Yuk, tingkatkan skillmu sekarang juga.
                    </p>
                </div>
                <div class="image-container">
                    <img src="assets/cover.jpg" alt="Students" class="main-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Program Pelatihan Section -->
    <section id="program" class="program-section">
        <h2 class="program-title">Pilihan Program Pelatihan</h2>
        <p class="program-description">Pilih program pelatihan yang sesuai dengan kebutuhanmu.</p>
        <div class="program-cards" id="programCards"></div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="benefits-section">
        <h2 class="benefits-title">Ikuti Program Pelatihan DevSchool dengan Berbagai Benefit</h2>
        <p class="benefits-description">Pilih program pelatihan yang sesuai dengan kebutuhanmu dan rasakan manfaat luar biasa dari DevSchool.</p>

        <div class="benefits-container">
            <div class="benefit-item">
                <div class="benefit-icon-text">
                    <i class="fa-solid fa-book fa-xs" style="color: #666661"></i>
                    <h4>Materi Berkualitas</h4>
                </div>
                <p>Kami menyediakan materi dari para praktisi yang berpengalaman di bidangnya.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon-text">
                    <i class="fa-solid fa-user-tie" style="color: #666661"></i>
                    <h4>Bimbingan Praktek</h4>
                </div>
                <p>Dapatkan bimbingan langsung dari para mentor ahli selama proses pelatihan.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon-text">
                    <i class="fa-solid fa-briefcase" style="color: #666661"></i>
                    <h4>Proyek Nyata</h4>
                </div>
                <p>Bangun portofolio profesional dengan proyek berbasis industri nyata.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon-text">
                    <i class="fa-solid fa-building" style="color: #666661"></i>
                    <h4>Job Placement</h4>
                </div>
                <p>Berpeluang langsung bekerja setelah menyelesaikan program pelatihan.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon-text">
                    <i class="fa-solid fa-certificate" style="color: #666661"></i>
                    <h4>Sertifikat Resmi</h4>
                </div>
                <p>Dapatkan sertifikat DevSchool sebagai bukti penyelesaian pelatihanmu.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon-text">
                    <i class="fa-solid fa-folder-open" style="color: #666661"></i>
                    <h4>Akses Kelas Gratis</h4>
                </div>
                <p>Nikmati akses gratis ke materi tambahan sesuai dengan program yang kamu pilih!</p>
            </div>
        </div>

        <a href="register.php" class="btn btn-primary">Daftar Sekarang Juga</a>
    </section>

    <!-- Berita dan Artikel -->
    <section id="berita">
        <h2 class="text-center">Bacaan Seputar Teknologi</h2>
        <div id="berita-container" class="card-container"></div>
        <div class="text-center">
            <a id="lihat-semua-btn" class="lihat-semua-btn" href="semua-berita.php">Lihat Semua Artikel</a>
        </div>
    </section>

    <!-- Galeri Foto dan Video dan Audio -->
    <section id="galeri">
        <h2>Galeri Foto</h2>
        <div class="slideshow-container"></div>
    </section>  

    <!-- Testimonial -->
    <section id="testimonial">
        <h2>Testimonial</h2>
        <div class="carousel-container">
            <div class="carousel"></div>
            <button class="next" onclick="moveSlide(1)">&#10095;</button>
            <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
        </div>
    </section>    

    <!-- Kontak -->
    <section id="kontak">
        <h2>Kontak Kami</h2>
        <form action="php/contact-process.php" method="POST">
            <label for="nama">Nama:</label><br />
            <input type="text" id="nama" name="nama" required /><br />
            <label for="email">Email:</label><br />
            <input type="email" id="email" name="email" required /><br />
            <label for="pesan">Pesan:</label><br />
            <textarea id="pesan" name="pesan" required></textarea><br />
            <input type="submit" value="Kirim" />
        </form>
    </section>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

    <?php
      // Tampilkan SweetAlert berdasarkan parameter di URL
      if (isset($_GET['login']) && $_GET['login'] == 'success') {
            echo "<script>
              Swal.fire({
                  icon: 'success',
                  title: 'Anda berhasil masuk!',
                  showConfirmButton: false,
                  timer: 2000
              }).then(() => {
                  window.history.replaceState(null, null, window.location.pathname);
              });
          </script>";
      }
    ?>

    <script src="scripts/galeri.js"></script> 
    <script src="scripts/program.js"></script> 
    <script src="scripts/berita.js"></script> 
    <script src="scripts/testimonial.js"></script>
</body>
</html>
