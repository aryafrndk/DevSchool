<?php

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'php/config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('Location: login.php');
    exit();     
}

// Mengambil semua program
$stmt = mysqli_prepare($koneksi, "SELECT program.id_program, program.nama_program, program.deskripsi, program.image_url, program.alt_text, program.button_text, program.jadwal, program.biaya, program.syarat, program.materi, user.nama as pelatih
        FROM program
        LEFT JOIN user ON program.id_pelatih = user.id");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Menghapus program
if (isset($_GET['deleteprogram_id'])) {
    $programId = $_GET['deleteprogram_id'];
    
    $stmt = mysqli_prepare($koneksi, "DELETE FROM program WHERE id_program = ?");
    mysqli_stmt_bind_param($stmt, 'i', $programId);
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

// Mendapatkan semua pelatih
function getAllTrainers() {
    global $koneksi; 
    $query = "SELECT id AS id_pelatih, nama AS nama_pelatih FROM user WHERE role = 3";
    $result = mysqli_query($koneksi, $query);

    $trainers = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $trainers[] = $row;
    }

    return $trainers;
}

$trainers = getAllTrainers();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['id'])) {
    $nama_program = isset($_POST['nama_program']) ? trim($_POST['nama_program']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
    $image_url = isset($_POST['image_url']) ? trim($_POST['image_url']) : '';
    $alt_text = isset($_POST['alt_text']) ? trim($_POST['alt_text']) : '';
    $button_text = isset($_POST['button_text']) ? trim($_POST['button_text']) : '';
    $jadwal = isset($_POST['jadwal']) ? trim($_POST['jadwal']) : '';
    $biaya = isset($_POST['biaya']) ? intval($_POST['biaya']) : 0;
    $syarat = isset($_POST['syarat']) ? trim($_POST['syarat']) : '';
    $materi = isset($_POST['materi']) ? trim($_POST['materi']) : '';
    $pelatih = isset($_POST['id_pelatih']) ? intval($_POST['id_pelatih']) : 0; 

    if (!empty($nama_program) && !empty($deskripsi)) {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO program (nama_program, deskripsi, image_url, alt_text, button_text, jadwal, biaya, syarat, materi, id_pelatih) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssssssissi', $nama_program, $deskripsi, $image_url, $alt_text, $button_text, $jadwal, $biaya, $syarat, $materi, $pelatih);

            if (mysqli_stmt_execute($stmt)) {
                $response = [
                    'success' => true,
                    'message' => 'Data program berhasil ditambahkan!'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Gagal menambahkan data program: ' . mysqli_stmt_error($stmt)
                ];
            }

            mysqli_stmt_close($stmt);
        } else {
            $response = [
                'success' => false,
                'message' => 'Terjadi kesalahan dalam mempersiapkan query: ' . mysqli_error($koneksi)
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Data tidak lengkap.'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Memperbarui program
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']); 
    $nama_program = isset($_POST['nama_program']) ? mysqli_real_escape_string($koneksi, $_POST['nama_program']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? mysqli_real_escape_string($koneksi, $_POST['deskripsi']) : '';
    $image_url = isset($_POST['image_url']) ? mysqli_real_escape_string($koneksi, $_POST['image_url']) : '';
    $alt_text = isset($_POST['alt_text']) ? mysqli_real_escape_string($koneksi, $_POST['alt_text']) : '';
    $button_text = isset($_POST['button_text']) ? mysqli_real_escape_string($koneksi, $_POST['button_text']) : '';
    $jadwal = isset($_POST['jadwal']) ? mysqli_real_escape_string($koneksi, $_POST['jadwal']) : '';
    $biaya = isset($_POST['biaya']) ? intval($_POST['biaya']) : 0; 
    $pelatih = isset($_POST['pelatih']) ? intval($_POST['pelatih']) : 0;

    if (!empty($nama_program) && !empty($deskripsi)) {
        $query = "UPDATE program SET 
                    nama_program = '$nama_program',
                    deskripsi = '$deskripsi',
                    image_url = '$image_url',
                    alt_text = '$alt_text',
                    button_text = '$button_text',
                    jadwal = '$jadwal',
                    biaya = $biaya,
                    id_pelatih = $pelatih
                  WHERE id_program = $id";

        if (mysqli_query($koneksi, $query)) {
            $response = [
                'success' => true,
                'message' => 'Data program berhasil diperbarui!'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal memperbarui data program: ' . mysqli_error($koneksi)
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Data tidak lengkap.'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Program</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="styles/manage-programs.css">
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
            <h2>Daftar Program </h2>
            <button class="btn-addprogram">Tambah Program</button>
            <table id="programsTable" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Program</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Alt Text</th>
                        <th>Button Text</th>
                        <th>Jadwal</th>
                        <th>Biaya</th>
                        <th>Syarat</th>
                        <th>Materi</th>
                        <th>Pelatih</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($program = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($program['id_program']); ?></td>
                            <td><?= htmlspecialchars($program['nama_program']); ?></td>
                            <td class="truncate"><?= htmlspecialchars($program['deskripsi']); ?></td>
                            <td><img src="<?= htmlspecialchars($program['image_url']); ?>" alt="<?= htmlspecialchars($program['alt_text']); ?>" width="100"></td>
                            <td><?= htmlspecialchars($program['alt_text']); ?></td>
                            <td class="truncate"><?= htmlspecialchars($program['button_text']); ?></td>
                            <td class="truncate"><?= htmlspecialchars($program['jadwal']); ?></td>
                            <td class="truncate"><?= htmlspecialchars($program['biaya']); ?></td>
                            <td class="truncate"><?= htmlspecialchars($program['syarat']); ?></td>
                            <td class="truncate"><?= htmlspecialchars($program['materi']); ?></td>
                            <td><?= htmlspecialchars($program['pelatih']); ?></td>
                            <td>
                                <button class="btn-view" data-id="<?= htmlspecialchars($program['id_program']); ?>">View</button>
                                <button class="btn-edit"
                                    data-id="<?= htmlspecialchars($program['id_program']); ?>"
                                    data-nama="<?= htmlspecialchars($program['nama_program']); ?>"
                                    data-deskripsi="<?= htmlspecialchars($program['deskripsi']); ?>"
                                    data-url="<?= htmlspecialchars($program['image_url']); ?>"
                                    data-alt_text="<?= htmlspecialchars($program['alt_text']); ?>"
                                    data-button_text="<?= htmlspecialchars($program['button_text']); ?>"
                                    data-jadwal="<?= htmlspecialchars($program['jadwal']); ?>"
                                    data-biaya="<?= htmlspecialchars($program['biaya']); ?>"
                                    data-syarat="<?= htmlspecialchars($program['syarat']); ?>"
                                    data-materi="<?= htmlspecialchars($program['materi']); ?>"
                                    data-pelatih="<?= htmlspecialchars($program['pelatih']); ?>">Edit</button>
                                <button class="btn-delete" data-id="<?= htmlspecialchars($program['id_program']); ?>">Hapus</button>
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
        $('#programsTable').DataTable({
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

<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const programId = this.getAttribute('data-id');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data program ini akan dihapus dan tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batalkan!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`?deleteprogram_id=${programId}`, {
                        method: 'GET'
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                "Dihapus!",
                                "Data program telah berhasil dihapus.",
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
                        "Data program aman :)",
                        "error"
                    );
                }
            });
        });
    });

    const trainers = <?= json_encode($trainers); ?>;

    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const programId = this.getAttribute('data-id');
            const namaProgram = this.getAttribute('data-nama');
            const deskripsi = this.getAttribute('data-deskripsi');
            const imageUrl = this.getAttribute('data-url');
            const altText = this.getAttribute('data-alt_text');
            const buttonText = this.getAttribute('data-button_text');
            const jadwal = this.getAttribute('data-jadwal');
            const biaya = this.getAttribute('data-biaya');
            const syarat = this.getAttribute('data-syarat');
            const materi = this.getAttribute('data-materi');
            const pelatihId = this.getAttribute('data-pelatih');

            const trainerOptions = trainers.map(trainer => `<option value="${trainer.id_pelatih}" ${trainer.id_pelatih == pelatihId ? 'selected' : ''}>${trainer.nama_pelatih}</option>`).join('');

            Swal.fire({
                title: "Edit Data Program",
                html:
                    `<label for="nama_program">Nama Program</label>` +
                    `<input id="nama_program" class="swal2-input" placeholder="Nama Program" value="${namaProgram}">` +
                    `<label for="deskripsi">Deskripsi</label>` +
                    `<textarea id="deskripsi" class="swal2-textarea" placeholder="Deskripsi">${deskripsi}</textarea>` +
                    `<label for="image_url">Image URL</label>` +
                    `<input id="image_url" class="swal2-input" placeholder="Image URL" value="${imageUrl}">` +
                    `<label for="alt_text">Alt Text</label>` +
                    `<input id="alt_text" class="swal2-input" placeholder="Alt Text" value="${altText}">` +
                    `<label for="button_text">Button Text</label>` +
                    `<input id="button_text" class="swal2-input" placeholder="Button Text" value="${buttonText}">` +
                    `<label for="jadwal">Jadwal</label>` +
                    `<textarea id="jadwal" class="swal2-textarea" placeholder="Jadwal">${jadwal}</textarea>` +
                    `<label for="biaya">Biaya</label>` +
                    `<input id="biaya" class="swal2-input" placeholder="Biaya" value="${biaya}">` +
                    `<label for="syarat">Syarat</label>` +
                    `<textarea id="syarat" class="swal2-textarea" placeholder="Syarat">${syarat}</textarea>` +
                    `<label for="materi">Materi</label>` +
                    `<textarea id="materi" class="swal2-textarea" placeholder="Materi">${materi}</textarea>` +
                    `<label for="id_pelatih">Pelatih</label>` +
                    `<select id="pelatih" class="swal2-select">${trainerOptions}</select>`,
                showCancelButton: true,
                confirmButtonText: "Simpan",
                cancelButtonText: "Batal",
                preConfirm: () => {
                    return {
                        nama_program: document.getElementById('nama_program').value,
                        deskripsi: document.getElementById('deskripsi').value,
                        image_url: document.getElementById('image_url').value,
                        alt_text: document.getElementById('alt_text').value,
                        button_text: document.getElementById('button_text').value,
                        jadwal: document.getElementById('jadwal').value,
                        biaya: document.getElementById('biaya').value,
                        syarat: document.getElementById('syarat').value,
                        materi: document.getElementById('materi').value,
                        pelatih: document.getElementById('pelatih').value
                    }
                },
                customClass: {
                    popup: 'swal2-modal1',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "manage-programs.php",
                        data: {
                            id: programId,
                            nama_program: result.value.nama_program,
                            deskripsi: result.value.deskripsi,
                            image_url: result.value.image_url,
                            alt_text: result.value.alt_text,
                            button_text: result.value.button_text,
                            jadwal: result.value.jadwal,
                            biaya: result.value.biaya,
                            syarat: result.value.syarat,
                            materi: result.value.materi,
                            pelatih: result.value.pelatih
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data program berhasil diperbarui!'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal memperbarui data program.'
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

    document.querySelectorAll('.btn-addprogram').forEach(button => {
        button.addEventListener('click', () => {

            const trainerOptions = trainers.map(trainer => `<option value="${trainer.id_pelatih}">${trainer.nama_pelatih}</option>`).join('');

            Swal.fire({
                title: "Add Program",
                html: `
                    <form id="addProgramForm">
                        <div>
                            <label for="nama_program">Nama Program:</label>
                            <input class="swal2-input" type="text" id="nama_program" required>
                        </div>
                        <div>
                            <label for="deskripsi">Deskripsi:</label>
                            <textarea class="swal2-textarea" id="deskripsi" required></textarea>
                        </div>
                        <div>
                            <label for="image_url">Image URL:</label>
                            <input class="swal2-input" type="text" id="image_url" required>
                        </div>
                        <div>
                            <label for="alt_text">Alt Text:</label>
                            <input class="swal2-input" type="text" id="alt_text" required>
                        </div>
                        <div>
                            <label for="button_text">Button Text:</label>
                            <input class="swal2-input" type="text" id="button_text" required>
                        </div>
                        <div>
                            <label for="jadwal">Jadwal:</label>
                            <textarea class="swal2-textarea" id="jadwal" required></textarea>
                        </div>
                        <div>
                            <label for="biaya">Biaya:</label>
                            <input class="swal2-input" type="number" id="biaya" required>
                        </div>
                        <div>
                            <label for="syarat">Syarat:</label>
                            <textarea class="swal2-textarea" id="syarat" required></textarea>
                        </div>
                        <div>
                            <label for="materi">Materi:</label>
                            <textarea class="swal2-textarea" id="materi" required></textarea>
                        </div>
                        <div>
                            <label for="id_pelatih">Pelatih</label>
                            <select id="id_pelatih" class="swal2-select">${trainerOptions}</select>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                preConfirm: () => {
                    const nama_program = document.getElementById('nama_program').value;
                    const deskripsi = document.getElementById('deskripsi').value;
                    const image_url = document.getElementById('image_url').value;
                    const alt_text = document.getElementById('alt_text').value;
                    const button_text = document.getElementById('button_text').value;
                    const jadwal = document.getElementById('jadwal').value;
                    const biaya = document.getElementById('biaya').value;
                    const syarat = document.getElementById('syarat').value;
                    const materi = document.getElementById('materi').value;
                    const id_pelatih = document.getElementById('id_pelatih').value; 

                    // Validate inputs
                    if (!nama_program || !deskripsi || !image_url || !alt_text || !button_text || !jadwal || !biaya || !syarat || !materi || !id_pelatih) {
                        Swal.showValidationMessage('Semua field wajib diisi!');
                        return false;
                    }

                    return {
                        nama_program,
                        deskripsi,
                        image_url,
                        alt_text,
                        button_text,
                        jadwal,
                        biaya,
                        syarat,
                        materi,
                        id_pelatih 
                    };
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = result.value;

                    $.ajax({
                        type: "POST",
                        url: "manage-programs.php",
                        data: {
                            nama_program: formData.nama_program,
                            deskripsi: formData.deskripsi,
                            image_url: formData.image_url,
                            alt_text: formData.alt_text,
                            button_text: formData.button_text,
                            jadwal: formData.jadwal,
                            biaya: formData.biaya,
                            syarat: formData.syarat,
                            materi: formData.materi,
                            id_pelatih: formData.id_pelatih,
                        },
                        dataType: "json",
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message || 'Gagal menambahkan data program.'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: `Terjadi kesalahan saat menghubungi server: ${xhr.status} ${xhr.statusText}`
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