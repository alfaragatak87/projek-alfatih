<?php
require_once '../config/koneksi.php';

$page_title = "Hubungi Saya - Muhammad Alfatih";
$pesan_sukses = '';

if (isset($_POST['kirim'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $pesan = htmlspecialchars($_POST['pesan']);
    
    // Di sini bisa ditambahkan logika kirim email atau simpan ke DB
    
    $pesan_sukses = "Terima kasih, " . $nama . "! Pesan Anda telah kami terima.";
}

require_once '../templates/header.php';
?>

<section id="contact" class="container mx-auto px-6 py-16 md:py-24">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-4xl md:text-5xl font-bold text-cyan-400 mb-4">
            Hubungi Saya
        </h2>
        <p class="text-slate-400 leading-relaxed">
            Punya pertanyaan, tawaran proyek, atau sekadar ingin menyapa? Silakan gunakan formulir di bawah ini atau hubungi saya melalui salah satu platform berikut.
        </p>
    </div>
    
    <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-5 gap-12">
        
        <div class="lg:col-span-3">
            <div class="glass-card p-8 h-full">
                <?php if (!empty($pesan_sukses)) : ?>
                    <div class="bg-green-500/20 text-green-300 p-4 rounded-md text-center flex flex-col justify-center items-center h-full">
                        <i class="fas fa-check-circle text-5xl mb-4"></i>
                        <p><?php echo $pesan_sukses; ?></p>
                    </div>
                <?php else : ?>
                    <form action="<?= BASE_URL ?>/pages/contact.php" method="POST" class="space-y-6">
                        <div>
                            <label for="nama" class="block mb-2 text-sm font-medium text-slate-300">Nama Anda</label>
                            <input type="text" name="nama" id="nama" class="w-full p-3 rounded-md bg-slate-700 text-white border border-slate-600 focus:ring-cyan-500 focus:border-cyan-500" required>
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-slate-300">Email Anda</label>
                            <input type="email" name="email" id="email" class="w-full p-3 rounded-md bg-slate-700 text-white border border-slate-600 focus:ring-cyan-500 focus:border-cyan-500" required>
                        </div>
                        <div>
                            <label for="pesan" class="block mb-2 text-sm font-medium text-slate-300">Pesan</label>
                            <textarea name="pesan" id="pesan" rows="5" class="w-full p-3 rounded-md bg-slate-700 text-white border border-slate-600 focus:ring-cyan-500 focus:border-cyan-500" required></textarea>
                        </div>
                        <button type="submit" name="kirim" class="w-full btn-neon btn-neon-fill">
                            Kirim Pesan
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card p-6">
                <h3 class="text-xl font-bold text-cyan-400 mb-4 flex items-center"><i class="fas fa-link mr-3"></i>Media Sosial</h3>
                <div class="space-y-4">
                    <a href="https://github.com/alfaragatak87" target="_blank" class="social-link-item group">
                        <i class="fab fa-github social-icon"></i>
                        <span class="social-text">@alfaragatak87</span>
                    </a>
                    <a href="https://instagram.com/alfamuhammad___" target="_blank" class="social-link-item group">
                        <i class="fab fa-instagram social-icon"></i>
                        <span class="social-text">@alfamuhammad___</span>
                    </a>
                    <a href="https://wa.me/6283188813237" target="_blank" class="social-link-item group">
                        <i class="fab fa-whatsapp social-icon"></i>
                        <span class="social-text">0831-8881-3237</span>
                    </a>
                </div>
            </div>
            <div class="glass-card p-6">
                <h3 class="text-xl font-bold text-cyan-400 mb-4 flex items-center"><i class="fas fa-map-marker-alt mr-3"></i>Lokasi</h3>
                <address class="not-italic text-slate-400">
                    Dusun Krajan 2, RT.05/RW.04<br>
                    Desa Pandanwangi, Kecamatan Tempeh<br>
                    Lumajang, Jawa Timur, 67371
                </address>
            </div>
        </div>

    </div>
</section>

<style>
.social-link-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border-radius: 8px;
    background-color: rgba(34, 211, 238, 0.05);
    border: 1px solid transparent;
    transition: all 0.3s ease;
}
.social-link-item:hover {
    border-color: rgba(34, 211, 238, 0.4);
    background-color: rgba(34, 211, 238, 0.1);
    transform: translateY(-3px);
}
.social-icon {
    font-size: 1.5rem;
    color: #22d3ee;
    margin-right: 16px;
    width: 24px;
    text-align: center;
}
.social-text {
    color: #e2e8f0; /* text-slate-200 */
    font-weight: 600;
    transition: color 0.3s ease;
}
.social-link-item:hover .social-text {
    color: #ffffff;
}
</style>

<?php require_once '../templates/footer.php'; ?>