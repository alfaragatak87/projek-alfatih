<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: login.php"); 
    exit; 
}

require_once '../config/koneksi.php';

$pesan = '';

if (isset($_POST['upload'])) {
    // Ambil data dari form
    $nama_deskripsi = htmlspecialchars($_POST['nama']);
    $semester = (int)$_POST['semester']; // Ambil data semester

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        $nama_file_asli = $file['name'];
        $lokasi_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        
        $allowed_ext = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'zip', 'rar'];
        $file_ext = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_ext)) {
            $pesan = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Format file tidak diizinkan!</div>";
        } else if ($ukuran_file > 10 * 1024 * 1024) { // Batas dinaikkan ke 10 MB
            $pesan = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Ukuran file terlalu besar! Maksimal 10 MB.</div>";
        } else {
            $nama_file_unik = uniqid() . '-' . basename($nama_file_asli);
            $path_tujuan = '../uploads/' . $nama_file_unik;

            if (move_uploaded_file($lokasi_tmp, $path_tujuan)) {
                // Query INSERT sekarang menyertakan kolom 'semester'
                $query = "INSERT INTO dokumen (nama, file, semester) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($koneksi, $query);
                
                // 'ssi' -> string, string, integer
                mysqli_stmt_bind_param($stmt, 'ssi', $nama_deskripsi, $nama_file_unik, $semester);

                if (mysqli_stmt_execute($stmt)) {
                    $pesan = "<div class='bg-green-500/20 text-green-300 p-3 rounded-md'>Upload dokumen berhasil!</div>";
                } else {
                    $pesan = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Error: Gagal menyimpan data ke database.</div>";
                }
                mysqli_stmt_close($stmt);
            } else {
                $pesan = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Error: Gagal memindahkan file.</div>";
            }
        }
    } else {
        $pesan = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Error: Tidak ada file yang dipilih atau terjadi kesalahan.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900 text-slate-200 flex">
    <aside class="sidebar p-6 flex flex-col justify-between w-64">
        <div>
            <h1 class="text-2xl font-bold text-cyan-400 mb-8 border-b border-cyan-800 pb-4">Admin Panel</h1>
            <nav class="flex flex-col space-y-3">
                <a href="<?= BASE_URL ?>/admin/dashboard.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-tachometer-alt w-6 mr-3"></i>Dashboard</a>
                <a href="<?= BASE_URL ?>/admin/kelola_artikel.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-newspaper w-6 mr-3"></i>Kelola Artikel</a>
                <a href="<?= BASE_URL ?>/admin/upload_dokumen.php" class="flex items-center p-3 bg-cyan-500/20 rounded-lg text-white font-bold"><i class="fas fa-file-upload w-6 mr-3"></i>Upload Dokumen</a>
                 <a href="<?= BASE_URL ?>/admin/arsip_dokumen.php" class="flex items-center p-3 text-slate-300 hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-archive w-6 mr-3"></i>Arsip Dokumen</a>
            </nav>
        </div>
        <div>
             <a href="<?= BASE_URL ?>/admin/logout.php" class="flex items-center p-3 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors"><i class="fas fa-sign-out-alt w-6 mr-3"></i>Logout</a>
        </div>
    </aside>

    <main class="main-content p-8 flex-grow">
        <h1 class="text-3xl font-bold text-cyan-400">Upload Dokumen Baru</h1>
        <p class="text-slate-400 mb-8">Tambahkan file atau materi perkuliahan ke dalam arsip.</p>
        
        <?php if (!empty($pesan)) { echo "<div class='mb-4 max-w-xl'>$pesan</div>"; } ?>

        <form action="<?= BASE_URL ?>/admin/upload_dokumen.php" method="POST" enctype="multipart/form-data" class="glass-card p-6 space-y-4 max-w-xl">
            <div>
                <label for="nama" class="block mb-1 text-sm font-medium text-slate-300">Nama/Deskripsi Dokumen</label>
                <input type="text" name="nama" id="nama" placeholder="Contoh: Makalah Kalkulus Pertemuan 5" class="w-full p-2.5 rounded-md bg-slate-700 text-white border border-slate-600" required>
            </div>
            
            <div>
                <label for="semester" class="block mb-1 text-sm font-medium text-slate-300">Arsipkan untuk Semester</label>
                <select name="semester" id="semester" class="w-full p-2.5 rounded-md bg-slate-700 text-white border border-slate-600" required>
                    <option value="">-- Pilih Semester --</option>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?= $i ?>">Semester <?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div>
                <label for="file" class="block mb-1 text-sm font-medium text-slate-300">Pilih File</label>
                <input type="file" name="file" id="file" class="w-full text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-cyan-500 file:text-slate-900 hover:file:bg-cyan-400" required>
                <p class="text-xs text-slate-500 mt-1">Tipe file: PDF, DOC, DOCX, JPG, PNG, ZIP, RAR. Ukuran maks: 10MB.</p>
            </div>
            <button type="submit" name="upload" class="w-full btn-neon btn-neon-fill">Upload Sekarang</button>
        </form>
    </main>
</body>
</html>