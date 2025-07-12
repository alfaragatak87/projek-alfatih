<?php
// PENTING: Sertakan file koneksi dan header
require_once '../config/koneksi.php';

// Ambil 'slug' dari URL
// Kita pakai isset() untuk ngecek apakah 'slug' ada di URL atau tidak
if (!isset($_GET['slug'])) {
    // Jika tidak ada slug, kembalikan ke halaman blog utama
    header("Location: " . BASE_URL . "/pages/blog.php");
    exit;
}

$slug = $_GET['slug'];

// --- Query untuk mengambil satu artikel berdasarkan SLUG ---
// Menggunakan prepared statement untuk keamanan dari SQL Injection
$query_artikel = "
    SELECT 
        artikel.judul, 
        artikel.konten, 
        artikel.gambar_unggulan, 
        artikel.tanggal_dibuat,
        kategori.nama_kategori,
        admin.username AS nama_penulis 
    FROM 
        artikel
    JOIN 
        kategori ON artikel.id_kategori = kategori.id
    JOIN 
        admin ON artikel.id_admin = admin.id
    WHERE 
        artikel.slug = ? 
    LIMIT 1";

$stmt = mysqli_prepare($koneksi, $query_artikel);
// 's' berarti kita binding parameter string
mysqli_stmt_bind_param($stmt, "s", $slug);
mysqli_stmt_execute($stmt);
$hasil_artikel = mysqli_stmt_get_result($stmt);
$artikel = mysqli_fetch_assoc($hasil_artikel);

// Set judul halaman secara dinamis berdasarkan judul artikel
// Jika artikel tidak ditemukan, judul default akan digunakan
$page_title = $artikel ? htmlspecialchars($artikel['judul']) . " | Blog Alfatih" : "Artikel Tidak Ditemukan";

require_once '../templates/header.php'; // Panggil header setelah punya judul halaman
?>

<div class="container mx-auto px-6 py-20 md:py-24">
    <div class="max-w-4xl mx-auto">

        <?php if ($artikel): ?>
            <article class="glass-card p-6 md:p-10">
                <header class="mb-8 border-b-2 border-cyan-500/20 pb-6">
                    <p class="text-cyan-400 font-mono text-sm mb-2">
                        <?= htmlspecialchars($artikel['nama_kategori']) ?>
                    </p>
                    
                    <h1 class="text-3xl md:text-5xl font-bold text-white leading-tight">
                        <?= htmlspecialchars($artikel['judul']) ?>
                    </h1>
                    
                    <p class="text-slate-400 mt-4 text-sm">
                        Oleh <span class="font-semibold text-white"><?= htmlspecialchars($artikel['nama_penulis']) ?></span>
                        <span class="mx-2">&bull;</span>
                        Dipublikasikan pada <?= date('d F Y', strtotime($artikel['tanggal_dibuat'])) ?>
                    </p>
                </header>

                <?php
                    // Menentukan path gambar unggulan
                    if (!empty($artikel['gambar_unggulan'])) {
                        $gambar_path = BASE_URL . '/uploads/artikel/' . htmlspecialchars($artikel['gambar_unggulan']);
                ?>
                    <figure class="mb-8">
                        <img src="<?= $gambar_path ?>" alt="Gambar unggulan untuk <?= htmlspecialchars($artikel['judul']) ?>" class="w-full h-auto max-h-[500px] object-cover rounded-lg shadow-lg">
                    </figure>
                <?php } ?>

                <div class="prose prose-invert max-w-none text-slate-300 leading-relaxed">
                    <?php
                        // Tampilkan konten artikel.
                        // Karena kita akan pakai editor teks, konten mungkin mengandung tag HTML (seperti <p>, <b>, dll)
                        // Jadi kita tidak menggunakan htmlspecialchars di sini agar formatnya tetap tampil.
                        // Keamanan input (sanitasi) akan kita handle saat menyimpan data dari form admin.
                        echo $artikel['konten']; 
                    ?>
                </div>
            </article>

        <?php else: ?>
            <div class="glass-card p-12 text-center">
                 <h1 class="text-5xl font-bold text-red-400 mb-4">404</h1>
                <h2 class="text-2xl font-bold text-white mb-2">Artikel Tidak Ditemukan</h2>
                <p class="text-gray-400 mb-6">Maaf, artikel yang Anda cari tidak ada atau mungkin telah dihapus.</p>
                <a href="<?= BASE_URL ?>/pages/blog.php" class="btn-neon btn-neon-fill">
                    &larr; Kembali ke Daftar Artikel
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
.prose {
    font-size: 1.1rem;
}
.prose h2 {
    @apply text-2xl font-bold mt-8 mb-4 text-cyan-400;
}
.prose h3 {
    @apply text-xl font-bold mt-6 mb-3 text-cyan-400;
}
.prose a {
    @apply text-cyan-400 hover:underline;
}
.prose blockquote {
    @apply border-l-4 border-cyan-500 pl-4 italic text-slate-400;
}
.prose code {
    @apply bg-slate-800 text-pink-400 rounded px-1 py-0.5 text-sm;
}
.prose pre {
    @apply bg-slate-800 p-4 rounded-lg overflow-x-auto;
}
</style>

<?php
// PENTING: Sertakan file footer
require_once '../templates/footer.php';
?>