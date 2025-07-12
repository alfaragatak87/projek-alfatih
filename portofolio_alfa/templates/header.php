<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!defined('BASE_URL')) { require_once __DIR__ . '/../config/koneksi.php'; }
$page_title = $page_title ?? "Portofolio Hyperspace | Alfatih";
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Exo+2:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

    <style>
        /* CSS Lengkap untuk semua fitur */
        :root { --font-size: 32px; }
        body { font-family: 'Exo 2', sans-serif; }
        #particles-js{position:fixed;width:100%;height:100%;top:0;left:0;z-index:-1}
        .preloader{position:fixed;top:0;left:0;width:100%;height:100%;background-color:#0f172a;display:flex;justify-content:center;align-items:center;z-index:9999;transition:opacity .75s ease,visibility .75s ease}
        .preloader.hidden{opacity:0;visibility:hidden}
        .loader-name{font-family:'Orbitron',sans-serif;font-size:2.5rem;font-weight:700;color:#22d3ee;text-shadow:0 0 15px rgba(34,211,238,.7);animation:pulse-loader 1.5s infinite alternate}
        @keyframes pulse-loader{from{opacity:.6;transform:scale(.95)}to{opacity:1;transform:scale(1.05)}}
        
        .main-header{position:sticky;top:0;z-index:50;background:rgba(15,23,42,.8);backdrop-filter:blur(10px);border-bottom:1px solid rgba(55,65,81,.5)}
        html:not([data-theme=dark]) .main-header{background:rgba(255,255,255,.8);border-bottom-color:rgba(0,0,0,.1)}
        
        .sliding-navbar{display:flex;position:relative;background:rgba(30,41,59,.5);padding:8px;border-radius:999px;border:1px solid rgba(55,65,81,.7)}
        html:not([data-theme=dark]) .sliding-navbar{background:rgba(229,231,235,.8);border:1px solid #d1d5db}
        .sliding-navbar .nav-item{position:relative;padding:10px 24px;font-size:.9rem;font-weight:600;color:#cbd5e1;text-decoration:none;z-index:10;transition:color .3s ease}
        html:not([data-theme=dark]) .sliding-navbar .nav-item{color:#374151}
        .sliding-navbar .nav-item.active,.sliding-navbar .nav-item:hover{color:#fff}
        html:not([data-theme=dark]) .sliding-navbar .nav-item.active,html:not([data-theme=dark]) .sliding-navbar .nav-item:hover{color:#000}
        .nav-highlighter{content:"";position:absolute;top:8px;bottom:8px;left:8px;border-radius:999px;background-color:#22d3ee;box-shadow:0 0 15px rgba(34,211,238,.5);transition:all .4s cubic-bezier(.23,1,.32,1);z-index:5}
        html:not([data-theme=dark]) .nav-highlighter{background-color:#fff;box-shadow:0 2px 10px rgba(0,0,0,.1)}
        
        .admin-link{font-size:.9rem;font-weight:600;padding:10px 16px;border-radius:999px;transition:all .3s ease}
        .admin-login{background:rgba(34,211,238,.1);color:#22d3ee;border:1px solid rgba(34,211,238,.3)}
        .admin-login:hover{background:rgba(34,211,238,.2);box-shadow:0 0 10px rgba(34,211,238,.3)}
        html:not([data-theme=dark]) .admin-login{background:rgba(0,122,255,.1);color:#007aff;border:1px solid rgba(0,122,255,.3)}
        html:not([data-theme=dark]) .admin-login:hover{background:rgba(0,122,255,.2)}
        .dashboard-link{color:#d1d5db}
        .dashboard-link:hover{color:#fff}
        html:not([data-theme=dark]) .dashboard-link{color:#4b5563}
        html:not([data-theme=dark]) .dashboard-link:hover{color:#000}

        /* CSS untuk Tombol Tema Baru */
        .theme-toggle-button{background:#0000;font-size:var(--font-size);height:2.5em;padding:0;border-radius:3em;border:0;aspect-ratio:1.8/1;position:relative;cursor:pointer}
        .theme-toggle-button :is(.socket,.face){position:absolute;border-radius:3em}
        .socket{box-shadow:-.05em .1em .2em -.2em #fff;background:hsl(0 0% 0%);inset:0}
        html[data-theme=light] .socket{background:hsl(0 0% 90%)}
        .face{inset:.15em}
    </style>
</head>
<body class="transition-colors duration-500">

<svg display="none"><symbol id="moon-and-stars" viewBox="0 0 24 24"></symbol></svg>
<div class="preloader"><div class="loader-name">ALFATIH</div></div>
<div id="particles-js"></div>

<header class="main-header">
    <div class="container mx-auto px-6">
        <div class="relative flex justify-between items-center h-20">
            
            <div class="flex-1 flex justify-start">
                <a href="<?= BASE_URL ?>/index.php" class="flex items-center group">
                    <span class="text-2xl font-['Orbitron'] font-bold bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-500">ALFATIH</span>
                </a>
            </div>
            
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 hidden md:block">
                <nav class="sliding-navbar">
                    <div class="nav-highlighter"></div>
                    <a href="<?= BASE_URL ?>/pages/index.php" class="nav-item <?= ($current_page == 'index.php') ? 'active' : '' ?>">Home</a>
                    <a href="<?= BASE_URL ?>/pages/about.php" class="nav-item <?= ($current_page == 'about.php') ? 'active' : '' ?>">About</a>
                    <a href="<?= BASE_URL ?>/pages/projects.php" class="nav-item <?= ($current_page == 'projects.php') ? 'active' : '' ?>">Projects</a>
                    <a href="<?= BASE_URL ?>/pages/blog.php" class="nav-item <?= ($current_page == 'blog.php') ? 'active' : '' ?>">Blog</a>
                    <a href="<?= BASE_URL ?>/pages/contact.php" class="nav-item <?= ($current_page == 'contact.php') ? 'active' : '' ?>">Contact</a>
                </nav>
            </div>
            
            <div class="hidden md:flex flex-1 justify-end items-center space-x-4">
                <?php if (isset($_SESSION['admin_logged_in'])): ?>
                    <a href="<?= BASE_URL ?>/admin/dashboard.php" class="admin-link dashboard-link">Dashboard</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/admin/login.php" class="admin-link admin-login">Admin Login</a>
                <?php endif; ?>
                
                <button aria-pressed="false" class="toggle theme-toggle-button">
                    <span class="sr-only">Toggle Theme</span>
                    <div class="socket"><div class="socket-shadow"></div></div>
                    <div class="face">
                         <div class="face-shadow"></div><div class="face-glowdrop"></div><div class="face-plate"></div><div class="face-shine"><div class="face-shine-shadow"></div></div><div class="face-glows"><div></div></div>
                         <svg class="glow" viewBox="0 0 24 24"><path class="glow-path" d="M9.8815 1.36438...Z" stroke-width="0"/></svg>
                         <svg class="main" viewBox="0 0 24 24"><use href="#moon-and-stars"></use></svg>
                    </div>
                </button>
            </div>

            <button id="mobile-menu-button" class="md:hidden text-gray-300"><i class="fas fa-bars text-2xl"></i></button>
        </div>
    </div>
</header>

<main class="relative z-10">