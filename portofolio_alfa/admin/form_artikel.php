<?php
session_start();

// Keamanan: Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

// --- Logika untuk mode Edit atau Tambah ---
$is_edit_mode = false;
$id_artikel = null;
$judul = '';
$konten = '';
$id_kategori_pilihan = '';
$gambar_sekarang = '';
$page_title = "Tulis Artikel Baru";
$form_action = "proses_artikel.php?aksi=tambah";

// Cek apakah ada ID di URL (mode edit)
if (isset($_GET['id'])) {
    $is_edit_mode = true;
    $id_artikel = (int)$_GET['id'];
    $page_title = "Edit Artikel";
    $form_action = "proses_artikel.php?aksi=edit&id=" . $id_artikel;

    // Ambil data artikel yang akan diedit dari database
    $query_get_artikel = "SELECT judul, konten, id_kategori, gambar_unggulan FROM artikel WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($koneksi, $query_get_artikel);
    mysqli_stmt_bind_param($stmt, 'i', $id_artikel);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($artikel_data = mysqli_fetch_assoc($result)) {
        $judul = $artikel_data['judul'];
        $konten = $artikel_data['konten'];
        $id_kategori_pilihan = $artikel_data['id_kategori'];
        $gambar_sekarang = $artikel_data['gambar_unggulan'];
    } else {
        // Jika artikel dengan ID tersebut tidak ditemukan, kembali ke halaman kelola
        header("Location: kelola_artikel.php");
        exit;
    }
    mysqli_stmt_close($stmt);
}

// Ambil semua data kategori untuk dropdown
$query_kategori = "SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$hasil_kategori = mysqli_query($koneksi, $query_kategori);

// Untuk header, kita panggil setelah semua variabel siap
require_once '../templates/header.php';
?>

<div class="container mx-auto px-6 py-24 md:py-32">
    <div class="flex justify-between items-center mb-12">
        <div>
            <h2 class="text-3xl font-bold text-cyan-400"><?= $page_title ?></h2>
            <p class="text-slate-400">Isi semua detail yang diperlukan untuk artikel Anda.</p>
        </div>
    </div>

    <form action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="glass-card p-8 space-y-6">
        
        <div>
            <label for="judul" class="block mb-2 text-sm font-medium text-slate-300">Judul Artikel</label>
            <input type="text" name="judul" id="judul" value="<?= htmlspecialchars($judul) ?>" class="w-full p-3 rounded-md bg-slate-700 text-white border border-slate-600 focus:ring-cyan-500 focus:border-cyan-500" required>
        </div>

        <div>
            <label for="id_kategori" class="block mb-2 text-sm font-medium text-slate-300">Kategori</label>
            <select name="id_kategori" id="id_kategori" class="w-full p-3 rounded-md bg-slate-700 text-white border border-slate-600 focus:ring-cyan-500 focus:border-cyan-500" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while ($kategori = mysqli_fetch_assoc($hasil_kategori)): ?>
                    <option value="<?= $kategori['id'] ?>" <?= ($kategori['id'] == $id_kategori_pilihan) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kategori['nama_kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label for="konten" class="block mb-2 text-sm font-medium text-slate-300">Konten Artikel</label>
            <textarea name="konten" id="konten" rows="15" class="w-full p-3 rounded-md bg-slate-700 text-white border border-slate-600"><?= htmlspecialchars($konten) ?></textarea>
        </div>

        <div>
            <label for="gambar_unggulan" class="block mb-2 text-sm font-medium text-slate-300">Gambar Unggulan</label>
            <input type="file" name="gambar_unggulan" id="gambar_unggulan" class="w-full text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-cyan-500 file:text-slate-900 hover:file:bg-cyan-400" accept="image/jpeg, image/png, image/gif">
            <p class="text-xs text-slate-500 mt-1">
                Kosongkan jika tidak ingin mengubah gambar. Tipe: JPG, PNG, GIF. Ukuran maks: 2MB.
            </p>
            <?php if ($is_edit_mode && !empty($gambar_sekarang)): ?>
                <div class="mt-4">
                    <p class="text-sm text-slate-400 mb-2">Gambar saat ini:</p>
                    <img src="<?= BASE_URL ?>/uploads/artikel/<?= htmlspecialchars($gambar_sekarang) ?>" alt="Gambar saat ini" class="w-48 h-auto rounded-lg border-2 border-slate-600">
                </div>
            <?php endif; ?>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-slate-700">
            <a href="<?= BASE_URL ?>/admin/kelola_artikel.php" class="btn-neon btn-neon-outline">Batal</a>
            <button type="submit" class="btn-neon btn-neon-fill">
                <i class="fas fa-save mr-2"></i> Simpan Artikel
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#konten',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    skin: 'oxide-dark', // Tema gelap
    content_css: 'dark', // Konten di dalam editor juga gelap
    height: 500
  });
</script>

<?php
require_once '../templates/footer.php';
?>