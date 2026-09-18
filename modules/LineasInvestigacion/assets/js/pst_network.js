// modules/LineasInvestigacion/assets/js/pst_network.js
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('li-network-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    
    let width, height;
    function resize() {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    }
    window.addEventListener('resize', resize);
    resize();

    const particles = [];
    const particleCount = 40; // Reducido para no afectar rendimiento
    const connectionDistance = 150;

    for (let i = 0; i < particleCount; i++) {
        particles.push({
            x: Math.random() * width,
            y: Math.random() * height,
            vx: (Math.random() - 0.5) * 0.5,
            vy: (Math.random() - 0.5) * 0.5,
            radius: Math.random() * 2 + 1
        });
    }

    function animate() {
        requestAnimationFrame(animate);
        ctx.clearRect(0, 0, width, height);

        for (let i = 0; i < particleCount; i++) {
            const p = particles[i];
            p.x += p.vx;
            p.y += p.vy;

            if (p.x < 0 || p.x > width) p.vx *= -1;
            if (p.y < 0 || p.y > height) p.vy *= -1;

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(80, 89, 132, 0.2)'; // li-violet opacity
            ctx.fill();

            for (let j = i + 1; j < particleCount; j++) {
                const p2 = particles[j];
                const dx = p.x - p2.x;
                const dy = p.y - p2.y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < connectionDistance) {
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                    ctx.lineTo(p2.x, p2.y);
                    ctx.strokeStyle = `rgba(112, 144, 203, ${0.15 * (1 - distance / connectionDistance)})`; // cyan fading
                    ctx.lineWidth = 1;
                    ctx.stroke();
                }
            }
        }
    }
    animate();
});
