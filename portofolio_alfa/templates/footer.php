<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/koneksi.php'; 
}
?>
</main> <footer class="bg-slate-100 text-slate-600 pt-16 pb-8 relative overflow-hidden dark:bg-gray-900/90 dark:border-t dark:border-cyan-900/20 dark:text-gray-300 transition-colors duration-500">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-sky-500 dark:text-cyan-400 flex items-center">
                    <span class="w-6 h-6 rounded-full bg-sky-500 dark:bg-cyan-500 mr-3 animate-pulse"></span>
                    Alfatih
                </h3>
                <p class="text-sm leading-relaxed">
                    Mahasiswa Informatika ITB Widyagama Lumajang, ahli dalam pengembangan web modern.
                </p>
                <div class="flex space-x-4 pt-2">
                    <?php 
                    $socials = [
                        ['github', 'https://github.com/alfaragatak87'],
                        ['instagram', 'https://instagram.com/alfamuhammad___'],
                        ['whatsapp', 'https://wa.me/6283188813237']
                    ];
                    foreach($socials as $s): ?>
                    <a href="<?= $s[1] ?>" target="_blank" 
                       class="social-icon w-8 h-8 flex items-center justify-center rounded-full bg-slate-200 dark:bg-gray-800 hover:bg-sky-500 dark:hover:bg-cyan-600 transition-all hover:text-white dark:hover:text-white hover:rotate-12">
                        <i class="fab fa-<?= $s[0] ?> text-sm"></i>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Navigation</h3>
                <ul class="space-y-2">
                     <?php 
                    $links = [ 'Home' => 'index.php', 'About' => 'about.php', 'Projects' => 'projects.php', 'Blog' => 'blog.php', 'Contact' => 'contact.php' ];
                    foreach($links as $text => $link): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/pages/<?= $link ?>" class="hover:text-sky-500 dark:hover:text-cyan-400 transition-colors flex items-center group">
                            <span class="w-2 h-2 rounded-full bg-sky-500 dark:bg-cyan-500 mr-3 opacity-0 group-hover:opacity-100 transition"></span>
                            <?= $text ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Contact</h3>
                <address class="not-italic space-y-2">
                    <p class="flex items-start"><i class="fas fa-map-marker-alt text-sky-500 dark:text-cyan-400 mt-1 mr-3"></i><span>Lumajang, Jawa Timur</span></p>
                    <p class="flex items-center"><i class="fas fa-phone-alt text-sky-500 dark:text-cyan-400 mr-3"></i><a href="tel:+6283188813237" class="hover:text-sky-500 dark:hover:text-cyan-400 transition">+62 831-8881-3237</a></p>
                    <p class="flex items-center"><i class="fas fa-id-card text-sky-500 dark:text-cyan-400 mr-3"></i><span>NIM: 224140248</span></p>
                </address>
            </div>

            <div class="space-y-4">
                 <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Updates</h3>
                <form class="space-y-3">
                    <input type="email" placeholder="Your email" class="w-full px-4 py-2 rounded bg-slate-200 dark:bg-gray-800 border border-slate-300 dark:border-gray-700 focus:border-sky-500 dark:focus:border-cyan-400 focus:ring-1 focus:ring-sky-500/50 dark:focus:ring-cyan-400/50 outline-none transition">
                    <button type="submit" class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-sky-600 to-blue-700 dark:from-cyan-600 dark:to-blue-700 text-white rounded hover:shadow-lg hover:shadow-sky-500/30 dark:hover:shadow-cyan-500/30 transition-all">Subscribe</button>
                </form>
            </div>
        </div>

        <div class="pt-8 mt-8 border-t border-slate-200 dark:border-gray-800">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p>&copy; <?= date('Y') ?> Muhammad Alfatih. All rights reserved.</p>
                <p class="text-sm mt-2 md:mt-0">Made with <i class="fas fa-heart text-red-500 mx-1"></i> in Lumajang</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://unpkg.com/scrollreveal"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles@3.4.0/tsparticles.bundle.min.js"></script>
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // --- FUNGSI UNTUK NAVBAR BARU (SLIDING HIGHLIGHTER) ---
        function handleSlidingNav() {
            const nav = document.querySelector('.sliding-navbar');
            if (!nav) return;
            const highlighter = nav.querySelector('.nav-highlighter');
            const navItems = nav.querySelectorAll('.nav-item');
            const activeItem = nav.querySelector('.nav-item.active');

            function highlightItem(item) {
                if (!item || !highlighter) return;
                const itemRect = item.getBoundingClientRect();
                highlighter.style.width = `${itemRect.width}px`;
                highlighter.style.transform = `translateX(${item.offsetLeft - nav.firstElementChild.offsetLeft}px)`;
            }

            if (activeItem) {
                setTimeout(() => highlightItem(activeItem), 150);
            }
            navItems.forEach(item => {
                item.addEventListener('mouseenter', () => highlightItem(item));
            });
            nav.addEventListener('mouseleave', () => {
                if (activeItem) highlightItem(activeItem);
            });
        }
        
        // --- FUNGSI UNTUK TOMBOL TEMA BARU (DARI CODEPEN) ---
        function handleNewThemeToggle() {
            const toggle = document.querySelector('.theme-toggle-button');
            if (!toggle) return;

            const handleToggle = () => {
                const isPressed = toggle.matches('[aria-pressed="true"]');
                toggle.setAttribute('aria-pressed', isPressed ? 'false' : 'true');
                const newTheme = isPressed ? 'dark' : 'light';
                document.documentElement.dataset.theme = newTheme;
                localStorage.setItem('theme', newTheme);
            };
            
            const currentTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.dataset.theme = currentTheme;
            if (currentTheme === 'light') {
                 toggle.setAttribute('aria-pressed', 'true');
            } else {
                 toggle.setAttribute('aria-pressed', 'false');
            }
            toggle.addEventListener('click', handleToggle);
        }

        // --- PANGGIL SEMUA FUNGSI INISIALISASI ---
        handleSlidingNav();
        handleNewThemeToggle();

        // Inisialisasi Partikel
        if (typeof tsParticles !== 'undefined') {
            tsParticles.load({ id: "particles-js", options: { background:{color:{value:"#0d1117"}},fpsLimit:60,interactivity:{events:{onHover:{enable:true,mode:"repulse"}},modes:{repulse:{distance:100,duration:.4}}},particles:{color:{value:"#22d3ee"},links:{color:"#4a5568",distance:150,enable:true,opacity:.2,width:1},move:{direction:"none",enable:true,outModes:"out",random:false,speed:1,straight:false},number:{density:{enable:true},value:80},opacity:{value:.3},shape:{type:"circle"},size:{value:{min:1,max:3}}},detectRetina:true }});
        }
        
        // Inisialisasi Galeri Proyek Isotope
        const projectGrid = document.querySelector('.project-grid');
        if (projectGrid && typeof Isotope !== 'undefined') {
            const iso = new Isotope(projectGrid, { itemSelector: '.project-card-wrapper', layoutMode: 'fitRows' });
            const filterButtons = document.querySelectorAll('#filter-buttons button');
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filterValue = button.getAttribute('data-filter');
                    iso.arrange({ filter: filterValue });
                    filterButtons.forEach(btn => btn.classList.remove('is-checked'));
                    button.classList.add('is-checked');
                });
            });
        }
    });

    // --- LOGIKA UNTUK PRELOADER ---
    window.addEventListener('load', () => {
        const preloader = document.querySelector('.preloader');
        if (preloader) {
            preloader.classList.add('hidden');
        }
    });
</script>

</body>
</html>