<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Artikel - DevSchool</title>
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/semua-berita.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header Section -->
    <?php include 'components/header.php'; ?>

    <!-- Semua Artikel Section -->
    <div class="container">
        <h2 class="text-center">Semua Artikel</h2>
        <div id="berita-container" class="card-container">
        </div>

        <div class="text-center">
            <a href="index.php" class="kembali-btn">Kembali ke Beranda</a>
        </div>
    </div>

    <!-- Footer Section -->
    <?php include 'components/footer.php'; ?>

    <script src="scripts/semua-berita.js"></script>
    <script src="scripts/header.js"></script>
    <script src="scripts/footer.js"></script>
</body>
</html>
