<?php
// Header CORS wajib agar aplikasi Flutter (terutama web/sandbox) dapat mengakses API
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Content-Type: application/json');

include 'koneksi.php'; // Memuat koneksi database Anda

// Menangkap parameter iduser dari aplikasi Flutter
$iduser = $_GET['iduser'] ?? '';

// DETEKSI OTOMATIS: Memeriksa apakah nama tabel pengguna di database Anda 'user' atau 'users'
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

// 1. MENGHITUNG STATISTIK GLOBAL (Total Seluruh Visi & Pengguna)
$query_total_visi = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM motivasi");
$res_total_visi = mysqli_fetch_assoc($query_total_visi);
$total_visi_global = $res_total_visi['total'] ?? 0;

$query_total_user = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM `$table_user`");
$res_total_user = mysqli_fetch_assoc($query_total_user);
$total_user_global = $res_total_user['total'] ?? 0;

// 2. MENGHITUNG STATISTIK PERSONAL USER (Jika iduser dikirim)
$total_visi_personal = 0;
if (!empty($iduser)) {
    $iduser_clean = mysqli_real_escape_string($koneksi, $iduser);
    $query_personal = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM motivasi WHERE iduser = '$iduser_clean'");
    $res_personal = mysqli_fetch_assoc($query_personal);
    $total_visi_personal = $res_personal['total'] ?? 0;
}

// 3. MENGHITUNG STATISTIK KATEGORI TERPOPULER
$query_kategori = "SELECT k.nama_kategori, COUNT(m.id) as total_kontribusi 
                  FROM kategori k 
                  LEFT JOIN motivasi m ON k.id_kategori = m.id_kategori 
                  GROUP BY k.id_kategori 
                  ORDER BY total_kontribusi DESC";
$result_kategori = mysqli_query($koneksi, $query_kategori);
$kategori_stats = [];
if ($result_kategori) {
    while ($row = mysqli_fetch_assoc($result_kategori)) {
        $kategori_stats[] = [
            "kategori" => $row['nama_kategori'] ?? 'Umum',
            "total" => (int)$row['total_kontribusi']
        ];
    }
}

// 4. MENGHITUNG LEADERBOARD (Top 5 Pengguna Paling Aktif Berkontribusi Visi)
$query_leaderboard = "SELECT u.nama, u.profesi, COUNT(m.id) as total_visi 
                      FROM `$table_user` u 
                      INNER JOIN motivasi m ON u.iduser = m.iduser 
                      GROUP BY u.iduser 
                      ORDER BY total_visi DESC 
                      LIMIT 5";
$result_leaderboard = mysqli_query($koneksi, $query_leaderboard);
$leaderboard = [];
if ($result_leaderboard) {
    while ($row = mysqli_fetch_assoc($result_leaderboard)) {
        $leaderboard[] = [
            "nama" => $row['nama'],
            "profesi" => $row['profesi'] ?? 'Generasi Indonesia',
            "total_visi" => (int)$row['total_visi']
        ];
    }
}

// MENGIRIMKAN SEMUA AGREGASI DATA KE FLUTTER DALAM SATU JALUR API YANG EFISIEN
echo json_encode([
    "status" => true,
    "personal" => [
        "total_visi" => (int)$total_visi_personal
    ],
    "global" => [
        "total_visi" => (int)$total_visi_global,
        "total_user" => (int)$total_user_global
    ],
    "kategori_stats" => $kategori_stats,
    "leaderboard" => $leaderboard
]);
?>