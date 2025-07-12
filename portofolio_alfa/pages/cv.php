<?php 
require_once '../config/koneksi.php'; // PENTING: Pastikan ini ada

$page_title = "Quantum CV Portal | Alfatih";
require_once '../templates/header.php'; // PENTING: Pastikan ini ada

// Validasi file
// PENTING: Pastikan path ini benar ke file CV Anda di folder uploads/cv/
$cv_file = BASE_URL . '/uploads/cv/cv-muhammad-alfatih.pdf'; 
// Untuk cek keberadaan file secara fisik di server (bukan URL)
$physical_cv_file_path = __DIR__ . '/../uploads/cv/cv-muhammad-alfatih.pdf';

$file_exists = file_exists($physical_cv_file_path);
$filesize = $file_exists ? round(filesize($physical_cv_file_path) / 1024) . ' KB' : 'File Missing';
$last_updated = $file_exists ? date("d/m/Y H:i", filemtime($physical_cv_file_path)) : 'N/A';
?>

<section class="quantum-portal min-h-screen flex items-center justify-center px-4 py-20">
    <div class="container mx-auto max-w-4xl">
        <!-- Portal Card -->
        <div class="bg-gray-800/80 backdrop-blur-lg border border-cyan-500/30 rounded-xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="portal-header bg-gradient-to-r from-cyan-900/40 to-blue-900/40 p-6 border-b border-cyan-500/20">
                <div class="flex items-center">
                    <div class="portal-icon animate-pulse">
                        <i class="fas fa-file-pdf text-3xl text-cyan-400"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-white">CV Download Portal</h2>
                        <p class="text-cyan-300 text-sm">Secure quantum transfer</p>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="p-6 md:p-8">
                <!-- File Info -->
                <div class="file-card bg-gray-700/50 rounded-lg p-5 mb-8 border border-dashed border-cyan-500/30">
                    <div class="file-meta grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <span class="text-sm text-gray-400 block">Filename:</span>
                            <span class="font-mono text-cyan-400">cv-muhammad-alfatih.pdf</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-400 block">Size:</span>
                            <span class="font-mono"><?= $filesize ?></span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-400 block">Type:</span>
                            <span class="font-mono">PDF Document</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-400 block">Updated:</span>
                            <span class="font-mono"><?= $last_updated ?></span>
                        </div>
                    </div>
                    
                    <!-- File Preview Placeholder -->
                    <div class="file-preview h-40 bg-gray-800 rounded flex items-center justify-center border border-gray-600">
                        <i class="far fa-file-pdf text-5xl text-cyan-400 opacity-50"></i>
                    </div>
                </div>
                
                <!-- Download Action -->
                <?php if($file_exists): ?>
                <div class="text-center">
                    <a href="<?= $cv_file ?>" download class="download-btn inline-block px-8 py-4 rounded-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold transition-all duration-300 hover:shadow-lg hover:shadow-cyan-500/30 relative group">
                        <span class="absolute inset-0 rounded-full bg-cyan-500 opacity-0 group-hover:opacity-20 transition duration-1000"></span>
                        <div class="relative flex items-center justify-center">
                            <i class="fas fa-download mr-3"></i>
                            <span>Initiate Download Sequence</span>
                            <div class="download-pulse absolute -z-10 w-full h-full rounded-full bg-cyan-500/20 scale-0 group-hover:scale-100 transition duration-700"></div>
                        </div>
                    </a>
                    <p class="text-sm text-gray-400 mt-3">Secure HTTPS transfer | SHA-256 encrypted</p>
                </div>
                <?php else: ?>
                <div class="alert bg-red-900/50 border border-red-500 rounded-lg p-4 text-center">
                    <i class="fas fa-exclamation-triangle text-red-300 mr-2"></i>
                    <span>Quantum storage anomaly detected - File temporarily unavailable</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.quantum-portal {
    background: radial-gradient(ellipse at center, #0f172a 0%, #020617 100%);
}

.file-card {
    transition: all 0.4s ease;
}

.file-card:hover {
    border-style: solid;
    box-shadow: 0 0 15px rgba(34, 211, 238, 0.2);
}

.download-btn {
    letter-spacing: 1px;
    text-shadow: 0 0 10px rgba(34, 211, 238, 0.5);
}

.download-pulse {
    animation: pulseAnimation 2s infinite;
}

@keyframes pulseAnimation {
    0% { opacity: 1; transform: scale(0.8); }
    100% { opacity: 0; transform: scale(1.5); }
}
</style>

<?php require_once '../templates/footer.php'; // PENTING: Pastikan ini ada ?>
