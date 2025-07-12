<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

// --- Logika untuk mode Edit atau Tambah ---
$is_edit_mode = false;
$id_proyek = null;
$judul = '';
$kategori_pilihan = '';
$deskripsi = '';
$link_proyek = '';
$gambar_sekarang = '';
$page_title = "Tambah Proyek Baru";
$form_action = "proses_proyek.php?aksi=tambah";

// Cek jika ada ID di URL (mode edit)
if (isset($_GET['id'])) {
    $is_edit_mode = true;
    $id_proyek = (int)$_GET['id'];
    $page_title = "Edit Proyek";
    $form_action = "proses_proyek.php?aksi=edit&id=" . $id_proyek;

    // Ambil data proyek dari database
    $stmt = mysqli_prepare($koneksi, "SELECT judul, kategori, deskripsi, link_proyek, gambar_proyek FROM proyek WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id_proyek);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($data = mysqli_fetch_assoc($result)) {
        $judul = $data['judul'];
        $kategori_pilihan = $data['kategori'];
        $deskripsi = $data['deskripsi'];
        $link_proyek = $data['link_proyek'];
        $gambar_sekarang = $data['gambar_proyek'];
    } else {
        header("Location: kelola_proyek.php");
        exit;
    }
    mysqli_stmt_close($stmt);
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $page_title ?> - Admin</title>
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
        <aside class="sidebar p-6 flex flex-col">
            <h1 class="text-2xl font-bold text-cyan-400 mb-8 border-b border-cyan-800 pb-4">Admin Panel</h1>
            <nav class="flex flex-col space-y-3">
                 <a href="<?= BASE_URL ?>/admin/dashboard.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-tachometer-alt w-6 mr-3"></i>Dashboard</a>
                <a href="<?= BASE_URL ?>/admin/kelola_artikel.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-newspaper w-6 mr-3"></i>Kelola Artikel</a>
                <a href="<?= BASE_URL ?>/admin/kelola_proyek.php" class="flex items-center p-3 bg-cyan-500/20 rounded-lg text-white font-bold"><i class="fas fa-project-diagram w-6 mr-3"></i>Kelola Proyek</a>
                <a href="<?= BASE_URL ?>/admin/arsip_dokumen.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-archive w-6 mr-3"></i>Arsip Dokumen</a>
            </nav>
        </aside>

        <main class="main-content p-8">
            <h2 class="text-3xl font-bold text-white mb-6"><?= $page_title ?></h2>

            <form action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="glass-card p-8 space-y-6 max-w-2xl">
                <div>
                    <label for="judul" class="block mb-2 text-sm font-medium text-slate-300">Judul Proyek</label>
                    <input type="text" name="judul" id="judul" value="<?= htmlspecialchars($judul) ?>" class="w-full p-3 rounded-md bg-slate-700 text-white border border-slate-600 focus:ring-cyan-500 focus:border-cyan-500" required>
                </div>
                <div>
                    <label for="kategori" class="block mb-2 text-sm font-medium text-slate-300">Kategori</label>
                    <select name="kategori" id="kategori" class="w-full p-3 rounded-md bg-slate-700 text-white border border-slate-600 focus:ring-cyan-500 focus:border-cyan-500" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="php" <?= ($kategori_pilihan == 'php') ? 'selected' : '' ?>>PHP</option>
                        <option value="java" <?= ($kategori_pilihan == 'java') ? 'selected' : '' ?>>Java</option>
                        <option value="ui-ux" <?= ($kategori_pilihan == 'ui-ux') ? 'selected' : '' ?>>UI/UX</option>
                        <option value="lainnya" <?= ($kategori_pilihan == 'lainnya') ? 'selected' : '' ?>>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label for="deskripsi" class="block mb-2 text-sm font-medium text-slate-300">Deskripsi Singkat</label>
                    <textarea name="deskripsi" id="deskripsi" rows="5" class="w-full p-3 rounded-md bg-slate-700 text-white border border-slate-600" required><?= htmlspecialchars($deskripsi) ?></textarea>
                </div>
                <div>
                    <label for="link_proyek" class="block mb-2 text-sm font-medium text-slate-300">Link Proyek (Opsional)</label>
                    <input type="url" name="link_proyek" id="link_proyek" placeholder="https://github.com/user/repo" value="<?= htmlspecialchars($link_proyek) ?>" class="w-full p-3 rounded-md bg-slate-700 text-white border border-slate-600">
                </div>
                <div>
                    <label for="gambar_proyek" class="block mb-2 text-sm font-medium text-slate-300">Gambar Proyek</label>
                    <input type="file" name="gambar_proyek" id="gambar_proyek" class="w-full text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-cyan-500 file:text-slate-900 hover:file:bg-cyan-400" accept="image/*">
                    <p class="text-xs text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah gambar. Maks: 2MB.</p>
                    <?php if ($is_edit_mode && !empty($gambar_sekarang)): ?>
                        <div class="mt-4">
                            <img src="<?= BASE_URL ?>/uploads/proyek/<?= htmlspecialchars($gambar_sekarang) ?>" alt="Gambar saat ini" class="w-48 h-auto rounded-lg border-2 border-slate-600">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex justify-end gap-4 pt-4 border-t border-slate-700">
                    <a href="<?= BASE_URL ?>/admin/kelola_proyek.php" class="btn-neon btn-neon-outline">Batal</a>
                    <button type="submit" class="btn-neon btn-neon-fill"><i class="fas fa-save mr-2"></i> Simpan Proyek</button>
                </div>
            </form>
        </main>
    </div>

</body>
</html>