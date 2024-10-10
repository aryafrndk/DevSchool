<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'php/config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('Location: login.php');
    exit();     
}

$stmt = mysqli_prepare($koneksi, "
    SELECT 
        u.id,
        u.nama,
        u.email,
        u.role,
        u.alamat,
        u.no_telp,
        p.nama_program 
    FROM 
        user u
    JOIN 
        user_program up ON u.id = up.user_id
    JOIN 
        program p ON up.program_id = p.id_program
    WHERE 
        u.role = 2
");

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Pengguna</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="styles/manage-user.css">
    <link rel="stylesheet" href="styles/sidebar-admin.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/header.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>

<?php include 'components/header.php'; ?>

<main>
    <div class="container">
        <?php include 'components/sidebar-admin.php'; ?>

        <section class="main-content">
            <h2>Daftar Peserta Program</h2>
            <table id="userProgramTable" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Alamat</th>
                        <th>No. Telepon</th>
                        <th>Program Pelatihan</th>
                    </tr>
                </thead>
                    <tbody>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td class="truncate"><?php echo htmlspecialchars($user['nama']); ?></td>
                            <td class="truncate"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $user['role'] == 2 ? 'Peserta' : ''; ?></td>
                            <td class="truncate"><?php echo htmlspecialchars($user['alamat']); ?></td>
                            <td><?php echo htmlspecialchars($user['no_telp']); ?></td>
                            <td><?php echo htmlspecialchars($user['nama_program']); ?></td> <
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</main>

<?php include 'components/footer.php'; ?>

<?php
mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#userProgramTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50],
            "ordering": true,
            "searching": true,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "<<",
                    "last": ">>",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>

</body>
</html>
