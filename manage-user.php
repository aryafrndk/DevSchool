<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'php/config.php';


if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('Location: login.php');
    exit();     
}

$stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE role = 2");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $stmtNilai = mysqli_prepare($koneksi, "DELETE FROM user_program WHERE user_id = ?");
    mysqli_stmt_bind_param($stmtNilai, 'i', $id);
    mysqli_stmt_execute($stmtNilai);
    mysqli_stmt_close($stmtNilai);
    $stmt = mysqli_prepare($koneksi, "DELETE FROM user WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];

    $stmt = mysqli_prepare($koneksi, "UPDATE user SET nama = ?, email = ?, alamat = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'sssi', $nama, $email, $alamat, $id);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
    exit;
}

if (isset($_GET['view_id'])) {
    $id = $_GET['view_id'];
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows > 0) {
        $userData = mysqli_fetch_assoc($result);
        echo json_encode($userData);
    } else {
        echo json_encode(['error' => 'User not found']);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
    exit;
}
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
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
</head>
<body>

<?php include 'components/header.php'; ?>

<main>
    <div class="container">
        <?php include 'components/sidebar-admin.php'; ?>

        <section class="main-content">
            <h2>Daftar Pengguna</h2>
            <table id="userTable" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Alamat</th>
                        <th>No. Telepon</th>
                        <th>Foto</th>
                        <th>Gender</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Tanggal Dibuat</th>
                        <th>Tanggal Diperbarui</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td class="truncate"><?php echo htmlspecialchars($user['nama']); ?></td>
                            <td class="truncate"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php if ($user['role'] == 2) { echo 'Peserta';} ?></td>
                            <td class="truncate"><?php echo htmlspecialchars($user['alamat']); ?></td>
                            <td><?php echo htmlspecialchars($user['no_telp']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($user['foto']); ?>" alt="Foto" width="50"></td>
                            <td><?php echo htmlspecialchars($user['gender']); ?></td>
                            <td class="truncate"><?php echo htmlspecialchars($user['birth_place']); ?></td>
                            <td><?php echo htmlspecialchars($user['birth_date']); ?></td>
                            <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($user['updated_at']); ?></td>
                            <td>
                                <button class="btn-view" data-id="<?php echo $user['id']; ?>">View</button>
                                <button class="btn-edit" 
                                data-id="<?php echo $user['id']; ?>" 
                                data-nama="<?php echo htmlspecialchars($user['nama']); ?>" 
                                data-email="<?php echo htmlspecialchars($user['email']); ?>" 
                                data-alamat="<?php echo htmlspecialchars($user['alamat']); ?>">Edit</button>
                                <button class="btn-delete" data-id="<?php echo $user['id']; ?>">Hapus</button>
                            </td>
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
        $('#userTable').DataTable({
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

<!-- Script untuk hapus data -->
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data pengguna ini akan dihapus dan tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batalkan!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`?delete_id=${userId}`, {
                        method: 'GET'
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                "Dihapus!",
                                "Data pengguna telah berhasil dihapus.",
                                "success"
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                "Gagal!",
                                "Terjadi kesalahan saat menghapus data.",
                                "error"
                            );
                        }
                    });
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    Swal.fire(
                        "Dibatalkan",
                        "Data pengguna aman :)",
                        "error"
                    );
                }
            });
        });
    });
</script>

<script>
    document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const nama = this.getAttribute('data-nama');
        const email = this.getAttribute('data-email');
        const alamat = this.getAttribute('data-alamat');

        Swal.fire({
            title: "Edit Data Pengguna",
            html:
                `<label for="nama">Nama</label>` +
                `<input id="nama" class="swal2-input" placeholder="Nama" value="${nama}">` +
                `<label for="email">Email</label>` +
                `<input id="email" class="swal2-input" placeholder="Email" value="${email}">` +
                `<label for="alamat">Alamat</label>` +
                `<input id="alamat" class="swal2-input" placeholder="Alamat" value="${alamat}">`,
            showCancelButton: true,
            confirmButtonText: "Simpan",
            cancelButtonText: "Batal",
            preConfirm: () => {
                return {
                    nama: document.getElementById('nama').value,
                    email: document.getElementById('email').value,
                    alamat: document.getElementById('alamat').value
                }
            },
            customClass: {
                popup: 'swal2-modal1',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "manage-user.php",
                    data: {
                        id: userId,
                        nama: result.value.nama,
                        email: result.value.email,
                        alamat: result.value.alamat
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data pengguna berhasil diperbarui!'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal memperbarui data pengguna.'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menghubungi server.'
                        });
                    }
                });
            }
        });
    });
});
</script>

<script>
    document.querySelectorAll('.btn-view').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id'); 
            fetch(`?view_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error,
                        });
                    } else {
                        Swal.fire({
                            title: 'User Data',
                            html: `
                                <img src="${data.foto}" alt="User Photo" style="width: 200px; height: 200px; border-radius: 50%; margin-bottom: 20px; object-fit: cover;"><br>
                                <strong>ID:</strong> ${data.id}<br>
                                <strong>Nama:</strong> ${data.nama}<br>
                                <strong>Email:</strong> ${data.email}<br>
                                <strong>Alamat:</strong> ${data.alamat}<br>
                                <strong>No. Telepon:</strong> ${data.no_telp}<br>
                                <strong>Gender:</strong> ${data.gender}<br>
                                <strong>Tempat Lahir:</strong> ${data.birth_place}<br>
                                <strong>Tanggal Lahir:</strong> ${data.birth_date}<br>
                                <strong>Hobi:</strong> ${data.hobbies}<br>
                                <strong>Tentang Saya:</strong> ${data.about_me}<br>
                                <strong>Tanggal Dibuat:</strong> ${data.created_at}<br>
                                <strong>Tanggal Diperbarui:</strong> ${data.updated_at}
                            `,
                            icon: 'ul',
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch user data!',
                    });
                });
        });
    });
</script>

</body>
</html>
