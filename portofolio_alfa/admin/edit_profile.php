<?php
session_start();

// Pastikan admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// PENTING: Include koneksi.php di awal file admin
require_once '../config/koneksi.php';

$pesan_status = '';
$admin_id = $_SESSION['admin_id']; // Mengambil ID admin dari sesi
$current_profile_image = ''; // Untuk menyimpan path gambar profil saat ini

// Ambil data admin saat ini, termasuk gambar profil
$query_get_admin = "SELECT profile_image FROM admin WHERE id = ?";
$stmt_get_admin = mysqli_prepare($koneksi, $query_get_admin);
mysqli_stmt_bind_param($stmt_get_admin, 'i', $admin_id);
mysqli_stmt_execute($stmt_get_admin);
$result_get_admin = mysqli_stmt_get_result($stmt_get_admin);
if ($admin_data = mysqli_fetch_assoc($result_get_admin)) {
    $current_profile_image = $admin_data['profile_image'];
}
mysqli_stmt_close($stmt_get_admin);


// Logika untuk upload foto profil baru
if (isset($_POST['upload_foto'])) {
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_pic'];
        $nama_file_asli = $file['name'];
        $lokasi_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));

        // Validasi ekstensi dan ukuran file
        if (!in_array($file_ext, $allowed_ext)) {
            $pesan_status = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Format file tidak diizinkan! Hanya JPG, JPEG, PNG, GIF.</div>";
        } elseif ($ukuran_file > 3 * 1024 * 1024) { // Batas 3 MB
            $pesan_status = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Ukuran file terlalu besar! Maksimal 3 MB.</div>";
        } else {
            // Membuat nama file yang unik
            $nama_file_unik = uniqid('profile_') . '.' . $file_ext;
            $path_tujuan = '../uploads/profile_pics/' . $nama_file_unik; // Path ke folder baru

            // Pastikan folder tujuan ada dan bisa ditulis
            if (!is_dir('../uploads/profile_pics/')) {
                mkdir('../uploads/profile_pics/', 0777, true); // Buat folder jika belum ada
            }

            if (move_uploaded_file($lokasi_tmp, $path_tujuan)) {
                // Update path gambar profil di database
                $query_update_profile = "UPDATE admin SET profile_image = ? WHERE id = ?";
                $stmt_update_profile = mysqli_prepare($koneksi, $query_update_profile);
                mysqli_stmt_bind_param($stmt_update_profile, 'si', $nama_file_unik, $admin_id);

                if (mysqli_stmt_execute($stmt_update_profile)) {
                    // Hapus gambar profil lama jika ada dan bukan gambar default
                    if (!empty($current_profile_image) && $current_profile_image !== 'default-profile.png') {
                        $old_file_path = '../uploads/profile_pics/' . $current_profile_image;
                        if (file_exists($old_file_path)) {
                            unlink($old_file_path);
                        }
                    }
                    $pesan_status = "<div class='bg-green-500/20 text-green-300 p-3 rounded-md'>Foto profil berhasil diubah!</div>";
                    $current_profile_image = $nama_file_unik; // Update variabel untuk tampilan
                } else {
                    $pesan_status = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Error: Gagal menyimpan path gambar ke database.</div>";
                    unlink($path_tujuan); // Hapus file yang baru diupload jika gagal disimpan ke DB
                }
                mysqli_stmt_close($stmt_update_profile);
            } else {
                $pesan_status = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Error: Gagal memindahkan file yang di-upload. Pastikan folder 'uploads/profile_pics/' bisa ditulis.</div>";
            }
        }
    } else {
        $pesan_status = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Error: Tidak ada file yang dipilih atau terjadi kesalahan saat upload.</div>";
    }
}

// Logika untuk menghapus foto profil
if (isset($_POST['remove_foto'])) {
    // Hapus gambar profil saat ini dari server jika bukan default
    if (!empty($current_profile_image) && $current_profile_image !== 'default-profile.png') {
        $old_file_path = '../uploads/profile_pics/' . $current_profile_image;
        if (file_exists($old_file_path)) {
            unlink($old_file_path);
        }
    }

    // Update database untuk mengatur profile_image menjadi NULL atau default
    $query_remove_profile = "UPDATE admin SET profile_image = NULL WHERE id = ?"; // Atau 'default-profile.png'
    $stmt_remove_profile = mysqli_prepare($koneksi, $query_remove_profile);
    mysqli_stmt_bind_param($stmt_remove_profile, 'i', $admin_id);

    if (mysqli_stmt_execute($stmt_remove_profile)) {
        $pesan_status = "<div class='bg-green-500/20 text-green-300 p-3 rounded-md'>Foto profil berhasil dihapus!</div>";
        $current_profile_image = ''; // Reset variabel untuk tampilan
    } else {
        $pesan_status = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Error: Gagal menghapus foto profil dari database.</div>";
    }
    mysqli_stmt_close($stmt_remove_profile);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- PENTING: Link ke file CSS utama Anda menggunakan BASE_URL -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="bg-slate-900 text-slate-200">
    <div class="container mx-auto p-8 max-w-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-cyan-400">Edit Profil Admin</h1>
            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="text-sm text-cyan-400 hover:underline">&larr; Kembali ke Dashboard</a>
        </div>

        <?php if (!empty($pesan_status)) { echo "<div class='mb-4'>$pesan_status</div>"; } ?>

        <div class="glass-card p-6 space-y-4 text-center">
            <h2 class="text-xl font-semibold mb-4 text-white">Foto Profil Saat Ini</h2>
            <?php
            // Menentukan path gambar yang akan ditampilkan
            // Menggunakan BASE_URL untuk path gambar
            $display_image_path = BASE_URL . '/assets/images/foto-profil.png'; // Default
            if (!empty($current_profile_image)) {
                $check_path = __DIR__ . '/../uploads/profile_pics/' . $current_profile_image; // Path fisik untuk cek file
                if (file_exists($check_path)) {
                    $display_image_path = BASE_URL . '/uploads/profile_pics/' . $current_profile_image;
                }
            }
            ?>
            <div class="w-32 h-32 rounded-full mx-auto overflow-hidden border-2 border-cyan-400 mb-4">
                <img src="<?php echo $display_image_path; ?>" alt="Foto Profil Admin" class="w-full h-full object-cover">
            </div>

            <form action="<?= BASE_URL ?>/admin/edit_profile.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="profile_pic" class="block mb-1 text-sm font-medium text-slate-300">Pilih Foto Profil Baru</label>
                    <input type="file" name="profile_pic" id="profile_pic" class="w-full text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-cyan-500 file:text-slate-900 hover:file:bg-cyan-400" accept="image/*">
                    <p class="text-xs text-slate-500 mt-1">Tipe file: JPG, PNG, GIF. Ukuran maks: 3MB.</p>
                </div>
                <button type="submit" name="upload_foto" class="w-full btn-neon btn-neon-fill">Upload & Ganti Foto</button>
            </form>
            <?php if (!empty($current_profile_image) && $current_profile_image !== 'default-profile.png'): ?>
                <form action="<?= BASE_URL ?>/admin/edit_profile.php" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus foto profil? Ini akan kembali ke foto default.');">
                    <button type="submit" name="remove_foto" class="text-red-400 hover:underline text-sm mt-4">Hapus Foto Profil</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
