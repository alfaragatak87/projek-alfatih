<?php
// Mulai session di baris paling awal
session_start();

// Panggil file koneksi
require_once '../config/koneksi.php';

// Jika admin sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: " . BASE_URL . "/admin/dashboard.php"); // Menggunakan BASE_URL
    exit();
}

// Inisialisasi variabel untuk menampung pesan error
$error = '';

// Cek jika form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi dasar agar tidak kosong
    if (empty($username) || empty($password)) {
        $error = 'Username dan password tidak boleh kosong!';
    } else {
        // Query aman dengan prepared statement
        $sql = "SELECT id, username, password FROM admin WHERE username = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Set session jika berhasil
                $_SESSION['admin_logged_in'] = true; 
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_id'] = $user['id'];
                
                header("Location: " . BASE_URL . "/admin/dashboard.php");
                exit();
            } else {
                $error = 'Username atau password salah!';
            }
        } else {
            $error = 'Username atau password salah!';
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Portofolio Alfatih</title>
    <style>
        html {
            height: 100%;
        }
        body {
            margin:0;
            padding:0;
            font-family: sans-serif;
            background: linear-gradient(#141e30, #243b55);
        }

        .login-box {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 400px;
            padding: 40px;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,.5);
            box-sizing: border-box;
            box-shadow: 0 15px 25px rgba(0,0,0,.6);
            border-radius: 10px;
        }

        .login-box h2 {
            margin: 0 0 30px;
            padding: 0;
            color: #fff;
            text-align: center;
        }

        .login-box .user-box {
            position: relative;
        }

        .login-box .user-box input {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            color: #fff;
            margin-bottom: 30px;
            border: none;
            border-bottom: 1px solid #fff;
            outline: none;
            background: transparent;
        }
        .login-box .user-box label {
            position: absolute;
            top:0;
            left: 0;
            padding: 10px 0;
            font-size: 16px;
            color: #fff;
            pointer-events: none;
            transition: .5s;
        }

        .login-box .user-box input:focus ~ label,
        .login-box .user-box input:valid ~ label {
            top: -20px;
            left: 0;
            color: #03e9f4;
            font-size: 12px;
        }

        .login-box form button {
            position: relative;
            display: inline-block;
            padding: 10px 20px;
            color: #03e9f4;
            font-size: 16px;
            text-decoration: none;
            text-transform: uppercase;
            overflow: hidden;
            transition: .5s;
            margin-top: 20px;
            letter-spacing: 4px;
            background: transparent;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: center;
        }

        .login-box button:hover {
            background: #03e9f4;
            color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px #03e9f4,
                        0 0 25px #03e9f4,
                        0 0 50px #03e9f4,
                        0 0 100px #03e9f4;
        }
        
        .error-message {
            color: #ff4d4d;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #888;
            font-size: 14px;
            text-decoration: none;
        }
        .back-link:hover {
            color: #fff;
        }

    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <form action="<?= BASE_URL ?>/admin/login.php" method="POST">
            <div class="user-box">
                <input type="text" name="username" required="">
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" name="password" required="">
                <label>Password</label>
            </div>
            
            <?php if($error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>

            <button type="submit">
                Login
            </button>
        </form>
        <a href="<?= BASE_URL ?>/index.php" class="back-link">&larr; Kembali ke Halaman Utama</a>
    </div>
</body>
</html>