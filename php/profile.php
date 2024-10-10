<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$uploadDir = 'uploads/';
$defaultImagePath = 'assets/user.svg';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save'])) {
        if (!empty($_POST['full-name']) && !empty($_POST['gender']) && !empty($_POST['birth-place']) && !empty($_POST['birth-date']) && !empty($_POST['address']) && !empty($_POST['phone-number']) && !empty($_POST['email'])) {
            $fullName = htmlspecialchars($_POST['full-name']);
            $gender = htmlspecialchars($_POST['gender']);
            if ($gender == 'pria') {
                $backgroundColor = 'blue';
                $fontColor = 'black';
            } elseif ($gender == 'wanita') {
                $backgroundColor = 'red';
                $fontColor = 'white';
            } else {
                $backgroundColor = 'white';
                $fontColor = 'black';
            }
            $birthPlace = htmlspecialchars($_POST['birth-place']);
            $birthDate = htmlspecialchars($_POST['birth-date']);
            $address = htmlspecialchars($_POST['address']);
            $phoneNumber = htmlspecialchars($_POST['phone-number']);
            $email = htmlspecialchars($_POST['email']);
            $hobbies = isset($_POST['hobbies']) ? htmlspecialchars($_POST['hobbies']) : 'Tidak ada';
            $aboutMe = isset($_POST['about-me']) ? htmlspecialchars($_POST['about-me']) : 'Tidak ada';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['success'] = false;
                $response['message'] = "Format email tidak valid.";
                echo json_encode($response);
                exit();
            } 

            // Proses unggahan gambar
            if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] == 0) {
                $fileName = $_FILES['profile-pic']['name'];
                $fileTmpName = $_FILES['profile-pic']['tmp_name'];
                $fileSize = $_FILES['profile-pic']['size'];
                $fileType = $_FILES['profile-pic']['type'];
                $fileError = $_FILES['profile-pic']['error'];

                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if ($fileSize <= 2097152 && in_array($fileExt, $allowedExtensions)) {
                    $newFileName = uniqid('profile_', true) . '.' . $fileExt;
                    $profilePicPath = $uploadDir . $newFileName;

                    if (!move_uploaded_file($fileTmpName, $profilePicPath)) {
                        $response['success'] = false;
                        $response['message'] = "Terjadi kesalahan saat mengunggah file.";
                        echo json_encode($response);
                        exit();
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = "File tidak valid atau terlalu besar.";
                    echo json_encode($response);
                    exit();
                }
            } else {
                $profilePicPath = $defaultImagePath;
            }

            $response['success'] = true;
            $response['message'] = "Data telah disimpan!";      
            if ($response['success']) {
                $response['backgroundColor'] = $backgroundColor; 
                $response['fontColor'] = $fontColor; 
            }      
        } else {
            $response['success'] = false;
            $response['message'] = "Beberapa data masih kosong, mohon lengkapi semua field yang dibutuhkan.";
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();

error_log("Gender: $gender");
error_log("Background Color: $backgroundColor");
error_log("Font Color: $fontColor");
error_log("Response: " . json_encode($response));
?>
