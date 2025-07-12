<?php
session_start();

// PENAMBAHAN: Panggil file koneksi untuk mendapatkan BASE_URL
require_once '../config/koneksi.php';

// Hapus semua data sesi
$_SESSION = [];
session_unset();
session_destroy();

// Redirect ke halaman login menggunakan BASE_URL yang sudah didefinisikan
header("Location: " . BASE_URL . "/admin/login.php");
exit;
?>