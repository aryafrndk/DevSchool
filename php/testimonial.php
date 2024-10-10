<?php
include 'config.php';

$query = "SELECT name, role, testimonial, photo FROM testimonials";
$result = $koneksi->query($query);

$testimonials = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimonials[] = [
            'name' => $row['name'],
            'role' => $row['role'],
            'testimonial' => $row['testimonial'],
            'photo' => $row['photo']
        ];
    }
}

header('Content-Type: application/json');

echo json_encode($testimonials);

$koneksi->close();
?>
