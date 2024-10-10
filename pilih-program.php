<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'php/config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 2) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$script = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['programs'])) {
        $programs = $_POST['programs'];

        mysqli_begin_transaction($koneksi);

        $delete_query = "DELETE FROM user_program WHERE user_id = '$user_id'";
        mysqli_query($koneksi, $delete_query);

        foreach ($programs as $program_id) {
            $insert_query = "INSERT INTO user_program (user_id, program_id) VALUES ('$user_id', '$program_id')";
            mysqli_query($koneksi, $insert_query);
        }

        mysqli_commit($koneksi);

        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Program pelatihan berhasil disimpan!',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location = 'pilih-program.php';
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Anda belum memilih program pelatihan!',
                confirmButtonText: 'OK'
            });
        ";
    }
}

// Fetch all available programs
$query = "SELECT id_program, nama_program FROM program";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Program Pelatihan</title>
    <link rel="stylesheet" href="styles/pilih-program.css" />
    <link rel="stylesheet" href="styles/header.css" />
    <link rel="stylesheet" href="styles/footer.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
</head>
<body>

    <?php include 'components/header.php'; ?>

    <h2>Pilih Program Pelatihan</h2>

    <form action="pilih-program.php" method="POST">
        <label for="programs">Program Pelatihan:</label>
        <div id="programs">
            <?php while ($program = mysqli_fetch_assoc($result)): ?>
                <div class="program-checkbox">
                    <input type="checkbox" name="programs[]" value="<?= htmlspecialchars($program['id_program']); ?>">
                    <?= htmlspecialchars($program['nama_program']); ?>
                </div>
            <?php endwhile; ?>
        </div>
        
        <br><br>
        <input type="submit" value="Simpan Pilihan Program">
    </form>

    <br><br>

    <!-- Display selected programs -->
    <?php
    $selected_query = "SELECT p.nama_program FROM user_program up JOIN program p ON up.program_id = p.id_program WHERE up.user_id = '$user_id'";
    $selected_result = mysqli_query($koneksi, $selected_query);
    
    if (mysqli_num_rows($selected_result) > 0): ?>
        <h3>Program yang Anda Pilih:</h3>
        <ul>
            <?php while ($selected_program = mysqli_fetch_assoc($selected_result)): ?>
                <li><?= htmlspecialchars($selected_program['nama_program']); ?></li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Anda belum memilih program pelatihan.</p>
    <?php endif; ?>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

    <script>
        <?php echo $script;?>
    </script>
</body>
</html>
