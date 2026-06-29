<?php
// Header CORS wajib agar FlutLab Web Sandbox dapat mengakses Apache localhost Anda
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Content-Type: application/json');

include 'koneksi.php'; // Memuat koneksi database Anda

// Menangkap parameter iduser jika dikirimkan oleh Flutter
$iduser = $_GET['iduser'] ?? '';

// DETEKSI OTOMATIS: Memeriksa apakah nama tabel di database Anda 'user' atau 'users'
$table_user = 'user'; // Default fallback
$check_users = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
if ($check_users && mysqli_num_rows($check_users) > 0) {
    $table_user = 'users';
} else {
    $check_user = mysqli_query($koneksi, "SHOW TABLES LIKE 'user'");
    if ($check_user && mysqli_num_rows($check_user) > 0) {
        $table_user = 'user';
    }
}

// DETEKSI KOLOM: Memastikan kolom iduser ada di tabel user tersebut sebelum melakukan JOIN
$has_user_join = false;
if (!empty($table_user)) {
    $check_columns = mysqli_query($koneksi, "SHOW COLUMNS FROM `$table_user` LIKE 'iduser'");
    if ($check_columns && mysqli_num_rows($check_columns) > 0) {
        $has_user_join = true;
    }
}

// MENYUSUN QUERY DINAMIS BERDASARKAN HASIL DETEKSI DATABASE
if ($has_user_join) {
    if (!empty($iduser)) {
        // Query untuk Visi Saya (dengan filter iduser)
        $iduser = mysqli_real_escape_string($koneksi, $iduser);
        // REVISI: Ditambahkan u.nama AS nama agar sinkron dengan Android tanpa merusak nama_pembuat versi Web
        $query = "SELECT m.*, k.nama_kategori, u.nama AS nama, u.nama AS nama_pembuat 
                  FROM motivasi m 
                  LEFT JOIN kategori k ON m.id_kategori = k.id_kategori 
                  LEFT JOIN `$table_user` u ON m.iduser = u.iduser
                  WHERE m.iduser = '$iduser' 
                  ORDER BY m.id DESC";
    } else {
        // Query untuk Explore / Jelajah (tanpa filter iduser untuk menampilkan semua data)
        // REVISI: Ditambahkan u.nama AS nama agar sinkron dengan Android tanpa merusak nama_pembuat versi Web
        $query = "SELECT m.*, k.nama_kategori, u.nama AS nama, u.nama AS nama_pembuat 
                  FROM motivasi m 
                  LEFT JOIN kategori k ON m.id_kategori = k.id_kategori 
                  LEFT JOIN `$table_user` u ON m.iduser = u.iduser
                  ORDER BY m.id DESC";
    }
} else {
    // Jalankan query fallback tanpa JOIN jika tabel user tidak terdeteksi agar program tidak crash
    if (!empty($iduser)) {
        $iduser = mysqli_real_escape_string($koneksi, $iduser);
        $query = "SELECT m.*, k.nama_kategori 
                  FROM motivasi m 
                  LEFT JOIN kategori k ON m.id_kategori = k.id_kategori 
                  WHERE m.iduser = '$iduser' 
                  ORDER BY m.id DESC";
    } else {
        $query = "SELECT m.*, k.nama_kategori 
                  FROM motivasi m 
                  LEFT JOIN kategori k ON m.id_kategori = k.id_kategori 
                  ORDER BY m.id DESC";
    }
}

$result = mysqli_query($koneksi, $query);
$data = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Jika tidak ada data JOIN user, set nama_pembuat & nama ke default secara aman
        if (!isset($row['nama_pembuat'])) {
            $row['nama_pembuat'] = 'Pengguna ViGeNesia';
        }
        if (!isset($row['nama'])) {
            $row['nama'] = 'Pengguna ViGeNesia';
        }
        $data[] = $row;
    }
    // Mengembalikan data JSON yang bersih dan valid
    echo json_encode($data);
} else {
    // Jika masih ada kesalahan SQL, tampilkan pesan error detail untuk mempermudah debug
    echo json_encode([
        "status" => false, 
        "message" => "SQL Error: " . mysqli_error($koneksi)
    ]);
}
?>