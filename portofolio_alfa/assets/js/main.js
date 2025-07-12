document.addEventListener('DOMContentLoaded', function() {

    // --- LOGIKA UNTUK MENU HAMBURGER (MOBILE) ---
    const menuBtn = document.getElementById('mobile-menu-button'); // Menggunakan ID yang benar
    const mobileMenu = document.getElementById('mobile-menu');
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // --- EFEK TYPEWRITER ---
    // Pastikan elemen dengan class 'typewriter-text' ada di halaman
    // Logika Typewriter sudah dipindahkan ke bagian bawah file ini (global scope)
    // dan diinisialisasi di DOMContentLoaded.
    // Jadi bagian ini tidak perlu ada lagi untuk Typewriter.

    // --- EFEK GLOWING NAMA ---
    const glowingName = document.querySelector('.glowing-name');
    if (glowingName) {
        const text = glowingName.textContent.trim();
        glowingName.innerHTML = '';
        for (let char of text) {
            const span = document.createElement('span');
            span.textContent = (char === ' ') ? '\u00A0' : char;
            glowingName.appendChild(span);
        }
    }

    // --- INISIALISASI SCROLLREVEAL ---
    // Pastikan Anda telah menyertakan library ScrollReveal di header.php atau sebelum main.js
    if (typeof ScrollReveal === 'function') {
        const sr = ScrollReveal({
            distance: '60px',
            duration: 1500,
            easing: 'cubic-bezier(0.5, 0, 0, 1)',
            reset: false
        });
        sr.reveal('h1, h2, h3, .hero-image-container', { origin: 'top' });
        sr.reveal('p, label, li, footer, .btn-neon', { origin: 'bottom', delay: 200 });
        sr.reveal('.glass-card, form', { origin: 'left', delay: 300, interval: 100 });
    }

    // --- LOGIKA NAVBAR BARU (CODEPEN STYLE) ---
    const nav = document.querySelector('.codepen-nav');
    if (nav) {
        const highlighter = nav.querySelector('.nav-highlighter');
        const navItems = nav.querySelectorAll('.nav-item');
        const activeItem = nav.querySelector('.nav-item.active');

        function highlightItem(item) {
            if (item && highlighter) {
                const itemRect = item.getBoundingClientRect();
                const navRect = nav.getBoundingClientRect();

                highlighter.style.width = `${itemRect.width}px`;
                highlighter.style.transform = `translateX(${item.offsetLeft - nav.firstElementChild.offsetLeft}px)`;
            }
        }

        if (activeItem) {
            setTimeout(() => highlightItem(activeItem), 150);
        }

        navItems.forEach(item => {
            item.addEventListener('mouseenter', () => highlightItem(item));
        });

        nav.addEventListener('mouseleave', () => {
            if (activeItem) {
                highlightItem(activeItem);
            } else if (highlighter) {
                highlighter.style.width = '0';
            }
        });
    }

    // --- INTERACTIVE CURSOR EFFECT ---
    // Membuat kepala kursor
    const cursorFollower = document.createElement('div');
    cursorFollower.classList.add('cursor-follower');
    document.body.appendChild(cursorFollower);

    // Membuat elemen-elemen "ekor"
    const numTails = 5; // Jumlah ekor yang diinginkan
    const cursorTails = [];
    for (let i = 0; i < numTails; i++) {
        const tail = document.createElement('div');
        tail.classList.add('cursor-tail');
        tail.style.width = `${10 - i * 1.5}px`; // Ekor semakin kecil
        tail.style.height = `${10 - i * 1.5}px`; // Ekor semakin kecil
        document.body.appendChild(tail);
        cursorTails.push(tail);
    }

    let mouseX = 0;
    let mouseY = 0;
    let cursorX = 0;
    let cursorY = 0;

    // Posisi untuk setiap ekor
    const points = [];
    for (let i = 0; i < numTails + 1; i++) { // +1 untuk kepala kursor
        points.push({ x: 0, y: 0 });
    }

    function animateCursor() {
        // Update posisi kepala kursor
        cursorX += (mouseX - cursorX) * 0.1;
        cursorY += (mouseY - cursorY) * 0.1;

        cursorFollower.style.left = `${cursorX}px`;
        cursorFollower.style.top = `${cursorY}px`;

        // Update posisi untuk setiap titik dalam 'points'
        points[0].x = mouseX;
        points[0].y = mouseY;

        for (let i = 1; i < points.length; i++) {
            // Lerp setiap titik menuju titik sebelumnya
            points[i].x += (points[i - 1].x - points[i].x) * 0.3; // Kecepatan ekor
            points[i].y += (points[i - 1].y - points[i].y) * 0.3;

            // Set posisi elemen ekor
            if (i - 1 < numTails) { // Pastikan tidak melebihi jumlah ekor
                cursorTails[i - 1].style.left = `${points[i].x}px`;
                cursorTails[i - 1].style.top = `${points[i].y}px`;
            }
        }

        requestAnimationFrame(animateCursor);
    }

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    animateCursor();

    const interactiveElements = document.querySelectorAll('a, button, .btn-neon, .glass-card');

    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', () => {
            document.body.classList.add('hovering-interactive');
        });
        element.addEventListener('mouseleave', () => {
            document.body.classList.remove('hovering-interactive');
        });
    });

    // Star Rating (dari rating.php, dipindahkan ke sini agar terpusat)
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            document.querySelectorAll('.star').forEach((s, i) => {
                s.classList.toggle('text-yellow-400', i < value); // Menggunakan toggle untuk class
                s.innerHTML = (i < value) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
            });
            // createStarEffect(this, value); // Jika fungsi ini ada dan ingin digunakan
        });

        star.addEventListener('mouseover', function() {
            const value = parseInt(this.getAttribute('data-value'));
            document.querySelectorAll('.star').forEach((s, i) => {
                if (i < value) {
                    s.classList.add('text-yellow-400');
                    s.innerHTML = '<i class="fas fa-star"></i>';
                } else {
                    s.classList.remove('text-yellow-400');
                    s.innerHTML = '<i class="far fa-star"></i>';
                }
            });
        });
        star.addEventListener('mouseout', function() {
            // Reset stars to current selected value or default if none
            const selectedValue = localStorage.getItem('portfolioRating') || 0;
            document.querySelectorAll('.star').forEach((s, i) => {
                if (i < selectedValue) {
                    s.classList.add('text-yellow-400');
                    s.innerHTML = '<i class="fas fa-star"></i>';
                } else {
                    s.classList.remove('text-yellow-400');
                    s.innerHTML = '<i class="far fa-star"></i>';
                }
            });
        });
    });
});

// Typewriter Effect (Ditempatkan di global scope agar bisa diakses oleh DOMContentLoaded)
class TypeWriter {
    constructor(el, period) {
        this.el = el;
        // Pastikan data-rotate di-parse sebagai array
        this.toRotate = JSON.parse(el.getAttribute('data-rotate')); 
        this.loopNum = 0;
        this.period = parseInt(period, 10) || 2000;
        this.txt = '';
        this.tick();
        this.isDeleting = false;
    }

    tick() {
        const i = this.loopNum % this.toRotate.length;
        const fullTxt = this.toRotate[i];

        if (this.isDeleting) {
            this.txt = fullTxt.substring(0, this.txt.length - 1);
        } else {
            this.txt = fullTxt.substring(0, this.txt.length + 1);
        }

        this.el.innerHTML = `<span class="wrap">${this.txt}</span>`;

        let delta = 200 - Math.random() * 100;

        if (this.isDeleting) {
            delta /= 2;
        }

        if (!this.isDeleting && this.txt === fullTxt) {
            delta = this.period;
            this.isDeleting = true;
        } else if (this.isDeleting && this.txt === '') {
            this.isDeleting = false;
            this.loopNum++;
            delta = 500;
        }

        setTimeout(() => this.tick(), delta);
    }
}
