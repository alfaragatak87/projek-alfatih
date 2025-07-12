<?php
session_start();

// Keamanan: Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// PENTING: Sertakan file koneksi dan header
require_once '../config/koneksi.php';
// Untuk halaman admin, kita mungkin butuh header khusus, tapi untuk sekarang pakai yang utama dulu
require_once '../templates/header.php'; 

$page_title = "Kelola Artikel - Admin";

// --- Query untuk mengambil semua artikel ---
$query = "
    SELECT 
        artikel.id, 
        artikel.judul, 
        artikel.tanggal_diperbarui,
        kategori.nama_kategori
    FROM 
        artikel
    JOIN 
        kategori ON artikel.id_kategori = kategori.id
    ORDER BY 
        artikel.tanggal_diperbarui DESC";
$hasil_artikel = mysqli_query($koneksi, $query);

?>

<div class="container mx-auto px-6 py-24 md:py-32">
    <div class="flex justify-between items-center mb-12">
        <div>
            <h2 class="text-3xl font-bold text-cyan-400">Kelola Artikel Blog</h2>
            <p class="text-slate-400">Tambah, edit, atau hapus artikel dari sini.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/form_artikel.php" class="btn-neon btn-neon-fill">
            <i class="fas fa-plus mr-2"></i> Tulis Artikel Baru
        </a>
    </div>

    <div class="bg-slate-800/50 rounded-lg shadow-lg overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-700/50">
                <tr>
                    <th class="p-4 font-semibold">Judul Artikel</th>
                    <th class="p-4 font-semibold">Kategori</th>
                    <th class="p-4 font-semibold">Terakhir Diperbarui</th>
                    <th class="p-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($hasil_artikel && mysqli_num_rows($hasil_artikel) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($hasil_artikel)): ?>
                        <tr class="border-b border-slate-700 hover:bg-slate-800 transition-colors">
                            <td class="p-4 align-middle">
                                <?= htmlspecialchars($row['judul']); ?>
                            </td>
                            <td class="p-4 align-middle text-sm text-slate-400">
                                <span class="bg-cyan-900/50 text-cyan-300 px-2 py-1 rounded-full text-xs">
                                    <?= htmlspecialchars($row['nama_kategori']); ?>
                                </span>
                            </td>
                            <td class="p-4 align-middle text-sm text-slate-400">
                                <?= date('d M Y, H:i', strtotime($row['tanggal_diperbarui'])); ?>
                            </td>
                            <td class="p-4 align-middle">
                                <div class="flex gap-2">
                                    <a href="<?= BASE_URL ?>/admin/form_artikel.php?id=<?= $row['id']; ?>" class="text-yellow-400 hover:text-yellow-300 font-semibold text-sm">Edit</a>
                                    <span class="text-slate-600">|</span>
                                    <a href="<?= BASE_URL ?>/admin/proses_artikel.php?aksi=hapus&id=<?= $row['id']; ?>" class="text-red-400 hover:text-red-300 font-semibold text-sm" onclick="return confirm('Anda yakin ingin menghapus artikel ini secara permanen?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-500">
                            Belum ada artikel yang ditulis. Mulai tulis artikel pertama Anda!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="mt-8 text-center">
        <a href="<?= BASE_URL ?>/admin/dashboard.php" class="text-sm text-cyan-400 hover:underline">&larr; Kembali ke Dashboard</a>
    </div>

</div>

<?php
// PENTING: Sertakan file footer
require_once '../templates/footer.php';
?>