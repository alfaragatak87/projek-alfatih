<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    die("Akses ditolak. Silakan login sebagai admin.");
}

require_once '../config/koneksi.php';

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

// --- LOGIKA TAMBAH PROYEK ---
if ($aksi == 'tambah') {
    $judul = htmlspecialchars($_POST['judul']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $link_proyek = filter_var($_POST['link_proyek'], FILTER_SANITIZE_URL);
    
    // Logika upload gambar
    $nama_file_unik = null;
    if (isset($_FILES['gambar_proyek']) && $_FILES['gambar_proyek']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['gambar_proyek'];
        if ($file['size'] <= 2 * 1024 * 1024) { // Maks 2MB
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                // Buat folder jika belum ada
                if (!is_dir('../uploads/proyek/')) {
                    mkdir('../uploads/proyek/', 0777, true);
                }
                $nama_file_unik = 'proyek_' . uniqid() . '.' . $ext;
                $path_tujuan = '../uploads/proyek/' . $nama_file_unik;
                move_uploaded_file($file['tmp_name'], $path_tujuan);
            }
        }
    }

    $stmt = mysqli_prepare($koneksi, "INSERT INTO proyek (judul, kategori, deskripsi, link_proyek, gambar_proyek) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sssss', $judul, $kategori, $deskripsi, $link_proyek, $nama_file_unik);
    
    if(mysqli_stmt_execute($stmt)){
        header("Location: kelola_proyek.php?status=sukses_tambah");
    } else {
        header("Location: kelola_proyek.php?status=gagal");
    }
    exit;
}

// --- LOGIKA EDIT PROYEK ---
elseif ($aksi == 'edit') {
    $id_proyek = (int)$_GET['id'];
    $judul = htmlspecialchars($_POST['judul']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $link_proyek = filter_var($_POST['link_proyek'], FILTER_SANITIZE_URL);

    // Ambil gambar lama
    $stmt_old = mysqli_prepare($koneksi, "SELECT gambar_proyek FROM proyek WHERE id = ?");
    mysqli_stmt_bind_param($stmt_old, 'i', $id_proyek);
    mysqli_stmt_execute($stmt_old);
    $nama_file_unik = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_old))['gambar_proyek'];

    // Jika ada gambar baru diupload
    if (isset($_FILES['gambar_proyek']) && $_FILES['gambar_proyek']['error'] === UPLOAD_ERR_OK) {
        // Hapus gambar lama
        if (!empty($nama_file_unik) && file_exists('../uploads/proyek/' . $nama_file_unik)) {
            unlink('../uploads/proyek/' . $nama_file_unik);
        }
        
        $file = $_FILES['gambar_proyek'];
        // Proses upload sama seperti di atas
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $nama_file_unik = 'proyek_' . uniqid() . '.' . $ext;
        $path_tujuan = '../uploads/proyek/' . $nama_file_unik;
        move_uploaded_file($file['tmp_name'], $path_tujuan);
    }

    $stmt = mysqli_prepare($koneksi, "UPDATE proyek SET judul=?, kategori=?, deskripsi=?, link_proyek=?, gambar_proyek=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'sssssi', $judul, $kategori, $deskripsi, $link_proyek, $nama_file_unik, $id_proyek);

    if(mysqli_stmt_execute($stmt)){
        header("Location: kelola_proyek.php?status=sukses_edit");
    } else {
        header("Location: kelola_proyek.php?status=gagal");
    }
    exit;
}

// --- LOGIKA HAPUS PROYEK ---
elseif ($aksi == 'hapus') {
    $id_proyek = (int)$_GET['id'];

    // Ambil nama file gambar untuk dihapus dari server
    $stmt_get = mysqli_prepare($koneksi, "SELECT gambar_proyek FROM proyek WHERE id = ?");
    mysqli_stmt_bind_param($stmt_get, 'i', $id_proyek);
    mysqli_stmt_execute($stmt_get);
    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_get));

    if ($result && !empty($result['gambar_proyek'])) {
        $path_gambar = '../uploads/proyek/' . $result['gambar_proyek'];
        if (file_exists($path_gambar)) {
            unlink($path_gambar);
        }
    }

    // Hapus data dari database
    $stmt_del = mysqli_prepare($koneksi, "DELETE FROM proyek WHERE id = ?");
    mysqli_stmt_bind_param($stmt_del, 'i', $id_proyek);
    
    if(mysqli_stmt_execute($stmt_del)){
        header("Location: kelola_proyek.php?status=sukses_hapus");
    } else {
        header("Location: kelola_proyek.php?status=gagal");
    }
    exit;
}

// Jika tidak ada aksi yang cocok
header("Location: dashboard.php");
exit;
?>