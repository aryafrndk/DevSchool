<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'php/config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('Location: login.php');
    exit();     
}

$stmt = mysqli_prepare($koneksi, "SELECT 
        testimonials.id, 
        testimonials.name, 
        testimonials.role, 
        testimonials.testimonial, 
        testimonials.photo
    FROM 
        testimonials
    ORDER BY 
        testimonials.id DESC");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (isset($_GET['deletetestimonial_id'])) {
    $testimonialId = $_GET['deletetestimonial_id'];

    // Menghapus testimonial
    $stmt = mysqli_prepare($koneksi, "DELETE FROM testimonials WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $testimonialId);
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

// Handle testimonial addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_testimonial') {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $testimonial = $_POST['testimonial'];
    $photo = $_POST['photo'];

    $stmt = mysqli_prepare($koneksi, "INSERT INTO testimonials (name, role, testimonial, photo) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssss', $name, $role, $testimonial, $photo);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan testimonial.']);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
    exit;
}

// Handle testimonial edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_testimonial') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $testimonial = $_POST['testimonial'];
    $photo = $_POST['photo'];

    $stmt = mysqli_prepare($koneksi, "UPDATE testimonials SET name = ?, role = ?, testimonial = ?, photo = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'ssssi', $name, $role, $testimonial, $photo, $id);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengedit testimonial.']);
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
    <title>Kelola Testimonial</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="styles/manage-testimonials.css">
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
            <h2>Daftar Testimonial</h2>
            <button class="btn-addtestimonial">Tambah Testimonial</button>
            <table id="testimonialTable" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Testimonial</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($testimonial = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($testimonial['id']); ?></td>
                            <td><?= htmlspecialchars($testimonial['name']); ?></td>
                            <td><?= htmlspecialchars($testimonial['role']); ?></td>
                            <td class="truncate"><?= htmlspecialchars($testimonial['testimonial']); ?></td>
                            <td><img src="<?= htmlspecialchars($testimonial['photo']); ?>" alt="<?= htmlspecialchars($testimonial['name']); ?>" width="100"></td>
                            <td>
                                <button class="btn-edit"
                                    data-id="<?= htmlspecialchars($testimonial['id']); ?>"
                                    data-name="<?= htmlspecialchars($testimonial['name']); ?>"
                                    data-role="<?= htmlspecialchars($testimonial['role']); ?>"
                                    data-testimonial="<?= htmlspecialchars($testimonial['testimonial']); ?>"
                                    data-photo="<?= htmlspecialchars($testimonial['photo']); ?>">Edit</button>
                                <button class="btn-delete" data-id="<?= htmlspecialchars($testimonial['id']); ?>">Hapus</button>
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

<!--jQuery dan DataTables JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#testimonialTable').DataTable({
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

    // Add testimonial
    document.querySelector('.btn-addtestimonial').addEventListener('click', function() {
        Swal.fire({
            title: 'Tambah Testimonial',
            html: `
                <form id="addTestimonialForm">
                    <label for="name">Nama:</label>
                    <input type="text" id="name" class="swal2-input" placeholder="Masukkan nama" required>
                    <label for="role">Role:</label>
                    <input type="text" id="role" class="swal2-input" placeholder="Masukkan role" required>
                    <label for="testimonial">Testimonial:</label>
                    <textarea id="testimonial" class="swal2-textarea" placeholder="Masukkan testimonial" required></textarea>
                    <label for="photo">URL Foto:</label>
                    <input type="text" id="photo" class="swal2-input" placeholder="Masukkan URL foto" required>
                </form>
            `,
            confirmButtonText: 'Tambah Testimonial',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const name = document.getElementById('name').value;
                const role = document.getElementById('role').value;
                const testimonial = document.getElementById('testimonial').value;
                const photo = document.getElementById('photo').value;

                if (!name || !role || !testimonial || !photo) {
                    Swal.showValidationMessage('Semua field wajib diisi!');
                    return false;
                }

                return { name, role, testimonial, photo };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = result.value;
                $.ajax({
                    type: "POST",
                    url: "",
                    data: {
                        action: 'add_testimonial', 
                        name: formData.name,
                        role: formData.role,
                        testimonial: formData.testimonial,
                        photo: formData.photo
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Testimonial baru berhasil ditambahkan!',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); 
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Gagal menambahkan testimonial.'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: `Terjadi kesalahan: ${xhr.status} ${xhr.statusText}`
                        });
                    }
                });
            }
        });
    });

    // Edit testimonial
    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const role = $(this).data('role');
        const testimonial = $(this).data('testimonial');
        const photo = $(this).data('photo');

        Swal.fire({
            title: 'Edit Testimonial',
            html: `
                <form id="editTestimonialForm">
                    <input type="hidden" id="editId" value="${id}">
                    <label for="editName">Nama:</label>
                    <input type="text" id="editName" class="swal2-input" value="${name}" required>
                    <label for="editRole">Role:</label>
                    <input type="text" id="editRole" class="swal2-input" value="${role}" required>
                    <label for="editTestimonial">Testimonial:</label>
                    <textarea id="editTestimonial" class="swal2-textarea" required>${testimonial}</textarea>
                    <label for="editPhoto">URL Foto:</label>
                    <input type="text" id="editPhoto" class="swal2-input" value="${photo}" required>
                </form>
            `,
            confirmButtonText: 'Simpan Perubahan',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const id = document.getElementById('editId').value;
                const name = document.getElementById('editName').value;
                const role = document.getElementById('editRole').value;
                const testimonial = document.getElementById('editTestimonial').value;
                const photo = document.getElementById('editPhoto').value;

                if (!name || !role || !testimonial || !photo) {
                    Swal.showValidationMessage('Semua field wajib diisi!');
                    return false;
                }

                return { id, name, role, testimonial, photo };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = result.value;

                $.ajax({
                    type: "POST",
                    url: "", 
                    data: {
                        action: 'edit_testimonial', 
                        id: formData.id,
                        name: formData.name,
                        role: formData.role,
                        testimonial: formData.testimonial,
                        photo: formData.photo
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', 'Testimonial berhasil diubah.', 'success').then(() => {
                                location.reload(); 
                            });
                        } else {
                            Swal.fire('Gagal', 'Gagal mengedit testimonial.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', `Terjadi kesalahan: ${xhr.status} ${xhr.statusText}`, 'error');
                    }
                });
            }
        });
    });

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const testimonialId = this.getAttribute('data-id'); 

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data testimonial ini akan dihapus dan tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batalkan!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`?deletetestimonial_id=${testimonialId}`, { 
                        method: 'GET'
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                "Dihapus!",
                                "Data testimonial telah berhasil dihapus.",
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
                        "Data testimonial aman :)",
                        "error"
                    );
                }
            });
        });
    });
</script>

</body>
</html>
