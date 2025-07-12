<?php
session_start();

// Keamanan utama: Pastikan hanya admin yang login bisa mengakses file ini
if (!isset($_SESSION['admin_logged_in'])) {
    // Jika tidak ada sesi, langsung hentikan eksekusi
    die("Akses ditolak. Silakan login sebagai admin.");
}

require_once '../config/koneksi.php';

// Ambil aksi dari URL (tambah, edit, atau hapus)
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

// Fungsi untuk membuat SLUG yang unik dan SEO-friendly
function buatSlug($judul, $koneksi) {
    $slug = strtolower($judul);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug); // Hapus karakter selain huruf, angka, spasi, dan strip
    $slug = preg_replace('/[\s-]+/', '-', $slug); // Ganti spasi dan strip berlebih dengan satu strip
    $slug = trim($slug, '-'); // Hapus strip di awal dan akhir

    // Cek ke database apakah slug sudah ada
    $query = "SELECT id FROM artikel WHERE slug = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $slug);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Jika slug sudah ada, tambahkan angka di belakangnya
    if (mysqli_num_rows($result) > 0) {
        $i = 1;
        $original_slug = $slug;
        do {
            $slug = $original_slug . '-' . $i;
            $i++;
            $stmt_check = mysqli_prepare($koneksi, "SELECT id FROM artikel WHERE slug = ?");
            mysqli_stmt_bind_param($stmt_check, "s", $slug);
            mysqli_stmt_execute($stmt_check);
        } while (mysqli_num_rows(mysqli_stmt_get_result($stmt_check)) > 0);
    }
    
    return $slug;
}

// ======================================================
// === LOGIKA UNTUK MENAMBAH ARTIKEL BARU (CREATE) ====
// ======================================================
if ($aksi == 'tambah') {
    $judul = htmlspecialchars($_POST['judul']);
    // Untuk konten dari TinyMCE, kita butuh penanganan khusus, tapi untuk sekarang kita percayakan input admin
    $konten = $_POST['konten']; 
    $id_kategori = (int)$_POST['id_kategori'];
    $id_admin = (int)$_SESSION['admin_id']; // Ambil ID admin dari sesi

    // Buat slug dari judul
    $slug = buatSlug($judul, $koneksi);

    // Logika upload gambar
    $nama_file_unik = null;
    if (isset($_FILES['gambar_unggulan']) && $_FILES['gambar_unggulan']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['gambar_unggulan'];
        $nama_file_asli = $file['name'];
        $lokasi_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext) && $ukuran_file <= 2 * 1024 * 1024) { // Maks 2MB
            $nama_file_unik = uniqid('artikel_') . '.' . $file_ext;
            $path_tujuan = '../uploads/artikel/' . $nama_file_unik;
            move_uploaded_file($lokasi_tmp, $path_tujuan);
        }
    }

    // Masukkan data ke database menggunakan prepared statement
    $query = "INSERT INTO artikel (judul, slug, konten, id_kategori, id_admin, gambar_unggulan) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'sssiis', $judul, $slug, $konten, $id_kategori, $id_admin, $nama_file_unik);
    
    if(mysqli_stmt_execute($stmt)){
        // Jika berhasil, kembali ke halaman kelola artikel
        header("Location: kelola_artikel.php?status=sukses_tambah");
    } else {
        // Jika gagal
        header("Location: kelola_artikel.php?status=gagal");
    }
    exit;
}

// ======================================================
// === LOGIKA UNTUK MENGEDIT ARTIKEL (UPDATE) =========
// ======================================================
elseif ($aksi == 'edit') {
    $id_artikel = (int)$_GET['id'];
    $judul = htmlspecialchars($_POST['judul']);
    $konten = $_POST['konten'];
    $id_kategori = (int)$_POST['id_kategori'];
    
    // Ambil data lama untuk cek slug dan gambar
    $query_get_old = "SELECT slug, gambar_unggulan FROM artikel WHERE id = ?";
    $stmt_get_old = mysqli_prepare($koneksi, $query_get_old);
    mysqli_stmt_bind_param($stmt_get_old, 'i', $id_artikel);
    mysqli_stmt_execute($stmt_get_old);
    $old_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_get_old));
    
    // Buat slug baru. Cek jika judul berubah.
    $slug = $old_data['slug']; 
    // (Untuk simplifikasi, kita tidak ubah slug setelah dibuat. Mengubah slug bisa berdampak buruk pada SEO)
    
    // Logika upload gambar baru
    $nama_file_unik = $old_data['gambar_unggulan']; // Default pakai gambar lama
    if (isset($_FILES['gambar_unggulan']) && $_FILES['gambar_unggulan']['error'] === UPLOAD_ERR_OK) {
        // Hapus gambar lama jika ada gambar baru yang diupload
        if (!empty($nama_file_unik) && file_exists('../uploads/artikel/' . $nama_file_unik)) {
            unlink('../uploads/artikel/' . $nama_file_unik);
        }

        $file = $_FILES['gambar_unggulan'];
        $nama_file_asli = $file['name'];
        $lokasi_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext) && $ukuran_file <= 2 * 1024 * 1024) {
            $nama_file_unik = uniqid('artikel_') . '.' . $file_ext;
            $path_tujuan = '../uploads/artikel/' . $nama_file_unik;
            move_uploaded_file($lokasi_tmp, $path_tujuan);
        }
    }

    // Update data di database
    $query = "UPDATE artikel SET judul = ?, konten = ?, id_kategori = ?, gambar_unggulan = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'ssisi', $judul, $konten, $id_kategori, $nama_file_unik, $id_artikel);

    if(mysqli_stmt_execute($stmt)){
        header("Location: kelola_artikel.php?status=sukses_edit");
    } else {
        header("Location: kelola_artikel.php?status=gagal");
    }
    exit;
}

// ======================================================
// === LOGIKA UNTUK MENGHAPUS ARTIKEL (DELETE) ========
// ======================================================
elseif ($aksi == 'hapus') {
    $id_artikel = (int)$_GET['id'];

    // 1. Ambil nama file gambar dari database sebelum dihapus
    $query_get_gambar = "SELECT gambar_unggulan FROM artikel WHERE id = ?";
    $stmt_get_gambar = mysqli_prepare($koneksi, $query_get_gambar);
    mysqli_stmt_bind_param($stmt_get_gambar, 'i', $id_artikel);
    mysqli_stmt_execute($stmt_get_gambar);
    $result_gambar = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_get_gambar));

    // 2. Hapus file gambar dari server jika ada
    if ($result_gambar && !empty($result_gambar['gambar_unggulan'])) {
        $path_gambar = '../uploads/artikel/' . $result_gambar['gambar_unggulan'];
        if (file_exists($path_gambar)) {
            unlink($path_gambar);
        }
    }
    
    // 3. Hapus data artikel dari database
    $query = "DELETE FROM artikel WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id_artikel);

    if(mysqli_stmt_execute($stmt)){
        header("Location: kelola_artikel.php?status=sukses_hapus");
    } else {
        header("Location: kelola_artikel.php?status=gagal");
    }
    exit;
}

// Jika tidak ada aksi yang cocok, kembali ke halaman utama admin
header("Location: dashboard.php");
exit;
?>