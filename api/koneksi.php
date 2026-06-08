<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Konfigurasi menggunakan Environment Variables dari Railway
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    echo json_encode(["status" => false, "message" => "Database Connection Failed: " . mysqli_connect_error()]);
    exit();
}
?>