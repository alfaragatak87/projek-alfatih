<?php
// Definisikan BASE_URL untuk path yang konsisten
// Ini penting agar link bisa diakses dengan benar dari mana saja.
$base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/portofolio_alfa";
if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Portofolio Muhammad Alfatih</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css"> 
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Exo 2', sans-serif;
            background: #0f172a;
        }
        .splash-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            flex-direction: column;
            padding: 1rem;
            background: linear-gradient(180deg, #000510 0%, #00081a 50%, #000c25 100%);
        }
        .splash-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: white;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 15px rgba(34, 211, 238, 0.5);
        }
        .splash-subtitle {
            font-size: 1.2rem;
            color: #94a3b8;
            margin-bottom: 3rem;
        }
        .splash-buttons {
            display: flex;
            gap: 1.5rem;
            flex-direction: row;
        }
        .splash-btn {
            padding: 0.8rem 2.5rem;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.4s ease;
            text-decoration: none;
            display: inline-block;
            min-width: 200px;
        }
        .btn-visitor {
            background: #22d3ee;
            border: 2px solid #22d3ee;
            color: #0f172a;
        }
        .btn-visitor:hover {
            background: transparent;
            color: #22d3ee;
            box-shadow: 0 0 20px #22d3ee;
        }
        .btn-admin {
            background: transparent;
            border: 2px solid #818cf8;
            color: #818cf8;
        }
        .btn-admin:hover {
            background: #818cf8;
            color: #0f172a;
            box-shadow: 0 0 20px #818cf8;
        }
    </style>
</head>
<body>
    <div class="splash-container">
        <h1 class="splash-title">Welcome to my Portfolio</h1>
        <p class="splash-subtitle">Muhammad Alfatih</p>
        
        <div class="splash-buttons">
            <a href="<?= BASE_URL ?>/pages/index.php" class="splash-btn btn-visitor">Pengunjung</a>
            <a href="<?= BASE_URL ?>/admin/login.php" class="splash-btn btn-admin">Admin</a>
        </div>
    </div>
</body>
</html>