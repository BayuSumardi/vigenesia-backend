<?php
include 'koneksi.php';
$isi = $_POST['isi_motivasi'] ?? '';
$iduser = $_POST['iduser'] ?? '';
$id_kat = $_POST['id_kategori'] ?? '1'; // Default ke kategori 1 jika tidak dikirim
$tgl = date("Y-m-d");

$query = "INSERT INTO motivasi (isi_motivasi, iduser, id_kategori, tanggal_input, tanggal_update) VALUES ('$isi', '$iduser', '$id_kat', '$tgl', '$tgl')";
if (mysqli_query($koneksi, $query)) {
    echo json_encode(["status" => true, "message" => "Berhasil"]);
} else {
    echo json_encode(["status" => false, "message" => mysqli_error($koneksi)]);
}
?>