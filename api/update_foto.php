<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $iduser = $_POST['iduser'] ?? '';
    $foto_base64 = $_POST['foto_profil'] ?? ''; // Menerima teks Base64 dari Flutter/Android
    $foto_base64 = str_replace(' ', '+', $foto_base64); // Paksa spasi balik jadi plus kembal

    if (empty($iduser) || empty($foto_base64)) {
        echo json_encode(["status" => false, "message" => "Data tidak lengkap!"]);
        exit();
    }

    // Update string foto ke database
    $query = "UPDATE user SET foto_profil = '$foto_base64' WHERE iduser = '$iduser'";
    
    if (mysqli_query($koneksi, $query)) {
        echo json_encode(["status" => true, "message" => "Foto profil berhasil diperbarui!"]);
    } else {
        echo json_encode(["status" => false, "message" => "Gagal memperbarui database: " . mysqli_error($koneksi)]);
    }
}
?>