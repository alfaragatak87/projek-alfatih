<?php
require_once '../config/koneksi.php'; // PENTING: Pastikan ini ada

$page_title = "Tentang Saya - Muhammad Alfatih";

// --- START: Ambil Foto Profil Dinamis ---
$profile_image_path = BASE_URL . '/assets/images/foto-profil.png'; // Default path jika tidak ada di DB atau error
// Query untuk mengambil profile_image dari admin (misal ID 1, atau ID admin utama)
// Asumsi ada 1 admin utama dengan ID 1. Jika ada banyak admin, perlu logika lebih lanjut
$query_profile_img = "SELECT profile_image FROM admin WHERE id = 1 LIMIT 1";
$result_profile_img = mysqli_query($koneksi, $query_profile_img);

if ($result_profile_img && mysqli_num_rows($result_profile_img) > 0) {
    $admin_profile_data = mysqli_fetch_assoc($result_profile_img);
    if (!empty($admin_profile_data['profile_image'])) {
        $dynamic_path = BASE_URL . '/uploads/profile_pics/' . htmlspecialchars($admin_profile_data['profile_image']);
        // Cek apakah file benar-benar ada di server
        // __DIR__ . '/../uploads/profile_pics/' adalah path fisik di server
        if (file_exists(__DIR__ . '/../uploads/profile_pics/' . $admin_profile_data['profile_image'])) {
            $profile_image_path = $dynamic_path;
        }
    }
}
// --- END: Ambil Foto Profil Dinamis ---

require_once '../templates/header.php'; // PENTING: Pastikan ini ada

// --- Logika untuk Tombol Download CV (diambil dari dokumen.php) ---
// PERBAIKAN: Menggunakan nama kolom yang benar yaitu 'nama' dan 'file'
$query_cv = "SELECT nama, file FROM dokumen WHERE nama LIKE ? ORDER BY id DESC LIMIT 1";
$stmt_cv = mysqli_prepare($koneksi, $query_cv);
$cv_keyword = "%CV%";
mysqli_stmt_bind_param($stmt_cv, 's', $cv_keyword);
mysqli_stmt_execute($stmt_cv);
$result_cv = mysqli_stmt_get_result($stmt_cv);
$cv_data = mysqli_fetch_assoc($result_cv);
// ----------------------------------------------------------------

?>
<section id="about" class="container mx-auto px-6 py-24 md:py-32">
    <div class="text-center max-w-lg mx-auto mb-16">
        <img src="<?php echo $profile_image_path; ?>" alt="Foto Profil Muhammad Alfatih" class="rounded-full w-48 h-48 mx-auto object-cover border-4 border-cyan-500 shadow-lg shadow-cyan-500/20 mb-6">
        <h2 class="text-4xl font-bold text-white">Muhammad Alfatih</h2>
        <p class="text-lg text-slate-400 mt-2">Mahasiswa Informatika & Web Developer</p>
        
        <?php if ($cv_data): ?>
            <a href="<?= BASE_URL ?>/uploads/<?php echo rawurlencode($cv_data['file']); ?>" download class="btn-neon btn-neon-fill mt-8 inline-block">
                Unduh CV Saya
            </a>
        <?php else: ?>
            <div class="mt-8 bg-yellow-900/50 text-yellow-300 p-3 rounded-md text-sm">
                File CV belum di-upload oleh admin.
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1 space-y-8">
            <div class="glass-card p-6">
                <h3 class="text-xl font-bold text-cyan-400 mb-4">Identitas Diri</h3>
                <ul class="space-y-2 text-sm text-slate-300">
                    <li><strong>Lahir:</strong> Lumajang, 6 November 2003</li>
                    <li><strong>Domisili:</strong> Tempeh, Lumajang</li>
                    <li><strong>Email:</strong> s.s.6624844@gmail.com</li>
                    <li><strong>Kontak:</strong> 0831-8881-3237</li>
                    <li><strong>Instagram:</strong> @alfamuhammad___</li>
                </ul>
            </div>
            <div class="glass-card p-6">
                <h3 class="text-xl font-bold text-cyan-400 mb-4">Latar Belakang Pendidikan</h3>
                <ul class="space-y-3 text-sm text-slate-300">
                    <li><strong>ITB Widyagama Lumajang</strong> (2023–Sekarang)</li>
                    <li><strong>Universitas Muhammadiyah Malang</strong> (2021, 2 semester)</li>
                    <li><strong>SMK Miftahul Islam Kunir</strong> (2018–2021)</li>
                </ul>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card p-6">
                <h3 class="text-xl font-bold text-cyan-400 mb-4">Tentang Saya</h3>
                <p class="text-slate-400 leading-relaxed">Saya adalah seseorang yang tumbuh dari lingkungan desa dengan semangat besar untuk berkembang di dunia teknologi. Saya percaya teknologi tidak hanya soal kode, tapi juga tentang nilai, komunikasi, dan kebermanfaatan. Dalam hidup, saya ingin menciptakan karya yang tidak hanya "jalan", tetapi juga bermakna.</p>
            </div>
            <div class="glass-card p-6">
                <h3 class="text-xl font-bold text-cyan-400 mb-4">Kemampuan & Keahlian</h3>
                <div class="flex flex-wrap gap-2">
                    <span class="skill-badge">HTML</span>
                    <span class="skill-badge">CSS</span>
                    <span class="skill-badge">JavaScript</span>
                    <span class="skill-badge">PHP</span>
                    <span class="skill-badge">MySQL</span>
                    <span class="skill-badge">Java</span>
                    <span class="skill-badge">UI/UX Design</span>
                    <span class="skill-badge">ERD</span>
                    <span class="skill-badge">Kerja Tim</span>
                </div>
            </div>
            <div class="glass-card p-6">
                <h3 class="text-xl font-bold text-cyan-400 mb-4">Pengalaman</h3>
                <ul class="list-disc list-inside text-slate-400 space-y-2">
                    <li>Operator Madrasah di MI Salafiyah Al-Yasiny</li>
                    <li>Pengurus OSIS SMK selama 2 periode</li>
                    <li>Magang Teknisi di Global Computer Jogoyudan</li>
                    <li>Proyek Sistem Penjualan Tiket Konser (Java)</li>
                    <li>Proyek Database Festival Kuliner & Skincare Online</li>
                </ul>
            </div>
             <div class="glass-card p-6 border-pink-500/50">
                <h3 class="text-xl font-bold text-pink-400 mb-4">Motivasi Personal</h3>
                <p class="text-slate-400 leading-relaxed">Saya menjalin hubungan dengan <span class="font-semibold text-white">Niawatul Hasanah</span>, mahasiswi Manajemen di kampus yang sama. Hubungan ini menjadi motivasi saya untuk menjadi pribadi yang lebih baik setiap hari, baik secara akhlak maupun keahlian.</p>
            </div>
        </div>
    </div>
</section>

<style>
    .skill-badge {
        background-color: rgba(34, 211, 238, 0.1);
        color: #22d3ee;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid rgba(34, 211, 238, 0.2);
    }
</style>

<?php require_once '../templates/footer.php'; // PENTING: Pastikan ini ada ?>
