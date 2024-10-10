<?php
include 'php/config.php';

$id_program = isset($_GET['id_program']) ? intval($_GET['id_program']) : 0;

$program = [];

if ($id_program > 0) {
    $query = $koneksi->prepare("
        SELECT 
            p.*, 
            u.nama AS pelatih_nama, 
            u.jabatan 
        FROM program p
        LEFT JOIN user u ON p.id_pelatih = u.id
        WHERE p.id_program = ?
    ");
    $query->bind_param("i", $id_program); 
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $program = $result->fetch_assoc();

        if ($program['materi']) {
            $program['materi'] = json_decode($program['materi']);
        }
    } else {
        $program['error'] = 'Program not found.';
    }
} else {
    $program['error'] = 'Invalid ID.';
}

$koneksi->close(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/program-detail.css" />
    <link rel="stylesheet" href="styles/header.css" />
    <link rel="stylesheet" href="styles/footer.css" />
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

    <div id="programDetail" class="program-detail-container">

        <?php if (isset($program['error'])): ?>
            <p><?php echo $program['error']; ?></p>
        <?php else: ?>
            <div class="border-box">
                <h2><?php echo htmlspecialchars($program['nama_program']); ?></h2>
                <img src="<?php echo htmlspecialchars($program['image_url']); ?>" alt="<?php echo htmlspecialchars($program['nama_program']); ?>" class="program-detail-img" />
            </div>

            <div class="border-box">
                <h3>Description</h3>
                <p><?php echo htmlspecialchars($program['deskripsi']); ?></p>
            </div>

            <div class="border-box">
                <h3>Kurikulum</h3>
                <ul>
                    <?php foreach ($program['materi'] as $materi): ?>
                        <li><?php echo htmlspecialchars($materi); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="border-box">
                <h3>Jadwal</h3>
                <p><?php echo htmlspecialchars($program['jadwal']); ?></p>
            </div>

            <div class="border-box">
                <h3>Biaya</h3>
                <p><?php echo 'Rp. ' . number_format($program['biaya'], 0, ',', '.'); ?></p>
            </div>

            <div class="border-box">
                <h3>Pelatih</h3>
                <ul>
                    <li>
                        <strong><?php echo htmlspecialchars($program['pelatih_nama']); ?></strong> - <?php echo htmlspecialchars($program['jabatan']); ?>
                    </li>
                </ul>
            </div>

            <div class="border-box">
                <h3>Syarat</h3>
                <p><?php echo htmlspecialchars($program['syarat']); ?></p>
            </div>

            <a href="registration.php?id_program=<?php echo $id_program; ?>" class="btn-primary">Daftar Program</a>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>
</body>
</html>
