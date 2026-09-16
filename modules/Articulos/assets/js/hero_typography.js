/**
 * modules/Articulos/assets/js/hero_typography.js
 * Animación Editorial de Tipografía Científica y Revelado de Abstract
 * Usa JavaScript Nativo y API HTML5 Canvas 2D (Sin dependencias ni librerías externas)
 */
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('artCanvasHero');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let width, height;
    let time = 0;
    let mouse = { x: -1000, y: -1000 };
    
    // Términos científicos, fórmulas y metadatos flotantes
    const scientificTerms = [
        "DOI: 10.1038/s41586-024", "ISSN: 2443-8910", "PEER-REVIEWED",
        "ABSTRACT", "METHODOLOGY", "p-value < 0.001", "∫ f(x)dx = F(x)",
        "E = mc²", "CORRELATION: r = 0.94", "HYPOTHESIS TESTED", "DATA SYNTHESIS",
        "ALGORITHM v4.2", "JOURNAL OF ADVANCED RESEARCH", "VOL. 12 | ISS. 4"
    ];

    // Fragmentos de abstract que se revelan como mecanografía académica
    const abstractLines = [
        "ABSTRACT: Monitoreo continuo y análisis empírico de publicaciones académicas de alto impacto...",
        "INDEXACIÓN: Divulgación científica en sistemas de revisión por pares y catálogos digitales...",
        "METODOLOGÍA: Modelado estructurado de datos, evaluación cualitativa y síntesis de resultados..."
    ];

    let floatingTexts = [];
    let currentAbstractIdx = 0;
    let charIdx = 0;
    let lastCharTime = 0;

    function init() {
        const wrapper = canvas.parentElement;
        width = canvas.width = wrapper ? (wrapper.clientWidth || window.innerWidth) : window.innerWidth;
        height = canvas.height = wrapper ? (wrapper.clientHeight || 320) : 320;
        
        floatingTexts = [];
        const count = Math.floor(width / 90);
        for (let i = 0; i < count; i++) {
            floatingTexts.push({
                text: scientificTerms[i % scientificTerms.length],
                x: Math.random() * width,
                y: Math.random() * height,
                speedX: (Math.random() - 0.5) * 0.4,
                speedY: -0.2 - Math.random() * 0.3,
                fontSize: Math.floor(Math.random() * 4) + 11,
                alpha: Math.random() * 0.25 + 0.1,
                fontFamily: (i % 2 === 0) ? 'monospace' : 'serif'
            });
        }
    }

    function animate(now) {
        ctx.clearRect(0, 0, width, height);
        time += 0.015;

        // 1. DIBUJAR LÍNEAS DE CUÁDRICULA EDITORIAL (Papel Científico / Grid)
        ctx.strokeStyle = "rgba(11, 26, 48, 0.05)";
        ctx.lineWidth = 1;
        const gridStep = 40;
        for (let x = 0; x < width; x += gridStep) {
            ctx.beginPath();
            ctx.moveTo(x, 0);
            ctx.lineTo(x, height);
            ctx.stroke();
        }
        for (let y = 0; y < height; y += gridStep) {
            ctx.beginPath();
            ctx.moveTo(0, y);
            ctx.lineTo(width, y);
            ctx.stroke();
        }

        // 2. TEXTOS Y FÓRMULAS CIENTÍFICAS FLOTANTES
        floatingTexts.forEach(t => {
            t.x += t.speedX;
            t.y += t.speedY;

            if (t.y < -20) {
                t.y = height + 20;
                t.x = Math.random() * width;
            }
            if (t.x < -50) t.x = width + 50;
            if (t.x > width + 50) t.x = -50;

            const dx = t.x - mouse.x;
            const dy = t.y - mouse.y;
            const dist = Math.sqrt(dx * dx + dy * dy);
            let highlight = 0;
            if (dist < 120) {
                highlight = (120 - dist) / 120 * 0.45;
            }

            ctx.font = `${t.fontSize}px ${t.fontFamily}`;
            ctx.fillStyle = `rgba(11, 26, 48, ${t.alpha + highlight})`;
            ctx.fillText(t.text, t.x, t.y);
        });

        // 3. REVELADO DE ABSTRACT EN MARGEN INFERIOR (ESTILO PAPER CIENTÍFICO)
        if (!lastCharTime) lastCharTime = now;
        if (now - lastCharTime > 60) {
            charIdx++;
            lastCharTime = now;
            const currentLine = abstractLines[currentAbstractIdx];
            if (charIdx > currentLine.length + 30) {
                charIdx = 0;
                currentAbstractIdx = (currentAbstractIdx + 1) % abstractLines.length;
            }
        }

        const fullLine = abstractLines[currentAbstractIdx];
        const visibleText = fullLine.substring(0, charIdx);
        const cursor = (Math.floor(now / 500) % 2 === 0) ? "▋" : "";

        ctx.font = "12px 'Courier New', monospace";
        ctx.fillStyle = "rgba(11, 26, 48, 0.65)";
        ctx.textAlign = "center";
        ctx.fillText(visibleText + cursor, width / 2, height - 16);
        ctx.textAlign = "left";

        requestAnimationFrame(animate);
    }

    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(init, 150);
    });
    if (canvas.parentElement) {
        canvas.parentElement.addEventListener('mousemove', (e) => {
            const rect = canvas.getBoundingClientRect();
            mouse.x = e.clientX - rect.left;
            mouse.y = e.clientY - rect.top;
        });
        canvas.parentElement.addEventListener('mouseleave', () => {
            mouse.x = -1000;
            mouse.y = -1000;
        });
    }

    init();
    requestAnimationFrame(animate);
});
