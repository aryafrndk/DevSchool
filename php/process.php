<?php
// Tampilkan error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Tentukan direktori tujuan untuk menyimpan file yang diunggah
$uploadDir = 'uploads/'; 
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true); // Buat folder jika belum ada
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['full-name']) && !empty($_POST['gender']) && !empty($_POST['birth-place']) && !empty($_POST['birth-date']) && !empty($_POST['address']) && !empty($_POST['phone-number']) && !empty($_POST['email'])) {
        $fullName = $_POST['full-name'];
        $gender = $_POST['gender'];
        $birthPlace = $_POST['birth-place'];
        $birthDate = $_POST['birth-date'];
        $address = $_POST['address'];
        $phoneNumber = $_POST['phone-number'];
        $email = $_POST['email'];
        $hobbies = isset($_POST['hobbies']) ? $_POST['hobbies'] : 'Tidak ada';
        $aboutMe = isset($_POST['about-me']) ? $_POST['about-me'] : 'Tidak ada';

        // Penanganan upload gambar
        if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] == 0) {
            $uploadFile = $uploadDir . basename($_FILES['profile-pic']['name']);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['profile-pic']['tmp_name']);
            
            if (in_array($fileType, $allowedTypes)) {
                if ($_FILES['profile-pic']['size'] <= 2 * 1024 * 1024) { // Maksimal ukuran 2MB
                    if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], $uploadFile)) {
                        $profilePicPath = $uploadFile;
                    } else {
                        echo "Terjadi kesalahan saat mengunggah file.";
                        exit();
                    }
                } else {
                    echo "Ukuran file terlalu besar. Maksimum 2MB.";
                    exit();
                }
            } else {
                echo "Jenis file tidak valid. Hanya gambar yang diizinkan (JPEG, PNG, GIF).";
                exit();
            }
        } else {
            // Jika tidak ada gambar diunggah, gunakan default avatar
            $profilePicPath = 'assets/user.svg';
        }

        // Menentukan warna background berdasarkan gender
        if ($gender == 'pria') {
            $backgroundColor = 'blue';
            $fontColor = 'black';
        } else {
            $backgroundColor = 'red';
            $fontColor = 'white';
        }
    } else {
        echo "Beberapa data masih kosong, mohon lengkapi semua field yang dibutuhkan.";
        exit();
    }
} else {
    echo "Invalid Request - Form harus dikirim menggunakan metode POST.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Registrasi</title>
    <style>
        /* Styling untuk kartu hasil registrasi */
        .registration-card {
            width: 300px;
            padding: 20px;
            border-radius: 10px;
            margin: 50px auto;
            background-color: <?php echo $backgroundColor; ?>;
            color: <?php echo $fontColor; ?>;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .registration-card h2 {
            margin-top: 0;
        }

        .registration-card p {
            margin: 5px 0;
        }

        .profile-pic img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover; /* Agar gambar tetap proporsional */
        }
    </style>
</head>
<body>

<div class="registration-card">
    <div class="profile-pic">
        <img src="<?php echo $profilePicPath; ?>" alt="Foto Profil" width="100" height="100">
    </div>
    <h2><?php echo htmlspecialchars($fullName); ?></h2>
    <p><strong>Jenis Kelamin:</strong> <?php echo ucfirst($gender); ?></p>
    <p><strong>Tempat Lahir:</strong> <?php echo htmlspecialchars($birthPlace); ?></p>
    <p><strong>Tanggal Lahir:</strong> <?php echo htmlspecialchars($birthDate); ?></p>
    <p><strong>Alamat:</strong> <?php echo htmlspecialchars($address); ?></p>
    <p><strong>No HP:</strong> <?php echo htmlspecialchars($phoneNumber); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    <p><strong>Hobi:</strong> <?php echo htmlspecialchars($hobbies); ?></p>
    <p><strong>Tentang Saya:</strong> <?php echo htmlspecialchars($aboutMe); ?></p>
</div>

</body>
</html>
