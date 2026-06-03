<?php
include 'koneksi.php';

$desc = $_POST['desc'] ?? '';

if (!empty($desc)) {
    $query = "INSERT INTO report_apk (desc) VALUES ('$desc')";
    if (mysqli_query($koneksi, $query)) {
        echo json_encode(["status" => true, "message" => "Terima kasih atas masukannya!"]);
    } else {
        echo json_encode(["status" => false, "message" => "Gagal mengirim laporan"]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Deskripsi tidak boleh kosong"]);
}
?>