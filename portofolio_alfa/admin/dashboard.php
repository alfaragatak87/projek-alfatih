<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php'; 

$username = $_SESSION['admin_username'];
$admin_id = $_SESSION['admin_id'];

// --- MENGAMBIL DATA UNTUK KARTU STATISTIK ---

// 1. Hitung jumlah total dokumen
$query_total_dokumen = "SELECT COUNT(id) as total FROM dokumen";
$hasil_total_dokumen = mysqli_query($koneksi, $query_total_dokumen);
$total_dokumen = mysqli_fetch_assoc($hasil_total_dokumen)['total'];

// 2. Hitung jumlah total artikel blog
$query_total_artikel = "SELECT COUNT(id) as total FROM artikel";
$hasil_total_artikel = mysqli_query($koneksi, $query_total_artikel);
$total_artikel = mysqli_fetch_assoc($hasil_total_artikel)['total'];

// 3. Ambil data dokumen terbaru untuk ditampilkan di tabel
$query_dokumen_terbaru = "SELECT id, nama, file, tanggal_upload FROM dokumen ORDER BY id DESC LIMIT 5";
$hasil_dokumen = mysqli_query($koneksi, $query_dokumen_terbaru);

// Ambil foto profil admin
$query_get_admin = "SELECT profile_image FROM admin WHERE id = ?";
$stmt_get_admin = mysqli_prepare($koneksi, $query_get_admin);
mysqli_stmt_bind_param($stmt_get_admin, 'i', $admin_id);
mysqli_stmt_execute($stmt_get_admin);
$result_get_admin = mysqli_stmt_get_result($stmt_get_admin);
$profile_image = 'default-profile.png'; // Default
if ($admin_data = mysqli_fetch_assoc($result_get_admin)) {
    if (!empty($admin_data['profile_image'])) {
        $profile_image = $admin_data['profile_image'];
    }
}
mysqli_stmt_close($stmt_get_admin);
$profile_image_path = BASE_URL . '/uploads/profile_pics/' . $profile_image;

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin - Alfatih</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        /* Custom styles untuk dashboard baru */
        .admin-wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background-color: #1a202c; /* Ganti dengan warna dari style.css jika ada */ flex-shrink: 0; }
        .main-content { flex-grow: 1; background-color: #0f172a; }
        .stat-card { background: linear-gradient(145deg, rgba(30, 41, 59, 0.5), rgba(51, 65, 85, 0.5)); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="bg-slate-900 text-slate-200">

    <div class="admin-wrapper">
        <aside class="sidebar p-6 flex flex-col justify-between">
            <div>
                <h1 class="text-2xl font-bold text-cyan-400 mb-8 border-b border-cyan-800 pb-4">Admin Panel</h1>
                <nav class="flex flex-col space-y-3">
                    <a href="<?= BASE_URL ?>/admin/dashboard.php" class="flex items-center p-3 bg-cyan-500/20 rounded-lg text-white font-bold"><i class="fas fa-tachometer-alt w-6 mr-3"></i>Dashboard</a>
                    <a href="<?= BASE_URL ?>/admin/kelola_artikel.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-newspaper w-6 mr-3"></i>Kelola Artikel</a>
                    <a href="<?= BASE_URL ?>/admin/upload_dokumen.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-file-upload w-6 mr-3"></i>Upload Dokumen</a>
                    <a href="<?= BASE_URL ?>/pages/index.php" target="_blank" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-globe w-6 mr-3"></i>Lihat Website</a>
                </nav>
            </div>
            <div>
                <div class="w-full border-t border-slate-700 pt-4 mt-4">
                    <a href="<?= BASE_URL ?>/admin/edit_profile.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-user-edit w-6 mr-3"></i>Edit Profil</a>
                    <a href="<?= BASE_URL ?>/admin/change_password.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-key w-6 mr-3"></i>Ganti Password</a>
                    <a href="<?= BASE_URL ?>/admin/logout.php" class="flex items-center p-3 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors"><i class="fas fa-sign-out-alt w-6 mr-3"></i>Logout</a>
                </div>
            </div>
        </aside>

        <main class="main-content p-8">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-white">Dashboard</h2>
                    <p class="text-slate-400">Selamat datang kembali, <span class="font-bold text-cyan-400"><?= htmlspecialchars($username) ?></span>!</p>
                </div>
                <div class="flex items-center">
                    <span class="mr-4 text-slate-300"><?= htmlspecialchars($username) ?></span>
                    <img src="<?= $profile_image_path ?>" alt="Foto Profil" class="w-12 h-12 rounded-full object-cover border-2 border-cyan-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="stat-card p-6 rounded-xl">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-slate-400 text-sm">Total Artikel</p>
                            <p class="text-3xl font-bold text-white"><?= $total_artikel ?></p>
                        </div>
                        <i class="fas fa-newspaper text-4xl text-cyan-500 opacity-50"></i>
                    </div>
                </div>
                 <div class="stat-card p-6 rounded-xl">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-slate-400 text-sm">Total Dokumen</p>
                            <p class="text-3xl font-bold text-white"><?= $total_dokumen ?></p>
                        </div>
                        <i class="fas fa-folder-open text-4xl text-purple-500 opacity-50"></i>
                    </div>
                </div>
                </div>

            <div>
                <h3 class="text-2xl font-bold text-white mb-4">Dokumen Terbaru</h3>
                <div class="bg-slate-800/50 rounded-lg shadow-lg overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-700/50">
                            <tr>
                                <th class="p-4">Nama Dokumen</th>
                                <th class="p-4">File Path</th>
                                <th class="p-4">Tanggal Upload</th>
                                <th class="p-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($hasil_dokumen && mysqli_num_rows($hasil_dokumen) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($hasil_dokumen)): ?>
                                    <tr class="border-b border-slate-700 hover:bg-slate-800 transition-colors">
                                        <td class="p-4"><?= htmlspecialchars($row['nama']); ?></td>
                                        <td class="p-4 text-sm text-slate-400"><?= htmlspecialchars($row['file']); ?></td>
                                        <td class="p-4 text-sm text-slate-400"><?= date('d M Y, H:i', strtotime($row['tanggal_upload'])); ?></td>
                                        <td class="p-4">
                                            <a href="<?= BASE_URL ?>/uploads/<?= rawurlencode($row['file']); ?>" download class="text-cyan-400 hover:text-cyan-300 mr-4"><i class="fas fa-download"></i></a>
                                            <a href="<?= BASE_URL ?>/admin/hapus_dokumen.php?id=<?= $row['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Anda yakin ingin menghapus dokumen ini?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="p-8 text-center text-slate-500">Belum ada dokumen yang di-upload.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

</body>
</html>