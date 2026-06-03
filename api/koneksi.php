<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Content-Type: application/json; charset=UTF-8");

// Menghentikan request OPTIONS preflight agar tidak lanjut ke logika berikutnya
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "vigenesia";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Memberikan respon jika terjadi error koneksi database
if (!$koneksi) {
    echo json_encode(["status" => false, "message" => "Database Connection Failed: " . mysqli_connect_error()]);
    exit();
}
?>