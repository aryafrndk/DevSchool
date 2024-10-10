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
        berita.id_berita, 
        berita.judul_berita, 
        berita.isi_berita, 
        berita.tanggal_publikasi, 
        berita.foto_berita, 
        kategori.nama_kategori
    FROM 
        berita
    LEFT JOIN 
        kategori ON berita.id_kategori = kategori.id_kategori
    ORDER BY 
        berita.tanggal_publikasi DESC");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$category_stmt = mysqli_prepare($koneksi, "SELECT id_kategori, nama_kategori FROM kategori");
mysqli_stmt_execute($category_stmt);
$categories_result = mysqli_stmt_get_result($category_stmt);


if (isset($_GET['deleteberita_id'])) {
    $beritaId = $_GET['deleteberita_id'];

    $stmt = mysqli_prepare($koneksi, "DELETE FROM berita WHERE id_berita = ?");
    mysqli_stmt_bind_param($stmt, 'i', $beritaId);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_news') {
    $judul_berita = isset($_POST['judul_berita']) ? trim($_POST['judul_berita']) : '';
    $isi_berita = isset($_POST['isi_berita']) ? trim($_POST['isi_berita']) : '';
    $tanggal_publikasi = isset($_POST['tanggal_publikasi']) ? trim($_POST['tanggal_publikasi']) : '';
    $foto_berita = isset($_POST['foto_berita']) ? trim($_POST['foto_berita']) : '';
    $kategori = isset($_POST['kategori']) ? intval($_POST['kategori']) : 0;

    if (!empty($judul_berita) && !empty($isi_berita) && !empty($tanggal_publikasi) && !empty($foto_berita) && !empty($kategori)) {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO berita (judul_berita, isi_berita, tanggal_publikasi, foto_berita, id_kategori) VALUES (?, ?, ?, ?, ?)");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssssi', $judul_berita, $isi_berita, $tanggal_publikasi, $foto_berita, $kategori);

            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan berita: ' . mysqli_stmt_error($stmt)]);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan dalam mempersiapkan query: ' . mysqli_error($koneksi)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
    }

    mysqli_close($koneksi);
    exit;
}

function getCategoryId($kategori) {
    global $koneksi;

    $stmt = mysqli_prepare($koneksi, "SELECT id_kategori FROM kategori WHERE nama_kategori = ?");
    mysqli_stmt_bind_param($stmt, 's', $kategori);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['id_kategori'];
    } else {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO kategori (nama_kategori) VALUES (?)");
        mysqli_stmt_bind_param($stmt, 's', $kategori);
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($koneksi);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_news') {
    $beritaId = isset($_POST['berita_id']) ? intval($_POST['berita_id']) : 0;
    $judul_berita = isset($_POST['judul_berita']) ? trim($_POST['judul_berita']) : '';
    $isi_berita = isset($_POST['isi_berita']) ? trim($_POST['isi_berita']) : '';
    $tanggal_publikasi = isset($_POST['tanggal_publikasi']) ? trim($_POST['tanggal_publikasi']) : '';
    $foto_berita = isset($_POST['foto_berita']) ? trim($_POST['foto_berita']) : '';
    $kategori = isset($_POST['kategori']) ? intval($_POST['kategori']) : 0;

    if (!empty($judul_berita) && !empty($isi_berita) && !empty($tanggal_publikasi) && !empty($foto_berita) && !empty($kategori)) {
        $stmt = mysqli_prepare($koneksi, "UPDATE berita SET judul_berita = ?, isi_berita = ?, tanggal_publikasi = ?, foto_berita = ?, id_kategori = ? WHERE id_berita = ?");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssssii', $judul_berita, $isi_berita, $tanggal_publikasi, $foto_berita, $kategori, $beritaId);

            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui berita: ' . mysqli_stmt_error($stmt)]);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan dalam mempersiapkan query: ' . mysqli_error($koneksi)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
    }

    mysqli_close($koneksi);
    exit;
}


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Berita</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="styles/manage-news.css">
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
            <h2>Daftar Berita</h2>
            <button class="btn-addnews">Tambah Berita</button>
            <table id="newsTable" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul Berita</th>
                        <th>Isi Berita</th>
                        <th>Tanggal Publikasi</th>
                        <th>Foto Berita</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($berita = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($berita['id_berita']); ?></td>
                            <td><?= htmlspecialchars($berita['judul_berita']); ?></td>
                            <td class="truncate"><?= htmlspecialchars($berita['isi_berita']); ?></td>
                            <td><?= htmlspecialchars($berita['tanggal_publikasi']); ?></td>
                            <td><img src="<?= htmlspecialchars($berita['foto_berita']); ?>" alt="<?= htmlspecialchars($berita['judul_berita']); ?>" width="100"></td>
                            <td><?= htmlspecialchars($berita['nama_kategori']); ?></td>
                            <td>
                                <button class="btn-edit"
                                    data-id="<?= htmlspecialchars($berita['id_berita']); ?>"
                                    data-judul="<?= htmlspecialchars($berita['judul_berita']); ?>"
                                    data-isi="<?= htmlspecialchars($berita['isi_berita']); ?>"
                                    data-tanggal="<?= htmlspecialchars($berita['tanggal_publikasi']); ?>"
                                    data-foto="<?= htmlspecialchars($berita['foto_berita']); ?>"
                                    data-kategori="<?= htmlspecialchars($berita['nama_kategori']); ?>">Edit</button>
                                <button class="btn-delete" data-id="<?= htmlspecialchars($berita['id_berita']); ?>">Hapus</button>
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
        $('#newsTable').DataTable({
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

    document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function() {
        const beritaId = this.getAttribute('data-id'); 

        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data berita ini akan dihapus dan tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Tidak, batalkan!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`?deleteberita_id=${beritaId}`, { 
                    method: 'GET'
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            "Dihapus!",
                            "Data berita telah berhasil dihapus.",
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
                    "Data berita aman :)",
                    "error"
                );
            }
        });
    });
});

document.querySelector('.btn-addnews').addEventListener('click', function() {
    Swal.fire({
        title: 'Tambah Berita Baru',
        html: `
            <form id="addNewsForm">
                <label for="judul_berita">Judul Berita:</label>
                <input type="text" id="judul_berita" class="swal2-input" placeholder="Masukkan judul berita" required>
                <label for="isi_berita">Isi Berita:</label>
                <textarea id="isi_berita" class="swal2-textarea" placeholder="Masukkan isi berita" required></textarea>
                <label for="tanggal_publikasi">Tanggal Publikasi:</label>
                <input type="date" id="tanggal_publikasi" class="swal2-input" required>
                <label for="foto_berita">URL Foto Berita:</label>
                <input type="text" id="foto_berita" class="swal2-input" placeholder="Masukkan URL foto berita" required>
                <label for="kategori">Kategori:</label>
                <select id="kategori" class="swal2-select" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                        <option value="<?= htmlspecialchars($category['id_kategori']); ?>">
                            <?= htmlspecialchars($category['nama_kategori']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>
        `,
        confirmButtonText: 'Tambah Berita',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const judul_berita = document.getElementById('judul_berita').value;
            const isi_berita = document.getElementById('isi_berita').value;
            const tanggal_publikasi = document.getElementById('tanggal_publikasi').value;
            const foto_berita = document.getElementById('foto_berita').value;
            const kategori = document.getElementById('kategori').value;

            if (!judul_berita || !isi_berita || !tanggal_publikasi || !foto_berita || !kategori) {
                Swal.showValidationMessage('Semua field wajib diisi!');
                return false;
            }

            return {
                judul_berita,
                isi_berita,
                tanggal_publikasi,
                foto_berita,
                kategori
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = result.value;

            $.ajax({
                type: "POST",
                url: "manage-news.php",
                data: {
                    action: 'add_news',
                    judul_berita: formData.judul_berita,
                    isi_berita: formData.isi_berita,
                    tanggal_publikasi: formData.tanggal_publikasi,
                    foto_berita: formData.foto_berita,
                    kategori: formData.kategori
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Berita baru berhasil ditambahkan!',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); 
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Gagal menambahkan berita.'
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

document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
        const beritaId = this.getAttribute('data-id');
        const judulBerita = this.getAttribute('data-judul');
        const isiBerita = this.getAttribute('data-isi');
        const tanggalPublikasi = this.getAttribute('data-tanggal');
        const fotoBerita = this.getAttribute('data-foto');
        const kategori = this.getAttribute('data-kategori');

        Swal.fire({
            title: 'Edit Berita',
            html: `
                <form id="editNewsForm">
                    <label for="judul_berita">Judul Berita:</label>
                    <input type="text" id="judul_berita" class="swal2-input" value="${judulBerita}" required>
                    <label for="isi_berita">Isi Berita:</label>
                    <textarea id="isi_berita" class="swal2-textarea" required>${isiBerita}</textarea>
                    <label for="tanggal_publikasi">Tanggal Publikasi:</label>
                    <input type="date" id="tanggal_publikasi" class="swal2-input" value="${tanggalPublikasi}" required>
                    <label for="foto_berita">URL Foto Berita:</label>
                    <input type="text" id="foto_berita" class="swal2-input" value="${fotoBerita}" required>
                    <label for="kategori">Kategori:</label>
                    <select id="kategori" class="swal2-select" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                        <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                            <option value="<?= htmlspecialchars($category['id_kategori']); ?>">
                                <?= htmlspecialchars($category['nama_kategori']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>                  
                    <input type="hidden" id="berita_id" value="${beritaId}">
                </form>
            `,
            confirmButtonText: 'Update Berita',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const judul_berita = document.getElementById('judul_berita').value;
                const isi_berita = document.getElementById('isi_berita').value;
                const tanggal_publikasi = document.getElementById('tanggal_publikasi').value;
                const foto_berita = document.getElementById('foto_berita').value;
                const kategori = document.getElementById('kategori').value;

                if (!judul_berita || !isi_berita || !tanggal_publikasi || !foto_berita || !kategori) {
                    Swal.showValidationMessage('Semua field wajib diisi!');
                    return false;
                }

                return {
                    berita_id: document.getElementById('berita_id').value,
                    judul_berita,
                    isi_berita,
                    tanggal_publikasi,
                    foto_berita,
                    kategori
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = result.value;

                $.ajax({
                    type: "POST",
                    url: "manage-news.php",
                    data: {
                        action: 'edit_news',
                        berita_id: formData.berita_id,
                        judul_berita: formData.judul_berita,
                        isi_berita: formData.isi_berita,
                        tanggal_publikasi: formData.tanggal_publikasi,
                        foto_berita: formData.foto_berita,
                        kategori: formData.kategori
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Berita berhasil diperbarui!',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); 
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Gagal memperbarui berita.'
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
});

</script>

</body>
</html>