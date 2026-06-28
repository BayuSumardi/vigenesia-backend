<?php
include 'koneksi.php';

// Mengambil parameter iduser dari kiriman Flutter/Android
$iduser = $_GET['iduser'] ?? '';

if (empty($iduser)) {
    echo json_encode(["status" => false, "message" => "ID User tidak boleh kosong!"]);
    exit();
}

// Ambil data nama dan foto_profil dari database cloud
$query = "SELECT nama, foto_profil FROM user WHERE iduser = '$iduser'";
$result = mysqli_query($koneksi, $query);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode([
        "status" => true,
        "nama" => $row['nama'],
        "foto_profil" => $row['foto_profil'] // Memuntahkan string Base64 ke aplikasi
    ]);
} else {
    echo json_encode(["status" => false, "message" => "User tidak ditemukan!"]);
}
?>gi