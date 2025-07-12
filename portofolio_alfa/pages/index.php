<?php
// PENTING: Include koneksi.php di awal setiap file PHP di folder 'pages'
require_once '../config/koneksi.php'; 

$page_title = "Quantum Portfolio | Alfatih";
// PENTING: Include header.php setelah koneksi.php
require_once __DIR__ . '/../templates/header.php';
?>

<div class="quantum-tunnel fixed inset-0 -z-10">
    <div class="tunnel-wall"></div>
    <div class="tunnel-particles"></div>
</div>

<main class="relative min-h-screen flex items-center justify-center pt-20 px-4">
    <div class="container mx-auto text-center">
        <div class="name-container mb-12 transform perspective-1000">
            <h1 class="neon-name text-5xl sm:text-7xl md:text-8xl font-bold leading-tight mb-4">
                <span class="name-gradient-1" data-text="MUHAMMAD">MUHAMMAD</span>
                <span class="name-gradient-2" data-text="ALFATIH">ALFATIH</span>
            </h1>
            <div class="quantum-underline mx-auto"></div>
        </div>

        <div class="typewriter-container mb-16">
            <h2 class="text-xl md:text-3xl text-gray-300">
                <span id="scramble-text" class="font-mono"></span> 
            </h2>
        </div>

        <div class="action-buttons flex flex-wrap justify-center gap-4 sm:gap-6">
            <a href="<?= BASE_URL ?>/pages/projects.php" class="btn-neon btn-neon-fill">
                <i class="fas fa-rocket mr-2"></i> Lihat Proyek
            </a>
            <a href="<?= BASE_URL ?>/pages/contact.php" class="btn-neon btn-neon-outline">
                <i class="fas fa-comment-dots mr-2"></i> Hubungi Saya
            </a>
        </div>
    </div>
</main>

<script>
class TextScramble {
  constructor(el) {
    this.el = el;
    this.chars = '!<>-_\\/[]{}—=+*^?#________';
    this.update = this.update.bind(this);
  }

  setText(newText) {
    const oldText = this.el.innerText;
    const length = Math.max(oldText.length, newText.length);
    const promise = new Promise((resolve) => this.resolve = resolve);
    this.queue = [];
    for (let i = 0; i < length; i++) {
      const from = oldText[i] || '';
      const to = newText[i] || '';
      const start = Math.floor(Math.random() * 40);
      const end = start + Math.floor(Math.random() * 40);
      this.queue.push({ from, to, start, end });
    }
    cancelAnimationFrame(this.frameRequest);
    this.frame = 0;
    this.update();
    return promise;
  }

  update() {
    let output = '';
    let complete = 0;
    for (let i = 0, n = this.queue.length; i < n; i++) {
      let { from, to, start, end, char } = this.queue[i];
      if (this.frame >= end) {
        complete++;
        output += to;
      } else if (this.frame >= start) {
        if (!char || Math.random() < 0.28) {
          char = this.randomChar();
          this.queue[i].char = char;
        }
        output += `<span class="scramble-char">${char}</span>`;
      } else {
        output += from;
      }
    }
    this.el.innerHTML = output;
    if (complete === this.queue.length) {
      this.resolve();
    } else {
      this.frameRequest = requestAnimationFrame(this.update);
      this.frame++;
    }
  }

  randomChar() {
    return this.chars[Math.floor(Math.random() * this.chars.length)];
  }
}

document.addEventListener('DOMContentLoaded', () => {
    const phrases = [
        'Full-Stack Developer',
        'UI/UX Enthusiast',
        'ITB Widyagama Student',
        'Tech Explorer',
        'Problem Solver'
    ];

    const el = document.getElementById('scramble-text');
    const fx = new TextScramble(el);

    let counter = 0;
    const next = () => {
        fx.setText(phrases[counter]).then(() => {
            setTimeout(next, 2000);
        });
        counter = (counter + 1) % phrases.length;
    };

    next();
});

</script>

<?php 
require_once __DIR__ . '/../templates/footer.php'; 
?>