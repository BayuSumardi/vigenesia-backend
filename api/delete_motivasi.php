<?php
include 'koneksi.php';
$id = $_POST['id'] ?? '';

$query = "DELETE FROM motivasi WHERE id='$id'";
if (mysqli_query($koneksi, $query)) {
    echo json_encode(["status" => true, "message" => "Berhasil dihapus"]);
} else {
    echo json_encode(["status" => false, "message" => "Gagal"]);
}
?>