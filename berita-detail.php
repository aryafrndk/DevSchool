<?php
include 'php/config.php'; 

$berita = null;

if (isset($_GET['id_berita'])) {
    $id = intval($_GET['id_berita']); 

    $query = "SELECT * FROM berita WHERE id_berita = ?";
    $stmt = $koneksi->prepare($query); 
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $berita = $result->fetch_assoc();
    }

    $stmt->close();
    $koneksi->close(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Berita - DevSchool</title>
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/berita-detail.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Header Section -->
    <?php include 'components/header.php'; ?>

    <!-- Detail Berita Section -->
    <div class="detail-container">
        <div id="berita-detail">
            <?php if ($berita): ?>
                <div class="card">
                    <h2 class="detail-title"><?php echo htmlspecialchars($berita['judul_berita']); ?></h2>
                    <p class="release-date"><?php echo date('d-m-Y', strtotime($berita['tanggal_publikasi'])); ?></p>
                    <img src="<?php echo htmlspecialchars($berita['foto_berita']); ?>" alt="<?php echo htmlspecialchars($berita['judul_berita']); ?>" class="detail-image">
                    <p class="detail-content"><?php echo nl2br(htmlspecialchars($berita['isi_berita'])); ?></p>
                </div>
            <?php else: ?>
                <p>Berita tidak ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Section -->
    <?php include 'components/footer.php'; ?>

</body>
</html>
