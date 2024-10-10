<?php
include 'config.php'; 

$query = "SELECT * FROM program";
$result = mysqli_query($koneksi, $query);

$programs = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) { 
        $programs[] = [
            'nama_program' => $row['nama_program'],
            'deskripsi' => $row['deskripsi'],
            'image_url' => $row['image_url'],  
            'alt_text' => $row['alt_text'],  
            'id_program' => $row['id_program'],  
            'button_text' => $row['button_text'],  
        ];
    }
}

echo json_encode(['programs' => $programs]);

mysqli_close($koneksi);
?>
