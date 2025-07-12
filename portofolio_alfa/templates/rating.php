<?php
$page_title = "Holographic Rating System | Alfatih";
require_once '../templates/header.php';
?>

<section class="holographic-rating min-h-screen pt-24 pb-16 px 4">
    <div class="container mx-auto max-w-3xl">
        <!-- Hologram Container -->
        <div class="hologram-device bg-gray-800/70 backdrop-blur-md rounded-2xl overflow-hidden border border-cyan-500/20 shadow-xl">
            <!-- Header -->
            <div class="hologram-header bg-gradient-to-r from-cyan-900/30 to-blue-900/30 p-5 border-b border-cyan-500/20 flex items-center">
                <div class="hologram-controls flex space-x-2 mr-4">
                    <span class="w-3 h-3 rounded-full bg-red[#ff5f56]"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                </div>
                <h3 class="text-lg font-mono text-cyan-300">FEEDBACK_TERMINAL</h3>
            </div>

            <!-- Main Content -->
            <div class="p-6 md:p-8">
                <!-- Rating Orb -->
                <div class="rating-orb mx-auto mb-12 relative">
                    <div class="orb-base"></div>
                    <div class="orb-glow"></div>
                    <div class="stars-container flex justify-center">
                        <?php for ($i=1; $i<=5; $i++): ?>
                        <div class="star mx-1 text-4xl cursor-pointer text-gray-500 hover:text-yellow-400 transition-colors duration-300" 
                             data-value="<?= $i ?>">
                            <i class="far fa-star"></i>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Feedback Form -->
                <form class="space-y-6">
                    <div class="form-group">
                        <label class="block text-sm font-mono text-cyan-300 mb-2">USER_IDENTITY</label>
                        <input type="text" class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 font-mono outline-none transition"
                               placeholder="Enter your name">
                    </div>

                    <div class="form-group">
                        <label class="block text-sm font-mono text-cyan-300 mb-2">FEEDBACK_INPUT</label>
                        <textarea class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 font-mono outline-none transition min-h-[120px]"
                                  placeholder="Share your experience..."></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 rounded-lg font-bold text-white hover:shadow-lg hover:shadow-cyan-500/30 transition-all duration-300 group relative overflow-hidden">
                            <span class="absolute inset-0 bg-cyan-500/30 opacity-0 group-hover:opacity-100 transition duration-700"></span>
                            <span class="relative flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                TRANSMIT_FEEDBACK
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
// Star Rating Logic
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('mouseover', function() {
        const value = parseInt(this.getAttribute('data-value'));
        
        // Highlight stars
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
    
    star.addEventListener('click', function() {
        const value = parseInt(this.getAttribute('data-value'));
        localStorage.setItem('portfolioRating', value);
        
        // Confetti effect
        createConfetti(this);
    });
});

function createConfetti(element) {
    const colors = ['#3b82f6', '#22d3ee', '#f59e0b', '#10b981'];
    const count = 50;
    
    for (let i = 0; i < count; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti-particle';
        confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.left = `${Math.random() * 100}%`;
        confetti.style.top = `${Math.random() * 100}%`;
        confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
        confetti.style.animationDuration = `${Math.random() * 2 + 1}s`;
        
        element.appendChild(confetti);
        
        setTimeout(() => {
            confetti.remove();
        }, 3000);
    }
}
</script>

<style>
.holographic-rating {
    background: radial-gradient(ellipse at center, rgba(15,23,42,0.8) 0%, rgba(2,6,23,0.9) 100%);
}

.hologram-device {
    box-shadow: 0 0 40px rgba(34, 211, 238, 0.1);
    transition: all 0.5s ease;
}

.hologram-device:hover {
    box-shadow: 0 0 50px rgba(34, 211, 238, 0.2);
}

.rating-orb {
    width: 250px;
    height: 250px;
    margin: 0 auto;
}

.orb-base {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: radial-gradient(circle at center, rgba(34,211,238,0.1) 0%, transparent 70%);
    box-shadow: inset 0 0 50px rgba(34,211,238,0.3);
}

.confetti-particle {
    position: absolute;
    width: 8px;
    height: 8px;
    opacity: 0.8;
    animation: confettiFall linear forwards;
}

@keyframes confettiFall {
    to {
        transform: translateY(100vh) rotate(360deg);
        opacity: 0;
    }
}
</style>

<?php require_once '../templates/footer.php'; ?>
