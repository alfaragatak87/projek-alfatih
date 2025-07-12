<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { // Gunakan session yang benar
    header("Location: login.php"); 
    exit; 
}

// PENTING: Include koneksi.php di awal file admin
require_once '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Casting ke integer untuk keamanan dasar

    // Amankan query SELECT dengan prepared statement
    $query_select = "SELECT file FROM dokumen WHERE id = ?";
    $stmt_select = mysqli_prepare($koneksi, $query_select);
    mysqli_stmt_bind_param($stmt_select, 'i', $id);
    mysqli_stmt_execute($stmt_select);
    $result_select = mysqli_stmt_get_result($stmt_select);
    
    if ($row = mysqli_fetch_assoc($result_select)) {
        $path_file = '../uploads/' . $row['file']; // Path relatif dari admin/ ke uploads/
        if (file_exists($path_file)) {
            unlink($path_file); // Hapus file dari server
        }

        // Amankan query DELETE dengan prepared statement
        $query_delete = "DELETE FROM dokumen WHERE id = ?";
        $stmt_delete = mysqli_prepare($koneksi, $query_delete);
        mysqli_stmt_bind_param($stmt_delete, 'i', $id);
        mysqli_stmt_execute($stmt_delete);
        mysqli_stmt_close($stmt_delete);
    }
    mysqli_stmt_close($stmt_select);
}

header("Location: " . BASE_URL . "/admin/dashboard.php"); // Menggunakan BASE_URL
exit;
?>
