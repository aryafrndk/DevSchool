<?php
session_start();
include 'config.php';

$user_id = $_SESSION['id']; 

$full_name = $_POST['full-name'];
$gender = $_POST['gender'];
$birth_place = $_POST['birth-place'];
$birth_date = $_POST['birth-date'];
$address = $_POST['address'];
$phone_number = $_POST['phone-number'];
$email = $_POST['email'];
$hobbies = $_POST['hobbies'];
$about_me = $_POST['about-me'];

$profile_pic = '';
if (!empty($_FILES['foto']['name'])) {
    $profile_pic = 'uploads/' . basename($_FILES['foto']['name']);
    move_uploaded_file($_FILES['foto']['tmp_name'], $profile_pic);
}

$query = "UPDATE user SET nama = ?, gender = ?, birth_place = ?, birth_date = ?, address = ?, no_telp = ?, email = ?, hobbies = ?, about_me = ?, foto = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssssssssi", $full_name, $gender, $birth_place, $birth_date, $address, $phone_number, $email, $hobbies, $about_me, $profile_pic, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Profil berhasil diperbarui!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Tidak ada perubahan yang dilakukan.']);
}

$stmt->close();
$conn->close();
?>
