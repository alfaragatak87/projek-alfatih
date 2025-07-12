<?php
require_once '../config/koneksi.php'; 

$page_title = "Proyek Saya - Muhammad Alfatih";
require_once __DIR__ . '/../templates/header.php';

// --- Mengambil data proyek dari database ---
$query_proyek = "SELECT id, judul, kategori, deskripsi, gambar_proyek, link_proyek FROM proyek ORDER BY tanggal_dibuat DESC";
$hasil_proyek = mysqli_query($koneksi, $query_proyek);

?>

<style>
/* Styling untuk filter dan kartu proyek */
.filter-btn {
    transition: all 0.3s ease;
}
.filter-btn.is-checked { /* Mengganti .active menjadi .is-checked sesuai standar Isotope */
    background-color: var(--primary-neon, #22d3ee);
    color: var(--bg-dark, #0f172a);
    box-shadow: 0 0 15px rgba(34, 211, 238, 0.5);
}
.project-card-wrapper {
    /* Ini adalah wrapper untuk setiap item, penting untuk animasi Isotope */
    transition: transform 0.4s ease, opacity 0.4s ease;
}
.project-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: block;
}
.project-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(34, 211, 238, 0.1);
}
.project-card img {
    transition: transform 0.4s ease;
}
.project-card:hover img {
    transform: scale(1.05);
}
</style>

<section id="projects-gallery" class="container mx-auto px-6 py-24 md:py-32">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-4xl md:text-5xl font-bold text-cyan-400 mb-4">
            Galeri Proyek
        </h2>
        <p class="text-slate-400 leading-relaxed dark:text-slate-400">
            Berikut adalah beberapa proyek pilihan yang pernah saya kerjakan, mencakup pengembangan web dan desain UI/UX.
        </p>
    </div>

    <div class="flex justify-center flex-wrap gap-4 mb-12" id="filter-buttons">
        <button class="filter-btn is-checked px-6 py-2 rounded-full font-semibold border border-cyan-500" data-filter="*">Semua</button>
        <button class="filter-btn px-6 py-2 rounded-full font-semibold border border-cyan-500" data-filter=".php">PHP</button>
        <button class="filter-btn px-6 py-2 rounded-full font-semibold border border-cyan-500" data-filter=".java">Java</button>
        <button class="filter-btn px-6 py-2 rounded-full font-semibold border border-cyan-500" data-filter=".ui-ux">UI/UX</button>
    </div>

    <div class="project-grid" id="project-grid">
        
        <?php if ($hasil_proyek && mysqli_num_rows($hasil_proyek) > 0): ?>
            <?php while($proyek = mysqli_fetch_assoc($hasil_proyek)): ?>
                <?php
                    $link_tujuan = !empty($proyek['link_proyek']) ? htmlspecialchars($proyek['link_proyek']) : '#';
                    $gambar_path = BASE_URL . '/assets/images/placeholder-blog.jpg';
                    if (!empty($proyek['gambar_proyek'])) {
                        $gambar_path = BASE_URL . '/uploads/proyek/' . htmlspecialchars($proyek['gambar_proyek']);
                    }
                ?>
                <div class="project-card-wrapper <?= htmlspecialchars($proyek['kategori']) ?>">
                    <a href="<?= $link_tujuan ?>" target="_blank" class="project-card glass-card overflow-hidden">
                        <div class="overflow-hidden">
                            <img src="<?= $gambar_path ?>" alt="Gambar Proyek <?= htmlspecialchars($proyek['judul']) ?>" class="w-full h-56 object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white mb-2 dark:text-white"><?= htmlspecialchars($proyek['judul']) ?></h3>
                            <p class="text-slate-400 text-sm dark:text-slate-400"><?= htmlspecialchars($proyek['deskripsi']) ?></p>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="col-span-full text-center text-slate-400">Belum ada proyek yang ditambahkan.</p>
        <?php endif; ?>

    </div>
</section>

<?php 
require_once __DIR__ . '/../templates/footer.php'; 
?>