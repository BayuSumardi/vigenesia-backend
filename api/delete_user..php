<?php
include 'koneksi.php';

$iduser = isset($_POST['iduser']) ? $_POST['iduser'] : '';

if (empty($iduser)) {
    echo json_encode([
        "status" => false, 
        "message" => "ID User tidak ditemukan"
    ]);
    exit();
}

// Langkah 1: Hapus semua motivasi milik user tersebut terlebih dahulu
$query_motivasi = "DELETE FROM motivasi WHERE iduser = '$iduser'";
mysqli_query($koneksi, $query_motivasi);

// Langkah 2: Baru hapus data user utama
$query_user = "DELETE FROM user WHERE iduser = '$iduser'";
$exec_user = mysqli_query($koneksi, $query_user);

if ($exec_user) {
    echo json_encode([
        "status" => true, 
        "message" => "Akun dan seluruh data motivasi berhasil dihapus permanen"
    ]);
} else {
    echo json_encode([
        "status" => false, 
        "message" => "Gagal menghapus akun: " . mysqli_error($koneksi)
    ]);
}
?>