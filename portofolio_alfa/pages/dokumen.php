<?php
session_start();
// PENTING: Include koneksi.php di awal setiap file PHP di folder 'pages'
require_once '../config/koneksi.php'; 

// Jika tidak ada sesi admin yang aktif, tendang pengunjung ke halaman utama
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: " . BASE_URL . "/pages/index.php"); // Menggunakan BASE_URL
    exit;
}

$page_title = "Dokumen & Sertifikat - Muhammad Alfatih";
require_once '../templates/header.php'; // PENTING: Pastikan ini ada

// Ambil SEMUA dokumen dari database, diurutkan dari yang terbaru
$query_docs = "SELECT id, nama, file, tanggal_upload FROM dokumen ORDER BY id DESC";
$hasil_docs = mysqli_query($koneksi, $query_docs);
?>

<section id="documents-gallery" class="container mx-auto px-6 py-24 md:py-32">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-4xl md:text-5xl font-bold text-cyan-400 mb-4">Galeri Dokumen (Admin)</h2>
        <p class="text-slate-400 leading-relaxed">
            Halaman ini bersifat pribadi. Di sini Anda dapat melihat semua dokumen yang telah di-upload.
        </p>
    </div>

    <?php if ($hasil_docs && mysqli_num_rows($hasil_docs) > 0) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while ($doc = mysqli_fetch_assoc($hasil_docs)): ?>
                <?php
                    // Logika untuk menentukan ikon berdasarkan ekstensi file
                    $file_ext = strtolower(pathinfo($doc['file'], PATHINFO_EXTENSION));
                    $icon_path = '';
                    switch ($file_ext) {
                        case 'pdf':
                            $icon_path = '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />';
                            break;
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                            $icon_path = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />';
                            break;
                        default: // Ikon untuk doc, docx, dll.
                            $icon_path = '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />';
                            break;
                    }
                ?>
                <div class="glass-card flex flex-col p-6 document-card">
                    <div class="flex-grow">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex-shrink-0 text-cyan-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                    <?php echo $icon_path; ?>
                                </svg>
                            </div>
                            <h3 class="font-bold text-lg text-white leading-tight"><?php echo htmlspecialchars($doc['nama']); ?></h3>
                        </div>
                        <p class="text-xs text-slate-500 mb-4">Di-upload pada: <?php echo date('d F Y', strtotime($doc['tanggal_upload'])); ?></p>
                    </div>
                    <a href="<?= BASE_URL ?>/uploads/<?php echo rawurlencode($doc['file']); ?>" download class="btn-neon btn-neon-outline w-full mt-auto">
                        Unduh File
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="glass-card p-12 text-center max-w-lg mx-auto">
            <h3 class="text-2xl font-bold text-white mb-2">Oops!</h3>
            <p class="text-gray-400">Belum ada dokumen yang di-upload.</p>
        </div>
    <?php endif; ?>

</section>

<style>
/* Efek hover tambahan untuk kartu dokumen */
.document-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}
.document-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(34, 211, 238, 0.15);
    border-color: rgba(34, 211, 238, 0.7);
}
</style>

<?php require_once '../templates/footer.php'; // PENTING: Pastikan ini ada ?>
