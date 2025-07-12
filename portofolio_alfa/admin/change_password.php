<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// PENTING: Include koneksi.php di awal file admin
require_once '../config/koneksi.php';

$pesan_status = '';
$username_admin = $_SESSION['admin_username'];

if (isset($_POST['ganti_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
        $pesan_status = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Semua field harus diisi!</div>";
    } elseif ($new_password !== $confirm_new_password) {
        $pesan_status = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Konfirmasi password baru tidak cocok!</div>";
    } elseif (strlen($new_password) < 6) {
        $pesan_status = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Password baru minimal 6 karakter!</div>";
    } else {
        $query_get_password = "SELECT password FROM admin WHERE username = ?";
        $stmt_get = mysqli_prepare($koneksi, $query_get_password);
        mysqli_stmt_bind_param($stmt_get, 's', $username_admin);
        mysqli_stmt_execute($stmt_get);
        $result_get = mysqli_stmt_get_result($stmt_get);
        $admin_data = mysqli_fetch_assoc($result_get);
        mysqli_stmt_close($stmt_get);

        if ($admin_data && password_verify($current_password, $admin_data['password'])) {
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            $query_update_password = "UPDATE admin SET password = ? WHERE username = ?";
            $stmt_update = mysqli_prepare($koneksi, $query_update_password);
            mysqli_stmt_bind_param($stmt_update, 'ss', $hashed_new_password, $username_admin);

            if (mysqli_stmt_execute($stmt_update)) {
                $pesan_status = "<div class='bg-green-500/20 text-green-300 p-3 rounded-md'>Password berhasil diganti!</div>";
            } else {
                $pesan_status = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Gagal mengganti password. Coba lagi.</div>";
            }
            mysqli_stmt_close($stmt_update);
        } else {
            $pesan_status = "<div class='bg-red-500/20 text-red-300 p-3 rounded-md'>Password lama salah!</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- PENTING: Link ke file CSS utama Anda menggunakan BASE_URL -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="bg-slate-900 text-slate-200">
    <div class="container mx-auto p-8 max-w-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-cyan-400">Ganti Password Admin</h1>
            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="text-sm text-cyan-400 hover:underline">&larr; Kembali ke Dashboard</a>
        </div>

        <?php if (!empty($pesan_status)) { echo "<div class='mb-4'>$pesan_status</div>"; } ?>

        <form action="<?= BASE_URL ?>/admin/change_password.php" method="POST" class="glass-card p-6 space-y-4">
            <div>
                <label for="current_password" class="block mb-1 text-sm font-medium text-slate-300">Password Lama</label>
                <input type="password" name="current_password" id="current_password" class="w-full p-2.5 rounded-md bg-slate-700 text-white border border-slate-600 focus:outline-none focus:border-cyan-500" required>
            </div>
            <div>
                <label for="new_password" class="block mb-1 text-sm font-medium text-slate-300">Password Baru</label>
                <input type="password" name="new_password" id="new_password" class="w-full p-2.5 rounded-md bg-slate-700 text-white border border-slate-600 focus:outline-none focus:border-cyan-500" required>
            </div>
            <div>
                <label for="confirm_new_password" class="block mb-1 text-sm font-medium text-slate-300">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_new_password" id="confirm_new_password" class="w-full p-2.5 rounded-md bg-slate-700 text-white border border-slate-600 focus:outline-none focus:border-cyan-500" required>
            </div>
            <button type="submit" name="ganti_password" class="w-full btn-neon btn-neon-fill">Ganti Password</button>
        </form>
    </div>
</body>
</html>
