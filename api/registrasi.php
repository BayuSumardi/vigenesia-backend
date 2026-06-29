<?php
// Matikan display errors format HTML bawaan server agar tidak merusak string JSON
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json");
include 'koneksi.php';

// Validasi gerbang koneksi awal
if (!$koneksi) {
    echo json_encode(["status" => false, "message" => "Gagal terhubung ke database server."]);
    exit;
}

$nama = $_POST['nama'] ?? '';
$profesi = $_POST['profesi'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$pertanyaan = $_POST['pertanyaan_keamanan'] ?? '';
$jawaban = $_POST['jawaban_keamanan'] ?? '';

if (empty($nama) || empty($profesi) || empty($email) || empty($password) || empty($pertanyaan) || empty($jawaban)) {
    echo json_encode(["status" => false, "message" => "Semua kolom wajib diisi!"]);
    exit;
}

$nama = mysqli_real_escape_string($koneksi, $nama);
$profesi = mysqli_real_escape_string($koneksi, $profesi);
$email = mysqli_real_escape_string($koneksi, $email);
$password = mysqli_real_escape_string($koneksi, $password);
$pertanyaan = mysqli_real_escape_string($koneksi, $pertanyaan);
$jawaban = mysqli_real_escape_string($koneksi, $jawaban);

try {
    // Cek duplikasi email
    $cek_email = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '$email'");
    if ($cek_email && mysqli_num_rows($cek_email) > 0) {
        echo json_encode(["status" => false, "message" => "Email sudah terdaftar!"]);
        exit;
    }

    // Eksekusi insert data baru
    $query = "INSERT INTO user (nama, profesi, email, password, pertanyaan_keamanan, jawaban_keamanan, role_id, tanggal_input) 
          VALUES ('$nama', '$profesi', '$email', '$password', '$pertanyaan', '$jawaban', 2, NOW())";
    if (mysqli_query($koneksi, $query)) {
        echo json_encode(["status" => true, "message" => "Registrasi berhasil!"]);
    } else {
        // Jika query gagal, muntahkan detail error MySQL ke dalam JSON agar bisa dibaca di HP
        echo json_encode(["status" => false, "message" => "SQL Error: " . mysqli_error($koneksi)]);
    }
} catch (Throwable $e) {
    // Menangkap fatal error system cloud Railway secara aman
    echo json_encode(["status" => false, "message" => "Database Exception: " . $e->getMessage()]);
}
?>