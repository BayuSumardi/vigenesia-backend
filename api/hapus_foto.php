<?php
include 'koneksi.php';
$iduser = $_POST['iduser'];

$query = "UPDATE user SET foto_profil = NULL WHERE iduser = '$iduser'";
if (mysqli_query($koneksi, $query)) {
    echo json_encode(["status" => true, "message" => "Foto berhasil dihapus"]);
} else {
    echo json_encode(["status" => false, "message" => "Gagal menghapus"]);
}
?>