<?php
header("Content-Type: application/json");
include 'koneksi.php';

$nama = $_POST['nama'] ?? '';
$profesi = $_POST['profesi'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$pertanyaan = $_POST['pertanyaan_keamanan'] ?? '';
$jawaban = $_POST['jawaban_keamanan'] ?? '';

// Validasi agar tidak ada data kosong yang masuk
if (empty($nama) || empty($profesi) || empty($email) || empty($password) || empty($pertanyaan) || empty($jawaban)) {
    echo json_encode(["status" => false, "message" => "Semua kolom wajib diisi!"]);
    exit;
}

// Proteksi input dari SQL Injection
$nama = mysqli_real_escape_string($koneksi, $nama);
$profesi = mysqli_real_escape_string($koneksi, $profesi);
$email = mysqli_real_escape_string($koneksi, $email);
$password = mysqli_real_escape_string($koneksi, $password);
$pertanyaan = mysqli_real_escape_string($koneksi, $pertanyaan);
$jawaban = mysqli_real_escape_string($koneksi, $jawaban);

// Cek apakah email sudah pernah didaftarkan sebelumnya
$cek_email = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '$email'");
if (mysqli_num_rows($cek_email) > 0) {
    echo json_encode(["status" => false, "message" => "Email sudah terdaftar!"]);
    exit;
}

// Query insert data baru termasuk pertanyaan dan jawaban keamanan kustom
$query = "INSERT INTO user (nama, profesi, email, password, pertanyaan_keamanan, jawaban_keamanan) 
          VALUES ('$nama', '$profesi', '$email', '$password', '$pertanyaan', '$jawaban')";

if (mysqli_query($koneksi, $query)) {
    echo json_encode(["status" => true, "message" => "Registrasi berhasil!"]);
} else {
    echo json_encode(["status" => false, "message" => "Gagal menyimpan data ke database."]);
}
?>