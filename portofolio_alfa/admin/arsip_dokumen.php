<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: login.php"); 
    exit; 
}

require_once '../config/koneksi.php';

// --- Mengambil dan Mengelompokkan Dokumen per Semester ---
$query = "SELECT id, nama, file, semester, tanggal_upload FROM dokumen ORDER BY semester ASC, tanggal_upload DESC";
$hasil_query = mysqli_query($koneksi, $query);

$dokumen_per_semester = [];
while ($row = mysqli_fetch_assoc($hasil_query)) {
    // Jika belum ada data untuk semester ini, buat "laci" baru
    if (!isset($dokumen_per_semester[$row['semester']])) {
        $dokumen_per_semester[$row['semester']] = [];
    }
    // Masukkan dokumen ke dalam "laci" semester yang sesuai
    $dokumen_per_semester[$row['semester']][] = $row;
}

// Mengurutkan semester dari yang terbesar ke terkecil
krsort($dokumen_per_semester);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Dokumen Perkuliahan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .accordion-header { transition: background-color 0.3s ease; }
        .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.5s ease-out, padding 0.5s ease; }
        .accordion-item.active .accordion-content { max-height: 1000px; /* Nilai besar agar konten muat */ padding: 1.5rem; }
        .accordion-item.active .rotate-icon { transform: rotate(180deg); }
        .rotate-icon { transition: transform 0.3s ease; }
    </style>
</head>
<body class="bg-slate-900 text-slate-200 flex">
    <aside class="sidebar p-6 flex flex-col justify-between w-64">
        <div>
            <h1 class="text-2xl font-bold text-cyan-400 mb-8 border-b border-cyan-800 pb-4">Admin Panel</h1>
            <nav class="flex flex-col space-y-3">
                <a href="<?= BASE_URL ?>/admin/dashboard.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-tachometer-alt w-6 mr-3"></i>Dashboard</a>
                <a href="<?= BASE_URL ?>/admin/kelola_artikel.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-newspaper w-6 mr-3"></i>Kelola Artikel</a>
                <a href="<?= BASE_URL ?>/admin/upload_dokumen.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-file-upload w-6 mr-3"></i>Upload Dokumen</a>
                <a href="<?= BASE_URL ?>/admin/arsip_dokumen.php" class="flex items-center p-3 bg-cyan-500/20 rounded-lg text-white font-bold"><i class="fas fa-archive w-6 mr-3"></i>Arsip Dokumen</a>
            </nav>
        </div>
        <div>
             <a href="<?= BASE_URL ?>/admin/logout.php" class="flex items-center p-3 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors"><i class="fas fa-sign-out-alt w-6 mr-3"></i>Logout</a>
        </div>
    </aside>

    <main class="main-content p-8 flex-grow">
        <h1 class="text-3xl font-bold text-cyan-400">Arsip Dokumen Perkuliahan</h1>
        <p class="text-slate-400 mb-8">Semua file materi, tugas, dan sertifikat yang tersimpan, terorganisir per semester.</p>

        <div class="space-y-4">
            <?php if (empty($dokumen_per_semester)): ?>
                <div class="glass-card p-8 text-center">
                    <i class="fas fa-box-open text-5xl text-slate-500 mb-4"></i>
                    <h2 class="text-2xl font-bold text-white">Arsip Masih Kosong</h2>
                    <p class="text-slate-400">Belum ada dokumen yang di-upload. Silakan mulai meng-upload file.</p>
                </div>
            <?php else: ?>
                <?php foreach ($dokumen_per_semester as $semester => $dokumens): ?>
                    <div class="accordion-item glass-card rounded-lg overflow-hidden">
                        <div class="accordion-header cursor-pointer p-6 flex justify-between items-center hover:bg-slate-800/50">
                            <h2 class="text-xl font-bold text-white">
                                <i class="fas fa-folder-open text-cyan-400 mr-3"></i>
                                Semester <?= htmlspecialchars($semester) ?>
                            </h2>
                            <span class="bg-cyan-500/20 text-cyan-300 text-sm font-bold px-3 py-1 rounded-full"><?= count($dokumens) ?> File</span>
                            <i class="fas fa-chevron-down rotate-icon text-slate-400"></i>
                        </div>
                        
                        <div class="accordion-content bg-slate-800/30">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-slate-700">
                                        <th class="p-3">Nama Dokumen</th>
                                        <th class="p-3">Tanggal Upload</th>
                                        <th class="p-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dokumens as $doc): ?>
                                    <tr class="border-b border-slate-700 last:border-b-0 hover:bg-slate-700/50">
                                        <td class="p-3"><?= htmlspecialchars($doc['nama']) ?></td>
                                        <td class="p-3 text-sm text-slate-400"><?= date('d M Y, H:i', strtotime($doc['tanggal_upload'])) ?></td>
                                        <td class="p-3">
                                            <a href="<?= BASE_URL ?>/uploads/<?= rawurlencode($doc['file']); ?>" download class="text-cyan-400 hover:text-cyan-300 mr-4" title="Unduh"><i class="fas fa-download"></i></a>
                                            <a href="<?= BASE_URL ?>/admin/hapus_dokumen.php?id=<?= $doc['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Anda yakin ingin menghapus dokumen ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script>
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', () => {
                const accordionItem = header.parentElement;
                // Tutup semua item lain sebelum membuka yang ini (opsional)
                document.querySelectorAll('.accordion-item').forEach(item => {
                    if (item !== accordionItem) {
                        item.classList.remove('active');
                    }
                });
                // Buka atau tutup item yang diklik
                accordionItem.classList.toggle('active');
            });
        });
    </script>
</body>
</html>