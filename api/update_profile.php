<?php
include 'koneksi.php';

$iduser   = isset($_POST['iduser']) ? $_POST['iduser'] : '';
$nama     = isset($_POST['nama']) ? $_POST['nama'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($iduser) || empty($nama)) {
    echo json_encode([
        "status" => false, 
        "message" => "ID User dan Nama tidak boleh kosong"
    ]);
    exit();
}

// Jika password baru diisi, update nama DAN password
if (!empty($password)) {
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);
    $query = "UPDATE user SET nama = '$nama', password = '$password_hashed' WHERE iduser = '$iduser'";
} else {
    // Jika password kosong, hanya update nama
    $query = "UPDATE user SET nama = '$nama' WHERE iduser = '$iduser'";
}

$exec = mysqli_query($koneksi, $query);

if ($exec) {
    echo json_encode([
        "status" => true, 
        "message" => "Profil berhasil diperbarui"
    ]);
} else {
    echo json_encode([
        "status" => false, 
        "message" => "Gagal memperbarui profil: " . mysqli_error($koneksi)
    ]);
}
?>