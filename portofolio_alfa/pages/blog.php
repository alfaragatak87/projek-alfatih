<?php
// PENTING: Sertakan file koneksi dan header
require_once '../config/koneksi.php';
require_once '../templates/header.php';

$page_title = "Blog - Muhammad Alfatih"; // Set judul halaman

// --- Query untuk mengambil semua artikel ---
// Kita gabungkan (JOIN) 3 tabel: artikel, kategori, dan admin
// untuk mendapatkan nama kategori dan nama penulisnya sekaligus.
$query_artikel = "
    SELECT 
        artikel.id, 
        artikel.judul, 
        artikel.slug, 
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
    ORDER BY 
        artikel.tanggal_dibuat DESC";

$hasil_artikel = mysqli_query($koneksi, $query_artikel);

?>

<section id="blog-list" class="container mx-auto px-6 py-24 md:py-32">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-4xl md:text-5xl font-bold text-cyan-400 mb-4">
            Catatan Digital Saya
        </h2>
        <p class="text-slate-400 leading-relaxed">
            Kumpulan tulisan, tutorial, dan opini saya seputar dunia teknologi, pemrograman, dan kehidupan sebagai mahasiswa informatika.
        </p>
    </div>

    <?php if ($hasil_artikel && mysqli_num_rows($hasil_artikel) > 0) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php while ($artikel = mysqli_fetch_assoc($hasil_artikel)): ?>
                <?php
                    // Membuat ringkasan konten (excerpt)
                    $konten_ringkas = strip_tags($artikel['konten']); // Hapus tag HTML
                    if (strlen($konten_ringkas) > 120) {
                        $konten_ringkas = substr($konten_ringkas, 0, 120) . '...';
                    }

                    // Menentukan path gambar unggulan
                    // Jika tidak ada gambar, pakai gambar default
                    $gambar_path = BASE_URL . '/assets/images/placeholder-blog.jpg'; // Buat gambar placeholder jika perlu
                    if (!empty($artikel['gambar_unggulan'])) {
                        // Nanti kita buat folder ini
                        $gambar_path = BASE_URL . '/uploads/artikel/' . htmlspecialchars($artikel['gambar_unggulan']);
                    }
                ?>

                <div class="glass-card flex flex-col overflow-hidden article-card">
                    <a href="<?= BASE_URL ?>/pages/single_post.php?slug=<?= htmlspecialchars($artikel['slug']) ?>">
                        <img src="<?= $gambar_path ?>" alt="Gambar unggulan untuk <?= htmlspecialchars($artikel['judul']) ?>" class="w-full h-48 object-cover card-image">
                    </a>

                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex justify-between items-center text-xs text-cyan-400 mb-2 font-mono">
                            <span><?= htmlspecialchars($artikel['nama_kategori']) ?></span>
                            <span><?= date('d M Y', strtotime($artikel['tanggal_dibuat'])) ?></span>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-3 flex-grow">
                            <a href="<?= BASE_URL ?>/pages/single_post.php?slug=<?= htmlspecialchars($artikel['slug']) ?>" class="hover:text-cyan-300 transition-colors">
                                <?= htmlspecialchars($artikel['judul']) ?>
                            </a>
                        </h3>
                        
                        <p class="text-slate-400 text-sm mb-6">
                            <?= $konten_ringkas ?>
                        </p>

                        <div class="mt-auto">
                             <a href="<?= BASE_URL ?>/pages/single_post.php?slug=<?= htmlspecialchars($artikel['slug']) ?>" class="btn-neon btn-neon-outline w-full">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

        </div>
    <?php else: ?>
        <div class="glass-card p-12 text-center max-w-lg mx-auto">
            <h3 class="text-2xl font-bold text-white mb-2">Belum Ada Tulisan</h3>
            <p class="text-gray-400">Saat ini belum ada artikel yang dipublikasikan. Silakan cek kembali nanti!</p>
        </div>
    <?php endif; ?>

</section>

<style>
.article-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.article-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(34, 211, 238, 0.1);
}
.article-card .card-image {
    transition: transform 0.4s ease;
}
.article-card:hover .card-image {
    transform: scale(1.05);
}
</style>

<?php
// PENTING: Sertakan file footer
require_once '../templates/footer.php';
?>