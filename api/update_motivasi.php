<?php
include 'koneksi.php';
$id = $_POST['id'] ?? '';
$isi = $_POST['isi_motivasi'] ?? '';
$id_kat = $_POST['id_kategori'] ?? '1';
$tgl = date("Y-m-d");

$query = "UPDATE motivasi SET isi_motivasi='$isi', id_kategori='$id_kat', tanggal_update='$tgl' WHERE id='$id'";
if (mysqli_query($koneksi, $query)) {
    echo json_encode(["status" => true, "message" => "Berhasil diupdate"]);
} else {
    echo json_encode(["status" => false, "message" => mysqli_error($koneksi)]);
}
?>