<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start(); 
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>

<header>
  <div class="nav-container">
    <a href="index.php" class="logo">
      <img src="assets/A.png" alt="DevSchool Logo" />
    </a>
    <nav>
      <ul>
        <li><a href="index.php#program">Program Pelatihan</a></li>
        <li><a href="index.php#informasi">Informasi Pelatihan</a></li>
        <li><a href="index.php#berita">Berita dan Artikel</a></li>
        <li><a href="index.php#galeri">Galeri</a></li>
        <li><a href="index.php#testimonial">Testimonial</a></li>
        <li><a href="index.php#kontak">Kontak</a></li>
      </ul>
    </nav>
    <div class="auth-buttons">
      <?php if (isLoggedIn()): ?>
        <!-- Jika Sudah login -->
        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <div class="user-icon" id="user-icon">
          <a href="#" id="user-icon-link">
            <img src="<?php 
              echo !empty($_SESSION['user_photo']) ? htmlspecialchars($_SESSION['user_photo']) : 'assets/user.svg'; 
            ?>" 
            alt="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User'; ?> Foto" 
            style="width: 40px;" />
          </a>
          <!-- Dropdown Menu -->
          <div class="dropdown-menu" id="dropdown-menu">
            <ul>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1): ?>
              <li><a href="manage-user.php">Kelola Data</a></li>
            <?php endif; ?>
            <?php
            if ($_SESSION['user_role'] == 2) {
                echo '<li><a href="pilih-program.php">Pilih Program Pelatihan</a></li>';
            }
            ?>
              <li><a href="dashboard.php">Dashboard</a></li>  
              <li><a href="profile.php">Pengaturan</a></li>
              <li><a href="logout.php" id="logout">Logout</a></li>
            </ul>
          </div>
        </div>      
        <script>
          // Dropdown menu logic
          const userIcon = document.getElementById('user-icon');
          const dropdownMenu = document.getElementById('dropdown-menu');

          userIcon.addEventListener("click", function (event) {
            event.preventDefault();
            userIcon.classList.toggle("active");
            dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
          });

          dropdownMenu.addEventListener("click", function (event) {
            event.stopPropagation();
          });

          // Tutup dropdown jika mengklik di luar
          document.addEventListener("click", function (event) {
            if (!userIcon.contains(event.target)) {
              dropdownMenu.style.display = "none";
              userIcon.classList.remove("active");
            }
          });
        </script>
      <?php else: ?>
        <!-- Jika Belum Login -->
        <a href="login.php" id="btn-masuk"><button class="btn-masuk">Masuk</button></a>
        <a href="register.php" id="btn-daftar"><button class="btn-daftar">Daftar</button></a>
      <?php endif; ?>
    </div>
  </div>
</header>
