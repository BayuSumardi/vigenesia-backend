<?php
include 'koneksi.php';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Tabel 'user' dan kolom 'iduser'
$query = "SELECT iduser, nama, profesi, email FROM user WHERE email='$email' AND password='$password' AND is_active=1";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(["status" => true, "data" => mysqli_fetch_assoc($result)]);
} else {
    echo json_encode(["status" => false, "message" => "Login gagal"]);
}
?>