<?php
include 'koneksi.php';

$query = "SELECT * FROM kategori";
$exec = mysqli_query($koneksi, $query);

$result = [];
while ($row = mysqli_fetch_assoc($exec)) {
    $result[] = $row;
}

echo json_encode($result);
?>