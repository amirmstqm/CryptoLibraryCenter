/**
 * landing.js — CryptoPortal Landing Page
 * Particle canvas animations + dark-mode toggle
 */

document.addEventListener('DOMContentLoaded', () => {
    initParticleCanvas('heroCanvas');
    initParticleCanvas('ctaCanvas');
    initDarkToggle();
    initScrollReveal();
});

/* ── Particle Canvas ───────────────────────── */
function initParticleCanvas(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const COUNT = 55;
    let particles = [];
    let animId;

    function resize() {
        canvas.width  = canvas.parentElement.offsetWidth;
        canvas.height = canvas.parentElement.offsetHeight;
    }

    function makeParticle() {
        return {
            x:      Math.random() * canvas.width,
            y:      Math.random() * canvas.height,
            size:   Math.random() * 2.5 + 0.8,
            vx:     (Math.random() - 0.5) * 1.2,
            vy:     (Math.random() - 0.5) * 1.2,
            alpha:  Math.random() * 0.5 + 0.25,
            r:      Math.floor(Math.random() * 60  + 140),
            g:      Math.floor(Math.random() * 80  + 160),
            b:      255,
        };
    }

    function init() {
        resize();
        particles = Array.from({ length: COUNT }, makeParticle);
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Lines
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 110) {
                    ctx.strokeStyle = `rgba(180,210,255,${(1 - dist / 110) * 0.4})`;
                    ctx.lineWidth = 0.6;
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }

        // Dots
        particles.forEach(p => {
            ctx.fillStyle = `rgba(${p.r},${p.g},${p.b},${p.alpha})`;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fill();

            p.x += p.vx;
            p.y += p.vy;
            if (p.x < 0 || p.x > canvas.width)  p.vx *= -1;
            if (p.y < 0 || p.y > canvas.height)  p.vy *= -1;
        });

        animId = requestAnimationFrame(draw);
    }

    init();
    draw();

    const observer = new ResizeObserver(() => { resize(); });
    observer.observe(canvas.parentElement);
}

/* ── Dark Mode Toggle ──────────────────────── */
function initDarkToggle() {
    const btn  = document.getElementById('dark-toggle');
    const icon = document.getElementById('dark-icon');

    function sync() {
        const dark = document.documentElement.classList.contains('dark');
        if (icon) icon.className = dark ? 'fas fa-sun' : 'fas fa-moon';
    }

    sync();

    if (btn) {
        btn.addEventListener('click', () => {
            const dark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', dark);
            sync();
        });
    }
}

/* ── Scroll Reveal ─────────────────────────── */
function initScrollReveal() {
    const targets = document.querySelectorAll(
        '.feature-card, .step-card, .stat-item, .algo-chip'
    );

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity    = '1';
                entry.target.style.transform  = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    targets.forEach((el, i) => {
        el.style.opacity    = '0';
        el.style.transform  = 'translateY(24px)';
        el.style.transition = `opacity 0.5s ease ${i * 0.05}s, transform 0.5s ease ${i * 0.05}s`;
        observer.observe(el);
    });
}
