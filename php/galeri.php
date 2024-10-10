<?php

include 'config.php';

$query = "SELECT * FROM galeri";
$result = $koneksi->query($query); 
$fotoList = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fotoList[] = $row;
    }
}

header('Content-Type: application/json');

echo json_encode($fotoList);

$koneksi->close(); 
?>
