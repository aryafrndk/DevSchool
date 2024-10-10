<?php
include 'config.php';

$query = "
    SELECT 
        berita.id_berita, 
        berita.judul_berita, 
        berita.isi_berita, 
        berita.tanggal_publikasi, 
        berita.foto_berita, 
        kategori.nama_kategori 
    FROM 
        berita 
    JOIN 
        kategori ON berita.id_kategori = kategori.id_kategori";
        
$result = $koneksi->query($query); 

$berita = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) { 
        $berita[] = [
            'id_berita' => $row['id_berita'],
            'judul_berita' => $row['judul_berita'],
            'isi_berita' => substr($row['isi_berita'], 0, 100) . '...',
            'tanggal_publikasi' => $row['tanggal_publikasi'],
            'foto_berita' => $row['foto_berita'],
            'nama_kategori' => $row['nama_kategori']
        ];
    }
}

echo json_encode($berita); 
?>
