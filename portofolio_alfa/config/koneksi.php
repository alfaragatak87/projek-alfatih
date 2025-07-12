<?php
// --- KODE KONEKSI DARI KAMU ---
$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "portofolio_alfa";
$port = 3307; // Port non-standar, penting untuk disertakan

// Buat koneksi menggunakan MySQLi
$koneksi = mysqli_connect($host, $user, $password, $database, $port);

// Cek koneksi
if (!$koneksi) {
    die("KONEKSI GAGAL: " . mysqli_connect_error());
}

// Definisikan BASE_URL untuk path yang konsisten di seluruh aplikasi
// PENTING: Sesuaikan ini dengan URL akses proyek Anda di browser!
// Contoh: Jika Anda mengaksesnya via http://localhost:8080/portofolio_alfa/
$base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/portofolio_alfa";
define('BASE_URL', $base_url);
// Jika Anda mengaksesnya via http://localhost/portofolio_alfa/
// define('BASE_URL', 'http://localhost/portofolio_alfa');
?>
