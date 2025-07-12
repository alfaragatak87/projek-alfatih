<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

// Ambil semua data proyek untuk ditampilkan di tabel
$query = "SELECT id, judul, kategori, tanggal_dibuat FROM proyek ORDER BY tanggal_dibuat DESC";
$hasil_proyek = mysqli_query($koneksi, $query);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kelola Proyek - Admin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        .admin-wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background-color: #1a202c; flex-shrink: 0; }
        .main-content { flex-grow: 1; background-color: #0f172a; }
    </style>
</head>
<body class="bg-slate-900 text-slate-200">

    <div class="admin-wrapper">
        <aside class="sidebar p-6 flex flex-col justify-between">
            <div>
                <h1 class="text-2xl font-bold text-cyan-400 mb-8 border-b border-cyan-800 pb-4">Admin Panel</h1>
                <nav class="flex flex-col space-y-3">
                    <a href="<?= BASE_URL ?>/admin/dashboard.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-tachometer-alt w-6 mr-3"></i>Dashboard</a>
                    <a href="<?= BASE_URL ?>/admin/kelola_artikel.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-newspaper w-6 mr-3"></i>Kelola Artikel</a>
                    <a href="<?= BASE_URL ?>/admin/kelola_proyek.php" class="flex items-center p-3 bg-cyan-500/20 rounded-lg text-white font-bold"><i class="fas fa-project-diagram w-6 mr-3"></i>Kelola Proyek</a>
                    <a href="<?= BASE_URL ?>/admin/arsip_dokumen.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-archive w-6 mr-3"></i>Arsip Dokumen</a>
                </nav>
            </div>
            <div>
                 <a href="<?= BASE_URL ?>/admin/logout.php" class="flex items-center p-3 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors"><i class="fas fa-sign-out-alt w-6 mr-3"></i>Logout</a>
            </div>
        </aside>

        <main class="main-content p-8">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-white">Kelola Proyek</h2>
                    <p class="text-slate-400">Tambah, edit, atau hapus data proyek portofolio Anda.</p>
                </div>
                <a href="<?= BASE_URL ?>/admin/form_proyek.php" class="btn-neon btn-neon-fill">
                    <i class="fas fa-plus mr-2"></i> Tambah Proyek Baru
                </a>
            </div>

            <div class="bg-slate-800/50 rounded-lg shadow-lg overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-700/50">
                        <tr>
                            <th class="p-4">Judul Proyek</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Tanggal Dibuat</th>
                            <th class="p-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($hasil_proyek && mysqli_num_rows($hasil_proyek) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($hasil_proyek)): ?>
                                <tr class="border-b border-slate-700 hover:bg-slate-800 transition-colors">
                                    <td class="p-4 align-middle"><?= htmlspecialchars($row['judul']); ?></td>
                                    <td class="p-4 align-middle">
                                        <span class="bg-purple-500/20 text-purple-300 px-2 py-1 rounded-full text-xs font-mono">
                                            <?= htmlspecialchars($row['kategori']); ?>
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle text-sm text-slate-400"><?= date('d M Y', strtotime($row['tanggal_dibuat'])); ?></td>
                                    <td class="p-4 align-middle">
                                        <div class="flex gap-2">
                                            <a href="<?= BASE_URL ?>/admin/form_proyek.php?id=<?= $row['id']; ?>" class="text-yellow-400 hover:text-yellow-300 font-semibold text-sm">Edit</a>
                                            <span class="text-slate-600">|</span>
                                            <a href="<?= BASE_URL ?>/admin/proses_proyek.php?aksi=hapus&id=<?= $row['id']; ?>" class="text-red-400 hover:text-red-300 font-semibold text-sm" onclick="return confirm('Anda yakin ingin menghapus proyek ini?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-500">
                                    Belum ada proyek yang ditambahkan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>