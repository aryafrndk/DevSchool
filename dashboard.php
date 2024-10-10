<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DevSchool Indonesia</title>
    <link rel="stylesheet" href="styles/header.css" />
    <link rel="stylesheet" href="styles/footer.css" />
    <link rel="stylesheet" href="styles/dashboard.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Header Section -->
    <?php include 'components/header.php'; ?>

    <div class="dashboard">
        <div class="dashboard-header">
          <h1>Dashboard</h1>
          <p>Selamat datang, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Pengguna'; ?></p>
        </div>
      
        <nav class="program-nav">
          <ul>
            <li><a href="?prog=aktif" class="<?php echo (isset($_GET['prog']) && $_GET['prog'] == 'aktif') ? 'active' : ''; ?>">Program Aktif</a></li>
            <li><a href="?prog=selesai" class="<?php echo (isset($_GET['prog']) && $_GET['prog'] == 'selesai') ? 'active' : ''; ?>">Program Selesai</a></li>
          </ul>
        </nav>
      
        <div class="programs">
          <div class="program-section" id="aktif-programs" style="<?php echo (isset($_GET['prog']) && $_GET['prog'] == 'aktif') ? '' : 'display: none;'; ?>">
            <h2>Program Aktif</h2>
            <ul class="program-list">
              <li class="program-item">
                <h3>Back-End Developer</h3>
                <p>Status: Sedang Berlangsung</p>
                <button class="detail-button" onclick="location.href='tugas_program.php?program=back-end-developer'">Detail Program</button>
              </li>
              <li class="program-item">
                <h3>Data Scientist</h3>
                <p>Status: Sedang Berlangsung</p>
                <button class="detail-button" onclick="location.href='tugas_program.php?program=data-scientist'">Detail Program</button>
              </li>
            </ul>
          </div>
      
          <div class="program-section" id="selesai-programs" style="<?php echo (isset($_GET['prog']) && $_GET['prog'] == 'selesai') ? '' : 'display: none;'; ?>">
            <h2>Program Selesai</h2>
            <ul class="program-list">
              <li class="program-item">
                <h3>IOS Developer</h3>
                <p>Status: Selesai</p>
                <button class="detail-button" onclick="location.href='tugas_program.php?program=ios-developer'">Detail Program</button>
              </li>
              <li class="program-item">
                <h3>Machine Learning Engineer</h3>
                <p>Status: Selesai</p>
                <button class="detail-button" onclick="location.href='tugas_program.php?program=machine-learning-engineer'">Detail Program</button>
              </li>
            </ul>
          </div>
        </div>
      </div>      
      
    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

    <script src="scripts/dashboard.js"></script>

</body>
</html>
