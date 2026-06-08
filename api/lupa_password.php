<?php
header("Content-Type: application/json");
include 'koneksi.php';

$email = $_POST['email'] ?? '';
$pertanyaan = $_POST['pertanyaan_keamanan'] ?? '';
$jawaban = $_POST['jawaban_keamanan'] ?? '';
$password_baru = $_POST['password_baru'] ?? '';

if (empty($email) || empty($pertanyaan) || empty($jawaban) || empty($password_baru)) {
    echo json_encode(["status" => false, "message" => "Semua kolom wajib diisi!"]);
    exit;
}

// Proteksi string input untuk query database
$email = mysqli_real_escape_string($koneksi, $email);
$pertanyaan = mysqli_real_escape_string($koneksi, $pertanyaan);
$jawaban = mysqli_real_escape_string($koneksi, $jawaban);
$password_baru = mysqli_real_escape_string($koneksi, $password_baru);

// Amankan pengecekan dengan mencocokkan 3 faktor data keamanan sekaligus
$query_cek = "SELECT * FROM user WHERE email = '$email' AND pertanyaan_keamanan = '$pertanyaan' AND jawaban_keamanan = '$jawaban'";
$result_cek = mysqli_query($koneksi, $query_cek);

if (mysqli_num_rows($result_cek) > 0) {
    $query_update = "UPDATE user SET password = '$password_baru' WHERE email = '$email'";
    if (mysqli_query($koneksi, $query_update)) {
        echo json_encode(["status" => true, "message" => "Password berhasil diperbarui!"]);
    } else {
        echo json_encode(["status" => false, "message" => "Gagal memperbarui kata sandi baru."]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Kombinasi data pemulihan tidak cocok!"]);
}
?>