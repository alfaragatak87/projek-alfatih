<?php
echo "<h3>Mulai Pengecekan URL...</h3>";

// 1. Memanggil file koneksi untuk mendapatkan BASE_URL
require_once 'config/koneksi.php';
echo "<p>File 'config/koneksi.php' berhasil dimuat.</p>";

// 2. Cek apakah BASE_URL sudah terdefinisi
if (defined('BASE_URL')) {
    echo "<p>Konstanta <strong>BASE_URL</strong> berhasil didefinisikan.</p>";
    
    // 3. Tampilkan nilai dari BASE_URL
    echo "<p>Nilai dari BASE_URL adalah: <strong>" . BASE_URL . "</strong></p>";

    // 4. Buat contoh link seperti yang ada di dashboard
    $link_lihat_website = BASE_URL . "/pages/index.php";

    echo "<hr>";
    echo "<h3>Hasil Akhir Link</h3>";
    echo "Ini adalah link 'Lihat Website' yang seharusnya dibuat di dashboard:<br>";
    echo "<a href='" . $link_lihat_website . "'>" . $link_lihat_website . "</a>";

} else {
    echo "<p style='color:red;'><strong>ERROR:</strong> Konstanta BASE_URL tidak terdefinisi setelah memuat koneksi.php.</p>";
}

echo "<hr>";
echo "<h3>Detail Variabel Server:</h3>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";

?>