<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Origin, Accept");

// Pastikan untuk menangani OPTIONS preflight dengan benar
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");
// Konfigurasi menggunakan Environment Variables dari Railway
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$port = getenv('MYSQLPORT');

// Ganti 'railway' di bawah ini dengan nama database kamu yang ada di DBeaver
$db   = getenv('MYSQLDATABASE') ?: 'railway';

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    echo json_encode(["status" => false, "message" => "Database Connection Failed: " . mysqli_connect_error()]);
    exit();
}
?>